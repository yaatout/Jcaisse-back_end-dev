<?php
session_start();
date_default_timezone_set('Africa/Dakar');
if(!$_SESSION['iduser']){
	header('Location:../index.php');
    }
    
require('connection.php');
require('declarationVariables.php');

/**********************/
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

$idFournisseur=htmlspecialchars(trim($_GET['iDS']));
$sql0="SELECT * FROM `".$nomtableFournisseur."` where idFournisseur=".$idFournisseur."";
$res0 = mysql_query($sql0) or die ("persoonel requête 3".mysql_error());
$fournisseur = mysql_fetch_assoc($res0);

if ($_SESSION['compte']==1) {
    # code...
    /***Debut compte qui fait le paiement ***/
    $sqlGetComptePay="SELECT * FROM `".$nomtableCompte."` where idCompte <> 2 and idCompte <> 3 ORDER BY idCompte";
    $resPay = mysql_query($sqlGetComptePay) or die ("persoonel requête 2".mysql_error());
}

/**Debut Button Terminer BL**/
if (isset($_POST['btnEnregistrerBl'])) {
    if($_SESSION['importExp']==1){
        $numero=htmlspecialchars(trim($_POST['numeroBl']));
        $description=htmlspecialchars(trim($_POST['description']));
        $dateAct_Bl=htmlspecialchars(trim($_POST['dateAct_Bl']));
        $tab1=explode("-",$dateAct_Bl);
        $dateBl=$tab1[2].'-'.$tab1[1].'-'.$tab1[0];
        $dateEcheance=$dateString;
        $montantDevise=htmlspecialchars(trim($_POST['montantDevise']));
        $devise=htmlspecialchars($_POST['devise']);
        $valeurDevise=htmlspecialchars(trim($_POST['valeurDevise']));
        $montantBl=$valeurDevise * $montantDevise;
        $sql1='INSERT INTO `'.$nomtableBl.'` (idFournisseur,numeroBl,dateBl,heureBl,montantBl,montantDevise,devise,valeurDevise,description,dateEcheance) 
        VALUES ("'.$idFournisseur.'","'.$numero.'","'.$dateBl.'","'.$heureString.'","'.$montantBl.'","'.$montantDevise.'","'.$devise.'","'.$valeurDevise.'","'.$description.'","'.$dateEcheance.'")';
        $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;
    }
    else{
        $numero=htmlspecialchars(trim($_POST['numeroBl']));
        $description=htmlspecialchars(trim($_POST['description']));
        $montant=htmlspecialchars($_POST['montantBl']);
        $dateAct_Bl=htmlspecialchars(trim($_POST['dateAct_Bl']));
        $tab1=explode("-",$dateAct_Bl);
        $dateBl=$tab1[2].'-'.$tab1[1].'-'.$tab1[0];
        $dateEcheance=$dateString;
        $sql1='INSERT INTO `'.$nomtableBl.'` (idFournisseur,numeroBl,dateBl,heureBl,montantBl,description,dateEcheance) 
        VALUES ("'.$idFournisseur.'","'.$numero.'","'.$dateBl.'","'.$heureString.'","'.$montant.'","'.$description.'","'.$dateEcheance.'")';
        $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;
    }
}
/**Fin Button Terminer BL**/

/**Debut Button Annuler BL**/
if (isset($_POST['btnAnnulerBl'])) {
    $idBl=htmlspecialchars(trim($_POST['idBl']));
    //on fait la suppression de cette Versement
    $sql3="DELETE FROM `".$nomtableBl."` where idBl=".$idBl;
    $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());
}
/**Fin Button Annuler BL**/

/**Debut Button Modifier BL**/
if (isset($_POST['btnModifierBl'])) {
    $idBl=htmlspecialchars(trim($_POST['idBl']));
    $blFourni=htmlspecialchars(trim($_POST['idFournisseur']));
    $numero=htmlspecialchars(trim($_POST['numeroBl']));
    $description=htmlspecialchars(trim($_POST['description']));
    $montant=htmlspecialchars($_POST['montantBl']);
    $dateAct_Bl=htmlspecialchars(trim($_POST['dateBl']));
    $dateAct_Echeance=htmlspecialchars(trim($_POST['dateEcheance']));
    $tab1=explode("-",$dateAct_Bl);
    $dateBl=$tab1[2].'-'.$tab1[1].'-'.$tab1[0];
    $tab2=explode("-",$dateAct_Echeance);
    $dateEcheance=$tab2[2].'-'.$tab2[1].'-'.$tab2[0];
    // var_dump($blFourni."/".$numero."/".$montant."/".$description."/".$dateBl."/".$dateEcheance."/".$idBl);
    $sql2="UPDATE `".$nomtableBl."` set idFournisseur='".$blFourni."',numeroBl='".$numero."',montantBl='".$montant."',description='".mysql_real_escape_string($description)."',dateBl='".$dateBl."',dateEcheance='".$dateEcheance."' where idBl='".$idBl."' ";
    $res2=mysql_query($sql2) or die ("modification 1 impossible =>".mysql_error());
}
/**Fin Button Modifier BL**/


/**Debut Button Terminer Versement**/
if (isset($_POST['btnEnregistrerVersement'])) {
    $montant=htmlspecialchars(trim($_POST['montant']));
    $paiement=htmlspecialchars($_POST['typeVersement']);
    $dateActualiser=htmlspecialchars(trim($_POST['dateActualiser']));
    $tab=explode("-",$dateActualiser);
    $dateVersement=$tab[2].'-'.$tab[1].'-'.$tab[0];
    $sql2="INSERT into `".$nomtableVersement."` (idFournisseur,paiement,montant,dateVersement,heureVersement,iduser) values(".$idFournisseur.",'".$paiement."',".$montant.",'".$dateVersement."','".$heureString."',".$_SESSION['iduser'].")";
    $res2=mysql_query($sql2) or die ("insertion Versement impossible =>".mysql_error());
    
    if (isset($_POST['compte'])) {
        $idCompteDebiter=htmlspecialchars(trim($_POST['compte']));
        # code...
        $operation='retrait';
        // $idCompte='2';
        $description="Versement fournisseur";
                                                                
        $sqlGetV="SELECT * FROM `".$nomtableVersement."` ORDER BY idVersement DESC LIMIT 1";
        $resV = mysql_query($sqlGetV) or die ("persoonel requête 2".mysql_error());
        $idV= mysql_fetch_array($resV);
        // var_dump($idCompte);
        // $description=$refTransf;
        // $newMontant=$compte['montantCompte']+$montantTransfert;

        $sql8="UPDATE `".$nomtableVersement."`  set  idCompte=".$idCompteDebiter." where  idVersement=".$idV['idVersement'];
        //var_dump($sql7);
        $res8=@mysql_query($sql8) or die ("mise à jour pour activer ou pas ".mysql_error());

        $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte - ".$montant." where  idCompte=".$idCompteDebiter;
        //var_dump($sql7);
        $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());

        $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,idVersement,idUser) values(".$montant.",'".$operation."',".$idCompteDebiter.",'".$description."','".$dateHeures."','".$dateHeures."',".$idV['idVersement'].",".$_SESSION['iduser'].")";
        //var_dump($sql6);
        $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error() );
    }

}
/**Fin Button Terminer Versement**/

/**Debut Button Annuler Versement**/
if (isset($_POST['btnAnnulerVersement'])) {
    $idVersement=htmlspecialchars(trim($_POST['idVersement']));
    $montant=htmlspecialchars(trim($_POST['montant']));
        //on fait la suppression de cette Versement
         
    if ($_SESSION['compte']==1) {            

        $sqlGetComptePay2="SELECT * FROM `".$nomtableVersement."` where idVersement = ".$idVersement;
        $resPay2 = mysql_query($sqlGetComptePay2) or die ("persoonel requête 2".mysql_error());
        $v = mysql_fetch_array($resPay2);
        $idCompte = $v['idCompte'];

        $sql70="UPDATE `".$nomtableCompte."` set  montantCompte=montantCompte+".$montant." where  idCompte='".$idCompte."'";
        $res70=@mysql_query($sql70) or die ("test 1 pour activer ou pas ".mysql_error());
        
        // $idCompteBon='2';
        // $description2="Versement annuler";

        // $sql7="UPDATE `".$nomtableCompte."` set  montantCompte=montantCompte+".$montant." where  idCompte=".$idCompteBon;
        // //var_dump($sql7);
        // $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());
        
        //on fait la suppression des mouvements
        $sql8="DELETE FROM `".$nomtableComptemouvement."` where idVersement=".$idVersement;
        $res8=@mysql_query($sql8) or die ("mise à jour client  impossible".mysql_error());
    }
    $sql3="DELETE FROM `".$nomtableVersement."` where idVersement=".$idVersement;
    $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());
}
/**Fin Button Annuler Versement**/

/**Debut Button Modifier Versement**/
if (isset($_POST['btnModifierVersement'])) {
    $idVersement=htmlspecialchars(trim($_POST['idVersement']));
    $montant=htmlspecialchars(trim($_POST['montant']));
    $description=htmlspecialchars($_POST['description']);
    $dateActualiser=htmlspecialchars(trim($_POST['dateVersement']));
    $tab=explode("-",$dateActualiser);
    $dateVersement=$tab[2].'-'.$tab[1].'-'.$tab[0];
    $sql2="UPDATE `".$nomtableVersement."` set montant='".$montant."',paiement='".$description."',dateVersement='".$dateVersement."' where idVersement='".$idVersement."' ";
    $res2=mysql_query($sql2) or die ("insertion Versement impossible =>".mysql_error()); 
}
/**Fin Button Modifier Versement**/

/**Debut Button upload Image Bl**/
if (isset($_POST['btnUploadImgBl'])) {
    $idBl=htmlspecialchars(trim($_POST['idBl']));
    if(isset($_FILES['file'])){
        $tmpName = $_FILES['file']['tmp_name'];
        $name = $_FILES['file']['name'];
        $size = $_FILES['file']['size'];
        $error = $_FILES['file']['error'];

        $tabExtension = explode('.', $name);
        $extension = strtolower(end($tabExtension));

        $extensions = ['jpg', 'png', 'jpeg', 'gif','pdf'];
        $maxSize = 400000;

        if(in_array($extension, $extensions) && $error == 0){

            $uniqueName = uniqid('', true);
            //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
            $file = $uniqueName.".".$extension;
            //$file = 5f586bf96dcd38.73540086.jpg

            move_uploaded_file($tmpName, './PiecesJointes/'.$file);

            $sql2="UPDATE `".$nomtableBl."` set image='".$file."' where idBl='".$idBl."' ";
            $res2=mysql_query($sql2) or die ("modification 1 impossible =>".mysql_error());
        }
        else{
            echo "Une erreur est survenue";
        }
    }
}
/**Fin Button upload Image Bl**/

/**Debut Button upload Image Versement**/
if (isset($_POST['btnUploadImgVersement'])) {
    $idVersement=htmlspecialchars(trim($_POST['idVersement']));
    if(isset($_FILES['file'])){
        $tmpName = $_FILES['file']['tmp_name'];
        $name = $_FILES['file']['name'];
        $size = $_FILES['file']['size'];
        $error = $_FILES['file']['error'];

        $tabExtension = explode('.', $name);
        $extension = strtolower(end($tabExtension));

        $extensions = ['jpg', 'png', 'jpeg', 'gif','pdf'];
        $maxSize = 400000;

        if(in_array($extension, $extensions) && $error == 0){

            $uniqueName = uniqid('', true);
            //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
            $file = $uniqueName.".".$extension;
            //$file = 5f586bf96dcd38.73540086.jpg

            move_uploaded_file($tmpName, './PiecesJointes/'.$file);

            $sql2="UPDATE `".$nomtableVersement."` set image='".$file."' where idVersement='".$idVersement."' ";
            $res2=mysql_query($sql2) or die ("modification 1 impossible =>".mysql_error());
        }
        else{
            echo "Une erreur est survenue";
        }
    }
}
/**Fin Button upload Image Versement**/

require('entetehtml.php');
?>
<!-- Debut Code HTML -->
<body onLoad="">
<header>
    <?php require('header.php'); ?>
    <!-- Debut Container HTML -->
    <div class="container" >
        <?php

            // On détermine sur quelle page on se trouve
            if(isset($_GET['page']) && !empty($_GET['page'])){
                $currentPage = (int) strip_tags($_GET['page']);
            }else{
                $currentPage = 1;
            }
            // On détermine le nombre d'articles par page
            $parPage = 10;
            $T_solde=0;

            if(isset($_GET['debut']) && isset($_GET['fin'])){
                $dateDebut=@htmlspecialchars($_GET["debut"]);
                $dateFin=@htmlspecialchars($_GET["fin"]);

                /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
                        $sqlC="SELECT
                        COUNT(*) AS total
                    FROM
                    (SELECT b.idFournisseur FROM `".$nomtableBl."` b where b.idFournisseur='".$idFournisseur."' AND b.dateBl BETWEEN '".$dateDebut."' AND '".$dateFin."'
                    UNION ALL
                    SELECT v.idFournisseur FROM `".$nomtableVersement."` v where v.idFournisseur='".$idFournisseur."'  AND v.dateVersement BETWEEN '".$dateDebut."' AND '".$dateFin."'
                    ) AS a ";
                    $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
                    $nbre = mysql_fetch_array($resC) ;
                    $nbPaniers = (int) $nbre['total'];
                /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/

                // On calcule le nombre de pages total
                $pages = ceil($nbPaniers / $parPage);
                                
                $premier = ($currentPage * $parPage) - $parPage;

                /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
                    $sqlP1="SELECT *,CONCAT(dateBl,'',heureBl,'',codeBl) AS dateHeure
                    FROM
                    (SELECT b.idFournisseur,b.idBl,b.dateBl,b.heureBl,CONCAT(b.idBl,'+1') as codeBl FROM `".$nomtableBl."` b where b.idFournisseur='".$idFournisseur."' AND b.dateBl BETWEEN '".$dateDebut."' AND '".$dateFin."'
                    UNION 
                    SELECT v.idFournisseur,v.idVersement,v.dateVersement,v.heureVersement,CONCAT(v.idVersement,'+2') as codeVersement FROM `".$nomtableVersement."` v where v.idFournisseur='".$idFournisseur."'  AND v.dateVersement BETWEEN '".$dateDebut."' AND '".$dateFin."'
                    ) AS a ORDER BY dateHeure DESC LIMIT ".$premier.",".$parPage." ";
                    $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
                /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/

                $sql12="SELECT SUM(montantBl) FROM `".$nomtableBl."` where idFournisseur=".$idFournisseur." AND dateBl BETWEEN '".$dateDebut."' AND '".$dateFin."' ";
                $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
                $TotalB = mysql_fetch_array($res12) ; 
    
                $sql13="SELECT SUM(montant) FROM `".$nomtableVersement."` where idFournisseur=".$idFournisseur." AND dateVersement BETWEEN '".$dateDebut."' AND '".$dateFin."' ";
                $res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());
                $TotalV = mysql_fetch_array($res13) ;
            }
            else{
                $dateDebut=$_SESSION['dateCB'];
                $dateFin=$dateString;

                /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
                        $sqlC="SELECT
                        COUNT(*) AS total
                    FROM
                    (SELECT b.idFournisseur FROM `".$nomtableBl."` b where b.idFournisseur='".$idFournisseur."' AND b.dateBl BETWEEN '".$dateDebut."' AND '".$dateFin."'
                    UNION ALL
                    SELECT v.idFournisseur FROM `".$nomtableVersement."` v where v.idFournisseur='".$idFournisseur."' AND v.dateVersement BETWEEN '".$dateDebut."' AND '".$dateFin."'
                    ) AS a ";
                    $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
                    $nbre = mysql_fetch_array($resC) ;
                    $nbPaniers = (int) $nbre['total'];
                /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/

                // On calcule le nombre de pages total
                $pages = ceil($nbPaniers / $parPage);
                                
                $premier = ($currentPage * $parPage) - $parPage;

                /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
                    $sqlP1="SELECT *,CONCAT(dateBl,'',heureBl,'',codeBl) AS dateHeure
                    FROM
                    (SELECT b.idFournisseur,b.idBl,b.dateBl,b.heureBl,CONCAT(b.idBl,'+1') as codeBl FROM `".$nomtableBl."` b where b.idFournisseur='".$idFournisseur."' 
                    UNION 
                    SELECT v.idFournisseur,v.idVersement,v.dateVersement,v.heureVersement,CONCAT(v.idVersement,'+2') as codeVersement FROM `".$nomtableVersement."` v where v.idFournisseur='".$idFournisseur."'
                    ) AS a ORDER BY dateHeure DESC LIMIT ".$premier.",".$parPage." ";
                    $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
                /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/

                $sql12="SELECT SUM(montantBl) FROM `".$nomtableBl."` where idFournisseur=".$idFournisseur." ";
                $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
                $TotalB = mysql_fetch_array($res12) ; 
    
                $sql13="SELECT SUM(montant) FROM `".$nomtableVersement."` where idFournisseur=".$idFournisseur." ";
                $res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());
                $TotalV = mysql_fetch_array($res13) ;
            }
            
            $T_solde=$TotalB[0] - $TotalV[0];

        ?>

        <div class="jumbotron noImpr">
             <div class="col-sm-2 pull-right" >
                <div aria-label="navigation">
                    <ul class="pager">
                        <li>
                        <input type="text" id="reportrange" />
                        </li>
                    </ul>
                </div>
            </div>
            <h2>Les Operations du Fournisseur : <?php echo strtoupper($fournisseur['nomFournisseur']); ?> </h2>
            <h5>Du : 
                <?php
                        $debutDetails=explode("-", $dateDebut);
                        $dateDebutA=$debutDetails[2].'-'.$debutDetails[1].'-'.$debutDetails[0];
                        $finDetails=explode("-", $dateFin);
                        $dateFinA=$finDetails[2].'-'.$finDetails[1].'-'.$finDetails[0];
                        echo $dateDebutA." au ".$dateFinA; 
                ?>
            </h5>
            <div class="panel-group">
                <div class="panel" style="background:#cecbcb;">
                    <div class="panel-heading" >
                        <h4 class="panel-title">
                        <a data-toggle="collapse" href="#collapse1">Montant à verser : <?php echo number_format($T_solde, 2, ',', ' '); ?>   </a>
                        </h4>
                    </div>
                    <div id="collapse1" class="panel-collapse collapse">
                        <div class="panel-heading" style="margin-left:2%;">
                            <h4>Total des Bons de Livraison : <?php echo number_format($TotalB[0], 2, ',', ' '); ?></h4>
                            <h4>Total des Versements : <?php echo number_format($TotalV[0], 2, ',', ' '); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <form class="form-inline pull-left noImpr"  target="_blank" style="margin-left:20px;"
                method="post" action="pdfOperationsFournisseur.php" >
                <input type="hidden" name="idFournisseur" id="idFournisseur"  <?php echo  "value=".$idFournisseur."" ; ?> >
                <input type="hidden" name="dateDebut"  <?php echo  "value=".$dateDebut."" ; ?> >
                <input type="hidden" name="dateFin"  <?php echo  "value=".$dateFin."" ; ?> >
                <button  class="btn btn-primary  pull-left" style="margin-left:20px;" >
                    <span class="glyphicon glyphicon-print"></span> Impression Relevé
                </button>
            </form>
        </div>

        <!--*******************************Debut Rechercher Produit****************************************-->
        <!--<form  class="pull-right" id="searchProdForm" method="post" name="searchProdForm" >
            <input type="hidden" id="clientBon" />
            <input type="text" size="30" class="form-control" name="produit" placeholder="Rechercher ..."  id="produitBon" autocomplete="off" />
                <span id="reponsePV"></span>
        </form>-->
        <!--*******************************Fin Rechercher Produit****************************************-->
        <!--*******************************Debut Ajouter Versement****************************************-->
            <button type="button" class="btn btn-success noImpr pull-right" data-toggle="modal" data-target="#addVer">
                <i class="glyphicon glyphicon-plus"></i> Versement
            </button>
            <div class="modal fade" id="addVer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Nouveau versement</h4>
                        </div>
                        <div class="modal-body">
                            <form name="formulaireVersement" method="post" <?php echo  "action=bonFournisseur.php?iDS=". $idFournisseur."" ; ?>>
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">Description</label>
                                    <textarea  type="textarea" class="form-control" id="typeVersement" name="typeVersement" placeholder="Description" ></textarea>
                                    <span class="text-danger" ></span>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">Montant</label>
                                    <input type="number" class="form-control" id="montant" name="montant" placeholder="Montant" required="">
                                    <span class="text-danger" ></span>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">Date (jj-mm-aaaa)</label>
                                    <input type="text" size='11' maxLength='10' value="" onKeyUp="masqueSaisieDate(this.form.dateActualiser)" class="form-control" id="dateActualiser" name="dateActualiser" placeholder="jj-mm-aaaa" required="">
                                    <span class="text-danger" ></span>
                                </div>                                
                                <?php if ($_SESSION['compte']==1) { ?>
                                    <select class="form-control compte" name="compte"  <?php echo  "id=compte".$pagnet['idPagnet'] ; ?>>
                                        <!-- <option value="caisse">Caisse</option> -->
                                        <?php
                                            while ($cpt=mysql_fetch_array($resPay)) { ?>
                                                <option value="<?= $cpt['idCompte'] ; ?>"><?= $cpt['nomCompte'] ; ?></option>
                                        <?php } ?>
                                    </select>
                                <?php } ?>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                    <button type="submit" name="btnEnregistrerVersement" class="btn btn-primary">Enregistrer</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        <!--*******************************Fin Ajouter Versement****************************************-->
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addBl">
                <i class="glyphicon glyphicon-plus"></i> Ajouter BL
            </button><br><br>
            <div id="addBl" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Ajouter BL</h4>
                  </div>
                  <div class="modal-body" style="padding:40px 50px;">
                    <form name="formulaireVersement" method="post" <?php echo  "action=bonFournisseur.php?iDS=". $idFournisseur."" ; ?>>
                      <div class="form-group">
                        <label for="numeroBl">Numero </label>
                        <input type="text" class="inputbasic form-control" name="numeroBl" />
                      </div>
                      <div class="form-group">
                        <label for="dateBl">Date (jj-mm-aaaa) </label>
                        <input class="inputbasic form-control" type="text" size='11' maxLength='10' value="" onKeyUp="masqueSaisieDate(this.form.dateAct_Bl)" name="dateAct_Bl" placeholder="jj-mm-aaaa" required=""/>
                      </div>
                      <?php if ($_SESSION['importExp']==1) { ?>
                        <div class="form-group">
                            <label for="montantDevise">Montant</label>
                            <input type="number" class="inputbasic form-control" name="montantDevise" />
                        </div>
                            <div class="form-group">
                                <label for="devise">Devise</label>
                                <input type="text" class="inputbasic form-control" name="devise" />
                        </div>
                        <div class="form-group">
                                <label for="valeurDevise">Valeur Devise</label>
                                <input type="text" class="inputbasic form-control" name="valeurDevise" />
                        </div>
                      <?php }
                      else { ?>
                        <div class="form-group">
                            <label for="montantBl">Montant TTC </label>
                            <input type="number" class="inputbasic form-control" name="montantBl" />
                        </div>
                      <?php } ?>                      
                        <div class="form-group">
                            <label for="description">Description </label>
                            <textarea class="inputbasic form-control" name="description" id="description" cols="30" rows="2"></textarea>
                        </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                        <button type="submit" name="btnEnregistrerBl" class="btn btn-primary">Enregistrer</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div> 

            
        <!-- Debut Style CSS pour eliminer les ascenseurs sur les Input de type Numeric -->
            <style >
                /* Firefox */
                input[type=number] {
                    -moz-appearance: textfield;
                }
                /* Chrome */
                input::-webkit-inner-spin-button,
                input::-webkit-outer-spin-button {
                    -webkit-appearance: none;
                    margin:0;
                }
                /* Opéra*/
                input::-o-inner-spin-button,
                input::-o-outer-spin-button {
                    -o-appearance: none;
                    margin:0
                }
            </style>
        <!-- Fin Style CSS pour eliminer les ascenseurs sur les Input de type Numeric -->

        <!-- Debut Javascript de l'Accordion pour Tout les Paniers -->
            <script >
                $(function() {
                    $(".expand").on( "click", function() {
                        // $(this).next().slideToggle(200);
                        $expand = $(this).find(">:first-child");

                        if($expand.text() == "+") {
                        $expand.text("-");
                        } else {
                        $expand.text("+");
                        }
                    });
                });
                function modifierVersement(idVersement) {
                    $("#ancienV"+idVersement).hide();
                    $("#nouveauV"+idVersement).show();
                }
                function modifierBl(idBl) {
                    $("#ancienB"+idBl).hide();
                    $("#nouveauB"+idBl).show();
                    $("#fournisseur"+idBl).show();
                }
                function showPreviewBl(event,idBl) {
                    var file = document.getElementById('input_file_Bl'+idBl).value;
                    var reader = new FileReader();
                    reader.onload = function()
                    {
                        var format = file.substr(file.length - 3);
                        var pdf = document.getElementById('output_pdf_Bl'+idBl);
                        var image = document.getElementById('output_image_Bl'+idBl);
                        if(format=='pdf'){
                            document.getElementById('output_pdf_Bl'+idBl).style.display = "block";
                            document.getElementById('output_image_Bl'+idBl).style.display = "none";
                            pdf.src = reader.result;
                        }
                        else{
                            document.getElementById('output_image_Bl'+idBl).style.display = "block";
                            document.getElementById('output_pdf_Bl'+idBl).style.display = "none";
                            image.src = reader.result;
                        }
                    }
                    reader.readAsDataURL(event.target.files[0]);
                    document.getElementById('btn_upload_Bl'+idBl).style.display = "block";
                }
                function showPreviewVersement(event,idVersement) {
                    var file = document.getElementById('input_file_Versement'+idVersement).value;
                    var reader = new FileReader();
                    reader.onload = function()
                    {
                        var format = file.substr(file.length - 3);
                        var pdf = document.getElementById('output_pdf_Versement'+idVersement);
                        var image = document.getElementById('output_image_Versement'+idVersement);
                        if(format=='pdf'){
                            document.getElementById('output_pdf_Versement'+idVersement).style.display = "block";
                            document.getElementById('output_image_Versement'+idVersement).style.display = "none";
                            pdf.src = reader.result;
                        }
                        else{
                            document.getElementById('output_image_Versement'+idVersement).style.display = "block";
                            document.getElementById('output_pdf_Versement'+idVersement).style.display = "none";
                            image.src = reader.result;
                        }
                    }
                    reader.readAsDataURL(event.target.files[0]);
                    document.getElementById('btn_upload_Versement'+idVersement).style.display = "block";
                }


            </script>
        <!-- Fin Javascript de l'Accordion pour Tout les Paniers -->

        <!-- Debut de l'Accordion pour Tout les Paniers -->
            <div class="panel-group" id="accordion">

                <?php

                /**Debut requete pour Rechercher le dernier Panier Ajouter  **/
                    $reqA="SELECT dateBl
                    FROM
                    (SELECT b.idFournisseur,b.idBl,b.dateBl,b.heureBl FROM `".$nomtableBl."` b where b.idFournisseur='".$idFournisseur."' 
                    UNION 
                    SELECT v.idFournisseur,v.idVersement,v.dateVersement,v.heureVersement FROM `".$nomtableVersement."` v where v.idFournisseur='".$idFournisseur."'
                    ) AS a ORDER BY dateBl DESC LIMIT 1";
                    $resA=mysql_query($reqA) or die ("persoonel requête 2".mysql_error());
                /**Fin requete pour Rechercher le dernier Panier Ajouter  **/ ?>         
                

                <!-- Debut Boucle while concernant les Paniers Vendus -->
                <?php $n=$nbPaniers - (($currentPage * 10) - 10);  $sav_BL=0; $sav_Versement=0;
                    while ($bons = mysql_fetch_assoc($resP1)) {   ?>
                        <?php	$idmax=mysql_result($resA,0); ?>

                            <?php

                                $bon = explode("+", $bons['dateHeure']);

                                if($bon[1]==1){

                                    $sqlT1="SELECT * FROM `".$nomtableBl."` where idBl='".$bons['idBl']."' AND dateBl='".$bons['dateBl']."' AND idFournisseur='".$idFournisseur."' ";
                                    $resT1 = mysql_query($sqlT1) or die ("persoonel requête 2".mysql_error());
                                    $bl = mysql_fetch_assoc($resT1);
                                    if($bl!=null){?>
                                        <div style="padding-top : 2px;" class="panel panel-success">
                                            <div class="panel-heading">
                                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#bl".$bl['idBl']."" ; ?>  class="panel-title expand">
                                                <div class="right-arrow pull-right">+</div>
                                                <a href="#"> BL 
                                                    <span class="spanDate noImpr"> </span>
                                                    <span class="spanDate noImpr"> </span>
                                                    <span class="spanDate noImpr"> Date: <?php echo $bl['dateBl']; ?> </span>
                                                    <span class="spanDate noImpr">Heure: <?php
                                                    if($bl['heureBl']!=null){
                                                        echo $bl['heureBl'];
                                                    }
                                                    else{
                                                        echo "00:00:00";
                                                    } 
                                                    ?>
                                                    </span>
                                                    <span class="spanDate noImpr">Montant: <?php echo number_format($bl['montantBl'], 2, ',', ' ').' '.$_SESSION['symbole']; ?></span>
                                                    <span class="spanDate noImpr"> Numero : <?php echo $bl['numeroBl']; ?></span>
                                                </a>
                                                </h4>
                                            </div>
                                            <div class="panel-collapse collapse " <?php echo  "id=bl".$bl['idBl']."" ; ?> >
                                                <div class="panel-body" >
                                                    <?php 
                                                    $sql2="SELECT * from `".$nomtableStock."` where idBl='".$bl["idBl"]."' ";
                                                    $res2=mysql_query($sql2);
                                                        if (mysql_num_rows($res2)){?>
                                                            <?php if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")){ ?>
                                                                <form class="form-inline pull-right noImpr" style="margin-right:20px;" method="post" <?php echo  "action=bl.php?id=".$bl['idBl']."" ; ?>  >
                                                                    <input type="hidden" name="idBl" id="idBl"  <?php echo  "value=".$bl['idBl']."" ; ?> >
                                                                    
                                                                    <button type="submit" class="btn btn-warning pull-right" >
                                                                    <span class="glyphicon glyphicon-folder-open"></span> Details
                                                                    </button>
                                                                </form>
                                                                <table class="table table-bordered" style="margin-top:40px;">
                                                                    <thead class="noImpr">
                                                                        <tr>
                                                                            <th style="display:none" id="fournisseur<?php echo $bl['idBl']; ?>">Fournisseur</th>
                                                                            <th>Numero</th>
                                                                            <th>Description</th>
                                                                            <th>Montant</th>
                                                                            <th>Date BL</th>
                                                                            <th>Date Echeance</th>
                                                                            <th>Piece Jointe</th>
                                                                            <th></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                            <tr id="ancienB<?php echo  $bl['idBl']; ?>">
                                                                                <td><?php echo strtoupper($bl['numeroBl']); ?></td>
                                                                                <td><?php echo $bl['description']; ?></td>
                                                                                <td><?php echo $bl['montantBl']; ?></td>
                                                                                <td><?php echo $bl['dateBl']; ?></td>
                                                                                <td><?php echo $bl['dateEcheance']; ?></td>
                                                                                <td>
                                                                                    <?php if($bl['image']!=null && $bl['image']!=' '){
                                                                                        $format=substr($bl['image'], -3); ?>
                                                                                        <?php if($format=='pdf'){ ?>
                                                                                            <img class="btn btn-xs" src="images/pdf.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" <?php echo "data-target=#imageNvBl".$bl['idBl'] ; ?> onclick="imageUpBl(<?php echo $bl['idBl']; ?>,<?php echo $bl['image']; ?>)" 	 />
                                                                                        <?php }
                                                                                        else { 
                                                                                        ?>
                                                                                            <img class="btn btn-xs" src="images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" <?php echo "data-target=#imageNvBl".$bl['idBl'] ; ?> onclick="imageUpBl(<?php echo $bl['idBl']; ?>,<?php echo $bl['image']; ?>)" 	 />
                                                                                        <?php } ?>
                                                                                    <?php }
                                                                                    else { 
                                                                                    ?>
                                                                                        <img class="btn btn-xs" src="images/upload.png" align="middle" alt="apperçu" width="45" height="40" data-toggle="modal" <?php echo "data-target=#imageNvBl".$bl['idBl'] ; ?> onclick="imageUpBl(<?php echo $bl['idBl']; ?>,<?php echo $bl['image']; ?>)" 	 />
                                                                                    <?php } ?>
                                                                                </td>
                                                                                <td>
                                                                                    <button type="button" class="btn btn-warning pull-right" onclick="modifierBl(<?php echo $bl['idBl']; ?>)" >
                                                                                            <span class="glyphicon glyphicon-pencil"></span>
                                                                                    </button>
                                                                                </td>
                                                                            </tr> 
                                                                            <tr style="display:none" id="nouveauB<?php echo $bl['idBl']; ?>">
                                                                                <form class="form-inline noImpr"  method="post" >
                                                                                    <td>
                                                                                        <select class="form-control" name="idFournisseur">
                                                                                                <option value="<?php echo $bl['idFournisseur'];?>"></option>
                                                                                                <?php
                                                                                                    $sql11="SELECT * FROM `".$nomtableFournisseur."` order by idFournisseur desc";
                                                                                                    $res11=mysql_query($sql11) or die ("insertion impossible Produit".mysql_error());
                                                                                                    while($fourn = mysql_fetch_array($res11)) {
                                                                                                        echo'<option  value= "'.$fourn['idFournisseur'].'">'.$fourn['nomFournisseur'].'</option>';
                                                                                                    }
                                                                                                ?>
                                                                                        </select>
                                                                                    </td>
                                                                                    <td><input type="text" class="form-control" value="<?php echo strtoupper($bl['numeroBl']);?>" name="numeroBl"  ></td>
                                                                                    <td><textarea  type="textarea" class="form-control" name="description" placeholder="Description" ><?php echo $bl['description'];?></textarea></td> 
                                                                                    <td><input type="number" class="form-control" value="<?php echo $bl['montantBl'];?>" name="montantBl" ></td>
                                                                                    <td><input type="text" size='11' maxLength='10' value="<?php $tab=explode("-",$bl['dateBl']); $dateBl=$tab[2].'-'.$tab[1].'-'.$tab[0]; echo $dateBl;?>" onKeyUp="masqueSaisieDate(this.form.dateBl)" class="form-control"  name="dateBl" placeholder="jj-mm-aaaa" required=""></td>
                                                                                    <?php if($bl['dateEcheance']!=null && $bl['dateEcheance']!=''){?>
                                                                                        <td><input type="text" size='11' maxLength='10' value="<?php $tab=explode("-",$bl['dateEcheance']); $dateEcheance=$tab[2].'-'.$tab[1].'-'.$tab[0]; echo $dateEcheance;?>" onKeyUp="masqueSaisieDate(this.form.dateEcheance)" class="form-control"  name="dateEcheance" placeholder="jj-mm-aaaa" required=""></td>
                                                                                    <?php }
                                                                                    else { 
                                                                                    ?>
                                                                                        <td><input type="text" size='11' maxLength='10' value="" onKeyUp="masqueSaisieDate(this.form.dateEcheance)" class="form-control"  name="dateEcheance" placeholder="jj-mm-aaaa" required=""></td>
                                                                                    <?php } ?>
                                                                                    <td>
                                                                                        <input type="hidden" name="idBl" <?php echo  "value=".$bl['idBl']."" ; ?> >
                                                                                        <button type="submit" class="btn btn-success pull-right" name="btnModifierBl">
                                                                                                <span class="glyphicon glyphicon-ok"></span>
                                                                                        </button>
                                                                                    </td>
                                                                                </form>
                                                                        </tr>
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <tr>
                                                                            <th colspan="6"> Solde avant : <?php echo  situationAvant($bl['dateBl'],$bl['heureBl'],$idFournisseur); ?> F CFA</th>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                                <table class="table ">
                                                                    <thead class="noImpr">
                                                                        <tr>
                                                                            <th>Référence</th>
                                                                            <th>Forme</th>
                                                                            <th>Quantite</th>
                                                                            <th>Prix Session</th>
                                                                            <th>Prix Public</th>
                                                                            <th>Enregistrement</th>
                                                                            <th>Expiration</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php 
                                                                            while ($stock = mysql_fetch_assoc($res2)) { echo '
                                                                                <tr>
                                                                                    <td>'.strtoupper($stock['designation']).'</td>
                                                                                    <td>'.strtoupper($stock['forme']).'</td>
                                                                                    <td>'.$stock['quantiteStockinitial'].'</td>
                                                                                    <td>'.$stock['prixSession'].'</td>
                                                                                    <td>'.$stock['prixPublic'].'</td>
                                                                                    <td>'.$stock['dateStockage'].'</td>
                                                                                    <td>'.$stock['dateExpiration'].'</td>
                                                                                </tr> ';
                                                                            }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            <?php }
                                                            else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) { ?>
                                                                <form class="form-inline pull-right noImpr" style="margin-right:20px;" method="post" <?php echo  "action=bl.php?id=".$bl['idBl']."" ; ?>  >
                                                                    <input type="hidden" name="idBl" id="idBl"  <?php echo  "value=".$bl['idBl']."" ; ?> >
                                                                    <button type="submit" class="btn btn-warning pull-right" >
                                                                    <span class="glyphicon glyphicon-folder-open"></span> Details
                                                                    </button> 
                                                                </form>
                                                                <table class="table table-bordered" style="margin-top:40px;">
                                                                    <thead class="noImpr">
                                                                        <tr>
                                                                            <th style="display:none" id="fournisseur<?php echo $bl['idBl']; ?>">Fournisseur</th>
                                                                            <?php if ($_SESSION['importExp']==1){ ?>
                                                                                <th>Voyage</th>
                                                                            <?php } ?>
                                                                            <th>Numero</th>
                                                                            <th>Description</th>
                                                                            <th>Montant</th>
                                                                            <th>Date BL</th>
                                                                            <th>Date Echeance</th>
                                                                            <th>Piece Jointe</th>
                                                                            <th></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                            <tr id="ancienB<?php echo  $bl['idBl']; ?>">
                                                                                <?php if ($_SESSION['importExp']==1){ 
                                                                                    $sqlV="SELECT * FROM `".$nomtableVoyage."` where idVoyage=".$bl['idVoyage']."";
                                                                                    $resV = mysql_query($sqlV) or die ("persoonel requête 3".mysql_error());
                                                                                    $voyage = mysql_fetch_assoc($resV);
                                                                                    if($voyage!=null){ ?>
                                                                                        <td><?php echo strtoupper($voyage['destination']); ?></td>
                                                                                        <?php
                                                                                    }
                                                                                    else{
                                                                                        echo '<td>NEANT</td>';
                                                                                    }
                                                                                 } ?>
                                                                                <td><?php echo strtoupper($bl['numeroBl']); ?></td>
                                                                                <td><?php echo $bl['description']; ?></td>
                                                                                <td><?php echo $bl['montantBl']; ?></td>
                                                                                <td><?php echo $bl['dateBl']; ?></td>
                                                                                <td><?php echo $bl['dateEcheance']; ?></td>
                                                                                <td>
                                                                                    <?php if($bl['image']!=null && $bl['image']!=' '){
                                                                                        $format=substr($bl['image'], -3); ?>
                                                                                        <?php if($format=='pdf'){ ?>
                                                                                            <img class="btn btn-xs" src="images/pdf.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" <?php echo "data-target=#imageNvBl".$bl['idBl'] ; ?> onclick="imageUpBl(<?php echo $bl['idBl']; ?>,<?php echo $bl['image']; ?>)" 	 />
                                                                                        <?php }
                                                                                        else { 
                                                                                        ?>
                                                                                            <img class="btn btn-xs" src="images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" <?php echo "data-target=#imageNvBl".$bl['idBl'] ; ?> onclick="imageUpBl(<?php echo $bl['idBl']; ?>,<?php echo $bl['image']; ?>)" 	 />
                                                                                        <?php } ?>
                                                                                    <?php }
                                                                                    else { 
                                                                                    ?>
                                                                                        <img class="btn btn-xs" src="images/upload.png" align="middle" alt="apperçu" width="45" height="40" data-toggle="modal" <?php echo "data-target=#imageNvBl".$bl['idBl'] ; ?> onclick="imageUpBl(<?php echo $bl['idBl']; ?>,<?php echo $bl['image']; ?>)" 	 />
                                                                                    <?php } ?>
                                                                                </td>
                                                                                <td>
                                                                                    <button type="button" class="btn btn-warning pull-right" onclick="modifierBl(<?php echo $bl['idBl']; ?>)" >
                                                                                            <span class="glyphicon glyphicon-pencil"></span>
                                                                                    </button>
                                                                                </td>
                                                                            </tr> 
                                                                            <tr style="display:none" id="nouveauB<?php echo $bl['idBl']; ?>">
                                                                                <form class="form-inline noImpr"  method="post" >
                                                                                    <td>
                                                                                        <select class="form-control" name="idFournisseur">
                                                                                                <option value="<?php echo $bl['idFournisseur'];?>"></option>
                                                                                                <?php
                                                                                                    $sql11="SELECT * FROM `".$nomtableFournisseur."` order by idFournisseur desc";
                                                                                                    $res11=mysql_query($sql11) or die ("insertion impossible Produit".mysql_error());
                                                                                                    while($fourn = mysql_fetch_array($res11)) {
                                                                                                        echo'<option  value= "'.$fourn['idFournisseur'].'">'.$fourn['nomFournisseur'].'</option>';
                                                                                                    }
                                                                                                ?>
                                                                                        </select>
                                                                                    </td>
                                                                                    <td><input type="text" class="form-control" value="<?php echo strtoupper($bl['numeroBl']);?>" name="numeroBl"  ></td>
                                                                                    <td><textarea  type="textarea" class="form-control" name="description" placeholder="Description" ><?php echo $bl['description'];?></textarea></td> 
                                                                                    <td><input type="number" class="form-control" value="<?php echo $bl['montantBl'];?>" name="montantBl" ></td>
                                                                                    <td><input type="text" size='11' maxLength='10' value="<?php $tab=explode("-",$bl['dateBl']); $dateBl=$tab[2].'-'.$tab[1].'-'.$tab[0]; echo $dateBl;?>" onKeyUp="masqueSaisieDate(this.form.dateBl)" class="form-control"  name="dateBl" placeholder="jj-mm-aaaa" required=""></td>
                                                                                    <?php if($bl['dateEcheance']!=null && $bl['dateEcheance']!=''){?>
                                                                                        <td><input type="text" size='11' maxLength='10' value="<?php $tab=explode("-",$bl['dateEcheance']); $dateEcheance=$tab[2].'-'.$tab[1].'-'.$tab[0]; echo $dateEcheance;?>" onKeyUp="masqueSaisieDate(this.form.dateEcheance)" class="form-control"  name="dateEcheance" placeholder="jj-mm-aaaa" required=""></td>
                                                                                    <?php }
                                                                                    else { 
                                                                                    ?>
                                                                                        <td><input type="text" size='11' maxLength='10' value="" onKeyUp="masqueSaisieDate(this.form.dateEcheance)" class="form-control"  name="dateEcheance" placeholder="jj-mm-aaaa" required=""></td>
                                                                                    <?php } ?>
                                                                                    <td>
                                                                                        <input type="hidden" name="idBl" <?php echo  "value=".$bl['idBl']."" ; ?> >
                                                                                        <button type="submit" class="btn btn-success pull-right" name="btnModifierBl">
                                                                                                <span class="glyphicon glyphicon-ok"></span>
                                                                                        </button>
                                                                                    </td>
                                                                                </form>
                                                                        </tr>
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <tr>
                                                                            <th colspan="6"> Solde avant : <?php echo  situationAvant($bl['dateBl'],$bl['heureBl'],$idFournisseur); ?> F CFA</th>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                                <table class="table ">
                                                                    <thead class="noImpr">
                                                                        <tr>
                                                                            <th>Référence</th>
                                                                            <th>Unite Stock (US)</th>
                                                                            <th>Quantite</th>
                                                                            <th>Prix Achat</th>
                                                                            <th>Prix US</th>
                                                                            <th>Enregistrement</th>
                                                                            <th>Expiration</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php 
                                                                            while ($stock = mysql_fetch_assoc($res2)) { echo '
                                                                                <tr>
                                                                                    <td>'.strtoupper($stock['designation']).'</td>
                                                                                    <td>'.strtoupper($stock['uniteStock']).'</td>
                                                                                    <td>'.$stock['quantiteStockinitial'].'</td>
                                                                                    <td>'.number_format($stock['prixachat'], 0, ',', ' ').'</td>
                                                                                    <td>'.number_format($stock['prixuniteStock'], 0, ',', ' ').'</td>
                                                                                    <td>'.$stock['dateStockage'].'</td>
                                                                                    <td>'.$stock['dateExpiration'].'</td>
                                                                                </tr> ';
                                                                            }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            <?php }
                                                            else  { ?>
                                                                <form class="form-inline pull-right noImpr" style="margin-right:20px;" method="post" <?php echo  "action=bl.php?id=".$bl['idBl']."" ; ?>  >
                                                                    <input type="hidden" name="idBl" id="idBl"  <?php echo  "value=".$bl['idBl']."" ; ?> >
                                                                    
                                                                    <button type="submit" class="btn btn-warning pull-right" >
                                                                    <span class="glyphicon glyphicon-folder-open"></span> Details
                                                                    </button>
                                                                </form>
                                                                <table class="table table-bordered" style="margin-top:40px;">
                                                                    <thead class="noImpr">
                                                                        <tr>
                                                                            <th style="display:none" id="fournisseur<?php echo $bl['idBl']; ?>">Fournisseur</th>
                                                                            <th>Numero</th>
                                                                            <th>Description</th>
                                                                            <th>Montant</th>
                                                                            <th>Date BL</th>
                                                                            <th>Date Echeance</th>
                                                                            <th>Piece Jointe</th>
                                                                            <th></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                            <tr id="ancienB<?php echo  $bl['idBl']; ?>">
                                                                                <td><?php echo strtoupper($bl['numeroBl']); ?></td>
                                                                                <td><?php echo $bl['description']; ?></td>
                                                                                <td><?php echo $bl['montantBl']; ?></td>
                                                                                <td><?php echo $bl['dateBl']; ?></td> 
                                                                                <td><?php echo $bl['dateEcheance']; ?></td>
                                                                                <td>
                                                                                    <?php if($bl['image']!=null && $bl['image']!=' '){
                                                                                        $format=substr($bl['image'], -3); ?>
                                                                                        <?php if($format=='pdf'){ ?>
                                                                                            <img class="btn btn-xs" src="images/pdf.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" <?php echo "data-target=#imageNvBl".$bl['idBl'] ; ?> onclick="imageUpBl(<?php echo $bl['idBl']; ?>,<?php echo $bl['image']; ?>)" 	 />
                                                                                        <?php }
                                                                                        else { 
                                                                                        ?>
                                                                                            <img class="btn btn-xs" src="images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" <?php echo "data-target=#imageNvBl".$bl['idBl'] ; ?> onclick="imageUpBl(<?php echo $bl['idBl']; ?>,<?php echo $bl['image']; ?>)" 	 />
                                                                                        <?php } ?>
                                                                                    <?php }
                                                                                    else { 
                                                                                    ?>
                                                                                        <img class="btn btn-xs" src="images/upload.png" align="middle" alt="apperçu" width="45" height="40" data-toggle="modal" <?php echo "data-target=#imageNvBl".$bl['idBl'] ; ?> onclick="imageUpBl(<?php echo $bl['idBl']; ?>,<?php echo $bl['image']; ?>)" 	 />
                                                                                    <?php } ?>
                                                                                </td>
                                                                                <td>
                                                                                    <button type="button" class="btn btn-warning pull-right" onclick="modifierBl(<?php echo $bl['idBl']; ?>)" >
                                                                                            <span class="glyphicon glyphicon-pencil"></span>
                                                                                    </button>
                                                                                </td>
                                                                            </tr> 
                                                                            <tr style="display:none" id="nouveauB<?php echo $bl['idBl']; ?>">
                                                                                <form class="form-inline noImpr"  method="post" >
                                                                                    <td>
                                                                                        <select class="form-control" name="idFournisseur">
                                                                                                <option value="<?php echo $bl['idFournisseur'];?>"></option>$i.'
                                                                                                <?php
                                                                                                    $sql11="SELECT * FROM `".$nomtableFournisseur."` order by idFournisseur desc";
                                                                                                    $res11=mysql_query($sql11) or die ("insertion impossible Produit".mysql_error());
                                                                                                    while($fourn = mysql_fetch_array($res11)) {
                                                                                                        echo'<option  value= "'.$fourn['idFournisseur'].'">'.$fourn['nomFournisseur'].'</option>';
                                                                                                    }
                                                                                                ?>
                                                                                        </select>
                                                                                    </td>
                                                                                    <td><input type="text" class="form-control" value="<?php echo strtoupper($bl['numeroBl']);?>" name="numeroBl"  ></td>
                                                                                    <td><textarea  type="textarea" class="form-control" name="description" placeholder="Description" ><?php echo $bl['description'];?></textarea></td> 
                                                                                    <td><input type="number" class="form-control" value="<?php echo $bl['montantBl'];?>" name="montantBl" ></td>
                                                                                    <td><input type="text" size='11' maxLength='10' value="<?php $tab=explode("-",$bl['dateBl']); $dateBl=$tab[2].'-'.$tab[1].'-'.$tab[0]; echo $dateBl;?>" onKeyUp="masqueSaisieDate(this.form.dateBl)" class="form-control"  name="dateBl" placeholder="jj-mm-aaaa" required=""></td>
                                                                                    <?php if($bl['dateEcheance']!=null && $bl['dateEcheance']!=''){?>
                                                                                        <td><input type="text" size='11' maxLength='10' value="<?php $tab=explode("-",$bl['dateEcheance']); $dateEcheance=$tab[2].'-'.$tab[1].'-'.$tab[0]; echo $dateEcheance;?>" onKeyUp="masqueSaisieDate(this.form.dateEcheance)" class="form-control"  name="dateEcheance" placeholder="jj-mm-aaaa" required=""></td>
                                                                                    <?php }
                                                                                    else { 
                                                                                    ?>
                                                                                        <td><input type="text" size='11' maxLength='10' value="" onKeyUp="masqueSaisieDate(this.form.dateEcheance)" class="form-control"  name="dateEcheance" placeholder="jj-mm-aaaa" required=""></td>
                                                                                    <?php } ?>
                                                                                    <td>
                                                                                        <input type="hidden" name="idBl" <?php echo  "value=".$bl['idBl']."" ; ?> >
                                                                                        <button type="submit" class="btn btn-success pull-right" name="btnModifierBl">
                                                                                                <span class="glyphicon glyphicon-ok"></span>
                                                                                        </button>
                                                                                    </td>
                                                                                </form>
                                                                        </tr>
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <tr>
                                                                            <th colspan="6"> Solde avant : <?php echo  situationAvant($bl['dateBl'],$bl['heureBl'],$idFournisseur); ?> F CFA</th>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                                <table class="table ">
                                                                    <thead class="noImpr">
                                                                        <tr>
                                                                            <th>Référence</th>
                                                                            <th>Unite Stock (US)</th>
                                                                            <th>Quantite</th>
                                                                            <th>Prix Achat</th>
                                                                            <th>Prix US</th>
                                                                            <th>Enregistrement</th>
                                                                            <th>Expiration</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php 
                                                                            while ($stock = mysql_fetch_assoc($res2)) { echo '
                                                                                <tr>
                                                                                    <td>'.strtoupper($stock['designation']).'</td>
                                                                                    <td>'.strtoupper($stock['uniteStock']).'</td>
                                                                                    <td>'.$stock['quantiteStockinitial'].'</td>
                                                                                    <td>'.$stock['prixachat'].'</td>
                                                                                    <td>'.$stock['prixuniteStock'].'</td>
                                                                                    <td>'.$stock['dateStockage'].'</td>
                                                                                    <td>'.$stock['dateExpiration'].'</td>
                                                                                </tr> ';
                                                                            }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            <?php }?>
                                                        <?php }
                                                        else {  ?>
                                                            <button type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_anl_bl".$bl['idBl'] ; ?>	 >
                                                                <span class="glyphicon glyphicon-remove"></span>Annuler
                                                            </button>
                                                            <?php if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")){ ?>
                                                                <form class="form-inline pull-right noImpr" style="margin-right:20px;" method="post" <?php echo  "action=bl.php?id=".$bl['idBl']."" ; ?>  >
                                                                    <input type="hidden" name="idBl" id="idBl"  <?php echo  "value=".$bl['idBl']."" ; ?> >
                                                                    
                                                                    <button type="submit" class="btn btn-warning pull-right" >
                                                                    <span class="glyphicon glyphicon-folder-open"></span> Details
                                                                    </button>
                                                                </form>
                                                            <?php }
                                                            else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) { ?>
                                                                <form class="form-inline pull-right noImpr" style="margin-right:20px;" method="post" <?php echo  "action=bl.php?id=".$bl['idBl']."" ; ?>  >
                                                                    <input type="hidden" name="idBl" id="idBl"  <?php echo  "value=".$bl['idBl']."" ; ?> >
                                                                    <button type="submit" class="btn btn-warning pull-right" >
                                                                    <span class="glyphicon glyphicon-folder-open"></span> Details
                                                                    </button>
                                                                </form>
                                                            <?php }
                                                            else  { ?>
                                                                <form class="form-inline pull-right noImpr" style="margin-right:20px;" method="post" <?php echo  "action=bl.php?id=".$bl['idBl']."" ; ?>  >
                                                                    <input type="hidden" name="idBl" id="idBl"  <?php echo  "value=".$bl['idBl']."" ; ?> >
                                                                    
                                                                    <button type="submit" class="btn btn-warning pull-right" >
                                                                    <span class="glyphicon glyphicon-folder-open"></span> Details
                                                                    </button>
                                                                </form>
                                                            <?php }?>
                                                                <table class="table table-bordered" style="margin-top:40px;">
                                                                    <thead class="noImpr">
                                                                        <tr>
                                                                            <th style="display:none" id="fournisseur<?php echo $bl['idBl']; ?>">Fournisseur</th>
                                                                            <th>Numero</th>
                                                                            <th>Description</th>
                                                                            <th>Montant</th>
                                                                            <th>Date BL</th>
                                                                            <th>Date Echeance</th>
                                                                            <th>Piece Jointe</th>
                                                                            <th></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                            <tr id="ancienB<?php echo  $bl['idBl']; ?>">
                                                                                <td><?php echo strtoupper($bl['numeroBl']); ?></td>
                                                                                <td><?php echo $bl['description']; ?></td>
                                                                                <td><?php echo $bl['montantBl']; ?></td>
                                                                                <td><?php echo $bl['dateBl']; ?></td> 
                                                                                <td><?php echo $bl['dateEcheance']; ?></td>
                                                                                <td>
                                                                                    <?php if($bl['image']!=null && $bl['image']!=' '){
                                                                                        $format=substr($bl['image'], -3); ?>
                                                                                        <?php if($format=='pdf'){ ?>
                                                                                            <img class="btn btn-xs" src="images/pdf.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" <?php echo "data-target=#imageNvBl".$bl['idBl'] ; ?> onclick="imageUpBl(<?php echo $bl['idBl']; ?>,<?php echo $bl['image']; ?>)" 	 />
                                                                                        <?php }
                                                                                        else { 
                                                                                        ?>
                                                                                            <img class="btn btn-xs" src="images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" <?php echo "data-target=#imageNvBl".$bl['idBl'] ; ?> onclick="imageUpBl(<?php echo $bl['idBl']; ?>,<?php echo $bl['image']; ?>)" 	 />
                                                                                        <?php } ?>
                                                                                    <?php }
                                                                                    else { 
                                                                                    ?>
                                                                                        <img class="btn btn-xs" src="images/upload.png" align="middle" alt="apperçu" width="45" height="40" data-toggle="modal" <?php echo "data-target=#imageNvBl".$bl['idBl'] ; ?> onclick="imageUpBl(<?php echo $bl['idBl']; ?>,<?php echo $bl['image']; ?>)" 	 />
                                                                                    <?php } ?>
                                                                                </td>
                                                                                <td>
                                                                                    <button type="button" class="btn btn-warning pull-right" onclick="modifierBl(<?php echo $bl['idBl']; ?>)" >
                                                                                            <span class="glyphicon glyphicon-pencil"></span>
                                                                                    </button>
                                                                                </td>
                                                                            </tr> 
                                                                            <tr style="display:none" id="nouveauB<?php echo $bl['idBl']; ?>">
                                                                                <form class="form-inline noImpr"  method="post" >
                                                                                    <td>
                                                                                        <select class="form-control" name="idFournisseur">
                                                                                                <option value="<?php echo $bl['idFournisseur'];?>"></option>$i.'
                                                                                                <?php
                                                                                                    $sql11="SELECT * FROM `".$nomtableFournisseur."` order by idFournisseur desc";
                                                                                                    $res11=mysql_query($sql11) or die ("insertion impossible Produit".mysql_error());
                                                                                                    while($fourn = mysql_fetch_array($res11)) {
                                                                                                        echo'<option  value= "'.$fourn['idFournisseur'].'">'.$fourn['nomFournisseur'].'</option>';
                                                                                                    }
                                                                                                ?>
                                                                                        </select>
                                                                                    </td>
                                                                                    <td><input type="text" class="form-control" value="<?php echo strtoupper($bl['numeroBl']);?>" name="numeroBl"  ></td>
                                                                                    <td><textarea  type="textarea" class="form-control" name="description" placeholder="Description" ><?php echo $bl['description'];?></textarea></td> 
                                                                                    <td><input type="number" class="form-control" value="<?php echo $bl['montantBl'];?>" name="montantBl" ></td>
                                                                                    <td><input type="text" size='11' maxLength='10' value="<?php $tab=explode("-",$bl['dateBl']); $dateBl=$tab[2].'-'.$tab[1].'-'.$tab[0]; echo $dateBl;?>" onKeyUp="masqueSaisieDate(this.form.dateBl)" class="form-control"  name="dateBl" placeholder="jj-mm-aaaa" required=""></td>
                                                                                    <?php if($bl['dateEcheance']!=null && $bl['dateEcheance']!=''){?>
                                                                                        <td><input type="text" size='11' maxLength='10' value="<?php $tab=explode("-",$bl['dateEcheance']); $dateEcheance=$tab[2].'-'.$tab[1].'-'.$tab[0]; echo $dateEcheance;?>" onKeyUp="masqueSaisieDate(this.form.dateEcheance)" class="form-control"  name="dateEcheance" placeholder="jj-mm-aaaa" required=""></td>
                                                                                    <?php }
                                                                                    else { 
                                                                                    ?>
                                                                                        <td><input type="text" size='11' maxLength='10' value="" onKeyUp="masqueSaisieDate(this.form.dateEcheance)" class="form-control"  name="dateEcheance" placeholder="jj-mm-aaaa" required=""></td>
                                                                                    <?php } ?>
                                                                                    <td>
                                                                                        <input type="hidden" name="idBl" <?php echo  "value=".$bl['idBl']."" ; ?> >
                                                                                        <button type="submit" class="btn btn-success pull-right" name="btnModifierBl">
                                                                                                <span class="glyphicon glyphicon-ok"></span>
                                                                                        </button>
                                                                                    </td>
                                                                                </form>
                                                                        </tr>
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <tr>
                                                                            <th colspan="6"> Solde avant : <?php echo  situationAvant($bl['dateBl'],$bl['heureBl'],$idFournisseur); ?> F CFA</th>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                        <?php }?>
                                                    
                                                    <div class="modal fade" <?php echo  "id=msg_anl_bl".$bl['idBl'] ; ?> role="dialog">
                                                        <div class="modal-dialog">
                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                                <div class="modal-header panel-primary">
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    <h4 class="modal-title">Confirmation</h4>
                                                                </div>
                                                                <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                    <div class="modal-body">
                                                                        <p><?php echo "Voulez-vous annuler le Bon de Livraison numéro : <b>".$bl['numeroBl']."<b>" ; ?></p>
                                                                        <input type="hidden" name="idBl" id="idBl"  <?php echo  "value=".$bl['idBl']."" ; ?>>
                                                                        <input type="hidden" name="montantB" id="montantBl"  <?php echo  "value=".$bl['montantBl']."" ; ?>>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                        <button type="submit" name="btnAnnulerBl" class="btn btn-success">Confirmer</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" <?php echo  "id=imageNvBl".$bl['idBl'] ; ?> role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="padding:35px 50px;">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> BL : <b><?php echo  $bl['numeroBl'] ; ?></b></h4>
                                                    </div>
                                                    <form   method="post" enctype="multipart/form-data">
                                                        <div class="modal-body" style="padding:40px 50px;">
                                                            <input  type="text" style="display:none" name="idBl" id="idBl_Upd_Nv" <?php echo  "value=".$bl['idBl']."" ; ?> />
                                                            <div class="form-group" style="text-align:center;" >
                                                                <?php 
                                                                    if($bl['image']!=null && $bl['image']!=' '){ 
                                                                        $format=substr($bl['image'], -3);
                                                                        ?>
                                                                            <input type="file" name="file" value="<?php echo  $bl['image']; ?>" accept="image/*" id="input_file_Bl<?php echo  $bl['idBl']; ?>" onchange="showPreviewBl(event,<?php echo  $bl['idBl']; ?>);"/><br />
                                                                            <?php if($format=='pdf'){ ?>
                                                                                <iframe id="output_pdf_Bl<?php echo  $bl['idBl']; ?>" src="./PiecesJointes/<?php echo  $bl['image']; ?>" width="100%" height="500px"></iframe>
                                                                                <img style="display:none;" width="500px" height="500px" id="output_image_Bl<?php echo  $bl['idBl']; ?>"/>
                                                                            <?php }
                                                                            else { ?>
                                                                                <img  src="./PiecesJointes/<?php echo  $bl['image']; ?>" width="500px" height="500px" id="output_image_Bl<?php echo  $bl['idBl']; ?>"/>
                                                                                <iframe id="output_pdf_Bl<?php echo  $bl['idBl'];  ?>"  style="display:none;"  width="100%" height="500px"></iframe> 
                                                                            <?php } ?>
                                                                        <?php 
                                                                    }
                                                                    else{ ?>
                                                                        <input type="file" name="file" accept="image/*" id="input_file_Bl<?php echo  $bl['idBl']; ?>" id="cover_image" onchange="showPreviewBl(event,<?php echo  $bl['idBl']; ?>);"/><br />
                                                                        <img  style="display:none;" width="500px" height="500px" id="output_image_Bl<?php echo  $bl['idBl']; ?>"/>
                                                                        <iframe id="output_pdf_Bl<?php echo  $bl['idBl'];  ?>"  style="display:none;"  width="100%" height="500px"></iframe> 
                                                                    <?php
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                                <button type="submit" style="display:none" class="btn btn-success pull-left" name="btnUploadImgBl" id="btn_upload_Bl<?php echo  $bl['idBl']; ?>" >
                                                                    <span class="glyphicon glyphicon-upload"></span> Enregistrer
                                                                </button>
                                                                <?php if($bl['image']!=null && $bl['image']!=' '){ ?>
                                                                    <!-- <button type="submit" class="btn btn-primary pull-right" name="btnDownloadImg" >
                                                                    <span class="glyphicon glyphicon-download"></span> Telecharger
                                                                </button> -->
                                                                <?php } ?>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                }
                                else if ($bon[1]==2){
                                    $sqlT2="SELECT * FROM `".$nomtableVersement."` where idVersement='".$bons['idBl']."' AND dateVersement='".$bons['dateBl']."' AND idFournisseur='".$idFournisseur."' ";
                                    $resT2 = mysql_query($sqlT2) or die ("persoonel requête 2".mysql_error());
                                    $versement = mysql_fetch_assoc($resT2);
                                    if($versement!=null){?>
                                        <div style="padding-top : 2px;" class="panel panel-warning">
                                            <div class="panel-heading">
                                                <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#versement".$versement['idVersement']."" ; ?>  class="panel-title expand">
                                                <div class="right-arrow pull-right">+</div>
                                                <a href="#"> Versement 
                                                    <span class="spanDate noImpr"> </span>
                                                    <span class="spanDate noImpr"> Date: <?php echo $versement['dateVersement']; ?> </span>
                                                    <span class="spanDate noImpr">Heure: <?php echo $versement['heureVersement']; ?></span>
                                                    <span class="spanDate noImpr">Montant: <?php echo ($versement['montant'] * $_SESSION['devise']).' '.$_SESSION['symbole']; ?></span>
                                                    <span class="spanDate noImpr"> Recu : #<?php echo $versement['idVersement']; ?></span>
                                                </a>
                                                </h4>
                                            </div>
                                            <div class="panel-collapse collapse " <?php echo  "id=versement".$versement['idVersement']."" ; ?> >
                                                <div class="panel-body" >
                                                    <?php if ($versement['iduser']==$_SESSION['iduser']){ ?>
                                                        <button type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_anl_versement".$versement['idVersement'] ; ?>	 >
                                                            <span class="glyphicon glyphicon-remove"></span>Annuler
                                                        </button>
                                                    <?php }
                                                    else {  ?>
                                                        <button disabled="true" type="submit" class="btn btn-danger pull-right" data-toggle="modal" >
                                                            <span class="glyphicon glyphicon-remove"></span>Annuler
                                                        </button>
                                                    <?php }?>
                                                    
                                                    <div class="modal fade" <?php echo  "id=msg_anl_versement".$versement['idVersement'] ; ?> role="dialog">
                                                        <div class="modal-dialog">
                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                                <div class="modal-header panel-primary">
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    <h4 class="modal-title">Confirmation</h4>
                                                                </div>
                                                                <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                    <div class="modal-body">
                                                                        <p><?php echo "Voulez-vous annuler le versement numéro <b>".$n."<b>" ; ?></p>
                                                                        <input type="hidden" name="idVersement" id="idVersement"  <?php echo  "value=".$versement['idVersement']."" ; ?>>
                                                                        <input type="hidden" name="montant" id="montant"  <?php echo  "value=".$versement['montant']."" ; ?>>
                                                                        <?php if ($_SESSION['compte']==1){ ?>
                                                                            <input type="hidden" name="idCompte" id="idCompte"  <?php echo  "value=".$versement['idCompte']."" ; ?>>
                                                                        <?php } ?>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                        <button type="submit" name="btnAnnulerVersement" class="btn btn-success">Confirmer</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <form class="form-inline pull-right noImpr" style="margin-right:20px;"
                                                        method="post"  >
                                                        <input type="hidden" name="idVersement" id="idVersement"  <?php echo  "value=".$versement['idVersement']."" ; ?> >
                                                        
                                                        <?php if ($_SESSION['caissier']==1){ ?>
                                                        
                                                        <button disabled="true" type="submit" class="btn btn-warning pull-right" data-toggle="modal" name="barcodeFactureV">
                                                        <span class="glyphicon glyphicon-lock"></span>Facture
                                                        </button>
                                                        
                                                        <?php } ?>
                                                        
                                                    </form>

                                                    <form class="form-inline pull-right noImpr" style="margin-right:20px;"
                                                        method="post" action="barcodeFacture.php" target="_blank"  >
                                                        <input type="hidden" name="idVersement" id="idVersement"  <?php echo  "value=".$versement['idVersement']."" ; ?> >
                                                        
                                                        <?php if ($_SESSION['caissier']==1){ ?>
                                                        
                                                        <button type="submit" class="btn btn-info pull-right" data-toggle="modal" name="barcodeFactureV">
                                                        <span class="glyphicon glyphicon-lock"></span>Ticket de Caisse
                                                        </button>
                                                        
                                                        <?php } ?>
                                                        
                                                    </form>
                                                        
                                                    <div <?php echo  "id=divImpVer".$versement['idVersement']."" ; ?>  >
                                                        <table class="table table-bordered" style="margin-top:40px;">
                                                            <thead>
                                                                <tr>
                                                                    <th>Description</th>
                                                                    <th>Montant</th>
                                                                    <th>Date</th>
                                                                    <th>Piece Jointe</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr id="ancienV<?php echo  $versement['idVersement'] ; ?>">
                                                                    <td> 
                                                                        <?php
                                                                            if($versement['paiement']!=''){
                                                                                echo  $versement['paiement'] ;
                                                                            }
                                                                            else{
                                                                                echo 'versement';
                                                                            }

                                                                            if($_SESSION['compte']==1){
                                                                            echo  "<br /> Type paiement : " ;
                                                                                $sqlGetComptePay2="SELECT * FROM `".$nomtableCompte."` where idCompte = ".$versement['idCompte'];
                                                                                $resPay2 = mysql_query($sqlGetComptePay2) or die ("persoonel requête 2".mysql_error());
                                                                                $cpt = mysql_fetch_array($resPay2);
                                                                                // echo $cpt['nomCompte'];
                                                                                $retVal = ($cpt['nomCompte']) ? $cpt['nomCompte'] : '----' ;
                                                                                echo $retVal;
                                                                            }
                                                                            else{
                                                                                echo '<br /> Type paiement : Espèces';
                                                                            }
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo  ($versement['montant'] * $_SESSION['devise']).' '.$_SESSION['symbole']; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $versement['dateVersement'];    ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php if($versement['image']!=null && $versement['image']!=' '){
                                                                            $format=substr($versement['image'], -3); ?>
                                                                            <?php if($format=='pdf'){ ?>
                                                                                <img class="btn btn-xs" src="images/pdf.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" <?php echo "data-target=#imageNvVersement".$versement['idVersement'] ; ?> onclick="imageUpVersement(<?php echo $versement['idVersement']; ?>,<?php echo $versement['image']; ?>)" 	 />
                                                                            <?php }
                                                                                else { 
                                                                            ?>
                                                                                <img class="btn btn-xs" src="images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" <?php echo "data-target=#imageNvVersement".$versement['idVersement'] ; ?> onclick="imageUpVersement(<?php echo $versement['idVersement']; ?>,<?php echo $versement['image']; ?>)" 	 />
                                                                            <?php } ?>
                                                                        <?php }
                                                                            else { 
                                                                        ?>
                                                                            <img class="btn btn-xs" src="images/upload.png" align="middle" alt="apperçu" width="45" height="40" data-toggle="modal" <?php echo "data-target=#imageNvVersement".$versement['idVersement'] ; ?> onclick="imageUpVersement(<?php echo $versement['idVersement']; ?>,<?php echo $versement['image']; ?>)" 	 />
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-warning pull-right"  onclick="modifierVersement(<?php echo $versement['idVersement']; ?>)" >
                                                                                <span class="glyphicon glyphicon-pencil"></span>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                                <tr style="display:none" id="nouveauV<?php echo  $versement['idVersement'] ; ?>">
                                                                    <form class="form-inline noImpr" method="post" >
                                                                        <td><textarea  type="textarea" class="form-control" name="description" placeholder="Description" ><?php echo $versement['paiement'];?></textarea></td> 
                                                                        <td><input type="number" class="form-control" value="<?php echo $versement['montant'];?>" name="montant" placeholder="Montant" required=""></td>
                                                                        <td>
                                                                            <input type="text" size='11' maxLength='10' value="<?php $tab=explode("-",$versement['dateVersement']); $dateVersement=$tab[2].'-'.$tab[1].'-'.$tab[0];  echo $dateVersement;?>" onKeyUp="masqueSaisieDate(this.form.dateVersement)" class="form-control"  name="dateVersement" placeholder="jj-mm-aaaa" required="">
                                                                        </td>
                                                                        <td>
                                                                            <input type="hidden" name="idVersement" <?php echo  "value=".$versement['idVersement']."" ; ?> >
                                                                            <button type="submit" class="btn btn-success pull-right" name="btnModifierVersement">
                                                                                    <span class="glyphicon glyphicon-ok"></span>
                                                                            </button>
                                                                        </td>
                                                                    </form>
                                                                </tr>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th colspan="5"> Solde avant : <?php echo  situationAvant($versement['dateVersement'],$versement['heureVersement'],$idFournisseur); ?> F CFA</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" <?php echo  "id=imageNvVersement".$versement['idVersement'] ; ?> role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="padding:35px 50px;">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> Versement : <b>#<?php echo  $versement['idVersement'] ; ?></b></h4>
                                                    </div>
                                                    <form   method="post" enctype="multipart/form-data">
                                                        <div class="modal-body" style="padding:40px 50px;">
                                                            <input  type="text" style="display:none" name="idVersement" id="idVersement_Upd_Nv" <?php echo  "value=".$versement['idVersement']."" ; ?> />
                                                            <div class="form-group" style="text-align:center;" >
                                                                <?php 
                                                                    if($versement['image']!=null && $versement['image']!=' '){ 
                                                                        $format=substr($versement['image'], -3);
                                                                        ?>
                                                                            <input type="file" name="file" value="<?php echo  $versement['image']; ?>" accept="image/*" id="input_file_Versement<?php echo  $versement['idVersement']; ?>" onchange="showPreviewVersement(event,<?php echo  $versement['idVersement']; ?>);"/><br />
                                                                            <?php if($format=='pdf'){ ?>
                                                                                <iframe id="output_pdf_Versement<?php echo  $versement['idVersement']; ?>" src="./PiecesJointes/<?php echo  $versement['image']; ?>" width="100%" height="500px"></iframe>
                                                                                <img style="display:none;" width="500px" height="500px" id="output_image_Versement<?php echo  $versement['idVersement']; ?>"/>
                                                                            <?php }
                                                                            else { ?>
                                                                                <img  src="./PiecesJointes/<?php echo  $versement['image']; ?>" width="500px" height="500px" id="output_image_Versement<?php echo  $versement['idVersement']; ?>"/>
                                                                                <iframe id="output_pdf_Versement<?php echo  $versement['idVersement'];  ?>"  style="display:none;"  width="100%" height="500px"></iframe> 
                                                                            <?php } ?>
                                                                        <?php 
                                                                    }
                                                                    else{ ?>
                                                                        <input type="file" name="file" accept="image/*" id="input_file_Versement<?php echo  $versement['idVersement']; ?>" id="cover_image" onchange="showPreviewVersement(event,<?php echo  $versement['idVersement']; ?>);"/><br />
                                                                        <img  style="display:none;" width="500px" height="500px" id="output_image_Versement<?php echo  $versement['idVersement']; ?>"/>
                                                                        <iframe id="output_pdf_Versement<?php echo  $versement['idVersement'];  ?>"  style="display:none;"  width="100%" height="500px"></iframe> 
                                                                    <?php
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                                <button type="submit" style="display:none" class="btn btn-success pull-left" name="btnUploadImgVersement" id="btn_upload_Versement<?php echo  $versement['idVersement']; ?>" >
                                                                    <span class="glyphicon glyphicon-upload"></span> Enregistrer
                                                                </button>
                                                                <?php if($versement['image']!=null && $versement['image']!=' '){ ?>
                                                                    <!-- <button type="submit" class="btn btn-primary pull-right" name="btnDownloadImg" >
                                                                    <span class="glyphicon glyphicon-download"></span> Telecharger
                                                                </button> -->
                                                                <?php } ?>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                }
                        ?>
                    <?php $n=$n-1;   } ?>
                    <?php if(isset($_GET['debut']) && isset($_GET['fin'])){ 
                        $dateDebut=@htmlspecialchars($_GET["debut"]);
                        $dateFin=@htmlspecialchars($_GET["fin"]); ?>
                        <?php if($nbPaniers >= 11){ ?>
                            <ul class="pagination pull-right">
                                <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
                                <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
                                    <a href="bonFournisseur.php?iDS=<?= $idFournisseur; ?>&&page=<?= $currentPage - 1 ?>&&debut=<?= $dateDebut ?>&&fin=<?= $dateFin ?>" class="page-link">Précédente</a>
                                </li>
                                <?php for($page = 1; $page <= $pages; $page++): ?>
                                    <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->
                                    <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">
                                        <a href="bonFournisseur.php?iDS=<?= $idFournisseur; ?>&&page=<?= $page ?>&&debut=<?= $dateDebut ?>&&fin=<?= $dateFin ?>" class="page-link"><?= $page ?></a>
                                    </li>
                                <?php endfor ?>
                                    <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
                                    <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
                                    <a href="bonFournisseur.php?iDS=<?= $idFournisseur; ?>&&page=<?= $currentPage + 1 ?>&&debut=<?= $dateDebut ?>&&fin=<?= $dateFin ?>" class="page-link">Suivante</a>
                                </li>
                            </ul>
                        <?php } ?>
                    <?php 
                        }else { 
                    ?>
                        <?php if($nbPaniers >= 11){ ?>
                            <ul class="pagination pull-right">
                                <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
                                <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
                                    <a href="bonFournisseur.php?iDS=<?= $idFournisseur; ?>&&page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>
                                </li>
                                <?php for($page = 1; $page <= $pages; $page++): ?>
                                    <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->
                                    <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">
                                        <a href="bonFournisseur.php?iDS=<?= $idFournisseur; ?>&&page=<?= $page ?>" class="page-link"><?= $page ?></a>
                                    </li>
                                <?php endfor ?>
                                    <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
                                    <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
                                    <a href="bonFournisseur.php?iDS=<?= $idFournisseur; ?>&&page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>
                                </li>
                            </ul>
                        <?php } ?>
                    <?php }?>
                <!-- Fin Boucle while concernant les Paniers Vendus  -->

            </div>
        <!-- Fin de l'Accordion pour Tout les Paniers -->


        <?php /*****************************/
        echo''.
        '<script>$(document).ready(function(){$("#AjoutLigne").click(function(){$("#AjoutLigneModal").modal();});});</script></body></html>';
        ?>

        <script type="text/javascript">
            $(function() {
                    idFournisseur=<?php echo json_encode($idFournisseur); ?>;
                    dateDebut = <?php echo json_encode($dateDebut); ?>;
                    dateFin = <?php echo json_encode($dateFin); ?>;
                    tabDebut=dateDebut.split('-');
                    tabFin=dateFin.split('-');
                    var start = tabDebut[2]+'/'+tabDebut[1]+'/'+tabDebut[0];
                    var end = tabFin[2]+'/'+tabFin[1]+'/'+tabFin[0];
                function cb(start, end) {
                    var debut=start.format('YYYY-MM-DD');
                    var fin=end.format('YYYY-MM-DD');
                    window.location.href = "bonFournisseur.php?iDS="+idFournisseur+"&&debut="+debut+"&&fin="+fin;
                }
                $('#reportrange').daterangepicker({
                    startDate: start,
                    endDate: end,
                    locale: {
                        format: 'DD/MM/YYYY',
                        separator: ' - ',
                        applyLabel: 'Valider',
                        cancelLabel: 'Annuler',
                        fromLabel: 'De',
                        toLabel: 'à',
                        customRangeLabel: 'Choisir',
                        daysOfWeek: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve','Sa'],
                        monthNames: ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'November', 'Decembre'],
                        firstDay: 1
                    },
                    ranges: {
                        'Hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Une Semaine': [moment().subtract(6, 'days'), moment()],
                        'Un Mois': [moment().subtract(30, 'days'), moment()],
                        'Ce mois ci': [moment().startOf('month'), moment()],
                        'Dernier Mois': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                }, cb);
                cb(start, end);

            });
        </script>

        <!-- Debut PopUp d'Alerte sur l'ensemble de la Page -->
            <?php
                if(isset($msg_info)) {
                echo"<script type='text/javascript'>
                            $(window).on('load',function(){
                                $('#msg_info').modal('show');
                            });
                        </script>";
                echo'<div id="msg_info" class="modal fade " role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header panel-primary">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Alerte</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p>'.$msg_info.'</p>
                                        
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                    </div>
                                    </div>
                            </div>
                        </div>';
                        
                }
            ?>
        <!-- Fin PopUp d'Alerte sur l'ensemble de la Page -->

        <!-- Debut Message d'Alerte concernant le Stock du Produit avant la vente -->
            <div id="msg_info_js" class="modal fade " role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header panel-primary">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Alerte</h4>
                            </div>
                            <div class="modal-body">
                                <p>IMPOSSIBLE.</br>
                                </br> La quantité de ce stock est insuffisant pour la ligne.
                                </br> Il vous reste  <span id="qte_stock"></span> Unités dans le Stock.
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                            </div>
                            </div>
                    </div>
            </div>
        <!-- Fin Message d'Alerte concernant le Stock du Produit avant la vente -->

    </div>
    <!-- Fin Container HTML -->
</header>
</body>
<!-- Fin Code HTML -->

<?php 

function situationAvant($dateArret,$heureArret,$idFournisseur){

    require('connection.php');
    require('declarationVariables.php');

    $sqlP1="SELECT *,CONCAT(dateBl,'',heureBl,'',codeBl) AS dateHeure
    FROM
    (SELECT b.idFournisseur,b.idBl,b.dateBl,b.heureBl,CONCAT(b.idBl,'+1') as codeBl FROM `".$nomtableBl."` b where b.idFournisseur='".$idFournisseur."' AND ((b.dateBL < '".$dateArret."') OR (b.dateBL = '".$dateArret."' AND b.heureBl <= '".$heureArret."'))
    UNION 
    SELECT v.idFournisseur,v.idVersement,v.dateVersement,v.heureVersement,CONCAT(v.idVersement,'+2') as codeVersement FROM `".$nomtableVersement."` v where v.idFournisseur='".$idFournisseur."' AND ((v.dateVersement < '".$dateArret."') OR (v.dateVersement = '".$dateArret."' AND v.heureVersement <= '".$heureArret."'))
    ) AS a ORDER BY dateHeure  ";
    $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());

    $sav_BL=0; $sav_Versement=0; $sav_Total=0;
    while ($bons = mysql_fetch_assoc($resP1)) { 
        $bon = explode("+", $bons['dateHeure']);
        
        if($bon[1]==1){
            $sqlT1="SELECT * FROM `".$nomtableBl."` where idBl='".$bons['idBl']."' AND dateBl='".$bons['dateBl']."' AND heureBl='".$bons['heureBl']."' AND idFournisseur='".$idFournisseur."' ";
            $resT1 = mysql_query($sqlT1) or die ("persoonel requête 2".mysql_error());
            $bl = mysql_fetch_assoc($resT1);
            if($bl!=null){
                $sav_BL=$sav_BL + $bl['montantBl'];
                $sav_Total=$sav_BL - $sav_Versement - $bl['montantBl'];
            }
        }
        else if ($bon[1]==2){
            $sqlT2="SELECT * FROM `".$nomtableVersement."` where idVersement='".$bons['idBl']."' AND dateVersement='".$bons['dateBl']."' AND heureVersement='".$bons['heureBl']."' AND  idFournisseur='".$idFournisseur."' ";
            $resT2 = mysql_query($sqlT2) or die ("persoonel requête 2".mysql_error());
            $versement = mysql_fetch_assoc($resT2);
            if($versement!=null){
                $sav_Versement=$sav_Versement + $versement['montant'];
                $sav_Total=$sav_BL - $sav_Versement + $versement['montant'];
            }
        }

 
    }

    return $sav_Total;

}

?>