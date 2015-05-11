<?php 
$monDebut = 0;
$maFin=4;
$paginationPage = 1;
if(array_key_exists('nombreParPageDash', $_GET))
{
	$monDebut= intval(($_GET['nombreParPageDash']-1)*$maFin);
	$paginationPage = intval($_GET['nombreParPageDash']);
}

//Pour récupérer tous les articles, les auteurs et les categories
$sql="SELECT article.*, auteur.nom as nom_auteur, categories.nom as nom_categorie FROM article
INNER JOIN auteur ON article.id_auteur=auteur.id
INNER JOIN categories ON article.id_categorie=categories.id
order by `date_ajout` desc
LIMIT :start, $maFin";
$requete = $connexion->prepare($sql);
$requete->bindValue(':start', $monDebut, PDO::PARAM_INT);
$success = $requete->execute();
$allArticles=$requete->fetchAll(PDO::FETCH_ASSOC);
// var_dump($allArticles);

//Nombre d'article
$sql="SELECT count(`id`) FROM `article`";
$requete = $connexion->prepare($sql);
$success = $requete->execute();
$nbreArticle=$requete->fetch();
// var_dump($nbreArticle);

//User
$sql="SELECT * FROM utilisateur";
$requete = $connexion->prepare($sql);
$success = $requete->execute();
$user=$requete->fetch();

//Pour récupérer le nombre de message
$sql="SELECT count(`id`) as nbMessage FROM messages";
$requete = $connexion->prepare($sql);
$success = $requete->execute();
$nbreMessages=$requete->fetch();
// var_dump($nbreMessages);


//Fonction pour couper le texte de la description
function getLittleDescription($texte, $nbchar = 20)
{
return (strlen($texte) > $nbchar ? substr(substr($texte,0,$nbchar),0,strrpos(substr($texte,0,$nbchar)," "))."..." : $texte);
}