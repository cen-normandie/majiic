<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

// Vérifier le token CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
    $_SESSION['error'] = "Token CSRF invalide.";
    header('Location: /extraction/index.php');
    exit;
}

// Vérifier les données du formulaire
$geometry_wgs84 = trim($_POST['geometry_wgs84'] ?? '');
$stored_data_id = trim($_POST['stored_data_id'] ?? '');
$layers = $_POST['layers'] ?? [];
$format = $_POST['format'] ?? DEFAULT_FORMAT;
$srs = $_POST['srs'] ?? DEFAULT_SRS;

if (empty($geometry_wgs84)) {
    $_SESSION['error'] = "Veuillez sélectionner une géométrie.";
    header('Location: /extraction/index.php');
    exit;
}

if (empty($stored_data_id)) {
    $_SESSION['error'] = "Veuillez sélectionner une donnée source.";
    header('Location: /extraction/index.php');
    exit;
}

// Construire la liste des couches sélectionnées
$selected_layers = [];
foreach ($layers as $key => $layer_data) {
    if (is_array($layer_data) && isset($layer_data['selected']) && $layer_data['selected'] == '1') {
        $selected_layers[] = [
            'table' => $layer_data['table'],
            'attributes' => explode(',', $layer_data['attributes'])
        ];
    }
}

if (empty($selected_layers)) {
    $_SESSION['error'] = "Veuillez sélectionner au moins une couche.";
    header('Location: /extraction/index.php');
    exit;
}

// Lancer l'extraction
$result = startExtraction($stored_data_id, $selected_layers, $geometry_wgs84, $format, $srs);

if (isset($result['error'])) {
    $_SESSION['error'] = $result['error'];
    header('Location: /extraction/index.php');
    exit;
} else {
    // Initialiser l'historique si inexistant
    if (!isset($_SESSION['extraction_jobs'])) {
        $_SESSION['extraction_jobs'] = [];
    }

    // Ajouter le job à l'historique
    $_SESSION['extraction_jobs'][] = [
        'jobID' => $result['jobID'],
        'status' => $result['status'],
        'timestamp' => time()
    ];

    // Rediriger vers la page de vérification
    header('Location: /extraction/check_job.php?jobID=' . $result['jobID']);
    exit;
}
?>