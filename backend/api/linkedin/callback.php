<?php

require "./init.php";
require "../connexion.php";

require "../../classes/class.User.php";
require "../../classes/class.LoreUtils.php";

// On arrive ici lorsque l'utilisateur a autorisé l'application
// à utiliser ses données, on reçoit un code permettant
// de demander un access token

// Dans le cadre de notre application, l'utilisateur peut être dirigé
// ici dans deux cas : sa première connexion, ou le rafraîchissement de son token
// On doit donc effectuer des actions différents selon la situation

// CSRF
// Si le nombre aléatoire que l'on a généré lors de l'instanciation
// de l'objet LinkedIn et que l'on a inséré dans l'URL d'authentification
// se retrouve dans le paramètre "state" de la réponse,
// LinkedIn est bien celui qui a renvoyé une réponse
// (et non quelqu'un d'autre se faisant passer pour LinkedIn)
if ($_GET['state'] != $_SESSION["linkedincsrf"]) {
    // Rediriger sur la page de connexion avec une erreur
    LoreUtils::redirectErrMessage("Connexion","err-li");
}

// Maintenant que l'on sait que l'on "discute" bien avec LinkedIn
// on va demander un access token, avec le code que nous a renvoyé LinkedIn
// (pour maintenant confirmer que c'est bien avec nous que LinkedIn "discute")
else {
    $infosToken = $linkedin->getAccessToken($_GET['code']);
    // Si on ne reçoit rien, on affiche une erreur
    if (!$infosToken) {
        // Rediriger sur la page de connexion, avec une erreur
        LoreUtils::redirectErrMessage("Connexion","err-li");
    }
    // Si cela a fonctionné
    else {
        include "./profile.php";
    }
}