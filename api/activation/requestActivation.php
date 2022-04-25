<?php
header('Access-Control-Allow-Origin: *');

include_once '../../config/Database.php';
include_once '../../models/User.php';
include_once '../../models/Activation.php';

$database=new Database();
$db=$database->connect();

$user=new User($db);
$activation=new Activation($db);
$phone=$_POST['phoneNumber'];

if(isset($_POST['phoneNumber'])){
    $user->phoneNumber=$_POST['phoneNumber'];
    if($user->phoneNumberExists()){
        $result=$activation->newActivation();
        if($result!=0){
            echo json_encode(array(
                'code'=>200,
                'message'=>'پیامک تاییدیه ارسال شد'
            ));
        }
        else{
            echo json_encode(array(
                'code'=>500,
                'message'=>'پیامک تاییدیه به دلیل خطا ارسال نشد'
            ));
        }
    }
}