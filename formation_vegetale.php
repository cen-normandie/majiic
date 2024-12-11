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
$admins = array("b.perceval@cen-normandie.fr");
if (in_array($_SESSION['email'], $admins)) {
    $_SESSION['is_admin'] = true;
}
?>
<!doctype html>
<html lang="fr">
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
    <link href="js/leaflet-1.9.4/leaflet.css" rel="stylesheet" type="text/css">
    <!--LEAFLET DRAW-->
    <link href="js/Leaflet.draw/src/leaflet.draw.css" rel="stylesheet" type="text/css" >
    <!-- Bootstrap Core CSS -->
    <!--<link href="css/bootstrap.min.css" rel="stylesheet">-->
    <link href="js/plugins/bs5-datepicker/css/bootstrap-datepicker.standalone.min.css" rel="stylesheet">
	<link href="bootstrap-5.0.0/css/bootstrap.min.css" rel="stylesheet">
    <!--Datatable-->
	<!--<link href="js/plugins/datatable/datatables.min.css" rel="stylesheet">-->
    <!-- Custom CSS -->
    <link href="css/cennormandie.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <!--FONT AWESOME-->
    <link href="fontawesome-free-5.15.2-web/css/all.css" rel="stylesheet" type="text/css">
    <!--Datatable bs5-->
    <!--<link href="css/plugins/twitter_bs5.css" rel="stylesheet">-->
    <link href="css/plugins/dataTables.bootstrap5.min.css" rel="stylesheet">
  </head>
  <body>
<div class="d-flex w-100 h-100 bg-light" style="min-height:100vh;">
  <?php $_POST["page"] = basename(__FILE__);include("menu.php"); ?>
	<div class="d-flex flex-column col-md-9 col-lg-10 bg-light " >
		<div class="d-flex justify-content-end  bg-dark sticky-top">
			<div class="m-2"><span class="text-light"><i class="fas fa-user"></i> <span id="courriel" class="text-light"> <?php echo $_SESSION['email']; ?></span></span></div>
			<div class="m-2"><a class="logout text-light" href="php/logout.php" ><i class="fa fa-fw fa-power-off"></i> Déconnexion</a></div>
		</div>
        <div class="d-flex flex-column justify-content-end" style="">
            <div class="d-flex justify-content-start bg-light m-2 border-bottom ">
                <h2 class="bebas">Formation vegetale</h2>
                <div id="loader" class=" bg-success loader mx-4 d-flex flex-wrap align-content-center flex-grow-1 visible_s" style="margin-bottom: .5rem"></div>
            </div>
            
            <div class="d-flex bg-light ">
                <div class="d-flex justify-content-around w-100" style="">
                  <div id="map" style="width:60%;height:600px;" class="leaflet-container leaflet-fade-anim" tabindex="0" style="position:relative;" ></div>
                  <div id="form" style="width:40%;height:600px;" class="d-flex flex-column m-2 justify-content-between" style="" >
                    <div id="" style="" class="d-flex flex-column" tabindex="0" style="" >
                      <div class="input-group input-group-sm py-2" disabled="">
                        <span class="input-group-text justify-content-center col-3 bs-secondary fw-bold" >Code</span>  
                        <input class="form-control" id="code" type="text" size="60" placeholder="...">
                      </div>
                      <div class="input-group input-group-sm py-2" disabled="">
                        <span class="input-group-text justify-content-center col-3 bs-secondary fw-bold" >Typo</span>  
                        <input class="form-control" id="typo" type="text" size="60" placeholder="...">
                      </div>
                      <div class="input-group input-group-sm py-2" disabled="">
                        <span class="input-group-text justify-content-center col-3 bs-secondary fw-bold" >Nom complet (fr)</span>  
                        <input class="form-control" id="nom_complet" type="text" size="60" placeholder="...">
                      </div>
                      <div class="input-group input-group-sm py-2" disabled="">
                        <span class="input-group-text justify-content-center col-3 bs-secondary fw-bold" >Année</span>  
                        <input class="form-control" id="annee" type="text" size="60" placeholder="...">
                      </div>
                      <div class="input-group input-group-sm py-2" disabled="">
                        <span class="input-group-text justify-content-center col-3 bs-secondary fw-bold" >Observateur</span>  
                        <input class="form-control" id="observateur" type="text" size="60" placeholder="...">
                      </div>
                      <div class="input-group input-group-sm py-2" disabled="">
                        <span class="input-group-text justify-content-center col-3 bs-secondary fw-bold" >Jeu de données Carto</span>  
                        <input class="form-control" id="jdd_carto" type="text" size="60" placeholder="...">
                      </div>
                      <ul id="list_docs" class="mb-3 list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light xxs p-2"><span class=" fs-6 ">Taxons (associés si existants):</span></li>
                          <li class="list-group-item">
                            <div id="taxons_" class="d-flex flex-wrap align-items-start" style="max-height:150px;overflow-y:scroll;">
                            </div>
                          </li>
                        </li>
                      </ul>
                    </div>
                    <div class="d-flex justify-content-end">
                      <div><span class="fw-bold fs-6">28</span> : <span class="fst-italic">Prodrome PVF2</span> | <span class="fw-bold fs-6">7</span> : <span class="fst-italic">EUNIS 2012</span> | <span class="fw-bold fs-6">100</span> : <span class="fst-italic">Eurovegchecklist 2016</span> | <span class="fw-bold fs-6">107</span> : <span class="fst-italic">EUNIS 2022</span></div>
                    </div>
                  </div>
                </div>  
            </div>	
		</div>

		<div class="d-flex mt-auto justify-content-end align-items-center text-muted fixed-bottom">
			<kbd class="small">CEN Normandie © <?php echo date("Y"); ?></kbd>
		</div>
	</div>

</div>

<!-- Modal -->
<div class="modal p-0" id="modalHabitat" tabindex="-1" aria-labelledby="modalHabitatLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalHabitatLabel">Selection de la formation végétale</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="autocompleteBS m-2">
            <div class="input-group w-100">
                <span for="input_hab" class="input-group-text">Formation Végétale : </span>
                <input type="text" class="form-control" id="input_hab" aria-describedby="basic-addon3">
            </div>
            </div>
            <div class="m-2">
            <div class="input-group w-100">
                <span for="input_observateur" class="input-group-text">Observateur : </span>
                <input type="text" class="form-control" id="input_observateur" aria-describedby="basic-addon3">
            </div>
            </div>
          <div class="autocompleteBS mt-4 mx-2">
            <div class="input-group input-group-sm w-75">
                <span for="input_hab_b" class="input-group-text">Formation Végétale 2: </span>
                <input type="text" class="form-control" id="input_hab_b" aria-describedby="basic-addon3">
            </div>
            </div>
            <div class="autocompleteBS mt-4 mx-2">
            <div class="input-group input-group-sm w-75">
                <span for="input_hab_c" class="input-group-text">Formation Végétale 3: </span>
                <input type="text" class="form-control" id="input_hab_c" aria-describedby="basic-addon3">
            </div>
            </div>
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="button" class="btn btn-success" id="save_hab">Enregistrer</button>
        </div>
        </div>
    </div>
</div>


<!--JQUERY-->
<script src="js/jquery-3.7.1.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="bootstrap-5.0.0/js/bootstrap.min.js"></script>
<!--Autocomplete Habitat -->
<script src="js/autocomplete_habitat.js"></script>
<!-- FONT AWESOME -->
<script src="fontawesome-free-5.15.2-web/js/fontawesome.min.js" ></script>
<!-- LEAFLET -->
<script src="js/leaflet-1.9.4/leaflet.js"></script>
<!-- LEAFLET DRAW -->
<script src="js/Leaflet.draw/src/Leaflet.draw.js"></script>
<script src="js/Leaflet.draw/src/Leaflet.Draw.Event.js"></script>
<script src="js/Leaflet.draw/src/Toolbar.js"></script>
<script src="js/Leaflet.draw/src/Tooltip.js"></script>
<script src="js/Leaflet.draw/src/ext/GeometryUtil.js"></script>
<script src="js/Leaflet.draw/src/ext/LatLngUtil.js"></script>
<script src="js/Leaflet.draw/src/ext/LineUtil.Intersect.js"></script>
<script src="js/Leaflet.draw/src/ext/Polygon.Intersect.js"></script>
<script src="js/Leaflet.draw/src/ext/Polyline.Intersect.js"></script>
<script src="js/Leaflet.draw/src/ext/TouchEvents.js"></script>
<script src="js/Leaflet.draw/src/draw/DrawToolbar.js"></script>
<script src="js/Leaflet.draw/src/draw/handler/Draw.Feature.js"></script>
<script src="js/Leaflet.draw/src/draw/handler/Draw.SimpleShape.js"></script>
<script src="js/Leaflet.draw/src/draw/handler/Draw.Polyline.js"></script>
<script src="js/Leaflet.draw/src/draw/handler/Draw.Marker.js"></script>
<script src="js/Leaflet.draw/src/draw/handler/Draw.Circle.js"></script>
<script src="js/Leaflet.draw/src/draw/handler/Draw.CircleMarker.js"></script>
<script src="js/Leaflet.draw/src/draw/handler/Draw.Polygon.js"></script>
<script src="js/Leaflet.draw/src/draw/handler/Draw.Rectangle.js"></script>
<script src="js/Leaflet.draw/src/edit/EditToolbar.js"></script>
<script src="js/Leaflet.draw/src/edit/handler/EditToolbar.Edit.js"></script>
<script src="js/Leaflet.draw/src/edit/handler/EditToolbar.Delete.js"></script>
<script src="js/Leaflet.draw/src/Control.Draw.js"></script>
<script src="js/Leaflet.draw/src/edit/handler/Edit.Poly.js"></script>
<script src="js/Leaflet.draw/src/edit/handler/Edit.SimpleShape.js"></script>
<script src="js/Leaflet.draw/src/edit/handler/Edit.Rectangle.js"></script>
<script src="js/Leaflet.draw/src/edit/handler/Edit.Marker.js"></script>
<script src="js/Leaflet.draw/src/edit/handler/Edit.CircleMarker.js"></script>
<script src="js/Leaflet.draw/src/edit/handler/Edit.Circle.js"></script>

<!-- general.js -->
<script type="text/javascript" src="js/general/general.js" ></script>
<!-- Init Leaflet -->
<script src="js/init_leaflet_formation_vegetale.js"></script>
<script type="text/javascript">


$(document).ready(function() {
    initmap();
    load_carto();
    load_habitat_ajax();
});




</script>

  </body>
</html>
