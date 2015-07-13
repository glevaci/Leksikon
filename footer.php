
	<div class="prev">
	<?php if (isset($_SESSION["broj_pitanja"]) && $_SESSION["broj_pitanja"] > 0) { ?>
		<form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="post">
			<input type="submit"  name='prev' value= "prethodno pitanje" >
		</form>
	</div>
		<?php } ?>
	<div class="next">
		<form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" 
				id="<?php if ($_SESSION['broj_pitanja']==0) echo 'slika';?>" method="post">
			<input type="submit" name='next' value= "sljedeće pitanje"  <?php echo isAnswered();?> >
		</form>
	</div>
</div>
</div>

</body>
</html>	