<?php 
    session_start();
if(!$_SESSION['iduserBack'])
    header('Location:../index.php');

  require('../connection.php');
  require('../connectionPDO.php');

  require('../declarationVariables.php');
 

 
$operation =@$_POST["operation"];

/*
if($operation == 1){

    $refTransfPm =@$_POST["refTransfPm"].'@';

	$sql="select * from `aaa-payement-salaire` where refTransfert like '%$refTransfPm%' order by datePS desc  LIMIT 0,1 ";

	$res=@mysql_query($sql);
	
	$tab=@mysql_fetch_array($res);
	

	$result=$tab['montantFixePayement'];
	exit($result);
}else */
if($operation == 1){

    $refTransfPm =@$_POST["refTransfPm"].'@';

    $sql="select * from `aaa-payement-salaire` where refTransfert like '%$refTransfPm%' order by datePS desc  LIMIT 0,1 ";

    $res=@mysql_query($sql);
    
    $tab=@mysql_fetch_array($res);
    

    $result=$tab['montantFixePayement'];
    exit($result);
}
else if($operation == 2){
    
    $dateTransfert=$_POST['dateTransfert'];
    $montantTransfert=$_POST['montantFixePayementM'];
    $refTransfPm='Sans reference';
    $refTransf='';
    $numTel=$_POST['numTelPm'];
    $typeCompteMobile=$_POST['typeCompteMobile'];
    $montantFixePayementTotalPm=$_POST['montantFixePayementTotalPm'];
    $nbrMoisPm=$_POST['nbrMoisPm'];
    $activer=1;
    $montantNonConforme=0;

    if(isset($_POST['refTransfPm'])){
        if ($_POST['refTransfPm'] !='') {
                // code...
            $refTransfPm=$_POST['refTransfPm'];
        }
    }

        $date = new DateTime();
        //R�cup�ration de l'ann�e
        $annee =$date->format('Y');
        //R�cup�ration du mois
        $mois =$date->format('m');
        //R�cup�ration du jours
        $jour =$date->format('d');
        $heureString=$date->format('H:i:s');

        $dateString=$annee.'-'.$mois.'-'.$jour;
        $dateString2=$jour.'-'.$mois.'-'.$annee;

        $dateHeures=$dateString.' '.$heureString;
    
        $datetime2 = new DateTime($dateString);
        $annee2 =$datetime2->format('Y');
        $mois2 =$datetime2->format('m');
        $regulateur =$datetime2->format('m');

    for($i=0;$i<$nbrMoisPm;$i++){
            
            $somme=$mois2+$i;
            if($somme>12){
                $mois2=$somme-12;
                $annee2=$annee2+1;
            }else{
                $mois2=$regulateur+$i;
            }
            $dateString2=$jour.'-'.$mois2.'-'.$annee2;
            $refTransf=$refTransfPm."@".$i;
            //var_dump($refTransf);
            if (($refTransf!="")&&($numTel!="")){
                $sql2="SELECT * FROM `aaa-payement-reference` where refTransfertValidation='".$refTransf."'";
                //var_dump($sql2);
                $res2 = mysql_query($sql2) or die ("acces requête 1".mysql_error());
                if(!@mysql_num_rows($res2)){
                    
                    $sql3="insert into `aaa-payement-reference` (dateRefTransfertValidation,montant,refTransfertValidation,telRefTransfertValidation) 
                    values('".$dateTransfert."',".$montantTransfert.",'".$refTransf."','".$numTel."')";
                    //var_dump($sql3);
                    $res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas wave ".mysql_error());

                    $sql4="SELECT * FROM `aaa-payement-salaire` where refTransfert='".$refTransf."'";
                    $res4 = mysql_query($sql4) or die ("acces requête 4".mysql_error());
                    if(@mysql_num_rows($res4)){
                        $paiement = mysql_fetch_array($res4);
                        if ($paiement['refTransfert']==$refTransf and $paiement['montantFixePayement']<=$montantTransfert){
                            $sql5="UPDATE `aaa-payement-salaire` set  aPayementBoutique='".$activer."' where refTransfert='".$refTransf."'";
                            $res5=@mysql_query($sql5) or die ("mise à jour acces pour activer ou pas2 ".mysql_error());

                            $sql6="UPDATE `aaa-payement-reference` set  idPS=".$paiement['idPS']." where refTransfertValidation='".$refTransf."'";
                            $res6=@mysql_query($sql6) or die ("mise à jour acces pour activer ou pas2 ".mysql_error());

                        }else{
                             $sql5="UPDATE `aaa-payement-reference` set  `montantNonConforme`='".$montantNonConforme."',`idPS`=".$paiement['idPS']." where refTransfertValidation='".$refTransf."'";
                            //var_dump($sql5);
                            $res5=@mysql_query($sql5) or die ("mise à jour acces pour activer ou pas5ff ".mysql_error());
                        }
                    }
                }
                
            }
    }
    
    /**********************************TABLE COMPTE *****************************************/ 
                          $sql8="SELECT * FROM `aaa-payement-reference` where refTransfertValidation='".$refTransf."' and dateRefTransfertValidation='".$dateTransfert."'";
                          $res8 = mysql_query($sql8) or die ("acces requête 3".mysql_error());
                          $payementReference =mysql_fetch_assoc($res8);
                            //var_dump($sql8);
                            ///var_dump($payementReference);
                          $sqlv="select * from `aaa-compte` where nomCompte='".$typeCompteMobile."'";
                          $resv=mysql_query($sqlv);
                          $compte =mysql_fetch_assoc($resv);
                          if($compte){
                              $operation='depot';
                              $idCompte=$compte['idCompte'];
                              $description=$refTransfPm;
                              $newMontant=$compte['montantCompte']+$montantFixePayementTotalPm;

                              $sql6="insert into `aaa-comptemouvement` (montant,operation,idCompte,dateSaisie,dateOperation,description,idUser,idPR) 
                              values('".$montantFixePayementTotalPm."','".$operation."','".$idCompte."','".$dateHeures."','".$dateTransfert."','".$description."','".$_SESSION['iduserBack']."','".$payementReference['id']."')";
                              //var_dump($sql6);
                              $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error() );

                              $sql7="UPDATE `aaa-compte` set  montantCompte='".$newMontant."' where  idCompte=".$compte['idCompte']."";
                              //var_dump($sql7);
                              $res7=@mysql_query($sql7) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
                          }
    /********************************** TABLE COMPTE *****************************************/
    
}else if($operation == 333){  
    $idPS=$_POST['idPS'];
    $idBoutique=$_POST['idBoutique'];
    $datePaiement=$_POST['datePaiement'];
    $heurePaiement=$_POST['heurePaiement'];
    $dateTransfert=$_POST['dateTransfert'];
    $montantTransfert=$_POST['montantFixePayementM'];
    $refTransfPm='Sans reference';
    $refTransf='';
    $numTel=$_POST['numTelPm'];
    $typeCompteMobile=$_POST['typeCompteMobile'];
    $montantFixePayementTotalPm=$_POST['montantFixePayementTotalPm'];
    $nbrMoisPm=$_POST['nbrMoisPm'];
    $activer=1;
    $montantNonConforme=0;

    if(isset($_POST['refTransfPm'])){
        if ($_POST['refTransfPm'] !='') {
                // code...
            $refTransfPm=$_POST['refTransfPm'];
        }
    }

    $date = new DateTime();
        //R�cup�ration de l'ann�e
        $annee =$date->format('Y');
        //R�cup�ration du mois
        $mois =$date->format('m');
        //R�cup�ration du jours
        $jour =$date->format('d');
        $heureString=$date->format('H:i:s');

        $dateString=$annee.'-'.$mois.'-'.$jour;
        $dateString2=$jour.'-'.$mois.'-'.$annee;

        $dateHeures=$dateString.' '.$heureString;
    
        $datetime2 = new DateTime($dateString);
        $annee2 =$datetime2->format('Y');
        $mois2 =$datetime2->format('m');
        $regulateur =$datetime2->format('m');

    for($i=0;$i<$nbrMoisPm;$i++){
            
            $somme=$mois2+$i;
            if($somme>12){
                $mois2=$somme-12;
                $annee2=$annee2+1;
            }else{
                $mois2=$regulateur+$i;
            }
            $dateString2=$jour.'-'.$mois2.'-'.$annee2;
            $refTransf=$refTransfPm."@".$i;
            //var_dump($refTransf);
            if (($refTransf!="")&&($numTel!="")){
                $sql2="SELECT * FROM `aaa-payement-reference` where refTransfertValidation='".$refTransf."'";
                //var_dump($sql2);
                $res2 = mysql_query($sql2) or die ("acces requête 1".mysql_error());
                if(!@mysql_num_rows($res2)){
                    
                    $sql3="insert into `aaa-payement-reference` (dateRefTransfertValidation,montant,refTransfertValidation,telRefTransfertValidation) 
                    values('".$dateTransfert."',".$montantTransfert.",'".$refTransf."','".$numTel."')";
                    //var_dump($sql3);
                    $res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas wave ".mysql_error());

                    $sql4="SELECT * FROM `aaa-payement-salaire` where refTransfert='".$refTransf."'";
                    $res4 = mysql_query($sql4) or die ("acces requête 4".mysql_error());
                    if(@mysql_num_rows($res4)){
                        $paiement = mysql_fetch_array($res4);
                        if ($paiement['refTransfert']==$refTransf and $paiement['montantFixePayement']<=$montantTransfert){
                            $sql5="UPDATE `aaa-payement-salaire` set  aPayementBoutique='".$activer."' where refTransfert='".$refTransf."'";
                            $res5=@mysql_query($sql5) or die ("mise à jour acces pour activer ou pas2 ".mysql_error());

                            $sql6="UPDATE `aaa-payement-reference` set  idPS=".$paiement['idPS']." where refTransfertValidation='".$refTransf."'";
                            $res6=@mysql_query($sql6) or die ("mise à jour acces pour activer ou pas2 ".mysql_error());

                        }else{
                             $sql5="UPDATE `aaa-payement-reference` set  `montantNonConforme`='".$montantNonConforme."',`idPS`=".$paiement['idPS']." where refTransfertValidation='".$refTransf."'";
                            //var_dump($sql5);
                            $res5=@mysql_query($sql5) or die ("mise à jour acces pour activer ou pas5ff ".mysql_error());
                        }
                    }
                }
                
            }
    }
    
    /**********************************TABLE COMPTE *****************************************/ 
                          $sql8="SELECT * FROM `aaa-payement-reference` where refTransfertValidation='".$refTransf."' and dateRefTransfertValidation='".$dateTransfert."'";
                          $res8 = mysql_query($sql8) or die ("acces requête 3".mysql_error());
                          $payementReference =mysql_fetch_assoc($res8);
                            //var_dump($sql8);
                            ///var_dump($payementReference);
                          $sqlv="select * from `aaa-compte` where nomCompte='".$typeCompteMobile."'";
                          $resv=mysql_query($sqlv);
                          $compte =mysql_fetch_assoc($resv);
                          if($compte){
                              $operation='depot';
                              $idCompte=$compte['idCompte'];
                              $description=$refTransfPm;
                              $newMontant=$compte['montantCompte']+$montantFixePayementTotalPm;

                              $sql6="insert into `aaa-comptemouvement` (montant,operation,idCompte,dateSaisie,dateOperation,description,idUser,idPR) 
                              values('".$montantFixePayementTotalPm."','".$operation."','".$idCompte."','".$dateHeures."','".$dateTransfert."','".$description."','".$_SESSION['iduserBack']."','".$payementReference['id']."')";
                              //var_dump($sql6);
                              $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error() );

                              $sql7="UPDATE `aaa-compte` set  montantCompte='".$newMontant."' where  idCompte=".$compte['idCompte']."";
                              //var_dump($sql7);
                              $res7=@mysql_query($sql7) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
                          }
    /********************************** TABLE COMPTE *****************************************/
    
}else if($operation == 4){
         $reponse="<ul><li>Boutique inexistante ou desactivée</li></ul>";
         $query=htmlspecialchars($_POST['nomBoutiquePm']);
          $reqS="SELECT idBoutique,labelB from `aaa-boutique` where enTest=1 and activer=1 and labelB LIKE '%$query%'";
          $resS=mysql_query($reqS) or die ("insertion impossible".mysql_error());
           if($resS){
                $reponse="<ul class='ulc'>";
                while ($data=mysql_fetch_array($resS)) {
                    /***************/                       
                        $sql3="SELECT * FROM `aaa-payement-salaire` WHERE `idBoutique`='".$data["idBoutique"]."' ORDER BY `aaa-payement-salaire`.`idPS` DESC LIMIT 0,1";
                        $res3 = @mysql_query($sql3) or die ("acces requête 3".mysql_error());
                        $variable = @mysql_fetch_array($res3);                        
                    /****************/
                  $reponse.="<li class='licR'>".$data['idBoutique'].'<>'.$data['labelB'].'<>'.$variable["montantFixePayement"]."</li>";
                }
                $reponse.="</ul>";
           }else {
                $reponse="<ul><li>erreur requette</li></ul>";
           }
          exit($reponse);
}else if($operation == 5){

    $date = new DateTime();
        //R�cup�ration de l'ann�e
        $annee =$date->format('Y');
        //R�cup�ration du mois
        $mois =$date->format('m');
        //R�cup�ration du jours
        $jour =$date->format('d');
        //$jour ='01';
        $heureString=$date->format('H:i:s');
        $dateString=$annee.'-'.$mois.'-'.$jour;
        $dateString2=$jour.'-'.$mois.'-'.$annee;
        $dateHeures=$dateString.' '.$heureString;

    $idBoutique=$_POST['idBoutique'];
    $sql2="SELECT * FROM `aaa-boutique` WHERE idBoutique =".$idBoutique;
    $res2 = mysql_query($sql2) or die ("personel requête 2".mysql_error());
    $boutique3 = mysql_fetch_array($res2);
    $nomB=$boutique3['labelB'];
    
    $heurePaiement=$_POST['heurePaiement'];
    $datePaiement=$_POST['datePaiement'];
    $dateTransfert=$_POST['dateTransfert'];
    $montantTransfert=$_POST['montantFixePayementM'];
    //var_dump($montantTransfert);
    $nbrMoisPm=$_POST['nbrMoisPm'];
    $refTransfPm='Paiement de '.$nbrMoisPm.' mois de '.$nomB.' du'.$dateTransfert;
    $refTransf='';
    $montantFixePayementTotalPm=$_POST['montantFixePayementTotalPm'];
    //var_dump($montantFixePayementTotalPm);
    $numTel='Sans Numero';
    $typeCompteMobile=$_POST['typeCompteMobile'];
    $activer=1;
    $montantNonConforme=0; 
    $plusieursMois=1;

    if(isset($_POST['refTransfPm'])){
        if ($_POST['refTransfPm'] !='') {
                // code...
            $refTransfPm=$_POST['refTransfPm'];
        }
    }
    if(isset($_POST['numTelPm'])){
        if ($_POST['numTelPm'] !='') {
            $numTel=$_POST['numTelPm'];
        }
    }

        $datePaiement = str_replace('/', '-', $datePaiement);
        $dateP = new DateTime($datePaiement);
        //R�cup�ration de l'ann�e
        $anneeP =$dateP->format('Y');
        //R�cup�ration du mois
        $moisP =$dateP->format('m');
        //R�cup�ration du jours
        $jourP =$dateP->format('d');
        $datePaiement=$anneeP.'-'.$moisP.'-'.$jourP;


        /************************* Determiner la dernier date de calcule ***********************/
            $sqlR="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$idBoutique." 
                 ORDER BY `aaa-payement-salaire`.`idPS` DESC LIMIT 0,1";
            $resR = mysql_query($sqlR) or die ("personel requête 2".mysql_error());
            $reR=mysql_fetch_array($resR);
            $d=$reR['datePS'];
            $dateR = new DateTime($d);
            //R�cup�ration de l'ann�e
            $anneeR =$dateR->format('Y');
            //R�cup�ration du mois
            $moisR =$dateR->format('m');
            //R�cup�ration du jours
            $jourR =$dateR->format('d');
            $jourR ='01';
            $dateOrigine=$anneeR.'-'.$moisR.'-'.$jourR;
            
            if ($reR['aPayementBoutique']==1) {                
                $somme=$moisR+1;
                        if($somme>12){
                            $moisR=$somme-12;
                            $anneeR=$anneeR+1;
                          }else{
                            $moisR=$moisR+1;
                        }
                        if ($moisR<10) {
                                $moisR='0'.$moisR;
                        }
                $dateOrigine=$anneeR.'-'.$moisR.'-'.$jourR;
            } 
            //////////////////////************** ****************///////////////////////
            $date_fin = date('Y-m-d', strtotime($dateOrigine.' + '.$nbrMoisPm.' month'));
            //Pour se positionner sur le dernier jour du mois
            $dateFin= date( 'Y-m-d', strtotime( $date_fin . '-1 day') );
            /////////////////////////////////////////////////////////////////////////////

        /***********************recherche boutique et parametre**************************/
            $sql2="SELECT * FROM `aaa-boutique` WHERE idBoutique ='".$idBoutique."'";
            $res2 = mysql_query($sql2) or die ("boutique requête 2".mysql_error());
            $boutique = mysql_fetch_array($res2);
            $volumeMoyenne=0;

            $tailleCatal=0;
            $nomtableDesignation=$boutique["nomBoutique"]."-designation";
            $sql="SELECT count(*) as nbreRef FROM `". $nomtableDesignation."` where classe=0";
            $res = mysql_query($sql) or die ("compte references requête ".mysql_error());
            if($compteur = mysql_fetch_array($res))
                $tailleCatal=$compteur["nbreRef"];

            $nomtableStock="";
            if ($boutique["type"]=="Entrepot") {
                $nomtableStock=$boutique["nomBoutique"]."-entrepotstock";
            } else {
                $nomtableStock=$boutique["nomBoutique"]."-stock";
            } 

            $tailleStocks=0;
            $sql="SELECT count(*) as nbreStock FROM `". $nomtableStock."` where quantiteStockCourant!=0";
            $res = mysql_query($sql) or die ("compte references requête ".mysql_error());
            if($compteur = mysql_fetch_array($res))
                $tailleStocks=$compteur["nbreStock"];
            $volumeMoyenne= ($tailleCatal+$tailleStocks)/2;

        /*    le nombre de mois entre deux dates    */

        $datetime1 = new DateTime($boutique["datecreation"]);
        $annee1 =$datetime1->format('Y');
        $mois1 =$datetime1->format('m');

        $datetime2 = new DateTime($dateOrigine);
        $annee2 =$datetime2->format('Y');
        $mois2 =$datetime2->format('m');


        //$etapeAccompagnement = ($mois2-$mois1)+12*($annee2-$annee1)+1;
        /*    le nombre de mois entre deux dates    */


        //recherche des varibles de Paiement de la boutique
        $sql3="SELECT * FROM `aaa-variablespayement` where typecaisse='".$boutique["type"]."' and categoriecaisse='".$boutique["categorie"]."' 
                and moyenneVolumeMin<=".$volumeMoyenne." and moyenneVolumeMax>=".$volumeMoyenne;
        //echo $sql3;
        $res3 = @mysql_query($sql3) or die ("acces requête 3".mysql_error());
        if($variable = @mysql_fetch_array($res3)){
            //BOUCLE
            for ($i=0; $i<$nbrMoisPm ; $i++) {
                $partAccompagnateur=0;
                $etapeAccompagnement=0;
                $volumeMoyenne=0;


                $datetimeN2 = new DateTime($dateOrigine);
                $anneeN2 =$datetimeN2->format('Y');
                $moisN2 =$datetimeN2->format('m');
                $regulateur =$datetimeN2->format('m');

                $somme=$moisN2+$i;
                if($somme>12){
                    $moisN2=$somme-12;
                    $anneeN2=$anneeN2+1;
                  }else{
                    $moisN2=$moisN2+$i;
                }
                if ($moisN2<10) {
                        $moisN2='0'.$moisN2;
                }

                 $etapeAccompagnement = ($moisN2-$mois1)+12*($anneeN2-$annee1)+1;
                /*    le nombre de mois entre deux dates    */

                $refTransf=$refTransfPm."@".$i;

                $dateStringNew=$anneeN2.'-'.$moisN2.'-'.$jour;
                //$dateFin=$anneeN2.'-'.$moisN2.'-28';
                //var_dump($dateStringNew);
                $dateStringNew2=$jour.'-'.$moisN2.'-'.$anneeN2;
                /********************DEBUT RECALCULE*********************/
                    $sql3="SELECT * FROM `aaa-payement-salaire` where ( datePS LIKE '%".$anneeN2."-".$moisN2."%' or datePS LIKE '%".$moisN2."-".$anneeN2."%' ) and idBoutique=".$boutique["idBoutique"];
                    $res3 = mysql_query($sql3) or die ("acces requête 3".mysql_error());
                    if(@mysql_num_rows($res3)){
                        if($variable["activerMontant"]==1){
                            if($etapeAccompagnement==1){
                                    $partAccompagnateur=$montantTransfert*50/100;
                                }else if (($etapeAccompagnement==2)||($etapeAccompagnement==3)){
                                    $partAccompagnateur=$montantTransfert*20/100;
                                }

                            $sql6="update `aaa-payement-salaire` set idBoutique=".$boutique["idBoutique"].",
                            accompagnateur='".$boutique["Accompagnateur"]."',datePS='".$dateStringNew."',montantFixePayement=".$montantTransfert.",
                            pourcentagePayement=".$variable["pourcentage"].",    prixlignesPayement=".$variable["prixLigne"].",
                            minmontant=".$variable["minmontant"].",maxmontant=".$variable["maxmontant"].",variablePayementActiver=1,
                            etapeAccompagnement=".$etapeAccompagnement." , 
                             refTransfert='".$refTransf."', telRefTransfert='".$numTel."', dateRefTransfert='".$dateString."', 
                             datePaiement='".$dateString."', heurePaiement='".$heureString."' , pPlusieursMois='".$plusieursMois."' ,aPayementBoutique='".$activer."'
                                where idBoutique=".$boutique["idBoutique"]." and ( datePS LIKE '%".$anneeN2."-".$moisN2."%' or datePS LIKE '%".$moisN2."-".$anneeN2."%' ) "  ;
                            //echo $sql6.'</br>';
                            $res6=@mysql_query($sql6) or die ("insertion aaa-payement-salaire impossible =>".mysql_error());

                        }else if($variable["activerPourcentage"]==1){
                            $sql6="update `aaa-payement-salaire` set idBoutique=".$boutique["idBoutique"].",
                            accompagnateur='".$boutique["Accompagnateur"]."',datePS='".$dateStringNew."',montantFixePayement=".$montantTransfert.",
                            pourcentagePayement=".$variable["pourcentage"].",    prixlignesPayement=".$variable["prixLigne"].",
                            minmontant=".$variable["minmontant"].",maxmontant=".$variable["maxmontant"].",variablePayementActiver=2,
                            etapeAccompagnement=".$etapeAccompagnement." ,
                             refTransfert='".$refTransf."', telRefTransfert='".$numTel."', dateRefTransfert='".$dateString."', datePaiement='".$dateString."',  aPayementBoutique='".$activer."'
                              heurePaiement='".$heureString."' , pPlusieursMois='".$plusieursMois."'
                                where idBoutique=".$boutique["idBoutique"]." and ( datePS LIKE '%".$anneeN2."-".$moisN2."%' or datePS LIKE '%".$moisN2."-".$anneeN2."%' ) " ;
                            //echo $sql6.'</br>';
                            $res6=@mysql_query($sql6) or die ("insertion aaa-payement-salaire impossible =>".mysql_error());

                        }else if($variable["activerPrix"]==1){
                            $sql6="update `aaa-payement-salaire` set idBoutique=".$boutique["idBoutique"].",
                            accompagnateur='".$boutique["Accompagnateur"]."',datePS='".$dateStringNew."',montantFixePayement=".$montantTransfert.",
                            pourcentagePayement=".$variable["pourcentage"].",    prixlignesPayement=".$variable["prixLigne"].",
                            minmontant=".$variable["minmontant"].",maxmontant=".$variable["maxmontant"].",variablePayementActiver=3,
                            etapeAccompagnement=".$etapeAccompagnement.",
                              refTransfert='".$refTransf."', telRefTransfert='".$numTel."', dateRefTransfert='".$dateString."', datePaiement='".$dateString."',  aPayementBoutique='".$activer."'
                               heurePaiement='".$heureString."'  , pPlusieursMois='".$plusieursMois."'
                                where idBoutique=".$boutique["idBoutique"]." and ( datePS LIKE '%".$anneeN2."-".$moisN2."%' or datePS LIKE '%".$moisN2."-".$anneeN2."%' ) " ;
                            //echo $sql6.'</br>';
                            $res6=@mysql_query($sql6) or die ("insertion aaa-payement-salaire impossible =>".mysql_error());
                        }
                    }else{

                            //var_dump("5");
                            if($variable["activerMontant"]==1){
                                //var_dump("6");
                                if($etapeAccompagnement==1){
                                    $partAccompagnateur=$montantTransfert*50/100;
                                }else if (($etapeAccompagnement==2)||($etapeAccompagnement==3)){
                                    $partAccompagnateur=$montantTransfert*20/100;
                                }

                                $sql6="insert into `aaa-payement-salaire`(idBoutique,accompagnateur,datePS,montantFixePayement,pourcentagePayement,prixlignesPayement,minmontant,maxmontant,variablePayementActiver,
                                etapeAccompagnement,refTransfert,datePaiement,heurePaiement,dateRefTransfert,telRefTransfert, pPlusieursMois , aPayementBoutique) 
                                values(".$boutique["idBoutique"].",'".$boutique["Accompagnateur"]."','".$dateStringNew."',".$montantTransfert.",".$variable["pourcentage"].",".$variable["prixLigne"].",".$variable["minmontant"].",
                                ".$variable["maxmontant"].",1,".$etapeAccompagnement.",'".$refTransf."','".$datePaiement."','".$heureString."','".$dateString."',
                                '".$numTel."','".$plusieursMois."','".$activer."')";
                                //var_dump($sql6);
                                //var_dump('calcul in');
                                $res6=@mysql_query($sql6) or die ("insertion aaa-payement-salaire impossible =>".mysql_error());
                                //var_dump('calcul out');
                                  

                            }elseif($variable["activerPourcentage"]==1){
                                    //var_dump("7");
                                    $sql6="insert into `aaa-payement-salaire`(idBoutique,accompagnateur,datePS,montantFixePayement,pourcentagePayement,prixlignesPayement,minmontant,maxmontant,variablePayementActiver,
                                    etapeAccompagnement,refTransfert,datePaiement,heurePaiement,dateRefTransfert,telRefTransfert, pPlusieursMois, aPayementBoutique) 
                                    values(".$boutique["idBoutique"].",'".$boutique["Accompagnateur"]."','".$dateStringNew."',".$montantTransfert.",".$variable["pourcentage"].",".$variable["prixLigne"].",".$variable["minmontant"].",".$variable["maxmontant"].",2
                                    ,".$etapeAccompagnement.",'".$refTransf."','".$datePaiement."','".$heureString."','".$dateString."','".$numTel."','".$plusieursMois."','".$activer."')";
                                    
                                    $res6=@mysql_query($sql6) or die ("insertion aaa-payement-salaire impossible =>".mysql_error());

                            }elseif($variable["activerPrix"]==1){
                                //var_dump("8");
                                $sql6="insert into `aaa-payement-salaire`(idBoutique,accompagnateur,datePS,montantFixePayement,pourcentagePayement,prixlignesPayement,minmontant,maxmontant,variablePayementActiver,
                                etapeAccompagnement,refTransfert,datePaiement,heurePaiement,dateRefTransfert,telRefTransfert, pPlusieursMois, aPayementBoutique) 
                                values(".$boutique["idBoutique"].",'".$boutique["Accompagnateur"]."','".$dateStringNew."',".$montantTransfert.",".$variable["pourcentage"].",".$variable["prixLigne"].",".$variable["minmontant"].",".$variable["maxmontant"].",3
                                ,".$etapeAccompagnement.",'".$refTransf."','".$datePaiement."','".$heureString."','".$dateString."','".$numTel."','".$plusieursMois."','".$activer."')";
                                
                                $res6=@mysql_query($sql6) or die ("insertion aaa-payement-salaire impossible =>".mysql_error());

                            }
                    }
                /********************FIN RECALCUL************************/

                /*----------------------DEBUT PAIEMENT------------------*/
                    //var_dump($refTransf);
                        $sql2="SELECT * FROM `aaa-payement-reference` where refTransfertValidation='".$refTransf."'";
                        //var_dump($sql2);
                        $res2 = mysql_query($sql2) or die ("acces requête 1".mysql_error());
                        if(!@mysql_num_rows($res2)){
                            //var_dump('paiement 1');
                            $sql3="insert into `aaa-payement-reference` (dateRefTransfertValidation,montant,refTransfertValidation,telRefTransfertValidation) 
                            values('".$dateTransfert."',".$montantTransfert.",'".$refTransf."','".$numTel."')";
                            //var_dump($sql3);
                            $res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas wave ".mysql_error());

                            $sql4="SELECT * FROM `aaa-payement-salaire` where refTransfert='".$refTransf."'";
                            $res4 = mysql_query($sql4) or die ("acces requête 4".mysql_error());
                            if(@mysql_num_rows($res4)){
                                //var_dump('paiement 2');
                                $paiement = mysql_fetch_array($res4);
                                if ($paiement['refTransfert']==$refTransf and $paiement['montantFixePayement']<=$montantTransfert){
                                    $sql5="UPDATE `aaa-payement-salaire` set  aPayementBoutique='".$activer."' where refTransfert='".$refTransf."'";
                                    $res5=@mysql_query($sql5) or die ("mise à jour acces pour activer ou pas2 ".mysql_error());

                                    $sql6="UPDATE `aaa-payement-reference` set  idPS=".$paiement['idPS']." where refTransfertValidation='".$refTransf."'";
                                    $res6=@mysql_query($sql6) or die ("mise à jour acces pour activer ou pas2 ".mysql_error());

                                }else{
                                     $sql5="UPDATE `aaa-payement-reference` set  `montantNonConforme`='".$montantNonConforme."',`idPS`=".$paiement['idPS']." where refTransfertValidation='".$refTransf."'";
                                    //var_dump($sql5);
                                    $res5=@mysql_query($sql5) or die ("mise à jour acces pour activer ou pas5ff ".mysql_error());
                                }
                            }
                        } 
                /*----------------------FIN   PAIEMENT------------------*/
            }
            //BOUCLE
           
        /**********************************TABLE COMPTE *****************************************/ 
        $sql8="SELECT * FROM `aaa-payement-reference` where refTransfertValidation='".$refTransf."' and dateRefTransfertValidation='".$dateTransfert."'";
        $res8 = mysql_query($sql8) or die ("acces requête 3".mysql_error());
        $payementReference =mysql_fetch_assoc($res8);
          //var_dump($sql8);
          ///var_dump($payementReference);

        $sqlv="select * from `aaa-compte` where nomCompte like '%".$typeCompteMobile."%'";
        $resv=mysql_query($sqlv);
        $compte =mysql_fetch_assoc($resv);
        if($compte){
            $operation='depot';
            $idCompte=$compte['idCompte'];
            $description=$refTransfPm;
            $newMontant=$compte['montantCompte']+$montantFixePayementTotalPm;

            $sql6="insert into `aaa-comptemouvement` (montant,operation,idCompte,dateSaisie,dateOperation,description,idUser,idPR) 
            values('".$montantFixePayementTotalPm."','".$operation."','".$idCompte."','".$dateHeures."','".$dateTransfert."','".$description."','".$_SESSION['iduserBack']."','".$payementReference['id']."')";
            //var_dump($sql6);
            $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error() );

            $sql7="UPDATE `aaa-compte` set  montantCompte='".$newMontant."' where  idCompte=".$compte['idCompte']."";
            //var_dump($sql7);
            $res7=@mysql_query($sql7) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
        }
        /********************************** TABLE COMPTE *****************************************/

        /**********************************TABLE PPAIEMENT PLUSIEUR MOIS *****************************************/

        $sqlX="INSERT INTO `aaa-payement-pmois`(`idBoutique`, `montantMensuel`, `montantTotal`, `datePaiement`, `dateDebut`, `dateFin`, `nombreMois`, `description`) 
        VALUES (".$idBoutique.",".$montantTransfert.",".$montantFixePayementTotalPm.",'".$datePaiement."','".$dateOrigine."','".$dateFin."',".$nbrMoisPm.",'".$refTransfPm."')";
        //var_dump($sqlX);
        $resX=@mysql_query($sqlX) or die ("insertion aaa-payement-salaire impossible =>".mysql_error());
        /*************************************************************** *****************************************/ 
        }       
}

if ($operation =='imprimerFactPPM') {
    $idPPM=$_POST['params'];
    $req1=$bdd->prepare("SELECT * FROM `aaa-payement-pmois`  WHERE idPPM=:idPPM");  
    $req1->execute(array('idPPM' =>$idPPM))  or die(print_r($req1->errorInfo())); 
    $ppmois=$req1->fetch();

    $req2=$bdd->prepare("SELECT * FROM `aaa-boutique`  WHERE idBoutique=:ib");  
    $req2->execute(array('ib' =>$ppmois['idBoutique']))  or die(print_r($req2->errorInfo())); 
    $boutiqueClient=$req2->fetch();

    $req3=$bdd->prepare("SELECT * FROM `aaa-boutique`  WHERE idBoutique=:iby");  
    $req3->execute(array('iby' =>110))  or die(print_r($req3->errorInfo())); 
    $boutiqueYaat=$req3->fetch();
    
    $result=$boutiqueClient['labelB']."<>".$boutiqueClient['adresseBoutique']."<>".$boutiqueClient['telephone']."<>".
    $ppmois['montantMensuel']."<>".$ppmois['montantTotal']."<>".$ppmois['datePaiement']."<>".$ppmois['dateDebut']."<>".$ppmois['dateFin']."<>".$ppmois['nombreMois']."<>".
    $boutiqueYaat['labelB']."<>".$boutiqueYaat['adresseBoutique']."<>".$boutiqueYaat['telephone'];
    exit($result);
}

		
?>