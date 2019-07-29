<?php
	$servername="localhost";
	$username="root";
	$password="0252117125";
	$dbname="share";
	$conn=mysqli_connect($servername,$username,$password,$dbname);
	if($conn===false){
		print "sorry!";
		die("Connection failed".mysqli_connect_error());
	}
?>