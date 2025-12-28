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

$idDesignation=@$_GET['idD'];
$idEntrepotTransfert=@$_GET['idT'];

$sql0="SELECT * FROM `".$nomtableEntrepotTransfert."`  where idEntrepotTransfert='".$idEntrepotTransfert."' ";
$res0=mysql_query($sql0);
$transfert=mysql_fetch_array($res0);

$sql="SELECT * from `".$nomtableEntrepotStock."` 
where idDesignation='".$idDesignation."' and idEntrepot!='".$transfert['idEntrepot']."' and quantiteStockCourant!=0  order by idEntrepotStock desc";
$res=mysql_query($sql);

$data=array();
$i=1;
while($stock=mysql_fetch_array($res)){

  $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
  $res1=mysql_query($sql1);
  $design=mysql_fetch_array($res1);

  $sql2="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$stock['idEntrepot']."'";
  $res2=mysql_query($sql2);
  $entrepot=mysql_fetch_array($res2);

  $rows = array();
  $rows[] = strtoupper($entrepot['nomEntrepot']);
  $rows[] = $stock['dateStockage'];
  $rows[] = $stock['quantiteStockinitial'];
  if($stock['quantiteStockCourant']!=0){
    $rows[] = ($stock['quantiteStockCourant'] / $design['nbreArticleUniteStock']);
  }
  else{
    $rows[] = $stock['quantiteStockCourant'];
  }
  $rows[] = '<input type="number" class="form-control" id="quantiteTransfert-'.$stock['idEntrepotStock'].'" min=1 value="" required=""/>';
  $rows[] = '<button type="button" onclick="ajt_TransfertStock('.$idEntrepotTransfert.','.$stock["idEntrepotStock"].')" id="btn_TransfertProduit-'.$stock['idEntrepotStock'].'" class="btn btn-success">
      <i class="glyphicon glyphicon-plus"></i>TRANSFERER
    </button>';

  $data[] = $rows;
  $i=$i + 1;
}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);


?>
