<?php
header('Access-Control-Allow-Origin: *');

include_once '../../config/Database.php';
include_once '../../models/Banner.php';

$database=new Database();
$db=$database->connect();

$banner=new Banner($db);

$stmt=$banner->getBanners();
$num=$stmt->rowCount();

if($num>0){
    $banners=array();
    $row=null;
    while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
        $record=array();
        $record['id']=$row['id'];
        $record['image']=$row['image'];
        $record['link']=$row['link'];
        $record['enabled']=$row['enabled'];
        $banners[]=$record;
}
echo json_encode($banners);
}

