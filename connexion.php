<?php

function connexionBDD(){
    $host = 'ec2-54-217-202-108.eu-west-1.compute.amazonaws.com' ;
    $dbname = 'dcaf0vuc1absq0' ;
    $id = 'otusbiptshtvyh' ;
    $mdp = 'zPGX6DtFYI8gu30-ONH5sgcUs2' ;
    $port = '5432';
    $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION ;
    $bdd = new PDO('mysql:host='.$host.'; '.$port.'=5432; dbname='.$dbname, $id, $mdp, $pdo_options);

    return $bdd;
}