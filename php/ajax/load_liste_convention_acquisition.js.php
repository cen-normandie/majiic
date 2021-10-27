<?php
include '../properties.php';
$arr = array();

//connexion a la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql1", "with values_ as (select 
id_acquisition as id,
id_acquisition as nom
from $acquisitions
UNION
select 
id_convention as id,
nom_convention as nom
from $conventions
UNION
select
id_ore as id,
nom_ore as nom
from $ore
UNION
select
id_pret as id,
nom_pret as nom
from $prets_a_u
UNION
select
id_bail as id,
nom_bail as nom
from $baux_ruraux
UNION
select
id_bail_e as id,
nom_bail_e as nom
from $bail_e
) 
select id, nom from values_ WHERE id ~* $1 or nom ~* $1 order by 1");
$result = pg_execute($dbconn, "sql1", array($_POST["term"]));
while($row = pg_fetch_row($result))
{
  $arr[]=trim($row[0])." - ".trim($row[1]);
}


//ferme la connexion a la BD
pg_close($dbconn);
echo json_encode($arr);
//echo $arr;
?>
