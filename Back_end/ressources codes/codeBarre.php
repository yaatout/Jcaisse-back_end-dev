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

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
     <link rel="stylesheet" href="css/bootstrap.css">
    <script src="js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="js/jquery-barcode.js"></script>
    <script src="js/bootstrap.js"></script>
    <style>.modal-header, h4, .close {background-color: #5cb85c; color:white !important; text-align: center; font-size: 30px;}.modal-footer {background-color: #f9f9f9;}</style>
    <script type="text/javascript" src="prixdesignation.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
	<title>JOURNAL DE CAISSE SALAM SERVICES SOLUTIONS</title>
</head>
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