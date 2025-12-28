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
$doublons=NULL;
$fusions=NULL;

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
$i=1;

while($tab3=mysql_fetch_array($res3)){
  $rows = array();	
  $rows[] = $i;	
  $rows[] = strtoupper($tab3['designation']);		
  $rows[] = strtoupper($tab3['categorie']);	
  $rows[] = strtoupper($tab3['uniteStock']);
  $rows[] = strtoupper($tab3['nbreArticleUniteStock']);	
  $rows[] = strtoupper($tab3['prix']);		
  $rows[] = strtoupper($tab3['prixuniteStock']);	

  $sqlD="SELECT COUNT( * ) FROM `".$catalogueTypeCateg."`
          WHERE designation ='".$tab3['designation']."' && categorie ='".$tab3['categorie']."' 
          && uniteStock ='".$tab3['uniteStock']."' && prix ='".$tab3['prix']."' 
          && prixuniteStock ='".$tab3['prixuniteStock']."' && nbreArticleUniteStock ='".$tab3['nbreArticleUniteStock']."' ";
  $resD = mysql_query($sqlD) or die ("persoonel requête 2".mysql_error());
  $nbreD = mysql_fetch_array($resD) ;
  if($nbreD[0] > 1){
    if ($tab3["image"]) {
      if($tab3["codeBarreDesignation"]!=null){
        $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a><img src="images/drop.png" align="middle" alt="supprimer"  onclick="spm_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a onclick="codeBR_Designation('.$tab3["id"].','.$id.','.$i.')" ><span class="glyphicon glyphicon-barcode"></span></a>
        <a><img src="images/iconfinder11.png" align="middle" alt="apperçu"  onclick="img_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a><button type="button"  name="button" onclick="dbl_Designation('.$tab3["id"].','.$id.','.$i.')">Doublon</button></a>';
      }
      else{
        $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a><img src="images/drop.png" align="middle" alt="supprimer"  onclick="spm_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a onclick="codeBR_Designation('.$tab3["id"].','.$id.','.$i.')" ><span style="color:red" class="glyphicon glyphicon-barcode"></span></a>
        <a><img src="images/iconfinder11.png" align="middle" alt="apperçu"  onclick="img_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a><button type="button"  name="button" onclick="dbl_Designation('.$tab3["id"].','.$id.','.$i.')">Doublon</button></a>';
      }
    }
    else{
      if($tab3["codeBarreDesignation"]!=null){
        $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a><img src="images/drop.png" align="middle" alt="supprimer"  onclick="spm_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a onclick="codeBR_Designation('.$tab3["id"].','.$id.','.$i.')" ><span class="glyphicon glyphicon-barcode"></span></a>
        <a><img src="images/iconfinder9.png" align="middle" alt="apperçu"  onclick="img_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a><button type="button"  name="button" onclick="dbl_Designation('.$tab3["id"].','.$id.','.$i.')">Doublon</button></a>';
      }
      else{
        $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a><img src="images/drop.png" align="middle" alt="supprimer"  onclick="spm_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a onclick="codeBR_Designation('.$tab3["id"].','.$id.','.$i.')" ><span style="color:red" class="glyphicon glyphicon-barcode"></span></a>
        <a><img src="images/iconfinder9.png" align="middle" alt="apperçu"  onclick="img_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a><button type="button"  name="button" onclick="dbl_Designation('.$tab3["id"].','.$id.','.$i.')">Doublon</button></a>';
      }
    } 
  }

  $sqlF="SELECT COUNT( * ) FROM `".$catalogueTypeCateg."`
  WHERE designation ='".$tab3['designation']."' ";
  $resF = mysql_query($sqlF) or die ("persoonel requête 2".mysql_error());
  $nbreF = mysql_fetch_array($resF) ;
  if($nbreF[0] > 1){
    if ($tab3["image"]) {
      if($tab3["codeBarreDesignation"]!=null){
        $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a><img src="images/drop.png" align="middle" alt="supprimer"  onclick="spm_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a onclick="codeBR_Designation('.$tab3["id"].','.$id.','.$i.')" ><span class="glyphicon glyphicon-barcode"></span></a>
        <a><img src="images/iconfinder11.png" align="middle" alt="apperçu"  onclick="img_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a><button type="button" name="button" onclick="fsn_Designation('.$tab3["id"].','.$id.','.$i.')">Fusion</button></a>';
      }
      else{
        $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a><img src="images/drop.png" align="middle" alt="supprimer"  onclick="spm_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a onclick="codeBR_Designation('.$tab3["id"].','.$id.','.$i.')" ><span style="color:red" class="glyphicon glyphicon-barcode"></span></a>
        <a><img src="images/iconfinder11.png" align="middle" alt="apperçu"  onclick="img_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a><button type="button" name="button" onclick="fsn_Designation('.$tab3["id"].','.$id.','.$i.')">Fusion</button></a>';
      }
    }
    else{
      if($tab3["codeBarreDesignation"]!=null){
        $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a><img src="images/drop.png" align="middle" alt="supprimer"  onclick="spm_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a onclick="codeBR_Designation('.$tab3["id"].','.$id.','.$i.')" ><span class="glyphicon glyphicon-barcode"></span></a>
        <a><img src="images/iconfinder9.png" align="middle" alt="apperçu"  onclick="img_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a><button type="button" name="button" onclick="fsn_Designation('.$tab3["id"].','.$id.','.$i.')">Fusion</button></a>';
      }
      else{
        $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a><img src="images/drop.png" align="middle" alt="supprimer"  onclick="spm_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a onclick="codeBR_Designation('.$tab3["id"].','.$id.','.$i.')" ><span style="color:red" class="glyphicon glyphicon-barcode"></span></a>
        <a><img src="images/iconfinder9.png" align="middle" alt="apperçu"  onclick="img_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a><button type="button" name="button" onclick="fsn_Designation('.$tab3["id"].','.$id.','.$i.')">Fusion</button></a>';
      }
    }
  }

  if($nbreD[0]<=1 && $nbreF[0]<=1){
    if ($tab3["image"]) {
      if($tab3["codeBarreDesignation"]!=null){
        $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a><img src="images/drop.png" align="middle" alt="supprimer"  onclick="spm_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a onclick="codeBR_Designation('.$tab3["id"].','.$id.','.$i.')" ><span class="glyphicon glyphicon-barcode"></span></a>
        <a><img src="images/iconfinder11.png" align="middle" alt="apperçu"  onclick="img_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>';
      }
      else{
        $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation_PH('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a><img src="images/drop.png" align="middle" alt="supprimer"  onclick="spm_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a onclick="codeBR_Designation('.$tab3["id"].','.$id.','.$i.')" ><span style="color:red" class="glyphicon glyphicon-barcode"></span></a>
        <a><img src="images/iconfinder11.png" align="middle" alt="apperçu"  onclick="img_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>';
      }
    }
    else{
      if($tab3["codeBarreDesignation"]!=null){
        $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a><img src="images/drop.png" align="middle" alt="supprimer"  onclick="spm_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a onclick="codeBR_Designation('.$tab3["id"].','.$id.','.$i.')" ><span class="glyphicon glyphicon-barcode"></span></a>
        <a><img src="images/iconfinder9.png" align="middle" alt="apperçu"  onclick="img_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>';
      }
      else{
        $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a><img src="images/drop.png" align="middle" alt="supprimer"  onclick="spm_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>
        <a onclick="codeBR_Designation('.$tab3["id"].','.$id.','.$i.')" ><span style="color:red" class="glyphicon glyphicon-barcode"></span></a>
        <a><img src="images/iconfinder9.png" align="middle" alt="apperçu"  onclick="img_Designation('.$tab3["id"].','.$id.','.$i.')" /></a>';
      }
    }
  }

  $data[] = $rows;
  $i= $i + 1;
}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);


?>