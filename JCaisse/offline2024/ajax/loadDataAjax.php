<?php
    session_start();
    if(!$_SESSION['iduser']){
    header('Location:../index.php');
    }

    require('../connexion.php');

    require('../declarationVariables.php');

	$tab_Boutique=array();
    $tabSearchElement=array();

    $reqS="SELECT * from `".$nomtableDesignation."`";
	$resS=mysql_query($reqS) or die ("Erreur!!!");

    $sql="SELECT * FROM `aaa-utilisateur` u
    INNER JOIN `aaa-acces` a ON a.idutilisateur = u.idutilisateur 
    INNER JOIN `aaa-boutique` b ON b.idBoutique = a.idBoutique 
    WHERE b.idBoutique=".$_SESSION['idBoutique']." AND b.offline=1
    AND (a.caissier=1 or a.vendeur=1 or a.proprietaire=1)";
    $res=mysql_query($sql) or die ("Erreur!!!");

    $reqC="SELECT * from `".$nomtableCompte."`";
	$resC=mysql_query($reqC) or die ("Erreur!!!");

	$reqCli="SELECT * from `".$nomtableClient."` WHERE activer=1 AND archiver=0";
	$resCli=mysql_query($reqCli) or die ("Erreur!!!");

    $reqS="SELECT * from `".$nomtableDesignation."`";
	$resS=mysql_query($reqS) or die ("Erreur!!!");

    $iduser=$_SESSION['iduser'];

    // $reqSal="SELECT * from `aaa-payement-salaire` WHERE idBoutique=".$_SESSION['idBoutique']."";
	// $resSal=mysql_query($reqSal) or die ("Erreur!!!");

	$reqB="SELECT * from `".$nomtableBon."`";
	$resB=mysql_query($reqB) or die ("Erreur!!!");

	$reqV="SELECT * from `".$nomtableVersement."`";
	$resV=mysql_query($reqV) or die ("Erreur!!!");

	$reqP="SELECT * from `".$nomtablePagnet."` WHERE iduser=".$_SESSION['iduser']." && datepagej= '".$dateString2."' && type!=2 ORDER BY idPagnet ASC LIMIT 10"; 

	$resP=mysql_query($reqP) or die ("Erreur!!!");  
	//$panier=mysql_fetch_assoc($resP);
	//var_dump($resP);  
	$paniers=[];
    
	while($panier=mysql_fetch_assoc($resP)){

		if (in_array($panier['idPagnet'], $tabSearchElement)){



		}else{

			$paniers[]=$panier;



		}

	} 
	
	while($bon=mysql_fetch_assoc($resB)){
		if (in_array($bon['idBon'], $tabSearchElement)){

		}else{
			$tabB[]=$bon;

		}
	}

	while($verse=mysql_fetch_assoc($resV)){
		if (in_array($verse['idVersement'], $tabSearchElement)){

		}else{
			$tabV[]=$verse;

		}
	}

    // while($payeS=mysql_fetch_assoc($resSal)){
	// 	if (in_array($payeS['idPS'], $tabSearchElement)){

	// 	}else{
	// 		$salaire[]=$payeS;

	// 	}
	// }

    while($des=mysql_fetch_assoc($resS)){
		if (in_array($des['idDesignation'], $tabSearchElement)){

		}else{
			$design[]=$des;

		}
	}

    while($user=mysql_fetch_array($res)){
        //var_dump($user);
        if (in_array($user['idutilisateur'], $tabSearchElement)){

		}else{
            $tab_User[]=$user;
			//var_dump($tab_User);
		}

    }

    while($cpt=mysql_fetch_assoc($resC)){
		if (in_array($cpt['idCompte'], $tabSearchElement)){

		}else{
			$tabC[]=$cpt;
			
		}
	}

	while($cli=mysql_fetch_assoc($resCli)){
		if (in_array($cli['idClient'], $tabSearchElement)){

		}else{ 
			$tabCli[]=$cli;
			
		}
	}

    $result=$iduser.'<>'.json_encode($tab_User).'<>'.json_encode($design).'<>'.json_encode($tabC).'<>'.json_encode($tabCli).'<>'.json_encode($tabB).'<>'.json_encode($tabV).'<>'.json_encode($paniers);

    exit($result);     

    

?>    
