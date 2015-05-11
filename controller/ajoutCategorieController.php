<?php 
testConnexion();
//var_dump($_POST);

$erreurs=[];
if (!empty($_POST)){
	//On créé un tableau$erreurs pour récupérer les messages d'erreurs
	//On teste si les champs sont vides
	if (empty($_POST['nom'])) {
		$erreurs['nom']="Vous devez entrer un nom.";
	}
	//On teste si les champs sont vides
	if (empty($_POST['description'])) {
		$erreurs['description']="Vous devez entrer une description.";
	}
	if (empty($erreurs)) {
		$nom=($_POST['nom']);
		$description=($_POST['description']);
		$sql="INSERT INTO categories (nom, description)
		VALUES(:nom, :description)";
		$requete = $connexion->prepare($sql);
		$requete->bindValue(':nom', $nom);
		$requete->bindValue(':description', $description);					
		$successAddCategorie = $requete->execute();			
	}
}
//var_dump($erreurs);