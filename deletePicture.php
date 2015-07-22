<?php

session_start();

if(isset($_SESSION['fb_token'])) {
    include("connectDB.php");
    $photo_id = $_POST['id_pic'];

    $del_query = "DELETE FROM pictures WHERE id = '".$photo_id."'";
    if (!($result = $mysqli->query($del_query))) {
       echo "Echec de la préparation : (" . $mysqli->errno . ") " . $mysqli->error;
    }

    echo "<script>top.location.href='contest.php';</script>";
}