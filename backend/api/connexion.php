<?php

$dsn     = "mysql:host=localhost;dbname=explorepsm";
$user    = "root";
$psw     = "";

// Adresse serveur de l'application
$host 	= "http://localhost/exp-lorepsm/backend/";
$hostFront 	= "http://127.0.0.1:5173/";

// Connexion à la bdd avec le fichier de paramètres
$pdo = new PDO(
    $dsn,
    $user,
    $psw,
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
);
?>