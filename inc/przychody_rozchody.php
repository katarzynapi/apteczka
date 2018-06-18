 <br><h5 class="display-4 container">Przychody/rozchody</h5><br>

 <?php
	if(isset($_SESSION['wybranaApteczka'])) {
		$form = '<form action="00.php?mn=7&mr=3" method="post">';
			$form .= '<div class="container"><fieldset>';
			$form .= '<div class="form-group" style="max-width:50%">';
			$form .= '<label for="nazwa">Nazwa</label>';
			$form .= '<input type="text" class="form-control" name="nazwa" id="nazwa" required></div></fieldset>';
			$form .= '<input type="submit" class="btn btn-primary" value="Wyszukaj"></div></form>';
		echo $form;

		$nazwaApteczki = $_SESSION['wybranaApteczka'];
		$kwerenda = "SELECT id FROM apteczki WHERE nazwa = '$nazwaApteczki'";
		$result = Zapytanie($kwerenda);
		$idApteczki = $result->fetch_object()->id;
		
		if(isset($_POST['nazwa'])){
			$nazwa = trim($_POST['nazwa']);
			
			$kwerenda = "SELECT `id`, `id_leku` FROM `RuchLekow` WHERE `id_dokumentu` = '1' AND `id_apteczki` = '$idApteczki' AND `pozostalo` > 0 ORDER BY `data_waznosci`";
			$result = Zapytanie($kwerenda);
			if($result->num_rows > 0){
				$form = '<div class="container" style="max-width:50%">';
					$form .= '<form action="00.php?mn=7&mr=3" method="post">';
					$form .= '<div class="container">';
					$form .= '<table class="table table-hover"><thead><tr>';
					$form .= '<th scope="col"></th>';
					$form .= '<th scope="col">Nazwa leku</th>';
					$form .= '<th scope="col">Opakowanie</th>';
					$form .= '<th scope="col">Dawka</th></tr></thead><tbody>';
				$znalezione = false; // czy istnieje lek o takiej samej nazwie
				while($row = $result->fetch_assoc()){
					$idRLekow = $row["id"];
					$idLeku = $row["id_leku"];
					$kwerenda = "SELECT `NazwaHandlowa`, `Opakowanie`, `Dawka` FROM `ListaLekow` WHERE `id` = '$idLeku'";
					$result2 = Zapytanie($kwerenda);
					$row2 = $result2->fetch_assoc();
					if(strpos(strtolower($row2['NazwaHandlowa']), strtolower($nazwa)) !== FALSE) { // porownanie dwoch stringow
						$znalezione = true;
						$form .= '<tr class="table-light">';
							$form .= '<th scope="row"><input type="radio" name="pozycja" value=' . $idLeku . '</th>';
							$form .= '<td>' . $row2["NazwaHandlowa"] . '</td>';
							$form .= '<td>' . $row2["Opakowanie"] . '</td>';
							$form .= '<td>' . $row2["Dawka"] . '</td></tr>';
					}
					
				}
				$form .= "</tbody></table></div>";
					
				// form - wybor okresu czasu
				if($znalezione) {
					$form .= '<div class="form-group"><label for="okres">Okres czasu</label>';
						$form .= '<select class="form-control" id="okres" name="okres" required>';
						$form .= '<option value="1">Pół roku</option>';
						$form .= '<option value="2">Rok</option>';
						$form .= '<option value="3">Dwa lata</option></select></div>';		
						$form .= '<input type="submit" class="btn btn-primary" value="Generuj raport">';
				}
				else echo "<div class='container'>Nie znaleziono leku.</div>";
				$form .= '</form></div>';
				echo $form;
			} else echo "Zwrócono 0 rekordów<br>";	
		}

		if(isset($_POST['okres'])) {
			$idLeku = $_POST['pozycja'];
			$okresCzasu = $_POST['okres'];
			switch($okresCzasu) {
				case 1:
					$liczbaMiesiecy = 6;
					break;
				case 2:
					$liczbaMiesiecy = 12;
					break;
				case 3:
					$liczbaMiesiecy = 24;
					break;
				default:
			}
			
			$form = '<br><div class="container">';
				$form .= '<table class="table table-hover"><thead><tr>';
				$form .= '<th scope="col">Od</th>';
				$form .= '<th scope="col">Do</th>';
				$form .= '<th scope="col">Ilość zakupionych</th>';
				$form .= '<th scope="col">Ilość zużytych</th>';
				$form .= '<th scope="col">Ilość zutylizowanych</th></tr></thead><tbody>';
			
			while($liczbaMiesiecy > 0) {
				$odKiedy = "-" . $liczbaMiesiecy . " month + 1 day";
				$doKiedy = "-" . $liczbaMiesiecy+1 . " month";
				$dataRef1 = date_create($odKiedy)->format('Y-m-d');
				$dataRef2 = date_create($doKiedy)->format('Y-m-d');
				$liczbaMiesiecy = $liczbaMiesiecy - 1;
			
				$kwerenda = "SELECT SUM(`ilosc`) AS sumaZakupionych FROM `RuchLekow` WHERE `id_leku`='$idLeku' AND `id_dokumentu` = '1' AND `id_apteczki` = '$idApteczki'" . 
					"AND `data_operacji` >= '$dataRef1' AND `data_operacji` <= '$dataRef2'";
				$result = Zapytanie($kwerenda);
				$sumaZakupionych = $result->fetch_object()->sumaZakupionych;
				if($sumaZakupionych == NULL) $sumaZakupionych = 0;
				
				$kwerenda = "SELECT SUM(`ilosc`) AS sumaZuzytych FROM `RuchLekow` WHERE `id_leku`='$idLeku' AND `id_dokumentu` = '2' AND `id_apteczki` = '$idApteczki'" . 
					"AND `data_operacji` >= '$dataRef1' AND `data_operacji` <= '$dataRef2'";
				$result = Zapytanie($kwerenda);
				$sumaZuzytych = $result->fetch_object()->sumaZuzytych;
				if($sumaZuzytych == NULL) $sumaZuzytych = 0;
				
				$kwerenda = "SELECT SUM(`ilosc`) AS sumaZutylizowanych FROM `RuchLekow` WHERE `id_leku`='$idLeku' AND `id_dokumentu` = '3' AND `id_apteczki` = '$idApteczki'" . 
					"AND `data_operacji` >= '$dataRef1' AND `data_operacji` <= '$dataRef2'";
				$result = Zapytanie($kwerenda);
				$sumaZutylizowanych = $result->fetch_object()->sumaZutylizowanych;
				if($sumaZutylizowanych == NULL) $sumaZutylizowanych = 0;
				
				$form .= '<tr class="table-light">';
					$form .= '<td>' . $dataRef1 . '</td>';
					$form .= '<td>' . $dataRef2 . '</td>';
					$form .= '<td>' . $sumaZakupionych . '</td>';
					$form .= '<td>' . $sumaZuzytych . '</td>';
					$form .= '<td>' . $sumaZutylizowanych . '</td></tr>';
			}
			
			$form .= "</tbody></table></div>";
			echo $form;
		}
	}
 ?>