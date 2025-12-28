<?php
if (isset($_POST['btnReferencetransfertsOrange'])) {

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
    $sql2="SELECT * FROM `aaa-payement-reference` where refTransfertValidation='".$refTransf."'";
    //var_dump('1');
    $res2 = mysql_query($sql2) or die ("acces requête 3".mysql_error());
    if(!@mysql_num_rows($res2)){
        $sql3="insert into `aaa-payement-reference` (dateRefTransfertValidation,montant,refTransfertValidation,telRefTransfertValidation,avecFrais,frais) values('".$dateTransfert."',".$montantTransfert.",'".$refTransf."','".$numTel."','".$avecFrais."','".$frais."')";
        //var_dump('2');
        $res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas1 ".mysql_error());

        /**********************************TABLE COMPTE *****************************************/
            $sql8="SELECT * FROM `aaa-payement-reference` where refTransfertValidation='".$refTransf."' and dateRefTransfertValidation='".$dateTransfert."'";
            $res8 = mysql_query($sql8) or die ("acces requête 3".mysql_error());
            $payementReference =mysql_fetch_assoc($res8);

            $sqlv="select * from `aaa-compte` where nomCompte=$typeCompteMobile";
            $resv=mysql_query($sqlv);
            $compte =mysql_fetch_assoc($resv);
            //var_dump($compte);
            if($compte){
                    //var_dump('3');
                $operation='depot';
                $idCompte=$compte['idCompte'];
                $description=$refTransf;
                $newMontant=$compte['montantCompte']+$montantTransfert;

                $sql6="insert into `aaa-comptemouvement` (montant,operation,idCompte,dateSaisie,dateOperation,description,idUser,idPR) values('".$montantTransfert."','".$operation."','".$idCompte."','".$dateHeures."','".$dateTransfert."','".$description."','".$_SESSION['iduser']."','".$payementReference['id']."')";
                //var_dump($sql6);
                $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error() );

                $sql7="UPDATE `aaa-compte` set  montantCompte='".$newMontant."' where  idCompte=".$compte['idCompte']."";
                //var_dump($sql7);
                $res7=@mysql_query($sql7) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
            }
        /********************************TABLE COMPTE **************************************/

        $sql4="SELECT * FROM `aaa-payement-salaire` where refTransfert='".$refTransf."'";
        $res4 = mysql_query($sql4) or die ("acces requête 4".mysql_error());
        if(@mysql_num_rows($res4)){
            //var_dump('4');
            $paiement = mysql_fetch_array($res4);
            if ($paiement['refTransfert']==$refTransf and $paiement['montantFixePayement']<=$montantTransfert){
                //var_dump('5');
                $sql5="UPDATE `aaa-payement-salaire` set  aPayementBoutique='".$activer."' where refTransfert='".$refTransf."'";
                $res5=@mysql_query($sql5) or die ("mise à jour acces pour activer ou pas2 ".mysql_error());

                $sql6="UPDATE `aaa-payement-reference` set  idPS=".$paiement['idPS']." where refTransfertValidation='".$refTransf."'";
                $res6=@mysql_query($sql6) or die ("mise à jour acces pour activer ou pas2 ".mysql_error());

            }else{
                //var_dump('6');
                 $sql5="UPDATE `aaa-payement-reference` set  `montantNonConforme`='".$montantNonConforme."',`idPS`=".$paiement['idPS']." where refTransfertValidation='".$refTransf."'";
                //var_dump($sql5);
                $res5=@mysql_query($sql5) or die ("mise à jour acces pour activer ou pas5ff ".mysql_error());
            }
        }

    }
}
}