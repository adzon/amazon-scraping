<?php

/////////////////////////
 // Database configuration for customer table //
 ////////////////////////
$host ="127.0.0.1";
$user ="root";
$pass ="";
$dbname = "amazon5";



/////////////////////////
 // Customer Database connection function //
 ////////////////////////

date_default_timezone_set("UTC"); 


function dbconnect(){
	global $host, $user, $pass, $dbname;

	$conn = mysqli_connect($host,$user,$pass,$dbname);

	return $conn;
}




?>