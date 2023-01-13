<?php

header("Access-Control-Allow-Origin: *");

// Création d'un post (souvenir (0) / commentaire (1))

require_once '../connexion.php';

$dossierDocuments = "../../documentsUGC/souvenirs/";

if (
    isset($_POST['typePost']) && isset($_POST['idAuteur']) && isset($_POST['textPost']) && isset($_POST['datePost'])
    && (
        ($_POST['typePost'] == 0 && isset($_POST['dateSvn']) && isset($_POST['coordsSvn']))
        ||
        ($_POST['typePost'] == 1 && isset($_POST['idRefPost']))
    )
) {

    $idUser = intval($_POST['idAuteur']);
    $textPost = $_POST['textPost'];
    $datePost = $_POST['datePost'];
    $typePost = intval($_POST['typePost']);

    // Souvenir
    if($typePost === 0) {
        $comRefPost = null;
        $coordsPost = $_POST['coordsSvn'];
        $dateSvn = $_POST['dateSvn'];
    }
    // Commentaire
    else {
        $comRefPost = intval($_POST['idRefPost']);
        $coordsPost = null;
        $dateSvn = null;
    }

    $sql = "INSERT INTO post (ID_USER, TYPE_POST, COM_REF_POST, COORDS_POST, DATE_POST, DATE_SVN, TEXT_POST) VALUES(?, ?, ?, ?, ?, ?, ?)";

    $requete = $pdo->prepare($sql);

    $requete->bindValue(1, $idUser);
    $requete->bindValue(2, $typePost);
    $requete->bindValue(3, $comRefPost);
    $requete->bindValue(4, $coordsPost);
    $requete->bindValue(5, $datePost);
    $requete->bindValue(6, $dateSvn);
    $requete->bindValue(7, $textPost);

    if ($requete->execute()) {

        if (isset($_POST['docSvn']) || isset($_FILES['docSvn'])) {
            // On cherche le post créé pour y attacher le document
            // (permet de ne pas empêcher la publication s'il y a
            // un problème lors de l'hébergement, car le document
            // pourra toujours être ajouté par la suite
            $idPost = $pdo->lastInsertId();

            // TYPE_DOC
            // 0 : photo
            // 1 : audio
            // 2 : URL

            // URL
            if (isset($_POST['docSvn'])) {
                $docSVN = $_POST['docSvn'];
                $typeDoc = 2;
            }
            // Fichiers
            else if (isset($_FILES['docSvn'])) {
                $docUpload = LoreUtils::uploadDocument("create", $_FILES['docSvn'], $idPost, null);

                if(is_array($docUpload)) {
                    $docSVN = $docUpload["nomDoc"];
                    $typeDoc = $docUpload["typeDoc"];
                }
                else exit($docUpload . "  le fichier n'a pas pu être hébergé");
            }

            // Création document
            $sql_Doc = "INSERT INTO document (ID_POST, TYPE_DOC, NOM_DOC) VALUES (?, ?, ?)";

            $requete_Doc = $pdo->prepare($sql_Doc);

            $requete_Doc->bindValue(1, $idPost);
            $requete_Doc->bindValue(2, $typeDoc);
            $requete_Doc->bindValue(3, $docSVN);

            echo $requete_Doc->execute();
        }

    } else {
        echo "Problème lors de la connexion à la base de données";
    }
} else {
    echo "Paramètre(s) manquant(s)";
}

exit();