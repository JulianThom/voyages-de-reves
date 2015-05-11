<?php 
testConnexion();
//var_dump($_POST);

$erreurs=[];
if (!empty($_POST)){
	//On créé un tableau$erreurs pour récupérer les messages d'erreurs
	//On teste si les champs sont vides
	if (empty($_POST['prenom'])) {
		$erreurs['prenom']="Vous devez entrer un prénom.";
	}
	if (empty($_POST['nom'])) {
		$erreurs['nom']="Vous devez entrer un nom.";
	}	
	//On teste si les champs sont vides
	if (empty($_POST['message'])) {
		$erreurs['message']="Vous devez écrire un message.";
	}
	if (empty($erreurs)) {
		$prenom=($_POST['prenom']);
		$nom=($_POST['nom']);		
		$message=($_POST['message']);
		$sql="INSERT INTO messages (prenom, nom, message, date_message)
		VALUES(:prenom, :nom, :message, :date_message)";
		$requete = $connexion->prepare($sql);
		$requete->bindValue(':prenom', $prenom);		
		$requete->bindValue(':nom', $nom);
		$requete->bindValue(':message', $message);
		$requete->bindValue(':date_message', date('Y-m-d H:i:s'));								
		$successAddMessage = $requete->execute();			
	}
}
//var_dump($erreurs);