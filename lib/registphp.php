<?php
/*
0 - login successful
1 - more than one line has same cellphone, or has password after register
2 - pass1  and pass2  not equal to each other
3 - no cellphone or code
4 - phone incorrect
5 - code incorrect or over time
6 - code fail to send
*/
  session_start();
  if(isset($_REQUEST["cellphone"])&&(isset($_REQUEST["code"]))) {
    $name = $cellphone = test_input($_REQUEST["cellphone"]);
    $code = test_input($_REQUEST["code"]);
    $time = date_create(date("Y-m-d h:i:s"));

    if(isset($_SESSION["vphone"])&&isset($_SESSION["vcode"])) {
      $vphone = $_SESSION["vphone"];
      $vcode = $_SESSION["vcode"];
      $vtime = date_create($_SESSION["vtime"]);
      date_add($vtime, new DateInterval("PT10M"));
      
      if(($cellphone==$vphone)&&($vcode==md5($code))&&($time<=$vtime)) {
         require "linktodb.php";
         $sql=sprintf("SELECT customerId, status FROM customers WHERE cellphone='%s'",
        mysqli_real_escape_string($conn,$cellphone));
         $result=mysqli_query($conn,$sql); 
         if($result===false) die("Could not query database to confirm user");
         if(mysqli_num_rows($result)==1) {
           $user=mysqli_fetch_array($result);
           $user_id = $user[0];
           $user_status = $user[1];
         }
      }
      elseif($cellphone!=$vphone) {
        echo 4; //phone incorrect
        exit();
      }
      else {
        echo 5; //code incorrect or overtime
        exit();
      }
    }
    else {
      echo 6;  //code send fail
      exit();
    }
  }
  else {
    echo 3; //no cellphone or code
    exit();
  }

  if(isset($_REQUEST["pass1"])&&(isset($_REQUEST["pass2"]))) {
     $pass1 = test_input($_REQUEST["pass1"]);
     $pass2 = test_input($_REQUEST["pass2"]);
     if($pass1==$pass2) {
        $passhash=password_hash(test_input($pass1),PASSWORD_DEFAULT);

       //old user want to regist with password and login
       if((mysqli_num_rows($result)==1)&&($user_status==0)) {
         $sql=sprintf("UPDATE customers SET name='%s',password='%s',status=1, registDate=now() WHERE customerId=".$user_id,
            mysqli_real_escape_string($conn,$name),mysqli_real_escape_string($conn,$passhash));
         $update=mysqli_query($conn,$sql);
         if($update==false) die("Could not query database to register 1");
         else {
           mysqli_close($conn);
           $_SESSION["userId"] = $user_id;
           $_SESSION["user"] = $name;
           $action = "login";
           require "activity.php";
           echo 0;
           exit();
         }
       }

      //new user first time login and regist, save user info in database  
      elseif(mysqli_num_rows($result)==0) {
        $sql=sprintf("INSERT INTO customers(name,cellphone,password,status,registDate) VALUES('%s','%s','%s',1,now())",
            mysqli_real_escape_string($conn,$name),mysqli_real_escape_string($conn,$cellphone),
            mysqli_real_escape_string($conn,$passhash));
        $insert=mysqli_query($conn,$sql);
        if($insert==false) die("Could not query database to register 2");
        else {
          mysqli_close($conn);
          $last_id = mysqli_insert_id($conn);
          $_SESSION["user"] = $name;
          $_SESSION["userId"] = $last_id;
          $action = "login";
          require "activity.php";
          echo 0;
          exit();
        }
      }
      else { 
       echo 1; //more than one line or already register, no need password
       exit();
      }
    }
    else {
       echo 2; //pass1 and pass2 not equal to each other
       exit();
    }
  }

  else { //password not set

    //new user want to login but not regist
    if(mysqli_num_rows($result)==0) {
      $sql=sprintf("INSERT INTO customers(name,cellphone,status,registDate) VALUES('%s','%s',0,now())",
            mysqli_real_escape_string($conn,$name),mysqli_real_escape_string($conn,$cellphone));
      $login=mysqli_query($conn,$sql);
      if($login==false) die("Could not query database to register 3");
      else {
        mysqli_close($conn);
        $last_id = mysqli_insert_id($conn);
        $_SESSION["user"] = $name;
        $_SESSION["userId"] = $last_id;
        $action = "login";
        require "activity.php";
        echo 0;
        exit();
      }
    } 

   //registed user use cellphone and code to login
    elseif(mysqli_num_rows($result)==1) {
      mysqli_close($conn);
      $_SESSION["user"] = $name;
      $_SESSION["userId"] = $user_id;
      $action = "login";
      require "activity.php";
      echo 0;
      exit();
    }

    else {
      echo 1; //more than 1 user same cellphone
      exit();
    }

  }

function test_input($data){
  $data=trim($data);
  $data=stripslashes($data);
  $data=htmlspecialchars($data);
  return $data;
} 

?>
