<?php 
$monDebut = 0;
$maFin=3;
$paginationPage = 1;
if(array_key_exists('searchNombreParPage', $_GET))
{
	$monDebut= intval(($_GET['searchNombreParPage']-1)*$maFin);
	$paginationPage = intval($_GET['searchNombreParPage']);
}


//Pour récupérer tous les articles, les auteurs et les categories
$sql="SELECT article.*, auteur.nom, categories.nom as nom_categorie FROM article
INNER JOIN auteur ON article.id_auteur=auteur.id
INNER JOIN categories ON article.id_categorie=categories.id
WHERE article.description LIKE :mot 
OR article.titre LIKE :mot
OR auteur.nom LIKE :mot
order by `date_ajout` desc
LIMIT :start, $maFin";
$requete = $connexion->prepare($sql);
$requete->bindValue(':start', $monDebut, PDO::PARAM_INT);
$requete->bindValue(':mot', "%".$_GET['word']."%", PDO::PARAM_INT);
$success = $requete->execute();
$allArticles=$requete->fetchAll(PDO::FETCH_ASSOC);
// var_dump($allArticles);

//Pour récupérer tous les articles, les auteurs et les categories
$sql="SELECT article.*, auteur.nom, categories.nom as nom_categorie FROM article
INNER JOIN auteur ON article.id_auteur=auteur.id
INNER JOIN categories ON article.id_categorie=categories.id
WHERE article.description LIKE :mot 
OR article.titre LIKE :mot
OR auteur.nom LIKE :mot
order by `date_ajout` desc";
$requete = $connexion->prepare($sql);
$requete->bindValue(':start', $monDebut, PDO::PARAM_INT);
$requete->bindValue(':mot', "%".$_GET['word']."%", PDO::PARAM_INT);
$success = $requete->execute();
$allArticlesforAjax=$requete->fetchAll(PDO::FETCH_ASSOC);
// var_dump($allArticlesforAjax);

//On teste si l'Ajax est bien là (voir dans Network/clique sur le lien/Response)
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	$listesLi="";
	foreach ($allArticlesforAjax as $key => $value) {
		$listesLi=$listesLi.'<li><a href="'.ROOT.'article-'.$value["id"].'">'.$value["titre"].'</a></li>';
	}

	die(json_encode([
		"Message"=>"Ca marche !",
		"listes"=>$listesLi
		]));
}

//Nombre d'article
$sql="SELECT count(article.id) FROM `article`
INNER JOIN auteur ON article.id_auteur=auteur.id
INNER JOIN categories ON article.id_categorie=categories.id
WHERE article.description LIKE :mot 
OR article.titre LIKE :mot
OR auteur.nom LIKE :mot";
$requete = $connexion->prepare($sql);
$requete->bindValue(':mot', "%".$_GET['word']."%", PDO::PARAM_INT);
$success = $requete->execute();
$nbreArticle=$requete->fetchColumn();
// var_dump($nbreArticle);

//Fonction pour couper le texte de la description
function getLittleDescription($texte, $nbchar = 200)
{
	return (strlen($texte) > $nbchar ? substr(substr($texte,0,$nbchar),0,strrpos(substr($texte,0,$nbchar)," "))."..." : $texte);
}