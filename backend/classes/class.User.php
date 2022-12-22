<?php

class User
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

}