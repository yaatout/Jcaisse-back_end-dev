<?php
session_start();
if(!$_SESSION['iduser']){
	header('Location:../index.php');
}
require('connection.php');
require('declarationVariables.php');
require('entetehtml.php');

if (isset($_POST['btnCorriger1'])) {
	echo'bonjour';
	//je récupére les l'ensemble des id des Références, dans la table TotalStock de la boutique en question, que je mets dans tab1.
	$sql1="select * from `".$nomtableTotalStock."` WHERE `quantiteEnStocke` !=0";
	$res1=mysql_query($sql1);
	//echo $sql1;
	while($tab1=mysql_fetch_array($res1)){   
			//pour chaqu'une des id des Références, je récupére la désignation dans tab2. 
			$sql2="select * from `".$nomtableDesignation."` where designation ='".$tab1['designation']."'";
			$res2=mysql_query($sql2);
			//echo $sql2;
			//$tab1['designation'];
			if($tab2=mysql_fetch_array($res2)){
				//pour chaqu'une des id des Références
				//je récupére aussi la quantité en stock totale(correspondant à la quatité du stock théorique) dans $QEnStock.
				$idDesignation=$tab2['idDesignation'];
				$QEnStock=$tab1['quantiteEnStocke'];
				echo $QEnStock.'-';
				//je récupére le stock réel ($nbrEnArticleTotal) donné dans le formulaire de controle de stock.
				$i=$tab2['idDesignation'];
				
				//echo "nbrEnArticleTotal-".$i;
				$nbrEnArticleTotal1 =@$_POST["nbrEnArticleTotal-".$i];
				
				echo $nbrEnArticleTotal1.'=' ;  //echo "Tbien";								
				//je calcule la variable $pointControle et je l'insert comme une nouvelle ligne dans le stock.
				$pointControle=$nbrEnArticleTotal1-$QEnStock;
				echo $pointControle;   echo "encore";
				if ($pointControle!=0){
					//echo "je suis la !---";
					$sql4="INSERT INTO `".$nomtableStock."`(designation,quantiteStockinitial,totalArticleStock,prixunitaire,uniteStock,nbreArticleUniteStock,dateStockage,quantiteStockCourant,pointControleArticle) VALUES('".$tab1['designation']."',".$pointControle.",".$pointControle.",".$tab2['prix'].",'Article',1,'".$dateString2."',".$pointControle.",".$pointControle.")";
					$res4=@mysql_query($sql4) or die ("insertion stock 3 impossible".mysql_error()) ;
					echo $sql4; 
				}
				// Ce qui permet d'ajuster les quantités de stocks dans la table TotalStock de chaque boutique en fonction des controles de stocks.
			
		}
	}
}
if (isset($_POST['btnCorriger2'])) {
	echo'bonsoir';
	//je récupére les l'ensemble des id des Références, dans la table TotalStock de la boutique en question, que je mets dans tab1.
	$sql="select * from `".$nomtableTotalStock."` WHERE `quantiteEnStocke` !=0";
	$res=mysql_query($sql);
	while($tab=mysql_fetch_array($res)){
			//pour chaqu'une des id des Références, je récupére la désignation dans tab2. 
			$sql2="select * from `".$nomtableDesignation."` where designation ='".$tab['designation']."'";
			$res2=mysql_query($sql2);
			if($tab2=mysql_fetch_array($res2)){
				//pour chaqu'une des id des Références
				//je récupére aussi la quantité en stock totale(correspondant à la quatité du stock théorique) dans $QEnStock.		
				$i=$tab2['idDesignation'];	
				if ($tab2["uniteStock"]!='Article'){
					$QEnUniteStock   =(int)($tab["quantiteEnStocke"]/$tab2["nbreArticleUniteStock"]);
					$QEnArticleStock =$tab["quantiteEnStocke"]%$tab2["nbreArticleUniteStock"];
				}
			}
			//je récupére le stock réel ($nbrEnUniteStock & $nbrArticle) donné dans le formulaire de controle de stock.		
			$nbrEnUniteStock =@$_POST['nbrEnUniteStock-'.$i];
			$nbrArticle      =@$_POST['nbrArticle-'.$i];
			//je calcule la variable $pointControle et je l'insert comme une nouvelle ligne dans le stock.		 
			$pointControleUnite=$QEnUniteStock-$nbrEnUniteStock;
			$pointControleArticle=$QEnArticleStock-$nbrArticle;
			if ($pointControleUnite!=0){
				//$sql4="INSERT INTO `".$nomtableStock."`(designation,quantiteStockinitial,prixunitaire,uniteStock,nbreArticleUniteStock,dateStockage,quantiteStockCourant,pointControleArticle,pointControleUnite) VALUES('".$designation."',".$pointControle.",'".$tab2['prix']."','".$tab2['uniteStock']."',"1",'".$dateString2."',".$pointControle."',".$pointControle.")";
				// $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;
			}
			if ($pointControleArticle!=0){
				//$sql4="INSERT INTO `".$nomtableStock."`(designation,quantiteStockinitial,prixunitaire,uniteStock,nbreArticleUniteStock,dateStockage,quantiteStockCourant,pointControle) VALUES('".$designation."',".$pointControle.",'".$tab2['prix']."',"Article","1",'".$dateString2."',".$pointControle."',".$pointControle.")";
				//$res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;
			}
				// Ce qui permet d'ajuster les quantités de stocks dans la table TotalStock de chaque boutique en fonction des controles de stocks.		 
	}
}
?>

<body>
<?php
require('header.php');

echo'<div class="container">
<ul class="nav nav-tabs">';
echo'<li class="active"><a data-toggle="tab" href="#PRODUIT">CONTROLE DE STOCKS TYPE 1</a></li>';
echo'<li><a data-toggle="tab" href="#SERVICE">CONTROLE DE STOCKS TYPE 2</a></li>'.
'</ul>';
echo'<div class="tab-content">';
?>
  <div class="tab-pane fade in active" id="TOTALSTOCK">
    <form class="" id="formulaire1" method="post" action="controlestock2.php">    
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
$sql="select * from `".$nomtableTotalStock."` WHERE `quantiteEnStocke` !=0";
//echo $sql;
$res=mysql_query($sql);
while($tab=mysql_fetch_array($res)){
	$sql2="select * from `".$nomtableDesignation."` where designation ='".$tab["designation"]."'";
	$res2=mysql_query($sql2);
	//echo $sql2;
	if($tab2=mysql_fetch_array($res2)){
		$i=$tab2['idDesignation'];
		$quantiteStocke=$tab["quantiteEnStocke"];
		echo'<tr><td>'.$tab["designation"].'</td>'.
			'<td><input type="text" name="nbrEnArticleTotal-'.$i.'" id="nbrEnArticleTotal-'.$i.'" size="3" value="'.$quantiteStocke.'"/>Article(s)</br> nbrEnArticleTotal-'.$i.'</td></tr>';
	}
}																		
?>																		
		</table>
  		
		<center>	
			<button type="submit" name="btnCorriger1" class="btn btn-success" data-toggle="modal" data-target="#Corriger">
												<i class="glyphicon glyphicon-plus"></i>Corriger
			</button>
			<button type="submit" name="btnAnnuler1" class="btn btn-danger" data-toggle="modal" data-target="#Annuler">
													<i class="glyphicon glyphicon-plus"></i>Annuler
			</button>
		</center>	
  						
  		</br></br>
    </form> <br /></div>
													
																										
	<div id="UNITESTOCK" class="tab-pane fade in active">				
		<form class="" id="" method="post" action="controlestock2.php">
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
$nbreR1=0; $nbreR2=0; $affiche="";
$sql="select * from `".$nomtableTotalStock."` WHERE `quantiteEnStocke` !=0";
$res=mysql_query($sql);
while($tab=mysql_fetch_array($res)){
	$sql2="select * from `".$nomtableDesignation."` where designation ='".$tab["designation"]."'";
	$res2=mysql_query($sql2);
	//echo $sql2;
    if($tab2=mysql_fetch_array($res2)){
		$i=$tab2['idDesignation'];
		/*echo $tab2["uniteStock"];
		echo $tab["designation"];
		echo $tab2["nbreArticleUniteStock"];*/
		if ($tab2["uniteStock"]!='Article'){
			$nbreR1=(int)($tab["quantiteEnStocke"]/$tab2["nbreArticleUniteStock"]);
			$nbreR2=$tab["quantiteEnStocke"]%$tab2["nbreArticleUniteStock"];
			/*
			echo $nbreR1."-"; 
			echo $nbreR2;
			$affiche1=$nbre1." ".$tab2["uniteStock"]."(s)";
			$affiche2=$nbre2." Article(s)";
			*/
				
        echo'<tr><td>'.$tab["designation"].'</td>'.
        '<td><input type="text" name="nbrEnUniteStock-'.$i.'" id="nbrEnUniteStock-'.$i.'" size="3" value="'.$nbreR1.'"/>'.$tab2["uniteStock"].'(s)</br> nbrEnUniteStock-'.$i.'</td><td> + </td><td><input type="text" name="nbrArticle-'.$i.'" id="nbrArticle-'.$i.'" size="3" value="'.$nbreR2.'"/>Article(s)</br> nbrArticle-'.$i.'</td>';
		}
		else{																					
			echo'<tr><td>'.$tab["designation"].'</td>'.
        '<td><input type="text" name="nbrEnUniteStock-'.$i.'" id="nbrEnUniteStock-'.$i.'" size="3" value="0" disabled=""/></br> nbrEnUniteStock-'.$i.'</td><td> + </td><td><input type="text" name="nbrArticle-'.$i.'" id="nbrArticle-'.$i.'" size="3" value="'.$tab["quantiteEnStocke"].'"/> Article(s)</br> nbrArticle-'.$i.' </td>'; 																										
	 }
}}																		
?>																		
		</table>
			
		<center>	
			<button type="submit" name="btnCorriger2" class="btn btn-success" data-toggle="modal" data-target="#addClient">
													<i class="glyphicon glyphicon-plus"></i>Corriger
			</button>
			<button type="submit" name="btnAnnuler2" class="btn btn-danger" data-toggle="modal" data-target="#addClient">
														<i class="glyphicon glyphicon-plus"></i>Annuler
			</button>
		</center>	
		</br></br>
    </form><br /></div>	
	
</div></div>

</body>
</html>
