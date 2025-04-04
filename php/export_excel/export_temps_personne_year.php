<?php
include '../properties.php';
require '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$_end_file = str_replace(' ', '_', $_POST["nom_personne"].'_'.strval($_POST["year"]));

//$_POST["nom_personne"] = 'Benoit Perceval';
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
    $4 || to_char(e.e_start::timestamp WITHOUT TIME ZONE , 'YYYY-MM-DD HH24:MI:SS') as start, 
    $4 ||to_char(e.e_end::timestamp WITHOUT TIME ZONE , 'YYYY-MM-DD HH24:MI:SS') as end, 
    EXTRACT(epoch FROM e.e_end - e.e_start)/3600 as nb_heures,
    e.e_id_projet,
    p.nom_projet,
    e.e_id_action,
    a.code_action,
    a.site,
    e.e_lieu,
    e.e_commentaire,
    e.e_salissure,
    e.e_panier,
    $4 ||to_char(e.e_date_saisie::timestamp WITHOUT TIME ZONE , 'YYYY-MM-DD') as date_saisie,
    $4 ||to_char(e.e_date_saisie_salissure::timestamp WITHOUT TIME ZONE , 'YYYY-MM-DD') as date_saisie_salissure,
    p.color,
    e.e_personne
FROM $progecen_temps e 
LEFT JOIN $progecen_projets p on e.e_id_projet = p.id_projet::text 
LEFT JOIN $progecen_actions a on e.e_id_action = a.id_action::text
WHERE e.e_personne = $1 AND (e.e_start > TO_DATE( $2 ,'YYYY') AND e.e_end < TO_DATE( $3,'YYYY') )
ORDER by 3, 2
"
);
$result = pg_execute($dbconn, "sql", array($_POST["nom_personne"], $_year_begin, $_year_end, $quote ));
$row_ = 1;
//write first line title
$arr_columnname = ['id','objet','start','end','nb_heures','id_projet','nom_projet','id_action','nom_action','site','lieu','commentaire','salissure','panier','date_saisie','date_saisie_salissure','color','personne'];
for ($column = 1; $column <= 18; $column++) {
    $column_in_pg = $column -1;
    $sheet->setCellValueByColumnAndRow($column, $row_, $arr_columnname[$column_in_pg]);
}
$row_ = 2;
while($row = pg_fetch_array($result))
{
    for ($column = 1; $column <= 18; $column++) {
        $column_in_pg = $column -1;
        //echo 'col = '.$column . 'row[x] = '.$row[$column_in_pg].'</br>';
        $sheet->setCellValueByColumnAndRow($column, $row_, is_null($row[$column_in_pg]) ? '' : $row[$column_in_pg]);
    };
    $row_ = $row_ + 1;
}
$writer = new Xlsx($spreadsheet);
$writer->save('../files/export_temps_'.$_end_file.'.xlsx');

//ferme la connexion a la BD
pg_close($dbconn);
echo 'export_temps_'.$_end_file.'.xlsx';
?>