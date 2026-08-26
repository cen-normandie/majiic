<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['email']) || !isset($_SESSION['password'])) {
    header('Location: /index.php');
    exit;
}

$job_id = $_GET['jobID'] ?? '';
if (empty($job_id)) {
    header('Location: /extraction/index.php');
    exit;
}

$download_info = getDownloadLink($job_id);
if (isset($download_info['error']) || empty($download_info['download_url'])) {
    $_SESSION['error'] = $download_info['error'] ?? "Impossible de récupérer le lien.";
    header('Location: /extraction/index.php');
    exit;
}

$filename = "extraction_" . date('Ymd_His') . ".zip";
if (!file_exists(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
$output_path = UPLOAD_DIR . $filename;

if (downloadFile($download_info['download_url'], $output_path)) {
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    readfile($output_path);
    unlink($output_path);
    exit;
} else {
    $_SESSION['error'] = "Impossible de télécharger le fichier.";
    header('Location: /extraction/index.php');
    exit;
}
?>