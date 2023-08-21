<?PHP
$emetteur = 'test@cenwms.xyz';
$destinataire = 'b.perceval@superceval.fr';

$sujet = "Test de la fonction PHP mail";
$message = "Ceci est un message de test";
$headers = 'From:' . $emetteur;

if (mail($destinataire, $sujet, $message, $headers))
{
    echo "Message envoyé avec succès !";
}
else
{
    echo "Erreur. Le message ne peut pas être envoyé.";
}
?>