<?php
include 'properties.php';

        $ldaphost="192.168.0.211";
        $ldapconn=ldap_connect($ldaphost);
        if ($ldapconn) {
            ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
            $log = "CSNHN\\"."...";
            // binding to ldap server
            $ldapbind = ldap_bind($ldapconn, $log, "...");
            // verify binding
            if ($ldapbind) {
                echo "LDAP bind successful...";
                //genere la liste des utilisateurs progecen depuis l'ad (groupe : progecen_user)
                $filter="(cn=progecen_user)";
                $result=ldap_search($ldapconn, "DC=CSNHN,DC=LOCAL", $filter);
                $entries= ldap_get_entries($ldapconn, $result);
                $groups = $entries[0]["member"];
                $list_personne = array();

                foreach($groups as $group) {
                    if (str_contains($group, "CN=")) {
                        $name_a = explode("CN=", $group)[1];
                        $name_ = explode(",OU", $name_a)[0];
                        array_push($list_personne, $name_);
                    }
                    sort($list_personne);
                }
                
                $dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS") or die ('Connexion impossible :'. pg_last_error());
                $del = pg_prepare($dbconn, "sql_del", "DELETE FROM progecen_copy.liste_personne_ ");
                $del = pg_execute($dbconn, "sql_del", array());
                $seq = pg_prepare($dbconn, "sql_seq", "ALTER SEQUENCE progecen_copy.liste_personne__id_seq RESTART WITH 1;");
                $seq = pg_execute($dbconn, "sql_seq", array());
                
                $result = pg_prepare($dbconn, "sql", "INSERT INTO progecen_copy.liste_personne_ (personne) VALUES ( $1 );");
                foreach($list_personne as $personne) {
                    $result = pg_execute($dbconn, "sql", array($personne)) or die ('Connexion impossible :'. pg_last_error());
                    echo $personne;
                }
                pg_close($dbconn);

                //genere la liste des responsable de projet depuis l'ad (groupe : progecen_resp_projet)
                $filter="(cn=progecen_resp_projet)";
                $result=ldap_search($ldapconn, "DC=CSNHN,DC=LOCAL", $filter);
                $entries= ldap_get_entries($ldapconn, $result);
                $groups = $entries[0]["member"];
                $list_resp_projet = array();

                foreach($groups as $group) {
                    if (str_contains($group, "CN=")) {
                        $name_a = explode("CN=", $group)[1];
                        $name_ = explode(",OU", $name_a)[0];
                        array_push($list_resp_projet, $name_);
                    }
                    sort($list_resp_projet);
                }
                
                $dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS") or die ('Connexion impossible :'. pg_last_error());
                $del = pg_prepare($dbconn, "sql_del", "DELETE FROM progecen_copy.liste_resp_projet ");
                $del = pg_execute($dbconn, "sql_del", array());
                $seq = pg_prepare($dbconn, "sql_seq", "ALTER SEQUENCE progecen_copy.liste_resp_projet_id_seq RESTART WITH 1;");
                $seq = pg_execute($dbconn, "sql_seq", array());
                
                $result = pg_prepare($dbconn, "sql", "INSERT INTO progecen_copy.liste_resp_projet (personne) VALUES ( $1 );");
                foreach($list_resp_projet as $personne) {
                    $result = pg_execute($dbconn, "sql", array($personne)) or die ('Connexion impossible :'. pg_last_error());
                    echo $personne;
                }
                pg_close($dbconn);
                
            } else {
                echo "LDAP bind failed...";
            }
        } else {
            echo "LDAP Conn...";
        }


?>