<?php


//For testing the AD server is work or not
$ldaphost="192.168.0.211";
$ldapconn=ldap_connect($ldaphost);
//$filter = "(&(mail=$courriel)(memberof=CN=PROGECEN_SALARIE,CN=Users,DC=CSNHN,DC=LOCAL))";
$ldaptree    = "memberof=CN=PROGECEN_SALARIE,DC=CSNHN,DC=LOCAL";
if($ldapconn) {
	echo "Connect success<br>";
    ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
    // binding to ldap server
    $ldapbind = ldap_bind($ldapconn, "CSNHN\Administrateur", "CENN2021");
    // verify binding
    if ($ldapbind) {
        $result = ldap_search($ldapconn,$ldaptree, "(cn=*)") or die ("Error in search query: ".ldap_error($ldapconn));
        $data = ldap_get_entries($ldapconn, $result);
        
        // SHOW ALL DATA
        echo '<h1>Dump all data</h1><pre>';
        print_r($data);    
        echo '</pre>';
        
        
        // iterate over array and print data for each entry
        echo '<h1>Show me the users</h1>';
        for ($i=0; $i<$data["count"]; $i++) {
            //echo "dn is: ". $data[$i]["dn"] ."<br />";
            echo "User: ". $data[$i]["cn"][0] ."<br />";
            if(isset($data[$i]["mail"][0])) {
                echo "Email: ". $data[$i]["mail"][0] ."<br /><br />";
            } else {
                echo "Email: None<br /><br />";
            }
        }
        // print number of entries found
        echo "Number of entries found: " . ldap_count_entries($ldapconn, $result);
            
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