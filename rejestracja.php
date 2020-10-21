<!-- https://www.google.com/recaptcha/admin/create - ReCAPTCHA -->




<?php

// 6Ldt8tkZAAAAAEdl2tKe6OTMiSLUxWiJVzLTt3UF site-key

// 6Ldt8tkZAAAAAP64HL5xgLicSLIg8kLchf8-LgXP php

	session_start();
	
	if(isset($_POST['email'])){			/*sprawdzenie czy był wysłany formularz, czyli czy istnieje tablica asocjacyjna $_POST[]*//*indeks tablicy może być dowolny*/
		
		//Udana walidacja
		$wszystko_OK=true;
		
		$nick=$_POST['nick'];
		
		if((strlen($nick)<3) || (strlen($nick)>20)){	//sprawdzenie długości nicku
			$wszystko_OK=false;
			$_SESSION['e_nick']='Nick musi posiadać od 3 do 20 znaków!';
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
				echo '<div class="error">'.$_SESSION['e_nick'].'</div>';
				unset($_SESSION['e_nick']);
				
			}
		?>
		
		E-mail:<br /><input type="text" name="email" /><br />
		
		Hasło: <br /><input type="password" name="haslo1" /><br />
		
		Powtórz hasło: <br /><input type="password" name="haslo2" /><br />
		
		<label>
			<input type="checkbox" name="regulamin"/>Akceptuję regulamin
		</label>
	
		<div class="g-recaptcha" data-sitekey="6Ldt8tkZAAAAAEdl2tKe6OTMiSLUxWiJVzLTt3UF"></div>
		
		<input type="submit" value="Zarejestruj" />
	</form>
</body>
</html>