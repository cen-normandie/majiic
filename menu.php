<div class="d-flex flex-column col-md-3 col-lg-2 h-100 bg-dark sticky-top " style="min-height:100vh;">
<div class="d-flex justify-content-center mt-2 align-iems-center w-100">
<div class="text-light mx-2"><img src="img/CenNormandie.png" style="max-width:80px;max-height: 40px;opacity:0.8;"/></div>
<h2 class="text-light mx-2">CEN Normandie</h2>
</div>
<ul class="nav flex-column">
  <li class="nav-item">
    <a class="nav-link <?php $t = ((($_POST['page']) == 'sites.php') ? ' active' : '' ); echo $t; ?>" href="sites.php">
      <span data-feather=""></span>
      <i class="fas fa-home ml-4"></i> Sites
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php $t = ((($_POST['page']) == 'majiic.php') ? ' active' : '' ); echo $t; ?>" href="majiic.php">
      <span data-feather=""></span>
      <i class="fas fa-user-shield"></i> Majiic
    </a>
  </li>
  <?php 
  $_SESSION['is_admin'] = false;
  $admins = array("n.moreira@cen-normandie.fr", "c.bouteiller@cen-normandie.fr", "f.buissart@cen-normandie.fr", "b.perceval@cen-normandie.fr", "v.boucey@cen-normandie.fr");
  
  if (in_array($_SESSION['email'], $admins)) {
      $_SESSION['is_admin'] = true;
  }

  if ($_SESSION['is_admin']) {
    $link_ = '<li class="nav-item"><a class="nav-link ';
    $t = ((($_POST["page"]) == "gestion-site.php") ? " active" : "" );
    $link_ = $link_.$t;
    $link_ = $link_.'" href="gestion-site.php">
      <span data-feather=""></span>
      <i class="fas fa-wrench"></i> Gestion Sites
    </a>
  </li>';
    echo $link_; 
  }


?>
  <li class="nav-item">
    <a class="nav-link <?php $t = ((($_POST['page']) == 'dashboard.php') ? ' active' : '' ); echo $t; ?>" href="dashboard.php">
      <span data-feather=""></span>
      <i class="fas fa-tachometer-alt"></i> Tableau de bord
    </a>
  </li>
  


  
</ul>
</div>