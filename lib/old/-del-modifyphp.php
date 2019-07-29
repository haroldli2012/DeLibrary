<?php
  $id=test_input($_REQUEST["sId"]);
  $lexie = test_input($_REQUEST["xiebi"]);
  $detail = test_input($_REQUEST["sDetail"]);
  $title = test_input($_REQUEST["sTitle"]);
  $address = test_input($_REQUEST["sAddr"]);
  $date = test_input($_REQUEST["sDate"]);

    require "linktodb_PDO.php";

      try {

       $sql = "UPDATE shares SET sharevalue = ".$lexie." , shareDetail = '".$detail."', shareTitle = '".$title."' , country = '".$address."',endDate = '".$date."' WHERE shareId = ".$id;
       
        $conn->exec($sql);

       echo "更新成功!";

     }
     catch(PDOException $e)  {
        echo "Connection failed:".$e->getMessage();
      }

    
function test_input($data){
  $data=trim($data);
  $data=stripslashes($data);
  $data=htmlspecialchars($data);
  return $data;
} 

?>