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
session_start();
if(!$_SESSION['iduser']){
	header('Location:../index.php');
}

require('connection.php');

require('declarationVariables.php');

/**********************/



$designation      =@htmlentities($_POST["designation"]);
$categorie2       =@htmlentities($_POST["categorie2"]);
$idDesignation    =@$_POST["idDesignation"];

$stock            =@$_POST["stock"];
$uniteStock       =@$_POST["uniteStock"];
$nbreArticleUS    =@$_POST["nbreArticleUniteStock"];

$prixuniteStock   =@$_POST["prixuniteStock"];
$prixunitaire     =@$_POST["prixunitaire"];

$prixSession         =@$_POST["prixSession"];
$prixPublic     =@$_POST["prixPublic"];

$forme        =@$_POST["forme"];
$tableau      =@$_POST["tableau"];

$nombreArticle    =@$_POST["nombreArticle"];
$dateExpiration   =@$_POST["dateExpiration"];

$insererStock     =@$_POST["insererStock"];
$supprimer        =@$_POST["supprimer"];
$modifier         =@$_POST["modifier"];
$annuler          =@$_POST["annuler"];
$insererDesignation   =@$_POST["insererDesignation"];


$typeCategorie=$_SESSION["type"]."-".$_SESSION["categorie"];
$catalogueTypeCateg='aaa-catalogue-'.$typeCategorie;
$categorieTypeCateg='aaa-categorie-'.$typeCategorie;

/***************/
if(isset($_POST["btnInitiliserAutoCatalogue"])) {
	$sql3='SELECT * from  `'.$catalogueTypeCateg.'` ';
	 if($res3=mysql_query($sql3)){
			while($tab3=mysql_fetch_array($res3)){

				$sql1="insert into `".$nomtableDesignation."`
						(designation,categorie,uniteStock,prix,nbreArticleUniteStock,prixuniteStock,codeBarreDesignation,codeBarreuniteStock,classe,idFusion) values
						('".$tab3['designation']."','".$tab3['categorie']."','".$tab3['uniteStock']."','".$tab3['prix']."','".$tab3['nbreArticleUniteStock']."','"
						.$tab3['prixuniteStock']."','".$tab3['codeBarreDesignation']."','".$tab3['codeBarreuniteStock']."','".$tab3['classe']."','".$tab3['idFusion']."')";
			// $res1=@mysql_query($sql1) ;
				$res1=@mysql_query($sql1) or die ("insertion stock 3 impossible".mysql_error()) ;
						}
			}

			$sql15="SELECT * FROM `".$categorieTypeCateg."`";
			 if ($res15 = mysql_query($sql15)) {
				 while($tab4=mysql_fetch_array($res15)){
						$sql16="insert into `".$nomtableCategorie."`
								(nomCategorie,categorieParent) values
								('".$tab4['nomCategorie']."','".$tab4['categorieParent']."')";
								$res16=@mysql_query($sql16);
						}
					}

	}


$i     =@$_POST["idDesignation-btnAjouterStock"];

//echo $i;

if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

 require('initialisationCatalogue-pharmacie.php');

}else{



/*************  IMPORTATION ET EXPORTATION CSV *******************/
/*****************************************************************/


/*************  LES BOUTONS IMPORTATION ET EXPORTATION EN CSV ET AJOUTER STOCK *******************/
/*************************************************************************************************/

require('entetehtml.php');
echo'
   <body >';
   require('header.php');


?>
	 <!--
		<div class="row" align="center">
			<form name="formulairePersonnel"   method="post" >
				<button type="submit" class="btn btn-success" name="btnInitiliserAutoCatalogue">
					<i class="glyphicon glyphicon-plus"></i>Initialisation automatique
				</button>
			</form>
		</div>
	 -->

<?php

echo'
    <div class="container-fluid">
        <ul class="nav nav-tabs">
		  <li class="active">
			  <a data-toggle="tab" href="#STOCKPRODUITDETAIL">LISTE POUR INITIALISATION DES REFERENCES DE PRODUITS

			  </a>
          </li> 
            <button type="button" class="btn btn-success pull-right noImpr" id="btnTableInitiale" >
					<i class="glyphicon glyphicon-plus"></i>
			</button>
          <div class="form-group row pull-right">
                <div class="col-sm-12">
                    <select  id="catalogue" class="form-control" onchange="this.form.submit()">';
						echo ' <option value="'.$_SESSION['type'].'-'.$_SESSION['categorie'].'">'.$_SESSION['type'].'-'.$_SESSION['categorie'].'</option> ';
					
                            $sql3='SELECT * from  `aaa-catalogueTotal`  order by id desc';
                            if($res3=mysql_query($sql3)){
                                while($catalogue=mysql_fetch_array($res3)){
									echo '  <option value="'.$catalogue["nom"].'">'.$catalogue["nom"].'</option>';
                                }
                            }
						echo '
                    </select>
                </div>
            </div>
        </ul>
        <div class="tab-content">
			<div class="tab-pane fade in active" id="STOCKPRODUITDETAIL">
				<div class="table-responsive" id="divTableInitiale" style="display:none">
					<table id="tableInitiale" class="table table-bordered table-responsive display" align="left" border="1">
						<thead>
							<tr>
								<th>CATEGORIE</th>
                                <th>REFERENCE</th>
                                <th>CODEBARRE </th>
								<th>UNITE STOCK(US)</th>
								<th>NOMBRE ARTICLE (US)</th>
								<th>PRIX(US)</th>
								<th>PRIX UNITAIRE</th>
								<th>PRIX COMCRT</th>
                                <th>PRIX ACHAT</th>
                                <th>QUANTITE</th>
                                <th>EXPIRATION</th>
								<th>OPERATIONS</th>
							</tr>
						</thead>
						<tbody>
						<tr>
							<td><input type="text" class="form-control" id="categorie" /></td>
                            <td><input type="text" class="form-control" id="designation"  /></td>
                            <td><input type="text" class="form-control" id="codeBarre"  /></td>
							<td><input type="text" class="form-control" id="uniteStock"  /></td>
							<td><input type="text" class="form-control" id="nbreArticleUniteStock"  /></td>
							<td><input type="text" class="form-control" id="prixuniteStock" value="0" /></td>
							<td><input type="text" class="form-control" id="prix" value="0" /></td>
							<td><input type="text" class="form-control" id="prixcommercant" value="0" /></td>
                            <td><input type="text" class="form-control" id="prixachat" value="0" /></td>
                            <td><input type="number" class="form-control" id="quantite"  /></td>
							<td><input type="date" class="form-control" id="dateExpiration"  /></td>
							<td>
								<button type="button" id="btn_init_Produit"  class="btn btn-success">
									<i class="glyphicon glyphicon-plus"></i>AJOUTER
								</button>
							</td>
						</tr>
						</tbody>
					</table>

					<script type="text/javascript">
						$(document).ready(function() {
							$("#tableInitiale").dataTable({
								bFilter: false, 
								bInfo: false,
								paging : false,
								
							});  
						});
					</script>
				</div>
                <div class="table-responsive">
                    <label class="pull-left" for="nbEntreeBInit">Nombre entrées </label>
                    <select class="pull-left" id="nbEntreeBInit">
                    <optgroup>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option> 
                    </optgroup>       
                    </select>
                    <input class="pull-right" type="text" name="" id="searchInputBInit" autocomplete="off" placeholder="Rechercher...">
                    <div id="resultsProductsInitB"><!-- content will be loaded here --></div> ';

            	echo '
                </div>
            </div>
        </div>
    </div>';



echo'</body></html>';

}
?>
