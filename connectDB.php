<?php

$mysqli = new mysqli("eu-cdbr-west-01.cleardb.com", "b7faa8912f6a83", "f84fc5f0", "heroku_77df0c1ec63aa7a");
if ($mysqli->connect_errno) {
	echo "Echec lors de la connexion à MySQL : (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

/*
if (!($result = $mysqli->query("SELECT * FROM pictures"))) {
	echo "Echec de la préparation : (" . $mysqli->errno . ") " . $mysqli->error;
}


$res = $result->fetch_all();

var_dump($res);
*/
