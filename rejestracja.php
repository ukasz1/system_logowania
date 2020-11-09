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
		
		//	/|\
		//	 |____ pobiera zawartość pliku z odpowiedzią Google'a
		
		$odpowiedz = json_decode($sprawdz);	//dekodowanie z formatu JSONa


		
		if($odpowiedz->success==false){		//czy walidacja się udała
			$wszystko_OK=false;
			$_SESSION['e_bot']="Potwierdź, że nie jesteś robotem!";
		}
		
		//Zapamiętanie wspowadzonych danych
		$_SESSION['form_nick']=$nick;
		$_SESSION['form_email']=$email;
		$_SESSION['form_nick']=$nick;
		
		if(isset($_POST['regulamin'])) $_SESSION['form_regulamin']=true;
		
		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT); // zamiast warningów użyj Exception (żeby nie ujawniać szczegółów użytkownikowi)
		
		try{
			$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
			if($polaczenie->connect_errno!=0){
				throw new Exception(mysqli_connect_errno());
			}
			else{
				//Czy email już istnieje?
				$rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE email='$email'");
				
				if(!$rezultat) throw new Exception($polaczenie->error);		//gdyby nie udało się pobrać rekordu
			
				$ile_takich_maili = $rezultat->num_rows;

				if($ile_takich_maili>0){
					$wszystko_OK=false;
					$_SESSION['e_email']="Istnieje już konto przypisane do tego adresu e-mail!";
				}
				
				//Czy login już istnieje?
				$rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE user='$nick'");
				
				if(!$rezultat) throw new Exception($polaczenie->error);		//gdyby nie udało się pobrać rekordu
			
				$ile_takich_nickow = $rezultat->num_rows;

				if($ile_takich_nickow>0){
					$wszystko_OK=false;
					$_SESSION['e_nick']="Istnieje już gracz o takim nicku!";
				}
				
				
				if($wszystko_OK==true){
				
				// Dodawanie gracza do bazy
				
				if($polaczenie->query("INSERT INTO uzytkownicy VALUES (NULL, '$nick', '$haslo_hash', '$email',100,100,100,14)")){
					$_SESSION['udanarejestracja']=true;
					header('Location: witamy.php');
				}
				else{
					throw new Exception($polaczenie->error);
				}
				exit();				
				}
				
				$polaczenie->close();
			}
		}
 		
		catch(Exception $e){	//$e - rodzaj błędu
			echo '<span style="color: red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie</span>';
			echo '<br />Informacja developerska: '.$e;	//komentarz o numerze błędu dla programisty
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

		Nick: <br /><input type="text" value="<?php
		if(isset($_SESSION['form_nick'])){
			echo $_SESSION['form_nick'];
			unset($_SESSION['form_nick']);
			
		}
		
		
		?>"name="nick" /><br />
		
		<?php
			if (isset($_SESSION['e_nick'])){
				echo '<div class="error">'.$_SESSION['e_nick'].'</div>';	//Komentarz: nieprawidłowy nick
				unset($_SESSION['e_nick']);
			}
		?>
		
		E-mail:<br /><input type="text" value="<?php
			if(isset($_SESSION['form_email'])){
				echo $_SESSION['form_email'];
				unset($_SESSION['form_email']);
				
			}
		
		?>"

		name="email" /><br />
		
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
			<input type="checkbox" name="regulamin" <?php
			if(isset($_SESSION['form_regulamin'])){
				echo "checked";
				unset($_SESSION['form_regulamin']);
			}
			
			?>
			/>Akceptuję regulamin
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