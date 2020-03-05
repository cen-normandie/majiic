<?php
session_start();
include "../../properties.php";

//connexion a la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());

$sql = 
"
INSERT INTO mares.caracterisations(
loc_id_plus, car_date, car_obsv, 
car_type, car_faune, car_faune_autre, car_veget, car_evolution, 

car_abreuv, car_topo, car_topo_autre, car_cloture, car_haie, car_form, car_long, car_larg, 
car_prof, car_natfond, car_natfond_autre, car_berges, car_bourrelet, 
car_bourrelet_pourcentage, car_pietinement, 

car_hydrologie, car_turbidite, car_couleur, car_tampon, car_exutoire, car_recou_total, car_recou_helophyte, 
car_recou_hydrophyte_e, car_recou_hydrophyte_ne, car_recou_algue, 
car_recou_eau_libre, car_embrous, car_ombrage, car_comt, car_recou_non_veget, 
car_patrimoine, car_patrimoine_autre, car_couleur_precision, 

car_objec_trav, car_travaux, car_liaison, car_dechet, car_alimentation, 
car_contexte, car_usage, car_bati, car_eaee, car_evee, 

car_liaison_autre, car_alimentation_autre, car_travaux_autre,

car_id_user
) 
(
SELECT
  '".str_replace("'","''",$_POST['loc_id_plus'])."' ,
  '".str_replace("'","''",$_POST['car_date'])."' ,
  '".str_replace("'","''",$_SESSION['nom_prenom'])."' ,
  '".str_replace("'","''",$_POST['car_type'])."' ,
  '".str_replace("'","''",$_POST['grp_faune'])."' ,
  '".str_replace("'","''",$_POST['grp_faune_autre'])."' ,
  '".str_replace("'","''",$_POST['car_veget'])."' ,
  '".str_replace("'","''",$_POST['car_stade'])."' ,
  
  '".str_replace("'","''",$_POST['car_pompe'])."' ,
  '".str_replace("'","''",$_POST['car_topo'])."' ,
  '".str_replace("'","''",$_POST['car_topo_autre'])."' ,
  '".str_replace("'","''",$_POST['car_cloture'])."' ,
  '".str_replace("'","''",$_POST['car_haie'])."' ,
  '".str_replace("'","''",$_POST['car_forme'])."' ,
  replace('".str_replace("'","''",$_POST['car_long'])."', '', '0')::int ,
  replace('".str_replace("'","''",$_POST['car_larg'])."', '', '0')::int ,
  '".str_replace("'","''",$_POST['car_hauteur'])."' ,
  '".str_replace("'","''",$_POST['car_fond'])."' ,
  '".str_replace("'","''",$_POST['car_fond_autre'])."' ,
  '".str_replace("'","''",$_POST['car_berges'])."' ,
  '".str_replace("'","''",$_POST['car_bourrelet'])."' ,
  '".str_replace("'","''",$_POST['car_bourrelet_prct'])."' ,
  '".str_replace("'","''",$_POST['car_surpietinement'])."' ,

  '".str_replace("'","''",$_POST['car_hydrologie'])."' ,
  '".str_replace("'","''",$_POST['car_turbidite'])."' ,
  '".str_replace("'","''",$_POST['car_couleur'])."' ,
  '".str_replace("'","''",$_POST['car_tampon'])."' ,
  '".str_replace("'","''",$_POST['car_exutoire'])."' ,
  replace('".str_replace("'","''",$_POST['rec_total'])."', '', '0')::float ,
  replace('".str_replace("'","''",$_POST['c_recou_helophyte'])."', '', '0')::int ,
  replace('".str_replace("'","''",$_POST['c_recou_hydrophyte_e'])."', '', '0')::int ,
  replace('".str_replace("'","''",$_POST['c_recou_hydrophyte_ne'])."', '', '0')::int ,
  replace('".str_replace("'","''",$_POST['c_recou_algue'])."', '', '0')::int ,
  replace('".str_replace("'","''",$_POST['c_recou_eau_libre'])."', '', '0')::int ,
  '".str_replace("'","''",$_POST['car_embroussaillement'])."' ,
  '".str_replace("'","''",$_POST['car_ombrage'])."' ,
  '".str_replace("'","''",$_POST['car_comt'])."' ,
  replace('".str_replace("'","''",$_POST['c_recou_non_veget'])."', '', '0')::int ,
  '".str_replace("'","''",$_POST['car_patrimoine'])."' ,
  '".str_replace("'","''",$_POST['car_patrimoine_autre'])."' ,
  '".str_replace("'","''",$_POST['car_couleur_autre'])."' ,
  
  '".str_replace("'","''",$_POST['car_objec_trav'])."' ,
  '".str_replace("'","''",$_POST['car_travaux'])."' ,
  '".str_replace("'","''",$_POST['car_liaisons'])."' ,
  '".str_replace("'","''",$_POST['car_dechets'])."' ,
  '".str_replace("'","''",$_POST['car_alimentations'])."' ,
  '".str_replace("'","''",$_POST['car_contextes'])."' ,
  '".str_replace("'","''",$_POST['car_usages'])."' ,
  '".str_replace("'","''",$_POST['car_patrimoine'])."' ,
  '".str_replace("'","''",$_POST['car_eaee'])."' ,
  '".str_replace("'","''",$_POST['car_evee'])."' ,
  
  '".str_replace("'","''",$_POST['car_liaisons_autre'])."' ,
  '".str_replace("'","''",$_POST['car_alimentations_autre'])."' ,
  '".str_replace("'","''",$_POST['car_travaux_autre'])."' ,
  '".str_replace("'","''",$_SESSION['email'])."' 
);
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