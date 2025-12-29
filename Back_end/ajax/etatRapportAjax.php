
<?php

session_start();
if(!$_SESSION['iduserBack'])
    header('Location:../index.php');

  require('../connection.php');
  require('../connectionPDO.php');

  require('../declarationVariables.php');

/**Debut informations sur la date d'Aujourdhui **/
    //$debut=@$_POST['debutPaiment'];
    
    $debut=@htmlspecialchars($_POST["debut"]);
    $fin=@htmlspecialchars($_POST["fin"]);
    
    /**Fin informations sur la date d'Aujourdhui **/

    $debutPlage=@htmlspecialchars($_POST["debutAnnee"]);
    $finPlage=@htmlspecialchars($_POST["finAnnee"]);
    $debutAnnee=@htmlspecialchars($_POST["debutAnnee"]);
    $finAnnee=@htmlspecialchars($_POST["finAnnee"]);
    $debutJour=@htmlspecialchars($_POST["debutJour"]);  
    $finJour=@htmlspecialchars($_POST["finJour"]);
    $operation=@htmlspecialchars($_POST["operation"]); 
    
    $debutAnneeSal=@htmlspecialchars($debut.' 00:00:00');
    $finAnneeSal=@htmlspecialchars($fin.' 23:59:59');
 
        /**Debut Entrees */
            $PaiementTo=0;
            $PaiementEf=0;
            $PaiementNef=0;
            $PaiementAlavance=0;
            $salairePers=0;
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

               /* $sql0="SELECT idBoutique FROM `aaa-payement-salaire` WHERE  aPayementBoutique=1 and (`datePS` LIKE '%".$anneeM."-".$moiM."%' or datePS LIKE '%".$moiM."-".$anneeM."%'  )" ;
                $res0 = mysql_query($sql0) or die ("etape requête 70".mysql_error());
                while($boutiqueP=mysql_fetch_array($res0)) {
                    $sql01="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$boutiqueP["idBoutique"]." order by datePS DESC LIMIT 1" ;
                    $res01 = mysql_query($sql01) or die ("etape requête 7".mysql_error());
                    while($payement=mysql_fetch_array($res01)) {
                        $PaiementTo=$PaiementTo+$payement['montantFixePayement'];
                    }
                }*/
                
                $somme01=0;
                $sql01 = $bdd->prepare("SELECT sum(montantFixePayement) as total FROM `aaa-payement-salaire` WHERE aPayementBoutique=:a and (`datePS`  BETWEEN '".$debutAnnee."' AND '".$finAnnee."' )"); 
                $sql01->execute(array('a' =>1 ))  or die(print_r($sql01->errorInfo()));                                                         
                $total =$sql01->fetch();
                $PaiementTo=$somme01+$total['total'];
                

                /*$sql0="SELECT idBoutique FROM `aaa-payement-salaire` WHERE aPayementBoutique=0 and (`datePS` LIKE '%".$anneeM."-".$moiM."%' or datePS LIKE '%".$moiM."-".$anneeM."%' )" ;
                $res0 = mysql_query($sql0) or die ("etape requête 80".mysql_error());
                while($boutiqueP=mysql_fetch_array($res0)) {
                    $sql01="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$boutiqueP["idBoutique"]." order by datePS DESC LIMIT 1" ;
                    $res01 = mysql_query($sql01) or die ("etape requête 8".mysql_error());
                    while($payement=mysql_fetch_array($res01)) {
                        $PaiementNef=$PaiementNef+$payement['montantFixePayement'];
                    }
                }*/
                
                $sql01 = $bdd->prepare("SELECT sum(montantFixePayement) as total FROM `aaa-payement-salaire` WHERE aPayementBoutique=:a and pPlusieursMois=:p   and (`datePS`  BETWEEN '".$debutAnnee."' AND '".$finAnnee."' )"); 
                $sql01->execute(array('a' =>0,  'p' =>0 ))  or die(print_r($sql01->errorInfo()));                                                         
                $total =$sql01->fetch();
                $PaiementNef=$PaiementNef+$total['total'];
                ///////////////////////////////////////////////////////////////////////
                    
                
                /*$sql10="SELECT idBoutique FROM `aaa-payement-salaire` WHERE pPlusieursMois=0 AND aPayementBoutique=1 and (`datePS` LIKE '%".$anneeM."-".$moiM."%' or datePS LIKE '%".$moiM."-".$anneeM."%' )" ;
                $res10 = mysql_query($sql10) or die ("etape requête 90".mysql_error());
                while($boutiqueP12=mysql_fetch_array($res10)) {
                        $sql412="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$boutiqueP12["idBoutique"]." order by datePS DESC LIMIT 1" ;
                        $res412 = mysql_query($sql412) or die ("etape requête 9".mysql_error());
                            while($payement12=mysql_fetch_array($res412)) {
                                    $PaiementEf=$PaiementEf+$payement12['montantFixePayement'];
                                }
                }*/

                $sql01 = $bdd->prepare("SELECT sum(montantFixePayement) as total FROM `aaa-payement-salaire` WHERE pPlusieursMois=:p and aPayementBoutique=:a and (`datePS`  BETWEEN '".$debutAnnee."' AND '".$finAnnee."' )"); 
                $sql01->execute(array('a' =>1,  'p' =>0 ))  or die(print_r($sql01->errorInfo()));                                                         
                $total =$sql01->fetch();
                $PaiementEf=$PaiementEf+$total['total'];
                ///////////////////////////////////////////////////////////////////////
                        
                /*$sql101="SELECT idBoutique FROM `aaa-payement-salaire` WHERE aPayementBoutique=1 and (`datePS` LIKE '%".$annee."-".$mois."%' or datePS LIKE '%".$mois."-".$annee."%' )" ;
                $res101 = mysql_query($sql101) or die ("etape requête 100".mysql_error());
                while($boutiqueP121=mysql_fetch_array($res101)) {
                        $sql4121="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$boutiqueP121["idBoutique"]." order by datePS DESC LIMIT 1" ;
                        $res4121 = mysql_query($sql4121) or die ("etape requête 10".mysql_error());
                            while($payement121=mysql_fetch_array($res4121)) {
                                    $PaiementAlavance=$PaiementAlavance+$payement121['montantFixePayement'];
                                }
                }*/

                $sql01 = $bdd->prepare("SELECT sum(montantFixePayement) as total FROM `aaa-payement-salaire` WHERE pPlusieursMois=:p and aPayementBoutique=:a and (`datePS`  BETWEEN '".$debutAnnee."' AND '".$finAnnee."' )"); 
                $sql01->execute(array('a' =>1,  'p' =>1 ))  or die(print_r($sql01->errorInfo()));                                                         
                $total =$sql01->fetch();
                $PaiementAlavance=$PaiementAlavance+$total['total'];
            /*************************FIN POUR MOIS PASSE ******************************/
            /*************************DEBUT POUR SALAIRE ********************************/

            
            /*$debutAnneeSal=@htmlspecialchars($_POST["debutAnnee"].' 00:00:00');
            $finAnneeSal=@htmlspecialchars($_POST["finAnnee"].' 23:59:59');
            */
              /*  SELECT * FROM `aaa-salaire-personnel` AS s 
                    INNER JOIN `aaa-contrat` AS c ON s.idSP = c.idContrat 
                    JOIN `aaa-personnel` AS p on c.idPersonnel=p.idPersonnel
                    WHERE s.aPayer=1
                */
                ///// salaire personnel
                $sql01 = $bdd->prepare("SELECT SUM(s.salaireDeBase) as total FROM `aaa-salaire-personnel` AS s 
                    INNER JOIN `aaa-contrat` AS c 
                    ON s.idSP = c.idContrat 
                    JOIN `aaa-personnel` AS p 
                    on c.idPersonnel=p.idPersonnel
                    WHERE  s.aPayer=:sa and ( s.datePaiement  BETWEEN '".$debutAnneeSal."' AND '".$finAnneeSal."' )"); 
                $sql01->execute(array(  'sa' =>1 ))  or die(print_r($sql01->errorInfo()));                                                         
                $total =$sql01->fetch();
                $salairePers=$salairePers+$total['total'];
               /* //ACCOMPAGNATEUR
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
                */
                $salaireTot=$salairePers+$salaireAcc+$salaireIng+$salaireAss+$salaireAdm; 
                

            /*************************FIN POUR SALAIR **********************************/
            /*************************DEBUT ORANGE MONEY *******************************/
                $nomCompte='Orange Money';          
                /*$sql0="SELECT * FROM `aaa-compte` WHERE nomCompte='".$nomCompte."' ";
                $res0=mysql_query($sql0);
                $compte=mysql_fetch_array($res0);        
                $compteOm=$compte['montantCompte']; */
                $sql0 = $bdd->prepare("SELECT * FROM `aaa-compte` WHERE nomCompte like '%".$nomCompte."%'"); 
                $sql0->execute()  or die(print_r($sql0->errorInfo()));                                                         
                $compte =$sql0->fetch();
                $compteOm=$compte['montantCompte'];

                // Depot  
                    /*$sql="SELECT * FROM `aaa-comptemouvement` WHERE idCompte='".$compte['idCompte']."' and operation='depot' AND (dateOperation LIKE '%".$annee."-".$mois."%' or dateOperation LIKE '%".$mois."-".$annee."%')";
                    $res=mysql_query($sql);
                    while($pay=mysql_fetch_array($res)){
                        $compteOmDep = $compteOmDep+$pay['montant'];
                    }*/

                    $sql = $bdd->prepare("SELECT sum(montant) as total FROM `aaa-comptemouvement` WHERE idCompte=:i and operation=:d and (`dateOperation`  BETWEEN '".$debutAnneeSal."' AND '".$finAnneeSal."' )"); 
                    $sql->execute(array('i' =>$compte['idCompte'],
                                           'd'=>'depot'))  or die(print_r($sql->errorInfo()));                                                         
                    $total =$sql->fetch();
                    $compteOmDep=$compteOmDep+$total['total'];
                // Retr  
                    /*$sql="SELECT * FROM `aaa-comptemouvement` WHERE idCompte='".$compte['idCompte']."' and operation='retrait' AND (dateOperation LIKE '%".$annee."-".$mois."%' or dateOperation LIKE '%".$mois."-".$annee."%')";
                    $res=mysql_query($sql);
                    while($pay=mysql_fetch_array($res)){
                        $compteOmRet = $compteOmRet+$pay['montant'];
                    }*/

                    $sql = $bdd->prepare("SELECT sum(montant) as total FROM `aaa-comptemouvement` WHERE idCompte=:i and operation=:r and (`dateOperation`  BETWEEN '".$debutAnneeSal."' AND '".$finAnneeSal."' )"); 
                    $sql->execute(array('i' =>$compte['idCompte'],
                                           'r'=>'retrait'))  or die(print_r($sql->errorInfo()));                                                         
                    $total =$sql->fetch();
                    $compteOmRet=$compteOmRet+$total['total'];
                 // Trans  
                    /*$sql="SELECT * FROM `aaa-comptemouvement` WHERE idCompte='".$compte['idCompte']."' and operation='transfert' AND (dateOperation LIKE '%".$annee."-".$mois."%' or dateOperation LIKE '%".$mois."-".$annee."%')";
                    $res=mysql_query($sql);
                    while($pay=mysql_fetch_array($res)){
                        $compteOmTra = $compteOmTra+$pay['montant'];
                    }*/

                    $sql = $bdd->prepare("SELECT sum(montant) as total FROM `aaa-comptemouvement` WHERE idCompte=:i and operation=:t and (`dateOperation`  BETWEEN '".$debutAnneeSal."' AND '".$finAnneeSal."' )"); 
                    $sql->execute(array('i' =>$compte['idCompte'],
                                           't'=>'transfert'))  or die(print_r($sql->errorInfo()));                                                         
                    $total =$sql->fetch();
                    $compteOmTra=$compteOmTra+$total['total'];
                     
            /*************************FIN ORANGE MONEY *********************************/
            /*************************DEBUT WAVE *******************************/
                $nomCompte='Wave';          
                /*$sql0="SELECT * FROM `aaa-compte` WHERE nomCompte='".$nomCompte."' ";
                $res0=mysql_query($sql0);
                $compte=mysql_fetch_array($res0);        
                $compteWa=$compte['montantCompte']; */

                $sql0 = $bdd->prepare("SELECT * FROM `aaa-compte` WHERE nomCompte like '%".$nomCompte."%'"); 
                $sql0->execute()  or die(print_r($sql0->errorInfo()));                                                         
                $compte =$sql0->fetch();
                $compteWa=$compte['montantCompte'];

                // Depot  
                    /*$sql="SELECT * FROM `aaa-comptemouvement` WHERE idCompte='".$compte['idCompte']."' and operation='depot' AND (dateOperation LIKE '%".$annee."-".$mois."%' or dateOperation LIKE '%".$mois."-".$annee."%')";
                    $res=mysql_query($sql);
                    while($pay=mysql_fetch_array($res)){
                        $compteWaDep = $compteWaDep+$pay['montant'];
                    }*/

                    $sql = $bdd->prepare("SELECT sum(montant) as total FROM `aaa-comptemouvement` WHERE idCompte=:i and operation=:d and (`dateOperation`  BETWEEN '".$debutAnneeSal."' AND '".$finAnneeSal."' )"); 
                    $sql->execute(array('i' =>$compte['idCompte'],
                                           'd'=>'depot'))  or die(print_r($sql->errorInfo()));                                                         
                    $total =$sql->fetch();
                    $compteWaDep=$compteWaDep+$total['total'];
                // Retr  
                    /*$sql="SELECT * FROM `aaa-comptemouvement` WHERE idCompte='".$compte['idCompte']."' and operation='retrait' AND (dateOperation LIKE '%".$annee."-".$mois."%' or dateOperation LIKE '%".$mois."-".$annee."%')";
                    $res=mysql_query($sql);
                    while($pay=mysql_fetch_array($res)){
                        $compteWaRet = $compteWaRet+$pay['montant'];
                    }*/
                    $sql = $bdd->prepare("SELECT sum(montant) as total FROM `aaa-comptemouvement` WHERE idCompte=:i and operation=:r and (`dateOperation`  BETWEEN '".$debutAnneeSal."' AND '".$finAnneeSal."' )"); 
                    $sql->execute(array('i' =>$compte['idCompte'],
                                           'r'=>'retrait'))  or die(print_r($sql->errorInfo()));                                                         
                    $total =$sql->fetch();
                    $compteWaRet=$compteWaRet+$total['total'];
                 // Trans  
                    /*$sql="SELECT * FROM `aaa-comptemouvement` WHERE idCompte='".$compte['idCompte']."' and operation='transfert' AND (dateOperation LIKE '%".$annee."-".$mois."%' or dateOperation LIKE '%".$mois."-".$annee."%')";
                    $res=mysql_query($sql);
                    while($pay=mysql_fetch_array($res)){
                        $compteWaTra = $compteWaTra+$pay['montant'];
                    }*/

                    $sql = $bdd->prepare("SELECT sum(montant) as total FROM `aaa-comptemouvement` WHERE idCompte=:i and operation=:t and (`dateOperation`  BETWEEN '".$debutAnneeSal."' AND '".$finAnneeSal."' )"); 
                    $sql->execute(array('i' =>$compte['idCompte'],
                                           't'=>'transfert'))  or die(print_r($sql->errorInfo()));                                                         
                    $total =$sql->fetch();
                    $compteWaTra=$compteWaTra+$total['total'];
                     
            /*************************FIN WAVE *********************************/
            /*************************DEBUT CAISSE *******************************/
             $nomCompte='Caisse';          
             /*$sql0="SELECT * FROM `aaa-compte` WHERE nomCompte='".$nomCompte."' ";
             $res0=mysql_query($sql0);
             $compte=mysql_fetch_array($res0);        
             $compteCa=$compte['montantCompte']; */

             $sql0 = $bdd->prepare("SELECT * FROM `aaa-compte` WHERE nomCompte like '%".$nomCompte."%'"); 
             $sql0->execute()  or die(print_r($sql0->errorInfo()));                                                         
             $compte =$sql0->fetch();
             $compteCa=$compteCa['montantCompte'];

             // Depot  
                 /*$sql="SELECT * FROM `aaa-comptemouvement` WHERE idCompte='".$compte['idCompte']."' and operation='depot' AND (dateOperation LIKE '%".$annee."-".$mois."%' or dateOperation LIKE '%".$mois."-".$annee."%')";
                 $res=mysql_query($sql);
                 while($pay=mysql_fetch_array($res)){
                     $compteCaDep = $compteCaDep+$pay['montant'];
                 }*/

                 $sql = $bdd->prepare("SELECT sum(montant) as total FROM `aaa-comptemouvement` WHERE idCompte=:i and operation=:d and (`dateOperation`  BETWEEN '".$debutAnneeSal."' AND '".$finAnneeSal."' )"); 
                 $sql->execute(array('i' =>$compte['idCompte'],
                                        'd'=>'depot'))  or die(print_r($sql->errorInfo()));                                                         
                 $total =$sql->fetch();
                 $compteCaDep=$compteCaDep+$total['total'];

             // Retr  
                 /*$sql="SELECT * FROM `aaa-comptemouvement` WHERE idCompte='".$compte['idCompte']."' and operation='retrait' AND (dateOperation LIKE '%".$annee."-".$mois."%' or dateOperation LIKE '%".$mois."-".$annee."%')";
                 $res=mysql_query($sql);
                 while($pay=mysql_fetch_array($res)){
                     $compteCaRet = $compteCaRet+$pay['montant'];
                 }  */
                 
                 $sql = $bdd->prepare("SELECT sum(montant) as total FROM `aaa-comptemouvement` WHERE idCompte=:i and operation=:r and (`dateOperation`  BETWEEN '".$debutAnneeSal."' AND '".$finAnneeSal."' )"); 
                 $sql->execute(array('i' =>$compte['idCompte'],
                                        'r'=>'retrait'))  or die(print_r($sql->errorInfo()));                                                         
                 $total =$sql->fetch();
                 $compteCaRet=$compteCaRet+$total['total'];

            /*************************FIN CAISSE *********************************/
            /*************************DEBUT PRET *******************************/
            $typeCompte='compte pret';          
            /*$sql0="SELECT * FROM `aaa-comptetype` WHERE nomType like '%".$typeCompte."%' ";
            $res0=mysql_query($sql0);
            $type=mysql_fetch_array($res0);
                    
            $sql0="SELECT * FROM `aaa-compte` WHERE typeCompte = '".$type['idType']."' ";
            $res0=mysql_query($sql0);
            $compte=mysql_fetch_array($res0);        
            $comptePr=$compte['montantCompte']; */

            $sql0 = $bdd->prepare("SELECT * FROM `aaa-comptetype` WHERE nomType like '%".$typeCompte."%' "); 
             $sql0->execute()  or die(print_r($sql0->errorInfo()));                                                         
             $type =$sql0->fetch();

            $sql0 = $bdd->prepare("SELECT * FROM `aaa-compte` WHERE typeCompte=:t"); 
             $sql0->execute(array('t' =>$type['idType']))  or die(print_r($sql0->errorInfo()));                                                         
             $compte =$sql0->fetch();
             $comptePr=$comptePr['montantCompte'];


            // Rembourssement
                /*$sql="SELECT * FROM `aaa-comptemouvement` WHERE idCompte='".$compte['idCompte']."' and operation='depot' AND (dateOperation LIKE '%".$annee."-".$mois."%' or dateOperation LIKE '%".$mois."-".$annee."%')";
                $res=mysql_query($sql);
                while($pay=mysql_fetch_array($res)){
                    $comptePrRemb = $comptePrRemb+$pay['montant'];
                }*/

                $sql = $bdd->prepare("SELECT sum(montant) as total FROM `aaa-comptemouvement` WHERE idCompte=:i and operation=:d and (`dateOperation`  BETWEEN '".$debutAnneeSal."' AND '".$finAnneeSal."' )"); 
                 $sql->execute(array('i' =>$compte['idCompte'],
                                        'd'=>'depot'))  or die(print_r($sql->errorInfo()));                                                         
                 $total =$sql->fetch();
                 $comptePrRemb=$comptePrRemb+$total['total'];
                 
            // Nouveau pret  
                /*$sql="SELECT * FROM `aaa-comptemouvement` WHERE idCompte='".$compte['idCompte']."' and operation='retrait' AND (dateOperation LIKE '%".$annee."-".$mois."%' or dateOperation LIKE '%".$mois."-".$annee."%')";
                $res=mysql_query($sql);
                while($pay=mysql_fetch_array($res)){
                    $comptePrNew = $comptePrNew+$pay['montant'];
                }  */
                
                $sql = $bdd->prepare("SELECT sum(montant) as total FROM `aaa-comptemouvement` WHERE idCompte=:i and operation=:r and (`dateOperation`  BETWEEN '".$debutAnneeSal."' AND '".$finAnneeSal."' )"); 
                 $sql->execute(array('i' =>$compte['idCompte'],
                                        'r'=>'retrait'))  or die(print_r($sql->errorInfo()));                                                         
                 $total =$sql->fetch();
                 $comptePrNew=$comptePrNew+$total['total'];
           /*************************FIN PRET *********************************/
            
            $result=number_format($PaiementTo, 0, ',', ' ').'<>'.number_format($PaiementEf, 0, ',', ' ').'<>'.number_format($PaiementNef, 0, ',', ' ').'<>'.
            number_format($PaiementAlavance, 0, ',', ' ').'<>'.number_format($salaireTot, 0, ',', ' ').'<>'.number_format($salaireAcc, 0, ',', ' ').'<>'.
                number_format($salaireIng, 0, ',', ' ').'<>'.number_format($salaireAss, 0, ',', ' ').'<>'.number_format($salaireAdm, 0, ',', ' ').'<>'.
        number_format($compteOm, 0, ',', ' ').'<>'.number_format($compteOmDep, 0, ',', ' ').'<>'.number_format($compteOmRet, 0, ',', ' ')
        .'<>'.number_format($compteOmTra, 0, ',', ' ').'<>'.number_format($compteWa, 0, ',', ' ').'<>'.number_format($compteWaDep, 0, ',', ' ')
        .'<>'.number_format($compteWaRet, 0, ',', ' ').'<>'.number_format($compteWaTra, 0, ',', ' ').'<>'.number_format($compteCa, 0, ',', ' ')
        .'<>'.number_format($compteCaDep, 0, ',', ' ').'<>'.number_format($compteCaRet, 0, ',', ' ').'<>'.number_format($comptePr, 0, ',', ' ')
        .'<>'.number_format($comptePrRemb, 0, ',', ' ').'<>'.number_format($comptePrNew, 0, ',', ' ').'<>'.number_format($salairePers, 0, ',', ' ').'<>'.$debutAnnee.'<>'.$finAnnee;/*.'<>'.number_format($som_EntreesPU2, 0, ',', ' ').'<>'.number_format($som_SortiesVente2, 0, ',', ' ')
        .'<>'.number_format($som_SortiesBon2, 0, ',', ' ').'<>'.number_format(($moinsR2 - $plusR2), 0, ',', ' ').'<>'.number_format(($moinsE2 - $plusE2), 0, ',', ' ')
        .'<>'.number_format(($moinsRe2 - $plusRe2), 0, ',', ' ').'<>'.number_format($som_Clients2, 0, ',', ' ').'<>'.number_format($som_ClientsBE2, 0, ',', ' ')
        .'<>'.number_format($som_Fournisseurs2, 0, ',', ' ').'<>'.number_format($som_FournisseursV2, 0, ',', ' ').'<>'.number_format($som_Services2, 0, ',', ' ')
        .'<>'.number_format($som_Depenses2, 0, ',', ' ').'<>'.number_format(($som_Clients2+$som_SortiesVente2+$som_Services2), 0, ',', ' ').'<>'.number_format(($som_Depenses2+$som_FournisseursV+$som_ClientsBE2), 0, ',', ' ')
        .'<>'.number_format(($som_Clients2+$som_SortiesVente2+$som_Services2)-($som_Depenses2+$som_FournisseursV+$som_ClientsBE2), 0, ',', ' ').'<>'.number_format(($som_SortiesBon2+$som_ClientsBE2), 0, ',', ' ')
        .'<>'.number_format(($som_EntreesPA2+$som_EntreesPU2), 0, ',', ' ').'<>'.number_format(($som_Clients2+$som_SortiesVente2+$som_Services2)-($som_Depenses2+$som_FournisseursV+$som_ClientsBE2)+$SommeMontantAverser+$montantPU-$restant_FournisseursD, 0, ',', ' ').'<>'.number_format($SommeMontantAverser, 0, ',', ' ').'<>'.number_format($montantPU, 0, ',', ' ').'<>'.number_format($montantPA, 0, ',', ' ').'<>'.number_format($restant_Fournisseurs, 0, ',', ' ')
        .'<>'.number_format($restant_FournisseursD, 0, ',', ' ');*/
        exit($result);
