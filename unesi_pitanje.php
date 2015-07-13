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
					echo "Uspjesno si postavio novo pitanje, svi korisnici bit će obaviješteni o tome. 
						<br> Očekujemo zanimljive odgovore.";
			}
		}
	    else echo "Popuni oba polja!";
	}
    
?>

<?php include "header.php"; ?>
<link rel="stylesheet" href="spomenar.css">

	<div class="main">
		<div class="pitanja">
			<h2> Pozdrav, naš admine! Ovdje možeš postaviti nova pitanja za leksikon.* </h2>
			<p>* ispod možeš vidjeti sva dosad postavljena pitanja, pa pripazi da koje ne ponoviš </p>
			<h3> Postavi novo tekstualno pitanje:</h3>

			<form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="post">
				Novo pitanje:<input type="text" name="pitanje" size="50%" height="100"/></br>
				<input type="submit" name="prvi" class="odg"  value="Pošalji!">

				<h3> Postavi novo ili-ili pitanje:</h3>
				<input type="text" class="ili" name="value1"> ili <input type="text" class="ili" name="value2"> <br>
				<input type="submit" name="ili" class="odg" value="Pošalji!">
			</form>

			<h3> Dosad postavljena tekstualna pitanja:</h3>
			<?php  
				require_once("php_functions.php");
				prikazi_pitanja();
				?>

			<h3> Dosad postavljena ili-ili pitanja:</h3>
			<?php  
				require_once("php_functions.php");
				prikazi_pitanja_ili();
			?>
			<br/><br/><br/>
		</div>
	</div>

	
</body>
</html>	

