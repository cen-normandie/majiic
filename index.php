<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Cen Normandie</title>
    <link rel="shortcut icon" href="img/CenNormandie.ico" />
    <!-- Bootstrap Core CSS -->
    <!--<link href="css/bootstrap.min.css" rel="stylesheet">-->
    <link href="bootstrap-5.0.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="fontawesome-free-5.15.2-web/css/all.css" rel="stylesheet" type="text/css">
	<link href="css/cennormandie.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="d-flex w-100 h-100 bg-light" style="min-height:100vh;">
	<div class="d-flex flex-column col-md-3 col-lg-2 h-100 bg-dark sticky-top " style="min-height:100vh;">
		<div class="d-flex flex-column justify-content-between align-items-between w-100" style="min-height:100vh;">
			<div class="d-flex ">
				<div class="d-flex justify-content-center mt-2 align-items-center w-100">
				<img src="img/CenNormandie.png" style="max-width:80px;max-height: 40px;opacity:0.8;"/>
					<div class="text-light mx-2">
						<h1 class="text-light mx-2 moonflower">CEN Normandie</h1>
					</div>
				</div>
			</div>
			<div class="d-flex flex-grow-1 ">
				<div id="" class="d-flex flex-column justify-content-end w-100">
					<div class="d-flex justify-content-around fs-6 my-2 text-secondary">
							<div><i class="fas fa-otter"></i></div>
							<div><i class="fas fa-spider"></i></div>
							<div><i class="fas fa-kiwi-bird"></i></div>
							<div><i class="fas fa-fish"></i></div>
							<div><i class="fas fa-crow"></i></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="d-flex flex-column col-md-9 col-lg-10 bg-light " >
		<div class="d-flex justify-content-end  bg-dark sticky-top">
			<p>.</p>
		</div>
        <div class="d-flex flex-column justify-content-end" style="">
			<div class="d-flex align-items-start flex-column" style="">
				<div class="p-4 w-100"><h2>Connexion à l'application CEN Normandie</h2></div>
				<div class="p-4 col-6">
					<form role="form">
						<div class="input-group w-50">
							<span class="input-group-text justify-content-center col-2" id="user"><i class="fas fa-user"></i></span>
                            <input id="courriel" type="text" class="form-control col-10 " placeholder="Identifiant de session " aria-label="courriel@mail.com" aria-describedby="user" tabindex="1">
                        </div>
						<div class="input-group w-50 my-2">
							<span class="input-group-text justify-content-center col-2" id="passwordLabel"><i class="fas fa-key"></i></span>
                            <input id="pwd" type="password" class="form-control col-10 " placeholder="MoTDePassE" aria-label="MoTDePassE" aria-describedby="passwordLabel" tabindex="2" >
                        </div>
						<div class="my-2">
							<button type="button" id="signin" class="btn btn-primary" tabindex="3">Valider</button>
						</div>
					</form>
				</div>
				<div class="p-4 w-50">
					<div class="d-flex justify-content-arround">
					</div>
				</div>
				<div class="mt-auto p-4">
<!-- 						<div class="my-2">
							<button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#CreateAccount">Création de compte</button>
						</div>
						<div class="my-2">
							<button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#ModalDelete">Réinitialiser le Mot de passe</button>
						</div> -->
				</div>
			</div>
		</div>
		<div class="d-flex mt-auto justify-content-end align-items-center text-muted fixed-bottom">
			<kbd class="small">CEN Normandie © <?php echo date("Y"); ?></kbd>
		</div>
	</div>
</div>
<!-- Modal -->
<!-- <div class="modal fade" id="CreateAccount" tabindex="-1" aria-labelledby="CreateAccountLabel" aria-hidden="true" >
  <div class="modal-dialog modal-lg" >
    <div class="modal-content" style="max-height:90vh;overflow-y:scroll;">
      <div class="d-flex modal-header">
		<div class="d-flex w-100">
			<h4 class="modal-title" id="CreateAccountLabel">Bienvenu !</h4>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
      </div>
      <div class="modal-body">
		<div class="alert alert-info m-y-2 small">
			Pour vous inscrire, veuillez renseigner les champs suivants et lire <strong>attentivement</strong> les conditions d'utilisation.
		</div>
		<form class="eventInsFormModal" action="#" method="post">
			<div class="input-group mt-4">
				<div class="input-group-prepend">
				<span class="input-group-text">Courriel et Mot de passe</span>
				</div>
				<input id="inscription_mail" type="text" class="form-control" placeholder="courriel">
				<input id="inscription_pwd"  type="text" class="form-control" placeholder="mot de passe">
			</div>
			<small  class="help-block mt-1 mb-4">8 caractères dont 1 Majuscule, 1 chiffre, 1 caractère spécial (?!#_)</small>
			<div class="input-group my-4">
				<div class="input-group-prepend">
				<span class="input-group-text">Nom et Prénom</span>
				</div>
				<input id="inscription_nom" type="text" class="form-control" placeholder="Barnaby">
				<input id="inscription_prenom"  type="text" class="form-control" placeholder="Jack">
			</div>
			<div class="input-group mt-4">
				<div class="input-group-prepend">
				<span class="input-group-text">Je ne suis pas un robot :</span>
				</div>
				<input id="verif" type="text" class="form-control" placeholder="MAJUSCULES" onblur="" >
				<img id="cap" src="php/captcha.php" style="border:solid 1px #ced4da;"></img>
				<a id="refresh" class="btn btn-default bg-light" style="border:solid 1px #ced4da;"><i  class="fas fa-sync"></i></a>
			</div>
			<small  class="help-block mt-1 mb-4">Recopiez les caractères ci-dessus</small>
			<div id="cgu_content" class="my-4">
				<h3>Conditions Générales d'Utilisation :</h3>
				<p class="small">
				Je soussigné·e <strong><span id="report_nom" class=""></span> <span id="report_prenom" class="strong"></span></strong> , exerçant au sein du Conservatoire d’Espaces Naturels Normandie (ci-après dénommée « CEN Normandie »), étant à ce titre amené·e à accéder à des données à caractère personnel, déclare reconnaître la confidentialité desdites données.<br><br>
				Je m’engage par conséquent, conformément aux articles 34 et 35 de la loi du 6 janvier 1978 modifiée relative à l’informatique, aux fichiers et aux libertés ainsi qu’aux articles 32 à 35 du règlement général sur la protection des données du 27 avril 2016, à prendre toutes précautions conformes aux usages et à l’état de l’art dans le cadre de mes attributions afin de protéger la confidentialité des informations auxquelles j’ai accès, et en particulier d’empêcher qu’elles ne soient communiquées à des personnes non expressément autorisées à recevoir ces informations.
				<br>Je m’engage en particulier à :<br></p>
				<div class="p-3">
				<li class="small" >ne pas utiliser les données auxquelles je peux accéder à des fins autres que celles prévues par mes attributions </li>
				<li class="small" >ne divulguer ces données qu’aux personnes dûment autorisées, en raison de leurs fonctions, à en recevoir communication, qu’il s’agisse de personnes privées, publiques, physiques ou morales </li>
				<li class="small" >ne faire aucune copie de ces données sauf à ce que cela soit nécessaire à l’exécution de mes fonctions </li>
				<li class="small" >prendre toutes les mesures conformes aux usages et à l’état de l’art dans le cadre de mes attributions afin d’éviter l’utilisation détournée ou frauduleuse de ces données </li>
				<li class="small" >prendre toutes précautions conformes aux usages et à l’état de l’art pour préserver la sécurité physique et logique de ces données </li>
				<li class="small" >m’assurer, dans la limite de mes attributions, que seuls des moyens de communication sécurisés seront utilisés pour transférer ces données </li>
				<li class="small" >en cas de cessation de mes fonctions, restituer intégralement les données, fichiers informatiques et tout support d’information relatif à ces données.</li>
				<p class="small">Cet engagement de confidentialité, en vigueur pendant toute la durée de mes fonctions, demeurera effectif, sans limitation de durée après la cessation de mes fonctions, quelle qu’en soit la cause, dès lors que cet engagement concerne l’utilisation et la communication de données à caractère personnel.<br>
				</div>
				J’ai été informé que toute violation du présent engagement m’expose à des sanctions disciplinaires et pénales conformément à la réglementation en vigueur, notamment au regard des articles 226-16 à 226-24 du code pénal.<br>
				</p><br>
				<div class="form-check form-switch">
					<input class="form-check-input" type="checkbox" id="cgu_c">
					<label class="form-check-label" for="cgu_c"><strong>J'ai lu et j'accepte les conditions générales d'utilisation</strong></label>
				</div>
			</div>
		</form>
      </div>
      <div class="modal-footer">
		<button type="button" id="save_create_account" class="btn btn-primary"  aria-hidden="true">Enregistrer</button>
      </div>
    </div>
  </div>
</div> -->

		
<!-- 		<div id="ModalDelete" class="modal fade" >
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<div class="d-flex w-100">
							<h4 class="modal-title" id="ModalDeleteLabel">Modification Mot de passe</h4>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
					</div>
					<div class="modal-body" >
						<div class="row text-left">
							<form class="eventInsFormModal" action="#" method="post">
								<div class="input-group mt-4">
									<div class="input-group-prepend">
									<span class="input-group-text">Courriel</span>
									</div>
									<input id="update_pwd_mail" type="text" class="form-control" placeholder="xxxxxxxx@yyyyyyy.fr">
								</div>
							</form>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" id="save_update_pwd_mail" class="btn btn-primary"  aria-hidden="true">Réinitialiser le mot de passe</button>
					</div>
				</div>
			</div>
		</div> -->
    <!-- jQuery -->
    <script src="js/jquery.js"></script>
    <!-- Bootstrap Core JavaScript -->
	<script src="bootstrap-5.0.0/js/bootstrap.min.js"></script>
    <script src="js/plugins/jquery-ui-1.12.1.custom/jquery-ui.js" ></script>
    <!--Custom-->
    <script src="js/index.js"></script>
    
    <script>
    if (sessionStorage.getItem('trying') === 'account') {
        $('#ModalLogin').show();
    }
	$("#refresh").click( function () {
		$("img#cap").attr("src","php/captcha.php?_="+((new Date()).getTime()));
	});


$(document).ready(function(){
});
    </script>
</body>
</html>
