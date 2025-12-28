<?php



session_start();

if(!$_SESSION['iduser']){

  header('Location:../index.php');

  }



  require('../connection.php');
  require('../connectionPDO.php');

  require('../connectionVitrine.php');
  
  require('../declarationVariables.php');



    /**Debut informations sur la date d'Aujourdhui **/
    $beforeTime = '00:00:00';
    $afterTime = '06:00:00';

    /**Debut informations sur la date d'Aujourdhui **/
    if($_SESSION['Pays']=='Canada'){ 
        $date = new DateTime();
        $timezone = new DateTimeZone('Canada/Eastern');
    }
    else{
        $date = new DateTime();
        $timezone = new DateTimeZone('Africa/Dakar');
    }
    $date->setTimezone($timezone);
    $heureString=$date->format('H:i:s');

    if ($heureString >= $beforeTime && $heureString < $afterTime) {
        // var_dump ('is between');
    $date = new DateTime (date('d-m-Y',strtotime("-1 days")));
    }


    $annee =$date->format('Y');

    $mois =$date->format('m');

    $jour =$date->format('d');

    $dateString=$annee.'-'.$mois.'-'.$jour;

    $dateString2=$jour.'-'.$mois.'-'.$annee;

/**Fin informations sur la date d'Aujourdhui **/



$idStock=@htmlspecialchars($_POST["idStock"]);



$designation=@htmlspecialchars($_POST["designation"]);

$categorie=@htmlspecialchars($_POST["categorie"]);

$tableau=@htmlspecialchars($_POST["tableau"]);

$forme=@htmlspecialchars($_POST["forme"]);



$operation=@htmlspecialchars($_POST["operation"]);

$idDesignation=@htmlspecialchars($_POST["idDesignation"]);

$quantite=@htmlspecialchars($_POST["quantite"]);

$quantiteR=@htmlspecialchars($_POST["quantiteR"]);

$quantiteD=@htmlspecialchars($_POST["quantiteD"]);

$uniteStock=@htmlspecialchars($_POST["uniteStock"]);

$dateExpiration=@htmlspecialchars($_POST["dateExpiration"]);



$prixUniteStock=@htmlspecialchars($_POST["prixUniteStock"]);

$prixUnitaire=@htmlspecialchars($_POST["prixUnitaire"]);

$prixAchat=@htmlspecialchars($_POST["prixAchat"]);



$prixSession=@$_POST["prixSession"];

$prixPublic=@$_POST["prixPublic"];



$codeBarre=@htmlspecialchars($_POST["codeBarre"]);

$code=@htmlspecialchars($_POST["code"]);

$codeBD=@htmlspecialchars($_POST["codeBD"]);

$codeBUS=@htmlspecialchars($_POST["codeBUS"]);



$idFournisseur=@htmlspecialchars($_POST["idFournisseur"]);

$nomFournisseur=@htmlspecialchars($_POST["nomFournisseur"]);

$adresseFournisseur=@htmlspecialchars($_POST["adresseFournisseur"]);

$telephoneFournisseur=@htmlspecialchars($_POST["telephoneFournisseur"]);

$banqueFournisseur=@htmlspecialchars($_POST["banqueFournisseur"]);

$numBanqueFournisseur=@htmlspecialchars($_POST["numBanqueFournisseur"]);



$idBl=@htmlspecialchars($_POST["idBl"]);

$fournisseur=@htmlspecialchars($_POST["fournisseur"]);

$numeroBl=@htmlspecialchars($_POST["numeroBl"]);

$dateBl=@htmlspecialchars($_POST["dateBl"]);

$montantBl=@htmlspecialchars($_POST["montantBl"]);



$idEntrepot=@htmlspecialchars($_POST["idEntrepot"]);

$idDepot=@htmlspecialchars($_POST["idDepot"]);

$nomEntrepot=@htmlspecialchars($_POST["nomEntrepot"]);

$adresseEntrepot=@htmlspecialchars($_POST["adresseEntrepot"]);

$idEntrepotStock=@htmlspecialchars($_POST["idEntrepotStock"]);

$idEntrepotTransfert=@htmlspecialchars($_POST["idEntrepotTransfert"]);



$idVoyage=@htmlspecialchars($_POST["idVoyage"]);

$destination=@htmlspecialchars($_POST["destination"]);

$motif=@htmlspecialchars($_POST["motif"]);

$dateVoyage=@htmlspecialchars($_POST["dateVoyage"]);



$debutAnnee=@htmlspecialchars($_POST["debutAnnee"]);

$finAnnee=@htmlspecialchars($_POST["finAnnee"]);

$debutJour=@htmlspecialchars($_POST["debutJour"]);

$finJour=@htmlspecialchars($_POST["finJour"]);



    if($operation == 1){



        $nombreArticleUniteStock=0;

        $categorie='Sans';



		$sql='select * from `'.$nomtableDesignation.'` where idDesignation="'.$idDesignation.'"';

		//echo $sql;

		$res=mysql_query($sql);

		if(mysql_num_rows($res)){

			if($design=mysql_fetch_array($res)) {

                $nombreArticleUniteStock  = $design["nbreArticleUniteStock"];

                $categorie=$design["categorie"];



                if (($uniteStock=="Article")||($uniteStock=="article")){

                    $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration) VALUES('.$idDesignation.',"'.mysql_real_escape_string($design["designation"]).'",'.$quantite.',"'.mysql_real_escape_string($uniteStock).'",'.$design['prix'].','.$nombreArticleUniteStock.','.$quantite.',"'.$dateString.'",'.$quantite.',"'.$dateExpiration.'")';

                    $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

                }

                else{

                    $totalArticleStock=$quantite*$nombreArticleUniteStock;

                    $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,prixuniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration) VALUES('.$idDesignation.',"'.mysql_real_escape_string($design['designation']).'",'.$quantite.',"'.mysql_real_escape_string($uniteStock).'",'.$design['prix'].','.$design['prixuniteStock'].','.$nombreArticleUniteStock.','.$totalArticleStock.',"'.$dateString.'",'.$totalArticleStock.',"'.$dateExpiration.'")';

                    $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

                }



		   }

	   }



       $sql3='SELECT * from  `'.$nomtableStock.'` where quantiteStockCourant!=0 and idDesignation="'.$idDesignation.'" order by idStock DESC';

       $res3=mysql_query($sql3);

       $stock=mysql_fetch_array($res3);



       $result=$stock['designation'].'+'.$design['categorie'].'+'.$stock['totalArticleStock'].'+'.$stock['dateStockage'];



       exit($result);





    }

    else if($operation == 2){



        $nombreArticleUniteStock=0;

        $categorie='Sans';



		$sql='select * from `'.$nomtableDesignation.'` where idDesignation="'.$idDesignation.'"';

		//echo $sql;

		$res=mysql_query($sql);

        if(mysql_num_rows($res)){

			if($design=mysql_fetch_array($res)) {

                $nombreArticleUniteStock  = $design["nbreArticleUniteStock"];

                $categorie=$design["categorie"];



                if (($uniteStock=="Article")||($uniteStock=="article")){

                    $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration) VALUES('.$idDesignation.',"'.mysql_real_escape_string($design["designation"]).'",'.$quantite.',"'.mysql_real_escape_string($uniteStock).'",'.$prixUnitaire.','.$nombreArticleUniteStock.','.$quantite.',"'.$dateString.'",'.$quantite.',"'.$dateExpiration.'")';

                    $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

                }

                else{

                    $totalArticleStock=$quantite*$nombreArticleUniteStock;

                    $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,prixuniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration) VALUES('.$idDesignation.',"'.mysql_real_escape_string($design['designation']).'",'.$quantite.',"'.mysql_real_escape_string($uniteStock).'",'.$prixUnitaire.','.$prixUniteStock.','.$nombreArticleUniteStock.','.$totalArticleStock.',"'.$dateString.'",'.$totalArticleStock.',"'.$dateExpiration.'")';

                    $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

                }



		   }

	    }



       $sql3='SELECT * from  `'.$nomtableStock.'` where quantiteStockCourant!=0 and idDesignation="'.$idDesignation.'" order by idStock DESC';

       $res3=mysql_query($sql3);

       $stock=mysql_fetch_array($res3);



       $result=$stock['designation'].'+'.$design['categorie'].'+'.$stock['totalArticleStock'].'+'.$stock['dateStockage'];



       exit($result);





    }

    else if($operation == 3){

        mysql_query("SET AUTOCOMMIT=0;");

        mysql_query("START TRANSACTION;");

        $nombreArticleUniteStock=1;
        $confirm=@htmlspecialchars($_POST["confirm"]);
        $result="0";

		$sql='select * from `'.$nomtableDesignation.'` where idDesignation="'.$idDesignation.'"';
		$res=mysql_query($sql);

		if(mysql_num_rows($res)){

            if($design=mysql_fetch_array($res)) {

                $sqlD='SELECT * from  `'.$nomtableStock.'` where quantiteStockinitial="'.$quantite.'" AND dateStockage="'.$dateString.'" AND dateExpiration="'.$dateExpiration.'" AND idDesignation="'.$idDesignation.'" order by idStock DESC';
                $resD=mysql_query($sqlD);
                
                if($stockDoublon=mysql_fetch_array($resD) && $confirm!=1) {

                    $result='2<>'.$idDesignation.'<>'.$design['designation'].'<>'.$design['codeBarreDesignation'].'<>'.$quantite.'<>'.$design['prixSession'].'<>'.$design['prixPublic'].'<>'.$dateExpiration.'<>'.$idBl;
                }
                else{

                    if($prixSession!=null && $prixPublic!=null && $idBl!=null){

                        $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,idBl,designation,quantiteStockinitial,forme,prixSession,prixPublic,dateStockage, quantiteStockCourant,dateExpiration) VALUES('.$idDesignation.','.$idBl.',"'.mysql_real_escape_string($design["designation"]).'",'.$quantite.',"'.mysql_real_escape_string($design["forme"]).'",'.$prixSession.','.$prixPublic.',"'.$dateString.'",'.$quantite.',"'.$dateExpiration.'")';
    
                        $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;
    
                        $sqlD="update `".$nomtableDesignation."` set prixSession=".$prixSession." where idDesignation=".$idDesignation;
    
                        $resD=@mysql_query($sqlD)or die ("modification impossible1 ".mysql_error());
    
                        if($res1){
    
                            $sql3='SELECT * from  `'.$nomtableStock.'` where quantiteStockCourant!=0 and idDesignation="'.$idDesignation.'" order by idStock DESC';
    
                            $res3=mysql_query($sql3);
    
                            $stock=mysql_fetch_array($res3);
    
                            $sql4="SELECT * FROM `". $nomtableBl."` where idBl='".$idBl."' ";
    
                            $res4=mysql_query($sql4);
    
                            $bl=mysql_fetch_array($res4);
    
                            mysql_query("COMMIT;");
    
                            $result='1<>'.$bl['numeroBl'].'<>'.$stock['designation'].'<>'.$stock['forme'].'<>'.$stock['quantiteStockinitial'].'<>'.$stock['prixSession'].'<>'.$stock['prixPublic'].'<>'.$stock['dateStockage'].'<>'.$design['categorie'];
    
                        }
    
                        else{
    
                            mysql_query("ROLLBACK;");
    
                        }
    
                    }
    
                    else{
    
                        if($prixSession!=null){
    
                            $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,forme,prixSession,prixPublic,dateStockage, quantiteStockCourant,dateExpiration,iduser) VALUES('.$idDesignation.',"'.mysql_real_escape_string($design["designation"]).'",'.$quantite.',"'.mysql_real_escape_string($design["forme"]).'",'.$prixSession.','.$design["prixPublic"].',"'.$dateString.'",'.$quantite.',"'.$dateExpiration.'","'.$_SESSION['iduser'].'")';
    
                        }
    
                        else{
    
                            $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,forme,prixSession,prixPublic,dateStockage, quantiteStockCourant,dateExpiration,iduser) VALUES('.$idDesignation.',"'.mysql_real_escape_string($design["designation"]).'",'.$quantite.',"'.mysql_real_escape_string($design["forme"]).'",'.$design["prixSession"].','.$design["prixPublic"].',"'.$dateString.'",'.$quantite.',"'.$dateExpiration.'","'.$_SESSION['iduser'].'")';
    
                        }
    
                        $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;
    
                        $sqlD="update `".$nomtableDesignation."` set prixSession=".$prixSession." where idDesignation=".$idDesignation;
    
                        $resD=@mysql_query($sqlD)or die ("modification impossible1 ".mysql_error());
    
                        if($res1){
    
                            $sql3='SELECT * from  `'.$nomtableStock.'` where quantiteStockCourant!=0 and idDesignation="'.$idDesignation.'" order by idStock DESC';
    
                            $res3=mysql_query($sql3);
    
                            $stock=mysql_fetch_array($res3);
    
                            mysql_query("COMMIT;");
    
                            $result='1<>NEANT<>'.$stock['designation'].'<>'.$stock['forme'].'<>'.$stock['quantiteStockinitial'].'<>'.$stock['prixSession'].'<>'.$stock['prixPublic'].'<>'.$stock['dateStockage'].'<>'.$design['categorie'];
    
                        }
    
                        else{
    
                            mysql_query("ROLLBACK;");
    
                        }
    
                    }
                }

            }

        }

        exit($result);

    }

    else if($operation == 30){



        $nombreArticleUniteStock=1;

		$sql='select * from `'.$nomtableDesignation.'` where idDesignation="'.$idDesignation.'"';

		//echo $sql;

		$res=mysql_query($sql);

		if(mysql_num_rows($res)){

            if($design=mysql_fetch_array($res)) {

                if($prixSession!=null && $prixPublic!=null && $idBl!=null){

                    $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,idBl,designation,quantiteStockinitial,forme,prixSession,prixPublic,dateStockage, quantiteStockCourant,dateExpiration,iduser) VALUES('.$idDesignation.','.$idBl.',"'.mysql_real_escape_string($design["designation"]).'",'.$quantite.',"'.mysql_real_escape_string($design["forme"]).'",'.$prixSession.','.$prixPublic.',"'.$dateString.'",'.$quantite.',"'.$dateExpiration.'","'.$_SESSION['iduser'].'")';

                    $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

                    $sqlD="update `".$nomtableDesignation."` set prixSession=".$prixSession." where idDesignation=".$idDesignation;

                    $resD=@mysql_query($sqlD)or die ("modification impossible1 ".mysql_error());

                    if($res1){

                        $sql3='SELECT * from  `'.$nomtableStock.'` where quantiteStockCourant!=0 and idDesignation="'.$idDesignation.'" order by idStock DESC';

                        $res3=mysql_query($sql3);

                        $stock=mysql_fetch_array($res3);



                        $result=$stock['designation'].'<>'.$stock['forme'].'<>'.$stock['quantiteStockinitial'].'<>'.$stock['dateStockage'].'<>'.$stock['prixSession'].'<>'.$stock['prixPublic'];

                    }

                }

                else{

                    if($prixSession!=null){

                        $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,forme,prixSession,prixPublic,dateStockage, quantiteStockCourant,dateExpiration,iduser) VALUES('.$idDesignation.',"'.mysql_real_escape_string($design["designation"]).'",'.$quantite.',"'.mysql_real_escape_string($design["forme"]).'",'.$prixSession.','.$design["prixPublic"].',"'.$dateString.'",'.$quantite.',"'.$dateExpiration.'","'.$_SESSION['iduser'].'")';

                    }

                    else{

                        $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,forme,prixSession,prixPublic,dateStockage, quantiteStockCourant,dateExpiration,iduser) VALUES('.$idDesignation.',"'.mysql_real_escape_string($design["designation"]).'",'.$quantite.',"'.mysql_real_escape_string($design["forme"]).'",'.$design["prixSession"].','.$design["prixPublic"].',"'.$dateString.'",'.$quantite.',"'.$dateExpiration.'","'.$_SESSION['iduser'].'")';

                    }

                    $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

                    $sqlD="update `".$nomtableDesignation."` set prixSession=".$prixSession." where idDesignation=".$idDesignation;

                    $resD=@mysql_query($sqlD)or die ("modification impossible1 ".mysql_error());

                    if(res1){

                        $sql3='SELECT * from  `'.$nomtableStock.'` where quantiteStockCourant!=0 and idDesignation="'.$idDesignation.'" order by idStock DESC';

                        $res3=mysql_query($sql3);

                        $stock=mysql_fetch_array($res3);

                    }

                }

            }

        }

        exit($result);



    }

    else if($operation == 4){



        $nombreArticleUniteStock=1;

		$sql='select * from `'.$nomtableDesignation.'` where idDesignation="'.$idDesignation.'"';

		//echo $sql;

		$res=mysql_query($sql);

		if(mysql_num_rows($res)){

            if($design=mysql_fetch_array($res)) {

                $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,forme,prixSession,prixPublic,dateStockage, quantiteStockCourant,dateExpiration) VALUES('.$idDesignation.',"'.mysql_real_escape_string($design["designation"]).'",'.$quantite.',"'.mysql_real_escape_string($design["forme"]).'",'.$prixSession.','.$prixPublic.',"'.$dateString.'",'.$quantite.',"'.$dateExpiration.'")';

                $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

            }

        }



       $sql3='SELECT * from  `'.$nomtableStock.'` where quantiteStockCourant!=0 and idDesignation="'.$idDesignation.'" order by idStock DESC';

       $res3=mysql_query($sql3);

       $stock=mysql_fetch_array($res3);



       $result=$stock['designation'].'+'.$design['categorie'].'+'.$stock['totalArticleStock'].'+'.$stock['dateStockage'];



       exit($result);





    }

    else if($operation == 40){

        $result="0";

		$sql='select * from `'.$nomtableDesignation.'` where designation="'.$designation.'"';

		$res=mysql_query($sql);

		if(mysql_num_rows($res)){

            if($design=mysql_fetch_array($res)) {

                $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,forme,prixSession,prixPublic,dateStockage, quantiteStockCourant,dateExpiration) VALUES('.$design["idDesignation"].',"'.mysql_real_escape_string($design["designation"]).'",'.$quantite.',"'.mysql_real_escape_string($design["forme"]).'",'.$design["prixSession"].','.$design["prixPublic"].',"'.$dateString.'",'.$quantite.',"'.$dateExpiration.'")';

                $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

                if($res1){

                    $result="1";

                }

            }

        }

       exit($result);

    }

    else if($operation == 400){

        $result="0";

		$sql='select * from `'.$nomtableDesignation.'` where designation="'.$designation.'"';

		$res=mysql_query($sql);

		if(mysql_num_rows($res)){

            if($design=mysql_fetch_array($res)) {

                if($idBl!=null){

                    $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,idBl,designation,quantiteStockinitial,forme,prixSession,prixPublic,dateStockage, quantiteStockCourant,dateExpiration) VALUES('.$design["idDesignation"].','.$idBl.',"'.mysql_real_escape_string($design["designation"]).'",'.$quantite.',"'.mysql_real_escape_string($design["forme"]).'",'.$design["prixSession"].','.$design["prixPublic"].',"'.$dateString.'",'.$quantite.',"'.$dateExpiration.'")';

                    $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

                    if($res1){

                        $result="1";

                    }

                }

                else{

                    $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,forme,prixSession,prixPublic,dateStockage, quantiteStockCourant,dateExpiration) VALUES('.$design["idDesignation"].',"'.mysql_real_escape_string($design["designation"]).'",'.$quantite.',"'.mysql_real_escape_string($design["forme"]).'",'.$design["prixSession"].','.$design["prixPublic"].',"'.$dateString.'",'.$quantite.',"'.$dateExpiration.'")';

                    $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

                    if($res1){

                        $result="1";

                    }

                }

            }

        }

       exit($result);

    }

    else if($operation == 5){



        $sql="update `".$nomtableDesignation."` set codeBarreDesignation='".$code."' where idDesignation=".$idDesignation;

        $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());



       exit($code);





    }

    else if($operation == 6){



        if ($designation!='' && $forme!='' && $tableau!='' && $prixSession!='' && $prixPublic!=''){

            $sql11="SELECT * FROM `". $nomtableDesignation."` where designation='".$designation."'";

            $res11=mysql_query($sql11);

            if(!mysql_num_rows($res11)){

                $sql="insert into `".$nomtableDesignation."` (designation,classe,prixSession,prixPublic,forme,tableau,categorie,codeBarreDesignation)values ('".mysql_real_escape_string($designation)."',0,".$prixSession.",".$prixPublic.",'".mysql_real_escape_string($forme)."','".mysql_real_escape_string($tableau)."','".mysql_real_escape_string($categorie)."','".$codeBarre."')";

                $res=@mysql_query($sql) or die ("insertion impossible Produit en Article".mysql_error());

            }



        }

        else{



        }



       $sql3='SELECT * from  `'.$nomtableDesignation.'` where forme="'.$forme.'" and prixPublic="'.$prixPublic.'" and designation="'.$designation.'" ';

       $res3=mysql_query($sql3);

       $design=mysql_fetch_array($res3);



       $result=$design['designation'].'+'.$design['codeBarreDesignation'].'+'.$design['forme'].'+'.$design['tableau'].'+'.$design['prixSession'].'+'.$design['prixPublic'];



       exit($result);





    }

    else if($operation == 7){

       $sql3='SELECT * from  `'.$nomtableDesignation.'` where idDesignation="'.$idDesignation.'" ';

       $res3=mysql_query($sql3);

       $design=mysql_fetch_array($res3);



       $result=$idDesignation.'+'.$design['designation'].'+'.$design['categorie'].'+'.$design['forme'].'+'.$design['tableau'].'+'.$design['prixSession'].'+'.$design['prixPublic'].'+'.$design['codeBarreDesignation'];

       exit($result);



    }

    else if($operation == 8){

        if ($designation!='' && $forme!='' && $tableau!='' && $prixSession!='' && $prixPublic!=''){

            $sql="update `".$nomtableDesignation."` set designation='".mysql_real_escape_string($designation)."',categorie='".mysql_real_escape_string($categorie)."',classe=0,forme='".mysql_real_escape_string($forme)."',tableau='".mysql_real_escape_string($tableau)."',prixSession=".$prixSession.",prixPublic=".$prixPublic." where idDesignation=".$idDesignation;

            $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());



            $sql2="update `".$nomtableStock."` set designation='".mysql_real_escape_string($designation)."' where idDesignation=".$idDesignation;

            //echo $sql2;

            $res2=@mysql_query($sql2)or die ("modification reference dans stock ".mysql_error());



        }

        else{



        }



       $sql3='SELECT * from  `'.$nomtableDesignation.'` where idDesignation="'.$idDesignation.'" ';

       $res3=mysql_query($sql3);

       $design=mysql_fetch_array($res3);



       $result=$design['designation'].'+'.$design['categorie'].'+'.$design['forme'].'+'.$design['tableau'].'+'.$design['prixSession'].'+'.$design['prixPublic'];



       exit($result);





    }

    else if($operation == 9){

        $sql="DELETE FROM `".$nomtableStock."` WHERE idDesignation=".$idDesignation;

        $res=@mysql_query($sql) or die ("suppression impossible designation".mysql_error());



        $sql1="DELETE FROM `".$nomtableDesignation."` WHERE idDesignation=".$idDesignation;

        $res1=@mysql_query($sql1) or die ("suppression impossible designation".mysql_error());



        $result='Ok';



        exit($result);



    }

    else if($operation == 10){

        $sql3='SELECT * from  `'.$nomtableStock.'` where idStock="'.$idStock.'" ';

        $res3=mysql_query($sql3);

        $stock=mysql_fetch_array($res3);



        $sql4="SELECT * FROM `". $nomtableBl."` where idBl='".$stock['idBl']."' ";

        $res4=mysql_query($sql4);

        $bl=mysql_fetch_array($res4);



        $result=$idStock.'<>'.$stock['designation'].'<>'.$bl['numeroBl'].'<>'.$stock['forme'].'<>'.$stock['quantiteStockinitial'].'<>'.$stock['quantiteStockCourant'].'<>'.$stock['dateStockage'].'<>'.$stock['dateExpiration'].'<>'.$stock['prixSession'].'<>'.$stock['prixPublic'].'<>'.$stock['retirer'];

        exit($result);



    }

    else if($operation == 11){

        mysql_query("SET AUTOCOMMIT=0;");

        mysql_query("START TRANSACTION;");

        $result="0";

        if($numeroBl!=null){

            if ($quantite!=0 && $prixSession!='' && $prixPublic!=''){

                $sql4="SELECT * FROM `". $nomtableBl."` where numeroBl='".$numeroBl."' ";

                $res4=mysql_query($sql4);

                if($bl=mysql_fetch_array($res4)){

                    $sql2="UPDATE `".$nomtableStock."` set idBl='".$bl['idBl']."',quantiteStockinitial='".$quantite."',quantiteStockCourant='".$quantite."',prixPublic='".$prixPublic."',prixSession='".$prixSession."',dateExpiration='".$dateExpiration."' where idStock=".$idStock." ";

                    $res2=@mysql_query($sql2)or die ("modification reference dans stock ".mysql_error());

                    if($res2){

                        $sql3="SELECT * from  `".$nomtableStock."` where idStock=".$idStock." ";

                        $res3=mysql_query($sql3);

                        $stock=mysql_fetch_array($res3);

                        mysql_query("COMMIT;");

                        $result='1<>'.$bl['numeroBl'].'<>'.$stock['designation'].'<>'.$stock['forme'].'<>'.$stock['quantiteStockinitial'].'<>'.$stock['prixSession'].'<>'.$stock['prixPublic'].'<>'.$stock['dateStockage'];

                    }

                    else{

                        mysql_query("ROLLBACK;");

                    }

                }

            }

        }

        else{

            if ($quantite!=0 && $prixSession!='' && $prixPublic!=''){

                $sql2="UPDATE `".$nomtableStock."` set quantiteStockinitial='".$quantite."',quantiteStockCourant='".$quantite."',prixPublic='".$prixPublic."',prixSession='".$prixSession."',dateExpiration='".$dateExpiration."' where idStock=".$idStock." ";

                $res2=@mysql_query($sql2)or die ("modification reference dans stock ".mysql_error());

                if($res2){

                    $sql3="SELECT * from  `".$nomtableStock."` where idStock=".$idStock." ";

                    $res3=mysql_query($sql3);

                    $stock=mysql_fetch_array($res3);

                    if($stock['idBl']!=null && $stock['idBl']!=0){

                        $sql4="SELECT * FROM `". $nomtableBl."` where numeroBl='".$numeroBl."' ";

                        $res4=mysql_query($sql4);

                        if($bl=mysql_fetch_array($res4)){

                            $result='1<>'.$bl['numeroBl'].'<>'.$stock['designation'].'<>'.$stock['forme'].'<>'.$stock['quantiteStockinitial'].'<>'.$stock['prixSession'].'<>'.$stock['prixPublic'].'<>'.$stock['dateStockage'];

                        }

                    }

                    else{

                        $result='1<>NEANT<>'.$stock['designation'].'<>'.$stock['forme'].'<>'.$stock['quantiteStockinitial'].'<>'.$stock['prixSession'].'<>'.$stock['prixPublic'].'<>'.$stock['dateStockage'];

                    }

                    mysql_query("COMMIT;");

                }

                else{

                    mysql_query("ROLLBACK;");

                }

            }

        }

        exit($result);

    }

    else if($operation == 101){

        mysql_query("SET AUTOCOMMIT=0;");

        mysql_query("START TRANSACTION;");

        $result="0";

        if ($quantite!=0 && $prixSession!='' && $prixPublic!=''){

            $sql2="UPDATE `".$nomtableStock."` set quantiteStockinitial='".$quantite."',quantiteStockCourant='".$quantite."',prixPublic='".$prixPublic."',prixSession='".$prixSession."',dateExpiration='".$dateExpiration."' where idStock=".$idStock." ";

            $res2=@mysql_query($sql2)or die ("modification reference dans stock ".mysql_error());

            if($res2){

                $sql3="SELECT * from  `".$nomtableStock."` where idStock=".$idStock." ";

                $res3=mysql_query($sql3);

                $stock=mysql_fetch_array($res3);

                mysql_query("COMMIT;");

                $result='1<>'.$stock['designation'].'<>'.$stock['forme'].'<>'.$stock['quantiteStockinitial'].'<>'.$stock['prixSession'].'<>'.$stock['prixPublic'].'<>'.$stock['dateStockage'];

            }

            else{

                mysql_query("ROLLBACK;");

            }

        }

        exit($result);

    }

    else if($operation == 110){

        mysql_query("SET AUTOCOMMIT=0;");

        mysql_query("START TRANSACTION;");

        $sqlS="SELECT * from  `".$nomtableStock."` where idStock=".$idStock." ";

        $resS=mysql_query($sqlS);

        $stock0=mysql_fetch_array($resS);

        $result="0";

        if($numeroBl!=null){

            if ($prixSession!='' && $prixPublic!=''){

                $sql4="SELECT * FROM `". $nomtableBl."` where numeroBl='".$numeroBl."' ";

                $res4=mysql_query($sql4);

                if($bl=mysql_fetch_array($res4)){

                    if($stock0['quantiteStockinitial'] >= $quantite){

                        $sql2="UPDATE `".$nomtableStock."` set idBl='".$bl['idBl']."',quantiteStockCourant='".$quantite."',prixPublic='".$prixPublic."',prixSession='".$prixSession."',dateExpiration='".$dateExpiration."' where idStock=".$idStock." ";

                        $res2=@mysql_query($sql2)or die ("modification reference dans stock ".mysql_error());

                        if($res2){

                            $sql3="SELECT * from  `".$nomtableStock."` where idStock=".$idStock." ";

                            $res3=mysql_query($sql3);

                            $stock=mysql_fetch_array($res3);

                            mysql_query("COMMIT;");

                            $result='1<>'.$bl['numeroBl'].'<>'.$stock['designation'].'<>'.$stock['forme'].'<>'.$stock['quantiteStockCourant'].'<>'.$stock['prixSession'].'<>'.$stock['prixPublic'].'<>'.$stock['dateStockage'];

                        }

                        else{

                            mysql_query("ROLLBACK;");

                        }

                    }

                }

            }

        }

        else{

            if ($prixSession!='' && $prixPublic!=''){

                if($stock0['quantiteStockinitial'] >= $quantite){

                    $sql2="UPDATE `".$nomtableStock."` set quantiteStockCourant='".$quantite."',prixPublic='".$prixPublic."',prixSession='".$prixSession."',dateExpiration='".$dateExpiration."' where idStock=".$idStock." ";

                    $res2=@mysql_query($sql2)or die ("modification reference dans stock ".mysql_error());

                    if($res2){

                        $sql3="SELECT * from  `".$nomtableStock."` where idStock=".$idStock." ";

                        $res3=mysql_query($sql3);

                        $stock=mysql_fetch_array($res3);

                        if($stock['idBl']!=null && $stock['idBl']!=0){

                            $sql4="SELECT * FROM `". $nomtableBl."` where numeroBl='".$numeroBl."' ";

                            $res4=mysql_query($sql4);

                            if($bl=mysql_fetch_array($res4)){

                                $result='1<>'.$bl['numeroBl'].'<>'.$stock['designation'].'<>'.$stock['forme'].'<>'.$stock['quantiteStockCourant'].'<>'.$stock['prixSession'].'<>'.$stock['prixPublic'].'<>'.$stock['dateStockage'];

                            }

                        }

                        else{

                            $result='1<>NEANT<>'.$stock['designation'].'<>'.$stock['forme'].'<>'.$stock['quantiteStockCourant'].'<>'.$stock['prixSession'].'<>'.$stock['prixPublic'].'<>'.$stock['dateStockage'];

                        }

                        mysql_query("COMMIT;");

                    }

                    else{

                        mysql_query("ROLLBACK;");

                    }

                }

            }

        }

        exit($result);

    }

    else if($operation == 1101){

        mysql_query("SET AUTOCOMMIT=0;");

        mysql_query("START TRANSACTION;");

        $result="0";

        $sqlS="SELECT * from  `".$nomtableStock."` where idStock=".$idStock." ";

        $resS=mysql_query($sqlS);

        $stock0=mysql_fetch_array($resS);

        if ($quantite!=0 && $prixSession!='' && $prixPublic!=''){

            if($stock0['quantiteStockinitial'] > $quantite){

                $sql2="UPDATE `".$nomtableStock."` set quantiteStockCourant='".$quantite."',prixPublic='".$prixPublic."',prixSession='".$prixSession."',dateExpiration='".$dateExpiration."' where idStock=".$idStock." ";

                $res2=@mysql_query($sql2)or die ("modification reference dans stock ".mysql_error());

                if($res2){

                    $sql3="SELECT * from  `".$nomtableStock."` where idStock=".$idStock." ";

                    $res3=mysql_query($sql3);

                    $stock=mysql_fetch_array($res3);

                    mysql_query("COMMIT;");

                    $result='1<>'.$stock['designation'].'<>'.$stock['forme'].'<>'.$stock['quantiteStockCourant'].'<>'.$stock['prixSession'].'<>'.$stock['prixPublic'].'<>'.$stock['dateStockage'];

                }

                else{

                    mysql_query("ROLLBACK;");

                }

            }

            else{

                $sql2="UPDATE `".$nomtableStock."` set quantiteStockinitial='".$quantite."',quantiteStockCourant='".$quantite."',prixPublic='".$prixPublic."',prixSession='".$prixSession."',dateExpiration='".$dateExpiration."' where idStock=".$idStock." ";

                $res2=@mysql_query($sql2)or die ("modification reference dans stock ".mysql_error());

                if($res2){

                    $sql3="SELECT * from  `".$nomtableStock."` where idStock=".$idStock." ";

                    $res3=mysql_query($sql3);

                    $stock=mysql_fetch_array($res3);

                    mysql_query("COMMIT;");

                    $result='2<>'.$stock['designation'].'<>'.$stock['forme'].'<>'.$stock['quantiteStockCourant'].'<>'.$stock['prixSession'].'<>'.$stock['prixPublic'].'<>'.$stock['dateStockage'];

                }

                else{

                    mysql_query("ROLLBACK;");

                }

            }

        }

        exit($result);

    }

    else if($operation == 12){

        mysql_query("SET AUTOCOMMIT=0;");

        mysql_query("START TRANSACTION;");

        $sql3="SELECT * from  `".$nomtableStock."` where idStock=".$idStock." ";

        $res3=mysql_query($sql3);

        $result="0";

        if($stock=mysql_fetch_array($res3)){

            if($stock['idBl']!=null && $stock['idBl']!=0){

                $sql4="SELECT * FROM `". $nomtableBl."` where idBl=".$stock['idBl']." ";

                $res4=mysql_query($sql4);

                $bl=mysql_fetch_array($res4);

                $sql="DELETE FROM `".$nomtableStock."` WHERE idStock=".$idStock;

                $res=@mysql_query($sql) or die ("suppression impossible designation".mysql_error());

                if($res){

                    mysql_query("COMMIT;");

                    $result='1<>'.$bl['numeroBl'].'<>'.$stock['designation'].'<>'.$stock['forme'].'<>'.$stock['quantiteStockCourant'].'<>'.$stock['prixSession'].'<>'.$stock['prixPublic'].'<>'.$stock['dateStockage'];

                }

                else{

                    mysql_query("ROLLBACK;");

                }

            }

            else{

                $sql="DELETE FROM `".$nomtableStock."` WHERE idStock=".$idStock;

                $res=@mysql_query($sql) or die ("suppression impossible designation".mysql_error());

                if($res){

                    mysql_query("COMMIT;");

                    $result='1<>NEANT<>'.$stock['designation'].'<>'.$stock['forme'].'<>'.$stock['quantiteStockCourant'].'<>'.$stock['prixSession'].'<>'.$stock['prixPublic'].'<>'.$stock['dateStockage'];

                }

                else{

                    mysql_query("ROLLBACK;");

                }

            }

        }

        exit($result);

    }

    else if($operation == 120){

        mysql_query("SET AUTOCOMMIT=0;");

        mysql_query("START TRANSACTION;");

        $sql3="SELECT * from  `".$nomtableStock."` where idStock=".$idStock." ";

        $res3=mysql_query($sql3);

        $result="0";

        if($stock=mysql_fetch_array($res3)){

            if($stock['idBl']!=null){

                $sql4="SELECT * FROM `". $nomtableBl."` where idBl='".$stock['idBl']."' ";

                $res4=mysql_query($sql4);

                $sql="DELETE FROM `".$nomtableStock."` WHERE idStock=".$idStock;

                $res=@mysql_query($sql) or die ("suppression impossible designation".mysql_error());

                if($res){

                    mysql_query("COMMIT;");

                    $result='1<>'.$stock['designation'].'<>'.$stock['forme'].'<>'.$stock['quantiteStockCourant'].'<>'.$stock['prixSession'].'<>'.$stock['prixPublic'].'<>'.$stock['dateStockage'];

                }

                else{

                    mysql_query("ROLLBACK;");

                }

            }

            else{

                $sql="DELETE FROM `".$nomtableStock."` WHERE idStock=".$idStock;

                $res=@mysql_query($sql) or die ("suppression impossible designation".mysql_error());

                if($res){

                    mysql_query("COMMIT;");

                    $result='1<>'.$stock['designation'].'<>'.$stock['forme'].'<>'.$stock['quantiteStockCourant'].'<>'.$stock['prixSession'].'<>'.$stock['prixPublic'].'<>'.$stock['dateStockage'];

                }

                else{

                    mysql_query("ROLLBACK;");

                }

            }

        }

        exit($result);

    }

    else if($operation == 13){

        $result="0";

        $sql="SELECT * from  `".$nomtableStock."` where idStock=".$idStock." ";

        $res=mysql_query($sql);

        if($res){

            $stock=mysql_fetch_array($res);

            $sql1="UPDATE `".$nomtableStock."` set quantiteStockCourant=0 where idStock=".$idStock." ";

            $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

            if($res1){

                $sql4='INSERT INTO `'.$nomtableInventaire.'` (idStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)

                VALUES('.$stock['idStock'].','.$stock['idDesignation'].',0,1,'.$stock['quantiteStockCourant'].',"'.$dateString.'",1)';

                $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;



                $result='1<>'.$stock['dateExpiration'].'<>'.$stock['designation'].'<>'.$stock['quantiteStockCourant'];

            }

        }

        exit($result);

    }

    else if($operation == 14){

        $result='';

        if($quantiteD!=0 && $quantiteD!=''){

            $quantite=$quantiteR - $quantiteD;

            if ($quantite >= 0){



                $sqlP0="SELECT * FROM `".$nomtablePagnet."` where idClient=0  && verrouiller=1 && datepagej ='".$dateString2."' && totalp=0 " ;

                $resP0=mysql_query($sqlP0) or die ("select stock impossible =>".mysql_error());

                $pagnet0 = mysql_fetch_array($resP0);



                $sql1="SELECT * FROM `". $nomtableDesignation."` where designation='".$designation."'";

                $res1=mysql_query($sql1);

                $design=mysql_fetch_array($res1);



                if($pagnet0!=null){

                    if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

                        $sqlL="insert into `".$nomtableLigne."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',".$idStock.",'".$design['forme']."',0,".$quantiteD.",0,".$pagnet0['idPagnet'].",0)";

                        $resL=mysql_query($sqlL) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                    }else{

                        $sqlL="insert into `".$nomtableLigne."`(designation,idDesignation,idStock, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',".$idStock.",'".$design['forme']."',0,".$quantiteD.",0,".$pagnet0['idPagnet'].",0)";

                        $resL=mysql_query($sqlL) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                    }



                    $sqlT="UPDATE `".$nomtablePagnet."` set verrouiller=1 where idPagnet=".$pagnet0['idPagnet'];

                    $resT=@mysql_query($sqlT) or die ("mise à jour verouillage  impossible".mysql_error());

                }

                else{

                    $sql6="insert into `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,remise,apayerPagnet,restourne,versement,verrouiller,idClient) values('".$dateString2."','".$heureString."',".$_SESSION['iduser'].",0,0,0,0,0,0,0,0)";

                    $res6=mysql_query($sql6) or die ("insertion pagnier impossible =>".mysql_error() );



                    $sqlP="SELECT * FROM `".$nomtablePagnet."` where datepagej='".$dateString2."' AND heurePagnet='".$heureString."' AND iduser='".$_SESSION['iduser']."' " ;

                    $resP=mysql_query($sqlP) or die ("select stock impossible =>".mysql_error());

                    $pagnet = mysql_fetch_array($resP);



                    if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

                        $sqlL="insert into `".$nomtableLigne."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',".$idStock.",'".$design['forme']."',0,".$quantiteD.",0,".$pagnet['idPagnet'].",0)";

                        $resL=mysql_query($sqlL) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                    }else{

                        $sqlL="insert into `".$nomtableLigne."`(designation,idDesignation,idStock, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',".$idStock.",'".$design['forme']."',0,".$quantiteD.",0,".$pagnet['idPagnet'].",0)";

                        $resL=mysql_query($sqlL) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                    }



                    $sqlT="UPDATE `".$nomtablePagnet."` set verrouiller=1 where idPagnet=".$pagnet['idPagnet'];

                    $resT=@mysql_query($sqlT) or die ("mise à jour verouillage  impossible".mysql_error());

                }



                $sqlS="SELECT *

                FROM `".$nomtableStock."`

                where idStock=".$idStock." ";

                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                $stock = mysql_fetch_array($resS);

                $quantiteDim=$quantiteD + $stock['retirer'];



                $result=$idStock.' '.$stock['retirer'].' '.$quantiteD.' '.$quantite;



                $sql2="update `".$nomtableStock."` set quantiteStockCourant=".$quantite.",retirer=".$quantiteDim." where idStock=".$idStock;

                $res2=@mysql_query($sql2)or die ("modification reference dans stock ".mysql_error());



            }

        }



        exit($result);

    }

    else if($operation == 15){



        $sql3='SELECT * from  `'.$nomtableDesignation.'` where idDesignation="'.$idDesignation.'" ';

        $res3=mysql_query($sql3);

        $design=mysql_fetch_array($res3);

        $result=0;



        if($design['tva']==0){

            $sql="update `".$nomtableDesignation."` set tva=1 where idDesignation=".$idDesignation;

            $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());

        }

        else{

            $sql="update `".$nomtableDesignation."` set tva=0 where idDesignation=".$idDesignation;

            $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());

        }



        if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

            $result='1<>'.$design['designation'].'<>'.$design['categorie'].'<>'.$design['forme'].'<>'.$design['tableau'].'<>'.$design['prixSession'].'<>'.$design['prixPublic'];

        }

        else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

            $result='2<>'.$design['designation'].'<>'.$design['categorie'].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$design['prixuniteStock'].'<>'.$design['prixachat'];

        }

        else{

            $result='3<>'.$design['designation'].'<>'.$design['codeBarreDesignation'].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$design['prixuniteStock'].'<>'.$design['prix'].'<>'.$design['prixachat'];

        }

         exit($result);





    }

    else if($operation == 16){

        $sql1='INSERT INTO `'.$nomtableFournisseur.'` (nomFournisseur,adresseFournisseur,telephoneFournisseur,banqueFournisseur,numBanqueFournisseur) VALUES("'.mysql_real_escape_string($nomFournisseur).'","'.$adresseFournisseur.'","'.$telephoneFournisseur.'","'.$banqueFournisseur.'","'.$numBanqueFournisseur.'")';

        $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

        if($res1){

            $result='1<>'.$nomFournisseur.'<>'.$adresseFournisseur.'<>'.$telephoneFournisseur.'<>'.$banqueFournisseur.'<>'.$numBanqueFournisseur;

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 17){

        $sql="SELECT * FROM `". $nomtableFournisseur."` where idFournisseur='".$idFournisseur."'";

        $res=mysql_query($sql);

        $result="0";

        if($res){

            $sql1='INSERT INTO `'.$nomtableBl.'` (idFournisseur,numeroBl,dateBl,montantBl) VALUES ("'.$idFournisseur.'","'.$numeroBl.'","'.$dateBl.'","'.$montantBl.'")';

            $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

            if($res1){

                $fourn=mysql_fetch_array($res);

                $sql2="SELECT * FROM `".$nomtableBl."` where numeroBl='".$numeroBl."' and dateBl='".$dateBl."' and montantBl='".$montantBl."' and idFournisseur='".$idFournisseur."'";

                $res2=mysql_query($sql2);

                $bl=mysql_fetch_array($res2);

                $result='1<>'.$numeroBl.'<>'.$fourn['nomFournisseur'].'<>'.$dateBl.'<>'.$montantBl.'<>'.$bl['idBl'];

            }

            else{

                $result="0";

            }

        }

        exit($result);

    }

    else if($operation == 18){

        $sql="SELECT * FROM `". $nomtableFournisseur."` where idFournisseur='".$idFournisseur."'";

        $res=mysql_query($sql);

        if($res){

            $fourn=mysql_fetch_array($res);

            $result='1<>'.$fourn['idFournisseur'].'<>'.$fourn['nomFournisseur'].'<>'.$fourn['adresseFournisseur'].'<>'.$fourn['telephoneFournisseur'].'<>'.$fourn['banqueFournisseur'].'<>'.$fourn['numBanqueFournisseur'];

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 19){

        $sql="SELECT * FROM `". $nomtableFournisseur."` where idFournisseur='".$idFournisseur."'";

        $res=mysql_query($sql);

        if($res){

            $sql1="UPDATE `".$nomtableFournisseur."` set nomFournisseur='".$nomFournisseur."', adresseFournisseur='".$adresseFournisseur."', telephoneFournisseur='".$telephoneFournisseur."', banqueFournisseur='".$banqueFournisseur."', numBanqueFournisseur='".$numBanqueFournisseur."' where idFournisseur='".$idFournisseur."'";

            $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

            if($res1){

                $sql12="SELECT SUM(montantBl) FROM `".$nomtableBl."` where idFournisseur='".$idFournisseur."' ";

                $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());

                $TotalB = mysql_fetch_array($res12) ;



                $sql13="SELECT SUM(montant) FROM `".$nomtableVersement."` where idFournisseur='".$idFournisseur."' ";

                $res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());

                $TotalV = mysql_fetch_array($res13) ;



                $T_solde=$TotalB[0] - $TotalV[0];



                $result='1<>'.$idFournisseur.'<>'.$nomFournisseur.'<>'.$adresseFournisseur.'<>'.$telephoneFournisseur.'<>'.$banqueFournisseur.'<>'.$numBanqueFournisseur.'<>'.$T_solde;

            }

            else{

                $result="0";

            }

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 20){

        $sql="SELECT * FROM `". $nomtableFournisseur."` where idFournisseur='".$idFournisseur."'";

        $res=mysql_query($sql);

        if($res){

            $sql1="DELETE FROM `".$nomtableFournisseur."` WHERE idFournisseur='".$idFournisseur."'";

            $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

            if($res1){

                $result='1';

            }

            else{

                $result="0";

            }

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 21){

        $sql="SELECT * FROM `".$nomtableBl."` where idBl='".$idBl."'";

        $res=mysql_query($sql);

        if($res){

            $bl=mysql_fetch_array($res);

            $sql1="SELECT * FROM `".$nomtableFournisseur."` where idFournisseur='".$bl['idFournisseur']."'";

            $res1=mysql_query($sql1);

            if($res1){

                $fourn=mysql_fetch_array($res1);

                $result='1<>'.$bl['idBl'].'<>'.$fourn['nomFournisseur'].'<>'.$bl['numeroBl'].'<>'.$bl['dateBl'].'<>'.$bl['montantBl'];

            }

            else{

                $result='0';

            }

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 22){

        $sql="SELECT * FROM `".$nomtableBl."` where idBl='".$idBl."'";

        $res=mysql_query($sql);

        if($res){

            $sql1="SELECT * FROM `".$nomtableFournisseur."` where nomFournisseur='".$fournisseur."' ";

            $res1=mysql_query($sql1);

            if($res1){

                $fourn=mysql_fetch_array($res1);

                $sql2="UPDATE `".$nomtableBl."` set idFournisseur='".$fourn['idFournisseur']."',numeroBl='".$numeroBl."',dateBl='".$dateBl."',montantBl='".$montantBl."' where idBl='".$idBl."'";

                $res2=@mysql_query($sql2)or die ("modification reference dans stock ".mysql_error());

                if($res2){

                    $result='1<>'.$idBl.'<>'.$numeroBl.'<>'.$fourn['nomFournisseur'].'<>'.$dateBl.'<>'.$montantBl;

                }

                else{

                    $result="0";

                }

            }

            else{

                $result="0";

            }

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 23){

        $sql="SELECT * FROM `".$nomtableBl."` where idBl='".$idBl."'";

        $res=mysql_query($sql);

        if($res){

            $sql1="DELETE FROM `".$nomtableBl."` WHERE idBl='".$idBl."'";

            $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

            if($res1){

                $result='1';

            }

            else{

                $result="0";

            }

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 24){

        $sql1='INSERT INTO `'.$nomtableEntrepot.'` (nomEntrepot,adresseEntrepot) VALUES("'.mysql_real_escape_string($nomEntrepot).'","'.$adresseEntrepot.'")';

        $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

        if($res1){

            $result='1<>'.$nomEntrepot.'<>'.$adresseEntrepot;

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 25){

        $sql="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$idEntrepot."'";

        $res=mysql_query($sql);

        if($res){

            $entrepot=mysql_fetch_array($res);

            $result='1<>'.$entrepot['idEntrepot'].'<>'.$entrepot['nomEntrepot'].'<>'.$entrepot['adresseEntrepot'];

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 26){

        $sql="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$idEntrepot."'";

        $res=mysql_query($sql);

        if($res){

            $sql1="UPDATE `".$nomtableEntrepot."` set nomEntrepot='".$nomEntrepot."',adresseEntrepot='".$adresseEntrepot."' where idEntrepot='".$idEntrepot."'";

            $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

            if($res1){

                $result='1<>'.$idEntrepot.'<>'.$nomEntrepot.'<>'.$adresseEntrepot;

            }

            else{

                $result="0";

            }

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 27){

        $sql="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$idEntrepot."'";

        $res=mysql_query($sql);

        if($res){

            $sql1="DELETE FROM `".$nomtableEntrepot."` WHERE idEntrepot='".$idEntrepot."'";

            $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

            if($res1){

                $result='1';

            }

            else{

                $result="0";

            }

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 28){

        $dateEnregistrement=@htmlspecialchars($_POST["dateJour"]);
        if($dateEnregistrement!='' && $dateEnregistrement!=null){
            $jourEnregistrement=$dateEnregistrement;
        }
        else{
            $jourEnregistrement=$dateString;
        }

        $sql3='SELECT * from  `'.$nomtableStock.'` where idStock="'.$idStock.'" ';

        $res3=mysql_query($sql3);

        $stock=mysql_fetch_array($res3);

        if($stock!=null){

            $sql_t="SELECT SUM(quantiteStockinitial) FROM `".$nomtableEntrepotStock."`

            where idStock=".$stock['idStock']." AND idTransfert=null  ";

            $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

            $t_stock = mysql_fetch_array($res_t) ;

            $reste=$stock['quantiteStockinitial'] - $t_stock[0] - $quantite;

            if($reste>=0){

                $totalArticleStock=$quantite*$stock['nbreArticleUniteStock'];

                $sql1="INSERT INTO `".$nomtableEntrepotStock."` (idStock,idEntrepot,idDesignation,designation,quantiteStockinitial,uniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant)

                VALUES(".$idStock.",".$idEntrepot.",".$stock['idDesignation'].",'".mysql_real_escape_string($stock["designation"])."','".$quantite."','".$stock['uniteStock']."',".$stock['nbreArticleUniteStock'].",".$totalArticleStock.",'".$jourEnregistrement."',".$totalArticleStock.") ";

                $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

                if($res1){

                    $sql2="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$idEntrepot."'";

                    $res2=mysql_query($sql2);

                    $entrepot=mysql_fetch_array($res2);

                    $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";

                    $res1=mysql_query($sql1);

                    $design=mysql_fetch_array($res1);


                    $result='1<>'.$entrepot['nomEntrepot'].'<>'.$stock['designation'].'<>'.$design['categorie'].'<>'.$quantite.'<>'.$stock['uniteStock'].'('.$design['nbreArticleUniteStock'].' x '.$design['uniteDetails'].') <>'.$jourEnregistrement.'<>'.$stock['dateStockage'].'<>'.$stock['dateExpiration'];

                }

                else{

                    $result="0";

                }

            }

            else{

                $result="2";

            }

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 29){

        $sql="SELECT * FROM `". $nomtableEntrepotStock."` where idEntrepotStock='".$idEntrepotStock."'";

        $res=mysql_query($sql);

        if($res){

            $stock=mysql_fetch_array($res);

            $sql2="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$stock['idEntrepot']."'";

                $res2=mysql_query($sql2);

                $entrepot=mysql_fetch_array($res2);

                if($stock['quantiteStockCourant']!=0 && $stock['quantiteStockCourant']!=null){

                    $result='1<>'.$idEntrepotStock.'<>'.$stock['idEntrepot'].'<>'.($stock['quantiteStockCourant']/ $stock['nbreArticleUniteStock']).'<>'.$entrepot['nomEntrepot'];

                }

                else{

                    $result='1<>'.$idEntrepotStock.'<>'.$stock['idEntrepot'].'<>0<>'.$entrepot['nomEntrepot'];

                }

            }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 31){

        $result="0";

        $sql="SELECT * FROM `". $nomtableEntrepotStock."` where idEntrepotStock='".$idEntrepotStock."'";

        $res=mysql_query($sql);

        if($res){

            $stock=mysql_fetch_array($res);

            if($stock['quantiteStockinitial']>=$quantite){

                $totalArticleStock=$quantite * $stock['nbreArticleUniteStock'];

                $sql1="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant='".$totalArticleStock."' where idEntrepotStock='".$idEntrepotStock."'";

                $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

                if($res1){

                    if($stock['quantiteStockCourant']!=$totalArticleStock){

                        $sql4='INSERT INTO `'.$nomtableInventaire.'` (idEntrepotStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type,iduser)

                        VALUES('.$idEntrepotStock.','.$stock['idDesignation'].','.$totalArticleStock.','.$stock['nbreArticleUniteStock'].','.$stock['quantiteStockCourant'].',"'.$dateString.'",2,'.$_SESSION['iduser'].')';

                        $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;

                    }

                    $sql3="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$stock['idTransfert']."'";

                    $res3=mysql_query($sql3);

                    $trans=mysql_fetch_array($res3);

                    if($trans!=null){

                        $transfert=' <= '.$trans['nomEntrepot'];

                    }

                    else{

                        $transfert='NEANT';

                    }

                    $sql2="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$stock['idEntrepot']."'";

                    $res2=mysql_query($sql2);

                    $entrepot=mysql_fetch_array($res2);

                    $sql1="SELECT * FROM `". $nomtableStock."` where idStock='".$stock['idStock']."'";

                    $res1=mysql_query($sql1);

                    $expire=mysql_fetch_array($res1);

                    $result='1<>'.$entrepot['nomEntrepot'].'<>'.$transfert.'<>'.$stock['quantiteStockinitial'].'<>'.$stock['uniteStock'].'<>'.$expire['prixuniteStock'].'<>'.$expire['dateExpiration'].'<>'.$stock['dateStockage'];

                }

            }

            else{

                $result="0";

            }

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 32){

        $sql="SELECT * FROM `". $nomtableEntrepotStock."` where idEntrepotStock='".$idEntrepotStock."'";

        $res=mysql_query($sql);

        if($res){

            $stock=mysql_fetch_array($res);

            $sql1="DELETE FROM `".$nomtableEntrepotStock."` WHERE idEntrepotStock='".$idEntrepotStock."'";

            $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

            if($res1){

                $sql3="SELECT * FROM `". $nomtableEntrepotStock."` where idEntrepotStock='".$stock['idTransfert']."'";

                $res3=mysql_query($sql3);

                $trans=mysql_fetch_array($res3);

                if($trans!=null){

                    $reste=$trans['quantiteStockCourant'] + $stock['quantiteStockCourant'];

                    $sqlT="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant='".$reste."' where idEntrepotStock='".$stock['idTransfert']."'";

                    $resT=@mysql_query($sqlT)or die ("modification reference dans stock ".mysql_error());

                    $transfert='NEANT';

                }

                else{

                    $transfert='NEANT';

                }

                $sql2="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$stock['idEntrepot']."'";

                $res2=mysql_query($sql2);

                $entrepot=mysql_fetch_array($res2);

                $sql10="SELECT * FROM `". $nomtableStock."` where idStock='".$stock['idStock']."'";

                $res10=mysql_query($sql10);

                $expire=mysql_fetch_array($res10);

                if($stock['quantiteStockCourant']!=0){

                    $result='1<>'.$entrepot['nomEntrepot'].'<>'.$transfert.'<>'.$stock['quantiteStockinitial'].'<>'.$stock['uniteStock'].'<>'.$expire['prixuniteStock'].'<>'.$expire['dateExpiration'].'<>'.$stock['dateStockage'].'<>'.($stock['quantiteStockCourant']/$stock['nbreArticleUniteStock']);

                }

                else{

                    $result='1<>'.$entrepot['nomEntrepot'].'<>'.$transfert.'<>'.$stock['quantiteStockinitial'].'<>'.$stock['uniteStock'].'<>'.$expire['prixuniteStock'].'<>'.$expire['dateExpiration'].'<>'.$stock['dateStockage'].'<>'.$stock['quantiteStockCourant'];

                }



            }

            else{

                $result="0";

            }

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 33){

        $nombreArticleUniteStock=0;

        $categorie='Sans';

        $result="0";

		$sql='select * from `'.$nomtableDesignation.'` where idDesignation="'.$idDesignation.'"';

		//echo $sql;

		$res=mysql_query($sql);

            if(mysql_num_rows($res)){

                if($design=mysql_fetch_array($res)) {

                    $sqlS="SELECT SUM(quantiteStockCourant)
                    FROM `".$nomtableEntrepotStock."`
                    where idDesignation ='".$idDesignation."'  ";
                    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                    $S_stock = mysql_fetch_array($resS);
                  
                    $sqlI="SELECT SUM(quantiteStockinitial)
                    FROM `".$nomtableStock."`
                    where idDesignation ='".$idDesignation."'  ";
                    $resI=mysql_query($sqlI) or die ("select stock impossible =>".mysql_error());
                    $I_stock = mysql_fetch_array($resI);
                  
                    $sqlE="SELECT SUM(quantiteStockinitial)
                    FROM `".$nomtableEntrepotStock."`
                    where idDesignation ='".$idDesignation."' AND (idTransfert=0 OR idTransfert IS NULL)   ";
                    $resE=mysql_query($sqlE) or die ("select stock impossible =>".mysql_error());
                    $E_stock = mysql_fetch_array($resE);

                    if ($uniteStock  == 'piece') {
                       
                        $nombreArticleUniteStock  = 1;

                    } else {

                        $nombreArticleUniteStock  = $design["nbreArticleUniteStock"];
                    }
                    

                    $categorie=$design["categorie"];

                    $totalArticleStock=$quantite*$nombreArticleUniteStock;

                    $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixuniteStock,prixunitaire,prixachat,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration,iduser) VALUES('.$idDesignation.',"'.mysql_real_escape_string($design['designation']).'",'.$quantite.',"'.mysql_real_escape_string($design['uniteStock']).'",'.$prixUniteStock.','.$prixUnitaire.','.$prixAchat.','.$nombreArticleUniteStock.','.$totalArticleStock.',"'.$dateString.'",'.$totalArticleStock.',"'.$dateExpiration.'","'.$_SESSION['iduser'].'")';

                    $res1=@mysql_query($sql1) or die ("insertion stock 11 impossible".mysql_error()) ;

                    if ($res1) {

                        $qte_stock = $S_stock[0] + ($I_stock[0] - $E_stock[0]);
                        $stock_Anc = $qte_stock * $design['prixachat'];
                        $stock_Nv = $totalArticleStock * $prixAchat;
                        $cump = round(( $stock_Anc + $stock_Nv ) / ($qte_stock + $totalArticleStock)) ;
                    
                        $sql0="UPDATE `".$nomtableDesignation."` set prixuniteStock='".$prixUniteStock."', prix='".$prixUnitaire."', prixachat='".$cump."' WHERE idDesignation='".$idDesignation."' ";
                        $res0=mysql_query($sql0) or die ("update quantiteStockCourant impossible =>".mysql_error());
                       

                        $sql3='SELECT * from  `'.$nomtableStock.'` where quantiteStockCourant!=0 and idDesignation="'.$idDesignation.'" order by idStock DESC';

                        $res3=mysql_query($sql3);

                        $stock=mysql_fetch_array($res3);

                        if ($idDepot =="0" || $idDepot ==null || $idDepot =='') {
                            
                            $result='1<>'.$stock['designation'].'<>'.$design['categorie'].'<>'.$design['uniteStock'].'<>'.$stock['prixuniteStock'].'<>'.$design['prixachat'];

                        }
                        else {
                            # code...
                            
                            $sql3='SELECT * from  `'.$nomtableStock.'` where idStock="'.$stock['idStock'].'" ';

                            $res3=mysql_query($sql3);

                            $stock=mysql_fetch_array($res3);

                            if($stock!=null){

                                $sql_t="SELECT SUM(quantiteStockinitial) FROM `".$nomtableEntrepotStock."`

                                where idStock=".$stock['idStock']." AND idTransfert=null  ";

                                $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

                                $t_stock = mysql_fetch_array($res_t) ;

                                $reste=$stock['quantiteStockinitial'] - $t_stock[0] - $quantite;

                                if($reste>=0){

                                    $totalArticleStock=$quantite*$stock['nbreArticleUniteStock'];

                                    $sql1='INSERT INTO `'.$nomtableEntrepotStock.'` (idStock,idEntrepot,idDesignation,designation,quantiteStockinitial,uniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant)

                                    VALUES('.$stock['idStock'].','.$idDepot.','.$stock['idDesignation'].',"'.mysql_real_escape_string($stock["designation"]).'",'.$quantite.',"'.mysql_real_escape_string($stock['uniteStock']).'",'.$stock['nbreArticleUniteStock'].','.$totalArticleStock.',"'.$dateString.'",'.$totalArticleStock.')';
                                    // var_dump($sql1);
                                    $res1=@mysql_query($sql1) or die ("insertion stock 12 impossible".mysql_error()) ;

                                    if($res1){

                                        $sql2="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$idEntrepot."'";

                                        $res2=mysql_query($sql2);

                                        $entrepot=mysql_fetch_array($res2);

                                        $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";

                                        $res1=mysql_query($sql1);

                                        $design=mysql_fetch_array($res1);


                                        $result='1<>'.$stock['designation'].'<>'.$design['categorie'].'<>'.$design['uniteStock'].'<>'.$stock['prixuniteStock'].'<>'.$design['prixachat'];

                                        // $result='1<>'.$entrepot['nomEntrepot'].'<>'.$stock['designation'].'<>'.$design['categorie'].'<>'.$quantite.'<>'.$stock['uniteStock'] .'('.$design['nbreArticleUniteStock'].' x '.$design['uniteDetails'].') <>'.$dateString;

                                    }

                                    else{

                                        $result="0";

                                    }

                                }

                                else{

                                    $result="2";

                                }

                            }

                            else {

                                $result="0";

                            }

                        }

                    }

                    else{

                        $result="0";

                    }

                }

            }


       exit($result);

    }

    else if($operation == 34){

        $sql="SELECT * FROM `". $nomtableStock."` where idStock='".$idStock."'";

        $res=mysql_query($sql);

        if($res){

            $stock=mysql_fetch_array($res);

            $result='1<>'.$stock['idStock'].'<>'.$stock['designation'].'<>'.$stock['quantiteStockinitial'].'<>'.$stock['dateStockage'].'<>'.$stock['dateExpiration'].'<>'.$stock['prixuniteStock'];

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 35){

        $result="0";

        $sql="SELECT * FROM `". $nomtableStock."` where idStock='".$idStock."'";

        $res=mysql_query($sql);

        if($res){

            $stock=mysql_fetch_array($res);

            $totalArticleStock=$quantite * $stock['nbreArticleUniteStock'];

            $sql1="UPDATE `".$nomtableStock."` set prixuniteStock='".$prixus."',quantiteStockinitial='".$quantite."',quantiteStockCourant='".$totalArticleStock."',dateExpiration='".$dateExpiration."' where idStock='".$idStock."'";

            $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

            if($res1){

                $sql1="SELECT * FROM `". $nomtableStock."` where idStock='".$stock['idStock']."'";

                $res1=mysql_query($sql1);

                $s_stock=mysql_fetch_array($res1);

                $result='1<>'.$s_stock['quantiteStockinitial'].'<>'.$s_stock['uniteStock'].'<>'.$s_stock['prixuniteStock'].'<>'.$s_stock['dateExpiration'].'<>'.$s_stock['dateStockage'];

            }

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 36){

        $result="0";

        $sql="SELECT * FROM `". $nomtableStock."` where idStock='".$idStock."'";

        $res=mysql_query($sql);

        if($res){

            $stock=mysql_fetch_array($res);

            $sql1="DELETE FROM `".$nomtableStock."` WHERE idStock='".$idStock."'";

            $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

            if($res1){

                $result='1<>'.$stock['quantiteStockinitial'].'<>'.$stock['uniteStock'].'<>'.$stock['prixuniteStock'].'<>'.$stock['dateExpiration'].'<>'.$stock['dateStockage'];

            }

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 37){

        $sql3='SELECT * from  `'.$nomtableDesignation.'` where idDesignation="'.$idDesignation.'" ';

        $res3=mysql_query($sql3);

        $design=mysql_fetch_array($res3);



        $result=$design['idDesignation'].'<>'.$design['designation'].'<>'.$design['categorie'].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$design['prixuniteStock'].'<>'.$design['prixachat'].'<>'.$design['prix'];

        exit($result);



    }

    else if($operation == 38){

        $dateEnregistrement=@htmlspecialchars($_POST["dateJour"]);
        if($dateEnregistrement!='' && $dateEnregistrement!=null){
            $jourEnregistrement=$dateEnregistrement;
        }
        else{
            $jourEnregistrement=$dateString;
        }

        $sql3='SELECT * from  `'.$nomtableEntrepotStock.'` where idEntrepotStock="'.$idEntrepotStock.'" ';

        $res3=mysql_query($sql3);

        $stock=mysql_fetch_array($res3);

        if($stock!=null){

           /* $sql_t="SELECT SUM(quantiteStockinitial) FROM `".$nomtableEntrepotStock."`

            where idStock=".$stock['idStock']."  ";

            $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

            $t_stock = mysql_fetch_array($res_t) ;*/

            $reste=$stock['quantiteStockCourant'] - ($quantite*$stock['nbreArticleUniteStock']);

            if($reste>=0 && $stock['idEntrepot']!=$idEntrepot ){

                $result="1";

                $sql1="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant='".$reste."' where idEntrepotStock='".$idEntrepotStock."'";

                $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

                if($res1){

                    $totalArticleStock=$quantite*$stock['nbreArticleUniteStock'];

                    $sql1='INSERT INTO `'.$nomtableEntrepotStock.'` (idStock,idEntrepot,idDesignation,idTransfert,designation,quantiteStockinitial,uniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant)

                    VALUES('.$stock['idStock'].','.$idEntrepot.','.$stock['idDesignation'].','.$idEntrepotStock.',"'.mysql_real_escape_string($stock["designation"]).'",'.$quantite.',"'.mysql_real_escape_string($stock['uniteStock']).'",'.$stock['nbreArticleUniteStock'].','.$totalArticleStock.',"'.$jourEnregistrement.'",'.$totalArticleStock.')';

                    $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

                    if($res1){

                        $sql2="SELECT * FROM `". $nomtableEntrepotStock."` where idEntrepotStock='".$idEntrepotStock."'";

                        $res2=mysql_query($sql2);

                        $entrepot=mysql_fetch_array($res2);

                        $sql3="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$entrepot['idEntrepot']."'";

                        $res3=mysql_query($sql3);

                        $depot=mysql_fetch_array($res3);

                        $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";

                        $res1=mysql_query($sql1);

                        $design=mysql_fetch_array($res1);
                        $sql4="SELECT * FROM `". $nomtableEntrepotStock."` where idEntrepotStock='".$stock['idTransfert']."'";
                        $res4=mysql_query($sql4);
                        $transfert=mysql_fetch_array($res4);
                    
                        if($transfert!=null){
                          $sql5="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$idEntrepot."'";
                          $res5=mysql_query($sql5);
                          $depotTrf=mysql_fetch_array($res5);

                           $result='1<>'.$depot['nomEntrepot'].'<>'.$stock['designation'].'<>'.$design['categorie'].'<>'.$quantite.'<>'.$stock['uniteStock'] .'('.$design['nbreArticleUniteStock'].' x '.$design['uniteDetails'].') <>'.$jourEnregistrement.'<>'.$stock['dateStockage'].'<>'.$stock['dateExpiration'].'<>'.$depotTrf['nomEntrepot'];
                        
                        }
                        else{

                            $result='1<>'.$depot['nomEntrepot'].'<>'.$stock['designation'].'<>'.$design['categorie'].'<>'.$quantite.'<>'.$stock['uniteStock'] .'('.$design['nbreArticleUniteStock'].' x '.$design['uniteDetails'].') <>'.$jourEnregistrement.'<>'.$stock['dateStockage'].'<>'.$stock['dateExpiration'].'<>NEANT';
                        
                        }
                    }

                    else{

                        $result="0";

                    }

                }

            }

            else if($stock['idEntrepot']==$idEntrepot){

                $result="3";

            }

            else{

                $result="2";

            }

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 39){

        $result="0";

        $sql="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$idDesignation."' ";

        $res=mysql_query($sql);

        if($res){

            $designation=mysql_fetch_array($res);

            $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableEntrepotStock."` where idEntrepot='".$idEntrepot."' AND idDesignation='".$idDesignation."' ";

            $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

            $t_stock = mysql_fetch_array($res_t);

            $totalArticleStock=$t_stock[0];

            $quantiteinventaire=$quantite * $designation['nbreArticleUniteStock'];

           if($quantiteinventaire > $totalArticleStock ){

                $sqlD="SELECT * FROM `".$nomtableEntrepotStock."` where idDesignation='".$idDesignation."'  AND idEntrepot='".$idEntrepot."' ORDER BY idEntrepotStock DESC ";

                $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                $retour=$quantiteinventaire - $t_stock[0];

                while ($stock = mysql_fetch_assoc($resD)) {

                    if($retour>= 0){

                        $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;

                        if($stock['totalArticleStock'] >= $quantiteStockCourant){

                            $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$quantiteStockCourant." where idEntrepotStock=".$stock['idEntrepotStock'];

                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());



                            $sql4='INSERT INTO `'.$nomtableInventaire.'` (idEntrepotStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)

                            VALUES('.$stock['idEntrepotStock'].','.$stock['idDesignation'].','.$quantiteStockCourant.','.$stock['nbreArticleUniteStock'].','.$stock['quantiteStockCourant'].',"'.$dateString.'",0)';

                            $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;

                        }

                        else{

                            $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$stock['totalArticleStock']." where idEntrepotStock=".$stock['idEntrepotStock'];

                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());



                            $sql4='INSERT INTO `'.$nomtableInventaire.'` (idEntrepotStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)

                            VALUES('.$stock['idEntrepotStock'].','.$stock['idDesignation'].','.$stock['totalArticleStock'].','.$stock['nbreArticleUniteStock'].','.$stock['quantiteStockCourant'].',"'.$dateString.'",0)';

                            $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;



                        }

                        $retour=($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'] ;

                    }

                }

                $result='1';

           }

           else if($totalArticleStock > $quantiteinventaire){

                $sqlD="SELECT * FROM `".$nomtableEntrepotStock."` where idDesignation='".$idDesignation."' AND idEntrepot='".$idEntrepot."' ORDER BY idEntrepotStock ASC ";

                $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                $restant=$t_stock[0] - $quantiteinventaire;

                while ($stock = mysql_fetch_assoc($resD)) {

                    if($restant>= 0){

                        $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;

                        if($quantiteStockCourant > 0){

                            $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$quantiteStockCourant." where idEntrepotStock=".$stock['idEntrepotStock'];

                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());



                            $sql4='INSERT INTO `'.$nomtableInventaire.'` (idEntrepotStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)

                            VALUES('.$stock['idEntrepotStock'].','.$stock['idDesignation'].','.$quantiteStockCourant.','.$stock['nbreArticleUniteStock'].','.$stock['quantiteStockCourant'].',"'.$dateString.'",0)';

                            $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;

                        }

                        else{

                            $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=0 where idEntrepotStock=".$stock['idEntrepotStock'];

                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());



                            $sql4='INSERT INTO `'.$nomtableInventaire.'` (idEntrepotStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)

                            VALUES('.$stock['idEntrepotStock'].','.$stock['idDesignation'].',0,'.$stock['nbreArticleUniteStock'].','.$stock['quantiteStockCourant'].',"'.$dateString.'",0)';

                            $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;

                        }

                        $restant= $restant - $stock['quantiteStockCourant'] ;

                    }

                }

                $result='2';

           }

        }

        else{

            $result="0";

        }

        exit($result);



    }

    else if($operation == 41){

        $sql3='SELECT * from  `'.$nomtableDesignation.'` where idDesignation="'.$idDesignation.'" ';

        $res3=mysql_query($sql3);

        $design=mysql_fetch_array($res3);



        $result=$design['idDesignation'].'<>'.$design['designation'].'<>'.$design['categorie'].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$design['prixuniteStock'].'<>'.$design['prix'].'<>'.$design['codeBarreDesignation'].'<>'.$design['codeBarreuniteStock'].'<>'.$design['prixachat'];

        exit($result);



    }

    else if($operation == 42){



        $sql="UPDATE `".$nomtableDesignation."` set codeBarreDesignation='".$codeBD."',codeBarreuniteStock='".$codeBUS."' where idDesignation=".$idDesignation;

        $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());



        $sql3='SELECT * from  `'.$nomtableDesignation.'` where idDesignation="'.$idDesignation.'" ';

        $res3=mysql_query($sql3);

        $design=mysql_fetch_array($res3);



        $result=$design['idDesignation'].'<>'.$design['designation'].'<>'.$design['categorie'].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$design['prixuniteStock'].'<>'.$design['prix'];

        exit($result);



    }

    else if($operation == 43){

        $nombreArticleUniteStock=0;
        $confirm=@htmlspecialchars($_POST["confirm"]);
        $categorie='Sans';



		$sql='select * from `'.$nomtableDesignation.'` where idDesignation="'.$idDesignation.'"';
		$res=mysql_query($sql);

		if(mysql_num_rows($res)){

			if($design=mysql_fetch_array($res)) {

                $sqlD='SELECT * from  `'.$nomtableStock.'` where uniteStock="'.$uniteStock.'" AND quantiteStockinitial="'.$quantite.'" AND dateStockage="'.$dateString.'" AND dateExpiration="'.$dateExpiration.'" AND idDesignation="'.$idDesignation.'" order by idStock DESC';
                $resD=mysql_query($sqlD);
                
                if($stockDoublon=mysql_fetch_array($resD) && $confirm!=1) {

                    $result='2<>'.$idDesignation.'<>'.$design['designation'].'<>'.$design['codeBarreDesignation'].'<>'.$quantite.'<>'.$uniteStock.'<>'.$dateExpiration;
                }
                else{

                    if($confirm==1){
                        $nombreArticleUniteStock  = $design["nbreArticleUniteStock"];

                        $categorie=$design["categorie"];
        
                        if (($uniteStock=="Article")||($uniteStock=="article")){
        
                            $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,prixachat,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration,iduser) VALUES('.$idDesignation.',"'.mysql_real_escape_string($design["designation"]).'",'.$quantite.',"'.mysql_real_escape_string($uniteStock).'",'.$design['prix'].','.$design['prixachat'].',1,'.$quantite.',"'.$dateString.'",'.$quantite.',"'.$dateExpiration.'","'.$_SESSION['iduser'].'")';
        
                            $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;
        
                        }
        
                        else{
        
                            $totalArticleStock=$quantite*$nombreArticleUniteStock;
        
                            $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,prixuniteStock,prixachat,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration,iduser) VALUES('.$idDesignation.',"'.mysql_real_escape_string($design['designation']).'",'.$quantite.',"'.mysql_real_escape_string($uniteStock).'",'.$design['prix'].','.$design['prixuniteStock'].','.$design['prixachat'].','.$nombreArticleUniteStock.','.$totalArticleStock.',"'.$dateString.'",'.$totalArticleStock.',"'.$dateExpiration.'","'.$_SESSION['iduser'].'")';
        
                            $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;


        
                        }
             
                        $result='1<>'.$design['designation'].'<>'.$design['codeBarreDesignation'].'<>'.$uniteStock.'<>'.$design['nbreArticleUniteStock'].'<>'.$design['prixuniteStock'].'<>'.$design['prix'].'<>'.$design['prixachat'];
     
                    }
                    else{

                        $sql_qte="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$idDesignation."' ";
                        $res_qte=mysql_query($sql_qte) or die ("select stock impossible =>".mysql_error());
                        $qte_stock = mysql_fetch_array($res_qte) ;

                        $nombreArticleUniteStock  = $design["nbreArticleUniteStock"];

                        $categorie=$design["categorie"];
        
                        if (($uniteStock=="Article")||($uniteStock=="article")){
        
                            $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,prixachat,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration,iduser) VALUES('.$idDesignation.',"'.mysql_real_escape_string($design["designation"]).'",'.$quantite.',"'.mysql_real_escape_string($uniteStock).'",'.$prixUnitaire.','.$prixAchat.',1,'.$quantite.',"'.$dateString.'",'.$quantite.',"'.$dateExpiration.'","'.$_SESSION['iduser'].'")';
        
                            $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

                            $stock_Anc = $qte_stock[0] * $design['prixachat'];
                            $stock_Nv = $quantite * $prixAchat;
                            $cump = round(( $stock_Anc + $stock_Nv ) / ($qte_stock[0] + $quantite)) ;
        
                        }
        
                        else{
        
                            $totalArticleStock=$quantite*$nombreArticleUniteStock;
        
                            $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,prixuniteStock,prixachat,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration,iduser) VALUES('.$idDesignation.',"'.mysql_real_escape_string($design['designation']).'",'.$quantite.',"'.mysql_real_escape_string($uniteStock).'",'.$prixUnitaire.','.$prixUniteStock.','.$prixAchat.','.$nombreArticleUniteStock.','.$totalArticleStock.',"'.$dateString.'",'.$totalArticleStock.',"'.$dateExpiration.'","'.$_SESSION['iduser'].'")';
        
                            $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

                            $stock_Anc = $qte_stock[0] * $design['prixachat'];
                            $stock_Nv = $totalArticleStock * $prixAchat;
                            $cump = round(( $stock_Anc + $stock_Nv ) / ($qte_stock[0] + $totalArticleStock)) ;
        
                        }

                        $sql0="UPDATE `".$nomtableDesignation."` set prixuniteStock='".$prixUniteStock."', prix='".$prixUnitaire."', prixachat='".$cump."' WHERE idDesignation='".$idDesignation."' ";
                        $res0=mysql_query($sql0) or die ("update quantiteStockCourant impossible =>".mysql_error());
        
                        $sql3='SELECT * from  `'.$nomtableStock.'` where quantiteStockCourant!=0 and idDesignation="'.$idDesignation.'" order by idStock DESC';
        
                        $res3=mysql_query($sql3);
                 
                        $stock=mysql_fetch_array($res3);
    
                        if($design['archiver']==1){
                            $sqlA="UPDATE `".$nomtableDesignation."` set archiver=0 WHERE idDesignation='".$stock['idDesignation']."' ";
                            $resA=mysql_query($sqlA) or die ("update quantiteStockCourant impossible =>".mysql_error());
                        }
                 
                        $result='1<>'.$stock['designation'].'<>'.$design['codeBarreDesignation'].'<>'.$stock['uniteStock'].'<>'.$stock['nbreArticleUniteStock'].'<>'.$prixUniteStock.'<>'.$prixUnitaire.'<>'.$prixAchat;
                 
                    }

                }

		   }

	    }


       exit($result);

    }

    else if($operation == 44){

        $sql="SELECT * FROM `". $nomtableStock."` where idStock='".$idStock."'";

        $res=mysql_query($sql);

        $result="0";

        if($res){

            $stock=mysql_fetch_array($res);

            if($stock['quantiteStockCourant']!=0 && $stock['quantiteStockCourant']!=null){

                $result=$stock['designation'].'<>'.$stock['quantiteStockinitial'].'<>'.($stock['quantiteStockCourant']/ $stock['nbreArticleUniteStock']).'<>'.$stock['uniteStock'].'<>'.$stock['dateExpiration'].'<>'.$stock['dateStockage'];

            }

            else{

                $result=$stock['designation'].'<>'.$stock['quantiteStockinitial'].'<>0<>'.$stock['uniteStock'].'<>'.$stock['dateExpiration'].'<>'.$stock['dateStockage'];

            }



        }

        exit($result);

    }

    else if($operation == 45){

        $result="0";

        $sql="SELECT * FROM `". $nomtableStock."` where idStock='".$idStock."'";

        $res=mysql_query($sql);

        if($res){

            $stock=mysql_fetch_array($res);

            if($stock['quantiteStockinitial']>=$quantite){

                $totalArticleStock=$quantite * $stock['nbreArticleUniteStock'];

                $sql1="UPDATE `".$nomtableStock."` set quantiteStockCourant='".$totalArticleStock."',dateExpiration='".$dateExpiration."'  where idStock='".$idStock."'";

                $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

                if($res1){                    

                    if($stock['quantiteStockCourant']!=$totalArticleStock){

                        $sql4='INSERT INTO `'.$nomtableInventaire.'` (idStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)

                        VALUES('.$idStock.','.$stock['idDesignation'].','.$totalArticleStock.','.$stock['nbreArticleUniteStock'].','.$stock['quantiteStockCourant'].',"'.$dateString.'",2)';

                        $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;

                    }

                    if ($_SESSION['categorie']=="Grossiste") {

                        if($totalArticleStock!=0 && $totalArticleStock!=null){

                            $result='1<>'.$stock['dateStockage'].'<>'.$stock['quantiteStockinitial'].'<>'.($totalArticleStock / $stock['nbreArticleUniteStock']).'<>'.$stock['uniteStock'].'<>'.$stock['nbreArticleUniteStock'].'<>'.$stock['prixuniteStock'].'<>'.$stock['prixunitaire'].'<>'.$dateExpiration;

                        }

                        else{

                            $result='1<>'.$stock['dateStockage'].'<>'.$stock['quantiteStockinitial'].'<>0<>'.$stock['uniteStock'].'<>'.$stock['nbreArticleUniteStock'].'<>'.$stock['prixuniteStock'].'<>'.$stock['prixunitaire'].'<>'.$dateExpiration;

                        }

                    }

                    else{

                        $result='1<>'.$stock['dateStockage'].'<>'.$stock['quantiteStockinitial'].'<>'.$quantite.'<>'.$stock['uniteStock'].'<>'.$stock['nbreArticleUniteStock'].'<>'.$stock['prixuniteStock'].'<>'.$stock['prixunitaire'].'<>'.$dateExpiration;

                    }

                }

            }

            else{

                $result="0";

            }

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 46){

        $result="0";

        $sql="SELECT * FROM `". $nomtableStock."` where idStock='".$idStock."'";

        $res=mysql_query($sql);

        if($res){

            $stock=mysql_fetch_array($res);

            if($stock['quantiteStockinitial']>=$quantite){

                $totalArticleStock=$quantite * $stock['nbreArticleUniteStock'];

                $sql1="DELETE FROM `".$nomtableStock."` WHERE idStock='".$idStock."'";

                $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

                if($res1){

                    if ($_SESSION['categorie']=="Grossiste") {

                        if($stock['quantiteStockCourant']!=0 && $stock['quantiteStockCourant']!=null){

                            $result='1<>'.$stock['dateStockage'].'<>'.$stock['quantiteStockinitial'].'<>'.($stock['quantiteStockCourant'] / $stock['nbreArticleUniteStock']).'<>'.$stock['uniteStock'].'<>'.$stock['nbreArticleUniteStock'].'<>'.$stock['prixuniteStock'].'<>'.$stock['prixunitaire'].'<>'.$stock['dateExpiration'];

                        }

                        else{

                            $result='1<>'.$stock['dateStockage'].'<>'.$stock['quantiteStockinitial'].'<>0<>'.$stock['uniteStock'].'<>'.$stock['nbreArticleUniteStock'].'<>'.$stock['prixuniteStock'].'<>'.$stock['prixunitaire'].'<>'.$stock['dateExpiration'];

                        }

                    }

                    else{

                        $result='1<>'.$stock['dateStockage'].'<>'.$stock['quantiteStockinitial'].'<>'.$stock['quantiteStockCourant'].'<>'.$stock['uniteStock'].'<>'.$stock['nbreArticleUniteStock'].'<>'.$stock['prixuniteStock'].'<>'.$stock['prixunitaire'].'<>'.$stock['dateExpiration'];

                    }

                }

            }

            else{

                $result="0";

            }

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 47){

        $sql="SELECT * FROM `". $nomtableEntrepotStock."` where idEntrepotStock='".$idEntrepotStock."'";

        $res=mysql_query($sql);

        if($res){

            $stock=mysql_fetch_array($res);

                $sql2="SELECT * FROM `". $nomtableStock."` where idStock='".$stock['idStock']."'";

                $res2=mysql_query($sql2);

                $expire=mysql_fetch_array($res2);

                $sql3="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$stock['idEntrepot']."'";

                $res3=mysql_query($sql3);

                $entrepot=mysql_fetch_array($res3);

                if($stock['quantiteStockCourant']!=0 && $stock['quantiteStockCourant']!=null){

                    $result='1<>'.$expire['dateExpiration'].'<>'.$expire['designation'].'<>'.($stock['totalArticleStock']/ $stock['nbreArticleUniteStock']).'<>'.($stock['quantiteStockCourant']/ $stock['nbreArticleUniteStock']).'<>'.$entrepot['nomEntrepot'];

                }

                else{

                    $result='1<>'.$expire['dateExpiration'].'<>'.$expire['designation'].'<>'.($stock['totalArticleStock']/ $stock['nbreArticleUniteStock']).'<>0<>'.$entrepot['nomEntrepot'];

                }

            }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 48){

        $result="0";

        $sql="SELECT * FROM `". $nomtableEntrepotStock."` where idEntrepotStock='".$idEntrepotStock."'";

        $res=mysql_query($sql);

        if($res){

            $stock=mysql_fetch_array($res);

            $sql1="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=0 where idEntrepotStock='".$idEntrepotStock."'";

            $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

            if($res1){

                $sql4='INSERT INTO `'.$nomtableInventaire.'` (idEntrepotStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)

                VALUES('.$idEntrepotStock.','.$stock['idDesignation'].',0,'.$stock['nbreArticleUniteStock'].','.$stock['quantiteStockCourant'].',"'.$dateString.'",1)';

                $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;



                $sql2="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$stock['idEntrepot']."'";

                $res2=mysql_query($sql2);

                $entrepot=mysql_fetch_array($res2);

                $sql3="SELECT * FROM `". $nomtableStock."` where idStock='".$stock['idStock']."'";

                $res3=mysql_query($sql3);

                $expire=mysql_fetch_array($res3);

                if($stock['quantiteStockCourant']!=0 && $stock['quantiteStockCourant']!=null){

                    $result='1<>'.$expire['dateExpiration'].'<>'.$expire['designation'].'<>'.($stock['quantiteStockCourant']/ $stock['nbreArticleUniteStock']).'<>'.$entrepot['nomEntrepot'];

                }

                else{

                    $result='1<>'.$expire['dateExpiration'].'<>'.$expire['designation'].'<>0<>'.$entrepot['nomEntrepot'];

                }

            }

        }

        exit($result);

    }

    else if($operation == 49){

        $sql3='SELECT * from  `'.$nomtableStock.'` where idStock="'.$idStock.'" ';

        $res3=mysql_query($sql3);

        $stock=mysql_fetch_array($res3);



        $result=$stock['designation'].'<>'.$stock['totalArticleStock'].'<>'.$stock['quantiteStockCourant'].'<>'.$stock['dateExpiration'];

        exit($result);



    }

    else if($operation == 50){

        $result="0";

        $sql="SELECT * FROM `". $nomtableStock."` where idStock='".$idStock."'";

        $res=mysql_query($sql);

        if($res){

            $stock=mysql_fetch_array($res);

            $sql1="UPDATE `".$nomtableStock."` set quantiteStockCourant=0 where idStock='".$idStock."'";

            $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

            if($res1){

                $sql4='INSERT INTO `'.$nomtableInventaire.'` (idStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)

                VALUES('.$stock['idStock'].','.$stock['idDesignation'].',0,'.$stock['nbreArticleUniteStock'].','.$stock['quantiteStockCourant'].',"'.$dateString.'",1)';

                $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;



                $result='1<>'.$stock['dateExpiration'].'<>'.$stock['designation'].'<>'.$stock['quantiteStockCourant'];

            }

        }

        exit($result);

    }

    else if($operation == 51){

        $result="0";

        $sql="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$idDesignation."' ";

        $res=mysql_query($sql);

        if($res){

            $designation=mysql_fetch_array($res);

            $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$idDesignation."' ";

            $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

            $t_stock = mysql_fetch_array($res_t);

            $totalArticleStock=$t_stock[0];

            $quantiteinventaire=$quantite;

           if($quantiteinventaire > $totalArticleStock ){

                $sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$idDesignation."'  ORDER BY idStock DESC ";

                $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                $retour=$quantiteinventaire - $t_stock[0];

                while ($stock = mysql_fetch_assoc($resD)) {

                    if($retour>= 0){

                        $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;

                        if($stock['totalArticleStock'] >= $quantiteStockCourant){

                            $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];

                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());



                            $sql4='INSERT INTO `'.$nomtableInventaire.'` (idStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)

                            VALUES('.$stock['idStock'].','.$stock['idDesignation'].','.$quantiteStockCourant.','.$stock['nbreArticleUniteStock'].','.$stock['quantiteStockCourant'].',"'.$dateString.'",0)';

                            $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;

                        }

                        else{

                            $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$stock['totalArticleStock']." where idStock=".$stock['idStock'];

                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());



                            $sql4='INSERT INTO `'.$nomtableInventaire.'` (idStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)

                            VALUES('.$stock['idStock'].','.$stock['idDesignation'].','.$stock['totalArticleStock'].','.$stock['nbreArticleUniteStock'].','.$stock['quantiteStockCourant'].',"'.$dateString.'",0)';

                            $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;



                        }

                        $retour=($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'] ;

                    }

                }

                if($retour > 0){

                    $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,iduser) VALUES('.$idDesignation.',"'.mysql_real_escape_string($designation["designation"]).'",'.$retour.',"Article",'.$designation['prix'].',1,'.$retour.',"'.$dateString.'",'.$retour.',"'.$_SESSION['iduser'].'")';

                    $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;



                    $sql4='INSERT INTO `'.$nomtableInventaire.'` (idStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)

                    VALUES(0,'.$idDesignation.','.$retour.',1,0,"'.$dateString.'",0)';

                    $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;

                }

                $result='1';

           }

           else if($totalArticleStock > $quantiteinventaire){

                $sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$idDesignation."'  ORDER BY idStock ASC ";

                $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                $restant=$t_stock[0] - $quantiteinventaire;

                while ($stock = mysql_fetch_assoc($resD)) {

                    if($restant>= 0){

                        $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;

                        if($quantiteStockCourant > 0){

                            $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];

                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());



                            $sql4='INSERT INTO `'.$nomtableInventaire.'` (idStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)

                            VALUES('.$stock['idStock'].','.$stock['idDesignation'].','.$quantiteStockCourant.','.$stock['nbreArticleUniteStock'].','.$stock['quantiteStockCourant'].',"'.$dateString.'",0)';

                            $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;

                        }

                        else{

                            $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=0 where idStock=".$stock['idStock'];

                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());



                            $sql4='INSERT INTO `'.$nomtableInventaire.'` (idStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)

                            VALUES('.$stock['idStock'].','.$stock['idDesignation'].',0,'.$stock['nbreArticleUniteStock'].','.$stock['quantiteStockCourant'].',"'.$dateString.'",0)';

                            $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;

                        }

                        $restant= $restant - $stock['quantiteStockCourant'] ;

                    }

                }

                $result='2';

           }

        }

        else{

            $result="0";

        }

        exit($result);



    }

    else if($operation == 52){

        $result="0";

        $sql="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$idDesignation."' ";

        $res=mysql_query($sql);

        if($res){

            $designation=mysql_fetch_array($res);

            $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$idDesignation."' ";

            $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

            $t_stock = mysql_fetch_array($res_t);

            $totalArticleStock=$t_stock[0];

            $quantiteinventaire=$quantite;

           if($quantiteinventaire > $totalArticleStock ){

                $sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$idDesignation."'  ORDER BY idStock DESC ";

                $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                $retour=$quantiteinventaire - $t_stock[0];

                while ($stock = mysql_fetch_assoc($resD)) {

                    if($retour>= 0){

                        $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;

                        if($stock['quantiteStockinitial'] >= $quantiteStockCourant){

                            $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];

                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());



                            $sql4='INSERT INTO `'.$nomtableInventaire.'` (idStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)

                            VALUES('.$stock['idStock'].','.$stock['idDesignation'].','.$quantiteStockCourant.',1,'.$stock['quantiteStockCourant'].',"'.$dateString.'",0)';

                            $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;

                        }

                        else{

                            $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$stock['quantiteStockinitial']." where idStock=".$stock['idStock'];

                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());



                            $sql4='INSERT INTO `'.$nomtableInventaire.'` (idStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)

                            VALUES('.$stock['idStock'].','.$stock['idDesignation'].','.$stock['quantiteStockinitial'].',1,'.$stock['quantiteStockCourant'].',"'.$dateString.'",0)';

                            $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;



                        }

                        $retour=($retour + $stock['quantiteStockCourant']) - $stock['quantiteStockinitial'] ;

                    }

                }

                $result='1';

           }

           else if($totalArticleStock > $quantiteinventaire){

                $sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$idDesignation."'  ORDER BY idStock ASC ";

                $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                $restant=$t_stock[0] - $quantiteinventaire;

                while ($stock = mysql_fetch_assoc($resD)) {

                    if($restant>= 0){

                        $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;

                        if($quantiteStockCourant > 0){

                            $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];

                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());



                            $sql4='INSERT INTO `'.$nomtableInventaire.'` (idStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)

                            VALUES('.$stock['idStock'].','.$stock['idDesignation'].','.$quantiteStockCourant.',1,'.$stock['quantiteStockCourant'].',"'.$dateString.'",0)';

                            $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;

                        }

                        else{

                            $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=0 where idStock=".$stock['idStock'];

                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());



                            $sql4='INSERT INTO `'.$nomtableInventaire.'` (idStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)

                            VALUES('.$stock['idStock'].','.$stock['idDesignation'].',0,1,'.$stock['quantiteStockCourant'].',"'.$dateString.'",0)';

                            $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;

                        }

                        $restant= $restant - $stock['quantiteStockCourant'] ;

                    }

                }

                $result='2';

           }

        }

        else{

            $result="0";

        }

        exit($result);



    }

    else if($operation == 53){

        $result="0";

        $sql='select * from `'.$nomtableDesignation.'` where designation="'.$designation.'"';

		$res=mysql_query($sql);

		if($design=mysql_fetch_array($res)) {

            $nombreArticleUniteStock  = $design["nbreArticleUniteStock"];

            $categorie=$design["categorie"];



            if (($uniteStock=="Article")||($uniteStock=="article")){

                $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration,iduser) VALUES('.$design["idDesignation"].',"'.mysql_real_escape_string($design["designation"]).'",'.$quantite.',"'.mysql_real_escape_string($uniteStock).'",'.$design['prix'].',1,'.$quantite.',"'.$dateString.'",'.$quantite.',"'.$dateExpiration.'","'.$_SESSION['iduser'].'")';

                $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

                if($res1){

                    $result="1";

                }

            }

            else{

                $totalArticleStock=($quantite * $nombreArticleUniteStock);

                $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,prixuniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration,iduser) VALUES('.$design["idDesignation"].',"'.mysql_real_escape_string($design["designation"]).'",'.$quantite.',"'.mysql_real_escape_string($uniteStock).'",'.$design['prix'].','.$design['prixuniteStock'].','.$nombreArticleUniteStock.','.$totalArticleStock.',"'.$dateString.'",'.$totalArticleStock.',"'.$dateExpiration.'","'.$_SESSION['iduser'].'")';

                $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

                if($res1){

                    $result="1";

                }

            }



        }

       exit($result);

    }

    else if($operation == 54){



        $prixAchat=@htmlspecialchars($_POST["prixAchat"]);

        $nombreArticleUniteStock=0;

        $categorie='Sans';

        $result="0";



		$sql='select * from `'.$nomtableDesignation.'` where idDesignation="'.$idDesignation.'"';

		//echo $sql;

		$res=mysql_query($sql);

		if(mysql_num_rows($res)){

			if($design=mysql_fetch_array($res)) {

                $sqlS="SELECT SUM(quantiteStockCourant)
                FROM `".$nomtableEntrepotStock."`
                where idDesignation ='".$idDesignation."'  ";
                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                $S_stock = mysql_fetch_array($resS);
              
                $sqlI="SELECT SUM(quantiteStockinitial)
                FROM `".$nomtableStock."`
                where idDesignation ='".$idDesignation."'  ";
                $resI=mysql_query($sqlI) or die ("select stock impossible =>".mysql_error());
                $I_stock = mysql_fetch_array($resI);
              
                $sqlE="SELECT SUM(quantiteStockinitial)
                FROM `".$nomtableEntrepotStock."`
                where idDesignation ='".$idDesignation."' AND (idTransfert=0 OR idTransfert IS NULL)   ";
                $resE=mysql_query($sqlE) or die ("select stock impossible =>".mysql_error());
                $E_stock = mysql_fetch_array($resE);

                $nombreArticleUniteStock  = $design["nbreArticleUniteStock"];

                $categorie=$design["categorie"];

                $totalArticleStock=$quantite*$nombreArticleUniteStock;

                $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,idBl,designation,quantiteStockinitial,uniteStock,prixunitaire,prixachat,prixuniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration,iduser)

                VALUES('.$idDesignation.','.$idBl.',"'.mysql_real_escape_string($design['designation']).'",'.$quantite.',"'.mysql_real_escape_string($design['uniteStock']).'",'.$prixUnitaire.','.$prixAchat.','.$prixUniteStock.','.$nombreArticleUniteStock.','.$totalArticleStock.',"'.$dateString.'",'.$totalArticleStock.',"'.$dateExpiration.'","'.$_SESSION['iduser'].'")';

                $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

                if($res1){

                    $qte_stock = $S_stock[0] + ($I_stock[0] - $E_stock[0]);
                    $stock_Anc = $qte_stock * $design['prixachat'];
                    $stock_Nv = $totalArticleStock * $prixAchat;
                    $cump = round(( $stock_Anc + $stock_Nv ) / ($qte_stock + $totalArticleStock)) ;
                
                    $sql0="UPDATE `".$nomtableDesignation."` set prixuniteStock='".$prixUniteStock."', prix='".$prixUnitaire."', prixachat='".$cump."' WHERE idDesignation='".$idDesignation."' ";
                    $res0=mysql_query($sql0) or die ("update quantiteStockCourant impossible =>".mysql_error());

                    $sql3='SELECT * from  `'.$nomtableStock.'` where quantiteStockCourant!=0 and idDesignation="'.$idDesignation.'" order by idStock DESC';

                    $res3=mysql_query($sql3);

                    $stock=mysql_fetch_array($res3);

                    if ($idDepot =="0" || $idDepot ==null || $idDepot =='') {
                            
                        $result='1<>'.$stock['designation'].'<>'.$design['categorie'].'<>'.$design['uniteStock'].'<>'.$stock['prixuniteStock'].'<>'.$design['prixachat'];

                    }
                    else {
                        # code...
                        
                        $sql3='SELECT * from  `'.$nomtableStock.'` where idStock="'.$stock['idStock'].'" ';

                        $res3=mysql_query($sql3);

                        $stock=mysql_fetch_array($res3);

                        if($stock!=null){

                            $sql_t="SELECT SUM(quantiteStockinitial) FROM `".$nomtableEntrepotStock."`

                            where idStock=".$stock['idStock']." AND idTransfert=null  ";

                            $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

                            $t_stock = mysql_fetch_array($res_t) ;

                            $reste=$stock['quantiteStockinitial'] - $t_stock[0] - $quantite;

                            if($reste>=0){

                                $totalArticleStock=$quantite*$stock['nbreArticleUniteStock'];

                                $sql1='INSERT INTO `'.$nomtableEntrepotStock.'` (idStock,idEntrepot,idDesignation,designation,quantiteStockinitial,uniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant)

                                VALUES('.$stock['idStock'].','.$idDepot.','.$stock['idDesignation'].',"'.mysql_real_escape_string($stock["designation"]).'",'.$quantite.',"'.mysql_real_escape_string($stock['uniteStock']).'",'.$stock['nbreArticleUniteStock'].','.$totalArticleStock.',"'.$dateString.'",'.$totalArticleStock.')';
                                // var_dump($sql1);
                                $res1=@mysql_query($sql1) or die ("insertion stock 12 impossible".mysql_error()) ;

                                if($res1){

                                    $sql2="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$idEntrepot."'";

                                    $res2=mysql_query($sql2);

                                    $entrepot=mysql_fetch_array($res2);

                                    $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";

                                    $res1=mysql_query($sql1);

                                    $design=mysql_fetch_array($res1);


                                    $result='1<>'.$stock['designation'].'<>'.$design['uniteStock'].'<>'.$dateString.'<>'.$design['categorie'].'<>'.$stock['prixuniteStock'].'<>'.$design['prixachat'];

                                    // $result='1<>'.$entrepot['nomEntrepot'].'<>'.$stock['designation'].'<>'.$design['categorie'].'<>'.$quantite.'<>'.$stock['uniteStock'] .'('.$design['nbreArticleUniteStock'].' x '.$design['uniteDetails'].') <>'.$dateString;

                                }

                                else{

                                    $result="0";

                                }

                            }

                            else{

                                $result="2";

                            }

                        }

                        else {

                            $result="0";

                        }

                    }

                    // $result='1<>'.$stock['designation'].'<>'.$design['uniteStock'].'<>'.$dateString;

                }

                else{

                    $result="0";

                }



		   }

	   }

       exit($result);

    }

    else if($operation == 55){



        $prixBrute=@htmlspecialchars($_POST["prixAchat"]);

        $nombreArticleUniteStock=0;

        $categorie='Sans';

        $result="0";



		$sql='select * from `'.$nomtableDesignation.'` where idDesignation="'.$idDesignation.'"';

		//echo $sql;

		$res=mysql_query($sql);

		if(mysql_num_rows($res)){

			if($design=mysql_fetch_array($res)) {

                $nombreArticleUniteStock  = $design["nbreArticleUniteStock"];

                $categorie=$design["categorie"];

                $totalArticleStock=$quantite*$nombreArticleUniteStock;



                    $sqlT1="SELECT * FROM `".$nomtableBl."` where idBl='".$idBl."'  ";

                    $resT1 = mysql_query($sqlT1) or die ("persoonel requête 2".mysql_error());

                    $bl = mysql_fetch_assoc($resT1);



                    $sql0="SELECT * FROM `".$nomtableVoyage."` where idVoyage=".$bl['idVoyage']."";

                    $res0 = mysql_query($sql0) or die ("persoonel requête 3".mysql_error());

                    $voyage = mysql_fetch_assoc($res0);

                    $tauxRevient=number_format($voyage['tauxRevient'], 2, '.', ' ');

                    $tauxVente=number_format($voyage['tauxVente'], 2, '.', ' ');



                    $prixAchat=($prixBrute / $bl['valeurDevise']) * $bl['valeurConversion'];

                    $prixRv=$prixAchat + ($prixAchat * $tauxRevient);

                    $prixVt=$prixRv + ($prixRv * $tauxVente);

                    $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,idBl,designation,quantiteStockinitial,uniteStock,prixachat,prixuniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration,iduser)

                    VALUES('.$idDesignation.','.$idBl.',"'.mysql_real_escape_string($design['designation']).'",'.$quantite.',"'.mysql_real_escape_string($design['uniteStock']).'",'.$prixAchat.','.$prixVt.','.$nombreArticleUniteStock.','.$totalArticleStock.',"'.$dateString.'",'.$totalArticleStock.',"'.$dateExpiration.'","'.$_SESSION['iduser'].'")';

                    $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

                    
                    $sqlS="UPDATE `".$nomtableDesignation."` set prixachat=".$prixAchat.",prixuniteStock=".$prixVt." where idDesignation=".$idDesignation;
                    $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());


                if($res1){

                    $sql3='SELECT * from  `'.$nomtableStock.'` where quantiteStockCourant!=0 and idDesignation="'.$idDesignation.'" order by idStock DESC';

                    $res3=mysql_query($sql3);

                    $stock=mysql_fetch_array($res3);



                    $result='1<>'.$stock['designation'].'<>'.$design['uniteStock'].'<>'.$dateString;

                }

                else{

                    $result="0";

                }



		   }

	   }

       exit($result);

    }

    else if($operation == 57){
        /**************************************** */

            /**Debut retrait caisse */
                
                $sqlRetraitC="SELECT *

                FROM `".$nomtableDesignation."` d

                INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation

                INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

                WHERE l.classe = 7 && p.idClient=0 && p.verrouiller=1 && p.type=0 && ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."') or (p.datepagej BETWEEN '".$debutAnnee."' AND '".$finAnnee."') ) ORDER BY p.idPagnet DESC  ";

                $resRetraitC = mysql_query($sqlRetraitC) or die ("persoonel requête 2".mysql_error());
                
                $T_RetraitC = 0;

                // $S_App2 = 20;

                while ($pagnet = mysql_fetch_array($resRetraitC)) {

                    $T_RetraitC=$T_RetraitC + $pagnet['prixtotal'];
                    // $S_App2=15;

                }
            /**Fin retrait caisse */

            /**Debut approvisionnement caisse */
                
                $sqlApp="SELECT *

                FROM `".$nomtableDesignation."` d

                INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation

                INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

                WHERE l.classe = 5 && p.idClient=0 && p.verrouiller=1 && p.type=0 && ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."') or (p.datepagej BETWEEN '".$debutAnnee."' AND '".$finAnnee."') ) ORDER BY p.idPagnet DESC  ";

                $resApp = mysql_query($sqlApp) or die ("persoonel requête 2".mysql_error());
                
                $T_App = 0;

                // $S_App2 = 20;

                while ($pagnet = mysql_fetch_array($resApp)) {

                    $T_App=$T_App + $pagnet['prixtotal'];
                    // $S_App2=15;

                }
            /**Fin approvisionnement caisse */

            /**Debut ClientsVersements */

                $som_Clients=0;

                $sqlVC="SELECT SUM(v.montant) FROM `".$nomtableVersement."` v , `".$nomtableClient."` c

                where c.idClient=v.idClient and c.archiver=0 and ((v.idClient!=0 AND  (CONCAT(CONCAT(SUBSTR(dateVersement,7, 10),'',SUBSTR(dateVersement,3, 4)),'',SUBSTR(dateVersement,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."')) or (v.idClient!=0 AND  (dateVersement BETWEEN '".$debutAnnee."' AND '".$finAnnee."'))) ";

                $resVC = mysql_query($sqlVC) or die ("persoonel requête 2".mysql_error());

                $totalVC = mysql_fetch_array($resVC) ;

                $som_Clients=$totalVC[0];

            /**Fin ClientsVersements */

            /**Debut SortiesVente */

                    $som_SortiesVente=0;

                    $sqlV="SELECT *

                    FROM `".$nomtableDesignation."` d

                    INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation

                    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

                    WHERE l.classe = 0 && p.idClient=0  && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY p.idPagnet DESC ";

                    $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());

                    while ($pagnet = mysql_fetch_array($resV)) {

                        $som_SortiesVente=$som_SortiesVente + $pagnet['prixtotal'];

                    }

            /**Fin SortiesVente */
            

            /**Debut SortiesVente */

                // $som_SortiesVente=0;

                // $sqlSV="SELECT *

                // FROM  `".$nomtableLigne."` l 

                // INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

                // WHERE l.classe = 0  && p.idClient =0  && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY p.idPagnet DESC ";

                // $resSV = mysql_query($sqlSV) or die ("persoonel requête 2".mysql_error());

                // $resSV=mysql_query($sqlSV);

                // $produitsSV=array();

                // while($stockS=mysql_fetch_array($resSV)){

                //     if(in_array($stockS['idDesignation'], $produitsSV)){

                //         // echo "Existe.";

                //     }

                //     else{                    

                //         $sqlT="SELECT SUM(prixtotal)

                //         FROM `".$nomtableLigne."` l

                //         INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation

                //         INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

                //         where p.idClient =0 &&  p.verrouiller=1 && p.type=0 && d.idDesignation='".$stockS["idDesignation"]."'  && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ";

                //         $resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());

                //         $prixTotal = mysql_fetch_array($resT);

                //         $som_SortiesVente=$som_SortiesVente + $prixTotal[0];

                //         $produitsSV[] = $stockS['idDesignation'];

                //         }

                //     }

            /**Fin SortiesVente */

            /**Debut Services */

                $som_Services=0;

                $sqlSv="SELECT *

                    FROM `".$nomtableDesignation."` d

                    INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation

                    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

                    WHERE l.classe = 1 && p.idClient=0 && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY p.idPagnet DESC  ";

                $resSv = mysql_query($sqlSv) or die ("persoonel requête 2".mysql_error());

                while ($pagnet = mysql_fetch_array($resSv)) {

                    $som_Services = $som_Services + $pagnet['prixtotal'];

                }

            /**Fin Services */

            /**Debut SortiesRetournés */

                $sql1="SELECT *from `".$nomtableInventaire."` i

                INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = i.idDesignation

                WHERE i.TYPE=3 AND i.dateInventaire BETWEEN '".$debutAnnee."' AND '".$finAnnee."' order by i.idInventaire desc";

                $res1=mysql_query($sql1);

                $som_SortiesRetournes=0;

                while ($inventaire = mysql_fetch_array($res1)) {

                    if($inventaire['quantite'] > $inventaire['quantiteStockCourant'] || $inventaire['quantite'] < $inventaire['quantiteStockCourant']){

                        if ($inventaire['quantite'] < $inventaire['quantiteStockCourant']) {

                            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")){

                                $som_SortiesRetournes = $som_SortiesRetournes + (($inventaire['quantiteStockCourant'] - $inventaire['quantite']) * $inventaire['prixPublic']);

                            }

                            else{

                                $som_SortiesRetournes = $som_SortiesRetournes + (($inventaire['quantiteStockCourant'] - $inventaire['quantite']) * $inventaire['prix']);

                            }    

                        }

                    }

                }

            

            /**Fin SortiesRetournés */

        /**************************************** */

        /**************************************** */

            /**Debut Depenses */

                $som_Depenses=0;

                $sqlD="SELECT DISTINCT p.idPagnet

                    FROM `".$nomtablePagnet."` p

                    INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

                    WHERE l.classe=2 && p.idClient=0  && p.verrouiller=1 && p.type=0  && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY p.idPagnet DESC";

                $resD = mysql_query($sqlD) or die ("persoonel requête 2".mysql_error());

                while ($pagnet = mysql_fetch_array($resD)) {

                    $sqlS="SELECT SUM(apayerPagnet)

                    FROM `".$nomtablePagnet."`

                    where idClient=0 &&  verrouiller=1 && idPagnet='".$pagnet['idPagnet']."' && type=0  && CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ORDER BY idPagnet DESC";

                    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                    $S_depenses = mysql_fetch_array($resS);

                    $som_Depenses = $S_depenses[0] + $som_Depenses;

                }



            /**Fin Depenses */

            /**Debut FournisseursV */

                $som_FournisseursV=0;

                $sqlV="SELECT SUM(montant) FROM `".$nomtableVersement."`

                WHERE idFournisseur!=0 && dateVersement BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ";

                $resV=mysql_query($sqlV) or die ("select stock impossible =>".mysql_error());

                $totalV = mysql_fetch_array($resV);

                $som_FournisseursV=$totalV[0];

            /**Fin FournisseursV */

            /**Debut ClientsBonsEspece */

                $som_ClientsBE=0;

                $sqlBE="SELECT DISTINCT p.idPagnet

                    FROM `".$nomtablePagnet."` p

                    INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

                    WHERE l.classe=6 && p.idClient!=0  && p.verrouiller=1 && p.type=0  && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ORDER BY p.idPagnet DESC";

                $resBE = mysql_query($sqlBE) or die ("persoonel requête 2".mysql_error());

                while ($pagnet = mysql_fetch_array($resBE)) {

                    $sqlS="SELECT SUM(apayerPagnet), idClient

                    FROM `".$nomtablePagnet."`

                    where idClient!=0 &&  verrouiller=1 && idPagnet='".$pagnet['idPagnet']."' && type=0  && CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ORDER BY idPagnet DESC";

                    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                    $S_bonEsp = mysql_fetch_array($resS);
                    
                    $sqlCli="SELECT * FROM `".$nomtableClient."` where idClient=".$S_bonEsp[1];

                    $resCli = mysql_query($sqlCli) or die ("persoonel requête 2".mysql_error());

                    $client = mysql_fetch_array($resCli) ;

                    if ($client['archiver'] == 0) {

                        $som_ClientsBE = $S_bonEsp[0] + $som_ClientsBE;

                    }


                }

            /**Fin ClientsBonsEspece */

            /**Debut Remises Vente*/

                $remiseTotalVente=0;

                $sqlRm="SELECT DISTINCT p.idPagnet

                    FROM `".$nomtablePagnet."` p

                    INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

                    WHERE (l.classe=0 || l.classe=1)  && p.idClient=0  && p.verrouiller=1 && p.type=0  && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ORDER BY p.idPagnet DESC";

                $resRm = mysql_query($sqlRm) or die ("persoonel requête 2".mysql_error());

                while ($pagnet = mysql_fetch_array($resRm)) {

                    $sqlS="SELECT SUM(remise)

                    FROM `".$nomtablePagnet."`

                    where verrouiller=1  && idClient=0 && type=0 && idPagnet='".$pagnet['idPagnet']."'  && CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ORDER BY idPagnet DESC";

                    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                    $S_remises0 = mysql_fetch_array($resS);

                    $remiseTotalVente = $S_remises0[0] + $remiseTotalVente;

                }

            /**Fin Remises Vente*/

        /**************************************** */

        /**************************************** */

            /**Debut SortiesBon */

                $som_SortiesBon=0;

                $sqlBP="SELECT DISTINCT p.idPagnet

                    FROM `".$nomtablePagnet."` p

                    INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

                    WHERE (l.classe!=6 && l.classe!=9) && p.idClient!=0  && p.verrouiller=1 && (p.type=0 || p.type=30) && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ORDER BY p.idPagnet DESC";

                $resBP = mysql_query($sqlBP) or die ("persoonel requête 2".mysql_error());

                while ($pagnet = mysql_fetch_array($resBP)) {

                    $sqlS="SELECT SUM(apayerPagnet)

                    FROM `".$nomtablePagnet."`

                    where idClient!=0 &&  verrouiller=1 && idPagnet='".$pagnet['idPagnet']."' && (type=0 || type=30) && CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY idPagnet DESC";

                    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                    $S_bonP = mysql_fetch_array($resS);

                    $som_SortiesBon = $S_bonP[0] + $som_SortiesBon;

                }

            /**Fin SortiesBon */  

            /**Debut SortiesBon */ 

                $sql12="SELECT idClient, SUM(apayerPagnet) as somme_bon FROM `".$nomtablePagnet."` where idClient!=0 AND verrouiller=1 AND (type=0 || type=30) Group By idClient ";

                $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());

                $TotalB = 0;
                // $TotalB = mysql_fetch_array($res12) ;

                while ($b = mysql_fetch_array($res12)) {
                    
                    $sqlCli="SELECT * FROM `".$nomtableClient."` where idClient=".$b['idClient'];

                    $resCli = mysql_query($sqlCli) or die ("persoonel requête 2".mysql_error());

                    $client = mysql_fetch_array($resCli) ;

                    if ($client['archiver'] == 0) {

                        $TotalB = $TotalB + $b['somme_bon'];

                    }
                    
                }
            

                $sql13="SELECT idClient, SUM(montant) as somme_versement FROM `".$nomtableVersement."` where idClient!=0 Group By idClient ";

                $res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());

                $TotalV = 0 ;
                // $TotalV = mysql_fetch_array($res13) ;

                while ($v = mysql_fetch_array($res13)) {
                    
                    $sqlCli="SELECT * FROM `".$nomtableClient."` where idClient=".$v['idClient'];

                    $resCli = mysql_query($sqlCli) or die ("persoonel requête 2".mysql_error());

                    $client = mysql_fetch_array($resCli) ;

                    if ($client['archiver'] == 0) {

                        $TotalV = $TotalV + $v['somme_versement'];

                    }
                    
                }

                $montantARecouvrir=$TotalB - $TotalV; 

            /**Fin SortiesBon */

        /**************************************** */

        /**************************************** */

            /** Debut montant stock actuel */

                if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

                    $sql2="SELECT s.prixSession,s.prixPublic,s.quantiteStockCourant,s.idStock FROM `".$nomtableStock."` s

                    LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation

                    WHERE d.classe=0 ORDER BY s.idStock DESC";

                    $res2=mysql_query($sql2);

                    $montantPA=0;

                    $montantPU=0;

                    while ($stock = mysql_fetch_array($res2)) {

                        if($stock['quantiteStockCourant']!=null && $stock['quantiteStockCourant']!=0){

                            $montantPA=$montantPA + ($stock['quantiteStockCourant'] * $stock['prixSession']);

                            $montantPU=$montantPU + ($stock['quantiteStockCourant'] * $stock['prixPublic']);

                        }

                    }

                }

                else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

                    $sql2="SELECT s.quantiteStockCourant,s.nbreArticleUniteStock,i.prixuniteStock,d.prixuniteStock as ps,d.prixachat FROM `".$nomtableEntrepotStock."` s

                    LEFT JOIN `".$nomtableStock."` i ON i.idStock = s.idStock

                    LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation

                    WHERE d.classe=0 ORDER BY s.idEntrepotStock DESC";

                    $res2=mysql_query($sql2);

                    $montantPA=0;

                    $montantPU=0;

                    while ($stock = mysql_fetch_array($res2)) {

                    if($stock['quantiteStockCourant']!=null && $stock['quantiteStockCourant']!=0){

                        $quantite=$stock['quantiteStockCourant'] / $stock['nbreArticleUniteStock'];

                    }

                    else{

                        $quantite=0;

                    }

                    $montantPA=$montantPA + ($quantite * $stock['prixachat']);

                    $montantPU=$montantPU + ($quantite * $stock['ps']);

                    }

                }

                else{

                    $sql2="SELECT d.prix,s.quantiteStockCourant,s.idStock,d.prixachat FROM `".$nomtableStock."` s

                    LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation

                    WHERE d.classe=0 ORDER BY s.idStock DESC";

                    $res2=mysql_query($sql2);

                    $montantPA=0;

                    $montantPU=0;

                    while ($stock = mysql_fetch_array($res2)) {

                        if($stock['quantiteStockCourant']!=null && $stock['quantiteStockCourant']!=0){

                            $montantPA=$montantPA + ($stock['quantiteStockCourant'] * $stock['prixachat']);

                            $montantPU=$montantPU + ($stock['quantiteStockCourant'] * $stock['prix']);

                        }

                    }

                }

            /** Fin montant stock actuel */

        /**************************************** */

        /**************************************** */

            /**Debut Entrees */

                $som_EntreesPU=0;

                $som_EntreesPA=0;

                $sqlE="SELECT * FROM `".$nomtableStock."` s

                LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation

                WHERE d.classe=0 AND s.dateStockage BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY s.idStock DESC";

                $resE=mysql_query($sqlE);

                $produitsE=array();

                while($stockE=mysql_fetch_array($resE)){

                    if(in_array($stockE['idDesignation'], $produitsE)){

                        // echo "Existe.";

                    }

                    else{

                        if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

                            $sqlS="SELECT SUM(quantiteStockinitial)

                            FROM `".$nomtableStock."`

                            where idDesignation ='".$stockE['idDesignation']."' AND dateStockage BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ";

                            $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                            $S_stock = mysql_fetch_array($resS);



                            $som_EntreesPU=$som_EntreesPU + ($stockE['prixPublic'] * $S_stock[0]);

                            $som_EntreesPA=$som_EntreesPA + ($stockE['prixSession'] * $S_stock[0]);

                        }

                        else{

                            $sqlS="SELECT SUM(totalArticleStock)

                            FROM `".$nomtableStock."`

                            where idDesignation ='".$stockE['idDesignation']."' AND dateStockage BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ";

                            $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                            $S_stock = mysql_fetch_array($resS);



                            if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {



                                $sqlN="select * from `".$nomtableDesignation."` where idDesignation='".$stockE["idDesignation"]."' ";

                                $resN=mysql_query($sqlN);

                                $designation = mysql_fetch_array($resN);



                                $som_EntreesPU=$som_EntreesPU + ($stockE['prix'] * round(($S_stock[0] / $designation['nbreArticleUniteStock']),1));

                                $som_EntreesPA=$som_EntreesPA + ($stockE['prixachat'] * round(($S_stock[0] / $designation['nbreArticleUniteStock']),1));

                            }

                            else{

                                $som_EntreesPU=$som_EntreesPU + ($stockE['prix'] * $S_stock[0]);

                                $som_EntreesPA=$som_EntreesPA + ($stockE['prixachat'] * $S_stock[0]);

                            }

                        }

                        $produitsE[] = $stockE['idDesignation'];

                    }

                }

            /**Fin Entrees */

            /**Debut SortiesBon */

                $som_SortiesBon=0;

                $sqlSB="SELECT *

                FROM  `".$nomtableLigne."` l 

                INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

                WHERE l.classe = 0  && p.idClient !=0  && p.verrouiller=1 && (p.type=0 || p.type=30) && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY p.idPagnet DESC ";

                $resSB = mysql_query($sqlSB) or die ("persoonel requête 2".mysql_error());

                $resSB=mysql_query($sqlSB);

                $produitsSB=array();

                while($stockSB=mysql_fetch_array($resSB)){

                    if(in_array($stockSB['idDesignation'], $produitsSB)){

                        // echo "Existe.";

                    }

                    else{                    

                        $sqlT="SELECT SUM(prixtotal)

                        FROM `".$nomtableLigne."` l

                        INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation

                        INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

                        where p.idClient !=0 &&  p.verrouiller=1 && (p.type=0 || p.type=30) && d.idDesignation='".$stockSB["idDesignation"]."'  && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ";

                        $resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());

                        $prixTotal = mysql_fetch_array($resT);

                        $som_SortiesBon=$som_SortiesBon + $prixTotal[0];

                        $produitsSB[] = $stockSB['idDesignation'];

                    }

                }

            /**Fin SortiesBon */

            /**Debut SortiesRetire */           

                $sql1="SELECT *from `".$nomtableInventaire."` i

                INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = i.idDesignation

                WHERE i.TYPE=1 AND i.dateInventaire BETWEEN '".$debutAnnee."' AND '".$finAnnee."' order by i.idInventaire desc";

                $res1=mysql_query($sql1);

                $som_SortiesRetire=0;

                while ($inventaire = mysql_fetch_array($res1)) {

                    if($inventaire['quantite'] > $inventaire['quantiteStockCourant'] || $inventaire['quantite'] < $inventaire['quantiteStockCourant']){

                        if ($inventaire['quantite'] < $inventaire['quantiteStockCourant']) {

                            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")){

                                $som_SortiesRetire = $som_SortiesRetire + (($inventaire['quantiteStockCourant'] - $inventaire['quantite']) * $inventaire['prixPublic']);

                            }

                            else{

                                $som_SortiesRetire = $som_SortiesRetire + (($inventaire['quantiteStockCourant'] - $inventaire['quantite']) * $inventaire['prix']);  

                            }  

                        }

                    }

                }  

            /**Fin SortiesRetire */

            /**Debut FournisseursBons */

                $som_Fournisseurs=0;

                $sqlB="SELECT SUM(montantBl) FROM `".$nomtableBl."` 

                WHERE idFournisseur!=0 &&  dateBl BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ";

                $resB=mysql_query($sqlB) or die ("select stock impossible =>".mysql_error());

                $totalFB = mysql_fetch_array($resB);

                $som_Fournisseurs=$som_Fournisseurs + $totalFB[0];

            /**Fin FournisseursBons */

            /**Debut Remises Client*/

                $remiseTotalClient=0;

                $sqlRm="SELECT DISTINCT p.idPagnet

                    FROM `".$nomtablePagnet."` p

                    INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

                    WHERE (l.classe=0 || l.classe=1)  && p.idClient!=0  && p.verrouiller=1 && (p.type=0 || p.type=30)  && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ORDER BY p.idPagnet DESC";

                $resRm = mysql_query($sqlRm) or die ("persoonel requête 2".mysql_error());

                while ($pagnet = mysql_fetch_array($resRm)) {

                    $sqlS="SELECT SUM(remise)

                    FROM `".$nomtablePagnet."`

                    where verrouiller=1  && idClient!=0 && (type=0 || type=30) && idPagnet='".$pagnet['idPagnet']."'  && CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ORDER BY idPagnet DESC";

                    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                    $S_remises0 = mysql_fetch_array($resS);

                    $remiseTotalClient = $S_remises0[0] + $remiseTotalClient;

                }

            /**Fin Remises Vente*/

            /**Debut Benefice Vente*/

                if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 

                    $som_Benefice=0;

                }

                else{

                    $sqlBN="SELECT *

                    FROM `".$nomtableDesignation."` d

                    INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation

                    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

                    WHERE l.classe = 0 && p.verrouiller=1 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' && p.type=0 ";

                    $resBN = mysql_query($sqlBN) or die ("persoonel requête 2".mysql_error());

                    $T_prixAchat_US = 0;

                    $T_prixAchat_UN = 0;

                    $T_prixAchat_DM = 0;

                    $T_prixventes = 0;

                    while ($pagnet = mysql_fetch_array($resBN)) {

                        if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

                            if($_SESSION['adresseB']=='NEMA COMPLEMENTAIRE'){
                                $tab_Pagnet = explode("-", $pagnet['datepagej']);
                                $date_Pagnet = $tab_Pagnet[2].'-'.$tab_Pagnet[1].'-'.$tab_Pagnet[0];
        
                                $sqlPrixAchat="SELECT * from  `".$nomtableStock."` 
                                WHERE  '".$date_Pagnet."' >= dateStockage && idDesignation='".$pagnet['idDesignation']."' 
                                order by idStock desc limit 1";
                                $resPrixAchat=mysql_query($sqlPrixAchat);
                                $stock_PrixAchat=mysql_fetch_array($resPrixAchat);
    
                                if($stock_PrixAchat['prixachat']!=0 || $stock_PrixAchat['prixachat']!=null){
                                    $prixAcht=$stock_PrixAchat['prixachat'];
                                }
                                else{
                                    $prixAcht=$pagnet['prixachat'];
                                }

                                if ($pagnet['unitevente']==$pagnet['uniteStock']) {

                                    $T_prixAchat_US = $T_prixAchat_US + ($prixAcht * $pagnet['quantite']);
    
                                }
    
                                else if ($pagnet['unitevente']=="Demi Gros") {
    
                                    $T_prixAchat_DM = $T_prixAchat_DM + (($prixAcht / $pagnet['nbreArticleUniteStock']) * $pagnet['quantite']);
    
                                }
    
                                else{
    
                                    $T_prixAchat_UN = $T_prixAchat_UN + (($prixAcht / $pagnet['nbreArticleUniteStock']) * $pagnet['quantite']);
    
                                    
    
                                }
    
                                $T_prixventes=$T_prixventes + $pagnet['prixtotal'];
                            }
                            else{

                                if ($pagnet['unitevente']==$pagnet['uniteStock']) {

                                    $T_prixAchat_US = $T_prixAchat_US + ($pagnet['prixachat'] * $pagnet['quantite']);
    
                                }
    
                                else if ($pagnet['unitevente']=="Demi Gros") {
    
                                    $T_prixAchat_DM = $T_prixAchat_DM + (($pagnet['prixachat'] / $pagnet['nbreArticleUniteStock']) * $pagnet['quantite']);
    
                                }
    
                                else{
    
                                    $T_prixAchat_UN = $T_prixAchat_UN + (($pagnet['prixachat'] / $pagnet['nbreArticleUniteStock']) * $pagnet['quantite']);
    
                                    
    
                                }
    
                                $T_prixventes=$T_prixventes + $pagnet['prixtotal'];

                            }

                        }

                        else{

                            if (($pagnet['unitevente']!="Article")&&($pagnet['unitevente']!="article")) {

                                $T_prixAchat_US = $T_prixAchat_US + ($pagnet['prixachat'] * $pagnet['quantite'] * $pagnet['nbreArticleUniteStock']);

                            }

                            else if(($pagnet['unitevente']=="Article")||($pagnet['unitevente']=="article")){

                                $T_prixAchat_UN = $T_prixAchat_UN + ($pagnet['prixachat'] * $pagnet['quantite']);

                            }

                            $T_prixventes=$T_prixventes + $pagnet['prixtotal'];

                        }

                    }

                    $T_prixAchat=$T_prixAchat_US + $T_prixAchat_UN + $T_prixAchat_DM;

                    $som_Benefice=$T_prixventes - $T_prixAchat;

                }

            /**Fin Benefice Vente

        /**************************************** */

        /**************************************** */    
    
            $T_mutuelleVente = 0;

            $S_mutuelleVente = 0;

            $T_mutuelleB = 0;

            $S_mutuelleB = 0;

            if (($_SESSION['mutuelle']==1) && ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {   

                $sqlMB="SELECT DISTINCT m.idMutuellePagnet
    
                    FROM `".$nomtableMutuellePagnet."` m
    
                    INNER JOIN `".$nomtableLigne."` l ON l.idMutuellePagnet = m.idMutuellePagnet
    
                    WHERE (l.classe!=6 && l.classe!=9) && m.verrouiller=1 && (m.type=0 || m.type=30) && CONCAT(CONCAT(SUBSTR(m.datepagej,7, 10),'',SUBSTR(m.datepagej,3, 4)),'',SUBSTR(m.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY m.idMutuellePagnet DESC";
    
                $resMB = mysql_query($sqlMB) or die ("persoonel requête 2".mysql_error());
    
                while ($mutuelle = mysql_fetch_array($resMB)) {
    
                    $sqlS="SELECT SUM(apayerMutuelle)
    
                    FROM `".$nomtableMutuellePagnet."`
    
                    where  verrouiller=1 && idMutuellePagnet='".$mutuelle['idMutuellePagnet']."' && (type=0 || type=30)   ORDER BY idMutuellePagnet DESC";
    
                    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    
                    $S_mutuelleB = mysql_fetch_array($resS);
    
                    $T_mutuelleB = $S_mutuelleB[0] + $T_mutuelleB;
    
                }

                $sqlMV="SELECT DISTINCT m.idMutuellePagnet
    
                    FROM `".$nomtableMutuellePagnet."` m
    
                    INNER JOIN `".$nomtableLigne."` l ON l.idMutuellePagnet = m.idMutuellePagnet
    
                    WHERE (l.classe!=6 && l.classe!=9) && m.verrouiller=1 && (m.type=0 || m.type=30) && CONCAT(CONCAT(SUBSTR(m.datepagej,7, 10),'',SUBSTR(m.datepagej,3, 4)),'',SUBSTR(m.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY m.idMutuellePagnet DESC";
    
                $resMV = mysql_query($sqlMV) or die ("persoonel requête 2".mysql_error());
    
                while ($mutuelle = mysql_fetch_array($resMV)) {
    
                    $sqlS="SELECT SUM(apayerPagnet)
    
                    FROM `".$nomtableMutuellePagnet."`
    
                    where  verrouiller=1 && idMutuellePagnet='".$mutuelle['idMutuellePagnet']."' && (type=0 || type=30)   ORDER BY idMutuellePagnet DESC";
    
                    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    
                    $S_mutuelleVente = mysql_fetch_array($resS);
    
                    $T_mutuelleVente = $S_mutuelleVente[0] + $T_mutuelleVente;
    
                }
                
                // $montantARecouvrir = $montantARecouvrir + $T_mutuelleB;

            }

        /**************************************** */


        $totalEntrees= $som_Clients + $som_SortiesVente + $T_mutuelleVente + $som_Services + $T_App;

        $totalSorties= $som_Depenses + $som_FournisseursV + $som_ClientsBE + $remiseTotalVente + $T_RetraitC;

        $restantCaisse= $totalEntrees - $totalSorties;

        $patrimoine = $montantPU + $montantARecouvrir + $restantCaisse + $T_mutuelleB;

        $totalFournisseur=$som_Fournisseurs - $som_FournisseursV;


        $result=number_format($totalEntrees, 0, ',', ' ').'<>'.number_format($som_Clients, 0, ',', ' ').'<>'.number_format($som_SortiesVente, 0, ',', ' ')
        
        .'<>'.number_format($som_Services, 0, ',', ' ').'<>'.number_format($som_SortiesRetournes, 0, ',', ' ')

        .'<>'.number_format($totalSorties, 0, ',', ' ').'<>'.number_format($som_Depenses, 0, ',', ' ').'<>'.number_format($som_FournisseursV, 0, ',', ' ')
        
        .'<>'.number_format($som_ClientsBE, 0, ',', ' ').'<>'.number_format($remiseTotalVente, 0, ',', ' ')

        .'<>'.number_format($restantCaisse, 0, ',', ' ')

        .'<>'.number_format($montantARecouvrir, 0, ',', ' ')

        .'<>'.number_format($montantPA, 0, ',', ' ').'<>'.number_format($montantPU, 0, ',', ' ')

        .'<>'.number_format($patrimoine, 0, ',', ' ')

        .'<>'.number_format($totalFournisseur, 0, ',', ' ').'<>'.number_format($som_SortiesRetire, 0, ',', ' ')

        .'<>'.number_format($som_EntreesPA, 0, ',', ' ').'<>'.number_format($som_EntreesPU, 0, ',', ' ')

        .'<>'.number_format($som_SortiesVente, 0, ',', ' ').'<>'.number_format($som_SortiesBon, 0, ',', ' ')

        .'<>'.number_format($som_Fournisseurs, 0, ',', ' ').'<>'.number_format($remiseTotalClient, 0, ',', ' ').'<>'.number_format($som_Benefice, 0, ',', ' ')
        
        .'<>'.number_format($T_App, 0, ',', ' ').'<>'.number_format($T_mutuelleB, 0, ',', ' ').'<>'.number_format($montantARecouvrir+$T_mutuelleB, 0, ',', ' ')
        
        .'<>'.number_format($T_mutuelleVente, 0, ',', ' ').'<>'.number_format($T_RetraitC, 0, ',', ' ');

        exit($result);

    }

    else if($operation == 67){

        $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$idDesignation."'";

        $res1=mysql_query($sql1);

        $designation=mysql_fetch_array($res1);

        /**Debut Entrees */

            $som_EntreesPU=0;

            $som_EntreesPA=0;

            $sqlE="SELECT * FROM `".$nomtableStock."` s

            LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation

            WHERE d.classe=0 AND d.idDesignation='".$idDesignation."' AND s.dateStockage BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY s.idStock DESC";

            $resE=mysql_query($sqlE);

            $produitsE=array();

            while($stockE=mysql_fetch_array($resE)){

                if(in_array($stockE['idDesignation'], $produitsE)){

                    // echo "Existe.";

                }

                else{

                    if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

                        $sqlS="SELECT SUM(quantiteStockinitial)

                        FROM `".$nomtableStock."`

                        where idDesignation ='".$stockE['idDesignation']."' AND dateStockage BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ";

                        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                        $S_stock = mysql_fetch_array($resS);



                        $som_EntreesPU=$som_EntreesPU + ($stockE['prixPublic'] * $S_stock[0]);

                        $som_EntreesPA=$som_EntreesPA + ($stockE['prixSession'] * $S_stock[0]);

                    }

                    else{

                        $sqlS="SELECT SUM(totalArticleStock)

                        FROM `".$nomtableStock."`

                        where idDesignation ='".$stockE['idDesignation']."' AND dateStockage BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ";

                        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                        $S_stock = mysql_fetch_array($resS);



                        if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

                            $som_EntreesPU=$som_EntreesPU + ($stockE['prix'] * round(($S_stock[0] / $designation['nbreArticleUniteStock']),1));

                            $som_EntreesPA=$som_EntreesPA + ($stockE['prixachat'] * round(($S_stock[0] / $designation['nbreArticleUniteStock']),1));

                                }

                        else{

                            $som_EntreesPU=$som_EntreesPU + ($stockE['prix'] * $S_stock[0]);

                            $som_EntreesPA=$som_EntreesPA + ($stockE['prixachat'] * $S_stock[0]);

                        }

                    }

                    $produitsE[] = $stockE['idDesignation'];

                }

            }

        /**Fin Entrees */

        /**Debut Sorties */

            $som_Sorties=0;

            $sqlS="SELECT *

            FROM  `".$nomtableLigne."` l

            INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

            WHERE l.classe = 0 && l.idDesignation='".$idDesignation."' &&  p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY p.idPagnet DESC ";

            $resS = mysql_query($sqlS) or die ("persoonel requête 2".mysql_error());

            $resS=mysql_query($sqlS);

            $produitsS=array();

            while($stockS=mysql_fetch_array($resS)){

                if(in_array($stockS['idDesignation'], $produitsS)){

                    // echo "Existe.";

                }

                else{

                    $sqlT="SELECT SUM(prixtotal)

                    FROM `".$nomtableLigne."` l

                    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation

                    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

                    where p.idClient=0 &&  p.verrouiller=1 && p.type=0 && d.idDesignation='".$stockS["idDesignation"]."'  && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ";

                    $resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());

                    $prixTotal = mysql_fetch_array($resT);

                    $som_Sorties=$som_Sorties + $prixTotal[0];

                    $produitsS[] = $stockS['idDesignation'];

                }

            }

        /**Fin Sorties */

        /**Debut SortiesBon */

           $som_SortiesBon=0;

           $sqlSB="SELECT *

           FROM  `".$nomtableLigne."` l

           INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

           WHERE l.classe = 0 && p.idClient !=0 && l.idDesignation='".$idDesignation."' && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY p.idPagnet DESC ";

           $resSB = mysql_query($sqlSB) or die ("persoonel requête 2".mysql_error());

           $resSB=mysql_query($sqlSB);

           $produitsSB=array();

           while($stockSB=mysql_fetch_array($resSB)){

             if(in_array($stockSB['idDesignation'], $produitsSB)){

             // echo "Existe.";

            }

            else{

             $sqlT="SELECT SUM(prixtotal)

             FROM `".$nomtableLigne."` l

             INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation

             INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

             where p.idClient !=0 && l.classe=0 && p.verrouiller=1 && p.type=0 && d.idDesignation='".$stockSB["idDesignation"]."'  && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ";

             $resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());

             $prixTotal = mysql_fetch_array($resT);

             $som_SortiesBon=$som_SortiesBon + $prixTotal[0];

             $produitsSB[] = $stockSB['idDesignation'];

            }

          }

        /**Fin SortiesBon */

        /**Debut Services */

            $som_Services=0;

            $sqlSV="SELECT *

            FROM  `".$nomtableLigne."` l

            INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

            WHERE l.classe = 1  AND l.idDesignation='".$idDesignation."'  && p.idClient=0  && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY p.idPagnet DESC ";

            $resSV = mysql_query($sqlSV) or die ("persoonel requête 2".mysql_error());

            $resSV=mysql_query($sqlSV);

            $produitsSV=array();

            while($stockSV=mysql_fetch_array($resSV)){

                if(in_array($stockSV['idDesignation'], $produitsSV)){

                    // echo "Existe.";

                }

                else{

                    $sqlT="SELECT SUM(prixtotal)

                    FROM `".$nomtableLigne."` l

                    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation

                    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

                    where p.idClient=0 &&  p.verrouiller=1 && p.type=0 && d.idDesignation='".$stockSV["idDesignation"]."'  && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ";

                    $resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());

                    $prixTotal = mysql_fetch_array($resT);

                    $som_Services=$som_Services + $prixTotal[0];

                    $produitsSV[] = $stockSV['idDesignation'];

                }

            }

        /**Fin Services */

        /**Debut Depenses */

            $som_Depenses=0;

            $sqlD="SELECT *

            FROM  `".$nomtableLigne."` l

            INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

            WHERE l.classe = 2  AND l.idDesignation='".$idDesignation."' && p.idClient=0  && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ORDER BY p.idPagnet DESC ";

            $resD = mysql_query($sqlD) or die ("persoonel requête 2".mysql_error());

            $resD=mysql_query($sqlD);

            $produitsD=array();

            while($stockD=mysql_fetch_array($resD)){

                if(in_array($stockD['idDesignation'], $produitsD)){

                    // echo "Existe.";

                }

                else{

                    $sqlT="SELECT SUM(prixtotal)

                    FROM `".$nomtableLigne."` l

                    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation

                    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

                    where p.idClient=0 &&  p.verrouiller=1 && p.type=0 && d.idDesignation='".$stockD["idDesignation"]."'  && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ";

                    $resT=mysql_query($sqlT) or die ("select stock impossible =>".mysql_error());

                    $prixTotal = mysql_fetch_array($resT);

                    $som_Depenses=$som_Depenses + $prixTotal[0];

                    $produitsD[] = $stockD['idDesignation'];

                }

            }

        /**Fin Depenses */

        $result=strtoupper($designation['designation']).'<>'.number_format($som_EntreesPA, 0, ',', ' ').'<>'.number_format($som_EntreesPU, 0, ',', ' ').'<>'.number_format($som_Sorties, 0, ',', ' ').'<>'.number_format($som_SortiesBon, 0, ',', ' ').'<>'.number_format($som_Services, 0, ',', ' ').'<>'.number_format($som_Depenses, 0, ',', ' ');

        exit($result);

    }

    else if($operation == 68){

        $sql1="SELECT * FROM `". $nomtableClient."` where idClient='".$idClient."'";

        $res1=mysql_query($sql1);

        $client=mysql_fetch_array($res1);

        /**Debut Clients */

            $som_Clients=0;

            $sqlC="SELECT *,CONCAT(CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)),'',heurePagnet) AS dateHeure

            FROM

            (SELECT p.idClient,p.idPagnet,p.datepagej,p.heurePagnet FROM `".$nomtablePagnet."` p where p.idClient!=0 AND p.verrouiller=1 AND CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'

            UNION

            SELECT v.idClient,v.idVersement,v.dateVersement,v.heureVersement FROM `".$nomtableVersement."` v where v.idClient!=0 AND CONCAT(CONCAT(SUBSTR(v.dateVersement,7, 10),'',SUBSTR(v.dateVersement,3, 4)),'',SUBSTR(v.dateVersement,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'

            ) AS a ORDER BY dateHeure DESC";

            $resC=mysql_query($sqlC);

            $clients=array();

            while($bon=mysql_fetch_assoc($resC)){

                if(in_array($bon['idClient'], $clients)){

                    // echo "Existe.";

                }

                else{

                    $sqlB="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."`

                    WHERE idClient='".$bon["idClient"]."' &&  verrouiller=1 && type=0 && CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ";

                    $resB=mysql_query($sqlB) or die ("select stock impossible =>".mysql_error());

                    $totalB = mysql_fetch_array($resB);



                    $sqlR="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."`

                    WHERE idClient='".$bon["idClient"]."' &&  verrouiller=1 && type=1 && CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ";

                    $resR=mysql_query($sqlR) or die ("select stock impossible =>".mysql_error());

                    $totalR = mysql_fetch_array($resR);



                    $sqlV="SELECT SUM(montant) FROM `".$nomtableVersement."`

                    WHERE idClient='".$bon["idClient"]."' && CONCAT(CONCAT(SUBSTR(dateVersement,7, 10),'',SUBSTR(dateVersement,3, 4)),'',SUBSTR(dateVersement,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ";

                    $resV=mysql_query($sqlV) or die ("select stock impossible =>".mysql_error());

                    $totalV = mysql_fetch_array($resV);



                    $som_Clients=$som_Clients + ($totalB[0] - $totalR[0] - $totalV[0]);

                    $clients[] = $bon['idClient'];

                }

            }

        /**Fin Clients */

        $result=strtoupper($client['prenom']).' '.strtoupper($client['nom']);

        exit($result);

    }

    else if($operation == 69){

        $sql1="SELECT * FROM `". $nomtableFournisseur."` where idFournisseur='".$idFournisseur."'";

        $res1=mysql_query($sql1);

        $fournisseur=mysql_fetch_array($res1);

        /**Debut Fournisseurs */

            $som_Fournisseurs=0;

            $sqlF="SELECT *,CONCAT(dateBl,'',heureBl) AS dateHeure

            FROM

            (SELECT b.idFournisseur,b.idBl,b.dateBl,b.heureBl FROM `".$nomtableBl."` b where  b.idFournisseur!=0 AND b.dateBl BETWEEN '".$debutAnnee."' AND '".$finAnnee."'

            UNION

            SELECT v.idFournisseur,v.idVersement,v.dateVersement,v.heureVersement FROM `".$nomtableVersement."` v where v.idFournisseur!=0 AND v.dateVersement BETWEEN '".$debutAnnee."' AND '".$finAnnee."'

            ) AS a ORDER BY dateHeure DESC ";

            $resF = mysql_query($sqlF) or die ("persoonel requête 2".mysql_error());

            $fournisseurs=array();

            while($bon=mysql_fetch_assoc($resF)){

                if(in_array($bon['idFournisseur'], $fournisseurs)){

                    // echo "Existe.";

                }

                else{

                    $sqlB="SELECT SUM(montantBl) FROM `".$nomtableBl."`

                    WHERE idFournisseur='".$bon["idFournisseur"]."' &&  dateBl BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ";

                    $resB=mysql_query($sqlB) or die ("select stock impossible =>".mysql_error());

                    $totalB = mysql_fetch_array($resB);



                    $sqlV="SELECT SUM(montant) FROM `".$nomtableVersement."`

                    WHERE idFournisseur='".$bon["idFournisseur"]."' && dateVersement BETWEEN '".$debutAnnee."' AND '".$finAnnee."'  ";

                    $resV=mysql_query($sqlV) or die ("select stock impossible =>".mysql_error());

                    $totalV = mysql_fetch_array($resV);



                    $som_Fournisseurs=$som_Fournisseurs + ($totalB[0] - $totalV[0]);

                    $fournisseurs[] = $bon['idFournisseur'];

                }

            }

        /**Fin Fournisseurs */

        $result=strtoupper($fournisseur['nomFournisseur']);

        exit($result);

    }

    else if($operation == 70){

        $tauxVente=0.25;

        $sql1='INSERT INTO `'.$nomtableVoyage.'` (destination,motif,dateVoyage,tauxRevient,tauxVente)

        VALUES("'.mysql_real_escape_string($destination).'","'.$motif.'","'.$dateVoyage.'",0,"'.$tauxVente.'")';

        $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

        if($res1){

            $result='1<>'.$destination.'<>'.$motif.'<>'.$dateVoyage;

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 71){

        $sql="SELECT * FROM `". $nomtableVoyage."` where idVoyage='".$idVoyage."'";

        $res=mysql_query($sql);

        if($res){

            $voyage=mysql_fetch_array($res);

            $result='1<>'.$voyage['idVoyage'].'<>'.$voyage['destination'].'<>'.$voyage['motif'].'<>'.$voyage['dateVoyage'];

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 72){

        $sql="SELECT * FROM `". $nomtableVoyage."` where idVoyage='".$idVoyage."'";

        $res=mysql_query($sql);

        if($res){

            $sql1="UPDATE `".$nomtableVoyage."` set destination='".$destination."',motif='".$motif."',dateVoyage='".$dateVoyage."' where idVoyage='".$idVoyage."'";

            $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

            if($res1){

                $result='1<>'.$idVoyage.'<>'.$destination.'<>'.$motif.'<>'.$dateVoyage;

            }

            else{

                $result="0";

            }

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 73){

        $sql="SELECT * FROM `". $nomtableVoyage."` where idVoyage='".$idVoyage."'";

        $res=mysql_query($sql);

        if($res){

            $sql1="DELETE FROM `".$nomtableVoyage."` WHERE idVoyage='".$idVoyage."'";

            $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

            if($res1){

                $result='1';

            }

            else{

                $result="0";

            }

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 74){

        $result='0';
        

        $prixUnitaire=@htmlspecialchars($_POST["prixUnitaire"]);

        $nbArticleUniteStock=@htmlspecialchars($_POST["nbArticleUniteStock"]);

        if ($designation!='' && $categorie!='' && $uniteStock!='' && $prixUniteStock!='' && $prixAchat!=''){



            $sql="UPDATE `".$nomtableDesignation."` set designation='".mysql_real_escape_string($designation)."',categorie='".mysql_real_escape_string($categorie)."',classe=0,uniteStock='".mysql_real_escape_string($uniteStock)."',prixuniteStock='".$prixUniteStock."',prixachat='".$prixAchat."',prix='".$prixUnitaire."',nbreArticleUniteStock='".$nbArticleUniteStock."' where idDesignation=".$idDesignation;

            $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());



            $sql2="UPDATE `".$nomtableStock."` set designation='".mysql_real_escape_string($designation)."' where idDesignation=".$idDesignation;

            //echo $sql2;

            $res2=@mysql_query($sql2)or die ("modification reference dans stock ".mysql_error());



            $sql3="UPDATE `".$nomtableEntrepotStock."` set designation='".mysql_real_escape_string($designation)."' where idDesignation=".$idDesignation;

            //echo $sql2;

            $res3=@mysql_query($sql3)or die ("modification reference dans stock ".mysql_error());



            $sql4="UPDATE `".$nomtableLigne."` set designation='".mysql_real_escape_string($designation)."' where idDesignation=".$idDesignation;

            //echo $sql2;

            $res4=@mysql_query($sql4)or die ("modification reference dans stock ".mysql_error());



            $sql3="SELECT * from  `".$nomtableDesignation."` where idDesignation=".$idDesignation;

            $res3=mysql_query($sql3);

            $design=mysql_fetch_array($res3);



            $result='1<>'.$design['designation'].'<>'.$design['categorie'].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'];



        }



       exit($result);





    }

    else if($operation == 75){

        $sql="SELECT * FROM `". $nomtableStock."` where idStock='".$idStock."'";

        $res=mysql_query($sql);

        $result="0";

        if($res){

            $stock=mysql_fetch_array($res);

            $sql3="SELECT * from  `".$nomtableDesignation."` where idDesignation=".$stock['idDesignation'];

            $res3=mysql_query($sql3);

            $design=mysql_fetch_array($res3);

            if($stock['quantiteStockCourant']!=0 && $stock['quantiteStockCourant']!=null){

                $result=$stock['designation'].'<>'.$stock['quantiteStockinitial'].'<>'.$stock['quantiteStockCourant'].'<>'.$stock['uniteStock'].'<>'.$stock['dateExpiration'].'<>'.$stock['dateStockage'].'<>'.$design['prix'].'<>'.$design['codeBarreuniteStock'].'<>'.$stock['idDesignation'];

            }

            else{

                $result=$stock['designation'].'<>'.$stock['quantiteStockinitial'].'<>0<>'.$stock['uniteStock'].'<>'.$stock['dateExpiration'].'<>'.$stock['dateStockage'].'<>'.$design['prix'].'<>'.$design['codeBarreuniteStock'].'<>'.$stock['idDesignation'];

            }



        }

        exit($result);

    }

    else if($operation == 76){

        $result="0";

        $sql="SELECT * FROM `". $nomtableStock."` where idStock='".$idStock."'";

        $res=mysql_query($sql);

        if($res){

            $stock=mysql_fetch_array($res);

            if($stock['quantiteStockCourant']>=$quantite){

                $totalArticleStock=$stock['quantiteStockCourant'] - $quantite;

                $sql1="UPDATE `".$nomtableStock."` set quantiteStockCourant='".$totalArticleStock."'  where idStock='".$idStock."'";

                $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

                if($res1){

                    $result='1<>'.$stock['dateStockage'].'<>'.$stock['quantiteStockinitial'].'<>'.$totalArticleStock.'<>'.$stock['uniteStock'].'<>'.$stock['nbreArticleUniteStock'].'<>'.$stock['prixuniteStock'].'<>'.$stock['prixunitaire'].'<>'.$stock['dateExpiration'];

                    $sql4='INSERT INTO `'.$nomtableInventaire.'` (idStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)

                    VALUES('.$stock['idStock'].','.$stock['idDesignation'].','.$totalArticleStock.',1,'.$stock['quantiteStockCourant'].',"'.$dateString.'",1)';

                    $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;

                }

            }

            else{

                $result="0";

            }

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 77){

        $result="0";

        $sql="SELECT * FROM `". $nomtableStock."` where idStock='".$idStock."'";

        $res=mysql_query($sql);

        if($res){

            $stock=mysql_fetch_array($res);

            if($stock['quantiteStockCourant']>=$quantite){

                $totalArticleStock=$stock['quantiteStockCourant'] - $quantite;

                $sql1="UPDATE `".$nomtableStock."` set quantiteStockCourant='".$totalArticleStock."'  where idStock='".$idStock."'";

                $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

                if($res1){

                    $result='1<>'.$stock['dateStockage'].'<>'.$stock['quantiteStockinitial'].'<>'.$totalArticleStock.'<>'.$stock['uniteStock'].'<>'.$stock['nbreArticleUniteStock'].'<>'.$stock['prixuniteStock'].'<>'.$stock['prixunitaire'].'<>'.$stock['dateExpiration'];

                    $sql4='INSERT INTO `'.$nomtableInventaire.'` (idStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)

                    VALUES('.$stock['idStock'].','.$stock['idDesignation'].','.$totalArticleStock.',1,'.$stock['quantiteStockCourant'].',"'.$dateString.'",3)';

                    $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;

                }

            }

            else{

                $result="0";

            }

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 78){



        $result="0";



        if ($_SESSION['gestionnaire']==1){

            $sql0="select * from `aaa-utilisateur` where idutilisateur='".$_SESSION['iduser']."' ";

            $res0=mysql_query($sql0);

            $depot=mysql_fetch_array($res0);



            $sql='select * from `'.$nomtableDesignation.'` where idDesignation="'.$idDesignation.'"';

            //echo $sql;

            $res=mysql_query($sql);

            if(mysql_num_rows($res)){

                if($design=mysql_fetch_array($res)) {



                    $sql1='INSERT INTO `'.$nomtableEntrepotTransfert.'`(idEntrepot,idDesignation,designation,quantiteInitiale,quantiteFinale,quantite,dateTransfert,etat1,etat2,iduser)

                    VALUES("'.$idEntrepot.'",'.$idDesignation.',"'.mysql_real_escape_string($design['designation']).'",'.$quantite.','.$quantite.','.$quantite.',"'.$dateString.'",1,1,"'.$_SESSION['iduser'].'")';

                    $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;



                    if($res1){

                        $sql3='SELECT * from  `'.$nomtableEntrepot.'` WHERE idEntrepot="'.$idEntrepot.'" ';

                        $res3=mysql_query($sql3);

                        $entrepot=mysql_fetch_array($res3);



                        $result='1<>'.strtoupper($entrepot['nomEntrepot']).'<>'.strtoupper($design['designation']).'<>'.strtoupper($design['uniteStock']).'<>'.$dateString.'<>'.strtoupper($depot['nom']);

                    }

                    else{

                        $result="0";

                    }



               }

            }

        }

        else{

            $sql0="select * from `aaa-utilisateur` where idutilisateur='".$_SESSION['iduser']."' ";

            $res0=mysql_query($sql0);

            $depot=mysql_fetch_array($res0);



            $sql='select * from `'.$nomtableDesignation.'` where idDesignation="'.$idDesignation.'"';

            //echo $sql;

            $res=mysql_query($sql);

            if(mysql_num_rows($res)){

                if($design=mysql_fetch_array($res)) {



                    $sql1='INSERT INTO `'.$nomtableEntrepotTransfert.'`(idEntrepot,idDesignation,designation,quantiteInitiale,quantiteFinale,quantite,dateTransfert,etat1,etat2,iduser)

                    VALUES("'.$depot['idEntrepot'].'",'.$idDesignation.',"'.mysql_real_escape_string($design['designation']).'",'.$quantite.','.$quantite.','.$quantite.',"'.$dateString.'",0,0,"'.$_SESSION['iduser'].'")';

                    $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;



                    if($res1){

                        $sql3='SELECT * from  `'.$nomtableEntrepotTransfert.'` WHERE idEntrepot="'.$depot['idEntrepot'].'" AND idDesignation="'.$idDesignation.'" order by idEntrepotTransfert DESC';

                        $res3=mysql_query($sql3);

                        $commande=mysql_fetch_array($res3);



                        $result='1<>'.strtoupper($commande['designation']).'<>'.strtoupper($design['categorie']).'<>'.strtoupper($design['uniteStock']).'<>'.$commande['dateTransfert'].'<>'.strtoupper($depot['nom']);

                    }

                    else{

                        $result="0";

                    }



               }

            }

        }



       exit($result);





    }

    else if($operation == 79){

        $sql="SELECT * FROM `". $nomtableEntrepotTransfert."` where idEntrepotTransfert='".$idEntrepotTransfert."'";

        $res=mysql_query($sql);

        if($res){

            $transfert=mysql_fetch_array($res);

            $sql1='select * from `'.$nomtableDesignation.'` where idDesignation="'.$transfert['idDesignation'].'"';

            $res1=mysql_query($sql1);

            $design=mysql_fetch_array($res1);

            $result='1<>'.$transfert['designation'].'<>'.$design['uniteStock'].'<>'.$transfert['quantite'];

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 80){

        $result="0";

        $sql="SELECT * FROM `". $nomtableEntrepotTransfert."` where idEntrepotTransfert='".$idEntrepotTransfert."'";

        $res=mysql_query($sql);

        if($res){

            $transfert=mysql_fetch_array($res);

            $sql0="select * from `aaa-utilisateur` where idutilisateur='".$transfert['iduser']."' ";

            $res0=mysql_query($sql0);

            $user=mysql_fetch_array($res0);



            $sqlE="SELECT SUM(quantiteStockinitial)

            FROM `".$nomtableEntrepotStock."`

            where  idEntrepotTransfert ='".$idEntrepotTransfert."'  ";

            $resE=mysql_query($sqlE) or die ("select stock impossible =>".mysql_error());

            $E_stock = mysql_fetch_array($resE);

            if($E_stock[0]!=0 && $E_stock[0]!=null){

                if($quantite >= $E_stock[0]){

                    if($_SESSION['iduser']==$transfert['iduser']){

                        $sql1="UPDATE `".$nomtableEntrepotTransfert."` set quantiteInitiale='".$quantite."',quantiteFinale='".$quantite."',quantite='".$quantite."' where idEntrepotTransfert='".$idEntrepotTransfert."'";

                        $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

                    }

                    else{

                        $sql1="UPDATE `".$nomtableEntrepotTransfert."` set quantiteFinale='".$quantite."',quantite='".$quantite."' where idEntrepotTransfert='".$idEntrepotTransfert."'";

                        $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

                    }



                    if($res1){

                        $sql2='select * from `'.$nomtableDesignation.'` where idDesignation="'.$transfert['idDesignation'].'"';

                        $res2=mysql_query($sql2);

                        $design=mysql_fetch_array($res2);

                        if($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1){

                            $sql2="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$transfert['idEntrepot']."' ";

                            $res2=mysql_query($sql2);

                            $depot=mysql_fetch_array($res2);

                            $result='1<>'.strtoupper($depot['nomEntrepot']).'<>'.strtoupper($design['designation']).'<>'.strtoupper($design['uniteStock']).'<>'.$transfert['dateTransfert'].'<>'.strtoupper($user['nom']);

                        }

                        else{

                            $result='1<>'.strtoupper($transfert['designation']).'<>'.strtoupper($design['categorie']).'<>'.strtoupper($design['uniteStock']).'<>'.$transfert['dateTransfert'].'<>'.strtoupper($user['nom']);

                        }

                    }

                }

                else{

                    $result="0";

                }

            }

            else{

                if($_SESSION['iduser']==$transfert['iduser']){

                    $sql1="UPDATE `".$nomtableEntrepotTransfert."` set quantiteInitiale='".$quantite."',quantiteFinale='".$quantite."',quantite='".$quantite."' where idEntrepotTransfert='".$idEntrepotTransfert."'";

                    $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

                }

                else{

                    $sql1="UPDATE `".$nomtableEntrepotTransfert."` set quantiteFinale='".$quantite."',quantite='".$quantite."' where idEntrepotTransfert='".$idEntrepotTransfert."'";

                    $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

                }

                if($res1){

                    $sql2='select * from `'.$nomtableDesignation.'` where idDesignation="'.$transfert['idDesignation'].'"';

                    $res2=mysql_query($sql2);

                    $design=mysql_fetch_array($res2);

                    if($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1){

                        $sql2="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$transfert['idEntrepot']."' ";

                        $res2=mysql_query($sql2);

                        $depot=mysql_fetch_array($res2);

                        $result='1<>'.strtoupper($depot['nomEntrepot']).'<>'.strtoupper($design['designation']).'<>'.strtoupper($design['uniteStock']).'<>'.$transfert['dateTransfert'].'<>'.strtoupper($user['nom']);

                    }

                    else{

                        $result='1<>'.strtoupper($transfert['designation']).'<>'.strtoupper($design['categorie']).'<>'.strtoupper($design['uniteStock']).'<>'.$transfert['dateTransfert'].'<>'.strtoupper($user['nom']);

                    }

                }

            }



        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 81){

        $result="0";

        $sql="SELECT * FROM `". $nomtableEntrepotTransfert."` where idEntrepotTransfert='".$idEntrepotTransfert."'";

        $res=mysql_query($sql);

        if($res){

            $transfert=mysql_fetch_array($res);

            $sql0="select * from `aaa-utilisateur` where idutilisateur='".$transfert['iduser']."' ";

            $res0=mysql_query($sql0);

            $user=mysql_fetch_array($res0);

           // if($stock['quantiteStockinitial']>=$quantite){

                $sql1="DELETE FROM `".$nomtableEntrepotTransfert."`  where idEntrepotTransfert='".$idEntrepotTransfert."'";

                $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

                if($res1){

                    $sql2='select * from `'.$nomtableDesignation.'` where idDesignation="'.$transfert['idDesignation'].'"';

                    $res2=mysql_query($sql2);

                    $design=mysql_fetch_array($res2);

                    if($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1){

                        $sql2="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$transfert['idEntrepot']."' ";

                        $res2=mysql_query($sql2);

                        $depot=mysql_fetch_array($res2);

                        $result='1<>'.strtoupper($depot['nomEntrepot']).'<>'.strtoupper($design['designation']).'<>'.strtoupper($design['uniteStock']).'<>'.$transfert['dateTransfert'].'<>'.strtoupper($user['nom']);

                    }

                    else{

                        $result='1<>'.strtoupper($transfert['designation']).'<>'.strtoupper($design['categorie']).'<>'.strtoupper($design['uniteStock']).'<>'.$transfert['dateTransfert'].'<>'.strtoupper($user['nom']);

                    }

                }

                /*

            }

            else{

                $result="0";

            }

            */

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 82){

        $sql3='SELECT * from  `'.$nomtableEntrepotStock.'` where idEntrepotStock="'.$idEntrepotStock.'" ';

        $res3=mysql_query($sql3);

        $stock=mysql_fetch_array($res3);

        if($stock!=null){

            $initial=0;

            $sqlE="SELECT SUM(quantiteStockinitial) FROM `".$nomtableEntrepotStock."` where  idEntrepotTransfert ='".$idEntrepotTransfert."'  ";

            $resE=mysql_query($sqlE) or die ("select stock impossible =>".mysql_error());

            $E_stock = mysql_fetch_array($resE);

            if($E_stock[0]!=null){

              $initial=$E_stock[0];

            }

            $total=$initial + $quantite;

            $sql0="SELECT * FROM `".$nomtableEntrepotTransfert."`  where idEntrepotTransfert='".$idEntrepotTransfert."' ";

            $res0=mysql_query($sql0);

            $transfert=mysql_fetch_array($res0);

            if($transfert['quantite']>=$total){

                $reste=$stock['quantiteStockCourant'] - ($quantite*$stock['nbreArticleUniteStock']);

                if($reste>=0){

                    $result="1";

                    $sql1="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant='".$reste."' where idEntrepotStock='".$idEntrepotStock."'";

                    $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

                    if($res1){

                        $totalArticleStock=$quantite*$stock['nbreArticleUniteStock'];

                        $sql1='INSERT INTO `'.$nomtableEntrepotStock.'` (idStock,idEntrepot,idEntrepotTransfert,idDesignation,idTransfert,designation,quantiteStockinitial,uniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant)

                        VALUES('.$stock['idStock'].','.$transfert['idEntrepot'].','.$idEntrepotTransfert.','.$stock['idDesignation'].','.$idEntrepotStock.',"'.mysql_real_escape_string($stock["designation"]).'",'.$quantite.',"'.mysql_real_escape_string($stock['uniteStock']).'",'.$stock['nbreArticleUniteStock'].','.$totalArticleStock.',"'.$dateString.'",'.$totalArticleStock.')';

                        $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

                        if($res1){

                            $result='1<>'.$transfert['quantite'].'<>'.($total);

                        }

                        else{

                            $result="0";

                        }

                    }

                }

                else{

                    $result="0";

                }

            }

            else{

                $result="0";

            }

        }

        else{

            $result="0";

        }

        exit($result);

    }
else if($operation == 83){
    if ($_SESSION['tampon'] == 1  && $_SESSION['page']=='vente') {
			// var_dump($_SESSION['nomB']);

			/******* Début déplacement tampon vers standard*******/
			$sql="SELECT * FROM `".$nomtablePagnetTampon."` where synchronise=0 and verrouiller=1";
				
			// var_dump($sql);
			$statement = $bdd->prepare($sql);
			$statement->execute();
			$tamponsP = $statement->fetchAll(PDO::FETCH_ASSOC); 

			$bdd->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

			try {
				// From this point and until the transaction is being committed every change to the database can be reverted
				$bdd->beginTransaction();  /**** generate barcode ****/

				foreach ($tamponsP as $tp) {
					// var_dump($tp);

					$sqlL="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet = ".$tp['idPagnet'];
				
			        // var_dump($sqlL);
					$statementL = $bdd->prepare($sqlL);
					$statementL->execute();
					$tamponsL = $statementL->fetchAll(PDO::FETCH_ASSOC); 

					$req4 = $bdd->prepare("INSERT INTO `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,apayerPagnet,verrouiller,idClient,remise,restourne,versement,idCompte,avance,idCompteAvance,idPagnetTampon,tampon)
					values (:d,:h,:u,:t,:tp,:ap,:v,:c,:r,:res,:ver,:idC,:av,:idcav,:pt,:tam)");
				    // var_dump($req4);
					$req4->execute(array(
						'd' => $tp['datepagej'],
						'h' => $tp['heurePagnet'],
						'u' => $tp['iduser'],
						't' => $tp['type'],
						'tp' => $tp['totalp'],
						'ap' => $tp['apayerPagnet'],
						'v' => 1,
						'c' => $tp['idClient'],
						'r' => $tp['remise'],
						'res' => $tp['restourne'],
						'ver' => $tp['versement'],
						'idC' => $tp['idCompte'],
						'av' => $tp['avance'],
						'idcav' => $tp['idCompteAvance'],
						'pt' => $tp['idPagnet'],
						'tam' => 1
					))  or die(print_r("Insert pagnet 2 ".$req4->errorInfo()));
					$req4->closeCursor();
					$idPagnet = $bdd->lastInsertId();
					// var_dump($idPagnet);

					foreach ($tamponsL as $tl) {
						// var_dump($tl);
						$preparedStatement = $bdd->prepare(
							"insert into `".$nomtableLigne."` (designation, idDesignation, unitevente, prixunitevente, quantite, prixtotal, idPagnet, numligneTampon, classe)
                            values (:d,:idd,:uv,:pu,:qty,:p,:idp,:lt,:c)"
						);
			        // var_dump($preparedStatement);
						
						$preparedStatement->execute([
							'd' => $tl['designation'],
							'idd' => $tl['idDesignation'],
							'uv' => $tl['unitevente'],
							'pu' => $tl['prixunitevente'],
							'qty' => $tl['quantite'],
							'p' => $tl['prixtotal'],
							'idp' => $idPagnet,
							'lt' => $tl['numligne'],
							'c' => $tl['classe']
						]);
					}
					
					$sqlU="UPDATE `".$nomtablePagnetTampon."` SET synchronise=1 where idPagnet=".$tp['idPagnet'];
					
					$statementU = $bdd->prepare($sqlU);
					$statementU->execute();
                    
				}

				// Make the changes to the database permanent
				$bdd->commit();
				// header("Refresh:0");
			}
			catch ( PDOException $e ) { 
				// Failed to insert the order into the database so we rollback any changes
				$bdd->rollback();
				throw $e;

				// echo '0';
			}
            /******* Fin déplacement tampon vers standard*******/

            
           /******* Début déplacement standard vers tampon*******/

            $sql="SELECT * FROM `".$nomtablePagnet."` where datepagej='".$dateString2."' and tampon=0 and verrouiller=1";
				
			// var_dump($sql);
			$statement = $bdd->prepare($sql);
			$statement->execute();
			$normalsP = $statement->fetchAll(PDO::FETCH_ASSOC); 
			// var_dump($normalsP);
			$bdd->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

			try {
				// From this point and until the transaction is being committed every change to the database can be reverted
				$bdd->beginTransaction();  /**** generate barcode ****/

				foreach ($normalsP as $np) {
					// var_dump($np);

					$sqlL="SELECT * FROM `".$nomtableLigne."` where idPagnet = ".$np['idPagnet'];
				
				   // var_dump($sqlL);
					$statementL = $bdd->prepare($sqlL);
					$statementL->execute();
					$normalsL = $statementL->fetchAll(PDO::FETCH_ASSOC); 
					$synchronise=1;

					// if ($np['verrouiller']==1) {
					// 	$synchronise=1;
					// } 
					

					$req4 = $bdd->prepare("INSERT INTO `".$nomtablePagnetTampon."` (datepagej,heurePagnet,iduser,type,totalp,apayerPagnet,verrouiller,idClient,remise,restourne,versement,idCompte,avance,idCompteAvance,synchronise)
					values (:d,:h,:u,:t,:tp,:ap,:v,:c,:r,:res,:ver,:idC,:av,:idcav,:syn)");
				   // var_dump($req4);
					$req4->execute(array(
						'd' => $np['datepagej'],
						'h' => $np['heurePagnet'],
						'u' => $np['iduser'],
						't' => $np['type'],
						'tp' => $np['totalp'],
						'ap' => $np['apayerPagnet'],
						'v' => $np['verrouiller'],
						'c' => $np['idClient'],
						'r' => $np['remise'],
						'res' => $np['restourne'],
						'ver' => $np['versement'],
						'idC' => $np['idCompte'],
						'av' => $np['avance'],
						'idcav' => $np['idCompteAvance'],
						'syn' => $synchronise
					))  or die(print_r("Insert pagnet 2 ".$req4->errorInfo()));
					$req4->closeCursor();
					$idPagnet = $bdd->lastInsertId();
					// var_dump($idPagnet);

					foreach ($normalsL as $nl) {
						// var_dump($nl);
						$preparedStatement = $bdd->prepare(
							"insert into `".$nomtableLigneTampon."` (designation, idDesignation, unitevente, prixunitevente, quantite, prixtotal, idPagnet, classe)
							 values (:d,:idd,:uv,:pu,:qty,:p,:idp,:c)"
						);
				    // var_dump($preparedStatement);
						
						$preparedStatement->execute([
							'd' => $nl['designation'],
							'idd' => $nl['idDesignation'],
							'uv' => $nl['unitevente'],
							'pu' => $nl['prixunitevente'],
							'qty' => $nl['quantite'],
							'p' => $nl['prixtotal'],
							'idp' => $idPagnet,
							'c' => $nl['classe']
						]);
					}

					// if ($np['verrouiller']==0) {
							
					// 	$sqlD = "DELETE FROM `".$nomtablePagnet."` WHERE idPagnet=?";
					// 	$stmt= $bdd->prepare($sqlD);
					// 	$stmt->execute([$np['idPagnet']]);
					// }
                    
					$sqlU="UPDATE `".$nomtablePagnet."` SET tampon=1 where idPagnet=".$np['idPagnet'];
					
					$statementU = $bdd->prepare($sqlU);
					$statementU->execute();
				}

				// Make the changes to the database permanent
				$bdd->commit();
				// header("Refresh:0");
			}
			catch ( PDOException $e ) { 
				// Failed to insert the order into the database so we rollback any changes
				$bdd->rollback();
				throw $e;

				// echo '0';
			}

           /******* Fin déplacement standard vers tampon*******/

			$jour_J=@htmlspecialchars($_POST["jour"]);

        if(strlen($jour_J)==1){

            $jour_J='0'.$jour_J;

        }

        $mois_J=@htmlspecialchars($_POST["mois"]);

        if(strlen($mois_J)==1){

            $mois_J='0'.$mois_J;

        }

        $annee_J=@htmlspecialchars($_POST["annee"]);

        $dateJour=$jour_J.'-'.$mois_J.'-'.$annee_J;

        $dateJour2=$annee_J.'-'.$mois_J.'-'.$jour_J;

        $dateAnnee=$annee_J.'-'.$mois_J.'-'.$jour_J;



        $sqlApp="SELECT DISTINCT p.idPagnet

            FROM `".$nomtablePagnetTampon."` p

            INNER JOIN `".$nomtableLigneTampon."` l ON l.idPagnet = p.idPagnet

            WHERE l.classe=5 && p.idClient=0 && p.type<>2  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  ORDER BY p.idPagnet DESC";

        $resApp = mysql_query($sqlApp) or die ("persoonel requête 2".mysql_error());

        $T_App = 0;

        $S_App = 0;

        while ($pagnet = mysql_fetch_array($resApp)) {

            $sqlS="SELECT SUM(apayerPagnet)

            FROM `".$nomtablePagnetTampon."`

            where idClient=0 &&  verrouiller=1 && datepagej ='".$dateJour."' && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";

            $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

            $S_App = mysql_fetch_array($resS);

            $T_App = $S_App[0] + $T_App;

        }



        $sqlRC="SELECT DISTINCT p.idPagnet

            FROM `".$nomtablePagnetTampon."` p

            INNER JOIN `".$nomtableLigneTampon."` l ON l.idPagnet = p.idPagnet

            WHERE l.classe=7 && p.idClient=0 && p.type=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  ORDER BY p.idPagnet DESC";

        $resRC = mysql_query($sqlRC) or die ("persoonel requête 2".mysql_error());

        $T_Rcaisse = 0;

        $S_Rcaisse = 0;

        while ($pagnet = mysql_fetch_array($resRC)) {

            $sqlS="SELECT SUM(apayerPagnet)

            FROM `".$nomtablePagnetTampon."`

            where idClient=0 && type=0 && verrouiller=1 && datepagej ='".$dateJour."' && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";

            $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

            $S_Rcaisse = mysql_fetch_array($resS);

            $T_Rcaisse = $S_Rcaisse[0] + $T_Rcaisse;

        }



        if (($_SESSION['mutuelle']==1) && ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

            $T_ventes = 0;

            $T_ventesMobile = 0;



            if ($_SESSION['compte']==1) {

                /************ Début ventes compte caisse ***************/



                $sqlV="SELECT *

                FROM `".$nomtableDesignation."` d

                INNER JOIN `".$nomtableLigneTampon."` l ON l.idDesignation = d.idDesignation

                INNER JOIN `".$nomtablePagnetTampon."` p ON p.idPagnet = l.idPagnet

                WHERE l.classe = 0 && p.idClient=0  && p.verrouiller=1 && (p.idCompte=1 || p.idCompte=0) && p.datepagej ='".$dateJour."' && p.type=0 ";

                $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());

                $T_ventes_V = 0;

                while ($pagnet = mysql_fetch_array($resV)) {

                    $T_ventes_V=$T_ventes_V + $pagnet['prixtotal'];

                }

    

                $sqlM="SELECT *

                FROM `".$nomtableDesignation."` d

                INNER JOIN `".$nomtableLigneTampon."` l ON l.idDesignation = d.idDesignation

                INNER JOIN `".$nomtableMutuellePagnet."` m ON m.idMutuellePagnet = l.idMutuellePagnet

                WHERE l.classe = 0 && m.idClient=0  && m.verrouiller=1 && (m.idCompte=1 || m.idCompte=0) && m.datepagej ='".$dateJour."' && m.type=0 ";

                $resM = mysql_query($sqlM) or die ("persoonel requête 2".mysql_error());

                $T_ventes_M = 0;

                while ($mutuelle = mysql_fetch_array($resM)) {

                    $T_ventes_M=$T_ventes_M + $mutuelle['apayerPagnet'];

                }

    

                $T_ventes = $T_ventes_V + $T_ventes_M;



                /************ Fin ventes compte caisse ***************/



                /************ Début ventes compte mobile ***************/



                $sqlV="SELECT *

                FROM `".$nomtableDesignation."` d

                INNER JOIN `".$nomtableLigneTampon."` l ON l.idDesignation = d.idDesignation

                INNER JOIN `".$nomtablePagnetTampon."` p ON p.idPagnet = l.idPagnet

                WHERE l.classe = 0 && p.idClient=0  && p.verrouiller=1 && p.idCompte<>0 && p.idCompte<>1 && p.idCompte<>2 && p.idCompte<>3 && p.idCompte<>1000 && p.datepagej ='".$dateJour."' && p.type=0 ";

                $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());

                $T_ventes_V = 0;

                while ($pagnet = mysql_fetch_array($resV)) {

                    $T_ventes_V=$T_ventes_V + $pagnet['prixtotal'];

                }

                $sqlM="SELECT *

                FROM `".$nomtableDesignation."` d

                INNER JOIN `".$nomtableLigneTampon."` l ON l.idDesignation = d.idDesignation

                INNER JOIN `".$nomtableMutuellePagnet."` m ON m.idMutuellePagnet = l.idMutuellePagnet

                WHERE l.classe = 0 && m.idClient=0  && m.verrouiller=1 && m.idCompte<>0 && m.idCompte<>1 && m.idCompte<>2 && m.idCompte<>3 && m.datepagej ='".$dateJour."' && m.type=0 ";

                $resM = mysql_query($sqlM) or die ("persoonel requête 2".mysql_error());

                $T_ventes_M = 0;

                while ($mutuelle = mysql_fetch_array($resM)) {

                    $T_ventes_M=$T_ventes_M + $mutuelle['apayerPagnet'];

                }

    

                $T_ventesMobile = $T_ventes_V + $T_ventes_M;



                /************ Fin ventes compte mobile ***************/


            } else {



                $sqlV="SELECT *

                FROM `".$nomtableDesignation."` d

                INNER JOIN `".$nomtableLigneTampon."` l ON l.idDesignation = d.idDesignation

                INNER JOIN `".$nomtablePagnetTampon."` p ON p.idPagnet = l.idPagnet

                WHERE l.classe = 0 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 ";

                $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());

                $T_ventes_V = 0;

                while ($pagnet = mysql_fetch_array($resV)) {

                    $T_ventes_V=$T_ventes_V + $pagnet['prixtotal'];

                }
    

                $sqlM="SELECT *

                FROM `".$nomtableDesignation."` d

                INNER JOIN `".$nomtableLigneTampon."` l ON l.idDesignation = d.idDesignation

                INNER JOIN `".$nomtableMutuellePagnet."` m ON m.idMutuellePagnet = l.idMutuellePagnet

                WHERE l.classe = 0 && m.idClient=0  && m.verrouiller=1 && m.datepagej ='".$dateJour."' && m.type=0 ";

                $resM = mysql_query($sqlM) or die ("persoonel requête 2".mysql_error());

                $T_ventes_M = 0;
                $taux = 0;

                while ($mutuelle = mysql_fetch_array($resM)) {
                    $taux = $mutuelle['taux'];

                    $T_ventes_M=$T_ventes_M + $mutuelle['prixtotal'] - ($mutuelle['prixtotal']* $taux / 100);

                }

                $T_ventes = $T_ventes_V + $T_ventes_M;

            }

        }

        else{

            $T_ventes = 0; 

            $T_ventesMobile = 0;

            if ($_SESSION['compte']==1) {

                /************ Début ventes compte caisse ***************/

                $sqlV="SELECT *

                FROM `".$nomtableLigneTampon."` l 

                INNER JOIN `".$nomtablePagnetTampon."` p ON p.idPagnet = l.idPagnet

                WHERE l.classe = 0 && p.idClient=0 && p.verrouiller=1 && (p.idCompte=1 || p.idCompte=0) && p.datepagej ='".$dateJour."' && p.type=0 ";

                $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());

                while ($pagnet = mysql_fetch_array($resV)) {

                    if ($_SESSION['Pays']=='Canada') { 

                        $T_ventes=$T_ventes + $pagnet['prixtotal'] + $pagnet['prixtotalTvaP'] + $pagnet['prixtotalTvaR'];

                    }

                    else {

                        $T_ventes=$T_ventes + $pagnet['prixtotal'];

                    }


                }

                /************ Fin ventes compte caisse ***************/

                /************ Début ventes compte mobile ***************/

                $sqlV="SELECT *

                FROM  `".$nomtableLigneTampon."` l 

                INNER JOIN `".$nomtablePagnetTampon."` p ON p.idPagnet = l.idPagnet

                WHERE l.classe = 0 && p.idClient=0  && p.verrouiller=1 && p.idCompte<>0 && p.idCompte<>1 && p.idCompte<>2 && p.idCompte<>3 && p.idCompte<>1000 && p.datepagej ='".$dateJour."' && p.type=0 ";

                $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());

                while ($pagnet = mysql_fetch_array($resV)) {

                    if($_SESSION['Pays']=='Canada'){ 

                        $T_ventesMobile=$T_ventesMobile + $pagnet['prixtotal'] + $pagnet['prixtotalTvaP'] + $pagnet['prixtotalTvaR'];

                    }

                    else{

                        $T_ventesMobile=$T_ventesMobile + $pagnet['prixtotal'];  

                    }

                }

                /************ Fin ventes compte mobile ***************/
                /************ Début ventes compte multiple ***************/
                $mntCpt = 0;
                $sqlV="SELECT *

                FROM  `".$nomtablePagnetTampon."` p WHERE p.idClient=0  && p.verrouiller=1 && p.idCompte=1000 && p.datepagej ='".$dateJour."' && p.type=0 ";

                $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());

                while ($pagnet = mysql_fetch_array($resV)) {

                    $idsComptes = explode('_', $pagnet['idsCpteMultiple']);
                    // var_dump($idsComptes);
                    foreach ($idsComptes as $key) {
                        $sqlM="SELECT *
                        FROM  `".$nomtableComptemouvement."` WHERE idCompte=".$key."  && mouvementLink=".$pagnet['idPagnet']."";

                        $resM = mysql_query($sqlM) or die ("persoonel requête Mv".mysql_error());
                        $cptMv = mysql_fetch_array($resM);
                        $mntCpt = $cptMv['montant'];

                        if ($key == '1' || $key == 1) {
                            $T_ventes = $T_ventes + $mntCpt;
                        } else {
                            $T_ventesMobile = $T_ventesMobile + $mntCpt;
                        }
                    }

                }

                /************ Fin ventes compte multiple ***************/

            }else{

                $sqlV="SELECT *

                FROM  `".$nomtableLigneTampon."` l 

                INNER JOIN `".$nomtablePagnetTampon."` p ON p.idPagnet = l.idPagnet

                WHERE l.classe = 0 && p.idClient=0 && p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 ";

                $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());

                while ($pagnet = mysql_fetch_array($resV)) {

                    if($_SESSION['Pays']=='Canada'){ 

                        $T_ventes=$T_ventes + $pagnet['prixtotal'] + $pagnet['prixtotalTvaP'] + $pagnet['prixtotalTvaR'];

                    }

                    else{

                        $T_ventes=$T_ventes + $pagnet['prixtotal'];     

                    }

                }

            }

        }

        $sqlRm="SELECT DISTINCT p.idPagnet

            FROM `".$nomtablePagnetTampon."` p

            INNER JOIN `".$nomtableLigneTampon."` l ON l.idPagnet = p.idPagnet

            WHERE (l.classe=0 || l.classe=1)  &&  p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  && p.type=0 ";

        $resRm = mysql_query($sqlRm) or die ("persoonel requête 2".mysql_error());

        $T_remises = 0 ;

        $S_remises = 0;

        while ($pagnet = mysql_fetch_array($resRm)) {

            $sqlS="SELECT SUM(remise)

            FROM `".$nomtablePagnetTampon."`

            where idClient=0 && verrouiller=1 && datepagej ='".$dateJour."' && type=0 && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";

            $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

            $S_remises = mysql_fetch_array($resS);

            $T_remises = $S_remises[0] + $T_remises;

        }

        

        $T_Rpagnet = 0;

        $S_Rpagnet = 0;

        $T_RpagnetMobile = 0;

        $S_RpagnetMobile = 0;

        if ($_SESSION['compte']==1) {

            $sqlRt="SELECT DISTINCT p.idPagnet

                FROM `".$nomtablePagnetTampon."` p

                INNER JOIN `".$nomtableLigneTampon."` l ON l.idPagnet = p.idPagnet

                WHERE l.classe=0 && p.idClient=0  && p.verrouiller=1 && (p.idCompte=1 || p.idCompte=0) && p.datepagej ='".$dateJour."' && p.type=1  ORDER BY p.idPagnet DESC";

            $resRt = mysql_query($sqlRt) or die ("persoonel requête 2".mysql_error());

            

            while ($pagnet = mysql_fetch_array($resRt)) {

                $sqlS="SELECT SUM(apayerPagnet)

                FROM `".$nomtablePagnetTampon."`

                where idClient=0 &&  verrouiller=1 && datepagej ='".$dateJour."' && type=1 && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";

                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                $S_Rpagnet = mysql_fetch_array($resS);

                $T_Rpagnet = $S_Rpagnet[0] + $T_Rpagnet;

            }



            $sqlRtMobile="SELECT DISTINCT p.idPagnet

                FROM `".$nomtablePagnetTampon."` p

                INNER JOIN `".$nomtableLigneTampon."` l ON l.idPagnet = p.idPagnet

                WHERE l.classe=0 && p.idClient=0  && p.verrouiller=1 && p.idCompte<>0 && p.idCompte<>1 && p.idCompte<>2 && p.idCompte<>3 && p.datepagej ='".$dateJour."' && p.type=1  ORDER BY p.idPagnet DESC";

            $resRtMobile = mysql_query($sqlRtMobile) or die ("persoonel requête 2".mysql_error());

            

            while ($pagnet = mysql_fetch_array($resRtMobile)) {

                $sqlSMobile="SELECT SUM(apayerPagnet)

                FROM `".$nomtablePagnetTampon."`

                where idClient=0 &&  verrouiller=1 && datepagej ='".$dateJour."' && type=1 && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";

                $resSMobile=mysql_query($sqlSMobile) or die ("select stock impossible =>".mysql_error());

                $S_RpagnetMobile = mysql_fetch_array($resSMobile);

                $T_RpagnetMobile = $S_RpagnetMobile[0] + $T_RpagnetMobile;

            }



        }else{

            $sqlRt="SELECT DISTINCT p.idPagnet

                FROM `".$nomtablePagnetTampon."` p

                INNER JOIN `".$nomtableLigneTampon."` l ON l.idPagnet = p.idPagnet

                WHERE l.classe=0 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=1  ORDER BY p.idPagnet DESC";

            $resRt = mysql_query($sqlRt) or die ("persoonel requête 2".mysql_error());

            $T_Rpagnet = 0;

            $S_Rpagnet = 0;

            while ($pagnet = mysql_fetch_array($resRt)) {

                $sqlS="SELECT SUM(apayerPagnet)

                FROM `".$nomtablePagnetTampon."`

                where idClient=0 &&  verrouiller=1 && datepagej ='".$dateJour."' && type=1 && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";

                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                $S_Rpagnet = mysql_fetch_array($resS);

                $T_Rpagnet = $S_Rpagnet[0] + $T_Rpagnet;

            }            

        }


        
        if (($_SESSION['mutuelle']==1) && ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

            $sqlSv="SELECT *

            FROM `".$nomtableDesignation."` d

            INNER JOIN `".$nomtableLigneTampon."` l ON l.idDesignation = d.idDesignation

            INNER JOIN `".$nomtablePagnetTampon."` p ON p.idPagnet = l.idPagnet

            WHERE l.classe = 1 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 ";

            $resSv = mysql_query($sqlSv) or die ("persoonel requête 2".mysql_error());

            $T_venteServ = 0;

            while ($pagnet = mysql_fetch_array($resSv)) {

                $T_venteServ = $T_venteServ + $pagnet['prixtotal'];

            }

            //******************** vente service mutuelle ***************** */

            $sqlSvM="SELECT *

            FROM `".$nomtableDesignation."` d

            INNER JOIN `".$nomtableLigneTampon."` l ON l.idDesignation = d.idDesignation
            
            INNER JOIN `".$nomtableMutuellePagnet."` m ON m.idMutuellePagnet = l.idMutuellePagnet

            WHERE l.classe = 1 && m.idClient=0  && m.verrouiller=1 && m.datepagej ='".$dateJour."' && m.type=0 ";

            // echo $sqlSvM;

            $resSvM = mysql_query($sqlSvM) or die ("persoonel requête 2".mysql_error());

            $T_venteServM = 0;
            $taux = 0;

            while ($mutuelle = mysql_fetch_array($resSvM)) {
                $taux = $mutuelle['taux'];

                $T_venteServM = $T_venteServM + $mutuelle['prixtotal'] - ($mutuelle['prixtotal']* $taux / 100);

            }
            //******************** fin vente service mutuelle***************** */
            // var_dump($taux) ; 
            $T_venteServ = $T_venteServ + $T_venteServM;

        }else{

            $sqlSv="SELECT *

            FROM `".$nomtableDesignation."` d

            INNER JOIN `".$nomtableLigneTampon."` l ON l.idDesignation = d.idDesignation

            INNER JOIN `".$nomtablePagnetTampon."` p ON p.idPagnet = l.idPagnet

            WHERE l.classe = 1 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 ";

            $resSv = mysql_query($sqlSv) or die ("persoonel requête 2".mysql_error());

            $T_venteServ = 0;

            while ($pagnet = mysql_fetch_array($resSv)) {

                $T_venteServ = $T_venteServ + $pagnet['prixtotal'];

            }
            
        }


        $sqlD="SELECT DISTINCT p.idPagnet

            FROM `".$nomtablePagnetTampon."` p

            INNER JOIN `".$nomtableLigneTampon."` l ON l.idPagnet = p.idPagnet

            WHERE l.classe=2 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  && p.type=0  ORDER BY p.idPagnet DESC";

        $resD = mysql_query($sqlD) or die ("persoonel requête 2".mysql_error());

        $T_depenses = 0;

        $S_depenses = 0;

        while ($pagnet = mysql_fetch_array($resD)) {

            $sqlS="SELECT SUM(apayerPagnet)

            FROM `".$nomtablePagnetTampon."`

            where idClient=0 &&  verrouiller=1 && datepagej ='".$dateJour."' && idPagnet='".$pagnet['idPagnet']."' && type=0  ORDER BY idPagnet DESC";

            $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

            $S_depenses = mysql_fetch_array($resS);

            $T_depenses = $S_depenses[0] + $T_depenses;

        }



        $sqlBE="SELECT DISTINCT p.idPagnet

            FROM `".$nomtablePagnetTampon."` p

            INNER JOIN `".$nomtableLigneTampon."` l ON l.idPagnet = p.idPagnet

            WHERE l.classe=6 && p.idClient!=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  && (p.type=0 || p.type=30)  ORDER BY p.idPagnet DESC";

        $resBE = mysql_query($sqlBE) or die ("persoonel requête 2".mysql_error());

        $T_bonEsp = 0;

        $S_bonEsp = 0;

        while ($pagnet = mysql_fetch_array($resBE)) {

            $sqlS="SELECT SUM(apayerPagnet)

            FROM `".$nomtablePagnetTampon."`

            where idClient!=0 &&  verrouiller=1 && datepagej ='".$dateJour."' && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";

            $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

            $S_bonEsp = mysql_fetch_array($resS);

            $T_bonEsp = $S_bonEsp[0] + $T_bonEsp;

        }



        if (($_SESSION['mutuelle']==1) && ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

            $T_bonV = 0;

            $sqlBP="SELECT DISTINCT p.idPagnet

                FROM `".$nomtablePagnetTampon."` p

                INNER JOIN `".$nomtableLigneTampon."` l ON l.idPagnet = p.idPagnet

                WHERE (l.classe!=6 && l.classe!=9) && p.idClient!=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."' && (p.type=0 || p.type=30)   ORDER BY p.idPagnet DESC";

            $resBP = mysql_query($sqlBP) or die ("persoonel requête 2".mysql_error());

            $T_bonP = 0;

            $S_bonP = 0;

            while ($pagnet = mysql_fetch_array($resBP)) {

                $sqlS="SELECT SUM(apayerPagnet)

                FROM `".$nomtablePagnetTampon."`

                where idClient!=0 &&  verrouiller=1 && datepagej ='".$dateJour."' && idPagnet='".$pagnet['idPagnet']."' && (type=0 || type=30)   ORDER BY idPagnet DESC";

                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                $S_bonP = mysql_fetch_array($resS);

                $T_bonP = $S_bonP[0] + $T_bonP;

            }



            $sqlBM="SELECT DISTINCT m.idMutuellePagnet

                FROM `".$nomtableMutuellePagnet."` m

                INNER JOIN `".$nomtableLigneTampon."` l ON l.idMutuellePagnet = m.idMutuellePagnet

                WHERE (l.classe!=6 && l.classe!=9) && m.idClient!=0  && m.verrouiller=1 && m.datepagej ='".$dateJour."' && (m.type=0 || m.type=30)   ORDER BY m.idMutuellePagnet DESC";

            $resBM = mysql_query($sqlBM) or die ("persoonel requête 2".mysql_error());

            $T_bonM = 0;

            $S_bonM = 0;

            while ($mutuelle = mysql_fetch_array($resBM)) {

                $sqlS="SELECT SUM(apayerPagnet)

                FROM `".$nomtableMutuellePagnet."`

                where idClient!=0 &&  verrouiller=1 && datepagej ='".$dateJour."' && idMutuellePagnet='".$mutuelle['idMutuellePagnet']."' && (type=0 || type=30)   ORDER BY idMutuellePagnet DESC";

                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                $S_bonM = mysql_fetch_array($resS);

                $T_bonM = $S_bonM[0] + $T_bonM;

            }

            $T_bonV = $T_bonP  + $T_bonM ;



            $sqlMB="SELECT DISTINCT m.idMutuellePagnet

                FROM `".$nomtableMutuellePagnet."` m

                INNER JOIN `".$nomtableLigneTampon."` l ON l.idMutuellePagnet = m.idMutuellePagnet

                WHERE (l.classe!=6 && l.classe!=9) && m.verrouiller=1 && m.datepagej ='".$dateJour."' && (m.type=0 || m.type=30)   ORDER BY m.idMutuellePagnet DESC";

            $resMB = mysql_query($sqlMB) or die ("persoonel requête 2".mysql_error());

            $T_mutuelleB = 0;

            $S_mutuelleB = 0;

            while ($mutuelle = mysql_fetch_array($resMB)) {

                $sqlS="SELECT SUM(apayerMutuelle)

                FROM `".$nomtableMutuellePagnet."`

                where  verrouiller=1 && datepagej ='".$dateJour."' && idMutuellePagnet='".$mutuelle['idMutuellePagnet']."' && (type=0 || type=30)   ORDER BY idMutuellePagnet DESC";

                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                $S_mutuelleB = mysql_fetch_array($resS);

                $T_mutuelleB = $S_mutuelleB[0] + $T_mutuelleB;

            }

        }

        else{

            $sqlBP="SELECT DISTINCT p.idPagnet

                FROM `".$nomtablePagnetTampon."` p

                INNER JOIN `".$nomtableLigneTampon."` l ON l.idPagnet = p.idPagnet

                WHERE (l.classe!=6 && l.classe!=9) && p.idClient!=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."' && (p.type=0 || p.type=30)   ORDER BY p.idPagnet DESC";

            $resBP = mysql_query($sqlBP) or die ("persoonel requête 2".mysql_error());

            $T_bonP = 0;

            $S_bonP = 0;

            $Ttva_bonP = 0;

            while ($pagnet = mysql_fetch_array($resBP)) {

                $sqlS="SELECT SUM(apayerPagnet)

                FROM `".$nomtablePagnetTampon."`

                where idClient!=0 &&  verrouiller=1 && datepagej ='".$dateJour."' && idPagnet='".$pagnet['idPagnet']."' && (type=0 || type=30)   ORDER BY idPagnet DESC";

                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                $S_bonP = mysql_fetch_array($resS);

                $T_bonP = $S_bonP[0] + $T_bonP;



                if($_SESSION['Pays']=='Canada'){ 

                    $sqlSP="SELECT SUM(apayerPagnetTvaP)

                    FROM `".$nomtablePagnetTampon."`

                    where idClient!=0 &&  verrouiller=1 && datepagej ='".$dateJour."' && idPagnet='".$pagnet['idPagnet']."' && (type=0 || type=30)   ORDER BY idPagnet DESC";

                    $resSP=mysql_query($sqlSP) or die ("select stock impossible =>".mysql_error());

                    $StvaP_bonP = mysql_fetch_array($resSP);



                    $sqlSR="SELECT SUM(apayerPagnetTvaR)

                    FROM `".$nomtablePagnetTampon."`

                    where idClient!=0 &&  verrouiller=1 && datepagej ='".$dateJour."' && idPagnet='".$pagnet['idPagnet']."' && (type=0 || type=30)   ORDER BY idPagnet DESC";

                    $resSR=mysql_query($sqlSR) or die ("select stock impossible =>".mysql_error());

                    $StvaR_bonR = mysql_fetch_array($resSR);



                    $Ttva_bonP = $Ttva_bonP + $StvaP_bonP[0] + $StvaR_bonR[0];

                }

            }

            $T_bonV=$T_bonP ;

            if($_SESSION['Pays']=='Canada'){ 

                $T_bonV=$T_bonP + $Ttva_bonP ;

            }

        }



        $T_versementClient=0;

        $sqlP5="SELECT SUM(montant) FROM `".$nomtableVersement."` where idClient!=0 && (dateVersement  ='".$dateJour."' || dateVersement  ='".$dateJour2."')  ORDER BY idVersement DESC";

        $resP5 = mysql_query($sqlP5) or die ("persoonel requête 2".mysql_error());

        $T_VClient = mysql_fetch_array($resP5) ;

        if($T_VClient[0]!=null){

            $T_versementClient=$T_VClient[0];

        }



        $T_versementFournisseur=0;

        $sqlP6="SELECT SUM(montant) FROM `".$nomtableVersement."` where idFournisseur!=0 && dateVersement  ='".$dateAnnee."'  ORDER BY idVersement DESC";

        $resP6 = mysql_query($sqlP6) or die ("persoonel requête 2".mysql_error());

        $T_VFournisseur = mysql_fetch_array($resP6) ;

        if($T_VFournisseur[0]!=null){

            $T_versementFournisseur=$T_VFournisseur[0];

        }



        if (($_SESSION['mutuelle']==1) && ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 

            $T_versementMutuelle=0;

            $sqlP6="SELECT SUM(montant) FROM `".$nomtableVersement."` where idMutuelle!=0 && dateVersement  ='".$dateJour."'  ORDER BY idVersement DESC";

            $resP6 = mysql_query($sqlP6) or die ("persoonel requête 2".mysql_error());

            $T_VMutuelle = mysql_fetch_array($resP6) ;

            if($T_VMutuelle[0]!=null){

                $T_versementMutuelle=$T_VMutuelle[0];

            }

        }


        if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 

            if ($_SESSION['mutuelle']==1){

                $T_Caisse=$T_App - $T_Rcaisse + $T_ventes - $T_remises - $T_Rpagnet + $T_venteServ - $T_depenses + $T_versementClient + $T_versementMutuelle - $T_versementFournisseur - $T_bonEsp;

            }

            else{

                $T_Caisse=$T_App - $T_Rcaisse + $T_ventes - $T_remises - $T_Rpagnet + $T_venteServ - $T_depenses + $T_versementClient - $T_versementFournisseur - $T_bonEsp;

            }

        }

        else{

            $sqlBN="SELECT *

            FROM `".$nomtableDesignation."` d

            INNER JOIN `".$nomtableLigneTampon."` l ON l.idDesignation = d.idDesignation

            INNER JOIN `".$nomtablePagnetTampon."` p ON p.idPagnet = l.idPagnet

            WHERE l.classe = 0 && p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 ";

            $resBN = mysql_query($sqlBN) or die ("persoonel requête 2".mysql_error());

            $T_prixAchat_US = 0;

            $T_prixAchat_UN = 0;

            $T_prixAchat_DM = 0;

            $T_prixventes = 0;

            while ($pagnet = mysql_fetch_array($resBN)) {

                if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

                    if ($pagnet['unitevente']==$pagnet['uniteStock']) {

                        $T_prixAchat_US = $T_prixAchat_US + ($pagnet['prixachat'] * $pagnet['quantite']);

                    }

                    else if ($pagnet['unitevente']=="Demi Gros") {

                        $T_prixAchat_DM = $T_prixAchat_DM + (($pagnet['prixachat'] / $pagnet['nbreArticleUniteStock']) * $pagnet['quantite']);

                    }

                    else{

                        $T_prixAchat_UN = $T_prixAchat_UN + (($pagnet['prixachat'] / $pagnet['nbreArticleUniteStock']) * $pagnet['quantite']);

                        

                    }

                    $T_prixventes=$T_prixventes + $pagnet['prixtotal'];

                }

                else{

                    if (($pagnet['unitevente']!="Article")&&($pagnet['unitevente']!="article")) {

                        $T_prixAchat_US = $T_prixAchat_US + ($pagnet['prixachat'] * $pagnet['quantite'] * $pagnet['nbreArticleUniteStock']);

                    }

                    else if(($pagnet['unitevente']=="Article")||($pagnet['unitevente']=="article")){

                        $T_prixAchat_UN = $T_prixAchat_UN + ($pagnet['prixachat'] * $pagnet['quantite']);

                    }

                    $T_prixventes=$T_prixventes + $pagnet['prixtotal'];

                }

            }

            $T_prixAchat=$T_prixAchat_US + $T_prixAchat_UN + $T_prixAchat_DM;

            $T_Benefice=$T_prixventes - $T_prixAchat;



            $T_Caisse=$T_App - $T_Rcaisse + $T_ventes - $T_remises - $T_Rpagnet + $T_venteServ - $T_depenses + $T_versementClient - $T_versementFournisseur - $T_bonEsp + $T_ventesMobile - $T_RpagnetMobile;

        }



        if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 

            if ($_SESSION['mutuelle']==1){

                $result='1<>'.number_format($T_App, 0, ',', ' ').'<>'.number_format($T_Rcaisse, 0, ',', ' ').'<>'.number_format($T_ventes, 0, ',', ' ').'<>'.number_format($T_Rpagnet, 0, ',', ' ').'<>'.number_format($T_venteServ, 0, ',', ' ').'<>'.number_format($T_depenses, 0, ',', ' ').'<>'.number_format($T_bonEsp, 0, ',', ' ').'<>'.number_format($T_bonV, 0, ',', ' ').'<>'.number_format($T_versementClient, 0, ',', ' ').'<>'.number_format($T_versementFournisseur, 0, ',', ' ').'<>'.number_format($T_Caisse, 0, ',', ' ').'<>0<>'.number_format($T_versementMutuelle, 0, ',', ' ').'<>'.number_format($T_ventesMobile, 0, ',', ' ').'<>'.number_format($T_mutuelleB, 0, ',', ' ');

            }

            else{

                $result='1<>'.number_format($T_App, 0, ',', ' ').'<>'.number_format($T_Rcaisse, 0, ',', ' ').'<>'.number_format($T_ventes, 0, ',', ' ').'<>'.number_format($T_Rpagnet, 0, ',', ' ').'<>'.number_format($T_venteServ, 0, ',', ' ').'<>'.number_format($T_depenses, 0, ',', ' ').'<>'.number_format($T_bonEsp, 0, ',', ' ').'<>'.number_format($T_bonV, 0, ',', ' ').'<>'.number_format($T_versementClient, 0, ',', ' ').'<>'.number_format($T_versementFournisseur, 0, ',', ' ').'<>'.number_format($T_Caisse, 0, ',', ' ').'<>0<>0<>'.number_format($T_ventesMobile, 0, ',', ' ');

            }

            

        }

        else{

            $result='1<>'.number_format($T_App, 0, ',', ' ').'<>'.number_format($T_Rcaisse, 0, ',', ' ').'<>'.number_format($T_ventes, 0, ',', ' ').'<>'.number_format($T_Rpagnet, 0, ',', ' ').'<>'.number_format($T_venteServ, 0, ',', ' ').'<>'.number_format($T_depenses, 0, ',', ' ').'<>'.number_format($T_bonEsp, 0, ',', ' ').'<>'.number_format($T_bonV, 0, ',', ' ').'<>'.number_format($T_versementClient, 0, ',', ' ').'<>'.number_format($T_versementFournisseur, 0, ',', ' ').'<>'.number_format($T_Caisse, 0, ',', ' ').'<>'.number_format($T_Benefice, 0, ',', ' ').'<>0<>'.number_format($T_ventesMobile, 0, ',', ' ');

        }

       

        exit($result);

    }
    
			else {

        $jour_J=@htmlspecialchars($_POST["jour"]);

        if(strlen($jour_J)==1){

            $jour_J='0'.$jour_J;

        }

        $mois_J=@htmlspecialchars($_POST["mois"]);

        if(strlen($mois_J)==1){

            $mois_J='0'.$mois_J;

        }

        $annee_J=@htmlspecialchars($_POST["annee"]);

        $dateJour=$jour_J.'-'.$mois_J.'-'.$annee_J;

        $dateJour2=$annee_J.'-'.$mois_J.'-'.$jour_J;

        $dateAnnee=$annee_J.'-'.$mois_J.'-'.$jour_J;



        $sqlApp="SELECT DISTINCT p.idPagnet

            FROM `".$nomtablePagnet."` p

            INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

            WHERE l.classe=5 && p.idClient=0 && p.type<>2  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  ORDER BY p.idPagnet DESC";

        $resApp = mysql_query($sqlApp) or die ("persoonel requête 2".mysql_error());

        $T_App = 0;

        $S_App = 0;

        while ($pagnet = mysql_fetch_array($resApp)) {

            $sqlS="SELECT SUM(apayerPagnet)

            FROM `".$nomtablePagnet."`

            where idClient=0 &&  verrouiller=1 && datepagej ='".$dateJour."' && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";

            $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

            $S_App = mysql_fetch_array($resS);

            $T_App = $S_App[0] + $T_App;

        }



        $sqlRC="SELECT DISTINCT p.idPagnet

            FROM `".$nomtablePagnet."` p

            INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

            WHERE l.classe=7 && p.idClient=0 && p.type=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  ORDER BY p.idPagnet DESC";

        $resRC = mysql_query($sqlRC) or die ("persoonel requête 2".mysql_error());

        $T_Rcaisse = 0;

        $S_Rcaisse = 0;

        while ($pagnet = mysql_fetch_array($resRC)) {

            $sqlS="SELECT SUM(apayerPagnet)

            FROM `".$nomtablePagnet."`

            where idClient=0 && type=0 && verrouiller=1 && datepagej ='".$dateJour."' && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";

            $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

            $S_Rcaisse = mysql_fetch_array($resS);

            $T_Rcaisse = $S_Rcaisse[0] + $T_Rcaisse;

        }



        if (($_SESSION['mutuelle']==1) && ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

            $T_ventes = 0;

            $T_ventesMobile = 0;



            if ($_SESSION['compte']==1) {

                /************ Début ventes compte caisse ***************/



                $sqlV="SELECT *

                FROM `".$nomtableDesignation."` d

                INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation

                INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

                WHERE l.classe = 0 && p.idClient=0  && p.verrouiller=1 && (p.idCompte=1 || p.idCompte=0) && p.datepagej ='".$dateJour."' && p.type=0 ";

                $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());

                $T_ventes_V = 0;

                while ($pagnet = mysql_fetch_array($resV)) {

                    $T_ventes_V=$T_ventes_V + $pagnet['prixtotal'];

                }

    

                $sqlM="SELECT *

                FROM `".$nomtableDesignation."` d

                INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation

                INNER JOIN `".$nomtableMutuellePagnet."` m ON m.idMutuellePagnet = l.idMutuellePagnet

                WHERE l.classe = 0 && m.idClient=0  && m.verrouiller=1 && (m.idCompte=1 || m.idCompte=0) && m.datepagej ='".$dateJour."' && m.type=0 ";

                $resM = mysql_query($sqlM) or die ("persoonel requête 2".mysql_error());

                $T_ventes_M = 0;

                while ($mutuelle = mysql_fetch_array($resM)) {

                    $T_ventes_M=$T_ventes_M + $mutuelle['apayerPagnet'];

                }

    

                $T_ventes = $T_ventes_V + $T_ventes_M;



                /************ Fin ventes compte caisse ***************/



                /************ Début ventes compte mobile ***************/



                $sqlV="SELECT *

                FROM `".$nomtableDesignation."` d

                INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation

                INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

                WHERE l.classe = 0 && p.idClient=0  && p.verrouiller=1 && p.idCompte<>0 && p.idCompte<>1 && p.idCompte<>2 && p.idCompte<>3 && p.idCompte<>1000 && p.datepagej ='".$dateJour."' && p.type=0 ";

                $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());

                $T_ventes_V = 0;

                while ($pagnet = mysql_fetch_array($resV)) {

                    $T_ventes_V=$T_ventes_V + $pagnet['prixtotal'];

                }

                $sqlM="SELECT *

                FROM `".$nomtableDesignation."` d

                INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation

                INNER JOIN `".$nomtableMutuellePagnet."` m ON m.idMutuellePagnet = l.idMutuellePagnet

                WHERE l.classe = 0 && m.idClient=0  && m.verrouiller=1 && m.idCompte<>0 && m.idCompte<>1 && m.idCompte<>2 && m.idCompte<>3 && m.datepagej ='".$dateJour."' && m.type=0 ";

                $resM = mysql_query($sqlM) or die ("persoonel requête 2".mysql_error());

                $T_ventes_M = 0;

                while ($mutuelle = mysql_fetch_array($resM)) {

                    $T_ventes_M=$T_ventes_M + $mutuelle['apayerPagnet'];

                }

    

                $T_ventesMobile = $T_ventes_V + $T_ventes_M;



                /************ Fin ventes compte mobile ***************/


            } else {



                $sqlV="SELECT *

                FROM `".$nomtableDesignation."` d

                INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation

                INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

                WHERE l.classe = 0 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 ";

                $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());

                $T_ventes_V = 0;

                while ($pagnet = mysql_fetch_array($resV)) {

                    $T_ventes_V=$T_ventes_V + $pagnet['prixtotal'];

                }
    

                $sqlM="SELECT *

                FROM `".$nomtableDesignation."` d

                INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation

                INNER JOIN `".$nomtableMutuellePagnet."` m ON m.idMutuellePagnet = l.idMutuellePagnet

                WHERE l.classe = 0 && m.idClient=0  && m.verrouiller=1 && m.datepagej ='".$dateJour."' && m.type=0 ";

                $resM = mysql_query($sqlM) or die ("persoonel requête 2".mysql_error());

                $T_ventes_M = 0;
                $taux = 0;

                while ($mutuelle = mysql_fetch_array($resM)) {
                    $taux = $mutuelle['taux'];

                    $T_ventes_M=$T_ventes_M + $mutuelle['prixtotal'] - ($mutuelle['prixtotal']* $taux / 100);

                }

                $T_ventes = $T_ventes_V + $T_ventes_M;

            }

        }

        else{

            $T_ventes = 0; 

            $T_ventesMobile = 0;

            if ($_SESSION['compte']==1) {

                /************ Début ventes compte caisse ***************/

                $sqlV="SELECT *

                FROM `".$nomtableLigne."` l 

                INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

                WHERE l.classe = 0 && p.idClient=0 && p.verrouiller=1 && (p.idCompte=1 || p.idCompte=0) && p.datepagej ='".$dateJour."' && p.type=0 ";

                $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());

                while ($pagnet = mysql_fetch_array($resV)) {

                    if ($_SESSION['Pays']=='Canada') { 

                        $T_ventes=$T_ventes + $pagnet['prixtotal'] + $pagnet['prixtotalTvaP'] + $pagnet['prixtotalTvaR'];

                    }

                    else {

                        $T_ventes=$T_ventes + $pagnet['prixtotal'];

                    }


                }

                /************ Fin ventes compte caisse ***************/

                /************ Début ventes compte mobile ***************/

                $sqlV="SELECT *

                FROM  `".$nomtableLigne."` l 

                INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

                WHERE l.classe = 0 && p.idClient=0  && p.verrouiller=1 && p.idCompte<>0 && p.idCompte<>1 && p.idCompte<>2 && p.idCompte<>3 && p.idCompte<>1000 && p.datepagej ='".$dateJour."' && p.type=0 ";

                $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());

                while ($pagnet = mysql_fetch_array($resV)) {

                    if($_SESSION['Pays']=='Canada'){ 

                        $T_ventesMobile=$T_ventesMobile + $pagnet['prixtotal'] + $pagnet['prixtotalTvaP'] + $pagnet['prixtotalTvaR'];

                    }

                    else{

                        $T_ventesMobile=$T_ventesMobile + $pagnet['prixtotal'];  

                    }

                }

                /************ Fin ventes compte mobile ***************/
                /************ Début ventes compte multiple ***************/
                $mntCpt = 0;
                $sqlV="SELECT *

                FROM  `".$nomtablePagnet."` p WHERE p.idClient=0  && p.verrouiller=1 && p.idCompte=1000 && p.datepagej ='".$dateJour."' && p.type=0 ";

                $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());

                while ($pagnet = mysql_fetch_array($resV)) {

                    $idsComptes = explode('_', $pagnet['idsCpteMultiple']);
                    // var_dump($idsComptes);
                    foreach ($idsComptes as $key) {
                        $sqlM="SELECT *
                        FROM  `".$nomtableComptemouvement."` WHERE idCompte=".$key."  && mouvementLink=".$pagnet['idPagnet']."";

                        $resM = mysql_query($sqlM) or die ("persoonel requête Mv".mysql_error());
                        $cptMv = mysql_fetch_array($resM);
                        $mntCpt = $cptMv['montant'];

                        if ($key == '1' || $key == 1) {
                            $T_ventes = $T_ventes + $mntCpt;
                        } else {
                            $T_ventesMobile = $T_ventesMobile + $mntCpt;
                        }
                    }

                }

                /************ Fin ventes compte multiple ***************/

            }else{

                $sqlV="SELECT *

                FROM  `".$nomtableLigne."` l 

                INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

                WHERE l.classe = 0 && p.idClient=0 && p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 ";

                $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());

                while ($pagnet = mysql_fetch_array($resV)) {

                    if($_SESSION['Pays']=='Canada'){ 

                        $T_ventes=$T_ventes + $pagnet['prixtotal'] + $pagnet['prixtotalTvaP'] + $pagnet['prixtotalTvaR'];

                    }

                    else{

                        $T_ventes=$T_ventes + $pagnet['prixtotal'];     

                    }

                }

            }

        }

        $sqlRm="SELECT DISTINCT p.idPagnet

            FROM `".$nomtablePagnet."` p

            INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

            WHERE (l.classe=0 || l.classe=1)  &&  p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  && p.type=0 ";

        $resRm = mysql_query($sqlRm) or die ("persoonel requête 2".mysql_error());

        $T_remises = 0 ;

        $S_remises = 0;

        while ($pagnet = mysql_fetch_array($resRm)) {

            $sqlS="SELECT SUM(remise)

            FROM `".$nomtablePagnet."`

            where idClient=0 && verrouiller=1 && datepagej ='".$dateJour."' && type=0 && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";

            $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

            $S_remises = mysql_fetch_array($resS);

            $T_remises = $S_remises[0] + $T_remises;

        }

        

        $T_Rpagnet = 0;

        $S_Rpagnet = 0;

        $T_RpagnetMobile = 0;

        $S_RpagnetMobile = 0;

        if ($_SESSION['compte']==1) {

            $sqlRt="SELECT DISTINCT p.idPagnet

                FROM `".$nomtablePagnet."` p

                INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

                WHERE l.classe=0 && p.idClient=0  && p.verrouiller=1 && (p.idCompte=1 || p.idCompte=0) && p.datepagej ='".$dateJour."' && p.type=1  ORDER BY p.idPagnet DESC";

            $resRt = mysql_query($sqlRt) or die ("persoonel requête 2".mysql_error());

            

            while ($pagnet = mysql_fetch_array($resRt)) {

                $sqlS="SELECT SUM(apayerPagnet)

                FROM `".$nomtablePagnet."`

                where idClient=0 &&  verrouiller=1 && datepagej ='".$dateJour."' && type=1 && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";

                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                $S_Rpagnet = mysql_fetch_array($resS);

                $T_Rpagnet = $S_Rpagnet[0] + $T_Rpagnet;

            }



            $sqlRtMobile="SELECT DISTINCT p.idPagnet

                FROM `".$nomtablePagnet."` p

                INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

                WHERE l.classe=0 && p.idClient=0  && p.verrouiller=1 && p.idCompte<>0 && p.idCompte<>1 && p.idCompte<>2 && p.idCompte<>3 && p.datepagej ='".$dateJour."' && p.type=1  ORDER BY p.idPagnet DESC";

            $resRtMobile = mysql_query($sqlRtMobile) or die ("persoonel requête 2".mysql_error());

            

            while ($pagnet = mysql_fetch_array($resRtMobile)) {

                $sqlSMobile="SELECT SUM(apayerPagnet)

                FROM `".$nomtablePagnet."`

                where idClient=0 &&  verrouiller=1 && datepagej ='".$dateJour."' && type=1 && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";

                $resSMobile=mysql_query($sqlSMobile) or die ("select stock impossible =>".mysql_error());

                $S_RpagnetMobile = mysql_fetch_array($resSMobile);

                $T_RpagnetMobile = $S_RpagnetMobile[0] + $T_RpagnetMobile;

            }



        }else{

            $sqlRt="SELECT DISTINCT p.idPagnet

                FROM `".$nomtablePagnet."` p

                INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

                WHERE l.classe=0 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=1  ORDER BY p.idPagnet DESC";

            $resRt = mysql_query($sqlRt) or die ("persoonel requête 2".mysql_error());

            $T_Rpagnet = 0;

            $S_Rpagnet = 0;

            while ($pagnet = mysql_fetch_array($resRt)) {

                $sqlS="SELECT SUM(apayerPagnet)

                FROM `".$nomtablePagnet."`

                where idClient=0 &&  verrouiller=1 && datepagej ='".$dateJour."' && type=1 && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";

                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                $S_Rpagnet = mysql_fetch_array($resS);

                $T_Rpagnet = $S_Rpagnet[0] + $T_Rpagnet;

            }            

        }


        
        if (($_SESSION['mutuelle']==1) && ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

            $sqlSv="SELECT *

            FROM `".$nomtableDesignation."` d

            INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation

            INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

            WHERE l.classe = 1 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 ";

            $resSv = mysql_query($sqlSv) or die ("persoonel requête 2".mysql_error());

            $T_venteServ = 0;

            while ($pagnet = mysql_fetch_array($resSv)) {

                $T_venteServ = $T_venteServ + $pagnet['prixtotal'];

            }

            //******************** vente service mutuelle ***************** */

            $sqlSvM="SELECT *

            FROM `".$nomtableDesignation."` d

            INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation
            
            INNER JOIN `".$nomtableMutuellePagnet."` m ON m.idMutuellePagnet = l.idMutuellePagnet

            WHERE l.classe = 1 && m.idClient=0  && m.verrouiller=1 && m.datepagej ='".$dateJour."' && m.type=0 ";

            // echo $sqlSvM;

            $resSvM = mysql_query($sqlSvM) or die ("persoonel requête 2".mysql_error());

            $T_venteServM = 0;
            $taux = 0;

            while ($mutuelle = mysql_fetch_array($resSvM)) {
                $taux = $mutuelle['taux'];

                $T_venteServM = $T_venteServM + $mutuelle['prixtotal'] - ($mutuelle['prixtotal']* $taux / 100);

            }
            //******************** fin vente service mutuelle***************** */
            // var_dump($taux) ; 
            $T_venteServ = $T_venteServ + $T_venteServM;

        }else{

            $sqlSv="SELECT *

            FROM `".$nomtableDesignation."` d

            INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation

            INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

            WHERE l.classe = 1 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 ";

            $resSv = mysql_query($sqlSv) or die ("persoonel requête 2".mysql_error());

            $T_venteServ = 0;

            while ($pagnet = mysql_fetch_array($resSv)) {

                $T_venteServ = $T_venteServ + $pagnet['prixtotal'];

            }
            
        }


        $sqlD="SELECT DISTINCT p.idPagnet

            FROM `".$nomtablePagnet."` p

            INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

            WHERE l.classe=2 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  && p.type=0  ORDER BY p.idPagnet DESC";

        $resD = mysql_query($sqlD) or die ("persoonel requête 2".mysql_error());

        $T_depenses = 0;

        $S_depenses = 0;

        while ($pagnet = mysql_fetch_array($resD)) {

            $sqlS="SELECT SUM(apayerPagnet)

            FROM `".$nomtablePagnet."`

            where idClient=0 &&  verrouiller=1 && datepagej ='".$dateJour."' && idPagnet='".$pagnet['idPagnet']."' && type=0  ORDER BY idPagnet DESC";

            $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

            $S_depenses = mysql_fetch_array($resS);

            $T_depenses = $S_depenses[0] + $T_depenses;

        }



        $sqlBE="SELECT DISTINCT p.idPagnet

            FROM `".$nomtablePagnet."` p

            INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

            WHERE l.classe=6 && p.idClient!=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  && (p.type=0 || p.type=30)  ORDER BY p.idPagnet DESC";

        $resBE = mysql_query($sqlBE) or die ("persoonel requête 2".mysql_error());

        $T_bonEsp = 0;

        $S_bonEsp = 0;

        while ($pagnet = mysql_fetch_array($resBE)) {

            $sqlS="SELECT SUM(apayerPagnet)

            FROM `".$nomtablePagnet."`

            where idClient!=0 &&  verrouiller=1 && datepagej ='".$dateJour."' && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";

            $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

            $S_bonEsp = mysql_fetch_array($resS);

            $T_bonEsp = $S_bonEsp[0] + $T_bonEsp;

        }



        if (($_SESSION['mutuelle']==1) && ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

            $T_bonV = 0;

            $sqlBP="SELECT DISTINCT p.idPagnet

                FROM `".$nomtablePagnet."` p

                INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

                WHERE (l.classe!=6 && l.classe!=9) && p.idClient!=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."' && (p.type=0 || p.type=30)   ORDER BY p.idPagnet DESC";

            $resBP = mysql_query($sqlBP) or die ("persoonel requête 2".mysql_error());

            $T_bonP = 0;

            $S_bonP = 0;

            while ($pagnet = mysql_fetch_array($resBP)) {

                $sqlS="SELECT SUM(apayerPagnet)

                FROM `".$nomtablePagnet."`

                where idClient!=0 &&  verrouiller=1 && datepagej ='".$dateJour."' && idPagnet='".$pagnet['idPagnet']."' && (type=0 || type=30)   ORDER BY idPagnet DESC";

                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                $S_bonP = mysql_fetch_array($resS);

                $T_bonP = $S_bonP[0] + $T_bonP;

            }



            $sqlBM="SELECT DISTINCT m.idMutuellePagnet

                FROM `".$nomtableMutuellePagnet."` m

                INNER JOIN `".$nomtableLigne."` l ON l.idMutuellePagnet = m.idMutuellePagnet

                WHERE (l.classe!=6 && l.classe!=9) && m.idClient!=0  && m.verrouiller=1 && m.datepagej ='".$dateJour."' && (m.type=0 || m.type=30)   ORDER BY m.idMutuellePagnet DESC";

            $resBM = mysql_query($sqlBM) or die ("persoonel requête 2".mysql_error());

            $T_bonM = 0;

            $S_bonM = 0;

            while ($mutuelle = mysql_fetch_array($resBM)) {

                $sqlS="SELECT SUM(apayerPagnet)

                FROM `".$nomtableMutuellePagnet."`

                where idClient!=0 &&  verrouiller=1 && datepagej ='".$dateJour."' && idMutuellePagnet='".$mutuelle['idMutuellePagnet']."' && (type=0 || type=30)   ORDER BY idMutuellePagnet DESC";

                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                $S_bonM = mysql_fetch_array($resS);

                $T_bonM = $S_bonM[0] + $T_bonM;

            }

            $T_bonV = $T_bonP  + $T_bonM ;



            $sqlMB="SELECT DISTINCT m.idMutuellePagnet

                FROM `".$nomtableMutuellePagnet."` m

                INNER JOIN `".$nomtableLigne."` l ON l.idMutuellePagnet = m.idMutuellePagnet

                WHERE (l.classe!=6 && l.classe!=9) && m.verrouiller=1 && m.datepagej ='".$dateJour."' && (m.type=0 || m.type=30)   ORDER BY m.idMutuellePagnet DESC";

            $resMB = mysql_query($sqlMB) or die ("persoonel requête 2".mysql_error());

            $T_mutuelleB = 0;

            $S_mutuelleB = 0;

            while ($mutuelle = mysql_fetch_array($resMB)) {

                $sqlS="SELECT SUM(apayerMutuelle)

                FROM `".$nomtableMutuellePagnet."`

                where  verrouiller=1 && datepagej ='".$dateJour."' && idMutuellePagnet='".$mutuelle['idMutuellePagnet']."' && (type=0 || type=30)   ORDER BY idMutuellePagnet DESC";

                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                $S_mutuelleB = mysql_fetch_array($resS);

                $T_mutuelleB = $S_mutuelleB[0] + $T_mutuelleB;

            }

        }

        else{

            $sqlBP="SELECT DISTINCT p.idPagnet

                FROM `".$nomtablePagnet."` p

                INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet

                WHERE (l.classe!=6 && l.classe!=9) && p.idClient!=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."' && (p.type=0 || p.type=30)   ORDER BY p.idPagnet DESC";

            $resBP = mysql_query($sqlBP) or die ("persoonel requête 2".mysql_error());

            $T_bonP = 0;

            $S_bonP = 0;

            $Ttva_bonP = 0;

            while ($pagnet = mysql_fetch_array($resBP)) {

                $sqlS="SELECT SUM(apayerPagnet)

                FROM `".$nomtablePagnet."`

                where idClient!=0 &&  verrouiller=1 && datepagej ='".$dateJour."' && idPagnet='".$pagnet['idPagnet']."' && (type=0 || type=30)   ORDER BY idPagnet DESC";

                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                $S_bonP = mysql_fetch_array($resS);

                $T_bonP = $S_bonP[0] + $T_bonP;



                if($_SESSION['Pays']=='Canada'){ 

                    $sqlSP="SELECT SUM(apayerPagnetTvaP)

                    FROM `".$nomtablePagnet."`

                    where idClient!=0 &&  verrouiller=1 && datepagej ='".$dateJour."' && idPagnet='".$pagnet['idPagnet']."' && (type=0 || type=30)   ORDER BY idPagnet DESC";

                    $resSP=mysql_query($sqlSP) or die ("select stock impossible =>".mysql_error());

                    $StvaP_bonP = mysql_fetch_array($resSP);



                    $sqlSR="SELECT SUM(apayerPagnetTvaR)

                    FROM `".$nomtablePagnet."`

                    where idClient!=0 &&  verrouiller=1 && datepagej ='".$dateJour."' && idPagnet='".$pagnet['idPagnet']."' && (type=0 || type=30)   ORDER BY idPagnet DESC";

                    $resSR=mysql_query($sqlSR) or die ("select stock impossible =>".mysql_error());

                    $StvaR_bonR = mysql_fetch_array($resSR);



                    $Ttva_bonP = $Ttva_bonP + $StvaP_bonP[0] + $StvaR_bonR[0];

                }

            }

            $T_bonV=$T_bonP ;

            if($_SESSION['Pays']=='Canada'){ 

                $T_bonV=$T_bonP + $Ttva_bonP ;

            }

        }



        $T_versementClient=0;

        $sqlP5="SELECT SUM(montant) FROM `".$nomtableVersement."` where idClient!=0 && (dateVersement  ='".$dateJour."' || dateVersement  ='".$dateJour2."')  ORDER BY idVersement DESC";

        $resP5 = mysql_query($sqlP5) or die ("persoonel requête 2".mysql_error());

        $T_VClient = mysql_fetch_array($resP5) ;

        if($T_VClient[0]!=null){

            $T_versementClient=$T_VClient[0];

        }



        $T_versementFournisseur=0;

        $sqlP6="SELECT SUM(montant) FROM `".$nomtableVersement."` where idFournisseur!=0 && dateVersement  ='".$dateAnnee."'  ORDER BY idVersement DESC";

        $resP6 = mysql_query($sqlP6) or die ("persoonel requête 2".mysql_error());

        $T_VFournisseur = mysql_fetch_array($resP6) ;

        if($T_VFournisseur[0]!=null){

            $T_versementFournisseur=$T_VFournisseur[0];

        }



        if (($_SESSION['mutuelle']==1) && ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 

            $T_versementMutuelle=0;

            $sqlP6="SELECT SUM(montant) FROM `".$nomtableVersement."` where idMutuelle!=0 && dateVersement  ='".$dateJour."'  ORDER BY idVersement DESC";

            $resP6 = mysql_query($sqlP6) or die ("persoonel requête 2".mysql_error());

            $T_VMutuelle = mysql_fetch_array($resP6) ;

            if($T_VMutuelle[0]!=null){

                $T_versementMutuelle=$T_VMutuelle[0];

            }

        }


        if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 

            if ($_SESSION['mutuelle']==1){

                $T_Caisse=$T_App - $T_Rcaisse + $T_ventes - $T_remises - $T_Rpagnet + $T_venteServ - $T_depenses + $T_versementClient + $T_versementMutuelle - $T_versementFournisseur - $T_bonEsp;

            }

            else{

                $T_Caisse=$T_App - $T_Rcaisse + $T_ventes - $T_remises - $T_Rpagnet + $T_venteServ - $T_depenses + $T_versementClient - $T_versementFournisseur - $T_bonEsp;

            }

        }

        else{

            $sqlBN="SELECT *

            FROM `".$nomtableDesignation."` d

            INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation

            INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

            WHERE l.classe = 0 && p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 ";

            $resBN = mysql_query($sqlBN) or die ("persoonel requête 2".mysql_error());

            $T_prixAchat_US = 0;

            $T_prixAchat_UN = 0;

            $T_prixAchat_DM = 0;

            $T_prixventes = 0;

            while ($pagnet = mysql_fetch_array($resBN)) {

                if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

                    if ($pagnet['unitevente']==$pagnet['uniteStock']) {

                        $T_prixAchat_US = $T_prixAchat_US + ($pagnet['prixachat'] * $pagnet['quantite']);

                    }

                    else if ($pagnet['unitevente']=="Demi Gros") {

                        $T_prixAchat_DM = $T_prixAchat_DM + (($pagnet['prixachat'] / $pagnet['nbreArticleUniteStock']) * $pagnet['quantite']);

                    }

                    else{

                        $T_prixAchat_UN = $T_prixAchat_UN + (($pagnet['prixachat'] / $pagnet['nbreArticleUniteStock']) * $pagnet['quantite']);

                        

                    }

                    $T_prixventes=$T_prixventes + $pagnet['prixtotal'];

                }

                else{

                    if (($pagnet['unitevente']!="Article")&&($pagnet['unitevente']!="article")) {

                        $T_prixAchat_US = $T_prixAchat_US + ($pagnet['prixachat'] * $pagnet['quantite'] * $pagnet['nbreArticleUniteStock']);

                    }

                    else if(($pagnet['unitevente']=="Article")||($pagnet['unitevente']=="article")){

                        $T_prixAchat_UN = $T_prixAchat_UN + ($pagnet['prixachat'] * $pagnet['quantite']);

                    }

                    $T_prixventes=$T_prixventes + $pagnet['prixtotal'];

                }

            }

            $T_prixAchat=$T_prixAchat_US + $T_prixAchat_UN + $T_prixAchat_DM;

            $T_Benefice=$T_prixventes - $T_prixAchat;



            $T_Caisse=$T_App - $T_Rcaisse + $T_ventes - $T_remises - $T_Rpagnet + $T_venteServ - $T_depenses + $T_versementClient - $T_versementFournisseur - $T_bonEsp + $T_ventesMobile - $T_RpagnetMobile;

        }



        if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 

            if ($_SESSION['mutuelle']==1){

                $result='1<>'.number_format($T_App, 0, ',', ' ').'<>'.number_format($T_Rcaisse, 0, ',', ' ').'<>'.number_format($T_ventes, 0, ',', ' ').'<>'.number_format($T_Rpagnet, 0, ',', ' ').'<>'.number_format($T_venteServ, 0, ',', ' ').'<>'.number_format($T_depenses, 0, ',', ' ').'<>'.number_format($T_bonEsp, 0, ',', ' ').'<>'.number_format($T_bonV, 0, ',', ' ').'<>'.number_format($T_versementClient, 0, ',', ' ').'<>'.number_format($T_versementFournisseur, 0, ',', ' ').'<>'.number_format($T_Caisse, 0, ',', ' ').'<>0<>'.number_format($T_versementMutuelle, 0, ',', ' ').'<>'.number_format($T_ventesMobile, 0, ',', ' ').'<>'.number_format($T_mutuelleB, 0, ',', ' ');

            }

            else{

                $result='1<>'.number_format($T_App, 0, ',', ' ').'<>'.number_format($T_Rcaisse, 0, ',', ' ').'<>'.number_format($T_ventes, 0, ',', ' ').'<>'.number_format($T_Rpagnet, 0, ',', ' ').'<>'.number_format($T_venteServ, 0, ',', ' ').'<>'.number_format($T_depenses, 0, ',', ' ').'<>'.number_format($T_bonEsp, 0, ',', ' ').'<>'.number_format($T_bonV, 0, ',', ' ').'<>'.number_format($T_versementClient, 0, ',', ' ').'<>'.number_format($T_versementFournisseur, 0, ',', ' ').'<>'.number_format($T_Caisse, 0, ',', ' ').'<>0<>0<>'.number_format($T_ventesMobile, 0, ',', ' ');

            }

            

        }

        else{

            $result='1<>'.number_format($T_App, 0, ',', ' ').'<>'.number_format($T_Rcaisse, 0, ',', ' ').'<>'.number_format($T_ventes, 0, ',', ' ').'<>'.number_format($T_Rpagnet, 0, ',', ' ').'<>'.number_format($T_venteServ, 0, ',', ' ').'<>'.number_format($T_depenses, 0, ',', ' ').'<>'.number_format($T_bonEsp, 0, ',', ' ').'<>'.number_format($T_bonV, 0, ',', ' ').'<>'.number_format($T_versementClient, 0, ',', ' ').'<>'.number_format($T_versementFournisseur, 0, ',', ' ').'<>'.number_format($T_Caisse, 0, ',', ' ').'<>'.number_format($T_Benefice, 0, ',', ' ').'<>0<>'.number_format($T_ventesMobile, 0, ',', ' ');

        }

       

        exit($result);

    }
}	

    else if($operation == 84){



        $nombreArticleUniteStock=0;

        $result='0';



		$sql='select * from `'.$nomtableDesignation.'` where idDesignation="'.$idDesignation.'"';

		//echo $sql;

		$res=mysql_query($sql);

		if(mysql_num_rows($res)){

			if($design=mysql_fetch_array($res)) {

                $sql_qte="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$idDesignation."' ";
                $res_qte=mysql_query($sql_qte) or die ("select stock impossible =>".mysql_error());
                $qte_stock = mysql_fetch_array($res_qte) ;

                $nombreArticleUniteStock  = $design["nbreArticleUniteStock"];

                $categorie=$design["categorie"];

                if (($uniteStock=="Article")||($uniteStock=="article")||($uniteStock=="ARTICLE")||($uniteStock=="ARTICLES")){

                    $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,idBl,designation,quantiteStockinitial,uniteStock,prixunitaire,prixachat,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration,iduser) VALUES('.$idDesignation.','.$idBl.',"'.mysql_real_escape_string($design["designation"]).'",'.$quantite.',"'.mysql_real_escape_string($uniteStock).'",'.$prixUniteStock.','.$prixAchat.',1,'.$quantite.',"'.$dateString.'",'.$quantite.',"'.$dateExpiration.'","'.$_SESSION['iduser'].'")';

                    $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

                    $stock_Anc = $qte_stock[0] * $design['prixachat'];
                    $stock_Nv = $quantite * $prixAchat;
                    $cump = round(( $stock_Anc + $stock_Nv ) / ($qte_stock[0] + $quantite)) ;

                }

                else{

                    $totalArticleStock=$quantite*$nombreArticleUniteStock;

                    $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,idBl,designation,quantiteStockinitial,uniteStock,prixunitaire,prixuniteStock,prixachat,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration,iduser) VALUES('.$idDesignation.','.$idBl.',"'.mysql_real_escape_string($design['designation']).'",'.$quantite.',"'.mysql_real_escape_string($uniteStock).'",'.$design['prix'].','.$prixUniteStock.','.$prixAchat.','.$nombreArticleUniteStock.','.$totalArticleStock.',"'.$dateString.'",'.$totalArticleStock.',"'.$dateExpiration.'","'.$_SESSION['iduser'].'")';

                    $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

                    $stock_Anc = $qte_stock[0] * $design['prixachat'];
                    $stock_Nv = $totalArticleStock * $prixAchat;
                    $cump = round(( $stock_Anc + $stock_Nv ) / ($qte_stock[0] + $totalArticleStock)) ;

                }

                $sql0="UPDATE `".$nomtableDesignation."` set prixuniteStock='".$prixUniteStock."', prix='".$design['prix']."', prixachat='".$cump."' WHERE idDesignation='".$idDesignation."' ";
                $res0=mysql_query($sql0) or die ("update quantiteStockCourant impossible =>".mysql_error());

		   }

	    }



       $sql3='SELECT * from  `'.$nomtableStock.'` where quantiteStockCourant!=0 and idDesignation="'.$idDesignation.'" order by idStock DESC';

       $res3=mysql_query($sql3);

       $stock=mysql_fetch_array($res3);



       $result='1<>'.$stock['designation'].'<>'.$design['uniteStock'].'<>'.$stock['quantiteStockinitial'].'<>'.$stock['prixuniteStock'].'<>'.$stock['prixunitaire'].'<>'.$stock['prixachat'];



       exit($result);





    }

    else if($operation == 85){

        $sql="SELECT * FROM `". $nomtableStock."` where idStock='".$idStock."'";

        $res=mysql_query($sql);

        $result="0";

        if($res){

            $stock=mysql_fetch_array($res);

            if (($stock['uniteStock']=="Article")||($stock['uniteStock']=="article")||($stock['uniteStock']=="ARTICLE")||($stock['uniteStock']=="ARTICLES")){

                $result='1<>'.$stock['designation'].'<>'.$stock['uniteStock'].'<>'.$stock['quantiteStockinitial'].'<>'.$stock['prixunitaire'].'<>'.$stock['prixachat'].'<>'.$stock['dateExpiration'].'<>'.$stock['dateStockage'];

            }

            else{

                $result='1<>'.$stock['designation'].'<>'.$stock['uniteStock'].'<>'.$stock['quantiteStockinitial'].'<>'.$stock['prixuniteStock'].'<>'.$stock['prixachat'].'<>'.$stock['dateExpiration'].'<>'.$stock['dateStockage'];

            }



        }

        exit($result);

    }

    else if($operation == 86){

        $result="0";

        $sql="SELECT * FROM `". $nomtableStock."` where idStock='".$idStock."'";

        $res=mysql_query($sql);

        if($res){

            $stock=mysql_fetch_array($res);

            if (($stock['uniteStock']=="Article")||($stock['uniteStock']=="article")||($stock['uniteStock']=="ARTICLE")||($stock['uniteStock']=="ARTICLES")){

                $totalArticleStock=$quantite;

                $sql1="UPDATE `".$nomtableStock."` set quantiteStockinitial='".$totalArticleStock."',quantiteStockCourant='".$totalArticleStock."',totalArticleStock='".$totalArticleStock."',prixunitaire='".$prixUniteStock."',prixachat='".$prixAchat."',dateExpiration='".$dateExpiration."'  where idStock='".$idStock."'";

                $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

                if($res1){

                    if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

                        $result='2<>'.$stock['designation'].'<>'.$stock['uniteStock'].'<>'.$quantite.'<>'.$stock['prixuniteStock'].'<>'.$stock['prixachat'].'<>'.$stock['dateStockage'].'<>'.$dateExpiration;

                    }

                    else{

                        $result='1<>'.$stock['designation'].'<>'.$stock['uniteStock'].'<>'.$quantite.'<>'.$stock['prixuniteStock'].'<>'.$stock['prixunitaire'].'<>'.$stock['prixachat'].'<>'.$stock['dateStockage'].'<>'.$dateExpiration;

                    }

                }

            }

            else{

                $totalArticleStock=$quantite * $stock['nbreArticleUniteStock'];

                $sql1="UPDATE `".$nomtableStock."` set quantiteStockinitial='".$quantite."',quantiteStockCourant='".$totalArticleStock."',totalArticleStock='".$totalArticleStock."',prixuniteStock='".$prixUniteStock."',prixachat='".$prixAchat."',dateExpiration='".$dateExpiration."'  where idStock='".$idStock."'";

                $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

                if($res1){

                    if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

                        $result='2<>'.$stock['designation'].'<>'.$stock['uniteStock'].'<>'.$quantite.'<>'.$stock['prixuniteStock'].'<>'.$stock['prixachat'].'<>'.$stock['dateStockage'].'<>'.$dateExpiration;

                    }

                    else{

                        $result='1<>'.$stock['designation'].'<>'.$stock['uniteStock'].'<>'.$quantite.'<>'.$stock['prixuniteStock'].'<>'.$stock['prixunitaire'].'<>'.$stock['prixachat'].'<>'.$stock['dateStockage'].'<>'.$dateExpiration;

                    }

                }

            }

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 87){

        $result="0";

        $sql="SELECT * FROM `". $nomtableStock."` where idStock='".$idStock."'";

        $res=mysql_query($sql);

        if($res){

            $stock=mysql_fetch_array($res);

            $sql1="DELETE FROM `".$nomtableStock."` WHERE idStock='".$idStock."'";

            $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

            if($res1){

                if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

                    $result='2<>'.$stock['designation'].'<>'.$stock['uniteStock'].'<>'.$stock['quantiteStockinitial'].'<>'.$stock['prixuniteStock'].'<>'.$stock['prixachat'].'<>'.$stock['dateStockage'].'<>'.$stock['dateExpiration'];

                }

                else{

                    $result='1<>'.$stock['designation'].'<>'.$stock['uniteStock'].'<>'.$stock['quantiteStockinitial'].'<>'.$stock['prixuniteStock'].'<>'.$stock['prixunitaire'].'<>'.$stock['prixachat'].'<>'.$stock['dateStockage'].'<>'.$stock['dateExpiration'];

                }

            }

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 88){

        $sql="SELECT * FROM `".$nomtableDesignation."` where idDesignation='".$idDesignation."'";

        $res=mysql_query($sql);

        $result="0";

        if($res){

            $design=mysql_fetch_array($res);

            if (($uniteStock=="Article")||($uniteStock=="article")||($uniteStock=="ARTICLE")||($uniteStock=="ARTICLES")){

                $result='1<>'.$design['prix'];

            }

            else{

                $result='1<>'.$design['prixuniteStock'];

            }



        }

        exit($result);

    }

    else if($operation == 89){

        $result="0";

        $sql="SELECT * FROM `". $nomtableEntrepotTransfert."` where idEntrepotTransfert='".$idEntrepotTransfert."'";

        $res=mysql_query($sql);

        if($res){

            $transfert=mysql_fetch_array($res);

            $sql1="UPDATE `".$nomtableEntrepotTransfert."` set etat1=1 where idEntrepotTransfert='".$idEntrepotTransfert."'";

            $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

            if($res1){

                $result="1";

            }

        }

        exit($result);

    }

    else if($operation == 90){

        $result="0";

        $sql="SELECT * FROM `". $nomtableEntrepotTransfert."` where idEntrepotTransfert='".$idEntrepotTransfert."'";

        $res=mysql_query($sql);

        if($res){

            $transfert=mysql_fetch_array($res);

            $sql1="UPDATE `".$nomtableEntrepotTransfert."` set etat2=1 where idEntrepotTransfert='".$idEntrepotTransfert."'";

            $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

            if($res1){

                $result="1";

            }

        }

        exit($result);

    }

    else if($operation == 91){



        $nombreArticleUniteStock=0;

        $categorie='Sans';



		$sql='select * from `'.$nomtableDesignation.'` where idDesignation="'.$idDesignation.'"';

		//echo $sql;

		$res=mysql_query($sql);

		if(mysql_num_rows($res)){

			if($design=mysql_fetch_array($res)) {

                $sql_qte="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$idDesignation."' ";
                $res_qte=mysql_query($sql_qte) or die ("select stock impossible =>".mysql_error());
                $qte_stock = mysql_fetch_array($res_qte) ;

                $nombreArticleUniteStock  = $design["nbreArticleUniteStock"];

                $categorie=$design["categorie"];

                $uniteStock=="Article";

                $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixachat,prixunitaire,prixuniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration,iduser) VALUES('.$idDesignation.',"'.mysql_real_escape_string($design["designation"]).'",'.$quantite.',"'.mysql_real_escape_string($uniteStock).'",'.$prixAchat.','.$prixUniteStock.','.$prixUniteStock.',1,'.$quantite.',"'.$dateString.'",'.$quantite.',"'.$dateExpiration.'","'.$_SESSION['iduser'].'")';

                $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

                $stock_Anc = $qte_stock[0] * $design['prixachat'];
                $stock_Nv = $quantite * $prixAchat;
                $cump = round(( $stock_Anc + $stock_Nv ) / ($qte_stock[0] + $quantite)) ;

                $sql0="UPDATE `".$nomtableDesignation."` set prixuniteStock='".$prixUniteStock."', prix='".$prixUniteStock."', prixachat='".$cump."' WHERE idDesignation='".$idDesignation."' ";
                $res0=mysql_query($sql0) or die ("update quantiteStockCourant impossible =>".mysql_error());

		   }

	    }



       $sql3='SELECT * from  `'.$nomtableStock.'` where quantiteStockCourant!=0 and idDesignation="'.$idDesignation.'" order by idStock DESC';

       $res3=mysql_query($sql3);

       $stock=mysql_fetch_array($res3);



       $result=$stock['designation'].'<>'.$design['codeBarreDesignation'].'<>'.$stock['uniteStock'].'<>'.$stock['nbreArticleUniteStock'].'<>'.$prixUniteStock.'<>'.$prixUniteStock.'<>'.$prixAchat;



       exit($result);





    }

    else if($operation == 92){

        $nbreArticleUniteStock=@htmlspecialchars($_POST["nbreArticleUniteStock"]);

        if ($designation!='' && $nbreArticleUniteStock!='' && $uniteStock!='' && $prixUnitaire!=''){

            $sql11="SELECT * FROM `". $nomtableDesignation."` where designation='".$designation."'";

            $res11=mysql_query($sql11);

            if(!mysql_num_rows($res11)){

                if ($uniteStock=="article" || $uniteStock=="Article"){

                    $sql="insert into `".$nomtableDesignation."` (designation,classe,prixachat,prix,uniteStock,prixuniteStock,nbreArticleUniteStock,codeBarreDesignation,categorie)

                    values ('".mysql_real_escape_string($designation)."',0,".$prixAchat.",".$prixUnitaire.",'Article','".$prixUnitaire."','1','".mysql_real_escape_string($codeBarre)."','".mysql_real_escape_string($categorie)."')";

                    $res=@mysql_query($sql) or die ("insertion impossible Produit en Article".mysql_error());

                }

                else {

                    $sql="insert into `".$nomtableDesignation."` (designation,classe,prixachat,prix,uniteStock,prixuniteStock,nbreArticleUniteStock,codeBarreDesignation,categorie)

                    values ('".mysql_real_escape_string($designation)."',0,".$prixAchat.",".$prixUnitaire.",'".mysql_real_escape_string($uniteStock)."','".$prixUniteStock."','".$nbreArticleUniteStock."','".mysql_real_escape_string($codeBarre)."','".mysql_real_escape_string($categorie)."')";

                    $res=@mysql_query($sql) or die ("insertion impossible Produit en uniteStock".mysql_error());

                }

                if($_SESSION['vitrine']==1){
                  /********************** Début alert mise à jour **********************************/      
                  $sql11="SELECT * FROM `". $nomtableDesignation."` ORDER BY idDesignation DESC LIMIT 1";
          
                  $res11=mysql_query($sql11);    
          
                  $key = mysql_fetch_array($res11);
          
          
                  $req4 = $bddV->prepare("INSERT INTO `".$nomtableDesignation."` (idDesignation,designation,designationJcaisse,categorie,uniteStock,nbreArticleUniteStock,prix,prixuniteStock,idBoutique)
          
                  VALUES(:idD,:des,:desJC,:categorie,:us,:nbus,:prix,:pus,:idB)") ;
          
                  $req4->execute(array(
          
                      'idD' => $key['idDesignation'],
          
                      'des' => $key['designation'],
          
                      'desJC' =>$key['designation'],
          
                      'categorie' => $key['categorie'],
          
                      'us' => $key['uniteStock'],
          
                      'nbus' => $key['nbreArticleUniteStock'],
          
                      'prix' => $key['prix'],
          
                      'pus' => $key['prixuniteStock'],
          
                      'idB' => $_SESSION['idBoutique']
          
                  ))  or die(print_r($req4->errorInfo()));
          
                  $req4->closeCursor();
          
                  /***************************** Fin alert mise à jour ****************************/ 
          
                //   var_dump(2);
                }

            }



        }

        else{



        }



       $sql3='SELECT * from  `'.$nomtableDesignation.'` where uniteStock="'.$uniteStock.'" and prix="'.$prixUnitaire.'" and designation="'.$designation.'" ';

       $res3=mysql_query($sql3);

       $design=mysql_fetch_array($res3);



       $result=$design['designation'].'+'.$design['codeBarreDesignation'].'+'.$design['uniteStock'].'+'.$design['nbreArticleUniteStock'].'+'.$design['prixuniteStock'].'+'.$design['prix'].'+'.$design['prixachat'];



       exit($result);





    }

    else if($operation == 93){

        $nbreArticleUniteStock=@htmlspecialchars($_POST["nbreArticleUniteStock"]);

        $uniteDetails=@htmlspecialchars($_POST["uniteDetails"]);

        if ($designation!='' && $nbreArticleUniteStock!='' && $uniteStock!='' && $prixUniteStock!=''){

            $sql11="SELECT * FROM `". $nomtableDesignation."` where designation='".$designation."'";

            $res11=mysql_query($sql11);

            if(!mysql_num_rows($res11)){

                $sql="insert into `".$nomtableDesignation."` (designation,classe,uniteStock,prixuniteStock,nbreArticleUniteStock,uniteDetails,prixachat,categorie)values ('".mysql_real_escape_string($designation)."',0,'".mysql_real_escape_string($uniteStock)."','".$prixUniteStock."','".$nbreArticleUniteStock."','".mysql_real_escape_string($uniteDetails)."','".$prixAchat."','".mysql_real_escape_string($categorie)."')";

                $res=@mysql_query($sql) or die ("insertion impossible Produit en ".mysql_error());

            }



        }

        else{



        }



       $sql3='SELECT * from  `'.$nomtableDesignation.'` where uniteStock="'.$uniteStock.'" and prixuniteStock="'.$prixUniteStock.'" and designation="'.$designation.'" ';

       $res3=mysql_query($sql3);

       $design=mysql_fetch_array($res3);



       $result=$design['designation'].'+'.$design['categorie'].'+'.$design['uniteStock'].'+'.$design['nbreArticleUniteStock'].'+'.$design['prixuniteStock'].'+'.$design['prixachat'];



       exit($result);





    }

    else if($operation == 94){

        $result="0";

        $sql='select * from `'.$nomtableDesignation.'` where designation="'.$designation.'"';

		$res=mysql_query($sql);

		if($design=mysql_fetch_array($res)) {

            $nombreArticleUniteStock  = $design["nbreArticleUniteStock"];

            $categorie=$design["categorie"];

            $totalArticleStock=($quantite * $nombreArticleUniteStock);

            $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixuniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration,iduser) VALUES('.$design["idDesignation"].',"'.mysql_real_escape_string($design["designation"]).'",'.$quantite.',"'.mysql_real_escape_string($uniteStock).'",'.$design['prixuniteStock'].','.$nombreArticleUniteStock.','.$totalArticleStock.',"'.$dateString.'",'.$totalArticleStock.',"'.$dateExpiration.'","'.$_SESSION['iduser'].'")';

            $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

            if($res1){

                $result="1";

            }

        }

       exit($result);

    }

    else if($operation == 95){



        $nombreArticleUniteStock=0;

        $categorie='Sans';



		$sql='select * from `'.$nomtableDesignation.'` where idDesignation="'.$idDesignation.'"';

		//echo $sql;

		$res=mysql_query($sql);

		if(mysql_num_rows($res)){

			if($design=mysql_fetch_array($res)) {

                $nombreArticleUniteStock  = $design["nbreArticleUniteStock"];

                $categorie=$design["categorie"];



                $force=0;

                $sqlJ="SELECT SUM(quantite)

                FROM `".$nomtableInventaire."`

                where idDesignation='".$idDesignation."' and dateInventaire='".$dateString."' and type=5   ";

                $resJ=mysql_query($sqlJ) or die ("select stock impossible =>".mysql_error());

                $J_stock = mysql_fetch_array($resJ);

                if($J_stock!=null){

                    $force=$J_stock[0];

                }



                if (($uniteStock=="Article")||($uniteStock=="article")){

                    $quantiteCourant=$quantite - $force;

                    $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration,iduser) VALUES('.$idDesignation.',"'.mysql_real_escape_string($design["designation"]).'",'.$quantite.',"'.mysql_real_escape_string($uniteStock).'",'.$design['prix'].',1,'.$quantite.',"'.$dateString.'",'.$quantiteCourant.',"'.$dateExpiration.'","'.$_SESSION['iduser'].'")';

                    $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

                }

                else{

                    $totalArticleStock=$quantite*$nombreArticleUniteStock;

                    $quantiteCourant=$totalArticleStock - $force;

                    $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,prixuniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration,iduser) VALUES('.$idDesignation.',"'.mysql_real_escape_string($design['designation']).'",'.$quantite.',"'.mysql_real_escape_string($uniteStock).'",'.$design['prix'].','.$design['prixuniteStock'].','.$nombreArticleUniteStock.','.$totalArticleStock.',"'.$dateString.'",'.$quantiteCourant.',"'.$dateExpiration.'","'.$_SESSION['iduser'].'")';

                    $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

                }



		   }

	    }



       $sql3='SELECT * from  `'.$nomtableStock.'` where quantiteStockCourant!=0 and idDesignation="'.$idDesignation.'" order by idStock DESC';

       $res3=mysql_query($sql3);

       $stock=mysql_fetch_array($res3);



       $result=$stock['designation'].'<>'.$design['codeBarreDesignation'].'<>'.$stock['uniteStock'].'<>'.$stock['nbreArticleUniteStock'].'<>'.$stock['prixuniteStock'].'<>'.$stock['prixunitaire'].'<>'.$design['prixachat'];



       exit($result);





    }

    else if($operation == 96){

        $result="0";

        $sql='select * from `'.$nomtableDesignation.'` where designation="'.$designation.'"';

		$res=mysql_query($sql);

		if($design=mysql_fetch_array($res)) {

            $nombreArticleUniteStock  = $design["nbreArticleUniteStock"];

            $categorie=$design["categorie"];

            $uniteStock='Article';

            

            $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration,iduser) VALUES('.$design["idDesignation"].',"'.mysql_real_escape_string($design["designation"]).'",'.$quantite.',"'.mysql_real_escape_string($uniteStock).'",'.$design['prix'].',1,'.$quantite.',"'.$dateString.'",'.$quantite.',"'.$dateExpiration.'","'.$_SESSION['iduser'].'")';

                $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

                if($res1){

                    $result="1";

                }



        }

       exit($result);

    }

    else if($operation == 97){

        $result="0";

        $sql="SELECT * FROM `". $nomtableStock."` where idStock='".$idStock."'";

        $res=mysql_query($sql);

        if($res){

            $stock=mysql_fetch_array($res);

            $sql1="UPDATE `".$nomtableStock."` set idBl='".$idBl."'  where idStock='".$idStock."'";

            $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());

            if($res1){

                $result="1";

            }

        }

        else{

            $result="0";

        }

        exit($result);

    }

    else if($operation == 1000){

        $sql="SELECT * FROM `". $nomtableEntrepotStock."` where idEntrepotStock='".$idEntrepotStock ."'";

        $res=mysql_query($sql);

        $result="0";

        if($res){

            $stock=mysql_fetch_array($res);

            $sql3="SELECT * from  `".$nomtableDesignation."` where idDesignation=".$stock['idDesignation'];

            $res3=mysql_query($sql3);

            $design=mysql_fetch_array($res3);

            if($stock['quantiteStockCourant']!=0 && $stock['quantiteStockCourant']!=null){

                $result=$stock['designation'].'<>'.$stock['quantiteStockinitial'].'<>'.$stock['quantiteStockCourant'].'<>'.$stock['uniteStock'].'<>'.$stock['dateExpiration'].'<>'.$stock['dateStockage'].'<>'.$design['prix'].'<>'.$design['codeBarreuniteStock'].'<>'.$stock['idDesignation'];

            }

            else{

                $result=$stock['designation'].'<>'.$stock['quantiteStockinitial'].'<>0<>'.$stock['uniteStock'].'<>'.$stock['dateExpiration'].'<>'.$stock['dateStockage'].'<>'.$design['prix'].'<>'.$design['codeBarreuniteStock'].'<>'.$stock['idDesignation'];

            }



        }

        exit($result);

    }
    // else if($operation == 98){

    //     $sql="SELECT * FROM `". $nomtableStock."` where idStock='".$idStock."'";

    //     $res=mysql_query($sql);

    //     $result="0";

    //     if($res){

    //         $stock=mysql_fetch_array($res);

    //         $sql3="SELECT * from  `".$nomtableDesignation."` where idDesignation=".$stock['idDesignation'];

    //         $res3=mysql_query($sql3);

    //         $design=mysql_fetch_array($res3);

    //         if($stock['quantiteStockCourant']!=0 && $stock['quantiteStockCourant']!=null){

    //             $result=$stock['designation'].'<>'.$stock['quantiteStockinitial'].'<>'.$stock['quantiteStockCourant'].'<>'.$stock['uniteStock'].'<>'.$stock['dateExpiration'].'<>'.$stock['dateStockage'].'<>'.$design['prix'].'<>'.$design['codeBarreuniteStock'];

    //         }

    //         else{

    //             $result=$stock['designation'].'<>'.$stock['quantiteStockinitial'].'<>0<>'.$stock['uniteStock'].'<>'.$stock['dateExpiration'].'<>'.$stock['dateStockage'].'<>'.$design['prix'].'<>'.$design['codeBarreuniteStock'];

    //         }



    //     }

    //     exit($result);

    // }


    else if ($operation == 98) {

        $result="0";
        $quantiteInventaire=$quantite;
            
        $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$idDesignation."'";
        $res1=mysql_query($sql1);
        $stock=mysql_fetch_array($res1);

        $sql="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$idDesignation."'";
        $res=mysql_query($sql);
        $sommeStock=mysql_fetch_array($res);
                
        $quantiteStockCourantTotal=($sommeStock[0]) ? $sommeStock[0] : 0 ;

        $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=0 where idDesignation=".$idDesignation;

        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
        

        if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {


            $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,prixPublic,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,iduser) VALUES('.$idDesignation.',"'.mysql_real_escape_string($stock["designation"]).'",'.$quantiteInventaire.','.$stock['prixPublic'].',1,'.$quantiteInventaire.',"'.$dateString.'",'.$quantiteInventaire.',"'.$_SESSION['iduser'].'")';

            $res1=@mysql_query($sql1) or die ("insertion stock 1 impossible".mysql_error()) ; 


            $sql4='INSERT INTO `'.$nomtableInventaire.'` (idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)
            
            VALUES ('.$stock['idDesignation'].','.$quantiteInventaire.',1,'.$quantiteStockCourantTotal.',"'.$dateString.'",0)';
            // var_dump($sql4);
            $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;

        } else {
            # code...

            $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,iduser) VALUES('.$idDesignation.',"'.mysql_real_escape_string($stock["designation"]).'",'.$quantiteInventaire.',"Article",'.$stock['prix'].',1,'.$quantiteInventaire.',"'.$dateString.'",'.$quantiteInventaire.',"'.$_SESSION['iduser'].'")';

            $res1=@mysql_query($sql1) or die ("insertion stock 1 impossible".mysql_error()) ; 


            $sql4='INSERT INTO `'.$nomtableInventaire.'` (idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)
            
            VALUES ('.$stock['idDesignation'].','.$quantiteInventaire.','.$stock['nbreArticleUniteStock'].','.$quantiteStockCourantTotal.',"'.$dateString.'",0)';
            // var_dump($sql4);
            $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;


        }

        exit($result);

    }
    
    else if($operation == 99){

        $result="0";
        $quantiteInventaire=$quantite;

        $sql="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$idDesignation."'";
        $res=mysql_query($sql);
        $sommeStock=mysql_fetch_array($res);
                
        $quantiteStockCourantTotal=($sommeStock[0]) ? $sommeStock[0] : 0 ;
            
        $sql1="SELECT * FROM `". $nomtableStock."` where idDesignation='".$idDesignation."' ORDER BY idStock DESC LIMIT 1";
        $res1=mysql_query($sql1);
        $stock=mysql_fetch_array($res1);
        $idLastStock=$stock['idStock'];

        if ($quantiteStockCourantTotal > 0) {
            
            $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=quantiteStockCourant+".$quantiteInventaire." where idStock=".$idLastStock;

            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());


            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {


                $sql4='INSERT INTO `'.$nomtableInventaire.'` (idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)
                
                VALUES ('.$stock['idDesignation'].','.$quantiteInventaire.',1,'.$quantiteStockCourantTotal.',"'.$dateString.'",0)';
                // var_dump($sql4);
                $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;
    
            } else {

                $sql4='INSERT INTO `'.$nomtableInventaire.'` (idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)
                
                VALUES ('.$stock['idDesignation'].','.$quantiteInventaire.','.$stock['nbreArticleUniteStock'].','.$quantiteStockCourantTotal.',"'.$dateString.'",0)';
                // var_dump($sql4);
                $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;
    
    
            }

        } else {


            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {


                $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,prixPublic,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,iduser) VALUES('.$idDesignation.',"'.mysql_real_escape_string($stock["designation"]).'",'.$quantiteInventaire.','.$stock['prixPublic'].',1,'.$quantiteInventaire.',"'.$dateString.'",'.$quantiteInventaire.',"'.$_SESSION['iduser'].'")';
    
                $res1=@mysql_query($sql1) or die ("insertion stock 1 impossible".mysql_error()) ; 


                $sql4='INSERT INTO `'.$nomtableInventaire.'` (idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)
                
                VALUES ('.$stock['idDesignation'].','.$quantiteInventaire.',1,'.$quantiteStockCourantTotal.',"'.$dateString.'",0)';
                // var_dump($sql4);
                $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;
    
            } else {
                # code...
    
                $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,iduser) VALUES('.$idDesignation.',"'.mysql_real_escape_string($stock["designation"]).'",'.$quantiteInventaire.',"Article",'.$stock['prix'].',1,'.$quantiteInventaire.',"'.$dateString.'",'.$quantiteInventaire.',"'.$_SESSION['iduser'].'")';
    
                $res1=@mysql_query($sql1) or die ("insertion stock 1 impossible".mysql_error()) ; 


                $sql4='INSERT INTO `'.$nomtableInventaire.'` (idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)
                
                VALUES ('.$stock['idDesignation'].','.$quantiteInventaire.','.$stock['nbreArticleUniteStock'].','.$quantiteStockCourantTotal.',"'.$dateString.'",0)';
                // var_dump($sql4);
                $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;
    
    
            }          

        }

        exit($result);

    }
    else if($operation == 100){
        
        $dateEnregistrement=@htmlspecialchars($_POST["dateJour"]);
        if($dateEnregistrement!='' && $dateEnregistrement!=null){
            $jourEnregistrement=$dateEnregistrement;
        }
        else{
            $jourEnregistrement=$dateString;
        }

        $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$idDesignation."'";
        $res1=mysql_query($sql1);
        $designation=mysql_fetch_array($res1);

        if($designation!=null){

            $sqlS="SELECT SUM(quantiteStockCourant) FROM `".$nomtableEntrepotStock."` 
            where quantiteStockCourant<>0 AND idDesignation ='".$idDesignation."' AND idEntrepot='".$idDepot."'  ";
            $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
            $S_stock = mysql_fetch_array($resS);
    
            $restant=$quantite * $designation['nbreArticleUniteStock'];

            if($S_stock[0] > 0 && $S_stock[0] >= $restant){

                $sqlD="SELECT idEntrepotStock,quantiteStockCourant,idStock FROM `".$nomtableEntrepotStock."` where idDesignation='".$idDesignation."' AND idEntrepot='".$idDepot."' AND quantiteStockCourant<>0 ORDER BY idEntrepotStock ASC ";
    
                $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
        
                while ($stock = mysql_fetch_assoc($resD)) {
        
                    if($restant>= 0){
        
                        $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;
        
                        if($quantiteStockCourant > 0){
        
                            $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$quantiteStockCourant." where idEntrepotStock=".$stock['idEntrepotStock'];
        
                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                            $totalArticleStock=$restant;
                            $quantiteStock=$totalArticleStock / $designation['nbreArticleUniteStock'];

                            if($totalArticleStock>0){

                                if($idEntrepot!=0){
                                    $sql1='INSERT INTO `'.$nomtableEntrepotStock.'` (idStock,idEntrepot,idDesignation,idTransfert,designation,quantiteStockinitial,uniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant)
            
                                    VALUES('.$stock['idStock'].','.$idEntrepot.','.$idDesignation.','.$stock['idEntrepotStock'].',"'.mysql_real_escape_string($designation["designation"]).'",'.$quantiteStock.',"'.mysql_real_escape_string($designation['uniteStock']).'",'.$designation['nbreArticleUniteStock'].','.$totalArticleStock.',"'.$jourEnregistrement.'",'.$totalArticleStock.')';
                
                                    $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;
                                }
                                else{
                                    $sql1='INSERT INTO `'.$nomtableEntrepotStock.'` (idStock,idEntrepot,idDesignation,idTransfert,designation,quantiteStockinitial,uniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant)
            
                                    VALUES('.$stock['idStock'].',0,'.$idDesignation.','.$stock['idEntrepotStock'].',"'.mysql_real_escape_string($designation["designation"]).'",'.$quantiteStock.',"'.mysql_real_escape_string($designation['uniteStock']).'",'.$designation['nbreArticleUniteStock'].','.$totalArticleStock.',"'.$jourEnregistrement.'",0)';
                
                                    $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;


                                    $sql2='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixuniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration,iduser) 
                                    VALUES('.$designation['idDesignation'].',"'.mysql_real_escape_string($designation['designation']).'",'.$quantiteStock.',"'.mysql_real_escape_string($designation['uniteStock']).'",'.$designation['prixuniteStock'].','.$designation['nbreArticleUniteStock'].','.$totalArticleStock.',"'.$jourEnregistrement.'",'.$totalArticleStock.',"'.$dateExpiration.'","'.$_SESSION['iduser'].'")';

                                    $res2=@mysql_query($sql2) or die ("insertion stock 11 impossible".mysql_error()) ;
                                }
                            }
        
                        }
        
                        else{
        
                            $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=0 where idEntrepotStock=".$stock['idEntrepotStock'];
        
                            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                            $totalArticleStock=$stock['quantiteStockCourant'];
                            $quantiteStock=$totalArticleStock / $designation['nbreArticleUniteStock'];

                            if($totalArticleStock>0){
                                if($idEntrepot!=0){
                                    $sql1='INSERT INTO `'.$nomtableEntrepotStock.'` (idStock,idEntrepot,idDesignation,idTransfert,designation,quantiteStockinitial,uniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant)
            
                                    VALUES('.$stock['idStock'].','.$idEntrepot.','.$idDesignation.','.$stock['idEntrepotStock'].',"'.mysql_real_escape_string($designation["designation"]).'",'.$quantiteStock.',"'.mysql_real_escape_string($designation['uniteStock']).'",'.$designation['nbreArticleUniteStock'].','.$totalArticleStock.',"'.$jourEnregistrement.'",'.$totalArticleStock.')';
                
                                    $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;
                                }
                                else{
                                    $sql1='INSERT INTO `'.$nomtableEntrepotStock.'` (idStock,idEntrepot,idDesignation,idTransfert,designation,quantiteStockinitial,uniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant)
            
                                    VALUES('.$stock['idStock'].',0,'.$idDesignation.','.$stock['idEntrepotStock'].',"'.mysql_real_escape_string($designation["designation"]).'",'.$quantiteStock.',"'.mysql_real_escape_string($designation['uniteStock']).'",'.$designation['nbreArticleUniteStock'].','.$totalArticleStock.',"'.$jourEnregistrement.'",0)';
                
                                    $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;
    
                                    $sql2='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixuniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration,iduser) 
                                    VALUES('.$designation['idDesignation'].',"'.mysql_real_escape_string($designation['designation']).'",'.$quantiteStock.',"'.mysql_real_escape_string($designation['uniteStock']).'",'.$designation['prixuniteStock'].','.$designation['nbreArticleUniteStock'].','.$totalArticleStock.',"'.$jourEnregistrement.'",'.$totalArticleStock.',"'.$dateExpiration.'","'.$_SESSION['iduser'].'")';
    
                                    $res2=@mysql_query($sql2) or die ("insertion stock 11 impossible".mysql_error()) ;
                                }
                            }
        
                        }
        
                        $restant= $restant - $stock['quantiteStockCourant'] ;
        
                    }
        
                }

                $sql4="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$idDepot."'";
                $res4=mysql_query($sql4);
                $depot_debut=mysql_fetch_array($res4);

                $sql5="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$idEntrepot."'";
                $res5=mysql_query($sql5);
                $depot_fin=mysql_fetch_array($res5);

                $result='1<>'.$designation['designation'].'<>'.$depot_debut['nomEntrepot'].'<>'.$jourEnregistrement.'<>'.$depot_fin['nomEntrepot'].'<>'.$quantite;
                                
            }
            else{

                $result="0";
    
            }

        }
        else{

            $result="0";

        }
        
        exit($result);

    }
    else if ($operation == 102) {
        $result="0";
        
        $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$idDesignation."'";
        $res1=mysql_query($sql1);
        
        $designation=mysql_fetch_array($res1);
        if($designation!=null){
           $result="1";
        }


        exit($result);

    }
    else if ($operation == 103) {
        $result="0";
       
        $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$idDesignation."'";
        $res1=mysql_query($sql1);
        $design=mysql_fetch_array($res1);

        if($design!=null){
            if($design['archiver']!=1){
                $sqlA="UPDATE `".$nomtableDesignation."` set archiver=1 WHERE idDesignation=".$idDesignation;
                $resA=mysql_query($sqlA) or die ("update quantiteStockCourant impossible =>".mysql_error());

                $sqlS="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation ='".$design['idDesignation']."'  ";
                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                $S_stock = mysql_fetch_array($resS);

                $sqlB="UPDATE `".$nomtableStock."` set quantiteStockCourant=0 WHERE idDesignation=".$idDesignation;
                $resB=mysql_query($sqlB) or die ("update quantiteStockCourant impossible =>".mysql_error());

                $sql4='INSERT INTO `'.$nomtableInventaire.'` (idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)
                VALUES('.$design['idDesignation'].',0,'.$design['nbreArticleUniteStock'].','.$S_stock[0].',"'.$dateString.'",5)';
                $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;

                if($_SESSION['vitrine']==1){
                    $req20 = $bddV->prepare("UPDATE `".$nomtableDesignation."` SET archiver=:archiver WHERE idDesignation = :idD");
                    $req20->execute(array(
                        'archiver' => 1,
                        'idD' => $idDesignation
                    )) or die(print_r($req20->errorInfo()));
                }
            }
            else{
                $sqlA="UPDATE `".$nomtableDesignation."` set archiver=0 WHERE idDesignation=".$idDesignation;
                $resA=mysql_query($sqlA) or die ("update quantiteStockCourant impossible =>".mysql_error());

                if($_SESSION['vitrine']==1){
                    $req20 = $bddV->prepare("UPDATE `".$nomtableDesignation."` SET archiver=:archiver WHERE idDesignation = :idD");
                    $req20->execute(array(
                        'archiver' => 0,
                        'idD' => $idDesignation
                    )) or die(print_r($req20->errorInfo()));
                }
            }
            $result=$design['idDesignation'].'<>'.$design['designation'].'<>'.$design['categorie'].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$design['prixuniteStock'].'<>'.$design['prix'];
        }

        exit($result);

    }
    
    else if ($operation == 104) {

        $result="0";
        $quantiteInventaire=$quantite;

        $sql="SELECT SUM(quantiteStockCourant) FROM `".$nomtableEntrepotStock."` where idDesignation='".$idDesignation."' and idEntrepot='".$idEntrepot."'";
        $res=mysql_query($sql);
        $sommeStock=mysql_fetch_array($res);
                
        $quantiteStockCourantTotal=($sommeStock[0]) ? $sommeStock[0] : 0 ;
            
        $sql1="SELECT * FROM `". $nomtableEntrepotStock."` where idDesignation='".$idDesignation."' and idEntrepot='".$idEntrepot."' ORDER BY idEntrepotStock DESC LIMIT 1";
        $res1=mysql_query($sql1);
        $stock=mysql_fetch_array($res1);
        $idLastStock=$stock['idEntrepotStock'];
        
        $sql01s="SELECT * FROM `". $nomtableStock."` where idDesignation='".$idDesignation."' ORDER BY idStock DESC LIMIT 1";
        $resS01s=mysql_query($sql01s);
        $stock01s=mysql_fetch_array($resS01s);
        $idLastS=$stock01s['idStock'];
        
        $sql2="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$idDesignation."'";
        $res2=mysql_query($sql2);
        $design=mysql_fetch_array($res2);
        // var_dump($design);

        if ($quantiteStockCourantTotal > 0) {
            // var_dump('>0');
            $totalArticleStock = $quantiteInventaire * $stock['nbreArticleUniteStock'];
            // $totalArticleStock = $quantiteInventaire * $stock['nbreArticleUniteStock'];
            
            $sqlS0="UPDATE `".$nomtableStock."` set quantiteStockinitial=quantiteStockinitial+".$quantiteInventaire.", totalArticleStock=totalArticleStock+".$totalArticleStock." where idStock=".$idLastS;

            $resS0=mysql_query($sqlS0) or die ("update quantiteStockCourant impossible =>".mysql_error());

            $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=quantiteStockCourant+".$totalArticleStock.", quantiteStockinitial=quantiteStockinitial+".$quantiteInventaire.", totalArticleStock=totalArticleStock+".$totalArticleStock." where idEntrepotStock=".$idLastStock;

            $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

            $sqlSI="UPDATE `".$nomtableInventaire."` set quantiteStockCourant=quantiteStockCourant+".$totalArticleStock.", quantite=quantite+".$quantiteInventaire." where idEntrepotStock=".$idLastStock;

            $resSI=mysql_query($sqlSI) or die ("update nomtableInventaire impossible =>".mysql_error());

            $sql0="UPDATE `".$nomtableDesignation."` set prixuniteStock='".$prixUniteStock."', prix='".$prixUnitaire."', prixachat='".$prixAchat."' WHERE idDesignation='".$idDesignation."' ";
            $res0=mysql_query($sql0) or die ("update prixuniteStock impossible =>".mysql_error());


            // if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {


            //     $sql4='INSERT INTO `'.$nomtableInventaire.'` (idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)
                
            //     VALUES ('.$stock['idDesignation'].','.$quantiteInventaire.',1,'.$quantiteStockCourantTotal.',"'.$dateString.'",0)';
            //     // var_dump($sql4);
            //     $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;
    
            // } else {

            //     $sql4='INSERT INTO `'.$nomtableInventaire.'` (idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)
                
            //     VALUES ('.$stock['idDesignation'].','.$quantiteInventaire.','.$stock['nbreArticleUniteStock'].','.$quantiteStockCourantTotal.',"'.$dateString.'",0)';
            //     // var_dump($sql4);
            //     $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;
    
    
            // } 
            
            $result = "1";

        } else {

            // var_dump('else');

            // if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {


            //     $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,prixPublic,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,iduser) VALUES('.$idDesignation.',"'.mysql_real_escape_string($stock["designation"]).'",'.$quantiteInventaire.','.$stock['prixPublic'].',1,'.$quantiteInventaire.',"'.$dateString.'",'.$quantiteInventaire.',"'.$_SESSION['iduser'].'")';
    
            //     $res1=@mysql_query($sql1) or die ("insertion stock 1 impossible".mysql_error()) ; 


            //     $sql4='INSERT INTO `'.$nomtableInventaire.'` (idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)
                
            //     VALUES ('.$stock['idDesignation'].','.$quantiteInventaire.',1,'.$quantiteStockCourantTotal.',"'.$dateString.'",0)';
            //     // var_dump($sql4);
            //     $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;
    
            // } else {
            //     # code...
    
                // $sql1='INSERT INTO `'.$nomtableEntrepotStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,iduser) VALUES('.$idDesignation.',"'.mysql_real_escape_string($stock["designation"]).'",'.$quantiteInventaire.',"Article",'.$stock['prix'].',1,'.$quantiteInventaire.',"'.$dateString.'",'.$quantiteInventaire.',"'.$_SESSION['iduser'].'")';
    
                // $res1=@mysql_query($sql1) or die ("insertion stock 1 impossible".mysql_error()) ; 

                $totalArticleStock = $quantiteInventaire * $stock['nbreArticleUniteStock'];
                
                $sql01='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,prixuniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant) VALUES('.$idDesignation.',"'.mysql_real_escape_string($design['designation']).'",'.$quantiteInventaire.',"'.mysql_real_escape_string($design['uniteStock']).'",'.$design['prix'].','.$design['prixuniteStock'].','.$design['nbreArticleUniteStock'].','.$totalArticleStock.',"'.$dateString.'",0)';

                $res01=@mysql_query($sql01) or die ("insertion stock 2 impossible".mysql_error()) ;

                
                $sql01s="SELECT * FROM `". $nomtableStock."` where idDesignation='".$idDesignation."' ORDER BY idStock DESC LIMIT 1";
                $resS01s=mysql_query($sql01s);
                $stock01s=mysql_fetch_array($resS01s);
                $idLastS=$stock01s['idStock'];

                $sql1='INSERT INTO `'.$nomtableEntrepotStock.'` (idStock,idEntrepot,idDesignation,designation,quantiteStockinitial,uniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant)

                VALUES('.$idLastS.','.$idEntrepot.','.$idDesignation.',"'.mysql_real_escape_string($stock["designation"]).'",'.$quantiteInventaire.',"'.mysql_real_escape_string($stock['uniteStock']).'",'.$stock['nbreArticleUniteStock'].','.$totalArticleStock.',"'.$dateString.'",'.$totalArticleStock.')';
                // var_dump($sql1);
                $res1=@mysql_query($sql1) or die ("insertion stock 12 impossible".mysql_error()) ;

                
                $sql01Es="SELECT * FROM `". $nomtableEntrepotStock."` where idDesignation='".$idDesignation."' ORDER BY idEntrepotStock DESC LIMIT 1";
                $resS01Es=mysql_query($sql01Es);
                $stock01Es=mysql_fetch_array($resS01Es);
                $idLastES=$stock01Es['idEntrepotStock'];

                $sql4='INSERT INTO `'.$nomtableInventaire.'` (idEntrepotStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)
                VALUES('.$idLastES.','.$design['idDesignation'].','.$quantiteInventaire.','.$design['nbreArticleUniteStock'].','.$totalArticleStock.',"'.$dateString.'",0)';
                $res4=mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;
                
                $sql0="UPDATE `".$nomtableDesignation."` set prixuniteStock='".$prixUniteStock."', prix='".$prixUnitaire."', prixachat='".$prixAchat."' WHERE idDesignation='".$idDesignation."' ";
                $res0=mysql_query($sql0) or die ("update prixuniteStock impossible =>".mysql_error());
                // var_dump($sql4);
                // var_dump($res4);
            //     $sql4='INSERT INTO `'.$nomtableInventaire.'` (idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)
                
            //     VALUES ('.$stock['idDesignation'].','.$quantiteInventaire.','.$stock['nbreArticleUniteStock'].','.$quantiteStockCourantTotal.',"'.$dateString.'",0)';
            //     // var_dump($sql4);
            //     $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;
    
            $result = "1";
    
            // }          

        }

        exit($result);

    }
    else if ($operation == 105) {

        $result="0";
        $quantiteInventaire=$quantite;        

        $sql0="UPDATE `".$nomtableDesignation."` set prixuniteStock='".$prixUniteStock."', prix='".$prixUnitaire."', prixachat='".$prixAchat."' WHERE idDesignation='".$idDesignation."' ";
        $res0=mysql_query($sql0) or die ("update prixuniteStock impossible =>".mysql_error());
        
        $sql2="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$idDesignation."'";
        $res2=mysql_query($sql2);
        $design=mysql_fetch_array($res2);
        
        $sqlI="SELECT SUM(quantiteStockinitial*nbreArticleUniteStock)
        FROM `".$nomtableStock."`
        where idDesignation ='".$idDesignation."'  ";
        $resI=mysql_query($sqlI) or die ("select stock impossible =>".mysql_error());
        $I_stock = mysql_fetch_array($resI);
    
        $sqlE="SELECT SUM(quantiteStockinitial*nbreArticleUniteStock)
        FROM `".$nomtableEntrepotStock."`
        where idDesignation ='".$idDesignation."' AND (idTransfert=0 OR idTransfert IS NULL)   ";
        $resE=mysql_query($sqlE) or die ("select stock impossible =>".mysql_error());
        $E_stock = mysql_fetch_array($resE);

        $diffInit = $I_stock[0] - $E_stock[0];

        if ($diffInit<0) {
            
            $sql01='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,prixuniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant) VALUES('.$idDesignation.',"'.mysql_real_escape_string($design['designation']).'",'.($diffInit*(-1)).',"'.mysql_real_escape_string($design['uniteStock']).'",'.$design['prix'].','.$design['prixuniteStock'].','.$design['nbreArticleUniteStock'].',0,"'.$dateString.'",0)';

            $res01=@mysql_query($sql01) or die ("insertion stock 2 impossible".mysql_error()) ;
        }
        
        $sqlS0="UPDATE `".$nomtableStock."` set quantiteStockCourant=0 where idDesignation='".$idDesignation."'";

        $resS0=mysql_query($sqlS0) or die ("update quantiteStockCourant impossible =>".mysql_error());

        $sqlS0="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=0 where idDesignation='".$idDesignation."'";

        $resS0=mysql_query($sqlS0) or die ("update quantiteStockCourant impossible =>".mysql_error());

        
        $totalArticleStock = $quantiteInventaire * $design['nbreArticleUniteStock'];

        $sql01='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,prixuniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant) VALUES('.$idDesignation.',"'.mysql_real_escape_string($design['designation']).'",'.$quantiteInventaire.',"'.mysql_real_escape_string($design['uniteStock']).'",'.$design['prix'].','.$design['prixuniteStock'].','.$design['nbreArticleUniteStock'].','.$totalArticleStock.',"'.$dateString.'",0)';

        $res01=@mysql_query($sql01) or die ("insertion stock 2 impossible".mysql_error()) ;
            
        $sql01s="SELECT * FROM `". $nomtableStock."` where idDesignation='".$idDesignation."' ORDER BY idStock DESC LIMIT 1";
        $resS01s=mysql_query($sql01s);
        $stock01s=mysql_fetch_array($resS01s);
        $idLastS=$stock01s['idStock'];

        $sql1='INSERT INTO `'.$nomtableEntrepotStock.'` (idStock,idEntrepot,idDesignation,designation,quantiteStockinitial,uniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant)

        VALUES('.$idLastS.','.$idEntrepot.','.$idDesignation.',"'.mysql_real_escape_string($design["designation"]).'",'.$quantiteInventaire.',"'.mysql_real_escape_string($design['uniteStock']).'",'.$design['nbreArticleUniteStock'].','.$totalArticleStock.',"'.$dateString.'",'.$totalArticleStock.')';
        $res1=@mysql_query($sql1) or die ("insertion stock 12 impossible".mysql_error()) ;
        
        $sql01Es="SELECT * FROM `". $nomtableEntrepotStock."` where idDesignation='".$idDesignation."' ORDER BY idEntrepotStock DESC LIMIT 1";
        $resS01Es=mysql_query($sql01Es);
        $stock01Es=mysql_fetch_array($resS01Es);
        $idLastES=$stock01Es['idEntrepotStock'];

        $sql4='INSERT INTO `'.$nomtableInventaire.'` (idEntrepotStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)
        VALUES('.$idLastES.','.$design['idDesignation'].','.$quantiteInventaire.','.$design['nbreArticleUniteStock'].','.$totalArticleStock.',"'.$dateString.'",0)';
        $res4=mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;
        
        
        $result = "1";

        ////////////////

        // $sql="SELECT SUM(quantiteStockCourant) FROM `".$nomtableEntrepotStock."` where idDesignation='".$idDesignation."' and idEntrepot='".$idEntrepot."'";
        // $res=mysql_query($sql);
        // $sommeStock=mysql_fetch_array($res);
                
        // $quantiteStockCourantTotal=($sommeStock[0]) ? $sommeStock[0] : 0 ;
            
        // $sql1="SELECT * FROM `". $nomtableEntrepotStock."` where idDesignation='".$idDesignation."' and idEntrepot='".$idEntrepot."' ORDER BY idEntrepotStock DESC LIMIT 1";
        // $res1=mysql_query($sql1);
        // $stock=mysql_fetch_array($res1);
        // $idLastStock=$stock['idEntrepotStock'];
        
        // $sql01s="SELECT * FROM `". $nomtableStock."` where idDesignation='".$idDesignation."' ORDER BY idStock DESC LIMIT 1";
        // $resS01s=mysql_query($sql01s);
        // $stock01s=mysql_fetch_array($resS01s);
        // $idLastS=$stock01s['idStock'];
        // // var_dump($design);

        // if ($quantiteStockCourantTotal > 0) {
        //     // var_dump('>0');
        //     $totalArticleStock = $quantiteInventaire * $stock['nbreArticleUniteStock'];
        //     // $totalArticleStock = $quantiteInventaire * $stock['nbreArticleUniteStock'];
            
        //     $sqlS0="UPDATE `".$nomtableStock."` set quantiteStockinitial=quantiteStockinitial+".$quantiteInventaire.", totalArticleStock=totalArticleStock+".$totalArticleStock." where idStock=".$idLastS;

        //     $resS0=mysql_query($sqlS0) or die ("update quantiteStockCourant impossible =>".mysql_error());

        //     $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=quantiteStockCourant+".$totalArticleStock.", quantiteStockinitial=quantiteStockinitial+".$quantiteInventaire.", totalArticleStock=totalArticleStock+".$totalArticleStock." where idEntrepotStock=".$idLastStock;

        //     $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

        //     $sqlSI="UPDATE `".$nomtableInventaire."` set quantiteStockCourant=quantiteStockCourant+".$totalArticleStock.", quantite=quantite+".$quantiteInventaire." where idEntrepotStock=".$idLastStock;

        //     $resSI=mysql_query($sqlSI) or die ("update nomtableInventaire impossible =>".mysql_error());

        //     $sql0="UPDATE `".$nomtableDesignation."` set prixuniteStock='".$prixUniteStock."', prix='".$prixUnitaire."', prixachat='".$prixAchat."' WHERE idDesignation='".$idDesignation."' ";
        //     $res0=mysql_query($sql0) or die ("update prixuniteStock impossible =>".mysql_error());
            
        //     $result = "1";

        // } else {

        //     $totalArticleStock = $quantiteInventaire * $stock['nbreArticleUniteStock'];
            
        //     $sql01='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixunitaire,prixuniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant) VALUES('.$idDesignation.',"'.mysql_real_escape_string($design['designation']).'",'.$quantiteInventaire.',"'.mysql_real_escape_string($design['uniteStock']).'",'.$design['prix'].','.$design['prixuniteStock'].','.$design['nbreArticleUniteStock'].','.$totalArticleStock.',"'.$dateString.'",0)';

        //     $res01=@mysql_query($sql01) or die ("insertion stock 2 impossible".mysql_error()) ;

            
        //     $sql01s="SELECT * FROM `". $nomtableStock."` where idDesignation='".$idDesignation."' ORDER BY idStock DESC LIMIT 1";
        //     $resS01s=mysql_query($sql01s);
        //     $stock01s=mysql_fetch_array($resS01s);
        //     $idLastS=$stock01s['idStock'];

        //     $sql1='INSERT INTO `'.$nomtableEntrepotStock.'` (idStock,idEntrepot,idDesignation,designation,quantiteStockinitial,uniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant)

        //     VALUES('.$idLastS.','.$idEntrepot.','.$idDesignation.',"'.mysql_real_escape_string($stock["designation"]).'",'.$quantiteInventaire.',"'.mysql_real_escape_string($stock['uniteStock']).'",'.$stock['nbreArticleUniteStock'].','.$totalArticleStock.',"'.$dateString.'",'.$totalArticleStock.')';
        //     // var_dump($sql1);
        //     $res1=@mysql_query($sql1) or die ("insertion stock 12 impossible".mysql_error()) ;

            
        //     $sql01Es="SELECT * FROM `". $nomtableEntrepotStock."` where idDesignation='".$idDesignation."' ORDER BY idEntrepotStock DESC LIMIT 1";
        //     $resS01Es=mysql_query($sql01Es);
        //     $stock01Es=mysql_fetch_array($resS01Es);
        //     $idLastES=$stock01Es['idEntrepotStock'];

        //     $sql4='INSERT INTO `'.$nomtableInventaire.'` (idEntrepotStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)
        //     VALUES('.$idLastES.','.$design['idDesignation'].','.$quantiteInventaire.','.$design['nbreArticleUniteStock'].','.$totalArticleStock.',"'.$dateString.'",0)';
        //     $res4=mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;
            
        //     $sql0="UPDATE `".$nomtableDesignation."` set prixuniteStock='".$prixUniteStock."', prix='".$prixUnitaire."', prixachat='".$prixAchat."' WHERE idDesignation='".$idDesignation."' ";
        //     $res0=mysql_query($sql0) or die ("update prixuniteStock impossible =>".mysql_error());
            
        //     $result = "1";
    
        //     // }          

        // }

        exit($result);

    }
    