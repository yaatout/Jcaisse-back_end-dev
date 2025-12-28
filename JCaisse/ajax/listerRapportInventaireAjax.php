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
$dateString2=$jour.'-'.$mois.'-'.$annee ;


$debut=@$_GET['debut'];
$fin=@$_GET['fin'];

$sql="SELECT * from `".$nomtableInventaire."`i where i.type=3 and i.dateInventaire BETWEEN '".$debut."' AND '".$fin."' order by idInventaire desc";
$res=mysql_query($sql);

$data=array();
$produits=array();
$i=1;
while($inventaire=mysql_fetch_array($res)){

  $sql4="SELECT * FROM `". $nomtableStock."`s where s.idStock='".$inventaire['idStock']."' ";
  $res4=mysql_query($sql4);
  $stock=mysql_fetch_array($res4);

  $sqlN="select * from `".$nomtableDesignation."` where idDesignation='".$stock["idDesignation"]."' ";
  $resN=mysql_query($sqlN);
  $designation = mysql_fetch_array($resN);

  $date1 = strtotime($dateString); 
  $date2 = strtotime($inventaire['dateInventaire']); 

  if(in_array($designation['idDesignation'], $produits)){
    // echo "Existe.";
  }
  else{

    if($inventaire['quantite'] > $inventaire['quantiteStockCourant'] || $inventaire['quantite'] < $inventaire['quantiteStockCourant']){
      $rows = array();
      if($date1==$date2){
        $rows[] = $i;
        $rows[] = '<span style="color:#ffcc00;">'.$stock['designation'].'</span>';
        $rows[] = '<span style="color:#ffcc00;">'.$stock['dateStockage'].'</span>';
        $rows[] = '<span style="color:#ffcc00;">'.$inventaire['quantiteStockCourant'].'</span>';
        if($inventaire['quantite'] > $inventaire['quantiteStockCourant']){
          $rows[] = '<span style="color:green;"> + '.($inventaire['quantite'] - $inventaire['quantiteStockCourant']).'</span>';
        }
        else if ($inventaire['quantite'] < $inventaire['quantiteStockCourant']) {
          $rows[] = '<span style="color:red;"> - '.($inventaire['quantiteStockCourant'] - $inventaire['quantite']).'</span>';
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
        else if($inventaire['type']==3){
          $rows[] = '<span style="color:#ffcc00;">RETIRER</span>';
        }	
        $rows[] = '<span style="color:#ffcc00;">'.$inventaire['dateInventaire'].'</span>';
      }		
      else{
        $rows[] = $i;
        $rows[] = $stock['designation'];
        $rows[] = $stock['dateStockage'];
        $rows[] = $inventaire['quantiteStockCourant'];
        if($inventaire['quantite'] > $inventaire['quantiteStockCourant']){
          $rows[] = '<span style="color:green;"> + '.($inventaire['quantite'] - $inventaire['quantiteStockCourant']).'</span>';
        }
        else if ($inventaire['quantite'] < $inventaire['quantiteStockCourant']) {
          $rows[] = '<span style="color:red;"> - '.($inventaire['quantiteStockCourant'] - $inventaire['quantite']).'</span>';
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
        else if($inventaire['type']==3){
          $rows[] = 'RETIRER';
        }	
        $rows[] = $inventaire['dateInventaire'];
      }	
      
      $debut=@$_GET['debut'];
      $fin=@$_GET['fin'];
      $db = explode("-", $debut);
      $date_debut=$db[0].''.$db[1].''.$db[2];
      $df = explode("-", $fin);
      $date_fin=$df[0].''.$df[1].''.$df[2];
      $rows[] = '<button  type="button" onclick="rapport_Inventaire('.$stock['idDesignation'].','.$date_debut.','.$date_fin.')" class="btn btn-primary" >
      <i class="glyphicon glyphicon-transfer"></i> Details
      </button>';
    
      $data[] = $rows;
      $i=$i + 1;
      $produits[] = $designation['idDesignation'];
    }

  }

}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);


?>
