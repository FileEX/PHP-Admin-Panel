<?php
ob_start();
session_start();

if(!isset($_SESSION["logged"]) || $_SESSION["logged"] !== true){
    header("location: index.php");
    exit;
}
if(!isset($_SESSION['rank']) || $_SESSION['rank'] < 4) {
	header("location: index.php");
	exit;
}

require_once 'config.php';

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function updateRec($tbl, $index) {
	if($GLOBALS['connection'] !== null) {
		$connection = $GLOBALS['connection'];

		if($tbl == 'ls_users') {

			if(isset($_POST['id']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['rank'])) {
				$id = test_input($_POST['id']);
				$login = test_input($_POST['username']);
				$pass = password_hash(test_input($_POST['password']), PASSWORD_DEFAULT);
				$rank = test_input($_POST['rank']);
				$q = "UPDATE $tbl SET id='$id', username='$login', password='$pass', rank='$rank' WHERE id='$index'";
			}
		}

		switch ($tbl) {
			case 'ls_users':
				$tb = 'users';
				break;
			/*case 'ls_vehicles':
				$tb = 'mainpage'; // TODO
				break;
			case 'ls_logs':
				$tb = 'mainpage'; // TODO
				break;
			case 'ls_admins':
				$tb = 'mainpage'; // TODO
				break;
			case 'ls_logs_transfer':
				$tb = 'mainpage'; // TODO
				break;*/
			default:
				$tb = 'mainpage';
				break;
		}

		mysqli_query($connection, $q);
		header('location: '.$tb.'.php?page=1');

		ob_end_flush();
	}
}

function delRec($tbl, $index) {
	if ($GLOBALS['connection'] !== null) {
		$connection = $GLOBALS['connection'];

		$q = "SELECT * FROM $tbl WHERE id='$index'";
		$qr = mysqli_query($connection, $q);
		if(mysqli_num_rows($qr) > 0) {
			$q = "DELETE FROM $tbl WHERE id='$index'";
			mysqli_query($connection, $q);

			switch ($tbl) {
				case 'ls_users':
					$tb = 'users';
					break;
				/*case 'ls_vehicles':
					$tb = 'mainpage'; // TODO
					break;
				case 'ls_logs':
					$tb = 'mainpage'; // TODO
					break;
				case 'ls_admins':
					$tb = 'mainpage'; // TODO
					break;
				case 'ls_logs_transfer':
					$tb = 'mainpage'; // TODO
					break;*/
				default:
					$tb = 'mainpage';
					break;
			}

			header('location: '.$tb.'.php?page=1');
			exit;
		}
		mysqli_close($connection);

		ob_end_flush();
	}
}

function createForm($a) {
	if ($a === 'del') {
		echo "<center>";
		echo "<form action=\"forms.php?action=delete&tbl=".$_GET['tbl']."&row=".$_GET['row']."\" method=\"post\" accept-charset=\"utf-8\" style=\"transform: translate(-10vw,0vh);\">";
		echo "<span style='display: inline-block; position: relative; color:green; top:18vh; left: 17vw;'>Jesteś pewien, że chcesz to zrobić?</span> <span style='display: inline-block; position: relative; color:red; top:18vh; left: 18vw;'>Tej operacji nie można cofnąć!</span>";
		echo '<button id="fButton" name="submit" style="transform: translate(-17vw,30vh);"onclick="">Tak</button></form>'. '<a href="'.$_GET['tbl'].'.php?page=1"><button id="fButton" style="transform: translate(0vw, 23vh); margin-left: 6%;" onclick="">Anuluj</button></a>';
		echo "</center>";
	};
	if ($a === 'delete') {
		delRec($_GET['tbl'], $_GET['row']);
	};
	if ($a === 'edit') {
		echo "<center><div id='editForm'>";
		echo '<form action="forms.php?action=saveE&tbl='.$_GET['tbl'].'&row='.$_GET['row'].'" method="post" accept-charset="utf-8" style="height: 0vh;">';
		$q = "DESCRIBE ".$_GET['tbl']."";
		$qr = mysqli_query($GLOBALS['connection'], $q);
		$qry = "SELECT * FROM ".$_GET['tbl']." WHERE id=".$_GET['row']." LIMIT 1;";
		$query = mysqli_query($GLOBALS['connection'], $qry);
		$data = mysqli_fetch_array($query);
		while ($rw = mysqli_fetch_array($qr)) {
			echo "<tr>";
			echo "<td> " . $rw['Field'] . "</td><br/>";
			echo "<td><textarea rows='2' cols='50' name='".$rw['Field']."'>".$data[$rw['Field']]."</textarea></td>";
			echo "<br/>";
			echo "</tr>";
		}
		echo '<button id="fButton" name="submit" style="">Zapisz</button><a href="'.$_GET['tbl'].'.php?page=1"><button id="fButton" style="transform: translate(-2vw, 9.7vh);">Anuluj</button></a></form>';
		echo "</div></center>";
	};
	if ($a === 'saveE') {
		updateRec($_GET['tbl'], $_GET['row']);
	};
}

if (isset($_GET['action'])) {
	createForm($_GET['action']);
}
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
	
	
</body>
</html>