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
$path_doc_foncier_autre = $path.'docs'.DIRECTORY_SEPARATOR.'foncier'.DIRECTORY_SEPARATOR.'autres';
$file_name_tmp = $_FILES['file']['name'];
$file_name = $_POST["name_doc"];
$id_site = $_POST["id_site"];



if( isset($_FILES["file"]))
{
    //$file_name = $_FILES['file']['name'];
    if(file_exists($path_doc_foncier_autre.DIRECTORY_SEPARATOR.$file_name) ) {
        $file_name = $file_name.date("d-m-Y_h_i_s");
        //TO DO UPDATE LIEN
    } else {
        $file_name = $_POST["name_doc"];
    }
    
    if ( move_uploaded_file($_FILES['file']['tmp_name'], $path_doc_foncier_autre.DIRECTORY_SEPARATOR.basename($file_name).'.pdf' ) ) {
        //echo "Uploaded";
    } else {
        //echo "Error";
        
    }
}


echo "fichier PDF enregistré";

$return_execute = false; 

//connexion a la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "autre_doc", "UPDATE $sites_data set autres_docs = coalesce( autres_docs ,'')||$1 where id_site = $2 ");
$return_execute = pg_execute($dbconn, "autre_doc",array( $file_name."'|'" , $id_site ));
//$sql = "UPDATE $sites_data set autres_docs = coalesce( autres_docs ,'') || '".$file_name."' || '|' where id_site = '".$id_site."' ;";
//echo $sql;
//execute la requete dans le moteur de base de donnees  
//$query_result = pg_query($dbconn,$sql) or die ( pg_last_error());
//ferme la connexion a la BD
pg_close($dbconn);


if ($return_execute == false) {
    echo 0;
} else {
    echo ' Document enregistré(e) et rattaché au site :'.$id_site;
};


//echo 'Le document est associé au site suivant : '.$id_site;





?>





