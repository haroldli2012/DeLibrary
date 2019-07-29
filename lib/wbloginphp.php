<?php
/*
apply weibo API
save name, city, gender info into database
get authorization from user first
then get token from weibo service
save token in to session
*/
  session_start();

  if(isset($_SESSION["logout"]))
   {
     $_SESSION["logout"] +=1;
     if($_SESSION["logout"]<10) {
      exit();
     }

    }


  if(isset($_REQUEST["wbname"])&&!isset($_SESSION["userId"])) {
    $name = test_input($_REQUEST["wbname"]);
    $city = test_input($_REQUEST["city"]);
    $gender = test_input($_REQUEST["gender"]);

    require "linktodb.php";
    $sql=sprintf("SELECT customerId FROM customers WHERE name='%s'",
        mysqli_real_escape_string($conn,$name));
         $result=mysqli_query($conn,$sql); 
         if($result===false) die("Could not query database to confirm user");
         if(mysqli_num_rows($result)==1) {
           $user=mysqli_fetch_array($result);
           $_SESSION["userId"] = $user[0];
           $_SESSION["user"] = $name;
           $action = "login";
           require "activity.php";
           echo 100;
           
         }
      //new user first time login and regist, save user info in database  
      elseif(mysqli_num_rows($result)==0) {
        $sql=sprintf("INSERT INTO customers(name,city,gender,status,registDate) VALUES('%s','%s','%s',0,now())",
            mysqli_real_escape_string($conn,$name),mysqli_real_escape_string($conn,$city),
            mysqli_real_escape_string($conn,$gender));
        $insert=mysqli_query($conn,$sql);
        if($insert==false) die("Could not query database to register");
        else {
          mysqli_close($conn);
          $last_id = mysqli_insert_id($conn);
          $_SESSION["user"] = $name;
          $_SESSION["userId"] = $last_id;
          $action = "regist";
          require "activity.php";
          echo 100;
          
        }
      }
      else { 
       echo 200; //more than one line or already register, no need password
       
      }
/*
//below is to get the weibo API token
if($_SESSION["userId"]) {

$ch = curl_init();

$data = array('client_id' => '2076762035', 'redirect_uri' => urlencode('http://heleshare.com'));

curl_setopt($ch, CURLOPT_URL, "https://api.weibo.com/oauth2/authorize");

curl_setopt($ch, CURLOPT_HEADER, 0);

curl_setopt($ch, CURLOPT_POST, TRUE);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

//curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

echo curl_exec($ch);

$errmsg = curl_error($ch); echo $errmsg;




$url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

echo $url;

curl_close($ch);

 

$code = strstr($url,"=");

$code = substr($code,1);



if(strlen($code)==32)

  {

$ch = curl_init();

$data = array('client_id' => '2076762035', 'client_secret' => '521573de53f7f0c6c44ffc74eb9d891a', 'grant_type' => 'authorization_code', 'code' => $code, 'redirect_uri' => urlencode('http://heleshare.com'));

curl_setopt($ch, CURLOPT_URL, "https://api.weibo.com/oauth2/access_toke");

curl_setopt($ch, CURLOPT_HEADER, 0);

curl_setopt($ch, CURLOPT_POST, TRUE);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

//curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$json = curl_exec($ch);

echo $json;

$errmsg = curl_error($ch); echo $errmsg;


curl_close($ch);

$json = json_decode($json); 

$token = $json["access_token"];

$_SESSION["weibo_token"] = $token;

  } // once get the code


}//once login or regist into data base success, have ID session

*/

   }//once correct weibo name passed

function test_input($data){
  $data=trim($data);
  $data=stripslashes($data);
  $data=htmlspecialchars($data);
  return $data;
} 

?>
