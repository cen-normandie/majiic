<?php
session_start();
include '../properties.php';


//connexion a la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$dep=substr($_POST["id"], 0, 2);
$com=substr($_POST["id"], 0, 5);
//select array_to_json(array_agg(row_to_json(t)))
//    from (
//      select idpar, ddenom, adresse_bien, nom_usage, prenom_usage, date_naissance, 
//       lieu_naissance, geom, idpk from d".$dep."_p_na
//      where 
//      idpar ='".$_POST["id"]."'
//    ) t

//2017 --> 2018
//dna.codnom --> dna.catpro3txt, --> DELETE
//dna.codgrmtxt --> dna.catpro2txt,
//dp.typproptxt --> dp.catpro3txt
$sql = "
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
dna.catpro2txt,
dna.catpro3txt as typproptxt,
dna.ddenom,
round((dp.ssuf::numeric /10000)::numeric,4) as ssuf
FROM 
$proprietaire_cad dna
LEFT JOIN $parcelles_cad dp
ON dp.idprocpte = dna.idprocpte
WHERE 
dna.idcom = '".$com."' AND
dp.idpar = '".$_POST["id"]."'
AND dp.idprocpte IS NOT NULL
AND dna.idprocpte IS NOT NULL
    ) t
";
//echo $sql;
;

$query_result = pg_exec($dbconn,$sql) or die (pg_last_error());
while($row = pg_fetch_row($query_result))
{
  echo trim($row[0]);
}
//ferme la connexion a la BD
pg_close($dbconn);

?>
