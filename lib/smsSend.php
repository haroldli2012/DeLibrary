<?php
namespace Qcloud\Sms\Demo;
require_once "SmsSender.php";
use Qcloud\Sms\SmsSingleSender;

session_start();

if(isset($_REQUEST["phone"]))
   $phone = $_REQUEST["phone"];
else exit();

try {
    // 请根据实际 appid 和 appkey 进行开发，以下只作为演示 sdk 使用
    $appid = 1400022598;
    $appkey = "0eb99426a26f13d8b7dc3886e6e3dbf7";
    $templId = 8430;
    $singleSender = new SmsSingleSender($appid, $appkey);

    // 指定模板单发
    $code = rand(100000, 999999);
    $params = array(".$code.", "10");
    $result = $singleSender->sendWithParam("86", $phone, $templId, $params, "", "", "");
    $rsp = json_decode($result, true);
    if($rsp["result"]==0) {
      echo $rsp["result"];
	  $_SESSION["vphone"] = $phone;
	  $_SESSION["vcode"] = md5($code);
	  $_SESSION["vtime"] = date("Y-m-d h:i:s");
	}
} catch (\Exception $e) {
    echo var_dump($e);
}