<?php 
if($_SESSION['Etap'] != "StartLogowania"){
			//header("Location: ./../00.php?mn=-1");
		}
		$_SESSION['Error'] = "";
		if(isset($_POST['inpLogName'])){
			// Sprawdzam czy podane konto istnieje
			/*$LogName = trim($_POST['inpLogName']);
			$kwerenda = "SELECT email FROM test_users WHERE email = '$LogName'";
			$result = Zapytanie($kwerenda);
			$zBazyName = $result->fetch_object()->email;
			
			// Sprawdzam czy hasło poprawne
			$LogHaslo = md5(trim($_POST['inpLogHaslo']));
			$kwerenda = "SELECT haslo FROM test_users WHERE email = '$LogName'";
			$result = Zapytanie($kwerenda);
			$zBazyHaslo = $result->fetch_object()->haslo;
			if($LogHaslo == $zBazyHaslo){
				$_SESSION['user'] = $zBazyName;
				unset($_SESSION['Etap']);
			} else{
				$_SESSION['Error'] = "Błąd danych logowania";
			}*/
			$LogName = trim($_POST['inpLogName']);
			$LogHaslo = md5(trim($_POST['inpLogHaslo']));
			$kwerenda = "SELECT * FROM test_users WHERE email = '$LogName' OR nazwa = '$LogName'";
			$result = Zapytanie($kwerenda);
			$row = $result->fetch_assoc();
			$zBazyEmail = $row["email"];
			$zBazyNazwa = $row["nazwa"];
			$zBazyHaslo = $row["haslo"];
			$confirmed = $row["confirmed"];
			
			// Sprawdzam czy hasło poprawne
			if($LogHaslo == $zBazyHaslo && $confirmed){
				$_SESSION['user'] = $zBazyEmail;
				$_SESSION['nazwa'] = $zBazyNazwa;
				unset($_SESSION['Etap']);
			} else{
				$_SESSION['Error'] = "Błąd danych logowania";
			}
		}
		if(!isset($_SESSION['user'])){
			if($_SESSION['Error'] != ""){
				echo "<br>" . $_SESSION['Error'] . "<br>";
			}
		?>
		
		<div class="container">
			<br><h5 class="display-4">Logowanie do systemu</h5><br>
			<fieldset>
				<form action="<?php echo $PlikGlowny . "?mn=0&ms=1"; ?>" method = "POST">
					<div class="form-group">
						<label for="exampleInputEmail1">E-mail lub nazwa użytkownika:</label>
						<input type="text" name="inpLogName" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Wprowadź swój e-mail lub nazwę użytkownika" required>
					</div>
					<div class="form-group">
					  <label for="exampleInputPassword1">Hasło:</label>
					  <input type="password" name="inpLogHaslo" class="form-control" id="exampleInputPassword1" placeholder="Wprowadź hasło" required>
					</div>
					<input type="submit" class="btn btn-primary" value="Zaloguj">
				</form>
			</fieldset>
		</div>
		
		<?php
		}
?>