<?php
    session_start();
  $path_req = $_SERVER["DOCUMENT_ROOT"];

    //get user info by session or cookie
    if(isset($_SESSION["user"])) {
         $user_name = $_SESSION["user"];
         $user_id = $_SESSION["userId"];
      }
    elseif (isset($_COOKIE["user"])) {
        $user_name = test_input($_COOKIE["user"]);
        $pass = test_input($_COOKIE["pass"]);
        require $path_req."/lib/loginByCookie.php";
       }
    else {
       $user_name = "";
       $user_id = 0;
     }

   if ($user_id==0)
    {
      header("Location:index.php");
      exit();
     }

   require $path_req."/lib/linktodb_PDO.php";

   $email = $birthday = $history = "";
   $date = $city = $country = $country = $community = $wechat = $gender = "";
   $helexie = $credit = "";

   // find user information by user name
     try {
       $sql="SELECT * FROM customers WHERE customerId = ".$user_id;
       $stmt=$conn->prepare($sql);
       $stmt->execute();
       $customer=$stmt->fetchAll();
     }
     catch(PDOException $e) {
       echo "Error:".$e->getMessage();
     }
        
     if(count($customer)==1) {
            
            $name=$customer[0]["name"];
            $cellphone=$customer[0]["cellphone"];
            $email=$customer[0]["email"];
            $date=$customer[0]["registDate"];
            $helexie=$customer[0]["xiebi"];
            $credit=$customer[0]["credit"];
            $city=$customer[0]["city"];
            $country=$customer[0]["country"];
            $community=$customer[0]["community"];
            $wechat= $customer[0]["wechat"];
            $birthday = $customer[0]["birthday"];
            $gender = $customer[0]["gender"];
            $history = $customer[0]["history"];
            $address=$city.$country.$community;

            
            $date1=date_create($history);
            $today = date_create(date("Y-m-d"));
            $interval = date_diff($date1,$today);
         }
     else {
         header("Location:index.html");
         exit();
        }

     //58 update personal info if there is post info
     $nameErr = $cellphoneErr = $emailErr = $commErr = $updateErr = "";
     if($_SERVER["REQUEST_METHOD"]=="POST") {
       $ok_update = 1;
       $complete = 0;
       $history = "";

       if(empty($_POST["username"])) {
         $nameErr = "用户名不能为空";
         $ok_update = 0;
        } else {
         $newname = test_input($_POST["username"]);
         if(!preg_match("/[a-zA-Z0-9\u4e00-\u9fa5]{3,20}/",$newname)) {
            $nameErr = "用户名必须由3-20位字母,汉字或数字组成";
            $ok_update = 0;
           }
        }

       if(empty($_POST["cellphone"])) {
         $cellphoneErr = "手机号码不能为空";
         $ok_update = 0;
        } else {
         $newcellphone = test_input($_POST["cellphone"]);
         if(!preg_match("/^1(3[0-9]|4[57]|5[0-35-9]|70|8[0-9])\d{8}$/",$newcellphone)) {
            $cellphoneErr = "请输入有效的手机号码";
            $ok_update = 0;
           }
        }

       if(!empty($_POST["city"])) {
         $newcity = test_input($_POST["city"]);
        }

       if(!empty($_POST["country"])) {
         $newcountry = test_input($_POST["country"]);
         $complete += 0.1;
        }

       if(!empty($_POST["community"])) {
         $newcomm = test_input($_POST["community"]);
         
         if(!preg_match("/".$community."/",$newcomm)) {
           $history = date("Y-m-d");
          }
         if(!preg_match("/[a-zA-Z0-9\u4e00-\u9fa5]{3,10}/",$comm)) {
            $commErr = "社区名称必须由3-10位字母,汉字或数字组成";
            $ok_update = 0;
           }
         else $complete += 0.35;
        }

       if(!empty($_POST["email"])) {
         $newemail = test_input($_POST["email"]);
         if(filter_var($newemail, FILTER_VALIDATE_EMAIL)===false) {
            $emailErr = "电子邮件格式输入错误";
            $ok_update = 0;
          }
         else $complete += 0.1;
        }

       if(!empty($_POST["gender"])) {
         $newgender = test_input($_POST["gender"]);
         $complete += 0.05;
        }

       if(!empty($_POST["birthday"])) {
         $newbirthday = test_input($_POST["birthday"]);
         $complete += 0.05;
        }

       if(!empty($_POST["wechat"])) {
         $newwechat = test_input($_POST["wechat"]);
         $complete += 0.35;
        }
      
       //credit amount caculation
       $newcredit = 100*$complete;

       if($ok_update==1) {
        try {
           $sql = "UPDATE customers SET name = '".$newname."', cellphone = '".$newcellphone."', email = '".$newemail."', gender = '".$newgender."', city = '".$newcity."', country = '".$newcountry."', community = '".$newcomm."', history = '".$history."', completelevel = ".$complete.", wechat = '".$newwechat."', birthday = '".$newbirthday."', credit = ".$newcredit." WHERE customerId=".$user_id;
       
        $conn->exec($sql);

            $name=$newname;
            $cellphone=$newcellphone;
            $email=$newemail;
            $credit=$newcredit;
            $city=$newcity;
            $country=$newcountry;
            $community=$newcomm;
            $wechat= $newwechat;
            $birthday = $newbirthday;
            $gender = $newgender;
            $address=$city.$country.$community;

    $action = "personUpdate";
    require $path_req."/lib/activity.php";

        }
       catch(PDOException $e)  {
        echo "Connection failed:".$e->getMessage();
        }
       }


     }

     //find all shares by the customer with customer Id             
     try {
              $sql="SELECT * FROM shares WHERE customerId= '".$user_id."'" ;
              $stmt=$conn->prepare($sql);
              $stmt->execute();
              $shares=$stmt->fetchAll();
       }
      catch(PDOException $e) {
            echo "Error:".$e->getMessage();
       }

     //find all shareId applyed by the customer with the Id
        try {
              $sql="SELECT shareId FROM requests WHERE customerId= '".$user_id."'" ;
              $stmt=$conn->prepare($sql);
              $stmt->execute();
              $applyShareIds=$stmt->fetchAll();
         }
       catch(PDOException $e) {
            echo "Error:".$e->getMessage();
         }

function test_input($data){
  $data=trim($data);
  $data=stripslashes($data);
  $data=htmlspecialchars($data);
  return $data;
} 

?>


<!DOCTYPE html>
<html xmlns:wb="http://open.weibo.com/wb">
  <head>
      <title>和乐分享</title>
      <meta name="viewport" content="width=device-width,initial-scale=1.0">
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <meta name="keywords" content="分享,share,共享">
      <link href="/etc/navtop.css" rel="stylesheet">
      <link href="/etc/common.css" rel="stylesheet">
      <link href="/etc/usercss.css" rel="stylesheet">
      <link rel="stylesheet" href="/etc/icon/iconfont.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <script src="/lib/jquery-1.11.3.min.js"></script>
      <script src="http://tjs.sjs.sinajs.cn/open/api/js/wb.js?appkey=2076762035" type="text/javascript" charset="utf-8"></script>
      <script type="text/javascript" src="http://qzonestyle.gtimg.cn/qzone/openapi/qc_loader.js" data-appid="101382672" data-redirecturi="http://www.heleshare.com/user.php" charset="utf-8" data-callback="true"></script>
  </head>

   <body onload="checkCookie()">

    <?php require $path_req."/lib/topnav.php"; ?>

      <div class="col-8 row">
        <div>
          <h2>用户中心</h2>
        </div>

        <div class="sidenav2">
           <span class="label baseinfo">基本信息</span><span class="label panxie">我的乐蟹</span><span class="label history">我的活动</span>
        </div>

        <div class="col-2 sidenav">
           <p class="label baseinfo">基本信息</p>
           <p class="label panxie">我的乐蟹</p>
           <p class="label history">我的活动</p>
        </div>

        <div class="col-9 main-window">


         <div class="baseinfo-detail">
            <div class="personal-info">
              <p class="my-name">用户名称:<span><?php echo $name;?></span></p>
              <p class="my-cellphone">手机号码:<span><?php echo $cellphone;?></span></p>
              <p class="my-address">地址:<span><?php echo $address;?></span></p>
              <p class="my-registdate">注册日期:<span><?php echo $date;?></span></p>
              <p class="my-email">电子邮箱:<span><?php echo $email;?></span></p>
              <p class="my-gender">性别:<span><?php echo $gender;?></span></p>
              <p class="my-birthday">出生日期:<span><?php echo $birthday;?></span></p>
              <button id="modify-info">修改个人信息</button><br/>
              <span>提示：完善个人信息可获得乐蟹信用额度。</span>
            </div>

           <div class="modify-personal-info">

             <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">

             <table>
             <tr>
               <td>用户名称</td>
               <td>
                  <input name="username" type="text" flag="0" required
                   pattern="[a-zA-Z0-9\u4e00-\u9fa5]{3,20}" 
                   title="3-20位字母,汉字或数字组成" onblur="inputCheck(this)"
                   value="<?php echo $name;?>">
               </td>
             </tr>
             <tr><td></td><td><?php echo $nameErr;?></td></tr>

             <tr>
               <td>手机号码</td>
               <td>
                 <input name="cellphone" type="text"  flag="0"  required
                   pattern="^1(3[0-9]|4[57]|5[0-35-9]|70|8[0-9])\d{8}$"
                   title="请输入11位手机号码"  onblur="inputCheck(this)"
                   value="<?php echo $cellphone;?>">
               </td>
             </tr>
             <tr><td></td><td><?php echo $cellphoneErr;?></td></tr>


             <tr>
                <td>城市</td>
                <td>
                  <input type="text" name="city" value="<?php echo $city;?>"
                   onchange="findctry(this)" onkeyup="cityhint(this)">
                  <div id="cityhint"><div>
                </td>
             </tr>
             <tr><td></td><td></td></tr>

             <tr>
                <td>区县</td>
                <td>
                  <select id="country" name="country"><option>请选择区域</option></select>
                </td>
             </tr>
             <tr><td></td><td></td></tr>

             <tr>
               <td>社区</td>
               <td>
                 <input type="text" name="community"
                   pattern="[a-zA-Z0-9\u4e00-\u9fa5]{3,10}" 
                   title="3-10位字母,汉字或数字组成" onblur="inputCheck(this)"
                  value="<?php echo $community;?>"
                  <?php if($interval->format("%R%a")>180) echo "disabled";?>>
               </td>
             </tr>
             <tr><td></td><td><?php echo $commErr;?></td></tr>

             <tr>
               <td>微信关注</td>
               <td>
                 <input type="text" name="wechat" value="<?php echo $wechat;?>">
               </td>
             </tr>
             <tr><td></td><td></td></tr>

             <tr>
                <td>电子邮箱</td>
                <td>
                  <input name="email" type="email"
                   pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$"
                   title="请输入有效email地址" onblur="inputCheck(this)"
                    value="<?php echo $email;?>">
                </td>
             </tr>
             <tr><td></td><td></td></tr>

             <tr>
                <td>性别</td>
                <td>
                  男<input type="radio" name="gender" value="Male" <?php if($gender=="Male") echo "checked";?>>
                  女<input type="radio" name="gender" value="Female" <?php if($gender=="Female") echo "checked";?>>
                  其它<input type="radio" name="gender" value="Others" <?php if($gender=="Others") echo "checked";?>>
                </td>
             </tr>

             <tr>
                <td>出生日期</td>
                <td>
                  <input type="date" name="birthday" value="<?php echo $birthday;?>">
                </td>
             </tr>
             <tr><td></td><td></td></tr>

             <tr><td>密码</td><td><button type="button" id="modify-pswd">修改密码</button></td></tr>

           </table>

           <input type="hidden" id="countryselect" value="<?php echo $country;?>">
           <input type="hidden" name="cid" value="<?php echo $user_id;?>">
           <br/>

           <input type="submit" id="ok-modify" value="完成修改">
           <span id="cancell-modify">取消修改</span>
           </form>
           </div>

            <div class="modify-password">
              <table>
               <tr>      
                 <td>原始密码</td>
                 <td>
                   <input name="oldpswd" type="password" required
                    pattern="[a-zA-Z0-9]{8,}" title="密码不正确!"
                    onblur="psCheck(this)">
                 </td>
               </tr>
               <tr><td></td><td></td></tr>
               <tr>
                  <td>新密码</td>
                  <td><input type="password" name="newpswd" required
                    pattern="[a-zA-Z0-9]{8,}" title="8位以上的字母或数字"
                    onblur="psCheck(this)">
                 </td>
               </tr>
               <tr><td></td><td></td></tr>
               <tr>
                 <td>密码确认</td>
                 <td><input type="password" name="repeatpswd" required
                    title="重复输入确认密码"
                    onkeyup="psCheck(this)">
                 </td>
                </tr>
               <tr><td></td><td></td></tr>
            </table>

              <p id="pswd-remind"></p>

              <button type="button" id="pswd-kick">完成修改</button>
              <span id="pswd-cancell">取消修改</span>
              <span id="pswd-forget">忘记密码</span>
           </div>

         </div>


           <div class="panxie-detail">
              <p class="my-panxie">我的乐蟹:<span><?php echo $helexie;?></span>只</p>
              <p class="my-limit">我的信用:<span><?php echo $credit;?></span>只乐蟹</p>
              <p class="my-spare">可用额度:<span><?php echo $helexie + $credit;?></span>只乐蟹</p>
           </div>


           <div class="history-detail">
              <h5 class="my-share">我的分享</h5>
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
            <div class="value"><span class="item">分享底价：</span><?php echo $value;?><span>乐蟹</span></div>
            <div class="detail"><span class="item">详细介绍：</span><?php echo $detail;?></div>
            <div class="date"><span class="item">截至日期：</span><?php echo $enddate;?> </div>
            <div class="addr"><span class="item">地址：</span><?php echo $city.$country;?></div>

<?php 
          }
       }
?>

       <h5 class="my-shared">我申请的分享</h5>
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
            <div class="value"><span class="item">分享底价：</span><?php echo $value;?><span>乐蟹</span></div>
            <div class="detail"><span class="item">详细介绍：</span><?php echo $detail;?></div>
            <div class="date"><span class="item">截至日期：</span><?php echo $enddate;?> </div>
            <div class="addr"><span class="item">地址：</span><?php echo $city.$country;?></div>

<?php        
           }
         }
      }

?>
           <h5 class="my-others">其它活动</h5>
      </div>

    </div>

      </div>

        <div id="footer"></div>


<?php
      require $path_req."/lib/logwindow.php"
?>

   <script src="/lib/nav.js"></script>
   <script src="/lib/logo.js"></script>
   <script src="/lib/userjs.js"></script>

<?php $conn = null; ?>

  </body>
</html>  