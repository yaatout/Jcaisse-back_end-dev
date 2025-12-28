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

require('../declarationVariables.php');

$date = new DateTime();
$timezone = new DateTimeZone('Africa/Dakar');
$date->setTimezone($timezone);

$annee =$date->format('Y');
$mois =$date->format('m');
$jour =$date->format('d');
$heureString=$date->format('H:i:s');
$dateString=$annee.'-'.$mois.'-'.$jour ;
/*
$sql="select * from `".$nomtableStock."` where quantiteStockCourant!=0 order by idStock DESC";
$res=mysql_query($sql);
*/

$idEntrepot=@$_GET['id'];

$sql="SELECT * FROM `".$nomtableEntrepotStock."` s
LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation
WHERE s.idEntrepot='".$idEntrepot."' AND d.classe=0 ORDER BY s.idEntrepotStock DESC";
$res=mysql_query($sql);

$data=array();
$produits=array();
$i=1;
while($stock=mysql_fetch_array($res)){

  $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
  $res1=mysql_query($sql1);
  $designation=mysql_fetch_array($res1);

  $sqlS="SELECT SUM(quantiteStockCourant)
  FROM `".$nomtableEntrepotStock."`
  where idDesignation ='".$stock['idDesignation']."' AND  idEntrepot ='".$idEntrepot."' ";
  $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
  $S_stock = mysql_fetch_array($resS);

  $date1 = strtotime($dateString); 
  $date2 = strtotime($stock['dateStockage']); 

  $sqlI="SELECT SUM(quantiteStockinitial)
  FROM `".$nomtableStock."`
  where idDesignation ='".$stock['idDesignation']."'  ";
  $resI=mysql_query($sqlI) or die ("select stock impossible =>".mysql_error());
  $I_stock = mysql_fetch_array($resI);

  $sqlE="SELECT SUM(quantiteStockinitial)
  FROM `".$nomtableEntrepotStock."`
  where idDesignation ='".$stock['idDesignation']."'  ";
  $resE=mysql_query($sqlE) or die ("select stock impossible =>".mysql_error());
  $E_stock = mysql_fetch_array($resE);

  if(in_array($designation['idDesignation'], $produits)){
    // echo "Existe.";
   }
   else{

  $rows = array();
  if($i==1){
    $rows[] = $i;
    $rows[] = '<span style="color:blue;">'.strtoupper($designation['designation']).'</span>';
    $rows[] = '<span style="color:blue;">'.strtoupper($designation['categorie']).'</span>';
    if($designation['nbreArticleUniteStock']!=0 || $designation['nbreArticleUniteStock']!=null){
      if($S_stock[0]!=0 && $S_stock[0]!=null){
        $rows[] = '<span style="color:blue;">'.($S_stock[0] / $designation['nbreArticleUniteStock']) .'</span>';
      }
      else{
        $rows[] = '<span style="color:blue;">'.$S_stock[0].'</span>';
      }
    }
    else{
      $rows[] = '<span style="color:blue;">'.$S_stock[0].'</span>';
    }
   // $rows[] = '<span style="color:blue;">'.($I_stock[0] - $E_stock[0]).'</span>';
    $rows[] = '<span style="color:blue;">'.strtoupper($designation['uniteStock']).'</span>';
    $rows[] = '<span style="color:blue;">'.$designation['prixuniteStock'].'</span>';
   // $rows[] = '<span style="color:blue;">'.$S_stock[0] .'</span>';
   // $rows[] = '<span style="color:blue;">'.$designation['uniteDetails'].'</span>';
    $rows[] = '<span style="color:blue;">'.$designation['prixachat'].'</span>';
  }	
  else if($date1==$date2){
    $rows[] = $i;
    $rows[] = '<span style="color:#ffcc00;">'.strtoupper($designation['designation']).'</span>';
    $rows[] = '<span style="color:#ffcc00;">'.strtoupper($designation['categorie']).'</span>';
    if($designation['nbreArticleUniteStock']!=0 || $designation['nbreArticleUniteStock']!=null){
      $rows[] = '<span style="color:#ffcc00;">'.($S_stock[0] / $designation['nbreArticleUniteStock']) .'</span>';
    }
    else{
      $rows[] = '<span style="color:#ffcc00;">'.$S_stock[0].'</span>';
    }
   // $rows[] = '<span style="color:#ffcc00;">'.($I_stock[0] - $E_stock[0]).'</span>';
    $rows[] = '<span style="color:#ffcc00;">'.strtoupper($designation['uniteStock']).'</span>';
    $rows[] = '<span style="color:#ffcc00;">'.$designation['prixuniteStock'].'</span>';
   // $rows[] = '<span style="color:#ffcc00;">'.$S_stock[0] .'</span>';
   // $rows[] = '<span style="color:#ffcc00;">'.$designation['uniteDetails'].'</span>';
    $rows[] = '<span style="color:#ffcc00;">'.$designation['prixachat'].'</span>';
  }	
  else{
    $rows[] = $i;
    $rows[] = strtoupper($designation['designation']);
    $rows[] = strtoupper($designation['categorie']);
    if($designation['nbreArticleUniteStock']!=0 || $designation['nbreArticleUniteStock']!=null){
      if($S_stock[0]!=0 && $S_stock[0]!=null){
        $rows[] = ($S_stock[0] / $designation['nbreArticleUniteStock']);
      }
      else{
        $rows[] = $S_stock[0] ;
      }
    }
    else{
      $rows[] = $S_stock[0] ;
    }
  //  $rows[] = ($I_stock[0] - $E_stock[0]);
    $rows[] = strtoupper($designation['uniteStock']);
    $rows[] = $designation['prixuniteStock'];
  //  $rows[] = $S_stock[0];
  //  $rows[] = $designation['uniteDetails'];
    $rows[] = $designation['prixachat']; 
  }	

  $sqlI="SELECT SUM(quantiteStockinitial)
  FROM `".$nomtableStock."`
  where idDesignation ='".$stock['idDesignation']."'  ";
  $resI=mysql_query($sqlI) or die ("select stock impossible =>".mysql_error());
  $I_stock = mysql_fetch_array($resI);

  $sqlE="SELECT SUM(quantiteStockinitial)
  FROM `".$nomtableEntrepotStock."`
  where idDesignation ='".$stock['idDesignation']."'  ";
  $resE=mysql_query($sqlE) or die ("select stock impossible =>".mysql_error());
  $E_stock = mysql_fetch_array($resE);

  if($I_stock[0]==$E_stock[0]){
    $rows[] = '<a href="stock-Entrepot.php?iDS='.$stock["idDesignation"].'"><span style="color:green;">Details</span></a>';
  }
  else{
    $rows[] = '<a href="stock-Entrepot.php?iDS='.$stock["idDesignation"].'"><span style="color:#ffcc00;">Details</span></a>';
  }

/*
  if($designation['nbreArticleUniteStock']!=0 || $designation['nbreArticleUniteStock']!=null){
    if($stock['quantiteStockinitial']==($S_stock[0]/$designation['nbreArticleUniteStock'])){
      if($_SESSION['proprietaire']==1){ 
        $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Stock_ET('.$stock["idDesignation"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$stock["idStock"].'" /></a>&nbsp;
      <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Stock_ET('.$stock["idDesignation"].','.$i.')"  data-toggle="modal" data-target="#imgsup'.$stock["idStock"].'" /></a>&nbsp;
      <a href="stockEntrepot.php?iDS='.$stock["idDesignation"].'">Details</a>';
      }
      else{
        if($dateString==$stock['dateStockage'] && $_SESSION['caisse']==1){
          $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Stock_ET('.$stock["idDesignation"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$stock["idStock"].'" /></a>&nbsp;
          <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Stock_ET('.$stock["idDesignation"].','.$i.')"  data-toggle="modal" data-target="#imgsup'.$stock["idStock"].'" /></a>&nbsp;
          <a href="stockEntrepot.php?iDS='.$stock["idDesignation"].'">Details</a>';
        }
        else{
          $rows[] = '<a href="stockEntrepot.php?iDS='.$stock["idDesignation"].'">Details</a>';
        }
      }
    }
    else{
      if($_SESSION['proprietaire']==1){
        $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Stock_ET('.$stock["idDesignation"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$stock["idStock"].'" /></a>&nbsp;
        <a href="stockEntrepot.php?iDS='.$stock["idDesignation"].'">Details</a>';
      }
      else{
        $rows[] = '<a href="stockEntrepot.php?iDS='.$stock["idDesignation"].'">Details</a>';
      }
       
    }
  }
  else{
    if($stock['quantiteStockinitial']==$stock['quantiteStockCourant']){
      if($_SESSION['proprietaire']==1){ 
        $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Stock_ET('.$stock["idDesignation"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$stock["idStock"].'" /></a>&nbsp;
      <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Stock_ET('.$stock["idDesignation"].','.$i.')"  data-toggle="modal" data-target="#imgsup'.$stock["idStock"].'" /></a>&nbsp;
      <a href="stockEntrepot.php?iDS='.$stock["idDesignation"].'">Details</a>';
      }
      else{
        if($dateString==$stock['dateStockage'] && $_SESSION['caisse']==1){
          $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Stock_ET('.$stock["idDesignation"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$stock["idStock"].'" /></a>&nbsp;
          <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Stock_ET('.$stock["idDesignation"].','.$i.')"  data-toggle="modal" data-target="#imgsup'.$stock["idStock"].'" /></a>&nbsp;
          <a href="stockEntrepot.php?iDS='.$stock["idDesignation"].'">Details</a>';
        }
        else{
          $rows[] = '<a href="stockEntrepot.php?iDS='.$stock["idDesignation"].'">Details</a>';
        }
      }
    }
    else{
      if($_SESSION['proprietaire']==1){
        $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Stock_ET('.$stock["idDesignation"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$stock["idStock"].'" /></a>&nbsp;
        <a href="stockEntrepot.php?iDS='.$stock["idDesignation"].'">Details</a>';
      }
      else{
        $rows[] = '<a href="stockEntrepot.php?iDS='.$stock["idDesignation"].'">Details</a>';
      }
       
    }
  }
*/


  $data[] = $rows;
  $i=$i + 1;
  $produits[] = $designation['idDesignation'];
}
}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);

?>
