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

if ($operation=="chercheUser") {
    $id = @$_POST["idUtilisateur"];
    //var_dump($sql13);
    $req13 = $bdd->prepare("SELECT * from  `aaa-utilisateur` where idutilisateur=:i"); 
    $req13->execute(array('i' =>$id))  or die(print_r($req13->errorInfo())); 
    $user=$req13->fetch();
      
    $result=$user['profil'];
    exit($result);
     
  }else if ($operation=="cherchePersonnel") {
    $id = @$_POST["idUtilisateur"];
    //var_dump($sql13);
    $req13 = $bdd->prepare("SELECT * from  `aaa-utilisateur` where idutilisateur=:i"); 
    $req13->execute(array('i' =>$id))  or die(print_r($req13->errorInfo())); 
    $user=$req13->fetch();
      
    $result=$user['profil'];
    exit($result);
     
  }
  else if ($operation=="chercheContrat") {
    $idContrat = @$_POST["idContrat"];
    $req = $bdd->prepare("SELECT * FROM `aaa-contrat` WHERE idContrat=:ic"); 
    $req->execute(array('ic' =>$idContrat))  or die(print_r($req->errorInfo())); 
    $contrat=$req->fetch();


    if ($contrat['idUtilisateur']!=null) {
        $sql1="SELECT * from  `aaa-utilisateur` where idutilisateur=".$contrat['idUtilisateur']."";
        $res1 = mysql_query($sql1) or die ("persoonel requête 3".mysql_error());
        $user = mysql_fetch_assoc($res1);

        $result=$contrat['idUtilisateur'].'<>'.$user['prenom'].'<>'.$user['nom'].'<>'.$user['profil'].'<>'.$contrat['montantSalaire'].'<>'.$contrat['dateDebut'].'<>'.$contrat['dateFin'].'<>'.$contrat['image'];
        exit($result);
    } else if ($contrat['idPersonnel'] !=null){

        $req1 = $bdd->prepare("SELECT * FROM `aaa-personnel` WHERE idPersonnel=:idP");                                                
        $req1->execute(array('idP' =>$contrat['idPersonnel'])) or die(print_r($req1->errorInfo()));  
        $personnel=$req1->fetch();

        $req2 = $bdd->prepare("SELECT * FROM `aaa-profil`  WHERE idProfil=:in"); 
        $req2->execute(array('in' =>$personnel['profilPersonnel']))  or die(print_r($req2->errorInfo())); 
        $profil=$req2->fetch();

        $result=$contrat['idPersonnel'].'<>'.$personnel['prenomPersonnel'].'<>'.$personnel['nomPersonnel'].'<>'.$profil['nomProfil'].'<>'.$contrat['montantSalaire'].'<>'.$contrat['dateDebut'].'<>'.$contrat['dateFin'].'<>'.$contrat['image'];
        exit($result);
    }
    
    
     
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