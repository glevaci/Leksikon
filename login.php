<?php include "header.php"; ?>

<body>
<div class="container-fluid">
	<h1> Leksikon </h1>

	<p> Dobrodošli u naš mali leksikon! <br/>
	Slobodno ga ispunite. Kenj kenj kenj.
	</p>

	<!--
	<div class="fb-login-button" data-max-rows="1" data-size="large" data-show-faces="false" data-auto-logout-link="false"></div>
	<div id="status"></div>-->


	<div class="facebook">
		<div> <a href="fbconfig.php"> Prijava preko Facebooka  </a> </div>
	</div>

	<div class="Registracija">
		<h2> Registracija za nove korisnike: </h2>

		<form name="form_register" method="post" action="spomenar.php?action=register">
			Korisničko ime: <input type="text" name="regUsername" onkeyup="checkUsername()"> 
			<span class="registrationFormAlert" id="spanUsername" > </span> <br/>
			E-mail: <input type="text" name="regMail" onkeyup="checkMail()">
			<span class="registrationFormAlert" id="spanMail"> </span> <br/>
			Lozinka: <input type="password" name="regPassword"><br>
			Provjera lozinke: <input type="password" name="regConfirmPassword" onkeyup="checkPasswordMatch()"> 
			<span class="registrationFormAlert" id="spanPasswordMatch"> </span> <br/>
			<input type="submit" value="registracija">
		</form>
	</div>

	<div class="login">
		<h2> Prijava postojećeg korisnika: </h2>
		 <form name="form_login" method="POST" action="spomenar.php?action=login" >
	        Korisničko ime: <input type="text" name="logUsername"><br/>
	        Lozinka: <input type="password" name="logPassword"><br/>
	        <input type="submit" value="prijava">
	      </form>
	</div>

<?php include "footer.php"; ?>
