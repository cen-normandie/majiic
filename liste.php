<?php
include 'php/properties.php';
// Configuration LDAP
$ldap_server = "ldap://192.168.0.211"; // Remplacez par l'adresse de votre serveur AD
$ldap_port = 389; // Port LDAP (389 pour non sécurisé, 636 pour LDAPS)
$ldap_user = $AD_admin; // Utilisateur avec droits de lecture sur l'AD
$ldap_password = $AD_admin_pwd; // Mot de passe de l'utilisateur
$base_dn = "DC=CSNHN,DC=LOCAL"; // Base DN de votre Active Directory (ex: DC=monentreprise,DC=local)
$group_name = "progecen_user"; // Nom du groupe à interroger



// Connexion au serveur LDAP
$ldap_conn = ldap_connect($ldap_server, $ldap_port);
if (!$ldap_conn) {
    die("Impossible de se connecter au serveur LDAP.");
}

// Configuration des options LDAP
ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($ldap_conn, LDAP_OPT_REFERRALS, 0);

// Authentification
$ldap_bind = ldap_bind($ldap_conn, $ldap_user, $ldap_password);
if (!$ldap_bind) {
    die("Échec de l'authentification LDAP.");
}

// Recherche du groupe
$group_search = ldap_search($ldap_conn, $base_dn, "(cn=$group_name)");
$group_info = ldap_get_entries($ldap_conn, $group_search);
if ($group_info['count'] == 0) {
    die("Le groupe '$group_name' n'a pas été trouvé.");
}

// Récupération des membres du groupe
$group_dn = $group_info[0]['dn'];
$members = $group_info[0]['member'];
$users = array();

// Pour chaque membre du groupe, récupérer les informations
foreach ($members as $member) {
    if (!empty($member) && $member != "count") {
        $user_search = ldap_search($ldap_conn, $base_dn, "(distinguishedName=$member)");
        $user_info = ldap_get_entries($ldap_conn, $user_search);

        // Vérifie si l'utilisateur est actif
        $user_enabled = true; // Par défaut, on suppose que le compte est actif
        if (isset($user_info[0]['useraccountcontrol'][0])) {
            $user_enabled = !($user_info[0]['useraccountcontrol'][0] & 2);
        }

        if ($user_enabled) {
            // Récupérer les informations de l'utilisateur
            $displayName = isset($user_info[0]['displayname'][0]) ? $user_info[0]['displayname'][0] : "N/A";
            $mail = isset($user_info[0]['mail'][0]) ? $user_info[0]['mail'][0] : "N/A";
            $sn = isset($user_info[0]['sn'][0]) ? $user_info[0]['sn'][0] : "N/A"; // Nom de famille
            $givenName = isset($user_info[0]['givenname'][0]) ? $user_info[0]['givenname'][0] : "N/A"; // Prénom

            $users[] = array(
                'displayName' => $displayName,
                'mail' => $mail,
                'sn' => $sn,
                'givenName' => $givenName,
                'fullName' => trim("$sn $givenName") // Concatenation nom + prénom
            );
        }
    }
}

// Fermeture de la connexion LDAP
ldap_close($ldap_conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des utilisateurs actifs du groupe "<?php echo $group_name; ?>"</title>
    <!-- Bootstrap 5 -->
    <link href="./bootstrap-5.0.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="./js/plugins/datatable/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Buttons CSS -->
    <link href="./js/plugins/datatable/Buttons-1.7.0/css/buttons.bootstrap5.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Utilisateurs actifs du groupe "<?php echo $group_name; ?>"</h1>
        <table id="usersTable" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>Nom d'affichage</th>
                    <th>Nom + Prénom</th>
                    <th>Adresse e-mail</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['displayName']); ?></td>
                    <td><?php echo htmlspecialchars($user['fullName']); ?></td>
                    <td><?php echo htmlspecialchars($user['mail']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- jQuery -->
    <script src="./js/jquery.js"></script>
    <!-- Bootstrap 5 JS -->
    <script src="./bootstrap-5.0.0/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="./js/plugins/datatable/dataTables.min.js"></script>
    <script src="./js/plugins/datatable/dataTables.bootstrap5.min.js"></script>
    <!-- DataTables Buttons JS -->
    <script src="./js/plugins/datatable/Buttons-1.7.0/js/dataTables.buttons.min.js"></script>
    <script src="./js/plugins/datatable/Buttons-1.7.0/js/buttons.html5.min.js"></script>
    <script src="./js/plugins/datatable/Buttons-1.7.0/js/buttons.print.min.js"></script>
    <script src="./js/plugins/datatable/jszip3.10.1/jszip.min.js"></script>
    <script src="./js/plugins/datatable/pdfmake0.2.7/pdfmake.min.js"></script>
    <script src="./js/plugins/datatable/pdfmake0.2.7/vfs_fonts.js"></script>

    <script>
    $(document).ready(function() {
        $('#usersTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Utilisateurs_actifs_<?php echo $group_name; ?>',
                    text: 'Exporter en Excel',
                    className: 'btn btn-success'
                }
            ],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json'
            }
        });
    });
    </script>
</body>
</html>
