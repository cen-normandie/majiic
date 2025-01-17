<?php
include '../../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 

//array_to_json(array_agg(f)) As features
"
WITH da_ as (
	select id_site, doc_reference, sum(sum) as ha, date_::text as annee from $dates_ group by 1,2,4
	order by annee
),
foncier as (
  SELECT s_.id_site, JSON_AGG(da_.*) as evol
        FROM $sites s_ left join da_ on da_.id_site = s_.id_site
      group by 1
      order by 1
),
t as (
  SELECT 
  s.id_site as id, 
  s.nom_site as name, 
  'site' as tablename,
  s.doc_reference,
  s.lien_doc,
  s.nb_parc,
  s.ens,
  s.code_milieu_princ,
  s.date_crea_site,
  s.gestionnaire_site,
  s.doc_gestion_presence,
  s.doc_justif_admin,
  CASE WHEN s.zh THEN 1 ELSE 0 END as is_zh,
  s.is_aesn,
  s.is_ddg,
  s.ens as is_ens,
  s.is_acquisition,
  s.is_convention,
  s.mc as is_mc,
  s.is_bail_e,
  s.is_bail_r,
  s.is_pau,
  s.is_ore,
  s.dep as dep,
  s.statuts_protection,
  s.bassin,
  s.ucg,
  round( (st_area( coalesce(s.geom_pp, s.geom) )/10000)::numeric,2) as surface,
  coalesce(d.autres_docs, '') as autres_docs,
  (select evol from foncier where s.id_site = foncier.id_site ) as evolution,
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
						            AND s.id_site = g.id_site
                        AND s.categorie_site = $1
                        ) As lp 
            ON lg.id_site = lp.id_site  ) As f )  As fc
  )
  FROM $sites s left join $sites_data as d on d.id_site = s.id_site
  WHERE s.categorie_site = $1
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


$result = pg_execute($dbconn, "sql", array('1'));
while($row = pg_fetch_row($result))
{
  echo trim($row[0]);
}
pg_close($dbconn);
?>
