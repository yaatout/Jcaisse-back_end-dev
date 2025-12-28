<?php

session_start();

if(!$_SESSION['iduser']){ 

  header('Location:../index.php');

}

require('../connectionOffline.php');

require('../declarationVariables.php');

$idPanier= $_POST['idPanier'];
$identifiantP=$_POST['identifiantP'];
//$datepagej= $_POST['datepagej'];
$type= $_POST['type'];
$heurePagnet= $_POST['heurePagnet'];
$totalp= $_POST['totalp'];
$remise= $_POST['remise'];
$apayerPagnet= $_POST['apayerPagnet'];
$restourne= $_POST['restourne'];
$versement= $_POST['versement'];
$verrouiller= $_POST['verrouiller'];
$idClient= $_POST['idClient'];
//$idVitrine= $_POST['idVitrine'];
//$iduser= $_POST['iduser'];
$idCompte= $_POST['idCompte'];
$idCompteAvance= $_POST['idCompteAvance'];
$avance= $_POST['avance'];
//$apayerPagnetTvaP= $_POST['apayerPagnetTvaP'];
//$apayerPagnetTvaR= $_POST['apayerPagnetTvaR'];
$dejaTerminer= $_POST['dejaTerminer'];
$synchronise= $_POST['synchronise'];
//$details= json_decode($_POST['details'], true);
//$lignes= json_decode($_POST['lignes'], true);
//var_dump($details);
 
try {
  //$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ATTR_ERRMODE_EXCEPTION);
  $pdo->beginTransaction();


    $data = [
        ':idPanier' => $idPanier,
        ':identifiantP' => $identifiantP,
        //':datepagej' => $datepagej,
        ':type' => $type,
        ':heurePagnet' => $heurePagnet,
        ':totalp' => $totalp,  
        ':remise' => $remise,
        ':apayerPagnet' => $apayerPagnet,
        ':restourne' => $restourne,
        ':versement' => $versement,
        ':verrouiller' => $verrouiller,
        ':idClient' => $idClient,
        // ':idVitrine' => $idVitrine,
        //':iduser' => $iduser,
        ':idCompte' => $idCompte,
        ':idCompteAvance' => $idCompteAvance,
        ':avance' => $avance, 
        // ':apayerPagnetTvaP' => $apayerPagnetTvaP,
        // ':apayerPagnetTvaR' => $apayerPagnetTvaR,                                            
        ':dejaTerminer' => $dejaTerminer,
        ':synchronise' => $synchronise,
    ];

    $sql = "update `".$nomtablePagnet."` set identifiantP=:identifiantP, heurePagnet=:heurePagnet, type=:type, totalp=:totalp, remise=:remise, apayerPagnet=:apayerPagnet, restourne=:restourne, versement=:versement, verrouiller=:verrouiller, idClient=:idClient, idCompte=:idCompte,idCompteAvance=:idCompteAvance, avance=:avance, dejaTerminer=:dejaTerminer, synchronise=:synchronise where idPagnet=:idPanier";

    $stmt= $pdo->prepare($sql);

    $stmt->execute($data); 

    $sqlT = "SELECT * FROM `".$nomtablePagnet."` WHERE idPagnet=$idPanier";
    //var_dump($sql7);
    $resT= $pdo->prepare($sqlT);

    $resT->execute();

    $panier=$resT->fetch(PDO::FETCH_ASSOC);

    $idP=$panier['identifiantP'];

    if($idP){
        $result="1";
    }else{
        $result="0";
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
