<?php include "header.php"; ?>
<link rel="stylesheet" type="text/css" href="login.css">

<body>
	<div class="main">
		<div class="uvod">
			<h1> Leksikon </h1>
			<p> Želimo ti dobrodošlicu u naš mali leksikon!	Ostani i ispuni ga do kraja. </p>
		</div>
		<!--
		<div class="fb-login-button" data-max-rows="1" data-size="large" data-show-faces="false" data-auto-logout-link="false"></div>
		<div id="status"></div>
		<div class="facebook">
			<div> <a href="fbconfig.php"> Prijava preko Facebooka  </a> </div>
		</div>-->

		<div class="Registracija">
			<h2> Registracija za nove korisnike: </h2>

			<form name="form_register" method="post" action="spomenar.php?action=register">
			
				Korisničko ime: <br> <input type="text" name="regUsername" onkeyup="checkUsername()"> 
				<span class="registrationFormAlert" id="spanUsername" > </span> <br/>
				E-mail: <br> <input type="text" name="regMail" onkeyup="checkMail()">
				<span class="registrationFormAlert" id="spanMail"> </span> <br/>
				Lozinka: <br> <input type="password" name="regPassword"><br>
				Provjera lozinke: <br> <input type="password" name="regConfirmPassword" onkeyup="checkPasswordMatch()"> 
				<span class="registrationFormAlert" id="spanPasswordMatch"> </span> <br/>
				<br>
				<input type="submit" value="registracija">

			</form>
		</div>

		<div class="registracija">
			<h2> Prijava postojećeg korisnika: </h2>
			 <form name="form_login" method="POST" action="spomenar.php?action=login" >
		        Korisničko ime: <br> <input type="text" name="logUsername"><br/>
		        Lozinka: <br> <input type="password" name="logPassword"><br/> 
		        <br>
		        <input type="submit" value="prijava">
		      </form>
		</div>
	</div>

</body>
</html>