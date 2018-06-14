<!--<form action="<?php //echo $PlikGlowny . "?mn=2"; ?>" method = "POST">
	<input type="submit" value="Wyświetl moje id">
</form>-->

<?php
//nazwa leku, liczba?, cena, data ważności
	$user = $_SESSION['user'];
	
	
	/*echo "<div class=\"container\"><H4>Twoje apteczki:</H4></div>";
	
	$kwerenda = "SELECT id FROM test_users WHERE email = '$user'";
	$result = Zapytanie($kwerenda);
	$idUser = $result->fetch_object()->id;
	
	$kwerenda = "SELECT * FROM Dostep WHERE id_uzytkownika = '$idUser'";
	$result = Zapytanie($kwerenda);
	$row = $result->fetch_assoc();
	
	else if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			 if($row["id_apteczki"] == $idUser){
				 echo "Podany użytkownik już jest przypisany do wybranej apteczki.";
				 break;
			 }
		} 
	}*/
	//$kwerenda = "SELECT * FROM RuchLekow WHERE email = '$user'";
	/*$kwerenda = "SELECT IdListaLekow FROM RuchLekow";
	$result = Zapytanie($kwerenda);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			echo $row["IdListaLekow"] . " | ";
			$IdListaLekow = $row["IdListaLekow"];
			$kwerenda1 = "SELECT NazwaHandlowa FROM ListaLekow WHERE id='$IdListaLekow'";
			$result1 = Zapytanie($kwerenda1);
			//$result1->num_rows;
			$row1 = $result1->fetch_object();
			echo $row1["NazwaHandlowa"] . "<br>";
		} 
	}*/
?>