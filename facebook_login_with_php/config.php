<?php
include_once("inc/facebook.php"); //include facebook SDK
######### Facebook API Configuration ##########
$appId = '1052811364791618'; //Facebook App ID
$appSecret = '905d33ed68af1901f92e3cc4a2fdbc80'; // Facebook App Secret
$homeurl = 'http://znamkarija.hotbit.eu:88/mainPage.php#profile';  //return to home
$fbPermissions = 'email';  //Required facebook permissions

//Call Facebook API
$facebook = new Facebook(array(
  'appId'  => $appId,
  'secret' => $appSecret

));
$fbuser = $facebook->getUser();
?>
