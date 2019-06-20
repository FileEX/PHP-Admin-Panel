<?php
session_start();
 
if(!isset($_SESSION["logged"]) || $_SESSION["logged"] !== true){
    header("location: index.php");
    exit;
}
if(!isset($_SESSION['rank']) || $_SESSION['rank'] < 1) {
	header("location: index.php");
	exit;
}

function getUserIP()
{
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
              $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
              $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}

$u_ip = getUserIP();
?>
 
<!DOCTYPE html>
<html lang="pl">
<head>
	<title>Adm</title>
	<link rel="stylesheet" type="text/css" href="global.css">
	<meta charset="utf-8" name="viewport" content="width=device.width, initial-scale=1">
</head>
<body>
	<header>
		<p>ADM</p>
	</header>

	<nav>
		<ul>
			<li><a class="active" href="mainpage.php">Strona główna</a></li>
			<li><a href="#logs">Logi</a></li>
			<li><a href="#vehicles_r">Pojazdy</a></li>
			<li><a href="#money_logs">Przelewy</a></li>
			<li><a href="logout.php">Wyloguj</a></li>
		</ul>
	</nav>
	<div id='welcome'>
		<span>Witaj <?php echo $_SESSION['username']; ?>! (<?php echo $u_ip; ?>)</span>
	</div>
</body>
</html>