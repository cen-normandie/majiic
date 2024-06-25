<?php
include '../../../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());

$id_projet_int  = $_POST["projet"];
//$id_projet_string  = strval($_POST["projet"]);


//suppression du temps
$result = pg_prepare($dbconn, "sql_temps", 
    "
    delete FROM $progecen_temps
	WHERE e_id_projet = $1
    "
);
$result = pg_execute($dbconn, "sql_temps", array(
    $id_projet_int
    )) or die (pg_last_error());

//suppression des actions
$result = pg_prepare($dbconn, "sql_actions", 
    "
    delete FROM $progecen_actions
	WHERE id_projet = $1
    "
);

$result = pg_execute($dbconn, "sql_actions", array(
    $id_projet_int
    )) or die (pg_last_error());

//suppression du projet
$result = pg_prepare($dbconn, "sql_projet", 
    "
    delete FROM $progecen_projets
	WHERE id_projet = $1
    "
);

$result = pg_execute($dbconn, "sql_projet", array(
    $id_projet_int
    )) or die ( pg_last_error());
pg_close($dbconn);

echo json_encode("output");
?>
