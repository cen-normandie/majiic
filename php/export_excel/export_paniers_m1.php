<?php
include '../properties.php';
require '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$quote = "'";

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 
"
SELECT 
    e_id,
	e_personne as personne, 
	$1||to_char(e_start::date, 'DD-MM-YYYY') as date_panier,
	$1||to_char(e_date_saisie::date, 'DD-MM-YYYY') as saisie,
	$1||to_char(e_date_valide_panier::date, 'DD-MM-YYYY') as validation
	FROM $progecen_temps
	WHERE e_panier  is true 
	AND e_date_valide_panier is not null
    AND e_date_valide_panier >= date_trunc('MONTH',now())::DATE::timestamp
	order by 3 Desc, 2 Desc, 1 Asc
;
"
);
$result = pg_execute($dbconn, "sql", array($quote));
$row_ = 1;
//write first line title
$arr_columnname = ['id','personne','date_panier','date_saisie','date_validation'];
for ($column = 1; $column <= 5; $column++) {
    $column_in_pg = $column -1;
    $sheet->setCellValueByColumnAndRow($column, $row_, $arr_columnname[$column_in_pg]  );
}
$row_ = 2;
while($row = pg_fetch_array($result))
{
    for ($column = 1; $column <= 5; $column++) {
        $column_in_pg = $column -1;
        $sheet->setCellValueByColumnAndRow($column, $row_, is_null($row[$column_in_pg]) ? '' : $row[$column_in_pg] );
    };
    $row_ = $row_ + 1;
}
$writer = new Xlsx($spreadsheet);
$writer->save('../files/export_paniers.xlsx');

//ferme la connexion a la BD
pg_close($dbconn);
echo 'export_paniers.xlsx';
?>