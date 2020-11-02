<?php

	session_start();
	if(!isset($_SESSION['udanarejestracja'])){
		header('Location: index.php');			//jeśli gracz się nie rejestrował tylko wpisał adres z palca
		exit();
	}
	else{
		unset($_SESSION['udanarejestracja']);
	}
	
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Plemiona - gra przeglądarkowa</title>
</head>

<body>

	Dziękujemy za rejestrację w serwisie! Możesz się zalogować!<br /><br />
	
	<a href="index.php">Zaloguj się na swoje konto!</a>
	<br /><br />
	
	

</body>
</html>