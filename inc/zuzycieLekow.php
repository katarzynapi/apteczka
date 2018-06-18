 <?php
	if(isset($_SESSION['wybranaApteczka'])) {
		echo '<br><h5 class="display-4 container">Prezentacja zużycia leków z apteczki</h5><br>';
		$nazwaApteczki = $_SESSION['wybranaApteczka'];
		$kwerenda = "SELECT id FROM apteczki WHERE nazwa = '$nazwaApteczki'";
		$result = Zapytanie($kwerenda);
		$idApteczki = $result->fetch_object()->id;
		
		echo '<h4 class="container">Wybierz użytkownika i opcjonalnie lek</h4>';
		
		$kwerenda = "SELECT id_uzytkownika FROM Dostep WHERE id_apteczki = '$idApteczki'";
		$result = Zapytanie($kwerenda);
		if($result->num_rows > 0){
			$form = '<div class="container" style="max-width:70%">';
			$form .= '<form action="00.php?mn=7&mr=0" method="post">';
			$form .= '<table class="table table-hover"><thead><tr>';
			$form .= '<th scope="col"></th>';
			$form .= '<th scope="col">Nazwa użytkownika</th></tr></thead><tbody>';
			while($row = $result->fetch_assoc()){
				$form .= '<tr class="table-light">';
				$idUzytkownika = $row["id_uzytkownika"];
				$kwerenda = "SELECT nazwa FROM test_users WHERE id = '$idUzytkownika'";
				$result1 = Zapytanie($kwerenda);
				$nazwaUzytkownika = $result1->fetch_object()->nazwa;
				$form .= '<th scope="row"><input type="radio" name="uzytkownik" value=' . $idUzytkownika . '</th>';
				$form .= '<td>' . $nazwaUzytkownika . '</td></tr>';
			}
			$form .= "</tbody></table><br>";
		
			$kwerenda = "SELECT `id_leku`, `data_waznosci` FROM `RuchLekow` WHERE `id_apteczki` = '$idApteczki' AND `id_dokumentu` = '1'";
			$result1 = Zapytanie($kwerenda);
			if($result1->num_rows > 0){
				$form .= '<table class="table table-hover"><thead><tr>';
				$form .= '<th scope="col"></th>';
				$form .= '<th scope="col">Nazwa leku</th>';
				$form .= '<th scope="col">Data ważności</th>';
				$form .= '<th scope="col">Opakowanie</th></tr></thead><tbody>';
				while($row1 = $result1->fetch_assoc()){
					$idLeku = $row1['id_leku'];
					$dataWaznosci = $row1['data_waznosci'];
					$kwerenda = "SELECT `NazwaHandlowa`, `Opakowanie` FROM `ListaLekow` WHERE `id` = '$idLeku'";
					$result2 = Zapytanie($kwerenda);
					$row2 = $result2->fetch_assoc();
					$nazwa = $row2['NazwaHandlowa'];
					$opakowanie = $row2['Opakowanie'];
					$form .= '<tr class="table-light">';
					$form .= '<th scope="row"><input type="radio" name="lek" value=' . $idLeku . '</th>';
					$form .= '<td>' . $nazwa . '</td>';
					$form .= '<td>' . $dataWaznosci . '</td>';
					$form .= '<td>' . $opakowanie . '</td></tr>';
				}
			}
			$form .= "</tbody></table>";
			$form .= 'Sprawdź zużycie leku/ów od: <input type="date" name="data"><br>';
			$form .= '<input type="submit" class="btn btn-primary" value="Pokaż"></div></form>';
			echo $form;
		}
			if(isset($_POST['uzytkownik']) && isset($_POST['data'])){
				if(isset($_POST['lek'])){
					$table = '<div class="container" style="max-width:70%">';
					$table .= '<table class="table table-hover"><thead><tr>';
					$table .= '<th scope="col">Nazwa leku</th>';
					$table .= '<th scope="col">Opakowanie</th>';
					$table .= '<th scope="col">Zużyta ilość</th>';
					$table .= '<th scope="col">Data</th></tr></thead><tbody>';
					$czyZlaData = true;
					$idLeku = $_POST['lek'];
					$wybranaData = $_POST['data'];
					$idUzytkownika = $_POST['uzytkownik'];
					echo "idleku" . $idLeku . "<br>";
					echo "wybrana data" . $wybranaData . "<br>";
					echo "idUzytkownika" . $idUzytkownika . "<br>";
					$kwerenda = "SELECT `data_operacji`, `ilosc` FROM `RuchLekow` WHERE `id_leku` = '$idLeku' AND `id_apteczki` = '$idApteczki' AND `id_uzytkownika` = '$idUzytkownika' AND `id_dokumentu`='2'";
					$result1 = Zapytanie($kwerenda);
					if($result1->num_rows > 0){
						while($row1 = $result1->fetch_assoc()){
							$dataOperacji = $row1["data_operacji"];
							$ilosc = $row1["ilosc"];
							if($dataOperacji >= $wybranaData){
								$kwerenda = "SELECT `NazwaHandlowa`, `Opakowanie` FROM `ListaLekow` WHERE `id` = '$idLeku'";
								$result2 = Zapytanie($kwerenda);
								$row2 = $result2->fetch_assoc();
								$nazwa = $row2['NazwaHandlowa'];
								$opakowanie = $row2['Opakowanie'];
								$table .= '<td>' . $nazwa . '</td>';
								$table .= '<td>' . $opakowanie . '</td>';
								$table .= '<td>' . $ilosc . '</td>';
								$table .= '<td>' . $dataOperacji . '</td></tr>';
								$czyZlaData = false;
							}
						}
						$table .= "</tbody></table>";
						if($czyZlaData) echo '<h6 class="container style="max-width:50%">Użytkownik nie zużył wybranego leku w tym terminie.</h6><br>';
						else echo $table;
					}
					else echo '<h6 class="container style="max-width:50%">Użytkownik w ogóle nie zużywał wybranego leku.</h6><br>';
				}
			else{
				$table = '<div class="container" style="max-width:70%">';
				$table .= '<table class="table table-hover"><thead><tr>';
				$table .= '<th scope="col">Nazwa leku</th>';
				$table .= '<th scope="col">Opakowanie</th>';
				$table .= '<th scope="col">Zużyta ilość</th>';
				$table .= '<th scope="col">Data</th></tr></thead><tbody>';
				$czyZlaData = true;
				$idUzytkownika = $_POST['uzytkownik'];
				$wybranaData = $_POST['data'];
				//echo "wybrana data" . $wybranaData . "<br>";
				//echo "idUzytkownika" . $idUzytkownika . "<br>";
				$kwerenda = "SELECT `id_leku`, `data_operacji`, `ilosc` FROM `RuchLekow` WHERE `id_apteczki` = '$idApteczki' AND `id_uzytkownika` = '$idUzytkownika' AND `id_dokumentu`=2";
				$result1 = Zapytanie($kwerenda);
				if($result1->num_rows > 0){
					while($row1= $result1->fetch_assoc()){
						$idLeku = $row1["id_leku"];
						$dataOperacji = $row1["data_operacji"];
						$ilosc = $row1["ilosc"];
						if($dataOperacji >= $wybranaData){
							$kwerenda = "SELECT `NazwaHandlowa`, `Opakowanie` FROM `ListaLekow` WHERE `id` = '$idLeku'";
							$result2 = Zapytanie($kwerenda);
							$row2 = $result2->fetch_assoc();
							$nazwa = $row2['NazwaHandlowa'];
							$opakowanie = $row2['Opakowanie'];
							$table .= '<td>' . $nazwa . '</td>';
							$table .= '<td>' . $opakowanie . '</td>';
							$table .= '<td>' . $ilosc . '</td>';
							$table .= '<td>' . $dataOperacji . '</td></tr>';
							$czyZlaData = false;
						}
					}
					$table .= "</tbody></table>";
					if($czyZlaData) echo '<h6 class="container style="max-width:50%">Wybrany użytkownik nie zużył żadnego leku z wybranej apteczki w tym terminie.</h6><br>';
					else echo $table;
				}
				else echo '<h6 class="container style="max-width:50%">Wybrany użytkownik nie zużył żadnego leku z wybranej apteczki.</h6><br>';

			}
		}	
	}
	else echo '<div class="container">Wybierz apteczkę, aby wyświetlić jej zawartość.</div>';
 ?>