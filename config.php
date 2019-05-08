<?php 
	define('host', 'localhost');
	define('dbusr', 'id9080186_base');
	define('dbpass', 'jwzpq');
	define('dbname', 'id9080186_database');
	
	$connection = mysqli_connect(host, dbusr, dbpass, dbname);

	global $connection;

	if ($connection === false) {
		die("ERROR: Cannot connect to database." . mysqli_connect_error());
	}



	$mysql_tables = array('logs','vehicles','money','users','admins');
	global $mysql_tables;
?>