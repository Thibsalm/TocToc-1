<?php
	session_start();

	$bdd = new PDO('mysql:host=localhost;dbname=espace_membre', 'root', '');
	
	$erreur = array();
	
	if(isset($_POST['formconnexion']))
	{
		$pseudoconnexion = htmlspecialchars($_POST['pseudoconnexion']);
		$mdpconnexion = sha1($_POST['mdpconnexion']);
		
		if(!empty($pseudoconnexion) AND !empty($mdpconnexion))
		{
			$requser = $bdd->prepare("SELECT * FROM MEMBRE WHERE PSEUDO=? AND MOTDEPASSE=?");
			$requser->execute(array($pseudoconnexion, $mdpconnexion));
			
			if($requser->rowCount() > 0)
			{
				$userinfo = $requser->fetch();
				$_SESSION['id'] = $userinfo['id'];
				$_SESSION['pseudo'] = $userinfo['pseudo'];
				
				header("Location: profil.php?id=" . $_SESSION['id']);
			}
			else
			{
				array_push($erreur, "Mauvais pseudo ou mot de passe");
			}
		}
		else
		{
			array_push($erreur, "Tous les champs doivent être renseignés");
		}
	}
?>

<html>
	<head>
		<title>TUTO PHP</title>
		<meta charset="utf-8">
	</head>
	
	<body>
		<div align="center">
			<h2>Connexion</h2>
			<br/><br/>

			<form method="POST" action="">
				<input type="text" name="pseudoconnexion" placeholder="Pseudo"/>
				<input type="password" name="mdpconnexion" placeholder="Mot de passe"/>
				<input type="submit" name="formconnexion" value="Se connecter"/>
			</form>
			<?php
				foreach($erreur as $e)
				{
					echo $e . '</br>';
				}
			?>
		</div>

	</body>
</html>