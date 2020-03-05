<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>PRAM Normandie</title>
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link href="css/leaflet.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">
    <link href="css/c_home.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    
    <!-- DATATABLES -->
    <link href="js/plugins/datatables_all.min.js" rel="stylesheet">
    <!--Autocomplete UI -->
    <link href="css/plugins/jquery-ui.css" rel="stylesheet">
    <link href="css/plugins/ui_autocomplete.css" rel="stylesheet">
    
</head>

<?php
session_start();
include 'php/properties.php';
if (!isset($_SESSION['email'])) {
    //$_SESSION['email'] = "b.perceval@cenbn.fr"; /* A desactiver pour mise en ligne */
    header('Location: index.php');
    exit();
};
if (!isset($_SESSION['password'])) {
    //$_SESSION['password'] = "bpsicen"; /* A desactiver pour mise en ligne */
};
if (!isset($_SESSION['id_sicen'])) {
    //$_SESSION['id_sicen'] = 21; /* A desactiver pour mise en ligne */
};

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());
$sql = "select i.u_nom,i.u_prenom, i.u_id_nom_structure, i.u_logo from $users i where i.u_courriel = '".$_SESSION['email']."' AND i.u_pwd = md5('".$_SESSION['password']."') ";
//execute la requete dans le moteur de base de donnees  
$query_result1 = pg_exec($dbconn,$sql) or die (pg_last_error());
while($row = pg_fetch_row($query_result1))
{
    $nom         = $row[0];
    $prenom      = $row[1];
    $structure   = $row[2];
    $logo        = $row[3];
}
//ferme la connexion a la BD
pg_close($dbconn);


?>

<body>
    <div id="wrapper">
        <?php include('menu.inc.php'); ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <!-- /.Page Heading -->
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1>Mes informations</h1>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>NOM Prénom :</label>
                            <p id="nom_prenom" class="form-control-static"><?php echo $nom.' '.$prenom; ?></p>
                        </div>
                        <div class="form-group">
                            <label>Courriel :</label>
                            <p id="courriel" class="form-control-static"><?php echo $_SESSION['email'];?></p>
                        </div>
                        <div class="form-group">
                            <label>Structure :</label>
                            <p id="structure" class="form-control-static"><?php echo $structure; ?></p>
                        </div>
                        <div class="form-group">
                            <label>Mot de passe :</label>
                            <p id="password" class="form-control-static"><?php echo $_SESSION['password'];?></p>
                        </div>
                        <div class="form-group">
                            <label>Logo :</label>
                            <p id="logo" class="form-control-static"></p>
                            <img src='img/logos/<?php echo $logo; ?>' style="height:60px;" />
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div id="evolution" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                    </div>
                    <div class="col-lg-12">
                        <i id="" class="fa fa-edit" style="font-size:16px;color:#004fa2;cursor: pointer;" data-toggle="modal" data-target="#ModalEdit">Éditer les données</i>
                    </div>
                </div>
                <!-- /.row -->
                <?php include('footer.inc.php'); ?>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

<div id="ModalEdit" class="modal fade" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <div class="col-sm-12">
                    <p ></p>
                </div>
            </div>
            <div class="modal-body" >
                <div class="row text-left">
                    <div class="col-sm-12">
                    <form class="eventInsFormModal" action="#" method="post">
                        <div class="col-sm-12">
                            <div class="col-sm-4">
                            <label>Nom :</label>
                            <input id="i_nom" class="form-control input-sm" value="<?php echo $nom; ?>" type="text"></input>
                            </div>
                            <div class="col-sm-4">
                            <label>Prenom:</label>
                            <input id="i_prenom" class="form-control input-sm" value="<?php echo $prenom; ?>" type="text"></input>
                            </div>
                            <div class="col-sm-4">
                            <label>Courriel:</label>
                            <input id="i_courriel" class="form-control input-sm" value="<?php echo $_SESSION['email'];?>" type="text"  disabled></input>
                            <small  class="help-block">N'est pas modifiable</small >
                            </div>
                            <div class="col-sm-4">
                            <label>Mot de passe :</label>
                            <input id="i_pwd" class="form-control input-sm" type="text" placeholder="zZZ11zz&&" value="<?php echo $_SESSION['password'];?>" type="text">
                            <small  class="help-block">8 caractères dont 1 Majuscule, 1 chiffre, 1 caractère spécial</small >
                            </div>
                            <div class="col-sm-4">
                            <label>Structure :</label>
                            <input id="i_structure" class="form-control input-sm" type="text" placeholder="CEN" value="<?php echo $structure; ?>" type="text">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="col-sm-12">
                            <label>Logo / Image :</label>
                            <input id="i_logo" class="custom-file-input" type="file" type="text" >
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="save_i_edit" class="btn btn-primary"  aria-hidden="true">Enregistrer</button> <!--data-dismiss="modal"-->
            </div>
        </div>
    </div>
</div>

    </div>
    <!-- /#wrapper -->
    <!-- jQuery -->
    <script src="js/jquery.js"></script>
    <!-- JQUERY AUTOCOMPLETE -->
    <script src="js/plugins/jquery-ui-1.12.1.custom/jquery-ui.js" ></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <!--HighCharts-->
    <script src="js/plugins/highcharts/code/highcharts.js" ></script>
    <script src="js/plugins/highcharts/code/modules/exporting.js" ></script>
    
    <!--Custom-->
    <script src="js/infos.js"></script>
    <script>
    yop();
    var validImageTypes = ['image/gif', 'image/jpeg', 'image/png'];
    var file_logo = false;
    $("#i_logo").change(function() {
        file_logo = true;
        file = this.files[0];
        fileType = file['type'];
        fileName = this.files[0].name;
    });
    
    
    </script>
    
</body>
</html>
