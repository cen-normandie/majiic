<?php
include '../properties.php';
require '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
/* $sheet->setCellValue('A1', 'Hello World !');
$writer = new Xlsx($spreadsheet);
$writer->save('hello world.xlsx'); */

//$_POST["id_projet"] = 273;
$quote = "'";

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 


"
SELECT 
    e.e_id, 
    e.e_objet, 
    $2||to_char(e.e_start AT TIME ZONE 'UTC' , 'YYYY-MM-DD HH24:MI:SS') as start, 
    $2||to_char(e.e_end AT TIME ZONE 'UTC' , 'YYYY-MM-DD HH24:MI:SS') as end, 
    EXTRACT(epoch FROM e.e_end - e.e_start)/3600 as nb_heures,
    e.e_id_projet,
    e.e_id_action,
    a.code_action,
    e.e_nom_projet,
    e.e_lieu,
    e.e_commentaire,
    e.e_salissure,
    e.e_panier,
    $2||to_char(e.e_date_saisie AT TIME ZONE 'UTC' , 'YYYY-MM-DD') as date_saisie,
    $2||to_char(e.e_date_saisie_salissure AT TIME ZONE 'UTC' , 'YYYY-MM-DD') as date_saisie_salissure,
    p.color,
    e.e_personne
FROM $progecen_temps e 
LEFT JOIN $progecen_projets p on e.e_id_projet = p.id_projet::text 
LEFT JOIN $progecen_actions a on e.e_id_action = a.id_action::text
WHERE e.e_id_projet = $1
ORDER by 3, 2
"
);
$result = pg_execute($dbconn, "sql", array($_POST["id_projet"],$quote));
$row_ = 1;
//write first line title
$arr_columnname = ['id','objet','start','end','nb_heures','id_projet','id_action','nom_action','nom_projet','lieu','commentaire','salissure','panier','date_saisie','date_saisie_salissure','color','personne'];
for ($column = 1; $column <= 17; $column++) {
    $column_in_pg = $column -1;
    $sheet->setCellValueByColumnAndRow($column, $row_, $arr_columnname[$column_in_pg]);
}
$row_ = 2;
while($row = pg_fetch_array($result))
{
    for ($column = 1; $column <= 17; $column++) {
        $column_in_pg = $column -1;
        //echo 'col = '.$column . 'row[x] = '.$row[$column_in_pg].'</br>';
        $sheet->setCellValueByColumnAndRow($column, $row_, is_null($row[$column_in_pg]) ? '' : $row[$column_in_pg]);

            ///////////////////////////
            // ajouter une quote avant les dates ?
            ///////////////////////////

        //COLOR
        /* if ($column_in_pg==16) {
            $cell_ ='O'.strval($row_);
            //echo $cell_.'</br>';
            $color = strtoupper(ltrim($row[$column_in_pg], $row[$column_in_pg][0]));
            $spreadsheet->getActiveSheet()->getStyle('B1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($color);
        } */
    };
    $row_ = $row_ + 1;
}
$writer = new Xlsx($spreadsheet);
$writer->save('../files/export_temps.xlsx');

//ferme la connexion a la BD
pg_close($dbconn);
echo 'export_temps.xlsx';
?>