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
//echo  $_SESSION['etiquette'];  

require('connection.php');

require('declarationVariables.php');



$sql="select * from `".$nomtableDesignation."` order by idDesignation desc";
$res=mysql_query($sql);

$data=array();
$i=1;
while($design=mysql_fetch_array($res)){
  /*$rows = array();			
  $rows[] = $design['idDesignation'];
  $rows[] = $design['designation'];
  $rows[] = $design['categorie'];		
  $rows[] = $design['forme'];	
*/
  $rows = array();			
  $rows[] = $i;
  $rows[] = $design['designation'];
  $rows[] = $design['categorie'];		
  $rows[] = $design['forme'];
  $rows[] = $design['tableau'];
  $rows[] = $design['prixSession'];		
  $rows[] = $design['prixPublic'];
  $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Reference_Ph('.$design["idDesignation"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;&nbsp
    <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="mdf_Reference_Ph('.$design["idDesignation"].')"  data-toggle="modal" data-target="#imgsup'.$design["idDesignation"].'" /></a>
    <a data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span class="glyphicon glyphicon-barcode"></span></a>';
 

  $data[] = $rows;
  $i=$i + 1;
}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);



?>
