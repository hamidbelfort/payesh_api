<?php
Header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/User.php';

$database=new Database();
$db=$database->connect();

$user=new User($db);
if(isset($_GET['id'])){
    $user->id=$_GET['id'];
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
        'enabled'=>$user->enabled
    );
    echo json_encode($user_arr);
}
else{
    echo '400';
}

