<?php
include '../properties.php';


//connexion a la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());

$sql = "SELECT row_to_json(fc)
FROM ( SELECT 'FeatureCollection' As type, array_to_json(array_agg(f)) As features
FROM (SELECT 'Feature' As type
   , ST_AsGeoJSON( st_transform(coalesce(lg.geom_pp,lg.geom),4326) )::json As geometry
   , row_to_json(lp) As properties
  FROM $parcelles As lg 
        INNER JOIN (SELECT id_unique, id_group as id_site FROM $parcelles  WHERE geom is not null ) As lp 
      ON lg.id_unique = lp.id_unique  ) As f )  As fc";
//where is_active = true

//execute la requete dans le moteur de base de donnees  
$query_result = pg_exec($dbconn,$sql) or die (pgErrorMessage());
while($row = pg_fetch_row($query_result))
{
  //echo(trim($row[0])."\t"."\n"); // trim pour retirer les espaces en début et fin de chaine
  echo trim($row[0]);
}
//ferme la connexion a la BD
pg_close($dbconn);

?>