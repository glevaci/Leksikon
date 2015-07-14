<html lang="hr">
<head>
	<meta charset="UTF-8">
 	<script src="js_functions.js"> </script>
 	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="jquery-ui-1.8.15.custom.min.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="/resources/demos/style.css">
	<link rel="stylesheet" href="style.css">
  	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
  	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  	<script>
  		$(function() {
    			$( "#datepicker" ).datepicker({ dateFormat: "dd.mm.yy.", changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            yearRange: "-100:+0" }).val();
  		});
  	</script>
</head>

<body onload="init()">