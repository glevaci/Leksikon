<?php
	header('Content-type: text/html; charset=UTF-8');
	require_once("php_functions.php");
	session_start();

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

	while($_SESSION["broj_pitanja"]<31 || !isset($_SESSION["broj_pitanja"])) {
		setSessionPitanje();
		setPitanje($_SESSION["broj_pitanja"]);

	}



	// mi uploadamo sliku



	
	if ($_SESSION["broj_pitanja"] == 0) { ?> 
		<h1> Dobrodošli. </h1>
		<form action="spomenar.php?action=slika" method="post" enctype="multipart/form-data">
	    	Za početak, odaberite proizvoljnu sliku i pošaljite nam ju. Po mogućnosti sliku svoju.
	    	<input type="file" name="images" id="images">
	    	<input type="submit" value="Pošalji" name="upload">
		</form>
		<?php uploadImage(); ?>

		<div name="slika">
			<b> <?php echo $_SESSION["user_id"]; ?> </b>
			<img src="\<?php echo $showimage; ?>" /> 	
		</div>



	}

<?php
	include "footer.php";
?>