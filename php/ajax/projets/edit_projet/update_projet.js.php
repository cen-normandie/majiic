<?php
include '../../../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());

$arr_param = json_decode($_POST["projet"]);

$id_projet  = $arr_param->{'id'};
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
    UPDATE $progecen_projets
	SET  
	nom_projet=$2, 
	territoire=$3, 
	type_projet=$4, 
	date_debut=$5, 
	date_fin=$6, 
	etat=$7, 
	responsable_projet=$8,  
	commentaire_projet=$9, 
	color=$10
	WHERE id_projet = $1;"
);

$result = pg_execute($dbconn, "sql", array(
    $id_projet,
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
