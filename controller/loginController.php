<?php 
$erreurs=[];
if (!empty($_POST)){
	//On créé un tableau$erreurs pour récupérer les messages d'erreurs
	//On teste si les champs sont vides
	if (empty($_POST['login'])) {
		$erreurs['login']="Vous devez entrer un nom d'utilisateur.";
	}
	//On teste si les champs sont vides
	if (empty($_POST['password'])) {
		$erreurs['password']="Vous devez entrer un mot de passe.";
	}
	
	if (empty($erreurs)) {
		$login=($_POST['login']);
		$password=($_POST['password']);
		//On récupère les infos du formulaire
		$sql="SELECT * FROM `utilisateur` WHERE `login`=:admin and `password`=:pass";
		$requete = $connexion->prepare($sql);
		$requete->bindValue(':admin', $login, PDO::PARAM_INT);
		$requete->bindValue(':pass', sha1($password), PDO::PARAM_INT);
		//Utiliser le blindValue dès qu'on utilise une variable dans la requête
		$success = $requete->execute();
		$infoFormAdmin=$requete->fetch(PDO::FETCH_ASSOC);
		// var_dump($infoFormAdmin);

		if (empty($infoFormAdmin)) {
			$erreurs['empty']="Utilisateur inconnu.";
		}
		else{
			$_SESSION["utilisateur"]
				["login"]=$_POST['login'];
			$_SESSION["utilisateur"]
				["password"]=sha1($password);
				header("Location:index.php?page=dashboard");
				die();
		}
	}
}	
// var_dump($erreurs);
?>