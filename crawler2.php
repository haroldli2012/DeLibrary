<?php

    if(isset($_POST['for'])) {
         $url0 = $_POST['for'];
         $div_class = $_POST["class"];
      }
    else {
       $div_class = "72 conwen";
       $url0="http://love.menww.com";
     }

?>


<!DOCTYPE html>
<html xmlns:wb="http://open.weibo.com/wb">

   <head>
      <title>和乐分享</title>
      <meta name="viewport" content="width=device-width,initial-scale=1.0">
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

   </head>

   <body>



      <div class="first search col-8"> 

           <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" class="searchform">
               <input type="search" name="for" placeholder="请输入url"
                 value="<?php echo $url0;?>" >
               <input type="search" name="class" placeholder="请输入类名称"
                 value="<?php echo $div_class;?>" >
               <input type="submit" value="开始爬">
           </form>
           
      </div>


<?php 

  $links = array(array($url0));
  foreach($links as $link) crawl_page($link[0],3);
 
  foreach($links as $link) {crawl_page($link[0],3); echo "%".$link[0]."*";}

  $j=0;

  foreach($links as $link) { echo "link-".$j; $j++;
      for($i=0;$i<count($link);$i++) {
            echo $i." URL:".$link[$i];
  //       $dom = loading($link[$i]);
  //       if($i==0) grab_page($dom,"h1");
  //        grab_page($dom, $div_class);
     }//line 50th;
     echo "<br/>array";
  }
 /*
  function loading($url) {
      $ch = curl_init();
      $header = array(
        "Accept:text/html,application/xhtml+xml,",
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
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
      $content = curl_exec($ch);
      echo curl_errno($ch);
      //echo "<br/>$ip";
      curl_close($ch);

      $dom = new DOMDocument('1.0','UTF-8');
      $internalErrors = libxml_use_internal_errors(true);
      $content = str_replace('&','&amp;',$content);
      //$dom->loadHTML($content);
      libxml_use_internal_errors($internalErrors);

       return $dom;
   }
      
   function grab_page($dom, $class) {
 

      $finder = new DomXPath($dom);
      
      if("$class"=="h1") {
        $title = $dom->getElementsByTagName("h1")->item(0);
        echo $title->nodeValue, PHP_EOL;
      }
      else {

        $div = $dom->getElementsByTagName("div");
        $query = "//*[contains(concat(' ', normalize-space(@class), ' '), $div_class)]";
        $nodes = $finder->query($query, $div);

        echo $nodes->length."<br/>";
        foreach($nodes as $node) {
           $prags = $node->getElementsByTagName("p");
           foreach($prags as $p) {
              echo $p->nodeValue, PHP_EOL;
           }
        }
      }
}*/

function crawl_page($url, $depth=5)
{

   global $links, $url0;

   $h_parts = parse_url($url0);
   $host = $h_parts['host'];

   $u_parts = parse_url($url);
   if(isset($u_parts['path'])) $way = rtrim($u_parts["path"],".html");

   $num= count($links);

   foreach($links as $link) {

    //$dom = loading($url);
    $dom = new DOMDocument("1.0");
    @$dom->loadHTMLFile($link[0]);

    $anchors = $dom->getElementsByTagName('a');
    foreach ($anchors as $element) {
        $href = $element->getAttribute('href');

        //quit if link not the children link
       if(false==strpos($href, $host)) continue;

        if (0 !== strpos($href, 'http')) {
            $path = '/' . ltrim($href, '/');
            if (extension_loaded('http')) {
                $href = http_build_query($url, array('path' => $path));
            } else {
                $parts = parse_url($url);
                $href = $parts['scheme'] . '://';
                if (isset($parts['user']) && isset($parts['pass'])) {
                    $href .= $parts['user'] . ':' . $parts['pass'] . '@';
                }
                $href .= $parts['host'];
                if (isset($parts['port'])) {
                    $href .= ':' . $parts['port'];
                }
                $href .= $path;
            }
        }


  $same=0;
  for($j=0;$j<count($links);$j++) {
     for($i=0;$i<count($links[$j]);$i++) {
       if($links[$j][$i] == $href) {
          $same=1;
          $aj=$j;
       }
     }
  }

  if($same==0) {
         if(isset($u_parts['path'])&&preg_match("/[0-9]+$/",$way)&&false!=strpos($href, $way))  {
            for($i=0;$i<count($links);$i++) {
               if(false!=strpos($links[$i][0], $way)) {
                  $links[$i][] = $href;
                 //echo "**".$links[$i][0]."**";
               }
            }
 
    echo "--".$way."--";
    echo "saved URL:<a href=".$href.">".($depth-1).$href."</a>--".$url;
         } else {
           $links[] = array($href);

    echo "<br/>".($depth-1)."saved and crawled URL:<a href=".$href.">".$href."</a>".$url;

         }
    }

    
    }

 }
    //echo "URL:<a href=".$url.">".$url."</a>",PHP_EOL;
   // echo "URL:",$url,PHP_EOL,"CONTENT:",PHP_EOL,$dom->saveHTML(),PHP_EOL,PHP_EOL;
}

//crawl_page("http://hobodave.com", 2);


 


?>

        <div id="footer"></div>

   </body>

</html>