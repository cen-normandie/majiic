<?php
include '../../properties.php';
$year_ = date("Y");

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 



//array_to_json(array_agg(f)) As features
"
UPDATE $progecen_temps set e_blocked = true where e_id = $1 ;
"
);



$result = pg_execute($dbconn, "sql", array($_POST['id_to_bloc']));
while($row = pg_fetch_row($result))
{
  echo trim($row[0]);
}
pg_close($dbconn);
?>
