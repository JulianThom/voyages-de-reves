<?php 
//Pour récupérer tous les articles, les auteurs et les categories
$sql="SELECT * FROM categories
order by nom asc";
$requete = $connexion->prepare($sql);
$success = $requete->execute();
$allCategories=$requete->fetchAll(PDO::FETCH_ASSOC);
// var_dump($allCategories);

//Fonction pour couper le texte de la description
function getLittleDescription($texte, $nbchar = 50)
{
return (strlen($texte) > $nbchar ? substr(substr($texte,0,$nbchar),0,strrpos(substr($texte,0,$nbchar)," "))."..." : $texte);
}