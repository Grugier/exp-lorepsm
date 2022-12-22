<?php

class Post
{
    private $idPost = 0;
    private $datePost = null;
    private $textPost = null;

    private $lAuteur = null;
    private $lesLike = array();

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

    public function setLAuteur($lAuteur){$this->lAuteur = $lAuteur;}
    public function setLesLike($lesLike){$this->lesLike = $lesLike;}

}