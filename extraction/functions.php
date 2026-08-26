<?php
require_once __DIR__ . '/config.php';

// === Fonction pour obtenir un token OAuth2 ===
function getAccessToken() {
    // Si un token existe et est valide, le retourner
    if (isset($_SESSION['geoplateforme_token']) && isset($_SESSION['geoplateforme_token_expires']) && $_SESSION['geoplateforme_token_expires'] > time()) {
        return $_SESSION['geoplateforme_token'];
    }

    // Sinon, en demander un nouveau
    $post_data = [
        'grant_type' => 'password',
        'username' => $_SESSION['email'] ?? null,
        'password' => $_SESSION['oauth_password'] ?? null, // À remplacer par un refresh_token si possible
        'client_id' => 'geoplateforme'
    ];

    // Si un refresh_token est disponible, l'utiliser
    if (isset($_SESSION['geoplateforme_refresh_token'])) {
        $post_data = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $_SESSION['geoplateforme_refresh_token'],
            'client_id' => 'geoplateforme'
        ];
    }

    if ((!isset($post_data['username']) || !isset($post_data['password'])) && !isset($post_data['refresh_token'])) {
        return null;
    }

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => GEOPLATEFORME_AUTH_URL,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($post_data),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => CURL_TIMEOUT,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/x-www-form-urlencoded',
            'Referer: ' . GEOPLATEFORME_REFERER
        ]
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($http_code === 200) {
        $token_data = json_decode($response, true);
        if (isset($token_data['access_token'])) {
            $_SESSION['geoplateforme_token'] = $token_data['access_token'];
            $_SESSION['geoplateforme_token_expires'] = time() + ($token_data['expires_in'] ?? 3600);
            if (isset($token_data['refresh_token'])) {
                $_SESSION['geoplateforme_refresh_token'] = $token_data['refresh_token'];
            }
            return $token_data['access_token'];
        }
    } else {
        error_log("Erreur OAuth2: HTTP $http_code - $error - $response");
    }
    return null;
}

// === Fonction pour vérifier si le token est valide ===
function isTokenValid() {
    return isset($_SESSION['geoplateforme_token']) && ($_SESSION['geoplateforme_token_expires'] > time());
}

// === Fonction pour obtenir le token (ou en demander un nouveau) ===
function getValidToken() {
    return isTokenValid() ? $_SESSION['geoplateforme_token'] : getAccessToken();
}

// === Fonction pour lancer une extraction ===
function startExtraction($stored_data_id, $layers, $geometry_wgs84, $format = DEFAULT_FORMAT, $srs = DEFAULT_SRS) {
    $token = getValidToken();
    if (!$token) {
        return ['error' => 'Token invalide. Veuillez vous reconnecter.'];
    }

    // Process ID fixe pour l'extraction vectorielle
    $process_id = '8ab6236b-21d8-471a-a07b-f84a5921f9f5';

    // Construire les relations pour les couches sélectionnées
    $relations = [];
    foreach ($layers as $layer_data) {
        if (isset($layer_data['selected']) && $layer_data['selected'] == '1') {
            $relations[$layer_data['table']] = [
                'attributes' => $layer_data['attributes'],
                'filters' => "ST_Intersects(geom, ST_Transform(ST_SetSRID('$geometry_wgs84'::geometry, 4326), " . str_replace('EPSG:', '', $srs) . "))"
            ];
        }
    }

    if (empty($relations)) {
        return ['error' => 'Aucune couche sélectionnée.'];
    }

    $body = [
        'inputs' => [
            'stored_data' => $stored_data_id,
            'relations' => $relations,
            'format' => $format,
            'srs' => $srs,
            'lifetime' => DEFAULT_LIFETIME,
            'append' => true,
            'compression' => '7zip'
        ],
        'outputs' => [
            'logs' => new stdClass(),
            'summary' => new stdClass(),
            'extractedData' => new stdClass()
        ]
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => GEOPLATEFORME_EXTRACTION_URL . "/processes/$process_id/execution",
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($body),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => CURL_TIMEOUT,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token,
            'Referer: ' . GEOPLATEFORME_REFERER
        ]
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($http_code === 200) {
        $result = json_decode($response, true);
        if (isset($result['jobID'])) {
            return $result;
        } else {
            error_log("Réponse invalide de l'API: $response");
            return ['error' => 'Réponse invalide de l\'API.'];
        }
    } else {
        error_log("Erreur extraction: HTTP $http_code - $error - $response");
        return ['error' => "Erreur $http_code: " . (json_decode($response, true)['message'] ?? 'Inconnu')];
    }
}

// === Fonction pour vérifier le statut d'un job ===
function checkJobStatus($job_id) {
    $token = getValidToken();
    if (!$token) {
        return ['error' => 'Token invalide.'];
    }

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => GEOPLATEFORME_EXTRACTION_URL . "/jobs/$job_id",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => CURL_TIMEOUT,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $token,
            'Referer: ' . GEOPLATEFORME_REFERER
        ]
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($http_code === 200) {
        return json_decode($response, true);
    } else {
        error_log("Erreur checkJobStatus: HTTP $http_code - $error - $response");
        return ['error' => "Erreur $http_code"];
    }
}

// === Fonction pour obtenir le lien de téléchargement ===
function getDownloadLink($job_id) {
    $token = getValidToken();
    if (!$token) {
        return ['error' => 'Token invalide.'];
    }

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => GEOPLATEFORME_EXTRACTION_URL . "/jobs/$job_id/results",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => CURL_TIMEOUT,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $token,
            'Referer: ' . GEOPLATEFORME_REFERER
        ]
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($http_code === 200) {
        $result = json_decode($response, true);
        if (isset($result['extractedData']['href'])) {
            return ['download_url' => $result['extractedData']['href']];
        }
    } else {
        error_log("Erreur getDownloadLink: HTTP $http_code - $error - $response");
    }
    return ['error' => "Erreur $http_code"];
}

// === Fonction pour télécharger un fichier ===
function downloadFile($url, $output_path) {
    if (!file_exists(dirname($output_path))) {
        mkdir(dirname($output_path), 0755, true);
    }

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => CURL_TIMEOUT
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($http_code === 200) {
        if (file_put_contents($output_path, $response) !== false) {
            return true;
        } else {
            error_log("Erreur écriture fichier: $output_path");
        }
    } else {
        error_log("Erreur téléchargement: HTTP $http_code - $error");
    }
    return false;
}
?>