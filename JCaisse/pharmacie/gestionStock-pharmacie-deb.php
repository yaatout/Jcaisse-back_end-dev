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
				if($prixuniteStock){
				$sql1="INSERT INTO `".$nomtableStock."`(idDesignation,designation,quantiteStockinitial,uniteStock,prixuniteStock,prixunitaire,prixDeRevientDuStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration) VALUES (".$tab['idDesignation'].",'".mysql_real_escape_string($designation)."',".$stock.",'".mysql_real_escape_string($uniteStock)."',".$prixuniteStock.",".$prixunitaire.",".$prixDeRevientDuStock.",".$nombreArticle.",".$total.",'".$dateString."',".$total.",'".$dateExpiration."')";
				//echo $sql1;
				$res1=@mysql_query($sql1) or die ("insertion stock 1 impossible".mysql_error()) ;}
				else{
				$sql1="INSERT INTO `".$nomtableStock."`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,prixDeRevientDuStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration) VALUES (".$tab['idDesignation'].",'".mysql_real_escape_string($designation)."',".$stock.",'".mysql_real_escape_string($uniteStock)."',".$prixunitaire.",".$prixDeRevientDuStock.",".$nombreArticle.",".$total.",'".$dateString."',".$total.",'".$dateExpiration."')";
				//echo $sql1;
				$res1=@mysql_query($sql1) or die ("insertion stock 1 impossible".mysql_error()) ;
				}
			}else{
			if($prixuniteStock){
            $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixuniteStock,prixunitaire,prixDeRevientDuStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant) VALUES('.$tab["idDesignation"].',"'.mysql_real_escape_string($designation).'",'.$stock.',"'.mysql_real_escape_string($uniteStock).'",'.$prixuniteStock.','.$prixunitaire.','.$prixDeRevientDuStock.','.$nombreArticle.','.$total.',"'.$dateString.'",'.$total.')';
			//echo $sql1;
            $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;
			}else{
			$sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,prixDeRevientDuStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant) VALUES('.$tab["idDesignation"].',"'.mysql_real_escape_string($designation).'",'.$stock.',"'.mysql_real_escape_string($uniteStock).'",'.$prixunitaire.','.$prixDeRevientDuStock.','.$nombreArticle.','.$total.',"'.$dateString.'",'.$total.')';
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
      $sql="update `".$nomtableStock."` set prixuniteStock=".$prixuniteStock.",prixunitaire=".$prixunitaire.",dateExpiration='".$dateExpiration."',prixDeRevientDuStock=".$prixDeRevientDuStock." where idStock=".$idStock;
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
      $prixuniteStock      =@$_POST["prixuniteStock"];
      $classe              =@$_POST["classe"];
      //var_dump($classe);
      $desig               =@$_POST["desig"];
      $uniteStock          =@htmlentities($_POST["uniteStock"]);
      $prixService         =@$_POST["prixService"];
      $montantFrais        =@$_POST["montantFrais"];
      $nbArticleUniteStock =@$_POST["nbArticleUniteStock"];

      if($classe==0){
        $sql="insert into `".$nomtableDesignation."` (designation,categorie,classe,prix,uniteStock,prixuniteStock,nbreArticleUniteStock) values ('".mysql_real_escape_string($designation)."','".mysql_real_escape_string($categorie2)."',0,".$prix.",'".mysql_real_escape_string($uniteStock)."',".$prixuniteStock.",".$nbArticleUniteStock.")";
        //var_dump($sql);
        $res=@mysql_query($sql) or die ("insertion impossible 1111");

      }
  }



/*************  LES BOUTONS IMPORTATION ET EXPORTATION EN CSV ET AJOUTER STOCK *******************/
/*************************************************************************************************/

require('entetehtml.php');
echo'
   <body >';
   require('header.php');
/*
echo '<div class="col-sm-3 ">
          <form class="form-horizontal" action="gestionStock.php" method="post" name="upload_excel"
                          enctype="multipart/form-data">
                      <div class="form-group">
                                <div class="col-md-4 col-md-offset-4">
                                    <input type="submit" name="Export" class="btn btn-success" value="Générer un fichier CSV"/>
                                </div>
                       </div>
            </form>
    </div>
    <div class="col-sm-3">
          <form class="form-inline" action='.$_SERVER["PHP_SELF"].' method="post" enctype="multipart/form-data">
              <div class="form-group">
                   <div class="col-md-8">
                       <input type="file" id="importInput" name="fileImport"
                        data-toggle="modal" onChange="loadCSV()" required >
                        <button type="submit" name="subImport" value="Importer" class="btn btn-success">Importer des Stocks </button>
                  </div>
              </div>
          </form>
    </div>';*/

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
	<tr class="reference"><td><label for="reference">REFERENCE <font color="red">*</font></label>&nbsp;&nbsp;<button type="button" class="btn btn-primary btn-danger" data-toggle="modal" data-target="#myModalTest" data-dismiss="modal">+</button> <span id="missIdD"></span></td></tr>
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
<tr class="div-uniteStock">
<td><label for="uniteStock">UNITE QUANTITE A STOCKER <font color="red">*</font></label></td></tr>
<tr class="div-uniteStock"><td>
	<SELECT size=1 class="form-control" id="uniteStock" required name="uniteStock" >
		<option selected>article</option>
		<option id="uniteStockId"> </option>
	</SELECT>
	</td>
</tr>
<tr class="div-uniteStock"><td><div class="help-block" id="helpUniteStock"></div></td></tr>
</div>

<div class="form-group">
<tr class="div-prixunitaire"><td><label for="prixunitaire">PRIX UNITAIRE</label></td></tr>
<tr class="div-prixunitaire"><td><input type="number" class="form-control" placeholder="Le prix unitaire à ici..."  id="prixunitaire" name="prixunitaire" value="" /></td></tr>
<tr class="div-prixunitaire"><td><div class="help-block" id="helpPrixunitaire"></div></td></tr>
</div>

<div class="form-group">
<tr class="div-prixuniteStock"><td><label for="prixuniteStock">PRIX UNITE DE STOCK </label></td></tr>
<tr class="div-prixuniteStock"><td><input type="number" class="form-control" placeholder="Le prix de unité de Stock ici..."  id="prixuniteStock" name="prixuniteStock" value="" /></td></tr>
<tr class="div-prixuniteStock"><td><div class="help-block" id="helpPrixuniteStock"></div></td></tr>
</div>

<div class="form-group">
<tr class="div-dateExpiration"><td><label for="dateExpiration">DATE EXPIRATION</label></td></tr>
<tr class="div-dateExpiration"><td><input type="date" class="form-control" placeholder="la quantité à ici..."  id="dateExpiration" name="dateExpiration" value="" /></td></tr>
<tr class="div-dateExpiration"><td><div class="help-block" id="helpDateExpiration"></div></td></tr>

</div>

<div class="form-group">
<tr class="div-PrixRSTOCK"><td><label for="PrixRSTOCK">PRIX DE REVIENT</label></td></tr>
<tr class="div-PrixRSTOCK"><td><input type="number" class="form-control" placeholder="Le prix de revient ici..."  id="prixDeRevientDuStock" name="prixDeRevientDuStock" value="0" /></td></tr>
<tr class="div-PrixRSTOCK"><td><div class="help-block" id="helpPrixRSTOCK"></div></td></tr>

</div>
<tr><td colspan="2" align="center"><div class="modal-footer"><font color="red">Les champs qui ont (*) sont obligatoires</font><br />
  <input type="submit" class="boutonbasic" name="insererStock" value="ENVOYER  >>">'.
  
  
'</td></tr>'.
'<tr><td colspan="2"><div id="apresEntre"></div></td></tr></div>';
echo'</form></table><br />'.
'</div></div></div></div></div>';

/*****************************/

 ?>

      <div id="myModalTest" class="modal fade " role="dialog">
                 <div class="modal-dialog">
                     <div class="modal-content">
                         <div class="modal-header">
                             <button type="button" class="close" data-dismiss="modal">&times;</button>
                             <h4 class="modal-title">Ajout de Reference</h4>
                         </div>
                         <div class="modal-body">
                               <form role="form" class="" id="form" name="formulaire2" method="post" action="gestionStock.php">




                                                <div class="form-group">
                                                  <tr class="div-reference"><td><label for="reference">REFERENCE <font color="red">*</font> </label></td></tr>
                                                  <tr class="div-reference"><td><input type="text" class="form-control" placeholder="Nom de la reference du Produit ici..."  name="designation" id="designation" value="" required /></td></tr>
                                                  <tr class="div-reference"><td><div class="help-block label label-danger" id="helpReference"></div></td></tr>
                                                </div>
                                                <div class="form-group">
                                                  <tr class="div-categorie"><td><label for="categorie"> CATEGORIE </label></td></tr>

                                                  <tr class="div-categorie"><td>
                                                    <select class="form-control" name="categorie2" id="categorie2">
                                                        <option selected value= "val1">Sans categorie</option>
                                                         <?php
                                                            $sql11="SELECT * FROM `". $nomtableCategorie."`";
                                                            $res11=mysql_query($sql11) or die ("insertion impossible Produit".mysql_error());
                                                            while($ligne2 = mysql_fetch_row($res11)) {
                                                                echo'<option  value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';

                                                              } ?>
                                                      
                                                    </select>
                                                  </td></tr>
                                                  <tr class="div-categorie"><td><div class="help-block label label-danger" id="helpCategorie"></div></td></tr>
                                                </div>



                                                <div class="form-group">
                                                  <tr class="div-uniteStock"><td><label for="uniteStock"> UNITE STOCK (U.S.) <font color="red">*</font> </label></td></tr>

                                                  <tr class="div-uniteStock"><td>
                                                    <select class="form-control" name="uniteStock" id="uniteStock">
                                                         <?php
                                                            $sql11="SELECT * FROM `unitestock`";
                                                            $res11=mysql_query($sql11);
                                                            while($ligne2 = mysql_fetch_row($res11)) {
                                                                echo'<option value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';

                                                              } ?>
                                                    </select>
                                                  </td></tr>
                                                  <tr class="div-uniteStock"><td><div class="help-block label label-danger" id="helpUniteStock"></div></td></tr>
                                                </div>


                                                <div class="form-group" >
                                                  <tr class="div-nbArticleUniteStock"><td><label for="nbArticleUniteStock">NOMBRE ARTICLE(S) U.S. <font color="red">*</font></label></td></tr>
                                                  <tr class="div-nbArticleUniteStock"><td><input type="number" class="form-control" placeholder="Nombre Article de l'Unite Stock" id="nbArticleUniteStock" name="nbArticleUniteStock" value="" required /></td></tr>
                                                  <tr class="div-nbArticleUniteStock"><td><div class="help-block label label-danger" id="helpNbArticleUniteStock"></div></td></tr>
                                                </div>

                                                <div class="form-group" >
                                                  <tr class="div-seuilStock"><td><label for="seuilStock">SEUIL DU STOCK</label></td></tr>
                                                  <tr class="div-seuilStock"><td><input type="number" class="form-control" placeholder="Seuil du stock" id="seuil" name="seuil" value="" /></td></tr>
                                                  <tr class="div-seuilStock"><td><div class="help-block label label-danger" id="helpSeuilStock"></div></td></tr>
                                                </div>


                                                <div class="form-group" >
                                                  <tr class="div-prix"><td><label for="prix">PRIX UNITAIRE </label></td></tr>
                                                  <tr class="div-prix"><td><input type="number" class="form-control" placeholder="Prix Unitaire" id="prix" name="prix" value="" /></td></tr>
                                                  <tr class="div-prix"><td><div class="help-block label label-danger" id="helpPrixUnitaire"></div></td></tr>
                                                </div>

                                                <div class="form-group" >
                                                  <tr class="div-prixuniteStock"><td><label for="prixuniteStock">PRIX UNITE STOCK</label></td></tr>
                                                  <tr class="div-prixuniteStock"><td><input type="number" class="form-control" placeholder="Prix Unite Stock" id="prixuniteStock" name="prixuniteStock" value="" /></td></tr>
                                                  <tr class="div-prixuniteStock"><td><div class="help-block label label-danger" id="helpPrixUniteStock"></div></td></tr>
                                                </div>



                                                <tr><td align="left">
                                                  <font color="red">Les champs qui ont (*) sont obligatoires</font><br />
                                                  <input type="submit" class="boutonbasic" name="inserer" value="ENVOYER  >>">
                                                    </td>
                                                </tr>
                                 </form>
                         </div>
                     </div>
                 </div>
       </div>
   
    <?php 


	
	
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
						<th>QUANTITE</th>
						<th>FORME</th>
						<th>RESTANT</th>
						<th>EXPIRATION</th>
						<th>OPERATIONS</th>
					</tr></thead>
					<tfoot><tr>
						<th>REFERENCE</th>
						<th>QUANTITE</th>
						<th>FORME</th>
						<th>RESTANT</th>
						<th>EXPIRATION</th>
						<th>OPERATIONS</th>
					</tr></tfoot>';
                      while($tab3=mysql_fetch_array($res3)){
                              echo'<tr><td>'.$tab3["designation"].'</td>
                              <td>'.$tab3["quantiteStockinitial"].'</td>';

                              $sql5='SELECT * from  `'.$nomtableDesignation.'` where designation="'.$tab3["designation"].'"';
						      $res5=mysql_query($sql5);
                              $tab5=mysql_fetch_array($res5);

                              echo'<td>'.$tab5["forme"].'</td>
                              <td>'.$tab3["quantiteStockCourant"].'</td>
                              <td>'.$tab3["dateExpiration"].'</td>
                              <td><a href="#">
                              <img src="images/edit.png" align="middle" alt="modifier" data-toggle="modal" data-target="#imgmodifier'.$tab3["idStock"].'" /></a>&nbsp;&nbsp;
                              <a href="#">'.
                              '<img src="images/drop.png" align="middle" alt="supprimer" data-toggle="modal" data-target="#imgsup'.$tab3["idStock"].'" /></a>
                              <a href="codeBarreStock.php?iDS='.$tab3["idStock"].'&iDD='.$tab3["designation"].'">'.
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
                                  <tr class="div-quantite"><td><label for="quantite">QUANTITE DU STOCK <font color="red">*</font></label></td></tr>
								  <tr class="div-quantite"><td><input type="number" class="form-control" id="stock" name="stock" size="35" value="'.$tab3["quantiteStockinitial"].'" required disabled="" /></td></tr>
                                  <tr class="div-quantite"><td><div class="help-block label label-danger" id="helpQuantite"></div></td></tr>
                                </div>
								
									<div class="form-group">
                                  <tr class="div-uniteStock"><td><label for="uniteStock"> UNITE DU STOCK <font color="red">*</font></label></td></tr>
                                  <tr class="div-uniteStock"><td>
                                    <select class="form-control" name="uniteStock" id="uniteStock" disabled="">
											<option selected value= "'.$tab3["uniteStock"].'">'.$tab3["uniteStock"].'</option>';                                       
                                            $sql11="SELECT * FROM `unitestock`";
                                            $res11=mysql_query($sql11);
                                            while($ligne2 = mysql_fetch_row($res11)) {
                                                echo'<option value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
                                              }
                                    echo'</select>
                                  </td></tr>
                                  <tr class="div-uniteStock"><td><div class="help-block label label-danger" id="helpUniteStock"></div></td></tr>
                                </div>	

							
								
								
								
								
								
								
								
								
								<div class="form-group" >
                                  <tr class="div-prix"><td><label for="prix">QUANTITE INITIALE </label></td></tr>
                                  <tr class="div-prix"><td><input type="number" class="form-control" id="totalArticleStock" name="totalArticleStock" value="'.$tab3["totalArticleStock"].'" disabled="" /></td></tr>
                                  <tr class="div-prix"><td><div class="help-block label label-danger" id="helpTotalArticleStock"></div></td></tr>
                                </div>
								
								<div class="form-group" >
                                  <tr class="div-prix"><td><label for="prix">QUANTITE COURANTE (RESTE) </label></td></tr>
                                  <tr class="div-prix"><td><input type="number" class="form-control" id="quantiteStockCourant" name="quantiteStockCourant" value="'.$tab3["quantiteStockCourant"].'" disabled=""/></td></tr>
                                  <tr class="div-prix"><td><div class="help-block label label-danger" id="helpQuantiteStockCourant"></div></td></tr>
                                </div>
								
								 <div class="form-group" >
                                  <tr class="div-prix"><td><label for="prix">PRIX UNITAIRE </label></td></tr>
                                  <tr class="div-prix"><td><input type="number" class="form-control" id="prixunitaire" name="prixunitaire" value="'.$tab3["prixunitaire"].'" /></td></tr>
                                  <tr class="div-prix"><td><div class="help-block label label-danger" id="helpPrixUnitaire"></div></td></tr>
                                </div>

                                <div class="form-group" >
                                  <tr class="div-prixuniteStock"><td><label for="prixuniteStock">PRIX UNITE STOCK</label></td></tr>
                                  <tr class="div-prixuniteStock"><td><input type="number" class="form-control" id="prixuniteStock" name="prixuniteStock" value="'.$tab3["prixuniteStock"].'" /></td></tr>
                                  <tr class="div-prixuniteStock"><td><div class="help-block label label-danger" id="helpPrixUniteStock"></div></td></tr>
                                </div>
 
                                <div class="form-group" >
                                  <tr class="div-dateEpiration"><td><label for="dateEpiration">DATE EXPIRATION</label></td></tr>
                                  <tr class="div-dateEpiration"><td><input type="date" class="form-control" id="dateExpiration" name="dateExpiration" value="'.$tab3["dateExpiration"].'" /></td></tr>
                                  <tr class="div-dateEpiration"><td><div class="help-block label label-danger" id="helpDateEpiration"></div></td></tr>
                                </div>
																
																<div class="form-group">
                                    <tr class="div-PrixRSTOCK"><td><label for="PrixRSTOCK">PRIX DE REVIENT</label></td></tr>
                                    <tr class="div-PrixRSTOCK"><td><input type="number" class="form-control" placeholder="Le prix de revient ici..."  id="prixDeRevientDuStock" name="prixDeRevientDuStock" value="'.$tab3["prixDeRevientDuStock"].'" /></td></tr>
                                    <tr class="div-PrixRSTOCK"><td><div class="help-block" id="helpPrixRSTOCK"></div></td></tr>
                                
                                </div>
																
																
																';
								
								}else{
								
						echo '<div class="form-group">
                                  <tr class="div-quantite"><td><label for="quantite">QUANTITE A STOCKER</label></td></tr>
								  <tr class="div-quantite"><td><input type="number" class="form-control" id="stock" name="stock" size="35" value="'.$tab3["quantiteStockinitial"].'" disabled="" /></td></tr>
                                  <tr class="div-quantite"><td><div class="help-block label label-danger" id="helpQuantite"></div></td></tr>
                                </div>
								
								
								<div class="form-group">
                                  <tr class="div-uniteStock"><td><label for="uniteStock"> UNITE STOCK </label></td></tr>
                                  <tr class="div-uniteStock"><td>
                                    <select class="form-control" name="uniteStock" id="uniteStock" disabled="">
											<option selected value= "'.$tab3["uniteStock"].'">'.$tab3["uniteStock"].'</option>';                                       
                                            $sql11="SELECT * FROM `unitestock`";
                                            $res11=mysql_query($sql11);
                                            while($ligne2 = mysql_fetch_row($res11)) {
                                                echo'<option value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
                                              }
                                    echo'</select>
                                  </td></tr>
                                  <tr class="div-uniteStock"><td><div class="help-block label label-danger" id="helpUniteStock"></div></td></tr>
                                </div>	

								<div class="form-group" >
                                  <tr class="div-prix"><td><label for="prix">QUANTITE INITIALE </label></td></tr>
                                  <tr class="div-prix"><td><input type="number" class="form-control" id="totalArticleStock" name="totalArticleStock" value="'.$tab3["totalArticleStock"].'" disabled="" /></td></tr>
                                  <tr class="div-prix"><td><div class="help-block label label-danger" id="helpTotalArticleStock"></div></td></tr>
                                </div>
								
								<div class="form-group" >
                                  <tr class="div-prix"><td><label for="prix">QUANTITE COURANTE (RESTE) </label></td></tr>
                                  <tr class="div-prix"><td><input type="number" class="form-control" id="quantiteStockCourant" name="quantiteStockCourant" value="'.$tab3["quantiteStockCourant"].'" disabled=""/></td></tr>
                                  <tr class="div-prix"><td><div class="help-block label label-danger" id="helpQuantiteStockCourant"></div></td></tr>
                                </div>
							
								 <div class="form-group" >
                                  <tr class="div-prix"><td><label for="prix">PRIX UNITAIRE </label></td></tr>
                                  <tr class="div-prix"><td><input type="number" class="form-control" id="prixunitaire" name="prixunitaire" value="'.$tab3["prixunitaire"].'" /></td></tr>
                                  <tr class="div-prix"><td><div class="help-block label label-danger" id="helpPrixUnitaire"></div></td></tr>
                                </div>

                                <div class="form-group" >
                                  <tr class="div-prixuniteStock"><td><label for="prixuniteStock">PRIX UNITE STOCK</label></td></tr>
                                  <tr class="div-prixuniteStock"><td><input type="number" class="form-control" id="prixuniteStock" name="prixuniteStock" value="'.$tab3["prixuniteStock"].'" /></td></tr>
                                  <tr class="div-prixuniteStock"><td><div class="help-block label label-danger" id="helpPrixUniteStock"></div></td></tr>
                                </div>
 
                                <div class="form-group" >
                                  <tr class="div-dateEpiration"><td><label for="dateEpiration">DATE EXPIRATION</label></td></tr>
                                  <tr class="div-dateEpiration"><td><input type="date" class="form-control" id="dateExpiration" name="dateExpiration" value="'.$tab3["dateExpiration"].'" disabled="" /></td></tr>
                                  <tr class="div-dateEpiration"><td><div class="help-block label label-danger" id="helpDateEpiration"></div></td></tr>
                                </div>
																
																<div class="form-group">
                                    <tr class="div-PrixRSTOCK"><td><label for="PrixRSTOCK">PRIX DE REVIENT</label></td></tr>
                                    <tr class="div-PrixRSTOCK"><td><input type="number" class="form-control" placeholder="Le prix de revient ici..."  id="prixDeRevientDuStock" name="prixDeRevientDuStock" value="'.$tab3["prixDeRevientDuStock"].'" /></td></tr>
                                    <tr class="div-PrixRSTOCK"><td><div class="help-block" id="helpPrixRSTOCK"></div></td></tr>
                                
                                </div>
																
																';								
								
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
                                  <tr class="div-quantite"><td><label for="quantite">QUANTITE A STOCKER</label></td></tr>
								  <tr class="div-quantite"><td><input type="number" class="form-control" id="stock" name="stock" size="35" value="'.$tab3["quantiteStockinitial"].'" disabled="" /></td></tr>
                                  <tr class="div-quantite"><td><div class="help-block label label-danger" id="helpQuantite"></div></td></tr>
                                </div>
								
								
								<div class="form-group">
                                  <tr class="div-uniteStock"><td><label for="uniteStock"> UNITE STOCK </label></td></tr>
                                  <tr class="div-uniteStock"><td>
                                    <select class="form-control" name="uniteStock" id="uniteStock" disabled="">
											<option selected value= "'.$tab3["uniteStock"].'">'.$tab3["uniteStock"].'</option>';                                       
                                            $sql11="SELECT * FROM `unitestock`";
                                            $res11=mysql_query($sql11);
                                            while($ligne2 = mysql_fetch_row($res11)) {
                                                echo'<option value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';
                                              }
                                    echo'</select>
                                  </td></tr>
                                  <tr class="div-uniteStock"><td><div class="help-block label label-danger" id="helpUniteStock"></div></td></tr>
                                </div>	

								<div class="form-group" >
                                  <tr class="div-nbreArticleUS"><td><label for="nbreArticleUS"> NOMBRE ARTICLES DANS US</label></td></tr>
                                  <tr class="div-nbreArticleUS"><td><input type="number" class="form-control" id="nombreArticle" name="nombreArticle" value="'.$tab3["nbreArticleUniteStock"].'" disabled="" /></td></tr>
                                  <tr class="div-nbreArticleUS"><td><div class="help-block label label-danger" id="helpnbreArticleUS"></div></td></tr>
                                </div>
							
								 <div class="form-group" >
                                  <tr class="div-prix"><td><label for="prix">PRIX UNITAIRE </label></td></tr>
                                  <tr class="div-prix"><td><input type="number" class="form-control" id="prixunitaire" name="prixunitaire" value="'.$tab3["prixunitaire"].'" disabled="" /></td></tr>
                                  <tr class="div-prix"><td><div class="help-block label label-danger" id="helpPrixUnitaire"></div></td></tr>
                                </div>

                                <div class="form-group" >
                                  <tr class="div-prixuniteStock"><td><label for="prixuniteStock">PRIX UNITE STOCK</label></td></tr>
                                  <tr class="div-prixuniteStock"><td><input type="number" class="form-control" id="prixuniteStock" name="prixuniteStock" value="'.$tab3["prixuniteStock"].'" disabled="" /></td></tr>
                                  <tr class="div-prixuniteStock"><td><div class="help-block label label-danger" id="helpPrixUniteStock"></div></td></tr>
                                </div>
 
                                <div class="form-group" >
                                  <tr class="div-dateEpiration"><td><label for="dateEpiration">DATE EXPIRATION</label></td></tr>
                                  <tr class="div-dateEpiration"><td><input type="date" class="form-control" id="dateExpiration" name="dateExpiration" value="'.$tab3["dateExpiration"].'" disabled="" /></td></tr>
                                  <tr class="div-dateEpiration"><td><div class="help-block label label-danger" id="helpDateEpiration"></div></td></tr>
                                </div>
																
																<div class="form-group">
                                    <tr class="div-PrixRSTOCK"><td><label for="PrixRSTOCK">PRIX DE REVIENT</label></td></tr>
                                    <tr class="div-PrixRSTOCK"><td><input type="number" class="form-control" placeholder="Le prix de revient ici..."  id="prixDeRevientDuStock" name="prixDeRevientDuStock" value="'.$tab3["prixDeRevientDuStock"].'" disabled="" /></td></tr>
                                    <tr class="div-PrixRSTOCK"><td><div class="help-block" id="helpPrixRSTOCK"></div></td></tr>
                                
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
	  
	 /* }else{
echo'</br></br><div class="container" align="center">
        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#STOCKPRODUITDETAIL">LISTE POUR INITIALISATION DES STOCKS DE PRODUITS</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="STOCKPRODUITDETAIL">';
                   $sql3='SELECT * from  `'.$nomtableDesignation.'` where classe =0 order by idDesignation desc';
                    if($res3=mysql_query($sql3)){
                    
					echo'<form name="formulaireInitialStock" method="post" action="gestionStock.php"><table id="exemple" class="display" border="1"><thead><tr>
						<th>REFERENCE</th>
						<th>QUANTITE</th>
						<th>UNITE STOCK</th>
						<th>PRIX UNITAIRE</th>
						<th>PRIX UNITE STOCK</th>
						<th>DATE EXPIRATION</th>
						<th>OPERATIONS</th>
					</tr></thead>
					<tfoot><tr>
						<th>REFERENCE</th>
						<th>QUANTITE</th>
						<th>UNITE STOCK</th>
						<th>PRIX UNITAIRE</th>
						<th>PRIX UNITE STOCK</th>
						<th>DATE EXPIRATION</th>
						<th>OPERATIONS</th>
					</tr></tfoot>';
					
					
					 $j=0;
                      while($tab3=mysql_fetch_array($res3)){
					  $j=$j+1;
							$sql5='SELECT * from  `'.$nomtableStock.'` where designation="'.$tab3["designation"].'"';
							$res5=mysql_query($sql5);
							
							if(!mysql_num_rows($res5)){
                              echo'<tr><form class="form" id="form" method="post" action="gestionStock.php"><td>'.$tab3["designation"].'</td>
                              
							  <td><input type="number" name="quantiteAStocke-'.$tab3['idDesignation'].'" id="quantiteAStocke-'.$tab3['idDesignation'].'" min=1 value="" required=""/></td>';
                           if(($tab3["uniteStock"]!='Article')&&($tab3["uniteStock"]!='article')){
							  echo'<td><select class="form-control" name="uniteStock-'.$tab3['idDesignation'].'" id="uniteStock-'.$tab3['idDesignation'].'">
								<option selected value= "'.$tab3["uniteStock"].'">'.$tab3["uniteStock"].'</option> 
								<option value="Article">Article</option>';					
                                    echo'</select></td>';
									}else{
									echo'<td><select class="form-control" name="uniteStock-'.$tab3['idDesignation'].'" id="uniteStock-'.$tab3['idDesignation'].'">
								<option selected value="Article">Article</option>';					
                                    echo'</select></td>';
									
									}
							  
							  echo'<td><input type="number" name="prixUnitaire-'.$tab3['idDesignation'].'" id="prixUnitaire-'.$tab3['idDesignation'].'" value="'.$tab3["prix"].'"/></td>';
							  
							 if(($tab3["uniteStock"]!='Article')&&($tab3["uniteStock"]!='article')){
							  echo'<td><input type="number" name="prixuniteStock-'.$tab3['idDesignation'].'" id="prixuniteStock-'.$tab3['idDesignation'].'" value="'.$tab3["prixuniteStock"].'" required="" /></td>';
							  }else{
							  echo'<td><input type="number" name="prixuniteStock-'.$tab3['idDesignation'].'" id="prixuniteStock-'.$tab3['idDesignation'].'" value="" disabled=""/></td>';
							  }
                              echo'<td><input type="Date" name="dateExpiration-'.$tab3['idDesignation'].'" id="dateExpiration-'.$tab3['idDesignation'].'" value=""/></td>
                             
							 <td><input type="hidden" name="idDesignation-btnAjouterStock" value="'.$tab3["idDesignation"].'"/><input type="hidden" name="designation-'.$tab3['idDesignation'].'" value="'.$tab3["designation"].'"/><button type="submit" name="btnAjouterStock-'.$tab3['idDesignation'].'" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i>AJOUTER</button></td>
							 
							 </form></tr>';

                      }else{
					  $tab5=mysql_fetch_array($res5);
					  echo'<tr><form class="form" id="form" method="post" action="gestionStock.php"><td>'.$tab5["designation"].'</td>
                              
							  <td><input type="number" name="quantiteAStocke-'.$tab5['idDesignation'].'" id="quantiteAStocke-'.$tab5['idDesignation'].'" value="'.$tab5['quantiteStockinitial'].'" disabled=""/></td>';
                           
							  echo'<td><select class="form-control" name="uniteStock-'.$tab3['idDesignation'].'" id="uniteStock-'.$tab3['idDesignation'].'" disabled="">
								<option selected value= "'.$tab5["uniteStock"].'">'.$tab5["uniteStock"].'</option> 
								<option value="Article">Article</option>';					
                                    echo'</select></td>';
									
							  
							  echo'<td><input type="number" name="prixUnitaire-'.$tab3['idDesignation'].'" id="prixUnitaire-'.$tab3['idDesignation'].'" value="'.$tab5["prixunitaire"].'" disabled=""/></td>';
							  
							 if(($tab3["uniteStock"]!='Article')&&($tab3["uniteStock"]!='article')){
							  echo'<td><input type="number" name="prixuniteStock-'.$tab3['idDesignation'].'" id="prixuniteStock-'.$tab3['idDesignation'].'" value="'.$tab5["prixuniteStock"].'" disabled="" /></td>';
							  }else{
							  echo'<td><input type="number" name="prixuniteStock-'.$tab3['idDesignation'].'" id="prixuniteStock-'.$tab3['idDesignation'].'" value="" disabled=""/></td>';
							  }
                              echo'<td><input type="Date" name="dateExpiration-'.$tab3['idDesignation'].'" id="dateExpiration-'.$tab3['idDesignation'].'" value="'.$tab5['dateExpiration'].'" disabled="" /></td>
                             
							 <td><button type="submit" name="btnAjouterStock-'.$tab3['idDesignation'].'" class="btn btn-success" disabled=""><i class="glyphicon glyphicon-plus"></i>AJOUTER</button></td>
							 
							 </form></tr>';
					  
					  
					  
					  
					  }
					  
				}
					  echo '</table><br/><center>	
							 
							 
							 
	<button type="submit" name="btnAjouterTousLesStocks" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i>AJOUTER TOUS LES STOCKS</button>
	
      <br><br>

							</center>	
																		
																	
                         	</form><br />';
							 
                    
					
					}
					
					
					
                    else{
                      echo'<table class="tableau2" width="80%" align="center" border="1"><th>DESIGNATION</th><th>QUANTITE</th><th>UNITE STOCK</th><th>NOMBRE ARTICLE/US</th><th>DATE EXPIRATION</th><th></th>';
                      echo'<tr><td colspan="6">Liste des Stocks généraux de Produits de la date du '.$dateString.' est pour le moment vide ';
					   echo'</table><br />';
                       }
					   
                   
           echo' </div>

        </div>
      </div>';	  
	  
	  
	  
	  
	  }
*/
echo'</body></html>';


?>
