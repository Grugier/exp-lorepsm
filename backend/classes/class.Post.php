<?php

class Post implements JsonSerializable
{
    protected $idPost = 0;
    protected $datePost = null;
    protected $textPost = null;

    protected $lAuteur = null;

    protected $lesLike = 0;
    protected $userALike = false;

    public function __construct($idPost, $datePost, $textPost)
    {
        $this->idPost = $idPost;
        $this->datePost = $datePost;
        $this->textPost = $textPost;
    }

    public function getIdPost(){return $this->idPost;}
    public function getDatePost(){return $this->datePost;}
    public function getTextPost(){return $this->textPost;}

    public function getLAuteur(){return $this->lAuteur;}
    public function getLesLike(){return $this->lesLike;}
    public function getUserALike(){return $this->userALike;}

    public function setLAuteur($lAuteur){$this->lAuteur = $lAuteur;}
    public function setLesLike($lesLike){$this->lesLike = $lesLike;}
    public function setUserALike($userALike){$this->userALike = $userALike;}

    // ---------------- //
    // STATIC FUNCTIONS //
    // ---------------- //

    // Chercher les likes (et le BOOL user a liké) d'un post
    // RETURN : le post avec les likes
    public static function retrieveLikesOfPost(Post $post, $idUserCo = null) {
        global $pdo;

        $idPost = $post->getIdPost();

        $sql_Like = "SELECT (SELECT EXISTS(SELECT * FROM aime WHERE ID_POST = ? AND ID_USER = ?)) AS USER_LIKE, COUNT(*) AS NB_LIKE FROM aime WHERE ID_POST = ?";

        $requete_Like = $pdo->prepare($sql_Like);
        $requete_Like->bindValue(1, $idPost);
        $requete_Like->bindValue(2, $idUserCo);
        $requete_Like->bindValue(3, $idPost);
        $requete_Like->execute();

        $donnees_Like = $requete_Like->fetch();

        $post->setLesLike($donnees_Like["NB_LIKE"]);
        $post->setUserALike(boolval($donnees_Like["USER_LIKE"]));

        return $post;
    }


    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}