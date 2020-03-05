<?php
session_start();
include 'properties.php';


$mail = str_replace("'","''",$_POST['courriel']); // Déclaration de l'adresse de destination.

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());

$text = htmlentities($_POST['cgu_content'], ENT_QUOTES);

$sql = "INSERT INTO $users (
            u_courriel, u_id_session, u_pwd,u_cgu, u_cgu_content)
VALUES ('".pg_escape_string($mail) ."', '".pg_escape_string($mail) ."', md5('".str_replace("'","''",pg_escape_string($_POST["dwp"]))."'), '".pg_escape_string($_POST['cgu_'])."', '".$text."' )";

$out = pg_exec($dbconn,$sql);
$line = pg_affected_rows($out);
pg_close($dbconn);

if( $line == 1) {

//$mail = 'b.perceval@cen-bn.fr';
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
$message_html .= "<img src=\"http://cen-normandie.com/majiic/img/logo.png\" alt=\"logo_cenno\"  style=\"display: inline-block; width: 120px;height: 44px;\" />";
$message_html .= "</td>";
$message_html .= "</tr>";
$message_html .= "<tr>";
$message_html .= "<td style=\"\">";
$message_html .= "<div style=\"\" ><p style=\"color:#004fa2;font-size:20px;font-weight:600;font-family: Calibri, Helvetica, Arial, sans-serif;\">Bonjour</p>";
$message_html .= "<p style=\"color:#004fa2;font-size:18px;font-weight:600;font-family: Calibri, Helvetica, Arial, sans-serif;\">Vous recevez ce courriel suite à votre inscription sur le site CEN Normandie VisuDGFIP</p>";

$message_html .= "<p><span style=\"color:#004fa2;font-size:16px;font-weight:600;font-family: Calibri, Helvetica, Arial, sans-serif;\">L'inscription a été réalisée avec l'adresse :&nbsp;</span>";
$message_html .= "<span style=\"font-family: Calibri, Helvetica, Arial, sans-serif;\">".$mail."</span></p>";
$message_html .= "<p><span style=\"color:#004fa2;font-size:16px;font-weight:600;font-family: Calibri, Helvetica, Arial, sans-serif;\">Le mot de passe associé est le suivant :&nbsp;</span>";
$message_html .= "<span style=\"font-family: Calibri, Helvetica, Arial, sans-serif;\">".str_replace("'","''",$_POST["dwp"])."</span></p>";

$message_html .= "<p><span style=\"color:#004fa2;font-size:16px;font-weight:600;font-family: Calibri, Helvetica, Arial, sans-serif;\">Vous pouvez dès à présent vous connecter et utiliser l'application selon les CGU accéptées.</span>";
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
$message_html .= "</br><p>Pour tout problème veuillez envoyer un mail à l'adresse suivante contact@cen-bn.fr</p></div>";
$message_html .= "</body>";
$message_html .= "</html>";



//==========
 
//=====Création de la boundary
$boundary = "-----=".md5(rand());
//==========
 
//=====Définition du sujet.
$sujet = "Validation de votre inscription CEN Normandie VisuDGFip";
//=========
 
//=====Création du header de l'e-mail.
$header = "From: \"cen normandie\"<contact@cen-bn.fr>".$passage_ligne;
$header.= "MIME-Version: 1.0".$passage_ligne;
$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
//==========
 
//=====Création du message.
//$message = $passage_ligne."--".$boundary.$passage_ligne;
////=====Ajout du message au format texte.
//$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
//$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
//$message.= $passage_ligne.$message_txt.$passage_ligne;
//==========
$message.= $passage_ligne."--".$boundary.$passage_ligne;
//=====Ajout du message au format HTML
$message.= "Content-Type: text/html; charset=\"UTF-8\"".$passage_ligne;
$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
$message.= $passage_ligne.$message_html.$passage_ligne;
//==========
$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
//==========
 
//=====Envoi de l'e-mail.
mail($mail,$sujet,$message,$header);
//==========

echo "send";
} else 
{
echo "error";
}
?>
