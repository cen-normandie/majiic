<?php
include 'properties.php';

$ldaphost="192.168.0.211";
$ldapconn=ldap_connect($ldaphost);
if($ldapconn)
	echo "Connect success<br>";
else
	echo "Connect Failure";
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

echo '################################ USERS ################################ ################################</br>';
if ($ldapconn) {
    // binding to ldap server
    $ldapbind = ldap_bind($ldapconn, "CSNHN\BP", "JR4Love#");
    // verify binding
    if ($ldapbind) {

    //VIDE LA TABLE
    //connexion a la BD
    $dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS") or die ('Connexion impossible :'. pg_last_error());
    $result = pg_prepare($dbconn, "delete", "DELETE FROM $progecen_personnes_");
    pg_execute($dbconn, "delete",array()) or die ( pg_last_error());
    pg_close($dbconn);



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
                    $filter="(name=".$name_.")";
                    $result_=ldap_search($ldapconn, "DC=CSNHN,DC=LOCAL", $filter);
                    $entries_= ldap_get_entries($ldapconn, $result_);
                    //$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS") or die ('Connexion impossible :'. pg_last_error());
                    //$result = pg_prepare($dbconn, "insert", "INSERT INTO $progecen_personnes_ (personne) VALUES ($1)");
                    //pg_execute($dbconn, "insert",array($name_ )) or die ( pg_last_error());
                    //pg_close($dbconn);
                    print_r($entries_[0]["name"][0]);//Nom
                    print_r($entries_[0]["mail"][0]);//B.Nom@cen-nxxx.fr
                    print_r($entries_[0]["department"][0]);//
                    print_r($entries_[0]["description"][0]);//site Caen
                    print_r($entries_[0]["mobile"][0]);// 06 06 06 06 06

                    print "</pre>";
                }
            }
             
    } else {
        echo "LDAP bind failed...";
    }
}
echo '################################ USERS ################################ ################################</br>';

?>