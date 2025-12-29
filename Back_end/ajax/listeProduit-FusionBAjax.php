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


if (isset($_GET['idCT'])) {
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
		$rows ;
		$i=1;

	  $sql13="SELECT * from  `".$catalogueTypeCateg."` where id='".$idD."'";
	  $res13 = mysql_query($sql13) or die ("persoonel requête 3".mysql_error());
	  $tab3 = mysql_fetch_assoc($res13);


	  $sqlD="SELECT * FROM `".$catalogueTypeCateg."` WHERE (categorie != '".$tab3['categorie']."' OR uniteStock != '".$tab3['uniteStock']."'
		OR nbreArticleUniteStock != '".$tab3['nbreArticleUniteStock']."' OR prixuniteStock != '".$tab3['prixuniteStock']."' OR prix != '".$tab3['prix']."' 
		OR prixAchat != '".$tab3['prixAchat']."' OR	codeBarreDesignation != '".$tab3['codeBarreDesignation']."') AND designation ='".$tab3['designation']."' ";
	  /* $sqlD="SELECT * FROM `".$catalogueTypeCateg."` WHERE (categorie != '".$tab3['categorie']."' OR forme != '".$tab3['forme']."'
		 			OR tableau != '".$tab3['tableau']."' OR prixSession != '".$tab3['prixSession']."' OR prixPublic != '".$tab3['prixPublic']."' OR
		 			codeBarreDesignation != '".$tab3['codeBarreDesignation']."') AND designation ='".$tab3['designation']."'  and id !='".$tab3['id']."' ";*/
					
					 $ca=1;
					 $un=1;
					 $nb=1;
					 $pus=1;
					 $pu=1;
					 $pra=1;
					 $co=1;
	  $tabIdFusionDoub=NULL;
		$tabDoublon=NULL;
		$i=1;
		$resD = mysql_query($sqlD);
		if (mysql_num_rows($resD)) {
						$rows = array();
						$rows[] ='<form class="form form-inline" role="form"  method="post" >
								<input type="hidden" class="designationCl" name="Bout" value="'.$tab3["designation"].'" id="designationBO-'.$tab3['id'].'" /> <label >'.$tab3["designation"].'</label>';
						$rows[] ='<input type="checkbox" class="categorie" name="Bout" value="'.$tab3["categorie"].'" id="categorieBO-'.$tab3['id'].'" checked/> <label >'.$tab3["categorie"].'</label>';
						$rows[] ='<input type="checkbox" class="uniteStock" name="Bout" value="'.$tab3["uniteStock"].'" id="uniteStockBO-'.$tab3['id'].'" checked/> <label >'.$tab3["uniteStock"].'</label>';
						$rows[] ='<input type="checkbox" class="nbreArticleUniteStock" name="Bout" value="'.$tab3["nbreArticleUniteStock"].'" id="nbreArticleUniteStockBO-'.$tab3['id'].'" checked/> <label >'.$tab3["nbreArticleUniteStock"].'</label>';
						$rows[] ='<input type="checkbox" class="prixuniteStock" name="Bout" value="'.$tab3["prixuniteStock"].'" id="prixuniteStockBO-'.$tab3['id'].'" checked/> <label >'.$tab3["prixuniteStock"].'</label>';
						$rows[] ='<input type="checkbox" class="prix" name="Bout" value="'.$tab3["prix"].'" id="prixBO-'.$tab3['id'].'" checked/> <label >'.$tab3["prix"].'</label>';
						$rows[] ='<input type="checkbox" class="prixAchat" name="Bout" value="'.$tab3["prixAchat"].'" id="prixAchatBO-'.$tab3['id'].'" checked/> <label >'.$tab3["prixAchat"].'</label>';
						$rows[] ='<input type="checkbox" class="codeBarreDesignation" name="Bout" value="'.$tab3["codeBarreDesignation"].'" id="codeBarreDesignationBO-'.$tab3['id'].'" checked/> <label >'.$tab3["codeBarreDesignation"].'</label>
											</form>';
						$data[] = $rows;
					while($t=mysql_fetch_array($resD)){

						$rows = array();
						
						if($tab3["categorie"]!=$t["categorie"]){$ca=0;$data[0][1]='<input type="checkbox" class="categorie" name="Bout" value="'.$tab3["categorie"].'" id="categorieBO-'.$tab3['id'].'" /> <label >'.$tab3["categorie"].'</label>';}
						if($tab3["uniteStock"]!=$t["uniteStock"]){$un=0; $data[0][2]='<input type="checkbox" class="uniteStock" name="Bout" value="'.$tab3["uniteStock"].'" id="uniteStockBO-'.$tab3['id'].'" /> <label >'.$tab3["uniteStock"].'</label>';}
						if($tab3["nbreArticleUniteStock"]!=$t["nbreArticleUniteStock"]){$nb=0; $data[0][3]='<input type="checkbox" class="nbreArticleUniteStock" name="Bout" value="'.$tab3["nbreArticleUniteStock"].'" id="nbreArticleUniteStockBO-'.$tab3['id'].'" /> <label >'.$tab3["nbreArticleUniteStock"].'</label>';}
						if($tab3["prixuniteStock"]!=$t["prixuniteStock"]){$pus=0; $data[0][4]='<input type="checkbox" class="prixuniteStock" name="Bout" value="'.$tab3["prixuniteStock"].'" id="prixuniteStockBO-'.$tab3['id'].'" /> <label >'.$tab3["prixuniteStock"].'</label>';}
						if($tab3["prix"]!=$t["prix"]){$pu=0; $data[0][5]='<input type="checkbox" class="prix" name="Bout" value="'.$tab3["prix"].'" id="prixBO-'.$tab3['id'].'" /> <label >'.$tab3["prix"].'</label>';}
						if($tab3["prixAchat"]!=$t["prixAchat"]){$pra=0; $data[0][6]='<input type="checkbox" class="prixAchat" name="Bout" value="'.$tab3["prixAchat"].'" id="prixAchatBO-'.$tab3['id'].'" /> <label >'.$tab3["prixAchat"].'</label>';}
						if($tab3["codeBarreDesignation"]!=$t["codeBarreDesignation"]){$co=0; $data[0][7]='<input type="checkbox" class="codeBarreDesignation" name="Bout" value="'.$tab3["codeBarreDesignation"].'" id="codeBarreDesignationBO-'.$tab3['id'].'" /> <label >'.$tab3["codeBarreDesignation"].'</label>
						 </form>';}

						$rows[] ='<form class="form form-inline" role="form"  method="post" >
							<input type="hidden" class="designation" name="Bout" value="'.$t["designation"].'" id="designationBO-'.$t['id'].'" /> <label >'.$t["designation"].'</label>';
							if ($ca==0) {
								$rows[] ='<input type="checkbox" class="categorie" name="Bout"   value="'.$t["categorie"].'" id="categorieBO-'.$t['id'].'" /> <label >'.$t["categorie"].'</label>';
							} else {
								$rows[] ='<input type="checkbox" class="categorie" name="Bout" disabled="true" value="'.$t["categorie"].'" id="categorieBO-'.$t['id'].'" checked="true" /> <label >'.$t["categorie"].'</label>';
							}
							
						if ($ca==0) {
							$rows[] ='<input type="checkbox" class="uniteStock" name="Bout" value="'.$t["uniteStock"].'" id="uniteStockBO-'.$t['id'].'" /> <label >'.$t["uniteStock"].'</label>';
							}else {
								$rows[] ='<input type="checkbox" class="uniteStock" name="Bout" value="'.$t["uniteStock"].'" id="uniteStockBO-'.$t['id'].'" disabled="true" checked="true" /> <label >'.$t["uniteStock"].'</label>';
							
							}
						if ($ca==0) {
							$rows[] ='<input type="checkbox" class="nbreArticleUniteStock" name="Bout" value="'.$t["nbreArticleUniteStock"].'" id="nbreArticleUniteStockBO-'.$t['id'].'" /> <label >'.$t["nbreArticleUniteStock"].'</label>';
							}else {
								$rows[] ='<input type="checkbox" class="nbreArticleUniteStock" name="Bout" value="'.$t["nbreArticleUniteStock"].'" id="nbreArticleUniteStockBO-'.$t['id'].'" disabled="true" checked="true" /> <label >'.$t["nbreArticleUniteStock"].'</label>';
							
							}
						if ($ca==0) {
							$rows[] ='<input type="checkbox" class="prixuniteStock" name="Bout" value="'.$t["prixuniteStock"].'" id="prixuniteStockBO-'.$t['id'].'" /> <label >'.$t["prixuniteStock"].'</label>';
							}else {
								$rows[] ='<input type="checkbox" class="prixuniteStock" name="Bout" value="'.$t["prixuniteStock"].'" id="prixuniteStockBO-'.$t['id'].'" disabled="true" checked="true" /> <label >'.$t["prixuniteStock"].'</label>';
							
							}
						if ($ca==0) {
							$rows[] ='<input type="checkbox" class="prix" name="Bout" value="'.$t["prix"].'" id="prixBO-'.$t['id'].'" /> <label >'.$t["prix"].'</label>';
							}else {
								$rows[] ='<input type="checkbox" class="prix" name="Bout" value="'.$t["prix"].'" id="prixBO-'.$t['id'].'" disabled="true" checked="true" /> <label >'.$t["prix"].'</label>';
								
							}
						if ($ca==0) {
							$rows[] ='<input type="checkbox" class="prixAchat" name="Bout" value="'.$t["prixAchat"].'" id="prixAchatBO-'.$t['id'].'" /> <label >'.$t["prixAchat"].'</label>';
							}else {
								$rows[] ='<input type="checkbox" class="prixAchat" name="Bout" value="'.$t["prixAchat"].'" id="prixAchatBO-'.$t['id'].'" disabled="true" checked="true" /> <label >'.$t["prixAchat"].'</label>';
							
							}
						if ($ca==0) {
							$rows[] ='<input type="checkbox" class="codeBarreDesignation" name="Bout" value="'.$t["codeBarreDesignation"].'" id="codeBarreDesignationBO-'.$t['id'].'" /> <label >'.$t["codeBarreDesignation"].'</label>
											</form>';
							}else {
								$rows[] ='<input type="checkbox" class="codeBarreDesignation" name="Bout" value="'.$t["codeBarreDesignation"].'" id="codeBarreDesignationBO-'.$t['id'].'" disabled="true" checked="true" /> <label >'.$t["codeBarreDesignation"].'</label>
								</form>';
							}
				          $data[] = $rows;
				           // $data[] = $i." =";
						 $i++;
					}
			}
	      $results = ["sEcho" => 1,
	          "iTotalRecords" => count($data),
	          "iTotalDisplayRecords" => count($data),
	          "aaData" => $data ];

	echo json_encode($results);
}
 ?>
