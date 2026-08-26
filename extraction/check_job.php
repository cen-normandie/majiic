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

$job_status = checkJobStatus($job_id);
if (isset($job_status['error'])) {
    $_SESSION['error'] = $job_status['error'];
    header('Location: /extraction/index.php');
    exit;
}

foreach ($_SESSION['extraction_jobs'] as &$job) {
    if ($job['jobID'] === $job_id) {
        $job['status'] = $job_status['status'];
        break;
    }
}

if ($job_status['status'] === 'successful') {
    header('Location: /extraction/download.php?jobID=' . $job_id);
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statut de l'extraction</title>
    <link href="/bootstrap-5.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="/fontawesome-free-5.15.2-web/css/all.css" rel="stylesheet">
    <link href="/css/cennormandie.css" rel="stylesheet">
</head>
<body>
    <?php
    $_POST["page"] = basename(__FILE__);
    include __DIR__ . '/../menu.php';
    ?>
    <div class="d-flex flex-column col-md-9 col-lg-10 h-100 bg-light" style="overflow-y:auto;overflow-x:hidden;min-height:100vh;">
        <div class="d-flex justify-content-end w-100 bg-dark">
            <div class="m-2"><span class="text-light"><i class="fas fa-user"></i> <?php echo $_SESSION['email']; ?></span></div>
            <div class="m-2"><a class="logout text-light" href="/php/logout.php"><i class="fa fa-fw fa-power-off"></i> Déconnexion</a></div>
        </div>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header"><h3>Statut de l'extraction</h3></div>
                        <div class="card-body text-center">
                            <div class="spinner-border text-primary mb-3" role="status"></div>
                            <h4>Extraction en cours...</h4>
                            <p class="text-muted">Job ID: <?= htmlspecialchars($job_id) ?></p>
                            <p>Statut: <strong><?= ucfirst($job_status['status'] ?? 'inconnu') ?></strong></p>
                            <div class="progress mt-4">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 75%"></div>
                            </div>
                            <p class="mt-3">L'extraction peut prendre plusieurs minutes.</p>
                            <a href="/extraction/index.php" class="btn btn-outline-secondary mt-3">Retour</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        setTimeout(function() { window.location.reload(); }, 5000);
    </script>
</body>
</html>