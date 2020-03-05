<?php
session_start();
include "../../properties.php";

//connexion a la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());

$sql = 
"
INSERT INTO $localisations( loc_nom, loc_type_propriete, loc_statut, loc_date, loc_obsv, loc_comt, loc_anonymiser, loc_geom, loc_id_user ) (
SELECT
  '".str_replace("'","''",$_POST['loc_nom'])."' ,
  '".str_replace("'","''",$_POST['loc_type_propriete'])."',
  '".str_replace("'","''",$_POST['loc_statut'])."',
  '".str_replace("'","''",$_POST['loc_date'])."',
  '".str_replace("'","''",$_SESSION['nom_prenom'])."',
  '".str_replace("'","''",$_POST['loc_comt'])."',
  '".str_replace("'","''",$_POST['loc_anonymiser'])."',
  st_setsrid(st_makepoint(".str_replace("'","''",$_POST['x']).", ".str_replace("'","''",$_POST['y'])."),4326),
  '".str_replace("'","''",$_SESSION['email'])."'
);
";

//echo $sql;
if ( pg_exec($dbconn,$sql) ) {
    echo "true";
} else {
    echo "false";
}

////ferme la connexion a la BD
pg_close($dbconn);

?>