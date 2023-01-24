<?php

header("Access-Control-Allow-Origin: *");

// Suppression d'un post (commentaire (0) / souvenir (1) / événement de frise (2))
// On supprime également les commentaires et réactions s'y rattachant s'ils existent

require_once '../connexion.php';

require "../../classes/class.LoreUtils.php";

$dossierDocuments = "../../documentsUGC/souvenirs/";

if (isset($_POST['idPost']) && isset($_POST['idUser'])) {

    // On récupère certaines informations sur le post
    $sql = "SELECT * FROM post WHERE ID_POST = ?";

    $requete = $pdo->prepare($sql);
    $requete->bindValue(1, $_POST['idPost']);

    // Si la requête s'exécute, on met dans une variable la ligne renvoyée par la requête
    if ($requete->execute()) {

        if ($donnees = $requete->fetch()) {
            $lePost = $donnees;

            // Bon auteur
            if($_POST['idUser'] == $donnees["ID_USER"]) {
                $idPostASuppr = $lePost['ID_POST'];

                // Si c'est un souvenir ou un événement
                if ($lePost['TYPE_POST'] == 0) {

                    $sql_Doc = "SELECT * FROM document WHERE ID_POST = ?";
                    $requete_Doc = $pdo->prepare($sql_Doc);
                    $requete_Doc->bindValue(1, $idPostASuppr);

                    if($requete_Doc->execute()) {

                        while($donnees_Doc = $requete_Doc->fetch()) {

                            // Si un fichier audio ou une image a été lié(e)
                            // On le supprime
                            if ($donnees_Doc["TYPE_DOC"] == 0 || $donnees_Doc["TYPE_DOC"] == 1) {
                                if (!(unlink($dossierDocuments . $donnees_Doc["NOM_DOC"]))) echo "Le fichier lié n'a pas pu être supprimé";
                            }

                        }

                    }
                }

                // On peut enfin supprimer le post en lui-même
                $sql_Suprr = "DELETE FROM post WHERE ID_POST = ?";

                $requete_Suppr = $pdo->prepare($sql_Suprr);
                $requete_Suppr->bindValue(1, $idPostASuppr);

                if (!($requete_Suppr->execute())) exit("Problème lors de la connexion à la base de données (3)");
            }
            else exit("Auteur non valide.");
        }
        else exit("Problème lors de la connexion à la base de données (2)");

    } else exit("Problème lors de la connexion à la base de données (1)");

} else echo "Paramètre(s) manquant(s)";

exit();