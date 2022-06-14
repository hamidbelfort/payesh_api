<?php
include "Property.php";
$property=new Property();
if(isset($_GET['cmd'])){
    $cmd=$_GET['cmd'];
    if($cmd=="getAll"){
        $property->getPosts();
    }
    else if($cmd=="getPost"){
        if (isset($_POST['pid'])){
            $pid=$_POST['pid'];
            $property->getPostById($pid);
        }
    }
    else if($cmd=="customPost"){
        $propTypeId=isset($_POST['propTypeId'])?$_POST['propTypeId']:0;
        $dealTypeId=isset($_POST['dealTypeId'])?$_POST['dealTypeId']:0;
        $property->getCustomPost($propTypeId,$dealTypeId);
    }
    else if($cmd=="newProperty"){
        if(isset($_POST['title']) && isset($_POST['userId'])
            && isset($_POST['propTypeId']) && isset($_POST['dealTypeId'])){

            $imageArr=isset($_POST['images'])?$_POST['images']:array();
            $title=$_POST['title'];
            $image=isset($_POST['image'])?$_POST['image']:"";
            $des=isset($_POST['des'])?$_POST['des']:"";
            $price=isset($_POST['price'])?$_POST['price']:0;
            $minPrice=isset($_POST['minPrice'])?$_POST['minPrice']:0;
            $maxPrice=isset($_POST['maxPrice'])?$_POST['maxPrice']:0;
            $propTypeId=$_POST['propTypeId'];
            $dealTypeId=$_POST['dealTypeId'];
            $hasElevator=isset($_POST['hasElevator'])?$_POST['hasElevator']:0;
            $hasParking=isset($_POST['hasParking'])?$_POST['hasParking']:0;
            $hasWarehouse=isset($_POST['hasWarehouse'])?$_POST['hasWarehouse']:0;
            $hasBalcony=isset($_POST['hasBalcony'])?$_POST['hasBalcony']:0;
            $bedsNo=isset($_POST['bedsNo'])?$_POST['bedsNo']:0;
            $toiletNo=isset($_POST['toiletNo'])?$_POST['toiletNo']:0;
            $cityId=$_POST['cityId'];
            $regionId=$_POST['regionId'];
            $address=isset($_POST['address'])?$_POST['address']:"";
            $userId=$_POST['userId'];

            $result=$property->insertProperty($imageArr,$image,$title,$des,$address,
                $price,$minPrice,$maxPrice,$propTypeId,$dealTypeId,
                $hasElevator,$hasParking,$hasWarehouse,$hasBalcony,$bedsNo,$toiletNo,
            $cityId,$regionId,$userId);
            if($result){
                echo json_encode(array("success"=>true,"message"=>"اطلاعات ملک با موفقیت ثبت شد"));
            }
            else{
                echo json_encode(array("success"=>false,"message"=>"ثبت اطلاعات ملک به دلیل خطا متوقف شد"));
            }
        }
    }
    else if($cmd=='confirmProperty'){
        if(isset($_POST['pid']) && isset($_POST['confirm'])){
            $pid=$_POST['pid'];
            $confirm=$_POST['confirm'];
            $result=$property->confirmProperty($pid,$confirm);
            if($result){
                echo json_encode(array("success"=>true,"message"=>"درخواست با موفقیت اجرا شد"));
            }
            else{
                echo json_encode(array("success"=>false,"message"=>"اجرای خطا به دلیل خطا متوقف شد"));
            }
        }
    }
    else if($cmd=='delete'){
        if(isset($_POST['pid'])){
            $pid=$_POST['pid'];
            $result=$property->deleteProperty($pid);
            if($result){
                echo json_encode(array("success"=>true,"message"=>"درخواست با موفقیت اجرا شد"));
            }
            else{
                echo json_encode(array("success"=>false,"message"=>"اجرای خطا به دلیل خطا متوقف شد"));
            }
        }
    }
}