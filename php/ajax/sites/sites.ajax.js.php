<?php
include '../../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 


/* SELECT array_to_json(array_agg(  row_to_json(t) )) FROM 
(
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
  is_aesn,
  right(id_site,2) as dep
  FROM $sites order by 1
) t */


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
  round( (st_area(geom)/10000)::numeric,2) as surface,
  (
	    SELECT row_to_json(fc) as geojson
        FROM ( SELECT 'FeatureCollection' As type, array_to_json(array_agg(f)) As features
        FROM (SELECT 'Feature' As type
        , ST_AsGeoJSON( st_transform(lg.geom,4326) )::json As geometry
        , row_to_json(lp) As properties
        FROM $sites As lg 
                INNER JOIN (
                    SELECT 
                        g.id_site, 
                        g.nom_site
                        FROM $sites g
                        WHERE g.geom is not null
						AND sites.id_site = g.id_site
                        ) As lp 
            ON lg.id_site = lp.id_site  ) As f )  As fc
  )
  FROM $sites order by 1
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
