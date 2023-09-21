<?php
include '../properties.php';
require '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$_year_begin = strval($_POST["year"]);
$_year_end = strval($_POST["year"] + 1);
$quote = "'";

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 
"
SELECT 
    e.e_id, 
    e.e_objet, 
    $3||to_char(e.e_start AT TIME ZONE 'UTC' , 'YYYY-MM-DD HH24:MI:SS') as start, 
    $3||to_char(e.e_end AT TIME ZONE 'UTC' , 'YYYY-MM-DD HH24:MI:SS') as end,
    EXTRACT(epoch FROM e.e_end - e.e_start)/3600 as nb_heures,
    e.e_id_projet,
    e.e_nom_projet,
    e.e_id_action,
    a.code_action,
    e.e_lieu,
    e.e_commentaire,
    e.e_salissure,
    e.e_panier,
    $3||to_char(e.e_date_saisie AT TIME ZONE 'UTC' , 'YYYY-MM-DD') as date_saisie,
    $3||to_char(e.e_date_saisie_salissure AT TIME ZONE 'UTC' , 'YYYY-MM-DD') as date_saisie_salissure,
    p.color,
    e.e_personne
FROM $progecen_temps e 
LEFT JOIN $progecen_projets p on e.e_id_projet = p.id_projet::text 
LEFT JOIN $progecen_actions a on e.e_id_action = a.id_action::text
WHERE (e.e_start > TO_DATE( $1 ,'YYYY') AND e.e_end < TO_DATE( $2,'YYYY') )
ORDER by 3, 2
"
);
echo $_year_begin;
echo $_year_end;
echo "OK";
$result = pg_execute($dbconn, "sql", array($_year_begin, $_year_end, $quote ));
$row_ = 1;
//write first line title
$arr_columnname = ['id','objet','start','end','nb_heures','id_projet','nom_projet','id_action','nom_action','lieu','commentaire','salissure','panier','date_saisie','date_saisie_salissure','color','personne'];
for ($column = 1; $column <= 17; $column++) {
    $column_in_pg = $column -1;
    $sheet->setCellValueByColumnAndRow($column, $row_, $arr_columnname[$column_in_pg]  );
}
$row_ = 2;
while($row = pg_fetch_array($result))
{
    for ($column = 1; $column <= 17; $column++) {
        $column_in_pg = $column -1;
        //echo 'col = '.$column . 'row[x] = '.$row[$column_in_pg].'</br>';
        $sheet->setCellValueByColumnAndRow($column, $row_, is_null($row[$column_in_pg]) ? '' : $row[$column_in_pg] );
    };
    $row_ = $row_ + 1;
}
$writer = new Xlsx($spreadsheet);
$writer->save('../files/export_global.xlsx');

//ferme la connexion a la BD
pg_close($dbconn);
echo 'export_global.xlsx';
?>