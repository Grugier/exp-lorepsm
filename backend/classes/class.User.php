<?php

class User implements JsonSerializable
{
    private $idUser = 0;
    private $typeUser = 0;
    private $prenom = null;
    private $nom = null;
    private $photoProfil = null;
    private $promo = null;

    private $lesSouvenirs = array();
    private $lesCommentaires = array();
    private $lesLike = array();

    public function __construct($idUser, $typeUser, $prenom, $nom, $photoProfil, $promo)
    {
        $this->idUser = $idUser;
        $this->typeUser = $typeUser;
        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->photoProfil = $photoProfil;
        $this->promo = $promo;
    }

    public function getIdUser(){return $this->idUser;}
    public function getTypeUser(){return $this->typeUser;}
    public function getPrenom(){return $this->prenom;}
    public function getNom(){return $this->nom;}
    public function getPhotoProfil(){return $this->photoProfil;}
    public function getPromo(){return $this->promo;}

    public function getLesSouvenirs(){return $this->lesSouvenirs;}
    public function getLesCommentaires(){return $this->lesCommentaires;}
    public function getLesLike(){return $this->lesLike;}

    public function setLesSouvenirs($lesSouvenirs){$this->lesSouvenirs = $lesSouvenirs;}
    public function setLesCommentaires($lesCommentaires){$this->lesCommentaires = $lesCommentaires;}
    public function setLesLike($lesLike){$this->lesLike = $lesLike;}

    // ---------------- //
    // STATIC FUNCTIONS //
    // ---------------- //

    // Chercher les informations de base d'un utilisateur en fonction de son id
    public static function getBasicUserInfos($idUser) {
        global $pdo;

        $user = null;

        $sql = "SELECT ID_USER, TYPE_USER, PRENOM, NOM, PHOTO_PROFIL, PROMO FROM user WHERE ID_USER = ?";

        $requete = $pdo->prepare($sql);
        $requete->bindValue(1, $idUser);

        $requete->execute();

        if ($donnees = $requete->fetch()) {
            $user = new User(
                $donnees['ID_USER'],
                $donnees['TYPE_USER'],
                $donnees['PRENOM'],
                $donnees['NOM'],
                $donnees['PHOTO_PROFIL'],
                $donnees['PROMO']
            );
        }

        return $user;
    }

    // Vérifier si l'utilisateur existe déjà dans la base de données
    public static function checkLinkedInUserValidity($idLinkedin, $tokenHash){
        global $pdo;

        if (isset($idLinkedin)) {
            // On cherche si l'utilisateur est déjà enregistré
            $sql = "SELECT * FROM user WHERE ID_LINKEDIN = ?";

            $requete = $pdo->prepare($sql);
            $requete->bindValue(1, $idLinkedin);

            $user = null;
            // Si on trouve une ligne, c'est qu'il existe déjà
            if ($requete->execute()) $user = $requete->fetch();
            else return ["err--bdd", ""];

            // Si on a bien une ligne, on récupère les colonnes qui nous intéressent
            if (isset($user['ID_LINKEDIN'])) {
                $dateExpToken = $user['TOKEN_EXP'];

                // Si le token hashé du localStorage ne correspond pas à celui
                // de l'utilisateur stocké dans la base de données
                if(!(password_verify($user['TOKEN_LI'], $tokenHash))) return ["user--NOT-OK", $user['ID_USER']];

                // Si le token a besoin d'être rafraîchi
                // on décide d'essayer de mettre à jour les
                // informations personnelles dans le même temps
                if (User::checkTokenExpirationDate($dateExpToken) === "REFRESH") return ["token--refresh", $user['ID_USER']];
                else return ["token--ok", $user['ID_USER']];
            }

            // $user vaut null, on n'a donc pas de correspondance
            else return ["user--nouveau", ""];
        }

        else return ["err--id", ""];
    }

    // Fonction pour savoir si le token doit être renouvelé (si expire dans moins de 15 jours)
    // => le principe des 15 jours peut être modifié
    public static function checkTokenExpirationDate($dateExpToken) {

        $nbJoursRequis = 15;

        // On cherche le timestamp à cet instant
        $timestampNow = time();
        // On calcule le timestamp de la date d'expiration
        $timestampExp = strtotime($dateExpToken);

        // On calcule la différence
        $diff = $timestampExp - $timestampNow;

        // Si le nombre de jours dans lesquels le token expire est supérieur
        // on ne le renouvelle pas encore
        if(($diff/86400)>$nbJoursRequis) return "OK";

        // Sinon, on signale qu'il faudrait le renouveler
        else return "REFRESH";
    }

    public static function cryptID($id) {
        global $wayfinder;

        $cipher_method = 'aes-128-ctr';
        $enc_key = openssl_digest($wayfinder, 'SHA256', TRUE);
        $enc_iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher_method));
        $exegol = openssl_encrypt($id, $cipher_method, $enc_key, 0, $enc_iv) . "::" . bin2hex($enc_iv);

        return $exegol;
    }

    public static function decryptID($id) {
        global $wayfinder;

        list($id, $enc_iv) = array_pad(explode("::", $id, 2), 2, null);
        $cipher_method = 'aes-128-ctr';
        $enc_key = openssl_digest($wayfinder, 'SHA256', TRUE);
        if(!($exegol = openssl_decrypt($id, $cipher_method, $enc_key, 0, hex2bin($enc_iv)))) $exegol = "err--decrypt";

        return $exegol;
    }

    // Ajouter un utilisateur à la base de données
    public static function createUser($idLinkedin, $prenom, $nom, $photo, $mail) {
        global $pdo;

        $sql = "INSERT INTO user (ID_LINKEDIN, TYPE_USER, PRENOM, NOM, PHOTO_PROFIL, MAIL) VALUES(?, ?, ?, ?, ?, ?)";

        $requete = $pdo->prepare($sql);

        $requete->bindValue(1, $idLinkedin);
        $requete->bindValue(2, 1);
        $requete->bindValue(3, $prenom);
        $requete->bindValue(4, $nom);
        $requete->bindValue(5, $photo);
        $requete->bindValue(6, $mail);

        if($requete->execute()) return "OK";
        else return "err--bdd";
    }

    // Modifier un utilisateur
    public static function updateUser($typeId, $id, $prenom, $nom, $photo, $mail, $token, $dateExp) {
        global $pdo;

        if(isset($typeId)) {
            switch ($typeId) {
                case "exp-lore":
                    $typeIdBdd = "USER";
                    break;
                case "linkedin":
                    $typeIdBdd = "LINKEDIN";
                    break;
                default:
                    exit("Type d'ID non valide");
            }
        }

        else {
            exit("Type d'ID non valide");
        }

        // On cherche d'abord les informations de l'user à modifier
        $sql = "SELECT * FROM user WHERE ID_".$typeIdBdd." = ?";

        $requete = $pdo->prepare($sql);
        $requete->bindValue(1, $id);

        $user = null;

        // Si on récupère une ligne
        if($requete->execute()) $user = $requete->fetch();
        else return "err--bdd";

        $sql2 = "UPDATE user SET ";

        $colonnes = [];
        $valeurs = [];

        // On ajoute les paramètres s'il sont différents des informations
        // stockées dans la base de données
        if(isset($prenom) && ($prenom !== $user['PRENOM'])) {
            $colonnes[] = "PRENOM";
            $valeurs[] = $prenom;
        }

        if(isset($nom) && ($nom !== $user['NOM'])) {
            $colonnes[] = "NOM";
            $valeurs[] = $nom;
        }

        if(isset($photo) && ($photo !== $user['PHOTO_PROFIL'])) {
            $colonnes[] = "PHOTO_PROFIL";
            $valeurs[] = $photo;
        }

        if(isset($mail) && ($mail !== $user['MAIL'])) {
            $colonnes[] = "MAIL";
            $valeurs[] = $mail;
        }

        if(isset($token)) {
            $colonnes[] = "TOKEN_LI";
            $valeurs[] = $token;
        }

        if(isset($dateExp)) {
            $colonnes[] = "TOKEN_EXP";
            $valeurs[] = $dateExp;
        }

        if(isset($colonnes[0])) {
            for($i=0; $i< count($colonnes); $i++){
                if($i !== 0) $sql2.=", ";
                $sql2.=$colonnes[$i]." = ?";
            }
        }
        else return "OK";

        $sql2.=" WHERE ID_".$typeIdBdd." = ?";

        $requete2 = $pdo->prepare($sql2);

        // On bind les différentes valeurs
        for($i2=0; $i2<count($colonnes); $i2++) {
            $requete2->bindValue($i2+1, $valeurs[$i2]);
        }

        // Ne pas oublier le WHERE
        $requete2->bindValue(count($colonnes)+1, $id);

        if ($requete2->execute()) return "OK";
        else return "err--bdd";
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}