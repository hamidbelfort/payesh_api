<?php

class Connect
{
    public $conn;
    private $host = "localhost";
    private $db_name = "payesh_db";
    private $db_username = "payesh_user";
    private $db_password = "Payesh@123";
    public function __construct()
    {
        try{
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name
                , $this->db_username, $this->db_password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (Exception $e){
            echo 'Connection Error:' . $e->getMessage();
        }
    }
}