<?php
include 'properties.php';

if (isset($_SESSION)) {
    session_destroy();
}





$mail_bd_result = '';
$password_bd_result = '';
$id_user_bd_result = '';
$id_ids_obs_bd_result = '';
$nom_ids_obs_bd_result = '';

$pwd = pg_escape_string($_POST['password']);
$courriel = pg_escape_string($_POST['email']);

if (isset($_POST['email']) && isset($_POST['password'])) {
    if( ($_POST['email'] != '') && ($_POST['password'] != '') ) {
        $dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS") or die ('Connexion impossible :'. pg_last_error());
        $sql_init = "select 
        i.u_courriel,
        i.u_pwd, 
        i.u_id_session , 
        i.u_nom||' '||i.u_prenom as nom_prenom, 
        i.u_id ,
        i.u_prenom||' '||i.u_nom as prenom_nom,
        i.u_id_progecen,
        i.u_responsable
        from $users i
        where (i.u_pwd = md5('". $_POST['password'] ."') and i.u_courriel = '".$_POST['email']."')";
        
        //LEFT JOIN $progecen_personnes p on lower(unaccent( p.personne )) = lower(unaccent( i.u_prenom||' '||i.u_nom )) 
        //execute la requete dans le moteur de base de donnees  
        $query_result1 = pg_exec($dbconn,$sql_init) or die (pg_last_error());
        while($row = pg_fetch_row($query_result1))
        {
            session_start ();
            $_SESSION['email']                  = trim($row[0]);
            $_SESSION['password']               = $_POST['password'];
            $_SESSION['session']                = trim($row[2]);
            $_SESSION['nom_prenom']             = $row[3];
            $_SESSION['u_id']                   = $row[4];
            //$_SESSION['u_nomuser_progecen']     = $row[5];
            $_SESSION['id_personne_progecen']   = $row[6];
            $_SESSION['u_nom_user_progecen']    = $row[5];
            $_SESSION['u_responsable']          = $row[7];
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