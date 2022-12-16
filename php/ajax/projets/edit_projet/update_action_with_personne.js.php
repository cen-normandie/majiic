<?php
include '../../../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());

$arr_param = json_decode($_POST["action"]);

$id_action  = $arr_param->{'id_action'};
$personne  = $arr_param->{'personne'};

$result = pg_prepare($dbconn, "sql", 
    "
    UPDATE $progecen_actions set personnes = $2 WHERE id_action = $1;
    "
);

$result = pg_execute($dbconn, "sql", array($id_action,$personne)) or die ( pg_last_error());
pg_close($dbconn);

echo json_encode("Action Modifiée : personne ajoutée");
?>
