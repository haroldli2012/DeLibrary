<?php

//find if name or cellphone is proper to be updated
   
   $value = test_input($_REQUEST["vali"]);
   $customerId = $_REQUEST["cid"];

    require "linktodb_PDO.php";

      try {
        $sql="SELECT * FROM customers WHERE (cellphone = '".$value."' OR name = '".$value."') AND customerId <> '".$customerId."'";
        $stmt=$conn->prepare($sql);
        $stmt->execute();
        $customer=$stmt->fetchAll();
     }
     catch(PDOException $e) {
       echo "Error:".$e->getMessage();
     }

    if(count($customer)>0)
        echo 1;
    else echo 0;

function test_input($data){
  $data=trim($data);
  $data=stripslashes($data);
  $data=htmlspecialchars($data);
  return $data;
} 

  $conn = null;

?>
