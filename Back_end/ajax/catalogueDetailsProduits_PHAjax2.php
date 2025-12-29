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

$id=0;
if (isset($_GET['id'])) {
	$id=@$_GET['id'];
} elseif (isset($_POST['id'])) {
	$id=@$_POST['id'];
}


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
$sql3="SELECT * from  `".$catalogueTypeCateg."` ORDER BY designation ASC ";


$res3=mysql_query($sql3);

$data=array();
$rows;
$i=1;

if (isset($_GET['operation'])) {
		$operation=@$_GET['operation'];
		if ($operation=='doublon') {
// tous les produit du cqtqlogue
// pour chaq prod fqire select COUNT
// si count sup 1
// alor rows
					while ($tab3 = mysql_fetch_array($res3)) {

								$sqlD="SELECT * FROM `".$catalogueTypeCateg."` WHERE designation ='".$tab3['designation']."' AND
								categorie ='".$tab3['categorie']."' AND forme ='".$tab3['forme']."' AND tableau ='".$tab3['tableau']."'
								AND prixSession ='".$tab3['prixSession']."' AND prixPublic ='".$tab3['prixPublic']."' AND
								codeBarreDesignation ='".$tab3['codeBarreDesignation']."' and id !=".$tab3['id']."";

								$resD = mysql_query($sqlD) or die ("persoonel requête 2".mysql_error());

								$nbreD = mysql_fetch_array($resD);
								if ($nbreD[0] >1 ) {
										$rows = array();
										$rows[] = strtoupper($tab3['designation']);
										$rows[] = strtoupper($tab3['idFusion']);
										$rows[] = strtoupper($tab3['categorie']);
										$rows[] = strtoupper($tab3['forme']);
										$rows[] = strtoupper($tab3['tableau']);
										$rows[] = strtoupper($tab3['prixSession']);
										$rows[] = strtoupper($tab3['prixPublic']);

										$rows[] = '<a><button type="button"  name="button" onclick="dbl_Designation_PH('.$tab3["id"].','.$id.','.$i.')">Doublon</button></a>';
										$data[] = $rows;
										$i = $i + 1;
								}
						}
		}
		elseif ($operation=='fusion') {

			while ($tab3 = mysql_fetch_array($res3)) {

						$sqlF="SELECT * FROM `".$catalogueTypeCateg."` WHERE (categorie != '".$tab3['categorie']."' OR forme != '".$tab3['forme']."'
	          OR tableau != '".$tab3['tableau']."' OR prixSession != '".$tab3['prixSession']."' OR prixPublic != '".$tab3['prixPublic']."' OR
						codeBarreDesignation != '".$tab3['codeBarreDesignation']."') AND designation ='".$tab3['designation']."'  and id !='".$tab3['id']."' ";

						$resF = mysql_query($sqlF) or die ("persoonel requête 2".mysql_error());

						$nbreF = mysql_fetch_array($resF);
						if ($nbreF[0] >1 ) {
								$rows = array();
								$rows[] = strtoupper($tab3['designation']);
								$rows[] = strtoupper($tab3['idFusion']);
								$rows[] = strtoupper($tab3['categorie']);
								$rows[] = strtoupper($tab3['forme']);
								$rows[] = strtoupper($tab3['tableau']);
								$rows[] = strtoupper($tab3['prixSession']);
								$rows[] = strtoupper($tab3['prixPublic']);

								$rows[] = '<a><button type="button"  name="button" onclick="fus_Designation_PH('.$tab3["id"].','.$id.','.$i.')">Fusion</button></a>';
								$data[] = $rows;
								$i = $i + 1;
						}
				}
		}
		elseif ($operation=='details') {
			$sql3="SELECT * from  `".$catalogueTypeCateg."` ORDER BY designation ASC ";
			$res3=mysql_query($sql3);
			while ($tab3 = mysql_fetch_array($res3)) {
						$rows = array();
						$rows[] = '<form class="form form-inline" role="form"  method="post" >
							<input type="text" class="form-control" id="designationD-'.$tab3['id'].'" min=1 value="'.$tab3['designation'].'" required=""/>';
						$rows[] = '<input type="text" class="form-control"  value="'.strtoupper($tab3['idFusion']).'" disabled/>'
						;

						// $categ='<select class="form-control" id="categorieD-'.$tab3['id'].'"  >
						// 	<option selected value= "'.$tab3['categorie'].'">'.$tab3['categorie'].'</option>';
						// 		 $sql11="SELECT * FROM `". $categorieTypeCateg."`";
						// 			$res11=mysql_query($sql11) or die ("insertion impossible Produit".mysql_error());
						// 			while($ligne2 = mysql_fetch_array($res11)) {
						// 					$categ=$categ.'<option  value= "'.$ligne2['nomCategorie'].'">'.$ligne2['nomCategorie'].'</option>';
						// 			}
						// $categ=$categ.'</select>';
						// $rows[] = 	$categ;

						// $frm='<select class="form-control" id="formeD-'.$tab3['id'].'"  >
						// 	<option selected value= "'.$tab3['forme'].'">'.$tab3['forme'].'</option>';
						// 		 $sql11="SELECT * from  `".$formeTypeCategPharmacie."` ";
						// 			$res11=mysql_query($sql11) or die ("insertion impossible Produit".mysql_error());
						// 			while($ligne2 = mysql_fetch_array($res11)) {
						// 					$frm=$frm.'<option  value= "'.$ligne2['nomForme'].'">'.$ligne2['nomForme'].'</option>';
						// 			}
						//
						// $frm=$frm.'</select>';


						$rows[] = ' <span onmouseover="selectCategorie('.$tab3["id"].','.$id.')"><select class="form-control" id="categorieD-'.$tab3['id'].'"  >
							<option selected value= "'.$tab3['categorie'].'"
							 >'.$tab3['categorie'].'</option></select></span>';

						$rows[] = ' <span onmouseover="selectForme('.$tab3["id"].','.$id.')"><select class="form-control" id="formeD-'.$tab3['id'].'"  >
							<option selected value= "'.$tab3['forme'].'"
							 >'.$tab3['forme'].'</option></select></span>';

						$rows[] = '<input type="text" class="form-control" id="tableauD-'.$tab3['id'].'" value="'.strtoupper($tab3['tableau']).'" required=""/>';
						$rows[] = '<input type="number" class="form-control" id="prixSessionD-'.$tab3['id'].'" min=0 value="'.strtoupper($tab3['prixSession']).'" required=""/>';
						$rows[] = '<input type="number" class="form-control" id="prixPublicD-'.$tab3['id'].'" min=0 value="'.strtoupper($tab3['prixPublic']).'" required=""/>
							</form>';


						// $rows[] = strtoupper($tab3['designation']);
						// $rows[] = strtoupper($tab3['idFusion']);
						// $rows[] = strtoupper($tab3['categorie']);
						// $rows[] = strtoupper($tab3['forme']);
						// $rows[] = strtoupper($tab3['tableau']);
						// $rows[] = strtoupper($tab3['prixSession']);
						// $rows[] = strtoupper($tab3['prixPublic']);

					if ($tab3["image"]) {
							if($tab3["codeBarreDesignation"]!=null){
							$rows[] = '<a onclick="mdf_Designation_PH('.$tab3["id"].','.$id.','.$i.')" id="pencilmoD-'.$tab3['id'].'"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
							<a onclick="spm_Designation_PH('.$tab3["id"].','.$id.','.$i.')" /></a>
							<a onclick="codeBR_Designation_PH('.$tab3["id"].','.$id.','.$i.')" id="iconeCBarre-'.$tab3['id'].'"><span class="glyphicon glyphicon-barcode"></span></a>
							<a><img src="images/iconfinder11.png" align="middle" alt="apperçu"  onclick="img_Designation_PH('.$tab3["id"].','.$id.','.$i.')" /></a>';
						}
							else{
							$rows[] = '<a onclick="mdf_Designation_PH('.$tab3["id"].','.$id.','.$i.')" id="pencilmoD-'.$tab3['id'].'"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
							<a onclick="spm_Designation_PH('.$tab3["id"].','.$id.','.$i.')" ><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
							<a onclick="codeBR_Designation_PH('.$tab3["id"].','.$id.','.$i.')" id="iconeCBarre-'.$tab3['id'].'"><span style="color:red" class="glyphicon glyphicon-barcode"></span></a>
							<a><img src="images/iconfinder11.png" align="middle" alt="apperçu"  onclick="img_Designation_PH('.$tab3["id"].','.$id.','.$i.')" /></a>';
						}
					}else{
						if($tab3["codeBarreDesignation"]!=null){
								$rows[] = '<a onclick="mdf_Designation_PH('.$tab3["id"].','.$id.','.$i.')" id="pencilmoD-'.$tab3['id'].'"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
								<a onclick="spm_Designation_PH('.$tab3["id"].','.$id.','.$i.')" ><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
								<a onclick="codeBR_Designation_PH('.$tab3["id"].','.$id.','.$i.')" id="iconeCBarre-'.$tab3['id'].'"><span class="glyphicon glyphicon-barcode"></span></a>
								<a><img src="images/iconfinder9.png" align="middle" alt="apperçu"  onclick="img_Designation_PH('.$tab3["id"].','.$id.','.$i.')" /></a>';
						}
						else{
								$rows[] = '<a onclick="mdf_Designation_PH('.$tab3["id"].','.$id.','.$i.')" id="pencilmoD-'.$tab3['id'].'"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
								<a onclick="spm_Designation_PH('.$tab3["id"].','.$id.','.$i.')" ><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
								<a onclick="codeBR_Designation_PH('.$tab3["id"].','.$id.','.$i.')" id="iconeCBarre-'.$tab3['id'].'"><span style="color:red" class="glyphicon glyphicon-barcode"></span></a>
								<a><img src="images/iconfinder9.png" align="middle" alt="apperçu"  onclick="img_Designation_PH('.$tab3["id"].','.$id.','.$i.')" /></a>';
						}
					}
					$data[] = $rows;
					$i = $i + 1;

			}
		}
		$results = ["sEcho" => 1,
		          "iTotalRecords" => count($data),
		          "iTotalDisplayRecords" => count($data),
		          "aaData" => $data ];

		echo json_encode($results);
}
if (isset($_POST['action'])) {
	if ($_POST['action']=='listeSelectForme') {
					$sql11="SELECT * from  `".$formeTypeCategPharmacie."` order by  nomForme";
					$res11=mysql_query($sql11) or die ("insertion impossible Produit".mysql_error());
					while($ligne2 = mysql_fetch_array($res11)) {
							$rows[]=$ligne2['nomForme'];
					}
					$data= $rows;
	} elseif ($_POST['action']=='listeSelectCategorie') {
					$sql11="SELECT * FROM `". $categorieTypeCateg."` order by  nomCategorie";
					 $res11=mysql_query($sql11) or die ("insertion impossible Produit".mysql_error());
					 while($ligne2 = mysql_fetch_array($res11)) {
							 $rows[]=$ligne2['nomCategorie'];
					 }
					$data= $rows;
	}


				echo json_encode($data);
				// exit('lq tqille est ='.count($data));
				// return $data;
}

?>
