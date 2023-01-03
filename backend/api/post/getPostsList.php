<?php

// Récupérer une liste de posts
// - basique : liste de souvenirs avec seulement ID du souvenir et coordonnées (payload minifié pour les points dans la visite)
// - avec paramètre idAuteur : liste des souvenirs (documents, NOMBRE de likes, commentaires et BOOL liké ou pas par user), commentaires postés et posts likés

require "../connexion.php";

// CLASSES
require "../../classes/class.User.php";
require "../../classes/class.Post.php";
require "../../classes/class.Souvenir.php";

// PARAMETRES
$retour = null;

if(isset($_GET["idAuteur"]) && $_GET["idAuteur"] !== "") {

    $idAuteur = intval($_GET["idAuteur"]);

    $idUserCo = null;

    if(isset($_GET["idUser"]) && $_GET["idUser"] !== "") {
        $idUserCo = intval($_GET["idUser"]);
    }

    $retour = Souvenir::getSouvenirs($idPost = null, $idAuteur, $idUserCo);
}
// Liste basique de souvenirs
else {
    $retour = Souvenir::getBasicSouvenirsList();
}

// Envoi du retour
echo json_encode($retour);

exit();