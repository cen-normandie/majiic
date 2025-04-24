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
SELECT 
    e.e_id, 
    e.e_objet, 
    e.e_start, 
    e.e_end, 
    e.e_id_projet,
    e.e_id_action,
    e.e_nom_action,
    e.e_nom_projet,
    e.e_lieu,
    e.e_commentaire,
    e.e_salissure,
    e.e_panier,
    e.e_date_saisie,
    e.e_date_saisie_salissure,
    p.color,
    e.e_personne,
    e.e_blocked
FROM $progecen_temps e 
LEFT JOIN $progecen_projets p on e.e_id_projet = p.id_projet::text 
WHERE e.e_personne = $1
AND e.e_start > (now()::date - (INTERVAL '1 year' + INTERVAL '7 month'))
"
//LEFT JOIN $progecen_actions a on e.e_id_action = a.id::text
);

$result = pg_execute($dbconn, "sql", array($user));
while($row = pg_fetch_array($result))
{
  $arr[]=array(
    'id'                =>$row["e_id"], 
    'title'             =>$row["e_objet"], 
    'start'             =>$row["e_start"], 
    'end'               =>$row["e_end"], 
    'backgroundColor'   =>($row["e_blocked"] == 't') ? '#a0a0a0' : $row["color"] ,  
    'borderColor'       =>($row["e_blocked"] == 't') ? '#bf1c1c' : $row["color"] , 
    'textColor'         =>($row["e_blocked"] == 't') ? '#bf1c1c' : '#fff', 
    'e_id_projet'       =>$row["e_id_projet"],
    'e_nom_projet'      =>$row["e_nom_projet"],
    'e_objet'           =>$row["e_objet"], 
    'e_id_action'       =>$row["e_id_action"],
    'e_nom_action'      =>$row["e_nom_action"],
    'e_lieu'            =>$row["e_lieu"],
    'e_commentaire'     =>$row["e_commentaire"],
    'e_salissure'       =>$row["e_salissure"],
    'e_panier'          =>$row["e_panier"],
    'e_objet'           =>$row["e_objet"], 
    'e_start'           =>$row["e_start"], 
    'e_end'             =>$row["e_end"], 
    'e_uuid'            =>$row["e_id"],
    'e_personne'        =>$row["e_personne"],
    'e_blocked'         =>$row["e_blocked"],
    'editable'          =>($row["e_blocked"] == 't') ? false : true
    );
}
//ferme la connexion a la BD
pg_close($dbconn);
echo json_encode($arr);
?>
