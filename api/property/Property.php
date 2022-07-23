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
                                   $bedsNo, $toiletsNo,$year,$area, $cityId, $regionId, $userId)
    {
        try {

            $imageName = $thumb != "" ? $this->uploadImage($thumb) : "";

            $query = "INSERT INTO tbl_property (image, title, description, price, minPrice, maxPrice, propertyTypeId, dealTypeId, hasElevator, hasParking, hasWarehouse, hasBalcony, bedsNo, toiletsNo,year,area, cityId, regionId, address, userId) 
                VALUES ($imageName,$title,$description,$price,$minPrice,$maxPrice,$propTypeId,$dealTypeId,$hasElevator,$hasParking,$hasWareHouse,$hasBalcony,$bedsNo,$toiletsNo,$year,$area,$cityId,$regionId,$address,$userId)";
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
        $query2 = "DELETE FROM tbl_bookmark WHERE propertyId=$pid";
        $stmt2 = $this->conn->prepare($query2);
        if ($stmt->execute() && $stmt2->execute()) {
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

    public function getPostById($pid,$userId)
    {
        $query = "SELECT * FROM tbl_property WHERE id=$pid ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row['post'] = $stmt->fetch(PDO::FETCH_ASSOC);

        $propTypeId = array_column($row, "propertyTypeId");
        $dealType = array_column($row, "dealTypeId");
        $cityId = array_column($row, "cityId");
        $regionId = array_column($row, "regionId");
        $contactId = array_column($row, "contactId");
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

        //contact

        $query = "SELECT id,concat(firstName,' ',lastName) AS fullname,nationalId FROM tbl_users WHERE id=$userId";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row['userInfo'] = $stmt->fetch(PDO::FETCH_ASSOC);

        //contact

        $query = "SELECT * FROM tbl_contact WHERE id=$contactId[0]";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row['contact'] = $stmt->fetch(PDO::FETCH_ASSOC);

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

        //isBookmarked
        $bookmarked=$this->isBookmarkExists($userId,$pid);
        $row['bookmarked']=$bookmarked;

        //property note
        $query = "SELECT id,propertyId,userId,noteText FROM tbl_notes WHERE propertyId=$pid AND userId=$userId";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        if($stmt->rowCount()>0){
            $row['note'] = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        else{
            $row['note']=null;
        }

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
    public function bookmarkProperty($userId, $pid){
        $bookmarkExists=$this->isBookmarkExists($userId,$pid);
        if(!$bookmarkExists){
            $query = "INSERT INTO tbl_bookmark (userId, propertyId) VALUES ($userId,$pid)";
        }
        else{
            $query = "DELETE FROM tbl_bookmark WHERE userId=$userId AND propertyId=$pid";
        }

        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {
            return true;
        }
        return false;

    }
    public function noteOnProperty($userId, $pid,$noteText){
        if(!$noteText==""){
            $noteExists=$this->isNoteExists($userId,$pid);
            if(!$noteExists){
                $query = "INSERT INTO tbl_notes (userId, propertyId,noteText) VALUES ($userId,$pid,'$noteText')";
            }
            else{
                $query = "UPDATE tbl_notes SET noteText='$noteText' WHERE userId=$userId AND propertyId=$pid";
            }
        }
        else{
            $query="DELETE FROM tbl_notes WHERE userId=$userId AND propertyId=$pid";
        }


        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {
            return true;
        }
        return false;

    }
    public function isNoteExists($userId,$pid){
        $query="SELECT count(*) FROM tbl_notes WHERE propertyId=$pid AND userId=$userId";
        $stmt=$this->conn->prepare($query);
        $stmt->execute();
        $row=$stmt->fetchColumn();
        if($row>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function isBookmarkExists($userId,$pid){
        $query="SELECT count(*) FROM tbl_bookmark WHERE propertyId=$pid AND userId=$userId";
        $stmt=$this->conn->prepare($query);
        $stmt->execute();
        $row=$stmt->fetchColumn();
        if($row>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function getBookmarkedProperties($userId){
        $query="SELECT tbl_property.* FROM tbl_property INNER JOIN tbl_bookmark on tbl_property.id = tbl_bookmark.propertyId WHERE tbl_property.isConfirmed AND tbl_bookmark.userId=$userId";
        $stmt=$this->conn->prepare($query);
        $stmt->execute();
        $row=$stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($row);
    }
}