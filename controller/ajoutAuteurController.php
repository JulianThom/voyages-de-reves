<?php 
testConnexion();
//var_dump($_POST);
// var_dump(getimagesize($_FILES['image']['tmp_name']));
// var_dump($_FILES);

$sql="SELECT * FROM auteur
order by nom asc";
$requete = $connexion->prepare($sql);
$success = $requete->execute();
$recupAuteur=$requete->fetchAll(PDO::FETCH_ASSOC);
// var_dump($recupAuteur);

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
	//On teste si les champs sont vides
	if (empty($_FILES['image']) || $_FILES['image']['error'] != 0) {
		$erreurs['image']="Vous devez choisir une image.";
	}
	else
	{
		//On teste si la personne upload à la bonne taille
		$tailleImage = getimagesize($_FILES['image']['tmp_name']);
		if ($tailleImage[0] > 1000 && $tailleImage[1] > 1000) {
			$erreurs['image']="Votre image doit faire moins de 1000 px.";
		}
		if ($tailleImage[0] != $tailleImage[1]) {
			$erreurs['image']="Votre image doit être carré.";
		}
		//On teste si la personne upload au bon format
		if ($_FILES['image']['type'] != "image/jpg" && $_FILES['image']['type'] != "image/jpeg" && $_FILES['image']['type'] != "image/png") {
			$erreurs['image']="Votre image doit être au format JPG ou PNG.";
		}	
		//On teste si la personne upload à la bonne taille		
		if ($_FILES['image']['size'] > 2000000) {
			$erreurs['image']="Votre image doit faire moins de 2mo.";
		}			

		}		
	if (empty($erreurs)) {
		//On change l'extension de l'image
		$extensionImage=str_replace("image/", "", $_FILES['image']['type']);
		//On renomme l'image avec un ID unique créé aléatoirement + l'extension de l'image
		$renameImage=uniqid().".".$extensionImage;
		//On déplace l'image dans le bon dossier
		$successMoveImage=move_uploaded_file($_FILES['image']['tmp_name'], "vue/images/".$renameImage);
		$nom=($_POST['nom']);
		$description=($_POST['description']);
		$image=($_FILES['image']['name']);
		//Si l'image se déplace bien, on lance la requête SQL
		if ($successMoveImage) {
		$sql="INSERT INTO auteur (nom, description, image)
		VALUES(:nom, :description, :image)";
		$requete = $connexion->prepare($sql);
		$requete->bindValue(':nom', $nom);
		$requete->bindValue(':description', $description);			
		$requete->bindValue(':image',$renameImage);			
		$successAddAuteur = $requete->execute();
		}
		else{
			$erreurs['image']="Erreur lors du téléchargement de l'image.";
		}				
	}
}
//var_dump($erreurs);