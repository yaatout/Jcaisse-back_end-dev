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



$sql="SELECT * from `".$nomtableVoyage."` order by idVoyage desc";
$res=mysql_query($sql);

$data=array();
$i=1;
while($voyage=mysql_fetch_array($res)){

  $rows = array();
  $rows[] = $i;	
  $rows[] = strtoupper($voyage['destination']);		
  $rows[] = strtoupper($voyage['motif']);
  $rows[] = strtoupper($voyage['dateVoyage']);
  $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Voyage('.$voyage["idVoyage"].','.$i.')"  /></a>&nbsp;
  <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Voyage('.$voyage["idVoyage"].','.$i.')"  /></a>&nbsp
  <a href=importExportDetails.php?iDS='.$voyage["idVoyage"].'>Details </a>'; 
    
  $data[] = $rows;
  $i=$i + 1;
}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);


?>
