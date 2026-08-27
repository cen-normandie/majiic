<?php
require_once __DIR__ . '/config.php';

// === Fonction pour lancer une extraction ===
function startExtraction($stored_data_id, $layers, $geometry_wgs84, $format = DEFAULT_FORMAT, $srs = DEFAULT_SRS) {
    if (empty(GEOPLATEFORME_API_KEY)) return ['error' => 'La clé API HASH n\'est pas configurée.'];

    $process_id = '8ab6236b-21d8-471a-a07b-f84a5921f9f5_' . $stored_data_id;
    $relations = [];
    foreach ($layers as $key => $layer) {
        if (isset($layer['selected']) && $layer['selected']) {
            $relations[$layer['table']] = [
                'attributes' => $layer['attributes'],
                'filters' => "ST_Intersects(geom, ST_Transform(ST_SetSRID('$geometry_wgs84'::geometry, 4326), " . str_replace('EPSG:', '', $srs) . "))"
            ];
        }
    }
    if (empty($relations)) return ['error' => 'Aucune couche sélectionnée.'];

    $body = [
        'inputs' => [
            'relations' => $relations,
            'format' => $format,
            'srs' => $srs,
            'lifetime' => DEFAULT_LIFETIME,
            'append' => true,
            'compression' => '7zip'
        ],
        'outputs' => ['logs' => new stdClass(), 'summary' => new stdClass(), 'extractedData' => new stdClass()]
    ];

    // Note : On ajoute la clé API directement dans les paramètres d'URL pour l'API de traitement
    $url = GEOPLATEFORME_EXTRACTION_URL . "/processes/$process_id/execution?apikey=" . GEOPLATEFORME_API_KEY;

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($body),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Referer: ' . GEOPLATEFORME_REFERER
        ]
    ]);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $http_code === 200 ? json_decode($response, true) : ['error' => "Erreur $http_code: $response"];
}

// === Fonction pour vérifier le statut du traitement ===
function checkJobStatus($job_id) {
    if (empty(GEOPLATEFORME_API_KEY)) return ['error' => 'La clé API HASH n\'est pas configurée.'];

    $url = GEOPLATEFORME_EXTRACTION_URL . "/jobs/$job_id?apikey=" . GEOPLATEFORME_API_KEY;

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Referer: ' . GEOPLATEFORME_REFERER
        ]
    ]);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $http_code === 200 ? json_decode($response, true) : ['error' => "Erreur $http_code"];
}

// === Fonction pour obtenir le lien de téléchargement ===
function getDownloadLink($job_id) {
    if (empty(GEOPLATEFORME_API_KEY)) return ['error' => 'La clé API HASH n\'est pas configurée.'];

    $url = GEOPLATEFORME_EXTRACTION_URL . "/jobs/$job_id/results?apikey=" . GEOPLATEFORME_API_KEY;

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Referer: ' . GEOPLATEFORME_REFERER
        ]
    ]);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $http_code === 200 ? ['download_url' => json_decode($response, true)['extractData']['href'] ?? null] : ['error' => "Erreur $http_code"];
}

// === Fonction pour télécharger physiquement le fichier ===
function downloadFile($url, $output_path) {
    // Si l'URL de téléchargement renvoyée par l'IGN ne contient pas déjà la clé, on l'ajoute
    if (!strpos($url, 'apikey=')) {
        $separator = (strpos($url, '?') === false) ? '?' : '&';
        $url .= $separator . "apikey=" . GEOPLATEFORME_API_KEY;
    }

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true
    ]);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code === 200) {
        return file_put_contents($output_path, $response) !== false;
    }
    return false;
}
?>