<?php

// Récupérer le profil d'un utilisateur
// - infos user (ID, type user, prénom, nom, photo, promo)
// - souvenirs postés
// - commentaires postés
// - posts likés

require "../connexion.php";

// CLASSES
require "../../classes/class.User.php";
require "../../classes/class.Post.php";
require "../../classes/class.Souvenir.php";

// PARAMETRES
if (!isset($_GET["idUser"])) {
    echo 'Aucun paramètre idUser passé';
    exit();
}

// Utilisateur
$idUser = $_GET["idUser"];
$user = User::getBasicUserInfos($idUser);

// Souvenirs avec likes et commentaires
$user->setLesSouvenirs(Souvenir::getSouvenirsUser($idUser));




echo json_encode($user);

exit();