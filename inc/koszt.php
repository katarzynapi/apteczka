<br><h5 class="display-4 container">Raport o kosztach</h5><br>

<form action="00.php?mn=7&mr=2" method="post">
	<div class="container">
	<fieldset>
		<div class="form-group">
		  <label for="rodzaj_raportu">Wybierz grupę leków, na temat której chcesz uzyskać informacje:</label>
		  <select class="form-control" id="rodzaj_raportu" name="rodzaj_raportu" required>
			<option value="1">Zutylizowane leki</option>
			<option value="2">Zakupione leki</option>
			<option value="3">Zużyte leki</option>
		  </select>
		</div>
	</fieldset>
	<fieldset>
		<div class="form-group"><label for="od">Od</label>
		<input type="date" class="form-control" name="od" id="od" required></div>
		<div class="form-group"><label for="do">Do</label>
		<input type="date" class="form-control" name="do" id="do" required></div>
		<input type="submit" class="btn btn-primary" value="Generuj raport">
	</fieldset>
	</div>
</form>

<?php 
	if(isset($_POST['rodzaj_raportu'])) {
		$rodzaj_raportu = $_POST['rodzaj_raportu'];
		$od = $_POST['od'];
		$do = $_POST['do'];
		
		if(isset($_SESSION['wybranaApteczka'])) {
			$nazwaApteczki = $_SESSION['wybranaApteczka'];
			$kwerenda = "SELECT id FROM apteczki WHERE nazwa = '$nazwaApteczki'";
			$result = Zapytanie($kwerenda);
			$idApteczki = $result->fetch_object()->id;
			
			switch($rodzaj_raportu) {
				case 1:
					$suma = 0;
					$kwerenda = "SELECT `id_leku`, `ilosc`, `data_operacji`, `cena` FROM `RuchLekow`" . 
						"WHERE `data_operacji` >= '$od' AND `data_operacji` <= '$do' AND `id_dokumentu` = '3' AND `id_apteczki` = '$idApteczki'  ORDER BY `data_operacji`";
					$result = Zapytanie($kwerenda);
					if($result->num_rows > 0){
						$form = '<br><br><div class="container">';
							$form .= '<table class="table table-hover"><thead><tr>';
							$form .= '<th scope="col">Nazwa leku</th>';
							$form .= '<th scope="col">Opakowanie</th>';
							$form .= '<th scope="col">Dawka</th>';
							$form .= '<th scope="col">Data operacji</th>';
							$form .= '<th scope="col">Ilość</th>';
							$form .= '<th scope="col">Cena</th></tr></thead><tbody>';
							
						while($row = $result->fetch_assoc()) {
							$idLeku = $row['id_leku'];
							$ilosc = $row['ilosc'];
							$data_operacji = $row['data_operacji'];
							$cena = $row['cena'];
							$suma += $cena;
							
							$kwerenda = "SELECT `NazwaHandlowa`, `Opakowanie`, `Dawka` FROM `ListaLekow` WHERE `id` = '$idLeku'";
							$result2 = Zapytanie($kwerenda);
							$row2 = $result2->fetch_assoc();
							
							$form .= '<tr class="table-light">';
							$form .= '<td>' . $row2["NazwaHandlowa"] . '</td>';
							$form .= '<td>' . $row2["Opakowanie"] . '</td>';
							$form .= '<td>' . $row2["Dawka"] . '</td>';
							$form .= '<td>' . $data_operacji . '</td>';
							$form .= '<td>' . $ilosc . '</td>';
							$form .= '<td>' . $cena . '</td></tr>';
						}
						$form .= "</tbody></table></div>";
						$form .= "<br><div class='container'>Koszt zutylizowanych w danym okresie leków to <strong>$suma</strong> zł.</div><br>";
						echo $form;
					} else echo "Żaden lek nie został zutylizowany w tym okresie czasu.";
					break;
				case 2:
					$suma = 0;
					$kwerenda = "SELECT `id_leku`, `ilosc`, `data_operacji`, `cena` FROM `RuchLekow`" . 
						"WHERE `data_operacji` >= '$od' AND `data_operacji` <= '$do' AND `id_dokumentu` = '1' AND `id_apteczki` = '$idApteczki' ORDER BY `data_operacji`";
					$result = Zapytanie($kwerenda);
					if($result->num_rows > 0){
						$form = '<br><br><div class="container">';
							$form .= '<table class="table table-hover"><thead><tr>';
							$form .= '<th scope="col">Nazwa leku</th>';
							$form .= '<th scope="col">Opakowanie</th>';
							$form .= '<th scope="col">Dawka</th>';
							$form .= '<th scope="col">Data operacji</th>';
							$form .= '<th scope="col">Ilość</th>';
							$form .= '<th scope="col">Cena</th></tr></thead><tbody>';
							
						while($row = $result->fetch_assoc()) {
							$idLeku = $row['id_leku'];
							$ilosc = $row['ilosc'];
							$data_operacji = $row['data_operacji'];
							$cena = $row['cena'];
							$suma += $cena;
							
							$kwerenda = "SELECT `NazwaHandlowa`, `Opakowanie`, `Dawka` FROM `ListaLekow` WHERE `id` = '$idLeku'";
							$result2 = Zapytanie($kwerenda);
							$row2 = $result2->fetch_assoc();
							
							$form .= '<tr class="table-light">';
							$form .= '<td>' . $row2["NazwaHandlowa"] . '</td>';
							$form .= '<td>' . $row2["Opakowanie"] . '</td>';
							$form .= '<td>' . $row2["Dawka"] . '</td>';
							$form .= '<td>' . $data_operacji . '</td>';
							$form .= '<td>' . $ilosc . '</td>';
							$form .= '<td>' . $cena . '</td></tr>';
						}
						$form .= "</tbody></table></div>";
						$form .= "<br><div class='container'>Koszt zakupionych w danym okresie leków to <strong>$suma</strong> zł.</div><br>";
						echo $form;
					} else echo "Żaden lek nie został zakupiony w tym okresie czasu.";
					break;
				case 3:
					$suma = 0;
					$kwerenda = "SELECT `id_leku`, `ilosc`, `data_operacji`, `cena` FROM `RuchLekow`" . 
						"WHERE `data_operacji` >= '$od' AND `data_operacji` <= '$do' AND `id_dokumentu` = '2' AND `id_apteczki` = '$idApteczki' ORDER BY `data_operacji`";
					$result = Zapytanie($kwerenda);
					if($result->num_rows > 0){
						$form = '<br><br><div class="container">';
							$form .= '<table class="table table-hover"><thead><tr>';
							$form .= '<th scope="col">Nazwa leku</th>';
							$form .= '<th scope="col">Opakowanie</th>';
							$form .= '<th scope="col">Dawka</th>';
							$form .= '<th scope="col">Data operacji</th>';
							$form .= '<th scope="col">Ilość</th>';
							$form .= '<th scope="col">Cena</th></tr></thead><tbody>';
							
						while($row = $result->fetch_assoc()) {
							$idLeku = $row['id_leku'];
							$ilosc = $row['ilosc'];
							$data_operacji = $row['data_operacji'];
							$cena = $row['cena'];
							$suma += $cena;
							
							$kwerenda = "SELECT `NazwaHandlowa`, `Opakowanie`, `Dawka` FROM `ListaLekow` WHERE `id` = '$idLeku'";
							$result2 = Zapytanie($kwerenda);
							$row2 = $result2->fetch_assoc();
							
							$form .= '<tr class="table-light">';
							$form .= '<td>' . $row2["NazwaHandlowa"] . '</td>';
							$form .= '<td>' . $row2["Opakowanie"] . '</td>';
							$form .= '<td>' . $row2["Dawka"] . '</td>';
							$form .= '<td>' . $data_operacji . '</td>';
							$form .= '<td>' . $ilosc . '</td>';
							$form .= '<td>' . $cena . '</td></tr>';
						}
						$form .= "</tbody></table></div>";
						$form .= "<br><div class='container'>Koszt zużytych w danym okresie leków to <strong>$suma</strong> zł.</div><br>";
						echo $form;
					} else echo "Żaden lek nie został zużyty w tym okresie czasu.";
					break;
				default:
			}
		}
	}
?>
