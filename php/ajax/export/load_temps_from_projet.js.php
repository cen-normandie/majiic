<?php
include '../../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 



//array_to_json(array_agg(f)) As features
"
with 
t as (
SELECT 
e_id, 
e_id_projet, 
e_nom_projet, 
e_id_action, 
e_nom_action, 
e_id_site, 
e_objet, 
to_char(e_start, 'DD-MM-YYYY HH24:MI') as e_start, 
to_char(e_end, 'DD-MM-YYYY HH24:MI') as e_end,
e_lieu,
e_commentaire, 
e_personne, 
e_nb_h,
e_blocked
 FROM $progecen_temps 
  WHERE e_id_projet = $1
   )
SELECT json_agg(t) FROM t;
"
);

$result = pg_execute($dbconn, "sql", array($_POST['projet_param']));
while($row = pg_fetch_row($result))
{
  echo trim($row[0]);
}
pg_close($dbconn);
?>
