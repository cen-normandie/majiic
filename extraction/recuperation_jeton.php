<?php

// Étape 1 : Récupération du Token OAuth2
$tokenUrl = "https://sso.geopf.fr/realms/geoplateforme/protocol/openid-connect/token";
$postData = [
    'grant_type'    => 'client_credentials',
    'client_id'     => 'qgis',
    'client_secret' => '',
    'scope'         => 'openid'
];

$ch = curl_init($tokenUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
$response = curl_exec($ch);
$data = json_decode($response, true);

if (!isset($data['access_token'])) {
    die("Erreur d'authentification : " . $response);
}
$accessToken = $data['access_token'];


// Étape 2 : Appel du flux privé (WFS)
// REMPLACEZ l'URL ci-dessous par l'URL exacte copiée depuis votre espace "Mes clés d'accès"
// Attention : Conservez bien la structure complète mais assurez-vous d'enlever le paramètre "&apikey=undefined" s'il est présent
$wfsUrl = "https://data.geopf.fr/private/wfs/?service=WFS&version=2.0.0&request=GetCapabilities";

$apiCall = curl_init($wfsUrl);
curl_setopt($apiCall, CURLOPT_RETURNTRANSFER, true);

// Injection indispensable du jeton d'accès et liaison à vos droits
curl_setopt($apiCall, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer " . $accessToken,
    "Accept: application/xml"
]);

$wfsFeatures = curl_exec($apiCall);
curl_close($apiCall);

// Votre flux XML de données géographiques s'affiche correctement
header('Content-Type: application/xml');
echo $wfsFeatures;
