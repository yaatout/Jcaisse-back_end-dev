<?php
session_start();

if(!$_SESSION['iduser'])
  header('Location:index.php');

		//include 'libPHP/barcode128.php';

		$idStock = $_POST['iDS'];
		$idDesignation = $_POST['iDD'];
		$uniteStockRef = $_POST['uniteStockRef'];
		$prixunitaire = @$_POST['prixunitaire'];
		$prixuniteStock = @$_POST['prixuniteStock'];

        $prixSession         =@$_POST["prixSession"];
        $prixPublic     =@$_POST["prixPublic"];

		$detail = @$_POST['detail'];
		$product = $_POST['designation'];
		$nombreEtiquette = $_POST['nombreEtiquette'];
		$etiquette = $_SESSION['etiquette'];
		$quantiteStockinitial = @$_POST['quantiteStockinitial'];
		$nbreArticleUniteStock = @$_POST['nbreArticleUniteStock'];
		$type=$_POST['type'];
		$orientation=$_POST['orientation'];
		$size=$_POST['size'];
		$print=$_POST['print'];
		$quantite="";

if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
 echo 'je suis la';
 require('pharmacie/barcode-pharmacie.php');

}else{
 ?>

<html>
<head>
<style>
p.inline {display: inline-block;}
span { font-size: 13px;}
</style>
<style type="text/css" media="print">
    @page
    {
        size: auto;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */

    }
</style>
<title>IMPRESSION</title>
</head>
<body onload="window.print();">
		<?php


    $codeBarre=$idStock.'-1';
    if ($uniteStockRef==2) {

	

    if ($detail) {
				if (($nombreEtiquette > $nbreArticleUniteStock)||($nombreEtiquette <1))
  					echo '<script type="text/javascript"> alert("ERREUR : SAISIE DU NOMBRE A IMPRIMER EST INCORRECTE ...");</script>';
				else {
            $codeBarre=$idStock.'-2';

            if(($etiquette==1) || ($etiquette==2) || ($etiquette==3)) {
            			  echo '<div style="margin-left: 35px"><div><div><div>';
            				for($i=1;$i<=$_POST['nombreEtiquette'];$i++){
            				/**********Petit**********/
            		   if($etiquette==1){
            				$size=17;
            			    echo '<p class="inline" style="width:47mm; height:10mm; margin-top:0px;margin-bottom:17px; margin-left:17%">';

            					echo'<span >
            					   <b style="font-size: 12px; text-align:left;position: absolute; left: 100px; color: black; ">';
            						    if (strlen($product)>20)
            							  echo substr($product,0,19).'...';
            						    else
            								echo $product;
            						   echo' </b></br>
            							 <b style="font-size: 10px; text-align:left;position: absolute; left: 100px; color: black; ">';
            						   if ($detail){
            								echo $prixunitaire.'  FCFA';
            							}else if(!$prixuniteStock){
            									echo $prixunitaire.'  FCFA';
            								}else {
            									echo $prixuniteStock.'  FCFA';
            								}
            								echo'</b></br>
            				    </span>

            				  
            					 <img class="barcode" style="font-size: 10px;" alt="'.$codeBarre.'" src="barcode128.php?text='.$codeBarre.'&codetype='.$type.'&orientation='.$orientation.'&size='.$size.'&print='.$print.'"/>
            				 </p>&nbsp&nbsp&nbsp&nbsp';

         			}
				/**********Moyen**********/
		  if($etiquette==2){
				$size=28;
			    echo '<div><div><p class="inline" style="width:47mm; height:15mm; margin-top:0px;margin-bottom:20px; margin-left:17%">';

					echo'<span >
					   <b style="font-size: 12px; text-align:left;position: absolute; left: 100px; color: black; ">';
						    if (strlen($product)>20)
							  echo substr($product,0,19).'...';
						    else
								echo $product;
						   echo' </b>
				    </span>
					</br>
					<span >
					   <b style="font-size: 12px; text-align:left;position: absolute; left: 100px; color: black; ">';
						   if ($detail){
								echo $prixunitaire.'  FCFA';
							}else if(!$prixuniteStock){
									echo $prixunitaire.'  FCFA';
								}else {
									echo $prixuniteStock.'  FCFA';
								}
								echo'</b>
				    </span>
					</br>
					 <img class="barcode" style="font-size: 10px;" alt="'.$codeBarre.'" src="barcode128.php?text='.$codeBarre.'&codetype='.$type.'&orientation='.$orientation.'&size='.$size.'&print='.$print.'"/>
				 </p>&nbsp&nbsp&nbsp&nbsp</div></div>';
				}
				/**********Grand**********/
		  if($etiquette==3){
				 $size=19;
			    echo '<p class="inline" style="width:62mm; height:10mm; margin-top:0px;margin-bottom:25px;">';
				
					echo'<span >
					   <b style="font-size: 11px; text-align:left;position: absolute; left: 100px; color: black; ">';
						    if (strlen($product)>20)
							  echo substr($product,0,19).'...';
						    else
								echo $product;
						   echo' </b></br>
							 <b style="font-size: 10px; text-align:left;position: absolute; left: 100px; color: black; ">';
						   if ($detail){
								echo $prixunitaire.'  FCFA';
							}else if(!$prixuniteStock){
									echo $prixunitaire.'  FCFA';
								}else {
									echo $prixuniteStock.'  FCFA';
								}
								echo'</b></br>
				    </span>
				  
					 <img class="barcode" alt="'.$codeBarre.'" src="barcode128.php?text='.$codeBarre.'&codetype='.$type.'&orientation='.$orientation.'&size='.$size.'&print='.$print.'"/>
				 </p>&nbsp&nbsp&nbsp&nbsp';
				}
						
			 }
			    echo '</div></div></div></div>';	
		   }
		   /**********Tres Grand**********/
		   if($etiquette==4){
		       //echo '<div>';
			   for($i=1;$i<=$_POST['nombreEtiquette'];$i++){
					 $size=42;
					echo '<p class="inline" style="width:30mm; height:21mm; text-align:center; margin-bottom:5px;">';

					echo'<span >
						    <b style="font-size: 12px;">';
						    if (strlen($product)>20)
							  echo substr($product,0,19).'...';
						    else
								echo $product;
						   echo'</b>
						</span>
						</br>
						<span >
						   <b style="font-size: 12px;">';
						   if ($detail){
								echo $prixunitaire.'  FCFA';
							}else if(!$prixuniteStock){
									echo $prixunitaire.'  FCFA';
								}else {
									echo $prixuniteStock.'  FCFA';
								}
								echo'</b></span>
						</br>

						 <img class="barcode" style="font-size: 10px;" alt="'.$codeBarre.'" src="barcode128.php?text='.$codeBarre.'&codetype='.$type.'&orientation='.$orientation.'&size='.$size.'&print='.$print.'"/>
					 </p>&nbsp&nbsp&nbsp&nbsp';
				}
			   //echo '</div>';
		   }

				 
				}
     }

    } if($codeBarre != '') {

		   if($etiquette==1 || $etiquette==2 || $etiquette==3) {
			    echo '<div style="margin-left: 35px"><div><div><div><div>';
				for($i=1;$i<=$_POST['nombreEtiquette'];$i++){
				/**********Petit**********/
				if($etiquette==1){
				$size=17;
			    echo '<p class="inline" style="width:47mm; height:10mm; margin-top:0px;margin-bottom:17px; margin-left:17%">';

					echo'<span >
					   <b style="font-size: 12px; text-align:left;position: absolute; left: 100px; color: black; ">';
						    if (strlen($product)>20)
							  echo substr($product,0,19).'...';
						    else
								echo $product;
						   echo' </b></br>
							 <b style="font-size: 10px; text-align:left;position: absolute; left: 100px; color: black; ">';
						   if ($detail){
								echo $prixunitaire.'  FCFA';
							}else if(!$prixuniteStock){
									echo $prixunitaire.'  FCFA';
								}else {
									echo $prixuniteStock.'  FCFA';
								}
								echo'</b></br>
				    </span>

				  
					 <img class="barcode" style="font-size: 10px;" alt="'.$codeBarre.'" src="barcode128.php?text='.$codeBarre.'&codetype='.$type.'&orientation='.$orientation.'&size='.$size.'&print='.$print.'"/>
				 </p>&nbsp&nbsp&nbsp&nbsp';

				}
				/**********Moyen**********/
				if($etiquette==2){
				$size=28;
			    echo '<div><div><p class="inline" style="width:47mm; height:15mm; margin-top:0px;margin-bottom:20px; margin-left:17%">';

					echo'<span >
					   <b style="font-size: 12px; text-align:left;position: absolute; left: 100px; color: black; ">';
						    if (strlen($product)>20)
							  echo substr($product,0,19).'...';
						    else
								echo $product;
						   echo' </b>
				    </span>
					</br>
					<span >
					   <b style="font-size: 12px; text-align:left;position: absolute; left: 100px; color: black; ">';
						   if ($detail){
								echo $prixunitaire.'  FCFA';
							}else if(!$prixuniteStock){
									echo $prixunitaire.'  FCFA';
								}else {
									echo $prixuniteStock.'  FCFA';
								}
								echo'</b>
				    </span>
					</br>
					 <img class="barcode" style="font-size: 10px;" alt="'.$codeBarre.'" src="barcode128.php?text='.$codeBarre.'&codetype='.$type.'&orientation='.$orientation.'&size='.$size.'&print='.$print.'"/>
				 </p>&nbsp&nbsp&nbsp&nbsp</div></div>';
				}
				/**********Grand**********/
				if($etiquette==3){
				 $size=19;
			    echo '<p class="inline" style="width:62mm; height:10mm; margin-top:0px;margin-bottom:25px;">';

					echo'<span >
					   <b style="font-size: 11px; text-align:left;position: absolute; left: 100px; color: black; ">';
						    if (strlen($product)>20)
							  echo substr($product,0,19).'...';
						    else
								echo $product;
						   echo' </b></br>
							 <b style="font-size: 10px; text-align:left;position: absolute; left: 100px; color: black; ">';
						   if ($detail){
								echo $prixunitaire.'  FCFA';
							}else if(!$prixuniteStock){
									echo $prixunitaire.'  FCFA';
								}else {
									echo $prixuniteStock.'  FCFA';
								}
								echo'</b></br>
				    </span>

					 <img class="barcode" alt="'.$codeBarre.'" src="barcode128.php?text='.$codeBarre.'&codetype='.$type.'&orientation='.$orientation.'&size='.$size.'&print='.$print.'"/>
				 </p>&nbsp&nbsp&nbsp&nbsp';
				}

			 }
			    echo '</div></div></div></div></div>';
		   }
		   /**********Tres Grand**********/
		   if($etiquette==4){
		       //echo '<div>';
			   for($i=1;$i<=$_POST['nombreEtiquette'];$i++){
					 $size=42;
					echo '<p class="inline" style="width:30mm; height:21mm; text-align:center; margin-bottom:5px;">';

						echo'<span >
						   <b style="font-size: 12px;">';
						    if (strlen($product)>20)
							  echo substr($product,0,19).'...';
						    else
								echo $product;
						   echo' </b>
						</span>
						</br>
						<span >
						   <b style="font-size: 12px;">';
						   if ($detail){
								echo $prixunitaire.'  FCFA';
							}else if(!$prixuniteStock){
									echo $prixunitaire.'  FCFA';
								}else {
									echo $prixuniteStock.'  FCFA';
								}
								echo'</b>
						</span>
						</br>
					  
						 <img class="barcode" style="font-size: 10px;" alt="'.$codeBarre.'" src="barcode128.php?text='.$codeBarre.'&codetype='.$type.'&orientation='.$orientation.'&size='.$size.'&print='.$print.'"/>
					 </p>&nbsp&nbsp&nbsp&nbsp';
				}
			   //echo '</div>';
		   }
           }
           }

		?>

</body>
</html>
