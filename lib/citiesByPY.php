<?php
    $i=1;
    echo "<tr><th>".$j."</th>";
    foreach($cities->children() as $city){
        if($city["PY"]==$j) {
	 
        echo "<td>".$city["CityName"]."</td>";
        if(($i%6)==0) echo "</tr><tr><th></th>";
        $i++;
       } 
    }
   echo "</tr>";   
?>