<?php
	$user = $_SESSION['user'];
	echo "<div class=\"container\"><H4>Dodawanie nowej apteczki:</H4></div>";
	$form = "<div class=\"container\"><form action=\"00.php?mn=6\" method=\"post\">";
		$form .= "Nazwa apteczki: <input type=\"text\" name=\"nazwaApteczki1\" required> <br>";
		$form .= "Hasło do apteczki: <input type=\"password\" name=\"hasloApteczki1\" required> <br>";
		$form .= "<input type=\"submit\" class=\"btn btn-primary\"value=\"Dodaj apteczkę\">";
		$form .= "</form>";
	 echo $form;
	 
	 if(isset($_POST['nazwaApteczki1']) && isset($_POST['hasloApteczki1'])){
		$nazwaApteczki = trim($_POST['nazwaApteczki1']);
		$hasloApteczki = md5(trim($_POST['hasloApteczki1']));
		$user = $_SESSION['user'];
		
		$kwerenda = "SELECT nazwa FROM apteczki WHERE nazwa = '$nazwaApteczki'";
		$result = Zapytanie($kwerenda);
		$nazwaApteczkiTest = $result->fetch_object()->nazwa;
		if($nazwaApteczkiTest != "") echo "Istnieje apteczka o takiej nazwie";
		else{
			$kwerenda = "SELECT id FROM test_users WHERE email = '$user'";
			$result = Zapytanie($kwerenda);
			$idUser = $result->fetch_object()->id;
		
	 		$kwerenda = "INSERT INTO `kpi`.`apteczki` (`id`, `nazwa`, `haslo`, `admin_id`) VALUES (NULL, '$nazwaApteczki', '$hasloApteczki', '$idUser')";
	 		$result = Zapytanie($kwerenda);
		
			$kwerenda = "SELECT id FROM apteczki WHERE nazwa = '$nazwaApteczki'";
			$result = Zapytanie($kwerenda);
			$idApteczki = $result->fetch_object()->id;
		
	 		$kwerenda = "INSERT INTO `kpi`.`Dostep` (`id`, `id_uzytkownika`, `id_apteczki`, `czy_admin`) VALUES (NULL, '$idUser', '$idApteczki', '1')";
	 		$result = Zapytanie($kwerenda);
		
			echo "Do bazy dodano nową apteczkę.";
		}
	}
	
	echo "<H4 style=\"margin-top: 30px\">Dodawanie nowych użytkowników apteczki (tylko admin):</H4></div>";
	$form = "<div class=\"container\"><form action=\"00.php?mn=6\" method=\"post\">";
		$form .= "Nazwa apteczki: <input type=\"text\" name=\"nazwaApteczki2\" required> <br>";
		$form .= "Nazwa użytkownika: <input type=\"text\" name=\"nazwaUzytkownika\" required> <br>";
		$form .= "<input type=\"submit\" class=\"btn btn-primary\"value=\"Dodaj do apteczki\">";
		$form .= "</form>";
	 echo $form;
	 
	 if(isset($_POST['nazwaApteczki2']) && isset($_POST['nazwaUzytkownika'])){
		$nazwaApteczki = trim($_POST['nazwaApteczki2']);
		$nazwaUzytkownika = trim($_POST['nazwaUzytkownika']);
		$user = $_SESSION['user'];
		
		$kwerenda = "SELECT id FROM apteczki WHERE nazwa = '$nazwaApteczki'";
		$result = Zapytanie($kwerenda);
		$idApteczki = $result->fetch_object()->id;
		
		$kwerenda = "SELECT id FROM test_users WHERE email = '$user'";
		$result = Zapytanie($kwerenda);
		$idAdmin = $result->fetch_object()->id;
		
		$kwerenda = "SELECT czy_admin FROM Dostep WHERE id_apteczki = '$idApteczki' AND id_uzytkownika = '$idAdmin'";
		$result = Zapytanie($kwerenda);
		$czy_admin = $result->fetch_object()->czy_admin;
		if($czy_admin == NULL || $czy_admin == 0) echo "Nie możesz dodać użytkownika, ponieważ nie jesteś administratorem wybranej apteczki.";
		else{
			$kwerenda = "SELECT id FROM test_users WHERE nazwa = '$nazwaUzytkownika'";
			$result = Zapytanie($kwerenda);
			$idUzytkownika = $result->fetch_object()->id;
			
			$kwerenda = "SELECT id_uzytkownika FROM Dostep WHERE id_apteczki = '$idApteczki'";
			$result = Zapytanie($kwerenda);
			if($idUzytkownika == NULL) echo "Użytkownik, którego chcesz dodać nie istnieje w bazie.";
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					 if($row["id_uzytkownika"] == $idAdmin || $row["id_uzytkownika"] == $idUzytkownika){
						 echo "Podany użytkownik już jest przypisany do wybranej apteczki.";
						 break;
					 }
		 			else{
		 	 			$kwerenda = "INSERT INTO `kpi`.`Dostep` (`id`, `id_uzytkownika`, `id_apteczki`, `czy_admin`) VALUES (NULL, '$idUzytkownika', '$idApteczki', '0')";
		 	 			$result = Zapytanie($kwerenda);
		 				echo "Do apteczki dodano nowego użytkownika.";
		 			}
				} 
			}

		}
	}
	
	echo "<H4 style=\"margin-top: 30px\">Dołączanie do apteczki (wymagane hasło dostępu):</H4></div>";
	$form = "<div class=\"container\"><form action=\"00.php?mn=6\" method=\"post\">";
		$form .= "Nazwa apteczki: <input type=\"text\" name=\"nazwaApteczki3\" required> <br>";
		$form .= "Hasło: <input type=\"password\" name=\"hasloApteczki2\" required> <br>";
		$form .= "<input type=\"submit\" class=\"btn btn-primary\"value=\"Dodaj mnie to apteczki\">";
		$form .= "</form>";
	 echo $form;
	 
	 if(isset($_POST['nazwaApteczki3']) && isset($_POST['hasloApteczki2'])){
		$nazwaApteczki = trim($_POST['nazwaApteczki3']);
		$hasloApteczki = md5(trim($_POST['hasloApteczki2']));
		$user = $_SESSION['user'];
		
		$kwerenda = "SELECT nazwa FROM apteczki WHERE nazwa = '$nazwaApteczki'";
		$result = Zapytanie($kwerenda);
		$nazwaApteczkiTest = $result->fetch_object()->nazwa;
		if($nazwaApteczkiTest == ""){
			echo "Nie istnieje apteczka o takiej nazwie lub nieprawidłowe hasłomm.";
		}
		$kwerenda = "SELECT haslo FROM apteczki WHERE nazwa = '$nazwaApteczki'";
		$result = Zapytanie($kwerenda);
		$hasloApteczkiTest = $result->fetch_object()->haslo;
		if($hasloApteczkiTest != $hasloApteczki){
			echo "Nie istnieje apteczka o takiej nazwie lub nieprawidłowe hasłojj.";
		}
		else{
			$kwerenda = "SELECT id FROM test_users WHERE email = '$user'";
			$result = Zapytanie($kwerenda);
			$idUser = $result->fetch_object()->id;
		
			$kwerenda = "SELECT id FROM apteczki WHERE nazwa = '$nazwaApteczki'";
			$result = Zapytanie($kwerenda);
			$idApteczki = $result->fetch_object()->id;
		
	 		$kwerenda = "INSERT INTO `kpi`.`Dostep` (`id`, `id_uzytkownika`, `id_apteczki`, `czy_admin`) VALUES (NULL, '$idUser', '$idApteczki', '0')";
	 		$result = Zapytanie($kwerenda);
		
			echo "Zostałeś/aś dodany/a do apteczki.";
		}
	}
?>

</div>