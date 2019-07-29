<?php
    require "linktodb.php";
    
    $sql=sprintf("SELECT customerId, name, password FROM customers WHERE name='%s' OR cellphone='%s'", mysqli_real_escape_string($conn,$user_name),mysqli_real_escape_string($conn,$user_name));
    $result=mysqli_query($conn,$sql);
    if($result===false) die("Could not query database to confirm user");
   mysqli_close($conn);

     if(mysqli_num_rows($result)==1) {
        $user=mysqli_fetch_array($result);
        $id = $user[0];
        $user_name = $user[1];
        $passhash = $user[2];
        
        if(password_verify($pass,$passhash))
         {
           $_SESSION["user"] = $user_name;
           $_SESSION["userId"] = $id;
           $user_id = $id;
           $action="login";
           require "activity.php";
          }
        else {
           $user_name="";
           $pass = "";
           $user_id = 0;
?>
     <script>
       function setCookie(cname,cvalue,exdays) {
         var d = new Date();
         d.setTime(d.getTime() + (exdays*24*60*60*1000));
         var expires = "expires=" + d.toGMTString();
         document.cookie = cname+"="+cvalue+"; "+expires;
         }
         setCookie("user",1,-1);
         setCookie("pass",1,-1);
     </script>
<?php
          }
      }
?>
