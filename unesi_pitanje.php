<?php  	
	require_once("php_functions.php");

	if (isset($_POST['prvi'])) {
		if(isset($_POST['pitanje'])){

			if ($_POST['pitanje']=="") {
				echo "Unesi pitanje!";
			}
			else {
				$npitanje =$_POST["pitanje"];
				unesi_u_bazu_tekst($npitanje);
				notifyAboutNewQuestions();
				echo "Uspjesno si postavio novo pitanje, svi korisnici bit će obaviješteni o tome.  <br> Očekujemo zanimljive odgovore.";


			}
		}
		else echo "Unesite pitanje!";
		

    }
	elseif (isset($_POST['ili'])) {
		if(isset($_POST['value1']) && isset($_POST['value2']) ){

			if ($_POST['value1']=="" || $_POST['value2']=="") {
				echo "Popuni oba polja!";
			}
			else {
					$v1=$_POST["value1"];
					$v2=$_POST["value2"];
					unesi_u_bazu_ili($v1,$v2);
					notifyAboutNewQuestions();
					echo "Uspjesno si postavio novo pitanje, svi korisnici bit će obaviješteni o tome.  <br> Očekujemo zanimljive odgovore.";

			}
		}
	    else echo "Popuni oba polja!";
		

   	
	}
    

?>

<?php include "header.php"; ?>
		<h2> Pozdrav, naš admine! Ovdje možeš postaviti nova pitanja za leksikon. </h2>
		<p>*ispod možeš vidjeti sva dosad postavljena pitanja, pa pripazi da koje ne ponoviš </p>
		<div class="pitanja">
			<h2> Postavi novo tekstualno pitanje:</h2>

		<form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="post">
				Novo pitanje:<input type="text" name="pitanje" size="50%" height="100"/></br>

				<br>
				<input type="submit" name="prvi" value="Pošalji!">

			
		
			<h2> Postavi novo ili-ili pitanje:</h2>

			
				<input type="text" name="value1"> ili <input type="text" name="value2">

				<br>
				<input type="submit" name="ili" value="Pošalji!">

			</form>
		</div>

		<div class="Tekstualna pitanja do sad">
			<h3> Dosad postavljena tekstualna pitanja:</h3>
		<?php  
			require_once("php_functions.php");
			prikazi_pitanja();
			?>
		</div>

		<div class="Ili-ili pitanja do sad">
			<h3> Dosad postavljena ili-ili pitanja:</h3>
		<?php  
			require_once("php_functions.php");
			prikazi_pitanja_ili();
			?>
		</div>
	
	
</body>
</html>	

