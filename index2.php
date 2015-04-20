<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link href="css/main-style.css" rel="stylesheet" type="text/css">
<script src="js/fittext.js"></script>
<title>Concours photo Swag Pizza</title>
</head>

<body>
	<div id="container-parent">
    	<div id="container-child-1">
       		<div id="pic">
        		<img src="img/swagpizza.png" width="220" height="200"/>
            </div>
            <div id="next">
            	<p id="accroche">
            		CONCOURS PHOTO SWAG PIZZA
            	</p>
            	<p id="description-concours">
                	Participez en postant une photo de votre plus belle pizza ainsi que sa recette et tentez de gagner votre pizza hebdomadaire pendant 3 mois* !
            	</p>
            </div>
        </div>
        
        <div id="container-child-2">
        	<a href="#" class="red-button">JE PARTICIPE !</a>
        </div>
    </div>
    <center>* En participant à ce concours, je certifie à l'équipe Swag Pizza d'avoir au moins 18 ans ou d'avoir un accord parental. Offre valable dans la limite de la bonne volonté de l'équipe Swag Pizza.</center>
	<script>
		window.fitText(document.getElementById("accroche"), 1.2);
		window.fitText(document.getElementById("description_concours"), 1.5);
	</script>
</body>
</html>
