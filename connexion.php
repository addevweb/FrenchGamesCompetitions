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
<!DOCTYPE html>
<html>
<head>
	<title>Connexion</title>
	 <link rel="stylesheet" type="text/css" href="CCSconnexion.css">
	<meta charset="utf-8">
</head>
<body>
	<div align="center">	
	<h2>Connexion</h2>
	<br /><br /><br />
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