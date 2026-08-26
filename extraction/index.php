<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

// Générer un token CSRF si inexistant
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Récupérer la liste des données disponibles
$stored_data_list = [];
$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);

$token = getValidToken();
if ($token) {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => GEOPLATEFORME_API_URL . '/api/me/stored_data?type=VECTOR-DB&limit=50',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => CURL_TIMEOUT,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $token,
            'Referer: ' . GEOPLATEFORME_REFERER
        ]
    ]);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code === 200) {
        $data = json_decode($response, true);
        $stored_data_list = $data['data'] ?? [];
    } else {
        $error = "Erreur lors de la récupération des données (HTTP $http_code).";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extraction Géoplateforme - CEN Normandie</title>
    <link href="/bootstrap-5.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="/fontawesome-free-5.15.2-web/css/all.css" rel="stylesheet">
    <link href="/css/cennormandie.css" rel="stylesheet">
    <link rel="stylesheet" href="/js/leaflet1.7/leaflet.css" />
    <link rel="stylesheet" href="/extraction/assets/style.css" />
</head>
<body>
    <?php
    $_POST["page"] = basename(__FILE__);
    include __DIR__ . '/../menu.php';
    ?>
    <div class="d-flex flex-column col-md-9 col-lg-10 h-100 bg-light" style="overflow-y:auto;overflow-x:hidden;min-height:100vh;">
        <div class="d-flex justify-content-end w-100 bg-dark">
            <div class="m-2"><span class="text-light"><i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['email']) ?></span></div>
            <div class="m-2"><a class="logout text-light" href="/php/logout.php"><i class="fa fa-fw fa-power-off"></i> Déconnexion</a></div>
        </div>
        <div class="d-flex justify-content-between w-100 bg-light m-2 border-bottom">
            <h3>Extraction de données Géoplateforme</h3>
        </div>

        <div class="container py-4">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="card mb-4">
                <div class="card-header"><h5>1. Sélectionner une zone géographique (WGS84)</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="geometry_wgs84" class="form-label">Géométrie WKT</label>
                                <textarea class="form-control" id="geometry_wgs84" name="geometry_wgs84" rows="3" placeholder="POLYGON((-0.37 49.18, -0.37 49.19, -0.36 49.19, -0.36 49.18, -0.37 49.18))"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="map" style="height: 300px; border: 1px solid #ddd; border-radius: 5px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5>2. Sélectionner les données à extraire</h5></div>
                <div class="card-body">
                    <form id="extractionForm" method="post" action="/extraction/extract.php">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                        <input type="hidden" name="geometry_wgs84" id="hidden_geometry_wgs84">

                        <div class="mb-3">
                            <label for="stored_data" class="form-label">Donnée source</label>
                            <select class="form-select" id="stored_data" name="stored_data_id" required>
                                <option value="" selected disabled>Sélectionnez une donnée...</option>
                                <?php foreach ($stored_data_list as $data): ?>
                                    <option value="<?= htmlspecialchars($data['_id']) ?>" data-srs="<?= htmlspecialchars($data['srs'] ?? 'EPSG:4326') ?>">
                                        <?= htmlspecialchars($data['name'] ?? $data['_id']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Couches à extraire</label>
                            <div class="row">
                                <?php foreach ($available_layers as $key => $layer): ?>
                                    <div class="col-md-6 col-lg-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="layers[<?= $key ?>][selected]" id="layer_<?= $key ?>" value="1">
                                            <label class="form-check-label" for="layer_<?= $key ?>">
                                                <strong><?= htmlspecialchars($layer['name']) ?></strong>
                                            </label>
                                            <input type="hidden" name="layers[<?= $key ?>][table]" value="<?= htmlspecialchars($layer['table']) ?>">
                                            <input type="hidden" name="layers[<?= $key ?>][attributes]" value="<?= htmlspecialchars(implode(',', $layer['attributes'])) ?>">
                                            <div class="text-muted small"><?= htmlspecialchars($layer['description']) ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="format" class="form-label">Format de sortie</label>
                            <select class="form-select" id="format" name="format">
                                <option value="GPKG" selected>GeoPackage (GPKG)</option>
                                <option value="GEOJSON">GeoJSON</option>
                                <option value="ESRI SHAPEFILE">Shapefile</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="srs" class="form-label">Projection (SRS)</label>
                            <select class="form-select" id="srs" name="srs">
                                <option value="EPSG:4326">WGS84 (EPSG:4326)</option>
                                <option value="EPSG:2154" selected>Lambert-93 (EPSG:2154)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Lancer l'extraction</button>
                    </form>
                </div>
            </div>

            <?php if (isset($_SESSION['extraction_jobs']) && !empty($_SESSION['extraction_jobs'])): ?>
                <div class="card">
                    <div class="card-header"><h5>3. Historique des extractions</h5></div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Job ID</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($_SESSION['extraction_jobs'] as $job): ?>
                                    <tr>
                                        <td><?= date('d/m/Y H:i:s', $job['timestamp']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= htmlspecialchars($job['status'] === 'successful' ? 'success' : ($job['status'] === 'running' ? 'primary' : 'danger')) ?>">
                                                <?= htmlspecialchars(ucfirst($job['status'])) ?>
                                            </span>
                                        </td>
                                        <td class="text-muted"><?= htmlspecialchars(substr($job['jobID'], 0, 8) . '...') ?></td>
                                        <td>
                                            <?php if ($job['status'] === 'successful'): ?>
                                                <a href="/extraction/download.php?jobID=<?= htmlspecialchars($job['jobID']) ?>" class="btn btn-sm btn-outline-success">Télécharger</a>
                                            <?php elseif ($job['status'] === 'running'): ?>
                                                <a href="/extraction/check_job.php?jobID=<?= htmlspecialchars($job['jobID']) ?>" class="btn btn-sm btn-outline-primary">Vérifier</a>
                                            <?php else: ?>
                                                <span class="text-muted">Échec</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="/js/jquery.js"></script>
    <script src="/bootstrap-5.0.0/js/bootstrap.min.js"></script>
    <script src="/fontawesome-free-5.15.2-web/js/fontawesome.min.js"></script>
    <script src="/js/leaflet1.7/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
    <script>
        // Initialiser la carte centrée sur la Normandie (Caen)
        const map = L.map('map').setView([49.1828, -0.3708], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        const drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);
        const drawControl = new L.Control.Draw({
            edit: { featureGroup: drawnItems },
            draw: { polygon: true, rectangle: true, polyline: false, circle: false, marker: false }
        });
        map.addControl(drawControl);

        map.on(L.Draw.Event.CREATED, function(e) {
            const layer = e.layer;
            drawnItems.addLayer(layer);
            const coords = layer.getLatLngs()[0];
            let wkt = "POLYGON((";
            coords.forEach((latlng, index) => {
                wkt += latlng.lng + " " + latlng.lat + (index < coords.length - 1 ? ", " : "");
            });
            wkt += "))";
            document.getElementById('geometry_wgs84').value = wkt;
            document.getElementById('hidden_geometry_wgs84').value = wkt;
        });

        document.getElementById('extractionForm').addEventListener('submit', function() {
            document.getElementById('hidden_geometry_wgs84').value = document.getElementById('geometry_wgs84').value;
        });
    </script>
</body>
</html>