<?php
$q=$_REQUEST["q"];

$cities=simplexml_load_file("Cities.xml");
$provinces=simplexml_load_file("Provinces.xml");
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
    case "东北": $pid=array(6,7,8); break;
    case "华北": $pid=array(1,2,3,4,5); break;
    case "华东": $pid=array(9,10,11,12,15); break;
    case "华南": $pid=array(13,19,20,21); break;
    case "华中": $pid=array(16,17,18,14); break;
    case "西南": $pid=array(22,23,24,25,26); break;
    case "西北": $pid=array(27,28,29,30,31); break;
   } 
  echo "<table>";
  for($x=0;$x<count($pid);$x++){
	$j=$pid[$x];
	require "citiesOnCerternProvince.php";
  }
  echo "</table>";


?>