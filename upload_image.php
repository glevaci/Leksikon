<?php
	function uploadImage() {
		echo getcwd() . "<br/>";
		$target_dir = "./slike/";
		$target_file = $target_dir . basename($_FILES["images"]["name"]);
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

		foreach (glob("*.txt") as $filename) {
    		echo $filename . "\n";
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
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
		    echo "Oprosti, greška, probaj opet.";
		// if everything is ok, try to upload file
		} else {
			$temp = explode(".",$_FILES["images"]["name"]);
			$newFilename = $_SESSION["user_id"] . '.' .end($temp);

			

		    if (move_uploaded_file($_FILES["images"]["tmp_name"], "./slike/" . $newFilename)) {
		        echo "Uspješno ste poslali ". basename( $_FILES["images"]["name"]). ".";
		    } else {
		        echo "Isprike, greška pri slanju fajla.";
		    }
		}
	}