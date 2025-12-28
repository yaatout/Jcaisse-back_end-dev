<?php

session_start();

if(!$_SESSION['iduser']){

header('Location:../index.php');

}

require('../connection.php');

require('../connectionPDO.php');

require('../declarationVariables.php');

try{

    $operation=@htmlspecialchars($_POST["operation"]);
    $idFournisseur=@$_POST["idFournisseur"];
    $nom=@$_POST["nom"];
    $adresse=@$_POST["adresse"];
    $telephone=@$_POST["telephone"];
    $banque=@$_POST["banque"];
    $numBanque=@$_POST["numBanque"];

    $dateDebut = @$_POST['dateDebut'];
    $dateFin = @$_POST['dateFin'];


}
catch(PDOException $e){
    echo "Erreur : " . $e->getMessage();
}



?>