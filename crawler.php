<?php
set_time_limit(200);

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
  crawl_page($url0,3);
  $j=0;

//line 50th;
  foreach($links as $link) { echo "<br/>link-".$j; $j++;
      echo "<br/> URL:".$link[0];
      for($i=0;$i<count($link);$i++) {

         if(preg_match("/\d/",$link[$i])) {
          if(count($link)==1) {
     /*     grab_page($link[$i],"h1");
            grab_page($link[$i], $div_class);
            for($a=2;$a<7;$a++) {
              $str = rtrim($link[0], ".html")."_".$a.".html";
            //  if(FALSE != get_headers($str)) 
                  if(grab_page($str, $div_class)===false) break;
             // else break;
            } */
          } 
         else {
          if($i==0) grab_page($link[$i],"h1");
          grab_page($link[$i], $div_class);
         }
      }
     }
     echo "<br/>";
  } 

  /*  $url = "http://love.menww.com/qggs/2017072911767.html";
    //$dom = new DOMDocument("1.0");
    //@$dom->loadHTMLFile("http://love.menww.com/qggs/2017072911767");
       // $dom = loading("http://love.menww.com/qggs/2017072911767");
          grab_page($url,"h1");
          grab_page($url, $div_class); */
 
  function grab_page($url, $class) {
   
     $dom = new DOMDocument('1.0','UTF-8');
      if(!@$dom->loadHTMLFile($url)) return false;
      
      if($class=="h1") {

        $title = $dom->getElementsByTagName("h1");
    // print_r($title);
     echo $dom->saveHTML($title[0]);

       // echo $title->nodeValue, PHP_EOL;
      }
      else {
      $finder = new DomXPath($dom);
        $divs = $dom->getElementsByTagName("div")->item(0);
        $query = "//div[contains(@class,'conwen')]";
        $nodes = $finder->query($query, $divs);
         //    print_r($nodes);
//line 100 th
        foreach($nodes as $node) {
           $prags = $node->getElementsByTagName("p");
           foreach($prags as $p)    
             echo $p->nodeValue;
          
        }
      }
}

function crawl_page($url, $depth=5)
{
   static $seen = array();
   global $links, $url0;

   $h_parts = parse_url($url0);
   $host = $h_parts['host'];

   $u_parts = parse_url($url);
   if(isset($u_parts['path'])) $way = rtrim($u_parts["path"],".html");

 //  repeat url or depth over , then stop stop
    if (isset($seen[$url]) || $depth == 0) {

      return;
    
    }

    $seen[$url] = true;

    //$dom = loading($url);
    $dom = new DOMDocument("1.0");
    @$dom->loadHTMLFile($url);
    
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
  //judge if the href is repeat or not
  for($j=0;$j<count($links);$j++) {
     for($i=0;$i<count($links[$j]);$i++) {
       if($links[$j][$i] == $href) {
          $same=1;
          $aj=$j;
       }
     }
  }
  // href not repeat then insert into array
  if($same==0) {
         if(isset($u_parts['path'])&&preg_match("/\d/",$way)&&false!=strpos($href, $way))  {
            for($i=0;$i<count($links);$i++) {
               if(false!=strpos($links[$i][0], $way)) {
                  $links[$i][] = $href;
                 //extend page of one main page
               }
            }
     //out put to check result
     //echo "saved URL:<a href=".$href.">".($depth-1).$href."</a>--".$url;
         } else {
           $links[] = array($href);
    //output to check result
    //echo "<br/>".($depth-1)."saved and crawled URL:<a href=".$href.">".$href."</a>",PHP_EOL;

         }
    }

           crawl_page($href, $depth-1);
    }

    //echo "URL:<a href=".$url.">".$url."</a>",PHP_EOL;
   // echo "URL:",$url,PHP_EOL,"CONTENT:",PHP_EOL,$dom->saveHTML(),PHP_EOL,PHP_EOL;
}



 


?>

        <div id="footer"></div>

   </body>

</html>