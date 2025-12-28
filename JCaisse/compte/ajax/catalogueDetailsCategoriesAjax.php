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

$id=@$_GET['id'];

$sql0="SELECT * from  `aaa-catalogueTotal`  where id=".$id;
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


  $sql15="SELECT * from  `".$categorieTypeCateg."` ";
  $res15=mysql_query($sql15);

$data=array();
$i=1;

while($tab5=mysql_fetch_array($res15)){

  $rows = array();
	$rows[] = $i;
  $rows[] = strtoupper($tab5['nomCategorie']);
  $rows[] = strtoupper($tab5['categorieParent']);
  $rows[] = '<a onclick="mdf_Categorie_PH('.$tab5["id"].','.$id.','.$i.')"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
						<a onclick="spm_Categorie_PH('.$tab5["id"].','.$id.','.$i.')"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>';
  $data[] = $rows;
$i= $i + 1;
}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);


?>
