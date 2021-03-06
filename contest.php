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

        $user_id_rq = new FacebookRequest($session, 'GET', '/me?fields=id');
        $user_id_rp = $user_id_rq->execute();
        $user_id = json_decode($user_id_rp->getRawResponse(), true);

        include("connectDB.php");
        $id_query = "SELECT * FROM pictures WHERE id_owner = '".$user_id["id"]."'";

        if (!($result = $mysqli->query($id_query))) {
            echo "Echec de la préparation : (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $num_rows = $result->num_rows;
        if($num_rows)
            $existing_photo = true;
        else
            $existing_photo = false;

        $res = $result->fetch_all();
        $res = $res[0];
    } else {
        echo "<script>top.location.href='index.php';</script>";
    }
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
        <div id="contest-container-child-2">

        <?php
        if(!$existing_photo) {
            echo    '<div id="ask-post" class="container">
                        <span class="span-ask-post">Je poste ma photo participante</span><br>
                        <img id="arrow-participate" src="img/arrow.png"/>
                    </div>

                    <div id="global-browse-zone">
                        <form id="form-upload" action="uploadPhoto.php" enctype="multipart/form-data" method="post">
                            <div id="hide-button"><input type="file" name="browse-image" id="browse-image" accept="image/*"></input></div>
                        </form>
                        <div id="dynamic-upload-zone">
                            <span id="button-browse-image" class="button-upload red-button">Je choisis une image de mon ordinateur...</span><br>
                            <span id="button-facebook-image" class="button-upload red-button">Je choisis une image dans mes albums Facebook</span>
                        </div>
                    </div>';
        }   
        else {
            echo    '<div id="already-uploaded" class="container">
                        <div class="uploaded-photo" style="background: url('.$res[3].'); background-size: cover; background-position: center center;"></div>
                        <div id="upload-infos" data-id="'.$res[0].'">
                            <span class="your-pic">Votre photo participante</span>
                            <span class="delete-pic red-button">Enlever cette photo du concours</span>
                        </div>
                    </div>';
        }

        ?>
        </div>
        
        <hr id="contest-separator">
        <div id="vote-zone">
            <div id="photos-candidats">
                
            </div>
            <div id="load-more">Charger plus</div>
        </div>
    </div>
    <center>* En participant à ce concours, je certifie à l'équipe Swag Pizza d'avoir au moins 18 ans ou d'avoir un accord parental. Offre valable dans la limite de la bonne volonté de l'équipe Swag Pizza.</center>

    <div id="dark-mask"></div>
    <script src="js/script.js"></script>
    <script>

    $.getJSON("../getContestPictures.php", function(data) {
      $.each(data, function(index, value) {
        isUrlExists(value[3], function(status){
            if(status === 200){
               $.ajax({
                    type: "POST",
                    url: "../countLikes.php",
                    data: {"id_pic": value[0]}
               }).success(function(rows){
                    rows = $.parseJSON(rows); 
                    var like_count = rows.length;
                    console.log(like_count);

                    $("#photos-candidats").append("<div class='global-pic'><div class='photo' data-id='"+value[0]+"' data-like='false' style='background: url("+value[3]+"); background-size: cover; background-position: center center;'></div><br><span class='photo-user-name'>"+value[2]+"</span><span class='vote-zone'><span class='flaticon-heart118'></span><span class='vote-count'>"+like_count+"</span></span></div>");
               });

               
            }
            else if(status === 404){
               // 404 not found
               console.log("ERROR");
            }
        });
      });
    });
    </script>
</body>
</html>