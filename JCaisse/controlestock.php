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
			
			echo'<div class="container">
      <ul class="nav nav-tabs">';
      echo'<li class="active"><a data-toggle="tab" href="#PRODUIT">CONTROLE DE STOCKS TYPE 1</a></li>';
      echo'<li><a data-toggle="tab" href="#SERVICE">CONTROLE DE STOCKS TYPE 2</a></li>';
      echo'</ul><div class="tab-content">';
      
      echo'<div id="PRODUIT" class="tab-pane fade in active">';
		 
		?>


              <form class="" id="" method="post" action="controlestock.php">
              <div class="table-responsive">
			 				 <table id="exemple2" class="display" border="1" class="table table-bordered table-striped table-condensed">	 
          				<thead>
          					<tr>
          						<th>REFERENCE</th>
          						<th>QUANTITE ARTICLE </th>
          					</tr>
          				</thead>
                  <tfoot>
          					<tr>
          						<th>REFERENCE</th>
          						<th>QUANTITE ARTICLE </th>
          					</tr>
          				</tfoot>							
    						 				
<?php
		$nbre1=0; $nbre2=0; $affiche="";
                                $sql="select * from `".$nomtableTotalStock."` WHERE `quantiteEnStocke` >=0";
                                $res=mysql_query($sql);
        												if(mysql_num_rows($res))
        												    while($tab=mysql_fetch_array($res)){
    																		$sql2="select * from `".$nomtableDesignation."` where designation ='".$tab["designation"]."'";
    																		$res2=mysql_query($sql2);
																				//echo $sql2;
																				if($tab2=mysql_fetch_array($res2)){
																						//echo $tab2["uniteStock"];
																						//echo $tab["designation"];
																						//echo $tab2["nbreArticleUniteStock"];
																						if ($tab2["uniteStock"]!='Article'){
																							  $nbre1=(int)($tab["quantiteEnStocke"]/$tab2["nbreArticleUniteStock"]);
																								$nbre2=$tab["quantiteEnStocke"]%$tab2["nbreArticleUniteStock"];
																								//echo $nbre1."-"; 
																								//echo $nbre2;
																								//$affiche1=$nbre1." ".$tab2["uniteStock"]."(s)";
																								//$affiche2=$nbre2." Article(s)";
                                                echo'<tr><td>'.$tab["designation"].'</td>'.
            																				'<td><input type="text" name="nbrEnArticleTotal" id="nbrEnArticleTotal'.$tab2['idDesignation'].'" size="3" value="'.$tab["quantiteEnStocke"].'"/>Article(s)</br> nbrEnArticleTotal-'.$tab2['idDesignation'].'</td></tr>';
																						}
																						else{
																								//$affiche3="0 ".$tab2["uniteStock"];
																								//$affiche4=$tab["quantiteEnStocke"]." Article(s)";																						
																								echo'<tr><td>'.$tab["designation"].'</td>'.
            																				'<td><input type="text" name="nbrEnArticleTotal" id="nbrEnArticleTotal'.$tab2['idDesignation'].'" size="3" value="'.$tab["quantiteEnStocke"].'"/>Article(s)</br> nbrEnArticleTotal'.$tab2['idDesignation'].'</td></tr>';																										
																					}
																		}}																		
?>																		
														</table>
														</div>
															 <center>	
																<button type="submit" name="btnCorriger" class="btn btn-success" data-toggle="modal" data-target="#Corriger">
																									<i class="glyphicon glyphicon-plus"></i>Corriger
																		</button>
																		<button type="submit" name="btnAnnuler" class="btn btn-danger" data-toggle="modal" data-target="#Annuler">
																										<i class="glyphicon glyphicon-plus"></i>Annuler
																		</button>
																		</center>	
																		
																		</br></br>
                         	</form> <br /></div>
													
													
													
													
													
													
						<div id="SERVICE" class="tab-pane fade in active">				
					
									
              <form class="" id="" method="post" action="controlestock.php">
            	<div class="table-responsive">
			 				 <table id="exemple3" class="display" border="1" class="table table-bordered table-striped table-condensed">	 
          				<thead>
          					<tr>
          						<th>REFERENCE</th>
          						<th colspan="3">QUANTITE UNITE STOCK</th>
          					</tr>
          				</thead>
                  <tfoot>
          					<tr>
          						<th>REFERENCE</th>
          						<th colspan="3">QUANTITE UNITE STOCK</th>
          					</tr>
          				</tfoot>							
    						 				
<?php
		$nbre1=0; $nbre2=0; $affiche="";
                                $sql="select * from `".$nomtableTotalStock."` WHERE `quantiteEnStocke` >=0";
                                $res=mysql_query($sql);
        												if(mysql_num_rows($res))
        												    while($tab=mysql_fetch_array($res)){
    																		$sql2="select * from `".$nomtableDesignation."` where designation ='".$tab["designation"]."'";
    																		$res2=mysql_query($sql2);
																				//echo $sql2;
																				if($tab2=mysql_fetch_array($res2)){
																						//echo $tab2["uniteStock"];
																						//echo $tab["designation"];
																						//echo $tab2["nbreArticleUniteStock"];
																						if ($tab2["uniteStock"]!='Article'){
																							  $nbre1=(int)($tab["quantiteEnStocke"]/$tab2["nbreArticleUniteStock"]);
																								$nbre2=$tab["quantiteEnStocke"]%$tab2["nbreArticleUniteStock"];
																								//echo $nbre1."-"; 
																								//echo $nbre2;
																								//$affiche1=$nbre1." ".$tab2["uniteStock"]."(s)";
																								//$affiche2=$nbre2." Article(s)";
                                                echo'<tr><td>'.$tab["designation"].'</td>'.
                                                    '<td><input type="text" name="nbrEnUniteStock" id="nbrEnUniteStock-'.$tab2['idDesignation'].'" size="3" value="'.$nbre1.'"/>'.$tab2["uniteStock"].'(s)</br> nbrEnUniteStock-'.$tab2['idDesignation'].'</td><td> + </td><td><input type="text" name="nbrArticle" id="nbrArticle-'.$tab2['idDesignation'].'" size="3" value="'.$nbre2.'"/>Article(s)</br> nbrArticle-'.$tab2['idDesignation'].'</td>';
																						}
																						else{
																								//$affiche3="0 ".$tab2["uniteStock"];
																								//$affiche4=$tab["quantiteEnStocke"]." Article(s)";																						
																								echo'<tr><td>'.$tab["designation"].'</td>'.
                                                    '<td><input type="text" name="nbrEnUniteStock" id="nbrEnUniteStock-'.$tab2['idDesignation'].'" size="3" value="0" disabled=""/></br> nbrEnUniteStock-'.$tab2['idDesignation'].'</td><td> + </td><td><input type="text" name="nbrArticle" id="nbrArticle-'.$tab2['idDesignation'].'" size="3" value="'.$tab["quantiteEnStocke"].'"/>Article(s)</br>nbrArticle-'.$tab2['idDesignation'].'</td>';																										
																					}
																		}}																		
?>																		
														</table>
														</div>
															 <center>	
																<button type="submit" name="btnTerminerInP" class="btn btn-success" data-toggle="modal" data-target="#addClient">
																									<i class="glyphicon glyphicon-plus"></i>Corriger
																		</button>
																		<button type="submit" name="btnAnnulerInP" class="btn btn-danger" data-toggle="modal" data-target="#addClient">
																										<i class="glyphicon glyphicon-plus"></i>Annuler
																		</button>
																		</center>	
																		
																		</br></br>
                         	</form><br /></div>
</div></div></body>
</html>
