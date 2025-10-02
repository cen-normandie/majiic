<?php 
/* include '../php/properties.php';

$_id_projet = 2872;


function replace_char_html_($str) {
    $init = array("é", "è", "à","ç", "É","ï");
    $outt   = array("&eacute;","&egrave;","&agrave;","&ccedil;","&Eacute;","&iuml;");
    return str_replace($init, $outt, $str);
}



//For testing the AD server is work or not
$ldaphost="192.168.0.211";
$ldapconn=ldap_connect($ldaphost);
if($ldapconn)
	echo "Connect success<br>";
else
	echo "Connect Failure";
$ad_id = "CSNHN\\"."$AD_admin";
$ad_pwd = $AD_admin_pwd;
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
//Reference：http://php.net/manual/en/function.ldap-bind.php

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS") or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "insert", "INSERT INTO progecen_copy.actions (code_action, financements,personnes, id_projet , nb_h_previ) VALUES ($1, $2, $3, $4, $5)");

echo '################################ A USER ################################ ################################</br>';
if ($ldapconn) {
    // binding to ldap server
    $ldapbind = ldap_bind($ldapconn, $ad_id, $ad_pwd);
    // verify binding
    if ($ldapbind) {
        echo "LDAP bind successful... A USER";

        //POUR AVOIR UN TABLEAU AVEC LA LISTE DES SALARIES
        $filter_salarie="(memberof=CN=enabled,OU=Groupes,DC=CSNHN,DC=LOCAL)";//CN=enabled,OU=Groupes,DC=CSNHN,DC=LOCAL
        $sr_salarie=ldap_search($ldapconn, "DC=CSNHN,DC=LOCAL", $filter_salarie);
        $result_salarie= ldap_get_entries($ldapconn, $sr_salarie);

        for ($i=0; $i<$result_salarie["count"]; $i++)
        {
            echo '################################</br>';
            echo $result_salarie[$i]["cn"][0].'</br>'; 
            $output = $result_salarie[$i][0];
            $mail = $result_salarie[$i]["mail"][0];
            $name = $result_salarie[$i]["cn"][0];
            $intitule = $result_salarie[$i]["title"][0];

            

            print "<pre>";
            print_r($mail);
            print_r($name);
            print_r($intitule);
            print_r($tel_string);
            print_r($bu);
            print "</pre>";


            pg_execute($dbconn, "insert",array('Formation','CENNormandie_100', $name, $_id_projet, 8)) or die ( pg_last_error());
            //





        }

    } else {
        echo "LDAP bind failed...";
    }
}
pg_close($dbconn); */

?>
