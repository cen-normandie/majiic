<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../properties.php';

// Connexion à la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
    or die('Connexion impossible : ' . pg_last_error());

$type = '';
$count_parcelle = $_POST["count_parcelle"];
$p_insee = $_POST["p_insee"];
$p_prefixe = $_POST["p_prefixe"];
$p_section = $_POST["p_section"];
$p_num = $_POST["p_num"];
$pp_ = $_POST["pp_"];
$p_conv = $_POST["p_conv"];
$p_acqu = $_POST["p_acqu"];
$p_site = $_POST["p_site"];
$p_ddg = $_POST["p_ddg"];
$p_ore = $_POST["p_ore"];

// Requête de vérification
$sql_check = "SELECT id_unique FROM $parcelles WHERE SPLIT_PART(id_unique,'|',2) = $1";
$res = pg_prepare($dbconn, "check_parcelle", $sql_check);
$res = pg_execute($dbconn, "check_parcelle", array($p_insee . $p_prefixe . $p_section . $p_num));

// Affichage de la requête de vérification
$sql_check_display = "SELECT id_unique FROM $parcelles WHERE SPLIT_PART(id_unique,'|',2) = '" . $p_insee . $p_prefixe . $p_section . $p_num . "'";
echo "Check SQL: " . $sql_check_display . "\n";

// Si la parcelle existe --> Mise à jour des données
if (pg_num_rows($res) > 0) {
    $type = 'update';
    $prepared_update = pg_prepare($dbconn, "update_parcelle", "UPDATE $parcelles
        SET
        id_convention = $1,
        id_acquisition = $2,
        id_doc_gestion = $3,
        id_ore = $4,
        id_unique = $5
        WHERE SPLIT_PART(id_unique,'|',2) = $6");

    // Construction de id_unique avec COALESCE et NULLIF directement dans la requête SQL
    $id_unique = $p_site . '|' . $p_insee . $p_prefixe . str_pad($p_section, 2, '0', STR_PAD_LEFT) . str_pad($p_num, 4, '0', STR_PAD_LEFT) . '|';

    $result_update = pg_execute($dbconn, "update_parcelle", array(
        $p_conv,
        $p_acqu,
        $p_ddg,
        $p_ore,
        $id_unique . "COALESCE(NULLIF($p_acqu, 'ø'), NULLIF($p_conv, 'ø'), NULLIF($p_ore, 'ø'), '')",
        $p_insee . $p_prefixe . str_pad($p_section, 2, '0', STR_PAD_LEFT) . str_pad($p_num, 4, '0', STR_PAD_LEFT)
    ));
    echo $result_update ? "Mise à jour réussie" : pg_last_error();
} else {
    $type = 'insert';
    $prepared_insert = pg_prepare($dbconn, "insert_parcelle", "INSERT INTO $parcelles (
        id_unique,
        id_group,
        id_convention,
        id_acquisition,
        pp,
        type_group,
        id_doc_gestion,
        id_ore,
        doc_reference
        )
        VALUES ($1, $2, $3, $4, $5, $6, $7, $8, COALESCE(NULLIF($9, 'ø'), NULLIF($10, 'ø'), NULLIF($11, 'ø'), ''))");

    $id_unique = $p_site . '|' . $p_insee . $p_prefixe . str_pad($p_section, 2, '0', STR_PAD_LEFT) . str_pad($p_num, 4, '0', STR_PAD_LEFT) . '|';

    $result_insert = pg_execute($dbconn, "insert_parcelle", array(
        $id_unique . "COALESCE(NULLIF($p_acqu, 'ø'), NULLIF($p_conv, 'ø'), NULLIF($p_ore, 'ø'), '')",
        $p_site,
        $p_conv,
        $p_acqu,
        $pp_,
        'site',
        $p_ddg,
        $p_ore,
        $p_acqu,
        $p_conv,
        $p_ore
    ));
    echo $result_insert ? "Insertion réussie" : pg_last_error();
}

// Ferme la connexion à la BD
pg_close($dbconn);
?>
