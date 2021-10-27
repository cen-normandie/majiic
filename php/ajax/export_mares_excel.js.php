<?php
include '../properties.php';

/** PHPExcel */
include '../Classes/PHPExcel.php';

/** PHPExcel_Writer_Excel2007 */
include '../Classes/PHPExcel/Writer/Excel2007.php';

$arr = array();

$table_name_search = $_POST["table_name_search"];
$id_search = $_POST["id_search"];

////LOCAL
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS") or die ('Connexion impossible :'. pg_last_error());

$sql = "select l.loc_id,  l.loc_id_plus,  l.loc_uuid,  l.loc_nom,  l.loc_type_propriete,  l.loc_statut,  l.loc_date,  l.loc_obsv,  l.loc_comt, 
 l.loc_anonymiser,  l.car_ids,  l.loc_id_user, c.id, c.id_plus, c.date, c.obsv, c.strp, c.type, c.veget, c.evolution, c.abreuv, c.topo, c.topo_autre, c.cloture, c.haie, c.form, c.long,
 c.larg, c.natfond, c.natfond_autre, c.berges, c.bourrelet, c.bourrelet_pourcentage, c.pietinement, c.hydrologie, c.turbidite, c.couleur, c.tampon, c.exutoire, 
 c.recou_total, c.recou_helophyte, c.recou_hydrophyte_e, c.recou_hydrophyte_ne, c.recou_algue, c.recou_eau_libre, c.prof, c.embrous, c.ombrage, 
 c.comt, c.recou_non_veget, c.patrimoine, c.patrimoine_autre, c.couleur_precision, c.objec_trav, c.travaux, c.liaison, c.dechet, c.alimentation, c.contexte, 
 c.faune, c.usage, c.bati, c.eaee, c.evee, c.id_user, c.faune_autre, c.liaison_autre, c.alimentation_autre, c.travaux_autre
 from  ( select  loc_id,  loc_id_plus,  loc_uuid,  loc_nom,  loc_type_propriete,  loc_statut,  loc_date,  loc_obsv,  loc_comt,  loc_anonymiser, 
  car_ids,  loc_id_user from mares.localisations left join 
  (select l_geom from layers.".pg_escape_string($table_name_search)." 
  where l_id = '".pg_escape_string($id_search)."') t 
  on st_intersects(localisations.loc_geom, t.l_geom) ) as l left join 
  ( select distinct on (loc_id_plus) 
  car_id as id, car_id_plus as id_plus, loc_id_plus, loc_id_strp, car_date as date, car_obsv as obsv, car_strp as strp, car_type as type, car_veget as veget, car_evolution as evolution,
  car_abreuv as abreuv, car_topo as topo, car_topo_autre as topo_autre, car_cloture as cloture, car_haie as haie, car_form as form, car_long as long, car_larg as larg, car_natfond as natfond,
  car_natfond_autre as natfond_autre, car_berges as berges, car_bourrelet as bourrelet, car_bourrelet_pourcentage as bourrelet_pourcentage, car_pietinement as pietinement, car_hydrologie as hydrologie,
  car_turbidite as turbidite, car_couleur as couleur, car_tampon as tampon, car_exutoire as exutoire, car_recou_total as recou_total, car_recou_helophyte as recou_helophyte,
  car_recou_hydrophyte_e as recou_hydrophyte_e, car_recou_hydrophyte_ne as recou_hydrophyte_ne, car_recou_algue as recou_algue, car_recou_eau_libre as recou_eau_libre, car_embrous as embrous,
  car_ombrage as ombrage, car_comt as comt, car_recou_non_veget as recou_non_veget, car_patrimoine as patrimoine, car_patrimoine_autre as patrimoine_autre, car_couleur_precision as couleur_precision,
  car_objec_trav as objec_trav, car_travaux as travaux, car_liaison as liaison, car_dechet as dechet, car_alimentation as alimentation, car_contexte as contexte, car_faune as faune, car_usage as usage,
  car_bati as bati, car_eaee as eaee, car_evee as evee, car_id_user as id_user, car_faune_autre as faune_autre, car_liaison_autre as liaison_autre, car_alimentation_autre as alimentation_autre,
  car_travaux_autre as travaux_autre, car_prof as prof from mares.caracterisations order by loc_id_plus, car_date desc ) as c on  (l.loc_id_plus = c.loc_id_plus);";

//echo $sql;

////execute la requete dans le moteur de base de donnees  
$query_result = pg_exec($dbconn,$sql) or die ( pg_last_error());
//
$arr = pg_fetch_all($query_result);
//
//
////ferme la connexion a la BD
pg_close($dbconn);



    // Create new PHPExcel object
    // echo date('H:i:s') . " Create new PHPExcel object\n";
    $objPHPExcel_out = new PHPExcel();
    // Set properties
    // echo date('H:i:s') . " Set properties\n";
    $objPHPExcel_out->getProperties()->setCreator($_SESSION['email']);
    $objPHPExcel_out->getProperties()->setLastModifiedBy($_SESSION['email']);
    $objPHPExcel_out->getProperties()->setTitle("Office 2007 XLSX Test Document");
    $objPHPExcel_out->getProperties()->setSubject("Office 2007 XLSX Test Document");
    $objPHPExcel_out->getProperties()->setDescription("Exported document for Office 2007 XLSX, generated using PHP classes.");
    
    $objPHPExcel_out->setActiveSheetIndex(0);
    
    
    
    foreach ($arr as $numero_ligne_excel => $array_data){
        $idx_colonne = 0;
        foreach ($array_data as $nom_champ_as_name_col =>  $value_asCell){
            // echo 'row : '. $numero_ligne_excel;
            // recupere les enetete sur la premiere ligne
            if ($numero_ligne_excel < 1) {
                $objPHPExcel_out->getActiveSheet()->setCellValueByColumnAndRow($idx_colonne , $numero_ligne_excel+1,  $nom_champ_as_name_col );
                //$objPHPExcel_out->getActiveSheet()->setCellValueByColumnAndRow($idx_colonne , $numero_ligne_excel+1,  "\nThis is <i>italic</i>." );
                
            }
            
            $objPHPExcel_out->getActiveSheet()->setCellValueByColumnAndRow($idx_colonne , $numero_ligne_excel+2 ,  $value_asCell );
            
            
            
            
            $idx_colonne++ ;
        }
        
    }
    // Rename sheet
    // echo date('H:i:s') . " Rename sheet\n";
    $objPHPExcel_out->getActiveSheet()->setTitle('Extraction_PRAM');
    // Save Excel 2007 file
    // echo date('H:i:s') . " Write to Excel2007 format\n";
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel_out);
    // $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
    $file_name = '../excel/extraction_donnees_'.date('H_i_s').'.xlsx';
    $objWriter->save($file_name);
    // Echo done
    $file_name = str_replace("../excel/","",$file_name);
    echo $file_name;







?>


























?>