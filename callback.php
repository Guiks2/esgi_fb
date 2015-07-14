<?php

session_start();

require __DIR__ . "/facebook-php-sdk-v4-4.0-dev/autoload.php";

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookCanvasLoginHelper;

const APPID = "764343183684137";
const APPSECRET = "56ec8f41e39c835873b223320ffdfcae";

FacebookSession::setDefaultApplication(APPID, APPSECRET);

$helper = new FacebookRedirectLoginHelper('https://esgi-fb.herokuapp.com/callback.php');

// Now you have the session
$session = $helper->getSessionFromRedirect();
$_SESSION['fb_token'] = $session;

if($session) {
	header("Location: https://esgi-fb.herokuapp.com/contest.php");
} else {
	header("Location: https://esgi-fb.herokuapp.com");
}