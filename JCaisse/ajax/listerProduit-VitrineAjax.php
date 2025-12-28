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

if($_SESSION['vitrine']==0){
	header('Location:accueil.php');
}

require('../connection.php');
require('../connectionVitrine.php');

require('../declarationVariables.php');


$req2 = $bddV->prepare("SELECT * from `".$nomtableDesignation."` ");
$req2->execute() or die(print_r($req2->errorInfo()));
if ($req2) {
$data=array();
$i=1;
while($design=$req2->fetch()){

  $rows = array();
  $rows[] = $i;
  $rows[] = strtoupper($design['designationJcaisse']);
  $rows[] = strtoupper($design['designation']);
  $rows[] = strtoupper($design['categorie']);		
  $rows[] = strtoupper($design['uniteStock']);
  $rows[] = $design['prixuniteStock'];
  $rows[] = $design['prix'];
  if ($design["image"]) {
    $rows[] = '  <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_DesignationVT('.$design["id"].')"  data-toggle="modal" data-target="#imgsup'.$design["idDesignation"].'" /></a>&nbsp;
    <a><img src="images/iconfinder11.png" align="middle" alt="apperçu" onclick="imgEX_DesignationVT('.$design["id"].')" data-toggle="modal" data-target="#app'.$design["id"].'" /></a>&nbsp;
    <a><img src="images/edit.png" align="middle" alt="modifier" onclick="edit_DesignationVT('.$design["id"].')"  data-toggle="modal" data-target="#imgedit'.$design["idDesignation"].'" /></a>';
  }
  else{
    $rows[] = '<a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_DesignationVT('.$design["id"].')"  data-toggle="modal" data-target="#imgsup'.$design["idDesignation"].'" /></a>&nbsp;
    <a><img src="images/iconfinder9.png" align="middle" alt="img" onclick="imgNV_DesignationVT('.$design["id"].')" data-toggle="modal" data-target="#img'.$design["id"].'" /></a>&nbsp;
    <a><img src="images/edit.png" align="middle" alt="modifier" onclick="edit_DesignationVT('.$design["id"].')"  data-toggle="modal" data-target="#imgedit'.$design["idDesignation"].'" /></a>';
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
