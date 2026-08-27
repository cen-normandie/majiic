<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

// Générer un token CSRF si inexistant
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Récupérer la liste des données disponibles via la clé HASH
$stored_data_list = [];
$error = null;

if (defined('GEOPLATEFORME_API_KEY') && !empty(GEOPLATEFORME_API_KEY)) {
    $ch = curl_init();
    
    // Utilisation de la clé HASH dans l'URL pour être certain que l'entrepôt valide la requête
    $url = GEOPLATEFORME_API_URL . '/api/me/stored_data?type=VECTOR-DB&limit=50&apikey=' . GEOPLATEFORME_API_KEY;

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => defined('CURL_TIMEOUT') ? CURL_TIMEOUT : 10,
        CURLOPT_HTTPHEADER => [
            'X-Api-Key: ' . GEOPLATEFORME_API_KEY, // Sécurité supplémentaire par Header
            'Referer: ' . GEOPLATEFORME_REFERER,
            'Accept: application/json'
        ]
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    // Débogage dans vos logs PHP
    error_log("Réponse API stored_data: HTTP $http_code - Réponse: $response - Erreur cURL: $curl_error");

    if ($http_code === 200) {
        $data = json_decode($response, true);
        
        // La Géoplateforme retourne souvent les résultats dans un tableau "user_data", "stored_data" ou directement "data"
        if (isset($data['data'])) {
            $stored_data_list = $data['data'];
        } elseif (isset($data['stored_data'])) {
            $stored_data_list = $data['stored_data'];
        } elseif (is_array($data) && !isset($data['data'])) {
            // Si le tableau est retourné directement à la racine
            $stored_data_list = $data;
        } else {
            error_log("Réponse API invalide ou vide : " . print_r($data, true));
            $error = "Aucune donnée disponible pour votre configuration actuelle.";
        }
    } else {
        $error = "Erreur lors de la récupération des données (HTTP $http_code). Vérifiez la validité de votre clé HASH.";
    }
} else {
    $error = "La clé API HASH n'est pas configurée dans votre fichier config.php.";
}
?>
