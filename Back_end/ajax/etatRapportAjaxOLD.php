
<?php

session_start();
if(!$_SESSION['iduserBack'])
    header('Location:../index.php');

  require('../connection.php');

  require('../declarationVariables.php');

/**Debut informations sur la date d'Aujourdhui **/
    $debut=@$_POST['debutPaiment'];
    $fin = new DateTime($debut);
    $annee =$fin->format('Y');
    $mois =$fin->format('m');
    /**Fin informations sur la date d'Aujourdhui **/

   /*$debutAnnee=@htmlspecialchars($_POST["debutAnnee"]);

    $finAnnee=@htmlspecialchars($_POST["finAnnee"]);
    
    $debutJour=@htmlspecialchars($_POST["debutJour"]);
    
    $finJour=@htmlspecialchars($_POST["finJour"]);

    $operation=@htmlspecialchars($_POST["operation"]); 
    *:

 
        /**Debut Entrees */
            $PaiementTo=0;
            $PaiementEf=0;
            $PaiementNef=0;
            $PaiementAlavance=0;
            $salaireAcc=0;
            $salaireIng=0;
            $salaireAdm=0;
            $salaireAss=0;
            $salaireTot=0;
            $compteOm=0;
            $compteOmDep=0;
            $compteOmRet=0;
            $compteOmTra=0;
            $compteWa=0;
            $compteWaDep=0;
            $compteWaRet=0;
            $compteWaTra=0;
            $compteCa=0;
            $compteCaDep=0;
            $compteCaRet=0;
            $comptePr=0;
            $comptePrRemb=0;
            $comptePrNew=0;
            /*************************DEBUT POUR MOIS PASSE ****************************/
                $moiM=0;
                $moiM=$mois-1;
                
                $anneeM=$annee;
                if($moiM<10){
                    $moiM='0'.$moiM;
                    if($mois=='01'){
                        $moiM=12;
                        $anneeM=$annee-1;
                        $anneeM="$anneeM";
                    }
                }

                $sql0="SELECT idBoutique FROM `aaa-payement-salaire` WHERE  aPayementBoutique=1 and (`datePS` LIKE '%".$anneeM."-".$moiM."%' or datePS LIKE '%".$moiM."-".$anneeM."%'  )" ;
                $res0 = mysql_query($sql0) or die ("etape requête 70".mysql_error());
                while($boutiqueP=mysql_fetch_array($res0)) {
                    $sql01="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$boutiqueP["idBoutique"]." order by datePS DESC LIMIT 1" ;
                    $res01 = mysql_query($sql01) or die ("etape requête 7".mysql_error());
                    while($payement=mysql_fetch_array($res01)) {
                        $PaiementTo=$PaiementTo+$payement['montantFixePayement'];
                    }
                }
                $sql0="SELECT idBoutique FROM `aaa-payement-salaire` WHERE aPayementBoutique=0 and (`datePS` LIKE '%".$anneeM."-".$moiM."%' or datePS LIKE '%".$moiM."-".$anneeM."%' )" ;
                $res0 = mysql_query($sql0) or die ("etape requête 80".mysql_error());
                while($boutiqueP=mysql_fetch_array($res0)) {
                    $sql01="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$boutiqueP["idBoutique"]." order by datePS DESC LIMIT 1" ;
                    $res01 = mysql_query($sql01) or die ("etape requête 8".mysql_error());
                    while($payement=mysql_fetch_array($res01)) {
                        $PaiementNef=$PaiementNef+$payement['montantFixePayement'];
                    }
                }
                ///////////////////////////////////////////////////////////////////////
                    
                $sql10="SELECT idBoutique FROM `aaa-payement-salaire` WHERE pPlusieursMois=0 AND aPayementBoutique=1 and (`datePS` LIKE '%".$anneeM."-".$moiM."%' or datePS LIKE '%".$moiM."-".$anneeM."%' )" ;
                $res10 = mysql_query($sql10) or die ("etape requête 90".mysql_error());
                while($boutiqueP12=mysql_fetch_array($res10)) {
                        $sql412="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$boutiqueP12["idBoutique"]." order by datePS DESC LIMIT 1" ;
                        $res412 = mysql_query($sql412) or die ("etape requête 9".mysql_error());
                            while($payement12=mysql_fetch_array($res412)) {
                                    $PaiementEf=$PaiementEf+$payement12['montantFixePayement'];
                                }
                }
                ///////////////////////////////////////////////////////////////////////
                        
                $sql101="SELECT idBoutique FROM `aaa-payement-salaire` WHERE aPayementBoutique=1 and (`datePS` LIKE '%".$annee."-".$mois."%' or datePS LIKE '%".$mois."-".$annee."%' )" ;
                $res101 = mysql_query($sql101) or die ("etape requête 100".mysql_error());
                while($boutiqueP121=mysql_fetch_array($res101)) {
                        $sql4121="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$boutiqueP121["idBoutique"]." order by datePS DESC LIMIT 1" ;
                        $res4121 = mysql_query($sql4121) or die ("etape requête 10".mysql_error());
                            while($payement121=mysql_fetch_array($res4121)) {
                                    $PaiementAlavance=$PaiementAlavance+$payement121['montantFixePayement'];
                                }
                }
            /*************************FIN POUR MOIS PASSE ******************************/
            /*************************DEBUT POUR SALAIRE ********************************/
                //ACCOMPAGNATEUR
                $sql1="SELECT * FROM `aaa-salaire-personnel` where `profil`='Accompagnateur' and 
                    (datePaiement LIKE '%".$mois."-".$annee."%'  or datePaiement LIKE '%".$annee."-".$mois."%'  ) ORDER BY idSP " ;
                    $res1 = mysql_query($sql1) or die ("etape requête 4 1".mysql_error());
                        while($accompagnateur=mysql_fetch_array($res1)) {                        
                            $salaireAcc=$salaireAcc+$accompagnateur['montant'];
                        }                                    
                //INGENIEUR
                $sql1="SELECT * FROM `aaa-salaire-personnel` where `profil`='Ingenieur' and 
                    (datePaiement LIKE '%".$mois."-".$annee."%'  or datePaiement LIKE '%".$annee."-".$mois."%'  ) ORDER BY idSP " ;
                    $res1 = mysql_query($sql1) or die ("etape requête 4 2".mysql_error());
                        while($accompagnateur=mysql_fetch_array($res1)) {                        
                            $salaireIng=$salaireIng+$accompagnateur['montant'];
                        }
                        //die($sql1);
                //ASSISTANT
                $sql1="SELECT * FROM `aaa-salaire-personnel` where `profil`='Assistant' and 
                    (datePaiement LIKE '%".$mois."-".$annee."%'  or datePaiement LIKE '%".$annee."-".$mois."%'  ) ORDER BY idSP " ;
                    $res1 = mysql_query($sql1) or die ("etape requête 4 3".mysql_error());
                        while($accompagnateur=mysql_fetch_array($res1)) {                        
                            $salaireAss=$salaireAss+$accompagnateur['montant'];
                        } 
                //Admin
                $sql1="SELECT * FROM `aaa-salaire-personnel` where `profil`='Admin' and 
                    (datePaiement LIKE '%".$mois."-".$annee."%'  or datePaiement LIKE '%".$annee."-".$mois."%'  ) ORDER BY idSP " ;
                    $res1 = mysql_query($sql1) or die ("etape requête 4 4".mysql_error());
                        while($accompagnateur=mysql_fetch_array($res1)) {                        
                            $salaireAdm=$salaireAdm+$accompagnateur['montant'];
                        } 
                $salaireTot=$salaireAcc+$salaireIng+$salaireAss+$salaireAdm;                       
            /*************************FIN POUR SALAIR **********************************/
            /*************************DEBUT ORANGE MONEY *******************************/
                $nomCompte='Orange Money';          
                $sql0="SELECT * FROM `aaa-compte` WHERE nomCompte='".$nomCompte."' ";
                $res0=mysql_query($sql0);
                $compte=mysql_fetch_array($res0);        
                $compteOm=$compte['montantCompte']; 
                // Depot  
                    $sql="SELECT * FROM `aaa-comptemouvement` WHERE idCompte='".$compte['idCompte']."' and operation='depot' AND (dateOperation LIKE '%".$annee."-".$mois."%' or dateOperation LIKE '%".$mois."-".$annee."%')";
                    $res=mysql_query($sql);
                    while($pay=mysql_fetch_array($res)){
                        $compteOmDep = $compteOmDep+$pay['montant'];
                    }
                // Retr  
                    $sql="SELECT * FROM `aaa-comptemouvement` WHERE idCompte='".$compte['idCompte']."' and operation='retrait' AND (dateOperation LIKE '%".$annee."-".$mois."%' or dateOperation LIKE '%".$mois."-".$annee."%')";
                    $res=mysql_query($sql);
                    while($pay=mysql_fetch_array($res)){
                        $compteOmRet = $compteOmRet+$pay['montant'];
                    }
                 // Trans  
                    $sql="SELECT * FROM `aaa-comptemouvement` WHERE idCompte='".$compte['idCompte']."' and operation='transfert' AND (dateOperation LIKE '%".$annee."-".$mois."%' or dateOperation LIKE '%".$mois."-".$annee."%')";
                    $res=mysql_query($sql);
                    while($pay=mysql_fetch_array($res)){
                        $compteOmTra = $compteOmTra+$pay['montant'];
                    }
                     
            /*************************FIN ORANGE MONEY *********************************/
            /*************************DEBUT WAVE *******************************/
                $nomCompte='Wave';          
                $sql0="SELECT * FROM `aaa-compte` WHERE nomCompte='".$nomCompte."' ";
                $res0=mysql_query($sql0);
                $compte=mysql_fetch_array($res0);        
                $compteWa=$compte['montantCompte']; 
                // Depot  
                    $sql="SELECT * FROM `aaa-comptemouvement` WHERE idCompte='".$compte['idCompte']."' and operation='depot' AND (dateOperation LIKE '%".$annee."-".$mois."%' or dateOperation LIKE '%".$mois."-".$annee."%')";
                    $res=mysql_query($sql);
                    while($pay=mysql_fetch_array($res)){
                        $compteWaDep = $compteWaDep+$pay['montant'];
                    }
                // Retr  
                    $sql="SELECT * FROM `aaa-comptemouvement` WHERE idCompte='".$compte['idCompte']."' and operation='retrait' AND (dateOperation LIKE '%".$annee."-".$mois."%' or dateOperation LIKE '%".$mois."-".$annee."%')";
                    $res=mysql_query($sql);
                    while($pay=mysql_fetch_array($res)){
                        $compteWaRet = $compteWaRet+$pay['montant'];
                    }
                 // Trans  
                    $sql="SELECT * FROM `aaa-comptemouvement` WHERE idCompte='".$compte['idCompte']."' and operation='transfert' AND (dateOperation LIKE '%".$annee."-".$mois."%' or dateOperation LIKE '%".$mois."-".$annee."%')";
                    $res=mysql_query($sql);
                    while($pay=mysql_fetch_array($res)){
                        $compteWaTra = $compteWaTra+$pay['montant'];
                    }
                     
            /*************************FIN WAVE *********************************/
            /*************************DEBUT CAISSE *******************************/
             $nomCompte='Caisse';          
             $sql0="SELECT * FROM `aaa-compte` WHERE nomCompte='".$nomCompte."' ";
             $res0=mysql_query($sql0);
             $compte=mysql_fetch_array($res0);        
             $compteCa=$compte['montantCompte']; 
             // Depot  
                 $sql="SELECT * FROM `aaa-comptemouvement` WHERE idCompte='".$compte['idCompte']."' and operation='depot' AND (dateOperation LIKE '%".$annee."-".$mois."%' or dateOperation LIKE '%".$mois."-".$annee."%')";
                 $res=mysql_query($sql);
                 while($pay=mysql_fetch_array($res)){
                     $compteCaDep = $compteCaDep+$pay['montant'];
                 }
             // Retr  
                 $sql="SELECT * FROM `aaa-comptemouvement` WHERE idCompte='".$compte['idCompte']."' and operation='retrait' AND (dateOperation LIKE '%".$annee."-".$mois."%' or dateOperation LIKE '%".$mois."-".$annee."%')";
                 $res=mysql_query($sql);
                 while($pay=mysql_fetch_array($res)){
                     $compteCaRet = $compteCaRet+$pay['montant'];
                 }     
            /*************************FIN CAISSE *********************************/
            /*************************DEBUT PRET *******************************/
            $typeCompte='compte pret';          
            $sql0="SELECT * FROM `aaa-comptetype` WHERE nomType like '%".$typeCompte."%' ";
            $res0=mysql_query($sql0);
            $type=mysql_fetch_array($res0);
                    
            $sql0="SELECT * FROM `aaa-compte` WHERE typeCompte = '".$type['idType']."' ";
            $res0=mysql_query($sql0);
            $compte=mysql_fetch_array($res0);        
            $comptePr=$compte['montantCompte']; 
            // Rembourssement
                $sql="SELECT * FROM `aaa-comptemouvement` WHERE idCompte='".$compte['idCompte']."' and operation='depot' AND (dateOperation LIKE '%".$annee."-".$mois."%' or dateOperation LIKE '%".$mois."-".$annee."%')";
                $res=mysql_query($sql);
                while($pay=mysql_fetch_array($res)){
                    $comptePrRemb = $comptePrRemb+$pay['montant'];
                }
            // Nouveau pret  
                $sql="SELECT * FROM `aaa-comptemouvement` WHERE idCompte='".$compte['idCompte']."' and operation='retrait' AND (dateOperation LIKE '%".$annee."-".$mois."%' or dateOperation LIKE '%".$mois."-".$annee."%')";
                $res=mysql_query($sql);
                while($pay=mysql_fetch_array($res)){
                    $comptePrNew = $comptePrNew+$pay['montant'];
                }     
           /*************************FIN PRET *********************************/
            
            $result=number_format($PaiementTo, 0, ',', ' ').'<>'.number_format($PaiementEf, 0, ',', ' ').'<>'.number_format($PaiementNef, 0, ',', ' ').'<>'.
            number_format($PaiementAlavance, 0, ',', ' ').'<>'.number_format($salaireTot, 0, ',', ' ').'<>'.number_format($salaireAcc, 0, ',', ' ').'<>'.
                number_format($salaireIng, 0, ',', ' ').'<>'.number_format($salaireAss, 0, ',', ' ').'<>'.number_format($salaireAdm, 0, ',', ' ').'<>'.
        number_format($compteOm, 0, ',', ' ').'<>'.number_format($compteOmDep, 0, ',', ' ').'<>'.number_format($compteOmRet, 0, ',', ' ')
        .'<>'.number_format($compteOmTra, 0, ',', ' ').'<>'.number_format($compteWa, 0, ',', ' ').'<>'.number_format($compteWaDep, 0, ',', ' ')
        .'<>'.number_format($compteWaRet, 0, ',', ' ').'<>'.number_format($compteWaTra, 0, ',', ' ').'<>'.number_format($compteCa, 0, ',', ' ')
        .'<>'.number_format($compteCaDep, 0, ',', ' ').'<>'.number_format($compteCaRet, 0, ',', ' ').'<>'.number_format($comptePr, 0, ',', ' ')
        .'<>'.number_format($comptePrRemb, 0, ',', ' ').'<>'.number_format($comptePrNew, 0, ',', ' ');/*.'<>'.number_format($som_EntreesPU2, 0, ',', ' ').'<>'.number_format($som_SortiesVente2, 0, ',', ' ')
        .'<>'.number_format($som_SortiesBon2, 0, ',', ' ').'<>'.number_format(($moinsR2 - $plusR2), 0, ',', ' ').'<>'.number_format(($moinsE2 - $plusE2), 0, ',', ' ')
        .'<>'.number_format(($moinsRe2 - $plusRe2), 0, ',', ' ').'<>'.number_format($som_Clients2, 0, ',', ' ').'<>'.number_format($som_ClientsBE2, 0, ',', ' ')
        .'<>'.number_format($som_Fournisseurs2, 0, ',', ' ').'<>'.number_format($som_FournisseursV2, 0, ',', ' ').'<>'.number_format($som_Services2, 0, ',', ' ')
        .'<>'.number_format($som_Depenses2, 0, ',', ' ').'<>'.number_format(($som_Clients2+$som_SortiesVente2+$som_Services2), 0, ',', ' ').'<>'.number_format(($som_Depenses2+$som_FournisseursV+$som_ClientsBE2), 0, ',', ' ')
        .'<>'.number_format(($som_Clients2+$som_SortiesVente2+$som_Services2)-($som_Depenses2+$som_FournisseursV+$som_ClientsBE2), 0, ',', ' ').'<>'.number_format(($som_SortiesBon2+$som_ClientsBE2), 0, ',', ' ')
        .'<>'.number_format(($som_EntreesPA2+$som_EntreesPU2), 0, ',', ' ').'<>'.number_format(($som_Clients2+$som_SortiesVente2+$som_Services2)-($som_Depenses2+$som_FournisseursV+$som_ClientsBE2)+$SommeMontantAverser+$montantPU-$restant_FournisseursD, 0, ',', ' ').'<>'.number_format($SommeMontantAverser, 0, ',', ' ').'<>'.number_format($montantPU, 0, ',', ' ').'<>'.number_format($montantPA, 0, ',', ' ').'<>'.number_format($restant_Fournisseurs, 0, ',', ' ')
        .'<>'.number_format($restant_FournisseursD, 0, ',', ' ');*/
        exit($result);
