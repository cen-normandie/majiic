<?php
include '../properties.php';

//connexion a la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$sql = "
SELECT row_to_json(fc)
FROM ( SELECT 'FeatureCollection' As type, array_to_json(array_agg(f)) As features
FROM (SELECT 'Feature' As type
   , ST_AsGeoJSON( st_transform(lg.l_geom,4326) )::json As geometry
   , row_to_json(lp) As properties
  FROM $l_admin As lg 
        INNER JOIN (SELECT l_id, l_nom, l_table_name FROM $l_admin WHERE l_nom ~* '".$_POST["term"]."' or l_id ~* '".$_POST["term"]."' ) As lp 
      ON lg.l_id = lp.l_id  ) As f )  As fc
";
//execute la requete dans le moteur de base de donnees  
$query_result = pg_query($dbconn,$sql) or die ( pg_last_error());
while($row = pg_fetch_row($query_result))
{
  echo trim($row[0]);
}
//ferme la connexion a la BD
pg_close($dbconn);

?>