
<?php
session_start();
if(!$_SESSION['iduser']){
  header('Location:../index.php');
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
    $idDesignation=@htmlspecialchars($_POST["idDesignation"]);
    $uniteStock=@htmlspecialchars($_POST["uniteStock"]);

    $codeBarreDesignation=@htmlspecialchars($_POST["codeBarreDesignation"]);
    $codeBarreuniteStock=@htmlspecialchars($_POST["codeBarreuniteStock"]);
    $idFusion=@htmlspecialchars($_POST["idFusion"]);


    $prixuniteStock=@htmlspecialchars($_POST["prixuniteStock"]);
    $prix=@htmlspecialchars($_POST["prix"]);

    $categorie=@htmlspecialchars($_POST["categorie"]);
    $designation=@htmlspecialchars($_POST["designation"]);
    $designation=@htmlentities($designation);
    $designationJcaisse=@htmlspecialchars($_POST["designationJC"]);
    $designationJcaisse=@htmlentities($designationJcaisse);



    if($operation == 1){

        $req = $bddV->prepare("INSERT INTO
              `".$nomtableDesignation."`(designation, designationJcaisse, categorie, idBoutique, idDesignation, uniteStock,prix, prixuniteStock, 
                codeBarreDesignation, codeBarreuniteStock, idFusion)
              VALUES(:designation,:designationJcaisse,:categorie,:idBoutique, :idDesignation, :uniteStock, :prix, :prixuniteStock,
               :codeBarreDesignation, :codeBarreuniteStock, :idFusion)") ;
              //var_dump($req);
              $req->execute(array(
                            'designation' => $designation,
                            'designationJcaisse' => $designationJcaisse,
                            'categorie' => $categorie,
                            'idBoutique' => $_SESSION['idBoutique'],
                            'idDesignation' => $idDesignation,
                            'uniteStock' => $uniteStock,
                            'prix' => $prix,
                            'prixuniteStock' => $prixuniteStock,
                            'codeBarreDesignation' => $codeBarreDesignation,
                            'codeBarreuniteStock' => $codeBarreuniteStock,
                            'idFusion' => $idFusion
              ))  or die(print_r($req->errorInfo()));
              $req->closeCursor();


       $result=$designation.'+'.$categorie.'+'.$uniteStock.'+'.$prix.'+'.$prixuniteStock;

       exit($result);


    }
    if($operation == 2){ 

       $forme=@htmlspecialchars($_POST["forme"]);
       $tableau=@htmlspecialchars($_POST["tableau"]);
       $prixPublic=@htmlspecialchars($_POST["prixPublic"]);

        $req = $bddV->prepare("INSERT INTO
              `".$nomtableDesignation."`(designation, designationJcaisse, categorie, idBoutique, idDesignation, forme,tableau, prixPublic,
               codeBarreDesignation,codeBarreuniteStock,idFusion)
              VALUES(:designation,:designationJcaisse,:categorie,:idBoutique, :idDesignation, :forme, :tableau, :prixPublic,
               :codeBarreDesignation, :codeBarreuniteStock, :idFusion)") ;
              //var_dump($req);
              $req->execute(array(
                            'designation' => $designation,
                            'designationJcaisse' => $designationJcaisse,
                            'categorie' => $categorie,
                            'idBoutique' => $_SESSION['idBoutique'],
                            'idDesignation' => $idDesignation,
                            'forme' => $forme,
                            'tableau' => $tableau,
                            'prixPublic' => $prixPublic,
                            'codeBarreDesignation' => $codeBarreDesignation,
                            'codeBarreuniteStock' => $codeBarreuniteStock,
                            'idFusion' => $idFusion
              ))  or die(print_r($req->errorInfo()));
              $req->closeCursor();


       $result=$designation.'+'.$categorie.'+'.$forme.'+'.$tableau.'+'.$prixPublic;

       exit($result);

    }
