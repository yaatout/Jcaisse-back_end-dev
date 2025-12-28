
<?php
session_start();
if(!$_SESSION['iduserBack'])
	header('Location:index.php');


require('../connection.php');
require('../connectionVitrine.php');


require('../declarationVariables.php');


$nomtableDesignation= $_SESSION['boutV']."-designation";
$idBoutique=$_SESSION['idBoutV'];

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

    if($operation == 1){

      $sql="SELECT * from `".$nomtableDesignation."` where idDesignation='".$idDesignation."' ";
      $res=mysql_query($sql);
      $design=mysql_fetch_array($res);

        $req = $bddV->prepare("INSERT INTO
              `".$nomtableDesignation."`(designation, categorie, idBoutique, idDesignation, uniteStock,prix, prixuniteStock, 
                codeBarreDesignation, codeBarreuniteStock, idFusion)
              VALUES(:designation,:categorie,:idBoutique, :idDesignation, :uniteStock, :prix, :prixuniteStock,
               :codeBarreDesignation, :codeBarreuniteStock, :idFusion)") ;
              //var_dump($req);
              $req->execute(array(
                            'designation' => $design['designation'],
                            'categorie' => $design['categorie'],
                            'idBoutique' => $_SESSION['idBoutV'],
                            'idDesignation' => $idDesignation,
                            'uniteStock' => $design['uniteStock'],
                            'prix' => $design['prix'],
                            'prixuniteStock' => $design['prixuniteStock'],
                            'codeBarreDesignation' => $design['codeBarreDesignation'],
                            'codeBarreuniteStock' => $design['codeBarreuniteStock'],
                            'idFusion' => $design['idFusion']
              ))  or die(print_r($req->errorInfo()));
              $req->closeCursor();


       $result=$design['designation'].'+'.$design['categorie'].'+'.$design['uniteStock'].'+'.$design['prixuniteStock'].'+'.$design['prix'];

       exit($result);

    }
    if($operation == 2){ 

       $forme=@htmlspecialchars($_POST["forme"]);
       $tableau=@htmlspecialchars($_POST["tableau"]);
       $prixPublic=@htmlspecialchars($_POST["prixPublic"]);

        $req = $bddV->prepare("INSERT INTO
              `".$nomtableDesignation."`(designation, categorie, idBoutique, idDesignation, forme,tableau, prixPublic,
               codeBarreDesignation,codeBarreuniteStock,idFusion)
              VALUES(:designation,:categorie,:idBoutique, :idDesignation, :forme, :tableau, :prixPublic,
               :codeBarreDesignation, :codeBarreuniteStock, :idFusion)") ;
              //var_dump($req);
              $req->execute(array(
                            'designation' => $designation,
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
