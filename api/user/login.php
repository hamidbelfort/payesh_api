<?php
header('Access-Control-Allow-Origin: *');

include_once '../../config/Database.php';
include_once '../../models/User.php';

$database=new Database();
$db=$database->connect();

$user=new User($db);
$activation=new Activation($db);
$user=new User($db);

$phone=$_POST['phoneNumber'];


if(isset($_POST['phoneNumber']) && isset($_POST['activationCode'])){
    $user->phoneNumber=$_POST['phoneNumber'];
    $userId=$user->getUserId();

    $activation->userId=$userId;
    $activation->activation_code=$_POST['activationCode'];
    $result=$activation->validateCode();

    if($result){
        $user->getUser();
        $user_arr=array(
            'id'=>$user->id,
            'firstName'=>$user->firstName,
            'lastName'=>$user->lastName,
            'nationalId'=>$user->nationalId,
            'fatherName'=>$user->fatherName,
            'phoneNumber'=>$user->phoneNumber,
            'address'=>$user->address,
            'verified'=>$user->verified,
            'enabled'=>$user->enabled,
            'roleId'=>$user->roleId
        );
        json_encode($user_arr);
    }
    else{
        json_encode(null);
    }
}