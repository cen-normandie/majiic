<?php
include '../../properties.php';

/* $term = $_POST["term"]; */
/* 
SELECT array_to_json(array_agg(row_to_json(t))) FROM 
(
  SELECT u_id as id , u_nom_personne as name, u_courriel, u_responsable FROM $progecen_personnes_ order by 2
) t
*/
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 
"
SELECT array_to_json(array_agg(row_to_json(t))) FROM 
(
  SELECT id as id , personne as name FROM $progecen_personnes_ order by 2
) t
"
);
$result = pg_execute($dbconn, "sql", array()) or die ('Connexion impossible :'. pg_last_error());
while($row = pg_fetch_row($result))
{
  echo trim($row[0]);
}
pg_close($dbconn);
?>

