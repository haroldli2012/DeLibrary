<?php

//record customer activity into database, must login, otherwise quit

  if(isset($_SESSION["userId"])) 
    $userId = $_SESSION["userId"];
  else exit();

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
     $_SESSION["aId"] = $result[0]["activityId"];
     $_SESSION["login"] = $result[0]["login"];
     $_SESSION["search"] = $result[0]["search"];
     $_SESSION["share"] = $result[0]["share"];
     $_SESSION["apply"] = $result[0]["apply"];
     $_SESSION["shareupdate"] = $result[0]["shareUpdate"];
     $_SESSION["personupdate"] = $result[0]["personUpdate"];
     $_SESSION["requestupdate"] =$result[0]["requestUpdate"];
     $_SESSION["approval"] = $result[0]["approval"];
     $_SESSION["logout"] = $result[0]["logout"];
   }
   else {
     $_SESSION["aId"] = 0;
     $_SESSION["login"] = 0;
     $_SESSION["search"] = 0;
     $_SESSION["share"] = 0;
     $_SESSION["apply"] = 0;
     $_SESSION["shareupdate"] = 0;
     $_SESSION["personupdate"] = 0;
     $_SESSION["requestupdate"] = 0;
     $_SESSION["approval"] = 0;
     $_SESSION["logout"] = 0;
   }
?>
