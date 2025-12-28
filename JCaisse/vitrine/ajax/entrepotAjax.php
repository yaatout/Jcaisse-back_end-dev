
<?php

session_start();
date_default_timezone_set('Africa/Dakar');
if(!$_SESSION['iduser']){
  header('Location:../../../index.php');
}

if($_SESSION['vitrine']==0){
	header('Location:../../accueil.php');
}

require('../../connectionPDO.php');
require('../../connection.php');
require('../../connectionVitrine.php');

require('../../declarationVariables.php');

/**Debut informations sur la date d'Aujourdhui **/
$date = new DateTime();
$timezone = new DateTimeZone('Africa/Dakar');
$date->setTimezone($timezone);
$annee =$date->format('Y');
$mois =$date->format('m');
$jour =$date->format('d');
$heureString=$date->format('H:i:s');
$dateString=$annee.'-'.$mois.'-'.$jour;
$dateString2=$jour.'-'.$mois.'-'.$annee;


$operation=@htmlspecialchars($_POST["operation"]);

if ($operation == 1) { // confirmation d'un panier dans commande
    $idPanierV = $_POST['idPanier'];
    // var_dump($idPanierV);

	$getPanierV = $bddV->prepare("SELECT * FROM panier WHERE idPanier = :idP");
	$getPanierV->execute(array(
		'idP' =>$idPanierV
		)) or die(print_r($getPanierV->errorInfo()));
	$panierV=$getPanierV->fetch();
	$totalp = $panierV['total'];
	$idClientV = $panierV['idClient'];

	$getLignesV = $bddV->prepare("SELECT * FROM ligne WHERE barrer=0 and idPanier = :idP");
	$getLignesV->execute(array(
		'idP' =>$idPanierV
		)) or die(print_r($getLignesV->errorInfo()));
	$lignesV=$getLignesV->fetchAll();

	$date = date("d-m-Y");
	$heure = date("H:i:s");
    
    $getClientJCaisse = $bdd->prepare("SELECT * FROM `".$nomtableClient."` where idClientVitrine = :idC");
    $getClientJCaisse->execute(array(
        'idC' =>$idClientV
        )) or die(print_r($getClientJCaisse->errorInfo()));
    $client=$getClientJCaisse->fetch();

    if ($client == false) {
        
        $getClient = $bddV->prepare("SELECT * FROM client WHERE idClient = :idC");
        $getClient->execute(array(
            'idC' =>$idClientV
            )) or die(print_r($getClient->errorInfo()));
        $clientV=$getClient->fetch();
        
	    $nom = $clientV['nom'];
	    $prenom = $clientV['prenom'];
	    $adresse = $clientV['adresse'];
	    $telephone = $clientV['telephone'];

        $req4 = $bdd->prepare("INSERT INTO
            `".$nomtableClient."` (nom,prenom,adresse,telephone,activer,idClientVitrine,iduser)
            VALUES(:n,:p,:a,:t,:act,:idV,:idU)") ;
            $req4->execute(array(
            'n' => $nom,
            'p' => $prenom,
            'a' => $adresse,
            't' =>$telephone,
            'act' =>1,
            'idV' =>$idClientV,
            'idU' => $_SESSION['iduser']
            ))  or die(print_r($req4->errorInfo()));
        $req4->closeCursor();
        $idClientJCaisse = $bdd->lastInsertId();

    } else {
        $idClientJCaisse = $client['idClient'];
    }

    $req4 = $bdd->prepare("INSERT INTO
        `".$nomtablePagnet."` (datepagej, type, idClient, heurePagnet, totalp, apayerPagnet, verrouiller, iduser, idVitrine)
        VALUES(:d,:ty,:idCJ,:h,:t,:ap,:v,:idU,:idPV)") ;
        $req4->execute(array(
        'd' => $date,
        'ty' => 11,
        'idCJ' => $idClientJCaisse,
        'h' =>$heure,
        't' =>$totalp,
        'ap' =>$totalp,
        'v' =>1,
        'idU' =>$_SESSION['iduser'],
        'idPV' => $idPanierV
        ))  or die(print_r($req4->errorInfo()));
    $req4->closeCursor();
    $idPagnet = $bdd->lastInsertId();

    
	foreach ($lignesV as $ligne) {
		$getDesignation = $bddV->prepare("SELECT * FROM `".$nomtableDesignation."` WHERE idDesignation = :idDes");
		$getDesignation->execute(array(
			'idDes' =>$ligne['idDesignation']
			)) or die(print_r($getDesignation->errorInfo()));
		$designation=$getDesignation->fetch();
        
        $req4 = $bdd->prepare("INSERT INTO
            `".$nomtableLigne."` (idPagnet,idDesignation,idEntrepot, designation, unitevente, prixunitevente, quantite, prixtotal, classe)
            VALUES(:idP,:idD,:idE,:des,:u,:pu,:qty,:pt,:c)") ;
            $req4->execute(array(
            'idP' => $idPagnet,
            'idD' => $designation['idDesignation'],
            'idE' => $ligne['idEntrepot'],
            'des' => $ligne['designation'],
            'u' => $ligne['unite'],
            'pu' => $ligne['prix'],
            'qty' => $ligne['quantite'],
            'pt' => $ligne['prixTotal'],
            'c' =>0
            ))  or die(print_r($req4->errorInfo()));
        $req4->closeCursor();
      
                
        if ($ligne['unite']==$designation['uniteStock']) {

            $sqlD="SELECT idEntrepotStock,quantiteStockCourant FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."' AND idEntrepot='".$ligne['idEntrepot']."' AND quantiteStockCourant<>0 ORDER BY idEntrepotStock ASC ";

            $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

            $restant=$ligne['quantite'] * $designation['nbreArticleUniteStock'];

            while ($stock = mysql_fetch_assoc($resD)) {

                if($restant>= 0){

                    $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;

                    if($quantiteStockCourant > 0){

                        $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$quantiteStockCourant." where idEntrepotStock=".$stock['idEntrepotStock'];

                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                    }

                    else{

                        $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=0 where idEntrepotStock=".$stock['idEntrepotStock'];

                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                    }

                    $restant= $restant - $stock['quantiteStockCourant'] ;

                }

            }

        }

        else if ($ligne['unite']=='demi') {

            $sqlD="SELECT idEntrepotStock,quantiteStockCourant FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."' AND idEntrepot='".$ligne['idEntrepot']."' AND quantiteStockCourant<>0 ORDER BY idEntrepotStock ASC ";

            $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

            $restant=$ligne['quantite'] * ($designation['nbreArticleUniteStock']/2);

            while ($stock = mysql_fetch_assoc($resD)) {

                if($restant>= 0){

                    $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;

                    if($quantiteStockCourant > 0){

                        $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$quantiteStockCourant." where idEntrepotStock=".$stock['idEntrepotStock'];

                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                    }

                    else{

                        $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=0 where idEntrepotStock=".$stock['idEntrepotStock'];

                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                    }

                    $restant= $restant - $stock['quantiteStockCourant'] ;

                }

            }

        }

        else {

            $sqlD="SELECT idEntrepotStock,quantiteStockCourant FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."' AND idEntrepot='".$ligne['idEntrepot']."' AND quantiteStockCourant<>0 ORDER BY idEntrepotStock ASC ";

            $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

            $restant=$ligne['quantite'];

            while ($stock = mysql_fetch_assoc($resD)) {

                if($restant>= 0){

                    $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;

                    if($quantiteStockCourant > 0){

                        $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$quantiteStockCourant." where idEntrepotStock=".$stock['idEntrepotStock'];

                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                    }

                    else{

                        $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=0 where idEntrepotStock=".$stock['idEntrepotStock'];

                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                    }

                    $restant= $restant - $stock['quantiteStockCourant'] ;

                }

            }

        }

    }
    
    $confirmer=1;
    $req1 = $bddV->prepare("UPDATE panier SET
                  confirmer=:confirmer,
                  dateConfirmer= NOW()
                  WHERE idPanier=:idPanier ");
    $req1->execute(array(
                    'confirmer' => $confirmer,
                    'idPanier' => $idPanierV )) or die(print_r($req1->errorInfo()));
    $req1->closeCursor();

    exit(1);

} else if ($operation == 2){ // retour d'un panier
    $idPagnet=htmlspecialchars(trim($_POST['idPanier']));
    $retourner=1;
    $confirmer=0;

    $sqlP="SELECT * FROM `".$nomtablePagnet."` where idVitrine=".$idPagnet." ";
    $resP = mysql_query($sqlP) or die ("persoonel requête 2".mysql_error());
    $pagnet = mysql_fetch_assoc($resP) ;

    $sqlL="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ";
    $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());

    
    if($pagnet['type']==11){

        while ($ligne=mysql_fetch_assoc($resL)){

            if($ligne['classe']==0){

                $sqlS="SELECT idDesignation,designation,uniteStock,nbreArticleUniteStock FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];

                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                $designation = mysql_fetch_assoc($resS) ;
                

                    if(mysql_num_rows($resS)){

                        if ($ligne['unitevente']==$designation['uniteStock']) {

                            $sqlD="SELECT idEntrepotStock,quantiteStockCourant,totalArticleStock FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."'  AND idEntrepot='".$ligne['idEntrepot']."' ORDER BY idEntrepotStock DESC LIMIT 10 ";

                            $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                            $retour=$ligne['quantite'] * $designation['nbreArticleUniteStock'];

                            while ($stock = mysql_fetch_assoc($resD)) {

                                if($retour>= 0){

                                    $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;

                                    if($stock['totalArticleStock'] >= $quantiteStockCourant){

                                        $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$quantiteStockCourant." where idEntrepotStock=".$stock['idEntrepotStock'];

                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                    }

                                    else{

                                        $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$stock['totalArticleStock']." where idEntrepotStock=".$stock['idEntrepotStock'];

                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                    }

                                    $retour=($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'] ;

                                }

                            }



                        }

                        else if ($ligne['unitevente']=='demi') {

                            $sqlD="SELECT idEntrepotStock,quantiteStockCourant,totalArticleStock FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."'  AND idEntrepot='".$ligne['idEntrepot']."' ORDER BY idEntrepotStock DESC  LIMIT 10 ";

                            $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                            $retour=$ligne['quantite']* ($designation['nbreArticleUniteStock']/2);

                            while ($stock = mysql_fetch_assoc($resD)) {

                                if($retour>= 0){

                                    $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;

                                    if($stock['totalArticleStock'] >= $quantiteStockCourant){

                                        $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$quantiteStockCourant." where idEntrepotStock=".$stock['idEntrepotStock'];

                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                    }

                                    else{

                                        $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$stock['totalArticleStock']." where idEntrepotStock=".$stock['idEntrepotStock'];

                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                    }

                                    $retour=($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'] ;

                                }

                            }



                        }

                        else {

                            $sqlD="SELECT idEntrepotStock,quantiteStockCourant,totalArticleStock  FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."'  AND idEntrepot='".$ligne['idEntrepot']."' ORDER BY idEntrepotStock DESC  LIMIT 10 ";

                            $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                            $retour=$ligne['quantite'];

                            while ($stock = mysql_fetch_assoc($resD)) {

                                if($retour >= 0){

                                    $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;

                                    if($stock['totalArticleStock'] >= $quantiteStockCourant){

                                        $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$quantiteStockCourant." where idEntrepotStock=".$stock['idEntrepotStock'];

                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                    }

                                    else{

                                        $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$stock['totalArticleStock']." where idEntrepotStock=".$stock['idEntrepotStock'];

                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                    

                                    }

                                    $retour=($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'] ;

                                    

                                }

                                

                            }



                        }

                    }

            }

        }
        // suppression du pagnet aprés su^ppression de ses lignes

        $sqlR="UPDATE `".$nomtablePagnet."` set type=2 where idVitrine=".$idPagnet;

        $resR=@mysql_query($sqlR) or die ("mise à jour client  impossible".mysql_error());
    }    



    $req1 = $bddV->prepare("UPDATE panier SET
          retourner=:retourner ,
          confirmer=:confirmer,
          dateRetourner=NOW()
          WHERE idPanier=:idPanier ");
    //var_dump($req1);
    $req1->execute(array(
                        'retourner' => $retourner,
                        'confirmer' => $confirmer,
                        'idPanier' => $idPagnet )) or die(print_r($req1->errorInfo()));
    $req1->closeCursor();

    exit('1');
}
else if ($operation == 3){
    $idArticle=$_POST['idArticle'];
    $idEntrepot=$_POST['idEntrepot'];
    $idDesignation=$_POST['idDesignation'];
    $qty=$_POST['qty'];
    $uniteV=$_POST['uniteV'];
    $qtyADebiter = 0;

    $sqlD0="SELECT * FROM `".$nomtableDesignation."` where idDesignation='".$idDesignation."'";
    // var_dump($sqlD);
    $resD0=mysql_query($sqlD0) or die ("select designation impossible =>".mysql_error());
    $designation = mysql_fetch_array($resD0);

    $sqlD="SELECT sum(quantiteStockCourant) FROM `".$nomtableEntrepotStock."` where idDesignation='".$idDesignation."' AND idEntrepot='".$idEntrepot."'";
    // var_dump($sqlD);
    $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
    $stock = mysql_fetch_array($resD);
    // var_dump($resD);
    // var_dump($stock[0]);

    if ($uniteV == $designation['uniteStock']) {

        $qtyADebiter = $qty * $designation['nbreArticleUniteStock'];

    } else if($uniteV == 'demi') {
        
        $qtyADebiter = $qty * ($designation['nbreArticleUniteStock']/2);

    } else {
        
        $qtyADebiter = $qty;
    }
    // var_dump($qtyADebiter);

    if ($resD && $stock[0]>$qtyADebiter) {
        # code...
    
        $req1 = $bddV->prepare("UPDATE ligne SET
            idEntrepot=:idE
            WHERE idArticle=:idA ");
        $req1->execute(array(
            'idA' => $idArticle,
            'idE' => $idEntrepot )) or die(print_r($req1->errorInfo()));
        $req1->closeCursor();

        if ($req1) {
            echo ('1');
            exit();
        } else {
            echo ('0');
            exit();
        }
    } else {
        echo ('3');
        exit();
    }
    
}
