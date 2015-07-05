<?php
	header('Content-type: text/html; charset=UTF-8');
	require_once("php_functions.php");
	session_start();
	
	//$_SESSION["broj_pitanja"] = 0;

	$action = isset($_GET["action"]) ? $_GET["action"] : null;

	include "header.php"; 


    if ($action == "login") {
		$username = $_POST["logUsername"];
		$password = $_POST["logPassword"];
		databaseLogin($username, $password);
	}

	if ($action == "register") {
	    $username = $_POST["regUsername"];
	    $password = $_POST["regPassword"];
	    $email = $_POST["regMail"];
	    databaseRegister($username, $password, $email);
	}

	/* započni ispis leksikona...
	1) dohvati pitanje, ispiši ga
	2) ispiši polje za unos odgovora
	3) dohvati i ispiši sve odgovore */

	//while($_SESSION["broj_pitanja"]<31 || !isset($_SESSION["broj_pitanja"])) {
		
		//echo $_SESSION["broj_pitanja"], ".", $_SESSION["pitanje"]  ;

	//}
	if (isset($_POST['next'])) {

		if (isset($_POST['odgovor'])) {spremiOdgovor();}

		setSessionPitanje();
		setPitanje($_SESSION["broj_pitanja"]);

		
	}



	// mi uploadamo sliku



	
	if ($_SESSION["broj_pitanja"] == 0) { ?> 
		<form action="spomenar.php?action=slika" method="post" enctype="multipart/form-data">
	    	Za početak, oadberite proizvoljnu sliku i pošaljite nam ju. Po mogućnosti sliku svoju.
	    	<input type="file" name="fileToUpload" id="fileToUpload">
	    	<input type="submit" value="Pošalji" name="upload">
		</form>
		<?php uploadImage();
	}

	include "footer.php";
?>
<?php include "header.php"; ?> 
	<form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="post">
		<p>  <?php echo $_SESSION["broj_pitanja"], ".", $_SESSION["pitanje"]; ?> </p>
		<?php echo tip_pitanja_pocetak($_SESSION["broj_pitanja"]);?>
		<?php echo tip_pitanja_kraj($_SESSION["broj_pitanja"]);?>
		<br>
		<input type="submit"  name='next' value= "Slijedeće pitanje!" >
	</form>		 


<?php  include "footer.php"; ?>
