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
    <!--<link href="css/leaflet.css" rel="stylesheet" type="text/css">-->
    <link href="js/leaflet1.7/leaflet.css" rel="stylesheet" type="text/css">
    <!--<link href="js/leaflet/plugins/leaflet_label/css/leafleat_label.css" rel="stylesheet" type="text/css">-->
    <!-- Bootstrap Core CSS -->
    <!--<link href="css/bootstrap.min.css" rel="stylesheet">-->
	<link href="bootstrap-5.0.0/css/bootstrap.min.css" rel="stylesheet">
    <!--Datatable-->
	<!--<link href="js/plugins/datatable/datatables.min.css" rel="stylesheet">-->
    <link href="css/plugins/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/cennormandie.css" rel="stylesheet">
	<link href="css/autocomplete.dashboard.css" rel="stylesheet">
    <!--FONT AWESOME-->
    <link href="fontawesome-free-5.15.2-web/css/all.css" rel="stylesheet" type="text/css">
    <!--Datatable bs5-->
    <!--<link href="css/plugins/twitter_bs5.css" rel="stylesheet">-->
  </head>
  <body>

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
$_SESSION['is_admin'] = false;
$admins = array("n.moreira@cen-normandie.fr", "c.bouteiller@cen-normandie.fr", "f.buissart@cen-normandie.fr", "b.perceval@cen-normandie.fr");

if (in_array($_SESSION['email'], $admins)) {
    $_SESSION['is_admin'] = true;
}
?>


<div class="d-flex w-100 h-100 bg-light" style="min-height:100vh;">
    <?php $_POST["page"] = basename(__FILE__);include("menu.php"); ?>
	<div class="d-flex flex-column col-md-9 col-lg-10 bg-light " >
		<div class="d-flex justify-content-end  bg-dark sticky-top">
			<div class="m-2"><span class="text-light"><i class="fas fa-user"></i> <?php echo $_SESSION['email']; ?></span></div>
			<div class="m-2"><a class="logout text-light" href="php/logout.php" ><i class="fa fa-fw fa-power-off"></i> Déconnexion</a></div>
		</div>
        <div class="d-flex flex-column justify-content-end" style="">
            <div class="d-flex justify-content-start bg-light m-2 border-bottom ">
                <h2 class="">Tableau de bord</h2>
                <div id="loader" class=" bg-success loader mx-4 d-flex flex-wrap align-content-center flex-grow-1 visible_s" style="margin-bottom: .5rem"></div>
            </div>
            
            <div class="d-flex bg-light ">
                <div class="d-flex flex-column bg-light w-25 m-2">
                    <div class="d-flex border mt-2 mb-2 shadow-sm">
                        <div class="d-flex flex-column bg-light p-2 col-sm-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="zh">
                                <label class="form-check-label" for="zh">Zone Humide</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="aesn">
                                <label class="form-check-label" for="aesn">AESN <span class="text-muted" style="font-size:11px">(Financement)</span></label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="doc_de_gestion">
                                <label class="form-check-label" for="doc_de_gestion">Doc de gestion</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="ens">
                                <label class="form-check-label" for="ens">ENS</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="acquisition">
                                <label class="form-check-label" for="acquisition">Acquisition</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="convention">
                                <label class="form-check-label" for="convention">Convention</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="mc">
                                <label class="form-check-label" for="mc">Mesure Compe.</label>
                            </div>
                        </div>
                        <!-- 2nd Column -->
                        <div class="d-flex flex-column bg-light p-2 col-sm-6">
                            <p class="mb-0 text-muted fs-6">Départements :</p>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="dep" id="all" value="all" checked>
                                <label class="form-check-label" for="all">Tous</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="dep" id="calvados" value="14">
                                <label class="form-check-label" for="calvados">Calvados - 14</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="dep" id="eure" value="27">
                                <label class="form-check-label" for="eure">Eure - 27</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="dep" id="manche" value="50">
                                <label class="form-check-label" for="manche">Manche - 50</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="dep" id="orne" value="61">
                                <label class="form-check-label" for="orne">Orne - 61</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="dep" id="seine-maritime" value="76">
                                <label class="form-check-label" for="seine-maritime">Seine-M. - 76</label>
                            </div>
                        </div>
                    </div>
                    
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                            Nombre de Sites :
                            <span id="nb_sites" class="badge bg-success">...</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                            <span>Surface des Sites <span class="text-muted" style="font-size:11px">(Ha)</span></span>
                            <span id="surface" class="badge bg-success">...</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                            Sites sous convention(s)
                            <span id="sites_convention" class="badge bg-success">...</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                            Sites en acquisition
                            <span id="sites_acquisition"  class="badge bg-success">...</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                            Nombre de Parcelles :
                            <span id="nb_parcelles" class="badge bg-info">...</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                            Parcelles sous convention(s)
                            <span id="parcelles_convention" class="badge bg-info">...</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                            Parcelles en acquisition
                            <span id="parcelles_acquisition"  class="badge bg-info">...</span>
                        </li>
                    </ul>
                    <div class="mt-2 mb-2">
                        <!--<input id="testpicker" type="text" class="form-control">-->
                        <!--<input id="autocompleteOneSite" type="text" class="form-control" placeholder="Rechercher un site...">-->
                        <!--<select id="selectSite"></select>-->
                        <div class="input-group" disabled>
                            
                            <input id="input_site" type="text" class="form-control col-10 " placeholder="Rechercher un site..." aria-label="Rechercher un site..." aria-describedby="del" >
                            <span class="input-group-text justify-content-center col-2" id="del"><i class="far fa-trash-alt"></i></span>
                            
                        </div>
                    </div>
                    <ul class="list-group">
                        <li id="list_docs" class="list-group-item d-none">
                            <div id="doc_refs" class="d-flex flex-wrap align-items-start">
                            </div>
                        </li>
                    </ul>

                    
                </div>
                

                <div class="d-flex flex-column bg-light w-75 m-2">
                    <div class="d-flex w-100 p-2">
                        <div class="col-4">
                            <div id="map" style="min-width:100%; height: 300px;" class="leaflet-container leaflet-fade-anim" tabindex="0"></div>
                        </div>
                        <div class="col-4">
                            <div id="nbSite_Dep" class="my-2" style="height: 300px;"></div>
                        </div>
                        <div class="col-4">
                            <div id="surface_Dep" class="my-2" style="height: 300px;"></div>
                        </div> 
                    </div>
                    <div class="d-flex w-100">
                        <div class="col-4">
                            <div id="graph_typologie_site" class="my-2"></div>
                        </div>
                        <div class="col-4">
                            <div id="nb_site_ddg" class="my-2"></div>
                        </div>
                    </div>
                    
                    

                    
                </div>
            </div>	
		</div>
        <div class="d-flex flex-column justify-content-center bg-light shadow m-2 p-2">
                <h4 class="border-bottom">Tableau des sites</h4>
                <div class="d-flex m-0 w-100">
                    <table id="sites" class="table table-striped" style="width:100%" >
                        <thead>
                            <tr>
                            <th>Nom</th>
                            <th>Ha</th>
                            <th>Bassin</th>
                            <th>ZH</th>
                            <th>ENS</th>
                            <th>Dep</th>
                            <th>Acqui.</th>
                            <th>Conv.</th>
                            <th>UCG</th>
                            <th>Milieu</th>
                            <th>Ddg</th>
                            <th>Protection</th>
                            <th>Nb Parc</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
		</div>
        <div class="d-flex flex-column justify-content-center bg-light shadow m-2 p-2">
                <h4 class="border-bottom">Tableau des parcelles</h4>
                <div><button id="updateTableauParcelle" class="btn btn-outline-dark" type="button">Actualiser le tableau des parcelles</button></div>
                <div class="d-flex justify-content-start w-100">
                    <table id="parcelles" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                            <th>ID Site</th>
                            <th>Nom Site</th>
                            <th>ID Parcelle</th>
                            <th>Doc. Référence</th>
                            <th>Surface</th>
                            <th>Acqui.</th>
                            <th>Conv.</th>
                            </tr>
                        </thead>
                        <tbody class="parcelles">
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
		</div>

		<div class="d-flex mt-auto justify-content-end align-items-center text-muted fixed-bottom">
			<kbd class="small">CEN Normandie © 2021</kbd>
		</div>
	</div>



    <!-- TEST TOAST #################################################### -->
	<div class="position-fixed" style="z-index: 1111; bottom: 30px; right:10px;">
		<div id="ola" class="toast border border-success text-success">
			<div class="toast-header">
				<!-- <img src="..." class="rounded me-2" alt="..."> -->
                <i class="fas fa-exclamation-circle text-success p-1"></i>
				<strong class="me-auto">Notification</strong>
				<small><?php echo date("d-m-Y à H:i"); ?></small>
				<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
			</div>
			<div class="toast-body">
                <strong>Attention :</strong> la base de données foncière n'est pas finalisée :
                données en cours de saisie !
			</div>
		</div>
	</div>
    <!-- TEST TOAST #################################################### -->


</div>


<script src="js/jquery.js" ></script>
<!-- Bootstrap Core JavaScript -->
<script src="bootstrap-5.0.0/js/bootstrap.min.js"></script>
<script src="js/plugins/bs5-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="js/plugins/bs5-datepicker/locales/bootstrap-datepicker.fr.min.js"></script>
<!-- FONT AWESOME -->
<script src="fontawesome-free-5.15.2-web/js/fontawesome.min.js" ></script>
<!-- LEAFLET -->
<script type="text/javascript" src="js/leaflet1.7/leaflet.js"></script>
<script type="text/javascript" src="js/leaflet1.7/leaflet-src.js"></script>
<!--<script type="text/javascript" src="js/leaflet1.7/leaflet_label/js/leaflet_label.js" ></script>-->

<!-- LEAFLET CUSTOM -->
<script type="text/javascript" src="js/init_leaflet_dashboard.js"></script>


<!-- HIGHCHARTS -->
<script type="text/javascript" src="js/plugins/highcharts/code/highcharts.js"></script>
<script type="text/javascript" src="js/plugins/highcharts/code/modules/exporting.js"></script>  

<!--Datatable bs5-->
<script src="js/plugins/datatable/dataTables.min.js"></script>
<script src="js/plugins/datatable/dataTables.bootstrap5.min.js"></script>
<!-- <script src="js/plugins/datatable/jquery.dataTables.min.js"></script> -->
<script src="js/plugins/datatable/Buttons-1.7.0/js/dataTables.buttons.min.js"></script>
<script src="js/plugins/datatable/Buttons-1.7.0/js/buttons.bootstrap5.min.js"></script>
<script src="js/plugins/datatable/pdfmake-0.1.36/vfs_fonts.js"></script>
<script src="js/plugins/datatable/Buttons-1.7.0/js/buttons.html5.min.js"></script>

<!-- general.js -->
<script type="text/javascript" src="js/general/general.js" ></script>
<!-- dashboard.js -->
<script type="text/javascript" src="js/autocompleteArray/autocomplete.dashboard.js" ></script>
<script type="text/javascript" src="js/dashboard_graph.js" ></script>
<script type="text/javascript" src="js/dashboard.js" ></script>
<script type="text/javascript">

//propriétés dans le json sites et valeur clef
const keys = {
    "zh" : [1,"is_zh"],
    "aesn" : [true,"is_aesn"],
    "ddg" : [1,"is_ddg"],
    "ens" : [1,"is_ens"],
    "acquisition" : [1,"is_acquisition"],
    "convention" : [1,"is_convention"],
    "mc" : [1,"is_mc"],
    "calvados" : ["14","dep"],
    "eure" : ["27","dep"],
    "manche" : ["50","dep"],
    "orne" : ["61","dep"],
    "seine-maritime" : ["76","dep"],
    "id_site" : ["null","id"]
};
//observeur de filtres actifs
let filters_active = {
    "zh":false,
    "aesn":false,
    "ddg":false,
    "ens":false,
    "acquisition":false,
    "convention":false,
    "mc":false,
    "calvados":false,
    "eure":false,
    "manche":false,
    "orne":false,
    "seine-maritime":false,
    "id_site":false
};

//Fonction général de  filtre
function filtre_obj(arr, requete) {
  var name_filter = requete;
  var field = keys[name_filter][1];
  var checkvalue = keys[name_filter][0];
  return arr.filter(function (el, idx) {
    //console.log(arr);
    if ( arr[idx][field] == keys[name_filter][0]) {
        //console.log(arr[idx][field]);
        return el;
    } else {
        return false;
    }
  })
}



$(document).ready(function() {


change_load("Chargement des données");
//chargement des sites
//cette fonction appelle ensuite load_parcelles_ajax
load_sites_ajax();



    //TEST TOAST ####################################################
    $('.toast').toast('show');
	// var myToastEl = document.getElementById('ola')
	// myToastEl.addEventListener('hidden.bs.toast', function () {
	// 	console.log("Hide");
	// });
	// ##############################################################



});




</script>

  </body>
</html>
