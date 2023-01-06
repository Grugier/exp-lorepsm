<?php

header("Access-Control-Allow-Origin: *");

require "./init.php";
require "../connexion.php";

require "../linkedin/app_credentials.php";

require "../../classes/class.User.php";

// On arrive ici lorsque l'utilisateur possède dans son local storage des
// informations relatives au site (id LinkedIn crypté et token hashé).

// Ce script a pour but de vérifier que l'utilisateur a des informations
// correctes (id enregistré, token valide) et de demander au front
// d'effectuer des actions différentes selon le cas : demande de nouveau token,
// suppression du local storage "falsifié", redirection vers profil, etc

// Si l'id LinkedIn (crypté) et le token (hashé) du local storage de l'utilisateur
// ne sont pas nuls (vérification supplémentaire)
if(isset($_POST['idLIStor']) && isset($_POST['tokenLIStor'])) {

    // On décrypte l'ID
    $idLinkedin = User::decryptID($_POST['idLIStor']);

    $tokenHash = $_POST['tokenLIStor'];

    // On regarde si l'utilisateur s'est déjà connecté précédemment
    $existant = User::checkLinkedInUserValidity($idLinkedin, $tokenHash);

    echo $existant[0];
}

// En front :

//"user--NOT-OK"
// --> vider localstore
// --> rediriger user sur accueil

//"token--refresh"
// --> mettre ID en $_SESSION
// --> rediriger user sur authLinkedIn.php (page qui redirigera sur URL authorization)
// --> dans le callback, récupérer l'ID (pour savoir que l'user existe déjà)

//"token--ok"
// --> ajax getLoggedUser.php (récupérer l'ID mis en $_SESSION)
// --> mettre cet ID dans App.vue (car on vérifie à chaque refresh si l'user est correct)

//"user--nouveau"
// --> ne rien faire en l'état sur le front
//     car l'user a dans son local storage des infos
//     pas stockées dans la bdd
// --> vider localstore
// --> rediriger user sur accueil

//"err--bdd"
// --> erreur de la base de données
