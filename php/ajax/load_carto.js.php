<?php
include '../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST_FG port=$PORT_FG dbname=$DBNAME_FG user=$LOGIN_FG password=$PASS_FG")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 

//array_to_json(array_agg(f)) As features
"
WITH t as (
  SELECT
  id, typo, code, lb, 
  (
	    SELECT row_to_json(fc) as geojson
        FROM ( SELECT 'FeatureCollection' As type, array_to_json(array_agg(f)) As features
        FROM (SELECT 'Feature' As type
        , ST_AsGeoJSON( st_transform( lg.geom ,4326) )::json As geometry
        , row_to_json(lp) As properties
        FROM $tmp As lg 
                INNER JOIN (
                    SELECT 
                        g.id, 
                        g.typo, 
                        g.code, 
                        g.lb,
                        g.annee_saisie::date,
                        g.observateur,
                        g.jdd_carto,
                        g.taxons
                        FROM $tmp g
                        WHERE g.geom is not null
						            AND s.id = g.id
                        ) As lp 
            ON lg.id = lp.id  ) As f )  As fc
  )
  FROM $tmp s
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
