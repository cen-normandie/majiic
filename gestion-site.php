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

    <link href="css/plugins/jquery-ui.css" rel="stylesheet">

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
    <div class="d-flex flex-column" style="">
      <div class="d-flex justify-content-start bg-light m-2" style="height:44px;">
          <div id="loader" class=" bg-success loader mx-4 d-flex flex-wrap align-content-center flex-grow-1 visible_s"></div>
      </div>

      <div class="m-2 shadow">
        <div class="d-flex justify-content-center border">
          <h2 class="text-uppercase text-secondary">Nouveau Document de Référence</h2>
        </div>
        <div class="d-flex align-items-start m-2">
          <div class="w-25 d-flex justify-content-start bg-light m-2">
            <div class="d-flex flex-column">
              <div class="m-2">
                <!-- <label class="form-label" for="doc_pdf">Selection du fichier</label> -->
                <input type="file" class="form-control" id="doc_pdf" />
              </div>
              <div class="m-2">
              <button id="save_new_doc" type="button" class="btn btn-outline-dark">Sauvegarder le document</button>
              </div>
            </div>
          </div>
          <div class="w-75 d-flex justify-content-start bg-light m-2">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
              <button class="nav-link active" id="v-pills-convention-tab" data-bs-toggle="pill" data-bs-target="#v-pills-convention" type="button" role="tab" aria-controls="v-pills-convention" aria-selected="true">Convention</button>
              <button class="nav-link" id="v-pills-acquisition-tab" data-bs-toggle="pill" data-bs-target="#v-pills-acquisition" type="button" role="tab" aria-controls="v-pills-acquisition" aria-selected="false">Acquisition</button>
              <button class="nav-link" id="v-pills-bail_e-tab" data-bs-toggle="pill" data-bs-target="#v-pills-bail_e" type="button" role="tab" aria-controls="v-pills-bail_e" aria-selected="false">Bail Emphyteotique</button>
              <button class="nav-link" id="v-pills-bail_rural-tab" data-bs-toggle="pill" data-bs-target="#v-pills-bail_rural" type="button" role="tab" aria-controls="v-pills-bail_rural" aria-selected="false">Bail Rural</button>
              <button class="nav-link" id="v-pills-pret_usage-tab" data-bs-toggle="pill" data-bs-target="#v-pills-pret_usage" type="button" role="tab" aria-controls="v-pills-pret_usage" aria-selected="false">Prêt-à-usage</button>
              <button class="nav-link" id="v-pills-ore-tab" data-bs-toggle="pill" data-bs-target="#v-pills-ore" type="button" role="tab" aria-controls="v-pills-ore" aria-selected="false">ORE</button>
              <button class="nav-link" id="v-pills-ddg-tab" data-bs-toggle="pill" data-bs-target="#v-pills-ddg" type="button" role="tab" aria-controls="v-pills-ddg" aria-selected="false">DDG</button>
            </div>
            <div class="tab-content d-flex w-100" id="v-pills-tabContent">
              <div class="tab-pane fade show active w-100 border border-success" id="v-pills-convention" role="tabpanel" aria-labelledby="v-pills-convention-tab">
                <div class="d-flex w-100">
                  <div class="d-flex flex-column text-white w-50">
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_c_id">ID de la convention</span>
                      <input id="n_c_id" type="text" class="form-control" placeholder="GEST_0144_014" aria-label="" aria-describedby="l_n_c_id">
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_c_nom">Nom de la convention</span>
                      <input class="form-control" id="n_c_nom" placeholder="Convention De ..." aria-label="" aria-describedby="l_n_c_nom"></input>
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_c_date_start">Date Début<i class="fas fa-calendar-alt mx-2"></i></span>
                      <input class="form-control" id="n_c_date_start" placeholder="04-04-2004" aria-label="" aria-describedby="l_n_c_date_start"></input>
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_c_date_end">Date Fin<i class="fas fa-calendar-alt mx-2"></i></span>
                      <input class="form-control" id="n_c_date_end" placeholder="04-04-2004" aria-label="" aria-describedby="l_n_c_date_end"></input>
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_c_date_sign">Date Signature<i class="fas fa-calendar-alt mx-2"></i></span>
                      <input class="form-control" id="n_c_date_sign" placeholder="04-04-2004" aria-label="" aria-describedby="l_n_c_date_sign"></input>
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_c_nb_reconduction">Nb reconductions</span>
                      <input class="form-control" id="n_c_nb_reconduction" placeholder="0" aria-label="" aria-describedby="l_n_c_nb_reconduction"></input>
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_c_duree_reconduction_mois">Durée reconduction</span>
                      <input class="form-control" id="n_c_duree_reconduction_mois" placeholder="0" aria-label="" aria-describedby="l_n_c_duree_reconduction_mois"></input>
                    </div>
                  </div>
                  <div class="d-flex flex-column text-white w-50">
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_c_nom_signataire_1">Signataire (1)</span>
                      <input class="form-control" id="n_c_nom_signataire_1" placeholder="M Dupont" aria-label="" aria-describedby="l_n_c_nom_signataire_1"></input>
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_c_type_signataire_1">Type signataire (1)</span>
                      <select class="form-select" id="n_c_type_signataire_1" aria-label="Default select example" aria-describedby="l_n_c_type_signataire_1">
                        <option selected="selected" value="Privé">Privé</option>
                        <option value="Public">Public</option>
                      </select>
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_c_nom_signataire_2">Signataire (2)</span>
                      <input class="form-control" id="n_c_nom_signataire_2" placeholder="Mairie de Basly" aria-label="" aria-describedby="l_n_c_nom_signataire_2"></input>
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_c_type_signataire_2">Type signataire (2)</span>
                      <select class="form-select" id="n_c_type_signataire_2" aria-label="Default select example" aria-describedby="l_n_c_type_signataire_2">
                        <option selected="selected" value="Privé">Privé</option>
                        <option value="Public">Public</option>
                      </select>
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_c_categorie_site">Catégorie Site Fédération</span>
                      <input class="form-control" id="n_c_categorie_site" placeholder="0" aria-label="" aria-describedby="l_n_c_categorie_site"></input>
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_c_commentaire">Commentaires</span>
                      <input class="form-control" id="n_c_commentaire" placeholder="BlaBlaBla" aria-label="" aria-describedby="l_n_c_commentaire"></input>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade w-100 border border-danger" id="v-pills-acquisition" role="tabpanel" aria-labelledby="v-pills-acquisition-tab">
                <div class="d-flex w-100">
                  <div class="d-flex flex-column text-white w-50">
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_a_id">ID Acquisition</span>
                      <input id="n_a_id" type="text" class="form-control" placeholder="ACQUI_0144_014" aria-label="" aria-describedby="l_n_a_id">
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_a_nom">Nom Acquisition</span>
                      <input id="n_a_nom" type="text" class="form-control" placeholder="Acquisition de la tablere" aria-label="" aria-describedby="l_n_a_nom">
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_a_date_sign">Date Signature<i class="fas fa-calendar-alt mx-2"></i></span>
                      <input class="form-control" id="n_a_date_sign" placeholder="04-04-2004" aria-label="" aria-describedby="l_n_a_date_sign"></input>
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_a_notaire">Nom Notaire</span>
                      <input id="n_a_notaire" type="text" class="form-control" placeholder="Maître Trepied" aria-label="" aria-describedby="l_n_a_notaire">
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_a_prix_vente">Prix de vente</span>
                      <input id="n_a_prix_vente" type="text" class="form-control" placeholder="10" aria-label="" aria-describedby="l_n_a_prix_vente">
                    </div>
                  </div>
                  <div class="d-flex flex-column text-white w-50">
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_a_nom_compta">Nom Compta</span>
                      <input class="form-control" id="n_a_nom_compta" placeholder="M Dupont" aria-label="" aria-describedby="l_n_a_nom_compta"></input>
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_a_surf_tot">Surface (ha)</span>
                      <input class="form-control" id="n_a_surf_tot" placeholder="1.12" aria-label="" aria-describedby="l_n_a_surf_tot"></input>
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_a_commentaire">Commentaires</span>
                      <input class="form-control" id="n_a_commentaire" placeholder="Blablablou" aria-label="" aria-describedby="l_n_a_commentaire"></input>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade w-100 border border-primary" id="v-pills-bail_e" role="tabpanel" aria-labelledby="v-pills-bail_e-tab">
                <div class="d-flex w-100">
                  <div class="d-flex flex-column text-white w-50">
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_b_e_id">ID Bail E.</span>
                      <input id="n_b_e_id" type="text" class="form-control" placeholder="BAIL_E_0144_014" aria-label="" aria-describedby="l_n_b_e_id">
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_b_e_nom">Nom Bail E.</span>
                      <input class="form-control" id="n_b_e_nom" placeholder="Bail Tablère 2012" aria-label="" aria-describedby="l_n_b_e_nom"></input>
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_b_e_date_sign">Date Signature<i class="fas fa-calendar-alt mx-2"></i></span>
                      <input class="form-control" id="n_b_e_date_sign" placeholder="04-04-2004" aria-label="" aria-describedby="l_n_b_e_date_sign"></input>
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_b_e_notaire">Nom Notaire</span>
                      <input class="form-control" id="n_b_e_notaire" placeholder="M. DoigtDePied" aria-label="" aria-describedby="l_n_b_e_notaire"></input>
                    </div>
                  </div>
                  <div class="d-flex flex-column text-white w-50">
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_b_e_nom_compta">Nom Notaire</span>
                      <input class="form-control" id="n_b_e_nom_compta" placeholder="Parcelle Gouville" aria-label="" aria-describedby="l_n_b_e_nom_compta"></input>
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_b_e_surf_tot">Surface (ha)</span>
                      <input class="form-control" id="n_b_e_surf_tot" placeholder="1.14" aria-label="" aria-describedby="l_n_b_e_surf_tot"></input>
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_b_e_commentaire">Commentaires</span>
                      <input class="form-control" id="n_b_e_commentaire" placeholder="BlouBlaBli" aria-label="" aria-describedby="l_n_b_e_commentaire"></input>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade w-100 border border-warning" id="v-pills-bail_rural" role="tabpanel" aria-labelledby="v-pills-bail_rural-tab">
                <div class="d-flex w-100">
                  <div class="d-flex flex-column text-white w-50">
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_b_id">ID Bail R.</span>
                      <input id="n_b_id" type="text" class="form-control" placeholder="BAIL_004" aria-label="" aria-describedby="l_n_b_id">
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_b_nom">Nom Bail R.</span>
                      <input id="n_b_nom" type="text" class="form-control" placeholder="Bail de chichebo" aria-label="" aria-describedby="l_n_b_nom">
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_b_bailleur">Nom Bailleur</span>
                      <input id="n_b_bailleur" type="text" class="form-control" placeholder="M. Jordan Mickaël" aria-label="" aria-describedby="l_n_b_bailleur">
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_b_preneur">Nom Preneur</span>
                      <input id="n_b_preneur" type="text" class="form-control" placeholder="M. Giscard" aria-label="" aria-describedby="l_n_b_preneur">
                    </div>
                  </div>
                  <div class="d-flex flex-column text-white w-50">
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_b_date_sign">Date Signature<i class="fas fa-calendar-alt mx-2"></i></span>
                      <input class="form-control" id="n_b_date_sign" placeholder="04-04-2004" aria-label="" aria-describedby="l_n_b_date_sign"></input>
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_b_date_start">Date Début Bail<i class="fas fa-calendar-alt mx-2"></i></span>
                      <input class="form-control" id="n_b_date_start" placeholder="04-04-2004" aria-label="" aria-describedby="l_n_b_date_start"></input>
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_b_date_end">Date Fin Bail<i class="fas fa-calendar-alt mx-2"></i></span>
                      <input class="form-control" id="n_b_date_end" placeholder="04-04-2004" aria-label="" aria-describedby="l_n_b_date_end"></input>
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_b_commentaire">Commentaires</span>
                      <input id="n_b_commentaire" type="text" class="form-control" placeholder="BliBlouBla" aria-label="" aria-describedby="l_n_b_commentaire">
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade w-100 border border-dark" id="v-pills-pret_usage" role="tabpanel" aria-labelledby="v-pills-pret_usage-tab">
                <div class="d-flex w-100">
                  <div class="d-flex flex-column text-white w-50">
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_p_id">ID Prêt-à-usage</span>
                      <input id="n_p_id" type="text" class="form-control" placeholder="PRET_004" aria-label="" aria-describedby="l_n_p_id">
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_p_nom">Nom Prêt-à-usage</span>
                      <input id="n_p_nom" type="text" class="form-control" placeholder="PRET de la tablere" aria-label="" aria-describedby="l_n_p_nom">
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_p_preteur">Nom Preteur</span>
                      <input id="n_p_preteur" type="text" class="form-control" placeholder="M Test" aria-label="" aria-describedby="l_n_p_preteur">
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_p_emprunteur">Nom Emprunteur</span>
                      <input id="n_p_emprunteur" type="text" class="form-control" placeholder="Mme Lauren S." aria-label="" aria-describedby="l_n_p_emprunteur">
                    </div>
                  </div>
                  <div class="d-flex flex-column text-white w-50">
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_p_date_sign">Date Signature<i class="fas fa-calendar-alt mx-2"></i></span>
                      <input class="form-control" id="n_p_date_sign" placeholder="04-04-2004" aria-label="" aria-describedby="l_n_p_date_sign"></input>
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_p_date_start">Date Début Prêt<i class="fas fa-calendar-alt mx-2"></i></span>
                      <input class="form-control" id="n_p_date_start" placeholder="04-04-2004" aria-label="" aria-describedby="l_n_p_date_start"></input>
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_p_date_end">Date Fin Prêt<i class="fas fa-calendar-alt mx-2"></i></span>
                      <input class="form-control" id="n_p_date_end" placeholder="04-04-2004" aria-label="" aria-describedby="l_n_p_date_end"></input>
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_p_commentaire">Commentaires</span>
                      <input id="n_p_commentaire" type="text" class="form-control" placeholder="BluBloBla" aria-label="" aria-describedby="l_n_p_commentaire">
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade w-100 border border-info" id="v-pills-ore" role="tabpanel" aria-labelledby="v-pills-ore-tab">
                <div class="d-flex w-100">
                  <div class="d-flex flex-column text-white w-50">
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_ore_id">ID ORE</span>
                      <input id="n_ore_id" type="text" class="form-control" placeholder="ORE_004" aria-label="" aria-describedby="l_n_ore_id">
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_ore_nom">Nom ORE</span>
                      <input id="n_ore_nom" type="text" class="form-control" placeholder="ORE sur la com de ..." aria-label="" aria-describedby="l_n_ore_nom">
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_ore_notaire">Nom Notaire</span>
                      <input id="n_ore_notaire" type="text" class="form-control" placeholder="Mme ..." aria-label="" aria-describedby="l_n_ore_notaire">
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_ore_acquisition">Nom Acquisition ORE</span>
                      <input id="n_ore_acquisition" type="text" class="form-control" placeholder="Acquisition ore ..." aria-label="" aria-describedby="l_n_ore_acquisition">
                    </div>
                  </div>
                  <div class="d-flex flex-column text-white w-50">
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_ore_date_sign">Date Signature<i class="fas fa-calendar-alt mx-2"></i></span>
                      <input class="form-control" id="n_ore_date_sign" placeholder="04-04-2004" aria-label="" aria-describedby="l_n_ore_date_sign"></input>
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_ore_commentaire">Commentaires</span>
                      <input id="n_ore_commentaire" type="text" class="form-control" placeholder="BliBliBli" aria-label="" aria-describedby="l_n_ore_commentaire">
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade w-100 border-purple" id="v-pills-ddg" role="tabpanel" aria-labelledby="v-pills-ddg-tab">
                <div class="d-flex w-100">
                  <div class="d-flex flex-column text-white w-50">
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_gestion_nom">Nom DDGestion</span>
                      <input id="n_gestion_nom" type="text" class="form-control" placeholder="Notice de gestion 2020-2025 Albert" aria-label="" aria-describedby="l_n_gestion_nom">
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_type_doc_gestion">Type de document</span>
                      <select class="form-select" id="n_type_doc_gestion" aria-label="" aria-describedby="l_n_type_doc_gestion">
                        <option selected="selected" value="plan" id="plan">plan de gestion</option>
                        <option value="notice" id="notice">notice de gestion</option>
                      </select>
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_gestion_date_start">Date Début DDG<i class="fas fa-calendar-alt mx-2"></i></span>
                      <input class="form-control" id="n_gestion_date_start" placeholder="04-04-2004" aria-label="" aria-describedby="l_n_gestion_date_start"></input>
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_gestion_date_end">Date Fin DDG<i class="fas fa-calendar-alt mx-2"></i></span>
                      <input class="form-control" id="n_gestion_date_end" placeholder="04-04-2004" aria-label="" aria-describedby="l_n_gestion_date_end"></input>
                    </div>
                  </div>
                  <div class="d-flex flex-column text-white w-50">
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_gestion_auteurs">Auteur</span>
                      <input id="n_gestion_auteurs" type="text" class="form-control" placeholder="Trump" aria-label="" aria-describedby="l_n_gestion_auteurs">
                    </div>
                    <div class="input-group p-2">
                      <span class="input-group-text" id="l_n_gestion_commentaire">Commentaires</span>
                      <input id="n_gestion_commentaire" type="text" class="form-control" placeholder="BlABlèBlu" aria-label="" aria-describedby="l_n_gestion_commentaire">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="m-2 shadow">
        <div class="d-flex justify-content-center border">
          <h2 class="text-uppercase text-secondary">Gestion des sites</h2>
        </div>
        <div class="d-flex align-items-start m-2">
          <div class="w-25 d-flex justify-content-start bg-light m-2">
            <div class="d-flex flex-column text-white">
              <div class="input-group p-2">
                <span class="input-group-text" id="l_site_data_autocomplete">Site</span>
                <input id="site_data_autocomplete" type="text" class="form-control" placeholder="Lande Mouton" aria-label="" aria-describedby="l_site_data_autocomplete">
              </div>
              <span class="px-2 text-success fs-6 d-none" id="out_site" >site actif : <span id="site_actif"></span></span>
              <div class="input-group p-2">
                <span class="input-group-text" id="l_doc_reference_autocomplete">Document de Référence</span>
                <input id="doc_reference_autocomplete" type="text" class="form-control" placeholder="GEST_112_0114_061, Convention ..." aria-label="" aria-describedby="l_doc_reference_autocomplete">
              </div>
              <span class="px-2 text-success fs-6 d-none" id="out_doc" >document actif : <span id="docref_actif"></span></span>
              <div class="input-group p-2">
                <span class="input-group-text" id="l_ddg_autocomplete">Document de Gestion</span>
                <input class="form-control" id="ddg_autocomplete" placeholder="Notice de gestion de ..." aria-label="" aria-describedby="l_ddg_autocomplete"></input>
              </div>
              <span class="px-2 text-success fs-6 d-none" id="out_ddg" >document actif : <span id="ddg_actif"></span></span>
            </div>
          </div>
          <div class="w-75 d-flex justify-content-start bg-light m-2">
            <div class="table-responsive w-100">
              <table class="table table-bordered table-hover " id="tab_logic">
                  <thead>
                      <tr >
                          <th class="text-center">
                              #
                          </th>
                          <th class="text-center col-sm-1">
                              Code INSEE
                          </th>
                          <th class="text-center col-sm-1">
                              Prefixe
                          </th>
                          <th class="text-center col-sm-1">
                              Section
                          </th>
                          <th class="text-center col-sm-1">
                              Numéro
                          </th>
                          <th class="text-center col-sm-1">
                              Pour partie
                          </th>
                          <th class="text-center col-sm-1">
                              PCI Vecteur
                          </th>
                          <th class="text-center col-sm-1">
                              Convention
                          </th>
                          <th class="text-center col-sm-1">
                              Acquisition
                          </th>
                          <th class="text-center col-sm-1">
                              ID Site
                          </th>
                          <th class="text-center col-sm-1">
                              Doc Gestion
                          </th>
                          <th class="text-center col-sm-1">
                              ID ORE
                          </th>
                      </tr>
                  </thead>
                  <tbody id='site_parcelles_content'>
                  </tbody>
              </table>
              <div class="d-flex w-100 justify-content-between">
                <div>
                  <a id="add_row" class="btn btn-outline-secondary pull-left">Ajouter une parcelle</a>
                </div>
                <div>
                  <a id='delete_row' class="pull-right btn btn-outline-secondary">Supprimer une parcelle</a>
                </div>
              </div>
              <div class="d-flex w-100 justify-content-end">
                <button id="save_site" class="btn btn-sm btn-primary m-2" type="">Enregistrer les parcelles</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="mx-2 mt-2 mb-5 shadow">
        <div class="d-flex justify-content-center border">
          <div class="w-50">
            <div class="d-flex justify-content-center border">
              <h2 class="text-uppercase text-secondary">Suppression de site et du parcellaire</h2>
            </div>
            <div class="d-flex align-items-start m-2">
              <div class="input-group p-2">
                <span class="input-group-text text-danger" id="l_site_autocomplete_todelete">Suppression de : </span>
                <input class="form-control" id="site_autocomplete_todelete" placeholder="Lande Mouton" aria-label="" aria-describedby="l_site_autocomplete_todelete"></input>
                <button id="delete_site" type="input-group-btn" class="btn btn-outline-danger">Supprimer</button>
              </div>
            </div>
          </div>
          <div class="w-50">
            <div class="d-flex justify-content-center border">
              <h2 class="text-uppercase text-secondary">Fichier annexe</h2>
            </div>
            <div class="d-flex flex-column m-2">
            <span class="m-2">Rattaché un document annexe à un site</span>
              <div class="input-group p-2">
                <span class="input-group-text" id="l_id_site_autre_doc">Site</span>
                <input id="id_site_autre_doc" type="text" class="form-control" placeholder="0014_014" aria-label="" aria-describedby="l_id_site_autre_doc">
              </div>
              <div class="input-group p-2">
                <input type="file" class="form-control" id="doc_pdf_autre" />
                <button id="save_new_doc_autre" type="input-group-btn" class="btn btn-outline-success">Sauvegarder le document</button>
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
<!-- JQUERY AUTOCOMPLETE -->
<script src="js/plugins/jquery-ui-1.12.1.custom/jquery-ui.js" ></script>
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
<!--gestion-->
<script type="text/javascript" src="js/gestion.js"></script> 
<script type="text/javascript" src="js/gestion-site.js"></script>
<script type="text/javascript">


$(document).ready(function() {


});




</script>

  </body>
</html>
