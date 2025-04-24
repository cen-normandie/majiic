<div class="d-flex flex-column col-md-3 col-lg-2 h-100 bg-dark sticky-top " style="min-height:100vh;">
<div class="d-flex justify-content-center mt-2 align-iems-center w-100">
<div class="text-light mx-2"><img id='cen_n_img' src="img/CenNormandie.png" style="max-width:80px;max-height: 40px;opacity:0.8;"/></div>
<h1 class="text-light mx-2 moonflower">CEN Normandie</h1>
</div>
<ul class="nav flex-column">
    
  <div class="ml-2">
      <span class="nav-link text-secondary">Sites :</span>
      <a class="nav-link py-1 <?php $t = ((($_POST['page']) == 'sites.php') ? ' active' : '' ); echo $t; ?>" href="sites.php">
        <span data-feather=""></span><i class="fas fa-home ml-4"></i> Consulter un Site
      </a> 
<?php 
 if ($_SESSION['is_equipe_si']) {
    $a = ((($_POST['page']) == 'gestion-site.php') ? ' active' : '' );
    echo '<li class="nav-item"><a class="nav-link py-1 ',$a,'" href="gestion-site.php">
      <span data-feather=""></span>
      <i class="fas fa-wrench"></i> Gestion sites
    </a>
  </li>
    ';
  }
?>

    <a class="nav-link py-1 <?php $t = ((($_POST['page']) == 'dashboard.php') ? ' active' : '' ); echo $t; ?>" href="dashboard.php">
      <span data-feather=""></span>
      <i class="fas fa-tachometer-alt"></i> Tableau de bord
    </a>
  </div>
  <?php 
 if ($_SESSION['cgu']) {
    $a = ((($_POST['page']) == 'majiic.php') ? ' active' : '' );
    echo '<hr class="bg-secondary mx-2 my-1"><li class="nav-item"><a class="nav-link py-1 ',$a,'" href="majiic.php">
      <span data-feather=""></span>
      <i class="fas fa-user-shield"></i> Majiic
    </a>
  </li>
    ';
  }
?>
  <hr class="bg-secondary mx-2 my-1">
  <span class="nav-link text-secondary">SIG :</span>
  <li class="nav-item">
      <a class="nav-link py-1"  href="https://geonature.cen-n.fr/geonature" target="_blank">
        <span data-feather=""></span>
        <i class="fas fa-frog"></i> Géonature
      </a>
  </li>
  <li class="nav-item">
      <a class="nav-link py-1"  href="https://gestparc.csnhn.local/glpi/" target="_blank">
        <span data-feather=""></span>
        <i class="fas fa-ticket-alt"></i> Ouvrir un ticket
      </a>
  </li>
  <li class="nav-item">
      <a class="nav-link py-1"  href="https://wiki.cen-n.fr" target="_blank">
        <span data-feather=""></span>
        <i class="fab fa-wikipedia-w"></i> Wiki CEN (tutos)
      </a>
  </li>
  <li class="nav-item">
      <a class="nav-link py-1"  href="https://nextcloud.cen-n.fr" target="_blank">
        <span data-feather=""></span>
        <i class="fas fa-cloud"></i> Nextcloud
      </a>
  </li>
  <li class="nav-item">
    <a class="nav-link py-1 <?php $t = ((($_POST['page']) == 'formation_vegetale.php') ? ' active' : '' ); echo $t; ?>" href="formation_vegetale.php">
      <span data-feather=""></span>
      <i class="fas fa-leaf"></i> Formation Végétale
    </a>
  </li>
  <hr class="bg-secondary mx-2 my-1">
    <div class="ml-2">
      <span class="nav-link text-secondary">Projets :</span>
        <a class="nav-link py-1 <?php $t = ((($_POST['page']) == 'analytique.php') ? ' active' : '' ); echo $t; ?>"  href="analytique.php">
            <span data-feather=""></span>
            <i class="far fa-calendar-alt"></i> Analytique
        </a>
        <a class="nav-link py-1 <?php $t = ((($_POST['page']) == 'suivi_projet.php') ? ' active' : '' ); echo $t; ?>"  href="suivi_projet.php">
            <span data-feather=""></span>
            <i class="fas fa-tasks"></i> Suivi Projet
        </a>
          <a class="nav-link py-1 <?php $t = ((($_POST['page']) == 'create_projet.php') ? ' active' : '' ); echo $t; ?>"  href="create_projet.php">
          <span data-feather=""></span>
          <i class="fas fa-plus"></i> Création de projet
        </a>

<?php 
 if ($_SESSION['fdt_optimisation']) {
    echo '        <a class="nav-link py-1';
    $t = ((($_POST['page']) == 'optimisation.php') ? ' active' : '' ); echo $t;
    echo '"  href="optimisation.php">
          <span data-feather=""></span>
          <i class="fas fa-edit"></i> Optimisation des temps
        </a>';
  } 
?>

<?php 
 if ($_SESSION['u_saf']) {
    echo '        <a class="nav-link py-1';
    $t = ((($_POST['page']) == 'export.php') ? ' active' : '' ); echo $t;
    echo '" href="export.php">
          <span data-feather=""></span>
          <i class="fas fa-file-excel"></i> Export des feuilles de temps
        </a>';
  } 
?>

        
        <!--<a class="nav-link"  href="reservation_materiel.php">
          <span data-feather=""></span>
          <i class="fas fa-edit"></i> Reservation Matos
        </a>-->
    </div>
  <hr class="bg-secondary mx-2 my-1">







  <?php 
  if ($_SESSION['is_equipe_rh']) {
    echo '        <a class="nav-link py-1';
    $t = ((($_POST['page']) == 'rh.php') ? ' active' : '' ); echo $t;
    echo '" href="rh.php">
          <span data-feather=""></span>
          <i class="fas fa-utensils"></i> Paniers Repas / <i class="fas fa-socks"></i> Primes Salissure
        </a>';
  }
?>
</ul>
</div>