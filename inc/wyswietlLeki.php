 <?php
	if(isset($_SESSION['wybranaApteczka'])) {
		echo '<br><h5 class="display-4 container">Aktualna zawartość apteczki</h5><br>';
		$nazwaApteczki = $_SESSION['wybranaApteczka'];
		$kwerenda = "SELECT id FROM apteczki WHERE nazwa = '$nazwaApteczki'";
		$result = Zapytanie($kwerenda);
		$idApteczki = $result->fetch_object()->id;
		
		$kwerenda = "SELECT id_leku, data_waznosci, pozostalo FROM `RuchLekow` WHERE `id_apteczki` = '$idApteczki' AND  `pozostalo` > 0";
		$result = Zapytanie($kwerenda);
		
		$form = '<div class="container">';
			$form .= '<table class="table table-hover"><thead><tr>';
			$form .= '<th scope="col">Nazwa leku</th>';
			$form .= '<th scope="col">Opakowanie</th>';
			$form .= '<th scope="col">Dawka</th>';
			$form .= '<th scope="col">Ilość</th>';
			$form .= '<th scope="col">Data ważności</th></tr></thead><tbody>';
		
		while($row = $result->fetch_assoc()){
			$id = $row['id_leku'];
			$data = $row['data_waznosci'];
			$ilosc = $row['pozostalo'];
			$kwerenda = "SELECT `NazwaHandlowa`, `Opakowanie`, `Dawka` FROM `ListaLekow` WHERE `id` = '$id'";
			$result2 = Zapytanie($kwerenda);
			$row2 = $result2->fetch_assoc();
			$nazwa = $row2['NazwaHandlowa'];
			$opakowanie = $row2['Opakowanie'];
			$dawka = $row2['Dawka'];
			
			$form .= '<tr class="table-light">';
			$form .= '<td>' . $nazwa . '</td>';
			$form .= '<td>' . $opakowanie . '</td>';
			$form .= '<td>' . $dawka . '</td>';
			$form .= '<td>' . $ilosc . '</td>';
			$form .= '<td>' . $data . '</td></tr>';
		}
		
		$form .= "</tbody></table></div>";
		echo $form;

	} 
	else echo '<div class="container">Wybierz apteczkę, aby wyświetlić jej zawartość.</div>';
 ?>