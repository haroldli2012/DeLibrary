<?php
session_start();
  $path_req = $_SERVER["DOCUMENT_ROOT"];

  if(isset($_REQUEST["searchfor"])) $search = $_REQUEST["searchfor"];
  else $search = "";

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
   <link href="/etc/searchcss.css" rel="stylesheet">
   <link rel="stylesheet" href="/etc/icon/ux/iconfont.css">
   <link rel="stylesheet" href="/etc/icon/iconfont.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   <script src="/lib/jquery-1.11.3.min.js"></script>

      <script src="http://tjs.sjs.sinajs.cn/open/api/js/wb.js?appkey=2076762035" type="text/javascript" charset="utf-8"></script>

      <script type="text/javascript" src="http://qzonestyle.gtimg.cn/qzone/openapi/qc_loader.js" data-appid="101382672" data-redirecturi="http://www.heleshare.com/search.php" charset="utf-8" data-callback="true"></script>

<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-2828558883487733",
    enable_page_level_ads: true
  });
</script>
</head>

<body onload="checkCookie()">

    <?php require $path_req."/lib/topnav.php"; ?>

   <div class="col-8 searching">
      <input type="search" id="trysearch" oninput="searchajax(event)" value="<?php echo $search;?>" autofocus>
      <button id="gosearch">搜索</button>
      <p class="hot">检索热词：<a href="search.php?searchfor=职场">职场</a> <a href="search.php?searchfor=两性">两性</a> <a href="search.php?searchfor=情感">情感</a> <a href="search.php?searchfor=丝袜">丝袜</a> <a href="search.php?searchfor=美女">美女</a> </p>
      <div class="search-hint"></div>
   </div>

      <div class="col-8 display">

        <div class="search_result">
        </div>

      </div>



   <div id="footer"></div>


<!-- The Modal for pic enlarge-->
<div id="myModal" class="modal">
   <span class="close">&times;</span>

   <img class="modal-content" id="img01">

</div>


     <!-- login interface window 登录窗口 -->
<?php
      require $path_req."/lib/logwindow.php"
?>



   <script src="/lib/nav.js"></script>
   <script src="/lib/logo.js"></script>
   <script src="/lib/searchjs.js"></script>
</body>

</html>