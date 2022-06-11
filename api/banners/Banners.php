<?php
include "../config/Connect.php";
class Banners
{
    private $conn;
    public function __construct()
    {
        $db=new Connect();
        $this->conn=$db->conn;
    }
    public function getBanners()
    {
        $query = 'SELECT * FROM tbl_banners ORDER BY id DESC LIMIT 5';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row=$stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($row);
    }

    public function insertBanner($image,$link,$enabled)
    {
        $query = "INSERT INTO tbl_banners (image,link,enabled) VALUES (:image,:link,:enabled)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":image", $image);
        $stmt->bindParam(":link", $link);
        $stmt->bindParam(":enabled", $enabled);
        if($stmt->execute()){
            return true;
        }
        else{
            return false;
        }
    }
}