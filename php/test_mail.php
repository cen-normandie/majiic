<?PHP
$emetteur = 'test@cenwms.xyz';
$destinataire = 'b.perceval@cen-normandie.fr';

$sujet = "Test de la fonction PHP mail";
$message = "Ceci est un message de test";

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
$message_html .= "<span style=\"font-family: Calibri, Helvetica, Arial, sans-serif;\">".$destinataire."</span></p>";
$message_html .= "<p><span style=\"color:#004fa2;font-size:16px;font-weight:600;font-family: Calibri, Helvetica, Arial, sans-serif;\">Le mot de passe associé est le suivant :&nbsp;</span>";
$message_html .= "<span style=\"font-family: Calibri, Helvetica, Arial, sans-serif;\">zzzzzzz</span></p>";

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




$headers = 'From:' . $emetteur;

if (mail($destinataire, $sujet, $message_html, $headers))
{
    echo "Message envoyé avec succès !";
}
else
{
    echo "Erreur. Le message ne peut pas être envoyé.";
}
?>