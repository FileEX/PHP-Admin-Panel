<?php
ob_start();
session_start();
 
if(!isset($_SESSION["logged"]) || $_SESSION["logged"] !== true){
    header("location: index.php");
    exit;
}
if(!isset($_SESSION['rank']) || $_SESSION['rank'] < 1) {
	header("location: index.php");
	exit;
}

require_once "config.php";

$sqltable = $GLOBALS['mysql_tables'][3];

function generate_page_links($cur_page, $num_pages) {
     $page_links = '';
 
     if ($cur_page > 1) {
          $page_links .= '<a href="' . $_SERVER['PHP_SELF'] . '?page=' . ($cur_page - 1) . '">«</a> ';
     }
 
     $i = $cur_page - 4;
     $page = $i + 8;
 
     for ($i; $i <= $page; $i++) {
 
          if ($i > 0 && $i <= $num_pages) {
               
               if ($cur_page == $i  && $i != 0) {
                    $page_links .= '' . $i;
               }
               else {
 
                    if ($i == ($cur_page - 4) && ($cur_page - 5) != 0) { 
                         $page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?page=1">1</a> '; 
                    }
               
                    if ($i == ($cur_page - 4) && (($cur_page - 6)) > 0) { 
                         $page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?page=' . ($cur_page - 5) . '">...</a> '; 
                    } 
               
                    $page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?page=' . $i . '"> ' . $i . '</a> ';
          
                    if ($i == $page && (($cur_page + 4) - ($num_pages)) < -1) { 
                         $page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?page=' . ($cur_page + 5) . '">...</a>'; 
                    } 
               
                    if ($i == $page && ($cur_page + 4) != $num_pages) { 
                         $page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?page=' . $num_pages . '">' . $num_pages . '</a> '; 
                    }
               }
          }
     }
 
     if ($cur_page < $num_pages) {
          $page_links .= '<a href="' . $_SERVER['PHP_SELF'] . '?page=' . ($cur_page + 1) . '">»</a>';
     }
 
     return $page_links;
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

	<nav>
		<ul>
			<li><a href="mainpage.php">Strona główna</a></li>
			<li><a href="#logs">Logi</a></li>
			<li><a href="#vehicles_r">Pojazdy</a></li>
			<li><a href="#money_logs">Przelewy</a></li>
			<li><a class ="active" href="users_na.php">Użytkownicy</a></li>
			<li><a href="#admins">Administratorzy</a></li>
			<li><a href="logout.php">Wyloguj</a></li>
		</ul>
	</nav>
	<div id="searchBar">
		<?php
		echo "
		<form action='users_na.php?search=1' method='post'>
			<input type='text' name='searchData' style='width: 15vw; height: 3vh; border-radius: 6px 6px 6px 6px; background-color: #333; color: white; border: none; font-size: 0.65em; outline: none; margin: 0;' placeholder='Szukaj'/>
			<button id='sButton'>Filtruj</button>
		</form>";
		?>
	</div>
	<div id="resultTableContent">
		<?php
			if (!isset($_GET['search'])) {
				$cur_page = isset($_GET['page']) ? $_GET['page'] : 1;
				$rows = 20;
				$skip = (($cur_page - 1) * $rows);
				$query = 'SELECT * FROM '.$sqltable;
				$d = mysqli_query($connection, $query);

				$t = mysqli_num_rows($d);
				$num_pages = ceil($t / $rows);
				$query .=  " LIMIT $skip, $rows";
				$r = mysqli_query($connection, $query);

				echo "<table id='resultTable' border='1'>
					<tr>
						<th>ID</th>
						<th>Login</th>
						<th>Ranga</th>
					</tr>
				";

				while ($row = mysqli_fetch_array($r))
				{
					echo "<tr>";
					echo "<td> " . $row['id'] . "</td>";
					echo "<td> " . $row['username'] . "</td>";
					echo "<td> " . $row['rank'] . "</td>";
					echo "</tr>";
				}

				if ($num_pages > 1) {
     				echo generate_page_links($cur_page, $num_pages);
				}
			} elseif (isset($_GET['search'])) {
				$d = $_POST['searchData'];
				if(!empty($d)) {
					$query = "SELECT * FROM $sqltable WHERE username='$d' OR id='$d' LIMIT 1";
					$q = mysqli_query($connection, $query);

					if (mysqli_num_rows($q) > 0) {

						echo "<table id='resultTable' border='1'>
							<tr>
								<th>ID</th>
								<th>Login</th>
								<th>Ranga</th>
							</tr>
						";

						while ($row = mysqli_fetch_array($q))
						{
							echo "<tr>";
							echo "<td> " . $row['id'] . "</td>";
							echo "<td> " . $row['username'] . "</td>";
							echo "<td> " . $row['rank'] . "</td>";
							echo "</tr>";
						}
					} else {
						echo "Nie znaleziono użytkownika o takim loginie lub id.";
					}
				} else {
					header('location: users_na.php?page=1');
				}
			}
			echo "</table>";
			mysqli_close($connection);
		?>
	</div>

</body>
</html>