<?php 
include '../php/properties.php';

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
$ad_id = "CSNHN\\".$AD_admin;
$ad_pwd = $AD_admin_pwd;
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
//Reference：http://php.net/manual/en/function.ldap-bind.php

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

            if ( isset( $result_salarie[$i]["mobile"] ) ) {
                $tel_string = $result_salarie[$i]["mobile"][0].' - ';
            } else {
                $tel_string = '';
            }
            if ( isset( $result_salarie[$i]["othertelephone"] ) ) {
                $tel_string = $tel_string.$result_salarie[$i]["othertelephone"][0];
            }
            $bu = $result_salarie[$i]["description"][0];


            print "<pre>";
            print_r($mail);
            print_r($name);
            print_r($intitule);
            print_r($tel_string);
            print_r($bu);
            print "</pre>";



        if ( strlen($intitule) > 120) {
            $police = '10';
        } else {
            $police = '11';
        };

        //////////////////////////////////////////////////////
        // IL FAUT CONVERTIR UTF-8 en ISO pour Windows server
        $myfile = fopen($root_f.DIRECTORY_SEPARATOR.'signatures'.DIRECTORY_SEPARATOR.'out'.DIRECTORY_SEPARATOR.$name.'.htm', "w") or die("Unable to open file!");

        $txt = "<meta http-equiv='Content-Type'  content='text/html charset=UTF-8' />
        <table border=0 cellspacing=0 cellpadding=0 width=600 style=''>
            <tr height=5>
                <td width=120 style='background:#CEDE90'>
                    <p style='line-height:5px;mso-line-height-rule: exactly;'><img border=0 width=3 height=3 src='../img/5_5.png'></p>
                </td>
                <td width=120 style='background:#B7C657'>
                    <p style='line-height:5px;mso-line-height-rule: exactly;'><img border=0 width=3 height=3 src='../img/5_5.png'></p>
                </td>
                <td width=120 style='background:#849F2C'>
                    <p style='line-height:5px;mso-line-height-rule: exactly;'><img border=0 width=3 height=3 src='../img/5_5.png'></p>
                </td>
                <td width=120 style='background:#4B6426'>
                    <p style='line-height:5px;mso-line-height-rule: exactly;'><img border=0 width=3 height=3 src='../img/5_5.png'></p>
                </td>
                <td width=120 style='background:#3A5121'>
                    <p style='line-height:5px;mso-line-height-rule: exactly;'><img border=0 width=3 height=3 src='../img/5_5.png'></p>
                </td>
            </tr>
            <tr>
                <td colspan=1 width=80 height=0 style='background:#4B6426;'>
                    <p style='margin-top:20px;margin-bottom:20px;'>
                        <p  align=center style='text-align:center;line-height:18px;margin-bottom:0px;margin-top:0px;margin-left:4px;'>
                            <a href='https://www.facebook.com/CENNormandie/' target='blank_'><img border=0 width=16 height=16 src='../img/fk.png'></a>&nbsp; 
                            <a href='https://www.instagram.com/cennormandie/' target='blank_'><img border=0 width=16 height=16 src='../img/im.png'></a>&nbsp; 
                            <a href='https://twitter.com/cen_normandie' target='blank_'><img border=0 width=16 height=16 src='../img/tr.png'></a>&nbsp; 
                            <a href='https://www.youtube.com/channel/UC0eh_NNl-WEjZhoIuUGRA3g' target='blank_'><img border=0 width=16 height=16 src='../img/ye.png'></a>&nbsp; 
                            <a href='https://www.youtube.com/channel/UC0eh_NNl-WEjZhoIuUGRA3g' target='blank_'><img border=0 width=16 height=16 src='../img/ye.png'></a>&nbsp; 
                        <p>
                        <p  align=center style='text-align:center;margin-bottom:0px;margin-top:0px;'>
                            <a href='https://cen-normandie.fr' target='blank_'><img border=0 width=80 height=8 src='../img/site_web.png'><a>
                        </p>
                    </p>
                </td>
                <td colspan=2 width=240 style='background:#4B6426;'>
                    <p  align=center style='text-align:center'>
                        <p style='font-family:Arial;font-size:14px;line-height:7px;color:#fff;margin-left:4px;margin-top:4px;margin-bottom:4px;'>".replace_char_html_($name)."</p>
                        <p style='font-family:Arial;font-size:".$police."px;line-height:7px;color:#fff;margin-left:4px;margin-top:6px;margin-bottom:4px;'>".replace_char_html_($intitule)."</p>
                        <p style='font-family:Arial;font-size:11px;line-height:7px;color:#fff;margin-left:4px;margin-top:6px;margin-bottom:4px;'>".$tel_string."</p>
                    </p>
                </td>
                <td colspan=2  style='background:white;border-right-style:solid;border-right-color:#3A5121;border-right-width:1px;'>
                    <img border=0 width=240 height=80 src='../img/logo.png'>
                </td>
            </tr>
        </table border=0 cellspacing=0 cellpadding=0 width=600 style=''>
            <tr>
                <td colspan=5>
                    <img border=0 src='../img/actu.jpg'>
                </td>
            </tr>
        </table>
        ";



        //fwrite(preg_replace('/[^A-Za-z0-9\-]/', '',$myfile), $txt);
        fwrite($myfile, $txt);
        fclose($myfile); 
        }
    } else {
        echo "LDAP bind failed...";
    }
}

?>
