<link rel="stylesheet" href="spomenar.css">

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

	$passkey = isset($_GET["passkey"]) ? $_GET["passkey"] : null;
    if ($passkey != null) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE code=:code");
        $stmt->bindParam(':code', $passkey, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchObject();

        if ($result) {
           	echo "Uspješno ste aktivirali račun.";
			$query = $conn->prepare("UPDATE users SET visited=:visited WHERE code=:code");
            $val = 1;
            $query->bindParam(":visited", $val, PDO::PARAM_INT);
            $query->bindParam(":code", $passkey, PDO::PARAM_STR);
            $query->execute();
        }
    }

	/* započni ispis leksikona...
	1) dohvati pitanje, ispiši ga
	2) ispiši polje za unos odgovora
	3) dohvati i ispiši sve odgovore */

	if (isset($_POST["prev"]) && $_SESSION["broj_pitanja"] > 0) {
		--$_SESSION["broj_pitanja"];
		setPitanje($_SESSION["broj_pitanja"]);
	}

	if (isset($_POST['next'])) {
		$disabled = "";

		if ($_SESSION["broj_pitanja"]==0) { ?>
			<script> alert("Sad slijede neka općenita pitanja o tebi!"); </script>
		<?php }
		
		if ($_SESSION["broj_pitanja"]==16) { ?>
			<script> alert("Prebacimo se malo na ljubav."); </script>
		<?php }

		if ($_SESSION["broj_pitanja"]==21) { ?>
			<script> alert("Sada malo o životu i prijateljstvu..."); </script>
		<?php } 

		if ($_SESSION["broj_pitanja"]==35 && numberOfTextQuestions()>35) { ?>
			<script> alert("Slijede pitanja nekih naših korisnika."); </script>
		<?php }
		
		if ($_SESSION["broj_pitanja"]==numberOfTextQuestions()) { ?>
			<script> alert("Sada ćeš morati birati - ili-ili! Dobro razmisli, odgovore na ova pitanje nije moguće ureživati, za razliku od prijašnjih! "); </script>
		<?php }

		if ($_SESSION["broj_pitanja"]==numberOfTextQuestions()+7) { ?>
			<script> alert("Slijede ili-ili pitanja nekih naših korisnika."); </script>
		<?php }

		if ($_SESSION["broj_pitanja"]==totalNumberOfQuestions()) { ?>
			<script> alert("Hvala ti što si ispunio naš mali leksikon! Slobodan si!"); </script>
		<?php }		

		setSessionPitanje();
		setPitanje($_SESSION["broj_pitanja"]);
	} 

	if (isset($_POST['odg']) ) {
		if($_SESSION["broj_pitanja"]<=numberOfTextQuestions()) {
		
			spremiOdgovor($_SESSION["user_id"] , $_SESSION["broj_pitanja"], $_POST['odgovor']); 
		}
		else {
			spremiOdgovor($_SESSION["user_id"] , $_SESSION["broj_pitanja"], $_POST['ili']); 
		}	
	} 
	?>

	<?php include "header.php"; ?> 
	<div class="main">
		<div class="pitanja"> <?php
		if (isset($_SESSION["broj_pitanja"])) {
			if ($_SESSION["broj_pitanja"] == 0) { ?> 
				<h2> Dobrodošli u leksikon! </h2>
				<form action="spomenar.php" method="post" enctype="multipart/form-data">
			    	<p> Za početak, prije teških pitanja, odaberite proizvoljnu sliku i pošaljite nam ju. 
			    	Po mogućnosti neka to bude baš vaša slika. </p>
			    	<input type="file" class="file" name="images" id="images"> <br/>
			    	<input type="submit" class="odg" name="upload" value="Pošalji" > </br>
			    </form> <?php 

				if (isset($_POST["upload"])) {
					uploadImage();
				} ?>
			<?php
					$images = glob('slike/*');
					natsort($images);
					foreach ($images as $image) {	
							$imageName = pathinfo($image,PATHINFO_FILENAME);
						?> 
						<div class="div_image_name"> 
							<b> <?php echo $imageName; ?>. </b>
						</div> 
							<img src=  <?php echo "'" . $image . "'"; ?> width="550px" > </img>
			 			<?php
					} ?>	
			<?php
			}
			else if ($_SESSION["broj_pitanja"] <= totalNumberOfQuestions()) { ?>
				<form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="post">
					<h2>  <?php echo $_SESSION["broj_pitanja"], ". ", $_SESSION["pitanje"]; ?> </h2>
					<?php echo tip_pitanja($_SESSION["broj_pitanja"]);?>
					<input type="submit" class="odg" name="odg" value="Pošalji!" > </p>
					<br> <br>
					<div> <?php echo prikazi_dosadasnje($_SESSION["broj_pitanja"]);?> </div>
					<br>
				</form> <?php 
			}

			else { ?>
				<p> Za sam kraj, ako želiš, crtaj do mile volje. Uživaj i pozdrav! </p> 
				<div id="platno"> 
					<canvas id="can" width="400" height="400" style="position:absolute;top:10%;left:10%;border:2px solid;"></canvas>
					<div style="position:absolute;top:12%;left:43%;"> boja </div>
					<div style="position:absolute;top:15%;left:45%;width:10px;height:10px;background:green;" id="green" onclick="color(this)"></div>
					<div style="position:absolute;top:15%;left:46%;width:10px;height:10px;background:blue;" id="blue" onclick="color(this)"></div>
					<div style="position:absolute;top:15%;left:47%;width:10px;height:10px;background:red;" id="red" onclick="color(this)"></div>
					<div style="position:absolute;top:17%;left:45%;width:10px;height:10px;background:yellow;" id="yellow" onclick="color(this)"></div>
					<div style="position:absolute;top:17%;left:46%;width:10px;height:10px;background:orange;" id="orange" onclick="color(this)"></div>
					<div style="position:absolute;top:17%;left:47%;width:10px;height:10px;background:black;" id="black" onclick="color(this)"></div>
					<div style="position:absolute;top:20%;left:43%;"> gumica </div>
					<div style="position:absolute;top:23%;left:44.8%;width:15px;height:15px;background:white;border:2px solid;" id="white" onclick="color(this)"></div>
					<img id="canvasimg" style="position:absolute;top:10%;left:52%;" style="display:none;"> </img>
					<input type="button" value="obriši" id="clr" size="23" onclick="erase()" style="position:absolute;top:55%;left:15%;">
				</div> <?php
			}
		}?>
		<?php
		include "footer.php"; ?>