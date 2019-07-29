<?php
$q=$_REQUEST["q"];

$cities=simplexml_load_file("../cont/Cities.xml");
$provinces=simplexml_load_file("../cont/Provinces.xml");
$p=count($provinces);
$pid=array();
switch($q) {
    case "热点":
      $i=1;
      echo "<table><tr>";
      foreach($cities->children() as $city){
        if($city["PID"]==$i) {
           if($i<=$p-3) {  
             echo "<td>".$city["CityName"]."</td>";
              if(($i%7)==0) echo "</tr><tr>";
              $i++;
            }
         } 
       }
      echo "</tr></table>";
      break;
    case "ABCDE": $pid=array("A","B","C","D","E"); break;
    case "FGHJ": $pid=array("F","G","H","J"); break;
    case "KLMNP": $pid=array("K","L","M","N","P"); break;
    case "QRST": $pid=array("Q","R","S","T"); break;
    case "WXYZ": $pid=array("W","X","Y","Z"); break;
   } 
  echo "<table>";
  for($x=0;$x<count($pid);$x++){
    $j=$pid[$x];
    require "citiesByPY.php";
  }
  echo "</table>";


?>