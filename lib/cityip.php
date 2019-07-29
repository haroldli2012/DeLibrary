<?php
  session_start();
  $lati = $_REQUEST["x"];
  $longi = $_REQUEST["y"];

  $city = "南京市";

  if($lati!=0 && $longi !=0) {
    $ch = curl_init("http://api.map.baidu.com/geocoder/v2/?location=".$lati.",".$longi."&output=json&ak=aBcMEiC3zbPPXLL1fVgfm3nf");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $output = curl_exec($ch);

    if(!curl_errno($ch)) {
     // $info = curl_getinfo($ch, CURLINFO_HTTP_CODE);
     // $info = test_input($info); 
     // $info = str_replace("200","",$info);
      $json = json_decode($output,true);
      if($json["status"]==0) {
        $tmp = $json["result"]["addressComponent"]["city"];
        if(preg_match("/^\p{Han}+$/u",$tmp)) $city = $tmp;
      }
    } 
  }
  else  {
    $client = @$_SERVER["HTTP_CLIENT_IP"];
    $forward = @$_SERVER["HTTP_X_FORWARDED_FOR"];
    $remote = $_SERVER["REMOTE_ADDR"];

    if(filter_var($client, FILTER_VALIDATE_IP)) {
       $ip = $client;
     }
    elseif(filter_var($forward, FILTER_VALIDATE_IP)) {
     $ip = $forward;
     }
    else $ip = $remote;

    $ch = curl_init("http://api.map.baidu.com/location/ip?ak=aBcMEiC3zbPPXLL1fVgfm3nf&ip=".$ip);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $output = curl_exec($ch);

    if(!curl_errno($ch)) {
    //  $info = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //  $info = test_input($info); 
    //  $info = str_replace("200","",$info); 
      $json = json_decode($output,true);
      if($json["status"]==0) {
        $tmp = $json["content"]["address_detail"]["city"];
        if(preg_match("/^\p{Han}+$/u",$tmp)) $city = $tmp;
       }
    }
  }
  

 if(isset($_REQUEST["IP"])) 
       echo $ip;
  else {
      //echo json_last_error();
       
        $city = substr($city,0,-3);
        $_SESSION["city"] = $city;
        echo $city;
   }

  curl_close($ch);

function test_input($data){
  $data=trim($data);
  $data=stripslashes($data);
  $data=htmlspecialchars($data);
  return $data;
} 
?>