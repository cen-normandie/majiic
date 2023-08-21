<?php
session_start();
include 'properties.php';
$_POST['courriel'] = 'b.perceval@cen-normandie.fr';

$mail = str_replace("'","''",$_POST['courriel']); // Déclaration de l'adresse de destination.

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());


function random_password( $length = 12 ) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789?!#_";
    $password = substr( str_shuffle( $chars ), 0, $length );
    return $password;
}

$pwd_new = random_password();

$result = pg_prepare($dbconn, "sql1", "UPDATE $users SET u_pwd = md5($1) WHERE u_courriel = $2 RETURNING *");
$out = pg_execute($dbconn, "sql1", array($pwd_new,$mail));

$line = pg_affected_rows($out);
pg_close($dbconn);

if( $line == 1) {


if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
{
	$passage_ligne = "\r\n";
}
else
{
	$passage_ligne = "\n";
}
//=====Déclaration des messages au format texte et au format HTML.
//$message_txt = "Salut à tous, voici un e-mail envoyé par un script PHP.";
$message_html = "<html>";
$message_html .= "<head>";
$message_html .= "<meta charset='utf-8' />"; 
$message_html .= "<head></head>";
$message_html .= "<body>";
$message_html .= "<table style=\"width: 100%;\">";
$message_html .= "<tr style=\"\">";
$message_html .= "<td style=\"text-align: center;background-color:#004fa2;\">";
$message_html .= "<img src=\"http://cen-normandie.com/majiic/img/normandie_qgis_small.jpg\" alt=\"CEN Normandie\"  style=\"display: inline-block; width: 120px;height: 44px;\" />";
$message_html .= "</td>";
$message_html .= "</tr>";
$message_html .= "<tr>";
$message_html .= "<td style=\"\">";
$message_html .= "<div style=\"\" ><p style=\"color:#004fa2;font-size:20px;font-weight:600;font-family: Calibri, Helvetica, Arial, sans-serif;\">Bonjour</p>";
$message_html .= "<p style=\"color:#004fa2;font-size:18px;font-weight:600;font-family: Calibri, Helvetica, Arial, sans-serif;\">Réinitialisation du mot de passe VisuDGFIP</p>";

$message_html .= "<p><span style=\"color:#004fa2;font-size:16px;font-weight:600;font-family: Calibri, Helvetica, Arial, sans-serif;\">Pour le compte :&nbsp;</span>";
$message_html .= "<span style=\"font-family: Calibri, Helvetica, Arial, sans-serif;\">".$mail."</span></p>";
$message_html .= "<p><span style=\"color:#004fa2;font-size:16px;font-weight:600;font-family: Calibri, Helvetica, Arial, sans-serif;\">Le mot de passe associé est le suivant :&nbsp;</span>";
$message_html .= "<span style=\"font-family: Calibri, Helvetica, Arial, sans-serif;\">".$pwd_new."</span></p>";

$message_html .= "<p><span style=\"color:#004fa2;font-size:16px;font-weight:600;font-family: Calibri, Helvetica, Arial, sans-serif;\">Vous pouvez dès à présent vous connecter à l'aide du nouveau mot de passe.</span>";
$message_html .= "<span style=\"\"></span></p>";

$message_html .= "<p style=\"color:#004fa2;font-size:16px;font-weight:600;font-family: Calibri, Helvetica, Arial, sans-serif;\">L'équipe du CEN Normandie</p></div>";
$message_html .= "</td>";
$message_html .= "</tr>";
$message_html .= "<tr>";
$message_html .= "<td style=\"\">&nbsp;</td>";
$message_html .= "</tr>";
$message_html .= "<tr>";
$message_html .= "<td style=\"\">";
$message_html .= "<div style=\"background-color:#004fa2;\" ><p style=\"color:#fff;font-size:18px;font-weight:600;\">&nbsp;</p></div>";
$message_html .= "</td>";
$message_html .= "</tr>";
$message_html .= "</table>";
$message_html .= "</br></br><div style=\"font-size:12px;\" ><p>Si vous n'êtes pas à l'origine de la création de ce compte veuillez ignorer cet e-mail.</p>";
$message_html .= "</br><p>Pour tout problème veuillez envoyer un mail à l'adresse suivante contact@cen-normandie.fr</p></div>";
$message_html .= "</body>";
$message_html .= "</html>";



//==========
 
 
//=====Définition du sujet.
$sujet = "Modification mot de passe Majiic CEN Normandie";
//=========
 
//=====Création du header de l'e-mail.
$headers = "MIME-Version: 1.0\n";
$headers .= "Content-type: text/html; charset=utf-8\n";
$headers .= 'From:' . 'contact@cen-normandie.xyz';
//==========
 
//==========
 
//=====Envoi de l'e-mail.
if (mail($mail,$sujet,$message_html,$headers)) {
    echo "send";
} else {
    echo "error envoi mail";
}
//==========


} else 
{
echo "error acces db";
}
?>
