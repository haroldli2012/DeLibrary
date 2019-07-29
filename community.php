<?php
  session_start();
  $path_req = $_SERVER["DOCUMENT_ROOT"];
  $community="";

    require $path_req."/lib/linktodb_PDO.php";

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

    if(isset($_POST['for'])) $community = test_input($_POST['for']);

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
      <link href="/etc/communitycss.css" rel="stylesheet">
      <link rel="stylesheet" href="/etc/icon/iconfont.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <script src="/lib/jquery-1.11.3.min.js"></script>
      <script src="http://tjs.sjs.sinajs.cn/open/api/js/wb.js?appkey=2076762035" type="text/javascript" charset="utf-8"></script>
      <script type="text/javascript" src="http://qzonestyle.gtimg.cn/qzone/openapi/qc_loader.js" data-appid="101382672" data-redirecturi="http://www.heleshare.com/community.php" charset="utf-8" data-callback="true"></script>
   </head>

   <body onload="checkCookie()">

    <?php require $path_req."/lib/topnav.php"; ?>


      <div class="first search col-8"> 

           <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" class="searchform">
               <input type="search" name="for" placeholder="请输入社区名称"
                 value="<?php echo $community;?>" >
               <input type="submit" value="我的社区">
           </form>
           
      </div>

      <div style="clear:both;width:100%;"></div>

      <div class="row">

<?php
      if($community=="") $community="社区分享";
      $url = "news.baidu.com/ns?word=$community&tn=news&cl=2&rn=20";
      //$url = urlencode("news.sogou.com/news?query=$community");
      $ch = curl_init();
      $header = array(
        "Accept:text/html,application/xhtml+xml,*/*",
        "Accept-Encoding:",
        "Accept-Language:zh-Hans-CN,zh-Hans;q=0.8,en-US;q=0.5,en;q=0.3",
        "Host:news.baidu.com",
        "Connection:keep-alive",
        "Cache-Control:no-cache"
       );
      $agent="ozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; rv:11.0) like Gecko";
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_VERBOSE, true);
      curl_setopt($ch, CURLOPT_USERAGENT, $agent);
      curl_setopt($ch, CURLOPT_URL,$url);
      curl_setopt($ch,CURLOPT_COOKIEJAR,'cookies.txt');
      curl_setopt($ch,CURLOPT_COOKIEFILE,'cookies.txt');
      curl_setopt($ch,CURLOPT_FAILONERROR,true);
      curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $content = curl_exec($ch);
      //echo curl_errno($ch); echo "<br/>$ip";
      curl_close($ch);

    /*  $dom = new DOMDocument();
      $content = str_replace('&','&amp;',$content);
      $dom->loadHTML(test_input($content));
      $finder = new DomXPath($dom);
      $query = "//*[contains(concat(' ', normalize-space(@class), ' '), 'result')]";
      $nodes = $finder->query($query);
    echo $nodes->length;
      foreach($nodes as $node) {
          echo $node->saveHTML();
      } */

      $content = str_replace(array("\r","\n","\t","\s"),'',$content);
      $index = preg_match('/<div[^>]*class="result"[^>]*id="1"[^>]*>.*<\/div><\/div><\/div><\/div>/i', $content, $match);
      if($index==1) echo "<div class='col-8 info'>".$match[0];
      else echo "<div class='col-8 info'>没有找到相关的信息</div>"; 
?>

      </div>

        <div id="footer"></div>

<?php
      require $path_req."/lib/logwindow.php"
?>


   <script src="/lib/nav.js"></script>
   <script src="/lib/logo.js"></script>
   <script src="/lib/communityjs.js"></script>
   </body>

</html>