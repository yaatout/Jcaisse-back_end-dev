<?php 
session_start();
mysql_connect("localhost","root","");
mysql_select_db("bdjournalcaisse");

/**********************/
//$date = new DateTime('25-02-2011');
$date = new DateTime();
//R�cup�ration de l'ann�e
$annee =$date->format('Y');
//R�cup�ration du mois
$mois =$date->format('m');
//R�cup�ration du jours
$jour =$date->format('d');

$dateString=$annee.'-'.$mois.'-'.$jour;

$nomtableClient=$_SESSION['nomB']."-client";
$nomtablePagnet=$_SESSION['nomB']."-pagnet";
$nomtableStock=$_SESSION['nomB']."-stock";
$nomtableLigne=$_SESSION['nomB']."-lignepj";
$nomtableDesignation=$_SESSION['nomB']."-designation";

if(!$_SESSION['iduser']){
	header('Location:index.php');
	}


	$idClient=htmlspecialchars(trim($_GET['c'])); 
	$sql3="SELECT * FROM `".$nomtableClient."` where idClient=".$idClient."";
	$res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());
	$client = mysql_fetch_array($res3);

	/*$sql9="SELECT * FROM `".$nomtablePagnet."` where idClient=".$idClient."";
    $res9=mysql_query($sql9) or die ("select nomtablePagnet impossible =>".mysql_error());
    $pagnet = mysql_fetch_array($res14); 
    $totalp=$pagnet['totalp']+$stock['prixunitaire'];*/


if (isset($_POST['btnEnregistrerCodeBarre'])) {

		//idStock->61
		//Montre->19

		$date = new DateTime();
		    $annee =$date->format('Y');
		    $mois =$date->format('m');
		    $jour =$date->format('d');
		    $dateString2=$jour.'-'.$mois.'-'.$annee;

		$codeBarre=htmlspecialchars(trim($_POST['codeBarre']));
		$idPagnet=htmlspecialchars(trim($_POST['idPagnet']));
		//$codeBrute=split("-", $codeBarre);
		$codeBrute=explode("-", $codeBarre);
		var_dump($codeBrute);
		$idStock=$codeBrute[0];
		$idDesignation=$codeBrute[1];
		$numero=$codeBrute[2];

		$sql4="SELECT * FROM `".$nomtableStock."` where idStock=".$idStock."";
  		$res4=mysql_query($sql4) or die ("select stock impossible =>".mysql_error());
  		$stock = mysql_fetch_array($res4);
  		$quantiteStockCourant=$stock['quantiteStockCourant'];

  		if ($stock['quantiteStockCourant']>0) {
  			// mise a jour de la table stock
  			/*$quantiteStockCourant--;
  			$sql5="UPDATE `".$nomtableStock."` set quantiteStockCourant='".$quantiteStockCourant."' where idStock=".$idStock;
			$res5=mysql_query($sql5) or die ("update quantiteStockCourant impossible =>".mysql_error());

			// insertion dans l'historique
			$sql6="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$idDesignation."";
	  		$res6=mysql_query($sql6) or die ("select stock impossible =>".mysql_error());
	  		$design = mysql_fetch_array($res6);
			$sql7="insert into `".$nomtableLigne."` (datepage,designation,unitevente,prixunitevente,quantite,remise,prixtotal,typeligne,idPagnet) 
			values('".$dateString2."','".$design['designation']."','".$design['uniteStock']."','".$design['prix']."','1','0','100000','Entree''".$idPagnet."')";
  			 $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );*/
  		}

		/*	var_dump($codeBrute);
		var_dump($idStock);
		var_dump($designation);
		var_dump($numero);

		$sql1="insert into `".$nomtablePagnet."` (datepagej,type) values('".$dateString2."','".$type."')";
			// var_dump($sql1);
  			 $res1=mysql_query($sql1) or die ("insertion pagnier impossible =>".mysql_error() );
			$message="Client ajouter avec succes";

			array
			    0 => string '16' (length=2)
			    1 => string 'Gomme' (length=5)
			    2 => string '1' (length=1)
		*/
		
  }
if (isset($_POST['btnSavePagnet'])) {

	 $sql13='SELECT * from  `'.$nomtablePagnet.'` order by idPagnet desc LIMIT 0,1';
	 $res13=mysql_query($sql13) or die ("select pagnier impossible =>".mysql_error() );
	 $pagnier = mysql_fetch_array($res13);
	  $sql14="UPDATE `".$nomtablePagnet."` set verrouiller='1' where idPagnet=".$pagnier['idPagnet'];
      $res14=mysql_query($sql14) or die ("update pagnier impossible =>".mysql_error());

		$date = new DateTime();
		$annee =$date->format('Y');
		$mois =$date->format('m');
		$jour =$date->format('d');
		$dateString2=$jour.'-'.$mois.'-'.$annee;
		$type='Bon';
		$sql9="insert into `".$nomtablePagnet."` (datepagej,type,idClient) values('".$dateString2."','".$type."','".$idClient."')";
  		$res9=mysql_query($sql9) or die ("insertion pagnier impossible =>".mysql_error() );
	}	
if (isset($_POST['btnEnregistrerVersement'])) {

		$date = new DateTime();
		$annee =$date->format('Y');
		$mois =$date->format('m');
		$jour =$date->format('d');
		$dateVersement=$jour.'-'.$mois.'-'.$annee;

		$montant=htmlspecialchars(trim($_POST['montant']));
		
		$sql2="insert into versement (idClient,montant,dateVersement) values('".$idClient."','".$montant."','".$dateVersement."')";
  		$res2=mysql_query($sql2) or die ("insertion Versement impossible =>".mysql_error());

  		$solde=$montant+$client['solde'];
  		


  		$sql3="UPDATE `".$nomtableClient."` set solde='".$solde."' where idClient=".$idClient;
		$res3=mysql_query($sql3) or die ("update solde client impossible =>".mysql_error());

		$message="Client ajouter avec succes";		
	}
if (isset($_POST['btnImprimerFacture'])) {
    
    $sql3="UPDATE `".$nomtablePagnet."` set verrouiller='1' where idPagnet=".$_POST['idPagnet'];
      $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());
  }
  if (isset($_POST['btnRetour'])) {
    
    $sql3="DELETE FROM `".$nomtableLigne." where idPagnet=".$_POST['idPagnet']." and designation=".$_POST['designation']." LIMIT 0,1";
     $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());
     if ($res3=@mysql_query($sql3)) {
     	
     $sql5="UPDATE `".$nomtableStock."` set quantiteStockCourant=1 where idStock=".$idStock;
			$res5=mysql_query($sql5) or die ("update quantiteStockCourant impossible =>".mysql_error());
     }
  }
$Total=0;

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <link rel="stylesheet" href="css/bootstrap.css">
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <style>.modal-header, h4, .close {background-color: #5cb85c; color:white !important; text-align: center; font-size: 30px;}.modal-footer {background-color: #f9f9f9;}</style>
    <style media="print" type="text/css">   .noImpr {   display:none;   } </style> 
    <script type="text/javascript" src="prixdesignation.js"></script>
   <script type="text/javascript" src="js/script.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>JOURNAL DE CAISSE SALAM SERVICES SOLUTIONS</title>
</head>
<body>
		<?php 
		  require('header.php');
		?>
		<div class="container" ">
			<div class="jumbotron">
			  <h2>Les pagnets du client : <?php echo $client['prenom']." ".strtoupper($client['nom']); ?> </h2>
			  <?php $sql12="SELECT SUM(totalp) FROM `".$nomtablePagnet."` where idClient=".$idClient." ";
					$res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
									$Total = mysql_fetch_array($res12) ; ?>
			  <p>Total des pagniers : <?php echo $Total[0]; ?></p>
			</div>
			
			
			
   			<div class="modal fade" id="addBon" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	            <div class="modal-dialog" role="document">
	                <div class="modal-content">
	                    <div class="modal-header">
	                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                        <h4 class="modal-title" id="myModalLabel">Ajout un Pagnet</h4>
	                    </div>
	                    <div class="modal-body">
	                    	<form name="formulairePersonnel" method="post" <?php echo  "action=bonPclient.php?c=". $idClient."" ; ?>>
			            	  <h3>Voulez vous vraiment ajouter un nouveau pagnet</h3>
							  <div class="modal-footer">
		                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
		                            <button type="submit" name="btnEnregistrerPagnet" class="btn btn-primary">Ajouter</button>
		                       </div>
							</form>
		                 <!--   <form name="formulairePersonnel" method="post" <?php echo  "action=bonPclient.php?c=". $idClient."" ; ?>>
			            	  <div class="form-group">
							      <label for="inputEmail3" class="control-label">code barre</label>					    
							      <input type="text" class="inputbasic" id="codeBarre" name="codeBarre" value = "" size="35" />
							      <span class="text-danger" ></span>
							  </div>
							  <div class="modal-footer">
		                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
		                            <button type="submit" name="btnEnregistrerPagnet" class="btn btn-primary">Enregistrer</button>
		                       </div>
							</form>-->
	                    </div>

	                </div>
	            </div>
        	</div>
        	<!--<div class="modal fade" id="addBon" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	            <div class="modal-dialog" role="document">
	                <div class="modal-content">
	                    <div class="modal-header">
	                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                        <h4 class="modal-title" id="myModalLabel">Ajout un Pagnet</h4>
	                    </div>
	                    <div class="modal-body">
		                    <form name="formulairePersonnel" method="post" <?php echo  "action=bonPclient.php?c=". $idClient."" ; ?>>
			            	  <div class="form-group">
							      <label for="inputEmail3" class="control-label">Date</label>					    
							      <input type="date" class="inputbasic" id="datePagnet" name="datePagnet" value = "" size="35" />
							      <span class="text-danger" ></span>
							  </div>
							  
							  <div class="form-group">
							      <label for="inputEmail3" class="control-label">Type</label>					    
							      <input type="text" class="form-control" id="inputprenom" name="type"  value="bon">
							      <span class="text-danger" ></span>
							  </div>
							  <div class="modal-footer">
		                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
		                            <button type="submit" name="btnEnregistrerPagnet" class="btn btn-primary">Enregistrer</button>
		                       </div>
							</form>
	                    </div>

	                </div>
	            </div>
        	</div> -->

	        <div class="row">
	        	
	        	<div class="col-lg-9">
	        		<form name="formulairePersonnel" method="post" <?php echo  "action=bonPclient.php?c=". $idClient."" ; ?>>
	        			<button type="submit" class="btn btn-success" name="btnSavePagnet">
	                    <i class="glyphicon glyphicon-plus"></i>Ajouter un Pagnet
	   				</button><br><br>
	   				<!--
	   					<button type="button" class="btn btn-success" data-toggle="modal" data-target="#addBon" name="btnEnregistrerPagnet">
	                    <i class="glyphicon glyphicon-plus"></i>Ajouter un Pagnet
	   				</button><br><br>
	   				-->
	        		</form>
					
	        		<div id="monaccordeon" class="panel-group ">
							<?php 		
								$sql2="SELECT * FROM `".$nomtablePagnet."` where idClient=".$idClient." ORDER BY idPagnet DESC";
								//var_dump($sql2);
								$res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
									while ($pagnet = mysql_fetch_array($res2)){  
										//if ($pagnet['totalp']>0){  ?>
										<div class="panel panel-info">
											<div class="panel-heading">
												<h3 class="panel-title ">
												<a class="accordion-toggle " <?php echo  "href=#item".$pagnet['idPagnet']."" ; ?>
												  dataparent="#monaccordeon"  data-toggle="collapse"  onclick="chargerId(<?php echo  $pagnet['idPagnet']; ?>,<?php echo  "'href=#item".$pagnet['idPagnet']."'" ; ?>)" 
												  > Pagnet <?php echo $pagnet['idPagnet']; ?>
												  
												</a>  <span class="spanDate"> Date: <?php echo $pagnet['datepagej']; ?> </span>
													 <span class="spanTotal">Total pagnet: <?php echo $pagnet['totalp']; ?> </span>
												</h3>
											</div>
											<div  <?php echo  "id=item".$pagnet['idPagnet']."" ; ?> 
											<?php if ($pagnet['verrouiller']==0)  { ?> class="panel-collapse collapse in" <?php }  else { ?> class="panel-collapse collapse " <?php } ?> >
												<div class="panel-body" >  
													<?php 
														if ($pagnet['verrouiller']==0) {  ?>
															<form  class="form-inline pull-left" id="bonForm" method="post"   <?php echo  "action=ajax/panierAjax.php?c=". $idClient."" ; ?> >
														  <input type="text" style="width:150px" class="input-sm
															form-control" placeholder="ajouter nouvelle entrée" name="codeBarre" autofocus="" value="" id="codeBarrePagnet">
															<input type="hidden" name="idPagnet" id="idPagnet" 
															 <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
															 <button type="submit" name="btnEnregistrerCodeBarreAjax" 
															  id="btnEnregistrerCodeBarreAjax" class="btn btn-primary btnxs" ><span class="glyphicon glyphicon-plus" ></span>
															Ajouter</button>
															</form>
															<!--
														<form  class="form-inline pull-left" id="bonForm"
																 method="post"  <?php echo  "action=bonPclient.php?c=". $idClient."" ; ?>>
															<input type="text" style="width:150px" class="input-sm
															form-control" placeholder="ajouter nouvelle entrée" name="codeBarre" autofocus="" value="">
															<input type="hidden" name="idPagnet" 
															 <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
															<button type="submit" name="btnEnregistrerCodeBarreAjax" 
															  id="btnEnregistrerCodeBarreAjax" class="btn btn-primary btnxs" ><span class="glyphicon glyphicon-plus" ></span>
															Ajouter</button></form>
															-->
													      

														 <?php
														}
													 ?>
													
													<form class="form-inline pull-right" id="factForm" method="post"
													    <?php echo  "action=bonPclient.php?c=". $idClient."" ; ?> >
							                            <input type="hidden" name="idPagnet" id="idPagnet" 
							                               <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
							                               <button type="submit"  name="btnImprimerFacture"  class="btn btn-success pull-right" data-toggle="modal" >
							                                  <span class="glyphicon glyphicon-lock"></span>Facture
							                               </button>
							                          </form>
													<table class="table ">
														<thead>
															<tr>
																<th>Designation</th>
																<th>Unité vente</th>
																<th>Prix unite vente</th>
																<th>Quantité</th>
															</tr>
														</thead>
														<tbody>
															<?php $sql8="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']."";
                        									    $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
                          										while ($ligne = mysql_fetch_array($res8)) { ?>
										                              <tr>
										                                <td><?php echo $ligne['designation']; ?></td>
										                                <td><?php echo $ligne['unitevente']; ?></td>
										                                <td><?php echo $ligne['prixunitevente']; ?></td>
										                                <td><?php echo $ligne['quantite']; ?></td>
										                                <td>
										                                	<form class="form-inline pull-right" id="factForm" 	method="post"
																			    <?php echo  "action=bonPclient.php?c=". $idClient."" ; ?> >
													                            <input type="hidden" name="idPagnet" id="idPagnet" 
													                               <?php echo  "value=".$pagnet['idPagnet']."" ; ?> >
													                            <input type="hidden" name="designation" id="idPagnet" 
													                               <?php echo  "value=".$ligne['designation'].""; ?> >
													                               <button type="submit"  name="btnRetour"  class="btn btn-warning pull-right" data-toggle="modal" >
													                                  Retour
													                               </button>
													                          </form>
										                                </td>
										                              </tr>
                            								  <?php   } ?>
														</tbody>
													</table>
												</div>
											</div>
										</div>

							<?php 	}?>
									
					</div>
	        	</div>
	        	<div class="col-lg-3">

	        </div>
		</div>

</body>
</html>