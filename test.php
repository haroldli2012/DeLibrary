<?php session_start();?>

<!DOCTYPE html>
<html>
  <head>
      <title>和乐分享注册</title>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <meta name="viewport" content="width=device-width,initial-scale=1.0">
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

  </head>

  <body> 

<div> mysqli link test result </div>
 <?php
	$servername="localhost";
	$username="root";
	$password="0252117125";
	$dbname="mysql";
	$conn=mysqli_connect($servername,$username,$password,$dbname);
	if($conn===false){
		print "sorry!";
		die("Connection failed".mysqli_connect_error());
	}
        else echo "mysqli link to database.";
?>
<div> PDO link test result </div>
<?php

     $servername="localhost";
     $username="root";
     $password="0252117125";
     $dbname="mysql";
     try {
        $conn=new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        //set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       echo "PDO connect success!";
       }
     catch(PDOException $e)  {
        echo "Connection failed:".$e->getMessage();
      }

?>
  </body>
</html>  