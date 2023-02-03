<?php
include 'properties.php';
require '../vendor/autoload.php';

$content = $_POST["json"];

$file = dirname(__FILE__).DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'export_sites.geojson';
file_put_contents($file, $content);
exec('ogr2ogr -f GPKG '.dirname(__FILE__).DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'export_sites.gpkg '.dirname(__FILE__).DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'export_sites.geojson -nln sites ');
//echo 'ogr2ogr -f GPKG '.dirname(__FILE__).DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'export_sites.gpkg '.dirname(__FILE__).DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'export_sites.geojson';
echo 'export_sites.gpkg';

/* header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary"); 
header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
readfile($file_url);  */
?>
