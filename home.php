<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>CEN Normandie</title>
    <link rel="shortcut icon" href="img/cenno.ico" />
    <script>L_PREFER_CANVAS = true;</script>
    
    
    <!--LEAFLET-->
    <link href="css/leaflet.css" rel="stylesheet" type="text/css">
    <link href="js/leaflet/plugins/Leaflet.draw-master/dist/leaflet.draw.css" rel="stylesheet" type="text/css">
    <link href="js/leaflet/plugins/leaflet_label/css/leafleat_label.css" rel="stylesheet" type="text/css">
    <link href="css/custom_leaflet.css" rel="stylesheet" type="text/css">
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/datatables.bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">
    <link href="css/c_home.css" rel="stylesheet">
    <!--Autocomplete UI -->
    <link href="css/plugins/jquery-ui.css" rel="stylesheet">
    <!--FONT AWESOME-->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    
</head>

<?php
session_start();
include 'php/properties.php';


if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit();
};
if (!isset($_SESSION['password'])) {
    header('Location: index.php');
    exit();
};
if (!isset($_SESSION['session'])) {
    header('Location: index.php');
    exit();
};

?>

<body class="body_">


    <div id="wrapper">
    
        <?php include('menu.inc.php'); ?>
        
        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                    
                    </div>
                </div>
                <!-- /.Page Heading -->
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-3">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div id="loader" class="loader visible_s">
                                    <i class="fa fa-refresh fa-spin"></i>Loading
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Rechercher une commune :</label>
                                <input id="layers_autocomplete" class="form-control" placeholder="" onblur="" >
                                <!--<p class="help-block">Commune</p>-->
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="panel panel-success">
                                <div class="panel-body">
                                    <p class="text-success">A - Recherchez une commune</p>
                                    <p class="text-success">B - Si à la fin du chargement rien ne s'affiche zoomez sur la carte</p>
                                    <p class="text-success">C - Cliquez sur une parcelle pour afficher les propriétaires dans le tableau ci-dessous</p>
                                    <p class="text-success">D - Il est possible d'exporter le tableau au format Excel</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="panel panel-info">
                                <div class="panel-body">
                                    <p class="text-primary bg-info">Sources : </p>
                                    <p class="text-primary bg-info">PCI Vecteur (07-2019)</p>
                                    <p class="text-primary bg-info">Direction Générale des Finances 2017</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9" ><!-- data-spy="affix" data-offset-top="60" -->
                        <div id="map" style="width:1000px;height:600px;" ></div><!-- data-spy="affix" data-offset-top="60" -->
                    </div>
                    <div class="col-sm-11 spacer">
                        <div class="col-sm-10">
                            <div class="table-responsive" >
                                <table id="ola_dt" class="table table-bordered table-hover"><!-- dt-responsive-->
                                    <thead>
                                        <tr>
                                            <th class="text-center">
                                                #
                                            </th>
                                            <th class="text-center">
                                                <small>ID par</small>
                                            </th>
                                            <th class="text-center">
                                                <small>Nom d'usage</small>
                                            </th>
                                            <th class="text-center">
                                                <small>Prénom d'usage</small>
                                            </th>
                                            <th class="text-center">
                                                <small>Date de naissance</small>
                                            </th>
                                            <th class="text-center">
                                                <small>Adresse du propriétaire</small>
                                            </th>
                                            <th class="text-center">
                                                <small>Dénomination</small>
                                            </th>
                                            <th class="text-center">
                                                <small>Type de propriétaire</small>
                                            </th>
                                            <th class="text-center">
                                                <small>Surface (ha)</small>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <button id="clear_table" class="btn btn-danger">Vider le tableau</button>
                        </div>
                    </div>
                <div class="row">
                </div>
                <!-- /.row -->
                <?php include('footer.inc.php'); ?>
                </div>
            <!-- /.row -->
            </div>
            <!-- /container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->



<!-- JQUERY-->
<script src="js/jquery.js" ></script>
<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap3.3.7.js"></script>
<!-- JQUERY AUTOCOMPLETE -->
<script src="js/plugins/jquery-ui-1.12.1.custom/jquery-ui.js" ></script>



<!-- DATATABLES -->
<script type="text/javascript" src="js/plugins/datatables_all.min.js"></script> 


<!--Leaflet-->
<script src="js/leaflet/leaflet.js" ></script>
<script src="js/leaflet/leaflet_add_function.js" ></script>
<script src="js/leaflet/plugins/Leaflet.draw-master/dist/leaflet.draw.js" ></script>
<script src="js/leaflet/plugins/leaflet_label/js/leaflet_label.js" ></script>
<script src="js/leaflet/plugins/Leaflet.markercluster-master/src/MarkerCluster.js" ></script>
<script src="js/leaflet/plugins/Leaflet.markercluster-master/src/MarkerClusterGroup.js" ></script>
<script src="js/leaflet/plugins/leaflet-image-gh-pages/leaflet-image.js"></script>
<script src="js/init_leaflet.js" ></script>

<!--Custom-->
<script src="js/home.js" ></script>

<script >
//bootstrap 3.x and datatable work but disable dropdown menu
$('body .dropdown-toggle').dropdown();
initmap();


var dt4 = $('#ola_dt').DataTable({
    "language": {
    "paginate": {
    "previous": "Préc.",
    "next": "Suiv."
    },
    "search": "Filtrer :",
    "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
    "sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
    "sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
    "sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
    "sInfoPostFix":    "",
    "sLoadingRecords": "Chargement en cours...",
    "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
    "sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau"
},
dom: 'Bpt',
buttons: [
    { 
    "extend": 'excel', 
    "text":'Excel',
    "className": 'btn btn-default' },
    { 
    "extend": 'pdf', 
    "text":'PDF',
    "className": 'btn btn-default' }
    ]
});
</script>




</body>
</html>
