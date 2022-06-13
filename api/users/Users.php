<?php
include "../config/Connect.php";
include "../service/SendSms.php";
class Users
{
    private $conn;
    public function __construct()
    {
        $db=new Connect();
        $this->conn=$db->conn;
    }
    public function register($firstName,$lastName,$nationalId,$birthdate,$phone){
        $query="INSERT INTO 
        tbl_users(firstName, lastName, nationalId, birthDate, fatherName, phoneNumber, address)
        VALUES ('$firstName','$lastName','$nationalId','$birthdate','','$phone','')";
        $stmt=$this->conn->prepare($query);
        $stmt->execute();
        return $this->conn->lastInsertId();
    }
    public function isPhoneExists($phone){
        $query = "SELECT * FROM tbl_users WHERE phoneNumber=:phoneNumber";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":phoneNumber", $this->$phone);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return true;
        } else {
            return false;
        }
    }
    public function getNewUsers(){
        $query="SELECT * FROM tbl_users WHERE verified=0 ORDER BY firstName,lastName";
        $stmt=$this->conn->prepare($query);
        $stmt->execute();
        $row=$stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($row);
    }
    public function verifyUser($uid){
        $query="UPDATE tbl_users SET verified=1, enabled=1 WHERE id=$uid";
        $stmt=$this->conn->prepare($query);
        if($stmt->execute()){
            return true;
        }
        return false;
    }
    public function enableUser($uid,$enable){
        $query="UPDATE tbl_users SET enabled=:enable WHERE id=$uid";
        $stmt=$this->conn->prepare($query);
        $stmt->bindParam(":enable",$enable);
        if($stmt->execute()){
            return true;
        }
        return false;
    }
    public function isUserVerified($uid){
        $query="SELECT * FROM tbl_users WHERE id=$uid AND verified=1";
        $stmt=$this->conn->prepare($query);
        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        if($stmt->rowCount()>0){
            return true;
        }
        return false;
    }
    public function getUserIdByPhone($phone){
        $query="SELECT id FROM tbl_users WHERE phoneNumber='$phone'";
        $stmt=$this->conn->prepare($query);
        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        if($row){
            return $row;
        }
        return 0;
    }
    public function getUserByPhone($phone){
        $query="SELECT * FROM tbl_users WHERE phoneNumber='$phone'";
        $stmt=$this->conn->prepare($query);
        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        if($row){
            return $row;
        }
        return 0;
    }
    public function sendCode($phone){
        $uid=$this->getUserIdByPhone($phone);
        $code = rand(100000, 999999);
        $msg = "کد تاییدیه شما در برنامه پایش : " . "\n" . $code;
        if ($this->isActivationExists($uid)) {
            $this->deleteActivation();
        }
        $query = "INSERT INTO tbl_activation (userId, activation_code) VALUES (:userId, :activation_code)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":userId", $uid);
        $stmt->bindParam(":activation_code", $code);

        if ($stmt->execute()) {
            $sendSms = new SendSms($msg, $phone);
            return $sendSms->send();
        } else {
            return 0;
        }
    }
    public function isActivationExists($userId){
        $query="SELECT * FROM tbl_activation WHERE userId=$userId";
        $stmt=$this->conn->prepare($query);
        $stmt->execute();
        if ($stmt->rowCount()>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function deleteActivation($userId){
        $query="DELETE FROM tbl_activation WHERE userId=$userId";
        $stmt=$this->conn->prepare($query);
        $stmt->execute();
    }
    public function confirmCode($phone,$code){
        $uid=$this->getUserIdByPhone($phone);
        $query="SELECT * FROM tbl_activation WHERE activation_code=$code AND userId=$uid";
        $stmt=$this->conn->prepare($query);
        $stmt->execute();
        if ($stmt->rowCount()>0){
            return true;
        }
        else{
            return false;
        }
    }

    public function addUserContact($uid,$phone,$address){
        $query="INSERT INTO tbl_contact (userId, phone, address) VALUES ($uid,'$phone','$address')";
        $stmt=$this->conn->prepare($query);
        $stmt->execute();
        return $this->conn->lastInsertId();
    }
    public function deleteUserContact($contactId){
        $query="DELETE FROM tbl_contact WHERE id=$contactId";
        $stmt=$this->conn->prepare($query);
        if($stmt->execute()){
            return true;
        }
        return false;
    }
    public function updateUserContact($contactId,$phone,$address){
        $query="UPDATE tbl_contact SET phone='$phone', address='$address' WHERE id=$contactId";
        $stmt=$this->conn->prepare($query);
        if($stmt->execute()){
            return true;
        }
        return false;
    }
    public function getUserContacts($uid){
        $query="SELECT * FROM tbl_contact WHERE userId=$uid";
        $stmt=$this->conn->prepare($query);
        $stmt->execute();
        $row=$stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($row);
    }
}