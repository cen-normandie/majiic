<?php session_start(); ?>
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
                <h2 class="">Import temps Excel</h2>
                <div id="loader" class=" bg-success loader mx-4 d-flex flex-wrap align-content-center flex-grow-1 visible_s" style="margin-bottom: .5rem"></div>
            </div>
            
            <div class="d-flex flex-column bg-light p-2 w-100">
                <div class="alert alert-info" role="alert">
                    Cette section permets de charger un excel pour une optimisation des temps.
                    Vos temps saisis existants seront supprimés. Seules les données du fichier Excel seront importées.
                    Attention toutefois cette opération n'est <span class="fw-bold">pas réversible</span> !
                    <ul class="list-group my-2">
                        <li class="list-group-item"><strong class="fs-5">A</strong> - Exportez vos temps existants :
                            
                                <!--<button id="export_excel_temps" type="button" class="btn btn-primary"><i class="fas fa-file-excel px-2"></i>Export de mes temps</button>-->
                                <button id="export_2021" type="button" class="btn btn-outline-primary shadow my-2 mx-4 "><i class="fas fa-file-excel pr-2"></i> 2021</button>
                                <button id="export_2022" type="button" class="btn btn-outline-primary shadow my-2 mx-4"><i class="fas fa-file-excel pr-2"></i> 2022</button>
                                <button id="export_2023" type="button" class="btn btn-outline-primary shadow my-2 mx-4"><i class="fas fa-file-excel pr-2"></i> 2023</button>
                            
                        </li>
                        <li class="list-group-item"><strong class="fs-5">B</strong> - Téléchargez la liste des codes projets <--> codes actions <--> codes sites servant de référentiel :
                            
                                <!--<button id="export_excel_temps" type="button" class="btn btn-primary"><i class="fas fa-file-excel px-2"></i>Export de mes temps</button>-->
                                <button id="anal_2021" type="button" class="btn btn-outline-warning  shadow my-2 mx-4 "><i class="fas fa-file-excel pr-2"></i> 2021</button>
                                <button id="anal_2022" type="button" class="btn btn-outline-warning  shadow my-2 mx-4"><i class="fas fa-file-excel pr-2"></i> 2022</button>
                                <button id="anal_2023" type="button" class="btn btn-outline-warning  shadow my-2 mx-4"><i class="fas fa-file-excel pr-2"></i> 2023</button>
                            
                        </li>
                        <li class="list-group-item"><strong class="fs-5">C</strong> - Faites vos modifications sur le fichier excel en conservant la structure ainsi que les codes du projet et les codes des actions </li>
                        <li class="list-group-item"><strong class="fs-5">D</strong> - Importez vos temps optimisés :<p class="text-danger"> Attention à l'année !</p>
                            <div class="d-flex flex-column col-sm-12 col-md-12 col-lg-6">
                                <div class="input-group my-2">
                                    <label class="input-group-text" for="year_replace">Année : </label>
                                    <select class="form-select" id="year_replace" >
                                        <option value="2021">2021</option>
                                        <option value="2022">2022</option>
                                        <option value="2023" selected>2023</option>
                                    </select>
                                </div>
                                <div class="input-group my-2">
                                    <input id="input_file" accept=".xls, .xlsx" type="file" class="form-control" placeholder="" aria-label="load_file" aria-describedby="" value="Ajouter un fichier">
                                    <button id="load_file" type="button" class="btn btn-success" ><i class="fas fa-file mx-2"></i>Importer le fichier </button>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

                <div id="lines_import" class="d-flex flex-wrap my-2" style="max-height:300px;overflow-y:scroll">
                </div>
                <div id="lines_import_error" class="d-flex flex-column my-2">
                </div>
            </div>
		</div>

		<div class="d-flex mt-auto justify-content-end align-items-center text-muted fixed-bottom">
			<kbd class="small">CEN Normandie © <?php echo date("Y"); ?></kbd>
		</div>
	</div>

</div>

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
<script type="text/javascript" src="js/plugins/highcharts/code/highstock.js"></script>
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
<!-- Empty.js -->
<script type="text/javascript" src="js/import_excel.js" ></script>
<script type="text/javascript">


$(document).ready(function() {

});




</script>

  </body>
</html>
