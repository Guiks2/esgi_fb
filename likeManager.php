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
$session = new FacebookSession($_SESSION['fb_token']);

$user_id_rq = new FacebookRequest($session, 'GET', '/me?fields=id');
$user_id_rp = $user_id_rq->execute();
$user_id = json_decode($user_id_rp->getRawResponse(), true);

echo "Cas A";
if(isset($_SESSION['fb_token'])) {
	include("connectDB.php");
	$photo_id = $_POST['id_pic'];
	$user_id = $user_id['id'];
	$liked = $_POST['liked'];
	echo "Cas 2";
	if($liked == "false") {
		$add_query = "INSERT INTO likes VALUES('".$photo_id."', '".$user_id."')";
		if (!($result = $mysqli->query($add_query))) {
			echo "Echec de la préparation : (" . $mysqli->errno . ") " . $mysqli->error;
		}
		echo "Cas 3";
	}
}