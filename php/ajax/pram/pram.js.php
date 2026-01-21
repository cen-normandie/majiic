<?php
include '../../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOSTPRAM port=$PORTPRAM dbname=$DBNAMEPRAM user=$LOGINPRAM password=$PASSPRAM")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 
"
SELECT array_to_json(array_agg(row_to_json(t))) FROM 
(
select mail_u, nom_u, profil_u, id_observateur_pram, id_structure_pram, nom_structure_pram
from $spop_users
) t
"
);


$result = pg_execute($dbconn, "sql", array()) or die ('Connexion impossible :'. pg_last_error());
while($row = pg_fetch_row($result))
{
  echo trim($row[0]);
}
pg_close($dbconn);
?>