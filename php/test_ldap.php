<?php
//For testing the AD server is work or not
$ldaphost="192.168.0.211";
$ldapconn=ldap_connect($ldaphost);
if($ldapconn) {
	echo "Connect success<br>";
}
else
	echo "Connect Failure";
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
//Reference：http://php.net/manual/en/function.ldap-bind.php

/* echo '################################ A USER ################################ ################################</br>';
if ($ldapconn) {
    // binding to ldap server
    $ldapbind = ldap_bind($ldapconn, "CSNHN\...", "...");
    // verify binding
    if ($ldapbind) {
        echo "LDAP bind successful... A USER";
            //POUR AVOIR UN TABLEAU AVEC LA LISTE DES SALARIES
            $filter="(cn=....)";
            $result=ldap_search($ldapconn, "DC=CSNHN,DC=LOCAL", $filter);
            $entries= ldap_get_entries($ldapconn, $result);
            $groups = $entries[0]["memberof"];
            print "<pre>";
            print_r($entries[0]);
            print "</pre>"; 
            
            
             $_SESSION['email'] = $entries[0]["mail"][0];
            $_SESSION['u_nom_user_progecen'] = $entries[0]["name"][0];
            $_SESSION['u_responsable'] = false;
            $_SESSION['u_ge_caen'] = false;
            $_SESSION['u_ge_rouen'] = false;
            $_SESSION['u_zoot'] = false;

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
                
            }

            print "<pre>";
            echo  "</br>".$_SESSION['email'];
            echo  "</br>".$_SESSION['u_nom_user_progecen'];
            if ($_SESSION['u_responsable']) {
                echo  "</br>Responsable";
            }
            print "</pre>";

    } else {
        echo "LDAP bind failed...";
    }
}
echo '################################ A USER ################################ ################################</br>';
 */
echo '################################ USERS ################################ ################################</br>';
if ($ldapconn) {
    // binding to ldap server
    $ldapbind = ldap_bind($ldapconn, "CSNHN\BP", "JR4Love#");
    // verify binding
    if ($ldapbind) {
        echo "LDAP bind successful... A USER";
            $filter="(cn=progecen_user)";
            $result=ldap_search($ldapconn, "DC=CSNHN,DC=LOCAL", $filter);
            $entries= ldap_get_entries($ldapconn, $result);
            $groups = $entries[0]["member"];
            foreach($groups as $group) {
                if (str_contains($group, "CN=")) {
                    print "<pre>";
                    $name_a = explode("CN=", $group)[1];
                    $name_ = explode(",OU", $name_a)[0];
                    print_r($name_);
                    print "</pre>";
                }
            }
             
    } else {
        echo "LDAP bind failed...";
    }
}
echo '################################ USERS ################################ ################################</br>';

echo '################################ GROUP ################################ ################################</br>';
if ($ldapconn) {
    // binding to ldap server
    $ldapbind = ldap_bind($ldapconn, "CSNHN\BP", "JR4Love#");
    // verify binding
    if ($ldapbind) {
        echo "LDAP bind successful... A USER";
            //POUR AVOIR UN TABLEAU AVEC LA LISTE DES SALARIES
            $filter="(cn=PROGECEN_RESP_PROJET)";
            $result=ldap_search($ldapconn, "DC=CSNHN,DC=LOCAL", $filter);
            $entries= ldap_get_entries($ldapconn, $result);
            $groups = $entries[0]["member"];
             print "<pre>";
            print_r($entries[0]["member"]);
            print "</pre>"; 
            $list_responsable = array();
            foreach($groups as $group) {
                
                if (str_contains($group, "CN=")) {
                    $name_a = explode("CN=", $group)[1];
                    $name_ = explode(",OU", $name_a)[0];
                    array_push($list_responsable, $name_);
                }
                sort($list_responsable);
            }
            print "<pre>";
            print_r($list_responsable);
            print "</pre>";
            
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
            
            print "<pre>";
            print_r($responsables);
            print 'html';
            echo($responsables_html);
            print 'html wo id';
            echo($responsables_html_wo_id);
            print "</pre>";

    } else {
        echo "LDAP bind failed...";
    }
}
echo '################################ GROUP ################################ ################################</br>';

?>