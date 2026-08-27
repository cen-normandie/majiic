<?php

// 1. Configuration des identifiants corrigée
$tokenUrl     = "https://sso.geopf.fr/realms/geoplateforme/protocol/openid-connect/token";
$username     = "bp@cen-normandie.fr"; // Votre email cartes.gouv.fr
$password     = "FDJ762Ksghsz&!!";   // Votre mot de passe associé
$clientId     = "gpf-warehouse";       // Le Client ID public valide pour l'IGN

// 2. Préparation des données pour le flux "password"
$postData = [
    'grant_type' => 'password',
    'client_id'  => $clientId,
    'username'   => $username,
    'password'   => $password,
    'scope'      => 'openid'
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
