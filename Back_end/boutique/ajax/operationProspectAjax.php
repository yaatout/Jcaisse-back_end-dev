<?php
   session_start();

   if(!$_SESSION['iduserBack']){

   header('Location:../index.php');

   }

   //require('../../connection.php');
   require('../../connectionPDO.php');
   require('../../declarationVariables.php');
   
   //$date = new DateTime('25-02-2011');
    $date = new DateTime();
    //R�cup�ration de l'ann�e
    $annee =$date->format('Y');
    //R�cup�ration du mois
    $mois =$date->format('m');
    //R�cup�ration du jours
    $jour =$date->format('d');
    $dateString=$annee.'-'.$mois.'-'.$jour;

   $operation=$_POST["operation"];

   switch ($operation) {
    case 'addNewProspectBoutique':
        
        $clientProsp=$_POST['clientProsp'];
        $nomBoutique=$_POST['nomBPr'];
        $adresseB=$_POST['adresseBPr'];
        $telephone=$_POST['telephoneBPr'];
        $type=$_POST['typeBPr'];
        $categorie=$_POST['categorieBPr'];
        $registreCom=$_POST['registreComBPr'];
        $ninea=$_POST['nineaBPr'];
        $accompagnateur=$_POST['accompagnateurBPr'];


        // $sql1="insert into `aaa-boutique` (nomBoutique,labelB,adresseBoutique,telephone,Pays,type,categorie,datecreation,RegistreCom,Ninea,Accompagnateur) values
        // ('".mysql_real_escape_string($_SESSION['nomB'])."','".mysql_real_escape_string($_SESSION['nomB'])."','".mysql_real_escape_string($_SESSION['adresseB'])."','".$telephone."','Senegal','".$_SESSION['type']."','".$_SESSION['categorie']."','".$dateString."','".$registreCom."','".$ninea."','".$accompagnateur."')";

        $req6 = $bdd->prepare("insert into `aaa-boutique-prospect` (nomBoutique,adresseBoutique,telephone,Pays,type,categorie,datecreation,RegistreCom,Ninea,Accompagnateur) 
                                                    values (:nom,:adr,:tel,:pay,:type,:cat,:date,:reg,:nin,:acc)");
        $req6->execute(array(
                            'nom' =>$nomBoutique,
                            'adr' =>$adresseB,
                            'tel' =>$telephone,
                            'pay' =>'Sénégal',
                            'type' =>$type,
                            'cat' =>$categorie,
                            'date' =>$dateString,
                            'reg' =>$registreCom,
                            'nin' =>$ninea,
                            'acc' =>$accompagnateur
                        ))  or die(print_r($req6->errorInfo()));

        $bouttiqueId=$bdd->lastInsertId();

        $req5 = $bdd->prepare("UPDATE `aaa-boutique-prospectClient` set  idBoutiqueProspect=:idBout WHERE idBPC=:idBPC ");
        $req5->execute(array( 'idBout' => $bouttiqueId,'idBPC' => $clientProsp)) or die(print_r($req5->errorInfo()));

        break;
    case 'addNewProspectClient':
            $prenom                 =@htmlentities($_POST["prenom"]);
            $nom              =@htmlentities($_POST["nom"]);
            $adresse             =@htmlentities($_POST["adresse"]);
            $telPortable               =@htmlentities($_POST["telPortable"]);
            $telFixe         =@htmlentities($_POST["telFixe"]);
            $email             =@htmlentities($_POST["email"]);
            $nomBoutique       =@htmlentities($_POST["nomBoutique"]);

            // $sql1="insert into `aaa-boutique-prospectClient` (nom,prenom,adresse,email,telPortable,telFixe,motdepasse,dateinscription, profil) 
            //     values('".$nom."','".$prenom."','".mysql_real_escape_string($adresse)."','".$email."','".$telPortable."','".$telFixe."','".$motdepasse2."','".$dateString."', 'Admin')";
		
            $req6 = $bdd->prepare("insert into `aaa-boutique-prospectClient` (nom,prenom,adresse,email,telPortable,telFixe,nomBoutique) 
                                                    values (:nom,:pren,:adr,:em,:telP,:telF,:nomBoutique)");
            $req6->execute(array(
                            'nom' =>$nom,
                            'pren' =>$prenom,
                            'adr' =>$adresse,
                            'em' =>$email,
                            'telP' =>$telPortable,
                            'telF' =>$telFixe,
                            'nomBoutique' =>$nomBoutique
                        ))  or die(print_r($req6->errorInfo()));
        break;
    case 'modProspectClient':
            $id=@htmlentities($_POST["id"]);
            $prenom                 =@htmlentities($_POST["prenom"]);
            $nom              =@htmlentities($_POST["nom"]);
            $adresse             =@htmlentities($_POST["adresse"]);
            $telPortable               =@htmlentities($_POST["telPort"]);
            $telFixe         =@htmlentities($_POST["telFix"]);
            $email             =@htmlentities($_POST["email"]);
            $nomBoutique       =@htmlentities($_POST["nomBoutique"]);
            // $sql1="insert into `aaa-boutique-prospectClient` (nom,prenom,adresse,email,telPortable,telFixe,motdepasse,dateinscription, profil) 
            //     values('".$nom."','".$prenom."','".mysql_real_escape_string($adresse)."','".$email."','".$telPortable."','".$telFixe."','".$motdepasse2."','".$dateString."', 'Admin')";
		
            $req3 = $bdd->prepare("UPDATE `aaa-boutique-prospectClient` SET nom=:nom,prenom=:pren,adresse=:adr,email=:em,telPortable=:telP,telFixe=:telF,nomBoutique=:nomBoutique WHERE idBPC=:id ");
            $req3->execute(array(
                'nom' =>$nom,
                'pren' =>$prenom,
                'adr' =>$adresse,
                'em' =>$email,
                'telP' =>$telPortable,
                'telF' =>$telFixe,
                'nomBoutique' =>$nomBoutique,
                'id'=>$id
            ))  or die(print_r($req3->errorInfo()));
        break;
    case 'installationBoutiqueProspect':
            $idBoutique =htmlentities($_POST["idB"]);
            $idClient =htmlentities($_POST["idC"]);
            
            $req1 = $bdd->prepare("SELECT * FROM `aaa-boutique-prospect` where idBP=:i "); 
            $req1->execute(['i'=>$idBoutique])  or die(print_r($req1->errorInfo())); 
            $boutique = $req1->fetch();
            /////////////////////////////////////////////////////////////////////////////
            ////////////  Verififier si nom boutique n'existe pas encore ////////////////
                $reponse='FALSE';
                $query=$boutique['nomBoutique'];
                $reqS = $bdd->prepare("SELECT labelB,nomBoutique from `aaa-boutique` where nomBoutique LIKE '$query' ");    
                $reqS->execute()  or die(print_r($reqS->errorInfo()));  
                $datas = $reqS->fetchAll();
                
                if(!empty($datas)){
                    $reponse="TRUE"; 
                    exit($reponse);
                }
            ///////////////////////////////////////////////////////
            ///////////////////////////////////////////////////////
        
            $req2 = $bdd->prepare("SELECT * FROM `aaa-boutique-prospectclient` where idBPC=:ic "); 
            $req2->execute(['ic'=>$idClient])  or die(print_r($req2->errorInfo())); 
            $client = $req2->fetch();
            
            // echo($boutique['nomBoutique']);
            // echo($idClient);
            $_SESSION['nomB']=$boutique['nomBoutique'];
            $_SESSION['type']=$boutique['type'];
            $_SESSION['categorie']=$boutique['categorie'];
            $_SESSION['Pays']="";
            date_default_timezone_set('Africa/Dakar');
            // session_start();
            //var_dump($_SESSION['nomB']);
            $nomtableJournal=$_SESSION['nomB']."-journal";
            $nomtableCategorie   =$_SESSION['nomB']."-categorie";
            $nomtablePage=$_SESSION['nomB']."-pagej";
            $nomtablePagnet=$_SESSION['nomB']."-pagnet";
            $nomtableLigne=$_SESSION['nomB']."-lignepj";
            $nomtableDesignation=$_SESSION['nomB']."-designation";
            $nomtableStock=$_SESSION['nomB']."-stock";

            $nomtableComposant=$_SESSION['nomB']."-composant";
            $nomtableStockComposant=$_SESSION['nomB']."-stockComposant";
            $nomtableComposition=$_SESSION['nomB']."-composition";

            $nomtableTotalStock=$_SESSION['nomB']."-totalstock";
            $nomtableBon=$_SESSION['nomB']."-bon";
            $nomtableClient=$_SESSION['nomB']."-client";
            $nomtableVersement=$_SESSION['nomB']."-versement";
            $nomtableInventaire1=$_SESSION['nomB']."-inventairePermanant";
            $nomtableInventaire2=$_SESSION['nomB']."-inventaireIntermittent";
            $nomtableDesignationSD =$_SESSION['nomB']."-designationsd";
            $nomtableRayon =$_SESSION['nomB']."-Rayon";

            $nomtableBl =$_SESSION['nomB']."-bl";
            //var_dump($_SESSION['nomB']);
            //var_dump($nomtableBl);
            $nomtableFournisseur =$_SESSION['nomB']."-fournisseur";
            $nomtableEntrepot =$_SESSION['nomB']."-entrepot";
            $nomtableEntrepotStock =$_SESSION['nomB']."-entrepotstock";
            $nomtableEntrepotTransfert =$_SESSION['nomB']."-entrepottransfert";
            $nomtableInventaire =$_SESSION['nomB']."-inventaire";
            $nomtableTransfert =$_SESSION['nomB']."-transfert";
            $nomtableVoyage =$_SESSION['nomB']."-voyage";
            $nomtableFrais =$_SESSION['nomB']."-frais";
            $nomtableFacture =$_SESSION['nomB']."-facture";
            $nomtableMutuelle =$_SESSION['nomB']."-mutuelle";
            $nomtableMutuelleBon =$_SESSION['nomB']."-mutuelleBon";
            $nomtableMutuellePagnet =$_SESSION['nomB']."-mutuellepagnet";
            $nomtableStructure =$_SESSION['nomB']."-structure";
            $nomtableStructureClient =$_SESSION['nomB']."-structureclient";
            $nomtableStructurePagnet =$_SESSION['nomB']."-structurepagnet";

            $nomtableCompte =$_SESSION['nomB']."-compte";
            $nomtableComptemouvement =$_SESSION['nomB']."-comptemouvement";
            $nomtableCompteoperation =$_SESSION['nomB']."-compteoperation";
            $nomtableComptetype =$_SESSION['nomB']."-comptetype";
            $nomtableContainer =$_SESSION['nomB']."-container";

            $nomtableReservation =$_SESSION['nomB']."-reservation";
            $nomtableLigneReservation =$_SESSION['nomB']."-lignerv";

            $nomtablePagnetTampon =$_SESSION['nomB']."-pagnetTampon";
            $nomtableLigneTampon =$_SESSION['nomB']."-lignepjTampon";

            $nomtableContainer =$_SESSION['nomB']."-container";
            $nomtableAvion=$_SESSION['nomB']."-avion";
            $nomtableEnregistrement=$_SESSION['nomB']."-enregistrement";
            $nomtableNature=$_SESSION['nomB']."-nature";

            $beforeTime = '00:00:00';
            $afterTime = '06:00:00';

            /**Debut informations sur la date d'Aujourdhui **/
            if($_SESSION['Pays']=='Canada'){ 
                $date = new DateTime();
                $timezone = new DateTimeZone('Canada/Eastern');
            }
            else{
                $date = new DateTime();
                $timezone = new DateTimeZone('Africa/Dakar');
            }
            $date->setTimezone($timezone);
            $heureString=$date->format('H:i:s');

            if ($heureString >= $beforeTime && $heureString < $afterTime) {
                // var_dump ('is between');
            $date = new DateTime (date('d-m-Y',strtotime("-1 days")));
            }

            $annee =$date->format('Y');
            $mois =$date->format('m');
            $jour =$date->format('d');
            $dateString=$annee.'-'.$mois.'-'.$jour;
            $dateString2=$jour.'-'.$mois.'-'.$annee;
            $dateHeures=$dateString.' '.$heureString;
            $dateStringMA=$annee.''.$mois;
            /**Fin informations sur la date d'Aujourdhui **/
            //include '../../../JCaisse/declarationVariables.php';
            //include '../CreationBoutiqueFunctions.php';
        $bdd->beginTransaction();    
            include '../CreateBoutique_User_Accee.php';

            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                 
                include "../createPharmaDetaillant.php";                

            }else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
        
                include "../createEntrepotGrossiste.php";

            }else{

                include "../createLeResteDesTypes.php";
                   
            }
            $req30 = $bdd->prepare(" UPDATE `aaa-boutique-prospect` SET `installer` = '1' 
                                        WHERE `aaa-boutique-prospect`.`idBP` =:id ");
            $req30->execute(array(
                'id'=>$idBoutique
            ))  or die(print_r($req30->errorInfo()));
            //var_dump($req30);
        $bdd->commit();
        
        break;
    case 'rechercheBoutique':
        $reponse='FALSE';
        $query=$_POST['nomBoutique'];
        //var_dump($query);
        //$reqS = $bdd->prepare("SELECT labelB,nomBoutique from `aaa-boutique` where nomBoutique LIKE '.$query.' OR labelB LIKE '.$query.'"); 
        $reqS = $bdd->prepare("SELECT labelB,nomBoutique from `aaa-boutique` where nomBoutique LIKE '$query' ");    
        $reqS->execute()  or die(print_r($reqS->errorInfo()));  
        $datas = $reqS->fetchAll();
         //var_dump($reqS);
         //die();
        if(!empty($datas)){
            $reponse="TRUE"; 
        }else {
            $reponse="FALSE";
        }
        exit($reponse);
        break;    
    case 'supProspectClient':
        $id=@htmlentities($_POST["id"]);
        
        $req1 = $bdd->prepare("SELECT * FROM `aaa-boutique-prospectclient` where idBPC=:i "); 
        $req1->execute(['i'=>$idBoutique])  or die(print_r($req1->errorInfo())); 
        $client = $req1->fetch();
        if ($client['idBoutiqueProspect'] !=NULL) {
            $req17 = $bdd->prepare("DELETE FROM `aaa-boutique-prospect` WHERE idBP=:i ");
            $req17->execute(array('i' => $client['idBoutiqueProspect'] )) or die(print_r($req17->errorInfo()));
        }


        $req17 = $bdd->prepare("DELETE FROM `aaa-boutique-prospectclient` WHERE idBPC=:i ");
        $req17->execute(array('i' => $id )) or die(print_r($req17->errorInfo()));

        
        break;

    default:
        # code...
        break;
   }
