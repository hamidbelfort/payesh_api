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
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name.';charset=utf8'
                , $this->db_username, $this->db_password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //$this->conn->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES utf8");
        }
        catch (Exception $e){
            echo 'Connection Error:' . $e->getMessage();
        }
    }
    public function getAppPath($mode){
        if($mode=="dev"){
            return "http://".gethostbyname(gethostname())."/payesh/api/";
        }
        else if($mode=="production"){
            return "https://".gethostbyname(gethostname())."/payesh/api/";
        }

    }
}