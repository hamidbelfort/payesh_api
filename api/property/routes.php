<?php
include "Property.php";
$property=new Property();
if(isset($_GET['cmd'])){
    $cmd=$_GET['cmd'];
    if($cmd=="getAll"){
        $property->getPosts();
    }
    else if($cmd=="getPost"){
        if (isset($_GET['pid'])){
            $pid=$_GET['pid'];
            $property->getPostById($pid);
        }
    }
}