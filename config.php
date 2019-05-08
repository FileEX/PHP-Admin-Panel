<?php 
	define('host', 'localhost');
	define('dbusr', ''); // WRITE
	define('dbpass', ''); // WRITE
	define('dbname', ''); // WRITE
	
	$connection = mysqli_connect(host, dbusr, dbpass, dbname);

	global $connection;

	if ($connection === false) {
		die("ERROR: Cannot connect to database." . mysqli_connect_error());
	}



	$mysql_tables = array('logs','vehicles','money','users','admins');
	global $mysql_tables;
?>
