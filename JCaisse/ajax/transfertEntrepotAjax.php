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


$sql="SELECT * from `".$nomtableEntrepotStock."` 
where idDesignation='".$idDesignation."' and idEntrepotTransfert='".$idEntrepotTransfert."' order by idEntrepotStock desc";
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

  $sql4="SELECT * FROM `". $nomtableEntrepotStock."` where idEntrepotStock='".$stock['idTransfert']."'";
  $res4=mysql_query($sql4);
  $transfert=mysql_fetch_array($res4);

  $sql5="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$transfert['idEntrepot']."'";
  $res5=mysql_query($sql5);
  $depot=mysql_fetch_array($res5);

  $rows = array();
  $rows[] = strtoupper($entrepot['nomEntrepot']);
  $rows[] = ' <= '.strtoupper($depot['nomEntrepot']);
  $rows[] = $stock['quantiteStockinitial'];
  if($stock['quantiteStockCourant']!=0){
    $rows[] = ($stock['quantiteStockCourant'] / $design['nbreArticleUniteStock']);
  }
  else{
    $rows[] = $stock['quantiteStockCourant'];
  }
  $rows[] = $stock['dateStockage'];
  $data[] = $rows;
  $i=$i + 1;
}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);


?>
