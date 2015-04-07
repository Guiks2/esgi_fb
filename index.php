<?php 
    session_start();

    require __DIR__ . "/facebook-php-sdk-v4-4.0-dev/autoload.php";
    echo('test');
    use Facebook\FacebookSession;
    use Facebook\FacebookRedirectLoginHelper;
    
    const APPID = "764343183684137";
    const APPSECRET = "56ec8f41e39c835873b223320ffdfcae";
    
    FacebookSession::setDefaultApplication(APPID, APPSECRET);
    
    $helper = new FacebookRedirectLoginHelper('http://localhost');
    $loginUrl = $helper -> getLoginUrl();
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
        $loginUrl = $helper -> getLoginUrl();
        echo '<a href="' . $loginUrl . '"> Se connecter </a>';
    ?>
  </body>
</html>