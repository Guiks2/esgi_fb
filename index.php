<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link href="css/main-style.css" rel="stylesheet" type="text/css">
<script src="js/fittext.js"></script>
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
<title>Concours photo Swag Pizza</title>
</head>

<?php error_reporting(E_ALL);
ini_set("display_errors", 1);
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

// Différenciation connexion site et canvas
if(!empty($_SERVER['HTTP_ORIGIN'])){
    $helper = new FacebookCanvasLoginHelper();
    $session = $helper->getSession();
} else {
    $helper = new FacebookRedirectLoginHelper('https://esgi-fb.herokuapp.com/');
    /*
     * Création de l'utilisateur à partir de la session ou affichage du lien de connexion
     */
    if (isset($_SESSION) && isset($_SESSION['fb_token'])) {
        $session = new FacebookSession($_SESSION['fb_token']);
    } else {
        $session = $helper->getSessionFromRedirect();
    }
}
//print_r($_SERVER);
//print_r('-------------------------------');
//print_r($_SERVER['HTTP_ORIGIN']);

if ($session) {
    $_SESSION['fb_token'] = (string)$session->getAccessToken();
} else {
    // Possibilité d'ajouter des paramètres dans getLoginUrl pour avoir les permissions
    $params = array(    'scope' => 'read_stream,publish_actions,user_photos,user_status'  );
    if(!empty($_SERVER['HTTP_ORIGIN'])){
        $params['canvas'] = 1;
    }
    $loginUrl = $helper->getLoginUrl($params);
    //echo "<script type='text/javascript'>top.location.href = '".$loginUrl."';</script>";
}
?>
<body>
	<div class="container-parent">
    	<div id="container-child-1" class="container">
       		<div id="header">
        		<img src="img/swagpizza.png" width="180" height="160"/>
            
            	<p id="accroche">
            		CONCOURS PHOTO SWAG PIZZA
            	</p>
            </div>

            <div id="next">
            	<p id="description-concours">
                	Participez en postant une photo de votre plus belle pizza, la plus apétissante ou la plus originale et tentez de gagner votre pizza hebdomadaire pendant 3 mois* !
            	</p>
            </div>
        </div>
        
        <div id="container-child-2" class="container">
        	<a href="<?php echo $loginUrl; ?>" class="red-button">JE PARTICIPE !</a>
        </div>

        <hr>

         <div id="container-child-3" class="container">
            <p id="description-vote">
                 Classement actuel
            </p>
            <div id="photos-candidats">
                <div class="photo"></div>
                <div class="photo"></div>
                <div class="photo"></div>
                <br/>
                <div class="photo"></div>
                <div class="photo"></div>
                <div class="photo"></div>
                <br/>
                <div class="photo"></div>
                <div class="photo"></div>
                <div class="photo"></div>
            </div>
        </div>
    </div>
    <center>* En participant à ce concours, je certifie à l'équipe Swag Pizza d'avoir au moins 18 ans ou d'avoir un accord parental. Offre valable dans la limite de la bonne volonté de l'équipe Swag Pizza.</center>

    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
