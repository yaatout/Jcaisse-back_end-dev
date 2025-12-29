<?php

    //$date = new DateTime('25-02-2011');
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

    require('../connectionPDO.php');
    require('../declarationVariables.php');

    //Mois Precedent
    $moisP=$mois-1;
        $anneeP=$annee;
        if($moisP<10){
            $moisP='0'.$moisP;
            if($mois=='01'){
                $moisP=12;
                $anneeP=$annee-1;
                $anneeP="$anneeP";
            }
        }
     
    $partAccompagnateur=0;
    
    echo 'kkkkkkkk';
    /**************************************** DEBUT ******************************************************/
        //DEBUT les boutiques en phase d'esploitation sans montantFixeHorsParametre
        $sql2 = $bdd->prepare("SELECT * FROM `aaa-boutique` WHERE enTest =:e and activer=:a and montantFixeHorsParametre=:m"); 
        $sql2->execute(array('e' =>1,'a' =>1,'m' =>0))  or die(print_r($sql2->errorInfo()));  
            while ($boutique =$sql2->fetch()) {
                
                //$sql3a="SELECT * FROM `z-testpayement-testsalaire` where idBoutique ( datePS LIKE '%".$anneeP."-".$moisP."%' or datePS LIKE '%".$moisP."-".$anneeP."%' ) and aPayementBoutique=1 and 
                // idBoutique=".$boutique["idBoutique"];
                
                
                $sql3a = $bdd->prepare("SELECT * FROM `z-testpayement-testsalaire` where idBoutique=:i ORDER BY `z-testpayement-testsalaire`.`idPS` DESC LIMIT 0,1"); 
                $sql3a->execute(array('i' =>$boutique["idBoutique"]))  or die(print_r($sql3a->errorInfo())); 
                $test=$sql3a->fetch();
                
                $num_rows= count($test);
                // Si c'est la premiere fois de recalcule pour la boutique ou bien la boutique concernee a paye son dernier mois
                //if ($num_rows==0 || $test['aPayementBoutique']==1 ) {
                 if (!$test || $test['aPayementBoutique']==1 ) {
                    //volume moyenne des données de chaque boutique
                    $partAccompagnateur=0;
                    $etapeAccompagnement=0;
                    $volumeMoyenne=0;

                    $tailleCatal=0;
                    $nomtableDesignation=$boutique["nomBoutique"]."-designation";

                    
                    $sql = $bdd->prepare("SELECT count(*)  as nbreRef FROM `". $nomtableDesignation."` where classe=:c"); 
                    $sql->execute(array('c' =>0))  or die(print_r($sql->errorInfo())); 
                    
                    
                    if($compteur = $sql->fetch())
                        $tailleCatal=$compteur["nbreRef"];

                    $nomtableStock="";
                    if ($boutique["type"]=="Entrepot") {
                        $nomtableStock=$boutique["nomBoutique"]."-entrepotstock";
                        
                    } else {
                        $nomtableStock=$boutique["nomBoutique"]."-stock";
                    } 
                     //var_dump($nomtableStock);   
                    $tailleStocks=0;

                    $sql = $bdd->prepare("SELECT count(*) as nbreStock FROM `". $nomtableStock."` where quantiteStockCourant!=:q"); 
                    $sql->execute(array('q' =>0))  or die(print_r($sql->errorInfo())); 


                    if($compteur =$sql->fetch())
                        $tailleStocks=$compteur["nbreStock"];

                    $volumeMoyenne=($tailleCatal+$tailleStocks)/2;
                    //recherche des varibles de Paiement de chaque boutique

                    $sql3 = $bdd->prepare("SELECT * FROM `aaa-variablespayement` where typecaisse=:t and 
                    categoriecaisse=:c and moyenneVolumeMin<=:min and moyenneVolumeMax>=:max"); 
                    $sql3->execute(array(
                                    't' =>$boutique["type"],
                                    'c' =>$boutique["categorie"],
                                    'min' =>$volumeMoyenne,
                                    'max' =>$volumeMoyenne
                                    ))  or die(print_r($sql3->errorInfo())); 

                    //echo $sql3;
                    /*    le nombre de mois entre deux dates    */

                    $datetime1 = new DateTime($boutique["datecreation"]);
                    $annee1 =$datetime1->format('Y');
                    $mois1 =$datetime1->format('m');

                    $datetime2 = new DateTime($dateString);
                    $annee2 =$datetime2->format('Y');
                    $mois2 =$datetime2->format('m');

                    $etapeAccompagnement = ($mois2-$mois1)+12*($annee2-$annee1)+1;
                       
                    /*    le nombre de mois entre deux dates    */
                    if($variable = $sql3->fetch()){ 
                        //Verification si paiement boutique
                        $sqa = $bdd->prepare("SELECT * FROM `z-testpayement-testsalaire` where ( datePS LIKE '%".$annee."-".$mois."%' or datePS LIKE '%".$mois."-".$annee."%' )
                        and idBoutique=:b"); 
                        $sqa->execute(array('b' =>$boutique["idBoutique"]))  or die(print_r($sqa->errorInfo())); 
                        $rea=$sqa->fetch();
                        
                        $num_rowsEA= count($rea);
                        //Si ppm=0 
                        //if($num_rowsEA>0){  
                        if($rea){   
                            if ($rea['pPlusieursMois']==0) {
                                if($variable["activerMontant"]==1){
                                    if($etapeAccompagnement==1){
                                            $partAccompagnateur=$variable["montant"]*50/100;
                                    }else if (($etapeAccompagnement==2)||($etapeAccompagnement==3)){
                                        $partAccompagnateur=$variable["montant"]*20/100;
                                    }
                                    $req6 = $bdd->prepare("UPDATE `z-testpayement-testsalaire` set accompagnateur=:ac,
                                        datePS=:datPS,montantFixePayement=:mfp,pourcentagePayement=:pp,
                                        prixlignesPayement=:prP,minmontant=:minM,maxmontant=:maxM,
                                        variablePayementActiver=:vP,etapeAccompagnement=:etap,
                                        partAccompagnateur=:part where idBoutique=:ib 
                                            and ( datePS LIKE '%".$annee."-".$mois."%' or datePS LIKE '%".$mois."-".$annee."%' ) ");
                                    $req6->execute(array( 
                                                    'ac'=>$boutique["Accompagnateur"],
                                                    'datPS' => $dateString,
                                                    'mfp' => $variable["montant"],
                                                    'pp' => $variable["pourcentage"],
                                                    'prP' => $variable["prixLigne"],
                                                    'minM' => $variable["minmontant"],
                                                    'maxM' => $variable["maxmontant"],
                                                    'vP' => 1,
                                                    'etap' => $etapeAccompagnement,
                                                    'part' => $partAccompagnateur,
                                                    'ib' => $boutique["idBoutique"] )) or die(print_r($req6->errorInfo()));


                                }else if($variable["activerPourcentage"]==1){

                                    $req6 = $bdd->prepare("UPDATE `z-testpayement-testsalaire` set accompagnateur=:ac,
                                        datePS=:datPS,montantFixePayement=:mfp,pourcentagePayement=:pp,
                                        prixlignesPayement=:prP,minmontant=:minM,maxmontant=:maxM,
                                        variablePayementActiver=:vP,etapeAccompagnement=:etap,
                                        partAccompagnateur=:part where idBoutique=:ib 
                                            and ( datePS LIKE '%".$annee."-".$mois."%' or datePS LIKE '%".$mois."-".$annee."%' ) ");
                                    $req6->execute(array( 
                                                    'ac'=>$boutique["Accompagnateur"],
                                                    'datPS' => $dateString,
                                                    'mfp' => $variable["montant"],
                                                    'pp' => $variable["pourcentage"],
                                                    'prP' => $variable["prixLigne"],
                                                    'minM' => $variable["minmontant"],
                                                    'maxM' => $variable["maxmontant"],
                                                    'vP' => 2,
                                                    'etap' => $etapeAccompagnement,
                                                    'part' => $partAccompagnateur,
                                                    'ib' => $boutique["idBoutique"] )) or die(print_r($req6->errorInfo()));

                                }else if($variable["activerPrix"]==1){
                                    
                                    $req6 = $bdd->prepare("UPDATE `z-testpayement-testsalaire` set accompagnateur=:ac,
                                        datePS=:datPS,montantFixePayement=:mfp,pourcentagePayement=:pp,
                                        prixlignesPayement=:prP,minmontant=:minM,maxmontant=:maxM,
                                        variablePayementActiver=:vP,etapeAccompagnement=:etap,
                                        partAccompagnateur=:part where idBoutique=:ib 
                                            and ( datePS LIKE '%".$annee."-".$mois."%' or datePS LIKE '%".$mois."-".$annee."%' ) ");
                                    $req6->execute(array( 
                                                    'ac'=>$boutique["Accompagnateur"],
                                                    'datPS' => $dateString,
                                                    'mfp' => $variable["montant"],
                                                    'pp' => $variable["pourcentage"],
                                                    'prP' => $variable["prixLigne"],
                                                    'minM' => $variable["minmontant"],
                                                    'maxM' => $variable["maxmontant"],
                                                    'vP' => 3,
                                                    'etap' => $etapeAccompagnement,
                                                    'part' => $partAccompagnateur,
                                                    'ib' => $boutique["idBoutique"] )) or die(print_r($req6->errorInfo()));
                                }
                            }       
                        }else{
                                        //var_dump("5");
                                        if($variable["activerMontant"]==1){
                                            //var_dump("6");
                                            if($etapeAccompagnement==1){
                                                $partAccompagnateur=$variable["montant"]*50/100;
                                            }else if (($etapeAccompagnement==2)||($etapeAccompagnement==3)){
                                                $partAccompagnateur=$variable["montant"]*20/100;
                                            }
                                            //echo 'hhhh';
                                            //var_dump($partAccompagnateur);
                                            $req6 = $bdd->prepare("insert into `z-testpayement-testsalaire`(idBoutique,accompagnateur,datePS,montantFixePayement,pourcentagePayement,
                                                                    prixlignesPayement,minmontant,maxmontant,variablePayementActiver,etapeAccompagnement,partAccompagnateur) 
                                                                    values (:ib,:acc,:dat,:mnt,:prc,:prxL,:min,:max,:var,:etap,:part)");
                                            $req6->execute(array(
                                                                'ib' =>$boutique["idBoutique"],
                                                                'acc' =>$boutique["Accompagnateur"],
                                                                'dat' =>$dateString,
                                                                'mnt' =>$variable["montant"],
                                                                'prc' =>$variable["pourcentage"],
                                                                'prxL' =>$variable["prixLigne"],
                                                                'min' =>$variable["minmontant"],
                                                                'max' =>$variable["maxmontant"],
                                                                'var' =>1,
                                                                'etap' =>$etapeAccompagnement,
                                                                'part' =>$partAccompagnateur
                                                                ))  or die(print_r($req6->errorInfo()));

                                        }else if($variable["activerPourcentage"]==1){
                                                //var_dump("7");
                                                $req6 = $bdd->prepare("insert into `z-testpayement-testsalaire`(idBoutique,accompagnateur,datePS,montantFixePayement,pourcentagePayement,
                                                                    prixlignesPayement,minmontant,maxmontant,variablePayementActiver,etapeAccompagnement,partAccompagnateur) 
                                                                    values (:ib,:acc,:dat,:mnt,:prc,:prxL,:min,:max,:var,:etap,:part)");
                                                $req6->execute(array(
                                                                'ib' =>$boutique["idBoutique"],
                                                                'acc' =>$boutique["Accompagnateur"],
                                                                'dat' =>$dateString,
                                                                'mnt' =>$variable["montant"],
                                                                'prc' =>$variable["pourcentage"],
                                                                'prxL' =>$variable["prixLigne"],
                                                                'min' =>$variable["minmontant"],
                                                                'max' =>$variable["maxmontant"],
                                                                'var' =>2,
                                                                'etap' =>$etapeAccompagnement,
                                                                'part' =>$partAccompagnateur
                                                                ))  or die(print_r($req6->errorInfo()));

                                        }else if($variable["activerPrix"]==1){
                                            //var_dump("8");
                                            $req6 = $bdd->prepare("insert into `z-testpayement-testsalaire`(idBoutique,accompagnateur,datePS,montantFixePayement,pourcentagePayement,
                                                                    prixlignesPayement,minmontant,maxmontant,variablePayementActiver,etapeAccompagnement,partAccompagnateur) 
                                                                    values (:ib,:acc,:dat,:mnt,:prc,:prxL,:min,:max,:var,:etap,:part)");
                                            $req6->execute(array(
                                                                'ib' =>$boutique["idBoutique"],
                                                                'acc' =>$boutique["Accompagnateur"],
                                                                'dat' =>$dateString,
                                                                'mnt' =>$variable["montant"],
                                                                'prc' =>$variable["pourcentage"],
                                                                'prxL' =>$variable["prixLigne"],
                                                                'min' =>$variable["minmontant"],
                                                                'max' =>$variable["maxmontant"],
                                                                'var' =>3,
                                                                'etap' =>$etapeAccompagnement,
                                                                'part' =>$partAccompagnateur
                                                                ))  or die(print_r($req6->errorInfo()));

                                        }

                        }
                    }
                }
            }
        
        //FIN les boutiques en phase d'esploitation sans montantFixeHorsParametre
    /**************************************** FIN ******************************************************/
    /////////////////////////////////////////////////////////////////////////////////////////////////////

    /**************************************** DEBUT AVEC MONTANT HORS PARAMETRE******************************************************/
   
    $sql1 = $bdd->prepare("SELECT * FROM `aaa-boutique` WHERE enTest =:e and activer=:a and montantFixeHorsParametre>:m"); 
    $sql1->execute(array('e' =>1,'a' =>1,'m' =>0))  or die(print_r($sql1->errorInfo()));  
            while ($boutique =$sql1->fetch()) {            
                         
                //var_dump($boutique);
                $sql3a = $bdd->prepare("SELECT * FROM `z-testpayement-testsalaire` where idBoutique=:i ORDER BY `z-testpayement-testsalaire`.`idPS` DESC LIMIT 0,1"); 
                $sql3a->execute(array('i' =>$boutique["idBoutique"]))  or die(print_r($sql3a->errorInfo())); 
                $test=$sql3a->fetch();
                $num_rows= count($test);

                // var_dump($num_rows);
                //  var_dump($test['aPayementBoutique']);
                // SI la boutique est sa prémiere paiement ou s'il a payer son dernier mois
                if (!$test || $test['aPayementBoutique']==1 ) {
                /*    le nombre de mois entre deux dates    */
                    
                    $partAccompagnateur=0;
                    $etapeAccompagnement=0;
                    

                    //echo $sql3;
                    /*    le nombre de mois entre deux dates    */

                    $datetime1 = new DateTime($boutique["datecreation"]);
                    $annee1 =$datetime1->format('Y');
                    $mois1 =$datetime1->format('m');

                    $datetime2 = new DateTime($dateString);
                    $annee2 =$datetime2->format('Y');
                    $mois2 =$datetime2->format('m');

                    $etapeAccompagnement = ($mois2-$mois1)+12*($annee2-$annee1)+1;
                    
                    /*    le nombre de mois entre deux dates    */
                    
                        //Verification si paiement boutique
                        $sq3 = $bdd->prepare("SELECT * FROM `z-testpayement-testsalaire` where ( datePS LIKE '%".$annee."-".$mois."%' or datePS LIKE '%".$mois."-".$annee."%' )
                        and idBoutique=:b"); 
                        $sq3->execute(array('b' =>$boutique["idBoutique"]))  or die(print_r($sq3->errorInfo())); 
                        //var_dump("$'sq3'" );
                        //var_dump($boutique["idBoutique"] );
                        $ps=$sq3->fetch();
                        // echo "COUNTss  ".empty($ps);
                        // var_dump( $ps);
                        if ( $ps){ 
                            // echo "COUNT IF ".count($ps);
                            //     if($etapeAccompagnement==1){
                            //             $partAccompagnateur=$boutique["montantFixeHorsParametre"]*50/100;
                            //     }else if (($etapeAccompagnement==2)||($etapeAccompagnement==3)){
                            //         $partAccompagnateur=$boutique["montantFixeHorsParametre"]*20/100;
                            //     }
                            //     $req6 = $bdd->prepare("UPDATE `z-testpayement-testsalaire` set accompagnateur=:ac,
                            //         datePS=:datPS,montantFixePayement=:mfp,pourcentagePayement=:pp,
                            //         prixlignesPayement=:prP,minmontant=:minM,maxmontant=:maxM,
                            //         variablePayementActiver=:vP,etapeAccompagnement=:etap,
                            //         partAccompagnateur=:part where idBoutique=:ib 
                            //             and ( datePS LIKE '%".$annee."-".$mois."%' or datePS LIKE '%".$mois."-".$annee."%' ) ");
                            //     $req6->execute(array( 
                            //                     'ac'=>$boutique["Accompagnateur"],
                            //                     'datPS' => $dateString,
                            //                     'mfp' => $boutique["montantFixeHorsParametre"],
                            //                     'pp' => 1,
                            //                     'prP' => $boutique["montantFixeHorsParametre"],
                            //                     'minM' => $boutique["montantFixeHorsParametre"],
                            //                     'maxM' =>$boutique["montantFixeHorsParametre"],
                            //                     'vP' => 1,
                            //                     'etap' => $etapeAccompagnement,
                            //                     'part' => $partAccompagnateur,
                            //                     'ib' => $boutique["idBoutique"] )) or die(print_r($req6->errorInfo()));                            
                        }else{
                                  // var_dump("6");
                            //echo "COUNT ELSE ".count($ps);
                                    if($etapeAccompagnement==1){
                                        $partAccompagnateur=$boutique["montantFixeHorsParametre"]*50/100;
                                    }else if (($etapeAccompagnement==2)||($etapeAccompagnement==3)){
                                        $partAccompagnateur=$boutique["montantFixeHorsParametre"]*20/100;
                                    }
                                    //echo 'hhhh';
                                    //var_dump($partAccompagnateur);
                                    $req6 = $bdd->prepare("insert into `z-testpayement-testsalaire`(idBoutique,accompagnateur,datePS,montantFixePayement,pourcentagePayement,
                                                            prixlignesPayement,minmontant,maxmontant,variablePayementActiver,etapeAccompagnement,partAccompagnateur) 
                                                            values (:ib,:acc,:dat,:mnt,:prc,:prxL,:min,:max,:var,:etap,:part)");
                                    $req6->execute(array(
                                                        'ib' =>$boutique["idBoutique"],
                                                        'acc' =>$boutique["Accompagnateur"],
                                                        'dat' =>$dateString,
                                                        'mnt' =>$boutique["montantFixeHorsParametre"],
                                                        'prc' =>1,
                                                        'prxL' =>$boutique["montantFixeHorsParametre"],
                                                        'min' =>$boutique["montantFixeHorsParametre"],
                                                        'max' =>$boutique["montantFixeHorsParametre"],
                                                        'var' =>1,
                                                        'etap' =>$etapeAccompagnement,
                                                        'part' =>$partAccompagnateur
                                                        ))  or die(print_r($req6->errorInfo()));

                                

                        }
            }
        }
    //die('fin');
    /**************************************** FIN AVEC MONTANT HORS PARAMETRE******************************************************/
    /******************************************************************************************************************************/

?>