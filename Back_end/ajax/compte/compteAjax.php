<?php

/*
Résumé :
Commentaire :
Version : 2.0
see also :
Auteur : Ibrahima DIOP; ,EL hadji mamadou korka
Date de création : 20/03/2016
Date derni�re modification : 20/04/2016; 15-04-2018
*/
session_start();
if(!$_SESSION['iduserBack']){
	header('Location:../../index.php');
}




require('../../connection.php');
require('../../connectionPDO.php');



require('../../declarationVariables.php');

$operation=@htmlspecialchars($_POST["operation"]);
if ($operation=="resumeTableauDeBord") {
  $idCompter=@htmlspecialchars($_POST["l"]);
  
$req1 = $bdd->prepare("SELECT * FROM `aaa-compte`  WHERE idCompte=:in"); 
$req1->execute(array('in' =>$idCompter))  or die(print_r($req1->errorInfo())); 
$compte=$req1->fetch(); 

  echo '<h2>Compte '.strtoupper($compte['nomCompte']).' : '.number_format($compte['montantCompte'], 0, ',', ' ')." FCFA </h2>";				
      echo      '<div class="panel-group">
                            <div class="panel" style="background:#cecbcb;">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse1">  Liste operation  </a>
                                    </h4>
                                </div>
                                <div id="collapse1" class="panel-collapse collapse">
                                    <div class="panel-heading" style="margin-left:2%;"> ';
                                        
                                        
                                            $sqlDV = $bdd->prepare("SELECT sum(montant) as total FROM `aaa-comptemouvement` WHERE idCompte=:a and (
                                                                                                                            operation=:v 
                                                                                                                            OR operation=:d or operation=:p) 
                                                                                                                            and annuler!=:n"); 
                                            $sqlDV->execute(array('a' =>$_SESSION['compteId'], 
                                                                'v' =>'versement' ,
                                                                'd' =>'depot' ,
                                                                'p' =>'pret' ,
                                                                'n' =>'1' 
                                                                ))  or die(print_r($sql01->errorInfo()));                                                         
                                            $S_facture =$sqlDV->fetch();
                                            $montantDV=$S_facture['total'];

                                            //var_dump($montantDV);
                                            $sqlR="SELECT SUM(montant)	FROM `aaa-comptemouvement`	where idCompte=".$_SESSION['compteId']." && 
                                            (operation='retrait' or  operation='remboursement' )&&  annuler!='1'  ";
                                            $resR=mysql_query($sqlR) or die ("select stock impossible =>".mysql_error());
                                            $S_factureR = mysql_fetch_array($resR);
                                            $montantR = $S_factureR[0];

                                            $sqlR = $bdd->prepare("SELECT sum(montant) as total FROM `aaa-comptemouvement` WHERE idCompte=:a and 
                                                ( operation=:r  OR operation=:rem ) and annuler!=:n"); 
                                            $sqlR->execute(array(
                                                'a' =>$_SESSION['compteId'], 
                                                'r' =>'retrait' ,
                                                'rem' =>'remboursement' ,
                                                'n' =>'1' 
                                                ))  or die(print_r($sqlR->errorInfo()));                                                         
                                            $S_factureR =$sqlR->fetch();
                                            $montantR=$S_factureR['total'];
                                            //var_dump( $montantR);
                                            //////
                                            
                                            
                                            $sqlVT = $bdd->prepare("SELECT sum(montant) as total FROM `aaa-comptemouvement` WHERE idCompte=:a and 
                                                ( operation=:v  OR operation=:t ) and annuler!=:n"); 
                                            $sqlVT->execute(array(
                                                'a' =>$_SESSION['compteId'], 
                                                'v' =>'virement' ,
                                                't' =>'transfert' ,
                                                'n' =>'1' 
                                                ))  or die(print_r($sqlVT->errorInfo()));                                                         
                                            $S_factureVT =$sqlVT->fetch();
                                            $montantVT=$S_factureVT['total'];

                                            
                                                //if($compte['typeCompte']=='compte bancaire'){
                                                if($compte['typeCompte']=='1'){
                                                    echo "<h6>Montant depot : ".number_format($montantDV, 0, ',', ' ')." FCFA</h6>
                                                    <h6>Montant retrait :  ".number_format($montantR, 0, ',', ' ')." FCFA</h6>
                                                    <h6>Montant virement : ".number_format($montantVT, 0, ',', ' ')." FCFA</h6>";
                                                //}elseif($compte['typeCompte']=='compte mobile'){
                                                }elseif($compte['typeCompte']=='2'){
                                                    echo "<h6>Montant depot : ".number_format($montantDV, 0, ',', ' ')." FCFA</h6>
                                                    <h6>Montant retrait : ".number_format($montantR, 0, ',', ' ')." FCFA</h6>
                                                    <h6>Montant transfert : ".number_format($montantVT, 0, ',', ' ')." FCFA</h6>";
                                                }
                                                //elseif($compte['typeCompte']=='compte cheques' || $compte['typeCompte']=='caisse'){
                                                elseif($compte['typeCompte']==3 || $compte['typeCompte']==5){
                                                    echo "<h6>Montant depot : ".number_format($montantDV, 0, ',', ' ')." FCFA</h6>
                                                    <h6>Montant retrait : ".number_format($montantR, 0, ',', ' ')." FCFA</h6>";
                                                //}elseif($compte['typeCompte']=='compte pret'){
                                                }elseif($compte['typeCompte']==4){
                                                    echo "<h6>Montant pret : ".number_format($montantDV, 0, ',', ' ')." FCFA</h6>
                                                    <h6>Montant remboursement : ".number_format($montantR, 0, ',', ' ')." FCFA</h6>";
                                                } 
                                                                                    
                                        echo '

                                    </div>
                                </div>
                            </div>
            </div>  ';
                                                                                  
                       
}
if ($operation=="chercheMvn") {
    $id = @$_POST["idMouv"];
    //var_dump($sql13);
    $req13 = $bdd->prepare("SELECT * from  `aaa-comptemouvement` where idMouvement=:i"); 
    $req13->execute(array('i' =>$id))  or die(print_r($req13->errorInfo())); 
    $cmptMvn=$req13->fetch();
      
    $result=$cmptMvn['idMouvement'].'<>'.$cmptMvn['pJointe'];
    exit($result);
     
  }elseif ($operation=="telechargerContrat") {
    $nomImage=$_POST['nomImage'];
    $fichier="../../PiecesJointes/".$nomImage;
    /*header("Content-Type: application/octet-stream");
    header("Content-disposition: attachment; filename=$nomImage");
    header("Content-Type: application/force-download");*/
    //readfile($fichier);

    header('Content-Description: File Transfer');
    header("Content-type:application/pdf");
    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: 0");
    header('Content-Disposition: attachment; filename="'.basename($fichier).'"');
    header('Content-Length: ' . filesize($fichier));
    header('Pragma: public');
    //Clear system output buffer
    flush();

    //Read the size of the file
    readfile($fichier);

    //Terminate from the script
    die();
    /*$imagpdf = file_put_contents($image, file_get_contents($fichier));
    echo $imagepdf;*/
  } 

?>