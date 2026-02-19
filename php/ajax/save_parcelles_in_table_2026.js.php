<?php
    include '../properties.php';

// Connexion à la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
    or die('Connexion impossible : ' . pg_last_error());

    $type = '';
    $count_parcelle             = $_POST["count_parcelle"];
    $p_insee                    = $_POST["p_insee"];
    $p_prefixe                  = $_POST["p_prefixe"];
    $p_section                  = $_POST["p_section"];
    $p_num                      = $_POST["p_num"];
    $pp_                        = $_POST["pp_"];
    $p_conv                     = $_POST["p_conv"];
    $p_acqu                     = $_POST["p_acqu"];
    $p_site                     = $_POST["p_site"];
    $p_ddg                      = $_POST["p_ddg"];
    $p_ore                      = $_POST["p_ore"];

$sql_check = "SELECT id_unique FROM $parcelles WHERE SPLIT_PART(id_unique,'|',2) = $1";
$res = pg_prepare($dbconn, "check_parcelle", $sql_check);
$res = pg_execute($dbconn, "check_parcelle", array($p_insee.$p_prefixe.$p_section.$p_num));

// Si la parcelle existe --> Mise à jour des données
if (pg_num_rows($res) > 0) {
    $type = 'update';
    $sql_update = "UPDATE $parcelles
        SET
        id_convention = $1,
        id_acquisition = $2,
        id_doc_gestion = $3,
        id_ore = $4,
        id_unique = $5
        WHERE SPLIT_PART(id_unique,'|',2) = $6";

    $prepared_update = pg_prepare($dbconn, "update_parcelle", $sql_update);

    $id_unique = $p_site . '|' . $p_insee . $p_prefixe . str_pad($p_section, 2, '0', STR_PAD_LEFT) . str_pad($p_num, 4, '0', STR_PAD_LEFT) . '|' . coalesce(nullif($p_acqu, 'ø'), nullif($p_conv, 'ø'), nullif($p_ore, 'ø'), '');

    $result_update = pg_execute($dbconn, "update_parcelle", array(
        $p_conv,
        $p_acqu,
        $p_ddg,
        $p_ore,
        $id_unique,
        $p_insee.$p_prefixe.str_pad($p_section, 2, '0', STR_PAD_LEFT).str_pad($p_num, 4, '0', STR_PAD_LEFT)
    ));

    echo $result_update ? "Mise à jour réussie" : pg_last_error();
} else {
    $type = 'insert';
    $sql_insert = "INSERT INTO $parcelles (
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
        VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)";

    $prepared_insert = pg_prepare($dbconn, "insert_parcelle", $sql_insert);

    $id_unique = $p_site . '|' . $p_insee . $p_prefixe . str_pad($p_section, 2, '0', STR_PAD_LEFT) . str_pad($p_num, 4, '0', STR_PAD_LEFT) . '|' . coalesce(nullif($p_acqu, 'ø'), nullif($p_conv, 'ø'), nullif($p_ore, 'ø'), '');

    $result_insert = pg_execute($dbconn, "insert_parcelle", array(
        $id_unique,
        $p_site,
        $p_conv,
        $p_acqu,
        $pp_,
        'site',
        $p_ddg,
        $p_ore,
        coalesce(nullif($p_acqu, 'ø'), nullif($p_conv, 'ø'), nullif($p_ore, 'ø'), '')
    ));

    echo $result_insert ? "Insertion réussie" : pg_last_error();
}

// Ferme la connexion à la BD
pg_close($dbconn);
?>
