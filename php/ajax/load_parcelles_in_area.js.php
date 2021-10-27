<?php
session_start();
include '../properties.php';


//connexion a la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());

$dep=substr($_POST["id"], 0, 2);

$sql = "
SELECT row_to_json(fc)
FROM ( SELECT 'FeatureCollection' As type, array_to_json(array_agg(f)) As features
FROM (SELECT 'Feature' As type
   , ST_AsGeoJSON( st_transform(lg.geom,4326) )::json As geometry
   , row_to_json(lp) As properties
  FROM pci_vecteur_complet As lg 
        INNER JOIN (SELECT 
            id, commune, prefixe, section, numero, contenance, arpente, created, 
       updated, geom
       FROM pci_vecteur_complet 
        WHERE id like '".$_POST["id"]."%' and st_isvalid(geom) ) As lp 
      ON lg.id = lp.id ) As f )  As fc;
";

$query_result = pg_exec($dbconn,$sql) or die (pg_last_error());
while($row = pg_fetch_row($query_result))
{
  echo trim($row[0]);
}
//ferme la connexion a la BD
pg_close($dbconn);

?>
