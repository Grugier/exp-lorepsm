<?php

class Souvenir extends Post implements JsonSerializable
{
    private $coords = null;
    private $dateSvn = null;

    private $lesCommentaires = array();
    private $lesDocuments = array();

    public function __construct($idPost, $datePost, $textPost, $coords, $dateSvn)
    {
        parent::__construct($idPost, $datePost, $textPost);
        $this->coords = $coords;
        $this->dateSvn = $dateSvn;
    }

    public function getCoords(){return $this->coords;}
    public function getDateSvn(){return $this->dateSvn;}

    public function getLesCommentaires(){return $this->lesCommentaires;}
    public function getLesDocuments(){return $this->lesDocuments;}

    public function setLesCommentaires($lesCommentaires){$this->lesCommentaires = $lesCommentaires;}
    public function setLesDocuments($lesDocuments){$this->lesDocuments = $lesDocuments;}

    // ---------------- //
    // STATIC FUNCTIONS //
    // ---------------- //

    // Tous les souvenirs sous forme d'ID et de coordonnées dans l'espace
    public static function getBasicSouvenirsList() {
        global $pdo;

        $souvenirs = [];

        $sql = "SELECT ID_POST, COORDS_POST FROM post WHERE TYPE_POST = 0";

        $requete = $pdo->prepare($sql);
        $requete->execute();

        while ($donnees = $requete->fetch()) {
            $souvenirs[] = [
                "idPost" => $donnees["ID_POST"],
                "coordsPost" => $donnees["COORDS_POST"]
            ];
        }

        return $souvenirs;
    }

    // Liste des souvenirs ou un souvenir avec documents, nombre de likes + BOOL user, commentaires avec nombre de likes + BOOL user + LISTE auteurs
    public static function getSouvenirs($idPost = null, $idAuteur = null, $idUserCo = null) {
        global $pdo;

        $sql_Svn = "SELECT ID_POST, ID_USER, COORDS_POST, DATE_POST, DATE_SVN, TEXT_POST FROM post WHERE TYPE_POST = 0 AND ";

        $listeSouvenirs = [];
        $listeAuteursId = [];

        // Un seul souvenir
        if($idPost !== null) {
            $sql_Svn .= "ID_POST = ?";
            $paramBind = $idPost;
        }
        else if($idAuteur !== null) {
            $sql_Svn .= "ID_USER = ?";
            $paramBind = $idAuteur;
        }
        else {
            return "Err : Aucun critère spécifié";
        }

        $requete_Svn = $pdo->prepare($sql_Svn);
        $requete_Svn->bindValue(1, $paramBind);
        $requete_Svn->execute();

        // Souvenir(s) trouvé(s)
        while($donnees_Svn = $requete_Svn->fetch()) {

            $souvenir = new Souvenir(
                $donnees_Svn["ID_POST"],
                $donnees_Svn["DATE_POST"],
                $donnees_Svn["TEXT_POST"],
                $donnees_Svn["COORDS_POST"],
                $donnees_Svn["DATE_SVN"]
            );

            // Auteur
            $souvenir->setLAuteur($donnees_Svn["ID_USER"]);

            if(!in_array($donnees_Svn["ID_USER"], $listeAuteursId)) {
                $listeAuteursId[] = $donnees_Svn["ID_USER"];
            }

            // Documents
            $documents = [];

            $sql_Doc = "SELECT ID_DOC, TYPE_DOC, NOM_DOC FROM document WHERE ID_POST = ?";

            $requete_Doc = $pdo->prepare($sql_Doc);
            $requete_Doc->bindValue(1, $idPost);
            $requete_Doc->execute();

            while($donnees_Doc = $requete_Doc->fetch()) {
                $documents[] = new Document(
                    $donnees_Doc["ID_DOC"],
                    $donnees_Doc["TYPE_DOC"],
                    $donnees_Doc["NOM_DOC"]
                );
            }

            $souvenir->setLesDocuments($documents);

            // Likes et BOOL user a liké
            $souvenir = Post::retrieveLikesOfPost($souvenir, $idUserCo);

            // Commentaires
            $commentaires = [];

            $sql_Com = "SELECT ID_POST, ID_USER, DATE_POST, TEXT_POST FROM post WHERE TYPE_POST = 1 AND COM_REF_POST = ?";

            $requete_Com = $pdo->prepare($sql_Com);
            $requete_Com->bindValue(1, $idPost);
            $requete_Com->execute();

            while($donnees_Com = $requete_Com->fetch()) {

                $comm = new Post(
                    $donnees_Com["ID_POST"],
                    $donnees_Com["DATE_POST"],
                    $donnees_Com["TEXT_POST"]
                );

                // Auteur
                $comm->setLAuteur($donnees_Com["ID_USER"]);

                if(!in_array($donnees_Com["ID_USER"], $listeAuteursId)) {
                    $listeAuteursId[] = $donnees_Com["ID_USER"];
                }

                // Likes et BOOL user a liké
                $comm = Post::retrieveLikesOfPost($comm, $idUserCo);

                $commentaires[] = $comm;
            }

            $souvenir->setLesCommentaires($commentaires);

            $listeSouvenirs[] = $souvenir;

        }

        $auteurs = [];

        foreach($listeAuteursId as $idAuteur) {
            $auteurs[] = User::getBasicUserInfos($idAuteur);
        }

        return [
            "souvenirs" => $listeSouvenirs,
            "auteurs" => $auteurs
        ];
    }


    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}