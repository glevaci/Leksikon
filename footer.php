
	<div class="prev">
	<?php if (isset($_SESSION["broj_pitanja"]) && $_SESSION["broj_pitanja"] > 0 && $_SESSION["broj_pitanja"] <  totalNumberOfQuestions()) { ?>
		<form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="post">
			<input type="submit"  name='prev' value= "prethodno pitanje" >
		</form>
	</div>
		<?php } ?>
	<div class="next">
		<?php if (isset($_SESSION["broj_pitanja"]) && $_SESSION["broj_pitanja"] <  totalNumberOfQuestions()) { ?>
		<form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" 
				id="<?php if ($_SESSION['broj_pitanja']==0) echo 'slika';?>" method="post">
			<input type="submit" name='next' value= "sljedeće pitanje"  <?php echo isAnswered();?> >
		</form>
	</div>
	<?php } ?>
</div>
</div>

</body>
</html>	