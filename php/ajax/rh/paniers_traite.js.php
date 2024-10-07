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
	to_char(p.date_du_panier::date, 'DD-MM-YYYY') as date_du_panier,
	e_commentaire as commentaire,
	to_char(p.date_validation_rh::date, 'DD-MM-YYYY') as validation_rh
	FROM $paniers_valide p left join $progecen_personnes_ m ON p.e_personne = m.personne
	WHERE 
	date_trunc('year', date_du_panier) = date_trunc('year', now())
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