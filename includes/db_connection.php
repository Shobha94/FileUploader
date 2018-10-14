<?php

$db_host = "localhost"; 
// Place the username for the MySQL database here 
$db_username = "root"; 
// Place the password for the MySQL database here 
$db_password = "";  
// Place the name for the MySQL database here 
$db_name = "uploadfile";

// Run the actual connection here  
$conn=mysql_connect("$db_host","$db_username","$db_password") or die ("Could not connect to Database");
$select=mysql_select_db("$db_name") or die ("No Database Found");
//echo "test";
if ($conn) 
{
	# code..
	//echo "Connected to Database";
	
	function toDBFormat($inputDate, $withTime = false) {
	if($withTime == true) {
	$parts = explode(' ', $inputDate);
	$time = count($parts) == 2 ? (' ' . $parts[1]) : '';
	$parts = explode('/', $parts[0]);

	return $parts[2] . '-' . $parts[0] . '-' . $parts[1] . ' ' . $time;
	} else {
	$parts = explode('/', $inputDate);
	return $parts[2] . '-' . $parts[0] . '-' . $parts[1];	
	}
	}

	// Date should be in MM-DD-YYYY HH:mm:ss format
	function toUSFormat($inputDate, $withTime = false) {
	if($withTime == true) {
	$parts = explode(' ', $inputDate);
	$time = count($parts) == 2 ? (' ' . $parts[1]) : '';
	$parts = explode('-', $parts[0]);

	return $parts[1] . '/' . $parts[2] . '/' . $parts[0] . ' ' . $time;
	} else {
	$parts = explode('-', $inputDate);
	return $parts[1] . '/' . $parts[2] . '/' . $parts[0];	
	}
	}
}
else
{
	echo "Connection Failed!";
}
?>