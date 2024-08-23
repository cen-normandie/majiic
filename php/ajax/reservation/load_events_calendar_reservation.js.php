<?php
session_start();
include '../../properties.php';
$arr = array();
$user = $_SESSION['u_nom_user_progecen'];//$_SESSION['u_id_progecen'];

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 

//array_to_json(array_agg(f)) As features
"
SELECT er_id, er_id_matos, er_nom_matos, er_start, er_end, er_personne, er_color
	FROM $reservation_e
"
//LEFT JOIN $progecen_actions a on e.e_id_action = a.id::text
);
$result = pg_execute($dbconn, "sql", array());
while($row = pg_fetch_array($result))
{
  $arr[]=array(
    'id'                =>$row["er_id"], 
    'title'             =>$row["er_nom_matos"], 
    'start'             =>$row["er_start"], 
    'end'               =>$row["er_end"], 
    'backgroundColor'   =>$row["er_color"], 
    'borderColor'       =>$row["er_color"], 
    'personne'          =>$row["er_personne"]
    );
}
//ferme la connexion a la BD
pg_close($dbconn);
echo json_encode($arr);
?>
