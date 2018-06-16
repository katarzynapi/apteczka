
<?php
	$user = $_SESSION['user'];
	$kwerenda = "SELECT id FROM test_users WHERE email = '$user'";
	$result = Zapytanie($kwerenda);
	$idUser = $result->fetch_object()->id;
	
	$kwerenda = "SELECT * FROM Dostep WHERE id_uzytkownika = '$idUser'";
	$result = Zapytanie($kwerenda);
	$apteczki = array();
	$i = 0;
	if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$idApteczki = $row["id_apteczki"];
				$czyAdmin = $row["czy_admin"];
				$apteczki[$i][0] = $idApteczki;
				if($czyAdmin) $apteczki[$i][2] = "administrator";
				else $apteczki[$i][2] = "użytkownik";
				$i = $i + 1;
			} 
	}
	$liczbaApteczek = sizeof($apteczki);
	$form = "<div class=\"container\"><br><h5 class=\"display-4\">Wybór apteczki</h5><br>";
	$form .= "<form action=\"00.php?mn=2\" method=\"post\">";
	$form .= "<table class=\"table table-hover\"><thead><tr><th scope=\"col\">Lp</th><th scope=\"col\">Nazwa apteczki</th><th scope=\"col\">Dostęp</th>";
	$form .= "<th scope=\"col\">Wybór</th></tr></thead>";
	for ($row = 0; $row < $liczbaApteczek; $row++) {
		$idApteczki = $apteczki[$row][0];
		$kwerenda = "SELECT nazwa FROM apteczki WHERE id = '$idApteczki'";
		$result = Zapytanie($kwerenda);
		$apteczki[$row][1] = $result->fetch_object()->nazwa;
		$nazwaApteczki = $apteczki[$row][1];
		$Dostep = $apteczki[$row][2];
		$Lp = $row+1;
			//$form .= "<div class=\"form-check\"><label class=\"form-check-label\">";
			$form .= "<tbody><tr><th scope=\"row\">$Lp</th><td>$nazwaApteczki</td><td>$Dostep</td>";
			$form .= "<td><input type=\"radio\" class=\"form-check-input\" name=\"apteczki\" value=\"$row\">";
			$form .= "</td></tr></tbody>";
	}
	$form .= "</table>";
	$form .= "<input type=\"submit\" class=\"btn btn-primary\" value=\"Wybierz apteczkę\">";
	$form .= "</form></div>";
 	echo $form;
	
	if(isset($_POST['apteczki'])){
		$row = $_POST['apteczki'];
		$_SESSION['idWybranaApteczka'] = $apteczki[$row][0];
		$_SESSION['wybranaApteczka'] = $apteczki[$row][1];
		$_SESSION['dostep'] = $apteczki[$row][2];
	}
?>