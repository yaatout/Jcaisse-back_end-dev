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



$sql="SELECT * from `".$nomtableMutuelle."` order by idMutuelle desc";
$res=mysql_query($sql);

$data=array();
$i=1;
while($mutuelle=mysql_fetch_array($res)){

  $sql12="SELECT SUM(apayerMutuelle) FROM `".$nomtableMutuellePagnet."` where idMutuelle=".$mutuelle['idMutuelle']." AND verrouiller=1 AND type=0 ";
  $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
  $TotalB = mysql_fetch_array($res12) ; 

  $sql13="SELECT SUM(montant) FROM `".$nomtableVersement."` where idMutuelle='".$mutuelle['idMutuelle']."' ";
  $res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());
  $TotalV = mysql_fetch_array($res13) ;
  
  $T_solde=$TotalB[0] - $TotalV[0];

  $rows = array();
  $rows[] = $i;	
  $rows[] = strtoupper($mutuelle['nomMutuelle']);	
  $rows[] = $mutuelle['tauxMutuelle'].' % - '.$mutuelle['taux1'].' % - '.$mutuelle['taux2'].' % - '.$mutuelle['taux3'].' % - '.$mutuelle['taux4'].' %';		
  $rows[] = strtoupper($mutuelle['adresseMutuelle']);
  $rows[] = strtoupper($mutuelle['telephoneMutuelle']);
  $rows[] = number_format($T_solde, 2, ',', ' ').' FCFA';
  /*
  if(mysql_num_rows($res1)){
    $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Mutuelle('.$mutuelle["idMutuelle"].','.$i.')"  /></a>&nbsp;
    <a href=bonMutuelle.php?iDS='.$mutuelle["idMutuelle"].'>Details </a>'; 
  }
  else{ */
    $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Mutuelle('.$mutuelle["idMutuelle"].','.$i.')"  /></a>&nbsp;
      <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Mutuelle('.$mutuelle["idMutuelle"].','.$i.')"  /></a>&nbsp
      <a href=bonMutuelle.php?iDS='.$mutuelle["idMutuelle"].'>Details </a>'; 
  //}
    
  $data[] = $rows;
  $i=$i + 1;
}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);


?>
