<?php
session_start();
include '../properties.php';


//connexion a la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$dep=substr($_POST["id"], 0, 2);
$com=substr($_POST["id"], 0, 5);


$sql = pg_prepare($dbconn, "get_data", "
select array_to_json(array_agg(row_to_json(t)))
    from (
SELECT 
dna.dlign4 as adresse_prop,
dna.dlign6 as cp_prop,
dna.dqualp as titre,
dna.dnomus as nom_usage,
dna.dprnus as prenom_usage,
dna.jdatnss as date_naissance,
dp.idpar as idpar, 
dna.codnom,
dna.codgrmtxt,
dp.typproptxt,
dna.ddenom,
dp.typproptxt,
round((dp.ssuf::numeric /10000)::numeric,4) as ssuf
FROM  
ff_prop_non_anonyme.d".$dep."_2017_proprietaire_droit_non_ano dna
LEFT JOIN ff_d".$dep."_2017.d".$dep."_2017_pnb10_parcelle dp
ON dp.idprocpte = dna.idprocpte
WHERE 
dna.idcom = $1 AND
dp.idpar = $2
AND dp.idprocpte IS NOT NULL
AND dna.idprocpte IS NOT NULL
    ) t
");


$qout = pg_execute($dbconn,"get_data",array($com, $_POST["id"])) or die (pg_last_error());
while($row = pg_fetch_row($qout))
{
  echo trim($row[0]);
}
//ferme la connexion a la BD
pg_close($dbconn);

?>
