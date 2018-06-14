<?php
session_start();
$BladIntegralnosciAplikacji = "Błąd integralności aplikacji";

// Zmienne najpierw, ponieważ mogą zawierać fragmenty ścieżek
$inc = "./inc/zmienne.php";
if(file_exists($inc)) include($inc);
else header("Location: app_error.php?tx_err=$BladIntegralnosciAplikacji&gdzie=$inc");

// Opisy mogą przydać się w nag.php
$inc = "./jezyk/$jezyk/opisy.php";
if(file_exists($inc)) include($inc);
else header("Location: app_error.php?tx_err=$BladIntegralnosciAplikacji&gdzie=$inc");

$inc = "nagl.php";
if(file_exists($inc)) include($inc);
else header("Location: app_error.php?tx_err=$BladIntegralnosciAplikacji&gdzie=$inc");

// Menu potrzebuje nagl.php i tekstów
$inc = "./inc/menu.php";
if(file_exists($inc)) include($inc);
else header("Location: app_error.php?tx_err-$BladIntegralnosciAplikacji&gdzie=$inc");

// Sprawdzenie wyboru opcji menu
if(isset($_GET['mn'])){
	$wybor = $_GET['mn'];
	if($_GET['mn'] > 0) echo '<div class="container" style="text-align: center"><H2 style="margin-top: 30px">' . $Naglowki[$wybor][2] . "</H2></div>";
}

if(isset($_GET['mm'])){
	$wyborZarzadzanie = $_GET['mm'];
}
// Potrzebne funkcje
$inc = "./inc/funkcje.php";
if(file_exists($inc)) include($inc);
else header("Location: app_error.php?tx_err=$BladIntegralnosciAplikacji&gdzie=$inc");

switch($wybor){
	case -1: //wylogowanie z systemu
		unset($wybor);
		session_unset();
		session_destroy();
		if(!isset($_SESSION['user'])) echo $ktx_wylogowano;
		break;
	case 1: //logowanie
		$_SESSION['Error'] = "";
		$_SESSION['Etap'] = "Start logowania";
		//Formularz logowania
		$inc = "./inc/logowanie.php";
		if(file_exists($inc)) include($inc);
		else header("Location: app_error.php?tx_err=$BladIntegralnosciAplikacji&gdzie=$inc");
		break;
	case 2: //wyświetlanie zawartości
		$_SESSION['Error'] = "";
		$_SESSION['Etap'] = "Wyswietlanie";
		if(!isset($_SESSION['user'])){
			echo "<div class=\"container\"><h6 style=\"margin-top: 30px\">Zaloguj się, żeby uzyskać dostęp.</h6></div>";
			if($_SESSION['Error'] != ""){
				echo "<br>" . $_SESSION['Error'] . "<br>";
			}
		} else {
			$inc = "./inc/wyswietlanie.php";
			if(file_exists($inc)) include($inc);
			else header("Location: app_error.php?tx_err=$BladIntegralnosciAplikacji&gdzie=$inc");
			/*$user = $_SESSION['user'];
			$kwerenda = "SELECT id_ksiazka FROM test_users WHERE email = '$user'";
			$result = Zapytanie($kwerenda);
			$zBazyIdKsiazka = $result->fetch_object()->id_ksiazka;
			echo "zBazyIdKsiazka: " . $zBazyIdKsiazka . " ";
			$kwerenda = "SELECT * FROM ksiazki WHERE id = '$zBazyIdKsiazka'";
			$result = Zapytanie($kwerenda);
			$row = $result->fetch_assoc();
			echo $row["nazwa"] . " | " . $row["autor"] . " | " . "<br>";*/
		}
		break;
	case 3:
		$_SESSION['Error'] = "";
		$_SESSION['Etap'] = "Wyszukiwanie";
		if(!isset($_SESSION['user'])){
			echo "<div class=\"container\"><h6 style=\"margin-top: 30px\">Zaloguj się, żeby uzyskać dostęp.</h6></div>";
			if($_SESSION['Error'] != ""){
				echo "<br>" . $_SESSION['Error'] . "<br>";
			}
		} else {
			$inc = "./inc/wyszukanie.php";
			if(file_exists($inc)) include($inc);
			else header("Location: app_error.php?tx_err=$BladIntegralnosciAplikacji&gdzie=$inc");
		}
		break;	
	case 4:
		$_SESSION['Error'] = "";
		$_SESSION['Etap'] = "Dokumentacja";
		if(!isset($_SESSION['user'])){
			echo "<div class=\"container\"><h6 style=\"margin-top: 30px\">Zaloguj się, żeby uzyskać dostęp.</h6></div>";
			if($_SESSION['Error'] != ""){
				echo "<br>" . $_SESSION['Error'] . "<br>";
		}
		} else {
			$user = $_SESSION['user'];
			$kwerenda = "SELECT id FROM test_users WHERE email = '$user'";
			$result = Zapytanie($kwerenda);
			$zBazyId = $result->fetch_object()->id;
			echo "zBazyId: " . $zBazyId . " ";
		}
		break;
	case 5:
	$_SESSION['Error'] = "";
	$_SESSION['Etap'] = "Testy";
	if(!isset($_SESSION['user'])){
		echo "<div class=\"container\"><h6 style=\"margin-top: 30px\">Zaloguj się, żeby uzyskać dostęp.</h6></div>";
		if($_SESSION['Error'] != ""){
			echo "<br>" . $_SESSION['Error'] . "<br>";
	}
	} else {
		$user = $_SESSION['user'];
		$kwerenda = "SELECT id FROM test_users WHERE email = '$user'";
		$result = Zapytanie($kwerenda);
		$zBazyId = $result->fetch_object()->id;
		echo "zBazyId: " . $zBazyId . " ";
	}
	break;
	case 6:
		$_SESSION['Error'] = "";
		$_SESSION['Etap'] = "Zarzadzaj";
		if(!isset($_SESSION['user'])){
			echo "<div class=\"container\"><h6 style=\"margin-top: 30px\">Zaloguj się, żeby uzyskać dostęp.</h6></div>";
			if($_SESSION['Error'] != ""){
				echo "<br>" . $_SESSION['Error'] . "<br>";
			}
		} else {
			$inc = "./inc/zarzadzanie.php";
			if(file_exists($inc)) include($inc);
			else header("Location: app_error.php?tx_err=$BladIntegralnosciAplikacji&gdzie=$inc");
			switch($wyborZarzadzanie){
				case 1:
					echo 'Załóż apteczkę';
				break;
				case 2:
					echo 'Dodaj użytkownika';
				break;
				case 3:
					echo 'Uzyskaj dostęp';
				break;
				default:
				break;
			}
		}
		break;
		case 7:
			$_SESSION['Error'] = "";
			$_SESSION['Etap'] = "Rejestracja";
			if(!isset($_SESSION['user'])){
				echo "<div class=\"container\"><h6 style=\"margin-top: 30px\">Zaloguj się, żeby uzyskać dostęp.</h6></div>";
				if($_SESSION['Error'] != ""){
					echo "<br>" . $_SESSION['Error'] . "<br>";
				}
			} else {
				$inc = "./inc/rejestracja.php";
				if(file_exists($inc)) include($inc);
				else header("Location: app_error.php?tx_err=$BladIntegralnosciAplikacji&gdzie=$inc");
				}
		break;
		case 8:
			$_SESSION['Error'] = "";
			$_SESSION['Etap'] = "Emailconfirm";
			if(!isset($_SESSION['user'])){
				echo "<div class=\"container\"><h6 style=\"margin-top: 30px\">Zaloguj się, żeby uzyskać dostęp.</h6></div>";
				if($_SESSION['Error'] != ""){
					echo "<br>" . $_SESSION['Error'] . "<br>";
				}
			} else {
				$inc = "./inc/emailconfirm.php";
				if(file_exists($inc)) include($inc);
				else header("Location: app_error.php?tx_err=$BladIntegralnosciAplikacji&gdzie=$inc");
				}
		break;
		default:
		break;
}
$inc = "stopka.php";
if(file_exists($inc)) include($inc);
else header("Location: app_error.php?tx_err=$BladIntegralnosciAplikacji&gdzie=$inc");
?>