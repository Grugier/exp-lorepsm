<?php

// On commence à créer une session
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../../../../session'));
session_start();

require "./linkedin.php";

// On spécifie les données propres à l'application
require "./app_credentials.php";

// Adresse à autoriser sur la page de l'application
// sur LinkedIn developpers
//$app_callback = "http://loremmi.viensenmmi.com/jsonLoremmi/json/linkedin/callback.php";
$app_callback = "http://localhost/exp-lorepsm/backend/api/linkedin/callback.php";

// Ce que l'on souhaite obtenir (ici nom, prénom, photo et mail)
$app_scopes = "r_liteprofile r_emailaddress";

$ssl = false;

$linkedin = new LinkedIn($app_id, $app_secret, $app_callback, $app_scopes, $ssl);