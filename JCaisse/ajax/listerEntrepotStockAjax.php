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

$idEntrepot=@$_GET['id'];

$sql="SELECT * from `".$nomtableEntrepotStock."` where idEntrepot='".$idEntrepot."' order by idEntrepotStock desc";
$res=mysql_query($sql);

$data=array();
$i=1;
while($stock=mysql_fetch_array($res)){

  $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
  $res1=mysql_query($sql1);
  $design=mysql_fetch_array($res1);

  $sql2="SELECT * FROM `". $nomtableStock."` where idStock='".$stock['idStock']."'";
  $res2=mysql_query($sql2);
  $stock0=mysql_fetch_array($res2);

  $date1 = strtotime($dateString); 
  $date2 = strtotime($stock['dateStockage']); 

  $rows = array();
  if($date1==$date2){
    $rows[] = $i;
    $rows[] = '<span style="color:#ffcc0;">'.strtoupper($stock['designation']).'</span>';
    $rows[] = '<span style="color:#ffcc0;">'.strtoupper($design['categorie']).'</span>';
    $rows[] = '<span style="color:#ffcc0;">'.$stock['quantiteStockinitial'].'</span>';
    $rows[] = '<span style="color:#ffcc0;">'.$stock['uniteStock'].'</span>';
    $rows[] = '<span style="color:#ffcc0;">'.$stock['totalArticleStock'].'</span>';
    $rows[] = '<span style="color:#ffcc0;">'.$stock0['dateExpiration'].'</span>';
    $rows[] = '<span style="color:#ffcc0;">'.$stock['dateStockage'].'</span>';	
  }		
  else{
    $rows[] = $i;
    $rows[] = strtoupper($stock['designation']);
    $rows[] = strtoupper($design['categorie']);
    $rows[] = $stock['quantiteStockinitial'];
    $rows[] = $stock['uniteStock'];
    $rows[] = $stock['totalArticleStock'];
    $rows[] = $stock0['dateExpiration'];
    $rows[] = $stock['dateStockage']; 
  }	


  if($stock['totalArticleStock']==$stock['quantiteStockCourant']){
    if($_SESSION['proprietaire']==1){ 
      $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier"  id="" data-toggle="modal" data-target="#imgmodifier'.$stock["idEntrepotStock"].'" /></a>&nbsp;
    <a><img src="images/drop.png" align="middle" alt="supprimer"  data-toggle="modal" data-target="#imgsup'.$stock["idEntrepotStock"].'" /></a>&nbsp;';
    }
    else{
      if($dateString==$stock['dateStockage'] && $_SESSION['caisse']==1){
        $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier"  id="" data-toggle="modal" data-target="#imgmodifier'.$stock["idEntrepotStock"].'" /></a>&nbsp;
        <a><img src="images/drop.png" align="middle" alt="supprimer" data-toggle="modal" data-target="#imgsup'.$stock["idEntrepotStock"].'" /></a>&nbsp;';
      }
      else{
        $rows[] = '';
      }
    }
     
  }
  else{
    if($_SESSION['proprietaire']==1){
      $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier"  id="" data-toggle="modal" data-target="#imgmodifier'.$stock["idEntrepotStock"].'" /></a>&nbsp;';
    }
    else{
      $rows[] = '';
    }
     
  }


  $data[] = $rows;
  $i=$i + 1;
}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);


?>
