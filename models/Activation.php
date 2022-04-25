<?php
require_once "../api/services/SendSms.php";
class Activation{
    private $conn;
    private $table='tbl_activation';

    public $userId;
    public $activation_code;
    public $recepient;
    public function __construct($db)
    {
        $this->conn=$db;
    }
    public function newActivation(){
        $code=rand(100000,999999);
        $msg="کد تاییدیه شما در برنامه پایش : "."\n".$code;
        if($this->activationExists()){
            $this->deleteActivation();
        }
        $query="INSERT INTO ".$this->table.
            " (userId,activation_code) VALUES (:userId, :activation_code)";
        $stmt=$this->conn->prepare($query);
        $stmt->bindParam(":userId",$this->userId);
        $stmt->bindParam(":activation_code",$code);

        if($stmt->execute()){
            $sendSms=new SendSms($msg,$this->recepient);
            return $sendSms->send();
        }
        else{
            return 0;
        }
    }
    public function deleteActivation(){
        $query="DELETE FROM ".$this->table.
            " WHERE userId=:userId";
        $stmt=$this->conn->prepare($query);
        $stmt->bindParam(":userId",$this->userId);

        if($stmt->execute()){
            return true;
        }
        else{
            return false;
        }
    }
    public function activationExists(){
        $query="SELECT * FROM ".$this->table.
            " WHERE userId=:userId";
        $stmt=$this->conn->prepare($query);
        $stmt->bindParam(":userId",$this->userId);
        $stmt->execute();

        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        if($row){
            return true;
        }
        else{
            return false;
        }
    }
    public function validateCode(){
        $query="SELECT * FROM ".$this->table.
            " WHERE userId=:userId and activation_code=:code";
        $stmt=$this->conn->prepare($query);
        $stmt->bindParam(":userId",$this->userId);
        $stmt->bindParam(":code",$this->activation_code);
        $stmt->execute();

        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        if($row){
            return true;
        }
        else{
            return false;
        }
    }
}