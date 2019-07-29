<?php
$q = $_REQUEST["q"];
$z = $_REQUEST["z"];
$id="";

$cities=simplexml_load_file("../cont/Cities.xml");


$txt="<option value=''>请选择区域</option>";

  foreach($cities->children() as $city) {
    
    if(preg_match("/".$city."/", $q)) $id = $city["ID"];
    }


     require "linktodb_PDO.php";
     try {       
        $sql="SELECT countryName FROM countries where cityId = ".$id;
        $stmt=$conn->prepare($sql);
        $stmt->execute();

        foreach($stmt->fetchAll() as $country) {
          if($z==$country[0])
             $txt .= "<option selected value =".$country[0].">".$country[0]."</option>";
          else
             $txt .= "<option value =".$country[0].">".$country[0]."</option>";
        }
       }
     catch(PDOException $e)  {
        echo "Connection failed:".$e->getMessage();
      }


   echo $txt;

  $conn = null;

?>