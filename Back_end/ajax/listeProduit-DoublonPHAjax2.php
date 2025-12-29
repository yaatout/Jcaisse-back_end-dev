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


require('../connection.php');
require('../declarationVariables.php');

$catalogueTotal='';
$type='';
$categorie='';
$typeCategorie='';
$catalogueTypeCateg='';
$doublons=NULL;
$fusions=NULL;
$catCatParent=NULL;

if (isset($_GET['operation']) and $_GET['operation']==1) {



}elseif (isset($_GET['idCT']) and !isset($_GET['operation'])) {
		$idCT=@$_GET['idCT'];
		$idD=@$_GET['idD'];


		$sql0="SELECT * from  `aaa-catalogueTotal`  where id=".$idCT;
			 $res0=mysql_query($sql0);
			 if ($res0) {
			 	// code...
				$tab0=mysql_fetch_array($res0);
				$catalogueTotal='aaa-catalogueTotal';
				$type=$tab0['type'];
		    $categorie=$tab0['categorie'];
		    $typeCategorie=$tab0['type']."-".$tab0['categorie'];
				$catalogueTypeCateg='aaa-catalogue-'.$typeCategorie;
				$categorieTypeCateg='aaa-categorie-'.$typeCategorie;
				$formeTypeCategPharmacie='aaa-forme-'.$typeCategorie;
				$tableauTypeCategPharmacie='aaa-tableau-'.$typeCategorie;
			} else {
				// code...
				$tab0=0;
			}


	  $typeCategorie=$tab0['type']."-".$tab0['categorie'];
	  $catalogueTypeCateg='aaa-catalogue-'.$typeCategorie;

		$data=array();
		$i=1;

	  $sql13="SELECT * from  `".$catalogueTypeCateg."` where id='".$idD."'";
	  $res13 = mysql_query($sql13) or die ("persoonel requête 3".mysql_error());
	  $cat = mysql_fetch_assoc($res13);


	  $sqlD="SELECT * FROM `".$catalogueTypeCateg."`
	          WHERE designation ='".$cat['designation']."' && categorie ='".$cat['categorie']."' && forme ='".$cat['forme']."'
	          && tableau ='".$cat['tableau']."' && prixSession ='".$cat['prixSession']."' && prixPublic ='".$cat['prixPublic']."' && codeBarreDesignation ='".$cat['codeBarreDesignation']."' ";
	  //$sqlD="SELECT * FROM `".$catalogueTypeCateg."` where designation ='".$cat['designation']."' and id!='.$idD.'";
	  //$sqlD="SELECT * FROM `".$catalogueTypeCateg."` where designation ='".$cat['designation']."'";
		$tabIdFusionDoub=NULL;
		$tabDoublon=NULL;
		if ($resD = mysql_query($sqlD)) {
				while($t=mysql_fetch_array($resD)){
						$tabIdFusionDoub[] =$t['idFusion'];
						$tabDoublon[] =$t['id'];

	          $rows = array();
						$rows[] = strtoupper($t["designation"]);
					  $rows[] = strtoupper($t['idFusion']);
						$rows[] = strtoupper($t["categorie"]);
						$rows[] = strtoupper($t["forme"]);
						$rows[] = strtoupper($t["tableau"]);
						$rows[] = strtoupper($t["prixSession"]);
						$rows[] = strtoupper($t["prixPublic"]);
	           $data[] = $rows;
					}
				}
	      $results = ["sEcho" => 1,
	          "iTotalRecords" => count($data),
	          "iTotalDisplayRecords" => count($data),
	          "aaData" => $data ];

	echo json_encode($results);
}
 ?>
