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
else {
	$dataMiesiacPo = date_create("+1 month")->format('Y-m-d');
	$emailUser = $_SESSION['user'];
	$kwerenda = "SELECT id FROM test_users WHERE email = '$emailUser'";
	$result = Zapytanie($kwerenda);
	$idUser = $result->fetch_object()->id;
	
	$kwerenda = "SELECT `id_apteczki` FROM `Dostep` WHERE `id_uzytkownika`='$idUser' ORDER BY `id_apteczki`";
	$result = Zapytanie($kwerenda);
	
	$form = '<br><h5 class="display-4 container">Leki przeterminowane bądź takie, które wkrótce ulegną przeterminowaniu</h5><br>';
		$form .= '<div class="container">';
		$form .= '<table class="table table-hover"><thead><tr>';
		$form .= '<th scope="col">Nazwa leku</th>';
		$form .= '<th scope="col">Opakowanie</th>';
		$form .= '<th scope="col">Dawka</th>';
		$form .= '<th scope="col">Ilość</th>';
		$form .= '<th scope="col">Data ważności</th>';
		$form .= '<th scope="col">Nazwa apteczki</th></tr></thead><tbody>';
	
	while($row = $result->fetch_assoc()) {
		$idApteczki = $row['id_apteczki'];
		$kwerenda = "SELECT nazwa FROM apteczki WHERE id = '$idApteczki'";
		$result5 = Zapytanie($kwerenda);
		$nazwaApteczki = $result5->fetch_object() ->nazwa;
		$kwerenda = "SELECT `id_leku`, `data_waznosci`, `pozostalo` FROM `RuchLekow` WHERE `id_apteczki`='$idApteczki' AND `pozostalo`>0 AND `id_dokumentu`='1' ORDER BY data_waznosci";
		$result2 = Zapytanie($kwerenda);
		while($row2 = $result2->fetch_assoc()) {
			$id_leku = $row2['id_leku'];
			$dataWaznosci = $row2['data_waznosci'];
			$pozostalo = $row2['pozostalo'];
			if($dataWaznosci < $dataMiesiacPo) {
				$kwerenda = "SELECT `NazwaHandlowa`,`Opakowanie`,`Dawka` FROM `ListaLekow` WHERE `id` = '$id_leku'";
				$result3 = Zapytanie($kwerenda);
				$row3 = $result3->fetch_assoc();
				$form .= '<tr class="table-light">';
					$form .= '<td>' . $row3['NazwaHandlowa'] . '</td>';
					$form .= '<td>' . $row3['Opakowanie'] . '</td>';
					$form .= '<td>' . $row3['Dawka'] . '</td>';
					$form .= '<td>' . $pozostalo . '</td>';
					$form .= '<td>' . $dataWaznosci . '</td>';
					$form .= '<td>' . $nazwaApteczki . '</td></tr>';
			}
		}
	}
	
	$form .= "</tbody></table></div>";
	echo $form;
	
	
	/*$kwerenda = "SELECT `data_waznosci` FROM `RuchLekow` WHERE `id`='7'";
	$result = Zapytanie($kwerenda);
	$dataProba = $result->fetch_object()->data_waznosci;
	$dataDzis = date("Y-m-d");
	
	if($dataDzis < $dataZaMiesiac) echo "prawda";
	else echo "fałsz";*/
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
	default:
}
?>