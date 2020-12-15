<?php
if (session_status() == PHP_SESSION_NONE) {
   session_start();
}
//Include Google client library 
include_once 'assets/src/Google_Client.php';
include_once 'assets/src/contrib/Google_Oauth2Service.php';

/*
 * Configuration and setup Google API
 */
$clientId = '583790614403-n8rstllbj6st59enh2dt1hv7dtm82rnb.apps.googleusercontent.com'; //Google client ID
$clientSecret = 'W1L0QDtPSHwIvkTCxQsP84GR'; //Google client secret
$redirectURL = 'http://localhost/algo/Gimnasio/index.php?controller=index&accion=logingoogle'; //Callback URL

//Call Google API
$gClient = new Google_Client();
$gClient->setApplicationName('Gimnasio pelotita');
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($redirectURL);

$google_oauthV2 = new Google_Oauth2Service($gClient);
