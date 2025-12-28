
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
    
    $operation=@htmlspecialchars($_POST["operation"]);
    
    if($operation == 1){
        $id=@htmlspecialchars($_POST["id"]);

        $req = $bddV->prepare("SELECT * FROM `".$nomtableDesignation."` WHERE id=:idV ");
        $req->execute(array('idV' => $id )) or die(print_r($req->errorInfo()));
        $design=$req->fetch();

        $result=$design['id'].'<>'.$design['designationJcaisse'].'<>'.$design['designation'].'<>'.$design['categorie'].'<>'.$design['uniteStock'].'<>'.$design['prixuniteStock'].'<>'.$design['prix'].'<>'.$design['image'].'<>'.$design['idBoutique'];
        exit($result);
        
    }
    if($operation == 2){
        $idArticle=@htmlspecialchars($_POST["idArticle"]);
        $idPanier=@htmlspecialchars($_POST["idPanier"]);
        $qtRetourner=@htmlspecialchars($_POST["qtRetourner"]);
        $qtCommander=@htmlspecialchars($_POST["qtCommander"]);
        
        if($qtCommander === $qtRetourner){
            $req = $bddV->prepare("DELETE FROM `ligne` WHERE idArticle = :idA and idPanier = :idP ");
            $req->execute(array(
                'idP' => $idPanier,
                'idA' => $idArticle
             )) or die(print_r($req->errorInfo()));

            $req0 = $bddV->prepare("SELECT * FROM `ligne` WHERE idPanier=:idP ");
            $req0->execute(array('idP' => $idPanier )) or die(print_r($req0->errorInfo()));
            $lignes=$req0->fetchAll();

            if (empty($lignes)) {
                $delParent = $bddV->prepare("DELETE FROM `panier` WHERE idPanier = :idP ");
                $delParent->execute(array(
                    'idP' => $idPanier
                )) or die(print_r($delParent->errorInfo()));
            }
        }else{
            $req = $bddV->prepare("UPDATE `ligne` SET quantite = quantite - :qtR, prixTotal = prixTotal - prix * :qtR WHERE idArticle = :idA and idPanier = :idP ");
            $req->execute(array(
                'qtR' => $qtRetourner,
                'idP' => $idPanier,
                'idA' => $idArticle
            )) or die(print_r($req->errorInfo()));

            $req01 = $bddV->prepare("SELECT * FROM `ligne` WHERE idArticle=:idA LIMIT 0,1 ");
            $req01->execute(array('idA' => $idArticle )) or die(print_r($req01->errorInfo()));
            $ligne=$req01->fetch();
            $prixLigne = $ligne['prix'];
            
            $req2 = $bddV->prepare("UPDATE `panier` SET total = total -  :pl * :qtR WHERE idPanier = :idP ");
            $req2->execute(array(
                'qtR' => $qtRetourner,
                'pl' => $prixLigne,
                'idP' => $idPanier
            )) or die(print_r($req2->errorInfo()));
        }
         
         exit("OK");
        
    }
