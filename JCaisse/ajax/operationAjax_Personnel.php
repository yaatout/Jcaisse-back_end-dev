
<?php

session_start();

date_default_timezone_set('Africa/Dakar');

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
$query=@htmlspecialchars($_POST["query"]);
$action=@htmlspecialchars($_POST["action"]);

$nom=@htmlspecialchars($_POST["nom"]);
$prenom=@htmlspecialchars($_POST["prenom"]);
$adresse=@htmlspecialchars($_POST["adresse"]);
$telephone=@htmlspecialchars($_POST["telephone"]);
$codeAdherant=@htmlspecialchars($_POST["codeAdherant"]);
$personnel=@htmlspecialchars($_POST["personnel"]);

$idutilisateur=@htmlspecialchars($_POST["idutilisateur"]);
$activer=@htmlspecialchars($_POST["activer"]);

if($operation == 1){
   
    $sql1="SELECT * FROM `aaa-utilisateur` where idutilisateur=".$idutilisateur." ";
    $res1=mysql_query($sql1) or die ("insertion client impossible =>".mysql_error() );
    $user = mysql_fetch_array($res1) ;
        
    if($res1){
        $sql2="SELECT * FROM `aaa-acces` where idutilisateur='".$user['idutilisateur']."' AND idBoutique='".$_SESSION['idBoutique']."' ";
        $res2=mysql_query($sql2) or die ("insertion client impossible =>".mysql_error() );
        $access = mysql_fetch_array($res2) ;

        if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
            if($user['idEntrepot']!=0 && $user['idEntrepot']!=null){
                $sql3="SELECT * FROM `".$nomtableEntrepot."` WHERE idEntrepot=".$user['idEntrepot']." ";
                $res3=mysql_query($sql3);
                $depot = mysql_fetch_array($res3);

                $result='1<>'.$user['idutilisateur'].'<>'.$user['nom'].'<>'.$user['prenom'].'<>'.$user['telPortable'].'<>'.$user['email'].'<>'.$access['proprietaire'].'<>'.$access['gerant'].'<>'.$access['gestionnaire'].'<>'.$access['caissier'].'<>'.$access['vendeur'].'<>'.$access['vitrine'].'<>'.$access['tableauBord'].'<>'.$access['ecommerce'].'<>'.$access['commande'].'<>'.$access['client'].'<>'.$depot['idEntrepot'].'<>'.$depot['nomEntrepot'];
            }
            else{
                $result='1<>'.$user['idutilisateur'].'<>'.$user['nom'].'<>'.$user['prenom'].'<>'.$user['telPortable'].'<>'.$user['email'].'<>'.$access['proprietaire'].'<>'.$access['gerant'].'<>'.$access['gestionnaire'].'<>'.$access['caissier'].'<>'.$access['vendeur'].'<>'.$access['vitrine'].'<>'.$access['tableauBord'].'<>'.$access['ecommerce'].'<>'.$access['commande'].'<>'.$access['client'].'<> 0 <> Tous ';
            }
        }
        else{
            $result='1<>'.$user['idutilisateur'].'<>'.$user['nom'].'<>'.$user['prenom'].'<>'.$user['telPortable'].'<>'.$user['email'].'<>'.$access['proprietaire'].'<>'.$access['gerant'].'<>'.$access['gestionnaire'].'<>'.$access['caissier'].'<>'.$access['vendeur'].'<>'.$access['vitrine'].'<>'.$access['tableauBord'].'<>'.$access['ecommerce'].'<>'.$access['commande'].'<>'.$access['client'].'<> <> ';
        }

        
    }
    else{
        $result="0"; 
    }
    exit($result);
}