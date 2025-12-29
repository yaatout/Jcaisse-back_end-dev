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

if (isset($_POST['tache'])) {
    $tache=$_POST['tache'];
	if ($tache=='fusionerPH') {
       
		$idD         =@$_POST["id"];
		$idCT         =@$_POST["idCT"];

		// $designation    =@$_POST["designation"];
		$categorie      =@$_POST["categorie"];
		$forme          =@$_POST["forme"];
		$tableau 				=@$_POST["tableau"];
		$prixSession    =@$_POST["prixSession"];
		$prixPublic     =@$_POST["prixPublic"];
		$codeBarreDesignation     ="null";
        if (isset($_POST["codeBarreDesignation"])) {
        if ($_POST["codeBarreDesignation"]!=null) {
            $codeBarreDesignation     =@$_POST["codeBarreDesignation"];
        }
        }
        //
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

        $sql13="SELECT * from  `".$catalogueTypeCateg."` where id='".$idD."'";
        $res13 = mysql_query($sql13) or die ("persoonel requête 3".mysql_error());
        $tab3 = mysql_fetch_assoc($res13);

            $idFusion     =  $tab3["idFusion"] ;
        $designation    =$tab3["designation"];
        $maxIdFusion=$idFusion;
            
        if ($idFusion==0) {
            $sql6="SELECT max(idFusion) FROM `".$catalogueTypeCateg."` WHERE (categorie != '".$tab3['categorie']."' OR forme != '".$tab3['forme']."'
            OR tableau != '".$tab3['tableau']."' OR prixSession != '".$tab3['prixSession']."' OR prixPublic != '".$tab3['prixPublic']."' OR
            codeBarreDesignation != '".$tab3['codeBarreDesignation']."') AND designation ='".$tab3['designation']."'  and id !='".$idD."'";
                if ($res6=mysql_query($sql6)) {
                    $t6=mysql_fetch_array($res6);
                    $maxIdFusion=$t6[0];                    
                }
        }
		$sql="update `".$catalogueTypeCateg."` set designation='".mysql_real_escape_string($designation)."',
		categorie='".mysql_real_escape_string($categorie)."',
		forme='".mysql_real_escape_string($forme)."',
		tableau='".$tableau."',
		prixSession=".$prixSession.",
		idFusion=".$maxIdFusion.",
		prixPublic=".$prixPublic.",
		codeBarreDesignation='".$codeBarreDesignation."'
		where id=".$idD;

        // echo "<br/>".$sql;
		//var_dump($sql);
		$res=@mysql_query($sql)or die ("modification impossibleAAA ".mysql_error());

		$sql3="SELECT * FROM `".$catalogueTypeCateg."` WHERE (categorie != '".$tab3['categorie']."' OR forme != '".$tab3['forme']."'
		OR tableau != '".$tab3['tableau']."' OR prixSession != '".$tab3['prixSession']."' OR prixPublic != '".$tab3['prixPublic']."' OR
		codeBarreDesignation != '".$tab3['codeBarreDesignation']."') AND designation ='".$tab3['designation']."'  and id !='".$idD."'";
        // var_dump($sql);
        // echo "<br/>".$sql3;
          if (	$res3=mysql_query($sql3)) {
    				while($t=mysql_fetch_array($res3)){
    					$sql2="SELECT idFusion,nomBoutique FROM `".$catalogueTypeCateg."` A
    					INNER JOIN `aaa-boutique` B
    					ON A.idBoutique = B.idBoutique
    					WHERE A.idFusion =".$t["idFusion"];
              // echo "<br/>".$sql2;
    					$res2=mysql_query($sql2);
    					$t2=mysql_fetch_array($res2);
              if ($t2['nomBoutique'] =='' or $t2['nomBoutique'] =='NULL' ) {
                $sql1="DELETE FROM `".$catalogueTypeCateg."` WHERE id=".$t["id"];
      					$res1=@mysql_query($sql1) or die ("suppression impossible designation".mysql_error());
              }
              else {
                $nomtableDesignation=$t2['nomBoutique']."-designation";

      					$sql1="DELETE FROM `".$catalogueTypeCateg."` WHERE id=".$t["id"];
      					$res1=@mysql_query($sql1) or die ("suppression impossible designation".mysql_error());
      						// echo "<br/>".$sql1;
                // var_dump($sql1);

      					$sql21="update `".$nomtableDesignation."` set idFusion='".mysql_real_escape_string($maxIdFusion)."'
      					where idDesignation=".$t['idDesignation'];
      					$res21=@mysql_query($sql21)or die ("modification impossible1 ".mysql_error());
            }

				 }
			}
            exit('FUSPH');
    } else if ($tache=='fusionerPH2') {
       
		$idD         =@$_POST["id"];
		$idCT         =@$_POST["idCT"];

		// $designation    =@$_POST["designation"];
		$categorie      =@$_POST["categorie"];
		$forme          =@$_POST["forme"];
		$tableau 				=@$_POST["tableau"];
		$prixSession    =@$_POST["prixSession"];
		$prixPublic     =@$_POST["prixPublic"];
		$codeBarreDesignation     ="null";
        //if (isset($_POST["codeBarreDesignation"])) {
        //if ($_POST["codeBarreDesignation"]!=null) {
            $codeBarreDesignation     =@$_POST["codeBarreDesignation"];
       // }
        //}
        
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

        $sql13="SELECT * from  `".$catalogueTypeCateg."` where id='".$idD."'";
        $res13 = mysql_query($sql13) or die ("persoonel requête 3".mysql_error());
        $tab3 = mysql_fetch_assoc($res13);

            $idFusion     =  $tab3["idFusion"] ;
        $designation    =$tab3["designation"];
        $maxIdFusion=$idFusion;
            
        if ($idFusion==0) {
            $sql6="SELECT max(idFusion) FROM `".$catalogueTypeCateg."` WHERE (categorie != '".$tab3['categorie']."' OR forme != '".$tab3['forme']."'
            OR tableau != '".$tab3['tableau']."' OR prixSession != '".$tab3['prixSession']."' OR prixPublic != '".$tab3['prixPublic']."' OR
            designation != '".$tab3['designation']."') AND codeBarreDesignation ='".$tab3['codeBarreDesignation']."'  and id !='".$idD."'";
                if ($res6=mysql_query($sql6)) {
                    $t6=mysql_fetch_array($res6);
                    $maxIdFusion=$t6[0];                    
                }
        }
		$sql="update `".$catalogueTypeCateg."` set designation='".mysql_real_escape_string($designation)."',
		categorie='".mysql_real_escape_string($categorie)."',
		forme='".mysql_real_escape_string($forme)."',
		tableau='".$tableau."',
		prixSession=".$prixSession.",
		idFusion=".$maxIdFusion.",
		prixPublic=".$prixPublic.",
		codeBarreDesignation='".$codeBarreDesignation."'
		where id=".$idD;

        // echo "<br/>".$sql;
		//var_dump($sql);
		$res=@mysql_query($sql)or die ("modification impossibleAAA ".mysql_error());

		$sql3="SELECT * FROM `".$catalogueTypeCateg."` WHERE (categorie != '".$tab3['categorie']."' OR forme != '".$tab3['forme']."'
		OR tableau != '".$tab3['tableau']."' OR prixSession != '".$tab3['prixSession']."' OR prixPublic != '".$tab3['prixPublic']."' OR
		designation != '".$tab3['designation']."') AND codeBarreDesignation ='".$tab3['codeBarreDesignation']."'  and id !='".$idD."'";
        // var_dump($sql);
        // echo "<br/>".$sql3;
          if (	$res3=mysql_query($sql3)) {
    				while($t=mysql_fetch_array($res3)){
    					$sql2="SELECT idFusion,nomBoutique FROM `".$catalogueTypeCateg."` A
    					INNER JOIN `aaa-boutique` B
    					ON A.idBoutique = B.idBoutique
    					WHERE A.idFusion =".$t["idFusion"];
              // echo "<br/>".$sql2;
    					$res2=mysql_query($sql2);
    					$t2=mysql_fetch_array($res2);
              if ($t2['nomBoutique'] =='' or $t2['nomBoutique'] =='NULL' ) {
                $sql1="DELETE FROM `".$catalogueTypeCateg."` WHERE id=".$t["id"];
      					$res1=@mysql_query($sql1) or die ("suppression impossible designation".mysql_error());
              }
              else {
                $nomtableDesignation=$t2['nomBoutique']."-designation";

      					$sql1="DELETE FROM `".$catalogueTypeCateg."` WHERE id=".$t["id"];
      					$res1=@mysql_query($sql1) or die ("suppression impossible designation".mysql_error());
      						// echo "<br/>".$sql1;
                // var_dump($sql1);

      					$sql21="update `".$nomtableDesignation."` set idFusion='".mysql_real_escape_string($maxIdFusion)."'
      					where idDesignation=".$t['idDesignation'];
      					$res21=@mysql_query($sql21)or die ("modification impossible1 ".mysql_error());
            }

				 }
			}
            exit('FUSPH2');
    } 
	else if ($tache=='fusionerB'){
        $idD         =@$_POST["id"];
        $id         =@$_POST["id"];
		$idCT         =@$_POST["idCT"];

		$designation         =@$_POST["designation"];
		$uniteStock          =@$_POST["uniteStock"];
		$nbArticleUniteStock =@$_POST["nbreArticleUniteStock"];
		$prixUnitaire        =@$_POST["prix"];
		$categorie        =@$_POST["categorie"];
		$prixuniteStock      =@$_POST["prixuniteStock"];
        
		$codeBarreDesignation     ="null";
        if (isset($_POST["codeBarreDesignation"])) {
        if ($_POST["codeBarreDesignation"]!=null) {
            $codeBarreDesignation     =@$_POST["codeBarreDesignation"];
        }
        }
        //
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
        
        $sql13="SELECT * from  `".$catalogueTypeCateg."` where id='".$idD."'";
        $res13 = mysql_query($sql13) or die ("persoonel requête 3".mysql_error());
        $tab3 = mysql_fetch_assoc($res13);

            $idFusion     =  $tab3["idFusion"] ;
        $designation    =$tab3["designation"];
        $maxIdFusion=$idFusion;
            
        if ($idFusion==0) {
            $sql6="SELECT max(idFusion) FROM `".$catalogueTypeCateg."` WHERE (categorie != '".$tab3['categorie']."' OR uniteStock != '".$tab3['uniteStock']."'
            OR nbreArticleUniteStock != '".$tab3['nbreArticleUniteStock']."' OR prixuniteStock != '".$tab3['prixuniteStock']."' OR prix != '".$tab3['prix']."' 
             OR codeBarreDesignation != '".$tab3['codeBarreDesignation']."' OR  codeBarreDesignation != '".$tab3['codeBarreDesignation']."') AND designation ='".$tab3['designation']."'  and id !='".$idD."'";
                if ($res6=mysql_query($sql6)) {
                    $t6=mysql_fetch_array($res6);
                    $maxIdFusion=$t6[0];                    
                }
        }
		$sql="update `".$catalogueTypeCateg."` set designation='".mysql_real_escape_string($designation)."',
		categorie='".mysql_real_escape_string($categorie)."',
		uniteStock='".mysql_real_escape_string($uniteStock)."',
		nbreArticleUniteStock='".$nbArticleUniteStock."',
		prixuniteStock=".$prixuniteStock.",
        prix=".$prixUnitaire.",
		idFusion=".$maxIdFusion.",
		codeBarreDesignation='".$codeBarreDesignation."'
		where id=".$idD;

        // echo "<br/>".$sql;
		//var_dump($sql);
		$res=@mysql_query($sql)or die ("modification impossibleBOUUT  ".mysql_error());

		$sql3="SELECT * FROM `".$catalogueTypeCateg."` WHERE (categorie != '".$tab3['categorie']."' OR uniteStock != '".$tab3['uniteStock']."'
		OR nbreArticleUniteStock != '".$tab3['nbreArticleUniteStock']."' OR prixuniteStock != '".$tab3['prixuniteStock']."' OR prix != '".$tab3['prix']."' 
		OR prixAchat != '".$tab3['prixAchat']."' OR	codeBarreDesignation != '".$tab3['codeBarreDesignation']."') AND designation ='".$tab3['designation']."' and id !='".$idD."'";
        // var_dump($sql);
        // echo "<br/>".$sql3;
          if (	$res3=mysql_query($sql3)) {
    				while($t=mysql_fetch_array($res3)){
    					$sql2="SELECT idFusion,nomBoutique FROM `".$catalogueTypeCateg."` A
    					INNER JOIN `aaa-boutique` B
    					ON A.idBoutique = B.idBoutique
    					WHERE A.idFusion =".$t["idFusion"];
              // echo "<br/>".$sql2;
    					$res2=mysql_query($sql2);
    					$t2=mysql_fetch_array($res2);
              if ($t2['nomBoutique'] =='' or $t2['nomBoutique'] =='NULL' ) {
                $sql1="DELETE FROM `".$catalogueTypeCateg."` WHERE id=".$t["id"];
      					$res1=@mysql_query($sql1) or die ("suppression impossible designation".mysql_error());
              }
              else {
                $nomtableDesignation=$t2['nomBoutique']."-designation";

      					$sql1="DELETE FROM `".$catalogueTypeCateg."` WHERE id=".$t["id"];
      					$res1=@mysql_query($sql1) or die ("suppression impossible designation".mysql_error());
      						// echo "<br/>".$sql1;
                // var_dump($sql1);

      					$sql21="update `".$nomtableDesignation."` set idFusion='".mysql_real_escape_string($maxIdFusion)."'
      					where idDesignation=".$t['idDesignation'];
      					$res21=@mysql_query($sql21)or die ("modification impossible1 ".mysql_error());
            }

				 }
			}
            exit('FUSBOU');
    
    }
    
}
 ?>
