<!DOCTYPE html>
<html>

   <head>
      <title>和乐分享</title>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
   </head>

   <body>
<?php
  echo "县级行政单位列表:";
  $row=1;
	$servername="localhost";
	$username="root";
	$password="";
	$dbname="share";
      try {
        $conn=new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        //set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Connnected successfully";
	}
      catch(PDOException $e)
        {
          echo "Connection failed:".$e->getMessage();
         }


  if(($handle=fopen("countries.csv","r"))!==FALSE) {
    while(($data=fgetcsv($handle,0))!==FALSE)  {
       $num=count($data);
       echo "<p>$num fields in line $row: <br/></p>\n";
       $row++;
       for($c=0;$c<$num;$c++)  {
         if($c==($num-1)) {
             $data[$c]=iconv("GBK","UTF-8",$data[$c]);
            try {
             $sql="UPDATE countries SET countryName = '".$data[$c]."' where countryId = ".$data[0];
             $stmt=$conn->prepare($sql);
             $stmt->execute();
             echo $stmt->rowCount()." records UPDATED successfully.";
             }
            catch(PDOException $e)
             {
              echo $sql."<br/>".$e->getMessage();
              }
         }
         echo $data[$c]."<br/>\n";
         }
    }
    fclose($handle);
  }

  $conn = null;
?>
   </body>

</html>