<div class="container">
<ul class="nav nav-tabs">
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="00.php?mn=6&mm=1">Dodaj apteczkę</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="00.php?mn=6&mm=2">Dodaj użytkowników apteczki</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="00.php?mn=6&mm=3">Dołącz do apteczki</a>
	</li>
</ul>
</div>

<?php
$wyborZarzadzanie = 0;
if(isset($_GET['mm'])){
	$wyborZarzadzanie = $_GET['mm'];
}
switch($wyborZarzadzanie){
	case 1:
	$user = $_SESSION['user'];
	echo "<div class=\"container\"><H4 style=\"margin-top: 20px\">Dodawanie nowej apteczki:</H4>";
	echo "<h6 style=\"margin-bottom: 30px\"><small class=\"text-muted\">Dodając nową apteczkę automatycznie stajesz się jej administratorem.</small></div></h6>";
	$form = "<div class=\"container\"><form action=\"00.php?mn=6&mm=1\" method=\"post\">";
		$form .= "Nazwa apteczki: <input type=\"text\" name=\"nazwaApteczki1\" required> <br>";
		$form .= "Hasło do apteczki: <input style=\"margin-bottom: 10px\" type=\"password\" name=\"hasloApteczki1\" required> <br>";
		$form .= "<input type=\"submit\" class=\"btn btn-primary\"value=\"Dodaj apteczkę\">";
		$form .= "</form>";
	 echo $form;
	 
	 if(isset($_POST['nazwaApteczki1']) && isset($_POST['hasloApteczki1'])){
		$nazwaApteczki = trim($_POST['nazwaApteczki1']);
		$hasloApteczki = md5(trim($_POST['hasloApteczki1']));
		$user = $_SESSION['user'];
		
		$kwerenda = "SELECT nazwa FROM apteczki WHERE nazwa = '$nazwaApteczki'";
		$result = Zapytanie($kwerenda);
		$nazwaApteczkiTest = $result->fetch_object()->nazwa;
		if($nazwaApteczkiTest != "") echo "Istnieje apteczka o takiej nazwie";
		else{
			$kwerenda = "SELECT id FROM test_users WHERE email = '$user'";
			$result = Zapytanie($kwerenda);
			$idUser = $result->fetch_object()->id;
		
	 		$kwerenda = "INSERT INTO `kpi`.`apteczki` (`id`, `nazwa`, `haslo`, `admin_id`) VALUES (NULL, '$nazwaApteczki', '$hasloApteczki', '$idUser')";
	 		$result = Zapytanie($kwerenda);
		
			$kwerenda = "SELECT id FROM apteczki WHERE nazwa = '$nazwaApteczki'";
			$result = Zapytanie($kwerenda);
			$idApteczki = $result->fetch_object()->id;
		
	 		$kwerenda = "INSERT INTO `kpi`.`Dostep` (`id`, `id_uzytkownika`, `id_apteczki`, `czy_admin`) VALUES (NULL, '$idUser', '$idApteczki', '1')";
	 		$result = Zapytanie($kwerenda);
		
			echo "Do bazy dodano nową apteczkę.";
		}
	}
	break;
	
	case 2:
	$user = $_SESSION['user'];
	echo "<div class=\"container\"><H4 style=\"margin-top: 20px\">Dodawanie nowych użytkowników apteczki:</H4>";
	echo "<h6 style=\"margin-bottom: 30px\"><small class=\"text-muted\">Funkcja dostępna tylko dla administratora apteczki.</small></div></h6>";
	$form = "<div class=\"container\"><form action=\"00.php?mn=6&mm=2\" method=\"post\">";
		$form .= "Nazwa apteczki: <input type=\"text\" name=\"nazwaApteczki2\" required> <br>";
		$form .= "Nazwa użytkownika: <input style=\"margin-bottom: 10px\" type=\"text\" name=\"nazwaUzytkownika\" required> <br>";
		$form .= "<input type=\"submit\" class=\"btn btn-primary\"value=\"Dodaj użytkownika\">";
		$form .= "</form>";
	 echo $form;
	 
	 if(isset($_POST['nazwaApteczki2']) && isset($_POST['nazwaUzytkownika'])){
		$poprawneDane = true;
		$nazwaApteczki = trim($_POST['nazwaApteczki2']);
		$nazwaUzytkownika = trim($_POST['nazwaUzytkownika']);
		$user = $_SESSION['user'];
		
		$kwerenda = "SELECT id FROM apteczki WHERE nazwa = '$nazwaApteczki'";
		$result = Zapytanie($kwerenda);
		$idApteczki = $result->fetch_object()->id;
		
		$kwerenda = "SELECT id FROM test_users WHERE email = '$user'";
		$result = Zapytanie($kwerenda);
		$idAdmin = $result->fetch_object()->id;
		
		$kwerenda = "SELECT id FROM test_users WHERE nazwa = '$nazwaUzytkownika'";
		$result = Zapytanie($kwerenda);
		$idUzytkownika = $result->fetch_object()->id;
		
		$kwerenda = "SELECT confirmed FROM test_users WHERE nazwa = '$nazwaUzytkownika'";
		$result = Zapytanie($kwerenda);
		$confirmed = $result->fetch_object()->confirmed;
		
		$kwerenda = "SELECT czy_admin FROM Dostep WHERE id_apteczki = '$idApteczki' AND id_uzytkownika = '$idAdmin'";
		$result = Zapytanie($kwerenda);
		$czy_admin = $result->fetch_object()->czy_admin;

		$kwerenda = "SELECT id_uzytkownika FROM Dostep WHERE id_apteczki = '$idApteczki'";
		$result = Zapytanie($kwerenda);
		
		if($czy_admin == NULL || $czy_admin == 0){
			echo "Nie możesz dodać użytkownika, ponieważ nie jesteś administratorem wybranej apteczki.";
			$poprawneDane = false;
		}
		else if($idUzytkownika == NULL || !$confirmed){
			echo "Użytkownik, którego chcesz dodać nie istnieje w bazie.";
			$poprawneDane = false;
		}
		else if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					 if($row["id_uzytkownika"] == $idUzytkownika){
						echo "Podany użytkownik już jest przypisany do wybranej apteczki.";
						$poprawneDane = false;
						break;
					 }
				} 
		}
		if($poprawneDane){
 	 		$kwerenda = "INSERT INTO `kpi`.`Dostep` (`id`, `id_uzytkownika`, `id_apteczki`, `czy_admin`) VALUES (NULL, '$idUzytkownika', '$idApteczki', '0')";
 	 		$result = Zapytanie($kwerenda);
 			echo "Do apteczki dodano nowego użytkownika.";
		}
	}
	break;
	
	case 3:
	$user = $_SESSION['user'];
	echo "<div class=\"container\"><H4 style=\"margin-top: 20px\">Dołączanie do apteczki:</H4>";
	echo "<h6 style=\"margin-bottom: 30px\"><small class=\"text-muted\">Aby uzyskać hasło dostępu do apteczki skontaktuj się z jej administratorem.</small></div></h6>";
	$form = "<div class=\"container\"><form action=\"00.php?mn=6&mm=3\" method=\"post\">";
		$form .= "Nazwa apteczki: <input type=\"text\" name=\"nazwaApteczki3\" required> <br>";
		$form .= "Hasło do apteczki: <input style=\"margin-bottom: 10px\" type=\"password\" name=\"hasloApteczki2\" required> <br>";
		$form .= "<input type=\"submit\" class=\"btn btn-primary\"value=\"Dodaj mnie to apteczki\">";
		$form .= "</form>";
	 echo $form;
	 
	 if(isset($_POST['nazwaApteczki3']) && isset($_POST['hasloApteczki2'])){
		$poprawneDane = true;
		$nazwaApteczki = trim($_POST['nazwaApteczki3']);
		$hasloApteczki = md5(trim($_POST['hasloApteczki2']));
		$user = $_SESSION['user'];
		
		$kwerenda = "SELECT nazwa FROM apteczki WHERE nazwa = '$nazwaApteczki'";
		$result = Zapytanie($kwerenda);
		$nazwaApteczkiTest = $result->fetch_object()->nazwa;
		
		$kwerenda = "SELECT haslo FROM apteczki WHERE nazwa = '$nazwaApteczki'";
		$result = Zapytanie($kwerenda);
		$hasloApteczkiTest = $result->fetch_object()->haslo;
		
		$kwerenda = "SELECT id FROM test_users WHERE email = '$user'";
		$result = Zapytanie($kwerenda);
		$idUzytkownika = $result->fetch_object()->id;
		
		$kwerenda = "SELECT id FROM apteczki WHERE nazwa = '$nazwaApteczki'";
		$result = Zapytanie($kwerenda);
		$idApteczki = $result->fetch_object()->id;
		
		$kwerenda = "SELECT id_uzytkownika FROM Dostep WHERE id_apteczki = '$idApteczki'";
		$result = Zapytanie($kwerenda);
		
		if($nazwaApteczkiTest == ""){
			echo "Nie istnieje apteczka o takiej nazwie lub nieprawidłowe hasło.";
			$poprawneDane = false;
		}
		else if($hasloApteczkiTest != $hasloApteczki){
			echo "Nie istnieje apteczka o takiej nazwie lub nieprawidłowe hasło.";
			$poprawneDane = false;
		}
		else if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					 if($row["id_uzytkownika"] == $idUzytkownika){
						echo "Już jesteś przypisany do wybranej apteczki.";
						$poprawneDane = false;
						break;
					 }
				} 
		}
		if($poprawneDane){
	 		$kwerenda = "INSERT INTO `kpi`.`Dostep` (`id`, `id_uzytkownika`, `id_apteczki`, `czy_admin`) VALUES (NULL, '$idUzytkownika', '$idApteczki', '0')";
	 		$result = Zapytanie($kwerenda);
			echo "Zostałeś/aś dodany/a do apteczki.";
		}
	}
	break;
	
	default:
	break;
}
?>

</div>