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



$sql="select * from `".$nomtableCategorie."` order by nomcategorie, categorieParent";
$res=mysql_query($sql);

$data=array();
$i=1;
while($categorie=mysql_fetch_array($res)){

  $sql1="SELECT * FROM `".$nomtableCategorie."` where idcategorie='".$categorie['categorieParent']."' ";
  $res1=mysql_query($sql1);
  $parent=mysql_fetch_array($res1);

  $rows = array();	
  $rows[] = $i;
  $rows[] = strtoupper($parent['nomcategorie']);
  $rows[] = strtoupper($categorie['nomcategorie']);	
  
  if ($categorie["image"]) { 

    $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Categorie('.$categorie["idcategorie"].','.$i.')" data-toggle="modal"    /></a>&nbsp;
      <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Categorie('.$categorie["idcategorie"].','.$i.')" data-toggle="modal"    /></a>&nbsp
      <a><img src="images/iconfinder11.png" align="middle" alt="apperçu" onclick="imgEX_Categorie('.$categorie["idcategorie"].')" data-toggle="modal" data-target="#app'.$categorie["idcategorie"].'" /></a>&nbsp;'; 
  }
  else{
    if ($parent["idcategorie"]) {
      # code...

      $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Categorie('.$categorie["idcategorie"].','.$i.')" data-toggle="modal"    /></a>&nbsp;
      <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Categorie('.$categorie["idcategorie"].','.$i.')" data-toggle="modal"    /></a>&nbsp
      <a hidden="true"><img id="'.$categorie["idcategorie"].'" src="images/iconfinder9.png" align="middle" alt="img" onclick="imgNV_Categorie('.$categorie["idcategorie"].')" data-toggle="modal" data-target="#img'.$categorie["idcategorie"].'" /></a>&nbsp;';
    } else {
      # code...

      $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Categorie('.$categorie["idcategorie"].','.$i.')" data-toggle="modal"    /></a>&nbsp;
      <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Categorie('.$categorie["idcategorie"].','.$i.')" data-toggle="modal"    /></a>&nbsp
      <a><img id="'.$categorie["idcategorie"].'" src="images/iconfinder9.png" align="middle" alt="img" onclick="imgNV_Categorie('.$categorie["idcategorie"].')" data-toggle="modal" data-target="#img'.$categorie["idcategorie"].'" /></a>&nbsp;';
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
