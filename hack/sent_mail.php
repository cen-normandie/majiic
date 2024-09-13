<?php
session_start();


//$mail = str_replace("'","''",$_POST['courriel']); // Déclaration de l'adresse de destination.
//
$email = str_replace("'","''",$_POST["email"]); // Déclaration de l'adresse de destination.
$entreprise = str_replace("'","''",$_POST["entreprise"]);
$name = str_replace("'","''",$_POST["name"]);
$msg_obj = str_replace("'","''",$_POST["msg"]);


if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
{
	$passage_ligne = "\r\n";
}
else
{
	$passage_ligne = "\n";
}
//=====Déclaration des messages au format texte et au format HTML.
$message_html = "<html>";
$message_html .= "<head>";
$message_html .= "<meta charset='utf-8' />"; 
$message_html .= "<head></head>";
$message_html .= "<body>";
$message_html .= "<table style=\"width: 100%;\">";
$message_html .= "<tr style=\"\">";
$message_html .= "<td style=\"text-align: center;background-color:#343a40;\">";
$message_html .= "<span style=\"color:#d14f14;font-size:20px;font-weight:600;font-family: Calibri;\" >Su</span><span style=\"color:#f39200;font-size:18px;font-weight:700;font-family: Calibri;\">perceval</span>&nbsp;|&nbsp;<span style=\"color:#ffffff;font-size:16px;font-weight:600;font-family: Calibri;\">Géomatique et SIG</span>";
//$message_html .= "<img src=\"http://superceval.com/img/northa_100_w.png\" alt=\"Superceval\"  style=\"display: inline-block; max-width: 24px;max-height:24px;\" />";
$message_html .= "</td>";
$message_html .= "</tr>";
$message_html .= "<tr>";
$message_html .= "<td style=\"\">";
$message_html .= "<div style=\"\" ><p style=\"color:#343a40;font-size:16px;font-weight:600;font-family: Calibri, Helvetica, Arial, sans-serif;\"></p>";
$message_html .= "<p style=\"color:#343a40;font-size:16px;font-weight:600;font-family: Calibri, Helvetica, Arial, sans-serif;\">Message laissé sur Superceval.fr :</p>";
$message_html .= "<p><span style=\"color:#343a40;font-size:16px;font-weight:600;font-family: Calibri, Helvetica, Arial, sans-serif;\">E-mail : </span>".$email."</p>";
$message_html .= "<p><span style=\"color:#343a40;font-size:16px;font-weight:600;font-family: Calibri, Helvetica, Arial, sans-serif;\">Entreprise : </span>".$entreprise."</p>";
$message_html .= "<p><span style=\"color:#343a40;font-size:16px;font-weight:600;font-family: Calibri, Helvetica, Arial, sans-serif;\">Nom : </span>".$name."</p>";
$message_html .= "<p><span style=\"color:#343a40;font-size:16px;font-weight:600;font-family: Calibri, Helvetica, Arial, sans-serif;\">Message : </span>".$msg_obj."</p>";
$message_html .= "<p><span style=\"color:#343a40;font-size:16px;font-weight:600;font-family: Calibri, Helvetica, Arial, sans-serif;\">Merci pour le message, je reviens vers vous dans les plus brefs délais et selon mes disponibilités.</span>";
$message_html .= "<span style=\"\"></span></p>";
$message_html .= "<p style=\"color:#343a40;font-size:16px;font-weight:600;font-family: Calibri, Helvetica, Arial, sans-serif;\">A bientôt !</p></div>";
$message_html .= "</td>";
$message_html .= "</tr>";
$message_html .= "<tr>";
$message_html .= "<td style=\"\">&nbsp;</td>";
$message_html .= "</tr>";
$message_html .= "<tr>";
$message_html .= "<td style=\"\">";
$message_html .= "<div style=\"background-color:#343a40;\" ><p style=\"color:#fff;font-size:18px;font-weight:600;\">&nbsp;</p></div>";
$message_html .= "</td>";
$message_html .= "</tr>";
$message_html .= "</table>";
$message_html .= "</br></br><div style=\"font-size:12px;\" ><p>Si vous n'êtes pas à l'origine de ce message veuillez ignorer cet e-mail.</p>";
$message_html .= "</body>";
$message_html .= "</html>";



//==========
 
//=====Création de la boundary
$boundary = "-----=".md5(rand());
//==========
 
//=====Définition du sujet.
$sujet = "Superceval - GIS freelancer";
//=========
 
//=====Création du header de l'e-mail.
$header = "From: \"Superceval\"<b.perceval@superceval.fr>".$passage_ligne;
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
//mail($email,$sujet,$message,$header);
mail('b.perceval@superceval.fr',$sujet,$message,$header);
mail($email,$sujet,$message,$header);
//==========

echo "send";
?>
