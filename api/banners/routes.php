<?php
include "./Banners.php";
$banners=new Banners();
if(isset($_GET['cmd'])) {
    $cmd = $_GET['cmd'];
    //region get last 5 banners
    if($cmd=="getAll"){
        $banners->getBanners();
    }//endregion
    //region insert new banner
    else if($cmd=="new-banner"){
        if(isset($_POST['image'])){
            $image=$_POST['image'];
            $link=isset($_POST['link'])?$_POST['link']:"";
            $enabled=isset($_POST['enabled'])?$_POST['enabled']:1;
            $result=$banners->insertBanner($image,$link,$enabled);
            if($result){
                echo json_encode(array("success"=>true,"message"=>"تصویر بنر با موفقیت ذخیره شد"));
            }
            else{
                echo json_encode(array("success"=>false,"message"=>"ذخیره تصویر بنر به دلیل خطا متوقف شد"));
            }
        }
    }
    //endregion
}