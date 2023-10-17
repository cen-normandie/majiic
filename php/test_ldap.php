<?php
//For testing the AD server is work or not
$ldaphost="192.168.0.211";
$ldapconn=ldap_connect($ldaphost);
if($ldapconn)
	echo "Connect success<br>";
else
	echo "Connect Failure";
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
//Reference：http://php.net/manual/en/function.ldap-bind.php
if ($ldapconn) {
    // binding to ldap server
    $ldapbind = ldap_bind($ldapconn, "CSNHN\Administrateur", "CENN2021");
    // verify binding
    if ($ldapbind) {
        echo "LDAP bind successful...";
            //POUR AVOIR UN TABLEAU AVEC LA LISTE DES SALARIES
            $filter_salarie="(memberof=CN=enabled,OU=Groupes,DC=CSNHN,DC=LOCAL)";//CN=enabled,OU=Groupes,DC=CSNHN,DC=LOCAL
            $sr_salarie=ldap_search($ldapconn, "DC=CSNHN,DC=LOCAL", $filter_salarie);
            $result_salarie= ldap_get_entries($ldapconn, $sr_salarie);

            for ($i=0; $i<$result_salarie["count"]; $i++)
            {
                echo '################################</br>';
                echo $result_salarie[$i]["cn"][0].'</br>'; 
                $output = $result_salarie[$i]['memberof'];
                //output[0] renvoit -->  CN=GP_MPUBLIC,OU=CSNHN_USERS,DC=CSNHN,DC=LOCAL
                // Si une chaine contient responsable projet
                foreach($output as $str_ldap_grp) {
                    if( str_contains($str_ldap_grp, 'PROGECEN_RESP_PROJET')) {
                        echo ' : '.'Responsable PROJET'.'</br>';
                        // Break loop
                        break;
                    }
                }
            }
        
    } else {
        echo "LDAP bind failed...";
    }
}
?>