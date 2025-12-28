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


$idDesignation=@$_GET['id'];
$debut=@$_GET['debut'];
$fin=@$_GET['fin'];


$sql="SELECT * FROM `".$nomtableStock."`
WHERE idDesignation='".$idDesignation."' AND  dateStockage BETWEEN '".$debut."' AND '".$fin."' ORDER BY idStock DESC";
$res=mysql_query($sql);

$data=array();
$produits=array();
$i=1;
while($stock=mysql_fetch_array($res)){

  $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
  $res1=mysql_query($sql1);
  $designation=mysql_fetch_array($res1);

  $date1 = strtotime($dateString);
  $date2 = strtotime($stock['dateStockage']);

  if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
    $sqlS="SELECT SUM(quantiteStockinitial)
    FROM `".$nomtableStock."`
    where idStock ='".$stock['idStock']."' ";
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $S_stock = mysql_fetch_array($resS);

    $rows = array();
    if($i==1){
      $rows[] = $i;
      $rows[] = '<span style="color:blue;">'.strtoupper($designation['designation']).'</span>';
      $rows[] = '<span style="color:blue;">'.strtoupper($designation['forme']).'</span>';
      $rows[] = '<span style="color:blue;">'.strtoupper($designation['tableau']).'</span>';
      $rows[] = '<span style="color:blue;">'.$S_stock[0].'</span>';
      $rows[] = '<span style="color:blue;">'.$stock['prixSession'].'</span>';
      $rows[] = '<span style="color:blue;">'.$stock['prixPublic'].'</span>';
      $rows[] = '<span style="color:blue;">'.$stock['dateStockage'].'</span>';
    }
    else if($date1==$date2){
      $rows[] = $i;
      $rows[] = '<span style="color:#ffcc00;">'.strtoupper($designation['designation']).'</span>';
      $rows[] = '<span style="color:#ffcc00;">'.strtoupper($designation['forme']).'</span>';
      $rows[] = '<span style="color:#ffcc00;">'.strtoupper($designation['tableau']).'</span>';
      $rows[] = '<span style="color:#ffcc00;">'.$S_stock[0].'</span>';
      $rows[] = '<span style="color:#ffcc00;">'.$stock['prixSession'].'</span>';
      $rows[] = '<span style="color:#ffcc00;">'.$stock['prixPublic'].'</span>';
      $rows[] = '<span style="color:#ffcc00;">'.$stock['dateStockage'].'</span>';
    }
    else{
      $rows[] = $i;
      $rows[] = strtoupper($designation['designation']);
      $rows[] = strtoupper($designation['forme']);
      $rows[] = strtoupper($designation['tableau']);
      $rows[] = $S_stock[0];
      $rows[] = $stock['prixSession'];
      $rows[] = $stock['prixPublic'];
      $rows[] = $stock['dateStockage'];
    }
  }
  else{
    $sqlS="SELECT SUM(quantiteStockinitial)
    FROM `".$nomtableStock."`
    where idStock ='".$stock['idStock']."' ";
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $S_stock = mysql_fetch_array($resS);

    $rows = array();
    if($i==1){
      $rows[] = $i;
      $rows[] = '<span style="color:blue;">'.strtoupper($designation['designation']).'</span>';
      if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
        if($S_stock[0]!=0 && $S_stock[0]!=null){
          $rows[] = '<span style="color:blue;">'.round(($S_stock[0] / $designation['nbreArticleUniteStock']),1) .'</span>';
        }
        else{
          $rows[] = '<span style="color:blue;">'.$S_stock[0].'</span>';
        }
      }
      else{
        $rows[] = '<span style="color:blue;">'.$S_stock[0].'</span>';
      }
      $rows[] = '<span style="color:blue;">'.strtoupper($stock['uniteStock']).'</span>';
      $rows[] = '<span style="color:blue;">'.$stock['nbreArticleUniteStock'].'</span>';
      $rows[] = '<span style="color:blue;">'.$designation['prixachat'].'</span>';
      $rows[] = '<span style="color:blue;">'.$stock['prixunitaire'].'</span>';
      $rows[] = '<span style="color:blue;">'.$stock['dateStockage'].'</span>';
    }
    else if($date1==$date2){
      $rows[] = $i;
      $rows[] = '<span style="color:#ffcc00;">'.strtoupper($designation['designation']).'</span>';
      if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
        if($S_stock[0]!=0 && $S_stock[0]!=null){
          $rows[] = '<span style="color:#ffcc00;">'.round(($S_stock[0] / $designation['nbreArticleUniteStock']),1) .'</span>';
        }
        else{
          $rows[] = '<span style="color:#ffcc00;">'.$S_stock[0].'</span>';
        }
      }
      else{
        $rows[] = '<span style="color:#ffcc00;">'.$S_stock[0].'</span>';
      }
      $rows[] = '<span style="color:#ffcc00;">'.strtoupper($stock['uniteStock']).'</span>';
      $rows[] = '<span style="color:#ffcc00;">'.$stock['nbreArticleUniteStock'].'</span>';
      $rows[] = '<span style="color:#ffcc00;">'.$designation['prixachat'].'</span>';
      $rows[] = '<span style="color:#ffcc00;">'.$stock['prixunitaire'].'</span>';
      $rows[] = '<span style="color:#ffcc00;">'.$stock['dateStockage'].'</span>';
    }
    else{
      $rows[] = $i;
      $rows[] = strtoupper($designation['designation']);
      if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
        if($S_stock[0]!=0 && $S_stock[0]!=null){
          $rows[] = round(($S_stock[0] / $designation['nbreArticleUniteStock']),1);
        }
        else{
          $rows[] = $S_stock[0] ;
        }
      }
      else{
        $rows[] = $S_stock[0] ;
      }
      $rows[] = strtoupper($stock['uniteStock']);
      $rows[] = $stock['nbreArticleUniteStock'];
      $rows[] = $designation['prixachat'];
      $rows[] = $stock['prixunitaire'];
      $rows[] = $stock['dateStockage'];
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
