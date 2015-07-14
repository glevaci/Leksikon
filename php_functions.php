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
	}

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
				<a href="index.php"> Vrati se nazad, pokušaj ponovno.</a>
				 <?php exit();
	        }

	        else if ( $result->password === crypt($password, $result->password)) {
	       		setSessionId($username);
	       		header("Location: postlogin.php");
	        }
	        else { ?>
	            <p> Pogrešna lozinka! <br/>
	           	<a href="index.php"> Vrati se nazad, pokušaj ponovno.</a> </p> <?php
	        }
			include "footer.php";
	        //$conn = null;
	    }
	    catch(PDOException $e) {
	    }
	}

	function databaseRegister($username, $password, $email) {
		
		global $conn;

		try {
			$stmt = $conn->prepare("SELECT * FROM Users WHERE username=:username");
	        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
	        $stmt->execute();
	        $result = $stmt->fetchObject();
	        include "header.php"; 
	        if ($result) { ?>
	            <p> To korisničko ime nije dostupno. Odaberite neko drugo. </p>
				<a href="index.php"> Vrati se nazad, pokušaj ponovno.</a>
				<?php exit();
	        }
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

	        if (isset($_POST["admin"])) {
	        	$code = uniqid("", true);
	        	$stmt = $conn->prepare("UPDATE Users SET code=:code WHERE username=:username");
		       	$stmt->bindParam(':username', $username, PDO::PARAM_STR);
		       	$stmt->bindParam(':code', $code, PDO::PARAM_STR);
		       	$stmt->execute();

		        $link = "http://192.168.89.245/~glevaci/Projekt/activate.php?passkey=".$code;

		        $subject = "Potvrda prijave za administratora leksikona";
		        $message = "Potvrdi prijavu za administratora leksikona:
		        			\r\n" . $link . "\r\n 
		        			Pozdrav, \r\n 
		        			administratori tvojeg leksikona";

		        mail($email, $subject, $message);
			}
			setSessionId($username);
			header("Location: postlogin.php");
	    }
	    catch(PDOException $e) {}
	}

	function setPitanje ($broj_pitanja) {
		global $conn;
		//echo $broj_pitanja;
		if($broj_pitanja <= numberOfTextQuestions() && $broj_pitanja>0 ){
			$stmt = $conn->prepare('SELECT * FROM Questions WHERE question_id=:question_id');
	    	$stmt->bindParam(':question_id', $broj_pitanja, PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetchObject();
			$pitanje = $result->question;
			$_SESSION["pitanje"]=$pitanje;}
			else {
				$_SESSION["pitanje"]=" ";
			}
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

	function vratiValue($broj_pitanja, $p){
			global $conn;
		//echo $broj_pitanja;
		
			$stmt = $conn->prepare('SELECT * FROM ili_ili WHERE id_ili=:id_ili');
			$b=$broj_pitanja-numberOfTextQuestions();
	    	$stmt->bindParam(':id_ili', $b, PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetchObject();
			$v1 = $result->value1;
			$v2 = $result->value2;
			if($p==1) return $v1;
			else return $v2;
	}

	function uploadImage() {
		$target_dir = "./slike/";
		$target_file = $target_dir . basename($_FILES["images"]["name"]);
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

		foreach (glob('slike/*') as $image) {	
			$imageName = pathinfo($image,PATHINFO_FILENAME);

			if ($imageName == $_SESSION["user_id"]) {
				unlink($image);
				break;
			}
		}

		if(isset($_POST["upload"])) {
		    $check = getimagesize($_FILES["images"]["tmp_name"]);
		    if($check !== false) {
		        $uploadOk = 1;
		    } else {
		        echo "Niste odabrali dokument koji je slika!";
		        $uploadOk = 0;
		    }
		}
		if ($_FILES["images"]["size"] > 1048576) {
		    echo "Slika je prevelika, odaberi neku manju!";
		    $uploadOk = 0;
		}
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

	function tip_pitanja($broj_pitanja){
		if($broj_pitanja==5){
			return '<input type="text" name="odgovor" id="datepicker">';
		}
		
		elseif($broj_pitanja<=numberOfTextQuestions()){
			return '<input name="odgovor" type="text" />';
		}
		else {
			$v1=vratiValue($broj_pitanja, 1);
			$v2=vratiValue($broj_pitanja, 2);
		 	$r1='<input type="radio" name="ili" value="value1" checked> '
		 		.$v1.'<br><input type="radio" name="ili" value="value2"> '. $v2 . '<br/><br/>';
			return $r1;
		}
	}


	function spremiOdgovor($user, $broj_pitanja, $odgovor) {
		
		global $conn;

		try {
	        if($broj_pitanja<=numberOfTextQuestions()){
	    	
	    		$stmt = $conn->prepare("SELECT * FROM Answers 
									WHERE question_id=:question_id AND user_id=:user_id");
		        $stmt->bindParam(':question_id', $broj_pitanja, PDO::PARAM_INT);
    	        $stmt->bindParam(':user_id', $user, PDO::PARAM_INT);
	    	    $stmt->execute();
	        	$result = $stmt->fetchObject();

	    	    if ($result && !empty(trim($_POST['odgovor']))) {
	        		$x = empty(trim($_POST['odgovor']));
	        		//var_dump($x);

					$stmt = $conn->prepare("UPDATE Answers SET answer=:answer 
										WHERE question_id=:question_id AND user_id=:user_id");
					$stmt->bindParam(':question_id', $broj_pitanja, PDO::PARAM_INT);
	        	    $stmt->bindParam(':user_id', $user, PDO::PARAM_INT);
	            	$stmt->bindParam(':answer', $odgovor, PDO::PARAM_STR);				
	        	    $stmt->execute();
				}
	        
	    	    else if(!$result && !empty(trim($_POST['odgovor']))) {
			        $stmt = $conn->prepare("INSERT INTO Answers (question_id, user_id, answer)
		    	    VALUES (:question_id, :user_id, :answer)");
			       	$stmt->bindParam(':question_id', $broj_pitanja, PDO::PARAM_INT);
			        $stmt->bindParam(':user_id', $user, PDO::PARAM_INT);
			        $stmt->bindParam(':answer', $odgovor, PDO::PARAM_STR);
		    	    $stmt->execute();
		    	}
		    	else 
	    			echo "<script>alert('Molimo unesite odgovor');</script>";
	    	}

	    	else{

	    		$b=$broj_pitanja - numberOfTextQuestions();
	    		
	    		$stmt = $conn->prepare("SELECT * FROM Answers_ili_ili 
									WHERE question_id=:question_id AND user_id=:user_id");
		        $stmt->bindParam(':question_id', $b, PDO::PARAM_INT);
    	        $stmt->bindParam(':user_id', $user, PDO::PARAM_INT);
	    	    $stmt->execute();
	        	$result = $stmt->fetchObject();

	    		$stmt1 = $conn->prepare("SELECT * FROM ili_ili WHERE id_ili=:id_ili");
		        $stmt1->bindParam(':id_ili', $b, PDO::PARAM_INT);
	    	    $stmt1->execute();
	        	$result1 = $stmt1->fetchObject();
	        	$o="";
	        	
	        	if($odgovor=="value1") {
	        		$o=$result1->value1;
	        	}
	       		else {
	       			$o=$result1->value2;
	        	}
	        	if(!$result ){	
	        		 $s = $conn->prepare("INSERT INTO Answers_ili_ili (question_id, user_id, answer)
		    	    VALUES (:question_id, :user_id, :answer)");
			       	$s->bindParam(':question_id', $b, PDO::PARAM_INT);
			        $s->bindParam(':user_id', $user, PDO::PARAM_INT);
			        $s->bindParam(':answer', $o, PDO::PARAM_STR);
			        
		    	    $s->execute();		    	
		    	}
		    	elseif($result ) {
		    		$stmt = $conn->prepare("UPDATE Answers_ili_ili SET answer=:answer 
										WHERE question_id=:question_id AND user_id=:user_id");
					$stmt->bindParam(':question_id', $b, PDO::PARAM_INT);
	        	    $stmt->bindParam(':user_id', $user, PDO::PARAM_INT);
	            	$stmt->bindParam(':answer', $o, PDO::PARAM_STR);				
	        	    $stmt->execute();
		    	}

		    	else{	
	    			echo "<script>alert('Greska pri dohvacanju iz baze!');</script>";
		    	}
	    	}
	    }
	    catch(PDOException $e) {}
	}

	function prikazi_dosadasnje ($broj_pitanja) {
		global $conn;
		//echo $broj_pitanja;
		class odgovori{
			public $question_id, $user_id, $answer,$ispis;
			public function __construct(){
				$this->ispis= "<b>{$this->user_id}.</b> {$this->answer}";
			}
		}

		 if($broj_pitanja<=numberOfTextQuestions()){
		
		$stmt = $conn->prepare("SELECT * FROM Answers WHERE question_id=:my_param ORDER BY user_id");
		$stmt->bindParam(':my_param', $broj_pitanja, PDO::PARAM_INT);

		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'odgovori');
		while($r= $stmt->fetch()){
				echo $r->ispis . "<br>";}
		}
		else {
		$b=$broj_pitanja - numberOfTextQuestions();	

			$stmt = $conn->prepare("SELECT * FROM Answers_ili_ili WHERE question_id=:my_param ORDER BY user_id");
		$stmt->bindParam(':my_param', $b, PDO::PARAM_INT);

		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'odgovori');
		while($r= $stmt->fetch()){
				echo $r->ispis . "<br>";}
		}	
	}

	function isAnswered() {
		if ($_SESSION["broj_pitanja"]==0) {
			foreach (glob('slike/*') as $image) {	
				$imageName = pathinfo($image,PATHINFO_FILENAME);
				//echo "Filename: " . $imageName . "<br/>";
				if ($imageName == $_SESSION["user_id"]) {
					return "";
				}
			}
			return "disabled";
		}

		elseif ($_SESSION["broj_pitanja"]<=numberOfTextQuestions()){
			global $conn;
			try {
				$stmt = $conn->prepare("SELECT * FROM Answers 
										WHERE question_id=:question_id AND user_id=:user_id");
		        $stmt->bindParam(':question_id', $_SESSION["broj_pitanja"], PDO::PARAM_INT);
	            $stmt->bindParam(':user_id', $_SESSION["user_id"], PDO::PARAM_INT);
		        $stmt->execute();
		        $result = $stmt->fetchObject();

		        if ($result)
		        	return "";
		        else 
		        	return "disabled";
		    }
		    catch(PDOException $e) {}	
		}
		else{
			global $conn;
			try {
				$stmt = $conn->prepare("SELECT * FROM Answers_ili_ili 
										WHERE question_id=:question_id AND user_id=:user_id");
				$b= $_SESSION["broj_pitanja"]- numberOfTextQuestions();	
		        $stmt->bindParam(':question_id', $b, PDO::PARAM_INT);
	            $stmt->bindParam(':user_id', $_SESSION["user_id"], PDO::PARAM_INT);
		        $stmt->execute();
		        $result = $stmt->fetchObject();

		        if ($result)
		        	return "";
		        else 
		        	return "disabled";
		    }
		    catch(PDOException $e) {}	
		}
	}

	function numberOfIliIli() {
		global $conn;
		try {
			$rowsIliIli = $conn->query("SELECT COUNT(*) FROM ili_ili")->fetchColumn();
			return intval($rowsIliIli);
		}
		catch(PDOException $e) {}
	}

	function numberOfTextQuestions() {
		global $conn;
		try {
			$rowsQuestions = $conn->query("SELECT COUNT(*) FROM Questions")->fetchColumn();
			return intval($rowsQuestions);
		}
		catch(PDOException $e) {}
	}

	function totalNumberOfQuestions() {
		return numberOfTextQuestions()+numberOfIliIli();
	}

	function notifyAboutNewQuestions() {
		global $conn;
		$stmt = $conn->query("SELECT email FROM Users");
		$stmt->execute();
		$subject = "Nova pitanja u leksikonu";
		$message = "Poštovani, pojavila su se nova pitanja u leksikonu.
					Zanimaju nas vaši odgovori! \r\n
					Pozdrav,\r\n
					administratori tvojeg leksikona";

		while ($r = $stmt->fetch()){
			//echo $r["email"];
		    mail($r["email"], $subject, $message);
		}
	}


	function unesi_u_bazu_tekst($pitanje){
		global $conn;

		try {
	        $stmt = $conn->prepare("INSERT INTO Questions (question)
	        						VALUES (:question)");
	       	$stmt->bindParam(':question', $pitanje, PDO::PARAM_STR);
	        $stmt->execute();
	       
	    }
	    catch(PDOException $e) {}
	}

	function unesi_u_bazu_ili($v1,$v2){
		global $conn;

		try {
	        $stmt = $conn->prepare("INSERT INTO ili_ili (value1, value2)
	        						VALUES (:value1, :value2)");

	       	$stmt->bindParam(':value1', $v1, PDO::PARAM_STR);
	        $stmt->bindParam(':value2', $v2, PDO::PARAM_STR);
	        $stmt->execute();
	    }
	    catch(PDOException $e) {}
	}

	function prikazi_pitanja() {
		global $conn;
		//echo $broj_pitanja;
		class pitanje{
			public $question_id,$question, $ispis;
			public function __construct(){
				$this->ispis= "<b> {$this->question_id}.</b> {$this->question}";
			}
		}

		$stmt = $conn->prepare("SELECT * FROM Questions ORDER BY question_id");
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'pitanje');
		while($r= $stmt->fetch()){
				echo $r->ispis . "<br>";}
		}

	function prikazi_pitanja_ili() {
		global $conn;
		//echo $broj_pitanja;
		class pitanje1{
			public $id_ili, $value1, $value2, $ispis;
			public function __construct(){
					$this->ispis= "<b> {$this->id_ili}.</b> {$this->value1} ili {$this->value2} ";
			}
		}

		$stmt = $conn->prepare("SELECT * FROM ili_ili ORDER BY id_ili");
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'pitanje1');
		while($r= $stmt->fetch()){
				echo $r->ispis . "<br>";}
	}

   	function admin($u){
   		global $conn;
		$stmt = $conn->prepare('SELECT * FROM Users WHERE user_id=:user_id');
	    $stmt->bindParam(':user_id', $u, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetchObject();
		$p = $result->admin;
		return $p;
   }