<?php
class SendSms{
    private  $url="https://ippanel.com/services.jspd";
    public $msg='';
    public $recipient='';
    private $from='+985000291950';
    private $uname='amlakmarkazi';
    private $pass='hamid@13579';
    public function __construct(){

    }
    public function send(){


        $rcpt_nm = array('9121111111','9122222222');
        $param = array
        (
            'uname'=>$this->uname,
            'pass'=>$this->pass,
            'from'=>$this->from,
            'message'=>$this->msg,
            'to'=>json_encode($this->recipient),
            'op'=>'send'
        );

        $handler = curl_init($this->url);
        curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($handler, CURLOPT_POSTFIELDS, $param);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        $response2 = curl_exec($handler);

        $response2 = json_decode($response2);
        $res_code = $response2[0];
        $res_data = $response2[1];


        echo $res_data;
    }
}