<?php


//For testing the AD server is work or not
$ldaphost="192.168.0.211";
$ldapconn=ldap_connect($ldaphost);
if($ldapconn) {
	echo "Connect success<br>";
    ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
    // binding to ldap server
    $ldapbind = ldap_bind($ldapconn, "CSNHN\Administrateur", "CENN2021");
    // verify binding
    if ($ldapbind) {
        echo "LDAP bind successful...";
        $ldap_base_dn = "DC=CSNHN,DC=LOCAL";
        $filter = "(memberof=CN=PROGECEN_SALARIE,CN=Users,DC=CSNHN,DC=LOCAL)";
        $justthese = array("mail", "samaccountname", "sn", "givenname", "cn", "memberof");
        $result = ldap_search($ldapconn, $ldap_base_dn, $filter, $justthese);
        $result_salarie = ldap_get_entries($ldapconn, $result);
        echo in_array("CN=PROGECEN_SALARIE,CN=Users,DC=CSNHN,DC=LOCAL", $result_salarie[0]["memberof"]);
        echo $result_salarie[0]["memberof"];
            
    } else {
        echo "LDAP bind failed...";
    }
}
else {
	echo "Connect Failure";
}



//echo copy("/var/www/html/nextcloud/data/8ACA0A1C-6E6E-4912-8511-DB8A02F1CA67/files/QGIS/Saisie_Libre/saisie_libre.gpkg",
//"/var/www/html/majiic/export/saisie_libre.gpkg");


exec('ogr2ogr -f PostgreSQL "PG:host=192.168.0.218 user=postgres password=postgres dbname=test" \
-lco SCHEMA=test \
-nln pointss \
/var/www/html/nextcloud/data/8ACA0A1C-6E6E-4912-8511-DB8A02F1CA67/files/QGIS/Saisie_Libre/saisie_libre.gpkg point \
');





?>