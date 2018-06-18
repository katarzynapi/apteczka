<?php

if(isset($_SESSION['wybranaApteczka'])){
	$nazwaApteczki = $_SESSION['wybranaApteczka'];
	$kwerenda = "SELECT id FROM apteczki WHERE nazwa = '$nazwaApteczki'";
	$result = Zapytanie($kwerenda);
	$idApteczki = $result->fetch_object()->id;

	$kwerenda = "SELECT `id_leku` FROM `RuchLekow` WHERE `id_apteczki` = '$idApteczki' AND `id_dokumentu`='1'";
	$result = Zapytanie($kwerenda);
	
	$table = '<div class="container" style="max-width:70%">';
	$table .= '<table class="table table-hover"><thead><tr>';
	$table .= '<th scope="col">Nazwa leku</th>';
	$table .= '<th scope="col">Opakowanie</th>';
	$table .= '<th scope="col">Zsumowana ilość</th>';
	$table .= '<th scope="col">Pozostało</th></tr></thead><tbody>';
	
	$czyIntegralna = true;
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			$sumaIlosci = 0;
			$sumaPozostalo = 0;
			$idLeku = $row['id_leku'];
			$kwerenda = "SELECT `NazwaHandlowa`, `Opakowanie` FROM `ListaLekow` WHERE `id` = '$idLeku'";
			$result1 = Zapytanie($kwerenda);
			$row1 = $result1->fetch_assoc();
			if($result1->num_rows > 0){
				$nazwaHandlowa = $row1['NazwaHandlowa'];
				$opakowanie = $row1['Opakowanie'];
				$table .= '<td>' . $nazwaHandlowa . '</td>';
				$table .= '<td>' . $opakowanie . '</td>';
			}
			//echo "idleku: " . $idLeku . "<br>";
			$kwerenda = "SELECT `id_dokumentu`, `ilosc`, `pozostalo` FROM `RuchLekow` WHERE `id_leku` = '$idLeku'";
			$result1 = Zapytanie($kwerenda);
			if($result1->num_rows > 0){
				while($row1 = $result1->fetch_assoc()){
					$idDokumentu = $row1['id_dokumentu'];
					$ilosc = $row1['ilosc'];
					$pozostalo = $row1['pozostalo'];
					//echo "id_dokumentu: " . $idDokumentu . "<br>";
					//echo "ilosc: " . $ilosc . "<br>";
					//echo "pozostalo: " . $pozostalo . "<br>";
					if($pozostalo >= 0) $sumaPozostalo = $sumaPozostalo + $pozostalo;
					if($idDokumentu==1) $sumaIlosci = $sumaIlosci+$ilosc;
					else if($idDokumentu==2 || $idDokumentu==3) $sumaIlosci = $sumaIlosci-$ilosc;
					//echo "SUMA ILOSCI: " . $sumaIlosci . "<br>";
					//echo "POZOSTAŁO: " . $sumaPozostalo . "<br><br>";
				}
				if($sumaIlosci != $sumaPozostalo) $czyIntegralna = false;
				$table .= '<td>' . $sumaIlosci . '</td>';
				$table .= '<td>' . $sumaPozostalo . '</td></tr>';
				//echo "SUMA ILOSCI: " . $sumaIlosci . "<br>";
				//echo "POZOSTAŁO: " . $sumaPozostalo . "<br><br>";
			}
		}
	}
	if($czyIntegralna) echo "<div class=\"container\"> Baza jest integralna"  . "</div>";
	else echo "<div class=\"container\"> Baza nie jest integralna"  . "</div>";
	$table .= "</tbody></table>";
	echo $table;
}
else echo '<div class="container">Wybierz apteczkę, aby wyświetlić jej zawartość.</div>';
?>