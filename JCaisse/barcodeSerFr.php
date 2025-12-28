<?php
session_start();

if(!$_SESSION['iduser'])
  header('Location:../index.php');

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
</head>
<body onload="window.print();">
	<div style="margin-left: 5%">
		<?php
		//include 'libPHP/barcode128.php';

    $idDesignation = $_POST['iDD'];
    $designation = $_POST['designation'];
    $nbr = $_POST['nbr'];
    $type=$_POST['type'];
		$imprimante = $_POST['imprimante'];
		$nombreEtiquette = 1;
    $orientation=$_POST['orientation'];
		$prix=$_POST['prix'];
    $size=$_POST['size'];
    $print=$_POST['print'];
    $codeBarre=$idDesignation."-".$nbr;
		
    if($codeBarre != '') {

			if(($imprimante==1) || ($imprimante==2) || ($imprimante==3)) {
            			  echo '<div style="margin-left: 35px"><div><div><div>';
            				for($i=1;$i<=$nombreEtiquette;$i++){
            				/**********Petit**********/
            				if($imprimante==1){
            				$size=17;
            			    echo '<p class="inline" style="width:47mm; height:10mm; margin-top:0px;margin-bottom:17px; margin-left:17%">';
            				
            					echo'<span >
            					   <b style="font-size: 12px; text-align:left;position: absolute; left: 100px; color: black; ">';
            						    if (strlen($designation)>20)
            							  echo substr($designation,0,19).'...';
            						    else
            								echo $designation;
            						   echo' </b></br>
            							 <b style="font-size: 10px; text-align:left;position: absolute; left: 100px; color: black; ">';
            						   
            									echo $prix.'  FCFA';
            								
            								echo'</b></br>
            				    </span>
            					
            				  
            					 <img class="barcode" style="font-size: 10px;" alt="'.$codeBarre.'" src="barcode128.php?text='.$codeBarre.'&codetype='.$type.'&orientation='.$orientation.'&size='.$size.'&print='.$print.'"/>
            				 </p>&nbsp&nbsp&nbsp&nbsp';
            				
         			}
				/**********Moyen**********/
				if($imprimante==2){
				$size=28;
			    echo '<div><div><p class="inline" style="width:47mm; height:15mm; margin-top:0px;margin-bottom:20px; margin-left:17%">';
			
					echo'<span >
					   <b style="font-size: 12px; text-align:left;position: absolute; left: 100px; color: black; ">';
						    if (strlen($designation)>20)
							  echo substr($designation,0,19).'...';
						    else
								echo $designation;
						   echo' </b>
				    </span>
					</br>
					<span >
					   <b style="font-size: 12px; text-align:left;position: absolute; left: 100px; color: black; ">';
						  
									echo $prix.'  FCFA';
								
								echo'</b>
				    </span>
					</br>
					 <img class="barcode" style="font-size: 10px;" alt="'.$codeBarre.'" src="barcode128.php?text='.$codeBarre.'&codetype='.$type.'&orientation='.$orientation.'&size='.$size.'&print='.$print.'"/>
				 </p>&nbsp&nbsp&nbsp&nbsp</div></div>';
				}
				/**********Grand**********/
				if($imprimante==3){
				 $size=19;
			    echo '<p class="inline" style="width:62mm; height:10mm; margin-top:0px;margin-bottom:25px;">';
				
					echo'<span >
					   <b style="font-size: 11px; text-align:left;position: absolute; left: 100px; color: black; ">';
						    if (strlen($designation)>20)
							  echo substr($designation,0,19).'...';
						    else
								echo $designation;
						   echo' </b></br>
							 <b style="font-size: 10px; text-align:left;position: absolute; left: 100px; color: black; ">';
						   
									echo $prix.'  FCFA';
								
								echo'</b></br>
				    </span>
				  
					 <img class="barcode" alt="'.$codeBarre.'" src="barcode128.php?text='.$codeBarre.'&codetype='.$type.'&orientation='.$orientation.'&size='.$size.'&print='.$print.'"/>
				 </p>&nbsp&nbsp&nbsp&nbsp';
				}
						
			 }
			    echo '</div></div></div></div>';	
		   } 
		   /**********Tres Grand**********/
		   if($imprimante==4){
		       //echo '<div>';
			   for($i=1;$i<=$nombreEtiquette;$i++){
					 $size=42;
					echo '<p class="inline" style="width:30mm; height:21mm; text-align:center; margin-bottom:5px;">';

					echo'<span >
						    <b style="font-size: 12px;">';
						    if (strlen($designation)>20)
							  echo substr($designation,0,19).'...';
						    else
								echo $designation;
						   echo'</b>
						</span>
						</br>
						<span >
						   <b style="font-size: 12px;">';
						  
									echo $prix.'  FCFA';
								
								echo'</b></span>
						</br>
					  
						 <img class="barcode" style="font-size: 10px;" alt="'.$codeBarre.'" src="barcode128.php?text='.$codeBarre.'&codetype='.$type.'&orientation='.$orientation.'&size='.$size.'&print='.$print.'"/>
					 </p>&nbsp&nbsp&nbsp&nbsp';
				}
			   //echo '</div>';		
		   } 
			 
			 
			 
			 
			}
		?>

	</div>
</body>
</html>
