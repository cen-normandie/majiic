<?php
// === Configuration de la Géoplateforme ===
define('GEOPLATEFORME_API_URL', 'https://data.geopf.fr');
define('GEOPLATEFORME_AUTH_URL', GEOPLATEFORME_API_URL . '/auth/realms/geoplateforme/protocol/openid-connect/token');
define('GEOPLATEFORME_EXTRACTION_URL', GEOPLATEFORME_API_URL . '/extraction');
define('GEOPLATEFORME_REFERER', 'http://' . $_SERVER['HTTP_HOST'] . '/');

// === Configuration locale ===
define('ROOT_PATH', dirname(__DIR__, 1) . '/extraction/');
define('UPLOAD_DIR', ROOT_PATH . 'uploads/');
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10 Mo
define('CURL_TIMEOUT', 30); // Timeout pour les requêtes cURL (secondes)

// === Couches disponibles ===
$available_layers = [
    'troncon_hydro' => [
        'name' => 'Tronçons hydro',
        'table' => 'troncon_hydrographique',
        'attributes' => ['id', 'geom', 'nature', 'longueur'],
        'description' => 'Réseau hydrographique'
    ],
    'bati_indifferencie' => [
        'name' => 'Bâtiments',
        'table' => 'bati_indifferencie',
        'attributes' => ['id', 'geom', 'nature', 'hauteur'],
        'description' => 'Bâtiments (BD TOPO®)'
    ],
    'route' => [
        'name' => 'Routes',
        'table' => 'troncon_route',
        'attributes' => ['id', 'geom', 'type', 'largeur'],
        'description' => 'Réseau routier'
    ],
];

// === Format de sortie par défaut ===
define('DEFAULT_FORMAT', 'GPKG');
define('DEFAULT_SRS', 'EPSG:2154');
define('DEFAULT_LIFETIME', 24);

// Démarre la session
session_start();

// === Vérification de l'accès ===
// Avec OAuth2, on vérifie simplement que l'utilisateur est connecté (email en session)
if (!isset($_SESSION['email'])) {
    header('Location: /index.php');
    exit;
}
?>