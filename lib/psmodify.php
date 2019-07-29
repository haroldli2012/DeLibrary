<?php

//find if password is proper to be updated
   
   $customerId = $_REQUEST["cid"];
   $oldps = test_input($_REQUEST["ops"]);
   $newps1 = test_input($_REQUEST["np1"]);
   $newps2 = test_input($_REQUEST["np2"]);

    require "linktodb_PDO.php";

   if(empty($oldps)||empty($newps1)||empty($newps2))
      echo 0;
   elseif(!preg_match("/[a-zA-Z0-9]{8,}/",$newps1))
      echo 2;
   elseif($newps1!=$newps2)
      echo 3;
   else {
      try {
        $sql="SELECT password FROM customers WHERE customerId = '".$customerId."'";
        $stmt=$conn->prepare($sql);
        $stmt->execute();
        $password=$stmt->fetchAll();
       }
      catch(PDOException $e) {
        echo "Error:".$e->getMessage();
       }
      if(password_verify($oldps,$password[0][0])) {
        $newpassword = password_hash($newps2,PASSWORD_DEFAULT);
        try {
          $sql="UPDATE customers SET password = '".$newpassword."' WHERE customerId = '".$customerId."'";
          $conn->exec($sql);
          echo 1;
    $action = "personUpdate";
    require "activity.php";
         }
        catch(PDOException $e) {
          echo "Error:".$e->getMessage();
         }
      }
      else echo 0;
    }

function test_input($data){
  $data=trim($data);
  $data=stripslashes($data);
  $data=htmlspecialchars($data);
  return $data;
} 

 $conn = null;
?>
