<?php
include '../../properties.php';

$term = $_POST["term"];

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 
"
SELECT array_to_json(array_agg(row_to_json(t))) FROM 
(
  SELECT id_action as id , definition as name, niveau FROM $progecen_liste_actions WHERE id_action ~* $1 or definition ~* $1 order by 1
) t
"
);
$result = pg_execute($dbconn, "sql", array($term));
while($row = pg_fetch_row($result))
{
  echo trim($row[0]);
}
pg_close($dbconn);
?>

