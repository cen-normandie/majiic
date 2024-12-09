<?php
include '../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST_FG port=$PORT_FG dbname=$DBNAME_FG user=$LOGIN_FG password=$PASS_FG")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 
"
with 
t as (
SELECT 
id, 
cd_hab, 
fg_validite, 
cd_typo, 
lb_code, 
lb_hab_fr, 
lb_hab_fr_complet, 
lb_hab_en, 
lb_auteur, 
niveau, 
lb_niveau	
	FROM $inpn_habref where lb_hab_fr is not null
    and cd_typo IN ('7','107','100','28')
    and (niveau >= 3)
UNION
SELECT 
id, 
cd_hab, 
fg_validite, 
cd_typo, 
lb_code, 
lb_hab_fr, 
lb_hab_fr_complet, 
lb_hab_en, 
lb_auteur, 
niveau, 
lb_niveau	
	FROM $inpn_habref where lb_hab_fr is not null
    and cd_typo ='28'
    and (niveau >= 2)
order by cd_typo
   )
SELECT json_agg(t) FROM t;
"
);



$result = pg_execute($dbconn, "sql", array());
while($row = pg_fetch_row($result))
{
  echo trim($row[0]);
}
pg_close($dbconn);
?>
