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
	if($_POST['album-id'] == -1){
        //$album_id = createAlbum($_POST['new_album_name'], $session, $id_user);
    } else{
        $album_id = $_POST['album-id'];
    }
    
    $curlFile = array('source' => new CURLFile($_FILES['browse-image']['tmp_name'], $_FILES['browse-image']['type']));

    try {
        $request = new FacebookRequest ($session, 'POST', '/'.$album_id.'/photos', $curlFile);
        $response = $request->execute();
        $done = json_decode($response->getRawResponse(), true);

        //echo "<script>top.location.href='contest.php;</script>";
    } catch (FacebookApiException $e) {
        error_log($e);
    }
}