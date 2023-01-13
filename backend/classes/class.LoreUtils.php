<?php


class LoreUtils
{

    public static function redirectErrMessage($vue,$err) {
        global $hostFront;
        header("Location: ".$hostFront.$vue."?err=".$err);
    }

    // Pour les images, on vérifie que le fichier n'est pas un autre type de fichier déguisé en image (extension renommée)
    // Actuellement supporté : jpg/jpeg et png
    public static function testImage($path)
    {
        return (@imagecreatefrompng($path) || @imagecreatefromjpeg($path) || @imagecreatefromstring(file_get_contents($path)) );
    }

    // Fonction pour pour déterminer le type d'un fichier et regarder si son extension est acceptée
    public static function checkFileExt($filename, $ext)
    {
        // De base considéré non valide
        $type = false;

        $patternImage = "/\.(png|jpg|jpeg|gif)$/";
        $patternAudio = "/\.(mp3|wav|ogg)$/";

        // On regarde si l'extension correspond à un type et format de document accepté
        if (preg_match($patternImage, $filename, $ext)) $type = "image";
        if (preg_match($patternAudio, $filename, $ext)) $type = "audio";

        return $type;
    }

    // Fonction pour héberger un document et en retourner le nom du fichier
    // $typeAction = create / update; $oldPath peut être mis à null lors de l'appel (car fonction récursive)
    public static function uploadDocument($typeAction, $file, $idPost, $oldPath)
    {

        global $pdo, $dossierDocuments;

        // On cherche le nom du fichier (fichier.ext)
        $filename = $file['name'];
        // On cherche l'extension du fichier (pour effectuer une première vérification)
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        // On veut déterminer le type de fichier en fonction de son extension
        // (une vérification plus détaillée viendra par la suite)
        $typeDocument = LoreUtils::checkFileExt($filename, $ext);

        // Si le fichier n'est pas un format accepté, on retourne une erreur
        if (!$typeDocument) return "err--format";

        // On détermine le numéro du type de document
        switch ($typeDocument) {
            case "image":
                $typeDocBDD = 0;
                break;
            case "audio":
                $typeDocBDD = 1;
                break;
            default:
                $typeDocBDD = 3;
        }

        // L'id du post et son extension
        $nom =  $idPost . "." . $ext;

        // Pour héberger un document
        if ($typeAction === "create") {

            $cheminFichier = $dossierDocuments . $nom;

            if (LoreUtils::storeDocument($typeDocument, $file, $cheminFichier, $oldPath, $ext)) return ["nomDoc" => $nom, "typeDoc" => $typeDocBDD];
            else return "err--depot";
        }

        // Si l'on veut modifier les documents du post, il faut donc son id
        // et supprimer le document précédent
        if (($typeAction === "update") && isset($idPost)) {

            // On cherche le document existant (s'il existe)
            $sql = "SELECT * FROM document WHERE ID_POST = ?";
            $requete = $pdo->prepare($sql);
            $requete->bindValue(1, $idPost);

            if ($requete->execute()) {
                $docActuel = null;
                while ($donnees = $requete->fetch()) {
                    $docActuel = $donnees['NOM_DOC'];
                }

                $oldPath = null;

                // S'il existe un document lié
                if ($docActuel !== null) {

                    // Si le document lié est du même type que celui que l'on s'apprête
                    // à rajouter et de la même extension (qu'on peut donc écraser
                    // en admettant que le fichier est valide (dans le cas d'un audio ou d'une image))
                    // alors on précise le chemin de l'ancien document

                    if (!(strpos($docActuel, $ext))) $oldPath = $dossierDocuments . $docActuel;
                }

                // Maintenant, on va héberger le fichier en sachant
                // si on écrase le document précédent ou non
                // (c'est-à-dire s'il y a un chemin vers l'ancien document)
                return LoreUtils::uploadDocument("create", $file, $idPost, $oldPath);
            }
            // Si la requête ne s'exécute pas, on signale qu'il y a
            // eu un problème avec la connexion à la base de données
            else return "err--bdd";
        }
    }

    // Fonction pour essayer de déposer le fichier dans le dossier prévu, et d'enlever celui précédemment stocké (s'il existe)
    public static function storeDocument($typeDocument, $file, $filePath, $oldPath, $ext)
    {
        $imgOK = false;
        $audOK = false;

        // Si on peut héberger le fichier
        if (move_uploaded_file($file['tmp_name'], $filePath)) {

            // Si c'est une image, il faut vérifier qu'elle est valide
            if ($typeDocument === "image") {
                // On vérifie si le fichier est véritablement une image
                if (LoreUtils::testImage($filePath)) $imgOK = true;
            }

            if ($typeDocument === "audio") {
                // Dans le cas des fichiers audio, on vérifie que le type MIME du fichier
                // commence bien par audio (une caractéristique commune de tous les formats audio)
                if (explode("/", mime_content_type($filePath))[0] === "audio") $audOK = true;
            }

            // Si les fichiers hébergés sont corrects
            if($imgOK || $audOK) {
                // S'il faut enlever le fichier précédent (qui n'est pas écrasé)
                if ($oldPath !== null) unlink($oldPath);
                // Succès de l'hébergement
                return true;
            }
            else {
                // Si l'image n'en est pas une (!) ou une image non valide
                // Si l'audio n'en est pas un
                // Alors on supprime le fichier
                unlink($filePath);
            }
        }
        // Sinon, l'hébergement a échoué
        return false;
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