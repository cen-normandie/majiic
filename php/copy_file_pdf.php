<?php
    include 'properties.php';

function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
            if (filetype($dir."/".$object) == "dir") rmdir($dir."/".$object); else unlink($dir."/".$object);
            }
        }
    reset($objects);
    rmdir($dir);
    }
}

define('ROOT_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR);
$path = ROOT_DIR;
$path_doc_foncier = $path.'docs/foncier';
$file_name_tmp = $_FILES['file']['name'];
$file_name = $_POST["id_doc"];
//echo $file_name;

if( isset($_FILES["file"]))
{
    //$file_name = $_FILES['file']['name'];
    if(file_exists($path_doc_foncier.'/'.$file_name.'.pdf') ) {
        $file_name = $file_name.date("d-m-Y_h_i_s");
        
        //TO DO UPDATE LIEN
        
    }
    
    if ( move_uploaded_file($_FILES['file']['tmp_name'], $path_doc_foncier.'/'.$file_name.'.pdf' ) ) {
            //echo ' sur '.$path_doc_foncier.'/'.$file_name.'.pdf ';
            } else {
            }
}


echo $file_name;







?>





