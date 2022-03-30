<?php
include("config.inc.php");
session_start();

$date_debut = $_POST['db'];
$date_fin = $_POST['df'];


//$date_debut = "2021-05-02";
//$date_fin ="2022-05-02";

$diff = date_diff(date_create($date_fin),date_create($date_debut));
//print_r($diff->format('%R%a days'));

$old =  date('Y-m-d', strtotime($date_debut . '- '. $diff->format('%a days')));

$sql = "SELECT count(*) as old FROM (SELECT  inf.id, inf.cin_infracteur , inf.date, inf.type,inf.pv,comm.nom as nom_zone,c.nom as nom_conduite, ar.nom as nom_commune FROM `infraction` as inf , commune comm, conduite c,arrondissement ar where inf.id_zone = comm.id and comm.id_conduite = c.id and c.id_commune = ar.id) as tab ,infracteur where tab.cin_infracteur=infracteur.cin and tab.date between '$date_debut' and '$date_fin'";
$sql2 = "SELECT count(*) as new FROM (SELECT  inf.id, inf.cin_infracteur , inf.date, inf.type,inf.pv,comm.nom as nom_zone,c.nom as nom_conduite, ar.nom as nom_commune FROM `infraction` as inf , commune comm, conduite c,arrondissement ar where inf.id_zone = comm.id and comm.id_conduite = c.id and c.id_commune = ar.id) as tab ,infracteur where tab.cin_infracteur=infracteur.cin and tab.date between '$old' and '$date_debut'";
$resultat = $connect->prepare($sql);
$resultat->execute() or die("Erreur lors de l'execution de la requete: ".mysql_error());


$resultat2 = $connect->prepare($sql2);
$resultat2->execute() or die("Erreur lors de l'execution de la requete: ".mysql_error());

if($resultat->rowCount())
{

    $output = $resultat->fetchAll(PDO::FETCH_ASSOC);
    $output2 = $resultat2->fetchAll(PDO::FETCH_ASSOC);

    echo(json_encode([$output,$output2]));

}

?>
