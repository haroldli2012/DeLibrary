<?php
  session_start();
  $action = "logout";
  require "activity.php";

/*
Weibo token to logout from Weibo Open API
if(isset($_SESSION["weibo_token"]))

 {

$ch = curl_init();

$data = array('access_token' => $_SESSION["weibo_token"]);

curl_setopt($ch, CURLOPT_URL, "https://api.weibo.com/oauth2/revokeoauth2");

curl_setopt($ch, CURLOPT_POST, TRUE);

curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

curl_exec($ch);

}
*/

  if(session_destroy()) echo 0;
  else echo 1;

  session_start();
$_SESSION["logout"]=1; //for third part logout record

?>
