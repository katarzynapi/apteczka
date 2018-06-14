<div class="container">
	
<?php
	if(isset($_GET['login']) && isset($_GET['code'])) {
		$login = $_GET['login'];
		$code = $_GET['code'];
		
		$kwerenda = "SELECT * FROM `test_users` WHERE `nazwa`='$login'";
		$result = Zapytanie($kwerenda);
		if($result) {
			$bcode = $result->fetch_object()->confirm_code;
			echo $bcode . "<br>";
			if($code == $bcode) {
				$kwerenda = "UPDATE `test_users` SET `confirmed`='1', `confirm_code`='0' WHERE `nazwa`='$login'";
				$result = Zapytanie($kwerenda);
?>

	<p class="lead">Rejestracja zakończona! Aby zacząć używać apteczki, zaloguj się.</p>
	<a class="btn btn-primary" href="00.php?mn=1" role="button">Zaloguj się</a>
</div>

<?php
			}
			else echo "Nazwa użytkownika i kod potwierdzający nie zgadzają się. Nie kombinuj!";
		}
		else echo "Bład - nie znaleziono w bazie.";
	}
?>