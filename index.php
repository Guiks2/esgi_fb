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

        
function uploadPhoto($session, $id_user){
    if($_POST['album_id'] == -1){
        $album_id = createAlbum($_POST['new_album_name'], $session, $id_user);
    } else{
        $album_id = $_POST['album_id'];
    }
    
    $curlFile = array('source' => new CURLFile($_FILES['photo']['tmp_name'], $_FILES['photo']['type']));
    try {
        $up = new FacebookRequest ($session, 'POST', '/'.$album_id.'/photos', $curlFile);
        $up->execute()->getGraphObject("Facebook\GraphUser");
    } catch (FacebookApiException $e) {
        error_log($e);
    }
}

function createAlbum($name, $session, $id){
    $albums = getAlbums($session, $id);
    if ($albums) {
        for ($i = 0; null !== $albums->getProperty('data')->getProperty($i); $i++) {
            if ($albums->getProperty('data')->getProperty($i)->getProperty('name') == $name) {
                $album_id = $albums->getProperty('data')->getProperty($i)->getProperty('id');
                break;
            } else {
                $album_id = 'blank';
            }
        }
    }
    
    // if the album is not present, create the album
    if ($album_id == 'blank') {
        $album_data = array('name' => $_POST['new_album_name'], 'message' => $album_description, );
    
        $new_album = new FacebookRequest ($session, 'POST', '/'.$id.'/albums', $album_data);
        $new_album = $new_album->execute()->getGraphObject("Facebook\GraphUser");
        $album_id = $new_album->getProperty('id');
    }
    
    return $album_id;
}

function getAlbums($session, $id){
    $request = new FacebookRequest($session, 'GET', '/' . $id . '/albums');
    $response = $request->execute();
    $albums = $response->getGraphObject();
    
    return $albums;
}

// Si $album_id est null, affiche les photos de tous les albums
function getPhotos($session, $id_user, $album_id) {
    
    $albums = getAlbums($session, $id_user);
    for ($i = 0; null !== $albums->getProperty('data')->getProperty($i); $i++) {
        $album = $albums->getProperty('data')->getProperty($i);
        $request = new FacebookRequest($session, 'GET', '/'.$album->getProperty('id').'/photos');
        $response = $request->execute();
        $photos = $response->getGraphObject();

        for ($j = 0; null !== $photos->getProperty('data')->getProperty($j); $j++) {
            if($album_id == null || $album_id == $album->getProperty('id')){
                $photo[] = $photos->getProperty('data')->getProperty($j);
            }
        }
    }
    return $photo;
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
    </script><!--
      <script> 
      		function showAlbum(album_id)
             {
              //until it loads the photos show a loading gif
              document.getElementById("txtHint").innerHTML = "<br><img src='images/ajax-loader.gif' /><br/><br/>Loading photos...";
        
        
            //here is the IE fix
             $.support.cors = true;
        
            // get images - the addition of the callback parameter is the 2nd IE fix
            $.getJSON('https://graph.facebook.com/' + album_id + '/photos?access_token=<?=$access_token?>
        		&
        		callback=?', function(json, status, xhr) {
        		var imgs = json.data;
        
        		var images='';
        		for (var i = 0; i < imgs.length; i++) {
        		//each image has a variety of different dimensions
        		//i am selecting the first dimension i.e. [0] and set my own width
        		images +='<br /><img src="' + imgs[i]['images'][0]['source'] + '" width=320><br><br>';
        		}
        		//append all the photos found for this album id inside the div
        		document.getElementById("txtHint").innerHTML = images;
        
        		}).error(function(jqXHR, textStatus, errorThrown) { alert(errorThrown); });
        
    		} 
</script> -->
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