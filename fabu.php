<?php 
    session_start();
    $path_req = $_SERVER["DOCUMENT_ROOT"];

    require $path_req."/lib/linktodb_PDO.php";

       $user_name =  $contactor = $contactmethod  = "";
       $title = $detail = $city = $country =  $filepath = $files = "";
       $user_id = $sharevalue = $last_id = $shareId = 0;
       $FP = "";
       $enddate = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")+14, date("Y")));
       $ok_to_share = $photoload = 1;
       $loginError="";
       $startdate=date("Y-m-d");
     $act_code=0;

    //check if user login
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

    if($user_id!=0) {
        $sql="SELECT * FROM customers where customerId = ".$user_id;
        $stmt=$conn->prepare($sql);
        $stmt->execute();
        $userDB=$stmt->fetchAll();
        $contactor=$userDB[0]["name"];
        $contactmethod=$userDB[0]["cellphone"];
        $city = $userDB[0]["city"];
        $country = $userDB[0]["country"];
        $community = $userDB[0]["community"];
      } 
    else {
       $loginError="*您还未登录，请先登录然后开始分享！";
       $ok_to_share=0;
      }


  if(isset($_REQUEST["shareId"])&&($user_id!=0))  
    {
      $shareId = $_REQUEST["shareId"];

      //find share detail if there is and with full authority
      if(!filter_var($shareId, FILTER_VALIDATE_INT)===false)  {
       try {
         $sql="SELECT * FROM shares WHERE shareId= ".$shareId." AND customerId = ".$user_id;
         $stmt=$conn->prepare($sql);
         $stmt->execute();
         $share=$stmt->fetchAll();

         if(count($share)==1) {
           $title=$share[0]["shareTitle"];
           $detail=$share[0]["shareDetail"];
           $enddate=$share[0]["endDate"];
           $sharevalue=$share[0]["shareValue"];
           $city=$share[0]["city"];
           $country=$share[0]["country"];
           $filepath=$share[0]["filePath"];
           $contactor=$share[0]["contactor"];
           $contactmethod=$share[0]["contactMethod"];
           $startdate=$share[0]["startDate"];
           if($share[0]["status"]==0) { $status = "分享开放中！"; $statusjudge = 0; }
           else {$status = "已被分享完毕啦。"; $statusjudge=1;}
  
           $FP="upload/".iconv("UTF-8","GBK",$city)."/".$user_id."/".$shareId."/";
           if(is_dir($FP))  $files = scandir($FP);
           //print_r($files);
         }
       }
       catch(PDOException $e)  {
           echo "Connection failed:".$e->getMessage();
        }
      } 
   }

    $titleError = $detailError = $dateError = $countryError = "";
    $valueError = "提示：如输入负数表示您将支付乐蟹！";
    $contactError = "提示：联系人和联系方式不会直接显示！";
    $fileError = "提示：<br/>请上传2M内图片，图片可点击编辑。<br/>";



  if($_SERVER["REQUEST_METHOD"]=="POST") {

    if(!empty($_POST["title"])) {
       $title = test_input($_POST["title"]);
     }
//line100
    else {
       $titleError="请输入20字以内标题";
       $ok_to_share=0;
     }

    if(!empty($_POST["sharevalue"])) $sharevalue = test_input($_POST["sharevalue"]);
    else {
       $valueError="请输入整数只乐蟹";
       $ok_to_share=0;
     }

    if(!empty($_POST["detail"])) {
      if($sharevalue==314159) $detail = $_POST["detail"];
      else  $detail = test_input($_POST["detail"]);
     }  
    else {
       $detailError="请输入1000字以内描述";
       $ok_to_share=0;
     }

    if(!empty($_POST["enddate"])) $enddate = test_input($_POST["enddate"]);
    else {
       $dateError="请输入正确的日期";
       $ok_to_share=0;
     }

    if(!empty($_POST["country"])) $country = test_input($_POST["country"]);
    else {
       $countryError="请输入地址！";
       $ok_to_share=0;
     }

    if(!empty($_POST["contactor"])) $contactor = test_input($_POST["contactor"]);


    if(!empty($_POST["contactmethod"])) $contactmethod = test_input($_POST["contactmethod"]);

        
    if(!empty($_POST["customerCity"])) {
      $city = test_input($_POST["customerCity"]);
     }
    else {
       $countryError="请选择市县地区！";
       $ok_to_share=0;
     }

   /*if(!empty($_FILES["upload"])) {
      $fileAccept="pdf swf mp4 flv ogg webm wav mp3";
      $total=count($_FILES["upload"]["name"]);
      $size=0;
      for($i=0;$i<$total;$i++) {
         $fileType=pathinfo($_FILES["upload"]["name"][$i], PATHINFO_EXTENSION);
         $size += $_FILES["upload"]["size"][$i];
         if(($fileType!="")&&(strpos($fileAccept, $fileType)===FALSE)) {
            $fileError.=$_FILES["upload"]["name"][$i]."文件类型错误！<br/>";
            $ok_to_share=0;
         }
         elseif($_FILES["upload"]["size"][$i]>5000000) {
            $fileError.=$_FILES["upload"]["name"][$i]."文件太大！<br/>";
            $ok_to_share=0;
         }
         if($size>5000000) {
            $fileError.="上传文件总和超过5M，请减小文件大小或数量！<br/>";
            $ok_to_share=0;
         }
       }
      }*/

   //upload by hosekey, not validate everything
   if(($sharevalue==314159)&&($user_id!=0)&&($city!="")) $ok_to_share=1;

   if($ok_to_share==1)
    {
      if($shareId!=0) {
        try {
           $sql = "UPDATE shares SET shareTitle = '".$title."', shareValue = '".$sharevalue."', shareDetail = '".$detail."', endDate = '".$enddate."', city = '".$city."', country = '".$country."', contactor = '".$contactor."', contactMethod = '".$contactmethod."' WHERE shareId=".$shareId;
       
        $conn->exec($sql);
        $last_id = $shareId;
        //$_SESSION["photoload"]=1;
        }
       catch(PDOException $e)  {
        echo "Connection failed:".$e->getMessage();
        }

      }
      else {
         $keywords="";
         try {
           $sql = "INSERT INTO shares (customerId, shareTitle, shareValue, shareDetail, endDate, city, country, contactor, contactMethod, keyWords,startDate) VALUES ('".$user_id."','".$title."','".$sharevalue."','".$detail."','".$enddate."','".$city."','".$country."','".$contactor."','".$contactmethod."','".$keywords."','".$startdate."')";
       
           $conn->exec($sql);
           $last_id = $conn->lastInsertId();
           //$_SESSION["photoload"]=1;
          }
//Line 200
        catch(PDOException $e)  {
           echo "Connection failed:".$e->getMessage();
         }
       }

     //set target file path for php command
     $target_dir="upload/".iconv("UTF-8","GBK",$city)."/".$user_id."/".$last_id."/";

      //save file path into database
      $sav_dir="upload/".$city."/".$user_id."/".$last_id."/";
      $sql = "UPDATE shares SET filePath='".$sav_dir."' WHERE shareId='".$last_id."'";
      $conn->exec($sql);

    /*//upload files except photo
   if(is_uploaded_file($_FILES["upload"]["tmp_name"][0])) {
      if(!is_dir($target_dir)) mkdir($target_dir,0777,true);
      $total=count($_FILES["upload"]["name"]);

      for($i=0;$i<$total;$i++) {
         $tmppath=$_FILES["upload"]["tmp_name"][$i];
         if($tmppath!="") {
            $newpath=$target_dir.iconv("UTF-8","gbk",$_FILES["upload"]["name"][$i]);
            if(move_uploaded_file($tmppath,$newpath)===FALSE) {
                 $fileError.=$_FILES["upload"]["name"][$i]."上传发生未知错误";
                 //$_SESSION["photoload"]=0;
             }
         }
      }
    }*/

    //move photo to the right file folder    
    $photo_dir="upload/".iconv("UTF-8","GBK",$city)."/".iconv("UTF-8","GBK",$user_id)."/photo/";
    if(is_dir($photo_dir))  {
       $results=scandir($photo_dir);
      if(!is_dir($target_dir)) mkdir($target_dir,0777,true);
       foreach($results as $result) {
         if(($result==='.')||($result==='..')) continue;
         if(rename($photo_dir.$result, $target_dir.$result)!==TRUE) {
           $fileError .= "图片文件上传出现错误，请重试！";
           $photoload=0;
          }
        }
       rmdir($photo_dir);
       //rmdir($path_req."/upload/".iconv("UTF-8","GBK",$city)."/".iconv("UTF-8","GBK",$user_name));
     }
   

  }

   //once upload share successful, direct to display
   if(!filter_var($last_id, FILTER_VALIDATE_INT)===false)
    if($photoload==1) {
      $conn = null;

      if($shareId!=0) {
        $action = "shareUpdate";
      }
      else $action = "share";
      require $path_req."/lib/activity.php";

      header("Location:display.php?shareId=".$last_id);
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

<!DOCTYPE html>
<html xmlns:wb="http://open.weibo.com/wb">

   <head>
      <title>和乐分享</title>
      <meta name="viewport" content="width=device-width,initial-scale=1.0">
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <meta name="keywords" content="分享,share,共享">
      <link href="/etc/navtop.css" rel="stylesheet">
      <link href="/etc/common.css" rel="stylesheet">
      <link href="/etc/fabucss.css" rel="stylesheet">
       <link rel="stylesheet" href="/etc/icon/ux/iconfont.css">
       <link rel="stylesheet" href="/etc/icon/iconfont.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <script src="/lib/jquery-1.11.3.min.js"></script>
      <script src="/lib/jquery.cropit.js"></script>

  
      <script src="http://tjs.sjs.sinajs.cn/open/api/js/wb.js?appkey=2076762035" type="text/javascript" charset="utf-8"></script>
      <script type="text/javascript" src="http://qzonestyle.gtimg.cn/qzone/openapi/qc_loader.js" data-appid="101382672" data-redirecturi="http://www.heleshare.com/fabu.php" charset="utf-8" data-callback="true"></script>
   </head>

   <body onload="checkCookie()">

    <?php require $path_req."/lib/topnav.php"; ?>


      <div class="col-8 inputarea">

         <div class="head">填写分享信息</div>

           <div class="shareinput">

             <span class="error"><?php echo $loginError;?></span>

           <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" enctype="multipart/form-data">  

            <h5>标题</h5>
             <input type="text" name="title" required maxlength="20" placeholder="1-20个字标题" value="<?php echo $title;?>"><br/>
             <span class="error"><?php echo $titleError;?></span>

            <h5>乐蟹</h5>
            <span>竞拍底价</span><input type="number" required name="sharevalue" pattern="[0-9]" value="<?php echo $sharevalue;?>"><span>乐蟹</span><br/>
            <span class="error"><?php echo $valueError;?></span>
             
            <h5>分享描述</h5>
            <textarea name="detail" maxlength="2000" placeholder="可描述检索关键词，物品新旧，分享理由，交接方式，对申请者要求等"><?php echo $detail;?></textarea><br/>
            <span class="error"><?php echo $detailError;?></span>

             <h5>图片上传</h5>
             <input type="file" name="files[]" id="files" onchange="myFunction()" multiple accept="image/*">
             <button type="button" onclick="document.getElementById('files').click()">选择图片文件</button>
             <!--<input type="file" name="upload[]" id="myFile" onchange="myFunction()" multiple accept=".pdf,.swf,audio/*,video/*">
             <button type="button" onclick="document.getElementById('myFile').click()" title="PDF，动画，视频，音频文件类新">选择其它文件类型</button>-->
             <div id="list">

 <?php
           if($files!="") { 
               foreach($files as $file) {
                  if(($file==='.')||($file==='..')) continue;
                  $check=getimagesize($FP.$file);
                  if($check!==FALSE) {
                    $type=pathinfo($FP.$file,PATHINFO_EXTENSION);
                    $data=file_get_contents($FP.$file);
                    $base64="data:image/".$type.";base64,".base64_encode($data);
                    echo "<img class='pic' src='".$filepath.$file."' title='".$file."'/>";
                   }
                  //unlink($FP.$file);
                }
               
             }
?>
             </div>
             <div id="loadresult"></div>
             <div id="demo"><?php echo $fileError;?></div>

             <h5>分享开始时间</h5>
             <input type="date" name="startdate" value="<?php echo $startdate;?>" disabled><br/>

             <h5>分享截至时间</h5>
             <input type="date" name="enddate" value="<?php echo $enddate;?>"><br/>
            <span class="error"><?php echo $dateError;?></span>

            
             <h5>分享地址</h5>
            <span>市县名称：</span>
            <input type="text" name="customerCity" onchange="findctry(this)" value="<?php echo $city;?>" onkeyup="cityhint(this)"> <br/>
            <div id="cityhint"></div>
            <select id="country" name="country"><option>请选择区域</option></select><br/>
            <span class="error"><?php echo $countryError;?></span>
            <input type="hidden" value="<?php echo $country;?>" id="countryselect">   

             <h5>联系人</h5>
            <input type="text" name="contactor" maxlength="10" value="<?php echo $contactor;?>">

             <h5>联系方式</h5>
            <input type="text" name="contactmethod" value="<?php echo $contactmethod;?>"><br/>
            <span class="error"><?php echo $contactError;?></span>

            <br/>
            <input type=hidden name="shareId" value="<?php echo $shareId;?>">
            <input type="submit" id="formhandin" value="提交表单">

           </form>
            <button id="forupload" <?php if($user_id==0) echo "disabled";?>>开始分享</button>
          </div>
        </div>

        <div id="footer"></div>

     <!-- login interface window 登录窗口 -->
<?php
      require $path_req."/lib/logwindow.php"
?>

    <!-- 图片处理部分-->

    <div  id="crop" class="col-6">
        <div class="image-editor">
   
          <div class="cropit-preview"></div>

        <div class="operate">  
          <i class="rotate-ccw iconfont">&#xe693;</i>
  
        <i class="rotate-cw iconfont">&#xe6d2;</i>

 
          <i class="iconfont">&#xe63e;</i>
   
          <input type="range" class="cropit-image-zoom-input">
  
          <i class="iconfont bigpic">&#xe6a8;</i>

        </div>
       </div>
       <div class="done">
          <button class="export">完成</button>

          <button class="delete">删除</button>
       </div>
    </div>
    <div id="cropback"></div>

   <script src="/lib/nav.js"></script>
   <script src="/lib/logo.js"></script>
   <script src="/lib/fabujs.js"></script>

   <?php      $conn = null; ?>

   </body>

</html>