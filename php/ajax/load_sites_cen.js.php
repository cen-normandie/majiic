<?php
include '../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());

$result = pg_prepare($dbconn, "sql", 
"
SELECT json_agg(
    json_build_object(
        'geojson', json_build_object(
            'type', 'Feature',
            'geometry', ST_AsGeoJSON(ST_Transform(s.geom, 4326))::json,
            'properties', json_build_object(
                'id_site', s.id_site,
                'nom_site', s.nom_site
            )
        )
    )
) AS data
FROM $sites AS s
WHERE s.geom IS NOT NULL
  AND s.categorie_site = $1;
"
);

$result = pg_execute($dbconn, "sql", array('1'));
while($row = pg_fetch_row($result))
{
  echo trim($row[0]);
}
pg_close($dbconn);
?>