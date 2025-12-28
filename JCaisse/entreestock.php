<?php
session_start();

if(!$_SESSION['iduser']){
	header('Location:../index.php');
}

require('connection.php');

require('declarationVariables.php');

require('entetehtml.php');
?>

<body>

		<?php
		  require('header.php');
		?>



		

        <div class="row">
        <div class="container" align="center">
            <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#LISTESTOCKS"> LISTE DES ENTREES EN STOCK </a></li>
            </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="LISTESTOCKS">
	        <table id="exemple" class="display" border="1" class="table table-bordered table-striped table-condensed">

				<thead>
					<tr>
						<th>Designation</th>
						<th>Quantite du Stock</th>
						<th>Unite du Stock</th>
						<th>Total Stock (en Article) </th>
            <th>Date de Stockage </th>
					</tr>
				</thead>
        <tfoot>
				 <tr>
						<th>Designation</th>
						<th>Quantite du Stock</th>
						<th>Unite du Stock</th>
						<th>Total Stock (en Article) </th>
            <th>Date de Stockage </th>
					</tr>
				</tfoot>
				<tbody>
					<?php

						$sql2="SELECT designation, quantiteStockinitial, uniteStock, totalArticleStock, dateStockage FROM `".$nomtableStock."` WHERE dateStockage = `".$dateString."`";
						$res2 = mysql_query($sql2) or die ("personel requete 2".mysql_error());
						while ($stock = mysql_fetch_array($res2)) { ?>
							<tr>
								<td> <?php echo  $stock['designation']; ?>  </td>
								<td> <?php echo  $stock['quantiteStockinitial']; ?>  </td>
								<td> <?php echo  $stock['uniteStock']; ?>  </td>
								<td> <?php echo  $stock['totalArticleStock']; ?>  </td>
								<td> <?php echo  $stock['dateStockage'];?> </td> 
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

</body>
</html>
