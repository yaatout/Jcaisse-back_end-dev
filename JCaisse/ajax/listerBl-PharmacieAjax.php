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



$sql="SELECT * from `".$nomtableBl."` order by idBl desc";
$res=mysql_query($sql);

$data=array();
$i=1;
while($bl=mysql_fetch_array($res)){

  $sql1="SELECT * FROM `". $nomtableFournisseur."` where idFournisseur='".$bl['idFournisseur']."' ";
  $res1=mysql_query($sql1);
  $fourn=mysql_fetch_array($res1);
  $sql2="select * from `".$nomtableStock."` where idBl='".$bl["idBl"]."' ";
  $res2=mysql_query($sql2);

  $rows = array();
  $rows[] = $i;			
  $rows[] = strtoupper($bl['numeroBl']);
  $rows[] = strtoupper($fourn['nomFournisseur']);		
  $rows[] = strtoupper($bl['dateBl']);
  $rows[] = strtoupper($bl['montantBl']);
  if($bl['image']!=null && $bl['image']!=' '){ 
      $format=substr($bl['image'], -3);
      if($format=='pdf'){ 
          $rows[] = '<img class="btn btn-xs" src="./images/pdf.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" data-target="#imageNvBl'.$bl['idBl'].'" onclick="showImageBl('.$bl['idBl'].')" />
          <input style="display:none;" id="imageBl'.$bl['idBl'].'"  value="'.$bl['image'].'" />';
      }
      else { 
        $rows[] = '<img class="btn btn-xs" src="./images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" data-target="#imageNvBl'.$bl['idBl'].'" onclick="showImageBl('.$bl['idBl'].')" />
        <input style="display:none;" id="imageBl'.$bl['idBl'].'"  value="'.$bl['image'].'" />';
      } 
  }
  else{ 
    $rows[] = '<img class="btn btn-xs" src="./images/upload.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" data-target="#imageNvBl'.$bl['idBl'].'" onclick="showImageBl('.$bl['idBl'].')" />';
  }
  if($stock=mysql_fetch_array($res2)){
    $rows[] = '<a href=bl.php?id='.$bl["idBl"].'>Details </a>&nbsp;
    <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Bl('.$bl["idBl"].','.$i.')"  /></a>&nbsp'; 
  }
  else{
    $rows[] = '<a href=bl.php?id='.$bl["idBl"].'>Details </a>&nbsp;
    <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Bl('.$bl["idBl"].','.$i.')"  /></a>&nbsp;
    <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Bl('.$bl["idBl"].','.$i.')"  /></a>'; 
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
