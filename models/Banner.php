<?php

class Banner
{
    private $conn;
    private $table='tbl_banners';

    public $id;
    public $image;
    public $link;
    public $enabled;
    public function __construct($db)
    {
        $this->conn=$db;
    }
    public function getBanners(){
        $query='SELECT * FROM '.$this->table.
            ' ORDER BY id DESC LIMIT 5';
        $stmt=$this->conn->prepare($query);

        $stmt->execute();
        return $stmt;
    }
    public function insertBanner(){
        $query="INSERT INTO ".$this->table.
            "(image,link,enabled) VALUES (:image,:link,:enabled)";
        $stmt=$this->conn->prepare($query);

        $stmt->bindParam(":image",$this->image);
        $stmt->bindParam(":link",$this->link);
        $stmt->bindParam(":enabled",$this->enabled);

        if($stmt->execute()){
            return true;
        }
        else{
            return false;
        }
    }
}