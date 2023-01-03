<?php

// Récupérer un souvenir en particulier
// - infos souvenir
// - documents
// - nombre de likes et BOOL liké ou pas par user
// - commentaires et BOOL liké ou pas par user

require "../connexion.php";

// CLASSES
require "../../classes/class.User.php";
require "../../classes/class.Post.php";
require "../../classes/class.Souvenir.php";
require "../../classes/class.Document.php";

// PARAMETRES
if(isset($_GET["idPost"]) && $_GET["idPost"] !== "") {

    $idPost = intval($_GET["idPost"]);

    $idUserCo = null;

    if(isset($_GET["idUser"]) && $_GET["idUser"] !== "") {
        $idUserCo = intval($_GET["idUser"]);
    }

    $retour = Souvenir::getSouvenirs($idPost, $idAuteur = null,  $idUserCo);

    // Envoi du retour
    echo json_encode($retour);
}

else {
    echo "Paramètre idPost manquant.";
}

exit();