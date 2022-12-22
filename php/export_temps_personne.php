<?php
include 'properties.php';
require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

//$_POST["nom_personne"] = 'Benoit Perceval';

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 


"
SELECT 
    e.e_id, 
    e.e_objet, 
    to_char(e.e_start AT TIME ZONE 'UTC' , 'YYYY-MM-DD HH24:MI:SS') as start, 
    to_char(e.e_end AT TIME ZONE 'UTC' , 'YYYY-MM-DD HH24:MI:SS') as end, 
    e.e_id_projet,
    e.e_id_action,
    e.e_nom_action,
    e.e_nom_projet,
    e.e_lieu,
    e.e_commentaire,
    e.e_salissure,
    e.e_panier,
    to_char(e.e_date_saisie AT TIME ZONE 'UTC' , 'YYYY-MM-DD') as date_saisie,
    to_char(e.e_date_saisie_salissure AT TIME ZONE 'UTC' , 'YYYY-MM-DD') as date_saisie_salissure,
    p.color,
    e.e_personne
FROM $progecen_temps e 
LEFT JOIN $progecen_projets p on e.e_id_projet = p.id_projet::text 
WHERE e.e_personne = $1
ORDER by 3, 2
"
);
$result = pg_execute($dbconn, "sql", array($_POST["nom_personne"]));
$row_ = 1;
//write first line title
$arr_columnname = ['id','objet','start','end','id_projet','id_action','nom_action','nom_projet','lieu','commentaire','salissure','panier','date_saisie','date_saisie_salissure','color','personne'];
for ($column = 1; $column <= 16; $column++) {
    $column_in_pg = $column -1;
    $sheet->setCellValueByColumnAndRow($column, $row_, $arr_columnname[$column_in_pg]);
}
$row_ = 2;
while($row = pg_fetch_array($result))
{
    for ($column = 1; $column <= 16; $column++) {
        $column_in_pg = $column -1;
        //echo 'col = '.$column . 'row[x] = '.$row[$column_in_pg].'</br>';
        $sheet->setCellValueByColumnAndRow($column, $row_, $row[$column_in_pg]);



        if ($column_in_pg==15) {
            $cell_ ='O'.strval($row_);
            //echo $cell_.'</br>';
            $color = strtoupper(ltrim($row[$column_in_pg], $row[$column_in_pg][0]));
            $spreadsheet->getActiveSheet()->getStyle('B1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($color);
        }
    };
    $row_ = $row_ + 1;
}
$writer = new Xlsx($spreadsheet);
$writer->save('export_temps.xlsx');

//ferme la connexion a la BD
pg_close($dbconn);
echo 'export_temps.xlsx';
?>