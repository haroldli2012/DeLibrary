<?php

$pinleis=simplexml_load_file("../cont/pinleis.xml");
$zileis=simplexml_load_file("../cont/zileis.xml");

  echo "<div class='classnav'><div class='classhead'>类别导航</div>";
  foreach($pinleis->children() as $pinlei) {
    echo "<div class='dropdown'><span>".$pinlei."</span><div class='dropcont'>";
    foreach($zileis->children() as $zilei) {
         if((string)$zilei["PID"]==(string)$pinlei["PinleiID"]) 
             echo "<span>".$zilei."</span>";
    }

    echo "</div></div>";
  }
  echo "</div>";
?>