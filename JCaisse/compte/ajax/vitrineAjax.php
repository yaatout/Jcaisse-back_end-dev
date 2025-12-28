
<?php

session_start();
if(!$_SESSION['iduser']){
  header('Location:../index.php');
}

if($_SESSION['vitrine']==0){
	header('Location:accueil.php');
}

require('../connection.php');
require('../connectionVitrine.php');

require('../declarationVariables.php');

/**Debut informations sur la date d'Aujourdhui **/
    $date = new DateTime();
    $timezone = new DateTimeZone('Africa/Dakar');
    $date->setTimezone($timezone);
    $annee =$date->format('Y');
    $mois =$date->format('m');
    $jour =$date->format('d');
    $heureString=$date->format('H:i:s');
    $dateString=$annee.'-'.$mois.'-'.$jour;
    $dateString2=$jour.'-'.$mois.'-'.$annee;
/**Fin informations sur la date d'Aujourdhui **/
    

$nomtableDesignation= $_SESSION['boutV']."-designation";

    $operation=@htmlspecialchars($_POST["operation"]);
    $id=@htmlspecialchars($_POST["id"]);
    
    if($operation == 1){

        $req = $bddV->prepare("SELECT * FROM `".$nomtableDesignation."` WHERE id=:idV ");
        $req->execute(array('idV' => $id )) or die(print_r($req->errorInfo()));
        $design=$req->fetch();

        $result=$design['id'].'<>'.$design['designation'].'<>'.$design['categorie'].'<>'.$design['uniteStock'].'<>'.$design['prixuniteStock'].'<>'.$design['prix'].'<>'.$design['image'];
        exit($result);
     
    }
