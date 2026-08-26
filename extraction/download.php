<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

$job_id = $_GET['jobID'] ?? '';
if (empty($job_id)) {
    header('Location: /extraction/index.php');
    exit;
}

$download_info = getDownloadLink($job_id);
if (isset($download_info['error']) || empty($download_info['download_url'])) {
    $_SESSION['error'] = $download_info['error'] ?? "Impossible de récupérer le lien de téléchargement.";
    header('Location: /extraction/index.php');
    exit;
}

// Créer le dossier uploads s'il n'existe pas
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

$filename = "extraction_" . date('Ymd_His') . ".zip";
$output_path = UPLOAD_DIR . $filename;

// Télécharger le fichier
if (downloadFile($download_info['download_url'], $output_path)) {
    if (file_exists($output_path)) {
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        readfile($output_path);
        unlink($output_path); // Supprimer le fichier temporaire
        exit;
    } else {
        $_SESSION['error'] = "Fichier temporaire introuvable.";
    }
} else {
    $_SESSION['error'] = "Impossible de télécharger le fichier.";
}

header('Location: /extraction/index.php');
exit;
?>