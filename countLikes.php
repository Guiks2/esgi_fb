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
    include("connectDB.php");
    $id_pic = $_POST['id_pic'];
    $sql = "SELECT * FROM likes WHERE id_pic = '".$id_pic."'";
    if (!($result = $mysqli->query($sql))) {
		echo "Echec de la préparation : (" . $mysqli->errno . ") " . $mysqli->error;
	}

	$res = json_encode($result->fetch_all());

	echo $res;
}