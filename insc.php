<?php
	session_start();

	$bdd = new PDO('mysql:host=localhost;dbname=espace_membre', 'root', '');
	
	$erreur = array();

	if(isset($_POST['forminscription']))
	{
		$pseudo = htmlspecialchars($_POST['pseudo']);
		$mail = htmlspecialchars($_POST['mail']);
		$mail2 = htmlspecialchars($_POST['mail2']);
		
		$can_continue = true;

		if(empty($_POST['pseudo']) OR empty($_POST['mail']) OR empty($_POST['mdp']))
		{
			array_push($erreur, "Tous les champs doivent être renseignés !");
			$can_continue = false;
		}
		
		if(strlen($pseudo) > 255)
		{
			array_push($erreur, "Votre pseudo ne peut dépasser les 255 caractères !");
			$can_continue = false;
		}
		
		if(!filter_var($mail, FILTER_VALIDATE_EMAIL))
		{
			array_push($erreur, "Le mail n'est pas valide");
			$can_continue = false;
		}
		else if($mail !== $mail2)
		{
			array_push($erreur, "Le mail de confirmation ne correspond pas");
			$can_continue = false;
		}
		
		if($_POST['mdp'] !== $_POST['mdp2'])
		{
			array_push($erreur, "Le mot de passe de confirmation ne correspond pas");
			$can_continue = false;
		}
		else if(!preg_match('/[A-Z]/', $_POST['mdp']) OR !preg_match('/[0-9]/', $_POST['mdp']))
		{
			array_push($erreur, "Le mot de passe doit contenir au moins une majuscule et un chiffre");
			$can_continue = false;
		}	

		if($can_continue == true)
		{
			$reqmail = $bdd->prepare("SELECT * FROM MEMBRES WHERE MAIL=?");
			$reqmail->execute(array($mail));
			$reqmail->rowCount();
			
			$reqpseudo = $bdd->prepare("SELECT * FROM MEMBRES WHERE PSEUDO=?");
			$reqpseudo->execute(array($pseudo));
			$reqpseudo->rowCount();
					
			if($reqmail->rowCount() > 0 || $reqpseudo->rowCount() > 0)
			{
				array_push($erreur, "Pseudo ou adresse mail déjà utilisé");
			}
			else
			{
				$mdp = sha1($_POST['mdp']);
				
				$insertmember = $bdd->prepare("INSERT INTO MEMBRES(pseudo, mail, motdepasse) VALUES(?,?,?)");
				$insertmember->execute(array($pseudo, $mail, $mdp));
				
				array_push($erreur, "Votre compte a bien été créé");
				header('Location: index.php');
			}
		}
	}
?>

<html>
	<head>
		<title>TUTO PHP</title>
		<meta charset="utf-8">
	</head>
	
	<body>
		<script src="insc.js"></script>
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
							<label for="mail">Confirmation mail :</label>
						</td>
						<td>
							<input type="text" placeholder="Votre mail" id="mail2" name="mail2"/>
						</td>
					</tr>
					<tr>
						<td>
							<label for="mdp">Mot de passe :</label>
						</td>
						<td>
							<input type="password" placeholder="Votre mot de passe" id="mdp" name="mdp" 
							onchange="myFunction(this.value)"/>
							<script>
							function myFunction(val) {
								if(val == "hello") {
									document.getElementById("mdp").style.color = 'red';
							  // alert("The input value has changed. The new value is: " + val);
							}
							</script>
						</td>
					</tr>
					<tr>
						<td>
							<label for="mail">Confirmation mot de passe :</label>
						</td>
						<td>
							<input type="password" placeholder="Votre mot de passe" id="mdp2" name="mdp2"/>
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
				foreach($erreur as $e)
				{
					echo $e . '</br>';
				}
			?>
		</div>

	</body>
</html>