<?php
    include 'properties.php';

function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
            if (filetype($dir.DIRECTORY_SEPARATOR.$object) == "dir") rmdir($dir.DIRECTORY_SEPARATOR.$object); else unlink($dir.DIRECTORY_SEPARATOR.$object);
            }
        }
    reset($objects);
    rmdir($dir);
    }
}
define('ROOT_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR);

$path = ROOT_DIR;
$path_doc_foncier = $path.'docs'.DIRECTORY_SEPARATOR.'foncier';
$file_name_tmp = $_FILES['file']['name'];
$file_name = $_POST["id_doc"];
//echo $file_name;

if( isset($_FILES["file"]))
{
    //$file_name = $_FILES['file']['name'];
    if(file_exists($path_doc_foncier.DIRECTORY_SEPARATOR.$file_name.'.pdf') ) {
        $file_name = $file_name.date("d-m-Y_h_i_s");
        //TO DO UPDATE LIEN
    } else {
        $file_name = $_POST["id_doc"];
    }
    
    //echo 'Path :'.$path_doc_foncier.DIRECTORY_SEPARATOR.$file_name.'.pdf';
    
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $path_doc_foncier.DIRECTORY_SEPARATOR.basename($file_name).'.pdf' )) {
        echo "The file has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
echo $file_name;







?>





