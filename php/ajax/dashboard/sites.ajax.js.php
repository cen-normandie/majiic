<?php
include '../../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 

//array_to_json(array_agg(f)) As features
"
WITH t as (
  SELECT 
  id_site as id, 
  nom_site as name, 
  'site' as tablename,
  doc_reference,
  lien_doc,
  nb_parc,
  ens,
  code_milieu_princ,
  date_crea_site,
  gestionnaire_site,
  doc_gestion_presence,
  doc_justif_admin,
  CASE WHEN zh THEN 1 ELSE 0 END as is_zh,
  is_aesn,
  is_ddg,
  ens as is_ens,
  is_acquisition,
  is_convention,
  mc as is_mc,
  is_bail_e,
  is_bail_r,
  is_pau,
  is_ore,
  dep as dep,
  statuts_protection,
  bassin,
  ucg,
  round( (st_area( coalesce(geom_pp, geom) )/10000)::numeric,2) as surface,
  (
	    SELECT row_to_json(fc) as geojson
        FROM ( SELECT 'FeatureCollection' As type, array_to_json(array_agg(f)) As features
        FROM (SELECT 'Feature' As type
        , ST_AsGeoJSON( st_transform( coalesce(lg.geom_pp, lg.geom) ,4326) )::json As geometry
        , row_to_json(lp) As properties
        FROM $sites As lg 
                INNER JOIN (
                    SELECT 
                        g.id_site, 
                        g.nom_site,
                        g.doc_reference,
                        g.nb_parc,
                        round( (st_area( coalesce(g.geom_pp, g.geom) )/10000)::numeric,2) as surface
                        FROM $sites g
                        WHERE g.geom is not null
						            AND sites.id_site = g.id_site
                        ) As lp 
            ON lg.id_site = lp.id_site  ) As f )  As fc
  ),
  d.autres_docs
  FROM $sites left join $sites_data d on d.id_site = $sites.id_site
  order by 1
)
SELECT json_agg(t) FROM t
"
);

//REQUETE PLUS LONGUE
//WITH 
//f_ as (
//    SELECT 'Feature' As type
//        , ST_AsGeoJSON( st_transform( coalesce(lg.geom_pp, lg.geom) ,4326) )::json As geometry
//        , row_to_json(lp) As properties
//		, lp.id_site
//        FROM $sites As lg 
//                INNER JOIN (
//                    SELECT 
//                        g.id_site, 
//                        g.nom_site,
//					g.doc_reference,
//					g.nb_parc,
//					g.code_milieu_princ,
//					g.date_crea_site,
//					g.gestionnaire_site,
//					g.doc_gestion_presence,
//					g.is_aesn,
//					g.is_ddg,
//					g.ens as is_ens,
//					g.is_acquisition,
//					g.is_convention,
//					g.mc as is_mc,
//					round( (st_area( coalesce(g.geom_pp, g.geom) )/10000)::numeric,2) as surface
//                        FROM $sites g
//                        WHERE g.geom is not null
//                        ) As lp 
//            ON lg.id_site = lp.id_site  
//),
//t as (
//  SELECT 
//  id_site as id, 
//  nom_site as name, 
//  'site' as tablename,
//  doc_reference,
//  lien_doc,
//  nb_parc,
//  ens,
//  code_milieu_princ,
//  date_crea_site,
//  gestionnaire_site,
//  doc_gestion_presence,
//  doc_justif_admin,
//  CASE WHEN zh THEN 1 ELSE 0 END as is_zh,
//  is_aesn,
//  is_ddg,
//  ens as is_ens,
//  is_acquisition,
//  is_convention,
//  mc as is_mc,
//  is_bail_e,
//  is_bail_r,
//  is_pau,
//  is_ore,
//  dep as dep,
//  statuts_protection,
//  bassin,
//  ucg,
//  round( (st_area( coalesce(geom_pp, geom) )/10000)::numeric,2) as surface,
//  (
//	    SELECT row_to_json(fc) as geojson
//        FROM ( SELECT 'FeatureCollection' As type, array_to_json(array_agg(f)) As features
//        FROM ( select type,geometry,properties  from f_ where f_.id_site = sites.id_site
//			) As f )  As fc
//  )
//	FROM $sites order by 1
//)
//SELECT json_agg(t) FROM t


$result = pg_execute($dbconn, "sql", array());
while($row = pg_fetch_row($result))
{
  echo trim($row[0]);
}
pg_close($dbconn);
?>
