<?php
include '../../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 
"
SELECT array_to_json(array_agg(row_to_json(t))) FROM 
(
SELECT 
    e_id,
	e_personne as personne, 
	'' as eligibilite,
	to_char(e_date_saisie::date, 'DD-MM-YYYY') as saisie,
	to_char(e_date_valide_panier::date, 'DD-MM-YYYY') as validation
	FROM $progecen_temps
	WHERE e_panier  is true 
	AND e_date_valide_panier is null
	order by 3 Desc, 2 Desc, 1 Asc
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