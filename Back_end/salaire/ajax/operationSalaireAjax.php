<?php
session_start();
if(!$_SESSION['iduserBack']){
	header('Location:../../index.php');
}
require('../../connectionPDO.php');
require('../../declarationVariables.php');


$date = new DateTime();
//R�cup�ration de l'ann�e
$annee =$date->format('Y');
//R�cup�ration du mois
$mois =$date->format('m');
//R�cup�ration du jours
$jour =$date->format('d');
$heureString=$date->format('H:i:s');
$dateString=$annee.'-'.$mois.'-'.$jour;
//var_dump($dateString);
$dateString2=$jour.'-'.$mois.'-'.$annee;
$dateHeures=$dateString.' '.$heureString;

//require_once '../functions/salaire_functions.php';
$operation=$_POST['operation'];

switch ($operation) {
    case 'detailSalaireAcc':
            $matricule=$_POST['mat'];
            $result='';
            $tab="";
            $sql2 = $bdd->prepare("SELECT * FROM `aaa-payement-salaire` 
                WHERE accompagnateur =:acc 
                and partAccompagnateur>:part
                and aSalaireAccompagnateur=:asa "); 
            $sql2->execute(array('acc' =>$matricule,'part'=>0,'asa' =>0))  
                    or die(print_r($sql2->errorInfo())); 
                    $payements=$sql2->fetchAll() ;
                    //var_dump($payements);
                    //die();
                $tableHead="<table  class='table table-bordered table-striped' id='table' >
                                    <thead>
                                        <tr>
                                            <th>NOM BOUTIQUE</th>
                                            <th>DATE PAIEMENT</th>
                                            <th>MONTANT</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
             $tableFoot="</tbody> </table>";
            foreach($payements as $payement) {
                $i=0;
                $result1="";
                $result2="";
                //var_dump($payement["idBoutique"]);
                    //$boutique=recupererBoutique($bdd,$payement["idBoutique"]);
                    $stmt = $bdd->prepare("
                        SELECT * FROM `aaa-boutique` 
                        WHERE idBoutique = :idBoutique
                    ");
                    $stmt->execute([':idBoutique' => $idBoutique]);
                    $boutique= $stmt->fetch(PDO::FETCH_ASSOC) ;

                    $result1="<tr>
                                <td><b>".$boutique['labelB']."</b></td>
                                <td><b>".$payement['datePS']."</b></td>";
                    if ($payement['aPayementBoutique']==1) {
                        $result2 ="<td>".$payement['partAccompagnateur']."<td>  ";
                    } else {
                        $result2 ="<td>Boutique pas encore payé </td> ";
                    }
                    
                    //$result2 = ($payement['aPayementBoutique']==1) ? "<td>".$payement['partAccompagnateur']."<td>  " : "<td>Pas encore</td> " ;
                    $tab=$tab.$result1.$result2."<tr>";
                    //var_dump($tab);          
                    
             }
             $result=$tableHead.$tab.$tableFoot;
             echo $result; 
        break;
    
    case "popVirerSalaireAcc":
        $id=$_POST['i'];
        $montant=$_POST['mont'];
        //$user=recupererUtilisateursById($bdd,$id);
        $stmt = $bdd->prepare("SELECT * FROM `aaa-utilisateur` WHERE idutilisateur = :id  ");
        $stmt->execute(["id"=>$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);  
        $tab[0]=$user["prenom"]."<>".$user["nom"]."<>".$user["profil"]."<>".$user["telPortable"]."<>".$user["matricule"];

        $stmt = $bdd->prepare("select * from `aaa-compte` 
                    WHERE (`typeCompte`=:n1 OR `typeCompte`=:n2) 
                    AND `montantCompte` >=:mont");
        $stmt->execute(['n1'=>2,'n2'=>5,'mont' => $montant]);
        $comptes=$stmt->fetchAll(PDO::FETCH_ASSOC) ; 
        //var_dump( $comptes);
        //die();
        $options="";

        foreach ($comptes as $compte) {
            $options= $options.'<option value="'.$compte["idCompte"].'">'.$compte["nomCompte"].'</option>';
        }  
        $tab[1]=$options;
        echo json_encode( $tab);

        break;
    case "popVirerSalairePers":
        $iC=$_POST['iC'];
        $iP=$_POST['iP'];
        $iS=$_POST['iS'];
        //$user=recupererUtilisateursById($bdd,$id);
        $stmt1 = $bdd->prepare("select * from `aaa-contrat` WHERE  `idContrat` =:iC");
        $stmt1->execute(['iC' => $iC]);
        $contrat=$stmt1->fetch(PDO::FETCH_ASSOC) ;

        
        $stmt2 = $bdd->prepare("select * from `aaa-personnel` WHERE  `idPersonnel` =:iP");
        $stmt2->execute(['iP' => $iP]);
        $personnel=$stmt2->fetch(PDO::FETCH_ASSOC) ;

        $stmt3 = $bdd->prepare("select * from `aaa-salaire-personnel` WHERE  `idSP` =:idSP");
        $stmt3->execute(['idSP' => $iS]);
        $salaire=$stmt3->fetch(PDO::FETCH_ASSOC) ;

        $montant=intval($salaire['salaireDeBase']);
        
        $tab[0]=$montant."<>".$personnel['telPersonnel']."<>".$personnel['matriculePersonnel'];

        $stmt = $bdd->prepare("select * from `aaa-compte` 
                    WHERE (`typeCompte`=:n1 OR `typeCompte`=:n2) 
                    AND `montantCompte` >=:mont");
        $stmt->execute(['n1'=>2,'n2'=>5,'mont' => $montant]);
        $comptes=$stmt->fetchAll(PDO::FETCH_ASSOC) ; 
        $options="";

        foreach ($comptes as $compte) {
            $options= $options.'<option value="'.$compte["idCompte"].'">'.$compte["nomCompte"].'</option>';
        }  
        $tab[1]=$options;
        echo json_encode( $tab);
        break;
    case "popVirerSalairePers_Avance":
        $iC=$_POST['iC'];
        $iP=$_POST['iP'];
        $iS=$_POST['iS'];
        //$user=recupererUtilisateursById($bdd,$id);
        $stmt1 = $bdd->prepare("select * from `aaa-contrat` WHERE  `idContrat` =:iC");
        $stmt1->execute(['iC' => $iC]);
        $contrat=$stmt1->fetch(PDO::FETCH_ASSOC) ;

        
        $stmt2 = $bdd->prepare("select * from `aaa-personnel` WHERE  `idPersonnel` =:iP");
        $stmt2->execute(['iP' => $iP]);
        $personnel=$stmt2->fetch(PDO::FETCH_ASSOC) ;

        $stmt3 = $bdd->prepare("select * from `aaa-salaire-personnel` WHERE  `idSP` =:idSP");
        $stmt3->execute(['idSP' => $iS]);
        $salaire=$stmt3->fetch(PDO::FETCH_ASSOC) ;
        
        $montant=intval($salaire['salaireDeBase']-$salaire['avanceSalaire1']-$salaire['avanceSalaire2']);
        
        $tab=$montant."<>".$personnel['telPersonnel']."<>".$personnel['matriculePersonnel'];

        // $stmt = $bdd->prepare("select * from `aaa-compte` 
        //             WHERE (`typeCompte`=:n1 OR `typeCompte`=:n2) 
        //             AND `montantCompte` >=:mont");
        // $stmt->execute(['n1'=>2,'n2'=>5,'mont' => $montant]);
        // $comptes=$stmt->fetchAll(PDO::FETCH_ASSOC) ; 
        // $options="";

        // foreach ($comptes as $compte) {
        //     $options= $options.'<option value="'.$compte["idCompte"].'">'.$compte["nomCompte"].'</option>';
        // }  
        // $tab[1]=$options;
        echo $tab;
        break;
    
    case "montantVirAvancePers":
        $montant=$_POST['mont'];
        $idSP=$_POST['idSP'];
        $tab="";
        
        $stmt3 = $bdd->prepare("select * from `aaa-salaire-personnel` WHERE  `idSP` =:idSP");
        $stmt3->execute(['idSP' => $idSP]);
        $salaire=$stmt3->fetch(PDO::FETCH_ASSOC) ;

        if ($salaire['avanceSalaire1']==0) {
            $stmt = $bdd->prepare("select * from `aaa-compte` 
                    WHERE (`typeCompte`=:n1 OR `typeCompte`=:n2) 
                    AND `montantCompte` >=:mont");
            $stmt->execute(['n1'=>2,'n2'=>5,'mont' => $montant]);
            $comptes=$stmt->fetchAll(PDO::FETCH_ASSOC) ; 
            $options="";

            foreach ($comptes as $compte) {
                $options= $options.'<option value="'.$compte["idCompte"].'">'.$compte["nomCompte"].'</option>';
            }  

            $tab=$options;
            ///////////////////////////////////////////////
           
            ///////////////////////////////////////////////
        }else{

            $stmt = $bdd->prepare("select * from `aaa-compte` 
                    WHERE (`typeCompte`=:n1 OR `typeCompte`=:n2) 
                    AND `montantCompte` >=:mont");
            $stmt->execute(['n1'=>2,'n2'=>5,'mont' => $salaire['retenu']]);
            $comptes=$stmt->fetchAll(PDO::FETCH_ASSOC) ; 
            $options="";

            foreach ($comptes as $compte) {
                $options= $options.'<option value="'.$compte["idCompte"].'">'.$compte["nomCompte"].'</option>';
            }  
            $tab=$options;
            $tab=$tab.'<>'.$salaire['avanceSalaire1'];
        }

        echo  $tab; 
        break;
    case 'btnVirerIng':

        $idSP=$_POST['idSP'];
        $datePaiement=$_POST['datePaiement'];
        $comptePaiement=$_POST['comptePaiement'];
        $montant=$_POST['montant'];
        $matricule=$_POST['matriculePersonnel'];
        $telephone=$_POST['telephone'];
        $payer=1;
    
        //die("ENDDDDDDDD");
        $req01 = $bdd->prepare("SELECT * FROM `aaa-salaire-personnel` sp  INNER JOIN `aaa-contrat` c ON sp.idContrat=c.idContrat 
                                WHERE sp.idSP =:idSP");                                           
        $req01->execute(array('idSP'=>$idSP))  or die(print_r($req01->errorInfo())); 
        $contrat=$req01->fetch(); 
    
       // $nouveauAvance= $contrat['avanceSalaire']+$motantAvance;
       //$nouveauAvance= $contrat['avanceSalaire'];
       $nouveauAvance=$montant;
        
    
        ///////////////////////
        
        $req02 = $bdd->prepare("select * from `aaa-personnel` where idPersonnel=:idP");
        $req02->execute(array('idP' => $contrat['idPersonnel'])) or die(print_r($req02->errorInfo()));
        $personnel=$req02->fetch();
    
        /**********************************TABLE COMPTE *****************************************/
                            
                            $req2 = $bdd->prepare("select * from `aaa-compte` where idCompte=:idC");
                            $req2->execute(array('idC' => $comptePaiement)) or die(print_r($req2->errorInfo()));
                            $compte=$req2->fetch();
                            //var_dump($compte);
                            if($compte['montantCompte']>=$montant and $montant>0){
                                /********************************DEBUT SALAIRE PERSONNEL**************************************/
                                // if ($contrat['retenu'] > 0 and $contrat['retenu']<=$motantAvance) {
                                //     $payer=1;
                                // }
                                $nnn="111111111111111";
                                var_dump($nnn);
                                //die($datePaiement);
                                $retenu=0;
                                $nouveauAvance=0;
                                $req2 = $bdd->prepare("UPDATE `aaa-salaire-personnel` SET `aPayer`=:payer,
                                                                                        `datePaiement`=:dateP,`comptePaiement`=:compteP,
                                                                                        `retenu`=:ret WHERE idSP=:idSP");
                                $req2->execute(array(
                                            'payer' => $payer,
                                            'dateP' => $datePaiement,
                                            'compteP' => $comptePaiement,
                                            'ret' => $retenu,
                                            'idSP' => $idSP )) or die(print_r($req2->errorInfo()));
                                $req2->closeCursor();                                                          
                                /******************************** FINSALAIRE PERSONNEL****************************************/
                                $operation='retrait';
                                $idCompte=$compte['idCompte'];
                                $compteDonateur='';
                                $description='Paiement salaire :'.$personnel['prenomPersonnel'].' '.$personnel['nomPersonnel'].' aux matricule'.$personnel['matriculePersonnel'];
                                $newMontant=$compte['montantCompte']-$montant;
    
                                $req3 = $bdd->prepare("INSERT INTO `aaa-comptemouvement` (montant,operation,idCompte,numeroDestinataire,compteDonateur,nomClient,dateSaisie,dateOperation,description,idUser,idSP) 
                                                     values (:montant,:operation,:idCompte,:numeroDestinataire,:compteDonateur,:nomClient,:dateSaisie,:dateOperation,:description,:idUser,:sp)");
                                $req3->execute(array(
                                        'montant'=>$montant,
                                        'operation'=>$operation,
                                        'idCompte'=>$idCompte,
                                        'numeroDestinataire'=>$telephone,
                                        'compteDonateur'=>$compteDonateur,
                                        'nomClient'=>$operation,
                                        'dateSaisie'=>$dateHeures,
                                        'dateOperation'=>$datePaiement,
                                        'description'=>$description,
                                        'idUser'=>$_SESSION['iduserBack'],
                                        'sp'=>$idSP
                                        ))  or die(print_r($req3->errorInfo()));   
                                $req3->closeCursor();
                                $nnn="22222222222222";
                                var_dump($nnn);
                                $req4 = $bdd->prepare("UPDATE `aaa-compte` SET `montantCompte`=:montCompt  WHERE idCompte=:idCompte");
                                $req4->execute(array(
                                            'montCompt' => $newMontant,
                                            'idCompte' => $compte['idCompte'] )) or die(print_r($req4->errorInfo()));
                                $req4->closeCursor();  
                                $nnn="3333333333333333";
                                var_dump($nnn);
                              }else{
                                  //var_dump('GGGGGGGGGGGG');
                              }
        /********************************TABLE COMPTE **************************************/ 
        break;
    case 'btnVirerAvance':
        $idSP=$_POST['idSP'];
        $datePaiement=$_POST['datePaiement'];
        $comptePaiement=$_POST['comptePaiement'];
        $motantAvance=$_POST['motantAvance'];
        $payer=0;
        $salaireNet=0;
        $retenu=0;
        ///////////////////////
        $req01 = $bdd->prepare("SELECT * FROM `aaa-salaire-personnel` sp  INNER JOIN `aaa-contrat` c ON sp.idContrat=c.idContrat 
                                WHERE sp.idSP =:idSP");                                           
        $req01->execute(array('idSP'=>$idSP))  or die(print_r($req01->errorInfo())); 
        $contrat=$req01->fetch(); 
        ///////////////////////
        
        // $nouveauAvance= $contrat['avanceSalaire']+$motantAvance;
        // $retenu=$contrat['salaireNet']-$nouveauAvance;
    
        $req02 = $bdd->prepare("select * from `aaa-personnel` where idPersonnel=:idP");
        $req02->execute(array('idP' => $contrat['idPersonnel'])) or die(print_r($req02->errorInfo()));
        $personnel=$req02->fetch();
        /**********************************TABLE COMPTE *****************************************/
                            
                            $req03 = $bdd->prepare("select * from `aaa-compte` where idCompte=:idC");
                            $req03->execute(array('idC' => $comptePaiement)) or die(print_r($req03->errorInfo()));
                            $compte=$req03->fetch();
                            //var_dump($compte);
                            if($compte['montantCompte']>=$motantAvance  and $motantAvance>0){
    
                                // Avance 1
                                if ($contrat['avanceSalaire1']==0) {
                                    $avance1= $motantAvance;
                                    $retenu=$contrat['salaireDeBase']-$avance1;
                                    //die("FINNNNNNN");
                                    /********************************DEBUT SALAIRE PERSONNEL**************************************/
                                    $req1 = $bdd->prepare("UPDATE `aaa-salaire-personnel` SET `avanceSalaire1`=:asa,`retenu`=:ret,`aPayer`=:payer,
                                                `datePaiement`=:dateP,`comptePaiement`=:compteP WHERE idSP=:idSP");
                                    $req1->execute(array(
                                                'asa' => $avance1,
                                                'ret' => $retenu,
                                                'payer' => $payer,
                                                'dateP' => $datePaiement,
                                                'compteP' => $comptePaiement,
                                                'idSP' => $idSP )) or die(print_r($req1->errorInfo()));
                                    $req1->closeCursor();                                                          
                                    /******************************** FINSALAIRE PERSONNEL****************************************/
                                }// Avance 2
                                elseif ($contrat['avanceSalaire1']>0) {
                                    $avance2= $motantAvance;
                                    $retenu=$contrat['retenu']-$avance2;
                                    if ($retenu==0) {
                                        $payer=1;
                                    }else {
                                        $payer=0;
                                    }
                                    

                                    /********************************DEBUT SALAIRE PERSONNEL**************************************/
                                        $req1 = $bdd->prepare("UPDATE `aaa-salaire-personnel` SET `avanceSalaire2`=:asa,`retenu`=:ret,`aPayer`=:payer,
                                                                    `datePaiement`=:dateP,`comptePaiement`=:compteP WHERE idSP=:idSP");
                                        $req1->execute(array(
                                                'asa' => $avance2,
                                                'ret' => $retenu,
                                                'payer' => $payer,
                                                'dateP' => $datePaiement,
                                                'compteP' => $comptePaiement,
                                                'idSP' => $idSP )) or die(print_r($req1->errorInfo()));
                                        $req1->closeCursor();                                                          
                                    /******************************** FINSALAIRE PERSONNEL****************************************/
                                }
                                
                                $operation='retrait';
                                $idCompte=$compte['idCompte'];
                                $compteDonateur='';
                                $description='Paiement salaire :'.$personnel['prenomPersonnel'].' '.$personnel['nomPersonnel'].' aux matricule '.$personnel['matriculePersonnel'];
                                $newMontant=$compte['montantCompte']-$motantAvance;
    
                                $req3 = $bdd->prepare("INSERT INTO `aaa-comptemouvement` (montant,operation,idCompte,numeroDestinataire,compteDonateur,nomClient,dateSaisie,dateOperation,description,idUser,idSP) 
                                                     values (:montant,:operation,:idCompte,:numeroDestinataire,:compteDonateur,:nomClient,:dateSaisie,:dateOperation,:description,:idUser,:sp)");
                                $req3->execute(array(
                                        'montant'=>$motantAvance,
                                        'operation'=>$operation,
                                        'idCompte'=>$idCompte,
                                        'numeroDestinataire'=>$personnel['telPersonnel'],
                                        'compteDonateur'=>$compteDonateur,
                                        'nomClient'=>$operation,
                                        'dateSaisie'=>$dateHeures,
                                        'dateOperation'=>$datePaiement,
                                        'description'=>$description,
                                        'idUser'=>$_SESSION['iduserBack'],
                                        'sp'=>$idSP
                                        ))  or die(print_r($req3->errorInfo()));   
                                $req3->closeCursor();
    
                                $req4 = $bdd->prepare("UPDATE `aaa-compte` SET `montantCompte`=:montCompt  WHERE idCompte=:idCompte");
                                $req4->execute(array(
                                            'montCompt' => $newMontant,
                                            'idCompte' => $compte['idCompte'] )) or die(print_r($req4->errorInfo()));
                                $req4->closeCursor();  
    
                              }else{
                                  var_dump('GGGGGGGGGGGG');
                              }
        /********************************TABLE COMPTE **************************************/  
        break;
    default:
        # code...
        break;
}
?>