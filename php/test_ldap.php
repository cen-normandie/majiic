<?php
//For testing the AD server is work or not
$ldaphost="__.__.__.___";
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
    $ldapbind = ldap_bind($ldapconn, "CSNHN\...", "...");
    // verify binding
    if ($ldapbind) {
        echo "LDAP bind successful...";
        
        
        
    } else {
        echo "LDAP bind failed...";
    }
}
?>