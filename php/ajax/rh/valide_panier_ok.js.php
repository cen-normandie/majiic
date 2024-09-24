<?php
include '../../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 
"
UPDATE $progecen_temps SET e_date_valide_panier = now()::date, e_commentaire = '_rh_panier_ok_'||now()::date::text||coalesce(e_commentaire, '')
WHERE e_id::text = $1;
"
);
$result = pg_execute($dbconn, "sql", array($_POST["id_panier"])) or die ('Connexion impossible :'. pg_last_error());
pg_close($dbconn);
echo 'validation panier : '.$_POST["id_panier"];
?>