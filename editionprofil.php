<?php
session_start();

$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');

if(isset($_SESSION['id']))
{
	$requser = $bdd->prepare("SELECT * FROM membres WHERE id = ?");
	$requser->execute(array($_SESSION['id']));
	$user = $requser->fetch();

	if(isset($_POST['newpseudo']) AND !empty($_POST['newpseudo']) AND $_POST['newpseudo'] != $user['pseudo'])
	{
		$newpseudo = htmlspecialchars(($_POST['newpseudo']));
		$insertpseudo = $bdd->prepare("UPDATE membres SET pseudo = ? WHERE id = ?");
		$insertpseudo->execute(array($newpseudo, $_SESSION['id']));
		header('Location: profil.php?id='.$_SESSION['id']);
	}

	if(isset($_POST['newmail']) AND !empty($_POST['newmail']) AND $_POST['newmail'] != $user['mail'])
	{
		$newmail = htmlspecialchars(($_POST['newmail']));
		$insertmail = $bdd->prepare("UPDATE membres SET mail = ? WHERE id = ?");
		$insertmail->execute(array($newmail, $_SESSION['id']));
		header('Location: profil.php?id='.$_SESSION['id']);
	}

	if(isset($_POST['newmdp1']) AND !empty($_POST['newmdp1']) AND isset($_POST['newmdp2']) AND !empty($_POST['newmdp2']))
	{
		$mdp1 = sha1($_POST['newmdp1']);
		$mdp2 = sha1($_POST['newmdp2']);
		
		if($mdp1 == $mdp2)
		{
			$insertmdp = $bdd->prepare("UPDATE membres SET motdepasse = ? WHERE id = ?");
			$insertmdp->execute(uxarray($mdp1, $_SESSION['id']));
			header('Location: profil.php?id='.$_SESSION['id']);
		}
		else
		{
			$msg = "Vos de mots de passe ne correspondent pas !";
		}

	}	

		if(isset($_POST['newpseudo']) AND $_POST['newpseudo'] == $user['pseudo'])
		{
			header('Location: profil.php?id='.$_SESSION['id']);	
		}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Profil</title>
	<meta charset="utf-8">
</head>
<body>
	<link rel="stylesheet" type="text/css" href="CSSconnexion.css">
	<div align="center">	
	<h2>Edition de mon profil</h2>
	<div align="center">
	<form method="POST" action="">
		<label style="color: #fff">Pseudo: </label>
		<input type="text" name="newpseudo" placeholder="Pseudo" value="<?php echo $user['pseudo']; ?>" /><br /><br />
		<label style="color: #fff">Mail: </label>
		<input type="text" name="newmail" placeholder="Mail" value="<?php echo $user['mail']; ?>"/><br /><br />
		<label style="color: #fff">Mot de passe: </label>
		<input type="password" name="newmdp1" placeholder="Mot de passe" /><br /><br />
		<label style="color: #fff">Confirmation du mot de passe: </label>
		<input type="password" name="newmdp2" placeholder="Confirmez votre mdp" /><br /><br />
		<input type="submit" value="Mettre a jour mon profil !" />
	</form>
	<?php if(isset($msg)) { echo $msg; }?>
	</div>
	</div>
</body>
</html>
<?php
}
else
{
	header("Location: connexion.php");
}
?>