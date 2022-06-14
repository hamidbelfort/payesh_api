<?php
include "../config/Connect.php";

class Property
{
    private $conn;

    public function __construct()
    {
        $db = new Connect();
        $this->conn = $db->conn;
    }

    public function insertProperty($imgArr, $thumb, $title, $description, $address,
                                   $price, $minPrice, $maxPrice, $propTypeId, $dealTypeId,
                                   $hasElevator, $hasParking, $hasWareHouse, $hasBalcony,
                                   $bedsNo, $toiletsNo, $cityId, $regionId, $userId)
    {
        try {

            $imageName = $thumb != "" ? $this->uploadImage($thumb) : "";

            $query = "INSERT INTO tbl_property (image, title, description, price, minPrice, maxPrice, propertyTypeId, dealTypeId, hasElevator, hasParking, hasWarehouse, hasBalcony, bedsNo, toiletsNo, cityId, regionId, address, userId) 
                VALUES ($imageName,$title,$description,$price,$minPrice,$maxPrice,$propTypeId,$dealTypeId,$hasElevator,$hasParking,$hasWareHouse,$hasBalcony,$bedsNo,$toiletsNo,$cityId,$regionId,$address,$userId)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $pid = $this->conn->lastInsertId();

            //now upload images

            $countImg = count($imgArr);
            for ($i = 0; $i < $countImg; $i++) {
                if ($imgArr[$i] != "") {
                    $imgGallery = $this->uploadImage($imgArr[$i]);
                    $this->storeImage($pid, $imgGallery);
                }
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateProperty()
    {
    }

    public function deleteProperty($pid)
    {
        $query = "DELETE FROM tbl_property WHERE id=$pid";
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function confirmProperty($pid, $confirm)
    {
        $query = "UPDATE tbl_property SET isConfirmed=$confirm WHERE id=$pid";
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getPosts()
    {
        $query = "SELECT * FROM tbl_property WHERE isConfirmed=1 ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row['post'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //propery types
        $query = "SELECT * FROM tbl_propertytype ORDER BY PropertyType";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row['propTypes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //propery types
        $query = "SELECT * FROM tbl_dealtype ORDER BY dealType";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row['dealTypes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($row);
    }

    public function getCustomPost($propTypeId, $dealTypeId)
    {
        if ($propTypeId != 0 && $dealTypeId != 0) {
            $query = "SELECT * FROM tbl_property WHERE 
                                 isConfirmed=1 AND propertyTypeId=$propTypeId
                                 AND dealTypeId=$dealTypeId ORDER BY id DESC";
        } else if ($propTypeId == 0 && $dealTypeId != 0) {
            $query = "SELECT * FROM tbl_property WHERE 
                                 isConfirmed=1 AND dealTypeId=$dealTypeId ORDER BY id DESC";
        } else if ($propTypeId != 0 && $dealTypeId == 0) {
            $query = "SELECT * FROM tbl_property WHERE 
                                 isConfirmed=1 AND propertyTypeId=$propTypeId ORDER BY id DESC";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row['post'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //property types
        $query = "SELECT * FROM tbl_propertytype ORDER BY PropertyType";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row['propTypes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //deal types
        $query = "SELECT * FROM tbl_dealtype ORDER BY dealType";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row['dealTypes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($row);
    }

    public function getPostById($pid)
    {
        $query = "SELECT * FROM tbl_property WHERE id=$pid ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row['post'] = $stmt->fetch(PDO::FETCH_ASSOC);

        $propTypeId = array_column($row, "propertyTypeId");
        $dealType = array_column($row, "dealTypeId");
        $cityId = array_column($row, "cityId");
        $regionId = array_column($row, "regionId");
        $view = array_column($row, "views");

        //update view
        $view[0] += 1;
        $query = "UPDATE tbl_property SET views=$view[0] WHERE id=$pid";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $query = "SELECT * FROM tbl_city WHERE id=$cityId[0]";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row['city'] = $stmt->fetch(PDO::FETCH_ASSOC);

        $query = "SELECT * FROM tbl_region WHERE id=$regionId[0]";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row['region'] = $stmt->fetch(PDO::FETCH_ASSOC);

        $query = "SELECT * FROM tbl_propertytype WHERE Id=$propTypeId[0]";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row['propType'] = $stmt->fetch(PDO::FETCH_ASSOC);

        $query = "SELECT * FROM tbl_dealtype WHERE id=$dealType[0]";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row['dealType'] = $stmt->fetch(PDO::FETCH_ASSOC);

        //images
        $query = "SELECT * FROM tbl_images WHERE propertyId=$pid";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row['images'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //similar post
        $query = "SELECT * FROM tbl_property WHERE propertyTypeId=$propTypeId[0] AND id<>$pid";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row['similar'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($row);
    }

    public function uploadImage($img)
    {
        $db = new Connect();
        $date = date('Ymd');
        $time = date('His');
        $rand = rand(1, 100000);
        $imageName = "image_" . $date . $rand . $time;
        $path = "images/$imageName";
        file_put_contents($path, base64_decode($img));
        return $db->getAppPath('dev') . $imageName;
    }

    public function storeImage($pid, $imagePath)
    {
        $query = "INSERT INTO tbl_images (propertyId, img) VALUES ($pid,'$imagePath')";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }
}