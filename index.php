<?php
  session_start();
  $path_req = $_SERVER["DOCUMENT_ROOT"];

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
      <meta name="baidu-site-verification" content="OmQjwH60bX"/>
      <meta property="wb:webmaster" content="b6bdd298fc187711"/>
      <meta name="viewport" content="width=device-width,initial-scale=1.0">
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <meta name="keywords" content="分享,share,共享">
      <link href="/etc/navtop.css" rel="stylesheet">
      <link href="/etc/common.css" rel="stylesheet">
      <link href="/etc/homepagecss.css" rel="stylesheet">
      <link rel="stylesheet" href="/etc/icon/iconfont.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <script src="/lib/jquery-1.11.3.min.js"></script>
      <script src="http://tjs.sjs.sinajs.cn/open/api/js/wb.js?appkey=2076762035" type="text/javascript" charset="utf-8"></script>
      <script type="text/javascript" src="http://qzonestyle.gtimg.cn/qzone/openapi/qc_loader.js" data-appid="101382672" data-redirecturi="http://www.heleshare.com" charset="utf-8" data-callback="true"></script>

<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  /*(adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-2828558883487733",
    enable_page_level_ads: true
  }); */
</script>

     <meta name="baidu_union_verify" content="bd021ea8122560b01572d33f14277427">

   </head>

   <body onload="checkCookie()">

    <?php require $path_req."/lib/topnav.php"; ?>

      <div class="second col-8">
         <div class="col-5 pic-box1">
           <h2><a href="fabu.php">我要分享</a></h2>
           <p>堆在墙角落满灰尘的玩具，遗忘在楼道里的自行车，为什么不把他们分享给需要的人呢？或许他正好有你想看的一本书呢。开始互相分享吧。<br/><br/>热点分享：
<a href="search.php?searchfor=职场">职场</a> <a href="search.php?searchfor=两性">两性</a> <a href="search.php?searchfor=情感">情感</a> <a href="search.php?searchfor=丝袜">丝袜</a> <a href="search.php?searchfor=美女">美女</a></p>
         </div>
         <div class="col-5 pic-box2">
           <h2><a href="community.php">我的社区</a></h2>
           <p>一起来建设我们自己的社区，和谐的邻里关系，绿色的分享方式，快乐的生活环境</p>
         </div>
      </div>

      <div class="first search col-8"> 

           <form action="search.php" method="post" class="searchform">
               <input id="trysearch" type="search" name="searchfor" value="管理">
               <input type="submit" value="搜索">
           </form>
           

        <div class="search_result">
        </div>

      </div>

        <div id="footer"></div>

    <?php require $path_req."/lib/logwindow.php"; ?>



   <script src="/lib/nav.js"></script>
   <script src="/lib/logo.js"></script>
   <script src="/lib/homepagejs.js"></script>
   </body>

</html>