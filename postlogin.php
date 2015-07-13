<?php
	session_start();
	include "header.php"; ?>
	<link rel="stylesheet" href="spomenar.css">

	<div class="main">
		<div class="pitanja">
			<p> Uspješno ste se prijavili u sustav. Vaš redni broj je 
			<?php echo $_SESSION["user_id"]; ?>.</p> <br/>

			<h3>Slijedi nekoliko uputa koje će vam pomoći pri popunjavanju leksikona:</h3>
			
			<p>Klikom na gumb <i>Slažem se!</i> počinjete popunjavati leksikon. Ujedno se obvezujete se da ćete govoriti istinu i samo istinu.<br>
			<p>Nije moguće odgovarati na sljedeće pitanje ako niste odgovorili na trenutno.<br>
			Ukoliko se predomislite u vezi odgovora, unesite novi, pošaljite ga i taj odgovor će se 
			zamijeniti sa starim.<br>
			Budite maštoviti pri odgovaranju i zabavite se čitajući odgovore drugih korisnika!</p>
			<form name="form_login" method="POST" action="spomenar.php" >
			    <input type="submit" class="next" value="Slažem se!">
			</form>
			</p>
			<br>
			<?php  
				require_once("php_functions.php");
				if(admin($_SESSION["user_id"])==1) {
				?>
			<br/> <br/>
			<p> Kao administrator klikom na donji gumb imate mogućnost postavljanja novih pitanja. 
			Nakon unosa novog pitanja, svi naši korisnici će putem emaila biti obaviješteni o 
			novom pitanju. </p>
			<form name="form_pitanje" method="POST" class="next" action="unesi_pitanje.php" >
				<input type="submit" value="Postavi nova pitanja!">
			</form>
			<?php } ?>
		</div>
	</div>

</body>
</html>

