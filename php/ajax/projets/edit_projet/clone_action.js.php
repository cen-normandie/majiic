<?php
include '../../../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());

$arr_param = json_decode($_POST["action"]);

$id_action  = strval($arr_param->{'id_action'});

$result = pg_prepare($dbconn, "sql", 
    "
    INSERT INTO $progecen_actions (code_action, financements, site, id_projet) 
    SELECT code_action, financements, site, id_projet FROM $progecen_actions WHERE id_action::text = $1 ;
    "
);
$result = pg_execute($dbconn, "sql", array($id_action)) or die ( pg_last_error());


pg_close($dbconn);

echo json_encode("Action Clonée");
?>
