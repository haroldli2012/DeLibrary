<?php
   session_start();
   $id=$_REQUEST["requestId"];

    require "linktodb_PDO.php";

    //get the shareId from the requestId
    $sql="SELECT shareId, gold FROM requests WHERE requestId = ".$id;
    $stmt=$conn->prepare($sql);
    $stmt->execute();
    $request=$stmt->fetchAll();
    $shareId=$request[0][0];
    $gold=$request[0][1];

    //find all request for this shareId
    $sql="SELECT requests.requestId, requests.customerId, requests.gold, customers.xiebi FROM requests LEFT JOIN customers ON requests.customerId=customers.customerId AND requests.shareId = ".$shareId;
    $stmt=$conn->prepare($sql);
    $stmt->execute();
    $requests=$stmt->fetchAll();

    //find customerId for this shareId
  /*  $sql="SELECT customerId FROM shares WHERE shareId = '".$shareId."'";
    $stmt=$conn->prepare($sql);
    $stmt->execute();
    $customerId=$stmt->fetchAll();
    $cid=$customerId[0][0]; */

    //find customerId's xiebi for this shareId
  /*  $sql="SELECT xiebi FROM customers WHERE customerId = '".$customerId[0]["customerId"]."'";
    $stmt=$conn->prepare($sql);
    $stmt->execute();
    $xiebi=$stmt->fetchAll();
    $lexie=$xiebi[0]["xiebi"]; */

    try {
    $sql="SELECT customers.customerId, customers.xiebi FROM customers INNER JOIN shares ON customers.customerId=shares.customerId AND shares.shareId = ".$shareId;
    $stmt=$conn->prepare($sql);
    $stmt->execute();
    $sharer=$stmt->fetchAll();
    $cid=$sharer[0]["customerId"];
    $lexie=$sharer[0]["xiebi"];
     }
    catch(PDOException $e) {
       echo "Error:".$e->getMessage();
     } 

    try {
       $conn->beginTransaction();

       //udpate request status
       foreach($requests as $request) 
        {      
          if($request["requestId"] == $id)
             $conn->exec("UPDATE requests SET status= 1 WHERE requestId = ".$request["requestId"]);
          else {

             $conn->exec("UPDATE requests SET status= 4 WHERE requestId=".$request["requestId"]);
             $conn->exec("UPDATE customers SET xiebi= ".($request["gold"]+$request["xiebi"])." WHERE customerId='".$request["customerId"]."'");

           }
         }

       //udpate shareId customer xiebi numbers
       $conn->exec("UPDATE customers SET xiebi=".($gold + $lexie)." WHERE customerId=".$cid);
       $conn->exec("UPDATE shares SET status= 1 WHERE shareId=".$shareId);
       $conn->commit();
?>

<script>
$(":radio").attr("disabled","true");
$("#share").attr("disabled","true");
</script>

<?php
      $action = "approval";
      require "activity.php";
      echo $id."谢谢,成功分享!";
     }
     catch(PDOException $e)  {
        $conn->rollback();
        echo "Connection failed:".$e->getMessage();
      }


  $conn = null;

?>



