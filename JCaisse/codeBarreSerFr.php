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
<!--
	article => 100
	paquet => 200
	caisse => 300
	douzaine => 400
	tonne => 500

-->

<body>
	<div class="container-fluid">
		<?php   require('header.php'); ?>
		<div class="noImpr">
							
			

			<?php
				$idDesignation=htmlspecialchars(trim($_GET['idD']));
				$sql="select * from `".$nomtableDesignationSD."` where idDesignation=".$idDesignation;
			    $res=mysql_query($sql)or die ("select de impossible-2");
			    $ligne = mysql_fetch_array($res);
					
				  $designation=@htmlspecialchars(trim($ligne['designation']));
					$prix=$ligne['prixSD'];
				if($ligne['classe']==1){
					  $nbr=3; 
						 	echo"<h3>INFORMATIONS DU SERVICE : ".$idDesignation."</h3>
							
							<hr>
							
							<p>REFERENCE SERVICE: <b>".$designation."</b></p>

							<p>PRIX DU SERVICE: <b>".$prix."</b></p>
							
							
							<br/>
							
							<h3>IMPRESSION DE CODE-BARRES</h3>
							
							<hr>
							
							";

						
				}else{
						$nbr=4;
						echo"<h3>INFORMATIONS DEPENCE : ".$idDesignation."</h3>
							
							<hr>
							
							<p>REFERENCE DEPENCE: <b>".$designation."</b></p>

							<p>PRIX DE LA DEPENCE: <b>".$prix."</b></p>
							
							
							<br/>
							
							<h3>IMPRESSION DE CODE-BARRES</h3>
							
							<hr>
							
							";
						
						}
				?>

				<div class='row'>
					<div class=" col-md-3 spc">
						<a href="#" class="thumbnail">
							<?php echo "<div id=demo></div> "; ?>
						</a>
								    <script type="text/javascript">
										$("#demo").barcode(<?php echo '"'.$idDesignation.'-'.$nbr.'"'; ?>,"code128");
									</script>
							    </div>
				</div>


	</div>
		<script type="text/javascript">


					$(function(){
						    $("#btnImprimer").click( function(){
						     window.print();
						    });
						});
			</script>
			<div >
			<form class="form-horizontal" id="barcode" method="post" action="barcodeSerFr.php" target="_blank">

				<input type="hidden" name="iDD"  <?php echo  "value=".$idDesignation."" ; ?> >
				<input type="hidden" name="nbr"  <?php echo  "value=".$nbr."" ; ?> >
				<input type="hidden" name="prix"  <?php echo  "value=".$prix."" ; ?> >
				<input type="hidden" name="nombreEtiquette"  value="1" >
				<?php echo '<input type="hidden" name="designation" value="'.$designation.'" >'  ; ?>
				
				<select size=1 class="form-control" id="imprimante" name="imprimante" >
					<option value="1" selected>Petite</option>
					<option value="2" >Moyenne</option>
					<option value="3"> Grande </option>
					<option value="4"> Tres Grande </option>
				</select>
								
				<input autocomplete="OFF" type="hidden" class="form-control" name="print" id="print" value="true" >
				<input autocomplete="OFF" type="hidden" class="form-control" name="type" id="type" value="code128">
				 <input autocomplete="OFF" type="hidden" class="form-control" name="orientation" value="horizontal">
				 <input autocomplete="OFF" type="hidden" class="form-control" name="size" id="size" value="50">
			 </form>
		</div>
			<button class="btn btn-success" onclick="document.getElementById('barcode').submit();">Imprimer</button><hr>
		</div>
</body>
</html>
