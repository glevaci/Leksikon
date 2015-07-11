<?php if (isset($_SESSION["broj_pitanja"]) && $_SESSION["broj_pitanja"] > 0) { ?>
	<form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="post">
		<input type="submit"  name='prev' value= "prethodno pitanje" >
	</form>
	<?php } ?>
	<form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="post">
		<input type="submit"  name='next' value= "sljedeće pitanje"  <?php echo isAnswered();?> >
	</form>

</body>
</html>	