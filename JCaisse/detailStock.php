<?php 
session_start();
if(!$_SESSION['iduser']){
	header('Location:../index.php');
}

require('connection.php');

/**********************/

require('declarationVariables.php');

require('entetehtml.php');
?>

<body>
	<?php 
		  require('header.php');
	?>
	<div class="container">
		<div class="row">
			<section>
				<?php 
					$sql3="SELECT * from `".$nomtableStock."` where designation='".$_GET["designation"]."'";
					 
                    if($res3=mysql_query($sql3)){
                    echo'<table class="tableau3" width="100%" align="left" border="1"><th>DESIGNATION</th><th>QUANTITE</th><th>UNITE STOCK</th><th>NOMBRE INITIAL</th><th>RESTANT</th><th>EXPIRATION</th><th>OPERATIONS</th>';
                      while($tab3=mysql_fetch_array($res3)){
                              echo'<tr><td>'.$tab3["designation"].'</td>
                              <td align="right">'.$tab3["quantiteStockinitial"].'</td>
                              <td align="right">'.$tab3["uniteStock"].'</td>
							  <td align="right">'.$tab3["totalArticleStock"].'</td>
							  <td align="right">'.$tab3["quantiteStockCourant"].'</td>
                              <td align="right">'.$tab3["dateExpiration"].'</td>
                              <td><a href="#">
                              <img src="images/edit.png" align="middle" alt="modifier" data-toggle="modal" data-target="#imgmodifier'.$tab3["idStock"].'" /></a>&nbsp;&nbsp;
                              <a href="#">'.
                              '<img src="images/drop.png" align="middle" alt="supprimer" data-toggle="modal" data-target="#imgsup'.$tab3["idStock"].'" /></a>
                              <a href="codeBarreStock.php?iDS='.$tab3["idStock"].'&iDD='.$tab3["idDesignation"].'">'.
                              'code barre</a></td></tr>';
                          	
                          	}
                          }
	                   else{
	                      echo'<table class="tableau2" width="80%" align="center" border="1"><th>DESIGNATION</th><th>QUANTITE</th><th>UNITE STOCK</th><th>NOMBRE ARTICLE/US</th><th>DATE EXPIRATION</th><th></th>';
	                      echo'<tr><td colspan="6">Liste des Stocks généraux de Produits de la date du '.$dateString.' est pour le moment vide</td></tr>';
	                  	}
	                    
	                    echo'</table><br />';
	 			?>
			</section>
		</div>
	</div>

	
</body>
</html>