<?php
session_start();
include '../../../properties.php';

$user = $_SESSION['u_nom_user_progecen'];//$_SESSION['u_id_progecen']; ex: Benoit Perceval

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 

"
update $progecen_temps set
e_start = $2,
e_end =$3
where e_id = $1
"
);

$result = pg_execute($dbconn, "sql", array(
$_POST['e_uuid'],
$_POST['e_start'],
$_POST['e_end']
));
if ( $result ) {
    echo  "Saved!";
}
else {
    echo  "Failed!". pg_last_error();  
}
pg_close($dbconn);
?>
