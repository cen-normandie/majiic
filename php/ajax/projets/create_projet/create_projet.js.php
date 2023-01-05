<?php
include '../../../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());

$arr_param = json_decode($_POST["projet"]);

$nom_projet  = $arr_param->{'name'};
$responsable_projet  = $arr_param->{'responsable_projet'};
$type_projet  = $arr_param->{'type_projet'};
$etat_projet  = $arr_param->{'etat_projet'};
$echelle_projet  = $arr_param->{'echelle_projet'};
$p_date_start  = $arr_param->{'p_date_start'};
$p_date_end  = $arr_param->{'p_date_end'};
$p_commentaire  = $arr_param->{'p_commentaire'};
$p_color  = $arr_param->{'p_color'};

$result = pg_prepare($dbconn, "sql", 
    "
    INSERT INTO $progecen_projets
	(
        id_projet,
        nom_projet, 
        territoire, 
        type_projet, 
        date_debut, 
        date_fin, 
        etat, 
        responsable_projet, 
        commentaire_projet, 
        color)
        VALUES (
            select SELECT nextval('progecen_copy.id_projet')
            $1,$2,$3,$4,$5,$6,$7,$8,$9);"
    );

$result = pg_execute($dbconn, "sql", array(
    $nom_projet,
    $echelle_projet,
    $type_projet,
    $p_date_start,
    $p_date_end,
    $etat_projet,
    $responsable_projet,
    $p_commentaire,
    $p_color
    )) or die ( pg_last_error());
pg_close($dbconn);

echo json_encode("output");
?>
