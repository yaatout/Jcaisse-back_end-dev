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



if (isset($_POST['btnAjouterStock-'.$i])) {



	if ($_POST['quantiteAStocke-'.$i] > 0){

		//echo $i;
		$designationInitiale= $_POST['designation-'.$i];
		$stockInitial= $_POST['quantiteAStocke-'.$i];
		//$formeInitial= @$_POST['forme-'.$i];
		$prixSessionInitial= $_POST['prixSession-'.$i];
		$prixPublicInitial= @$_POST['prixPublic-'.$i];
		$dateExpiration= $_POST['dateExpiration-'.$i];

       // echo $formeInitial;

		$nombreArticleUniteStock=1;

		/*$sql='select * from `'.$nomtableDesignation.'` where designation="'.$designationInitiale.'"';
		//echo $sql;
		$res=mysql_query($sql);
		if(mysql_num_rows($res)){
			if($tab=mysql_fetch_array($res))
				$nombreArticleUniteStock  = 1;        */


				if ($dateExpiration){
				//echo 'je suis un';
				$sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,prixSession,prixPublic,dateStockage, quantiteStockCourant,dateExpiration) VALUES('.$i.',"'.mysql_real_escape_string($designationInitiale).'",'.$stockInitial.','.$prixSessionInitial.','.$prixPublicInitial.',"'.$dateString.'",'.$stockInitial.',"'.$dateExpiration.'")';
				//echo $sql1;
				$res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;
				echo '<script type="text/javascript"> alert("ALERT : LE STOCK EST INITIALISE AVEC SUCCESS. VEUILLEZ LE VERIFIER DANS GESTION DE STOCK DANS LE MENU STOCK ...");</script>';
				}else{
				//echo 'je suis deux';
					$sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,prixSession,prixPublic,dateStockage, quantiteStockCourant) VALUES('.$i.',"'.mysql_real_escape_string($designationInitiale).'",'.$stockInitial.','.$prixSessionInitial.','.$prixPublicInitial.',"'.$dateString.'",'.$stockInitial.')';
				//echo $sql1;
				$res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;
				echo '<script type="text/javascript"> alert("ALERT : LE STOCK EST INITIALISE AVEC SUCCESS. VEUILLEZ LE VERIFIER DANS GESTION DE STOCK DANS LE MENU STOCK ...");</script>';

				}

		}


	}

if (isset($_POST['btnAjouterTousLesStocks'])) {
	//echo 'la suite-';
	//echo $_POST['nombre'];

	$sql3='SELECT * from  `'.$nomtableDesignation.'` where classe =0 order by idDesignation desc';
	if($res3=mysql_query($sql3)){
		while($tab3=mysql_fetch_array($res3)){

			$i     =$tab3["idDesignation"];
			$nombreArticleUniteStock  = $tab3["nbreArticleUniteStock"];

			$sql4="SELECT * FROM `".$nomtableStock."` where idDesignation=".$i;
			$res4=mysql_query($sql4) or die ("select stock impossible =>".mysql_error());
			if(!mysql_num_rows($res4)){
			   echo $_POST['quantiteAStocke-'.$i];
			   if (@$_POST['quantiteAStocke-'.$i] > 0){

    				$designationInitiale= $_POST['designation-'.$i];
    				$stockInitial= $_POST['quantiteAStocke-'.$i];
    				$formeInitial= $_POST['forme-'.$i];
    				$prixSessionInitial= $_POST['prixUnitaire-'.$i];
    				$prixPublicInitial= @$_POST['prixuniteStock-'.$i];
    				$dateExpiration= $_POST['dateExpiration-'.$i];

					if ($dateExpiration){
					//echo 'je suis un';
					$sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,forme,prixunitaire,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration) VALUES('.$i.',"'.mysql_real_escape_string($designationInitiale).'",'.$stockInitial.',"'.mysql_real_escape_string($formeInitial).'",'.$prixSessionInitial.','.$nombreArticleUniteStock.','.$stockInitial.',"'.$dateString.'",'.$stockInitial.',"'.$dateExpiration.'")';
					//echo $sql1;
					$res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;
					echo '<script type="text/javascript"> alert("ALERT : LE STOCK EST INITIALISE AVEC SUCCESS. VEUILLEZ LE VERIFIER DANS GESTION DE STOCK DANS LE MENU STOCK ...");</script>';

					}else{
					//echo 'je suis deux';
						$sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,forme,prixunitaire,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant) VALUES('.$i.',"'.mysql_real_escape_string($designationInitiale).'",'.$stockInitial.',"'.mysql_real_escape_string($formeInitial).'",'.$prixSessionInitial.','.$nombreArticleUniteStock.','.$stockInitial.',"'.$dateString.'",'.$stockInitial.')';
					//echo $sql1;
					$res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;
					echo '<script type="text/javascript"> alert("ALERT : LE STOCK EST INITIALISE AVEC SUCCESS. VEUILLEZ LE VERIFIER DANS GESTION DE STOCK DANS LE MENU STOCK ...");</script>';

					}



			}
		}
		}
	}
}







/*************  LES BOUTONS IMPORTATION ET EXPORTATION EN CSV ET AJOUTER STOCK *******************/
/*************************************************************************************************/

require('entetehtml.php');
echo'
   <body >';
   require('header.php');

echo '<br/><br />';


/*****************************/





	echo'
	<div class="container" align="center">
		<ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#STOCKPRODUITDETAIL">LISTE POUR INITIALISATION DES STOCKS DE PRODUITS</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane fade in active" id="STOCKPRODUITDETAIL">
				<div class="table-responsive">
					<table id="tableStock" class="display tabDesign" class="tableau3" align="left" border="1">
						<thead>
						<tr id="thStock">
							<th>Reference</th>
							<th>Categorie</th>
							<th>Forme</th>
							<th>Quantite</th>
							<th>Prix Session</th>
							<th>Prix Public</th>
							<th>Expiration</th>
							<th>Operations</th> 
						</tr>
						</thead>
					</table>
				
					<script type="text/javascript">
						$(document).ready(function() {
							$("#tableStock").dataTable({
							"bProcessing": true,
							"sAjaxSource": "ajax/initialisation-PharmacieAjax.php",
							"aoColumns": [
									{ mData: "0" } ,
									{ mData: "1" },
									{ mData: "2" },
									{ mData: "3" },
									{ mData: "4" },
									{ mData: "5" },
									{ mData: "6" },
									{ mData: "7" },
								],
								
							});  
						});
					</script>
				</div>
			</div>
		</div>
	</div>';	  
	  
	  
	  
	  
	  

echo'</body></html>';



?>
