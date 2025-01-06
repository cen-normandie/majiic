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
    <!-- Calendar -->
    <link href='js/fullcalendar/lib/main.css' rel='stylesheet' />
    <!-- Custom CSS -->
    <link href="css/cennormandie.css" rel="stylesheet">
    <!--FONT AWESOME-->
    <link href="fontawesome-free-5.15.2-web/css/all.css" rel="stylesheet" type="text/css">
    <!--Datatable bs5-->
    <!--<link href="css/plugins/twitter_bs5.css" rel="stylesheet">-->
    <link href="css/plugins/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!--custom autocomplete-->
    <link href="css/autocomplete.dashboard.css" rel="stylesheet">
  </head>
  <body>

<div class="d-flex w-100 h-100 bg-light" style="min-height:100vh;">
  <?php $_POST["page"] = basename(__FILE__);include("menu.php"); ?>
	<div class="d-flex flex-column col-md-9 col-lg-10 bg-light " >
		<div class="d-flex justify-content-end  bg-dark sticky-top">
			<div class="m-2"><span class="text-light"><i class="fas fa-user"></i> <?php echo $_SESSION['email']; ?></span></div>
			<div class="m-2"><a class="logout text-light" href="php/logout.php" ><i class="fa fa-fw fa-power-off"></i> Déconnexion</a></div>
		</div>
        <div class="d-flex flex-column justify-content-end" style="">
            <div class="d-flex justify-content-start bg-light m-2 border-bottom">
                <h2 class="">Saisie Analytique</h2>
                <div id="loader" class=" bg-success loader mx-4 d-flex flex-wrap align-content-center flex-grow-1 " style="margin-bottom: .5rem">
                </div>
            </div>
            
            <div class="d-flex bg-light mb-5">
                
                <div class="d-flex flex-column bg-light w-50 p-2 ">
                    <div id="calendar" class="">
                    </div>
                    <div id="" class="d-flex col-4 align-items-end">
                        <div class="input-group input-group-sm p-2 ">
                            <span class="input-group-text" id="l_go_to_date">Go To Date<i class="fas fa-calendar-alt mx-2"></i> : </span>
                            <input class="form-control" id="go_to_date" placeholder="01-01-2024" aria-label="" aria-describedby="l_go_to_date" value="" >
                        </div>
                    </div>
                </div>
                
                <div id="panel" class="d-flex flex-column bg-light w-50">
                        <!--<div class="mb-3 autocompleteBS">
                            <label for="input_projet" class="form-label">Projet : </label>
                            <div class="input-group" disabled>
                                <input class="form-control" id="input_projet" type="text" size="60" placeholder="ex: PRAM 2021">
                                <span class="input-group-text justify-content-center col-2" id="del"><i class="far fa-trash-alt"></i></span>
                            </div>
                        </div>-->
                    <div class="d-flex flex-column px-2">
                        <h4>Ajouter un évènement :</h4>
                        <div class="input-group input-group-sm mb-3">
                            <span for="objet" class="input-group-text">Objet : </span>
                            <input type="text" class="form-control" id="input_objet"  value="..."></input><!-- onClick="this.select();"-->
                        </div>
                        <div class="input-group input-group-sm mb-3">
                            <span for="input_projet" class="input-group-text">Projet : </span>
                            <select type="text" class="form-control" id="input_projet" aria-describedby="">
                                <!--<option id="">A</option>
                                <option id="">B</option>-->
                            </select>
                        </div>
                        <div class="input-group input-group-sm mb-3">
                            <span for="input_action" class="input-group-text">Action : </span>
                            <select type="text" class="form-control" id="input_action" aria-describedby="">
                                <!--<option id="">A</option>
                                <option id="">B</option>-->
                            </select>
                        </div>
                        <!--<div class="input-group input-group-sm mb-3">
                            <span for="input_site" class="input-group-text">Site : </span>
                            <select type="text" class="form-control" id="input_site" aria-describedby="">
                            </select>
                        </div>-->
                        <div class="d-flex">
                            <div class="d-flex flex-column w-25">
                                <div class="form-check form-check-inline form-control-sm py-0">
                                    <input class="form-check-input form-check-input-sm" type="radio" name="Lieux" value="Bureau">
                                    <label class="form-check-label small" for="Lieu_Bureau">Bureau</label>
                                    </input>
                                </div>
                                <div class="form-check form-check-inline form-control-sm py-0">
                                    <input class="form-check-input" type="radio" name="Lieux" value="Réunion">
                                    <label class="form-check-label small" for="lieu_reunion">Réunion</label>
                                </div>
                                <div class="form-check form-check-inline form-control-sm py-0">
                                    <input class="form-check-input" type="radio" name="Lieux" value="Terrain">
                                    <label class="form-check-label small" for="lieu_terrain">Terrain</label>
                                </div>
                                <div class="form-check form-check-inline form-control-sm py-0">
                                    <input class="form-check-input" type="radio" name="Lieux" value="Télétravail">
                                    <label class="form-check-label small" for="lieu_teletravail">Télétravail</label>
                                </div>
                                <div class="form-check form-check-inline form-control-sm py-0">
                                    <input class="form-check-input" type="radio" name="Lieux" value="Grève">
                                    <label class="form-check-label small" for="lieu_greve">Grève</label>
                                </div>
                                <div class="form-check form-check-inline form-control-sm py-0">
                                    <input class="form-check-input" type="radio" name="Lieux" value="Modulation">
                                    <label class="form-check-label small" for="lieu_modulation">Modulation</label>
                                </div>
                            </div>
                            <div class="d-flex flex-column w-25">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="input_panier">
                                    <label class="form-check-label small" for="input_panier">Panier Repas</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="input_salissure">
                                    <label class="form-check-label small" for="input_salissure">Salissure</label>
                                </div>
                            </div>
                            <div class="d-flex flex-column w-50">
                                <div class="input-group input-group-sm mb-3">
                                    <span for="commentaire" class="input-group-text">Commentaire</span>
                                    <textarea class="form-control" id="input_commentaire" rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center w-100" id="help" >
                            <div class="col-7 d-flex justify-content-center" id="external-events-list"><div class="fs-4 m-0 badge bg-success fc-event col-6 text-truncate" id="event_title" title="" >...</div></div>
                            <div class="col-5 py-4"><small>Glisser / déposer l'évènement sur le calendrier</small></div>
                        </div>
                    </div>
                    <hr class="m-1">
                </div>
                </div>
            </div>	
		</div>        
		<div class="d-flex mx-auto justify-content-end align-items-center text-muted fixed-bottom">
			<div  class="d-flex" ><kbd class="small">CEN Normandie © <?php echo date("Y"); ?></kbd></div> 
		</div>
	</div>

</div>
<!-- The Modal -->
<div class="modal p-0" id="ModalEditEvent">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <div class="d-flex flex-column w-100">
            <div class="d-flex justify-content-between w-100">
                <h5 class="modal-title">Modifier l'évènement : </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="d-flex flex-inline w-100">
                <small><div class="py-2 fst-italic" id="update_id_e"></div></small>
            </div>
        </div>
      </div>

      <!-- Modal body -->
      <div class="modal-body">

        <div class="d-flex flex-column px-2">
                
                <div class="input-group input-group-sm mb-3">
                    <span for="objet" class="input-group-text">Objet : </span>
                    <input type="text" class="form-control" id="update_input_objet" ></input>
                </div>
                <div class="input-group input-group-sm mb-3">
                    <span for="input_projet" class="input-group-text">Projet : </span>
                    <select type="text" class="form-control" id="update_input_projet" aria-describedby="">
                        <!--<option id="">A</option>
                        <option id="">B</option>-->
                    </select>
                </div>
                <div class="input-group input-group-sm mb-3">
                    <span for="input_action" class="input-group-text">Action : </span>
                    <select type="text" class="form-control" id="update_input_action" aria-describedby="">
                        <!--<option id="">A</option>
                        <option id="">B</option>-->
                    </select>
                </div>
                <!--<div class="input-group input-group-sm mb-3">
                    <span for="input_site" class="input-group-text">Site : </span>
                    <select type="text" class="form-control" id="update_input_site" aria-describedby="">
                        <option id="">A</option>
                        <option id="">B</option>
                    </select>
                </div>-->
                <div class="d-flex">
                    <div class="d-flex flex-column w-25">
                        <div class="form-check form-check-inline form-control-sm py-0">
                            <input class="form-check-input form-check-input-sm" type="radio" name="update_Lieux" id="update_lieu_bureau" value="Bureau">
                            <label class="form-check-label small" for="Lieu_Bureau">Bureau</label>
                            </input>
                        </div>
                        <div class="form-check form-check-inline form-control-sm py-0">
                            <input class="form-check-input" type="radio" name="update_Lieux" id="update_lieu_reunion" value="Réunion">
                            <label class="form-check-label small" for="lieu_reunion">Réunion</label>
                        </div>
                        <div class="form-check form-check-inline form-control-sm py-0">
                            <input class="form-check-input" type="radio" name="update_Lieux" id="update_lieu_terrain" value="Terrain">
                            <label class="form-check-label small" for="lieu_terrain">Terrain</label>
                        </div>
                        <div class="form-check form-check-inline form-control-sm py-0">
                            <input class="form-check-input" type="radio" name="update_Lieux" id="update_lieu_teletravail" value="Télétravail">
                            <label class="form-check-label small" for="lieu_teletravail">Télétravail</label>
                        </div>
                        <div class="form-check form-check-inline form-control-sm py-0">
                            <input class="form-check-input" type="radio" name="update_Lieux" id="update_lieu_greve" value="Grève">
                            <label class="form-check-label small" for="lieu_greve">Grève</label>
                        </div>
                        <div class="form-check form-check-inline form-control-sm py-0">
                            <input class="form-check-input" type="radio" name="update_Lieux" id="update_lieu_modulation" value="Modulation">
                            <label class="form-check-label small" for="lieu_modulation">Modulation</label>
                        </div>
                    </div>
                    <div class="d-flex flex-column w-25">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="update_input_panier">
                            <label class="form-check-label small" for="update_input_panier">Panier Repas</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="update_input_salissure">
                            <label class="form-check-label small" for="update_input_salissure">Salissure</label>
                        </div>
                    </div>
                    <div class="d-flex flex-column w-50">
                        <div class="input-group input-group-sm mb-3">
                            <span for="commentaire" class="input-group-text">Commentaire</span>
                            <textarea class="form-control" id="update_input_commentaire" rows="4"></textarea>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center" id="help">
                    
                </div>
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>-->
        <div class="col-4 d-flex justify-content-between w-100">
            <button type="button" class="btn btn-outline-success" id="update_e">Enregistrer</button>
            <button type="button" class="btn btn-outline-danger" id="delete_e">Supprimer</button>
        </div>
      </div>

    </div>
  </div>
</div>

<span id="c_resp" class="d-none" value="<?php echo $_SESSION['u_nom_user_progecen']  ; ?>"><?php echo $_SESSION['u_nom_user_progecen']  ; ?></span>
<span id="c_user" class="d-none" value="<?php echo $_SESSION['u_nom_user_progecen']  ; ?>"><?php echo $_SESSION['u_nom_user_progecen']  ; ?></span>

<?php 
    if (isset($_GET['datedebut'])) {
        echo '<span id="get_start" class="" >'.str_pad(str_replace(" ","T",gmdate("Y-m-d H:i:s", $_GET['datedebut'])),20,'Z').'</span>';
        echo '<span id="get_end" class="" >'.str_pad(str_replace(" ","T",gmdate("Y-m-d H:i:s", $_GET['datefin'])),20,'Z').'</span>';
        echo '<span id="get_objet" class="" value="'.$_GET['objet'].'">'.$_GET['objet'].'</span>';
        echo '<span id="get_remarque" class="" value="'.$_GET['remarque'].'">'.$_GET['remarque'].'</span>';
    };
?>

<script src="js/jquery.js" ></script>
<!-- Bootstrap Core JavaScript -->
<!--<script src="bootstrap-5.0.0/js/bootstrap.min.js"></script>-->
<script src="bootstrap-5.0.0/js/bootstrap.bundle.js"></script>
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
<!-- FullCalendar -->
<script type="text/javascript" src="js/fullcalendar/lib/main.min.js"></script>
<script type="text/javascript" src="js/fullcalendar/lib/locales/fr.js"></script>

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
<!-- custom.js -->


<script type="text/javascript" src="js/analytique.js" ></script>
<script type="text/javascript">


$(document).ready(function() {
    //charge les projets (fonction qui appelle ensuite le chargement des actions
    load_projets_ajax();

    var get_vars = document.getElementById("get_start");
    //console.log(get_vars);
    //Test si les variable get sont renseignées pour un event depuis outlook 
    if(get_vars){
            get_uuid();
            console.log(new_uuid);
            calendar.addEvent({
            id:new_uuid,
            title: document.getElementById("get_objet").innerHTML,//document.getElementById("get_objet").innerHTML,
            start: document.getElementById("get_start").innerHTML,
            end: document.getElementById("get_end").innerHTML
            });
            console.log(typeof new_uuid);
            let e_event = calendar.getEventById(new_uuid);
            
            e_event.e_uuid = new_uuid;
            e_event.e_start = document.getElementById("get_start").innerHTML;
            e_event.e_end = document.getElementById("get_end").innerHTML;
            e_event.e_id_projet = 0;
            e_event.e_nom_projet = '';
            e_event.e_id_action = 0;
            e_event.e_nom_action = '';
            e_event.e_objet = document.getElementById("get_objet").innerHTML;
            e_event.e_lieu = 'Bureau';
            e_event.e_panier = false;
            e_event.e_salissure = false;
            e_event.e_commentaire = document.getElementById("get_remarque").innerHTML;
            save_event(e_event);
            calendar.getEventById(new_uuid).remove();
            calendar.gotoDate(document.getElementById("get_start").innerHTML);
            calendar.refetchEvents();
            calendar.gotoDate(event.e_start);
    //delete
    delete window.get_vars;
    }
    
});
//propriétés dans le json projets et valeur clef
const keys = {
    "id_projet" : ["null","id"]
};
//observeur de filtres actifs
let filters_active = {
    "id_projet":false
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
</script>
  </body>
</html>