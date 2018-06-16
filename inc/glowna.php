<?php 
if(!isset($_SESSION['user'])) 
{?>
	<div class="container">
		<h1 class="display-4" style="text-align:center; margin:20px 0px">System zarządzania apteczkami</h1>
		<!--<hr class="my-4">-->

	<ul class="nav nav-tabs">
		<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="00.php?mn=0&ms=1">Logowanie</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="00.php?mn=0&ms=2">Rejestracja</a>
		</li>
	</ul>
	</div>

<?php 
} 

$wyborGlowna = 0;
if(isset($_GET['ms'])){
	$wyborGlowna = $_GET['ms'];
}

switch($wyborGlowna){
	case 1:
		$inc = "./inc/logowanie.php";
		if(file_exists($inc)) include($inc);
		else header("Location: app_error.php?tx_err=$BladIntegralnosciAplikacji&gdzie=$inc");
		break;
	case 2: 
		$inc = "./inc/rejestracja.php";
		if(file_exists($inc)) include($inc);
		else header("Location: app_error.php?tx_err=$BladIntegralnosciAplikacji&gdzie=$inc");
		break;
		break;
	default:
}
?>