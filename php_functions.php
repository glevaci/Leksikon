<?php
	// podaci za bazu
	$servername = "192.168.89.245";
    $db_username = "student";
    $db_password = "pass.mysql";
    $db_name = "glevacic";

    $conn = new PDO("mysql:host=$servername;dbname=$db_name;charset=utf8", $db_username, $db_password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	function setSessionId ($username) {
		global $conn;
		$stmt = $conn->prepare("SELECT * FROM Users WHERE username=:username");
	    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetchObject();
		$_SESSION["user_id"] = (int)$result->user_id;
		$_SESSION["broj_pitanja"] = 0;
		//var_dump($_SESSION["user_id"]);
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
	           <p> Uspješna prijava u sustav, uvaženi <?php echo $result->username; ?> ! <br/>
	            <?php setSessionId($username);
	        }
	        else { ?>
	            <p> Pogrešna lozinka! <br/>
	           	<a href="login.php"> Vrati se nazad, pokušaj ponovno.</a> </p> <?php
	        }
			include "footer.php";
	        //$conn = null;
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
	    	//$conn = null;
	    }
	    catch(PDOException $e) {
	        echo "Error: " . $e->getMessage();
	    }
	}

	function setPitanje ($broj_pitanja) {
		global $conn;
		//echo $broj_pitanja;
		$stmt = $conn->prepare('SELECT * FROM Questions WHERE question_id=:question_id');
	    $stmt->bindParam(':question_id', $broj_pitanja, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetchObject();
		$pitanje = $result->question;
		//var_dump($_SESSION["user_id"]);
		//echo $broj_pitanja, ".", $pitanje;
		$_SESSION["pitanje"]=$pitanje;
	}


	function setSessionPitanje () {
		global $conn;
		if( !isset($_SESSION["broj_pitanja"]) ) {
			$_SESSION["broj_pitanja"] = 0;
		}
		else {
			$_SESSION["broj_pitanja"] = $_SESSION["broj_pitanja"]+1;
		}
	}

/*
	function imageUpload() {
		$imageJPG = $_SESSION["user_id"] . '.jpg';
		$imageJPEG = $_SESSION["user_id"] . '.jpeg';
		$imageGIF = $_SESSION["user_id"] . '.gif';
		$image = $_SESSION["user_id"] . '.png';


		
    		die('File with that name already exists.');
		}
	}
	*/


	function uploadImage() {
		// echo getcwd() . "<br/>";
		$target_dir = "./slike/";
		$target_file = $target_dir . basename($_FILES["images"]["name"]);
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

		foreach (glob('slike/*') as $image) {	
			$imageName= pathinfo($image,PATHINFO_FILENAME);
			echo "Filename: " . $imageName . "<br/>";

			if ($imageName == $_SESSION["user_id"]) {
				echo $image;
				unlink($image);
			}
		}

		// Check if image file is a actual image or fake image
		if(isset($_POST["upload"])) {
		    $check = getimagesize($_FILES["images"]["tmp_name"]);
		    if($check !== false) {
		        $uploadOk = 1;
		    } else {
		        echo "Niste odabrali dokument koji je slika!";
		        $uploadOk = 0;
		    }
		}
		// Check file size
		if ($_FILES["images"]["size"] > 1048576) {
		    echo "Prevelika ti je slika!";
		    $uploadOk = 0;
		}
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
		    echo "Samo JPG, JPEG, PNG i GIF smiješ poslati!";
		    $uploadOk = 0;
		}
		if ($uploadOk == 0) {
		    echo "Oprosti, greška, probaj opet.";
		} 
		else {
			$temp = explode(".",$_FILES["images"]["name"]);
			$newFilename = $_SESSION["user_id"] . '.' .end($temp);

		    if (move_uploaded_file($_FILES["images"]["tmp_name"], "./slike/" . $newFilename)) {
		        echo "Uspješno ste poslali ". basename( $_FILES["images"]["name"]). ".";
		    }
		    else {
		        echo "Isprike, greška pri slanju fajla.";
		    }
		}
	}

	function tip_pitanja_pocetak($broj_pitanja){
		if($broj_pitanja < 31){
			return '<input name="odgovor" type="text" />';
		}
	}

	function tip_pitanja_kraj($broj_pitanja){
		if($broj_pitanja < 31){
			return '';
		}
	}

	function spremiOdgovor($user, $broj_pitanja, $odgovor) {
		
		global $conn;

		try {
	        // prepare sql and bind parameters
	        $stmt = $conn->prepare("INSERT INTO Answers (question_id, user_id, answer)
	        VALUES (:question_id, :user_id, :answer)");

	        
	       	$stmt->bindParam(':question_id', $broj_pitanja, PDO::PARAM_INT);
	        $stmt->bindParam(':user_id', $user, PDO::PARAM_INT);
	        $stmt->bindParam(':answer', $odgovor, PDO::PARAM_STR);
	        $stmt->execute();

	       
	    	//$conn = null;
	    }
	    catch(PDOException $e) {
	        echo "Error: " . $e->getMessage();
	    }
	}

	function prikazi_dosadasnje ($broj_pitanja) {
		global $conn;
		//echo $broj_pitanja;
		class odgovori{
			public $question_id, $user_id, $answer,$ispis;
			public function __construct(){
					$this->ispis= "{$this->user_id} . {$this->answer}";

			}
		}
		
		$stmt = $conn->prepare("SELECT * FROM Answers WHERE question_id=:my_param ORDER BY user_id");
		$stmt->bindParam(':my_param', $broj_pitanja, PDO::PARAM_INT);

		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'odgovori');
		while($r= $stmt->fetch()){
				echo $r->ispis . "<br>";
		}

		//$stmt = $conn->prepare('SELECT * FROM Questions WHERE question_id=:question_id');
	    //$stmt->bindParam(':question_id', $broj_pitanja, PDO::PARAM_INT);
		//$stmt->execute();
		//$result = $stmt->fetchObject();
		//$pitanje = $result->question;
		//var_dump($_SESSION["user_id"]);
		//echo $broj_pitanja, ".", $pitanje;
		//$_SESSION["pitanje"]=$pitanje;
	}