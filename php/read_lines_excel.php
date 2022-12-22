<?php
include 'properties.php';
require '../vendor/autoload.php';
define('ROOT_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR);

$file_name = $_POST['file_name'];
$row = $_POST['row'];

if( isset($file_name))
{
    $inputFileName = $file_name;
    /**  Identify the type of $inputFileName  **/
    $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
    /**  Create a new Reader of the type that has been identified  **/
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
    /**  Load $inputFileName to a Spreadsheet Object  **/
    $sheet = $reader->load($inputFileName);
    $arr_columnname = ['id','objet','start','end','id_projet','id_action','nom_action','nom_projet','lieu','commentaire','salissure','panier','date_saisie','date_saisie_salissure','color','personne'];
    //connexion a la BD
    $dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
    or die ('Connexion impossible :'. pg_last_error());
    $result = pg_prepare($dbconn, "insert_", "
        INSERT INTO $progecen_temps
            (
                e_objet,
                e_start,
                e_end,
                e_id_projet,
                e_id_action,
                e_nom_action,
                e_nom_projet,
                e_lieu,
                e_commentaire,
                e_salissure,
                e_panier,
                e_date_saisie,
                e_date_saisie_salissure,
                e_personne
            ) 
        VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14);"
    );
    
    echo (pg_execute($dbconn, "insert_",array(
        ($sheet->getActiveSheet()->getCellByColumnAndRow(2, $row)->getValue()??''),
        ($sheet->getActiveSheet()->getCellByColumnAndRow(3, $row)->getValue()??''),
        ($sheet->getActiveSheet()->getCellByColumnAndRow(4, $row)->getValue()??''),
        ($sheet->getActiveSheet()->getCellByColumnAndRow(5, $row)->getValue()??''),
        ($sheet->getActiveSheet()->getCellByColumnAndRow(6, $row)->getValue()??''),
        ($sheet->getActiveSheet()->getCellByColumnAndRow(7, $row)->getValue()??''),
        ($sheet->getActiveSheet()->getCellByColumnAndRow(8, $row)->getValue()??''),
        ($sheet->getActiveSheet()->getCellByColumnAndRow(9, $row)->getValue()??''),
        ($sheet->getActiveSheet()->getCellByColumnAndRow(10, $row)->getValue()??''),
        ($sheet->getActiveSheet()->getCellByColumnAndRow(11, $row)->getValue()??''),
        ($sheet->getActiveSheet()->getCellByColumnAndRow(12, $row)->getValue()??''),
        ($sheet->getActiveSheet()->getCellByColumnAndRow(13, $row)->getValue()??''),
        ($sheet->getActiveSheet()->getCellByColumnAndRow(14, $row)->getValue()??''),
        ($sheet->getActiveSheet()->getCellByColumnAndRow(16, $row)->getValue()??'')
    )) or die ( pg_last_error()));

    //ferme la connexion a la BD
    pg_close($dbconn);
}
//echo $return_execute;
/* echo $sheet->getActiveSheet()->getCellByColumnAndRow(2, $row)->getValue()
.'    '.$sheet->getActiveSheet()->getCellByColumnAndRow(3, $row)->getValue()
.'    '.$sheet->getActiveSheet()->getCellByColumnAndRow(4, $row)->getValue()
.'    '.$sheet->getActiveSheet()->getCellByColumnAndRow(5, $row)->getValue()
.'    '.$sheet->getActiveSheet()->getCellByColumnAndRow(6, $row)->getValue()
.'    '.$sheet->getActiveSheet()->getCellByColumnAndRow(7, $row)->getValue()
.'    '.$sheet->getActiveSheet()->getCellByColumnAndRow(8, $row)->getValue()
.'    '.$sheet->getActiveSheet()->getCellByColumnAndRow(9, $row)->getValue()
.'    '.$sheet->getActiveSheet()->getCellByColumnAndRow(10, $row)->getValue()
.'    '.$sheet->getActiveSheet()->getCellByColumnAndRow(11, $row)->getValue()
.'    '.$sheet->getActiveSheet()->getCellByColumnAndRow(12, $row)->getValue()
.'    '.$sheet->getActiveSheet()->getCellByColumnAndRow(13, $row)->getValue()
.'    '.$sheet->getActiveSheet()->getCellByColumnAndRow(14, $row)->getValue()
.'    '.$sheet->getActiveSheet()->getCellByColumnAndRow(16, $row)->getValue(); */

/* echo  " INSERT INTO ".$progecen_temps."
        (e_objet,start,end,id_projet,id_action,nom_action,nom_projet,lieu,commentaire,salissure,panier,date_saisie,date_saisie_salissure,color,personne) 
    VALUES (".$sheet->getActiveSheet()->getCellByColumnAndRow(2, $row)->getValue().", ".
    ($sheet->getActiveSheet()->getCellByColumnAndRow(3, $row)->getValue()??'') .", ".
    ($sheet->getActiveSheet()->getCellByColumnAndRow(4, $row)->getValue()??'').", ".
    ($sheet->getActiveSheet()->getCellByColumnAndRow(5, $row)->getValue()??'').", ".
    ($sheet->getActiveSheet()->getCellByColumnAndRow(6, $row)->getValue()??'').", ".
    ($sheet->getActiveSheet()->getCellByColumnAndRow(7, $row)->getValue()??'').", ".
    ($sheet->getActiveSheet()->getCellByColumnAndRow(8, $row)->getValue()??'').", ".
    ($sheet->getActiveSheet()->getCellByColumnAndRow(9, $row)->getValue()??'').", ".
    ($sheet->getActiveSheet()->getCellByColumnAndRow(10, $row)->getValue()??'').", ".
    ($sheet->getActiveSheet()->getCellByColumnAndRow(11, $row)->getValue()??'').", ".
    ($sheet->getActiveSheet()->getCellByColumnAndRow(12, $row)->getValue()??'').", ".
    ($sheet->getActiveSheet()->getCellByColumnAndRow(13, $row)->getValue()??'').", ".
    ($sheet->getActiveSheet()->getCellByColumnAndRow(14, $row)->getValue()??'').", ".
    ($sheet->getActiveSheet()->getCellByColumnAndRow(15, $row)->getValue()??'').", ".
    ($sheet->getActiveSheet()->getCellByColumnAndRow(16, $row)->getValue()??'')
    .");"; */





?>





