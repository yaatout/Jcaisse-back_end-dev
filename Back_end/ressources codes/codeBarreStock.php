<?php
/*
Résumé :
Commentaire :
Version : 2.1
see also :
Auteur : EL hadji mamadou korka
Date de création : 18-05-2018
Date derniére modification :  19-05-2018
*/

session_start();
mysql_connect("localhost","root","");
mysql_select_db("bdjournalcaisse");


if(!$_SESSION['iduser'])
	header('Location:index.php'); 
$nomtableDesignation=$_SESSION['nomB']."-designation";
$nomtableJournal=$_SESSION['nomB']."-journal";
$nomtablePage=$_SESSION['nomB']."-pagej";
$nomtablePagnet=$_SESSION['nomB']."-pagnet";
$nomtableLigne=$_SESSION['nomB']."-lignepj";
$nomtableDesignation=$_SESSION['nomB']."-designation";
$nomtableStock=$_SESSION['nomB']."-stock";
$nomtableTotalStock=$_SESSION['nomB']."-totalstock";
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
     <link rel="stylesheet" href="css/bootstrap.css">
    <script src="js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="js/jquery-barcode.js"></script>
    <script src="js/bootstrap.js"></script>
    <script type="text/javascript" src="prixdesignation.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style media="print" type="text/css">   .noImpr {   display:none;   } </style> 
	<title>JOURNAL DE CAISSE SALAM SERVICES SOLUTIONS</title>
</head>
<body>
	<div class="container-fluid">
		<?php   require('header.php'); ?>
		<div class="noImpr">
			<button class="btn btn-success" id="btnImprimer">Imprimer</button><hr>
		</div>
		<section>
			<?php $idStock=htmlspecialchars(trim($_GET['iDS'])); 
				$idDesignation=htmlspecialchars(trim($_GET['iDD'])); 
				$sql="select quantiteStockCourant,totalArticleStock,uniteStock,designation,quantiteStockinitial from `".$nomtableStock."` where idStock=".$idStock;
			    $res=mysql_query($sql)or die ("select de impossible-2");
				
				

						$ligne = mysql_fetch_array($res);
						$quantiteStockCourant=$ligne['quantiteStockCourant'];
						$quantiteStockinitial=$ligne['quantiteStockinitial'];
						$uniteStockRef=0;
						$uniteStock=$ligne['uniteStock'];

							if ($uniteStock=="article") { $uniteStockRef=100;} 
							elseif ($uniteStock=="paquet") { $uniteStockRef=200;} 
							elseif ($uniteStock=="caisse") { $uniteStockRef=300;} 
							elseif ($uniteStock=="douzaine") { $uniteStockRef=400;} 
							elseif ($uniteStock=="tonne") { $uniteStockRef=500;} 
							
						if ($uniteStock!="article") {
							echo   "Code barre par ".$uniteStock;
							echo   "<div class='row'>";
							for ($i=1; $i <= $quantiteStockinitial; $i++) { ?>
					    	<div class=" col-md-4">
							    <a href="#" class="thumbnail">
							    	<?php echo "<div id=demo".$i."></div> "?>
							    </a>
							    <script type="text/javascript">
									$(<?php echo '"#demo'.$i.'"'; ?>).barcode(<?php echo '"'.$idStock.'-'.$idDesignation.'-'.$uniteStockRef.'"'; ?>,"code128",{	barWidth: 1,barHeight: 50,moduleSize: 5,
														showHRI: true,addQuietZone: true,marginHRI: 5,
														bgColor: "#FFFFFF",color: "#000000",fontSize: 10,
														output: "css",posX: 0,Y: 0});
								</script>
								<?php echo '<a href="codeBarreStockDetail.php?iDS='.$idStock.'&iDD='.$idDesignation.'&nbr='.$uniteStockRef.'">'.
                              'Details</a>'; ?> 
						    </div>
					 <?php   }
					 	 echo   "</div>";
						}
						else {
							echo "<div class='row'>";
								for ($i=1; $i <= $quantiteStockCourant; $i++) { ?>
						    	<div class=" col-md-6">
								    <a href="#" class="thumbnail">
								    	<?php echo "<div id=demo".$i."></div> "?>
								    </a>
								    <script type="text/javascript">
										$(<?php echo '"#demo'.$i.'"'; ?>).barcode(<?php echo '"'.$idStock.'-'.$idDesignation.'-'.$i.'"'; ?>,"code128",{	barWidth: 1,barHeight: 50,moduleSize: 5,
															showHRI: true,addQuietZone: true,marginHRI: 5,
															bgColor: "#FFFFFF",color: "#000000",fontSize: 10,
															output: "css",posX: 0,Y: 0});
									</script>
							    </div>
						   </div>
					 <?php   }
						}
					   
				
			?> 

			<script type="text/javascript">
				

				$(function(){
					    $("#btnImprimer").click( function(){ 
					     window.print();
					    });
					});
								
			</script>
							<!--<div id="demo"></div>

							<script type="text/javascript">
							$('#demo').barcode('36291',"datamatrix");
							</script>
							*******************************
							<script type="text/javascript">
								window.print();
							</script>



							 <script type="text/javascript">
									$(<?php echo '"#demo'.$i.'"'; ?>).barcode(<?php echo '"'.$idStock."-".$ligne['designation']."-".$i.'"'; ?>,"code128");
								</script>
							*******************************-->

		</section>
	</div>
	
</body>
</html>