<?php
session_start();
include '../../properties.php';

$user = $_SESSION['u_nom_user_progecen'];//$_SESSION['u_id_progecen'];

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 

//array_to_json(array_agg(f)) As features
"
WITH t as (
  SELECT 
  p.id_projet as id, 
  p.nom_projet as name, 
  p.color,
  a.id_action as id_action,
  a.code_action, 
  a.financements, 
  a.site, 
  $1::text as personne, 
  a.nb_h_real, 
  a.id_bdd
  FROM $progecen_actions a
    LEFT JOIN $progecen_projets p on a.id_projet = p.id_projet 
  WHERE personnes ~* $1
)
SELECT json_agg(t) FROM t
"
);

$result = pg_execute($dbconn, "sql", array($user));
while($row = pg_fetch_row($result))
{
  echo trim($row[0]);
}
pg_close($dbconn);
?>