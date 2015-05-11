<?php 
$monDebut = 0;
$maFin=4;
$paginationPage = 1;
if(array_key_exists('nombreParPageMessageDashboard', $_GET))
{
	$monDebut= intval(($_GET['nombreParPageMessageDashboard']-1)*$maFin);
	$paginationPage = intval($_GET['nombreParPageMessageDashboard']);
}

//Pour récupérer tous les messages
$sql="SELECT * FROM messages
order by date_message desc
LIMIT :start, $maFin";
$requete = $connexion->prepare($sql);
$requete->bindValue(':start', $monDebut, PDO::PARAM_INT);
$success = $requete->execute();
$allMessages=$requete->fetchAll(PDO::FETCH_ASSOC);
// var_dump($allMessages);

//Pour récupérer le nombre de message
$sql="SELECT count(`id`) as nbMessage FROM messages";
$requete = $connexion->prepare($sql);
$requete->bindValue(':start', $monDebut, PDO::PARAM_INT);
$success = $requete->execute();
$nbreMessages=$requete->fetch();

//Fonction pour couper le texte de la description
function getLittleDescription($texte, $nbchar = 20)
{
return (strlen($texte) > $nbchar ? substr(substr($texte,0,$nbchar),0,strrpos(substr($texte,0,$nbchar)," "))."..." : $texte);
}