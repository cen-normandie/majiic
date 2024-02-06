<?php
include '../../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 

//array_to_json(array_agg(f)) As features
"
WITH t as (
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
  s.id_doc_gestion,
  round( (st_area( coalesce(s.geom_pp, s.geom) )/10000)::numeric,2) as surface,
  d.autres_docs,
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
                        g.nom_site
                        FROM $sites g
                        WHERE coalesce(g.geom_pp, g.geom) is not null
						            AND s.id_site = g.id_site
                        AND g.categorie_site = '1'
                        ) As lp 
            ON lg.id_site = lp.id_site  ) As f )  As fc
  )
  FROM $sites s left join $sites_data as d on d.id_site = s.id_site order by 1
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
