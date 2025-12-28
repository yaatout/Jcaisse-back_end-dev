<?php
/*
Résumé :
Commentaire :
Version : 2.0
see also :
Auteur : Ibrahima DIOP; ,EL hadji mamadou korka
Date de création : 20/03/2016
Date derni�re modification : 20/04/2016; 15-04-2018
*/
session_start();
if(!$_SESSION['iduser']){
  header('Location:../index.php');
  }

require('../connection.php');
require('../connectionPDO.php');

require('../declarationVariables.php');

$operation=@htmlspecialchars($_POST["operation"]);

function find_p_with_position($pns,$des) {
  foreach($pns as $index => $p) {
      if(($p['idDesignation'] == $des)){
          return $index;
      }
  } 
  return FALSE;
}


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

if ($operation=="1") {

  $idDesignation=@htmlspecialchars($_POST["idDesignation"]);
  $qty=@htmlspecialchars($_POST["qty"]);
  
  $sqlGetArticle = $bdd->prepare("SELECT * FROM `".$nomtableDesignation."` WHERE idDesignation = :idP");
  $sqlGetArticle->execute(array(
    'idP' => $idDesignation
  )) or  die(print_r($sqlGetArticle->errorInfo()));
  $a = $sqlGetArticle->fetch();
  
  if (isset($_SESSION['panier'])) {
    $panier=array_values($_SESSION['panier']);
  } else {
    $panier= array();
  }
  // var_dump(find_p_with_position($panier, $a['idDesignation']) !==FALSE);
  if (find_p_with_position($panier, $a['idDesignation']) !==FALSE) {
          
    $i=find_p_with_position($panier, $a['idDesignation']);
             
    $panier[$i]['quantite'] += $qty;  

  } else {

    $ligne = array();
    $ligne['idDesignation']=$a['idDesignation'];
    $ligne['designation']=$a['designation'];
    $ligne['quantite']=1;
    $ligne['prix']=$a['prix'];
    $ligne['unite']='article';
    $ligne['classe']=$a['classe'];

    array_unshift($panier, $ligne);
  }
      
  $_SESSION['panier'] = $panier;


  echo 1;
  exit();

} else if ($operation=="2") {

  $idDesignation=@htmlspecialchars($_POST["idDesignation"]);

  if (find_p_with_position($_SESSION['panier'], $idDesignation) !==FALSE) {
          
    $i=find_p_with_position($_SESSION['panier'], $idDesignation);

    $qtyDelete=$_SESSION['panier'][$i]['quantite'];
             
    unset($_SESSION['panier'][$i]);  

  }
  echo $qtyDelete;
  exit();
  
} else if ($operation=="3") {
  $panier = $_SESSION['panier'];
  $idCompte = ($_SESSION['compte']==1) ? $_POST['compte'] : 0 ;
  $totalPanier = 0;

  foreach ($panier as $p) {
    $totalPanier += $p['quantite'] * $p['prix'];
  }
  
  $req4 = $bdd->prepare("INSERT INTO
  `".$nomtablePagnet."` (`datepagej`, `type`, `heurePagnet`, `totalp`, `apayerPagnet`, `idCompte`, `verrouiller`, `iduser`)
  VALUES(:dp,:t,:hp, :tp, :ap, :idC, :v,:u)") ;
  $req4->execute(array(
  'dp' => $dateString2,
  't' => 0,
  'hp' => $heureString,
  'tp' =>$totalPanier,
  'ap' => $totalPanier,
  'idC' => $idCompte,
  'v' => 1,
  'u' => $_SESSION['iduser']
  ))  or die(print_r($req4->errorInfo()));
  $req4->closeCursor();

  $idPagnet = $bdd->lastInsertId();

  foreach ($panier as $ligne) {
    
    $req4 = $bdd->prepare("INSERT INTO
    `".$nomtableLigne."` (designation, idDesignation, unitevente, prixunitevente, quantite, prixtotal, idPagnet, classe)
    VALUES (:d,:idd,:uv,:pu,:qty,:pt,:idp,:c)") ;
    $req4->execute(array(
    'd' => $ligne['designation'],
    'idd' => $ligne['idDesignation'],
    'uv' =>$ligne['unite'],
    'pu' => $ligne['prix'],
    'qty' => $ligne['quantite'],
    'pt' => $ligne['quantite'] * $ligne['prix'],
    'idp' => $idPagnet,
    'c' => $ligne['classe']
    ))  or die(print_r($req4->errorInfo()));
    $req4->closeCursor();
  
    if($ligne['classe']==0){
      $sqlS="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];
      $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
      $designation = mysql_fetch_assoc($resS) ;
      if(mysql_num_rows($resS)){
        $sql_E="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' AND quantiteStockCourant!=0 ";
        $res_E=mysql_query($sql_E) or die ("select stock impossible =>".mysql_error());
        $t_stock = mysql_fetch_array($res_E) ;
        if (($ligne['unite']!="Article")&&($ligne['unite']!="article")) {
            $sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' AND quantiteStockCourant!=0 ORDER BY idStock ASC ";
            $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
            $restant=$ligne['quantite']*$designation['nbreArticleUniteStock'];
            // $qtyVendu=$ligne['quantite']*$designation['nbreArticleUniteStock'];
            while ($stock = mysql_fetch_assoc($resD)) {
                if($restant>= 0) {
                    $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;
                    if($quantiteStockCourant > 0){
                        $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];
                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
                    }
                    else {
                        $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=0 where idStock=".$stock['idStock'];
                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
                    }
                    $restant= $restant - $stock['quantiteStockCourant'] ;
                }
            }
            // $stock_V=$ligne['quantite']*$designation['nbreArticleUniteStock'];
            // if($t_stock[0]<$stock_V){
            //     if(is_numeric($t_stock[0])){
            //         $stock_T=$t_stock[0];
            //     }
            //     else{
            //         $stock_T=0;
            //     }
            //     $sql4='INSERT INTO `'.$nomtableInventaire.'` (idStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)
            //     VALUES(0,'.$ligne['idDesignation'].','.$stock_V.','.$designation['nbreArticleUniteStock'].','.$stock_T.',"'.$dateString.'",5)';
            //     $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;
            // }

        }
        else if(($ligne['unite']=="Article")||($ligne['unite']=="article")){
            $sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' AND quantiteStockCourant!=0 ORDER BY idStock ASC ";
            $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
            $restant=$ligne['quantite'];
            // $qtyVendu=$ligne['quantite'];
            while ($stock = mysql_fetch_assoc($resD)) {
                if($restant>= 0){
                    $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;
                    if($quantiteStockCourant > 0){
                        $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];
                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
                    }
                    else{
                        $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=0 where idStock=".$stock['idStock'];
                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
                    }
                    $restant= $restant - $stock['quantiteStockCourant'] ;
                }
            }
            // $stock_V=$ligne['quantite'];
            // if($t_stock[0]<$stock_V){
            //     if(is_numeric($t_stock[0])){
            //         $stock_T=$t_stock[0];
            //     }
            //     else{
            //         $stock_T=0;
            //     }
            //     $sql4='INSERT INTO `'.$nomtableInventaire.'` (idStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)
            //     VALUES(0,'.$ligne['idDesignation'].','.$stock_V.',1,'.$stock_T.',"'.$dateString.'",5)';
            //     $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;
            // }

        }
        /************************/
        
        // $sqlSV="UPDATE `".$nomtableStock."` set quantiteStockTemp=quantiteStockTemp-".$qtyVendu." where idDesignation=".$ligne['idDesignation'];
        // $resSV=mysql_query($sqlSV) or die ("update quantiteStockCourant impossible =>".mysql_error());
        /************************/
      }
    }
  }
  
  if ($_SESSION['compte'] == 1) {
    
    // if ($idCompte == '1000') {
    //   # code...
    //   $i = 0;
    //   $idComptes = [];
    //   $choiceArray = [];
    //   foreach ($_SESSION['cptChoiceArray'] as $choice) {
    //       # code... 
    //       foreach ($choice as $key) {      
    //           $items = explode('-', $key);
    //           $idPanier = $items[0];
    //           // $idCompte = $items[1];
    //           // $montant = $items[2];

    //           if ($idPagnet == $idPanier) {
    //               # code.
    //               $choiceArray = $_SESSION['cptChoiceArray'][$i];
    //               break 2;
    //           }
    //           //  else {
    //           //     var_dump(4);
    //           //     // $_SESSION['cptChoiceArray'][] = $choiceArray;
    //           //     $j++;
    //           // }
    //       }
    //       $i++;
    //   }
    //   // var_dump($choiceArray);
    //   $k=0;
    //   foreach ($choiceArray as $key) {

    //       $_items = explode('-', $key);
    //       $idPagnet = $_items[0];
    //       $_idCompte = $_items[1];
    //       $montant = $_items[2];
    //       $idComptes[$k] = $_idCompte;

    //       $sql7="UPDATE `".$nomtableCompte."` set montantCompte= montantCompte + ".$montant." where  idCompte=".$_idCompte;
    //       $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());

    //       $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,mouvementLink,idUser) values(".$montant.",'".$operation."',".$_idCompte.",'".$description."','".$dateHeures."','".$dateHeures."',".$idPagnet.",".$_SESSION['iduser'].")";
    //       $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error());
    //       $k++;
    //   }
      
    //   $idsCpteMultiple = implode('_',$idComptes);
    //   // var_dump($idComptes);
    //   // var_dump($idsCpteMultiple);

    //   $sql8="UPDATE `".$nomtablePagnet."`  set  idCompte=".$idCompte.", idsCpteMultiple='".$idsCpteMultiple."'  where  idPagnet=".$idPagnet;
    //   $res8=@mysql_query($sql8) or die ("mise à jour pour activer ou pas ".mysql_error());


    // } else {
      # code...
      
      $description='Encaissement vente';
      $operation='depot';
      $sql8="UPDATE `".$nomtablePagnet."`  set  idCompte=".$idCompte." where  idPagnet=".$idPagnet;
      $res8=@mysql_query($sql8) or die ("mise à jour pour activer ou pas ".mysql_error());

      $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte + ".$totalPanier." where  idCompte=".$idCompte;
      $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());

      $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,mouvementLink,idUser) values(".$totalPanier.",'".$operation."',".$idCompte.",'".$description."','".$dateHeures."','".$dateHeures."',".$idPagnet.",".$_SESSION['iduser'].")";
      $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error());
    // }
  }

  $_SESSION['panier'] = array();

  echo $idPagnet;
  exit();
}