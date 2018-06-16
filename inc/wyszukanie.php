<?php
if(isset($_SESSION['wybranaApteczka'])) {
	echo "Apteczka : " . $_SESSION['wybranaApteczka'] . "<br>";
	$form = "<form action=\"00.php?mn=3\" method=\"post\">";
		$form .= "Nazwa: <input type=\"text\" name=\"nazwa\" required> <br>";
		$form .= "Postac: <input type=\"text\" name=\"postac\"> <br>";
		$form .= "Opakowanie: <input type=\"text\" name=\"opakowanie\"> <br>";
		$form .= "<input type=\"submit\" value=\"Wyszukaj\">";
		$form .= "</form>";
	echo $form;
	 
	if(isset($_POST['nazwa']) || isset($_POST['postac']) || isset($_POST['opakowanie'])){
		// Sprawdzam czy podane konto istnieje
		$nazwa = trim($_POST['nazwa']);
		$nazwa = "\"%" . $nazwa . "%\"";
		$postac = trim($_POST['postac']);
		$postac = "\"%" . $postac . "%\"";
		$opakowanie = trim($_POST['opakowanie']);
		$opakowanie = "\"%" . $opakowanie . "%\"";
		$kwerenda = "SELECT * FROM ListaLekow WHERE (NazwaHandlowa LIKE $nazwa AND Postac LIKE $postac AND Opakowanie LIKE $opakowanie)";
		$result = Zapytanie($kwerenda);
		echo "<br>Liczba znalezionych rekordów: " . $result->num_rows . "<br><br>";
		if($result->num_rows > 25){
			echo "Zbyt wiele rekordów do wyświetlenia. Doprecyzuj wyszukiwanie." . "<br>";
		} else {
			if($result->num_rows > 0){
				$form = "<form action=\"00.php?mn=3\" method=\"post\">";
				$i = 1;
				$form .= '<div class="container">';
				$form .= '<table class="table table-hover"><thead><tr>';
				$form .= '<th scope="col"></th>';
				$form .= '<th scope="col">Nazwa leku</th>';
				$form .= '<th scope="col">Opakowanie</th>';
				$form .= '<th scope="col">Dawka</th>';
				$form .= '<th scope="col">Kod kreskowy</th></tr></thead><tbody>';
		
				//$form .= '<table><th></th><th>Nazwa leku</th><th>Opakowanie</th><th>Dawka</th><th>Kod kreskowy</th>';
				while($row = $result->fetch_assoc()){
					$form .= '<tr class="table-light">';
					$form .= '<th scope="row"><input type="radio" name="pozycja" value=' . $row["id"] . '</th>';
					$form .= '<td>' . $row["NazwaHandlowa"] . '</td>';
					$form .= '<td>' . $row["Opakowanie"] . '</td>';
					$form .= '<td>' . $row["Dawka"] . '</td>';
					$form .= '<td>' . $row["KodKreskowy"] . '</td></tr>';
					
					//$form .= '<tr><td><input type="radio" name="pozycja" value=' . $row["id"] . '</td><td>' . $row["NazwaHandlowa"] . "</td><td>";
					//$form .= $row["Opakowanie"] . "</td><td>" . $row["Dawka"] . "</td><td>" . $row['KodKreskowy'] . "</td></tr>";
					
					//$form .= $i . ". Nazwa handlowa: " . $row["NazwaHandlowa"] . "<br>" . "Kod kreskowy: " . $row["KodKreskowy"] . "<br>" . "Postać: " . $row["Postac"] . "<br>" . "Opakowanie: " . $row["Opakowanie"];
					//$id = $row["id"];
					//$form .= " <input type=\"radio\" name=\"pozycja\" value=\"$id\"><br><br>";
					//$i = $i + 1;
				}
				$form .= "</tbody></table></div>";
				$form .= " Liczba sztuk: <input type=\"text\" name=\"sztuki\" required><br>";
				$form .= " Cena: <input type=\"numeric\" name=\"cena\" required><br>";
				$form .= " Data ważności: <input type=\"date\" name=\"datawaznosci\" required><br>";
				$form .= "<input type=\"submit\" value=\"Dodaj\">";
				echo $form;
			} else {
				echo "Zwrócono 0 rekordów<br>";
			}
		}
	}

	if(isset($_POST['pozycja']) && isset($_POST['sztuki']) && isset($_POST['cena']) && isset($_POST['datawaznosci'])){
		$id = $_POST['pozycja'];
		$sztuki = $_POST['sztuki'];
		$user = $_SESSION['user'];
		$cena = $_POST['cena'];
		$datawaznosci = $_POST['datawaznosci'];
		date_default_timezone_set("Europe/Warsaw");
		$time = date("Y-m-d G:i:s");
		$kwerenda = "SELECT id FROM test_users WHERE email = '$user'";
		$result = Zapytanie($kwerenda);
		$IdUser = $result->fetch_object()->id;
		$kwerenda = "SELECT * FROM ListaLekow WHERE id = '$id'";
		$result = Zapytanie($kwerenda);
		$row = $result->fetch_assoc();
		$kodKreskowy = $row["KodKreskowy"];
		
		// id apteczki
		$nazwaApteczki = $_SESSION['wybranaApteczka'];
		$kwerenda = "SELECT `id` FROM `apteczki` WHERE `nazwa`='$nazwaApteczki'";
		$result = Zapytanie($kwerenda);
		$idApteczki = $result->fetch_object()->id;
		
		// dodanie do bazy
		$kwerenda = "INSERT INTO `kpi`.`RuchLekow` (`id`, `id_apteczki`, `id_uzytkownika`, `id_leku`,  `id_dokumentu`, `ilosc`, `data_waznosci`, `cena`, `data_operacji`, `pozostalo`) " . 
			"VALUES (NULL, '$idApteczki', '$IdUser', '$id', '1', '$sztuki', '$datawaznosci', '$cena', '$time', '$sztuki')";
		$result = Zapytanie($kwerenda);
		echo "<br>Do bazy dodano następującą pozycję: <br>" . "Nazwa handlowa: " . $row["NazwaHandlowa"] . "<br>" . "Kod kreskowy: " . $row["KodKreskowy"] . "<br>" . "Postać: " . $row["Postac"] . "<br>" . "Opakowanie: " . $row["Opakowanie"] . "<br>" . "Liczba sztuk: " . $sztuki . "<br>" . "Czas: ". $time;
	}
}
else echo "<div class='container'>Wybierz apteczkę, aby dodać lek.</div>";

?>

