	<?php
		if(isset($_SESSION['wybranaApteczka'])) {
			// form - wyszukanie leku
			$form = '<div class="container" style="max-width:50%">';
				$form .= '<form action="00.php?mn=5&mm=1" method="post"><fieldset>';
				$form .= '<div class="form-group"><label for="nazwa">Nazwa</label>';
				$form .= '<input type="text" class="form-control" name="nazwa" id="nazwa" required></div>';
				$form .= '<div class="form-group"><label for="postac">Postać</label>';
				$form .= '<input type="text" class="form-control" name="postac" id="postac"></div>';
				$form .= '<div class="form-group"><label for="opakowanie">Opakowanie</label>';
				$form .= '<input type="text" class="form-control" name="opakowanie" id="opakowanie"></div>';
				$form .= '<div class="form-group"><label for="ean">Kod kreskowy</label>';
				$form .= '<input type="text" class="form-control" name="ean" id="ean"></div>';
				$form .= '<input type="submit" class="btn btn-primary" value="Wyszukaj">';
				$form .= '</fieldset></form></div>';
			echo $form;
			
			// wyszukanie leku
			if(isset($_POST['nazwa']) || isset($_POST['postac']) || isset($_POST['opakowanie']) || isset($_POST['ean'])){
				$nazwa = trim($_POST['nazwa']);
				$nazwa = '"%' . $nazwa . '%"';
				$postac = trim($_POST['postac']);
				$postac = '"%' . $postac . '%"';
				$opakowanie = trim($_POST['opakowanie']);
				$opakowanie = '"%' . $opakowanie . '%"';
				$ean = trim($_POST['ean']);
				$ean = '"%' . $ean . '%"';
				$kwerenda = "SELECT * FROM ListaLekow WHERE (NazwaHandlowa LIKE $nazwa AND Postac LIKE $postac AND Opakowanie LIKE $opakowanie AND KodKreskowy LIKE $ean)";
				$result = Zapytanie($kwerenda);
				echo "<br><div class='container'>Liczba znalezionych rekordów: " . $result->num_rows . "</div><br>";
				if($result->num_rows > 25){
					echo "Zbyt wiele rekordów do wyświetlenia. Doprecyzuj wyszukiwanie." . "<br>";
				} else {
					if($result->num_rows > 0){
						$form = '<div class="container" style="max-width:50%">';
						$form .= '<form action="00.php?mn=5&mm=1" method="post">';
						$form .= '<div class="container">';
						$form .= '<table class="table table-hover"><thead><tr>';
						$form .= '<th scope="col"></th>';
						$form .= '<th scope="col">Nazwa leku</th>';
						$form .= '<th scope="col">Opakowanie</th>';
						$form .= '<th scope="col">Dawka</th>';
						$form .= '<th scope="col">Kod kreskowy</th></tr></thead><tbody>';
						while($row = $result->fetch_assoc()){
							$form .= '<tr class="table-light">';
							$form .= '<th scope="row"><input type="radio" name="pozycja" value=' . $row["id"] . '</th>';
							$form .= '<td>' . $row["NazwaHandlowa"] . '</td>';
							$form .= '<td>' . $row["Opakowanie"] . '</td>';
							$form .= '<td>' . $row["Dawka"] . '</td>';
							$form .= '<td>' . $row["KodKreskowy"] . '</td></tr>';
						}
						$form .= "</tbody></table></div>";
						
						// form - wybor ilosci, ceny, daty waznosci
						
						$form .= '<fieldset><div class="form-group"><label for="sztuki">Liczba sztuk</label>';
						$form .= '<input type="number" class="form-control" name="sztuki" id="sztuki" required></div>';
						$form .= '<div class="form-group"><label for="cena">Cena</label>';
						$form .= '<input type="number" class="form-control" name="cena" id="cena"></div>';
						$form .= '<div class="form-group"><label for="datawaznosci">Data ważności</label>';
						$form .= '<input type="date" class="form-control" name="datawaznosci" id="datawaznosci"></div>';
						$form .= '<input type="submit" class="btn btn-primary" value="Dodaj">';
						$form .= '</fieldset></form></div>';
						echo $form;
					} else {
						echo "Zwrócono 0 rekordów<br>";
					}
				}
			}
	
			// okreslenie ilosci, daty waznosci, ceny
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
				$nazwaApteczki = $_SESSION['wybranaApteczka'];
				$kwerenda = "SELECT `id` FROM `apteczki` WHERE `nazwa`='$nazwaApteczki'";
				$result = Zapytanie($kwerenda);
				$idApteczki = $result->fetch_object()->id;
				
				// dodanie do bazy
				$kwerenda = "INSERT INTO `kpi`.`RuchLekow` (`id`, `id_apteczki`, `id_uzytkownika`, `id_leku`,  `id_dokumentu`, `ilosc`, `data_waznosci`, `cena`, `data_operacji`, `pozostalo`) " . 
					"VALUES (NULL, '$idApteczki', '$IdUser', '$id', '1', '$sztuki', '$datawaznosci', '$cena', '$time', '$sztuki')";
				$result = Zapytanie($kwerenda);
				echo "<div class='container'><br>Do bazy dodano następującą pozycję: <br>" . "Nazwa handlowa: " . 
					$row["NazwaHandlowa"] . "<br>" . "Kod kreskowy: " . $row["KodKreskowy"] . "<br>" . "Postać: " .
					$row["Postac"] . "<br>" . "Opakowanie: " . $row["Opakowanie"] . "<br>" . "Liczba sztuk: " . $sztuki . 
					"<br>" . "Czas: ". $time . "</div>";
			}
		}
		else echo "<div class='container'>Wybierz apteczkę, aby dodać lek.</div>";
	?>