<?php
/** */    
$sql1="SELECT * FROM `aaa-boutique` WHERE nomBoutique=:nomBoutique";
$req1 = $bdd->prepare($sql1);
$req1->execute(['nomBoutique'=>$_SESSION['nomB']]);            
$res1 = $req1->fetch();
//Si une boutique avec le meme nom n'existe pas
if (! $res1) {
    $sql30="SELECT * FROM `aaa-utilisateur` WHERE matricule=:accompagnateur";            
    $req30 = $bdd->prepare($sql30);
    $req30->execute(['accompagnateur'=>$boutique['Accompagnateur']]);
    //Si l'accompagnateur exist            
    if($req30->rowCount() > 0   ){  
        /****************************************************/
        /***********************POUR USER********************/
        //$motdepasse2=sha1($motdepasse);
		//echo $motdepasse; 
		
        $profil              ='Admin';
        $motdepasse=sha1($client['nom']);
        
		if($client['telFixe']!=""){
            $sql1 = "INSERT INTO `aaa-utilisateur` 
                    (nom, prenom, adresse, email, telPortable, telFixe, motdepasse, dateinscription, profil) 
                    VALUES (:nom, :prenom, :adresse, :email, :telPortable, :telFixe, :motdepasse, :dateinscription, :profil)";
            $req1 = $bdd->prepare($sql1);
            $req1->execute([
                'nom' => $client['nom'],
                'prenom' => $client['prenom'],
                'adresse' => $client['adresse'],
                'email' => $client['email'],
                'telPortable' => $client['telPortable'],
                'telFixe' => $client['telFixe'],
                'motdepasse' => $motdepasse,
                'dateinscription' => $dateString,
                'profil' => $profil
            ]);
        }else{
            $sql1 = "INSERT INTO `aaa-utilisateur` 
                        (nom, prenom, adresse, email, telPortable, motdepasse, dateinscription, profil) 
                        VALUES (:nom, :prenom, :adresse, :email, :telPortable, :motdepasse, :dateinscription, :profil)";
            $req1 = $bdd->prepare($sql1);
            $req1->execute([
                    'nom' => $client['nom'],
                    'prenom' => $client['prenom'],
                    'adresse' => $client['adresse'],
                    'email' => $client['email'],
                    'telPortable' => $client['telPortable'],
                    'motdepasse' => $motdepasse,
                    'dateinscription' => $dateString,
                    'profil' => $profil
                ]);
        }
		 // Récupération du dernier ID inséré
         $idUtilisateur = $bdd->lastInsertId();

         // Insertion dans la table aaa-acces
         $sql3 = "INSERT INTO `aaa-acces` 
                 (idutilisateur, proprietaire, gerant, caissier, activer, profil) 
                 VALUES (:idutilisateur, 1, 1, 1, 1, 'Admin')";
         $req3 = $bdd->prepare($sql3);
         $req3->execute(['idutilisateur' => $idUtilisateur]);
         

        /**************************************** */  
        /**POUR BOUTIQUE */
        $sql1="INSERT INTO `aaa-boutique` 
            (nomBoutique,labelB,adresseBoutique,telephone,Pays,type,categorie,datecreation,RegistreCom,Ninea,Accompagnateur) VALUES
            (:nomBoutique,:labelB,:adresseBoutique,:telephone,:Pays,:type,:categorie,:datecreation,:RegistreCom,:Ninea,:Accompagnateur)";
        $req1 = $bdd->prepare($sql1);
        $req1->execute([
            'nomBoutique'=>$_SESSION['nomB'],
            'labelB'=>$_SESSION['nomB'],
            'adresseBoutique'=>$boutique['adresseBoutique'],
            'telephone'=>$boutique['telephone'] ,
            'Pays'=>$boutique['Pays'] ,
            'type'=>$_SESSION['type'],
            'categorie'=>$_SESSION['categorie'],
            'datecreation'=>$boutique['datecreation'],
            'RegistreCom'=>$boutique['RegistreCom'],
            'Ninea'=>$boutique['Ninea'],
            'Accompagnateur'=>$boutique['Accompagnateur']
        ]) or die(print_r($req1->errorInfo()));

        $idBoutique =$bdd->lastInsertId();
        $_SESSION['idBoutique']=$idBoutique;

        $sql8="SELECT * FROM `aaa-boutique` WHERE nomBoutique=:nomBoutique";            
        $req8 = $bdd->prepare($sql8);
        $req8->execute(['nomBoutique'=>$_SESSION['nomB']]) or die(print_r($req8->errorInfo()));            
        if($req8->rowCount()){            
            $tab8 = $req8->fetch();            
            //$_SESSION['idBoutique']=$tab8['idBoutique'];            
            $sqlC="SELECT * FROM `aaa-acces` WHERE idutilisateur=:iduser AND idBoutique=0";            
            $reqC = $bdd->prepare($sqlC);
            $reqC->execute(['iduser'=>$idUtilisateur]) or die(print_r($reqC->errorInfo()));            
            $nbre = $reqC->fetch();            
            if($nbre){            
                $sql9="UPDATE `aaa-acces` SET idBoutique=:idBoutique WHERE idutilisateur=:iduser";
                $req9 = $bdd->prepare($sql9);
                $req9->execute([
                    'idBoutique'=>$_SESSION['idBoutique'],
                    'iduser'=>$idUtilisateur
                ]) or die(print_r($req9->errorInfo()));
            }
            else{
                $sql3="INSERT INTO `aaa-acces` (idutilisateur,idBoutique,proprietaire,gerant,caissier,activer,profil)
                                 VALUES (:iduser,:idBoutique,1,1,1,1,'Admin')";
                $req3 = $bdd->prepare($sql3);
                $req3->execute([
                    'iduser'=>$idUtilisateur,
                    'idBoutique'=>$_SESSION['idBoutique']
                ]) or die(print_r($req3->errorInfo()));
            }            
        }            
        $sql10="UPDATE `aaa-utilisateur` SET creationB=1 WHERE idutilisateur=:iduser";
        $req10 = $bdd->prepare($sql10);
        $req10->execute(['iduser'=>$idUtilisateur]) or die(print_r($req10->errorInfo()));

    }else{
        ?>
        <script> alert("Un accompagnateur avec ce matricule n'existe pas ")</script>
        <?php
    }
}else{
    echo "";
    ?>
    <script> alert("Une boutique de ce nom existe déja")</script>
    <?php
}


/** */  