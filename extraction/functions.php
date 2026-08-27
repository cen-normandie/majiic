<?php
require_once __DIR__ . '/config.php';

// === Fonction pour lancer une extraction ===
function startExtraction($stored_data_id, $layers, $geometry_wgs84, $format = DEFAULT_FORMAT, $srs = DEFAULT_SRS) {
    if (empty(GEOPLATEFORME_API_KEY)) return ['error' => 'La clé API HASH n\'est pas configurée.'];

    $process_id = '8ab6236b-21d8-471a-a07b-f84a5921f9f5'; // On garde l'ID de processus propre
    $relations = [];
    
    foreach ($layers as $key => $layer) {
        // Vérifie si la ligne est cochée (vaut "1" ou existe)
        if (isset($layer['selected']) && $layer['selected'] == 1) {
            
            // Sécurité : Si les attributs arrivent sous forme de chaîne de texte depuis le HTML, on les transforme en tableau
            $attributesArray = is_string($layer['attributes']) ? explode(',', $layer['attributes']) : $layer['attributes'];

            $relations[$layer['table']] = [
                'attributes' => $attributesArray,
                'filters' => "ST_Intersects(geom, ST_Transform(ST_SetSRID(ST_GeomFromText('$geometry_wgs84'), 4326), " . str_replace('EPSG:', '', $srs) . "))"
            ];
        }
    }
    
    if (empty($relations)) return ['error' => 'Aucune couche sélectionnée. Veuillez cocher au moins une case.'];
    
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