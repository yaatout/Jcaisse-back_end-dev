<?php
session_start();

if(!$_SESSION['iduser']){
	header('Location:../index.php');
}

require('connection.php');

require('declarationVariables.php');

require('entetehtml.php');

$dateInventaire     =@$_POST["dateInventaire"];

//echo $dateInventaire;
?>

<body>

		<?php
		  require('header.php');
			echo'<div class="container" align="center">

             <div class="btn-group">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#InventaireModal" data-dismiss="modal" id="InventaireStock">Inventaire par Jour </button>
                <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-toggle="dropdown">
                    <span class="caret"></span>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Par Mois</a>
                    <a class="dropdown-item" href="#">Par Année</a>
                </div>
            </div>';

            /*<button type="button" class="btn btn-success" data-toggle="modal" data-target="#InventaireModal" data-dismiss="modal" id="InventaireStock">
<i class="glyphicon glyphicon-plus"></i>Historique Inventaire </button>'; */

echo'<div class="modal fade" id="InventaireModal" role="dialog">';
echo'<div class="modal-dialog">';
echo'<div class="modal-content">';
echo'<div class="modal-header" style="padding:35px 50px;">';
echo'<button type="button" class="close" data-dismiss="modal">&times;</button>';
echo"<h4><span class='glyphicon glyphicon-lock'></span> Date Inventaire </h4>";
echo'</div>';
echo'<div class="modal-body" style="padding:40px 50px;">';

echo'<table width="100%" align="center" border="0">'.
'<form role="form" class="" name="formulaire2" id="InventaireStockForm" method="post" action="invPerma.php">';

echo'<div class="form-group" >'.
	'<tr class="reference">';
		echo'<td><input type="Date" class="form-control" min="2018-10-10" max="'.date('Y-m-d', strtotime('-1 days')).'" placeholder="14-08-2019" id="dateInventaire" name="dateInventaire" required="" value="" /></td></tr>';
	
echo'<tr><td colspan="2" align="center"><div class="modal-footer">'.
  '<input type="submit" class="boutonbasic" name="envoyer" value="RECHERCHER" />';


echo'</td></tr>'.
'</div>';
echo'</form></table><br />'.
'</div></div></div></div></div>';
if (!$dateInventaire){
	 
		?>



		

        <div class="row">
        <div class="container" align="center">
            <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#LISTESTOCKS"> <b>LISTE DES ENTREES EN STOCK DU <?php echo  $dateString2; ?> (AUJOURD'HUI)</b></a></li>
            </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="LISTESTOCKS">
	        <table id="exemple" class="display" border="1" class="table table-bordered table-striped table-condensed">

				<thead>
					<tr>
						<th>REFERENCE</th>
						<th>QUANTITE</th>
						<th>UNITE</th>
						<th>TOTAL STOCK (en Article) </th>
						<th>DATE DE STOCKAGE </th>
					</tr>
				</thead>
        <tfoot>
				 <tr>
						<th>REFERENCE</th>
						<th>QUANTITE</th>
						<th>UNITE</th>
						<th>TOTAL STOCK (en Article) </th>
						<th>DATE DE STOCKAGE </th>
					</tr>
				</tfoot>   
				<tbody>
					<?php

  					$sql2="SELECT * FROM `".$nomtableStock."` WHERE dateStockage = '".$dateString2."' OR dateStockage = '".$dateString."'";
        		$res2 = mysql_query($sql2) or die ("personel requete 2".mysql_error());
						while ($stock = mysql_fetch_array($res2)) { ?>
							<tr>
								<td> <?php echo  $stock['designation']; ?>  </td>
								<td> <?php echo  $stock['quantiteStockinitial']; ?>  </td>
								<td> <?php echo  $stock['uniteStock']; ?>  </td>
								<td> <?php echo  $stock['totalArticleStock']; ?>  </td>
								<td> <?php 
								
								$date1=$stock['dateStockage'];
	  
										  $date2 = new DateTime($date1);
											//R�cup�ration de l'ann�e
											$annee =$date2->format('Y');
											//R�cup�ration du mois
											$mois =$date2->format('m');
											//R�cup�ration du jours
											$jour =$date2->format('d');
										$date=$jour.'-'.$mois.'-'.$annee;
								
								echo  $date;?> </td> 
							</tr>
					
	<!----------------------------------------------------------->
					<?php 	}
					?>

				</tbody>
			</table>
        </div>
	</div>
	</div>
	</div>
	</div>
	



<div class="row">
        <div class="container" align="center">
            <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#LISTESTOCKSORTIE"> <b>LISTE DES SORTIES EN STOCK DU <?php echo  $dateString2; ?> (AUJOURD'HUI)</b></a></li>
            </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="LISTESTOCKSORTIE">
	        <table id="exemple2" class="display" border="1" class="table table-bordered table-striped table-condensed">

				<thead>
					<tr>
						<th>REFERENCE</th>
						<th>QUANTITE</th>
						<th>UNITE </th>
					</tr>
				</thead>
        <tfoot>
				 <tr>
						<th>REFERENCE</th>
						<th>QUANTITE</th>
						<th>UNITE </th>
					</tr>
				</tfoot>   
				<tbody>
					<?php
						$sql2="SELECT designation, quantite, unitevente FROM `".$nomtableLigne."` , `".$nomtablePagnet."` WHERE `".$nomtableLigne."`.idPagnet = `".$nomtablePagnet."`.idPagnet AND datepagej = '".$dateString."'";
  					//echo $sql2; 
        		$res2 = mysql_query($sql2) or die ("personel requete 2".mysql_error());
						while ($sortie = mysql_fetch_array($res2)) { ?>
							<tr>
								<td> <?php echo  $sortie['designation']; ?>  </td>
								<td> <?php echo  $sortie['quantite']; ?>  </td>
								<td> <?php echo  $sortie['unitevente']; ?>  </td>
							</tr>
					
	<!----------------------------------------------------------->
					<?php 	}
					?>

				</tbody>
			</table>
        </div>
	</div>
	</div>
	</div>
	</div>
	<?php 	}else{


$dateInv = date("d-m-Y", strtotime($dateInventaire));
$dateInv2 = date("Y-m-d", strtotime($dateInventaire));
//echo $dateInv;
	?>
	
	
	
 <div class="row">
        <div class="container" align="center">
            <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#LISTESTOCKS"> <b>LISTE DES ENTREES EN STOCK DU <?php echo  $dateInv ?></b></a></li>
            </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="LISTESTOCKS">
	        <table id="exemple" class="display" border="1" class="table table-bordered table-striped table-condensed">

				<thead>
					<tr>
						<th>REFERENCE</th>
						<th>QUANTITE</th>
						<th>UNITE</th>
						<th>TOTAL STOCK (en Article) </th>
						<th>DATE DE STOCKAGE </th>
					</tr>
				</thead>
        <tfoot>
				 <tr>
						<th>REFERENCE</th>
						<th>QUANTITE</th>
						<th>UNITE</th>
						<th>TOTAL STOCK (en Article) </th>
						<th>DATE DE STOCKAGE </th>
				</tr>
		</tfoot>   
		<tbody>
					<?php

  					$sql2="SELECT * FROM `".$nomtableStock."` WHERE dateStockage = '".$dateInv."' OR dateStockage = '".$dateInv2."'";
        		$res2 = mysql_query($sql2) or die ("personel requete 2".mysql_error());
						//echo $sql2;
						while ($stock = mysql_fetch_array($res2)) { ?>
							<tr>
								<td> <?php echo  $stock['designation']; ?>  </td>
								<td> <?php echo  $stock['quantiteStockinitial']; ?>  </td>
								<td> <?php echo  $stock['uniteStock']; ?>  </td>
								<td> <?php echo  $stock['totalArticleStock']; ?>  </td>
								<td> <?php 
								
								$date1=$stock['dateStockage'];
	  
										  $date2 = new DateTime($date1);
											//R�cup�ration de l'ann�e
											$annee =$date2->format('Y');
											//R�cup�ration du mois
											$mois =$date2->format('m');
											//R�cup�ration du jours
											$jour =$date2->format('d');
										$date=$jour.'-'.$mois.'-'.$annee;
								
								echo  $date;?>  </td> 
							</tr>
					
	<!----------------------------------------------------------->
					<?php 	}
					?>

				</tbody>
			</table>
        </div>
	</div>
	</div>
	</div>
	</div>
	



<div class="row">
        <div class="container" align="center">
            <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#LISTESTOCKSORTIE"> <b>LISTE DES SORTIES EN STOCK DU <?php echo  $dateInv; ?></b></a></li>
            </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="LISTESTOCKSORTIE">
	        <table id="exemple2" class="display" border="1" class="table table-bordered table-striped table-condensed">

				<thead>
					<tr>
						<th>REFERENCE</th>
						<th>QUANTITE</th>
						<th>UNITE </th>
					</tr>
				</thead>
        <tfoot>
					 <tr>
						<th>REFERENCE</th>
						<th>QUANTITE</th>
						<th>UNITE </th>
					</tr>
				</tfoot>   
				<tbody>
					<?php
						$sql2="SELECT designation, quantite, unitevente FROM `".$nomtableLigne."` , `".$nomtablePagnet."` WHERE `".$nomtableLigne."`.idPagnet = `".$nomtablePagnet."`.idPagnet AND datepagej = '".$dateInv."'";
  					//echo $sql2; 
        		$res2 = mysql_query($sql2) or die ("personel requete 2".mysql_error());
						while ($sortie = mysql_fetch_array($res2)) { ?>
							<tr>
								<td> <?php echo  $sortie['designation']; ?>  </td>
								<td> <?php echo  $sortie['quantite']; ?>  </td>
								<td> <?php echo  $sortie['unitevente']; ?>  </td>
							</tr>
					
	<!----------------------------------------------------------->
					<?php 	}
					?>

				</tbody>
			</table>
        </div>
	</div>
	</div>
	</div>
	</div>	
	
	
<?php	
	}
					?>

</body>
</html>
