<?php
include 'properties.php';
require '../vendor/autoload.php';
define('ROOT_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR);

$path = ROOT_DIR;
$path_doc_files = $path.'files';
$path_parts = pathinfo($_FILES['file']['name']);
$extension = ".".$path_parts['extension'];
$file_name_tmp = $path_parts['filename'];

if( isset($_FILES["file"]))
{
    $file_name = $file_name_tmp.'_'.date("d-m-Y_h_i_s");
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $path_doc_files.DIRECTORY_SEPARATOR.$file_name.$extension )) {
        $inputFileName = $path_doc_files.DIRECTORY_SEPARATOR.$file_name.$extension;
        /**  Identify the type of $inputFileName  **/
        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
        /**  Create a new Reader of the type that has been identified  **/
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        /**  Load $inputFileName to a Spreadsheet Object  **/
        $sheet = $reader->load($inputFileName);
        $highestRow = $sheet->getActiveSheet()->getHighestRow();
        echo trim($highestRow.'|'.$path_doc_files.DIRECTORY_SEPARATOR.$file_name.$extension);

        //DELETE ALL EVENT FROM SOMEBODY
        //connexion a la BD
        $dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
        or die ('Connexion impossible :'. pg_last_error());
        $result = pg_prepare($dbconn, "delete", "
            DELETE FROM $progecen_temps WHERE e_personne = $1 
            "
        );
        
        $resultat = pg_execute($dbconn, "delete",array( $_POST["nom_personne"] ));

        //ferme la connexion a la BD
        pg_close($dbconn);
        




    } else {
        //echo "Error";
    }
}
?>





