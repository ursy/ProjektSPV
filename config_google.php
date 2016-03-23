<?php
session_start();
include_once("src/Google_Client.php");
include_once("src/contrib/Google_Oauth2Service.php");
######### edit details ##########
$clientId = '925596038179-lmqm46eki9l1amesu3cpnhosm8fn5gop.apps.googleusercontent.com'; //Google CLIENT ID
$clientSecret = '_7VG1Q8ZpcLH8T3P54x_gsAJ'; //Google CLIENT SECRET
$redirectUrl = 'http://znamkarija.hotbit.eu:88';  //return url (url to script)
$homeUrl = 'http://znamkarija.hotbit.eu:88';  //return to home

##################################

$gClient = new Google_Client();
$gClient->setApplicationName('Login to codexworld.com');
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($redirectUrl);

$google_oauthV2 = new Google_Oauth2Service($gClient);
?>
