<?php
require_once __DIR__ . '/config.php';

// Si déjà connecté à l'application principale, rediriger vers index.php
if (isset($_SESSION['email']) && isset($_SESSION['password'])) {
    header('Location: /extraction/index.php');
    exit;
}

// Si déjà connecté à l'extraction, rediriger
if (isset($_SESSION['geoplateforme_username'])) {
    header('Location: /extraction/index.php');
    exit;
}

// Traitement du formulaire de connexion (si nécessaire)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    if (!empty($username) && !empty($password)) {
        $_SESSION['geoplateforme_username'] = $username;
        $_SESSION['geoplateforme_password'] = $password;
        if (getAccessToken()) {
            header('Location: /extraction/index.php');
            exit;
        } else {
            $error = "Identifiants incorrects.";
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Extraction Géoplateforme</title>
    <!-- Utilisation des ressources existantes -->
    <link href="/bootstrap-5.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="/fontawesome-free-5.15.2-web/css/all.css" rel="stylesheet">
    <link href="/css/cennormandie.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../menu.php'; ?>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Connexion à l'extraction Géoplateforme</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Email</label>
                                <input type="email" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>