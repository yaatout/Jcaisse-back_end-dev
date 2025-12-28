<?php
/*
Résumé :
Commentaire : 
Version : 2.0
see also :
Auteur : Mor Mboup
Date de création : 10/08/2023
Date derni�re modification : 
*/
session_start();
if(!$_SESSION['iduser']){
  header('Location:../index.php');
  }

require('../connection.php');
require('../connectionPDO.php');

require('../declarationVariables.php');

// function cmp1($a, $b)
// {
//     return strcmp($b["designation"], $a["designation"]);
// }

// function cmp2($a, $b)
// {
//     return number_format($a["s_cli"]) - number_format($b["s_cli"]);
// }

$date = new DateTime();
$timezone = new DateTimeZone('Africa/Dakar');
$date->setTimezone($timezone);

$operation=@htmlspecialchars($_POST["operation"]);
$tri=@htmlspecialchars($_POST["tri"]);

if($operation == 1){

    $designations = [];
  
    $query=htmlspecialchars(trim($_POST['query']));
  
    
    $sqlGetDesignations = $bdd->prepare("SELECT * FROM `".$nomtableDesignation."` WHERE designation LIKE '%$query%' and classe=0 ORDER BY designation");
    $sqlGetDesignations->execute(array()) or
    die(print_r($sqlGetDesignations->errorInfo()));
    // $data = $sqlGetDesignations->fetchAll();
  
    while($d=$sqlGetDesignations->fetch()){
  
        $designations[] = $d['idDesignation']." -/- ".$d['designation'];
  
    }
  
    echo json_encode($designations);
  
  } elseif ($operation == 2) {
    # code...
    $result = 0;
    $idDesignation = htmlspecialchars(trim($_POST['idDesignation']));
    // $idDesignation = htmlspecialchars(trim($_POST['idDesignation']));
    
    $sqlGetDesignation = $bdd->prepare("SELECT * FROM `".$nomtableDesignation."` WHERE idDesignation=".$idDesignation);
    $sqlGetDesignation->execute(array()) or die(print_r($sqlGetDesignation->errorInfo()));
    $data = $sqlGetDesignation->fetch();

    if ($sqlGetDesignation && ($data[0] == $idDesignation)) {
        # code...
        $result='1<>'.$data['idDesignation'].'<>'.$data['designation'].'<>'.$data['categorie'].'<>'.$data['prix'];

    } else {
        # code...
        $result='2<>0';
    }
    
    // while($d=$sqlGetDesignation->fetch()){
  
    //     $designations[] = $d['idDesignation']." -/- ".$d['designation'];
  
    // }

    echo $result;

  } elseif ($operation == 3) {
        # code...
    
        $idDesignation=htmlspecialchars(trim($_POST['idDesignation']));  
        $quantite=htmlspecialchars(trim($_POST['quantite']));
        $idEntrepot=htmlspecialchars(trim($_POST['depotChoice']));

        $result="0";
        $quantiteInventaire=$quantite;

        $sql="SELECT SUM(quantiteStockCourant) FROM `".$nomtableEntrepotStock."` where idDesignation='".$idDesignation."' and idEntrepot='".$idEntrepot."'";
        $res=mysql_query($sql);
        $sommeStock=mysql_fetch_array($res);
                
        $quantiteStockCourantTotal=($sommeStock[0]) ? $sommeStock[0] : 0 ;
            
        $sql1="SELECT * FROM `". $nomtableEntrepotStock."` where idDesignation='".$idDesignation."' and idEntrepot='".$idEntrepot."' ORDER BY idEntrepotStock DESC LIMIT 1";
        $res1=mysql_query($sql1);
        $stock=mysql_fetch_array($res1);
        $idLastStock=$stock['idEntrepotStock'];
        
        $sql01s="SELECT * FROM `". $nomtableStock."` s, `". $nomtableEntrepotStock."` e where s.idStock=e.idStock and idEntrepotStock=".$idLastStock."";
        $resS01s=mysql_query($sql01s);
        // var_dump($sql01s);
        // die();
        $stock01s=mysql_fetch_array($resS01s);
        $idLastS=$stock01s['idStock'];
        
        $sql2="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$idDesignation."'";
        $res2=mysql_query($sql2);
        $design=mysql_fetch_array($res2);
        // var_dump($design);

        if ($quantiteStockCourantTotal > 0) {
            
            $totalArticleStock = $quantiteInventaire * $design['nbreArticleUniteStock'];
            // $totalArticleStock = $quantiteInventaire * $stock['nbreArticleUniteStock'];
            
            $sqlS0="UPDATE `".$nomtableStock."` set quantiteStockinitial=quantiteStockinitial+".$quantiteInventaire.", totalArticleStock=totalArticleStock+".$totalArticleStock." where idStock=".$idLastS;

            $resS0=mysql_query($sqlS0) or die ("update quantiteStockCourant impossible =>".mysql_error());

            $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=quantiteStockCourant+".$totalArticleStock.", quantiteStockinitial=quantiteStockinitial+".$quantiteInventaire.", totalArticleStock=totalArticleStock+".$totalArticleStock." where idEntrepotStock=".$idLastStock;

            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

            // $sqlSI="UPDATE `".$nomtableInventaire."` set quantiteStockCourant=quantiteStockCourant+".$totalArticleStock.", quantite=quantite+".$quantiteInventaire." where idEntrepotStock=".$idLastStock;

            // $resSI=mysql_query($sqlSI) or die ("update nomtableInventaire impossible =>".mysql_error());
            
            $result = "1";

        } else {

            $totalArticleStock = $quantiteInventaire * $design['nbreArticleUniteStock'];
            
            $sql01='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,prixuniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant) VALUES('.$idDesignation.',"'.mysql_real_escape_string($design['designation']).'",'.$quantiteInventaire.',"'.mysql_real_escape_string($design['uniteStock']).'",'.$design['prix'].','.$design['prixuniteStock'].','.$design['nbreArticleUniteStock'].','.$totalArticleStock.',"'.$dateString.'",0)';

            $res01=@mysql_query($sql01) or die ("insertion stock 2 impossible".mysql_error()) ;

            
            $sql01s="SELECT * FROM `". $nomtableStock."` where idDesignation='".$idDesignation."' ORDER BY idStock DESC LIMIT 1";
            $resS01s=mysql_query($sql01s);
            $stock01s=mysql_fetch_array($resS01s);
            $idLastS=$stock01s['idStock'];

            $sql1='INSERT INTO `'.$nomtableEntrepotStock.'` (idStock,idEntrepot,idDesignation,designation,quantiteStockinitial,uniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant)

            VALUES('.$idLastS.','.$idEntrepot.','.$idDesignation.',"'.mysql_real_escape_string($design["designation"]).'",'.$quantiteInventaire.',"'.mysql_real_escape_string($design['uniteStock']).'",'.$design['nbreArticleUniteStock'].','.$totalArticleStock.',"'.$dateString.'",'.$totalArticleStock.')';
            // var_dump($sql1);
            $res1=@mysql_query($sql1) or die ("insertion stock 12 impossible".mysql_error()) ;
            // var_dump($sql1);
            // $sql01Es="SELECT * FROM `". $nomtableEntrepotStock."` where idDesignation='".$idDesignation."' ORDER BY idStock DESC LIMIT 1";
            // $resS01Es=mysql_query($sql01Es);
            // $stock01Es=mysql_fetch_array($resS01Es);
            // $idLastES=$stock01Es['idEntrepotStock'];

            // $sql4='INSERT INTO `'.$nomtableInventaire.'` (idEntrepotStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)
            // VALUES('.$idLastES.','.$design['idDesignation'].','.$quantiteInventaire.','.$design['nbreArticleUniteStock'].','.$totalArticleStock.',"'.$dateString.'",0)';
            // $res4=mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;

    
            $result = "1";
    
        }

        exit($result);

  } elseif ($operation == 4) {
    # code...
     
    $designation=htmlspecialchars(trim($_POST['designation']));  
    $categorie=htmlspecialchars(trim($_POST['categorie']));  
    $quantiteInventaire=htmlspecialchars(trim($_POST['quantite']));
    $prix=htmlspecialchars(trim($_POST['prix']));
    $idEntrepot=htmlspecialchars(trim($_POST['depotChoice']));
    $uniteStock='piece';
    $nbreArticleUniteStock=1;

    $req4 = $bdd->prepare("INSERT INTO `".$nomtableDesignation."` (designation,classe,uniteStock,prix,prixuniteStock,nbreArticleUniteStock,prixachat,categorie)
    VALUES (:d,:cl,:uS,:p,:pS,:nA,:pA,:c)") ;
    $req4->execute(array(
        'd' => mysql_real_escape_string($designation),
        'cl' => 0,
        'uS' => $uniteStock,
        'p' => $prix,
        'pS' => $prix,
        'nA' => 1,
        'pA' => 0,
        'c' => $categorie
    ))  or die(print_r("Insert designation 1 ".$req4->errorInfo()));
    $req4->closeCursor();
    
    $idDesignation = $bdd->lastInsertId();

    $req5 = $bdd->prepare("INSERT INTO `".$nomtableStock."` (idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,prixuniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant)
    VALUES (:id,:d,:qsi,:uS,:p,:pS,:nA,:tA,:ds,:qsc)") ;
    $req5->execute(array(
        'id' => $idDesignation,
        'd' => mysql_real_escape_string($designation),
        'qsi' => $quantiteInventaire,
        'uS' => $uniteStock,
        'p' => $prix,
        'pS' => $prix,
        'nA' => 1,
        'tA' => $quantiteInventaire,
        'ds' => $dateString,
        'qsc' => 0
    ))  or die(print_r("Insert designation 1 ".$req5->errorInfo()));
    $req5->closeCursor();
    
    $idStock = $bdd->lastInsertId();         

    $sql1='INSERT INTO `'.$nomtableEntrepotStock.'` (idStock,idEntrepot,idDesignation,designation,quantiteStockinitial,uniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant)

    VALUES('.$idStock.','.$idEntrepot.','.$idDesignation.',"'.mysql_real_escape_string($designation).'",'.$quantiteInventaire.',"'.mysql_real_escape_string($uniteStock).'",1,'.$quantiteInventaire.',"'.$dateString.'",'.$quantiteInventaire.')';
    // var_dump($sql1);
    $res1=@mysql_query($sql1) or die ("insertion stock 12 impossible".mysql_error()) ;
    // var_dump($sql1);
    // $sql01Es="SELECT * FROM `". $nomtableEntrepotStock."` where idDesignation='".$idDesignation."' ORDER BY idStock DESC LIMIT 1";
    // $resS01Es=mysql_query($sql01Es);
    // $stock01Es=mysql_fetch_array($resS01Es);
    // $idLastES=$stock01Es['idEntrepotStock'];

    echo "1";
  }
//   elseif ($operation == 1) {
//     # code...
//   }

