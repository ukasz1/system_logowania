<!-- https://www.google.com/recaptcha/admin/create - ReCAPTCHA -->




<?php

// 6Ldt8tkZAAAAAEdl2tKe6OTMiSLUxWiJVzLTt3UF site-key

// 6Ldt8tkZAAAAAP64HL5xgLicSLIg8kLchf8-LgXP php

	session_start();
	
	if(isset($_POST['email'])){			/*sprawdzenie czy był wysłany formularz, czyli czy istnieje tablica asocjacyjna $_POST[]*//*indeks tablicy może być dowolny*/
		
		//Udana walidacja
		$wszystko_OK=true;
		
		$nick=$_POST['nick'];
		
		if(strlen($nick)<3 || strlen($nick)>20){	//sprawdzenie długości nicku
			$wszystko_OK=false;
			$_SESSION['e_nick']='Nick musi posiadać od 3 do 20 znaków!';
		}
		
		if(ctype_alnum($nick)==false){				//ctype_alnum() - funkcja do sprawdzania znaków alfanumerycznych
			$wszystko_OK=false;
			$_SESSION['e_nick']="Nick może składać się tylko z liter i cyfr (bez polskich znaków)!";
		}
		
		//Sprawdzanie poprawności adresu e-mail
		$email = $_POST['email'];
		
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);	//filter_var($zmienna, FILTER_SANITIZE_EMAIL) - funkcja wycinająca z napisu niedopuszczalne znaki
		
		if(filter_var($email,FILTER_VALIDATE_EMAIL)!=true || $email!=$emailB){	//filter_var($zmienna,FILTER_VALIDATE_EMAIL) - funkcja boolowska sprawdzająca poprawność formy e-maila
			$wszystko_OK=false;
			$_SESSION['e_email']="Podaj poprawny adres e-mail!";
		}
		
		//Sprawdzanie poprawności hasła
		$haslo1 = $_POST['haslo1'];
		$haslo2 = $_POST['haslo2'];
		
		if(strlen($haslo1)<8 || strlen($haslo1)>20){
			$wszystko_OK=false;
			$_SESSION['e_haslo1']="Hasło musi posiadać od 8 do 20 znaków!";
		}
		
		if($haslo1!=$haslo2){
			$wszystko_OK=false;
			$_SESSION['e_haslo2']="Podane hasła nie są identyczne!";
		}
		
		$haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);  // hashowanie hasla
		
		//Czy zaakceptowano regulamin?
		if(!isset($_POST['regulamin'])){
			$wszystko_OK=false;
			$_SESSION['e_regulamin']="Potwierdź akceptację regulaminu!";
		}
		
		//Antybot
		$sekret="6Ldt8tkZAAAAAP64HL5xgLicSLIg8kLchf8-LgXP";

//		$sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$sekret.'&response='.$POST_[

		$sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$sekret.'&response='.$_POST['g-recaptcha-response']);
		
		$odpowiedz = json_decode($sprawdz);
		

		
		if($odpowiedz->success==false){
			$wszystko_OK=false;
			$_SESSION['e_bot']="Potwierdź, że nie jesteś robotem!";
		}
 		
		if($wszystko_OK==true){
			// Dodać gracza do bazy
			echo "Udana walidacja!";
			exit();
			
		}
	}
	
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Plemiona - rejestracja</title>
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	
	<style>
		.error{
			color: red;
			margin-top: 10px;
			margin-bottom: 10px;
		}
	</style>
	
	
</head>

<body>

	<form method="post"> <!-- bez atrybutu 'action' dane otrzyma ten sam plik -->

		Nick: <br /><input type="text" name="nick" /><br />
		
		<?php
			if (isset($_SESSION['e_nick'])){
				echo '<div class="error">'.$_SESSION['e_nick'].'</div>';	//Komentarz: nieprawidłowy nick
				unset($_SESSION['e_nick']);
			}
		?>
		
		E-mail:<br /><input type="text" name="email" /><br />
		
		<?php
			if (isset($_SESSION['e_email'])){
				echo '<div class="error">'.$_SESSION['e_email'].'</div>';	//Komunikat: nieprawidłowy e-mail
				unset($_SESSION['e_email']);	
			}
		?>	
		
		Hasło: <br /><input type="password" name="haslo1" /><br />
		
		<?php
			if (isset($_SESSION['e_haslo1'])){
				echo '<div class="error">'.$_SESSION['e_haslo1'].'</div>';	//Komunikat: złą długość hasła
				unset($_SESSION['e_haslo1']);								
				
			}
		?>
		
		Powtórz hasło: <br /><input type="password" name="haslo2" /><br />
		
		<?php
			if (isset($_SESSION['e_haslo2'])){
				echo '<div class="error">'.$_SESSION['e_haslo2'].'</div>';	//Komunikat: różne hasła
				unset($_SESSION['e_haslo2']);								
				
			}
		?>
		
		<label>
			<input type="checkbox" name="regulamin"/>Akceptuję regulamin
		</label>
	
		<?php
			if (isset($_SESSION['e_regulamin'])){
				echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';	//Komunikat: brak akceptacji regulaminu
				unset($_SESSION['e_regulamin']);								
				
			}
		?>
	
		<div class="g-recaptcha" data-sitekey="6Ldt8tkZAAAAAEdl2tKe6OTMiSLUxWiJVzLTt3UF"></div>
		
		<?php
			if (isset($_SESSION['e_bot'])){
				echo '<div class="error">'.$_SESSION['e_bot'].'</div>';	//Komunikat: antybot
				unset($_SESSION['e_bot']);								
				
			}
		?>
		
		<input type="submit" value="Zarejestruj" />
	</form>
</body>
</html>