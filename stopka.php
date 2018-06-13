<br/>
<hr/>
<div class="container">
	<?php
		if(isset($_SESSION['user'])){
			echo '<h5 style="margin-bottom: 20px">Zalogowany jako: ' . $_SESSION['user'] . '</h5>' . '<fieldset><form action="00.php?mn=-1" method = "POST"><input type="submit" class="btn btn-primary btn-sm" value="Wyloguj"></form></fieldset>';
		}
	?>
<div>
<h6 style="margin-top: 20px">
	<small class="text-muted">Przykłady z prezentacji danych w internecie z wykorzystaniem php: kpi@student.agh.edu.pl i samoone@student.agh.edu.pl</small>
</h6>
</div>
</div>
</body>
</html>

