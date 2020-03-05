<?php
session_start();
include '../properties.php';


//connexion a la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());

$sql = "
WITH s_area AS ( SELECT st_transform(l_geom,4326) as l_geom FROM $layers_admin WHERE l_id ='".$_POST["id"]."' AND l_table_name = '".$_POST["table_name"]."' )
SELECT row_to_json(fc)
FROM ( SELECT 'FeatureCollection' As type, array_to_json(array_agg(f)) As features
FROM (SELECT 'Feature' As type
   , ST_AsGeoJSON( st_transform(lg.loc_geom,4326) )::json As geometry
   , row_to_json(lp) As properties
  FROM mares.localisations As lg 
        INNER JOIN (SELECT 
        FROM $localisations , s_area WHERE st_intersects(s_area.l_geom, loc_geom) ) As lp 
      ON lg.loc_id_plus = lp.loc_id_plus ) As f )  As fc";
//echo $sql;


$query_result = pg_exec($dbconn,$sql) or die (pg_last_error());
while($row = pg_fetch_row($query_result))
{
  echo trim($row[0]);
}
//ferme la connexion a la BD
pg_close($dbconn);

?>