<?php 
	define('host', '94.23.90.14');
	define('dbusr', 'db_41700');
	define('dbpass', 'qV8SYirDJuzt');
	define('dbname', 'db_41700');
	
	$connection = mysqli_connect(host, dbusr, dbpass, dbname);

	global $connection;

	if ($connection === false) {
		die("ERROR: Cannot connect to database." . mysqli_connect_error());
	}

	$mysql_tables = array('ls_logs','ls_vehicles','ls_logs_transfer','ls_users','ls_admins');
	global $mysql_tables;
?>
