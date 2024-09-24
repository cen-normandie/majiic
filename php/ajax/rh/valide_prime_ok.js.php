<?php
include '../../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 
"
UPDATE $progecen_temps SET e_date_valide_salissure = now()::date, e_commentaire = '_rh_prime_ok_'||now()::date::text||coalesce(e_commentaire, '')
WHERE e_id::text = $1;
"
);
$result = pg_execute($dbconn, "sql", array($_POST["id_prime"])) or die ('Connexion impossible :'. pg_last_error());
pg_close($dbconn);
echo 'validation prime salissure : '.$_POST["id_prime"];
?>