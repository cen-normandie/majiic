<?php
    include '../properties.php';

//connexion a la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$new_site_id   = $_POST["new_site_id"];
$sql = "
INSERT INTO $sites_data_mfu (id_site) VALUES ( '".$new_site_id."' ) ;";
//execute la requete dans le moteur de base de donnees  
$query_result = pg_query($dbconn,$sql) or die ( pg_last_error());
//ferme la connexion a la BD
pg_close($dbconn);
echo 'site enregistré !!!!';

?>
