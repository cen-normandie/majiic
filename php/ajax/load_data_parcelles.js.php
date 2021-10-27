<?php
include '../properties.php';
$arr = array();


//connexion a la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());

$sql = "
SELECT row_to_json(fc)
FROM ( SELECT array_agg(data) As parcelles
FROM (SELECT row_to_json(lp) As properties
  FROM $parcelles As lg 
        INNER JOIN (SELECT 
                id_unique, 
                id_group as id_site, 
                nom_group as nom_site, 
                left(split_part(id_unique, '|', 2),2) as dep,
                left(split_part(id_unique, '|', 2),5) as insee,
                substring(split_part(id_unique, '|', 2) from 6 for 3) as prefixe,
                substring(split_part(id_unique, '|', 2) from 9 for 2) as section,
                substring(split_part(id_unique, '|', 2) from 11 for 4) as numero,
                id_acquisition, 
                id_convention, 
                id_doc_gestion, 
                id_ore, 
                round((st_area(geom)/10000)::numeric , 2) as surface,
                id_unique as rowid
                FROM $parcelles ) As lp 
      ON lg.id_unique = lp.id_unique AND lp.id_site = '".$_POST["id_site"]."' ) As data )  As fc;
";

//execute la requete dans le moteur de base de donnees
$query_result = pg_query($dbconn,$sql) or die ( pg_last_error());
while($row = pg_fetch_row($query_result))
{
  echo trim($row[0]);
}
//ferme la connexion a la BD
pg_close($dbconn);

?>