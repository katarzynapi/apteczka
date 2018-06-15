<br/>
<hr/>
<div class="container">
	<?php
		
		if(isset($_SESSION['user'])){
			$uzytkownikEmail = $_SESSION['user'];
			$uzytkownikNazwa = $_SESSION['nazwa'];
			$form = "<h6>Zalogowany jako: $uzytkownikNazwa (e-mail: $uzytkownikEmail)</h6>";
		}
		if(isset($_SESSION['wybranaApteczka']) && isset($_SESSION['dostep'])){
			$apteczka = $_SESSION['wybranaApteczka'];
			$dostep = $_SESSION['dostep'];
			$form .= "<h6>Nazwa wybranej apteczki: $apteczka</h6>";
			$form .= "<h6 style=\"margin-bottom: 20px\">Dostęp: $dostep";
		}
		if(isset($_SESSION['user'])){
		$form .= "<fieldset><form action=\"00.php?mn=-1\" method = \"POST\"><input type=\"submit\" class=\"btn btn-primary btn-sm\"";
		$form .= "value=\"Wyloguj\"></form></fieldset>";
		}
		echo $form;
			
			//echo '<h6 style="margin-bottom: 20px">Zalogowany jako: ' . $_SESSION['user'] . '</h6>' . '<fieldset><form action="00.php?mn=-1" method = "POST"><input type="submit" class="btn btn-primary btn-sm" value="Wyloguj"></form></fieldset>';
	?>
<div>
<h6 style="margin-top: 20px">
	<small class="text-muted">Przykłady z prezentacji danych w internecie z wykorzystaniem php: kpi@student.agh.edu.pl i samoone@student.agh.edu.pl</small>
</h6>
</div>
</div>
</body>
</html>

