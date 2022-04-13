<?php
class User{
    private $conn;
    private $table='tbl_users';

    public $id;
    public $firstName;
    public $lastName;
    public $nationalId;
    public $birthDate;
    public $fatherName;
    public $phoneNumber;
    public $address;
    public $roleId;
    public $contactId;
    public $verified;
    public $enabled;
    public $createdAt;

    public function __construct($db)
    {
        $this->conn=$db;
    }
    public function getNewUsers(){
        $this->verified=0;
        $query='SELECT * FROM '.$this->table.
            ' WHERE verified =:verified ORDER BY lastname';
        $stmt=$this->conn->prepare($query);
        $stmt->bindParam(':verified',$this->verified);

        $stmt->execute();
        return $stmt;
    }
    public function getDisabledUsers(){
        $this->enabled=0;
        $query='SELECT * FROM '.$this->table.
            ' WHERE enabled =:enabled ORDER BY lastname';
        $stmt=$this->conn->prepare($query);
        $stmt->bindParam(':enabled',$this->enabled);

        $stmt->execute();
        return $stmt;
    }
    public function getUser(){
        $query='SELECT * FROM '.$this->table.
            ' WHERE id =? LIMIT 0,1';
        $stmt=$this->conn->prepare($query);
        $stmt->bindParam(1,$this->id);

        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_ASSOC);

        $this->id=$row['id'];
        $this->firstName=$row['firstName'];
        $this->lastName=$row['lastName'];
        $this->nationalId=$row['nationalId'];
        $this->birthDate=$row['birthDate'];
        $this->fatherName=$row['fatherName'];
        $this->phoneNumber=$row['phoneNumber'];
        $this->address=$row['address'];
        $this->verified=$row['verified'];
        $this->enabled=$row['enabled'];
    }
    public function insertUser(){
        $query="INSERT INTO ".$this->table.
            ' (firstName,lastName,nationalId,birthDate,fatherName,phoneNumber,address,roleId)'.
            ' VALUES(:firstName,:lastName,:nationalId,:birthDate,:fatherName,:phoneNumber,:address,:roleId)';
        $stmt=$this->conn->prepare($query);
        $stmt->bindParam(":firstName",$this->firstName);
        $stmt->bindParam(":lastName",$this->lastName);
        $stmt->bindParam(":nationalId",$this->nationalId);
        $stmt->bindParam(":birthDate",$this->birthDate);
        $stmt->bindParam(":fatherName",$this->fatherName);
        $stmt->bindParam(":phoneNumber",$this->phoneNumber);
        $stmt->bindParam(":address",$this->address);
        $stmt->bindParam(":roleId",$this->roleId);

        if($stmt->execute()){
            return true;
        }
        printf("Error %s.".$stmt->error);
        return false;
    }
    public function phoneNumberExists(){
        $query="SELECT * FROM ".$this->table.' WHERE phoneNumber=:phoneNumber';
        $stmt=$this->conn->prepare($query);
        $stmt->bindParam(":phoneNumber",$this->phoneNumber);

        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        if($row){
            return true;
        }
        else{
            return false;
        }

    }
    public function userIdExists(){
        $query="SELECT * FROM ".$this->table.' WHERE id=:id';
        $stmt=$this->conn->prepare($query);
        $stmt->bindParam(":id",$this->id);

        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        if($row){
            return true;
        }
        else{
            return false;
        }

    }
    public function verifyUser(){

        $query="UPDATE ".$this->table.
            ' SET verified=:verified, enabled=:enabled  WHERE id=:id';
        $stmt=$this->conn->prepare($query);
        $stmt->bindParam(':verified',$this->verified);
        $stmt->bindParam(':enabled',$this->enabled);
        $stmt->bindParam(':id',$this->id);
        if($stmt->execute()){
            return true;
        }
        return false;
    }
    public function enableUser(){

        $query="UPDATE ".$this->table.
            ' SET enabled=:enabled WHERE id=:id';
        $stmt=$this->conn->prepare($query);
        $stmt->bindParam(':verified',$this->enabled);
        $stmt->bindParam(':id',$this->id);
        if($stmt->execute()){
            return true;
        }
        return false;
    }
    public function deleteUser(){

        $query="DELETE FROM ".$this->table.
            ' WHERE id=:id';
        $stmt=$this->conn->prepare($query);
        $stmt->bindParam(':id',$this->id);

        if($stmt->execute()){
            return true;
        }
        return false;
    }
    public function updateUser(){
        $query="UPDATE  ".$this->table.
            ' SET firstName=:firstName,lastName=:lastName,nationalId=:nationalId,
            birthDate=:birthDate,fatherName=:fatherName,address=:address 
            WHERE id=:id';
        $stmt=$this->conn->prepare($query);
        $stmt->bindParam(":firstName",$this->firstName);
        $stmt->bindParam(":lastName",$this->lastName);
        $stmt->bindParam(":nationalId",$this->nationalId);
        $stmt->bindParam(":birthDate",$this->birthDate);
        $stmt->bindParam(":fatherName",$this->fatherName);
        $stmt->bindParam(":address",$this->address);
        $stmt->bindParam(":id",$this->id);

        if($stmt->execute()){
            return true;
        }
        printf("Error %s.".$stmt->error);
        return false;
    }
}
