<?php
include '../properties.php';
$arr = array();

//connexion a la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());

$result = pg_prepare($dbconn, "sql1", "SELECT id_site, nom_site FROM $sites_data WHERE nom_site ~* $1 or id_site ~* $1 order by 1");
$result = pg_execute($dbconn, "sql1", array($_POST["term"]));
while($row = pg_fetch_row($result))
{
  $arr[]=trim($row[0])." - ".trim($row[1]);
}
//ferme la connexion a la BD
pg_close($dbconn);
echo json_encode($arr);
//echo $arr;
?>