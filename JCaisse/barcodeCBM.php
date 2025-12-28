<?php
	session_start();

	if(!$_SESSION['iduser'])
		header('Location:index.php');


	require('connection.php');
	require('connectionPDO.php');

	require('declarationVariables.php');
 ?>
 


<html>
<head>
<style>
/* p.inline {display: inline-block;}
span { font-size: 12px;} */
p {
    display: block;
    margin-block-start: 0px;
    margin-block-end: 0px;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
}
</style>
<style type="text/css" media="print">
    /* @page
    { */
        /* size: auto;   auto is the initial value */
        /* margin: 5mm;  this affects the margin in the printer settings */

    /* } */
</style>
<title>IMPRESSION</title>
</head>
<body onload="window.print();" style="padding: 0px;margin-top: 0px;">
		<?php

			$idPagnetCbm = @htmlentities($_POST['idPagnetCbm']);
			$codeBarrePcsInContainer = @htmlentities($_POST['codeBarrePcsInContainer']);
			$nbPcsInContainer = @htmlentities($_POST['nbPcsInContainer']);
			$idContainer = @htmlentities($_POST['numContainer']);
			$idClient = @htmlentities($_POST['idClient']);

			// $nomtableClient = $_SESSION['nomB'].'client';

			$sql3="SELECT * FROM `".$nomtableClient."` where idClient=".$idClient."";

			$res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());
			
			$client = mysql_fetch_assoc($res3);
			 
  
			$sql3="SELECT * FROM `".$nomtableContainer."` where idContainer=".$idContainer;
  
			$res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());
		  
			$container = mysql_fetch_assoc($res3);
			$numContainer=$container['numContainer'];

			// $codeBarreuniteStock = @htmlentities($_POST['codeBarreuniteStock']);
			// $prixUnitaire = @$_POST['prixUnitaire'];
			// $unite=number_format($prixUnitaire, 0, ',', ' ').' FCFA';


			$type='code128';
			$orientation='horizontal';
			$size='50';
			$print='true';
			$codeBarre=$codeBarrePcsInContainer;

			for($i=1;$i<=$nbPcsInContainer;$i++){
				// echo '<p class="inline" style="width:40mm; height:26mm; text-align:center; margin-top:5px;">';
				// echo '<p class="" style="width:5cm; height:3cm;text-align:center;background-color:gray;">';
				
				if ($i==1) {
					# code...
					echo '<p class="" style="width:5cm; height:3cm;text-align:center;margin-left: -1mm;background-color:gray;">';

				}  else {
					# code...
					echo '<p class="" style="width:5cm; height:3cm; text-align:center;padding: 0px;margin-top: 7mm;margin-left: -1mm;background-color:gray;">';

				}
				// echo '<p class="" style="width:5cm; height:3cm;margin-left:36%;text-align:center;background-color:gray;">';
				if (strlen($numContainer)>18){
					echo'<span></br>';
						echo '<b style="font-size: 12px;">';
						echo strtoupper(substr($numContainer,0,16)).'..';
						echo' </b>
					</span> 
					</br>
					<span>
						<b style="font-size: 40px;">';
							echo '<img class="barcode" style="width:5cm;" alt="'.$codeBarre.'" src="barcode128.php?text='.$codeBarre.'&codetype='.$type.'&orientation='.$orientation.'&size='.$size.'&print='.$print.'&unite='.$client['prenom'].'"/>';
							echo '</b>
					</span>
					</br></br>';

				}
				else {
					echo'<span></br>';
						echo '<b style="font-size: 12px;">';
						echo strtoupper($numContainer).'';
						echo' </b>
					</span>
					</br>
					<span>
						<b style="font-size: 40px;">';
						echo '<img class="barcode" style="width:5cm;" alt="'.$codeBarre.'" src="barcode128.php?text='.$codeBarre.'&codetype='.$type.'&orientation='.$orientation.'&size='.$size.'&print='.$print.'&unite='.$client['prenom'].'"/>';
							echo '</b>
					</span>
					</br></br>';
				}
				
				
				echo '</p>';
			}
		
		
		?>

</body>
</html>
