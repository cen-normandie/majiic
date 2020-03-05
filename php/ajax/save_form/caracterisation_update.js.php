<?php
session_start();
include "../../properties.php";

//connexion a la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());

$sql ="
UPDATE $caracterisations 
set
car_date= to_date('".str_replace("'","''",$_POST['car_date'])."', 'YYYY-MM-DD') ,
car_obsv='".str_replace("'","''",$_POST['nom_prenom'])."' ,
car_strp='".str_replace("'","''",$_POST['____'])."' ,
car_type='".str_replace("'","''",$_POST['car_type'])."' ,
car_veget='".str_replace("'","''",$_POST['car_veget'])."' ,
car_evolution='".str_replace("'","''",$_POST['car_stade'])."' ,
car_abreuv='".str_replace("'","''",$_POST['car_pompe'])."' ,
car_topo='".str_replace("'","''",$_POST['car_topo'])."' ,
car_topo_autre='".str_replace("'","''",$_POST['car_topo_autre'])."' ,
car_cloture='".str_replace("'","''",$_POST['car_cloture'])."' ,
car_haie='".str_replace("'","''",$_POST['car_haie'])."' ,
car_form='".str_replace("'","''",$_POST['car_forme'])."' ,
car_long='".str_replace("'","''",$_POST['car_long'])."'::int ,
car_larg='".str_replace("'","''",$_POST['car_larg'])."'::int ,
car_natfond='".str_replace("'","''",$_POST['car_fond'])."' ,
car_natfond_autre='".str_replace("'","''",$_POST['car_fond_autre'])."' ,
car_berges='".str_replace("'","''",$_POST['car_berges'])."' ,
car_bourrelet='".str_replace("'","''",$_POST['car_bourrelet'])."' ,
car_bourrelet_pourcentage='".str_replace("'","''",$_POST['car_bourrelet_prct'])."' ,
car_pietinement='".str_replace("'","''",$_POST['car_surpietinement'])."' ,
car_hydrologie='".str_replace("'","''",$_POST['car_hydrologie'])."' ,
car_turbidite='".str_replace("'","''",$_POST['car_turbidite'])."' ,
car_couleur='".str_replace("'","''",$_POST['car_couleur'])."' ,
car_tampon='".str_replace("'","''",$_POST['car_tampon'])."' ,
car_exutoire='".str_replace("'","''",$_POST['car_exutoire'])."' ,
car_recou_total='".str_replace("'","''",$_POST['rec_total'])."' ,
car_recou_helophyte='".str_replace("'","''",$_POST['c_recou_helophyte'])."' ,
car_recou_hydrophyte_e='".str_replace("'","''",$_POST['c_recou_hydrophyte_e'])."' ,
car_recou_hydrophyte_ne='".str_replace("'","''",$_POST['c_recou_hydrophyte_ne'])."' ,
car_recou_algue='".str_replace("'","''",$_POST['c_recou_algue'])."' ,
car_recou_eau_libre='".str_replace("'","''",$_POST['c_recou_eau_libre'])."' ,
car_embrous='".str_replace("'","''",$_POST['car_embroussaillement'])."' ,
car_ombrage='".str_replace("'","''",$_POST['car_ombrage'])."' ,
car_comt='".str_replace("'","''",$_POST['car_comt'])."' ,
car_recou_non_veget='".str_replace("'","''",$_POST['c_recou_non_veget'])."'::int ,
car_patrimoine='".str_replace("'","''",$_POST['car_patrimoine'])."' ,
car_patrimoine_autre='".str_replace("'","''",$_POST['car_patrimoine_autre'])."' ,
car_couleur_precision='".str_replace("'","''",$_POST['car_couleur_autre'])."' ,
car_objec_trav='".str_replace("'","''",$_POST['car_objec_trav'])."' ,
car_travaux='".str_replace("'","''",$_POST['car_travaux'])."' ,
car_liaison='".str_replace("'","''",$_POST['car_liaisons'])."' ,
car_dechet='".str_replace("'","''",$_POST['car_dechets'])."' ,
car_alimentation='".str_replace("'","''",$_POST['car_alimentations'])."' ,
car_contexte='".str_replace("'","''",$_POST['car_contextes'])."' ,
car_faune='".str_replace("'","''",$_POST['grp_faune'])."' ,
car_usage='".str_replace("'","''",$_POST['car_usages'])."' ,
car_bati='".str_replace("'","''",$_POST['car_patrimoine'])."' ,
car_eaee='".str_replace("'","''",$_POST['car_eaee'])."' ,
car_evee='".str_replace("'","''",$_POST['car_evee'])."' ,
car_id_user='".str_replace("'","''",$_SESSION['email'])."' ,
car_faune_autre='".str_replace("'","''",$_POST['grp_faune_autre'])."' ,
car_liaison_autre='".str_replace("'","''",$_POST['car_liaisons_autre'])."' ,
car_alimentation_autre='".str_replace("'","''",$_POST['car_alimentations_autre'])."' ,
car_travaux_autre='".str_replace("'","''",$_POST['car_travaux_autre'])."' ,
car_prof='".str_replace("'","''",$_POST['car_hauteur'])."' 
WHERE car_id = ".str_replace("'","''",$_POST['car_id'])."
";

//echo $sql;
if ( pg_exec($dbconn,$sql) ) {
    echo "true";
} else {
    echo "false";
}

////ferme la connexion a la BD
pg_close($dbconn);

?>










