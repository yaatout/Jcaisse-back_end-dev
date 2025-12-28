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

require('connection.php');


if(!$_SESSION['iduser'])
	header('Location:../index.php');

require('declarationVariables.php');

require('entetehtml.php');

?>

<body>
	<div class="container-fluid">
		<?php   require('header.php'); ?>
		<div class="noImpr">
			
			<?php $idStock=htmlspecialchars(trim($_GET['iDS']));
				$idDesignation=htmlspecialchars(trim($_GET['iDD']));
				 $nbr=htmlspecialchars(trim($_GET['nbr']));
				//$nbr=3;
				$sql="select * from `".$nomtableStock."` where idStock=".$idStock;
					$res=mysql_query($sql)or die ("select de impossible-2");



						$ligne = mysql_fetch_array($res);
						$designation=$ligne['designation'];
						$quantiteStockCourant=$ligne['quantiteStockCourant'];
						$quantiteStockinitial=$ligne['quantiteStockinitial'];
						$nbreArticleUniteStock=$ligne['nbreArticleUniteStock'];
						$uniteStock=$ligne['uniteStock'];
						$uniteStockRef=$nbr;
						$prixunitaire=$ligne['prixunitaire'];
						$prixuniteStock=$ligne['prixuniteStock'];
						
						
						echo"<h3>INFORMATIONS DU STOCK : ".$idStock."</h3>
							
							<hr>
							
							<p>REFERENCE : <b>".$designation."</b></p>

							<p>QUANTITE COURANTE : <b>".$quantiteStockCourant."</b></p>
							
							<p>UNITE DETAIL : <b>Article </b></p>
							
							
							<br/>
							
							<h3>IMPRESSION DE CODE-BARRES</h3>
							
							<hr>
							
							";

						?>
						
			
		</div>

						<?php

							echo "<div class='row'>";

								for ($i=1; $i <= 1; $i++) { ?>
									<?php if ($i==1 || $i==2): ?>
										<div class="col-md-3 col-sm-3 col-xm-3 spc" <?php echo "id='div'.$i."; ?> >
									<?php else: ?>
										<div class="col-md-3 col-sm-3 col-xm-3 spc noImpr" <?php echo "id='div'.$i."; ?> >
									<?php endif; ?>
								    <a href="#" class="thumbnail">
								    	<?php echo "<div id=demo".$i."></div> "?>
								    </a>
								    <!-- <script type="text/javascript">
										$(<?php echo '"#demo'.$i.'"'; ?>).barcode(<?php echo '"'.$idStock.'-'.$idDesignation.
											'-'.$nbr.'"'; ?>,"code128",{	barWidth: 1.5,barHeight: 25,moduleSize: 1,
														showHRI: true,addQuietZone: true,marginHRI: 5,
														bgColor: "#FFFFFF",color: "#000000",fontSize: 10,
														output: "css",posX: 0,Y: 0});
									</script> -->
								    <script type="text/javascript">
										$(<?php echo '"#demo'.$i.'"'; ?>).barcode(<?php echo '"'.$idStock.'-2"'; ?>,"code128",{	barWidth: 1.5,barHeight: 25,moduleSize: 1,
														showHRI: true,addQuietZone: true,marginHRI: 5,
														bgColor: "#FFFFFF",color: "#000000",fontSize: 10,
														output: "css",posX: 0,Y: 0});
									</script>
							    </div>
								
								<div >
								 <form class="form-horizontal" id="barcode" method="post" action="barcode.php" target="_blank">

									 <input type="hidden" name="detail"  value="1" >
									 <input type="hidden" name="iDS"   <?php echo  "value='".$idStock."'" ; ?>>
									 <input type="hidden" name="iDD"  <?php echo  "value='".$idDesignation."'" ; ?> >
									 <input type="hidden" name="uniteStockRef"  <?php echo  "value='".$uniteStockRef."'" ; ?> >
									 <input type="hidden" name="quantiteStockCourant"  <?php echo  "value='".$quantiteStockCourant."'" ; ?> >
									 <input type="hidden" name="quantiteStockinitial"  <?php echo  "value='".$quantiteStockinitial."'" ; ?> >
										<input type="hidden" name="nbreArticleUniteStock"  <?php echo  'value="'.$nbreArticleUniteStock.'"' ; ?> >
									 <input type="hidden" name="prixunitaire"  <?php echo  'value="'.$prixunitaire.'"' ; ?> >
	 								<input type="hidden" name="prixuniteStock"  <?php echo  'value="'.$prixuniteStock.'"' ; ?> >
									
								 <input type="number" name="nombreEtiquette" <?php echo  "value='".$nbreArticleUniteStock."' required min='1' max='".$nbreArticleUniteStock."'" ; ?> >
									 <?php echo '<input type="hidden" name="designation" value="'.$designation.'" >'  ; ?>
									 
								
									 <input autocomplete="OFF" type="hidden" class="form-control" name="print" id="print" value="true" >
									 <input autocomplete="OFF" type="hidden" class="form-control" name="type" id="type" value="code128">
										<input autocomplete="OFF" type="hidden" class="form-control" name="orientation" value="horizontal">
										<input autocomplete="OFF" type="hidden" class="form-control" name="size" id="size" value="50">
									</form>
									<button class="btn btn-success" onclick="document.getElementById('barcode').submit();">Imprimer</button>
						</div>
								<?php   } ?>

						   </div>


			<script type="text/javascript">


				$(function(){
					    $("#btnImprimer").click( function(){
					     window.print();
					    });
					});

			</script>

	</div>

</body>
</html>
