<?php
	include("nagl.php");
	echo "<H1>Błąd aplikacji</H1>";
	$a = $_GET['tx_err'];
	echo $_GET['tx_err'] . ": " . $_GET['gdzie'] . "<br>";
?>
<a href="http://localhost/~Kasia/bb/00.php?mn=-1">Strona główna serwisu</a>
<?php
	include("stopka.php");
?>