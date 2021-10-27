<?php
include '../../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 
"
WITH t as (
  SELECT 
  p.id_unique,
  s.id_site as id,
  s.nom_site,
  round( (st_area(p.geom)/10000)::numeric,2) as surface,
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
  y.date_,
  p.doc_reference,
  (
    SELECT row_to_json(fc) as geojson
      FROM ( SELECT 'FeatureCollection' As type, array_to_json(array_agg(f)) As features
      FROM (SELECT 'Feature' As type
      , ST_AsGeoJSON( st_transform(lg.geom,4326) )::json As geometry
      , row_to_json(lp) As properties
      FROM $parcelles As lg 
              INNER JOIN (
                  SELECT 
                      g.id_unique
                      FROM $parcelles g
                      WHERE g.geom is not null
          AND p.id_unique = g.id_unique
                      ) As lp 
          ON lg.id_unique = lp.id_unique  ) As f )  As fc
)


  FROM $parcelles p LEFT JOIN  $sites s ON p.id_group = s.id_site 
  LEFT JOIN $doc_annee y ON y.doc_reference = p.doc_reference
  order by 1
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
