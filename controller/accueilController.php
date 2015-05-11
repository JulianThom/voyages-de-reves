<?php 
$monDebut = 0;
$maFin=3;
$paginationPage = 1;
if(array_key_exists('nombreParPage', $_GET))
{
	$monDebut= intval(($_GET['nombreParPage']-1)*$maFin);
	$paginationPage = intval($_GET['nombreParPage']);
}

//Pour récupérer tous les articles, les auteurs et les categories
$sql="SELECT article.*, AVG(commentaire.note) as noteMoyenne, auteur.nom, categories.nom as nom_categorie, count(commentaire.note) as nbCom FROM article
INNER JOIN auteur ON article.id_auteur=auteur.id
INNER JOIN categories ON article.id_categorie=categories.id
LEFT JOIN commentaire ON article.id=commentaire.id_article
group by article.id
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


//Fonction pour couper le texte de la description
function getLittleDescription($texte, $nbchar = 200)
{
return (strlen($texte) > $nbchar ? substr(substr($texte,0,$nbchar),0,strrpos(substr($texte,0,$nbchar)," "))."..." : $texte);
}