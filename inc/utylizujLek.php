 <?php
	if(isset($_SESSION['wybranaApteczka'])) {
		$nazwaApteczki = $_SESSION['wybranaApteczka'];
		$kwerenda = "SELECT id FROM apteczki WHERE nazwa = '$nazwaApteczki'";
		$result = Zapytanie($kwerenda);
		$idApteczki = $result->fetch_object()->id;
		
		// form - wyszukanie leku
		$form = '<br><h5 class="display-4 container">Utylizacja leku</h5><br>';
			$form .= '<div class="container" style="max-width:50%">';
			$form .= '<form action="00.php?mn=5&mm=3" method="post"><fieldset>';
			$form .= '<div class="form-group"><label for="nazwa">Nazwa</label>';
			$form .= '<input type="text" class="form-control" name="nazwa" id="nazwa" required></div>';
			$form .= '<input type="submit" class="btn btn-primary" value="Wyszukaj">';
			$form .= '</fieldset></form></div>';
		echo $form;
			
		// wyszukanie leku
		if(isset($_POST['nazwa'])){
			$nazwa = trim($_POST['nazwa']);
			
			$kwerenda = "SELECT `id`, `id_leku`, `pozostalo`, `data_waznosci` FROM `RuchLekow` WHERE `id_dokumentu` = '1' AND `id_apteczki` = '$idApteczki' AND `pozostalo` > 0 ORDER BY `data_waznosci`";
			$result = Zapytanie($kwerenda);
			if($result->num_rows > 0){
				$form = '<div class="container" style="max-width:50%">';
				$form .= '<form action="00.php?mn=5&mm=3" method="post">';
				$form .= '<div class="container">';
				$form .= '<table class="table table-hover"><thead><tr>';
				$form .= '<th scope="col"></th>';
				$form .= '<th scope="col">Nazwa leku</th>';
				$form .= '<th scope="col">Opakowanie</th>';
				$form .= '<th scope="col">Dawka</th>';
				$form .= '<th scope="col">Data ważności</th>';
				$form .= '<th scope="col">Ilość</th></tr></thead><tbody>';
				$znalezione = false; // czy istnieje lek o takiej samej nazwie
				while($row = $result->fetch_assoc()){
					$idRLekow = $row["id"];
					$idLeku = $row["id_leku"];
					$data_waznosci = $row["data_waznosci"];
					$kwerenda = "SELECT `NazwaHandlowa`, `Opakowanie`, `Dawka` FROM `ListaLekow` WHERE `id` = '$idLeku'";
					$result2 = Zapytanie($kwerenda);
					$row2 = $result2->fetch_assoc();
					if(strpos(strtolower($row2['NazwaHandlowa']), strtolower($nazwa)) !== FALSE) { // porownanie dwoch stringow
						$znalezione = true;
						$form .= '<tr class="table-light">';
						$form .= '<th scope="row"><input type="radio" name="pozycja" value=' . $idRLekow . '</th>';
						$form .= '<td>' . $row2["NazwaHandlowa"] . '</td>';
						$form .= '<td>' . $row2["Opakowanie"] . '</td>';
						$form .= '<td>' . $row2["Dawka"] . '</td>';
						$form .= '<td>' . $row["data_waznosci"] . '</td>';
						$form .= '<td>' . $row["pozostalo"] . '</td></tr>';
					}
					
				}
				$form .= "</tbody></table></div>";
				
				if($znalezione) $form .= '<input type="submit" class="btn btn-primary" value="Wybierz">';
				else echo "<div class='container'>Nie znaleziono leku.</div>";
				$form .= '</fieldset></form></div>';
				
				echo $form;
			} else {
				echo "Zwrócono 0 rekordów<br>";
			}
			
		}
	
		// okreslenie ilosci
		if(isset($_POST['pozycja'])){
			$idRuchLekow = $_POST['pozycja'];
			$kwerenda = "SELECT `pozostalo`, `ilosc`, `cena`, `id_leku`, `data_waznosci` FROM `RuchLekow` WHERE `id` = '$idRuchLekow'";
			$result = Zapytanie($kwerenda);
			$row = $result->fetch_assoc();
			$ilosc = $row['pozostalo'];
			$idLeku = $row['id_leku'];
			$data_waznosci = $row['data_waznosci'];
			$stracone_pieniadze = $row['pozostalo'] / $row['ilosc'] * $row['cena'];

			date_default_timezone_set("Europe/Warsaw");
			$time = date("Y-m-d G:i:s");
			$user = $_SESSION['user'];
			$kwerenda = "SELECT id FROM test_users WHERE email = '$user'";
			$result = Zapytanie($kwerenda);
			$IdUser = $result->fetch_object()->id;
			
			// dodanie do bazy
			$kwerenda = "INSERT INTO `kpi`.`RuchLekow` (`id`, `id_apteczki`, `id_uzytkownika`, `id_leku`, `id_ruchLekow`, `id_dokumentu`, `ilosc`, `data_waznosci`, `cena`, `data_operacji`, `pozostalo`) " . 
				"VALUES (NULL, '$idApteczki', '$IdUser', '$idLeku', '$idRuchLekow','3', '$ilosc', '$data_waznosci', '$stracone_pieniadze', '$time', '-1')";
			$result = Zapytanie($kwerenda);
			// update ilosci
			$kwerenda = "UPDATE `RuchLekow` SET `pozostalo`='0' WHERE `id`='$idRuchLekow'";
			$result = Zapytanie($kwerenda);
			echo "<div class='container'>Lek został zutylizowany.</div>";
		}
	}
	else echo "<div class='container'>Wybierz apteczkę, aby dodać lek.</div>";
?>