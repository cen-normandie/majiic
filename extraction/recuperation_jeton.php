<?php

// Configuration des identifiants (à récupérer sur votre espace cartes.gouv.fr)
$tokenUrl    = "https://sso.geopf.fr/realms/geoplateforme/protocol/openid-connect/token";
$clientId    = "bp@cen-normandie.fr"; // Exemple par défaut : 'qgis' ou votre ID dédié
$clientSecret = "FDJ762Ksghsz&!!"; 

// Préparation des données de la requête
$postData = [
    'grant_type'    => 'client_credentials', // Dépend du type de clé configuré
    'client_id'     => $clientId,
    'client_secret' => $clientSecret,
    'scope'         => 'openid'
];

// Initialisation de cURL pour l'obtention du token
$ch = curl_init($tokenUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded'
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    die('Erreur cURL : ' . curl_error($ch));
}

curl_close($ch);

// Analyse de la réponse JSON
$data = json_decode($response, true);

if (!isset($data['access_token'])) {
    die("Impossible d'obtenir le token. Réponse du serveur : " . $response);
}

// Votre jeton est prêt
$accessToken = $data['access_token'];
echo "Token obtenu avec succès !\n";
