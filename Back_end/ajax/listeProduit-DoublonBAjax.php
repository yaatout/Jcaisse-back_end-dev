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

if (isset($_GET['operation']) and isset($_GET['operation']) AND $_GET['operation']==1) {

	if ($_GET['tache']=='eliminerTDoub') {
		/*************************************/
	   $sql0="SELECT * from  `aaa-catalogueTotal`  where id='".$_GET['idCT']."'";
	   //var_dump($sql0);
		 $res0=mysql_query($sql0);
		 	// code...
			$tab0=mysql_fetch_array($res0);
			$catalogueTotal='aaa-catalogueTotal';
			$type=$tab0['type'];
		    $categorie=$tab0['categorie'];
		    $typeCategorie=$tab0['type']."-".$tab0['categorie'];
			$catalogueTypeCateg='aaa-catalogue-'.$typeCategorie;
			
		  	$sql2="SELECT *,COUNT(*) AS total_row   FROM `".$catalogueTypeCateg."`
				GROUP BY designation, categorie, uniteStock,nbreArticleUniteStock,prixuniteStock,prix,codeBarreDesignation HAVING COUNT(*) > 1";
			$res2=mysql_query($sql2);
			//var_dump($sql2);
			while (  $distinct = mysql_fetch_assoc($res2)) {
		  		
		      			$sql3="SELECT * FROM `".$catalogueTypeCateg."`
		    					WHERE designation ='".$distinct['designation']."' && categorie ='".$distinct['categorie']."' && uniteStock ='".$distinct['uniteStock']."'
		    					&& nbreArticleUniteStock ='".$distinct['nbreArticleUniteStock']."' && prixuniteStock ='".$distinct['prixuniteStock']."' && prix ='".$distinct['prix']."'
		              			 && codeBarreDesignation ='".$distinct['codeBarreDesignation']."' and id !=".$distinct["id"]."";
						 	$res3=mysql_query($sql3);
						 	if(mysql_num_rows($res3)) {
								while($t=mysql_fetch_array($res3)){
				                    $sql1="DELETE FROM `".$catalogueTypeCateg."` WHERE id=".$t["id"];
				                      //var_dump($sql1);
				    				  $res1=@mysql_query($sql1) or die ("suppression impossible designation".mysql_error());

								}
							}
			
		}
			
    			
	}elseif ($_GET['tache']=='eliminerDoub') {
		
		$id         =@$_GET["id"];
		$idCT         =@$_GET["idCT"];

		$idFusion         =null;
		$idDesignation    =null;
		$tabIdFusionDoub  =null;
  	$tabDoublon       =NULL;
		//$tabDoublon =explode('-',$tabDoublon);

  		$sql0="SELECT * from  `aaa-catalogueTotal`  where id='".$_GET['idCT']."'";
		 $res0=mysql_query($sql0);
		 	// code...
			$tab0=mysql_fetch_array($res0);
			$catalogueTotal='aaa-catalogueTotal';
			$type=$tab0['type'];
		    $categorie=$tab0['categorie'];
		    $typeCategorie=$tab0['type']."-".$tab0['categorie'];
			$catalogueTypeCateg='aaa-catalogue-'.$typeCategorie;
			

    $sql13="SELECT * from  `".$catalogueTypeCateg."` where id='".$id."'";
  	$res13 = mysql_query($sql13) or die ("persoonel requête 3".mysql_error());
  	$cat = mysql_fetch_assoc($res13);
  	//var_dump($sql13);
    $idFusion=$cat['idFusion'];
    $designation=$cat['designation'];

    $maxIdFusion=$idFusion;

    if ($idFusion==0) {
        $sql6="SELECT MAX(idFusion) FROM `".$catalogueTypeCateg."`
    					WHERE designation ='".$cat['designation']."' && categorie ='".$cat['categorie']."' && uniteStock ='".$cat['uniteStock']."'
    					&& nbreArticleUniteStock ='".$cat['nbreArticleUniteStock']."' && prixuniteStock ='".$cat['prixuniteStock']."' && prix ='".$cat['prix']."'
               && codeBarreDesignation ='".$cat['codeBarreDesignation']."' and id !=".$id."";
		
		if ($res6=mysql_query($sql6)) {
          $t6=mysql_fetch_array($res6);
          $maxIdFusion=$t6[0];
          //var_dump($maxIdFusion);
           $sql21="update `".$catalogueTypeCateg."` set idFusion='".mysql_real_escape_string($maxIdFusion)."'
           where id=".$id;
           $res21=@mysql_query($sql21)or die ("modification impossible1 ".mysql_error());
      }

    }
      $sql="SELECT * FROM `".$catalogueTypeCateg."`
    					WHERE designation ='".$cat['designation']."' && categorie ='".$cat['categorie']."' && uniteStock ='".$cat['uniteStock']."'
    					&& nbreArticleUniteStock ='".$cat['nbreArticleUniteStock']."' && prixuniteStock ='".$cat['prixuniteStock']."' && prix ='".$cat['prix']."'
               && codeBarreDesignation ='".$cat['codeBarreDesignation']."' and id !=".$id."";
               	//var_dump($sql);
				  if($res=mysql_query($sql)) {
					while($t=mysql_fetch_array($res)){
							$sql2="SELECT idFusion,nomBoutique FROM `".$catalogueTypeCateg."` A
									INNER JOIN `aaa-boutique` B
									ON A.idBoutique = B.idBoutique
									WHERE A.idFusion =".$t['idFusion'];
									$res2=mysql_query($sql2);
	                   $t2=mysql_fetch_array($res2) ;
	                    if ($t2['nomBoutique'] =='' or $t2['nomBoutique'] =='NULL' ) {
	                      //echo "string1";
	                      $sql1="DELETE FROM `".$catalogueTypeCateg."` WHERE id=".$t["id"];
	                      	//var_dump($sql1);
	    							    $res1=@mysql_query($sql1) or die ("suppression impossible designation".mysql_error());
	                      // if ($cat['codeBarreDesignation']='' and $t['codeBarreDesignation']!='') {
	                      //     $sql21="update `".$catalogueTypeCateg."` set codeBarreDesignation='".$t['codeBarreDesignation']."'
	                      //     where id=".$id;
	                      //     $res21=@mysql_query($sql21)or die ("modification impossible1 ".mysql_error());
	                      // }

	                    } else {
                      //echo "string2";
    									$nomtableDesignation=$t2['nomBoutique']."-designation";
    									$sql1="DELETE FROM `".$catalogueTypeCateg."` WHERE id=".$t["id"];
                      //echo "string2 =".$sql1;
    							     $res1=@mysql_query($sql1) or die ("suppression impossible designation".mysql_error());
    									$sql21="update `".$nomtableDesignation."` set idFusion='".mysql_real_escape_string($maxIdFusion)."'
    									where idDesignation=".$t['idDesignation'];
                      //var_dump($sql21);
    									$res21=@mysql_query($sql21)or die ("modification impossible1 ".mysql_error());
                  		}

					 }
			 		}
	}
	

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
	          WHERE designation ='".$cat['designation']."' && categorie ='".$cat['categorie']."' && uniteStock ='".$cat['uniteStock']."'
	          && nbreArticleUniteStock ='".$cat['nbreArticleUniteStock']."' && prixuniteStock ='".$cat['prixuniteStock']."' && prix ='".$cat['prix']."'
              && codeBarreDesignation ='".$cat['codeBarreDesignation']."' and id!='.$idD.' ";
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
					  	$rows[] = $t['categorie'];
						$rows[] = strtoupper($t["uniteStock"]);
						$rows[] = strtoupper($t["nbreArticleUniteStock"]);
						$rows[] = strtoupper($t["prixuniteStock"]);
						$rows[] = $t["prix"];
						$rows[] = $t["codeBarreDesignation"];
	           			$data[] = $rows;
						/////////////////////////////
						
					}
			}
		      $results = ["sEcho" => 1,
		          "iTotalRecords" => count($data),
		          "iTotalDisplayRecords" => count($data),
		          "aaData" => $data ];

	echo json_encode($results);
}
 ?>
