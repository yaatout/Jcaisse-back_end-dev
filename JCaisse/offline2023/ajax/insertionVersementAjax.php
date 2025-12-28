<?php

session_start();

if(!$_SESSION['iduser']){ 

  header('Location:../index.php');

}

require('../connectionOffline.php');

require('../declarationVariables.php');

//$idPanier= $_POST['idPanier'];
$identifiantV=$_POST['identifiantV'];
$dateVersement= $_POST['dateVersement'];
$heureVersement= $_POST['heureVersement'];
$montant= $_POST['montant'];
//$apayerPagnet= $_POST['apayerPagnet'];

//$verrouiller= $_POST['verrouiller'];
$idClient= $_POST['idClient'];

$iduser= $_POST['iduser'];
$idCompte= $_POST['idCompte'];

$synchronise= $_POST['synchronise'];
//$details= json_decode($_POST['details'], true);
//$lignes= json_decode($_POST['lignes'], true);
//$nbreLignes= $_POST['nbreLignes'];
//var_dump($details);
 
try {
  //$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ATTR_ERRMODE_EXCEPTION);
  $pdo->beginTransaction();

    $sqlIdP = "SELECT * FROM `".$nomtableVersement."` WHERE identifiantV=$identifiantV";
    $residP= $pdo->prepare($sqlIdP);

    $residP->execute();
    $searchPanier=$residP->fetch(PDO::FETCH_ASSOC);

    //print_r($searchPanier);
    

    if($searchPanier){
        $result="0"; 
        //exit($result);
    }
    else{

        $data = [
            ':identifiantV' => $identifiantV,
            ':dateVersement' => $dateVersement,
            //':type' => $type,
            ':heureVersement' => $heureVersement,
            ':iduser' => $iduser, 
            ':montant' => $montant,     
                 
            //':apayerPagnet' => $apayerPagnet,            
            //':verrouiller' => $verrouiller,
            ':idClient' => $idClient,           
            
            ':idCompte' => $idCompte,           
            ':synchronise' => $synchronise,
        ];
    
        $sql = "insert into `".$nomtableVersement."` (identifiantV,dateVersement,heureVersement,iduser,montant,idClient,idCompte,synchronise)
        values (:identifiantV,:dateVersement,:heureVersement,:iduser,:montant,:idClient,:idCompte,:synchronise)";

        $stmt= $pdo->prepare($sql);
        $stmt->execute($data); 

        //$sql7 = "SELECT * FROM `".$nomtableVersement."`  order by idPagnet desc limit 1";
        $sql7 = "SELECT * FROM `".$nomtableVersement."` WHERE identifiantV=$identifiantV";
        //var_dump($sql7);
        $res= $pdo->prepare($sql7);

        $res->execute();
        $versement=$res->fetch(PDO::FETCH_ASSOC); 

        $result=$versement['idVersement'];    
    }

    $pdo->commit();
    exit($result);
    

  
}catch (PDOException $e){
  
  $pdo->rollback();
  exit('Erreur!!! '.$e->getMessage()) ;
  //var_dump($e->getMessage());
  throw $e;
}
 
                                    
?>
