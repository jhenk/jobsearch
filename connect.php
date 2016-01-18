<?php
$db = mysqli_connect($db_hostname, $db_username, $db_password, $db_database);

if (!$db) { 
	die('Could not connect to MySQL: ' . mysql_error()); 
}

/* check connection */
if ($db->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

mysqli_select_db($db, $db_database);
?>