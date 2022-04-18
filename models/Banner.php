<?php

class Banner
{
    private $conn;
    private $table='tbl_banners';

    private $id;
    private $image;
    private $link;
    private $enabled;
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
}