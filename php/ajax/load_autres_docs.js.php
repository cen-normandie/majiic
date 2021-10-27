<?php
include '../properties.php';
$arr = array();
$id_site   = $_POST["id_site"];

//connexion a la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());

$sql = "
select array_to_json(regexp_split_to_array(autres_docs, '\|')) from $sites_data where id_site ='".$id_site."' ;
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