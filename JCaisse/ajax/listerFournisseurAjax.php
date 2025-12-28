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



$sql="SELECT * from `".$nomtableFournisseur."` order by idFournisseur desc";
$res=mysql_query($sql);

$data=array();
$i=1;
while($fourn=mysql_fetch_array($res)){

  $sql1="SELECT * FROM `". $nomtableBl."` where idFournisseur='".$fourn['idFournisseur']."' ";
  $res1=mysql_query($sql1);

  $sql12="SELECT SUM(montantBl) FROM `".$nomtableBl."` where idFournisseur='".$fourn['idFournisseur']."' ";
  $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
  $TotalB = mysql_fetch_array($res12) ; 

  $sql13="SELECT SUM(montant) FROM `".$nomtableVersement."` where idFournisseur='".$fourn['idFournisseur']."' ";
  $res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());
  $TotalV = mysql_fetch_array($res13) ;
  
  $T_solde=$TotalB[0] - $TotalV[0];
 
  $rows = array();
  $rows[] = $i;	
  $rows[] = strtoupper($fourn['nomFournisseur']);		
  $rows[] = strtoupper($fourn['adresseFournisseur']);
  $rows[] = strtoupper($fourn['telephoneFournisseur']);
  $rows[] = strtoupper($fourn['banqueFournisseur']);
  $rows[] = strtoupper($fourn['numBanqueFournisseur']);
  $rows[] = number_format($T_solde, 2, ',', ' ').' FCFA';
  if(mysql_num_rows($res1)){
    $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Fournisseur('.$fourn["idFournisseur"].','.$i.')"  /></a>&nbsp;
    <a href=bonFournisseur.php?iDS='.$fourn["idFournisseur"].'>Details </a>'; 
  }
  else{
    $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Fournisseur('.$fourn["idFournisseur"].','.$i.')"  /></a>&nbsp;
      <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Fournisseur('.$fourn["idFournisseur"].','.$i.')"  /></a>&nbsp
      <a href=bonFournisseur.php?iDS='.$fourn["idFournisseur"].'>Details </a>'; 
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
