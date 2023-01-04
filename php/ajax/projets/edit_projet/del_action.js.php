<?php
include '../../../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());

$arr_param = json_decode($_POST["action"]);

$id_action  = strval($arr_param->{'id_action'});
$id_projet  = strval($arr_param->{'id_projet'});

$result = pg_prepare($dbconn, "sql", 
    "
    DELETE FROM $progecen_actions WHERE id_action::text = $1 ;
    "
);
$result = pg_execute($dbconn, "sql", array($id_action)) or die ( pg_last_error());

$result_ = pg_prepare($dbconn, "sql2", 
    "
    DELETE FROM $progecen_temps WHERE e_id_projet = $1 AND e_id_action = $2;
    "
);

$result_ = pg_execute($dbconn, "sql2", array($id_projet,$id_action)) or die ( pg_last_error());

pg_close($dbconn);

echo json_encode("Action Supprimée");
?>