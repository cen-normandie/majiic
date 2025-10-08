<?php
include '../../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 



//array_to_json(array_agg(f)) As features
"
with 
t as (
with sums as (
select 
	t1.e_start::date as date_ , 
	sum(t1.e_nb_h) as som, 
	e_id_projet,
  e_nom_projet,  
	e_personne ,
	extract('month' from t1.e_start::date)::int as month_,
	string_agg(e_id,'|') as e_ids
	FROM $progecen_temps t1 
	where 
	1=1
	and t1.e_id_projet = $1
	group by 1,3,4,5
	order by 4,5,1
)
SELECT 
	e_personne as personne,
	e_id_projet as id_projet,
  e_nom_projet as nom_projet,
	date_ as date,
	e_ids as e_ids,
     SUM(CASE extract('month' from date_)::int WHEN 1 THEN som ELSE 0 END) AS janvier,  
     SUM(CASE extract('month' from date_)::int WHEN 2 THEN som ELSE 0 END) AS fevrier,
	   SUM(CASE extract('month' from date_)::int WHEN 3 THEN som ELSE 0 END) AS mars,
	   SUM(CASE extract('month' from date_)::int WHEN 4 THEN som ELSE 0 END) AS avril,
	   SUM(CASE extract('month' from date_)::int WHEN 5 THEN som ELSE 0 END) AS mai,
	   SUM(CASE extract('month' from date_)::int WHEN 6 THEN som ELSE 0 END) AS juin,
	   SUM(CASE extract('month' from date_)::int WHEN 7 THEN som ELSE 0 END) AS juillet,
	   SUM(CASE extract('month' from date_)::int WHEN 8 THEN som ELSE 0 END) AS aout,
	   SUM(CASE extract('month' from date_)::int WHEN 9 THEN som ELSE 0 END) AS septembre,
	   SUM(CASE extract('month' from date_)::int WHEN 10 THEN som ELSE 0 END) AS octobre,
	   SUM(CASE extract('month' from date_)::int WHEN 11 THEN som ELSE 0 END) AS novembre,
	   SUM(CASE extract('month' from date_)::int WHEN 12 THEN som ELSE 0 END) AS decembre
FROM  sums
GROUP BY 1,2,3,4,5
order by 1,3 
   )
SELECT json_agg(t) FROM t;
"
);

$result = pg_execute($dbconn, "sql", array($_POST['projet_param']));
while($row = pg_fetch_row($result))
{
  echo trim($row[0]);
}
pg_close($dbconn);
?>
