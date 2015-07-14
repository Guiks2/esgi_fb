<?php

session_start();

if(isset($_SESSION["fb_token"]) && !empty($_SESSION["fb_token"])) {
    header("Location: https://esgi-fb.herokuapp.com/contest.php");
    exit();
} else {
	header("Location: http://www.google.fr");
	exit();
}

