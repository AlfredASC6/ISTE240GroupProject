<?php
	//set the servername
	$servername = "localhost";
	//set the server username
	$username = "ajs7938";
	// set the server password (you must put password here if your using live server)
	$password = 'Northeastward2&planet';
	// set the table name
	$dbname = "ajs7938"; 

	$db = new mysqli($servername, $username, $password, $dbname);

	if ($db->connect_errno) {
	  echo "Failed to connect to MySQL: " . $db->connect_error;
	  exit();
	}

	// Include functions file
	require_once 'functions.php';
?>