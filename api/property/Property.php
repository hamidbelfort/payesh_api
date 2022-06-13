<?php
include "../config/Connect.php";
class Property
{
    private $conn;
    public function __construct()
    {
        $db=new Connect();
        $this->conn=$db->conn;
    }
    public function getPosts(){
        $query="SELECT * FROM tbl_property WHERE isConfirmed=1 ORDER BY id DESC";
        $stmt=$this->conn->prepare($query);
        $stmt->execute();
        $row['post']=$stmt->fetchAll(PDO::FETCH_ASSOC);

        //propery types
        $query="SELECT * FROM tbl_propertytype ORDER BY PropertyType";
        $stmt=$this->conn->prepare($query);
        $stmt->execute();
        $row['propTypes']=$stmt->fetchAll(PDO::FETCH_ASSOC);

        //propery types
        $query="SELECT * FROM tbl_dealtype ORDER BY dealType";
        $stmt=$this->conn->prepare($query);
        $stmt->execute();
        $row['dealTypes']=$stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($row);
    }
    public function getPostById($pid){
        $query="SELECT * FROM tbl_property WHERE id=$pid ORDER BY id DESC";
        $stmt=$this->conn->prepare($query);
        $stmt->execute();
        $row['post']=$stmt->fetch(PDO::FETCH_ASSOC);

        $propTypeId=array_column($row,"propertyTypeId");
        $dealType=array_column($row,"dealTypeId");
        $cityId=array_column($row,"cityId");
        $regionId=array_column($row,"regionId");
        $view=array_column($row,"views");

        //update view
        $view[0]+=1;
        $query="UPDATE tbl_property SET views=$view[0] WHERE id=$pid";
        $stmt=$this->conn->prepare($query);
        $stmt->execute();

        $query="SELECT * FROM tbl_city WHERE id=$cityId[0]";
        $stmt=$this->conn->prepare($query);
        $stmt->execute();
        $row['city']=$stmt->fetch(PDO::FETCH_ASSOC);

        $query="SELECT * FROM tbl_region WHERE id=$regionId[0]";
        $stmt=$this->conn->prepare($query);
        $stmt->execute();
        $row['region']=$stmt->fetch(PDO::FETCH_ASSOC);

        $query="SELECT * FROM tbl_propertytype WHERE Id=$propTypeId[0]";
        $stmt=$this->conn->prepare($query);
        $stmt->execute();
        $row['propType']=$stmt->fetch(PDO::FETCH_ASSOC);

        $query="SELECT * FROM tbl_dealtype WHERE id=$dealType[0]";
        $stmt=$this->conn->prepare($query);
        $stmt->execute();
        $row['dealType']=$stmt->fetch(PDO::FETCH_ASSOC);

        //images
        $query="SELECT * FROM tbl_images WHERE propertyId=$pid";
        $stmt=$this->conn->prepare($query);
        $stmt->execute();
        $row['images']=$stmt->fetchAll(PDO::FETCH_ASSOC);

        //similar post
        $query="SELECT * FROM tbl_property WHERE propertyTypeId=$propTypeId[0] AND id<>$pid";
        $stmt=$this->conn->prepare($query);
        $stmt->execute();
        $row['similar']=$stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($row);
    }
}