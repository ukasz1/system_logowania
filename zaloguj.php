<?php

	session_start(); 		//uzyskanie dostępu do sesji

	require_once "connect.php";  //załącza skryp 'connect' do wysterowania połączenia z SQL

	$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name); //obiekt reprezentujący połączenie; @ sprawia, że PHP nie wyrzuci na ekran żadnych informacji

	if($polaczenie->connect_errno!=0){
		
		echo "Error: ".$polaczenie->connect_errno; // ."Opis: ",$polaczenie.)connect->error;
		
	}
	else{
	
		$login = $_POST['login'];
		$haslo = $_POST['haslo'];
		
		$sql="SELECT * FROM uzytkownicy WHERE user='$login' AND pass='$haslo'";			//zmienna $sql zawiera kwerendę dla bd
		
		if($rezultat=@$polaczenie->query($sql)){	//jeśli zapytanie jest poprawne, np. bez literówek; $rezultat to pobrane rekordy

			$ilu_userow = $rezultat->num_rows;		//liczba zwracanych rekordów	
	
			if($ilu_userow==1){
				$wiersz=$rezultat->fetch_assoc();	//$wiersz to $rezultat ale zamiast indeksów liczbowych można się odwołać przez nazwy kolumn
													//czyli $wiersz to tablica asocjacyjna
				
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
				header('Location:index.php');
			}
			
		}
		
		$polaczenie->close();
	}

?>