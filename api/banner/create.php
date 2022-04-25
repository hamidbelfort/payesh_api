<?php
require_once '../../config/Database.php';

$database=new Database();
$db=$database->connect();

$banner=new Banner($db);

$banner->image=$_POST['image'];
$banner->link=$_POST['link'];
$banner->enabled=$_POST['enabled'];

$result=$banner->insertBanner();

if($result){
    echo json_encode(array(
        'message'=>'بنر با موفقیت ثبت شد'
    ));
}
else{
    echo json_encode(array(
        'message'=>'عملیات به دلیل خطا متوقف شد'
    ));
}