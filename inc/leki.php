<div class="container">
<ul class="nav nav-tabs">
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="00.php?mn=5&mm=1">Dodanie</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="00.php?mn=5&mm=2">Wydanie</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="00.php?mn=5&mm=3">Utylizacja</a>
	</li>
</ul>
</div>

<?php
$wyborLeki = 0;
if(isset($_GET['mm'])){
	$wyborLeki = $_GET['mm'];
}
 date_default_timezone_set("Europe/Warsaw");
 switch($wyborLeki){
 case 1:
 $form = "<div class=\"container\"><form action=\"00.php?mn=3\" method=\"post\">";
 	$form .= "Nazwa: <input type=\"text\" name=\"nazwa\" required> <br>";
 	$form .= "Postać: <input type=\"text\" name=\"postac\"> <br>";
 	$form .= "Opakowanie: <input type=\"text\" name=\"opakowanie\"> <br>";
	$form .= "EAN: <input type=\"numeric\" name=\"ean\"> <br>";
 	$form .= "<input type=\"submit\" value=\"Wyszukaj\" class=\"btn btn-primary btn-sm\">";
 	$form .= "</form></div>";
  echo $form;
  
  if(isset($_POST['nazwa']) || isset($_POST['postac']) || isset($_POST['opakowanie'])){
  	$nazwa = trim($_POST['nazwa']);
  	$nazwa = "\"%" . $nazwa . "%\"";
  	$postac = trim($_POST['postac']);
  	$postac = "\"%" . $postac . "%\"";
  	$opakowanie = trim($_POST['opakowanie']);
  	$opakowanie = "\"%" . $opakowanie . "%\"";
  	$ean = trim($_POST['ean']);
  	$ean = "\"%" . $ean . "%\"";
  	$kwerenda = "SELECT * FROM ListaLekow WHERE (NazwaHandlowa LIKE $nazwa AND Postac LIKE $postac AND Opakowanie LIKE $opakowanie AND KodKreskowy LIKE $ean)";
  	$result = Zapytanie($kwerenda);
  	echo "<br>Liczba znalezionych rekordów: " . $result->num_rows . "<br><br>";
  	if($result->num_rows > 25){
  		echo "Zbyt wiele rekordów do wyświetlenia. Doprecyzuj wyszukiwanie." . "<br>";
  	} else {
  		if($result->num_rows > 0){
  			$form = "<form action=\"00.php?mn=3\" method=\"post\">";
  			$i = 1;
  			while($row = $result->fetch_assoc()){
  				$form .= $i . ". Nazwa handlowa: " . $row["NazwaHandlowa"] . "<br>" . "Kod kreskowy: " . $row["KodKreskowy"] . "<br>" . "Postać: " . $row["Postac"] . "<br>" . "Opakowanie: " . $row["Opakowanie"];
  				$id = $row["id"];
  				$form .= " <input type=\"radio\" name=\"pozycja\" value=\"$id\"><br><br>";
  				$i = $i + 1;
  			}
  			$form .= " Ilość: <input type=\"text\" name=\"ilosc\" required><br>";
  			$form .= " Cena: <input type=\"numeric\" name=\"cena\" required><br>";
  			$form .= " Data ważności: <input type=\"date\" name=\"datawaznosci\" required><br>";
  			$form .= "<input type=\"submit\" value=\"Dodaj\ class=\"btn btn-primary btn-sm\">";
  			echo $form;
  		} else {
  			echo "Zwrócono 0 rekordów<br>";
  		}
  	}
  }
  if(isset($_POST['pozycja']) && isset($_POST['ilosc']) && isset($_POST['cena']) && isset($_POST['datawaznosci'])){
  	$id = $_POST['pozycja'];
	$id_apteczki = $_SESSION['idWybranaApteczka'];
  	$ilosc = $_POST['ilosc'];
  	$user = $_SESSION['user'];
  	$cena = $_POST['cena'];
  	$datawaznosci = $_POST['datawaznosci'];
  	date_default_timezone_set("Europe/Warsaw");
  	$dataOperacji = date("Y-m-d");
  	$kwerenda = "SELECT id FROM test_users WHERE email = '$user'";
  	$result = Zapytanie($kwerenda);
  	$IdUser = $result->fetch_object()->id;
  	$kwerenda = "SELECT * FROM ListaLekow WHERE id = '$id'";
  	$result = Zapytanie($kwerenda);
  	$row = $result->fetch_assoc();
  	$kodKreskowy = $row["KodKreskowy"];
  	$kwerenda = "INSERT INTO `kpi`.`RuchLekow` (`id`, `id_apteczki`, `id_uzytkownika`, `id_dokumentu`, `id_leku`, `ilosc`, `data_waznosci`, `cena`, `data_operacji`, 'pozostalo') VALUES (NULL, '$id_apteczki', '$IdUser', '1', '$kodKreskowy', '$ilosc', '$datawaznosci', '$cena', '$dataOperacji', '$ilosc')";
  	$result = Zapytanie($kwerenda);
  	echo "<br>Do bazy dodano następującą pozycję: <br>" . "Nazwa handlowa: " . $row["NazwaHandlowa"] . "<br>" . "Kod kreskowy: " . $row["KodKreskowy"] . "<br>" . "Postać: " . $row["Postac"] . "<br>" . "Opakowanie: " . $row["Opakowanie"] . "<br>" . "Liczba sztuk: " . $ilosc . "<br>" . "Czas: ". $dataOperacji;
  }
 break;
 
 case 2:
 break;
 
 case 3:
 break;
 
 default:
 break;
}
 ?>