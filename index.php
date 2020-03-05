<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Cen Normandie</title>
    <link rel="shortcut icon" href="img/cenno.ico" />
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="css/c_home.css" rel="stylesheet">
    
    
    
</head>
<body>
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php">VisuDGFIP CEN Normandie</a>
            </div>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li>
                        <a href="index.php"><i class="fa fa-fw fa-sign-in"></i> Login</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>
        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Connexion à l'application VisuDGFIP du CEN Normandie&nbsp;<img class="img_top" src="img/logo.png"/> :
                        </h1>
                    </div>
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-6">
                        <form role="form">
                            <div class="form-group">
                                <label>Identifiant :</label>
                                <input id="courriel" class="form-control" placeholder="courriel@mail.com" onblur="" >
                            </div>
                            <div class="form-group">
                                <label>Mot de passe :</label>
                                <input id="pwd" type="password" class="form-control" placeholder="xxxxxx" onblur="" >
                            </div>
                            <div class="form-group">
                                <!--<input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-login" value="Valider" alt="Submit">-->
                                <button type="button" id="signin" class="btn btn-lg btn-primary">Valider</button>
                            </div>
                            
                        </form>
                        <a class="create_account point_er" data-toggle="modal" data-target="#ModalLogin">Création de compte</a>
                    </div>
                    <div class="col-lg-6">
                    </div>
                </div>
                <!-- /.row -->
                <?php include('footer.inc.php'); ?>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
<div id="ModalLogin" class="modal fade" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <div class="col-sm-6">
                    <h4 class="modal-title" id="">Bienvenu !</h4>
                </div>
                <div class="col-sm-12">
                    <p >Pour vous inscrire, veuillez renseigner les champs suivants et lire <strong>attentivement</strong> les conditions d'utilisation.</p>
                    <p>Une fois le compte activé, vous pourrez consulter les données !</p>
                </div>
            </div>
            <div class="modal-body" >
                <div class="row text-left">
                    <div class="col-sm-12">
                    <form class="eventInsFormModal" action="#" method="post">
                        <div class="col-sm-12">
                            <div class="col-sm-3">
                            <label>Courriel:</label>
                            <input id="inscription_mail" class="form-control input-sm" placeholder="xxxxxxxx@yyyyyyy.fr" type="text"></input>
                            </div>
                            <div class="col-sm-3">
                            <label>Mot de passe :</label>
                            <input id="inscription_pwd" class="form-control input-sm" type="text" placeholder="zZZ11zz&&" type="text">
                            <small  class="help-block">8 caractères dont 1 Majuscule, 1 chiffre, 1 caractère spécial (?!#_)</small >
                            </div>
                            <div class="col-sm-3">
                            <label>Nom :</label>
                            <input id="inscription_nom" class="form-control input-sm" type="text" placeholder="Barnaby" type="text">
                            <small  class="help-block"></small >
                            </div>
                            <div class="col-sm-3">
                            <label>Prénom :</label>
                            <input id="inscription_prenom" class="form-control input-sm" type="text" placeholder="Jack" type="text">
                            <small  class="help-block"></small >
                            </div>
                            <div class="col-sm-12">
                                <label>Je ne suis pas un robot :</label>
                                <div class="tooltip_c">
                                <img src="php/captcha.php"></img>
                                <span class="tooltiptext">En MAJ : ABCDEFGHIJKL MNOPQRSTUVWXYZ</span>
                                </div>
                                <input id="verif" type="text" class="form-control" placeholder="xxxxxx" onblur="" >
                            </div>
                            <div id="cgu_content" class="col-sm-12 importante">
                                <h3>Conditions Générales d'Utilisation :</h3>
<p>
Je soussigné·e <strong><span id="report_nom" class=""></span> <span id="report_prenom" class="strong"></span></strong> , exerçant au sein du Conservatoire d’Espaces Naturels Normandie (ci-après dénommée « CENNO »), étant à ce titre amené·e à accéder à des données à caractère personnel, déclare reconnaître la confidentialité desdites données.<br><br>
Je m’engage par conséquent, conformément aux articles 34 et 35 de la loi du 6 janvier 1978 modifiée relative à l’informatique, aux fichiers et aux libertés ainsi qu’aux articles 32 à 35 du règlement général sur la protection des données du 27 avril 2016, à prendre toutes précautions conformes aux usages et à l’état de l’art dans le cadre de mes attributions afin de protéger la confidentialité des informations auxquelles j’ai accès, et en particulier d’empêcher qu’elles ne soient communiquées à des personnes non expressément autorisées à recevoir ces informations.
<br>Je m’engage en particulier à :<br>
<li>ne pas utiliser les données auxquelles je peux accéder à des fins autres que celles prévues par mes attributions </li>
<li>ne divulguer ces données qu’aux personnes dûment autorisées, en raison de leurs fonctions, à en recevoir communication, qu’il s’agisse de personnes privées, publiques, physiques ou morales </li>
<li>ne faire aucune copie de ces données sauf à ce que cela soit nécessaire à l’exécution de mes fonctions </li>
<li>prendre toutes les mesures conformes aux usages et à l’état de l’art dans le cadre de mes attributions afin d’éviter l’utilisation détournée ou frauduleuse de ces données </li>
<li>prendre toutes précautions conformes aux usages et à l’état de l’art pour préserver la sécurité physique et logique de ces données </li>
<li>m’assurer, dans la limite de mes attributions, que seuls des moyens de communication sécurisés seront utilisés pour transférer ces données </li>
<li>en cas de cessation de mes fonctions, restituer intégralement les données, fichiers informatiques et tout support d’information relatif à ces données.</li>
Cet engagement de confidentialité, en vigueur pendant toute la durée de mes fonctions, demeurera effectif, sans limitation de durée après la cessation de mes fonctions, quelle qu’en soit la cause, dès lors que cet engagement concerne l’utilisation et la communication de données à caractère personnel.<br>
J’ai été informé que toute violation du présent engagement m’expose à des sanctions disciplinaires et pénales conformément à la réglementation en vigueur, notamment au regard des articles 226-16 à 226-24 du code pénal.<br>
</p><br>
                                <input id="cgu_c" value="" type="checkbox" ><strong>J'ai lu et j'accepte les conditions générales d'utilisation</strong></input>

                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="save_create_account" class="btn btn-primary"  aria-hidden="true">Enregistrer</button> <!--data-dismiss="modal"-->
            </div>
        </div>
    </div>
</div>
    </div>
    <!-- /#wrapper -->
    <!-- jQuery -->
    <script src="js/jquery.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/jquery-ui-1.12.1.custom/jquery-ui.js" ></script>
    <!--Custom-->
    <script src="js/index.js"></script>
    
    <script>
    if (sessionStorage.getItem('trying') === 'account') {
        $('#ModalLogin').modal();
    }
    </script>
    
</body>
</html>
