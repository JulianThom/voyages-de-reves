<?php 
if (array_key_exists("id", $_GET)==false) {
	header("location:index.php");
	die();
}

$sql="SELECT * FROM `article` WHERE `id_categorie`=:protect";
$requete = $connexion->prepare($sql);
$requete->bindValue(':protect', intVal($_GET['id']), PDO::PARAM_INT);
$success = $requete->execute();
$allArticles=$requete->fetchAll(PDO::FETCH_ASSOC);
// var_dump($allArticles);

//Fonction pour couper le texte de la description
function getLittleDescription($texte, $nbchar = 50)
{
return (strlen($texte) > $nbchar ? substr(substr($texte,0,$nbchar),0,strrpos(substr($texte,0,$nbchar)," "))."..." : $texte);
}