<?php
include '../../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 



//array_to_json(array_agg(f)) As features
"
WITH t as (
  SELECT 
  p.id_projet as id, 
  p.nom_projet as name, 
  'projet' as tablename,
  p.territoire, 
  p.type_projet, 
  p.date_debut, 
  p.date_fin, 
  p.etat, 
  p.responsable_projet, 
  p.multi_site, 
  p.nombre_financeur, 
  p.commentaire_projet, 
  p.annee_saisie, 
  p.date_demande_solde, 
  p.date_butoir_dossier, 
  p.tags,
  p.color,
  p.sites,
  (select json_agg(acts) FROM 
   	(SELECT
          a.id_action as id_action,
          a.id_projet,
          a.code_action,
          a.financements,
          a.site,
          a.personnes as personne_action,
          sum(a.nb_h_previ) as previ,
          coalesce(sum(t.realise), 0) as realise,
          p.color
	  FROM $progecen_projets p2 left join 
          $progecen_actions a on p2.id_projet = a.id_projet left join 
          $progecen_vue_tps_a t on t.e_id_action = a.id_action::text
	  WHERE p.id_projet = p2.id_projet
    GROUP BY 1,2,3,4,5,6,9
	  ORDER BY 3
	) acts
  )::text as json_actions
  FROM $progecen_projets p
)
SELECT json_agg(t) FROM t
"
);


$result = pg_execute($dbconn, "sql", array());
while($row = pg_fetch_row($result))
{
  echo trim($row[0]);
}
pg_close($dbconn);
?>
