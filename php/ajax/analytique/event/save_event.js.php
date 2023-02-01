<?php
session_start();
include '../../../properties.php';

$user = $_SESSION['u_nom_user_progecen'];//$_SESSION['u_id_progecen']; ex: Benoit Perceval

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 

"
insert into $progecen_temps (
e_id, 
e_id_projet, 
e_nom_projet, 
e_id_action, 
e_nom_action,
e_objet, 
e_start, 
e_end, 
e_lieu, 
e_commentaire, 
e_personne,
e_salissure, 
e_panier
)
select 
$1,
$2,
$3,
$4,
$5,
$6,
$7,
$8,
$9,
$10,
$11,
$12,
$13
"
);

$result = pg_execute($dbconn, "sql", array(
$_POST['e_uuid'],
$_POST['e_id_projet'],
$_POST['e_nom_projet'],
$_POST['e_id_action'],
$_POST['e_nom_action'],
//$_POST['e_id_site'],
$_POST['e_objet'],
$_POST['e_start'],
$_POST['e_end'],
$_POST['e_lieu'],
$_POST['e_commentaire'],
$user,
$_POST['e_salissure'],
$_POST['e_panier']
));
if ( $result ) {
    echo  "Saved!";
}
else {
    echo  "Failed!". pg_last_error();  
}
pg_close($dbconn);
?>
