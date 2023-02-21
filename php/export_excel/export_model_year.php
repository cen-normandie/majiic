<?php
include '../properties.php';
require '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$_year = strval($_POST["year"]);


$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 
"
SELECT 
    p.id_projet,
    p.nom_projet,
    a.id_action,
    a.code_action,
    a.site,
    a.personnes
FROM $progecen_projets p 
LEFT JOIN $progecen_actions a on p.id_projet = a.id_projet
LEFT JOIN $progecen_group g on a.personnes = g.id_group
WHERE (a.personnes ~* $1 or g.personnes ~* $1 )
AND ( $2 = date_part('year', p.date_debut )::text  )
ORDER by 1,3,5
"
);
$result = pg_execute($dbconn, "sql", array($_POST["nom_personne"], $_year ));
$row_ = 1;
//write first line title
$arr_columnname = ['id_projet','nom_projet','id_action','nom_action','site','personne'];
for ($column = 1; $column <= 6; $column++) {
    $column_in_pg = $column -1;
    $sheet->setCellValueByColumnAndRow($column, $row_, $arr_columnname[$column_in_pg]);
}
$row_ = 2;
while($row = pg_fetch_array($result))
{
    for ($column = 1; $column <= 6; $column++) {
        $column_in_pg = $column -1;
        //echo 'col = '.$column . 'row[x] = '.$row[$column_in_pg].'</br>';
        $sheet->setCellValueByColumnAndRow($column, $row_, is_null($row[$column_in_pg]) ? '' : $row[$column_in_pg]);
    };
    $row_ = $row_ + 1;
}
$writer = new Xlsx($spreadsheet);
$writer->save('../files/export_modele.xlsx');

//ferme la connexion a la BD
pg_close($dbconn);
echo 'export_modele.xlsx';
?>