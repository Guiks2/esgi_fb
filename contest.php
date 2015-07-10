<?php 
    session_start();
    $response = $fb->get('/me?fields=id,name', $_SESSION['fb_token']);
    $user = $response->getGraphUser();
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link href="css/main-style.css" rel="stylesheet" type="text/css">
<title>Concours photo Swag Pizza</title>
<script src="js/jquery-2.1.4.min.js"></script>
</head>

<body>
	<div class="contest-container container-parent">
    	<div id="container-child-1" class="container">
       		<div id="header">
        		<img src="img/swagpizza.png" width="180" height="160"/>
            
            	<p id="accroche">
            		CONCOURS PHOTO SWAG PIZZA
            	</p>
            </div>
        </div>

        <div id="inter-informations">
            <?php echo 'Name: '.$user['name']; ?>

        </div>
        <div id="contest-container-child-2">
            <div id="ask-post" class="container">
            	<span class="span-ask-post">Je poste ma photo participante</span><br>
                <img id="arrow-participate" src="img/arrow.png"/>
            </div>

            <div id="global-browse-zone">
                <div id="hide-button"><input type="file" name="browse-image" id="browse-image" accept="image/*"></input></div>
                <div id="dynamic-upload-zone">
                    <span id="button-browse-image" class="button-upload red-button">Je choisis une image de mon ordinateur...</span><br>
                    <span id="button-facebook-image" class="button-upload red-button">Je choisis une image dans mes albums Facebook</span>
                </div>
            </div>
        </div>
        
        <hr id="contest-separator">
        <div id="vote-zone">
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
            <div id="load-more">Charger plus</div>
        </div>
    </div>
    <center>* En participant à ce concours, je certifie à l'équipe Swag Pizza d'avoir au moins 18 ans ou d'avoir un accord parental. Offre valable dans la limite de la bonne volonté de l'équipe Swag Pizza.</center>

    <script src="js/script.js"></script>
</body>
</html>