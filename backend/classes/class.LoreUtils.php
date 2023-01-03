<?php


class LoreUtils
{

    public static function redirectErrMessage($vue,$err) {
        global $hostFront;
        header("Location: ".$hostFront.$vue."?err=".$err);
    }

    // Télécharger un fichier depuis une URL
    // (nécessaire pour obtenir la photo de profil de l'utilisateur
    // car les liens obtenus avec le token expirent en même temps que lui)
    // /!\ /!\
    // Attention pour l'adresse de destination de la copie (outputURL)
    // car comme on veut modifier ou créer un fichier, il faut une URL
    // relative par rapport au fichier où la fonction est appelée
    // ("./" obligatoire si on veut aller dans un dossier enfant de celui où l'on se trouve)
    public static function getFileFromUrl($fileURL,$outputURL) {

        // On ouvre une session cURL
        $ch = curl_init($fileURL);

        // "w" signifie que l'on veut être en lecture et écriture : si le fichier n'existe pas, on le crée
        // Si le fichier existe déjà, on le remplace
        // "b" car si l'on travaille avec du binaire (exemple : images), cela permet d'éviter d'avoir
        // des images corrompues
        $fp = fopen($outputURL, 'wb');

        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        // Il faut une option supplémentaire en cas de redirection par le serveur
        // Apparemment nécessaire pour retrouver une photo de profil Facebook
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }
}