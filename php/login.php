<?php
session_start ();
include 'properties.php';

if (isset($_SESSION)) {
    session_destroy();
}

$mail_bd_result = '';
$password_bd_result = '';
$id_user_bd_result = '';
$id_ids_obs_bd_result = '';
$nom_ids_obs_bd_result = '';

if (isset($_POST['email']) && isset($_POST['password'])) {
    if( ($_POST['email'] != '') && ($_POST['password'] != '') ) {

        $ldaphost="192.168.0.211";
        $ldapconn=ldap_connect($ldaphost);
        if($ldapconn)
            //echo "Connect success<br>";
        //else
            //echo "Connect Failure";
        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

        if ($ldapconn) {
            $log = "CSNHN\\".$_POST['email'];
            // binding to ldap server
            $ldapbind = ldap_bind($ldapconn, $log, $_POST['password']);
            // verify binding
            if ($ldapbind) {
                    $filter="(sAMAccountName=".$_POST['email'].")";
                    $result=ldap_search($ldapconn, "DC=CSNHN,DC=LOCAL", $filter);
                    $entries= ldap_get_entries($ldapconn, $result);
                    $groups = $entries[0]["memberof"];

                    session_start ();
                    $_SESSION['email'] = $entries[0]["mail"][0];
                    $_SESSION['password'] = $_POST['password'];
                    $_SESSION['u_nom_user_progecen'] = $entries[0]["name"][0];
                    $_SESSION['u_responsable'] = false;
                    $_SESSION['u_ge_caen'] = false;
                    $_SESSION['u_ge_rouen'] = false;
                    $_SESSION['u_zoot'] = false;
                    $_SESSION['session'] = $entries[0]["mail"][0];
                    $_SESSION['cgu'] = false ;
                    $_SESSION['is_equipe_si'] = false;
                    $_SESSION['is_equipe_rh'] = false;
        
                    foreach($groups as $group) {
                        if( str_contains($group, 'PROGECEN_RESP_PROJET')) {
                            $_SESSION['u_responsable'] = true;
                        }
                        if( str_contains($group, 'EQUIPE_GE_CAEN')) {
                            $_SESSION['u_ge_caen'] = true;
                        }
                        if( str_contains($group, 'PROGECEN_EQUIPETECHNIQUE_ROUEN')) {
                            $_SESSION['u_ge_rouen'] = true;
                        }
                        if( str_contains($group, 'PROGECEN_EQUIPEZOOT_ROUEN')) {
                            $_SESSION['u_zoot'] = true;
                        }
                        if( str_contains($group, 'CGU_Foncier')) {
                            $_SESSION['cgu'] = true;
                        }
                        if( str_contains($group, 'FILIERE_GEOMATIQUE')) {
                            $_SESSION['is_equipe_si'] = true;
                        }
                        if( str_contains($group, 'FILIERE_RESSOURCES_HUMAINE')) {
                            $_SESSION['is_equipe_rh'] = true;
                        }
                    }

                    $filter="(cn=PROGECEN_RESP_PROJET)";
                    $result=ldap_search($ldapconn, "DC=CSNHN,DC=LOCAL", $filter);
                    $entries= ldap_get_entries($ldapconn, $result);
                    $groups = $entries[0]["member"];
                    $list_responsable = array();
                    foreach($groups as $group) {
                        if (str_contains($group, "CN=")) {
                            $name_a = explode("CN=", $group)[1];
                            $name_ = explode(",OU", $name_a)[0];
                            array_push($list_responsable, $name_);
                        }
                        sort($list_responsable);
                    }
                    $ii = 0;
                    //liste des responables projets triés
                    $responsables= array();
                    //liste des responables projets triés html
                    $responsables_html = '';
                    //liste des responables projets triés html
                    $responsables_html_wo_id = '';
                    foreach($list_responsable as $resp) {
                        $responsables[$resp] = $ii." - ".$resp;
                        $responsables_html = $responsables_html.'<option value = "'.$ii.' - '.$resp.'">'.$ii.' - '.$resp.'</option>';
                        $responsables_html_wo_id = $responsables_html_wo_id.'<option value = "'.$resp.'">'.$resp.'</option>';
                        $ii ++;
                    }
                    $_SESSION['responsables']=$responsables;
                    $_SESSION['responsables_html']=$responsables_html;
                    $_SESSION['responsables_html_wo_id']=$responsables_html_wo_id ;

                if($_SESSION['cgu']) {
                    echo "Success";
                } else {
                    echo "CGU";
                }
                
            } else {
                echo "LDAP bind failed...";
            }
        }


/*         $dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")or die ('Connexion impossible :'. pg_last_error());
        $result = pg_prepare($dbconn, "sql", 
        "select 
        i.u_courriel,
        i.u_pwd, 
        i.u_id_session , 
        i.u_nom||' '||i.u_prenom as nom_prenom, 
        i.u_id ,
        i.u_prenom||' '||i.u_nom as prenom_nom,
        i.u_id_progecen,
        i.u_responsable
        from $users i
        where (i.u_pwd = md5( $1 ) and i.u_courriel = $2)
        "
        );
        
        $result = pg_execute($dbconn, "sql", array($_POST['password'] ,$_POST['email']));
        while($row = pg_fetch_row($result))
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
        pg_close($dbconn); */
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