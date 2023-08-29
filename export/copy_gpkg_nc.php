<?php


//For testing the AD server is work or not
$ldaphost="192.168.0.211";
$ldapconn=ldap_connect($ldaphost);

if($ldapconn) {
	echo "Connect success<br>";
    ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
    // binding to ldap server
    $ldapbind = ldap_bind($ldapconn, 'CSNHN\Administrateur', 'CENN2021');
    // verify binding
    if ($ldapbind) {
        echo "Bind success<br>";
        $filter="(memberof=CN=NextcloudUsers,OU=Groupes,DC=CSNHN,DC=LOCAL)";
        $search=ldap_search($ldapconn, "DC=CSNHN,DC=LOCAL", $filter) or die ("Error in search query: ".ldap_error($ldapconn));
        $result= ldap_get_entries($ldapconn, $search);
        for ($i=0; $i<$result["count"]; $i++)
        {
            echo $result[$i]["description"][0];
            echo ' || '.$result[$i]["mail"][0];
            echo ' || '.$result[$i]["cn"][0].'<br>';
        }   
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