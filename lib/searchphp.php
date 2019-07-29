<?php
  ini_set("memory_limit","1024M");
  session_start();

  $search = test_input($_REQUEST["seek"]);
  if(strlen($search)<1) exit();

  $path_req = $_SERVER["DOCUMENT_ROOT"];

  require_once $path_req."/jiebafenci/src/vendor/multi-array/MultiArray.php";
  require_once $path_req."/jiebafenci/src/vendor/multi-array/Factory/MultiArrayFactory.php";
  require_once $path_req."/jiebafenci/src/class/Jieba.php";
  require_once $path_req."/jiebafenci/src/class/Finalseg.php";
  require_once $path_req."/jiebafenci/src/class/JiebaAnalyse.php";
  use Fukuball\Jieba\Jieba;
  use Fukuball\Jieba\Finalseg;
  use Fukuball\Jieba\JiebaAnalyse;
  Jieba::init();
  Finalseg::init();
  JiebaAnalyse::init();


  //key words check in both country and share,
  //if length longer than 4, use keywords extract, then comparing one by one. check if there is city or   country key words, found it and back to shares;
  // pinyin no need extract.

   // database search
   $shareId=array();
   require "linktodb_PDO.php";
     try {
       $sql="SELECT shareId FROM shares WHERE city like '".$search."%'or country like'".$search."%' or keywords like '%".$search."%' or shareTitle like '%".$search."%'";
       $stmt=$conn->prepare($sql);
       $stmt->execute();
       $shareIds=$stmt->fetchAll();
       foreach($shareIds as $id) {
          array_push($shareId,$id[0]);
         
         }
      }
      catch(PDOException $e) {
        echo "Error:".$e->getMessage();
      }
    

  if(strlen($search)>10) {
       $Tags=JiebaAnalyse::extractTags($search);
       //var_dump($Tags);
       //echo strlen($search);

   // database search
   foreach($Tags as $search => $value)
    { echo $search."=>".$value;
     try {
       $sql="SELECT shareId FROM shares WHERE city like '".$search."%'or country like'".$search."%' or keywords like '%".$search."%' or shareTitle like '%".$search."%'" ;
       $stmt=$conn->prepare($sql);
       $stmt->execute();
       $shareIds=$stmt->fetchAll();
       foreach($shareIds as $id) {
          array_push($shareId,$id[0]);
         }
      }
      catch(PDOException $e) {
        echo "Error:".$e->getMessage();
      }
    }
  }

   //search result sort by repeat times
   $shareId=array_count_values($shareId);
   arsort($shareId);

        //search result display

        if(count($shareId)>0)  {
           foreach($shareId as $resultId => $value) {
        try {
              $sql="SELECT * FROM shares WHERE shareId= '".$resultId."'" ;
              $stmt=$conn->prepare($sql);
              $stmt->execute();
              $share=$stmt->fetchAll();
         }
       catch(PDOException $e) {
            echo "Error:".$e->getMessage();
         }

                 $share_customerId=$share[0]["customerId"];
                 $title=$share[0]["shareTitle"];
                 $detail=$share[0]["shareDetail"];
                 $enddate=$share[0]["endDate"];
                 $value=$share[0]["shareValue"];
                 $city=$share[0]["city"];
                 $country=$share[0]["country"];
                 $f=$share[0]["filePath"];
                 $contactor=$share[0]["contactor"];

                 $filepath="../upload/".iconv("UTF-8","GBK",$city)."/".$share_customerId."/".$resultId."/";
                 if(is_dir($filepath))
                    $files = scandir($filepath);
                 else $files = "";
                //print_r($files);
              
 ?>
       <h5 class="title"><a href="display.php?shareId=<?php echo $resultId;?>" target="_blank"><?php echo $title;?></a></h5>

      <div class="maincontent">
<?php
        if($files!="") { 
?>

         <div class="pics">
           <img class="pic" src='<?php echo $f.$files[2];?>' title='<?php echo $files[2];?>'/>
           <div class="disc">共有<?php echo count($files)-2;?>张图片</div>
         </div>

<?php
         } 
        if($value==314159) {
?>
         <div class="discr">
            <p class="small">  <?php echo substr($detail,0,299);?></p>
         </div>
<?php
        }
        else {
?>
         <div class="discr">
            <p class="small first"><b>分享底价：</b><?php echo $value;?>乐蟹</p>
            <p class="small"><b>详细介绍：</b><?php echo substr($detail,0,299);?></p>
            <p class="small"><b>截至日期：</b><?php echo $enddate;?> </p>
            <p class="small"><b>地址：</b><?php echo $city.$country;?></p>
          </div>
  <?php
       }
  ?>
        </div>

  <?php
       }
     }
     else echo $search."没有搜到与之匹配的分享,换个检索词试一下看看?";

function test_input($data){
  $data=trim($data);
  $data=stripslashes($data);
  $data=htmlspecialchars($data);
  return $data;
} 

  $conn = null;

//save search words as next time search hint
  if(count($shareId)>0) {
$xml=simplexml_load_file("../cont/searchhint.xml");
$repeat = 0;
foreach($xml->children() as $hint) {
   if(preg_match("/".$hint."/",$search)) {
      $hint["frequency"] +=1;
      $repeat = 1;
    }
  }

if($repeat==0) {
   require "../pinyin/src/Pinyin/Pinyin.php";
   \Overtrue\Pinyin\Pinyin::set('accent','');
   \Overtrue\Pinyin\Pinyin::set('delimiter','');
   $searchPY = \Overtrue\Pinyin\Pinyin::trans($search);

   $xml->addChild("hint",$search);
   $n = $xml->count() - 1;
   $xml->hint[$n]->addAttribute("hintID",$n);
   $xml->hint[$n]->addAttribute("hintName",$searchPY);
   $xml->hint[$n]->addAttribute("frequency",1);
  }

$xml->asXML("../cont/searchhint.xml");

}

 $action = "search";
 require "activity.php";

  ?>




