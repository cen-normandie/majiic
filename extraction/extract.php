<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

// Sécurité : Vérification du jeton CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Erreur de sécurité : Jeton CSRF invalide.");
}

// Récupération et nettoyage des données du formulaire
$stored_data_id = $_POST['stored_data_id'] ?? null;
$geometry_wgs84 = $_POST['geometry_wgs84'] ?? null;
$format         = $_POST['format'] ?? DEFAULT_FORMAT;
$srs            = $_POST['srs'] ?? DEFAULT_SRS;
$layers         = $_POST['layers'] ?? [];

// Validation basique
if (!$stored_data_id || !$geometry_wgs84) {
    die("Erreur : La zone géographique ou la donnée source est manquante.");
}

// Lancement de l'extraction via la fonction modifiée (clé HASH)
$result = startExtraction($stored_data_id, $layers, $geometry_wgs84, $format, $srs);

// Traitement du résultat de l'IGN
if (isset($result['error'])) {
    // Si l'API renvoie une erreur, on l'affiche proprement pour comprendre le problème
    echo "<h3>Une erreur est survenue lors du lancement de l'extraction :</h3>";
    echo "<div style='color:red; background:#f8d7da; padding:15px; border-radius:5px; border:1px solid #f5c6cb;'>";
    echo htmlspecialchars($result['error']);
    echo "</div>";
    echo "<br><a href='/extraction/index.php'>Retourner au formulaire</a>";
    exit;
}

// Si l'IGN a accepté la demande, un "job_id" (ID de traitement) est retourné
if (isset($result['jobID']) || isset($result['id'])) {
    $job_id = $result['jobID'] ?? $result['id'];

    // Enregistrement du job en session pour l'historique
    if (!isset($_SESSION['extraction_jobs'])) {
        $_SESSION['extraction_jobs'] = [];
    }

    $_SESSION['extraction_jobs'][] = [
        'job_id'    => $job_id,
        'date'      => date('Y-m-d H:i:s'),
        'status'    => 'running', // Statut initial
        'format'    => $format
    ];

    // Redirection immédiate vers la page de suivi du statut
    header("Location: /extraction/check_job.php?job_id=" . urlencode($job_id));
    exit;
}

// Cas imprévu : l'IGN renvoie un format inconnu
echo "<h3>Réponse inattendue du serveur IGN :</h3>";
echo "<pre>" . print_r($result, true) . "</pre>";
echo "<a href='/extraction/index.php'>Retourner au formulaire</a>";
