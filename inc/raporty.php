<div class="container">
<ul class="nav nav-tabs">
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="00.php?mn=7&mr=0">Zużycie leków</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="00.php?mn=7&mr=1">Historia leku</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="00.php?mn=7&mr=2">Koszta</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="00.php?mn=7&mr=3">Przychody/rozchody</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="00.php?mn=7&mr=4">Badanie integralności</a>
	</li>
</ul>
</div>

<?php
$wyborRaporty = -1;
if(isset($_GET['mr'])){
	$wyborRaporty = $_GET['mr'];
}
 date_default_timezone_set("Europe/Warsaw");
 switch($wyborRaporty){
	case 0:
	$inc = "./inc/zuzycieLekow.php";
	if(file_exists($inc)) include($inc);
	else header("Location: app_error.php?tx_err=$BladIntegralnosciAplikacji&gdzie=$inc");
		break;
	case 1:
		$inc = "./inc/historiaLeku.php";
		if(file_exists($inc)) include($inc);
		else header("Location: app_error.php?tx_err=$BladIntegralnosciAplikacji&gdzie=$inc");
		break;
	case 2:
		$inc = "./inc/koszt.php";
		if(file_exists($inc)) include($inc);
		else header("Location: app_error.php?tx_err=$BladIntegralnosciAplikacji&gdzie=$inc");
		break;
	case 3:
		break;
	case 4:
		// badanie integralności bazy
		$inc = "./inc/integralnosc.php";
		if(file_exists($inc)) include($inc);
		else header("Location: app_error.php?tx_err=$BladIntegralnosciAplikacji&gdzie=$inc");
		break;
	default:
		break;
}
 ?>