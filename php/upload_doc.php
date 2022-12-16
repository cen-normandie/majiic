<?php
    include 'properties.php';

define('ROOT_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR);

$path = ROOT_DIR;
$path_doc_files = $path.DIRECTORY_SEPARATOR.'files';
$path_parts = pathinfo($_FILES['file']['name']);
$extension = ".".$path_parts['extension'];
$file_name_tmp = $path_parts['filename'];

if( isset($_FILES["file"]))
{
    $file_name = $file_name_tmp.'_'.date("d-m-Y_h_i_s");
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $path_doc_files.DIRECTORY_SEPARATOR.$file_name.$extension )) {
        //echo "Uploaded" save line in db
        //connexion a la BD
        $dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
        or die ('Connexion impossible :'. pg_last_error());
        $result = pg_prepare($dbconn, "up", "
            INSERT INTO $files (id_projet, file_name) VALUES ($1,$2);"
        );
        $return_execute = pg_execute($dbconn, "up",array($_POST["id_projet"], basename($file_name).$extension ));
        //ferme la connexion a la BD
        pg_close($dbconn);


    } else {
        //echo "Error";
    }
}
echo $file_name;







?>





