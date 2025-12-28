<?php
/*
Résumé :
Commentaire :
Version : 2.0
see also :
Auteur : Ibrahima DIOP
Date de création : 20/03/2016
Date dernière modification : 20/04/2016
*/


  if(!$modifier){
      if($insererStock){

          $nombreArticle=0;

          $sql='select * from `'.$nomtableDesignation.'` where designation="'.$designation.'"';
		  //echo $sql;
          $res=mysql_query($sql);
          if(mysql_num_rows($res)){
          	 if($tab=mysql_fetch_array($res))
                 $nombreArticle  = $tab["nbreArticleUniteStock"];
          if ($uniteStock=="article")
            $total=$stock;
          else
            $total=$stock*$nombreArticle;

			if($dateExpiration){
				if($prixPublic){
				$sql1="INSERT INTO `".$nomtableStock."`(idDesignation,designation,quantiteStockinitial,forme,prixPublic,prixSession,prixDeRevientDuStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration) VALUES (".$tab['idDesignation'].",'".mysql_real_escape_string($designation)."',".$stock.",'".mysql_real_escape_string($forme)."',".$prixPublic.",".$prixSession.",".$prixDeRevientDuStock.",".$nombreArticle.",".$total.",'".$dateString."',".$total.",'".$dateExpiration."')";
				//echo $sql1;
				$res1=@mysql_query($sql1) or die ("insertion stock 1 impossible".mysql_error()) ;}
				else{
				$sql1="INSERT INTO `".$nomtableStock."`(idDesignation,designation,quantiteStockinitial,forme,prixSession,prixDeRevientDuStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration) VALUES (".$tab['idDesignation'].",'".mysql_real_escape_string($designation)."',".$stock.",'".mysql_real_escape_string($forme)."',".$prixSession.",".$prixDeRevientDuStock.",".$nombreArticle.",".$total.",'".$dateString."',".$total.",'".$dateExpiration."')";
				//echo $sql1;
				$res1=@mysql_query($sql1) or die ("insertion stock 1 impossible".mysql_error()) ;
				}
			}else{
			if($prixPublic){
            $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,forme,prixPublic,prixSession,prixDeRevientDuStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant) VALUES('.$tab["idDesignation"].',"'.mysql_real_escape_string($designation).'",'.$stock.',"'.mysql_real_escape_string($forme).'",'.$prixPublic.','.$prixSession.','.$prixDeRevientDuStock.','.$nombreArticle.','.$total.',"'.$dateString.'",'.$total.')';
			//echo $sql1;
            $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;
			}else{
			$sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,forme,prixSession,prixDeRevientDuStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant) VALUES('.$tab["idDesignation"].',"'.mysql_real_escape_string($designation).'",'.$stock.',"'.mysql_real_escape_string($forme).'",'.$prixSession.','.$prixDeRevientDuStock.','.$nombreArticle.','.$total.',"'.$dateString.'",'.$total.')';
			//echo $sql1;
            $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;
			}
		}
		
          	$sql='select * from `'.$nomtableTotalStock.'` where designation="'.mysql_real_escape_string($designation).'"';
            $res=mysql_query($sql);
            if(mysql_num_rows($res)){
            if($tab=mysql_fetch_array($res)){
          	 $totalstock=$tab["quantiteEnStocke"]+$total;

          	/* $date = new DateTime($tab["dateExpiration"]);
              $annee =$date->format('Y');
              $mois =$date->format('m');
              $jour =$date->format('d');
              $dateExp=$annee.'-'.$mois.'-'.$jour;*/

          	 $sql="update `".$nomtableTotalStock."` set quantiteEnStocke=".$totalstock." where designation='".mysql_real_escape_string($designation)."'";
             $res=@mysql_query($sql)or die ("modification impossible");
          	 }
          	 }else{
          	 $sql1='INSERT INTO `'.$nomtableTotalStock.'` (`designation`, `quantiteEnStocke`) VALUES("'.mysql_real_escape_string($designation).'",'.$total.')';
             $res1=@mysql_query($sql1) or die ("insertion stock impossible");
          	 }
        }else{
		echo '<script type="text/javascript"> alert("ALERT : LA REFERENCE DU STOCK QUE VOUS VOULEZ AJOUTER EST INEXISTANTE DANS LE CATALOGUE ...");</script>';
		}
		}
   }
  else{
    if($idStock){

      $total=$stock*$nombreArticle;
      $sql="update `".$nomtableStock."` set prixPublic=".$prixPublic.",prixSession=".$prixSession.",dateExpiration='".$dateExpiration."' where idStock=".$idStock;
	  //echo $sql;
      $res=@mysql_query($sql)or die ("modification impossible");

        $sql2="select totalArticleStock from `".$nomtableStock."` where designation='".mysql_real_escape_string($designation)."'";
        $res2=mysql_query($sql2);
      	$stocktotalmaj=0;
        if(mysql_num_rows($res2))
      	while($tab2=mysql_fetch_array($res2))
          $stocktotalmaj+=$tab2["totalArticleStock"];

        $sql="update `".$nomtableTotalStock."` set quantiteEnStocke=".$stocktotalmaj." where designation='".mysql_real_escape_string($designation)."'";
        $res=@mysql_query($sql)or die ("modification impossible");
    }
  }
  if ($supprimer) {
    $sql="DELETE FROM `".$nomtableStock."` WHERE idStock=".$idStock;
    $res=@mysql_query($sql) or die ("suppression impossible");
  }

   if($insererDesignation){
      $designation         =@htmlentities($_POST["designation"]);
	  $categorie2          =@htmlentities($_POST["categorie2"]);
      $prix                =@$_POST["prix"];
      //var_dump($prix);
      $prixPublic      =@$_POST["prixPublic"];
      $classe              =@$_POST["classe"];
      //var_dump($classe);
      $desig               =@$_POST["desig"];
      $forme          =@htmlentities($_POST["forme"]);
      $prixService         =@$_POST["prixService"];
      $montantFrais        =@$_POST["montantFrais"];
      $nbArticleUniteStock =@$_POST["nbArticleUniteStock"];

      if($classe==0){
        $sql="insert into `".$nomtableDesignation."` (designation,categorie,classe,prix,forme,prixPublic,nbreArticleUniteStock) values ('".mysql_real_escape_string($designation)."','".mysql_real_escape_string($categorie2)."',0,".$prix.",'".mysql_real_escape_string($forme)."',".$prixPublic.",".$nbArticleUniteStock.")";
        //var_dump($sql);
        $res=@mysql_query($sql) or die ("insertion impossible 1111");

      }
  }

/*************  IMPORTATION ET EXPORTATION CSV *******************/
/*****************************************************************/

  if (isset($_POST['subImport'])) {

  $fname=$_FILES['fileImport']['name'];
  if ($_FILES["fileImport"]["size"] > 0) {
    $fileName=$_FILES['fileImport']['tmp_name'];
    $handle=fopen($fileName,"r");
    $headers = fgetcsv($handle, 1000, ";");

    while (($data=fgetcsv($handle,1000,";")) !=FALSE) {

		$reference=$data[0];
		$quantite=$data[1];
		$uQuantite=$data[2];
		$prixU=$data[3];
		$prixUS=$data[4];
		$dateE=$data[5];
		 $nombreArticleT=0;

          $sql="select * from `".$nomtableDesignation."` where designation='".mysql_real_escape_string($reference)."'";
          $res=mysql_query($sql);
          if(mysql_num_rows($res))
          	 if($tab=mysql_fetch_array($res)){
				  if ($uQuantite=="Article")
						$total=$quantite;
				  else{
					 $nombreArticleT  = $tab["nbreArticleUniteStock"];
					 $total=$quantite*$nombreArticleT;
				  }
				 if($dateE){
					 $sql3="INSERT INTO `".$nomtableStock."` (idDesignation,designation,quantiteStockinitial,forme,nbreArticleUniteStock,prixSession,prixPublic, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration)
					 VALUES(".$tab["idDesignation"].",'".mysql_real_escape_string($reference)."',".$quantite.",'".mysql_real_escape_string($uQuantite)."',".$tab["nbreArticleUniteStock"].",".$prixU.",".$prixUS.",".$total.",'".$dateString."',".$total.",'".$dateE."')";
					 //echo $sql3;
					 //var_dump($sql3);
					 
					 $res3=@mysql_query($sql3) or die ("insertion stock 1 CSV impossible".mysql_error());
					}else{
						$sql3="INSERT INTO `".$nomtableStock."` (idDesignation,designation,quantiteStockinitial,forme,nbreArticleUniteStock,prixSession,prixPublic, totalArticleStock, dateStockage, quantiteStockCourant) VALUES(".$tab["idDesignation"].",'".mysql_real_escape_string($reference)."',".$quantite.",'".mysql_real_escape_string($uQuantite)."',".$tab["nbreArticleUniteStock"].",".$prixU.",".$prixUS.",".$total.",'".$dateString."',".$total.")";
						//echo $sql3;
					 	//var_dump($sql3);
						 $res3=@mysql_query($sql3) or die ("insertion stock 2 CSV impossible".mysql_error());
					}
	}
  }
    fclose($handle);
	echo'<!DOCTYPE html>';
	echo'<html>';
	echo'<head>';
	echo'<script language="JavaScript">document.location="gestionStock.php"</script>';
	echo'</head>';
	echo'</html>';
  }

if ( $_GET['l']==6) {

  $fname=$_FILES['fileImport']['name'];
  if ($_FILES["fileImport"]["size"] > 0) {
    $fileName=$_FILES['fileImport']['tmp_name'];
    $handle=fopen($fileName,"r");
    $tabDes;
    while (($data=fgetcsv($handle,1000,";")) !=FALSE) {

     $tabDes[]=$data;

    }
    //return  $tabDes;
    //var_dump($tabDes);
    fclose($handle);

  }

}

}
if(isset($_POST["Export"])){

      header('Content-Type: text/csv; charset=utf-8');
      header('Content-Disposition: attachment; filename=data-stock.csv');
      $delimiter = ";";
      $output = fopen("php://output","w");
      $fields=array('REFERENCE', 'QUANTITE','FORME','PRIX SESSION', 'PRIX PUBLIC', 'DATE EXPIRATION');
      fputcsv($output,$fields, $delimiter);
      fclose($output); exit;

 }

/*************  LES BOUTONS IMPORTATION ET EXPORTATION EN CSV ET AJOUTER STOCK *******************/
/*************************************************************************************************/

require('entetehtml.php'); 
echo'
   <body >';
   require('header.php');

echo'<div class="container" align="center"><button type="button" class="btn btn-success" data-toggle="modal" data-target="#AjoutStockModal" data-dismiss="modal" id="AjoutStock">
<i class="glyphicon glyphicon-plus"></i>Ajouter un Stock</button>';

echo'<div class="modal fade" id="AjoutStockModal" role="dialog">';
echo'<div class="modal-dialog">';
echo'<div class="modal-content">';
echo'<div class="modal-header" style="padding:35px 50px;">';
echo'<button type="button" class="close" data-dismiss="modal">&times;</button>';
echo"<h4><span class='glyphicon glyphicon-lock'></span> Ajout de Stock </h4>";
echo'</div>';
echo'<div class="modal-body" style="padding:40px 50px;">';

echo'<table width="100%" align="center" border="0">
<form role="form" class="" name="formulaire2" id="ajouterStockForm" method="post" action="gestionStock.php">

<div class="form-group" >
	<tr class="reference"><td><label for="reference">REFERENCE <font color="red">*</font></label>';
    //echo'&nbsp;&nbsp;<button type="button" class="btn btn-primary btn-danger" data-toggle="modal" data-target="#myModalTest" data-dismiss="modal">+</button> <span id="missIdD"></span>';
    echo'</td></tr>
	<tr class="reference">
		<td>
			<input type="text" class="form-control" placeholder="la Référence ici..." id="designationStock" name="designation" value="" required />
			<input type="hidden" name="idDesignation" value="" id="idD" >
		</td>
	</tr>
	<tr>
		<td>
			<div id="reponseS"></div>
		</td>
	</tr>
	<tr class="reference">
		<td><div class="help-block" id="helpReference">
		    </div>
		</td>
	</tr>
</div>

<div class="form-group">
<tr class="div-stock"><td><label for="stock">QUANTITE A STOCKER <font color="red">*</font></label></td></tr>
<tr class="div-stock"><td><input type="number" class="form-control" placeholder="la quantité à ici..."  id="stock" name="stock" value="" required /></td></tr>
<tr class="div-stock"><td><div class="help-block" id="helpStock"></div></td></tr>
</div>

<div class="form-group">
<tr class="div-forme">
<td><label for="forme">FORME <font color="red">*</font></label></td></tr>
<tr class="div-forme"><td>
	<SELECT size=1 class="form-control" id="forme" required name="forme" >
		<option selected>article</option>
		<option id="formeId"> </option>
	</SELECT>
	</td>
</tr>
<tr class="div-forme"><td><div class="help-block" id="helpforme"></div></td></tr>
</div>

<div class="form-group">
<tr class="div-prixSession"><td><label for="prixSession">PRIX SESSION</label></td></tr>
<tr class="div-prixSession"><td><input type="number" class="form-control" placeholder="Le prix session ici..."  id="prixSession" name="prixSession" value="" /></td></tr>
<tr class="div-prixSession"><td><div class="help-block" id="helpprixSession"></div></td></tr>
</div>

<div class="form-group">
<tr class="div-prixPublic"><td><label for="prixPublic">PRIX PUBLIC </label></td></tr>
<tr class="div-prixPublic"><td><input type="number" class="form-control" placeholder="Le prix public ici..."  id="prixPublic" name="prixPublic" value="" /></td></tr>
<tr class="div-prixPublic"><td><div class="help-block" id="helpprixPublic"></div></td></tr>
</div>

<div class="form-group">
<tr class="div-dateExpiration"><td><label for="dateExpiration">DATE EXPIRATION</label></td></tr>
<tr class="div-dateExpiration"><td><input type="date" class="form-control" placeholder="la quantité à ici..."  id="dateExpiration" name="dateExpiration" value="" /></td></tr>
<tr class="div-dateExpiration"><td><div class="help-block" id="helpDateExpiration"></div></td></tr>

</div>';

/*echo'<div class="form-group">
<tr class="div-PrixRSTOCK"><td><label for="PrixRSTOCK">PRIX DE REVIENT</label></td></tr>
<tr class="div-PrixRSTOCK"><td><input type="number" class="form-control" placeholder="Le prix de revient ici..."  id="prixDeRevientDuStock" name="prixDeRevientDuStock" value="0" /></td></tr>
<tr class="div-PrixRSTOCK"><td><div class="help-block" id="helpPrixRSTOCK"></div></td></tr> ';       */

echo'</div>
<tr><td colspan="2" align="center"><div class="modal-footer"><font color="red">Les champs qui ont (*) sont obligatoires</font><br />
  <input type="submit" class="boutonbasic" name="insererStock" value="ENVOYER  >>">'.


'</td></tr>'.
'<tr><td colspan="2"><div id="apresEntre"></div></td></tr></div>';
echo'</form></table><br />'.
'</div></div></div></div></div>';

/*****************************/




//if($_SESSION['enConfiguration'] ==0){
echo'<div class="container" align="center">
        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#STOCKPRODUITDETAIL">LISTE DES STOCKS DE PRODUITS</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="STOCKPRODUITDETAIL">';
                   $sql3='SELECT * from  `'.$nomtableStock.'` where quantiteStockCourant!=0 order by idStock desc';
                    if($res3=mysql_query($sql3)){
                    echo'<table id="exemple" class="display" border="1"><thead><tr>
						<th>REFERENCE</th>
						<th>QUANTITE INITIALE</th>
						<th>FORME</th>
                        <th>PRIX SESSION</th>
						<th>PRIX PUBLIC</th>
						<th>QUANTITE RESTANTE</th>
						<th>EXPIRATION</th>
						<th>OPERATIONS</th>
					</tr></thead>
					<tfoot><tr>
						<th>REFERENCE</th>
						<th>QUANTITE INITIALE</th>
						<th>FORME</th>
                        <th>PRIX SESSION</th>
						<th>PRIX PUBLIC</th>
						<th>QUANTITE RESTANTE</th>
						<th>EXPIRATION</th>
						<th>OPERATIONS</th>
					</tr></tfoot>';
                      while($tab3=mysql_fetch_array($res3)){
                              echo'<tr><td>'.$tab3["designation"].'</td>
                              <td align="right">'.$tab3["quantiteStockinitial"].'</td>';


                              $sql5='SELECT * from  `'.$nomtableDesignation.'` where designation="'.$tab3["designation"].'"';
						      $res5=mysql_query($sql5);
                              $tab5=mysql_fetch_array($res5);

                              echo'<td>'.$tab5["forme"].'</td>
                              <td align="right">'.$tab3["prixSession"].'</td>
                              <td align="right">'.$tab3["prixPublic"].'</td>
							  <td align="right">'.$tab3["quantiteStockCourant"].'</td>
                              <td>'.$tab3["dateExpiration"].'</td>
                              <td><a href="#">
                              <img src="images/edit.png" align="middle" alt="modifier" data-toggle="modal" data-target="#imgmodifier'.$tab3["idStock"].'" /></a>&nbsp;&nbsp;
                              <a href="#">'.
                              '<img src="images/drop.png" align="middle" alt="supprimer" data-toggle="modal" data-target="#imgsup'.$tab3["idStock"].'" /></a>
                              <a href="pharmacie/codeBarreStock-pharmacie.php?iDS='.$tab3["idStock"].'&iDD='.$tab3["designation"].'">'.
                              'Detail</a>
<div id="imgmodifier'.$tab3["idStock"].'"  class="modal fade " role="dialog">
        <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modification du stock</h4>
                    </div>
                    <div class="modal-body" style="padding:40px 50px;">
					<form role="form" class="" name="formulaire2" method="post" action="gestionStock.php">
					<table align="center" border="0">
					
                       

								<div class="form-group">
                                  <tr class="div-reference"><td><label for="reference">REFERENCE </label></td></tr>
                                  <tr class="div-reference"><td><input type="text" class="form-control" name="designation" id="designation" value="'.$tab3["designation"].'" disabled=""/></td></tr>
                                  <tr class="div-reference"><td><div class="help-block label label-danger" id="helpReference"></div></td></tr>
                                </div>

                               <input type="hidden" name="idDesignation" value="" id="idD">';
							   if (($tab3["totalArticleStock"] == $tab3["quantiteStockCourant"])&&($tab3["dateStockage"] == $dateString)){
                                echo '<div class="form-group">
                                  <tr class="div-quantite"><td><label for="quantite">QUANTITE INITIALE <font color="red">*</font></label></td></tr>
								  <tr class="div-quantite"><td><input type="number" class="form-control" id="stock" name="stock" size="35" value="'.$tab3["quantiteStockinitial"].'" required disabled="" /></td></tr>
                                  <tr class="div-quantite"><td><div class="help-block label label-danger" id="helpQuantite"></div></td></tr>
                                </div>
								
									<div class="form-group">
                                  <tr class="div-forme"><td><label for="forme"> FORME <font color="red">*</font></label></td></tr>
                                  <tr class="div-forme"><td>
                                    <select class="form-control" name="forme" id="forme" disabled="">
											<option selected value= "'.$tab3["forme"].'">'.$tab3["forme"].'</option>';
                                            $sql11="SELECT * FROM `unitestock`";
                                            $res11=mysql_query($sql11);
                                            while($ligne2 = mysql_fetch_row($res11)) {
                                                echo'<option value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
                                              }
                                    echo'</select>
                                  </td></tr>
                                  <tr class="div-forme"><td><div class="help-block label label-danger" id="helpforme"></div></td></tr>
                                </div>	

								<div class="form-group" >
                                  <tr class="div-prix"><td><label for="prix">QUANTITE COURANTE (RESTE) </label></td></tr>
                                  <tr class="div-prix"><td><input type="number" class="form-control" id="quantiteStockCourant" name="quantiteStockCourant" value="'.$tab3["quantiteStockCourant"].'" disabled=""/></td></tr>
                                  <tr class="div-prix"><td><div class="help-block label label-danger" id="helpQuantiteStockCourant"></div></td></tr>
                                </div>
								
								 <div class="form-group" >
                                  <tr class="div-prix"><td><label for="prix">PRIX SESSION </label></td></tr>
                                  <tr class="div-prix"><td><input type="number" class="form-control" id="prixSession" name="prixSession" value="'.$tab3["prixSession"].'" /></td></tr>
                                  <tr class="div-prix"><td><div class="help-block label label-danger" id="helpprixSession"></div></td></tr>
                                </div>

                                <div class="form-group" >
                                  <tr class="div-prixPublic"><td><label for="prixPublic">PRIX PUBLIC</label></td></tr>
                                  <tr class="div-prixPublic"><td><input type="number" class="form-control" id="prixPublic" name="prixPublic" value="'.$tab3["prixPublic"].'" /></td></tr>
                                  <tr class="div-prixPublic"><td><div class="help-block label label-danger" id="helpprixPublic"></div></td></tr>
                                </div>
 
                                <div class="form-group" >
                                  <tr class="div-dateEpiration"><td><label for="dateEpiration">DATE EXPIRATION</label></td></tr>
                                  <tr class="div-dateEpiration"><td><input type="text" class="form-control" id="dateExpiration" name="dateExpiration" value="'.$tab3["dateExpiration"].'" /></td></tr>
                                  <tr class="div-dateEpiration"><td><div class="help-block label label-danger" id="helpDateEpiration"></div></td></tr>
                                </div>';

								}else{
								
						echo '<div class="form-group">
                                  <tr class="div-quantite"><td><label for="quantite">QUANTITE INITIALE</label></td></tr>
								  <tr class="div-quantite"><td><input type="number" class="form-control" id="stock" name="stock" size="35" value="'.$tab3["quantiteStockinitial"].'" disabled="" /></td></tr>
                                  <tr class="div-quantite"><td><div class="help-block label label-danger" id="helpQuantite"></div></td></tr>
                                </div>';
								
								
								$sql5='SELECT * from  `'.$nomtableDesignation.'` where designation="'.$tab3["designation"].'"';
						      $res5=mysql_query($sql5);
                              $tab5=mysql_fetch_array($res5);


								echo'<div class="form-group">
                                  <tr class="div-forme"><td><label for="forme"> FORME </label></td></tr>
                                  <tr class="div-forme"><td>
                                    <select class="form-control" name="forme" id="forme" disabled="">
											<option selected value= "'.$tab5["forme"].'">'.$tab5["forme"].'</option>';
                                    echo'</select>
                                  </td></tr>
                                  <tr class="div-forme"><td><div class="help-block label label-danger" id="helpforme"></div></td></tr>
                                </div>


								<div class="form-group" >
                                  <tr class="div-prix"><td><label for="prix">QUANTITE COURANTE (RESTE) </label></td></tr>
                                  <tr class="div-prix"><td><input type="number" class="form-control" id="quantiteStockCourant" name="quantiteStockCourant" value="'.$tab3["quantiteStockCourant"].'" disabled=""/></td></tr>
                                  <tr class="div-prix"><td><div class="help-block label label-danger" id="helpQuantiteStockCourant"></div></td></tr>
                                </div>
							
								 <div class="form-group" >
                                  <tr class="div-prix"><td><label for="prix"> PRIX SESSION </label></td></tr>
                                  <tr class="div-prix"><td><input type="number" class="form-control" id="prixSession" name="prixSession" value="'.$tab3["prixSession"].'" /></td></tr>
                                  <tr class="div-prix"><td><div class="help-block label label-danger" id="helpprixSession"></div></td></tr>
                                </div>

                                <div class="form-group" >
                                  <tr class="div-prixPublic"><td><label for="prixPublic">PRIX PUBLIC</label></td></tr>
                                  <tr class="div-prixPublic"><td><input type="number" class="form-control" id="prixPublic" name="prixPublic" value="'.$tab3["prixPublic"].'" /></td></tr>
                                  <tr class="div-prixPublic"><td><div class="help-block label label-danger" id="helpprixPublic"></div></td></tr>
                                </div>
 
                                <div class="form-group" >
                                  <tr class="div-dateEpiration"><td><label for="dateEpiration">DATE EXPIRATION</label></td></tr>
                                  <tr class="div-dateEpiration"><td><input type="text" class="form-control" id="dateExpiration1" name="dateExpiration1" value="'.$tab3["dateExpiration"].'" disabled="" /><input type="hidden" name="dateExpiration" value="'.$tab3["dateExpiration"].'"/></td></tr>
                                  <tr class="div-dateEpiration"><td><div class="help-block label label-danger" id="helpDateEpiration"></div></td></tr>
                                </div>';
								
								}
								
                           echo'<tr><td align="center">
                                  <font color="red">Les champs qui ont (*) sont obligatoires</font><br />
                              <input type="submit" class="boutonbasic"  name="btnModifier" value="MODIFIER  >>"/>
                              
                              <input type="hidden" name="idStock" value="'.$tab3["idStock"].'"/>
                              <input type="hidden" name="modifier" value="1"/>
							</td>
                           </tr>
                       </table>
					   </form>

                     
                    </div>
                </div>
            </div>
      </div>
      <div id="imgsup'.$tab3["idStock"].'"  class="modal fade " role="dialog">
        <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Confirmation Suppression du stock</h4>
                    </div>
                    <div class="modal-body" style="padding:40px 50px;">
                       
					   
					   
					<table align="center" border="0">
					
                       <form role="form" class="" name="formulaire2" method="post" action="gestionStock.php">

								<div class="form-group">
                                  <tr class="div-reference"><td><label for="reference">REFERENCE </label></td></tr>
                                  <tr class="div-reference"><td><input type="text" class="form-control" name="designation" id="designation" value="'.$tab3["designation"].'" disabled="" /></td></tr>
                                  <tr class="div-reference"><td><div class="help-block label label-danger" id="helpReference"></div></td></tr>
                                </div>

                               <input type="hidden" name="idDesignation" value="" id="idD">'.
							   
                                '<div class="form-group">
                                  <tr class="div-quantite"><td><label for="quantite">QUANTITE INITIALE</label></td></tr>
								  <tr class="div-quantite"><td><input type="number" class="form-control" id="stock" name="stock" size="35" value="'.$tab3["quantiteStockinitial"].'" disabled="" /></td></tr>
                                  <tr class="div-quantite"><td><div class="help-block label label-danger" id="helpQuantite"></div></td></tr>
                                </div>';

					          $sql5='SELECT * from  `'.$nomtableDesignation.'` where designation="'.$tab3["designation"].'"';
						      $res5=mysql_query($sql5);
                              $tab5=mysql_fetch_array($res5);


								echo'<div class="form-group">
                                  <tr class="div-forme"><td><label for="forme"> FORME </label></td></tr>
                                  <tr class="div-forme"><td>
                                    <select class="form-control" name="forme" id="forme" disabled="">
											<option selected value= "'.$tab5["forme"].'">'.$tab5["forme"].'</option>';
                                    echo'</select>
                                  </td></tr>
                                  <tr class="div-forme"><td><div class="help-block label label-danger" id="helpforme"></div></td></tr>
                                </div>
							
								 <div class="form-group" >
                                  <tr class="div-prixSession"><td><label for="prix">PRIX SESSION </label></td></tr>
                                  <tr class="div-prixSession"><td><input type="number" class="form-control" id="prixSession" name="prixSession" value="'.$tab3["prixSession"].'" disabled="" /></td></tr>
                                  <tr class="div-prixSession"><td><div class="help-block label label-danger" id="helpprixSession"></div></td></tr>
                                </div>

                                <div class="form-group" >
                                  <tr class="div-prixPublic"><td><label for="prixPublic">PRIX PUBLIC</label></td></tr>
                                  <tr class="div-prixPublic"><td><input type="number" class="form-control" id="prixPublic" name="prixPublic" value="'.$tab3["prixPublic"].'" disabled="" /></td></tr>
                                  <tr class="div-prixPublic"><td><div class="help-block label label-danger" id="helpprixPublic"></div></td></tr>
                                </div>
 
                                <div class="form-group" >
                                  <tr class="div-dateEpiration"><td><label for="dateEpiration">DATE EXPIRATION</label></td></tr>
                                  <tr class="div-dateEpiration"><td><input type="text" class="form-control" id="dateExpiration1" name="dateExpiration1" value="'.$tab3["dateExpiration"].'" disabled="" /><input type="hidden" name="dateExpiration" value="'.$tab3["dateExpiration"].'"/></td></tr>
                                  <tr class="div-dateEpiration"><td><div class="help-block label label-danger" id="helpDateEpiration"></div></td></tr>
                                </div>


                           <tr><td align="center">
                                  <br/><br/>

                              <input type="submit" class="boutonbasic"  name="btnSupprimer" value="SUPPRIMER  >>"/>

                              <input type="hidden" name="idStock" value="'.$tab3["idStock"].'"/>
                              <input type="hidden" name="supprimer" value="1"/>
							</td>
                           </tr>
                          </form>
                       </table>
	
                    </div>
                </div>
            </div>
      </div>
                              ';

                      }
                    }
                    else{
                      echo'<table class="tableau2" width="80%" align="center" border="1"><th>DESIGNATION</th><th>QUANTITE</th><th>UNITE STOCK</th><th>NOMBRE ARTICLE/US</th><th>DATE EXPIRATION</th><th></th>';
                      echo'<tr><td colspan="6">Liste des Stocks généraux de Produits de la date du '.$dateString.' est pour le moment vide ';
                       }

                    echo'</td></tr></table><br />
            </div>

        </div>
      </div>';

echo'</body></html>';


?>
