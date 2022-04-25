<?php
header('Access-Control-Allow-Origin: *');

include_once '../../config/Database.php';
include_once '../../models/User.php';

$database=new Database();
$db=$database->connect();

$user=new User($db);
if(isset($_POST['phoneNumber'])){
    $user->phoneNumber=$_POST['phoneNumber'];
    if(!$user->phoneNumberExists()){
        $user->firstName=isset($_POST['firstName'])?$_POST['firstName']:"";
        $user->lastName=isset($_POST['lastName'])?$_POST['lastName']:"";
        $user->nationalId=isset($_POST['nationalId'])?$_POST['nationalId']:"0";
        $user->fatherName=isset($_POST['fatherName'])?$_POST['fatherName']:"";
        $user->birthDate=isset($_POST['birthDate'])?$_POST['birthDate']:Date('Y-m-d');
        $user->address=isset($_POST['address'])?$_POST['address']:"";
        $user->roleId=isset($_POST['roleId'])?$_POST['roleId']:2;

        if($user->insertUser()){
            echo json_encode(array(
                'code'=>200,
                'message'=>'کاربر با موفقیت ثبت شد'
            ));
        }
        else{
            echo json_encode(array(
                'code'=>500,
                'message'=>'عملیات به دلیل خطا متوقف شد'
            ));
        }
    }
    else{
        echo json_encode(array(
            'code'=>400,
            'message'=>"شماره موبایل تکراری میباشد"
        ));
    }

}


