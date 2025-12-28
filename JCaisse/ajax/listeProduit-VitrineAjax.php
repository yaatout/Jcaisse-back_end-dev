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


$sql="select * from `".$nomtableDesignation."` where classe=0 order by idDesignation desc";
$res=mysql_query($sql);

$data=array();
$i=1;
while($design=mysql_fetch_array($res)){

  $rows = array();
  $rows[] = $i;
  $rows[] = strtoupper($design['designation']);
  $rows[] = strtoupper($design['categorie']);		
  $rows[] = strtoupper($design['uniteStock']);
  $rows[] = $design['prix'];
  $rows[] = $design['prixuniteStock'];
  $rows[] = '<button type="button" onclick="ajt_vitrine('.$design["idDesignation"].')" id="btn_ajtVitrine-'.$design['idDesignation'].'"
   class="btn btn-success "><i class="glyphicon glyphicon-plus">
   </i>AJOUTER
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
