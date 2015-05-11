<?php 
//On test la connexion
testConnexion();
// var_dump($_POST);

//On test si l'id est bien présent dans l'URL
if (array_key_exists("id", $_GET)==false) {
	header("location:index.php");
	die();
}

//On récupère l'article voulu grâce à son ID
$sql="SELECT * FROM `article` WHERE `id`=:protect";
$requete = $connexion->prepare($sql);
$requete->bindValue(':protect', intVal($_GET['id']), PDO::PARAM_INT);
$success = $requete->execute();
$articleModif=$requete->fetch(PDO::FETCH_ASSOC);
//var_dump($articleModif);

$sql="SELECT * FROM categories
order by nom asc";
$requete = $connexion->prepare($sql);
$success = $requete->execute();
$recupCategorie=$requete->fetchAll(PDO::FETCH_ASSOC);
// var_dump($recupCategorie);

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
	if (!empty($_FILES['image']) && $_FILES['image']['error'] == 0) {

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
		$titre=($_POST['titre']);
		$description=($_POST['description']);
		$date=($_POST['date']);
		$auteur=($_POST['auteur']);
		$renameImage=($articleModif['image']);
		$categories=($_POST['categories']);

		if (!empty($_FILES['image']) && $_FILES['image']['error'] == 0) {	
			//On change l'extension de l'image
			$extensionImage=str_replace("image/", "", $_FILES['image']['type']);
			//On renomme l'image avec un ID unique créé aléatoirement + l'extension de l'image
			$renameImage=uniqid().".".$extensionImage;
			//On déplace l'image dans le bon dossier
			$successMoveImage=move_uploaded_file($_FILES['image']['tmp_name'], "vue/images/".$renameImage);
			if($successMoveImage == false)
			{
				$erreurs['image']="Erreur lors du téléchargement de l'image.";
			}
		}

		if (empty($erreurs)) {
		
			
			//Si l'image se déplace bien, on lance la requête SQL
			//if ($successMoveImage) {
				$sql='UPDATE article
				SET titre=:titre, description=:description, date_ajout=:date_ajout, id_auteur=:id_auteur, image=:image, id_categorie=:id_categorie
				WHERE article.id=:id';
				$requete = $connexion->prepare($sql);
				$requete->bindValue(':titre', $titre, PDO::PARAM_STR);
				$requete->bindValue(':description', $description, PDO::PARAM_STR);	
				$requete->bindValue(':date_ajout', $date, PDO::PARAM_STR);			
				$requete->bindValue(':id_auteur', intVal($auteur), PDO::PARAM_INT);
				$requete->bindValue(':image',$renameImage, PDO::PARAM_STR);
				$requete->bindValue(':id_categorie',intVal($categories), PDO::PARAM_INT);	
				$requete->bindValue(':id',intVal($_GET['id']), PDO::PARAM_INT);		
				$successUpdateArticle = $requete->execute();

				if ($successUpdateArticle)
				{
					if ($renameImage != $articleModif['image'])
					{
						if( file_exists ("vue/images/".$articleModif['image']))
						{
     						unlink( "vue/images/".$articleModif['image'] ) ;
						}
					}
				// Récupération des nouvelles informations de l'article pour afficher l'article modifié
					$sql="SELECT * FROM `article` WHERE `id`=:protect";
					$requete = $connexion->prepare($sql);
					$requete->bindValue(':protect', intVal($_GET['id']), PDO::PARAM_INT);
					$success = $requete->execute();
					$articleModif=$requete->fetch(PDO::FETCH_ASSOC);	
				}
		}
	}
}	