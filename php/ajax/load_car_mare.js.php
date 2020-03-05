<?php
include '../properties.php';


//connexion a la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());

$sql = "
select row_to_json(t) from 
(SELECT car_id, car_id_plus, loc_id_plus, loc_id_strp, car_date, car_obsv, 
       car_strp, car_type, car_veget, car_evolution, car_abreuv, car_topo, 
       car_topo_autre, car_cloture, car_haie, car_form, car_long, car_larg, 
       car_natfond, car_natfond_autre, car_berges, car_bourrelet, car_bourrelet_pourcentage, 
       car_pietinement, car_hydrologie, car_turbidite, car_couleur, 
       car_tampon, car_exutoire, car_recou_total, car_recou_helophyte, 
       car_recou_hydrophyte_e, car_recou_hydrophyte_ne, car_recou_algue, 
       car_recou_eau_libre, car_embrous, car_ombrage, car_comt, car_recou_non_veget, 
       car_patrimoine, car_patrimoine_autre, car_couleur_precision, 
       car_objec_trav, car_travaux, car_liaison, car_dechet, car_alimentation, 
       car_contexte, car_faune, car_usage, car_bati, car_eaee, car_evee, 
       car_id_user, car_faune_autre, car_liaison_autre, car_alimentation_autre, 
       car_travaux_autre, car_prof
  FROM $caracterisations where car_id = ".$_POST["id_mare_car"]." ) t
";
//echo $sql;


$query_result = pg_exec($dbconn,$sql) or die (pg_last_error());
while($row = pg_fetch_row($query_result))
{
  echo trim($row[0]);
}
//ferme la connexion a la BD
pg_close($dbconn);

?>