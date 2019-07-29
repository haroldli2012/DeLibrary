<?php
//record customer activity into database, must login, otherwise quit

  if(isset($_SESSION["userId"])) 
    $userId = $_SESSION["userId"];
  else exit();

  if(isset($_REQUEST["act"]))
    $action = $_REQUEST["act"];

   $date = date("Y-m-d");

    require "linktodb_PDO.php";

  try {
        $sql="SELECT * FROM activity WHERE date = '".$date."' AND customerId = ".$userId;
        $stmt=$conn->prepare($sql);
        $stmt->execute();
        $result=$stmt->fetchAll();
  }
  catch(PDOException $e) {
       echo "Error:".$e->getMessage();
  }

   if(count($result)>0) {
     $_aId = $result[0]["activityId"];
     /*$_login = $result[0]["login"];
     $_search = $result[0]["search"];
     $_share = $result[0]["share"];
     $_apply = $result[0]["apply"];
     $_shareupdate = $result[0]["shareUpdate"];
     $_personupdate = $result[0]["personUpdate"];
     $_requestupdate =$result[0]["requestUpdate"];
     $_approval = $result[0]["approval"];
     $_logout = $result[0]["logout"];*/

     $number = $result[0][$action]+1;

       try {
        $sql="UPDATE activity SET ".$action." = ".$number." WHERE activityId = ".$_aId;
        $stmt=$conn->prepare($sql);
        $stmt->execute();
       }
       catch(PDOException $e) {
         echo "Error:".$e->getMessage();
       }

   }
   else {
       try {
        $sql="INSERT INTO activity (customerId, date, ".$action.") VALUES (".$userId.",'".$date."', 1)";
        $stmt=$conn->prepare($sql);
        $stmt->execute();
        }
       catch(PDOException $e) {
         echo "Error:".$e->getMessage();
       }
   }

  $conn = null;
?>
