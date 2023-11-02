<?php

$db = mysqli_connect($db_hostname, $db_username, $db_password, $db_database);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    //Open a new connection to the MySQL server
$db = new mysqli('localhost','root','root','jobsearch');


if ($db->connect_error) {
        die('Error : ('. $db->connect_errno .') '. $db->connect_error);
    }
// printf($db);

if (!$db) { 
	die('Could not connect to MySQL: ' . mysqli_error($db)); 
}

/* check connection */
if ($db->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

mysqli_select_db($db, $db_database);
?>