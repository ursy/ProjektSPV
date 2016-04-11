<?php
/*error_reporting(-1);
ini_set('display_errors', 'On');*/
session_start();
require_once './facebook-php-sdk-v4-5.0.0/src/Facebook/autoload.php';
require_once 'config.php';
require_once 'includes/functions.php';
# login-callback.php
$fb = new Facebook\Facebook([
    'app_id' => $appId,
    'app_secret' => $appSecret,
    'default_graph_version' => 'v2.5']);

$helper = $fb->getRedirectLoginHelper();
try {
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

if (isset($accessToken)) {
  // Logged in!
  $_SESSION['facebook_access_token'] = (string) $accessToken;

  // Now you can redirect to another page and use the
  // access token from $_SESSION['facebook_access_token']
  // Sets the default fallback access token so we don't have to pass it to each request
    $fb->setDefaultAccessToken((string) $accessToken);

    try {
      $response = $fb->get('/me?fields=id,first_name,last_name,email,gender,locale,picture');
      $user_profile = $response->getGraphUser();
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
      // When Graph returns an error
      echo 'Graph returned an error: ' . $e->getMessage();
      exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
      // When validation fails or other local issues
      echo 'Facebook SDK returned an error: ' . $e->getMessage();
      exit;
    }

    $user = new Users();
    $user_data = $user->checkUser('facebook',$user_profile['id'],$user_profile['first_name'],$user_profile['last_name'],$user_profile['email'],$user_profile['gender'],$user_profile['locale'],$user_profile['picture']['url']);
    //print_r($user_data);
    if(!empty($user_data)){
        $output = '<h1>Facebook Profile Details </h1>';
        $output .= '<img src="'.$user_data['picture'].'">';
        $output .= '<br/>Facebook ID : ' . $user_data['oauth_uid'];
        $output .= '<br/>Name : ' . $user_data['fname'].' '.$user_data['lname'];
        $output .= '<br/>Email : ' . $user_data['email'];
        $output .= '<br/>Gender : ' . $user_data['gender'];
        $output .= '<br/>Locale : ' . $user_data['locale'];
        $output .= '<br/>You are login with : Facebook';
        $output .= '<br/>Logout from <a href="logout.php?logout">Facebook</a>';
        $_SESSION['provider']="facebook";
        $_SESSION['user_id']=$user_data['id'];
        $_SESSION['email']=$user_data['email'];
        $_SESSION['admin']=$user_data['isAdmin'];
    }else{
        $output = '<h3 style="color:red">Some problem occurred, please try again.</h3>';
    }
    //print_r($output);
    header("Location: mainPage.php?page=profile");
}

