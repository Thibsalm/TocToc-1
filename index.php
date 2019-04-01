<?php
	session_start();
	$_SESSION['connected'] = false;

	// header("Location: insc.php");
?>

<html>
	<head>
		<title>Bienvenue sur TocToc !</title>
		<meta charset="utf-8">
	</head>
	
	<body>
		<a href="insc.php"><button>INSCRIPTION</button></a>
	</body>
	
</html>
