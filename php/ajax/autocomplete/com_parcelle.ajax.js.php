<?php
include '../../properties.php';


$term = urldecode($_POST["term"]);



$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 
"
SELECT array_to_json(array_agg(row_to_json(t))) FROM 
(
select l.l_id as id, l.l_nom as name, l_table_name as tablename, 
    st_asgeojson( st_transform(l.l_geom,4326) )::json As geometry
from $l_admin l
where l.l_id ~* $1 or l_nom ~* $1
order by 1
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

