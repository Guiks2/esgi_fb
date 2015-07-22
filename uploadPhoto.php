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

if(isset($_SESSION['fb_token'])) {
    $session = new FacebookSession($_SESSION['fb_token']);
	if($_POST['album-id'] == -1){
        //$album_id = createAlbum($_POST['new_album_name'], $session, $id_user);
    } else{
        $album_id = $_POST['album-id'];
    }
    
    $curlFile = array('source' => new CURLFile($_FILES['browse-image']['tmp_name'], $_FILES['browse-image']['type']));

    try {
        $request_add = new FacebookRequest ($session, 'POST', '/'.$album_id.'/photos', $curlFile);
        $response_add = $request_add->execute();
        $done = json_decode($response_add->getRawResponse(), true);


        $request_get = new FacebookRequest ($session, 'GET', '/'.$done["id"]);
        $response_get = $request_get->execute();
        $photo = json_decode($response_get->getRawResponse(), true);

        include("connectDB.php");
        $photo_id = $photo['id'];
        $photo_from_id = $photo['from']['id'];
        $photo_from_name = $photo['from']['name'];
        $photo_url = $photo['source'];

        $sql = "INSERT INTO pictures VALUES('".$photo_id."', '".$photo_from_id."', '".$photo_from_name."', '".$photo_url."')";
        if (!($result = $mysqli->query($sql))) {
             echo "Echec de la préparation : (" . $mysqli->errno . ") " . $mysqli->error;
        }

        echo "<script>top.location.href='contest.php';</script>";
    } catch (FacebookApiException $e) {
        error_log($e);
    }
}