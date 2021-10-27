<?php
session_start();
include '../properties.php';


//connexion a la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$sql = "
insert into log.logs(l_u_courriel) VALUES ('".$_POST["email"]."');
";

$query_result = pg_exec($dbconn,$sql) or die (pg_last_error());

//ferme la connexion a la BD
pg_close($dbconn);

?>
