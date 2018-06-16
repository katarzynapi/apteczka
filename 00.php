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

$wybor = 0;
// Sprawdzenie wyboru opcji menu
if(isset($_GET['mn'])){
	$wybor = $_GET['mn'];
	if($_GET['mn'] > 0) ;//echo '<div class="container" style="text-align: center"><H2 style="margin-top: 30px">' . $Naglowki[$wybor][2] . "</H2></div>";
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
		if(!isset($_SESSION['user'])) echo "<div class=\"container\">" . $ktx_wylogowano . "</div>";
		break;
	case 0:
		$_SESSION['Error'] = "";
		$_SESSION['Etap'] = "StartLogowania";
		//Formularz logowania
		$inc = "./inc/glowna.php";
		if(file_exists($inc)) include($inc);
		else header("Location: app_error.php?tx_err=$BladIntegralnosciAplikacji&gdzie=$inc");
		break;	
	case 1: //logowanie
		$_SESSION['Error'] = "";
		$_SESSION['Etap'] = "StartLogowania";
		//Formularz logowania
		$inc = "./inc/logowanie.php";
		if(file_exists($inc)) include($inc);
		else header("Location: app_error.php?tx_err=$BladIntegralnosciAplikacji&gdzie=$inc");
		break;
	case 2: //wyświetlanie zawartości
		$_SESSION['Error'] = "";
		$_SESSION['Etap'] = "wyborApteczki";
		if(!isset($_SESSION['user'])){
			echo "<div class=\"container\"><h6 style=\"margin-top: 30px\">Zaloguj się, żeby uzyskać dostęp.</h6></div>";
			if($_SESSION['Error'] != ""){
				echo "<br>" . $_SESSION['Error'] . "<br>";
			}
		} else {
			$inc = "./inc/wyborApteczki.php";
			if(file_exists($inc)) include($inc);
			else header("Location: app_error.php?tx_err=$BladIntegralnosciAplikacji&gdzie=$inc");
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
	$_SESSION['Etap'] = "Leki";
	if(!isset($_SESSION['user'])){
		echo "<div class=\"container\"><h6 style=\"margin-top: 30px\">Zaloguj się, żeby uzyskać dostęp.</h6></div>";
		if($_SESSION['Error'] != ""){
			echo "<br>" . $_SESSION['Error'] . "<br>";
	}
	} else {
		$inc = "./inc/leki.php";
		if(file_exists($inc)) include($inc);
		else header("Location: app_error.php?tx_err=$BladIntegralnosciAplikacji&gdzie=$inc");
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
		}
		break;
		case 7:
			$_SESSION['Error'] = "";
			$_SESSION['Etap'] = "Rejestracja";
			if(isset($_SESSION['user'])){
				echo "<div class=\"container\"><h6 style=\"margin-top: 30px\">Już jesteś zalogowany. Rejestracja możliwa tylko dla niezalogowanych użytkowników.</h6></div>";
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
			$inc = "./inc/emailconfirm.php";
			if(file_exists($inc)) include($inc);
			else header("Location: app_error.php?tx_err=$BladIntegralnosciAplikacji&gdzie=$inc");
		break;
		default:
			echo "<div class=\"container\"><h1 class=\"display-4\">System zarządzania apteczkami</h1><hr class=\"my-4\"></div>";
		break;
}
$inc = "stopka.php";
if(file_exists($inc)) include($inc);
else header("Location: app_error.php?tx_err=$BladIntegralnosciAplikacji&gdzie=$inc");
?>