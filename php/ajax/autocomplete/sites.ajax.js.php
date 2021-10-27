<?php
include '../../properties.php';

$term = $_POST["term"];

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 
"
SELECT array_to_json(array_agg(row_to_json(t))) FROM 
(
  SELECT id_site as id, nom_site as name, st_transform(geom,4326) as geom, 'site' as tablename FROM $sites WHERE nom_site ~* $1 or id_site ~* $1 order by 1
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

