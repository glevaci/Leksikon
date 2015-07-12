<?php
	session_start();
	include "header.php"; 
?>
	<div name="uvod">
		<p> Uspješno ste se prijavili u sustav. Vaš redni broj je 
		<?php echo $_SESSION["user_id"]; ?>. <br/>

		Klikom na donji gumb obvezujete se da ćete govoriti istinu kenj kenj kenj vi to napišite lijepo.
		<form name="form_login" method="POST" action="spomenar.php" >
		    <input type="submit" value="Slažem se!">
		</form>
		</p>
	</div>

</body>
</html>

