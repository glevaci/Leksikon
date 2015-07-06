<?php
	header('Content-type: text/html; charset=UTF-8');
	require_once("php_functions.php");
	session_start();

	$action = isset($_GET["action"]) ? $_GET["action"] : null;

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

		

		setSessionPitanje();
		setPitanje($_SESSION["broj_pitanja"]);

	} 


	if (isset($_POST['odg']) ) {
		if($_POST['odgovor']) {
			//echo "evo odgovor:". $_POST['odgovor'];
			spremiOdgovor($_SESSION["user_id"] , $_SESSION["broj_pitanja"], $_POST['odgovor']);
		}
			else echo "Unesite, odgovor!";
	}

	?>

	<?php include "header.php"; 
	if ($_SESSION["broj_pitanja"] == 0) { ?> 
		<h1> Dobrodošli. </h1>
		<form action="spomenar.php?action=" method="post" enctype="multipart/form-data">
	    	Za početak, odaberite proizvoljnu sliku i pošaljite nam ju. Po mogućnosti sliku svoju.
	    	<input type="file" name="images" id="images">
	    	<input type="submit" name="upload" value="Pošalji" >
			<input type="submit"  name='next' value= "Sljedeće pitanje!" >
		</form>
		<?php uploadImage(); }
/*
<!--		<div name="slika">
			<b> <?php echo $_SESSION["user_id"]; ?> </b>
			<img src=\<?php echo $showimage; ?> />	
		</div> -->
*/
	else  { ?>

		<form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="post">
			<p>  <?php echo $_SESSION["broj_pitanja"], ".", $_SESSION["pitanje"]; ?> </p>
			<?php echo tip_pitanja_pocetak($_SESSION["broj_pitanja"]);?>
			<?php echo tip_pitanja_kraj($_SESSION["broj_pitanja"]);?>
			<br>
			<input type="submit" name="odg" value="Pošalji!" >
			<br>
			<div> <?php echo prikazi_dosadasnje($_SESSION["broj_pitanja"]);?> </div>
			<br>
			<input type="submit"  name='next' value= "Sljedeće pitanje!" >
		</form>		 

<?php }

	include "footer.php"; ?>