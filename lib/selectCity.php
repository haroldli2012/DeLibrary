<?php
$q = $_REQUEST["q"];

$cities=simplexml_load_file("../cont/Cities.xml");


$txt="";

  foreach($cities->children() as $city) {
    
    if(preg_match("/".$q."/", $city)||preg_match("/".$city."/", $q)) 
           $txt .= "<span>".$city["CityName"]."</span><br/>";
    }

 echo $txt;


?>