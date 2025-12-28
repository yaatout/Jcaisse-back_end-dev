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

require('../connection.php');



if(!$_SESSION['iduser'])
	header('Location:../../index.php');

require('../declarationVariables.php');

require('../entetehtml.php');

?>
<body>
	<div class="container-fluid">
		<?php   require('../header.php'); ?>
		<div class="noImpr">

		<?php $idStock=htmlspecialchars(trim(@$_GET['iDS']));
				$designation=htmlspecialchars(trim(@$_GET['iDD']));

				 $sql1="SELECT * FROM `".$nomtableDesignation."` where designation='".$designation."'";
				 $res1=mysql_query($sql1);
				 if($tab3=mysql_fetch_array($res1))
					$idDesignation=$tab3['idDesignation'];

				$sql="select * from `".$nomtableStock."` where idStock=".$idStock;
					$res=mysql_query($sql)or die ("select de impossible-2");

						$ligne = mysql_fetch_array($res);
						$designation=$ligne['designation'];
						$quantiteStockCourant=$ligne['quantiteStockCourant'];
						$quantiteStockinitial=$ligne['quantiteStockinitial'];
						$prixPublic=$ligne['prixPublic'];
						$uniteStockRef=0;
						$forme=$tab3['forme'];
						$prixSession=$ligne['prixSession'];
                        $uniteStockRef=1;


							?>


		</div>



							<?php
                  
						
						
						echo   "<div class='noImpr'>
							
							<h3>INFORMATIONS DU STOCK : ".$idStock."</h3>
							
							<hr>
							
							<p>REFERENCE : <b>".$designation."</b></p>

							<p>QUANTITE INITIALE : <b>".$quantiteStockinitial."</b></p>
							
							<p>FORME : <b>".$forme."</b></p>
							
							
							
							
							<br/>
							<h3>IMPRESSION DE CODE-BARRES  </h3>
							
							<hr>
							</div>";?>
							<?php
								for ($i=1; $i <= 1; $i++) { ?>
						    	<div class=" col-md-3 spc" >
								    <a href="#" class="thumbnail">
								    	<?php echo "<div id=demo".$i."></div> "?>
								    </a>
								    <script type="text/javascript">
										$(<?php echo '"#demo'.$i.'"'; ?>).barcode(<?php echo '"'.$idStock.'-1"'; ?>,"code128",{	barWidth: 1.5,barHeight: 25,moduleSize: 1,
														showHRI: true,addQuietZone: true,marginHRI: 5,
														bgColor: "#FFFFFF",color: "#000000",fontSize: 10,
														output: "css",posX: 0,Y: 0});
									</script>
							    </div>

					 <?php   }	?>
					 <form class="form-horizontal" id="barcode" method="post" action="barcode.php" target="_blank">
					      <input type="hidden" name="detail"  value="0" >
								<input type="hidden" name="iDS"   <?php echo  "value=".$idStock."" ; ?>>
								<input type="hidden" name="iDD"  <?php echo  "value=".$idDesignation."" ; ?> >
								<input type="hidden" name="uniteStockRef"  <?php echo  "value=".$uniteStockRef."" ; ?> >
								<input type="hidden" name="quantiteStockinitial"  <?php echo  "value=".$quantiteStockinitial."" ; ?> >
								<input type="hidden" name="prixSession"  <?php echo  "value=".$prixSession."" ; ?> >
								<input type="hidden" name="prixPublic"  <?php echo  "value=".$prixPublic."" ; ?> >
							 <input type="number" name="nombreEtiquette" placeholder="Nombre d'etiquette à imprimer" <?php echo  "value=".$quantiteStockinitial."" ; ?> >
								<?php echo '<input type="hidden" name="designation" value="'.$designation.'" >'  ; ?>

								
								<input autocomplete="OFF" type="hidden" class="form-control" name="print" id="print" value="true" >
								<input autocomplete="OFF" type="hidden" class="form-control" name="type" id="type" value="code128">
								 <input autocomplete="OFF" type="hidden" class="form-control" name="orientation" value="horizontal">
								 <input autocomplete="OFF" type="hidden" class="form-control" name="size" id="size" value="50">
							 </form><br>
				<button class="btn btn-success" onclick="document.getElementById('barcode').submit();">Imprimer</button>
				<br/><br/><br/>
					 
							 



	</div>


</body>
</html>
