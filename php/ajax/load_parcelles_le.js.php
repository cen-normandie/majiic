<?php
include '../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 

//array_to_json(array_agg(f)) As features
"
SELECT json_agg(
    json_build_object(
        'geojson', json_build_object(
            'type', 'Feature',
            'geometry', ST_AsGeoJSON(ST_Transform(lg.geom, 4326))::json,
            'properties', json_build_object(
                'uuid', lg.uuid,
                'numerisateur', lg.utilisateur,
                'nom_site', lg.nom_site
            )
        )
    )
) AS data
FROM $parcelles_le AS lg
WHERE lg.geom IS NOT NULL;

"
);



$result = pg_execute($dbconn, "sql", array());
while($row = pg_fetch_row($result))
{
  echo trim($row[0]);
}
pg_close($dbconn);
?>
