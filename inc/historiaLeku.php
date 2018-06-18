 <?php
	if(isset($_SESSION['wybranaApteczka'])) {
			$nazwaApteczki = $_SESSION['wybranaApteczka'];
			$kwerenda = "SELECT id FROM apteczki WHERE nazwa = '$nazwaApteczki'";
			$result = Zapytanie($kwerenda);
			$idApteczki = $result->fetch_object()->id;
			
			$kwerenda = "SELECT DISTINCT `id_leku` FROM `RuchLekow` WHERE `id_apteczki` = '$idApteczki' AND `pozostalo` > 0 AND `id_dokumentu` = '1'";
			$result = Zapytanie($kwerenda);
			$liczbaLekow = $result->num_rows;
			$form = '<br><h5 class="display-4 container">Historia leku</h5><br>';
			$form .= '<div class="container">Aktualnie w apteczce znajduje się ' . $liczbaLekow . ' różnych leków.</div>';
			
			$form .= '<br><div class="container">Wybierz lek, aby wyświetlić historię operacji.<br><br>';
			$form .= '<form action="00.php?mn=7&mr=1" method="post"><fieldset>';
			$form .= '<div class="form-group" style="max-width:50%"><label for="nazwa">Nazwa</label>';
			$form .= '<input type="text" class="form-control" name="nazwa" id="nazwa" required></div>';
			$form .= '<input type="submit" class="btn btn-primary" value="Wyszukaj">';
			$form .= '</fieldset></form></div>';
			
			echo $form;
			
			// wyswietlenie leków
			if(isset($_POST['nazwa'])){
				$nazwa = trim($_POST['nazwa']);
				
				$kwerenda = "SELECT `id`, `id_leku`, `pozostalo`, `data_waznosci` FROM `RuchLekow` WHERE `id_dokumentu` = '1' AND `id_apteczki` = '$idApteczki' AND `pozostalo` > 0 ORDER BY `data_waznosci`";
				$result = Zapytanie($kwerenda);
				if($result->num_rows > 0){
					$form = '<div class="container" style="max-width:75%">';
					$form .= '<form action="00.php?mn=7&mr=1" method="post">';
					$form .= '<div class="container">';
					$form .= '<br><table class="table table-hover"><thead><tr>';
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
					$form .= '<input type="submit" class="btn btn-primary" value="Generuj raprot">';
					$form .= '</fieldset></form></div>';
					echo $form;
				} 
				
				if(!$znalezione) echo "<div class='container'>Nie znaleziono leku o podanej nazwie.</div>";
			}
			
			if(isset($_POST['pozycja'])) {
				$form = '<div class="container">';
				$idRuchLekow = $_POST['pozycja'];
				$kwerenda = "SELECT `id_uzytkownika`, `id_leku`, `data_operacji`, `id_dokumentu`, `ilosc` FROM `RuchLekow` WHERE `id` = '$idRuchLekow' OR `id_ruchLekow` = '$idRuchLekow'";
				$result = Zapytanie($kwerenda);
				$form .= '<br><table class="table table-hover"><thead><tr>';
					$form .= '<th scope="col">Typ operacji</th>';
					$form .= '<th scope="col">Data operacji</th>';
					$form .= '<th scope="col">Ilość</th>';
					$form .= '<th scope="col">Nazwa użytkownika</th></tr></thead><tbody>';
					
				while($row = $result->fetch_assoc()){
					$idUser = $row['id_uzytkownika'];
					$idLeku = $row['id_leku'];
					$dataOperacji = $row['data_operacji'];
					$ilosc = $row['ilosc'];
					$idDokumentu = $row['id_dokumentu'];
					
					switch($idDokumentu) {
						case 1:
							$nazwaDokumentu = "Zakup";
							break;
						case 2:
							$nazwaDokumentu = "Wydanie";
							break;
						case 3:
							$nazwaDokumentu = "Utylizacja";
							break;
						default:
					}
					
					$kwerenda = "SELECT `nazwa` FROM `test_users` WHERE `id` = '$idUser'";
					$result2 = Zapytanie($kwerenda);
					$nazwaUser = $result2->fetch_object()->nazwa;
					
					
					$form .= '<tr class="table-light">';
					$form .= '<td>' . $nazwaDokumentu . '</td>';
					$form .= '<td>' . $dataOperacji . '</td>';
					$form .= '<td>' . $ilosc . '</td>';
					$form .= '<td>' . $nazwaUser . '</td></tr>';
				}	
				
				$form .= "</tbody></table></div>";					
				$form .= '</fieldset></form></div>';
				echo $form;
					
			}
			
			
			
			
			
			
	}
	
	
 ?>