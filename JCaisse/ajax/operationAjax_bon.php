
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

$idMutuelle=@htmlspecialchars($_POST["idMutuelle"]);
$nom=@htmlspecialchars($_POST["nom"]);
$prenom=@htmlspecialchars($_POST["prenom"]);
$marqueur=@htmlspecialchars($_POST["marqueur"]);
$adresse=@htmlspecialchars($_POST["adresse"]);
$telephone=@htmlspecialchars($_POST["telephone"]);
$plafond=@htmlspecialchars($_POST["plafond"]);
$codeAdherant=@htmlspecialchars($_POST["codeAdherant"]);
$personnel=@htmlspecialchars($_POST["personnel"]);

$idClient=@htmlspecialchars($_POST["idClient"]);
$activer=@htmlspecialchars($_POST["activer"]);
$archiver=@htmlspecialchars($_POST["archiver"]);
$avoir=@htmlspecialchars($_POST["avoir"]);
$matp=@htmlspecialchars($_POST["matp"]);
$numCarnet=@htmlspecialchars($_POST["numCarnet"]);
$numCarnetOld=@htmlspecialchars($_POST["numCarnetOld"]);
$montantAvoir=@htmlspecialchars($_POST["montantAvoir"]);

if (!$avoir) {
    # code...
    $avoir=0;
}

if (!$montantAvoir) {
    # code...
    $montantAvoir=0;
}

if (!$matp) {
    # code...
    $matp=0;
}

if (!$numCarnet) {
    # code...
    $numCarnet=0;
}


if($operation == 1){
    $solde=0;
    $activer=0;

    if ($_SESSION['cbm']=="1") {
        $sql1="insert into `".$nomtableClient."` (nom,prenom,marqueur,adresse,telephone,plafond,solde,activer,personnel,avoir,montantAvoir,matriculePension,numCarnet,iduser) values('".$nom."','".$prenom."','".$marqueur."','".mysql_real_escape_string($adresse)."','".$telephone."','".$plafond."','".$solde."','".$activer."',".$personnel.",".$avoir.",".$montantAvoir.",'".$matp."','".$numCarnet."','".$_SESSION['iduser']."')";

    } else {
        $sql1="insert into `".$nomtableClient."` (nom,prenom,adresse,telephone,plafond,solde,activer,personnel,avoir,montantAvoir,matriculePension,numCarnet,iduser) values('".$nom."','".$prenom."','".mysql_real_escape_string($adresse)."','".$telephone."','".$plafond."','".$solde."','".$activer."',".$personnel.",".$avoir.",".$montantAvoir.",'".$matp."','".$numCarnet."','".$_SESSION['iduser']."')";

    }
    
    //var_dump($sql1);
    $res1=mysql_query($sql1) or die ("insertion client impossible =>".mysql_error());

    $sql1="SELECT * FROM `".$nomtableClient."` ORDER BY idClient desc limit 1 ";
    $res1=mysql_query($sql1) or die ("insertion client impossible =>".mysql_error());
    $client = mysql_fetch_array($res1) ;

    if ($avoir==0) {
        $sql2="insert into `".$nomtableBon."` (idClient,iduser) values(".$client['idClient'].",'".$_SESSION['iduser']."')";
        //var_dump($sql1);
        $res2=mysql_query($sql2) or die ("insertion client impossible =>".mysql_error());
    }
    if ($avoir==1) {
        # code...
        $sql0="insert into `".$nomtableVersement."` (idClient,paiement,montantAvoir,dateVersement,heureVersement,iduser) values(".$client['idClient'].",'Initialisation avoir', ".$montantAvoir.",'".$dateString2."','".$heureString."',".$_SESSION['iduser'].")";
        //echo $sql2;
        $res0=mysql_query($sql0) or die ("insertion Versement impossible =>".mysql_error());
    }
        
    if($res1){
        $result='1<>'.$nom.'<>'.$prenom.'<>'.$adresse.'<>'.$telephone.'<>'.$plafond;
    }
    else{
        $result="0"; 
    }
    exit($result);
}else if($operation == 2){
    
    $sql1="UPDATE `".$nomtableClient."` set  activer='".$activer."' where idClient=".$idClient;
    $res1=mysql_query($sql1) or die ("insertion client impossible =>".mysql_error() );
        
    if($res1){
        $result="1";
    }
    else{
        $result="0"; 
    }
    exit($result);
}else if($operation == 3){
   
    $sql1="SELECT * FROM `".$nomtableClient."` where idClient=".$idClient." ";
    $res1=mysql_query($sql1) or die ("insertion client impossible =>".mysql_error() );
    $client = mysql_fetch_array($res1) ;
        
    if($res1){
        $result='1<>'.$client['idClient'].'<>'.$client['nom'].'<>'.$client['prenom'].'<>'.$client['adresse'].'<>'.$client['telephone'].'<>'.$client['avoir'].'<>'.$client['montantAvoir'].'<>'.$client['matriculePension'].'<>'.$client['numCarnet'].'<>'.$client['plafond'];
    }
    else{
        $result="0"; 
    }
    exit($result);
}else if($operation == 4) {

    $sql0="UPDATE `".$nomtableClient."` set `nom`='".$nom."',prenom='".$prenom."',adresse='".mysql_real_escape_string($adresse)."',telephone='".$telephone."',plafond='".$plafond."',personnel='".$personnel."',avoir='".$avoir."',montantAvoir=".$montantAvoir.",matriculePension='".$matp."',numCarnet='".$numCarnet."' where idClient=".$idClient;
    $res0=@mysql_query($sql0) or die ("mise à jour client  impossible".mysql_error());

    if($res0) {
        $sql1="SELECT * FROM `".$nomtableClient."` where idClient=".$idClient." ";
        $res1=mysql_query($sql1) or die ("insertion client impossible =>".mysql_error() );
        $client = mysql_fetch_array($res1) ;

        $sql2="SELECT montant FROM `".$nomtableBon."` where idClient='".$idClient."' ";
        $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
        $TotalB = mysql_fetch_array($res2) ; 
        
        $T_solde=$TotalB[0] - $client['solde'];

        if($res1){
            $result='1<>'.$client['idClient'].'<>'.$client['nom'].'<>'.$client['prenom'].'<>'.$client['adresse'].'<>'.$client['telephone'].'<>'.number_format($T_solde, 2, ',', ' ').' FCFA';
        }
        else{
            $result="0"; 
        }
    }
    else{
        $result="0";
    }
   
    exit($result);
}else if($operation == 5){
    $sql1="SELECT * FROM `".$nomtableClient."` where idClient=".$idClient." ";
    $res1=mysql_query($sql1) or die ("insertion client impossible =>".mysql_error() );
    $client = mysql_fetch_array($res1) ;

    $sql2="SELECT montant FROM `".$nomtableBon."` where idClient='".$idClient."' ";
    $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
    $TotalB = mysql_fetch_array($res2) ; 
    
    $T_solde=$TotalB[0] - $client['solde'];

    if($res1){
        $sql0="DELETE FROM `".$nomtableClient."`  where idClient=".$idClient;
        $res0=@mysql_query($sql0) or die ("mise à jour client  impossible".mysql_error());
        if($res0){
            $result='1<>'.$client['idClient'].'<>'.$client['nom'].'<>'.$client['prenom'].'<>'.$client['adresse'].'<>'.$client['telephone'].'<>'.number_format($T_solde, 2, ',', ' ').' FCFA';
        }
        else{
            $result="0";
        }
    }
    else{
        $result="0"; 
    }
    exit($result);
}else if($operation == 6){
    
    $sql1="UPDATE `".$nomtableClient."` set  archiver='".$archiver."' where idClient=".$idClient;
    $res1=mysql_query($sql1) or die ("insertion client impossible =>".mysql_error() );
        
    if($res1){
        $result="1";
    }
    else{
        $result="0"; 
    }
    exit($result);
}else if($operation == 7){
    $result=0;

    $sql1="SELECT * FROM `".$nomtableClient."` where numCarnet='".$query."'";
    $res1=mysql_query($sql1) or die ("insertion client impossible =>".mysql_error());
    $client = mysql_fetch_array($res1) ; 

    // while ($client=mysql_fetch_array($res1)) {
        # code...
        // $nom = explode(" / ", $client['nom']);
        
        if ($action=="modifier" && $numCarnetOld==$query) {
            # code...
            // $result=1;
        } else if ($client['numCarnet']==$query && $query!='') {
            # code...
            // if ($nom[0]==$query) {
                # code...
                $result=1;
                // break;
            // } 
        }
        
    // }
    
    echo $result;
}