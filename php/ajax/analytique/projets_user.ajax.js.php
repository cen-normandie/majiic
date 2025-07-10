<?php
session_start();
include '../../properties.php';
$year_ = date("Y")-1;


$user = $_SESSION['u_nom_user_progecen'];//$_SESSION['u_id_progecen'];

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
  STRING_AGG(a.id_action||'_'||a.code_action||'_'||$1||'_'||a.nb_h_real , '|') as actions
  FROM $progecen_projets p
    LEFT JOIN $progecen_actions a on a.id_projet = p.id_projet 
    LEFT JOIN $progecen_group g on a.personnes = g.id_group
  WHERE 
  (p.etat <> 'Réalisé') AND 
  (
    ( a.personnes ~* $1 or g.personnes ~* $1 ) AND ((p.date_fin > to_date( $2::text||'0101', 'YYYYMMDD')))
  )
  group by 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17
)
SELECT json_agg(t) FROM t
"
);
//SELECTIOn UNIQUEMENT 2025 --> AND p.date_debut::text like '%2025%'


// ADD actions du projet
//,
//  round( (st_area(geom)/10000)::numeric,2) as surface,
//  (
//	    SELECT row_to_json(fc) as geojson
//        FROM ( SELECT 'FeatureCollection' As type, array_to_json(array_agg(f)) As features
//        FROM (SELECT 'Feature' As type
//        , ST_AsGeoJSON( st_transform(lg.geom,4326) )::json As geometry
//        , row_to_json(lp) As properties
//        FROM $sites As lg 
//                INNER JOIN (
//                    SELECT 
//                        g.id_site, 
//                        g.nom_site
//                        FROM $sites g
//                        WHERE g.geom is not null
//						AND sites.id_site = g.id_site
//                        ) As lp 
//            ON lg.id_site = lp.id_site  ) As f )  As fc
//  )
//  FROM $sites order by 1


// ADD personnes rattachées au projet
//,
//  round( (st_area(geom)/10000)::numeric,2) as surface,
//  (
//	    SELECT row_to_json(fc) as geojson
//        FROM ( SELECT 'FeatureCollection' As type, array_to_json(array_agg(f)) As features
//        FROM (SELECT 'Feature' As type
//        , ST_AsGeoJSON( st_transform(lg.geom,4326) )::json As geometry
//        , row_to_json(lp) As properties
//        FROM $sites As lg 
//                INNER JOIN (
//                    SELECT 
//                        g.id_site, 
//                        g.nom_site
//                        FROM $sites g
//                        WHERE g.geom is not null
//						AND sites.id_site = g.id_site
//                        ) As lp 
//            ON lg.id_site = lp.id_site  ) As f )  As fc
//  )
//  FROM $sites order by 1

$result = pg_execute($dbconn, "sql", array($user,$year_));
while($row = pg_fetch_row($result))
{
  echo trim($row[0]);
}
pg_close($dbconn);
?>
