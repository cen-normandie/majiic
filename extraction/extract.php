<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['email']) || !isset($_SESSION['password'])) {
    header('Location: /index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /extraction/index.php');
    exit;
}

$geometry_wgs84 = $_POST['geometry_wgs84'] ?? '';
$stored_data_id = $_POST['stored_data_id'] ?? '';
$layers = $_POST['layers'] ?? [];
$format = $_POST['format'] ?? DEFAULT_FORMAT;
$srs = $_POST['srs'] ?? DEFAULT_SRS;

if (empty($geometry_wgs84) || empty($stored_data_id)) {
    $_SESSION['error'] = "Veuillez sélectionner une géométrie et une donnée source.";
    header('Location: /extraction/index.php');
    exit;
}

$selected_layers = [];
foreach ($layers as $key => $layer) {
    if (isset($layer['selected']) && $layer['selected']) {
        $selected_layers[$key] = [
            'table' => $layer['table'],
            'attributes' => explode(',', $layer['attributes'])
        ];
    }
}

if (empty($selected_layers)) {
    $_SESSION['error'] = "Veuillez sélectionner au moins une couche.";
    header('Location: /extraction/index.php');
    exit;
}

$result = startExtraction($stored_data_id, $selected_layers, $geometry_wgs84, $format, $srs);
if (isset($result['error'])) {
    $_SESSION['error'] = $result['error'];
    header('Location: /extraction/index.php');
    exit;
} else {
    if (!isset($_SESSION['extraction_jobs'])) $_SESSION['extraction_jobs'] = [];
    $_SESSION['extraction_jobs'][] = [
        'jobID' => $result['jobID'],
        'status' => $result['status'],
        'timestamp' => time()
    ];
    header('Location: /extraction/check_job.php?jobID=' . $result['jobID']);
    exit;
}
?>