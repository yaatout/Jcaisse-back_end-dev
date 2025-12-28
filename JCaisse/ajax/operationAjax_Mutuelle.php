
<?php

session_start();
if(!$_SESSION['iduser']){
  header('Location:../index.php');
  }

require('../connection.php');

require('../declarationVariables.php');

/**Debut informations sur la date d'Aujourdhui **/
    $date = new DateTime();
    $timezone = new DateTimeZone('Africa/Dakar');
    $date->setTimezone($timezone);
    $annee =$date->format('Y');
    $mois =$date->format('m');
    $jour =$date->format('d');
    $heureString=$date->format('H:i:s');
    $dateString=$annee.'-'.$mois.'-'.$jour;
    $dateString2=$jour.'-'.$mois.'-'.$annee;
/**Fin informations sur la date d'Aujourdhui **/
    

$operation=@htmlspecialchars($_POST["operation"]);

$idMutuelle=@htmlspecialchars($_POST["idMutuelle"]);
$nom=@htmlspecialchars($_POST["nomMutuelle"]);
$taux=@htmlspecialchars($_POST["tauxMutuelle"]);
$taux1=@htmlspecialchars($_POST["taux1"]);
$taux2=@htmlspecialchars($_POST["taux2"]);
$taux3=@htmlspecialchars($_POST["taux3"]);
$taux4=@htmlspecialchars($_POST["taux4"]);
$adresse=@htmlspecialchars($_POST["adresseMutuelle"]);
$telephone=@htmlspecialchars($_POST["telephoneMutuelle"]);

if($operation == 1){
    $result="0";
        $sql1="insert into `".$nomtableMutuelle."` (nomMutuelle,tauxMutuelle,taux1,taux2,taux3,taux4,adresseMutuelle,telephoneMutuelle) values('".$nom."','".$taux."','".$taux1."','".$taux2."','".$taux3."','".$taux4."','".mysql_real_escape_string($adresse)."','".$telephone."')";
        $res1=mysql_query($sql1) or die ("insertion client impossible =>".mysql_error() );
        if($res1){
            $result='1<>'.$nom.'<>'.$taux.'<>'.$adresse.'<>'.$telephone.'<>'.$taux1.'<>'.$taux2.'<>'.$taux3.'<>'.$taux4;
        }
    exit($result);
}
if($operation == 2){
    $sql="SELECT * FROM `". $nomtableMutuelle."` where idMutuelle='".$idMutuelle."'";
    $res=mysql_query($sql);
    if($res){
        $mutuelle=mysql_fetch_array($res);
        $result='1<>'.$mutuelle['idMutuelle'].'<>'.$mutuelle['nomMutuelle'].'<>'.$mutuelle['tauxMutuelle'].'<>'.$mutuelle['adresseMutuelle'].'<>'.$mutuelle['telephoneMutuelle'].'<>'.$mutuelle['taux1'].'<>'.$mutuelle['taux2'].'<>'.$mutuelle['taux3'].'<>'.$mutuelle['taux4'];
       
    }
    else{
        $result="0"; 
    }
    exit($result);
}
if($operation == 3){
    $result="0"; 
    $sql="SELECT * FROM `". $nomtableMutuelle."` where idMutuelle='".$idMutuelle."'";
    $res=mysql_query($sql);
    if($res){
            $sql1="UPDATE `".$nomtableMutuelle."` set nomMutuelle='".$nom."',tauxMutuelle='".$taux."',taux1='".$taux1."',taux2='".$taux2."',taux3='".$taux3."',taux4='".$taux4."',adresseMutuelle='".$adresse."',telephoneMutuelle='".$telephone."' where idMutuelle='".$idMutuelle."'";
            $res1=@mysql_query($sql1)or die ("modification reference dans stock ".mysql_error());
            if($res1){
    
                $sql12="SELECT SUM(apayerMutuelle) FROM `".$nomtableMutuellePagnet."` where idMutuelle=".$idMutuelle." ";
                $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
                $TotalB = mysql_fetch_array($res12) ; 
    
                $sql13="SELECT SUM(montant) FROM `".$nomtableVersement."` where idMutuelle='".$idMutuelle."' ";
                $res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());
                $TotalV = mysql_fetch_array($res13) ;
                
                $T_solde=$TotalB[0] - $TotalV[0];
    
                $result='1<>'.$idMutuelle.'<>'.$nom.'<>'.$taux.'<>'.$adresse.'<>'.$telephone.'<>'.$T_solde.'<>'.$taux1.'<>'.$taux2.'<>'.$taux3.'<>'.$taux4;
            }
    }
    exit($result);
}
if($operation == 4){
    $sql="SELECT * FROM `". $nomtableMutuelle."` where idMutuelle='".$idMutuelle."'";
    $res=mysql_query($sql);
    if($res){
        $sql1="DELETE FROM `".$nomtableMutuelle."` WHERE idMutuelle='".$idMutuelle."'";
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

