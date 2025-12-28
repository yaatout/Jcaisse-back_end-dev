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

$id=@$_GET['id'];

$date = new DateTime();
$timezone = new DateTimeZone('Africa/Dakar');
$date->setTimezone($timezone);

$annee =$date->format('Y');
$mois =$date->format('m');
$jour =$date->format('d');
$heureString=$date->format('H:i:s');
$dateString=$annee.'-'.$mois.'-'.$jour ;

$sql="select * from `".$nomtableStock."` where idBl='".$id."' order by idStock DESC";
$res=mysql_query($sql);

$data=array();
$i=1;
while($stock=mysql_fetch_array($res)){

  $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
  $res1=mysql_query($sql1);
  $designation=mysql_fetch_array($res1);

  $date1 = strtotime($dateString); 
  $date2 = strtotime($stock['dateStockage']); 

  $rows = array();
  if($i==1){
    $rows[] = $i;
    $rows[] = '<span style="color:blue;">'.strtoupper($stock['designation']).'</span>';
    $rows[] = '<span style="color:blue;">'.strtoupper($stock['uniteStock']).'</span>';
    $rows[] = '<span style="color:blue;">'.$stock['quantiteStockinitial'].'</span>';
    if($designation['nbreArticleUniteStock']!=0 || $designation['nbreArticleUniteStock']!=null){
      if($stock['quantiteStockCourant']!=0 && $stock['quantiteStockCourant']!=null){
        $rows[] = '<span style="color:blue;">'.($stock['quantiteStockCourant'] / $designation['nbreArticleUniteStock']) .'</span>';
      }
      else{
        $rows[] = '<span style="color:blue;">'.$stock['quantiteStockCourant'].'</span>';
      }
    }
    else{
      $rows[] = '<span style="color:blue;">'.$stock['quantiteStockCourant'].'</span>';
    }
    $rows[] = '<span style="color:blue;">'.number_format($stock['prixachat'], 0, ',', ' ').'</span>';
    $rows[] = '<span style="color:blue;">'.number_format($stock['prixuniteStock'], 0, ',', ' ').'</span>';
    $rows[] = '<span style="color:blue;">'.$stock['dateStockage'].'</span>';		
    $rows[] = '<span style="color:blue;">'.$stock['dateExpiration'].'</span>';
  }	
  else if($date1==$date2){
    $rows[] = $i;
    $rows[] = '<span style="color:#ffcc00;">'.strtoupper($stock['designation']).'</span>';
    $rows[] = '<span style="color:#ffcc00;">'.strtoupper($stock['uniteStock']).'</span>';
    $rows[] = '<span style="color:#ffcc00;">'.$stock['quantiteStockinitial'].'</span>';
    if($designation['nbreArticleUniteStock']!=0 || $designation['nbreArticleUniteStock']!=null){
      if($stock['quantiteStockCourant']!=0 && $stock['quantiteStockCourant']!=null){
        $rows[] = '<span style="color:#ffcc00;">'.($stock['quantiteStockCourant'] / $designation['nbreArticleUniteStock']) .'</span>';
      }
      else{
        $rows[] = '<span style="color:#ffcc00;">'.$stock['quantiteStockCourant'].'</span>';
      }
    }
    else{
      $rows[] = '<span style="color:#ffcc00;">'.$stock['quantiteStockCourant'].'</span>';
    }
    $rows[] = '<span style="color:#ffcc00;">'.number_format($stock['prixachat'], 0, ',', ' ').'</span>';
    $rows[] = '<span style="color:#ffcc00;">'.number_format($stock['prixuniteStock'], 0, ',', ' ').'</span>';
    $rows[] = '<span style="color:#ffcc00;">'.$stock['dateStockage'].'</span>';		
    $rows[] = '<span style="color:#ffcc00;">'.$stock['dateExpiration'].'</span>';
  }	
  else{
    $rows[] = $i;
    $rows[] = strtoupper($stock['designation']);
    $rows[] = strtoupper($stock['uniteStock']);
    $rows[] = $stock['quantiteStockinitial'];
    if($designation['nbreArticleUniteStock']!=0 || $designation['nbreArticleUniteStock']!=null){
      if($stock['quantiteStockCourant']!=0 && $stock['quantiteStockCourant']!=null){
        $rows[] = ($stock['quantiteStockCourant'] / $designation['nbreArticleUniteStock']);
      }
      else{
        $rows[] = $stock['quantiteStockCourant'];
      }
    }
    else{
      $rows[] = $stock['quantiteStockCourant'];
    }
    $rows[] = number_format($stock['prixachat'], 0, ',', ' ');
    $rows[] = number_format($stock['prixuniteStock'], 0, ',', ' ');
    $rows[] = $stock['dateStockage'];		
    $rows[] = $stock['dateExpiration']; 
  }	

  if($stock['totalArticleStock']==$stock['quantiteStockCourant']){
    if($_SESSION['proprietaire']==1){ 
      $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Stock_Bl('.$stock["idStock"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$stock["idStock"].'" /></a>&nbsp;
    <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Stock_Bl('.$stock["idStock"].','.$i.')"  data-toggle="modal" data-target="#imgsup'.$stock["idStock"].'" /></a>&nbsp;
    <a onclick="trf_Stock_Bl('.$stock["idStock"].','.$i.')" data-toggle="modal"><span class="glyphicon glyphicon-export"></span></a>&nbsp;';
    }
    else{
      if($dateString==$stock['dateStockage'] && $_SESSION['caisse']==1){
        $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Stock_Bl('.$stock["idStock"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$stock["idStock"].'" /></a>&nbsp;
        <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Stock_Bl('.$stock["idStock"].','.$i.')"  data-toggle="modal" data-target="#imgsup'.$stock["idStock"].'" /></a>&nbsp;';
      }
      else{
        $rows[] = ' ';
      }
    }
     
  }
  else{
    if($_SESSION['proprietaire']==1){
      $rows[] = '<a onclick="trf_Stock_Bl('.$stock["idStock"].','.$i.')" data-toggle="modal"><span class="glyphicon glyphicon-export"></span></a>&nbsp;';
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
