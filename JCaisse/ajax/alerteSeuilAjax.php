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

$sql="select * from `".$nomtableDesignation."` where classe=0 ";
$res=mysql_query($sql);

$data=array();
$i=1;
while($design=mysql_fetch_array($res)){

  if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
    $sqlS="SELECT SUM(quantiteStockCourant)
    FROM `".$nomtableEntrepotStock."`
    where idDesignation ='".$design['idDesignation']."' ";
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $S_produit = mysql_fetch_array($resS);

    if($S_produit[0]!=null){
      $rows = array();
      $T_produit=($S_produit[0]/$design['nbreArticleUniteStock']);
      if ($T_produit<=10){
        if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
          if($S_produit[0]!=0 && $S_produit[0]!=null){
            $rows[] = '<span style="color:red;">'.($S_produit[0]/$design['nbreArticleUniteStock']).'</span>';
          }
          else{
            $rows[] = '<span style="color:red;">'.$S_produit[0].'</span>';
          }
        }
        else{
          $rows[] = '<span style="color:red;">'.$S_produit[0].'</span>';
        }
        $rows[] = '<span style="color:red;">'.strtoupper($design['designation']).'</span>';
        $rows[] = '<span style="color:red;">'.strtoupper($design['categorie']).'</span>';
        $rows[] = '<span style="color:blue;">REAPPROVISIONNEMENT</span>';
      }else if (($T_produit>10) && ($T_produit<=40)){
        if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
          $rows[] = '<span style="color:#ffcc00;">'.($S_produit[0]/$design['nbreArticleUniteStock']).'</span>';
        }
        else{
          $rows[] = '<span style="color:#ffcc00;">'.$S_produit[0].'</span>';
        }
        $rows[] = '<span style="color:#ffcc00;">'.strtoupper($design['designation']).'</span>';
        $rows[] = '<span style="color:#ffcc00;">'.strtoupper($design['categorie']).'</span>';
        $rows[] = '<span style="color:orange;">CONSEILLER</span>';
      }else if ($T_produit>40){
        if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
          $rows[] = '<span style="color:green;">'.($S_produit[0]/$design['nbreArticleUniteStock']).'</span>';
        }
        else{
          $rows[] = '<span style="color:green;">'.$S_produit[0].'</span>';
        }
        $rows[] = '<span style="color:green;">'.strtoupper($design['designation']).'</span>';
        $rows[] = '<span style="color:green;">'.strtoupper($design['categorie']).'</span>';
        $rows[] = '<span style="color:green;">NORMAL</span>';
      }
      $data[] = $rows;
    }
  }
  else{
    $sqlS="SELECT SUM(quantiteStockCourant)
    FROM `".$nomtableStock."`
    where idDesignation ='".$design['idDesignation']."' ";
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $S_produit = mysql_fetch_array($resS); 

    if($S_produit[0]!=null){
      $rows = array();
      if ($S_produit[0]<=10){
        if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
          if($S_produit[0]!=0 && $S_produit[0]!=null){
            $rows[] = '<span style="color:red;">'.($S_produit[0]/$design['nbreArticleUniteStock']).'</span>';
          }
          else{
            $rows[] = '<span style="color:red;">'.$S_produit[0].'</span>';
          }
        }
        else{
          $rows[] = '<span style="color:red;">'.$S_produit[0].'</span>';
        }
        $rows[] = '<span style="color:red;">'.strtoupper($design['designation']).'</span>';
        $rows[] = '<span style="color:red;">'.strtoupper($design['categorie']).'</span>';
        $rows[] = '<span style="color:blue;">REAPPROVISIONNEMENT</span>';
      }else if (($S_produit[0]>10) && ($S_produit[0]<=40)){
        if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
          $rows[] = '<span style="color:#ffcc00;">'.($S_produit[0]/$design['nbreArticleUniteStock']).'</span>';
        }
        else{
          $rows[] = '<span style="color:#ffcc00;">'.$S_produit[0].'</span>';
        }
        $rows[] = '<span style="color:#ffcc00;">'.strtoupper($design['designation']).'</span>';
        $rows[] = '<span style="color:#ffcc00;">'.strtoupper($design['categorie']).'</span>';
        $rows[] = '<span style="color:orange;">CONSEILLER</span>';
      }else if ($S_produit[0]>40){
        if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
          $rows[] = '<span style="color:green;">'.($S_produit[0]/$design['nbreArticleUniteStock']).'</span>';
        }
        else{
          $rows[] = '<span style="color:green;">'.$S_produit[0].'</span>';
        }
        $rows[] = '<span style="color:green;">'.strtoupper($design['designation']).'</span>';
        $rows[] = '<span style="color:green;">'.strtoupper($design['categorie']).'</span>';
        $rows[] = '<span style="color:green;">NORMAL</span>';
      }
      $data[] = $rows;
    }
  }


}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);





?>
