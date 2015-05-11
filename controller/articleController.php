<?php 
// var_dump($_GET);

if (array_key_exists("id", $_GET)==false) {
	header("location:index.php");
	die();
}

$sql="SELECT article.*, auteur.nom as auteurName, categories.nom as catName FROM article 
INNER JOIN auteur ON article.id_auteur=auteur.id
INNER JOIN categories ON article.id_categorie = categories.id
where article.id=:protect";
$requete = $connexion->prepare($sql);
$requete->bindValue(':protect', intVal($_GET['id']), PDO::PARAM_INT);
//Utiliser le blindValue dès qu'on utilise une variable dans la requête
$success = $requete->execute();
$articleChoisi=$requete->fetch(PDO::FETCH_ASSOC);
//var_dump($articleChoisi);

if (empty($articleChoisi)) {
	header("location:index.php");
}

//Formulaire commentaires
// var_dump($_POST);

$erreurs=[];
if (!empty($_POST)){
	//On créé un tableau$erreurs pour récupérer les messages d'erreurs
	//On teste si le champ nom est vide
	if (empty($_POST['nom'])) {
		$erreurs['nom']="Vous devez entrer un nom.";
	}
	//On teste si le champ note est vide
	if (empty($_POST['note'])) {
		$erreurs['note']="Vous devez entrer une note.";
	}
	//On teste si le champ note a une valeur entre 1 et 5	
	if ($_POST['note']<0 || $_POST['note']>5) {
		$erreurs['note']="Votre note doit être comprise entre 1 et 10.";
	}
	//On teste si le champ message est vide
	if (empty($_POST['message'])) {
		$erreurs['message']="Votre message est vide";
	}
	if (empty($erreurs)) {
		$nom=($_POST['nom']);
		$note=($_POST['note']);
		$message=($_POST['message']);
		$sql="INSERT INTO commentaire (user, note, contenu, id_article, date_commentaire)
		VALUES(:nom, :note, :message, :id_article, :date_commentaire)";
		//on utilise une fonction prepare pour affecter les variables protégées des vraies valeurs
		$requete = $connexion->prepare($sql);
		$requete->bindValue(':nom', $nom); // permet de faire le lien entre :firstname et la valeur réelle contenue dans le $_POST
		$requete->bindValue(':note', intVal($note), PDO::PARAM_INT);
		$requete->bindValue(':message',$message);
		$requete->bindValue(':id_article',intVal($_GET['id']), PDO::PARAM_INT);	
		$requete->bindValue(':date_commentaire', date('Y-m-d H:i:s'));		
		$successAddCommentaire = $requete->execute(); // execute réellement la requête. $success vaut true ou false
		/* AJAX check  */
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			die(json_encode([
				"message"=>"Votre commentaire a bien été ajouté.",
				"commentaire"=>'<div class="media"><a class="pull-left" href="#"></a><div class="media-body"><h4 class="media-heading">'.$_POST["nom"].'<small> a posté le '.date('d-m-Y \à\ H:i:s', strtotime(date('Y-m-d H:i:s'))).'</small></h4><div class="ratyCommentaire" data-number="'. $_POST["note"].'"></div>'.$_POST["message"].'</div></div>'
				]));
		}
	}
}
// var_dump($erreurs);
// var_dump($_GET);

//Récupération des commentaires
$sql="SELECT * FROM `commentaire` where `id_article`=:protect
order by `date_commentaire` desc";
$requete = $connexion->prepare($sql);
$requete->bindValue(':protect', intVal($_GET['id']), PDO::PARAM_INT);
$success = $requete->execute();
$allCommentaires=$requete->fetchAll(PDO::FETCH_ASSOC);
// var_dump($allCommentaires);