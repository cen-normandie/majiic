<?php
include '../properties.php';
$arr = array();

//connexion a la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$sql = "SELECT a.id_site, a.nom_site, st_astext(st_transform(a.geom_point_in_polygon,4326)) as centroid FROM $sites a
    WHERE a.nom_site ~* '".$_POST["term"]."' or a.id_site ~* '".$_POST["term"]."' order by 1";
//execute la requete dans le moteur de base de donnees  
$query_result = pg_query($dbconn,$sql) or die ( pg_last_error());
while($row = pg_fetch_row($query_result))
{
  $arr[]=trim($row[0])." - ".trim($row[1])." - ".trim($row[2]);
}
//ferme la connexion a la BD
pg_close($dbconn);
echo json_encode($arr);
//echo $arr;
?>