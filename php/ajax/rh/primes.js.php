<?php
include '../../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 
"
SELECT array_to_json(array_agg(row_to_json(t))) FROM 
(
SELECT 
	p.e_id,
	m.p_nom_prenom as personne, 
	to_char(p.e_start::date, 'DD-MM-YYYY') as date_prime,
    to_char(p.e_date_saisie::date, 'DD-MM-YYYY') as saisie,
	p.e_personne as prenom
	FROM $progecen_temps p left join $progecen_personnes_ m ON p.e_personne = m.personne
	WHERE p.e_salissure  is true 
	AND p.e_start > date_trunc('year', now()) - interval '1 year'
	AND p.e_date_valide_salissure is null
	AND not exists (
		select v.date_de_prime from $primes_valide v
		where v.e_personne = p.e_personne AND v.date_de_prime::date = p.e_start::date 
		)
	order by 3 Desc, 2 Desc, 1 Asc
) t
"
);
//	to_char(p.e_date_valide_salissure::date, 'DD-MM-YYYY') as validation
$result = pg_execute($dbconn, "sql", array()) or die ('Connexion impossible :'. pg_last_error());
while($row = pg_fetch_row($result))
{
  echo trim($row[0]);
}
pg_close($dbconn);
?>