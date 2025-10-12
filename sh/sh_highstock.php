<?php

include '../php/properties.php';

//connexion a la BD
$conn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS");

// Récupération des paramètres
$suivi = $_POST["suivi"] ?? "phyto";
$site = $_POST["site"] ?? "";
$annee = $_POST["annee"] ?? "";
$plot = $_POST["plot"] ?? "";
$transect = $_POST["transect"] ?? "";
$releve_id = $_POST["releve_id"] ?? "";

// Fonctions à interroger
$fonctions = [
    "shannon" => $suivi == "phyto" ? "sh.f_shannon_phyto" : "sh.f_shannon_contact",
    "richesse" => $suivi == "phyto" ? "sh.f_richesse_phyto" : "sh.f_richesse_contact",
    "sumcoef" => $suivi == "phyto" ? "sh.f_sumcoef_phyto" : "sh.f_sumcoef_contact"
];

// Requête et stockage des résultats
$data = [];
foreach ($fonctions as $key => $fonction) {
    if ($suivi == "phyto") {
        $result = pg_query_params($conn, "SELECT * FROM $fonction($1, $2, $3, $4)", [$site, $annee, $plot, $transect]);
    } else {
        $result = pg_query_params($conn, "SELECT * FROM $fonction($1)", [$releve_id]);
    }
    $data[$key] = [];
    while ($row = pg_fetch_assoc($result)) {
        $data[$key][] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Indicateurs écologiques</title>
    <!--LEAFLET-->
    <!--<link href="css/leaflet.css" rel="stylesheet" type="text/css">-->
    <link href="../js/leaflet1.7/leaflet.css" rel="stylesheet" type="text/css">
    <!-- Bootstrap Core CSS -->
    <!--<link href="css/bootstrap.min.css" rel="stylesheet">-->
	<link href="../bootstrap-5.0.0/css/bootstrap.min.css" rel="stylesheet">
    <!--Datatable-->
	<!--<link href="../js/plugins/datatable/datatables.min.css" rel="stylesheet">-->
    <link href="../css/plugins/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../css/cennormandie.css" rel="stylesheet">
	<link href="../css/autocomplete.dashboard.css" rel="stylesheet">
    <!--FONT AWESOME-->
    <link href="../fontawesome-free-5.15.2-web/css/all.css" rel="stylesheet" type="text/css">
    <!--Datatable bs5-->
    <!--<link href="css/plugins/twitter_bs5.css" rel="stylesheet">-->
</head>
<body class="container py-4">
    <h1 class="mb-4">Indicateurs écologiques - Highstock</h1>

    <form method="POST" class="row g-3 mb-4">
        <div class="col-md-2">
            <label class="form-label">Type de suivi</label>
            <select name="suivi" class="form-select">
                <option value="phyto" <?= $suivi == "phyto" ? "selected" : "" ?>>Relevé Phyto</option>
                <option value="contact" <?= $suivi == "contact" ? "selected" : "" ?>>Carré Contact</option>
            </select>
        </div>
        <div class="col-md-2"><label class="form-label">Site</label><input type="text" name="site" class="form-control" value="<?= $site ?>"></div>
        <div class="col-md-2"><label class="form-label">Année</label><input type="number" name="annee" class="form-control" value="<?= $annee ?>"></div>
        <div class="col-md-2"><label class="form-label">Plot</label><input type="text" name="plot" class="form-control" value="<?= $plot ?>"></div>
        <div class="col-md-2"><label class="form-label">Transect</label><input type="text" name="transect" class="form-control" value="<?= $transect ?>"></div>
        <div class="col-md-2"><label class="form-label">ID Relevé</label><input type="number" name="releve_id" class="form-control" value="<?= $releve_id ?>"></div>
        <div class="col-12"><button type="submit" class="btn btn-primary">Afficher</button></div>
    </form>

    <div id="container_shannon" class="mb-5" style="height: 400px;"></div>
    <div id="container_richesse" class="mb-5" style="height: 400px;"></div>
    <div id="container_sumcoef" class="mb-5" style="height: 400px;"></div>


<script src="../js/jquery.js" ></script>
<!-- Bootstrap Core JavaScript -->
<script src="bootstrap-5.0.0/js/bootstrap.min.js"></script>
<script src="../js/plugins/bs5-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="../js/plugins/bs5-datepicker/locales/bootstrap-datepicker.fr.min.js"></script>
<!-- FONT AWESOME -->
<script src="fontawesome-free-5.15.2-web/js/fontawesome.min.js" ></script>
<!-- LEAFLET -->
<script type="text/javascript" src="../js/leaflet1.7/leaflet.js"></script>
<script type="text/javascript" src="../js/leaflet1.7/leaflet-src.js"></script>
<!--<script type="text/javascript" src="../js/leaflet1.7/leaflet_label/js/leaflet_label.js" ></script>-->

<!-- LEAFLET CUSTOM -->
<script type="text/javascript" src="../js/init_leaflet_dashboard.js"></script>


<!-- HIGHCHARTS -->
<script type="text/javascript" src="../js/plugins/highcharts/code/highstock.js"></script>
<script type="text/javascript" src="../js/plugins/highcharts/code/modules/exporting.js"></script>  


    <script>
    Highcharts.setOptions({ time: { timezone: 'Europe/Paris' } });

    function renderChart(container, title, data, valueKey) {
        Highcharts.stockChart(container, {
            rangeSelector: { selected: 1 },
            title: { text: title },
            series: [{
                name: title,
                data: data.map(row => [Date.UTC(row.annee, 0, 1), parseFloat(row[valueKey])]),
                tooltip: { valueDecimals: 2 }
            }]
        });
    }

    const dataShannon = <?= json_encode($data["shannon"]) ?>;
    const dataRichesse = <?= json_encode($data["richesse"]) ?>;
    const dataSumcoef = <?= json_encode($data["sumcoef"]) ?>;

    renderChart("container_shannon", "Indice de Shannon", dataShannon, "shannon");
    renderChart("container_richesse", "Richesse spécifique", dataRichesse, "richesse");
    renderChart("container_sumcoef", "Somme des coefficients", dataSumcoef, "sumcoef");
    </script>
</body>
</html>
