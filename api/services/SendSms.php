<?php
class SendSms{
    private  $url="https://ippanel.com/services.jspd";
    public $msg='';
    public $recipient='';
    private $from='+985000291950';
    private $uname='amlakmarkazi';
    private $pass='hamid@13579';
    public function __construct($msg, $recipient){
        $this->msg=$msg;
        $this->recipient=$recipient;
    }
    public function send(){

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
        return $response2[1];

    }
}