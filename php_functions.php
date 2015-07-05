<?php
	// podaci za bazu
	$servername = "192.168.89.245";
    $db_username = "student";
    $db_password = "pass.mysql";
    $db_name = "glevacic";

    $conn = new PDO("mysql:host=$servername;dbname=$db_name", $db_username, $db_password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	function setSessionId ($username) {
		global $conn;
		$stmt = $conn->prepare("SELECT * FROM Users WHERE username=:username");
	    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetchObject();
		$_SESSION["user_id"] = (int)$result->user_id;
		var_dump($_SESSION["user_id"]);
	}

    // provjeri je li osoba koja se logira preko Facebooka već u bazi, ili ju treba tek ubaciti
    // za doraditi, treba provjeriti ima li ga u bazi
	/*function checkFacebook( $name, $email) {	
		
		global $conn;
		try {
	   
	        // set the PDO error mode to exception
	        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	       
	        // prepare sql and bind parameters
	        $stmt = $conn->prepare("SELECT * FROM Users WHERE username=:username");
	        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
	        $stmt->execute();
	        $result = $stmt->fetchObject();
	        if (!$result) {
	            // dodaj u bazu - izvući van dio za baš ubacivanje u bazu kao posebnu funkciju?

	        }
			else {
				// postavi $_SESSION["user_id"]
				setSessionId($username);
			}
	        
	        $conn = null;
	    }

	    catch(PDOException $e) {
	        echo "Error: " . $e->getMessage();
	    }
	}*/

	// provjeri je li se osoba dobro logirala u sustav
	function databaseLogin( $username, $password) {
		
		global $conn;
		try {
	        // prepare sql and bind parameters
	        $stmt = $conn->prepare("SELECT * FROM Users WHERE username=:username");
	        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
	        $stmt->execute();
	        $result = $stmt->fetchObject();
	        include "header.php"; 
	        if (!$result) { ?>
	            <p> Korisničko ime ne postoji u bazi! </p>
				<a href="login.php"> Vrati se nazad, pokušaj ponovno.</a>
				 <?php exit();
	        }

	        else if ( $result->password === crypt($password, $result->password)) { ?>
	           <p> Uspješna prijava u sustav, uvaženi <?php echo $result->username; ?> ! <br/>"
	            <?php setSessionId($username);
	        }
	        else { ?>
	            <p> Pogrešna lozinka! <br/>
	           	<a href="login.php"> Vrati se nazad, pokušaj ponovno.</a> </p> <?php
	        }
			include "footer.php";
	        $conn = null;
	    }
	    catch(PDOException $e) {
	        echo "Error: " . $e->getMessage();
	    }
	}

	// ubaci novog korisnika u bazu
	// za dodati: provjera je li username jedinstven
	function databaseRegister($username, $password, $email) {
		
		global $conn;

		try {
	        // prepare sql and bind parameters
	        $stmt = $conn->prepare("INSERT INTO Users (username, password, email)
	        VALUES (:username, :password, :email)");

	        # enkriptiraj lozinku prije spremanja u bazu
	        $cost = 10;
	        $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
	        $salt = sprintf("$2a$%02d$", $cost) . $salt;
	        $hash = crypt($password, $salt);
	       	$stmt->bindParam(':username', $username, PDO::PARAM_STR);
	        $stmt->bindParam(':password', $hash, PDO::PARAM_STR);
	        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
	        $stmt->execute();

	        setSessionId($username);
	    	$conn = null;
	    }
	    catch(PDOException $e) {
	        echo "Error: " . $e->getMessage();
	    }
	}


	function setPitanje ($broj_pitanja) {
		global $conn;
		$stmt = $conn->prepare("SELECT * FROM Questions WHERE question_id=:broj_pitanja");
	    $stmt->bindParam(':broj_pitanja', $broj_pitanja, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetchObject();
		$pitanje = $result->question;
		//var_dump($_SESSION["user_id"]);
		echo $broj_pitanja, ".", $pitanje;
	}

		function setSessionPitanje () {
		global $conn;
		if( !isset($_SESSION["broj_pitanja"]) ){ $_SESSION["broj_pitanja"]=1;}
		else {$_SESSION["broj_pitanja"]=$_SESSION["broj_pitanja"]+1;}
		
	}
