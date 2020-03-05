<?php
session_start();
include '../properties.php';


//connexion a la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());

$sql = pg_prepare($dbconn, "logs_", "
insert into $log (l_u_courriel) VALUES ($1);
");

$qout = pg_execute($dbconn,"logs_",array($_POST["email"])) or die (pg_last_error());

//ferme la connexion a la BD
pg_close($dbconn);

?>
