<?php
require_once __DIR__ . '/config.php';

// === Fonction pour obtenir un token OAuth2 ===
function getAccessToken() {
    $post_data = [
        'grant_type' => 'password',
        'username' => $_SESSION['email'] ?? null, // Utilise les infos de la session principale
        'password' => $_SESSION['password'] ?? null,
        'client_id' => 'geoplateforme'
    ];
    if (!isset($post_data['username']) || !isset($post_data['password'])) return null;

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => GEOPLATEFORME_AUTH_URL,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($post_data),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/x-www-form-urlencoded',
            'Referer: ' . GEOPLATEFORME_REFERER
        ]
    ]);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code === 200) {
        $token_data = json_decode($response, true);
        $_SESSION['geoplateforme_token'] = $token_data['access_token'];
        $_SESSION['geoplateforme_token_expires'] = time() + ($token_data['expires_in'] ?? 3600);
        return $token_data['access_token'];
    }
    return null;
}

// === Fonction pour vérifier si le token est valide ===
function isTokenValid() {
    return isset($_SESSION['geoplateforme_token']) &&
           ($_SESSION['geoplateforme_token_expires'] > time());
}

// === Fonction pour obtenir le token (ou en demander un nouveau) ===
function getValidToken() {
    return isTokenValid() ? $_SESSION['geoplateforme_token'] : getAccessToken();
}

// === Fonction pour lancer une extraction ===
function startExtraction($stored_data_id, $layers, $geometry_wgs84, $format = DEFAULT_FORMAT, $srs = DEFAULT_SRS) {
    $token = getValidToken();
    if (!$token) return ['error' => 'Token invalide. Veuillez vous reconnecter.'];

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

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => GEOPLATEFORME_EXTRACTION_URL . "/processes/$process_id/execution",
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($body),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token,
            'Referer: ' . GEOPLATEFORME_REFERER
        ]
    ]);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $http_code === 200 ? json_decode($response, true) : ['error' => "Erreur $http_code: $response"];
}

// === Autres fonctions (checkJobStatus, getDownloadLink, downloadFile) ===
// (Identiques au précédent, je les omets pour éviter la redondance, mais elles sont nécessaires)
function checkJobStatus($job_id) {
    $token = getValidToken();
    if (!$token) return ['error' => 'Token invalide.'];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => GEOPLATEFORME_EXTRACTION_URL . "/jobs/$job_id",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $token,
            'Referer: ' . GEOPLATEFORME_REFERER
        ]
    ]);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $http_code === 200 ? json_decode($response, true) : ['error' => "Erreur $http_code"];
}

function getDownloadLink($job_id) {
    $token = getValidToken();
    if (!$token) return ['error' => 'Token invalide.'];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => GEOPLATEFORME_EXTRACTION_URL . "/jobs/$job_id/results",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $token,
            'Referer: ' . GEOPLATEFORME_REFERER
        ]
    ]);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $http_code === 200 ? ['download_url' => json_decode($response, true)['extractData']['href'] ?? null] : ['error' => "Erreur $http_code"];
}

function downloadFile($url, $output_path) {
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
        file_put_contents($output_path, $response);
        return true;
    }
    return false;
}
?>