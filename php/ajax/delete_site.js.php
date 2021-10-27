<?php
    include '../properties.php';

//connexion a la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());

$site = $_POST["id_site"];


$result = pg_prepare($dbconn, "sql1", "DELETE FROM $sites where id_site = $1 ");
$result = pg_execute($dbconn, "sql1", array($_POST["id_site"]));
$result = pg_prepare($dbconn, "sql2", "DELETE FROM $parcelles where id_group = $1 ");
$result = pg_execute($dbconn, "sql2", array($_POST["id_site"]));
$result = pg_prepare($dbconn, "sql3", "DELETE FROM $sites_data where id_site = $1 ");
$result = pg_execute($dbconn, "sql3", array($_POST["id_site"]));



//$sql = "DELETE FROM $sites_mfu where id_site = '$site' ";
//$query_result = pg_query($dbconn,$sql) or die ( pg_last_error());
//$sql = "DELETE FROM $parcelles_mfu where id_group = '$site' ";
//$query_result = pg_query($dbconn,$sql) or die ( pg_last_error());
//$sql = "DELETE FROM $sites_data_mfu where id_site = '$site' ";
//$query_result = pg_query($dbconn,$sql) or die ( pg_last_error());


pg_close($dbconn);
echo 'supprimé';





?>





