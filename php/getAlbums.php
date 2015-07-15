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

error_reporting(E_ALL);
ini_set("display_errors", 1); 
if(isset($_SESSION['fb_token'])) {
    $session = new FacebookSession($_SESSION['fb_token']);
    $request = new FacebookRequest($session, 'GET', '/me/albums');
    $response = $request->execute();
    $photos = json_decode($response->getRawResponse(), true);

    return json_encode($photos["data"]);
}