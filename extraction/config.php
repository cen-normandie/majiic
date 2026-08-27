<?php
// === Configuration de la Géoplateforme ===
define('GEOPLATEFORME_API_URL', 'https://data.geopf.fr');
define('GEOPLATEFORME_EXTRACTION_URL', GEOPLATEFORME_API_URL . '/extraction');

// METTEZ VOTRE CLÉ HASH ICI (Exemple: 'votre_cle_hash_ici')
define('GEOPLATEFORME_API_KEY', 'XdG24TWtS6AiShxmJ80LLk9dSLDAy5Yf'); 

define('GEOPLATEFORME_REFERER', 'http://' . $_SERVER['HTTP_HOST'] . '/'); // Domaine dynamique

// === Configuration locale ===
define('ROOT_PATH', dirname(__DIR__, 1) . '/extraction/'); // Chemin racine pour l'extraction
define('UPLOAD_DIR', ROOT_PATH . 'uploads/');
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10 Mo

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
if (!isset($_SESSION['email']) || !isset($_SESSION['password'])) {
    header('Location: /index.php');
    exit;
}
?>