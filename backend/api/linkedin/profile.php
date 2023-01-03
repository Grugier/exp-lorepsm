<?php

// Cette page est inclue dans "callback.php" lorsque LinkedIn nous
// renvoie un access token valide.
$token = $infosToken[0];
$tempsExp = $infosToken[1];

// On demande donc à LinkedIn les informations de la personne
// avec ce même access token
$profile = $linkedin->getPerson($token);
$mail = $linkedin->getMail($token);

$etat = null;

// Si on a bien reçu un json en réponse
if (($mail !== "err--requetehttp") && ($profile !== "err--requetehttp")) {

    // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    // Gérer si on ne parvient pas à obtenir un des trucs
    if(isset($mail->status) || isset($profile->status)) $etat = "err--401";

    $idPersonneLinkedin = $profile->id;

    $leMail = $mail->elements[0]->{"handle~"}->emailAddress;

    // Tableau associatif (exemple : fr_FR comme première clé)
    $lePrenom = get_object_vars($profile->firstName->localized);
    $lePrenom = $lePrenom[array_key_first($lePrenom)];

    // Tableau associatif (exemple : fr_FR comme première clé)
    $leNom = get_object_vars($profile->lastName->localized);
    $leNom = $leNom[array_key_first($leNom)];

    if(isset($profile->profilePicture->{"displayImage~"}->elements[0]->identifiers[0]->identifier)) {
        $laPhoto = $profile->profilePicture->{"displayImage~"}->elements[0]->identifiers[0]->identifier;

        // On remplace l'image existante (s'il y en avait une)
        $new_Photo = $idPersonneLinkedin . ".jpg";

        // Les liens des photos de profil donnés expirant en même temps
        // que les tokens, on sauvegarde la photo sur notre serveur
        $picOutputURL = "../../documentsUGC/profilePicUsers/" . $new_Photo;

        LoreUtils::getFileFromUrl($laPhoto, $picOutputURL);

        $laPhoto = $new_Photo;
    }
    else $laPhoto = "defaultUser.png";

    // On ajoute le timestamp de l'instant au nombre de secondes avant
    // l'expiration du token obtenu
    $tempsExp += time();
    // On formate le timestamp d'expiration du token en date
    $dateExp = date('Y-m-d H:i:s', $tempsExp);

    $tokenHashCo = password_hash($token, PASSWORD_BCRYPT, array('cost' => 10));
    $existant = User::checkLinkedInUserValidity($idPersonneLinkedin, $tokenHashCo)[0];

    // Si le token est identique à celui dans la base de données OU non (un nouveau qui vient d'être généré) ou s'il faut le rafraîchir
    if((($existant === "token--ok") || ($existant === "token--refresh")) || ($existant === "user--NOT-OK")) {
        // Si l'utilisateur s'est déjà connecté, on considère qu'il rafraîchit son token
        if(User::updateUser("linkedin", $idPersonneLinkedin, $lePrenom, $leNom, $laPhoto, $leMail, $token, $dateExp) === "err--bdd") $etat = "err--bdd";
        else $etat = "refresh--ok";
    }
    if($existant === "user--nouveau") {
        // On crée l'utilisateur dans la base de données, avec les principales informations
        if(User::createUser($idPersonneLinkedin, $lePrenom, $leNom, $laPhoto, $leMail) === "err--bdd") $etat = "err--bdd";
        // On ajoute après le token et la date d'expiration
        // Peu d'importance si la mise à jour du token échoue, on pourra repasser par le système d'autorisation
        // Et être dans le cas d'un rafraîchissement de token
        if(User::updateUser("linkedin", $idPersonneLinkedin, null, null, null, null, $token, $dateExp) === "err--bdd") $etat = "err--bdd";

        if($etat !== "err--bdd") $etat = "creation--ok";
    }

    // Maintenant que l'on a créé ou rafraîchi un token, on les stocke dans des variables de session
    // Un script sera appelé au moment d'arriver sur le profil (car on redirige l'user sur cette route (du front))
    // Le script se chargera de donner les informations nécessaires à la création du localStorage (infos sur la personne,
    // token hashé, si date de promo ou non...) et de détruire la session (éviter de garder les informations sur le token
    // et de ne pas interférer avec une possible autre connexion ou autre)

    if(($etat === "creation--ok") || ($etat === "refresh--ok")) {
        $CRYPTidLinkedIn = User::cryptID($idPersonneLinkedin);
        session_destroy();
        // On encode en base 64 l'ID crypté pour éviter les problèmes d'encoding
        $CRYPTidLinkedIn = urlencode(base64_encode($CRYPTidLinkedIn));
        header("Location: ".$hostFront."?prog=".$CRYPTidLinkedIn);
        exit();
    }

    // Si une erreur s'est produite
    else {
        LoreUtils::redirectErrMessage("Connexion", $etat);
    }
}
else {
    LoreUtils::redirectErrMessage("Connexion", "err--requetehttp");
}

exit();