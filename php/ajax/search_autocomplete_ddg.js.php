<?php
include '../properties.php';
$arr = array();

//connexion a la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());

$result = pg_prepare($dbconn, "sql1", "SELECT * FROM $ddg WHERE nom_doc_gestion ~* $1 or id_doc_gestion ~* $1 order by 1");
$result = pg_execute($dbconn, "sql1", array($_POST["term"]));
while($row = pg_fetch_array($result))
{
  $arr[]=array(
    'id_doc_gestion'      =>trim($row["id_doc_gestion"]), 
    'nom_doc_gestion'     =>trim($row["nom_doc_gestion"]), 
    'type_doc_gestion'    =>trim($row["type_doc_gestion"]), 
    'd_debut_doc_gestion' =>trim($row["d_debut_doc_gestion"]), 
    'd_fin_doc_gestion'   =>trim($row["d_fin_doc_gestion"]), 
    'auteurs'             =>trim($row["auteurs"]), 
    'commentaires'        =>trim($row["commentaires"]),
    'lien'                =>trim($row["lien"]),
    'd_maj_doc_gestion'   =>trim($row["d_maj_doc_gestion"]), 
    'multisite'           =>trim($row["multisite"])
    );
}
//ferme la connexion a la BD
pg_close($dbconn);
echo json_encode($arr);
//echo $arr;
?>