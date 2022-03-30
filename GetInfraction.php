<?php
include("config.inc.php");
session_start();

$date_debut = $_POST['db'];
$date_fin = $_POST['df'];



//$date_debut = "2021-05-02";
//$date_fin ="2022-05-02";


$sql = "SELECT * FROM (SELECT  inf.id, inf.cin_infracteur , inf.date, inf.type,inf.pv,comm.nom as nom_zone,c.nom as nom_conduite, ar.nom as nom_commune FROM `infraction` as inf , commune comm, conduite c,arrondissement ar where inf.id_zone = comm.id and comm.id_conduite = c.id and c.id_commune = ar.id) as tab ,infracteur where tab.cin_infracteur=infracteur.cin and tab.date between '$date_debut' and '$date_fin'";
$resultat = $connect->prepare($sql);
$resultat->execute() or die("Erreur lors de l'execution de la requete: ".mysql_error());

	if($resultat->rowCount())
	{
		$output = $resultat->fetchAll(PDO::FETCH_ASSOC);
		echo(json_encode($output));
	
	}

 ?>
