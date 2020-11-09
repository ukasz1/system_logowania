<?php

	session_start();
	if(!isset($_SESSION['udanarejestracja'])){
		header('Location: index.php');			//jeśli gracz się nie rejestrował tylko wpisał adres z palca
		exit();
	}
	else{
		unset($_SESSION['udanarejestracja']);
	}
	
	//Usuwanie zmiennych pamiętających wartości formularza
	if(isset($_SESSION['form_nick'])) unset($_SESSION['form_nick']);
	if(isset($_SESSION['form_email'])) unset($_SESSION['form_email']);
	if(isset($_SESSION['form_regulamin'])) unset($_SESSION['form_regulamin']);
	
	//Usuwanie błędów rejestracji
	if(isset($_SESSION['e_nick'])) unset($_SESSION['e_nick']);
	if(isset($_SESSION['e_email'])) unset($_SESSION['e_email']);
	if(isset($_SESSION['e_haslo'])) unset($_SESSION['e_haslo']);
	if(isset($_SESSION['e_regulamin'])) unset($_SESSION['e_regulamin']);
	if(isset($_SESSION['e_bot'])) unset($_SESSION['e_bot']);
	
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