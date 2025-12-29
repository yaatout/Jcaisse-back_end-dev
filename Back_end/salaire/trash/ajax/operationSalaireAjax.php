<?php
session_start();
if(!$_SESSION['iduserBack']){
	header('Location:../../index.php');
}
require('../../connectionPDO.php');
require('../../declarationVariables.php');
require_once '../functions/salaire_functions.php';
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
                    $boutique=recupererBoutique($bdd,$payement["idBoutique"]);
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
        $user=recupererUtilisateursById($bdd,$id);
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

        $montant=intval($salaire['salaireNet']-$salaire['avanceSalaire']);
        
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
        
         $montant=intval($salaire['salaireNet']-$salaire['avanceSalaire']);
        
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
        echo  $tab;
        break;
    default:
        # code...
        break;
}
?>