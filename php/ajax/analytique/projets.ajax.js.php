<?php
include '../../properties.php';
$year_ = date("Y");

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 


//array_to_json(array_agg(f)) As features
"
WITH t as (
  SELECT 
  id_projet as id, 
  nom_projet as name, 
  'projet' as tablename,
  territoire, 
  type_projet, 
  date_debut, 
  date_fin, 
  etat, 
  responsable_projet, 
  multi_site, 
  nombre_financeur, 
  commentaire_projet, 
  annee_saisie, 
  date_demande_solde, 
  date_butoir_dossier, 
  tags,
  color
  FROM $progecen_projets
  WHERE etat <> 'Réalisé'
  AND (date_fin > to_date( $1::text||'0101', 'YYYYMMDD') OR (date_fin > to_date( '2024'::text||'0101', 'YYYYMMDD') ) )
)
SELECT json_agg(t) FROM t
"
);

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

$result = pg_execute($dbconn, "sql", array($year_));
while($row = pg_fetch_row($result))
{
  echo trim($row[0]);
}
pg_close($dbconn);
?>
