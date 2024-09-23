<?php
include 'properties.php';
require '../vendor/autoload.php';

$content = $_POST["json"];
$content_p = $_POST["json_parcelle"];

$file = dirname(__FILE__).DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'export_sites.geojson';
$file_p = dirname(__FILE__).DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'export_parcelles.geojson';
file_put_contents($file, $content);
file_put_contents($file_p, $content_p);

exec('rm '.dirname(__FILE__).DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'export_sites.gpkg ');
exec('ogr2ogr -f GPKG '.dirname(__FILE__).DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'export_sites.gpkg '.dirname(__FILE__).DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'export_sites.geojson -nln sites ');
//exec('ogr2ogr -f GPKG '.dirname(__FILE__).DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'export_sites.gpkg '.dirname(__FILE__).DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'export_parcelles.geojson -nln parcelles -update ');
echo 'export_sites.gpkg';

/* header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary"); 
header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
readfile($file_url);  */
?>
