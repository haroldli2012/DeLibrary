<?php

  $search = test_input($_REQUEST["hintby"]);

$xml=simplexml_load_file("../cont/searchhint.xml");

$key = array();
$value = array();
foreach($xml->children() as $hint) {
   $pinyin = $hint["hintName"];
   if(preg_match("/".$search."/",$hint)||preg_match("/".$search."/",$pinyin)) {
      array_push($key,$hint);
      array_push($value,(integer)$hint["frequency"]);
    }
  }

$hint = array_combine($key,$value);

arsort($hint);

$i=0;

foreach($hint as $x => $x_value) {
  if($i<6) echo"<p>".$x."</p>";
  $i++;
}

function test_input($data){
  $data=trim($data);
  $data=stripslashes($data);
  $data=htmlspecialchars($data);
  return $data;
} 

  ?>




