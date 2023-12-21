<?php session_start(); 
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
$_SESSION['is_admin'] = false;
$admins = array("n.moreira@cen-normandie.fr", "c.bouteiller@cen-normandie.fr", "f.buissart@cen-normandie.fr", "b.perceval@cen-normandie.fr");
if (in_array($_SESSION['email'], $admins)) {
    $_SESSION['is_admin'] = true;
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>CEN Normandie</title>
    <link rel="shortcut icon" href="img/CenNormandie.ico" />
    <script>L_PREFER_CANVAS = true;</script>
    
    
    <!--LEAFLET-->
    <link href="js/leaflet1.7/leaflet.css" rel="stylesheet" type="text/css">
    <link href="css/custom_leaflet.css" rel="stylesheet" type="text/css">
    <!-- Bootstrap Core CSS -->
    <link href="bootstrap-5.0.0/css/bootstrap.min.css" rel="stylesheet">
    <!--Datatable-->
    <!--<link href="js/plugins/datatable/datatables.min.css" rel="stylesheet">-->
    <link href="css/plugins/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/cennormandie.css" rel="stylesheet">
    <link href="css/autocompleteBS.css" rel="stylesheet">
    <!--FONT AWESOME-->
    <link href="fontawesome-free-5.15.2-web/css/all.css" rel="stylesheet" type="text/css">
  </head>
  <body>
<div class="d-flex w-100 h-100 bg-light" style="min-height:100vh;">
    <?php $_POST["page"] = basename(__FILE__);include("menu.php"); ?>
    <div class="d-flex flex-column col-md-9 col-lg-10 h-100 bg-light" style="overflow-y:auto;overflow-x:hidden;min-height:100vh;">
        <div class="d-flex justify-content-end w-100 bg-dark">
            <div class="m-2"><span class="text-light"><i class="fas fa-user"></i> <?php echo $_SESSION['email']; ?></span></div>
            <div class="m-2"><a class="logout text-light" href="php/logout.php" ><i class="fa fa-fw fa-power-off"></i> Déconnexion</a></div>
        </div>
        <div class="d-flex justify-content-arround w-100 bg-light m-2 border-bottom ">
            <h3 class="">VisuDGFIP</h3>
            <div id="loader" class=" bg-success loader mx-4 d-flex flex-wrap align-content-center flex-grow-1 visible_s" style="margin-bottom: .5rem">
            </div>
        </div>
        
        <div class="d-flex bg-light w-100">
            <div class="d-flex flex-column bg-light w-25 m-2">
                <div class="mb-3 autocompleteBS">
                    <label for="autocompleteAdmin" class="form-label">Communes :</label>
                    <input type="text" class="form-control" id="autocompleteAdmin" placeholder="Caen, Basly, ...">
                </div>
                <div class="">
                    <p class="text-success"><strong>A</strong> - Recherchez une commune</p>
                    <p class="text-success"><strong>B</strong> - Si à la fin du chargement rien ne s'affiche zoomez sur la carte</p>
                    <p class="text-success"><strong>C</strong> - Cliquez sur une parcelle pour afficher les propriétaires dans le tableau ci-dessous</p>
                    <p class="text-success"><strong>D</strong> - Il est possible d'exporter le tableau au format Excel</p>
                </div>
                <div class="">
                    <p class="text-muted">Sources : </p>
                    <!--<p class="text-muted">PCI Vecteur <strong  class="fs-5">(10-2020)</strong></p>-->
                    <p class="text-muted">Direction Générale des Finances <strong class="fs-5">2022</strong></p>
                </div>
            </div>
            <div class="d-flex flex-column bg-light w-75">
                <div id="map" style="min-width: 70%; height: 500px; position: relative;" class="leaflet-container leaflet-fade-anim" tabindex="0">
                </div>
            </div>
        </div>
        <div class="d-flex flex-column bg-light w-100 p-4">
            <div id="table_wrapper" class="table-responsive" >
                    <table id="ola_dt" class="table table-bordered table-hover display compact datatable-font-small"><!-- dt-responsive-->
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
                <div class="d-flex justify-content-end my-4">
                    <button id="clear_table" class="btn btn-danger">Vider le tableau</button>
                </div>
        </div>
        <div class="d-flex mt-auto justify-content-end align-items-center text-muted fixed-right" style="">
            <kbd class="small">CEN Normandie © <?php echo date("Y"); ?></kbd>
        </div>
    </div>

</div>


<script src="js/jquery.js" ></script>
<!-- Bootstrap Core JavaScript -->
<script src="bootstrap-5.0.0/js/bootstrap.min.js"></script>
<!-- FONT AWESOME -->
<script src="fontawesome-free-5.15.2-web/js/fontawesome.min.js" ></script>
<!-- DATATABLES -->
<script src="js/plugins/datatable/datatables.min.js"></script>
<script src="js/plugins/datatable/dataTables.bootstrap5.min.js"></script>
<!--Leaflet-->
<script src="js/leaflet1.7/leaflet.js" ></script>
<script src="js/init_leaflet.js" ></script>

<!--Custom-->
<script src="js/majiic.js" ></script>
<script src="js/autocompleteBS5/autocompleteBS5.js" ></script>
<script src="js/autocompleteBS5/com_parcelle.config.js" ></script>

<script >
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
dom: 't<"bottom"<"d-flex justify-content-between align-items-center"pB>>',
//buttons: [ 'copy', 'excel', 'pdf' ]
buttons: [
    { 
    extend: 'excel', 
    text:'Excel',
    className: 'btn btn-success m-2',
    init: function(api, node, config) {
       $(node).removeClass('dt-button')
    }
    }
    ]
});

window.onload = function () {

}
</script>




</body>
</html>
