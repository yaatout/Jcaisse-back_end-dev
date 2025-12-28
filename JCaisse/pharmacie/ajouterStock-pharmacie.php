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





echo'</br></br><div class="container" align="center">
        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#STOCKPRODUITDETAIL">LISTE POUR INITIALISATION DES STOCKS DE PRODUITS</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="STOCKPRODUITDETAIL">';
                   $sql3='SELECT * from  `'.$nomtableDesignation.'` where classe =0 order by idDesignation desc';
                    if($res3=mysql_query($sql3)){

					echo'<form name="formulaireInitialStock" method="post" action="ajouterStock.php"><table id="exemple" class="display" border="1"><thead><tr>
						<th>REFERENCE</th>
						<th>QUANTITE</th>
						<th>FORME</th>
						<th>PRIX SESSION</th>
						<th>PRIX PUBLIC</th>
						<th>DATE EXPIRATION</th>
						<th>OPERATIONS</th>
					</tr></thead>
					<tfoot><tr>
						<th>REFERENCE</th>
						<th>QUANTITE</th>
						<th>FORME</th>
						<th>PRIX SESSION</th>
						<th>PRIX PUBLIC</th>
						<th>DATE EXPIRATION</th>
						<th>OPERATIONS</th>
					</tr></tfoot>';


					 //$j=0;
                      while($tab3=mysql_fetch_array($res3)){
					  //$j=$j+1;
							$sql5='SELECT * from  `'.$nomtableStock.'` where designation="'.$tab3["designation"].'"';
							$res5=mysql_query($sql5);
							
							if(!mysql_num_rows($res5)){

                            echo'<tr><form class="form" id="form-'.$tab3['idDesignation'].'" method="post" action="ajouterStock.php"><td>'.$tab3["designation"].'</td>
                              
							  <td><input type="number" name="quantiteAStocke-'.$tab3['idDesignation'].'" id="quantiteAStocke-'.$tab3['idDesignation'].'" min=1 value="" required=""/></td>';

						    echo'<td><select class="form-control" name="forme-'.$tab3['idDesignation'].'" id="forme-'.$tab3['idDesignation'].'" disabled="">
								<option selected value="'.$tab3["forme"].'">'.$tab3["forme"].'</option>';
                                    echo'</select></td>';
									
                           // echo'<td><input type="text" name="forme-'.$tab3['idDesignation'].'" id="forme-'.$tab3['idDesignation'].'" value="'.$tab3["forme"].'" disabled=""/></td>';


						    echo'<td><input type="number" name="prixSession-'.$tab3['idDesignation'].'" id="prixSession-'.$tab3['idDesignation'].'" value="'.$tab3["prixSession"].'"/></td>';

							echo'<td><input type="number" name="prixPublic-'.$tab3['idDesignation'].'" id="prixPublic-'.$tab3['idDesignation'].'" value="'.$tab3["prixPublic"].'" required="" /></td>';

                            echo'<td><input type="Date" name="dateExpiration-'.$tab3['idDesignation'].'" id="dateExpiration-'.$tab3['idDesignation'].'" value=""/></td>

							 <td><input type="hidden" name="idDesignation-btnAjouterStock" value="'.$tab3["idDesignation"].'"/><input type="hidden" name="designation-'.$tab3['idDesignation'].'" value="'.$tab3["designation"].'"/><button type="submit" name="btnAjouterStock-'.$tab3['idDesignation'].'" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i>AJOUTER</button></td>

							 </form></tr>';

                      }else{
					  $tab5=mysql_fetch_array($res5);
					        echo'<form class="form" id="form-'.$tab3['idDesignation'].'" method="post" action="ajouterStock.php"><tr><td>'.$tab5["designation"].'</td>

							<td><input type="number" name="quantiteAStocke-'.$tab5['idDesignation'].'" id="quantiteAStocke-'.$tab5['idDesignation'].'" value="'.$tab5['quantiteStockinitial'].'" disabled=""/></td>';

							echo'<td><select class="form-control" name="forme-'.$tab3['idDesignation'].'" id="forme-'.$tab3['idDesignation'].'" disabled="">
								<option selected value= "'.$tab3["forme"].'">'.$tab3["forme"].'</option>
								<option value="'.$tab3["forme"].'">'.$tab3["forme"].'</option>';
                            echo'</select></td>';

							echo'<td><input type="number" name="prixSession-'.$tab3['idDesignation'].'" id="prixSession-'.$tab3['idDesignation'].'" value="'.$tab5["prixSession"].'" disabled=""/></td>';
							  
							echo'<td><input type="number" name="prixPublic-'.$tab3['idDesignation'].'" id="prixPublic-'.$tab3['idDesignation'].'" value="'.$tab5["prixPublic"].'" disabled="" /></td>';

                            echo'<td><input type="Date" name="dateExpiration-'.$tab3['idDesignation'].'" id="dateExpiration-'.$tab3['idDesignation'].'" value="'.$tab5['dateExpiration'].'" disabled="" /></td>';

							echo'<td><button type="submit" name="btnAjouterStock-'.$tab3['idDesignation'].'" class="btn btn-success" disabled=""><i class="glyphicon glyphicon-plus"></i>AJOUTER</button></td>
							 
							 </tr></form>';
					  
					  
					  
					  
					  }
					  
				}
					  echo '</table><br/><center>';	


							echo '</center>	
																		
																	
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
	  
	  
	  
	  
	  

echo'</body></html>';



?>
