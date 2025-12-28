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

$sql="SELECT e.designation,e.idEntrepot,e.dateStockage,i.idInventaire,i.quantite,i.quantiteStockCourant,i.nbreArticleUniteStock,i.type,i.dateInventaire from `".$nomtableInventaire."` i
INNER JOIN `".$nomtableEntrepotStock."` e ON e.idEntrepotStock = i.idEntrepotStock
where e.idEntrepot='".$idEntrepot."' order by i.idInventaire desc";
$res=mysql_query($sql);

$data=array();
$i=1;
while($inventaire=mysql_fetch_array($res)){

  $date1 = strtotime($dateString); 
  $date2 = strtotime($inventaire['dateInventaire']); 

  if($inventaire['quantite'] > $inventaire['quantiteStockCourant'] || $inventaire['quantite'] < $inventaire['quantiteStockCourant']){
    $rows = array();
    if($date1==$date2){
      $rows[] = $i;
      $rows[] = '<span style="color:#ffcc00;">'.strtoupper($inventaire['designation']).'</span>';
      $rows[] = '<span style="color:#ffcc00;">'.$inventaire['dateStockage'].'</span>';
      if($inventaire['quantiteStockCourant']!=0){
        $rows[] = '<span style="color:#ffcc00;">'.($inventaire['quantiteStockCourant'] / $inventaire['nbreArticleUniteStock']).'</span>';
      }
      else{
        $rows[] = '<span style="color:#ffcc00;">'.$inventaire['quantiteStockCourant'].'</span>';
      }
      if($inventaire['quantite'] > $inventaire['quantiteStockCourant']){
        $rows[] = '<span style="color:green;"> + '.(($inventaire['quantite'] - $inventaire['quantiteStockCourant']) / $inventaire['nbreArticleUniteStock']).'</span>';
      }
      else if ($inventaire['quantite'] < $inventaire['quantiteStockCourant']) {
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
      $rows[] = '<span style="color:#ffcc00;">'.$inventaire['dateInventaire'].'</span>';
    }		
    else{
      $rows[] = $i;
      $rows[] = strtoupper($inventaire['designation']);
      $rows[] = $inventaire['dateStockage'];
      if($inventaire['quantiteStockCourant']!=0){
        $rows[] = ($inventaire['quantiteStockCourant'] / $inventaire['nbreArticleUniteStock']);
      }
      else{
        $rows[] = $inventaire['quantiteStockCourant'];
      }
      if($inventaire['quantite'] > $inventaire['quantiteStockCourant']){
        $rows[] = '<span style="color:green;"> + '.(($inventaire['quantite'] - $inventaire['quantiteStockCourant']) / $inventaire['nbreArticleUniteStock']).'</span>';
      }
      else if ($inventaire['quantite'] < $inventaire['quantiteStockCourant']) {
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
