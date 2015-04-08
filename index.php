<?php 
    error_reporting(E_ALL);
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
    
    $helper = new FacebookRedirectLoginHelper('https://esgi-fb.herokuapp.com/');
    
    //SI les variables de sessions existent et que $_SESSION['fb_token'] existe
    // alors je veux créer mon utilisateur à partir de cette session
    if( isset($_SESSION) && isset($_SESSION['fb_token']) ){
        $session = new FacebookSession($_SESSION['fb_token']);
    }
    //Sinon j'affiche le lien de connection
    else {
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
		};
		( function(d, s, id) {
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
        if($session){
            $_SESSION['fb_token'] = (string) $session->getAccessToken();
            $request_user = new FacebookRequest( $session,"GET","/me");
            $request_user_executed = $request_user->execute();
            $user = $request_user_executed->getGraphObject(GraphUser::className());
            echo "Bonjour ".$user->getName();
        }else{
            $loginUrl = $helper->getLoginUrl();
            echo "<a href='".$loginUrl."'>Se connecter</a>";
        }
    ?>
  </body>
</html>