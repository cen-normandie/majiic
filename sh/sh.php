<?php session_start(); 
include '../php/properties.php';
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
  <body>


<div class="d-flex w-100 h-100 bg-light" style="min-height:100vh;">
    <?php $_POST["page"] = basename(__FILE__);include("../menu.php"); ?>
	<div class="d-flex flex-column col-md-9 col-lg-10 bg-light " >
		<div class="d-flex justify-content-end  bg-dark sticky-top">
			<div class="m-2"><span class="text-light"><i class="fas fa-user"></i> <?php echo $_SESSION['email']; ?></span></div>
			<div class="m-2"><a class="logout text-light" href="../php/logout.php" ><i class="fa fa-fw fa-power-off"></i> Déconnexion</a></div>
		</div>
        <div class="d-flex flex-column justify-content-end" style="">
            <div class="d-flex justify-content-start bg-light m-2 border-bottom ">
                <h2 class="">Tableau de bord</h2>
                <div id="loader" class=" bg-success loader mx-4 d-flex flex-wrap align-content-center flex-grow-1 visible_s" style="margin-bottom: .5rem"></div>
            </div>
            
            <div class="d-flex bg-light ">
                <div class="d-flex flex-column bg-light w-25 m-2">
                    
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


		<div class="d-flex mt-auto justify-content-end align-items-center text-muted fixed-bottom">
			<kbd class="small">CEN Normandie © <?php echo date("Y"); ?></kbd>
		</div>
        <div class="d-none" id="absolute_path" ><?php echo $root_; ?></div>
        <div class="d-none" id="dir_from_localhost" ><?php $docroot_; ?></div>
        <div class="d-none" id="cur_dir" ><?php echo $dir_; ?></div>
        <div class="d-none" id="sep" ><?php echo DIRECTORY_SEPARATOR; ?></div>
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
                <strong>Attention :</strong> Les informations foncières sont actualisées en fonction des documents transmis, si problème :
                    Contactez le service SIG --> foncier@cen-normandie.fr
			</div>
		</div>
	</div>
    <!-- TEST TOAST #################################################### -->


</div>


<script src="../js/jquery.js" ></script>
<!-- Bootstrap Core JavaScript -->
<script src="bootstrap-5.0.0/js/bootstrap.min.js"></script>
<script src="../js/plugins/bs5-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="../js/plugins/bs5-datepicker/locales/bootstrap-datepicker.fr.min.js"></script>
<!-- FONT AWESOME -->
<script src="fontawesome-free-5.15.2-web/js/fontawesome.min.js" ></script>
<!-- HIGHCHARTS -->
<script type="text/javascript" src="../js/plugins/highcharts/code/highstock.js"></script>
<script type="text/javascript" src="../js/plugins/highcharts/code/modules/exporting.js"></script>  

<!--Datatable bs5-->
<script src="../js/plugins/datatable/datatables.min.js"></script>
<script src="../js/plugins/datatable/dataTables.bootstrap5.min.js"></script>
<!-- <script src="../js/plugins/datatable/jquery.datatables.min.js"></script> -->
<script src="../js/plugins/datatable/Buttons-1.7.0/js/dataTables.buttons.min.js"></script>
<script src="../js/plugins/datatable/Buttons-1.7.0/js/buttons.bootstrap5.min.js"></script>
<script src="../js/plugins/datatable/pdfmake-0.1.36/vfs_fonts.js"></script>
<script src="../js/plugins/datatable/Buttons-1.7.0/js/buttons.html5.min.js"></script>

<!-- general.js -->
<script type="text/javascript" src="../js/general/general.js" ></script>
<!-- sh.js -->
<script type="text/javascript" src="../js/sh.js" ></script>
<script type="text/javascript">



$(document).ready(function() {


change_load("Chargement des données");
//TEST TOAST ####################################################
});




</script>

  </body>
</html>