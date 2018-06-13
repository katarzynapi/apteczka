<?php
session_start();
if($_SESSION['Etap'] != "StartLogowania"){
	//header("Location: ./../00.php?mn=-1");
}
$_SESSION['Error'] = "";
if(isset($_POST['inpLogName'])){
	// Sprawdzam czy podane konto istnieje
	$LogName = trim($_POST['inpLogName']);
	$kwerenda = "SELECT email FROM test_users WHERE email = '$LogName'";
	$result = Zapytanie($kwerenda);
	$zBazyName = $result->fetch_object()->email;
	
	// Sprawdzam czy hasło poprawne
	$LogHaslo = md5(trim($_POST['inpLogHaslo']));
	// $LogHaslo = trim($_POST['inpLogHaslo']);
	//echo "LogHaslo: " . $LogHaslo . " ";
	$kwerenda = "SELECT haslo FROM test_users WHERE email = '$LogName'";
	$result = Zapytanie($kwerenda);
	$zBazyHaslo = $result->fetch_object()->haslo;
	//echo "zBazyHaslo: " . $zBazyHaslo . " ";
	if($LogHaslo == $zBazyHaslo){
		$_SESSION['user'] = $zBazyName;
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
	<fieldset>
		<form action="<?php echo $PlikGlowny . "?mn=1"; ?>" method = "POST">
			<div class="form-group">
				<label for="exampleInputEmail1">E-mail:</label>
				<input type="email" name="inpLogName" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Wprowadź email" required>
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