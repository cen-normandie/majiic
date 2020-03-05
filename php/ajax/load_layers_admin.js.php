<?php
include '../properties.php';

//connexion a la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());


$sql = pg_prepare($dbconn, "admin", "
SELECT row_to_json(fc)
FROM ( SELECT 'FeatureCollection' As type, array_to_json(array_agg(f)) As features
FROM (SELECT 'Feature' As type
   , ST_AsGeoJSON( st_transform(lg.l_geom,4326) )::json As geometry
   , row_to_json(lp) As properties
  FROM $l_admin As lg 
        INNER JOIN (SELECT l_id, l_nom, l_table_name FROM $l_admin WHERE l_nom ~* $1 or l_id ~* $2 ) As lp 
      ON lg.l_id = lp.l_id  ) As f )  As fc
");

$qout = pg_execute($dbconn,"admin",array($_POST["term"], $_POST["term"])) or die (pg_last_error());

//execute la requete dans le moteur de base de donnees  
while($row = pg_fetch_row($qout))
{
  echo trim($row[0]);
}
//ferme la connexion a la BD
pg_close($dbconn);

?>