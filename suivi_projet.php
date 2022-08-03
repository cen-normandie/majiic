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
                <h2 class="">Suivi Projet</h2>
                <div id="loader" class=" bg-success loader mx-4 d-flex flex-wrap align-content-center flex-grow-1 visible_s" style="margin-bottom: .5rem"></div>
            </div>
            
            <div class="d-flex bg-light">
                <div id="" class="d-flex flex-column bg-light w-100 m-2 ">
                    <div class="d-flex flex-column">
                        <div class="autocompleteBS col-10">
                            <div class="input-group w-100">
                                <span for="input_projet" class="input-group-text">Projet : </span>
                                <input type="text" class="form-control" id="input_projet" aria-describedby="basic-addon3">
                                <span class="input-group-text justify-content-center" id="del"><i class="far fa-trash-alt"></i></span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-start align-items-center col-12">
                            <div class="form-check form-switch my-2">
                                <input class="form-check-input" type="checkbox" id="edit_projet">
                                <label class="form-check-label" for="edit_projet">Editer le projet</label>
                            </div>
                            <div class="my-2">
                                <button class="ml-4 btn input-group-text justify-content-center" id="add"><i class="mx-2 fas fa-plus-square text-success"></i>Créer un projet</button>
                            </div>
                        </div>
                    </div>
                    <!-- PANEL 100% ACTIONS -->
                    <div class="d-flex flex-column w-100 border border-secondary rounded-2 p-2 mt-2 mb-2">
                        <div class="d-flex w-100 justify-content-center"><h4>Actions du projet</h4></div>
                        <div class="d-flex flex w-100">
                            <div class="d-flex flex-column w-25 m-2 p-2 shadow-sm border">
                                <h6>Nouvelle action :</h6>
                                <div class="d-flex  justify-content-between my-2">
                                    <div class="autocompleteBS w-75">
                                        <div class="input-group input-group-sm">
                                            <span for="input_actions" class="input-group-text">Action : </span>
                                            <input type="text" class="form-control" id="input_actions" aria-describedby="basic-addon3" placeholder="fauche, ANIM_...">
                                            <span class="input-group-text justify-content-center" id="del_action"><i class="far fa-trash-alt"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex  justify-content-between my-2">
                                    <div class="autocompleteBS w-75" id="financeurs">
                                        <div class="input-group input-group-sm">
                                            <span for="input_financeurs" class="input-group-text">Financeur : </span>
                                            <input type="text" class="form-control" id="input_financeurs" aria-describedby="basic-addon3" placeholder="AESN, Lehman Brothers...">
                                            <span class="input-group-text">% :</span>
                                            <input type="number" class="form-control" id="input_p_financeurs" aria-describedby="basic-addon3" placeholder="100">
                                            <span class="input-group-text justify-content-center" id="del_financeur"><i class="far fa-trash-alt"></i></span>
                                        </div>
                                    </div>
                                    <div class="ml-2">
                                        <div id="plus_f" type="button" class="btn btn-outline-secondary btn-sm"><i class="fas fa-plus"></i></div>
                                    </div>
                                </div>
                                <div  id="list_f" class="d-flex flex-column align-items-end w-100">
                                </div>
                                <div class="d-flex  justify-content-between my-2">
                                    <div class="autocompleteBS w-75" id="personnes">
                                        <div class="input-group input-group-sm">
                                            <span for="input_personnes" class="input-group-text">Personne : </span>
                                            <input type="text" class="form-control" id="input_personnes" aria-describedby="basic-addon3" placeholder="...">
                                            <span class="input-group-text">% :</span>
                                            <input type="number" class="form-control" id="input_p_financeurs" aria-describedby="basic-addon3" placeholder="100">
                                            <span class="input-group-text justify-content-center" id="del_personne"><i class="far fa-trash-alt"></i></span>
                                        </div>
                                    </div>
                                    <div class="ml-2">
                                        <div id="plus_p" type="button" class="btn btn-outline-secondary btn-sm"><i class="fas fa-plus"></i></div>
                                    </div>
                                </div>
                                <div  id="list_p" class="d-flex flex-column align-items-end w-100">
                                </div>
                                <div class="d-flex  justify-content-between my-2">
                                    <div class="autocompleteBS w-75" id="sites">
                                        <div class="input-group input-group-sm">
                                            <span for="input_site" class="input-group-text">Site : </span>
                                            <input type="text" class="form-control" id="input_site" aria-describedby="basic-addon3" placeholder="...">
                                            <span class="input-group-text justify-content-center" id="del_site"><i class="far fa-trash-alt"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex  justify-content-between my-2">
                                    <div class="w-75">
                                        <div class="input-group input-group-sm w-100">
                                            <span for="input_heures" class="input-group-text">Heures : </span>
                                            <input type="number" class="form-control" id="input_heures" aria-describedby="basic-addon3" placeholder="5">
                                        </div>
                                    </div>
                                    <div class="ml-2">
                                    </div>
                                </div>
                                <div class="d-flex  justify-content-end my-2">
                                    <div class="">
                                        <button id="add_action" type="button" class="btn btn-outline-success btn-sm"><i class="fas fa-plus pr-2"></i> Ajouter l'action</button>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap w-75 m-2" id="actions_list">
                                <div id="list_actions">
                                    <div class="w-100 p-2 border">
                                        <div class="row g-3 align-items-center">
                                            <div class="col-auto">
                                                <label for="id_action" class="col-form-label">0</label>
                                            </div>
                                            <div class="col-auto">
                                                <input type="text" id="nom_action" value="ANIM_TRANSVERSALE" disabled></input>
                                            </div>
                                            <div class="col-auto">
                                                <input type="text"  id="fianceurs_action" value="f1-50%|f2-40%" disabled></input>
                                            </div>
                                            <div class="col-auto">
                                                <input type="text"  id="heures_action" value="50" disabled></input>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-wrap m-2 justify-content-end">
                                            <h5 id="a0_p0"><span class="badge m-1 bg-success text-light">Light<i id="" class="ps-1 fas fa-window-close"></i></span></h5>
                                            <h5 id="a0_p1"><span class="badge m-1 bg-success text-light">Light<i id="" class="ps-1 fas fa-window-close"></i></span></h5>
                                            <h5 id="a0_p0"><span class="badge m-1 bg-success text-light">Light<i id="" class="ps-1 fas fa-window-close"></i></span></h5>
                                            <h5 id="a0_p1"><span class="badge m-1 bg-success text-light">Light<i id="" class="ps-1 fas fa-window-close"></i></span></h5>
                                            <h5 id="a0_p0"><span class="badge m-1 bg-success text-light">Light<i id="" class="ps-1 fas fa-window-close"></i></span></h5>
                                            <h5 id="a0_p1"><span class="badge m-1 bg-success text-light">Light<i id="" class="ps-1 fas fa-window-close"></i></span></h5>
                                            <h5 id="a0_p0"><span class="badge m-1 bg-success text-light">Light<i id="" class="ps-1 fas fa-window-close"></i></span></h5>
                                            <h5 id="a0_p1"><span class="badge m-1 bg-success text-light">Light<i id="" class="ps-1 fas fa-window-close"></i></span></h5>
                                            <h5 id="a0_p0"><span class="badge m-1 bg-success text-light">Light<i id="" class="ps-1 fas fa-window-close"></i></span></h5>
                                            <h5 id="a0_p1"><span class="badge m-1 bg-success text-light">Light<i id="" class="ps-1 fas fa-window-close"></i></span></h5>
                                            <h5 id="a0_p0"><span class="badge m-1 bg-success text-light">Light<i id="" class="ps-1 fas fa-window-close"></i></span></h5>
                                            <h5 id="a0_p1"><span class="badge m-1 bg-success text-light">Light<i id="" class="ps-1 fas fa-window-close"></i></span></h5>
                                            <h5 id="a0_p0"><span class="badge m-1 bg-success text-light">Light<i id="" class="ps-1 fas fa-window-close"></i></span></h5>
                                            <h5 id="a0_p1"><span class="badge m-1 bg-success text-light">Light<i id="" class="ps-1 fas fa-window-close"></i></span></h5>
                                            <h5 id="a0_p0"><span class="badge m-1 bg-success text-light">Light<i id="" class="ps-1 fas fa-window-close"></i></span></h5>
                                            <h5 id="a0_p1"><span class="badge m-1 bg-success text-light">Light<i id="" class="ps-1 fas fa-window-close"></i></span></h5>
                                            <h5 id="a0_p0"><span class="badge m-1 bg-success text-light">Light<i id="" class="ps-1 fas fa-window-close"></i></span></h5>
                                            <h5 id="a0_p1"><span class="badge m-1 bg-success text-light">Light<i id="" class="ps-1 fas fa-window-close"></i></span></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                            	<div class="form-group row">
    	<div class="col-12 col-md-3">
            <label for="nom" class="col-12 col-form-label text-cen-bleu">Taxon</label>
            <div class="col-12" v-if="!disabled">
                <multiselect 
                @input="searchFauneByTaxon()"
                v-model="data.taxon_faune" 
                :options="taxon_faunes" 
                placeholder="Sélection" 
                label="nom" 
                track-by="id"
                selectLabel=""
                deselectLabel=""
                selectedLabel="Selectionner"
                >
                </multiselect>
            </div>
            <div class="col-12" v-else>
                <input type="text" class="form-control" :value="data.taxon_faune && data.taxon_faune.nom ? data.taxon_faune.nom : '' " readonly="readonly">
            </div>
        </div>
        <div class="col-12 col-md-3">
            <label for="nom" class="col-12 col-form-label text-cen-bleu">Faune</label>
            <div class="col-12" v-if="!disabled">
                <multiselect
                :disabled="data.taxon_faune.length == 0"
                v-model="data.faune" 
                :options="faunes" 
                placeholder="Sélection" 
                label="nom" 
                track-by="id"
                selectLabel=""
                deselectLabel=""
                selectedLabel="Selectionner"
                >
                </multiselect>
            </div>
            <div class="col-12" v-else>
                <input type="text" class="form-control" :value="data.faune && data.faune.nom ? data.faune.nom : '' " readonly="readonly">
            </div>
        </div>
        <div class="col-12 col-md-4">
            <label for="nom" class="col-12 col-form-label text-cen-bleu">Nombre</label>
            <div class="col-6">
                <input type="number" class="form-control text-right" v-model="data.nombre" :disabled="data.faune.length == 0 || disabled == true">
            </div>
        </div>
        <div class="col-12 col-md-2" v-if="!disabled">
            <label for="nom" class="col-12 col-form-label text-cen-bleu">&nbsp;</label>
            <div class="col-6">
                <button class="btn btn-danger" @click.prevent="deleteFauneComponent()">
                	<i class="fas fa-trash-alt"></i>
                </button>
            </div>
        </div>
    </div>
                            </div>
                        </div>
                    </div>
                    <!-- PANEL 100% Synthese du projet -->
                    <div class="d-flex flex-column w-100 border border-secondary rounded-2 p-2">
                        <div class="d-flex w-100 justify-content-center"><h4>Synthèse du projet</h4></div>
                        <div class="d-flex flex w-100">
                            <div class="d-flex flex-column w-25 m-2">
                                <div class="input-group input-group-sm p-2">
                                    <span class="input-group-text" id="basic-addon1">Nom du projet : </span>
                                    <input type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="" disabled value="Ola">
                                </div>
                                <div class="input-group input-group-sm p-2">
                                    <span class="input-group-text" id="basic-addon1">Responsable : </span>
                                    <input type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="" disabled value="Ola">
                                </div>
                                <div class="input-group input-group-sm p-2">
                                    <label class="input-group-text" for="l_type_projet">Type de projet : </label>
                                    <select class="form-select" id="type_projet" disabled>
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
                                <div class="input-group input-group-sm p-2">
                                    <label class="input-group-text" for="l_etat_projet">État du projet : </label>
                                    <select class="form-select" id="etat_projet" disabled>
                                        <option value="Prévisionnel" >Prévisionnel</option>
                                        <option value="Validé" >Validé</option>
                                        <option value="" >Réalisé</option>
                                    </select>
                                </div>
                                <div class="input-group input-group-sm p-2">
                                    <label class="input-group-text" for="l_echelle_projet">Échelle : </label>
                                    <select class="form-select" id="echelle_projet" disabled>
                                        <option value="Commune" >Commune</option>
                                        <option value="Département" >Département</option>
                                        <option value="EPCI" >EPCI</option>
                                        <option value="Région" >Région</option>
                                        <option value="Site" >Site</option>
                                        <option value="Multi-site" >Multi-site</option>
                                        <option value="ENS" >ENS</option>
                                    </select>
                                </div>
                                <div class="input-group input-group-sm p-2">
                                    <span class="input-group-text" id="l_p_date_start">Date Début<i class="fas fa-calendar-alt mx-2"></i> : </span>
                                    <input class="form-control" id="p_date_start" placeholder="04-04-2004" aria-label="" aria-describedby="l_p_date_start" value="01-01-2022" disabled>
                                </div>
                                <div class="input-group input-group-sm p-2">
                                    <span class="input-group-text" id="l_p_date_end">Date Fin<i class="fas fa-calendar-alt mx-2"></i> : </span>
                                    <input class="form-control" id="p_date_end" placeholder="08-08-2088" aria-label="" aria-describedby="l_p_date_end" value="01-01-2026" disabled>
                                </div>
                                <div class="input-group input-group-sm p-2">
                                    <span for="commentaire" class="input-group-text">Commentaire</span>
                                    <textarea class="form-control" id="p_commentaire" rows="2" disabled></textarea>
                                </div>
                                <div class="input-group input-group-sm p-2">
                                    <label for="p_color" class="input-group-text">Couleur</label>
                                    <input type="color" class="form-control form-control-color" id="p_color" value="#563d7c" title="Choisissez une couleur" aria-describedby="helpColor" disabled>
                                    <span id="helpColor" class="form-text pl-1">couleur utilisée pour le calendrier</span>
                                </div>
                                
                            </div>
                            <div class="d-flex flex-wrap w-75 m-2" id="graphss">
                                <div class="col-6" id="graph1" ></div>
                            </div>
                        </div>
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
<!-- custom.js -->

<script type="text/javascript" src="js/autocompleteArray/autocomplete.projets.js" ></script>
<script type="text/javascript" src="js/autocompleteArray/autocomplete.actions.js" ></script>
<script type="text/javascript" src="js/autocompleteArray/autocomplete.personnes.js" ></script>
<script type="text/javascript" src="js/autocompleteArray/autocomplete.financeurs.js" ></script>
<script type="text/javascript" src="js/autocompleteArray/autocomplete.sites_liste.js" ></script>


<script type="text/javascript" src="js/suivi_projet.js" ></script>
<script type="text/javascript">


$(document).ready(function() {
    load_projets_ajax();
});



//propriétés dans le json projets et valeur clef
const keys = {
    "2021" : [1,"is_zh"],
    "2022" : [true,"is_aesn"],
    "2023" : [1,"is_ddg"],
    "2024" : [1,"is_ens"],
    "2025" : [1,"is_acquisition"],
    "2026" : [1,"is_convention"],
    "2027" : [1,"is_mc"],
    "2028" : ["14","dep"],
    "id_projet" : ["null","id"],
    "id_action" : ["null","id"]
};
//observeur de filtres actifs
let filters_active = {
    "2021":false,
    "2022":false,
    "2023":false,
    "2024":false,
    "2025":false,
    "2026":false,
    "2027":false,
    "2028":false,
    "id_projet":false,
    "id_action":false
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
graph_();

</script>

  </body>
</html>
