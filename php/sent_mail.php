<?php
header("Access-Control-Allow-Origin: *");
session_start();
include 'properties.php';


$mail = str_replace("'","''",$_POST['courriel']); // Déclaration de l'adresse de destination.

$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS")
or die ('Connexion impossible :'. pg_last_error());

$text = htmlentities($_POST['cgu_content'], ENT_QUOTES);


$sql_select = pg_prepare($dbconn, "sql_select", "select u_courriel from $users where u_courriel = $1 ");
$return_select = pg_execute($dbconn, "sql_select",array( pg_escape_string($mail) ));
if (pg_affected_rows($return_select) == 0 ) {

		$sql = "INSERT INTO $users (
		u_courriel, u_id_session, u_pwd,u_cgu, u_cgu_content)
		VALUES ('".pg_escape_string($mail) ."', '".pg_escape_string($mail) ."', md5('".str_replace("'","''",pg_escape_string($_POST["dwp"]))."'), '".pg_escape_string($_POST['cgu_'])."', '".$text."' )";
		$out = pg_exec($dbconn,$sql);
		$line = pg_affected_rows($out);
		pg_close($dbconn);

		//ENVOI DU MAIL
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
		$message_html .= "<img src=\"http://cen-normandie.com/majiic/img/cen-normandie_qgis_small.jpg\" alt=\"logo_cenno\"  style=\"display: inline-block; width: 120px;height: 44px;\" />";
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
		
		//=====Définition du sujet.
		$sujet = "Validation de votre inscription CEN Normandie Majiic";
		//=========
		//=====Création du header de l'e-mail.
		$headers = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=utf-8\n";
		$headers .= 'From:' . 'contact@cen-normandie.xyz';
		
		//=====Envoi de l'e-mail.
		if (mail($mail,$sujet,$message_html,$headers)) {
    		echo "send";
		} else {
    		echo "error envoi mail";
		}
} else 
{
echo "error";
}
?>
