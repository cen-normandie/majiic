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
                    $_SESSION['u_saf'] = false;
                    $_SESSION['u_ge_caen'] = false;
                    $_SESSION['u_ge_rouen'] = false;
                    $_SESSION['u_zoot'] = false;
                    $_SESSION['session'] = $entries[0]["mail"][0];
                    $_SESSION['cgu'] = false ;
                    $_SESSION['is_equipe_si'] = false;
                    $_SESSION['is_equipe_rh'] = false;
                    $_SESSION['fdt_optimisation'] = false;
        
                    foreach($groups as $group) {
                        if( str_contains($group, 'progecen_resp_projet')) {
                            $_SESSION['u_responsable'] = true;
                        }
                        if( str_contains($group, 'ge_caen')) {
                            $_SESSION['u_ge_caen'] = true;
                        }
                        if( str_contains($group, 'ge_rouen')) {
                            $_SESSION['u_ge_rouen'] = true;
                        }
                        if( str_contains($group, 'saf_fdt')) {
                            $_SESSION['u_saf'] = true;
                        }
                        if( str_contains($group, 'zoo')) {
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
                        if( str_contains($group, 'fdt_optimisation')) {
                            $_SESSION['fdt_optimisation'] = true;
                        }
                    }

                    $filter="(cn=progecen_resp_projet)";
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
                    /* $ii = 0;
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
                    $_SESSION['responsables_html_wo_id']=$responsables_html_wo_id ; */

                echo "Success";
            } else {
                echo "LDAP bind failed...";
            }
        }

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