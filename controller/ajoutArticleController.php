<?php 
testConnexion();
//var_dump($_POST);
// var_dump(getimagesize($_FILES['image']['tmp_name']));
// var_dump($_FILES);

$sql="SELECT * FROM categories
order by nom asc";
$requete = $connexion->prepare($sql);
$success = $requete->execute();
$recupCategorie=$requete->fetchAll(PDO::FETCH_ASSOC);
//var_dump($recupCategorie);

$sql="SELECT * FROM auteur
order by nom asc";
$requete = $connexion->prepare($sql);
$success = $requete->execute();
$recupAuteur=$requete->fetchAll(PDO::FETCH_ASSOC);
//var_dump($recupAuteur);

$erreurs=[];
if (!empty($_POST)){
	//On créé un tableau$erreurs pour récupérer les messages d'erreurs
	//On teste si les champs sont vides
	if (empty($_POST['titre'])) {
		$erreurs['titre']="Vous devez entrer un titre.";
	}
	//On teste si les champs sont vides
	if (empty($_POST['description'])) {
		$erreurs['description']="Vous devez entrer une description.";
	}
	//On teste si les champs sont vides
	if (empty($_POST['date'])) {
		$erreurs['date']="Vous devez entrer une date.";
	}
	//On teste si les champs sont vides
	if (empty($_POST['auteur'])) {
		$erreurs['auteur']="Vous devez sélectionner un auteur.";
	}
	//On teste si les champs sont vides
	if (empty($_FILES['image']) || $_FILES['image']['error'] != 0) {
		$erreurs['image']="Vous devez choisir une image.";
	}
	else
	{
		//On teste si la personne upload à la bonne taille
		$tailleImage = getimagesize($_FILES['image']['tmp_name']);
		if ($tailleImage[0] != 900 && $tailleImage[1] != 300) {
			$erreurs['image']="Votre image doit faire 900x300 px.";
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
			//On teste si les champs sont vides
		if (empty($_POST['categories'])) {
			$erreurs['categories']="Vous devez sélectionner une categorie.";
		}		
	if (empty($erreurs)) {
		//On change l'extension de l'image
		$extensionImage=str_replace("image/", "", $_FILES['image']['type']);
		//On renomme l'image avec un ID unique créé aléatoirement + l'extension de l'image
		$renameImage=uniqid().".".$extensionImage;
		//On déplace l'image dans le bon dossier
		$successMoveImage=move_uploaded_file($_FILES['image']['tmp_name'], "vue/images/".$renameImage);
		$titre=($_POST['titre']);
		$description=($_POST['description']);
		$date=($_POST['date']);
		$auteur=($_POST['auteur']);
		$image=($_FILES['image']['name']);
		$categories=($_POST['categories']);
		//Si l'image se déplace bien, on lance la requête SQL
		if ($successMoveImage) {
		$sql="INSERT INTO article (titre, description, date_ajout, id_auteur, image, id_categorie)
		VALUES(:titre, :description, :date_ajout, :id_auteur, :image, :id_categorie)";
		$requete = $connexion->prepare($sql);
		$requete->bindValue(':titre', $titre);
		$requete->bindValue(':description', $description);	
		$requete->bindValue(':date_ajout', $date);			
		$requete->bindValue(':id_auteur', intVal($auteur), PDO::PARAM_INT);
		$requete->bindValue(':image',$renameImage);
		$requete->bindValue(':id_categorie',intVal($categories), PDO::PARAM_INT);			
		$successAddArticle = $requete->execute();
		}
		else{
			$erreurs['image']="Erreur lors du téléchargement de l'image.";
		}				
	}
}
//var_dump($erreurs);