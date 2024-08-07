<div class="d-flex flex-column col-md-3 col-lg-2 h-100 bg-dark sticky-top " style="min-height:100vh;">
<div class="d-flex justify-content-center mt-2 align-iems-center w-100">
<div class="text-light mx-2"><img src="img/CenNormandie.png" style="max-width:80px;max-height: 40px;opacity:0.8;"/></div>
<h1 class="text-light mx-2 moonflower">CEN Normandie</h1>
</div>
<ul class="nav flex-column">
    
  <div class="ml-2">
      <span class="nav-link text-secondary">Sites :</span>
      <a class="nav-link <?php $t = ((($_POST['page']) == 'sites.php') ? ' active' : '' ); echo $t; ?>" href="sites.php">
        <span data-feather=""></span><i class="fas fa-home ml-4"></i> Consulter un Site
      </a> 
<?php 
    //WRITE ALL SESSIONS VARS
    //echo "<h3> PHP List All Session Variables</h3>";
    //foreach ($_SESSION as $key=>$val)
    //echo "<span  class='text-white'>".$key." ".$val."</span><br/>";
  $_SESSION['is_admin'] = false;
/*   $admins = array(
    "n.moreira@cen-normandie.fr", 
    "m.seguin@cen-normandie.fr", 
    "f.buissart@cen-normandie.fr", 
    "b.perceval@cen-normandie.fr", 
    "v.boucey@cen-normandie.fr",
    "m.pellevilain@cen-normandie.fr"
  ); */
/*   $rh = array(
    "b.perceval@cen-normandie.fr", 
    "v.yver@cen-normandie.fr",
    "h.bliard@cen-normandie.fr",
    "n.peschard@cen-normandie.fr"
  );  */
  //if (in_array($_SESSION['email'], $admins)) {
  //    $_SESSION['is_admin'] = true;
  //}
  //if ($_SESSION['is_admin']) {
  if ($_SESSION['is_equipe_si']) {
    $link_ = '
    <a class="nav-link ';
    $t = ((($_POST["page"]) == "gestion-site.php") ? " active" : "" );
    $link_ = $link_.$t;
    $link_ = $link_.'" href="gestion-site.php"><span data-feather=""></span><i class="fas fa-wrench"></i> Gestion Sites
    </a>';
    echo $link_; 
  }
  ?>
    <a class="nav-link <?php $t = ((($_POST['page']) == 'dashboard.php') ? ' active' : '' ); echo $t; ?>" href="dashboard.php">
      <span data-feather=""></span>
      <i class="fas fa-tachometer-alt"></i> Tableau de bord
    </a>
  </div>
  <hr class="bg-secondary mx-2 my-1">
  <li class="nav-item">
    <a class="nav-link <?php $t = ((($_POST['page']) == 'majiic.php') ? ' active' : '' ); echo $t; ?>" href="majiic.php">
      <span data-feather=""></span>
      <i class="fas fa-user-shield"></i> Majiic
    </a>
  </li>
  <hr class="bg-secondary mx-2 my-1">
  <span class="nav-link text-secondary">SIG :</span>
  <li class="nav-item">
      <a class="nav-link"  href="https://geonature.cenwms.xyz/geonature" target="_blank">
        <span data-feather=""></span>
        <i class="fas fa-frog"></i> Géonature
      </a>
  </li>
  <li class="nav-item">
      <a class="nav-link"  href="https://gestparc.csnhn.local/glpi/" target="_blank">
        <span data-feather=""></span>
        <i class="fas fa-ticket-alt"></i> Ouvrir un ticket
      </a>
  </li>
  <hr class="bg-secondary mx-2 my-1">
    <div class="ml-2">
      <span class="nav-link text-secondary">Projets :</span>
        <a class="nav-link"  href="analytique.php">
            <span data-feather=""></span>
            <i class="far fa-calendar-alt"></i> Analytique
        </a>
        <a class="nav-link"  href="suivi_projet.php">
            <span data-feather=""></span>
            <i class="fas fa-tasks"></i> Suivi Projet
        </a>
          <a class="nav-link"  href="create_projet.php">
          <span data-feather=""></span>
          <i class="fas fa-plus"></i> Création de projet
        </a> 
         <a class="nav-link"  href="import_temps_excel.php">
          <span data-feather=""></span>
          <i class="fas fa-edit"></i> Optimisation des temps
        </a>
        <a class="nav-link"  href="export.php">
          <span data-feather=""></span>
          <i class="fas fa-file-excel"></i> Export des feuilles de temps
        </a>
    </div>
  <hr class="bg-secondary mx-2 my-1">







  <?php 
  if ($_SESSION['is_equipe_rh']) {
    echo '<div class="ml-2">
    <span class="nav-link text-secondary">RH :</span>
        <a class="nav-link"  href="rh.php">
                <span data-feather=""></span>
                <i class="fas fa-utensils"></i> Panier Repas / <i class="fas fa-socks"></i> Prime Salissure
            </a>
    </div>';
  }
?>
</ul>
</div>