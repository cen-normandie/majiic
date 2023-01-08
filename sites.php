<!DOCTYPE html>
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
    <link href="js/leaflet/plugins/leaflet_label/css/leafleat_label.css" rel="stylesheet" type="text/css">
    <link href="css/custom_leaflet.css" rel="stylesheet" type="text/css">
    <!-- Bootstrap Core CSS -->
    <link href="bootstrap-5.0.0/css/bootstrap.min.css" rel="stylesheet">
    <!--Datatable-->
    <link href="css/plugins/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/cennormandie.css" rel="stylesheet">
    <link href="css/autocomplete.sites.css" rel="stylesheet">
    <!--FONT AWESOME-->
    <link href="fontawesome-free-5.15.2-web/css/all.css" rel="stylesheet" type="text/css">
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
    <div class="d-flex flex-column col-md-9 col-lg-10 h-100 bg-light" style="overflow-y:auto;overflow-x:hidden;min-height:100vh;">
        <div class="d-flex justify-content-end w-100 bg-dark">
            <div class="m-2"><span class="text-light"><i class="fas fa-user"></i> <?php echo $_SESSION['email']; ?></span></div>
            <div class="m-2"><a class="logout text-light" href="php/logout.php" ><i class="fa fa-fw fa-power-off"></i> Déconnexion</a></div>
        </div>
        <div class="d-flex justify-content-arround w-100 bg-light m-2 border-bottom ">
            <h3 class="">Consultation de site</h3>
            <div id="loader" class=" bg-success loader mx-4 d-flex flex-wrap align-content-center flex-grow-1 visible_s" style="margin-bottom: .5rem">
            </div>
        </div>
        
        
        <div class="d-flex bg-light w-100">
            <div class="d-flex flex-column bg-light w-75 p-2">
                <div id="map" style="min-width: 60%; height: 500px; position: relative;" class="leaflet-container leaflet-fade-anim p-2" tabindex="0">
                </div>
            </div>
            <div class="d-flex flex-column bg-light w-25 p-2">
                <div class="mb-3 autocompleteBS">
                    <label for="input_site" class="form-label">Sélectionnez un site du Conservatoire : </label>
                    <div class="input-group" disabled>
                        <input class="form-control" id="input_site" type="text" size="60" placeholder="ex: Lande Mouton">
                        <span class="input-group-text justify-content-center col-2" id="del"><i class="far fa-trash-alt"></i></span>
                    </div>
                </div>
                <div class="mb-3">
                    <ul class="list-group"  id="parcelles" >
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light" >
                            Nombre de parcelles :<span  id="nb_parcelles" class="badge bg-success">...</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light" >
                            Surface :<span  id="sum_surface" class="badge bg-success">...</span>
                        </li>
                    </ul>
                </div>
                <ul class="list-group">
                    <li id="list_docs" class="list-group-item d-none">
                        <div id="doc_refs" class="d-flex flex-wrap align-items-start">
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="list-group-item" >
        <!--<div class="table-responsive list-group">-->
            <div>
                <button id="updateTableauParcelle" class="btn btn-outline-dark" type="button">Actualiser le tableau des parcelles</button>
            </div>
            <table id="parcelles_dt" class="table table-striped table-hover display compact w-100 p-2"><!-- dt-responsive-->
                <thead>
                    <tr>
                        <th>ID Site</th>
                        <th>DEP</th>
                        <th>INSEE</th>
                        <th>Prefixe</th>
                        <th>Section</th>
                        <th>Numero</th>
                        <th>Doc Référence</th>
                        <th>Convention</th>
                        <th>Acquisition</th>
                        <th>Doc Gestion</th>
                        <th>ORE</th>
                        <th>Surface (ha)</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        <!--</div>-->
        </div>
        <div class="d-flex mt-auto justify-content-end align-items-center text-muted fixed-right">
            <kbd class="small">CEN Normandie © 2023</kbd>
        </div>
    </div>

<div class="d-none" id="absolute_path" ><?php echo $root_; ?></div>
<div class="d-none" id="cur_dir" ><?php echo $dir_; ?></div>
<div class="d-none" id="sep" ><?php echo DIRECTORY_SEPARATOR; ?></div>

</div>



<script src="js/jquery.js" ></script>
<!-- Bootstrap Core JavaScript -->
<script src="bootstrap-5.0.0/js/bootstrap.min.js"></script>
<!-- FONT AWESOME -->
<script src="fontawesome-free-5.15.2-web/js/fontawesome.min.js" ></script>
<!-- DATATABLES -->
<script type="text/javascript" src="js/plugins/datatable/datatables.min.js"></script>
<script src="js/plugins/datatable/dataTables.bootstrap5.min.js"></script>
<!--Leaflet-->
<script src="js/leaflet1.7/leaflet.js" ></script>
<script src="js/leaflet1.7/leaflet_label/js/leaflet_label.js" ></script>
<script src="js/leaflet/leaflet_add_function.js" ></script>
<script src="js/init_leaflet.sites.js" ></script>

<!-- general.js -->
<script type="text/javascript" src="js/general/general.js" ></script>

<!--Custom-->
<script type="text/javascript" src="js/autocompleteArray/autocomplete.dashboard.js" ></script>
<script src="js/sites.js" ></script>
<!--<script src="js/autocompleteBS5/autocompleteBS5.js" ></script>-->
<!--<script src="js/autocompleteBS5/page_sites.config.js" ></script>-->





<script >
//GET PATH
const sep = $('#sep').html();
const absolute_path = $('#absolute_path').html();
const cur_dir = $('#cur_dir').html();
// console.log(absolute_path+cur_dir);

const keys = {
    "id_site" : ["null","id"]
};
//observeur de filtres actifs
let filters_active = {
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
};

</script>




</body>
</html>
