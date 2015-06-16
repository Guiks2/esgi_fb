<?php error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();

require __DIR__ . "/facebook-php-sdk-v4-4.0-dev/autoload.php";

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\GraphUser;

const APPID = "764343183684137";
const APPSECRET = "56ec8f41e39c835873b223320ffdfcae";

FacebookSession::setDefaultApplication(APPID, APPSECRET);
//$helper = new FacebookRedirectLoginHelper('https://esgi-fb.herokuapp.com/');
$helper = new FacebookRedirectLoginHelper('http://localhost/esgi_fb/');

/*
 * Création de l'utilisateur à partir de la session ou affichage du lien de connexion
 */
if (isset($_SESSION) && isset($_SESSION['fb_token'])) {
    $session = new FacebookSession($_SESSION['fb_token']);
} else {
    $session = $helper->getSessionFromRedirect();
}


?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset='UTF-8'>
    <title>ESGI FB</title>
    <meta name='Description' content='description page'>
    <script>
		window.fbAsyncInit = function() {
			FB.init({
				appId : '764343183684137',
				xfbml : true,
				version : 'v2.3'
			});
		}; ( function(d, s, id) {
				var js,
				    fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) {
					return;
				}
				js = d.createElement(s);
				js.id = id;
				js.src = "//connect.facebook.net/fr_FR/sdk.js";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
    </script>
  </head>
  <body>
    <div class="fb-like" data-share="true" data-width="450" data-show-faces="true"></div>

    <?php

    if ($session) {
        $_SESSION['fb_token'] = (string)$session->getAccessToken();
        $request = new FacebookRequest($session, "GET", "/me");
        $response = $request->execute();
        $user = $response->getGraphObject(GraphUser::className());        
        
        $albums = getAlbums($session, 'me');
        
        // Affichage de toutes les photos


        if($_POST['submit_upload_photo'] == '1'){
            uploadPhoto($session, 'me');
        }        
        if($_POST['show_photos'] == '1'){
            $listPhotos = getPhotos($session, 'me', $_POST['album_id']);
            foreach($listPhotos as $photo){
                echo "<img src='{$photo->getProperty("source")}' />", "<br />";
            }
        }

    } else {
        // Possibilité d'ajouter des paramètres dans getLoginUrl pour avoir les permissions
        $params = array('scope' => 'read_stream,publish_actions, user_photos, user_status,user_photos'#,publish_stream, offline_access', 'photo_upload'
        //redirect_uri => 'http://localhost/esgi_fb/'
        );
        $loginUrl = $helper->getLoginUrl($params);
        echo "<a href='" . $loginUrl . "'>Se connecter</a>";
    }
    ?>
    
    <form class="form-horizontal" enctype="multipart/form-data" method="POST" action="index.php">
      <input id="photo" name="photo" class="input-file" type="file">
      <select name="album_id" id="album_id">
          <?php
            for ($i = 0; null !== $albums->getProperty('data')->getProperty($i); $i++) {
                $album_id = $albums->getProperty('data')->getProperty($i)->getProperty('id');
                $album_name = $albums->getProperty('data')->getProperty($i)->getProperty('name');
                echo('<option value='.$album_id.'>'.$album_name.'</option>');
            }
          ?>
          <option value='-1'>Nouvel Album</option>
      </select>
      <input id="new_album_name" name="new_album_name" class="input-file" type="text">
      <button id="submit_upload_photo" name="submit_upload_photo" value="1" type="submit" class="btn btn-primary">Upload</button>
    </form>
    
    <form class="form-horizontal" enctype="multipart/form-data" method="POST" action="index.php">
      <select name="album_id" id="album_id">
          <?php
            for ($i = 0; null !== $albums->getProperty('data')->getProperty($i); $i++) {
                $album_id = $albums->getProperty('data')->getProperty($i)->getProperty('id');
                $album_name = $albums->getProperty('data')->getProperty($i)->getProperty('name');
                echo('<option value='.$album_id.'>'.$album_name.'</option>');
            }
          ?>
      </select>
      <button id="show_photos" name="show_photos" value="1" type="submit" class="btn btn-primary">Show</button>
    </form>
  </body>
</html>