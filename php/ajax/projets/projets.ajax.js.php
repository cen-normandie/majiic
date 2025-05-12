<?php
include '../../properties.php';
$year_ = date("Y");

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 



//array_to_json(array_agg(f)) As features
// 12-05-2025 : sum(a.nb_h_previ) as previ,
//a.nb_h_previ as previ
"
with 
json_actions as 
(select json_agg(acts)::text as json_actions, p.id_projet FROM 
     progecen_copy.projets p left join
      (SELECT
         a.id_action as id_action,
         a.id_projet,
         a.code_action,
         a.financements,
         a.site,
         a.personnes as personne_action,
         a.nb_h_previ as previ,
         coalesce(sum(t.realise), 0) as realise,
         p2.color,
         p2.financement_default as financement
     FROM $progecen_projets p2 left join 
         $progecen_actions a on p2.id_projet = a.id_projet left join 
         $progecen_vue_tps_a t on 
         (t.e_id_action = a.id_action::text AND t.e_id_projet::integer = p2.id_projet) WHERE 1 = 1
   GROUP BY 1,2,3,4,5,6,9,10
     ORDER BY 3
   ) acts on p.id_projet = acts.id_projet
 group by 2 order by 2
    ),
t as (
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
 p.financement_default as financement,
 p.sites,
 p.files,
 j.json_actions
 FROM $progecen_projets p left join json_actions j on p.id_projet = j.id_projet
  WHERE  (date_fin > to_date( $1::text||'0101', 'YYYYMMDD') OR (date_fin > to_date( '2024'::text||'0101', 'YYYYMMDD') ) )
   )
SELECT json_agg(t) FROM t;
"
);


//p.etat <> 'Réalisé'  AND


$result = pg_execute($dbconn, "sql", array($year_));
while($row = pg_fetch_row($result))
{
  echo trim($row[0]);
}
pg_close($dbconn);
?>
