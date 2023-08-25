<?php

//echo copy("/var/www/html/nextcloud/data/8ACA0A1C-6E6E-4912-8511-DB8A02F1CA67/files/QGIS/Saisie_Libre/saisie_libre.gpkg",
//"/var/www/html/majiic/export/saisie_libre.gpkg");

//exec('ogr2ogr -f PostgreSQL "PG:host=192.168.0.218 user=postgres password=postgres dbname=test" saisie_libre.gpkg point');


exec('ogr2ogr -f PostgreSQL "PG:host=192.168.0.218 user=postgres password=postgres dbname=test" /var/www/html/nextcloud/data/8ACA0A1C-6E6E-4912-8511-DB8A02F1CA67/files/QGIS/Saisie_Libre/saisie_libre.gpkg point');


?>