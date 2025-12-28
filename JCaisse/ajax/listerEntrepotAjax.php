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


$sql="SELECT * from `".$nomtableEntrepot."` order by idEntrepot desc";
$res=mysql_query($sql);

$data=array();
$i=1;
while($entrepot=mysql_fetch_array($res)){

  //$sql2="select * from `".$nomtableStock."` where idBl='".$bl["idBl"]."' ";
  //$res2=mysql_query($sql2);

  $rows = array();
  $rows[] = $i;			
  $rows[] = strtoupper($entrepot['nomEntrepot']);
  $rows[] = strtoupper($entrepot['adresseEntrepot']);
  if($_SESSION['proprietaire']==1 || $_SESSION['gestionnaire']==1){ 
    $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Entrepot('.$entrepot["idEntrepot"].','.$i.')"  /></a>&nbsp;
    <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Entrepot('.$entrepot["idEntrepot"].','.$i.')"  /></a>&nbsp;
    <a style="color:blue" href=entrepotStock.php?iDS='.$entrepot["idEntrepot"].'>Details </a>&nbsp;
    <a style="color:brown" href=inventairePartiel-Entrepot.php?iDS='.$entrepot["idEntrepot"].'>Inventaire Partielle </a>&nbsp;
    <a style="color:gray" href=inventaireProduit.php?id='.$entrepot["idEntrepot"].'>Inventaire Annuel </a>';
  }
  else{
    $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Entrepot('.$entrepot["idEntrepot"].','.$i.')"  /></a>&nbsp;
    <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Entrepot('.$entrepot["idEntrepot"].','.$i.')"  /></a>&nbsp;
    <a href=entrepotStock.php?iDS='.$entrepot["idEntrepot"].'>Details </a>';
  }
 /* if($stock=mysql_fetch_array($res2)){
    $rows[] = '<a href=bl.php?id='.$bl["idBl"].'>Details </a>&nbsp;
    <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Bl('.$bl["idBl"].','.$i.')"  /></a>&nbsp'; 
  }
  else{
    $rows[] = '<a href=bl.php?id='.$bl["idBl"].'>Details </a>&nbsp;
    <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Bl('.$bl["idBl"].','.$i.')"  /></a>&nbsp;
    <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Bl('.$bl["idBl"].','.$i.')"  /></a>'; 
  }
  */
  $data[] = $rows;
  $i=$i + 1;
}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);


?>
