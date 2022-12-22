<?php

class Souvenir extends Post
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

}