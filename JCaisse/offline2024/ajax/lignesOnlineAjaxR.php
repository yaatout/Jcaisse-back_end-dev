<?php

	session_start();

	if(!$_SESSION['iduser']){ 

	header('Location:../index.php');

	}
  
	require('../connexion.php');
	require('../declarationVariables.php');

	$lignes=[];
	$tabSearchElement=array();

	$reqP="SELECT * from `".$nomtablePagnet."` WHERE datepagej= '".$dateString2."' && type!=2 ORDER BY idPagnet DESC"; 

	$resP=mysql_query($reqP) or die ("Erreur!!!");  
	//$panier=mysql_fetch_assoc($resP);
	//var_dump($resP);  
	
   
	while($panier=mysql_fetch_assoc($resP)){ 

		$sqlL = "SELECT * FROM `".$nomtableLigne."` WHERE idPagnet=".$panier['idPagnet']."";  
		$resL=mysql_query($sqlL) or die ("selection lignes impossible =>".mysql_error());
		//$ligne=mysql_fetch_assoc($resL);   
		//var_dump($sql);
		while($ligne=mysql_fetch_assoc($resL)){

			if (in_array($ligne['numligne'], $tabSearchElement)){
	
			}else{

				$lignes[]=$ligne;



			}
		}
	}   

  

	//$sql = "SELECT * FROM `".$nomtableLigne."` WHERE idPagnet=$idPanier";
	//var_dump($sql);
	//$res=mysql_query($sql) or die ("selection lignes impossible =>".mysql_error());
	//$res=mysql_query($sql);  
	//$ligne=mysql_fetch_assoc($res);    

	

	// $lignes=[];
	
	// while($ligne=mysql_fetch_assoc($res)){

	// 	if (in_array($ligne['numligne'], $tabSearchElement)){



	// 	}else{

	// 		$lignes[]=$ligne;



	// 	}

	// } 


	$result=json_encode($lignes);


	exit($result);

?> 