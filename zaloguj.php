<?php

	session_start(); 		//uzyskanie dostępu do sesji
	
	if(!isset($_POST['login']) || !isset($_POST['haslo'])){
		header('Location: index.php');
		exit();
	}

	require_once "connect.php";  //załącza skryp 'connect' do wysterowania połączenia z SQL

	$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name); //obiekt reprezentujący połączenie; @ sprawia, że PHP nie wyrzuci na ekran żadnych informacji

	if($polaczenie->connect_errno!=0){
		
		echo "Error: ".$polaczenie->connect_errno; // connect_errno - numer błędu
		
	}
	else{
	
		$login = $_POST['login'];
		$haslo = $_POST['haslo'];
		
		$login = htmlentities($login, ENT_QUOTES, "UTF-8");
		
		if($rezultat=@$polaczenie->query(
		sprintf("SELECT * FROM uzytkownicy WHERE user='%s'",
		mysqli_real_escape_string($polaczenie,$login))))			
		{											//jeśli zapytanie jest poprawne, np. bez literówek; $rezultat to obiekt pobranych rekordów + zabezpieczenie przed SQL injection
			$ilu_userow = $rezultat->num_rows;		//liczba zwracanych rekordów	
	
			if($ilu_userow>0){
				
				$wiersz=$rezultat->fetch_assoc();	//$wiersz to $rezultat ale zamiast indeksów liczbowych można się odwołać przez nazwy kolumn
													//czyli $wiersz to tablica asocjacyjna
				
				if(password_verify($haslo,$wiersz['pass']))
				{
					$_SESSION['zalogowany']=true;
														//$wiersz to $rezultat ale zamiast indeksów liczbowych można się odwołać przez nazwy kolumn
														//czyli $wiersz to tablica asocjacyjna
					
					$_SESSION['id']=$wiersz['id'];
					$_SESSION['user']=$wiersz['user'];	//przesłanie usera z rekordu do sesji
					$_SESSION['drewno']=$wiersz['drewno'];	
					$_SESSION['kamien']=$wiersz['kamien'];
					$_SESSION['zboze']=$wiersz['zboze'];
					$_SESSION['email']=$wiersz['email'];
					$_SESSION['dnipremium']=$wiersz['dnipremium'];
					
					unset($_SESSION['blad']);	//kasowanie zmiennej błędu loginu/hasła z sesji
					
					$rezultat->free();			//zwalnianie pamięci $rezultat'u
					
					header('Location:gra.php');
					}
				else{
					$_SESSION['blad']='<span style="color: red;">Nieprawdłowy login lub hasło</span>';
					header('Location:index.php');	//dobry login, złe hasło
				}
					
			}
			else{
				$_SESSION['blad']='<span style="color: red;">Nieprawdłowy login lub hasło</span>';
				header('Location:index.php');		//zły login, dowolne hasło
			}
			
		}
		
		$polaczenie->close();
	}

?>