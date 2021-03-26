<?php

/* Iniciando la sesión*/
session_start();

/* Cambiar según la ubicación de tu directorio*/
require_once __DIR__ . '/connections/autoload.php';

$fb = new Facebook\Facebook([
  'app_id' => '1401397840112695',
  'app_secret' => 'ece48ed0bfc19a11aa9b3e3a4af976b2',
  'default_graph_version' => 'v2.9',
]); 
  
$helper = $fb->getRedirectLoginHelper();  
  
try {  
  $accessToken = $helper->getAccessToken();  
} catch(Facebook\Exceptions\FacebookResponseException $e) {  
  // Cuando Graph devuelve un error 
  echo 'Graph returned an error: ' . $e->getMessage();  
  exit;  
} catch(Facebook\Exceptions\FacebookSDKException $e) {  
  // Cuando la validación falla  
  echo 'Facebook SDK returned an error: ' . $e->getMessage();  
  exit;  
}  

if (! isset($accessToken)) {  
  if ($helper->getError()) {  
    header('HTTP/1.0 401 Unauthorized');  
    echo "Error: " . $helper->getError() . "\n";
    echo "Error Code: " . $helper->getErrorCode() . "\n";
    echo "Error Reason: " . $helper->getErrorReason() . "\n";
    echo "Error Description: " . $helper->getErrorDescription() . "\n";
  } else {  
    header('HTTP/1.0 400 Bad Request');  
    echo 'Bad request';  
  }  
  exit;  
}  

// Logged in  
echo '<h3>Access Token</h3>';  
var_dump($accessToken->getValue());  
  
// El controlador de cliente de OAuth 2.0 nos ayuda a gestionar los tokens de acceso 
$oAuth2Client = $fb->getOAuth2Client();  
  
$tokenMetadata = $oAuth2Client->debugToken($accessToken);  
echo '<h3>Metadata</h3>';  
var_dump($tokenMetadata);  
  
// Validación (esto lanzará FacebookSDKException cuando falla )  
// $tokenMetadata->validateAppId($config['app_id']);  
// Si se conoce el ID de usuario de este token de acceso, puede validarlo aquí  
// $tokenMetadata->validateUserId('123');  
$tokenMetadata->validateExpiration();   
   
if (! $accessToken->isLongLived()) {  
  // Cambiando un token de acceso de corta duración por uno de larga vida 
  try {  
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);  
  } catch (Facebook\Exceptions\FacebookSDKException $e) {  
    echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>";  
    exit;  
  } 
  echo '<h3>Long-lived</h3>';  
  var_dump($accessToken->getValue());  
}

$_SESSION['fb_access_token'] = (string) $accessToken;  
  
?>