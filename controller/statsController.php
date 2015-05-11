<?php 
//On appel la fonction pour savoir si on est connecté
testConnexion();

//Nombre d'article
$sql="SELECT count(`id`) FROM `article`";
$requete = $connexion->prepare($sql);
$success = $requete->execute();
$nbreArticle=$requete->fetch();
// var_dump($nbreArticle);

//Nombre de categorie
$sql="SELECT count(`id`) FROM `categories`";
$requete = $connexion->prepare($sql);
$success = $requete->execute();
$nbreCategories=$requete->fetch();
// var_dump($nbreCategories);

// Meilleur article
$sql="SELECT article.titre, AVG(`note`), `id_article` FROM `commentaire` 
INNER JOIN article ON commentaire.id_article = article.id
group by `id_article`
order by AVG(`note`) desc
Limit 1";
$requete = $connexion->prepare($sql);
$success = $requete->execute();
$bestArticle=$requete->fetch();
// var_dump($bestArticle);

//Meilleure catégorie
$sql="SELECT article.id_categorie, categories.nom, AVG(`note`) FROM `commentaire`
INNER JOIN article ON commentaire.id_article = article.id
INNER JOIN categories ON article.id_categorie=categories.id
group by article.id_categorie
order by AVG(`note`) desc
LIMIT 1";
$requete = $connexion->prepare($sql);
$success = $requete->execute();
$bestCategorie=$requete->fetch();
// var_dump($bestCategorie);