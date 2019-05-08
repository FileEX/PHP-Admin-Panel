<?php

session_start();

if(isset($_SESSION["logged"]) && $_SESSION["logged"] === true)
{
	if(isset($_SESSION['rank']) && $_SESSION['rank'] >= 4)
	{
		header("location: mainpage_r.php");
	} else {
		header("location: mainpage.php");
	}
	exit;
}

require_once "config.php";

$user = $pass = "";
$user_err = $pass_err = "";

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if(empty(trim($_POST['loginInput'])))
	{
		$user_err = "Wprowadź login.";
	} else {
		$user = trim($_POST['loginInput']);
	}

	if (empty(trim($_POST['passwordInput'])))
	{
		$pass_err = "Wprowadź hasło.";
	} else {
		$pass = trim($_POST['passwordInput']);
	}

	if(empty($user_err) && empty($pass_err))
	{
		$query = "SELECT username, password, rank FROM users WHERE username = ?";

			if ($stmt = mysqli_prepare($connection, $query))
			{
				mysqli_stmt_bind_param($stmt, 's', $pr_user);

				$pr_user = $user;

				if (mysqli_stmt_execute($stmt))
				{
					mysqli_stmt_store_result($stmt);

					if(mysqli_stmt_num_rows($stmt) == 1)
					{
						mysqli_stmt_bind_result($stmt, $user, $pass_h, $rankLevel);
						
						if (mysqli_stmt_fetch($stmt))
						{
							if(password_verify($pass, $pass_h))
							{
								//session_start();

								$_SESSION['logged'] = true;
								$_SESSION['username'] = $user;
								$_SESSION['rank'] = $rankLevel;

								if($rankLevel >= 4) {
								header("location: mainpage_r.php");} else {
									header("location: mainpage.php");
								}
							} else {
								$pass_err = "Podane hasło jest nie prawidłowe.";
							}
						}
					} else {
						$user_err = "Konto o takim loginie nie istnieje.";
					}
				} else {
					echo "Ups! Coś poszło nie tak, spróbój później.";
				}
			}
			mysqli_stmt_close($stmt);
	}

	mysqli_close($connection);
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<title>Control Panel</title>
	<link rel="stylesheet" type="text/css" href="global.css">
	<meta charset="utf-8" name="viewport" content="width=device.width, initial-scale=1">
</head>
<body>
	<header>
		<p>Admin Control Panel</p>
	</header>

	<div id="loginForm">
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<div id='loginContent'>
				<center>
					<p id="_loginForm">Zaloguj się</p>
					<p id="labelLogin"><?php echo $user_err ?></p>
					<p id="labelPassword"><?php echo $pass_err ?></p>
					<button id="lButton" onclick="">Zaloguj</button>
				</center>
			</div>
			<input type="text" placeholder="Login" name="loginInput">
			<input type="password" placeholder="Hasło" name="passwordInput">
		</form>
	</div>
</body>
</html>