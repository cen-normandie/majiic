<?php
include '../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 
"
WITH t as (
  SELECT 1
  FROM $progecen_projets
  where 1 = $1
)
SELECT json_agg(t) FROM t
"
);

$result = pg_execute($dbconn, "sql", array(1));
while($row = pg_fetch_row($result))
{
  echo trim($row[0]);
}
pg_close($dbconn);
?>
