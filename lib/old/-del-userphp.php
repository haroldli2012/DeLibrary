<?php

  $search = test_input($_REQUEST["seek"]);

   // database link
   require "linktodb_PDO.php";

   // find user information by user name
     try {
       $sql="SELECT * FROM customers WHERE name = '".$search."'or cellphone = '".$search."'";
       $stmt=$conn->prepare($sql);
       $stmt->execute();
       $customer=$stmt->fetchAll();
     }
     catch(PDOException $e) {
       echo "Error:".$e->getMessage();
     }
        
     if(count($customer)==1) {
            $customerId=$customer[0]["customerId"];
            $name=$customer[0]["name"];
            $password=$customer[0]["password"];
            $cellphone=$customer[0]["cellphone"];
            $email=$customer[0]["email"];
            $date=$customer[0]["date"];
            $helexie=$customer[0]["xiebi"];
            $credit=$customer[0]["credit"];
            $address=$customer[0]["address"];
         }

     //find all shares by the customer with customer Id             
     try {
              $sql="SELECT * FROM shares WHERE customerId= '".$customerId."'" ;
              $stmt=$conn->prepare($sql);
              $stmt->execute();
              $shares=$stmt->fetchAll();
       }
      catch(PDOException $e) {
            echo "Error:".$e->getMessage();
       }

     //find all shareId applyed by the customer with the Id
        try {
              $sql="SELECT shareId FROM requests WHERE customerId= '".$customerId."'" ;
              $stmt=$conn->prepare($sql);
              $stmt->execute();
              $applyShareIds=$stmt->fetchAll();
         }
       catch(PDOException $e) {
            echo "Error:".$e->getMessage();
         }
 ?>

           <div class="baseinfo-detail">
              <p class="my-name">用户名称:<span><?php echo $name;?></span></p>
              <p class="my-password">密码:<button>修改密码</button></p>
              <p class="my-cellphone">手机号码:<span><?php echo $cellphone;?></span></p>
              <p class="my-address">地址:<span><?php echo $address;?></span></p>
              <p class="my-registdate">开始日期:<span><?php echo $date;?></span></p>
           </div>

           <div class="panxie-detail">
              <p class="my-panxie">我的乐蟹:<span><?php echo $helexie;?></span>只</p>
              <p class="my-limit">我的信用:<span><?php echo $credit;?></span>只乐蟹</p>
              <p class="my-spare">可用额度:<span><?php echo $helexie + $credit;?></span>只乐蟹</p>
           </div>

           <div class="history-detail">
              <p class="my-share">我的分享</p>
<?php 
       //display all shares customer distribute
       if(count($shares)>0) {
          foreach($shares as $share )
           {                 
             $title=$share["shareTitle"];
             $detail=$share["shareDetail"];
             $enddate=$share["endDate"];
             $value=$share["shareValue"];
             $city=$share["city"];
             $country=$share["country"];
             $contactor=$share["contactor"];
             $shareId=$share["shareId"];
?>

            <div class="title"><a href="display.php?shareId=<?php echo $shareId;?>" target="_blank"><?php echo $title;?></a></div>
            <div class="value"><span class="item">分享底价：</span><?php echo $value;?><span>蟹币</span></div>
            <div class="detail"><span class="item">详细介绍：</span><?php echo $detail;?></div>
            <div class="date"><span class="item">截至日期：</span><?php echo $enddate;?> </div>
            <div class="addr"><span class="item">地址：</span><?php echo $city.$country;?></div>

<?php 
          }
       }
?>

       <p class="my-shared">我申请的分享</p>
<?php    
      //display all shares applyed by customer
       if(count($applyShareIds)>0) {
         foreach($applyShareIds as $shareid) {
            try {
              $sql="SELECT * FROM shares WHERE shareId= '".$shareid[0]."'" ;
              $stmt=$conn->prepare($sql);
              $stmt->execute();
              $share=$stmt->fetchAll();
            }
            catch(PDOException $e) {
            echo "Error:".$e->getMessage();
            }
           if(count($share)==1) {
             $shareId=$share[0]["shareId"];
             $title=$share[0]["shareTitle"];
             $detail=$share[0]["shareDetail"];
             $enddate=$share[0]["endDate"];
             $value=$share[0]["shareValue"];
             $city=$share[0]["city"];
             $country=$share[0]["country"];
             $contactor=$share[0]["contactor"];
?>
            <div class="title"><a href="display.php?shareId=<?php echo $shareId;?>" target="_blank"><?php echo $title;?></a></div>
            <div class="value"><span class="item">分享底价：</span><?php echo $value;?><span>蟹币</span></div>
            <div class="detail"><span class="item">详细介绍：</span><?php echo $detail;?></div>
            <div class="date"><span class="item">截至日期：</span><?php echo $enddate;?> </div>
            <div class="addr"><span class="item">地址：</span><?php echo $city.$country;?></div>

<?php        
           }
         }
      }

?>
           <p class="my-others">其它活动</p>
    </div>



  <?php

function test_input($data){
  $data=trim($data);
  $data=stripslashes($data);
  $data=htmlspecialchars($data);
  return $data;
} 

  ?>




