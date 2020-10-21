<?php

	session_start();
	if((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true)){
		header('Location:gra.php');
		exit();
	}
	
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Plemiona - gra przeglądarkowa</title>
</head>

<body>

	Plemiona - gra przeglądarkowa<br /><br />
	
	<a href="rejestracja.php">Rejestracja</a>
	<br /><br />
	
	<form action="zaloguj.php" method="post">
		Zaloguj<br />
		<input type="text" name="login" /><br /><br>
		Hasło<br />
		<input type="password" name="haslo" /><br /><br />
		<input type="submit" value="zaloguj" />
	</form>
	
<?php
	if(isset($_SESSION['blad']))
		echo $_SESSION['blad'];			//Komunikat błędu loginu/hasła
	
	unset($_SESSION['blad']);			//Zresetowanie komunikatu

?>

</body>
</html>