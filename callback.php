<?php

session_start();

if(isset($_SESSION["fb_token"]) && !empty($_SESSION["fb_token"])) {
    header("Location: https://esgi-fb.herokuapp.com/contest.php");
} else {
	header("Location: https://esgi-fb.herokuapp.com/");
}

