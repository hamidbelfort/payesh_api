<?php
include "./Users.php";
$users=new Users();
if(isset($_GET['cmd'])){
    $cmd=$_GET['cmd'];
    //region register user
    if($cmd=='register'){
        if(isset($_POST['firstname']) && isset($_POST['lastname'])
            && isset($_POST['birthdate']) && isset($_POST['nationalId'])
            && isset($_POST['phone'])){

            $firstName=$_POST['firstname'];
            $lastname=$_POST['lastname'];
            $phone=$_POST['phone'];
            $nationalId=$_POST['nationalId'];
            $birthDate=$_POST['birthdate'];
            if(!$users->isPhoneExists($phone)){
                $id=$users->register($firstName,$lastname,$nationalId,$birthDate,$phone);
                echo json_encode(array("success"=>true,"message"=>$id));
            }
            else{
                echo json_encode(array("success"=>false,"message"=>"این شماره قبلا ثبت شده است"));
            }
        }
    }//endregion
    //region verify user activation code
    else if($cmd=='confirm'){
        if(isset($_POST['phone']) && isset($_POST['code'])){
            $phone=$_POST['phone'];
            $code=$_POST['code'];
            $result=$users->confirmCode($phone,$code);
            $loggedUser=$users->getUserByPhone($phone);
            echo json_encode($loggedUser);
        }
    }//endregion
    //region login and send activation code
    else if($cmd=='login'){
        if(isset($_POST['phone'])){
            $phone=$_POST['phone'];
            $phoneExists=$users->isPhoneExists($phone);
            if($phoneExists){
                $uid=$users->getUserByPhone($phone);

                $isVerify=$users->isUserVerified($uid);

                if($isVerify){
                    $result=$users->sendCode($phone);
                    if($result>0){
                        echo json_encode(array("success"=>true,"message"=>"کد تاییدیه با موفقیت ارسال شد"));
                    }
                    else{
                        echo json_encode(array("success"=>false,"message"=>"کد تاییدیه ارسال نشد"));
                    }
                }
                else{
                    echo json_encode(array("success"=>false,"message"=>"حساب کاربری شما هنوز تایید نشده است"));
                }
            }
            else{
                echo json_encode(array("success"=>false,"message"=>"حسابی مرتبط با این شماره در برنامه یافت نشد"));
            }

        }
    }//endregion
    //region verify new user
    else if($cmd=='verify'){
        if(isset($_POST['id'])){
            $uid=$_POST['id'];
            $result=$users->verifyUser($uid);
            if($result){
                echo json_encode(array("success"=>true,"message"=>"کاربر با موفقیت تایید و فعال شد"));
            }
            else{
                echo json_encode(array("success"=>false,"message"=>"تایید کاربر به دلیل خطا متوقف شد"));
            }
        }
    }//endregion
    //region enable or disable user
    else if($cmd=='enable'){
        if(isset($_POST['id']) && isset($_POST['enable'])) {
            $uid = $_POST['id'];
            $enable = $_POST['enable'];
            $result=$users->enableUser($uid,$enable);
            if($result){
                if($enable==1){
                    echo json_encode(array("success"=>true,"message"=>"کاربر با موفقیت فعال شد"));
                }else{
                    echo json_encode(array("success"=>true,"message"=>"کاربر با موفقیت غیرفعال شد"));
                }
            }
            else{
                echo json_encode(array("success"=>false,"message"=>"عملیات به دلیل خطا متوقف شد"));
            }
        }
    }//endregion
    //region getNewUsers
    else if($cmd=='newUsers'){
        $users->getNewUsers();
    }
    //endregion
    //region searchByName
    else if($cmd=='searchByName'){
        if(isset($_POST['name'])){
            $criteria=$_POST['name'];
            $users->searchUserByName($criteria);
        }
    }
    //endregion
    //region get user contacts
    else if($cmd=='userContacts'){
        if(isset($_POST['uid'])){
            $uid=$_POST['uid'];
            $users->getUserContacts($uid);
        }
    }
    //endregion
    //region get contact by contactId
    else if($cmd=='getContact'){
        if(isset($_POST['id'])){
            $id=$_POST['id'];
            $users->getUserContactById($id);
        }
    }
    //endregion

}