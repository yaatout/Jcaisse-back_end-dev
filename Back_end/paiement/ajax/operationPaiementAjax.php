<?php
session_start();
if(!$_SESSION['iduserBack']){
	header('Location:../../index.php');
}
require('../../connectionPDO.php');
require('../../declarationVariables.php');

$date = new DateTime();
$timezone = new DateTimeZone('Africa/Dakar');
$date->setTimezone($timezone);

$annee =$date->format('Y');
$mois =$date->format('m');
$jour =$date->format('d');
$heureString=$date->format('H:i:s');
$dateString=$annee.'-'.$mois.'-'.$jour ;
$dateString2=$jour.'-'.$mois.'-'.$annee ;


$dateHeures=$dateString.' '.$heureString;

$operation=$_POST['operation'];

if ($operation=='recalcule') {
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
                
                //$sql3a="SELECT * FROM `aaa-payement-salaire` where idBoutique ( datePS LIKE '%".$anneeP."-".$moisP."%' or datePS LIKE '%".$moisP."-".$anneeP."%' ) and aPayementBoutique=1 and 
                // idBoutique=".$boutique["idBoutique"];
                
                
                $sql3a = $bdd->prepare("SELECT * FROM `aaa-payement-salaire` where idBoutique=:i ORDER BY `aaa-payement-salaire`.`idPS` DESC LIMIT 0,1"); 
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
                        $sqa = $bdd->prepare("SELECT * FROM `aaa-payement-salaire` where ( datePS LIKE '%".$annee."-".$mois."%' or datePS LIKE '%".$mois."-".$annee."%' )
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
                                    $req6 = $bdd->prepare("UPDATE `aaa-payement-salaire` set accompagnateur=:ac,
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

                                    $req6 = $bdd->prepare("UPDATE `aaa-payement-salaire` set accompagnateur=:ac,
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
                                    
                                    $req6 = $bdd->prepare("UPDATE `aaa-payement-salaire` set accompagnateur=:ac,
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
                                            $req6 = $bdd->prepare("insert into `aaa-payement-salaire`(idBoutique,accompagnateur,datePS,montantFixePayement,pourcentagePayement,
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
                                                $req6 = $bdd->prepare("insert into `aaa-payement-salaire`(idBoutique,accompagnateur,datePS,montantFixePayement,pourcentagePayement,
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
                                            $req6 = $bdd->prepare("insert into `aaa-payement-salaire`(idBoutique,accompagnateur,datePS,montantFixePayement,pourcentagePayement,
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
                $sql3a = $bdd->prepare("SELECT * FROM `aaa-payement-salaire` where idBoutique=:i ORDER BY `aaa-payement-salaire`.`idPS` DESC LIMIT 0,1"); 
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
                        $sq3 = $bdd->prepare("SELECT * FROM `aaa-payement-salaire` where ( datePS LIKE '%".$annee."-".$mois."%' or datePS LIKE '%".$mois."-".$annee."%' )
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
                            //     $req6 = $bdd->prepare("UPDATE `aaa-payement-salaire` set accompagnateur=:ac,
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
                                    $req6 = $bdd->prepare("insert into `aaa-payement-salaire`(idBoutique,accompagnateur,datePS,montantFixePayement,pourcentagePayement,
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
} else if ($operation=="versementPaiOM") {
    $dateTransfert=$_POST['dateTransfert'];
    $montantTransfert=$_POST['montantTransfert'];
    $refTransf=$_POST['refTransf'];
    $numTel=$_POST['numTel'];

	$typeCompteMobile='Orange Money';
	$avecFrais=0;
	$frais=0;
    if(isset($_POST['avecFrais'])){
        $avecFrais=1;
    }
    if(isset($_POST['frais'])){
        $frais=$_POST['frais'];
    }
	$activer=1;
    $montantNonConforme=1;

    if (($refTransf!="")&&($numTel!="")){
		
        $sql2 = $bdd->prepare("SELECT * FROM `aaa-payement-reference` where refTransfertValidation=:ref");     
        $sql2->execute(array('ref'=>$refTransf))  or die(print_r($sql2->errorInfo()));  
        $reference= $sql2->fetch();
        
		if( empty($reference)){
            $req3 = $bdd->prepare("insert into `aaa-payement-reference` 
                (dateRefTransfertValidation,montant,refTransfertValidation,telRefTransfertValidation,avecFrais,frais) 
                values(:dateRef,:mont,:refTrans,:telRef,:avF,:fr)");
            $req3->execute(array(
                                'dateRef' =>$dateTransfert,
                                'mont' =>$montantTransfert,
                                'refTrans' =>$refTransf,
                                'telRef' =>$numTel,
                                'avF' =>$avecFrais,
                                'fr' =>$frais
            ))  or die(print_r($req3->errorInfo()));

                    /**********************************TABLE COMPTE *****************************************/
                          
                          $sql8 = $bdd->prepare("SELECT * FROM `aaa-payement-reference` where refTransfertValidation=:ref and dateRefTransfertValidation=:dateRef");     
                          $sql8->execute(array('ref'=>$refTransf,'dateRef'=>$dateTransfert));  
                          $payementReference= $sql8->fetch()  or die(print_r($sql8->errorInfo()));

                          $sqlv = $bdd->prepare("select * from `aaa-compte` where nomCompte like '%".$typeCompteMobile."%'");     
                          $sqlv->execute()  or die(print_r($sqlv->errorInfo()));  
                          $compte= $sqlv->fetch();

                          if($compte){
                          		//var_dump('3');
                            $operation='depot';
                            $idCompte=$compte['idCompte'];
                            $description=$refTransf;
                            $newMontant=$compte['montantCompte']+$montantTransfert;

                              
                            $operation='depot';
                            $idCompte=$compte['idCompte'];
                            $description=$refTransf;
                            $newMontant=$compte['montantCompte']+$montantTransfert;
    
                            $req6 = $bdd->prepare("insert into `aaa-comptemouvement` (montant,operation,idCompte,dateSaisie,dateOperation,description,idUser,idPR)
                                                            values(:mont,:op,:idC,:dateS,:dateO,:descr,:idUs,:idPR)");
                            $req6->execute(array(
                                                  'mont' =>$montantTransfert,
                                                  'op' =>$operation,
                                                  'idC' =>$idCompte,
                                                  'dateS' =>$dateHeures,
                                                  'dateO' =>$dateTransfert,
                                                  'descr' =>$description,
                                                  'idUs' =>$_SESSION['iduserBack'],
                                                  'idPR' =>$payementReference['id']
                                                  ))  or die(print_r($req6->errorInfo()));
    
                                
                            $req7 = $bdd->prepare("UPDATE `aaa-compte` set  montantCompte=:mntC WHERE idCompte=:idc ");
                            $req7->execute(array( 'mntC' => $newMontant,'idc' => $compte['idCompte'] )) or die(print_r($req7->errorInfo()));
                          }
                    /********************************TABLE COMPTE **************************************/

            $sql4 = $bdd->prepare("SELECT * FROM `aaa-payement-salaire` WHERE refTransfert=:ref");     
                
                if($sql4->execute(array('ref'=>$refTransf))){
                    $paiement = $sql4->fetch();
                    if ($paiement['refTransfert']==$refTransf and $paiement['montantFixePayement']<=$montantTransfert){

                        $req5 = $bdd->prepare("UPDATE `aaa-payement-salaire` set  aPayementBoutique=:act WHERE refTransfert=:ref ");
                        $req5->execute(array( 'act' => $activer,'ref' => $refTransf)) or die(print_r($req5->errorInfo()));

                        $req6 = $bdd->prepare("UPDATE `aaa-payement-reference` set  idPS=:idPS WHERE refTransfertValidation=:ref ");
                        $req6->execute(array( 'idPS' => $paiement['idPS'],'refTransfertValidation' => $refTransf)) or die(print_r($req6->errorInfo()));

                    }else{

                        $req5 = $bdd->prepare("UPDATE `aaa-payement-reference` set  montantNonConforme=:mt,`idPS`=:idPS WHERE refTransfertValidation=:ref ");
                        $req5->execute(array( 'mt' => $montantNonConforme,'idPS' => $paiement['idPS'],'ref'=>$refTransf )) or die(print_r($req5->errorInfo()));
                    }
                }

		}
	}

} else if ($operation=="versementPaiWav") {
    $dateTransfert=$_POST['dateTransfert'];
    $montantTransfert=$_POST['montantTransfert'];
    $refTransf=$_POST['refTransf'];
    $numTel=$_POST['numTel'];

	$typeCompteMobile='Wave';
	
	$activer=1;
    $montantNonConforme=1;

    if (($refTransf!="")&&($numTel!="")){
		
        $sql2 = $bdd->prepare("SELECT * FROM `aaa-payement-reference` where refTransfertValidation=:ref");     
        $sql2->execute(array('ref'=>$refTransf))  or die(print_r($sql2->errorInfo()));  
        $reference= $sql2->fetch();
        
		if( empty($reference)){
            $req3 = $bdd->prepare("insert into `aaa-payement-reference` 
                (dateRefTransfertValidation,montant,refTransfertValidation,telRefTransfertValidation) 
                values(:dateRef,:mont,:refTrans,:telRef)");
            $req3->execute(array(
                                'dateRef' =>$dateTransfert,
                                'mont' =>$montantTransfert,
                                'refTrans' =>$refTransf,
                                'telRef' =>$numTel
            ))  or die(print_r($req3->errorInfo()));

                    /**********************************TABLE COMPTE *****************************************/
                          
                          $sql8 = $bdd->prepare("SELECT * FROM `aaa-payement-reference` where refTransfertValidation=:ref and dateRefTransfertValidation=:dateRef");     
                          $sql8->execute(array('ref'=>$refTransf,'dateRef'=>$dateTransfert));  
                          $payementReference= $sql8->fetch()  or die(print_r($sql8->errorInfo()));

                          $sqlv = $bdd->prepare("select * from `aaa-compte` where nomCompte like '%".$typeCompteMobile."%'");     
                          $sqlv->execute()  or die(print_r($sqlv->errorInfo()));  
                          $compte= $sqlv->fetch();

                          if($compte){
                          		//var_dump('3');
                            $operation='depot';
                            $idCompte=$compte['idCompte'];
                            $description=$refTransf;
                            $newMontant=$compte['montantCompte']+$montantTransfert;

                              
                            $operation='depot';
                            $idCompte=$compte['idCompte'];
                            $description=$refTransf;
                            $newMontant=$compte['montantCompte']+$montantTransfert;
    
                            $req6 = $bdd->prepare("insert into `aaa-comptemouvement` (montant,operation,idCompte,dateSaisie,dateOperation,description,idUser,idPR)
                                                            values(:mont,:op,:idC,:dateS,:dateO,:descr,:idUs,:idPR)");
                            $req6->execute(array(
                                                  'mont' =>$montantTransfert,
                                                  'op' =>$operation,
                                                  'idC' =>$idCompte,
                                                  'dateS' =>$dateHeures,
                                                  'dateO' =>$dateTransfert,
                                                  'descr' =>$description,
                                                  'idUs' =>$_SESSION['iduserBack'],
                                                  'idPR' =>$payementReference['id']
                                                  ))  or die(print_r($req6->errorInfo()));
    
                                
                            $req7 = $bdd->prepare("UPDATE `aaa-compte` set  montantCompte=:mntC WHERE idCompte=:idc ");
                            $req7->execute(array( 'mntC' => $newMontant,'idc' => $compte['idCompte'] )) or die(print_r($req7->errorInfo()));
                          }
                    /********************************TABLE COMPTE **************************************/

            $sql4 = $bdd->prepare("SELECT * FROM `aaa-payement-salaire` WHERE refTransfert=:ref");     
                
                if($sql4->execute(array('ref'=>$refTransf))){
                    $paiement = $sql4->fetch();
                    if ($paiement['refTransfert']==$refTransf and $paiement['montantFixePayement']<=$montantTransfert){

                        $req5 = $bdd->prepare("UPDATE `aaa-payement-salaire` set  aPayementBoutique=:act WHERE refTransfert=:ref ");
                        $req5->execute(array( 'act' => $activer,'ref' => $refTransf)) or die(print_r($req5->errorInfo()));

                        $req6 = $bdd->prepare("UPDATE `aaa-payement-reference` set  idPS=:idPS WHERE refTransfertValidation=:ref ");
                        $req6->execute(array( 'idPS' => $paiement['idPS'],'refTransfertValidation' => $refTransf)) or die(print_r($req6->errorInfo()));

                    }else{

                        $req5 = $bdd->prepare("UPDATE `aaa-payement-reference` set  montantNonConforme=:mt,`idPS`=:idPS WHERE refTransfertValidation=:ref ");
                        $req5->execute(array( 'mt' => $montantNonConforme,'idPS' => $paiement['idPS'],'ref'=>$refTransf )) or die(print_r($req5->errorInfo()));
                    }
                }

		}
	}

} else if ($operation=='chercherPS') {
    $idPS=$_POST['a'];
    $sqla = $bdd->prepare("SELECT * FROM `aaa-payement-salaire` WHERE idPS=:idPS");     
    $sqla->execute(array('idPS'=>$idPS));  
    $paiement= $sqla->fetch();
    $result=$paiement['idPS'].'<>'.$paiement['idBoutique'].'<>'.$paiement['accompagnateur'].'<>'.$paiement['montantFixePayement'].'<>'.
            $paiement['pourcentagePayement'].'<>'.$paiement['prixlignesPayement'].'<>'.$paiement['minmontant'].'<>'.$paiement['maxmontant'].'<>'.
            $paiement['datePS'].'<>'.$paiement['variablePayementActiver'].'<>'.$paiement['etapeAccompagnement'].'<>'.$paiement['partAccompagnateur'].'<>'.
            $paiement['aPayementBoutique'].'<>'.$paiement['datePaiement'].'<>'.$paiement['heurePaiement'].'<>'.$paiement['aSalaireAccompagnateur'].'<>'.
            $paiement['naturePayement'].'<>'.$paiement['refTransfert'].'<>'.$paiement['telRefTransfert'].'<>'.$paiement['dateRefTransfert'].'<>'.$paiement['pPlusieursMois'];
    exit($result);

}else if ($operation=='validationPaiOM') {
    //echo "YASSS";
	$idPS=$_POST['idPS'];

        $sqla = $bdd->prepare("SELECT * FROM `aaa-payement-salaire` WHERE idPS=:idPS");     
        $sqla->execute(array('idPS'=>$idPS))   or die(print_r($sqla->errorInfo()));  
        $paiement= $sqla->fetch();
        //echo $paiement['montantFixePayement'];
        // $montantFixePayement=$paiement['montantFixePayement'];
        $dateTransfert=$_POST['dateTransOMValidation'];
        $montantTransfert=$_POST['montantFPaiementOMValidation'];
        $refTransf=$_POST['refTransfOMValidation'];
        $numTel=$_POST['numTelOMValidation'];
        $typeCompteMobile='Orange Money';
        $avecFrais=0;
        $frais=0;
        //echo $dateTransfert.'<>'.$montantFixePayement.'<>'.$montantTransfert.'<>'.$refTransf.'<>'.$numTel.'<>'.$typeCompteMobile;
        if(isset($_POST['avecFrais'])){
            $avecFrais=1;
        }
        if(isset($_POST['frais'])){
            $frais=$_POST['frais'];
        }

        $activer=1;
        $montantNonConforme=1;

    $sql2 = $bdd->prepare("SELECT * FROM `aaa-payement-reference` where refTransfertValidation=:ref");     
    $sql2->execute(array('ref'=>$refTransf))  or die(print_r($sql2->errorInfo()));  
    $reference= $sql2->fetch();
    //echo '00000000';
		if(! empty($refTransf)){
            //echo '11111';
            if (($refTransf!="")&&($numTel!="")){
                $req3 = $bdd->prepare("insert into `aaa-payement-reference` 
                (dateRefTransfertValidation,montant,refTransfertValidation,telRefTransfertValidation,idPS,avecFrais,frais) 
                values(:dateRef,:mont,:refTrans,:telRef,:idPS,:avF,:fr)");
                $req3->execute(array(
                                'dateRef' =>$dateTransfert,
                                'mont' =>$montantTransfert,
                                'refTrans' =>$refTransf,
                                'telRef' =>$numTel,
                                'idPS' =>$idPS,
                                'avF' =>$avecFrais,
                                'fr' =>$frais
                                ))  or die(print_r($req3->errorInfo()));

                    /**********************************TABLE COMPTE *****************************************/
                
                            $sql8 = $bdd->prepare("SELECT * FROM `aaa-payement-reference` where refTransfertValidation=:ref and dateRefTransfertValidation=:dateRef");     
                            $sql8->execute(array('ref'=>$refTransf,'dateRef'=>$dateTransfert));  
                            $payementReference= $sql8->fetch()  or die(print_r($sql8->errorInfo()));

                            $sqlv = $bdd->prepare("select * from `aaa-compte` where nomCompte like '%".$typeCompteMobile."%'");     
                            $sqlv->execute()  or die(print_r($sqlv->errorInfo()));  
                            $compte= $sqlv->fetch();

                          if($compte){
                          		//var_dump('222');
                              $operation='depot';
                              $idCompte=$compte['idCompte'];
                              $description=$refTransf;
                              $newMontant=$compte['montantCompte']+$montantTransfert;

                              $req6 = $bdd->prepare("insert into `aaa-comptemouvement` (montant,operation,idCompte,dateSaisie,dateOperation,description,idUser,idPR)
                                                        values(:mont,:op,:idC,:dateS,:dateO,:descr,:idUs,:idPR)");
                              $req6->execute(array(
                                              'mont' =>$montantTransfert,
                                              'op' =>$operation,
                                              'idC' =>$idCompte,
                                              'dateS' =>$dateHeures,
                                              'dateO' =>$dateTransfert,
                                              'descr' =>$description,
                                              'idUs' =>$_SESSION['iduserBack'],
                                              'idPR' =>$payementReference['id']
                                              ))  or die(print_r($req6->errorInfo()));

                                $req7 = $bdd->prepare("UPDATE `aaa-compte` set  montantCompte=:mntC WHERE idCompte=:idc ");
                                $req7->execute(array( 'mntC' => $newMontant,'idc' => $compte['idCompte'] )) or die(print_r($req7->errorInfo()));

                          }
                    /********************************TABLE COMPTE **************************************/

                $sql4 = $bdd->prepare("SELECT * FROM `aaa-payement-salaire` WHERE idPS=:idPS");     
                
                if($sql4->execute(array('idPS'=>$idPS))){
                    
                     //echo '333333';
                    $paiement = $sql4->fetch();
                    if ($paiement['montantFixePayement']==$montantTransfert){

                        $req5 = $bdd->prepare("UPDATE `aaa-payement-salaire` set  aPayementBoutique=:act WHERE idPS=:id ");
                        $req5->execute(array( 'act' => $activer,'id' => $idPS)) or die(print_r($req5->errorInfo()));

                    }else{

                        $req5 = $bdd->prepare("UPDATE `aaa-payement-reference` set  montantNonConforme=:mt WHERE idPS=:id ");
                        $req5->execute(array( 'mt' => $montantNonConforme,'id' => $idPS)) or die(print_r($req5->errorInfo()));
                    }
                }
            }
        }

}elseif ($operation=="validationPaiWav") {
    $idPS=$_POST['idPS'];

        $sqla = $bdd->prepare("SELECT * FROM `aaa-payement-salaire` WHERE idPS=:idPS");     
        $sqla->execute(array('idPS'=>$idPS))   or die(print_r($sqla->errorInfo()));  
        $paiement= $sqla->fetch();
        //echo $paiement['montantFixePayement'];

        $montantTransfert=$paiement['montantFixePayement'];
        $dateTransfert=$_POST['dateTransfert'];
        $numTel=$_POST['numTel'];
        $typeCompteMobile='Wave';

        //$refTransf='Sans reference';
        $activer=1;
        $montantNonConforme=1;
        if(isset($_POST['refTransf'])){
            if ($_POST['refTransf'] !='') {
                // code...
                $refTransf=$_POST['refTransf'];
            }
        }

        $sql2 = $bdd->prepare("SELECT * FROM `aaa-payement-reference` where refTransfertValidation=:ref");     
    $sql2->execute(array('ref'=>$refTransf))  or die(print_r($sql2->errorInfo()));  
    $reference= $sql2->fetch();
    //echo '00000000';
		if(! empty($refTransf)){
            //echo '11111';
            if (($refTransf!="")&&($numTel!="")){
                $req3 = $bdd->prepare("insert into `aaa-payement-reference` 
                (dateRefTransfertValidation,montant,refTransfertValidation,telRefTransfertValidation,idPS) 
                values(:dateRef,:mont,:refTrans,:telRef,:idPS)");
                $req3->execute(array(
                                'dateRef' =>$dateTransfert,
                                'mont' =>$montantTransfert,
                                'refTrans' =>$refTransf,
                                'telRef' =>$numTel,
                                'idPS' =>$idPS
                                ))  or die(print_r($req3->errorInfo()));

                    /**********************************TABLE COMPTE *****************************************/
                
                            $sql8 = $bdd->prepare("SELECT * FROM `aaa-payement-reference` where refTransfertValidation=:ref and dateRefTransfertValidation=:dateRef");     
                            $sql8->execute(array('ref'=>$refTransf,'dateRef'=>$dateTransfert));  
                            $payementReference= $sql8->fetch()  or die(print_r($sql8->errorInfo()));

                            $sqlv = $bdd->prepare("select * from `aaa-compte` where nomCompte like '%".$typeCompteMobile."%'");     
                            $sqlv->execute()  or die(print_r($sqlv->errorInfo()));  
                            $compte= $sqlv->fetch();

                          if($compte){
                          		//var_dump('222');
                              $operation='depot';
                              $idCompte=$compte['idCompte'];
                              $description=$refTransf;
                              $newMontant=$compte['montantCompte']+$montantTransfert;

                              $req6 = $bdd->prepare("insert into `aaa-comptemouvement` (montant,operation,idCompte,dateSaisie,dateOperation,description,idUser,idPR)
                                                        values(:mont,:op,:idC,:dateS,:dateO,:descr,:idUs,:idPR)");
                              $req6->execute(array(
                                              'mont' =>$montantTransfert,
                                              'op' =>$operation,
                                              'idC' =>$idCompte,
                                              'dateS' =>$dateHeures,
                                              'dateO' =>$dateTransfert,
                                              'descr' =>$description,
                                              'idUs' =>$_SESSION['iduserBack'],
                                              'idPR' =>$payementReference['id']
                                              ))  or die(print_r($req6->errorInfo()));

                                $req7 = $bdd->prepare("UPDATE `aaa-compte` set  montantCompte=:mntC WHERE idCompte=:idc ");
                                $req7->execute(array( 'mntC' => $newMontant,'idc' => $compte['idCompte'] )) or die(print_r($req7->errorInfo()));

                          }
                    /********************************TABLE COMPTE **************************************/

                $sql4 = $bdd->prepare("SELECT * FROM `aaa-payement-salaire` WHERE idPS=:idPS");     
                
                if($sql4->execute(array('idPS'=>$idPS))){
                    
                     //echo '333333';
                    $paiement = $sql4->fetch();
                    if ($paiement['montantFixePayement']==$montantTransfert){

                        $req5 = $bdd->prepare("UPDATE `aaa-payement-salaire` set  aPayementBoutique=:act WHERE idPS=:id ");
                        $req5->execute(array( 'act' => $activer,'id' => $idPS)) or die(print_r($req5->errorInfo()));

                    }else{

                        $req5 = $bdd->prepare("UPDATE `aaa-payement-reference` set  montantNonConforme=:mt WHERE idPS=:id ");
                        $req5->execute(array( 'mt' => $montantNonConforme,'id' => $idPS)) or die(print_r($req5->errorInfo()));
                    }
                }
            }
        }
}


?>