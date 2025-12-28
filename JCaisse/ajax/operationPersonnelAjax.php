<?php

session_start();
if(!$_SESSION['iduser']){ 
  header('Location:../index.php');
  }

require('../connection.php');

require('../connectionPDO.php');

require('../declarationVariables.php'); 

$beforeTime = '00:00:00';
$afterTime = '06:00:00';

    // var_dump(date('d-m-Y',strtotime("-1 days")));

if($_SESSION['Pays']=='Canada'){  
	$date = new DateTime();
	$timezone = new DateTimeZone('Canada/Eastern');
}
else{
	$date = new DateTime();
	$timezone = new DateTimeZone('Africa/Dakar');
}
$date->setTimezone($timezone);
$heureString=$date->format('H:i:s');

if ($heureString >= $beforeTime && $heureString < $afterTime) {
   	// var_dump ('is between');
	$date = new DateTime (date('d-m-Y',strtotime("-1 days")));
}

// $date->setTimezone($timezone);
$annee =$date->format('Y');
$mois =$date->format('m');
$jour =$date->format('d');
$dateString=$annee.'-'.$mois.'-'.$jour;
$dateString2=$jour.'-'.$mois.'-'.$annee;
$dateHeures=$dateString.' '.$heureString;

$msg_info='';


if(isset($_POST['personnelAuto'])){

    // $idEnreg=htmlspecialchars(trim($_POST['idEnreg']));
    $_SESSION['idBoutique']=119; 

    $sqlU = "SELECT u.idutilisateur, u.prenom, u.nom FROM `aaa-utilisateur` u,  `aaa-acces` a where u.idutilisateur=a.idutilisateur and idBoutique=".$_SESSION['idBoutique'];

    $resU=$bdd->query($sqlU);
    $_users=$resU->fetchAll();
    $users=[];

    foreach ($_users as $key) {
        $users[]=$key['idutilisateur']." . ".$key['prenom']." ".$key['nom'];
    }

    echo json_encode($users);
  
}