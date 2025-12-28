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

$idDesignation=@$_GET['id'];

$sql="SELECT * FROM `".$nomtableStock."`
 where idDesignation='".$idDesignation."' ORDER BY dateStockage DESC ";
$res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

$data=array();
$i=1;
while($stock=mysql_fetch_array($res)){

  $sql2="select * from `".$nomtableBl."` where idBl='".$stock['idBl']."' ";
  $res2=mysql_query($sql2);
  $bl=mysql_fetch_array($res2);

  $rows = array(); 
  $date1 = strtotime($dateString); 
  $date2 = strtotime($stock['dateStockage']);
  if($date1==$date2){
    $rows[] = $i;
    if($bl!=null){
      $rows[] = '<span style="color:#ffcc00;">'.$bl['numeroBl'].'</span>';	
    }
    else{
      $rows[] = '<span style="color:#ffcc00;">NEANT</span>';	
    }
    $rows[] = '<span style="color:#ffcc00;">'.$stock['dateStockage'].'</span>';	
    $rows[] = '<span style="color:#ffcc00;">'.$stock['quantiteStockinitial'].'</span>';
    $rows[] = '<span style="color:#ffcc00;">'.$stock['quantiteStockCourant'].'</span>';
    $rows[] = '<span style="color:#ffcc00;">'.strtoupper($stock['forme']).'</span>';
    $rows[] = '<span style="color:#ffcc00;">'.$stock['prixSession'].'</span>';
    $rows[] = '<span style="color:#ffcc00;">'.$stock['prixPublic'].'</span>';	
    $rows[] = '<span style="color:#ffcc00;">'.$stock['dateExpiration'].'</span>';
  }
  else{
    $rows[] = $i;
    if($bl!=null){
      $rows[] = $bl['numeroBl'];	
    }
    else{
      $rows[] = 'NEANT';	
    }
    $rows[] = $stock['dateStockage'];	
    $rows[] = $stock['quantiteStockinitial'];
    $rows[] = $stock['quantiteStockCourant'];
    $rows[] = strtoupper($stock['forme']);
    $rows[] = $stock['prixSession'];
    $rows[] = $stock['prixPublic'];	
    $rows[] = $stock['dateExpiration'];
  }

  
  if($stock['quantiteStockinitial']==$stock['quantiteStockCourant']){
    if($_SESSION['proprietaire']==1){ 
      $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Stock_Ph('.$stock["idStock"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$stock["idStock"].'" /></a>&nbsp;
    <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Stock_Ph('.$stock["idStock"].','.$i.')"  data-toggle="modal" data-target="#imgsup'.$stock["idStock"].'" /></a>&nbsp;
    <a href="codeBarreStock-pharmacie.php?iDS='.$stock["idStock"].'&iDD='.$stock["designation"].'">Details</a>';
    }
    else{
      if($dateString==$stock['dateStockage'] && $_SESSION['caisse']==1){
        $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Stock_Ph('.$stock["idStock"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$stock["idStock"].'" /></a>&nbsp;
        <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Stock_Ph('.$stock["idStock"].','.$i.')"  data-toggle="modal" data-target="#imgsup'.$stock["idStock"].'" /></a>&nbsp;
        <a href="codeBarreStock-pharmacie.php?iDS='.$stock["idStock"].'&iDD='.$stock["designation"].'">Details</a>';
      }
      else{
        $rows[] = '<a href="codeBarreStock-pharmacie.php?iDS='.$stock["idStock"].'&iDD='.$stock["designation"].'">Details</a>';
      }
    }
  }
  else{
    if($_SESSION['proprietaire']==1){
      $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Stock_Ph('.$stock["idStock"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$stock["idStock"].'" /></a>&nbsp;
      <a href="codeBarreStock.php?iDS='.$stock["idStock"].'&iDD='.$stock["designation"].'">Details</a>';
    }
    else{
      $rows[] = '<a href="codeBarreStock-pharmacie.php?iDS='.$stock["idStock"].'&iDD='.$stock["designation"].'">Details</a>';
    }
     
  }

  //$rows[] = 'Operation';

  $data[] = $rows;
  $i=$i + 1;
}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);

?>
