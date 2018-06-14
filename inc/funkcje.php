<?php
// Łączenie z bazą
function PolaczBaza(){
	// Dane do połączenia - najlepiej dołączyć z pliku powyżej public_html
	//$pliktxt = "./../../dane.txt";
	$pliktxt = "./../../Documents/dane.txt";
	if(file_exists($pliktxt)){
		$fp = fopen($pliktxt, "r");
		$linia = fgets($fp);
		$MyUs = explode(";", $linia);
		//print_r($MyUs);
		$servername = $MyUs[0];
		$username = $MyUs[1];
		$password = $MyUs[2];
		$database = $MyUs[3];
	} else{
		header("Location: ./../app_error.php?tx_err=OtworzPlik&gdzie=$pliktxt");
	}
	
	// Wygenerowanie connection: obiekt conn
	$conn = new mysqli($servername, $username, $password, $database);
	
	// Sprawdzenie połączenia
	if($conn->connect_error){
		die("Brak połączenia: " . $conn->connect_error . "<br>");
		return NULL;
	} else{
		return $conn;
	}
}

function Zapytanie($kwerenda){
	$conn = PolaczBaza();
	$result = $conn->query("SET NAMES 'utf8'");
	if(mysqli_errno($conn)){
		$BladTxt = "Błąd " . mysqli_errno($conn) . ": " . mysqli_error($conn);
		header("Location: ./app_error.php?tx_err=Zapytanie&gdzie=$BladTxt");
	}
	//echo $kwerenda . "<br>";
	$result = $conn->query($kwerenda);
	if(mysqli_errno($conn)){
		$BladTxt = "Błąd " . mysqli_errno($conn) . ": " . mysqli_error($conn);
		header("Location: ./app_error.php?tx_err=Zapytanie&gdzie=$BladTxt");
	}
	$conn->close();
	return $result;
}
?>