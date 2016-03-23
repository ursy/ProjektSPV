<?php
if(array_key_exists('logout',$_GET))
{
    session_start();
    unset($_SESSION['provider']);
    unset($_SESSION['user_id']);
    unset($_SESSION['email']); //Google session data unset
    session_destroy();
    header("Location:mainPage.php");
}
?>
