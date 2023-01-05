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
    <link href="css/autocomplete.sites.css" rel="stylesheet">
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
                <h2 class="bebas">Creation de projet</h2>
                <div id="loader" class=" bg-success loader mx-4 d-flex flex-wrap align-content-center flex-grow-1 visible_s" style="margin-bottom: .5rem"></div>
            </div>
            
            <div class="d-flex bg-light ">
                <div class="d-flex flex-column col-md-4 col-lg-3 m-2">
                    <div class="input-group input-group-sm py-2">
                        <span class="input-group-text" id="basic-addon1">Nom du projet : </span>
                        <input id="nom_projet" type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="" value="">
                    </div>
                    <div class="input-group input-group-sm py-2">
                        <label class="input-group-text" for="l_type_projet">Responsable : </label>
                        <select class="form-select" id="responsable_projet" >
                            <?php echo $responsables_html ?>
                        </select>

                        

                    </div>
                    <div class="input-group input-group-sm py-2">
                        <label class="input-group-text" for="l_type_projet">Type de projet : </label>
                        <select class="form-select" id="type_projet" >
                            <option value="Programme Régional" >Programme Régional</option>
                            <option value="Suivi ENS" >Suivi ENS</option>
                            <option value="Gestion site" >Gestion site</option>
                            <option value="Étude" >Étude</option>
                            <option value="Programme de conservation" >Programme de conservation</option>
                            <option value="Animation site Natura 2000" >Animation site Natura 2000</option>
                            <option value="Contrat Natura 2000" >Contrat Natura 2000</option>
                            <option value="Animation territoral SRCE/TVB" >Animation territoral SRCE/TVB</option>
                            <option value="Accompagnement à la gestion-suivi" >Accompagnement à la gestion-suivi</option>
                            <option value="Interne CEN" >Interne CEN</option>
                            <option value="Mesure compensatoire" >Mesure compensatoire</option>
                            <option value="Expertise scientifique" >Expertise scientifique</option>
                            <option value="Autre" >Autre</option>
                        </select>
                    </div>
                    <div class="input-group input-group-sm py-2">
                        <label class="input-group-text" for="l_etat_projet">État du projet : </label>
                        <select class="form-select" id="etat_projet" >
                            <option value="Prévisionnel" >Prévisionnel</option>
                            <option value="Validé" >Validé</option>
                        </select>
                    </div>
                    <div class="input-group input-group-sm py-2">
                        <label class="input-group-text" for="l_echelle_projet">Échelle : </label>
                        <select class="form-select" id="echelle_projet" >
                            <option value="Commune" >Commune</option>
                            <option value="Département" >Département</option>
                            <option value="EPCI" >EPCI</option>
                            <option value="Régionale" >Régionale</option>
                            <option value="Site" >Site</option>
                            <option value="Multi-site" >Multi-site</option>
                            <option value="ENS" >ENS</option>
                        </select>
                    </div>
                    <div class="input-group input-group-sm py-2">
                        <span class="input-group-text" id="l_p_date_start">Date Début<i class="fas fa-calendar-alt mx-2"></i> : </span>
                        <input class="form-control" id="p_date_start" placeholder="04-04-2004" aria-label="" aria-describedby="l_p_date_start" value="01-01-2023" >
                    </div>
                    <div class="input-group input-group-sm py-2">
                        <span class="input-group-text" id="l_p_date_end">Date Fin<i class="fas fa-calendar-alt mx-2"></i> : </span>
                        <input class="form-control" id="p_date_end" placeholder="08-08-2088" aria-label="" aria-describedby="l_p_date_end" value="31-12-2023" >
                    </div>
                    <div class="input-group input-group-sm py-2">
                        <span for="commentaire" class="input-group-text">Commentaire</span>
                        <textarea class="form-control" id="p_commentaire" rows="2" ></textarea>
                    </div>
                    <div class="input-group input-group-sm py-2">
                        <label for="p_color" class="input-group-text">Couleur</label>
                        <input type="color" class="form-control form-control-color" id="p_color" value="#563d7c" title="Choisissez une couleur" aria-describedby="helpColor" >
                        <span id="helpColor" class="form-text px-2">couleur utilisée pour le calendrier</span>
                    </div>
                    <div>
                        <button id="save_projet" type="button" class="btn btn-outline-success shadow my-1 "><i class=""></i> Enregistrer</button>
                    </div>
                </div>
                

                
            </div>	
		</div>

		<div class="d-flex mt-auto justify-content-end align-items-center text-muted fixed-bottom">
			<kbd class="small">CEN Normandie © 2021</kbd>
		</div>
	</div>

</div>


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
<script src="js/plugins/datatable/dataTables.min.js"></script>
<script src="js/plugins/datatable/dataTables.bootstrap5.min.js"></script>
<!-- <script src="js/plugins/datatable/jquery.dataTables.min.js"></script> -->
<script src="js/plugins/datatable/Buttons-1.7.0/js/dataTables.buttons.min.js"></script>
<script src="js/plugins/datatable/Buttons-1.7.0/js/buttons.bootstrap5.min.js"></script>
<script src="js/plugins/datatable/pdfmake-0.1.36/vfs_fonts.js"></script>
<script src="js/plugins/datatable/Buttons-1.7.0/js/buttons.html5.min.js"></script>

<!-- general.js -->
<script type="text/javascript" src="js/general/general.js" ></script>
<!-- Empty.js -->
<script type="text/javascript" src="js/create_projet.js" ></script>
<script type="text/javascript">


$(document).ready(function() {

});




</script>

  </body>
</html>
