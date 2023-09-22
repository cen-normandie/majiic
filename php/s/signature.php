<?php
include '../properties.php';
$arr = array();

$name = '';
$titre = '';
$cell = '';
$bureau = '';
$fixe = '';



//connexion a la BD
$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS") or die ('Connexion impossible :'. pg_last_error());

$sql = "SELECT email, nom_signature, intitule_signature, coalesce(mobile, '' ), bureau, coalesce(fixe, '-' ) FROM users.personnes where poste='y';";

function replace_char_html_($str) {
    $init = array("é", "è", "à","ç", "É","ï");
    $outt   = array("&eacute;","&egrave;","&agrave;","&ccedil;","&Eacute;","&iuml;");
    return str_replace($init, $outt, $str);
}


//execute la requete dans le moteur de base de donnees  
$query_result = pg_exec($dbconn,$sql) or die (pgErrorMessage());
while($row = pg_fetch_row($query_result))
{
$mail = $row[0];
$name = $row[1];
$intitule = $row[2];
$mobile = $row[3];
$bu = $row[4];
$fixe = $row[5];
$telephones = '';

$int  = strlen($intitule );
$intitule = str_replace(" - ", "<br>", $intitule);
echo '<br>|'.$fixe.'| : '.$name;

//é -> &eacute;
//è -> &egrave;
//à -> &agrave;
//ç -> &ccedil;
//É -> &Eacute;



if ($bu == 'NO') {
    if ($fixe == '-') {
        $telephones = '02 31 53 01 05';
    } else {
        $telephones = $fixe;
    }
} else {
    //CENNS
    if ($fixe == '-') {
        $telephones = '02 35 65 47 10';
    } else {
        $telephones = $fixe;
    }
    
};

$telephones = $telephones.' - ';

if ($mobile!= '-') {
    $telephones = $telephones.$mobile;
} else {
    $telephones = str_replace(" - ","",$telephones);
}


if ($int > 120) {
    $police = '10';
} else {
    $police = '11';
};

echo $name;

//////////////////////////////////////////////////////
// IL FAUT CONVERTIR UTF-8 en ISO pour Windows server
//$myfile = fopen('/signatures/'.iconv('UTF-8', 'ISO-8859-15', $name).".htm", "w") or die("Unable to open file!");
$myfile = fopen('./signatures/'.$name.'.htm', "w") or die("Unable to open file!");

$txt = "<meta http-equiv='Content-Type'  content='text/html charset=UTF-8' />
<table border=0 cellspacing=0 cellpadding=0 width=600 style=''>
    <tr height=5>
        <td width=120 style='background:#CEDE90'>
            <p style='line-height:5px;mso-line-height-rule: exactly;'><img border=0 width=3 height=3 src='http://cen-normandie.com/doc_images/5_5.png'></p>
        </td>
        <td width=120 style='background:#B7C657'>
            <p style='line-height:5px;mso-line-height-rule: exactly;'><img border=0 width=3 height=3 src='http://cen-normandie.com/doc_images/5_5.png'></p>
        </td>
        <td width=120 style='background:#849F2C'>
            <p style='line-height:5px;mso-line-height-rule: exactly;'><img border=0 width=3 height=3 src='http://cen-normandie.com/doc_images/5_5.png'></p>
        </td>
        <td width=120 style='background:#4B6426'>
            <p style='line-height:5px;mso-line-height-rule: exactly;'><img border=0 width=3 height=3 src='http://cen-normandie.com/doc_images/5_5.png'></p>
        </td>
        <td width=120 style='background:#3A5121'>
            <p style='line-height:5px;mso-line-height-rule: exactly;'><img border=0 width=3 height=3 src='http://cen-normandie.com/doc_images/5_5.png'></p>
        </td>
    </tr>
    <tr>
        <td colspan=1 width=80 height=0 style='background:#4B6426;'>
            <p style='margin-top:20px;margin-bottom:20px;'>
                <p  align=center style='text-align:center;line-height:14px;margin-bottom:0px;margin-top:0px;margin-left:4px;'>
                    <a href='https://www.facebook.com/CENNormandie/' target='blank_'><img border=0 width=14 height=14 src='http://cen-normandie.com/doc_images/complements/fk.png'></a>&nbsp; 
                    <a href='https://www.instagram.com/cennormandie/' target='blank_'><img border=0 width=14 height=14 src='http://cen-normandie.com/doc_images/complements/im.png'></a>&nbsp; 
                    <a href='https://twitter.com/cen_normandie' target='blank_'><img border=0 width=14 height=14 src='http://cen-normandie.com/doc_images/complements/tr.png'></a>&nbsp; 
                    <a href='https://www.youtube.com/channel/UC0eh_NNl-WEjZhoIuUGRA3g' target='blank_'><img border=0 width=14 height=14 src='http://cen-normandie.com/doc_images/complements/ye.png'></a>&nbsp; 
                <p>
                <p  align=center style='text-align:center;margin-bottom:0px;margin-top:0px;'>
                    <a href='https://cen-normandie.fr' target='blank_'><img border=0 width=80 height=8 src='http://cen-normandie.com/doc_images/complements/site_web.png'><a>
                </p>
            </p>
        </td>
        <td colspan=2 width=240 style='background:#4B6426;'>
            <p  align=center style='text-align:center'>
                <p style='font-family:Arial;font-size:14px;line-height:7px;color:#fff;margin-left:4px;margin-top:4px;margin-bottom:4px;'>".replace_char_html_($name)."</p>
                <p style='font-family:Arial;font-size:".$police."px;line-height:7px;color:#fff;margin-left:4px;margin-top:6px;margin-bottom:4px;'>".replace_char_html_($intitule)."</p>
                <p style='font-family:Arial;font-size:11px;line-height:7px;color:#fff;margin-left:4px;margin-top:6px;margin-bottom:4px;'>".$telephones."</p>
            </p>
        </td>
        <td colspan=2  style='background:white;border-right-style:solid;border-right-color:#3A5121;border-right-width:1px;'>
            <img border=0 width=240 height=80 src='http://cen-normandie.com/doc_images/complements/logo.png'>
        </td>
    </tr>
</table border=0 cellspacing=0 cellpadding=0 width=600 style=''>
    <tr>
        <td colspan=5>
            <img border=0 src='http://cen-normandie.com/doc_images/actu.jpg'>
        </td>
    </tr>
</table>
";



//fwrite(preg_replace('/[^A-Za-z0-9\-]/', '',$myfile), $txt);
fwrite($myfile, $txt);
fclose($myfile);

  }
  
  
//ferme la connexion a la BD
pg_close($dbconn);

//echo "];";

?>
