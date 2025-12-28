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

  $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$idDesignation."' ";
  $res1=mysql_query($sql1);
  $design=mysql_fetch_array($res1);

  $sqlU="SELECT * from `aaa-utilisateur` where idutilisateur='".$stock['iduser']."' ";
  $resU=mysql_query($sqlU);

  $date1 = strtotime($dateString); 
  $date2 = strtotime($stock['dateStockage']);

  $rows = array();
  if($date1==$date2){
    $rows[] = $i;
    $rows[] = '<span style="color:#ffcc00;">'.$stock['dateStockage'].'</span>';	
    $rows[] = '<span style="color:#ffcc00;">'.$stock['quantiteStockinitial'].'</span>';
    $rows[] = '<span style="color:#ffcc00;">'.$stock['quantiteStockCourant'].'</span>';
    $rows[] = '<span style="color:#ffcc00;">'.$stock['uniteStock'].'</span>';
    $rows[] = '<span style="color:#ffcc00;">'.$stock['nbreArticleUniteStock'].'</span>';
    $rows[] = '<span style="color:#ffcc00;">'.$stock['prixuniteStock'].'</span>';
    $rows[] = '<span style="color:#ffcc00;">'.$stock['prixunitaire'].'</span>';	
    $rows[] = '<span style="color:#ffcc00;">'.$stock['dateExpiration'].'</span>';
    if(mysql_num_rows($resU)){
      $user=mysql_fetch_array($resU);
      $rows[] = '<span style="color:#ffcc00;">'.strtoupper($user["prenom"]).'</span>';
    }
    else{
      $rows[] = '<span style="color:#ffcc00;">NEANT</span>';
    }
  }
  else{
    $rows[] = $i;
    $rows[] = $stock['dateStockage'];	
    $rows[] = $stock['quantiteStockinitial'];
    $rows[] = $stock['quantiteStockCourant'];
    $rows[] = $stock['uniteStock'];
    $rows[] = $stock['nbreArticleUniteStock'];
    $rows[] = $design['prixuniteStock'];
    $rows[] = $design['prix'];	
    $rows[] = $stock['dateExpiration'];
    if(mysql_num_rows($resU)){
      $user=mysql_fetch_array($resU);
      $rows[] = strtoupper($user["prenom"]);
    }
    else{
      $rows[] = 'NEANT';
    }
  }
  
  if($stock['totalArticleStock']==$stock['quantiteStockCourant']){
    if($_SESSION['proprietaire']==1){ 
        $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Stock('.$stock["idStock"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$stock["idStock"].'" /></a>&nbsp;
        <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Stock('.$stock["idStock"].','.$i.')"  data-toggle="modal" data-target="#imgsup'.$stock["idStock"].'" /></a>&nbsp
        <a onclick="retirer_Stock('.$stock["idStock"].','.$i.')" data-toggle="modal" ><span style="color:red" class="glyphicon glyphicon-open"></span></a>&nbsp
        <a onclick="retourner_Stock('.$stock["idStock"].','.$i.')" data-toggle="modal" ><span style="color:blue" class="glyphicon glyphicon-export"></span></a>&nbsp
        <a onclick="imprimer_Stock('.$stock["idStock"].','.$i.')" data-toggle="modal" ><span style="color:green" class="glyphicon glyphicon-print"></span></a>&nbsp
        <a onclick="imprimerCodeBarre_Stock('.$stock["idStock"].','.$i.')" data-toggle="modal" ><span style="color:green" class="glyphicon glyphicon-barcode"></span></a>&nbsp';
    }
    else{
        if($dateString==$stock['dateStockage'] && $_SESSION['caisse']==1){
          $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Stock('.$stock["idStock"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$stock["idStock"].'" /></a>&nbsp;
          <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Stock('.$stock["idStock"].','.$i.')"  data-toggle="modal" data-target="#imgsup'.$stock["idStock"].'" /></a>
          <a onclick="imprimer_Stock('.$stock["idStock"].','.$i.')" data-toggle="modal" ><span style="color:green" class="glyphicon glyphicon-print"></span></a>&nbsp
          <a onclick="imprimerCodeBarre_Stock('.$stock["idStock"].','.$i.')" data-toggle="modal" ><span style="color:green" class="glyphicon glyphicon-barcode"></span></a>&nbsp';
        }
        else{
          $rows[] = '<a onclick="imprimer_Stock('.$stock["idStock"].','.$i.')" data-toggle="modal" ><span style="color:green" class="glyphicon glyphicon-print"></span></a>&nbsp';
        }
    }  
  }
  else{
    if($_SESSION['proprietaire']==1){
        $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Stock('.$stock["idStock"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$stock["idStock"].'" /></a>&nbsp;&nbsp
        <a onclick="retirer_Stock('.$stock["idStock"].','.$i.')" data-toggle="modal" ><span style="color:red" class="glyphicon glyphicon-open"></span></a>&nbsp
        <a onclick="retourner_Stock('.$stock["idStock"].','.$i.')" data-toggle="modal" ><span style="color:blue" class="glyphicon glyphicon-export"></span></a>&nbsp
        <a onclick="imprimer_Stock('.$stock["idStock"].','.$i.')" data-toggle="modal" ><span style="color:green" class="glyphicon glyphicon-print"></span></a>&nbsp
        <a onclick="imprimerCodeBarre_Stock('.$stock["idStock"].','.$i.')" data-toggle="modal" ><span style="color:green" class="glyphicon glyphicon-barcode"></span></a>&nbsp';
    }
    else{
        $rows[] = '<a onclick="imprimer_Stock('.$stock["idStock"].','.$i.')" data-toggle="modal" ><span style="color:green" class="glyphicon glyphicon-print"></span></a>&nbsp
        <a onclick="imprimerCodeBarre_Stock('.$stock["idStock"].','.$i.')" data-toggle="modal" ><span style="color:green" class="glyphicon glyphicon-barcode"></span></a>&nbsp';
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
