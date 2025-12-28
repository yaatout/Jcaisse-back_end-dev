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



$sql="select * from `".$nomtableDesignation."` where classe=0 order by idDesignation desc";
$res=mysql_query($sql);

$data=array();
$i=1;
while($design=mysql_fetch_array($res)){

  $sql1="SELECT * FROM `". $nomtableStock."` where idDesignation='".$design['idDesignation']."'";
  $res1=mysql_query($sql1);

  $rows = array();
  if($i==1){
    $rows[] = $i;
    $rows[] = '<span style="color:blue;">'.strtoupper($design['designation']).'</span>';
    $rows[] = '<span style="color:blue;">'.strtoupper($design['codeBarreDesignation']).'</span>';		
    $rows[] = '<span style="color:blue;">'.strtoupper($design['uniteStock']).'</span>';
    $rows[] = '<span style="color:blue;">'.$design['nbreArticleUniteStock'].'</span>';
    $rows[] = '<span style="color:blue;">'.$design['prixuniteStock'].'</span>';	
    $rows[] = '<span style="color:blue;">'.$design['prix'].'</span>';	
    $rows[] = '<span style="color:blue;">'.$design['prixachat'].'</span>';
    $rows[] = '<span style="color:blue;">'.$design['categorie'].'</span>';
  }	
  else{
    $rows[] = $i;
    $rows[] = strtoupper($design['designation']);
    $rows[] = strtoupper($design['codeBarreDesignation']);		
    $rows[] = strtoupper($design['uniteStock']);
    $rows[] = $design['nbreArticleUniteStock'];
    $rows[] = $design['prixuniteStock'];
    $rows[] = $design['prix'];
    $rows[] = $design['prixachat'];		
    $rows[] = $design['categorie'];
  }		

  if($_SESSION['proprietaire']==1){
    if($design["codeBarreDesignation"]!=null){
      $rows[] = '<a onclick="mdf_CodeBarreDesign('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span class="glyphicon glyphicon-barcode"></span></a>&nbsp;
      <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$design["idDesignation"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;
      <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Designation('.$design["idDesignation"].')"  data-toggle="modal" data-target="#imgsup'.$design["idDesignation"].'" /></a>';   
    }
    else{
      $rows[] = '<a onclick="mdf_CodeBarreDesign('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span style="color:red" class="glyphicon glyphicon-barcode"></span></a>&nbsp;
      <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$design["idDesignation"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;
      <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Designation('.$design["idDesignation"].')"  data-toggle="modal" data-target="#imgsup'.$design["idDesignation"].'" /></a>'; 
    }
  }
  else{
    if($design["codeBarreDesignation"]!=null){
      $rows[] = '<a onclick="mdf_CodeBarreDesign('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span class="glyphicon glyphicon-barcode"></span></a>&nbsp;
      <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$design["idDesignation"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;';  
    }
    else{
      $rows[] = '<a onclick="mdf_CodeBarreDesign('.$design["idDesignation"].','.$i.')" data-toggle="modal" data-target="#codeBP'.$design["idDesignation"].'"><span style="color:red" class="glyphicon glyphicon-barcode"></span></a>&nbsp;
      <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$design["idDesignation"].')" id="" data-toggle="modal" data-target="#imgmodifier'.$design["idDesignation"].'" /></a>&nbsp;'; 
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
