<?php
include 'properties.php';

$mail_bd_result = '';
$password_bd_result = '';
$id_user_bd_result = '';
$id_ids_obs_bd_result = '';
$nom_ids_obs_bd_result = '';

$pwd = pg_escape_string($_POST['password']);
$courriel = pg_escape_string($_POST['password']);

if (isset($_POST['email']) && isset($_POST['password'])) {
    if( ($_POST['email'] != '') && ($_POST['password'] != '') ) {
        $dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
        or die ('Connexion impossible :'. pg_last_error());
        $sql_init = "select i.u_courriel,i.u_pwd, i.u_id_session , i.u_nom||' '||i.u_prenom as nom_prenom, i.u_id from $users i where (i.u_pwd = md5('". $_POST['password'] ."') and i.u_courriel = '".$_POST['email']."')";
        
        //execute la requete dans le moteur de base de donnees  
        $query_result1 = pg_exec($dbconn,$sql_init) or die (pg_last_error());
        while($row = pg_fetch_row($query_result1))
        {
            session_start ();
            $_SESSION['email']              = $_POST['email'];
            $_SESSION['password']           = $_POST['password'];
            $_SESSION['session']            = trim($row[2]);
            $_SESSION['nom_prenom']         = $row[3];
            $_SESSION['u_id']               = $row[4];
            echo "Success";
        }
        //ferme la connexion a la BD
        pg_close($dbconn);
    }
    else {
        echo "Failed";
    }
}
else {
    /*header ('location: index.php');*/
    echo "Failed and failed";
}

?>