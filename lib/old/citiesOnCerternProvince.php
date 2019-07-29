<?php
   $i=1;
   echo "<tr><th>".$provinces->Province[$j-1]."</th>";
   foreach($cities->children() as $city){
     if($city["PID"]==$j) {
	 
         echo "<td>".$city["CityName"]."</td>";
	 if(($i%6)==0) echo "</tr><tr><th></th>";
	   $i++;
       } 
     }
   echo "</tr>";   
?>