<?php

	$bdd = new PDO('mysql:host=localhost;dbname=espace_membre', 'root', '');
	
	if(isset($_POST['forminscription']))
	{
		$pseudo = htmlspecialchars($_POST['pseudo']);
		$mail = htmlspecialchars($_POST['mail']);
		
		if(!empty($_POST['pseudo']) AND !empty($_POST['mail']) AND !empty($_POST['mdp']))
		{
			$mdp = sha1($_POST['mdp']);
			
			if(strlen($pseudo) > 255)
			{
				$erreur = "Votre pseudo ne peut dépasser les 255 caractères !";
			}
			else
			{
				if(!filter_var($mail, FILTER_VALIDATE_EMAIL))
				{
					$erreur = "Le mail n'est pas valide";
				}
				else
				{
					$reqmail = $bdd->prepare("SELECT * FROM MEMBRE WHERE MAIL=?");
					$reqmail->execture(array($mail));
					$reqmail->rowCount();
					
					if($reqmail->rowCount() > 0)
					{
						$erreur = "Cette adresse mail est déjà utilisée";
					}
					else
					{
						$insertmember = $bdd->prepare("INSERT INTO MEMBRE(pseudo, mail, motdepasse) VALUES(?,?,?)");
						$insertmember->execute(array($pseudo, $mail, $mdp));
						$erreur = "Votre compte a bien été créé";
						header('Location: index.php');
					}
				}
			}
		}
		else
		{
			$erreur = "Tous les champs doivent être renseignés !";
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
			<h2>Inscription</h2>
			<br/><br/>

			<form method="POST" action="">
				<table>
					<tr>
						<td>
							<label for="pseudo">Pseudo :</label>
						</td>
						<td>
							<input type="text" placeholder="Votre pseudo" id="pseudo" name="pseudo"
							value="<?php if(isset($pseudo)) { echo $pseudo; } ?>"/>
						</td>
					<tr>
					<tr>
						<td>
							<label for="mail">Mail :</label>
						</td>
						<td>
							<input type="text" placeholder="Votre mail" id="mail" name="mail"
							value="<?php if(isset($mail)) { echo $mail; } ?>"/>
						</td>
					</tr>
					<tr>
						<td>
							<label for="mdp">Mot de passe :</label>
						</td>
						<td>
							<input type="password" placeholder="Votre mot de passe" id="mdp" name="mdp"/>
						</td>
					</tr>
					<tr>
						<td></td>
						<td align="center">
							<br/>
							<input type="submit" value="Je m'inscris" name="forminscription"/>
						</td>
					</tr>
				</table>
			</form>
			<?php 
				if(isset($erreur))
				{
					echo $erreur;
				}
			?>
		
		</div>

	</body>
</html>