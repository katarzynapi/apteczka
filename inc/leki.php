<div class="container">
<ul class="nav nav-tabs">
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="00.php?mn=5&mm=0">Wyświetlenie</a>
	</li>
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
$wyborLeki = -1;
if(isset($_GET['mm'])){
	$wyborLeki = $_GET['mm'];
}
 date_default_timezone_set("Europe/Warsaw");
 switch($wyborLeki){
	case 0:
		// wyswietlenie wszystkich lekow
		$inc = "./inc/wyswietlLeki.php";
		if(file_exists($inc)) include($inc);
		else header("Location: app_error.php?tx_err=$BladIntegralnosciAplikacji&gdzie=$inc");
		break;
	case 1:
		// dodawanie leku
		$inc = "./inc/dodajLek.php";
		if(file_exists($inc)) include($inc);
		else header("Location: app_error.php?tx_err=$BladIntegralnosciAplikacji&gdzie=$inc");
		break;
	case 2:
		// wydanie leku
		$inc = "./inc/wydajLek.php";
		if(file_exists($inc)) include($inc);
		else header("Location: app_error.php?tx_err=$BladIntegralnosciAplikacji&gdzie=$inc");
		break;
	case 3:
		// utylizacja leku
		$inc = "./inc/utylizujLek.php";
		if(file_exists($inc)) include($inc);
		else header("Location: app_error.php?tx_err=$BladIntegralnosciAplikacji&gdzie=$inc");
		break;
	default:
		break;
}
 ?>