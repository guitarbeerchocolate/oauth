<?php
session_start();
require 'classes/autoload.php';
$oauth = new oauth;
if(isset($oauth->user))
{
  // echo 'The user e-mail is '.$oauth->user->email.'<br />';
  echo 'The user e-mail is '.$_SESSION['AUTH_USERNAME'].'<br />';
}

if(isset($oauth->authUrl))
{
    echo '<a class="login" href="'.$oauth->authUrl.'">Login with Google</a>';
}
else
{
   echo '<a class="logout" href="?logout">Logout</a>';
}
?>