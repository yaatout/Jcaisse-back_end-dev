<?php
session_start();

if(!$_SESSION['iduser'])
  header('Location:index.php');

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

			$designation = @htmlentities($_POST['designation']);
			$quantite = @$_POST['quantite'];
			$codeBarreuniteStock = @htmlentities($_POST['codeBarreuniteStock']);
			$prixUnitaire = @$_POST['prixUnitaire'];
			$unite=number_format($prixUnitaire, 0, ',', ' ').' FCFA';

			$type='code128';
			$orientation='horizontal';
			$size='50';
			$print='true';
			$codeBarre=$codeBarreuniteStock;

			for($i=1;$i<=$quantite;$i++){
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
				if (strlen($designation)>18){
					echo'<span></br>';
						echo '<b style="font-size: 12px;">';
						echo strtoupper(substr($designation,0,16)).'..';
						echo' </b>
					</span> 
					</br>
					<span>
						<b style="font-size: 40px;">';
							echo '<img class="barcode" style="width:5cm;" alt="'.$codeBarre.'" src="barcode128.php?text='.$codeBarre.'&codetype='.$type.'&orientation='.$orientation.'&size='.$size.'&print='.$print.'&unite='.$unite.'"/>';
							echo '</b>
					</span>
					</br></br>';

				}
				else {
					echo'<span></br>';
						echo '<b style="font-size: 12px;">';
						echo strtoupper($designation).'';
						echo' </b>
					</span>
					</br>
					<span>
						<b style="font-size: 40px;">';
						echo '<img class="barcode" style="width:5cm;" alt="'.$codeBarre.'" src="barcode128.php?text='.$codeBarre.'&codetype='.$type.'&orientation='.$orientation.'&size='.$size.'&print='.$print.'&unite='.$unite.'"/>';
							echo '</b>
					</span>
					</br></br>';
				}
				
				
				echo '</p>';
			}
		
		
		?>

</body>
</html>
