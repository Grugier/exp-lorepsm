<?php

header("Access-Control-Allow-Origin: *");

// Récupérer le profil basique de l'utilisateur connecté

require "../connexion.php";

// Chiffrage
require "../linkedin/app_credentials.php";

// CLASSES
require "../../classes/class.User.php";

// Si l'on a passé un ID LinkedIn crypté ("promo")
if (isset($_POST['promo'])) {

    $sql = "SELECT ID_USER, TYPE_USER, NOM, PRENOM, PROMO, PHOTO_PROFIL, TOKEN_LI FROM user WHERE ID_LINKEDIN = ?";

    $idLinkedin = User::decryptID($_POST['promo']);

    $requete = $pdo->prepare($sql);
    $requete->bindValue(1, $idLinkedin);

    if ($requete->execute()) $user = $requete->fetch();
    else exit("err--bdd");

    $progression = password_hash($user['TOKEN_LI'], PASSWORD_BCRYPT, array('cost' => 10));

    $retour = ['idUser' => $user['ID_USER'], 'annee' => $user['PROMO'], 'promo' => $_POST['promo'], 'typeUser' => $user['TYPE_USER'], 'nom' => $user['NOM'], 'prenom' => $user['PRENOM'], 'photoProfil' => $user['PHOTO_PROFIL'], 'progression' => $progression];

    echo json_encode($retour);
    exit();
}

else {
    exit("Paramètres manquants");
}
