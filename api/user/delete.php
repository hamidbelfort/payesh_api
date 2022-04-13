<?php
header('Access-Control-Allow-Origin: *');

include_once '../../config/Database.php';
include_once '../../models/User.php';

$database=new Database();
$db=$database->connect();

$user=new User($db);

//REQUIRED FIELDS=id
if(isset($_POST['id'])){
    $user->id=$_POST['id'];
    if($user->userIdExists()){
        if($user->deleteUser()){
            echo json_encode(array([
                'message'=>'عملیات با موفقیت انجام شد'
            ]));
        }
        else{
            echo json_encode(array([
                'message'=>'عملیات به دلیل خطا متوقف شد'
            ]));
        }
    }
    else{
        echo json_encode(array([
            'message'=>'کاربر موردنظر یافت نشد'
        ]));
    }

}