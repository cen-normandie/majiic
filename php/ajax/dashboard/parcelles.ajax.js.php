<?php
include '../../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 
"
WITH 
f_ as (
      SELECT 'Feature' As type
          , ST_AsGeoJSON( st_transform( coalesce(lg.geom_pp, lg.geom) ,4326) )::json As geometry
          , row_to_json(lp) As properties
  		, lp.id_unique
          FROM $parcelles As lg 
                  INNER JOIN (
                      SELECT 
                          g.id_unique, 
                          g.nom_group,
  					round( (st_area( coalesce(g.geom_pp, g.geom) )/10000)::numeric,2) as surface
                          FROM $parcelles g
                          WHERE g.geom is not null
                          AND g.categorie_site = $1 and date_fin is null 
                          ) As lp 
              ON lg.id_unique = lp.id_unique  
  ),
t as (
  SELECT 
  p.id_unique,
  s.id_site as id,
  s.nom_site,
  round( (st_area(coalesce(p.geom_pp, p.geom))/10000)::numeric,2) as surface,
  s.zh as is_zh,
  s.is_aesn,
  s.is_ddg,
  s.ens as is_ens,
  CASE WHEN p.id_acquisition <> 'ø' THEN 1 ELSE 0 END AS is_acquisition,
  CASE WHEN p.id_convention <> 'ø' THEN 1 ELSE 0 END AS is_convention,
  CASE WHEN p.id_bail_emphy <> 'ø' THEN 1 ELSE 0 END AS is_bail_e,
  CASE WHEN p.id_pret_a_usage <> 'ø' THEN 1 ELSE 0 END AS is_pau,
  CASE WHEN p.id_ore <> 'ø' THEN 1 ELSE 0 END AS is_ore,
  CASE WHEN p.id_bail_rural <> 'ø' THEN 1 ELSE 0 END AS is_bail_r,
  s.mc as is_mc,
  s.dep as dep,
  s.statuts_protection,
  s.bassin,
  s.ucg,
  p.doc_reference,
  p.id_doc_gestion,
  (
    	    SELECT row_to_json(fc) as geojson
            FROM ( SELECT 'FeatureCollection' As type, array_to_json(array_agg(f)) As features
            FROM ( select type,geometry,properties  from f_ where f_.id_unique = p.id_unique
    			) As f )  As fc
      )
  FROM $parcelles p LEFT JOIN  $sites s ON p.id_group = s.id_site 
  WHERE p.categorie_site = $1 AND date_fin is null 
  order by 1
)
SELECT json_agg(t) FROM t
"
);
//y.date_,
//LEFT JOIN $doc_annee y ON y.doc_reference = p.doc_reference


$result = pg_execute($dbconn, "sql", array('1'));
while($row = pg_fetch_row($result))
{
  echo trim($row[0]);
}
pg_close($dbconn);
?>
