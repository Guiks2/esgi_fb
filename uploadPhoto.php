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
        echo "Cas 1";
    } else{
        $album_id = $_POST['album-id'];
        echo "Cas 2";
    }
    
    $curlFile = array('source' => new CURLFile($_FILES['browse-image']['tmp_name'], $_FILES['browse-image']['type']));

    try {
        $request_add = new FacebookRequest ($session, 'POST', '/'.$album_id.'/photos', $curlFile);
        $response_add = $request_add->execute();
        $done = json_decode($response_add->getRawResponse(), true);

        print_r($done);
        
        $request_get = new FacebookRequest ($session, 'GET', '/'.$done[0]);
        $response_get = $request_get->execute();
        $photo = json_decode($response_get->getRawResponse(), true);

        print_r($photo);

        //echo "<script>top.location.href='contest.php;</script>";
    } catch (FacebookApiException $e) {
        error_log($e);
    }
}