<?php
session_start();
include '../../../properties.php';

$user = $_SESSION['u_nom_user_progecen'];//$_SESSION['u_id_progecen']; ex: Benoit Perceval

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 

"
update $progecen_temps set
e_id_projet = $2,
e_nom_projet= $3,
e_id_action = $4,
e_objet = $5,
e_start = $6,
e_end =$7,
e_lieu = $8, 
e_commentaire = $9, 
e_personne = $10,
e_salissure = $11,
e_panier = $12
where e_id = $1
"
);

$result = pg_execute($dbconn, "sql", array(
$_POST['e_uuid'],
$_POST['e_id_projet'],
$_POST['e_nom_projet'],
$_POST['e_id_action'],
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
