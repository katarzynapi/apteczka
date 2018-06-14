   <?php
	$text = array();
	
	if(isset($_POST['dalej'])) {
		$login = $_POST['login'];
		$email = $_POST['email'];
		$haslo = $_POST['haslo'];
		
		$haslo_md5 = md5($haslo);
		
		if($login && $email && $haslo) {
			// sprawdzenie czy istnieje juz ktos taki w bazie
			$kwerenda = "SELECT COUNT(`nazwa`) AS total1 FROM `test_users` WHERE `nazwa`='$login'";
			$result = Zapytanie($kwerenda);
			$total1 = $result->fetch_object()->total1;
			if($total1 > 0) {
				array_push($text,'Wpisz inną nazwę użytkownika.');
			}
			$kwerenda = "SELECT COUNT(`nazwa`) AS total2 FROM `test_users` WHERE `email`='$email'";
			$result = Zapytanie($kwerenda);
			$total2 = $result->fetch_object()->total2;
			if($total2 > 0) {
				array_push($text,'Wpisz inny adres e-mail.');
			}
			if($total1 == 0 && $total2 == 0) { 
				// wyslanie mail potwierdzajacego
				$confirm_code = rand(1000000,2000000);
				$kwerenda = "INSERT INTO `test_users`(`id`, `nazwa`, `haslo`, `email`, `confirmed`, `confirm_code`)" . 
					"VALUES (NULL,'$login','$haslo','$email','0','$confirm_code')";
				$result = Zapytanie($kwerenda);
				if($result) {
					/*$message = "
						Potwierdź swój adres e-mail.
						Kliknij link poniżej, aby dokończyć rejestrację:
						http://student.agh.edu.pl/samoone/apteczka/emailconfirm.php?login=$login&code=$confirm_code
						
						Nie odpowiadaj na ten e-mail.
					";*/
					$message = "
						Potwierdź swój adres e-mail.
						Kliknij link poniżej, aby dokończyć rejestrację:
						http://localhost/~Kasia/apteczka/00.php?mn=8&login=$login&code=$confirm_code
						
						Nie odpowiadaj na ten e-mail.
					";
					mail("$email","Apteczka - potwierdzenie rejestracji","$message","From: noreply@apteczka.com");
					array_push($text,'Sprawdź skrzynkę mailową i kliknij w link, aby zakończyć rejestrację.');
				}
				else echo "Nie dodano do bazy";
			}
		}
		else {
			echo "Wypełnij wszystkie pola!";
		}
	}
	
	for($x = 0; $x < count($text); $x++) {
		echo "<p class='lead'>" . $text[$x] . "</p>";
	}
	unset($text);
	
?>

<div class="container">
	<h1 class="display-3">Rejestracja</h1>
	<hr class="my-4">

	<form action="00.php?mn=7" method="post">
		<fieldset>
			<div class="form-group">
			  <label for="login">Login</label>
			  <input type="text" class="form-control" id="login" name="login" placeholder="Wpisz login">
			</div>
			<div class="form-group">
			  <label for="login">E-mail</label>
			  <input type="email" class="form-control" id="email" name="email" placeholder="Wpisz e-mail">
			</div>
			<div class="form-group">
			  <label for="haslo">Hasło</label>
			  <input type="password" class="form-control" id="haslo" name="haslo" placeholder="Wpisz hasło">
			</div>
			<input type="submit" class="btn btn-primary" name="dalej" value="Dalej">
		</fieldset>
	</form>
</div>