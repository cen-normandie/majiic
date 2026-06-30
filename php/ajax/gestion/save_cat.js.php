<?php
    include '../../properties.php';

// Connexion à la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
    or die('Connexion impossible : ' . pg_last_error());

    $id_site    = $_POST["id_site"];
    $categorie  = $_POST["categorie"];


// Requête de vérification
$sql_check = "UPDATE $sites SET categorie_site = $1 WHERE id_site = $2";
$res = pg_prepare($dbconn, "cat", $sql_check);
$res = pg_execute($dbconn, "cat", array($categorie, $id_site));
$sql_check_data = "UPDATE $sites_data SET categorie_site = $1 WHERE id_site = $2";
$res = pg_prepare($dbconn, "cat_data", $sql_check_data);
$res = pg_execute($dbconn, "cat_data", array($categorie, $id_site));
$sql_check_parcelle = "UPDATE $parcelles SET categorie_site = $1 WHERE id_group = $2";
$res = pg_prepare($dbconn, "cat_parcelle", $sql_check_parcelle);
$res = pg_execute($dbconn, "cat_parcelle", array($categorie, $id_site));

echo $res ? "update réussie" : pg_last_error();

// Ferme la connexion à la BD
pg_close($dbconn);
?>
