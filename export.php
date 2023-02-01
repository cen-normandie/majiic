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
    <link href="css/leaflet.css" rel="stylesheet" type="text/css">
    <link href="js/leaflet/plugins/leaflet_label/css/leafleat_label.css" rel="stylesheet" type="text/css">
    <!-- Bootstrap Core CSS -->
    <!--<link href="css/bootstrap.min.css" rel="stylesheet">-->
    <link href="js/plugins/bs5-datepicker/css/bootstrap-datepicker.standalone.min.css" rel="stylesheet">
	<link href="bootstrap-5.0.0/css/bootstrap.min.css" rel="stylesheet">
    <!--Datatable-->
	<!--<link href="js/plugins/datatable/datatables.min.css" rel="stylesheet">-->
    <!-- Custom CSS -->
    <link href="css/cennormandie.css" rel="stylesheet">
    <!--FONT AWESOME-->
    <link href="fontawesome-free-5.15.2-web/css/all.css" rel="stylesheet" type="text/css">
    <!--Datatable bs5-->
    <!--<link href="css/plugins/twitter_bs5.css" rel="stylesheet">-->
    <link href="css/plugins/dataTables.bootstrap5.min.css" rel="stylesheet">
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
                <h2 class="bebas">Export des feuilles de temps</h2>
                <div id="loader" class=" bg-success loader mx-4 d-flex flex-wrap align-content-center flex-grow-1 visible_s" style="margin-bottom: .5rem"></div>
            </div>
            
            <div class="d-flex bg-light ">
                <div class="d-flex flex-column col-md-4 col-lg-3 m-2 p-2 border rounded">
                    <h4 class="h_background_image w-100 bebas d-flex justify-content-center">Export par annee</h4>
                    <div class="d-flex flex-column w-100">
                        <button id="export_2021" type="button" class="btn btn-outline-success shadow my-2 mx-4 "><i class="fas fa-file-excel pr-2"></i> 2021</button>
                        <button id="export_2022" type="button" class="btn btn-outline-success shadow my-2 mx-4"><i class="fas fa-file-excel pr-2"></i> 2022</button>
                        <button id="export_2023" type="button" class="btn btn-outline-success shadow my-2 mx-4"><i class="fas fa-file-excel pr-2"></i> 2023</button>
                    </div>
                </div>
                <div class="d-flex flex-column col-md-4 col-lg-3 m-2 p-2 border rounded">
                    <h4 class="h_background_image w-100 bebas d-flex justify-content-center">Export par personne</h4>
                    <div class="d-flex flex-column w-100">
                        <div class="autocompleteBS w-100" id="personnes">
                            <div class="input-group input-group-sm">
                                <span for="input_personnes" class="input-group-text">Personne : </span>
                                <input type="text" class="form-control" id="input_personnes" aria-describedby="basic-addon3" placeholder="...">
                                <span class="input-group-text justify-content-center" id="del_personne"><i class="far fa-trash-alt"></i></span>
                            </div>
                        </div>
                        <button id="export_p_2021" type="button" class="btn btn-outline-success shadow my-2 mx-4"><i class="fas fa-file-excel pr-2"></i> 2021</button>
                        <button id="export_p_2022" type="button" class="btn btn-outline-success shadow my-2 mx-4"><i class="fas fa-file-excel pr-2"></i> 2022</button>
                        <button id="export_p_2023" type="button" class="btn btn-outline-success shadow my-2 mx-4"><i class="fas fa-file-excel pr-2"></i> 2023</button>
                    </div>
                </div>
            </div>	
		</div>

		<div class="d-flex mt-auto justify-content-end align-items-center text-muted fixed-bottom">
			<kbd class="small">CEN Normandie © <?php echo date("Y"); ?></kbd>
		</div>
	</div>

</div>
<span id="c_resp" class="d-none" value="<?php echo $_SESSION['u_nom_user_progecen']  ; ?>"><?php echo $_SESSION['u_nom_user_progecen']  ; ?></span>
<span id="c_user" class="d-none" value="<?php echo $_SESSION['u_nom_user_progecen']  ; ?>"><?php echo $_SESSION['u_nom_user_progecen']  ; ?></span>

<script src="js/jquery.js" ></script>
<!-- Bootstrap Core JavaScript -->
<script src="bootstrap-5.0.0/js/bootstrap.min.js"></script>
<script src="js/plugins/bs5-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="js/plugins/bs5-datepicker/locales/bootstrap-datepicker.fr.min.js"></script>
<!-- FONT AWESOME -->
<script src="fontawesome-free-5.15.2-web/js/fontawesome.min.js" ></script>
<!-- LEAFLET -->
<script type="text/javascript" src="js/leaflet/leaflet.js"></script>
<script type="text/javascript" src="js/leaflet/plugins/leaflet_label/js/leaflet_label.js" ></script>
<!-- HIGHCHARTS -->
<script type="text/javascript" src="js/plugins/highcharts/code/highcharts.js"></script>
<script type="text/javascript" src="js/plugins/highcharts/code/modules/exporting.js"></script>  

<!--Datatable bs5-->
<script src="js/plugins/datatable/datatables.min.js"></script>
<script src="js/plugins/datatable/dataTables.bootstrap5.min.js"></script>
<!-- <script src="js/plugins/datatable/jquery.datatables.min.js"></script> -->
<script src="js/plugins/datatable/Buttons-1.7.0/js/dataTables.buttons.min.js"></script>
<script src="js/plugins/datatable/Buttons-1.7.0/js/buttons.bootstrap5.min.js"></script>
<script src="js/plugins/datatable/pdfmake-0.1.36/vfs_fonts.js"></script>
<script src="js/plugins/datatable/Buttons-1.7.0/js/buttons.html5.min.js"></script>

<!-- general.js -->
<script type="text/javascript" src="js/general/general.js" ></script>
<!-- autocomplete -->
<script type="text/javascript" src="js/autocompleteArray/autocomplete.personnes.js" ></script>
<!-- Export.js -->
<script type="text/javascript" src="js/export.js" ></script>
<script type="text/javascript">

$(document).ready(function() {
    load_personnes_ajax ();
});




</script>

  </body>
</html>
