<?php
session_start();

if(!$_SESSION['iduser'])
  header('Location:index.php');

  require('connection.php');

  require('declarationVariables.php');
 ?>


<html>
<head>
<style>
/* p.inline {display: inline-block;} */
/* span { font-size: 12px;} */
p {
    display: block;
    margin-block-start: 0px;
    margin-block-end: 0px;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
}
</style>
<style type="text/css" media="print">
    @page
    {
        /*size: auto;    auto is the initial value */
       /* margin: 5mm;   this affects the margin in the printer settings */
		/* background-color:blue; */

    }
</style>
<title>IMPRESSION</title>
</head>
<body onload="window.print();" style="padding: 0px;margin-top: 0px;">
		<?php
		//include 'libPHP/barcode128.php';
		
		$idDesignation = @htmlentities($_POST['idDesignation']);
		$designation = @htmlentities($_POST['designation']);
		$uniteStock = @$_POST['uniteStock'];
		$prixUnitaire = @$_POST['prixUnitaire'];
		$quantite = @$_POST['quantite'];
		$nombreFois = @$_POST['nombreFois'];
		// var_dump($nombreFois);
		// var_dump('nombreFois');
		if (isset($nombreFois)) {
			// var_dump('$nombreFois');
			# code...			
			// $sql="select * from `".$nomtableDesignation."` where prix=".$prixUnitaire." and classe=0";
			$sql="select * from `".$nomtableDesignation."` where prix=".$prixUnitaire."";
			$res=mysql_query($sql);
			$i=0;
			while($design=mysql_fetch_array($res)){ //fetch values
			// for($i=1;$i<=$quantite;$i++){
				// var_dump($design);
				$size=42;
				// echo '<p class="inline" style="width:40mm; height:21mm; text-align:center; margin-top:5px;">';
				// if ($i==15) {
				// 	# code...
				// 	$i=-1;
				// } else {
					# code...
					if ($i==0) {
					# code...
						echo '<p class="" style="width:5cm; height:3cm;text-align:center;margin-left: -1mm;background-color:gray;">';

					} else if ($i==14) {
						# code...
						echo '<p class="" style="width:5cm; height:3cm; text-align:center;padding: 0px;margin-top: 12mm;margin-left: -1mm;background-color:gray;">';

					} else if ($i==45) {
						# code...
						echo '<p class="" style="width:5cm; height:3cm; text-align:center;padding: 0px;margin-top: 14mm;margin-left: -1mm;background-color:gray;">';

					} else if ($i==76) {
						# code...
						echo '<p class="" style="width:5cm; height:3cm; text-align:center;padding: 0px;margin-top: 14mm;margin-left: -1mm;background-color:gray;">';

					} else if ($i==107) {
						# code...
						echo '<p class="" style="width:5cm; height:3cm; text-align:center;padding: 0px;margin-top: 14mm;margin-left: -1mm;background-color:gray;">';

					} else if ($i==138) {
						# code...
						echo '<p class="" style="width:5cm; height:3cm; text-align:center;padding: 0px;margin-top: 14mm;margin-left: -1mm;background-color:gray;">';

					} else if ($i==169) {
						# code...
						echo '<p class="" style="width:5cm; height:3cm; text-align:center;padding: 0px;margin-top: 14mm;margin-left: -1mm;background-color:gray;">';

					}  else {
						# code...
						echo '<p class="" style="width:5cm; height:3cm; text-align:center;padding: 0px;margin-top: 5mm;margin-left: -1mm;background-color:gray;">';

					}
					
					if (strlen($design['designation'])>35){
						echo'<span ></br>';
							echo '<b style="font-size: 40px;">';
							echo number_format($prixUnitaire, 0, ',', ' ').' ';
							echo' </b>
						</span>
						</br>
						<span>
							<b style="font-size: 12px">';
								echo substr($design['designation'],0,33). '...';
								echo '</b>
						</span>
						</br>';
					}
					else if (strlen($design['designation'])<18){
						echo'<span ></br>';
							echo '<b style="font-size: 40px;">';
							echo number_format($prixUnitaire, 0, ',', ' ').' ';
							echo' </b>
						</span>
						</br>
						<span>
							<b style="font-size: 12px">';
								echo substr($design['designation'],0,33).'';
								echo '</b>
						</span>
						</br>';
					}
					else{
						echo'<span ></br>';
							echo '<b style="font-size: 40px;">';
							echo number_format($prixUnitaire, 0, ',', ' ').' ';
							echo' </b>
						</span>
						</br>
						<span>
							<b style="font-size: 12px">';
								echo substr($design['designation'],0,33);
								echo '</b>
						</span>
						</br>';
					} 

					
					echo '</br></p>';
					$i++;
				// }
				
			// }
			}
		} else {
			# code...
			$sql="select * from `".$nomtableDesignation."` where idDesignation=".$idDesignation."";
			// var_dump($sql);
			$res=mysql_query($sql);
			// var_dump($res);
			$design=mysql_fetch_array($res);
			// var_dump($design);
				
			for($i=0;$i<$quantite;$i++){
				$size=42;
				// echo '<p class="inline" style="width:40mm; height:21mm; text-align:center; margin-top:5px;">';
				// if ($i==15) {
				// 	# code...
				// 	$i=-1;
				// } else {
				
				if ($design['nbreArticleUniteStock']!=1 && strtolower($design['uniteStock']!='article' )) {
					# code...
					if ($i==0) {
					# code...
						echo '<p class="" style="width:5.2cm; height:3cm;text-align:center;margin-left: -1mm;margin-top: -2mm;background-color:gray;">';

					} else if ($i==14) {
						# code...
						echo '<p class="" style="width:5.2cm; height:3cm; text-align:center;padding: 0px;margin-top: 12mm;margin-left: -1mm;background-color:gray;">';

					} else if ($i==45) {
						# code...
						echo '<p class="" style="width:5.2cm; height:3cm; text-align:center;padding: 0px;margin-top: 14mm;margin-left: -1mm;background-color:gray;">';

					} else if ($i==76) {
						# code...
						echo '<p class="" style="width:5.2cm; height:3cm; text-align:center;padding: 0px;margin-top: 14mm;margin-left: -1mm;background-color:gray;">';

					} else if ($i==107) {
						# code...
						echo '<p class="" style="width:5.2cm; height:3cm; text-align:center;padding: 0px;margin-top: 14mm;margin-left: -1mm;background-color:gray;">';

					} else if ($i==138) {
						# code...
						echo '<p class="" style="width:5.2cm; height:3cm; text-align:center;padding: 0px;margin-top: 14mm;margin-left: -1mm;background-color:gray;">';

					} else if ($i==169) {
						# code...
						echo '<p class="" style="width:5.2cm; height:3cm; text-align:center;padding: 0px;margin-top: 14mm;margin-left: -1mm;background-color:gray;">';

					}  else {
						# code...
						echo '<p class="" style="width:5.2cm; height:3cm; text-align:center;padding: 0px;margin-top: 5mm;margin-left: -1mm;background-color:gray;">';

					}
					
					if (strlen($designation)>23){
						echo'<span>';
							echo '<b style="font-size: 40px;">';
							echo number_format($prixUnitaire, 0, ',', ' ').' <span style="font-size: 12px;">Unite</span><br>';									
							echo number_format($design['prixuniteStock'], 0, ',', ' ').' <span style="font-size: 12px;">'.$design['uniteStock'].'</span>';
							echo' </b>
						</span>
						</br>
						<span>
							<b style="font-size: 12px">';
								echo substr($designation,0,33). '...';
								echo '</b>
						</span>
						</br>';
					}
					else if (strlen($designation)<18){
						echo'<span>';
							echo '<b style="font-size: 40px;">';
							echo number_format($prixUnitaire, 0, ',', ' ').' <span style="font-size: 12px;">Unite</span><br>';									
							echo number_format($design['prixuniteStock'], 0, ',', ' ').' <span style="font-size: 12px;">'.$design['uniteStock'].'</span>';
							echo' </b>
						</span>
						</br>
						<span>
							<b style="font-size: 12px">';
								echo substr($designation,0,33).'';
								echo '</b>
						</span>
						</br>';
					}
					else{
						echo'<span>';
							echo '<b style="font-size: 40px;">';
							echo number_format($prixUnitaire, 0, ',', ' ').' <span style="font-size: 12px;">Unite</span><br>';									
							echo number_format($design['prixuniteStock'], 0, ',', ' ').' <span style="font-size: 12px;">'.$design['uniteStock'].'</span>';
							echo' </b>
						</span>
						</br>
						<span>
							<b style="font-size: 12px">';
								echo substr($designation,0,33);
								echo '</b>
						</span>
						</br>';
					} 
					
					echo '</br></p>';
				} else {
					# code...
					if ($i==0) {
					# code...
						echo '<p class="" style="width:5cm; height:3cm;text-align:center;margin-left: -1mm;margin-top: -2mm;background-color:gray;">';

					} else if ($i==14) {
						# code...
						echo '<p class="" style="width:5cm; height:3cm; text-align:center;padding: 0px;margin-top: 12mm;margin-left: -1mm;background-color:gray;">';

					} else if ($i==45) {
						# code...
						echo '<p class="" style="width:5cm; height:3cm; text-align:center;padding: 0px;margin-top: 14mm;margin-left: -1mm;background-color:gray;">';

					} else if ($i==76) {
						# code...
						echo '<p class="" style="width:5cm; height:3cm; text-align:center;padding: 0px;margin-top: 14mm;margin-left: -1mm;background-color:gray;">';

					} else if ($i==107) {
						# code...
						echo '<p class="" style="width:5cm; height:3cm; text-align:center;padding: 0px;margin-top: 14mm;margin-left: -1mm;background-color:gray;">';

					} else if ($i==138) {
						# code...
						echo '<p class="" style="width:5cm; height:3cm; text-align:center;padding: 0px;margin-top: 14mm;margin-left: -1mm;background-color:gray;">';

					} else if ($i==169) {
						# code...
						echo '<p class="" style="width:5cm; height:3cm; text-align:center;padding: 0px;margin-top: 14mm;margin-left: -1mm;background-color:gray;">';

					}  else {
						# code...
						echo '<p class="" style="width:5cm; height:3cm; text-align:center;padding: 0px;margin-top: 5mm;margin-left: -1mm;background-color:gray;">';

					}
					
					if (strlen($designation)>35){
						echo'<span ></br>';
							echo '<b style="font-size: 40px;">';
							echo number_format($prixUnitaire, 0, ',', ' ').' ';
							echo' </b>
						</span>
						</br>
						<span>
							<b style="font-size: 12px">';
								echo substr($designation,0,33). '...';
								echo '</b>
						</span>
						</br>';
					}
					else if (strlen($designation)<18){
						echo'<span ></br>';
							echo '<b style="font-size: 40px;">';
							echo number_format($prixUnitaire, 0, ',', ' ').' ';
							echo' </b>
						</span>
						</br>
						<span>
							<b style="font-size: 12px">';
								echo substr($designation,0,33).'';
								echo '</b>
						</span>
						</br>';
					}
					else{
						echo'<span ></br>';
							echo '<b style="font-size: 40px;">';
							echo number_format($prixUnitaire, 0, ',', ' ').' ';
							echo' </b>
						</span>
						</br>
						<span>
							<b style="font-size: 12px">';
								echo substr($designation,0,33);
								echo '</b>
						</span>
						</br>';
					} 
					
					echo '</br></p>';
				}
			}
		}
		
		// for($i=1;$i<=$quantite;$i++){
		// 	$size=42;
		// 	echo '<p class="inline" style="width:40mm; height:21mm; text-align:center; margin-top:5px;">';
		// 	if (strlen($designation)>35){
		// 		echo'<span >';
		// 			echo '<b style="font-size: 12px;">';
		// 			echo substr($designation,0,33).'..';
		// 			echo' </b>
		// 		</span>
		// 		</br>
		// 		<span >
		// 			<b style="font-size: 28px;">';
		// 				echo number_format($prixUnitaire, 0, ',', ' '). ' ';
		// 				echo '</b>
		// 		</span>
		// 		</br>';
		// 	}
		// 	else if (strlen($designation)<18){
		// 		echo'<span >';
		// 			echo '<b style="font-size: 15px;">';
		// 				echo $designation;
		// 			echo' </b>
		// 		</span>
		// 		</br>
		// 		<span >
		// 			<b style="font-size: 30px;">';
		// 				echo number_format($prixUnitaire, 0, ',', ' '). ' ';
		// 				echo '</b>
		// 		</span>
		// 		</br>';
		// 	}
		// 	else{
		// 		echo'<span >';
		// 			echo '<b style="font-size: 14px;">';
		// 				echo $designation;
		// 			echo' </b>
		// 		</span>
		// 		</br>
		// 		<span >
		// 			<b style="font-size: 28px;">';
		// 				echo number_format($prixUnitaire, 0, ',', ' '). ' ';
		// 				echo '</b>
		// 		</span>
		// 		</br>';
		// 	} 

			
		// 	echo '</p></br></br>';
		// }

		?>

</body>
</html>
