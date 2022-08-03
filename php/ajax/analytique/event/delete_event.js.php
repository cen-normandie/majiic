<?php
session_start();
include '../../../properties.php';

$user = $_SESSION['u_nom_user_progecen'];//$_SESSION['u_id_progecen']; ex: Benoit Perceval

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 

"
delete from $progecen_temps where e_id = $1
"
);

$result = pg_execute($dbconn, "sql", array(
$_POST['e_uuid']
));
if ( $result ) {
    echo  "Deleted!";
}
else {
    echo  "Failed!". pg_last_error();  
}
pg_close($dbconn);
?>
