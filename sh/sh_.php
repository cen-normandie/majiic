<?php
// Connexion à PostgreSQL
$conn = pg_connect("host=localhost dbname=your_db user=your_user password=your_password");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Indicateurs écologiques</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">
<div class="container py-4">
    <h2 class="mb-4">Interrogation des indicateurs écologiques</h2>
    <form method="POST" class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Type de suivi</label>
            <select name="suivi" class="form-select">
                <option value="phyto">Relevé Phyto</option>
                <option value="contact">Carré Contact</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Site</label>
            <input type="text" name="site" class="form-control">
        </div>
        <div class="col-md-2">
            <label class="form-label">Année</label>
            <input type="number" name="annee" class="form-control">
        </div>
        <div class="col-md-2">
            <label class="form-label">Plot</label>
            <input type="text" name="plot" class="form-control">
        </div>
        <div class="col-md-2">
            <label class="form-label">Transect</label>
            <input type="text" name="transect" class="form-control">
        </div>
        <div class="col-md-2">
            <label class="form-label">ID Relevé (Carré Contact)</label>
            <input type="number" name="releve_id" class="form-control">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Interroger</button>
        </div>
    </form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $suivi = $_POST["suivi"];
    $site = $_POST["site"];
    $annee = $_POST["annee"];
    $plot = $_POST["plot"];
    $transect = $_POST["transect"];
    $releve_id = $_POST["releve_id"];

    $results = [];

    if ($suivi == "phyto") {
        $results["Shannon"] = pg_query_params($conn, "SELECT * FROM sh.f_shannon_phyto($1, $2, $3, $4)", [$site, $annee, $plot, $transect]);
        $results["Richesse"] = pg_query_params($conn, "SELECT * FROM sh.f_richesse_phyto($1, $2, $3, $4)", [$site, $annee, $plot, $transect]);
        $results["Somme des coefficients"] = pg_query_params($conn, "SELECT * FROM sh.f_sumcoef_phyto($1, $2, $3, $4)", [$site, $annee, $plot, $transect]);
    } else {
        $results["Shannon"] = pg_query_params($conn, "SELECT * FROM sh.f_shannon_contact($1)", [$releve_id]);
        $results["Richesse"] = pg_query_params($conn, "SELECT * FROM sh.f_richesse_contact($1)", [$releve_id]);
        $results["Somme des coefficients"] = pg_query_params($conn, "SELECT * FROM sh.f_sumcoef_contact($1)", [$releve_id]);
    }

    echo '<ul class="nav nav-tabs mt-5" id="indicatorTabs" role="tablist">';
    $i = 0;
    foreach ($results as $label => $res) {
        $active = $i === 0 ? 'active' : '';
        echo '<li class="nav-item" role="presentation">
                <button class="nav-link '.$active.'" id="tab'.$i.'" data-bs-toggle="tab" data-bs-target="#content'.$i.'" type="button" role="tab">'.$label.'</button>
              </li>';
        $i++;
    }
    echo '</ul>';

    echo '<div class="tab-content border p-3 bg-white" id="indicatorTabsContent">';
    $i = 0;
    foreach ($results as $label => $res) {
        $active = $i === 0 ? 'show active' : '';
        echo '<div class="tab-pane fade '.$active.'" id="content'.$i.'" role="tabpanel">';
        echo '<h5>'.$label.'</h5>';
        echo '<table class="table table-bordered table-sm">';
        $first = true;
        while ($row = pg_fetch_assoc($res)) {
            if ($first) {
                echo '<thead><tr>';
                foreach ($row as $col => $val) {
                    echo '<th>'.htmlspecialchars($col).'</th>';
                }
                echo '</tr></thead><tbody>';
                $first = false;
            }
            echo '<tr>';
            foreach ($row as $val) {
                echo '<td>'.htmlspecialchars($val).'</td>';
            }
            echo '</tr>';
        }
        echo '</tbody></table>';
        echo '</div>';
        $i++;
    }
    echo '</div>';
}
?>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
