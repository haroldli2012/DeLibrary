<?php
//input name and pass to login, set session userId for service operation
//set session name and Js cookie name & pass for customer interface
//need login again with cookie name &pass, no need login with session name

    session_start();
    $name = test_input($_REQUEST["name"]);
    $pass = test_input($_REQUEST["pass"]);
    $secur = test_input($_REQUEST["secur"]);

    require "linktodb.php";
    

    require_once "../securimage/securimage.php";

    $image = new Securimage();
    $sql=sprintf("SELECT customerId, name, password FROM customers WHERE name='%s' or cellphone='%s'", mysqli_real_escape_string($conn,$name),mysqli_real_escape_string($conn,$name));
    $result=mysqli_query($conn,$sql);
    if($result===false) die("Could not query database to confirm user");
   mysqli_close($conn);

    if(mysqli_num_rows($result)!=1) {
        echo "用户名不存在";
      }
    elseif(mysqli_num_rows($result)==1) {
        $user=mysqli_fetch_array($result);
        $user_id = $user[0];
        $user_name = $user[1];
        $passhash = $user[2];
        
        if((password_verify($pass,$passhash))&&($image->check($secur) == true))
         {
           $_SESSION["user"] = $user_name;
           $_SESSION["userId"] = $user_id;
           $action = "login";
           require "activity.php";

?>
     <script>
       function setCookie(cname,cvalue,exdays) {
         var d = new Date();
         d.setTime(d.getTime() + (exdays*24*60*60*1000));
         var expires = "expires=" + d.toGMTString();
         document.cookie = cname+"="+cvalue+"; "+expires;
         }
      if(navigator.cookieEnabled) {
         setCookie("user",document.getElementsByName("myname")[0].value,30);
         setCookie("pass",document.getElementsByName("pass")[0].value,30);
        }
      var path = window.location.pathname;
      if(path.match(/register/)) window.location.assign("index.html");
      else location.reload();
     </script>

<?php
        exit();
      }
      if(!password_verify($pass,$passhash))
           echo "密码错误！";
      if($image->check($secur) != true) 
           echo "验证码错误！";
        }
    else
       echo "对不起，系统繁忙，暂时不能登录。";

function test_input($data){
  $data=trim($data);
  $data=stripslashes($data);
  $data=htmlspecialchars($data);
  return $data;
} 

?>
