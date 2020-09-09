<?php

$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');

if(isset($_POST['forminscription']))
{
		$pseudo = htmlspecialchars($_POST['pseudo']);
		$mail = htmlspecialchars($_POST['mail']);
		$mail2 = htmlspecialchars($_POST['mail2']);
		$mdp = sha1($_POST['mdp']);
		$mdp2 = sha1($_POST['mdp2']);

		if(!empty($_POST['pseudo']) AND !empty($_POST['mail']) AND !empty($_POST['mail2']) AND !empty($_POST['mdp']) AND !empty($_POST['mdp2']))
	{


		$pseudolength = strlen($pseudo);
		if($pseudolength <= 255)
		{
			if($mail == $mail2)
			{
				if(filter_var($mail, FILTER_VALIDATE_EMAIL))
				{

				$reqmail = $bdd->prepare("SELECT * FROM membres WHERE mail = ?");
				$reqmail->execute(array($mail));
				$mailexist = $reqmail->rowCount();
				if($mailexist == 0)
				{
					if($mdp == $mdp2)
					{
						$insertmbr = $bdd->prepare("INSERT INTO membres(pseudo, mail, motdepasse) VALUES(?, ?, ?)");
						$insertmbr->execute(array($pseudo, $mail, $mdp));
						$erreur = "Votre compte a bien été créé ! <a href=\"connexion.php\">Me connecter</a>";
					}
					else
					{
						$erreur = "Vos mots de passe ne sont pas identique !";
					}
				}
				else
				{
					$erreur = "Adresse mail déjà utilisée !";
				}
			}
			else
			{
				$erreur = "Votre adresse mail n'est pas valide !";
			}

			}
			else
			{
				$erreur = "Vos mail ne sont pas identique !";
			}
		}
		else
		{
			$erreur = "Votre Pseudo ne doit pas dépasser 255 caractères !";
		}
		}
		else
		{
			$erreur = "Tous les champs doivent être complétés !";
		}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="CSSconnexion.css">
</head>
<body>
	<div align="center">	
	<h2>Inscription</h2>
	<br /><br /><br />
	<form method="POST" action="">
		<table>
			<tr>
				<td align="right">
					<label for="pseudo" style="color: #fff">Pseudo :</label>
				</td>
				<td>				
					<input type="text" placeholder="Votre pseudo" id="pseudo" name="pseudo" value="<?php if(isset($pseudo)) { echo $pseudo; }?>" />
				</td>
			</tr>
			<tr>
				<td align="right">
					<label for="mail" style="color: #fff">Mail :</label>
				</td>
				<td>				
					<input type="email" placeholder="Votre mail" id="mail" name="mail" value="<?php if(isset($mail)) { echo $mail; }?>"/>
				</td>
			</tr>
			<tr>
				<td align="right">
					<label for="mail2" style="color: #fff">Mail de confirmation :</label>
				</td>
				<td>				
					<input type="email" placeholder="Confirmez votre mail " id="mail2" name="mail2" />
				</td>
			</tr>
			<tr>
				<td align="right">
					<label for="mdp" style="color: #fff">Mot de passe :</label>
				</td>
				<td>				
					<input type="password" placeholder="Votre mot de passe" id="mdp" name="mdp" />
				</td>
			</tr>
			<tr>
				<td align="right">
					<label for="mdp2" style="color: #fff">Confirmation du mot de passe :</label>
				</td>
				<td>				
					<input type="password" placeholder="Confirmez votre mdp" id="mdp2" name="mdp2" />
				</td>
			</tr>
			<tr>
				<td></td>
				<td align="center">
					<br />
					<input type="submit" name="forminscription" value="Je m'inscrit" />
				</td>
			</tr>
		</table>
	</form>
	<?php
	if (isset($erreur))
	{
		echo '<font color="red">'.$erreur."</font>";
	}
	?>
</body>
</html>

<?php
session_start();

$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');
if(isset($_POST['fromconnexion']))
{
	$mailconnect = htmlspecialchars($_POST['mailconnect']);
	$mdpconnect = sha1($_POST['mdpconnect']);
	if(!empty($mailconnect) AND !empty($mdpconnect))
	{
		$requser = $bdd->prepare("SELECT * FROM membres WHERE mail = ? AND motdepasse = ? ");
		$requser->execute(array($mailconnect, $mdpconnect));
		$userexist = $requser->rowCount();
		if($userexist == 1)
		{
			$userinfo = $requser->fetch();
			$_SESSION['id'] = $userinfo['id'];
			$SESSION['pseudo'] = $userinfo['pseudo'];
			$SESSION['mail'] = $userinfo['mail'];
			header("Location: profil.php?id=".$_SESSION['id']);
		}
		else
		{
			$erreur = "Mauvais mail ou mot de passe !";
		}
	}
	else
	{
		$erreur = "Tous les champs doivent être compétés !";
	}
}

?>
<br /><br />
<!DOCTYPE html>
<html>
<head>
	<title>Connexion</title>
	<meta charset="utf-8">
</head>
<body>
	<div align="center">	
	<h2>Connexion</h2>
	<br /><br />
	<form method="POST" action="">
		<input type="text" name="mailconnect" placeholder="Mail" />
		<input type="password" name="mdpconnect" placeholder="Mot de passe" />
		<input type="submit" name="fromconnexion" value="Se connecter !" />
	</form>
	<?php
	if (isset($erreur))
	{
		echo '<font color="red">'.$erreur."</font>";
	}
	?>
</body>
</html>