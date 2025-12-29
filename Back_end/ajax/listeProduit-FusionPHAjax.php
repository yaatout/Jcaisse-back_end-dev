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


	  $sqlD="SELECT * FROM `".$catalogueTypeCateg."` WHERE (categorie != '".$tab3['categorie']."' OR forme != '".$tab3['forme']."'
		OR tableau != '".$tab3['tableau']."' OR prixSession != '".$tab3['prixSession']."' OR prixPublic != '".$tab3['prixPublic']."' OR
		codeBarreDesignation != '".$tab3['codeBarreDesignation']."') AND designation ='".$tab3['designation']."' ";
	  /* $sqlD="SELECT * FROM `".$catalogueTypeCateg."` WHERE (categorie != '".$tab3['categorie']."' OR forme != '".$tab3['forme']."'
		 			OR tableau != '".$tab3['tableau']."' OR prixSession != '".$tab3['prixSession']."' OR prixPublic != '".$tab3['prixPublic']."' OR
		 			codeBarreDesignation != '".$tab3['codeBarreDesignation']."') AND designation ='".$tab3['designation']."'  and id !='".$tab3['id']."' ";*/
			$de=1;
			$ca=1;
			$fo=1;
			$ta=1;
			$ps=1;
			$pp=1;
			$co=1;
	  $tabIdFusionDoub=NULL;
		$tabDoublon=NULL;
		$i=1;
		$resD = mysql_query($sqlD);
		if (mysql_num_rows($resD)) {
						$rows = array();
						$rows[] ='<form class="form form-inline" role="form"  method="post" >
								<input type="hidden" class="designationrad" name="ph" value="'.$tab3["designation"].'" id="designation-'.$tab3['id'].'"  /> <label >'.$tab3["designation"].'</label>';
						$rows[] ='<input type="checkbox" class="categorierad" name="ph" value="'.$tab3["categorie"].'" id="categorie-'.$tab3['id'].'" checked="true" disabled="true" /> <label >'.$tab3["categorie"].'</label>';
						$rows[] ='<input type="checkbox" class="formerad" name="ph" value="'.$tab3["forme"].'" id="forme-'.$tab3['id'].'" checked="true" disabled="true" /> <label >'.$tab3["forme"].'</label>';
						$rows[] ='<input type="checkbox" class="tableaurad" name="ph" value="'.$tab3["tableau"].'" id="tableau-'.$tab3['id'].'" checked="true" disabled="true" /> <label >'.$tab3["tableau"].'</label>';
						$rows[] ='<input type="checkbox" class="prixSessionrad" name="ph" value="'.$tab3["prixSession"].'" id="prixSession-'.$tab3['id'].'" checked="true" disabled="true" /> <label >'.$tab3["prixSession"].'</label>';
						$rows[] ='<input type="checkbox" class="prixPublicrad" name="ph" value="'.$tab3["prixPublic"].'" id="prixPublic-'.$tab3['id'].'" checked="true" disabled="true" /> <label >'.$tab3["prixPublic"].'</label>';
						$rows[] ='<input type="checkbox" class="codeBarreDesignationrad" name="ph" value="'.$tab3["codeBarreDesignation"].'" id="codeBarreDesignation-'.$tab3['id'].'" checked="true" disabled="true" /> <label >'.$tab3["codeBarreDesignation"].'</label>
											</form>';
						$data[] = $rows;
					while($t=mysql_fetch_array($resD)){
						if($tab3["categorie"]!=$t["categorie"]){$ca=0; $data[0][1]='<input type="checkbox" class="categorierad" name="ph" value="'.$tab3["categorie"].'" id="categorie-'.$tab3['id'].'" /> <label >'.$tab3["categorie"].'</label>';}
						if($tab3["forme"]!=$t["forme"]){$fo=0; $data[0][2]='<input type="checkbox" class="formerad" name="ph" value="'.$tab3["forme"].'" id="forme-'.$tab3['id'].'"  /> <label >'.$tab3["forme"].'</label>';}
						if($tab3["tableau"]!=$t["tableau"]){$ta=0; $data[0][3]='<input type="checkbox" class="tableaurad" name="ph" value="'.$tab3["tableau"].'" id="tableau-'.$tab3['id'].'"  /> <label >'.$tab3["tableau"].'</label>';}
						if($tab3["prixSession"]!=$t["prixSession"]){$ps=0; $data[0][4]='<input type="checkbox" class="prixSessionrad" name="ph" value="'.$tab3["prixSession"].'" id="prixSession-'.$tab3['id'].'"  /> <label >'.$tab3["prixSession"].'</label>';}
						if($tab3["prixPublic"]!=$t["prixPublic"]){$pp=0; $data[0][5]='<input type="checkbox" class="prixPublicrad" name="ph" value="'.$tab3["prixPublic"].'" id="prixPublic-'.$tab3['id'].'"  /> <label >'.$tab3["prixPublic"].'</label>';}
						if($tab3["codeBarreDesignation"]!=$t["codeBarreDesignation"]){$co=0; $data[0][6]='<input type="checkbox" class="codeBarreDesignationrad" name="ph" value="'.$tab3["codeBarreDesignation"].'" id="codeBarreDesignation-'.$tab3['id'].'"  /> <label >'.$tab3["codeBarreDesignation"].'</label>
						 </form>';}
						$rows = array();
						$rows[] ='<form class="form form-inline" role="form"  method="post" >
						<input type="hidden" class="designationrad" name="ph" value="'.$t["designation"].'" id="designation-'.$t['id'].'" /> <label >'.$t["designation"].'</label>';				
						if ($ca==0) {
							$rows[] ='<input type="checkbox" class="categorierad" name="ph" value="'.$t["categorie"].'" id="categorie-'.$t['id'].'" /> <label >'.$t["categorie"].'</label>';						
							} else {
								$rows[] ='<input type="checkbox" class="categorierad" name="ph" value="'.$t["categorie"].'" id="categorie-'.$t['id'].'" disabled="true" /> <label >'.$t["categorie"].'</label>';						
							}
						if ($fo==0) {
							$rows[] ='<input type="checkbox" class="formerad" name="ph" value="'.$t["forme"].'" id="forme-'.$t['id'].'"  /> <label >'.$t["forme"].'</label>';
							}else {
								$rows[] ='<input type="checkbox" class="formerad" name="ph" value="'.$t["forme"].'" id="forme-'.$t['id'].'" disabled="true" /> <label >'.$t["forme"].'</label>';
							}
						if ($ta==0) {
							$rows[] ='<input type="checkbox" class="tableaurad" name="ph" value="'.$t["tableau"].'" id="tableau-'.$t['id'].'" /> <label >'.$t["tableau"].'</label>';
						}else {
							$rows[] ='<input type="checkbox" class="tableaurad" name="ph" value="'.$t["tableau"].'" id="tableau-'.$t['id'].'" disabled="true" /> <label >'.$t["tableau"].'</label>';
						}
						if ($ps==0) {	
							$rows[] ='<input type="checkbox" class="prixSessionrad" name="ph" value="'.$t["prixSession"].'" id="prixSession-'.$t['id'].'" /> <label >'.$t["prixSession"].'</label>';
							}else {
								$rows[] ='<input type="checkbox" class="prixSessionrad" name="ph" value="'.$t["prixSession"].'" id="prixSession-'.$t['id'].'" disabled="true" /> <label >'.$t["prixSession"].'</label>';						
							}
						if ($pp==0) {
							$rows[] ='<input type="checkbox" class="prixPublicrad" name="ph" value="'.$t["prixPublic"].'" id="prixPublic-'.$t['id'].'" /> <label >'.$t["prixPublic"].'</label>';
						}else {
							$rows[] ='<input type="checkbox" class="prixPublicrad" name="ph" value="'.$t["prixPublic"].'" id="prixPublic-'.$t['id'].'" disabled="true" /> <label >'.$t["prixPublic"].'</label>';
							
						}
						if ($co==0) {
							$rows[] ='<input type="checkbox" class="codeBarreDesignationrad" name="ph" value="'.$t["codeBarreDesignation"].'" id="codeBarreDesignation-'.$t['id'].'" /> <label >'.$t["codeBarreDesignation"].'</label>
											</form>';
							}else {
							$rows[] ='<input type="checkbox" class="codeBarreDesignationrad" name="ph" value="'.$t["codeBarreDesignation"].'" id="codeBarreDesignation-'.$t['id'].'" disabled="true"  /> <label >'.$t["codeBarreDesignation"].'</label>
								</form>';	
							}
				        $data[] = $rows;
				           // $data[] = $i." =";
						  
						
						 $i++;
					}
					
					/*if($ca=0){ $data[0][1]='<input type="checkbox" class="categorierad" name="ph" value="'.$tab3["categorie"].'" id="categorie-'.$tab3['id'].'" /> <label >'.$tab3["categorie"].'</label>';}
					if($fo=0){ $data[0][2]='<input type="checkbox" class="formerad" name="ph" value="'.$tab3["forme"].'" id="forme-'.$tab3['id'].'" /> <label >'.$tab3["forme"].'</label>';}
					if($ta=0){ $data[0][3]='<input type="checkbox" class="tableaurad" name="ph" value="'.$tab3["tableau"].'" id="tableau-'.$tab3['id'].'" /> <label >'.$tab3["tableau"].'</label>';}
					if($ps=0){ $data[0][4]='<input type="checkbox" class="prixSessionrad" name="ph" value="'.$tab3["prixSession"].'" id="prixSession-'.$tab3['id'].'" /> <label >'.$tab3["prixSession"].'</label>';}
					if($pp=0){ $data[0][5]='<input type="checkbox" class="prixPublicrad" name="ph" value="'.$tab3["prixPublic"].'" id="prixPublic-'.$tab3['id'].'" /> <label >'.$tab3["prixPublic"].'</label>';}
					if($co=0){ $data[0][6]='<input type="checkbox" class="codeBarreDesignationrad" name="ph" value="'.$tab3["codeBarreDesignation"].'" id="codeBarreDesignation-'.$tab3['id'].'" /> <label >'.$tab3["codeBarreDesignation"].'</label>
						</form>';}*/
			}
			//var_dump($data);
	      $results = ["sEcho" => 1,
	          "iTotalRecords" => count($data),
	          "iTotalDisplayRecords" => count($data),
	          "aaData" => $data ];

	echo json_encode($results);
}
 ?>
