<?php

class Document implements JsonSerializable
{
    private $idDoc = 0;
    private $typeDoc = 0;
    private $nomDoc = null;

    private $leSouvenir = null;

    public function __construct($idDoc, $typeDoc, $nomDoc)
    {
        $this->idDoc = $idDoc;
        $this->typeDoc = $typeDoc;
        $this->nomDoc = $nomDoc;
    }

    public function getIdDoc(){return $this->idDoc;}
    public function getTypeDoc(){return $this->typeDoc;}
    public function getNomDoc(){return $this->nomDoc;}

    public function getLeSouvenir(){return $this->leSouvenir;}

    public function setLeSouvenir($leSouvenir){$this->leSouvenir = $leSouvenir;}


    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}