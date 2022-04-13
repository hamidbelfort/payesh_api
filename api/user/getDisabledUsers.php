<?php
Header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/User.php';

$database=new Database();
$db=$database->connect();

$user=new User($db);

$stmt=$user->getDisabledUsers();
$num=$stmt->rowCount();

if($num>0){
    $users=array();
    $row=null;
    while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
        $record=array();
        $record['id']=$row['id'];
        $record['firstName']=$row['firstName'];
        $record['lastName']=$row['lastName'];
        $record['nationalId']=$row['nationalId'];
        $record['fatherName']=$row['fatherName'];
        $record['address']=$row['address'];
        $record['phoneNumber']=$row['phoneNumber'];

        $users[]=$record;
    }
    echo json_encode($users);
}
else{
    echo json_encode(
        array('message'=>'No records found')
    );
}