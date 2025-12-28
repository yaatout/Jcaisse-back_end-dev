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

$idDesignation=@$_GET['id'];

$sql="SELECT * from `".$nomtableInventaire."` 
where idDesignation='".$idDesignation."' order by idInventaire desc";
$res=mysql_query($sql);

$data=array();
$i=1;
while($inventaire=mysql_fetch_array($res)){

  $sql4="SELECT * FROM `". $nomtableEntrepotStock."` where idEntrepotStock='".$inventaire['idEntrepotStock']."'";
  $res4=mysql_query($sql4);
  $stock=mysql_fetch_array($res4);

  $sql2="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$stock['idEntrepot']."'";
  $res2=mysql_query($sql2);
  $entrepot=mysql_fetch_array($res2);

  $date1 = strtotime($dateString); 
  $date2 = strtotime($inventaire['dateInventaire']); 

  if($inventaire['quantite'] >= $inventaire['quantiteStockCourant'] || $inventaire['quantite'] <= $inventaire['quantiteStockCourant']){
    $rows = array();
    if($date1==$date2){
      $rows[] = $i;
      $rows[] = '<span style="color:#ffcc00;">'.strtoupper($entrepot['nomEntrepot']).'</span>';
      $rows[] = '<span style="color:#ffcc00;">'.$stock['dateStockage'].'</span>';
      if($inventaire['quantiteStockCourant']!=0){
        $rows[] = '<span style="color:#ffcc00;">'.($inventaire['quantiteStockCourant'] / $inventaire['nbreArticleUniteStock']).'</span>';
      }
      else{
        $rows[] = '<span style="color:#ffcc00;">'.$inventaire['quantiteStockCourant'].'</span>';
      }
      if($inventaire['quantite'] >= $inventaire['quantiteStockCourant']){
        $rows[] = '<span style="color:green;"> + '.(($inventaire['quantite'] - $inventaire['quantiteStockCourant']) / $inventaire['nbreArticleUniteStock']).'</span>';
      }
      else if ($inventaire['quantite'] <= $inventaire['quantiteStockCourant']) {
        $rows[] = '<span style="color:red;"> - '.(($inventaire['quantiteStockCourant'] - $inventaire['quantite']) / $inventaire['nbreArticleUniteStock']).'</span>';
      }	
      if($inventaire['type']==0){
        $rows[] = '<span style="color:#ffcc00;">NORMAL</span>';
      }
      else if($inventaire['type']==1){
        $rows[] = '<span style="color:#ffcc00;">EXPIRATION</span>';
      }
      else if($inventaire['type']==2){
        $rows[] = '<span style="color:#ffcc00;">MODIFICATION</span>';
      }
      else if($inventaire['type']==20){
        $rows[] = '<span style="color:#ffcc00;">FUSION</span>';
      }
      else if($inventaire['type']==2024){
        $rows[] = '<span style="color:#ffcc00;">ANNUEL</span>';
      }
      else {
        $rows[] = '<span style="color:#ffcc00;">NON SPECIFIEL</span>';
      }

      $rows[] = '<span style="color:#ffcc00;">'.$inventaire['dateInventaire'].'</span>';
    }		
    else{
      $rows[] = $i;
      $rows[] = strtoupper($entrepot['nomEntrepot']);
      $rows[] = $stock['dateStockage'];
      if($inventaire['quantiteStockCourant']!=0){
        $rows[] = ($inventaire['quantiteStockCourant'] / $inventaire['nbreArticleUniteStock']);
      }
      else{
        $rows[] = $inventaire['quantiteStockCourant'];
      }
      if($inventaire['quantite'] >= $inventaire['quantiteStockCourant']){
        $rows[] = '<span style="color:green;"> + '.(($inventaire['quantite'] - $inventaire['quantiteStockCourant']) / $inventaire['nbreArticleUniteStock']).'</span>';
      }
      else if ($inventaire['quantite'] <= $inventaire['quantiteStockCourant']) {
        $rows[] = '<span style="color:red;"> - '.(($inventaire['quantiteStockCourant'] - $inventaire['quantite']) / $inventaire['nbreArticleUniteStock']).'</span>';
      }	
      if($inventaire['type']==0){
        $rows[] = 'NORMAL';
      }
      else if($inventaire['type']==1){
        $rows[] = 'EXPIRATION';
      }
      else if($inventaire['type']==2){
        $rows[] = 'MODIFICATION';
      }	
      else if($inventaire['type']==20){
        $rows[] = 'FUSION';
      }
      else if($inventaire['type']==2024){
        $rows[] = 'ANNUEL';
      }
      else {
        $rows[] = 'NON SPECIFIEL';
      }
      $rows[] = $inventaire['dateInventaire'];
    }	
  
    $data[] = $rows;
    $i=$i + 1;
  }

}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);


?>
