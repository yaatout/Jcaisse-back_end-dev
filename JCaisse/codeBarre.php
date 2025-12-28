<?php
/*

Résumé :
Commentaire :
Version : 2.1
See also :
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
		<section>
			<?php $idDesignation=htmlspecialchars(trim($_GET['iD'])); 
				$sql="select nbreArticleUniteStock from `".$nomtableDesignation."` where idDesignation=".$idDesignation;
			    $res=mysql_query($sql)or die ("select de impossible-2");
				echo   "<div class='row'>";
						$ligne = mysql_fetch_array($res);
						$nbreArticleUniteStock=$ligne['nbreArticleUniteStock'];
						

					   for ($i=1; $i <= $nbreArticleUniteStock; $i++) { ?>
					    	<div class="col-xs-6 col-md-3">
							    <a href="#" class="thumbnail">
							    	<?php echo "<div id=demo".$i."></div> "?>  
							    </a>
							    <script type="text/javascript">
									$(<?php echo '"#demo'.$i.'"'; ?>).barcode(<?php echo '"'.$idDesignation."-".$i.'"'; ?>,"datamatrix");
								</script>
						    </div>
					 <?php   }
				echo   "</div>";
			?>
							<!--<div id="demo"></div>

							<script type="text/javascript">
							$("#demo").barcode("36291","datamatrix");
							</script>-->
							<script type="text/javascript">
								window.print();
							</script>

		</section>
	</div>
	
</body>
</html>