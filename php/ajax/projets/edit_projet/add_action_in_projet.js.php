<?php
include '../../../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());

$arr_param = json_decode($_POST["action"]);

$code_action  = $arr_param->{'action_name'};
$site  = $arr_param->{'site'};
$heures  = $arr_param->{'heures'};
$financeurs  = $arr_param->{'financeurs'};
$id_projet  = $arr_param->{'id_projet'};

$result = pg_prepare($dbconn, "sql", 
    "
    INSERT INTO $progecen_actions (code_action, financements, site, id_projet, nb_h_previ) 
    VALUES ( 
        $1,$2,$3,$4,$5
    );"
);

$result = pg_execute($dbconn, "sql", array($code_action,$financeurs,$site,$id_projet,$heures)) or die ( pg_last_error());
pg_close($dbconn);

echo json_encode("Action ajoutée au projet : recharger la page");
?>
