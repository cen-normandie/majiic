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
	p.e_personne as personne, 
	to_char(p.date_de_prime::date, 'DD-MM-YYYY') as date_de_prime,
	e_commentaire as commentaire,
	to_char(p.date_validation_rh::date, 'DD-MM-YYYY') as validation_rh
	FROM $primes_valide p
	WHERE date_de_prime > to_date('2023', 'YYYY')
	order by 5 Desc, 2 Asc
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