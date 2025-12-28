<?php

session_start();

date_default_timezone_set('Africa/Dakar');

if(!$_SESSION['iduser']){
	header('Location:../index.php');
}
/*
Résumé : Ce code permet d'inserer une ligne (une entrée ou une sortie) dans le journal d'une boutique.
Commentaire : Ce code contient un formulaire récupérant l'ensemble des informations (typeligne, designation,prix unitaire,quantite,remise,prix total) sur une de journal de la boutique.
Pour facilité le remplissage de ce formulaire ce code est associé avec du code AJAX (JavaScript:verificationdesignation.js et PHP:verificationdesig.php),
qui vérifie le champ désignation si il est vide et si il existe ou il est absent de la base de données et qui compléte les champs : prix unitaire et prix total.
Il insère ces informations dans la table commençant par le nom de la boutique et suivi de : -lignepj. Pour cela ce code à partir de la date courrante regarde si pour cette ligne y'a une page déja créer sinon il le crée et regarde aussi si pour cette page de la date courrante si le mois et l'année ya un journal déjà créer sinon il le crée.
Ainsi de façon automatique le code pour une ligne donnée le relie avec une page et un journal si ils existent. sinon le les créent avant de les associer avec cette nouvelle ligne.
Ce code permet d'afficher la liste des lignes (des entrées ou des sorties) du journal d'une boutique pour la date courrante et de modifier et de supprimer une ligne de la liste des lignes du journal.
Version : 2.0
see also : modifierLigne.php et supprimerLigne.php
Auteur : Ibrahima DIOP
Date de création : 20/03/2016
Date dernière modification : 20/04/2016
*/
require('connection.php');

require('declarationVariables.php');

require('fpdf/fpdf.php');

if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
    if (isset($_POST['idFournisseur'])) {

        $dateDebut=htmlspecialchars(trim($_POST['dateDebut']));
        $debutDetails=explode("-", $dateDebut);
        $dateDebutA=$debutDetails[2].'-'.$debutDetails[1].'-'.$debutDetails[0];
        $dateFin=htmlspecialchars(trim($_POST['dateFin']));
        $finDetails=explode("-", $dateFin);
        $dateFinA=$finDetails[2].'-'.$finDetails[1].'-'.$finDetails[0];
        $idFournisseur=htmlspecialchars(trim($_POST['idFournisseur']));

        /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
                $sqlC="SELECT
                COUNT(*) AS total
            FROM
            (SELECT b.idFournisseur FROM `".$nomtableBl."` b where b.idFournisseur='".$idFournisseur."' AND b.dateBl BETWEEN '".$dateDebut."' AND '".$dateFin."'
            UNION ALL
            SELECT v.idFournisseur FROM `".$nomtableVersement."` v where v.idFournisseur='".$idFournisseur."'  AND v.dateVersement BETWEEN '".$dateDebut."' AND '".$dateFin."'
            ) AS a ";
            $resC = mysql_query($sqlC) or die ("persoonel requête 1".mysql_error());
            $nbre = mysql_fetch_array($resC) ;
        /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/

        /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
            $sqlP1="SELECT codeBl
            FROM
            (SELECT CONCAT(b.dateBl,'',b.heureBl,'+',b.idBl,'+1')  AS codeBl  FROM `".$nomtableBl."` b where b.idFournisseur='".$idFournisseur."' AND b.dateBl BETWEEN '".$dateDebut."' AND '".$dateFin."'
            UNION 
            SELECT CONCAT(v.dateVersement,'',v.heureVersement,'+',v.idVersement,'+2')  AS codeBl FROM `".$nomtableVersement."` v where v.idFournisseur='".$idFournisseur."'  AND v.dateVersement BETWEEN '".$dateDebut."' AND '".$dateFin."'
            ) AS a ORDER BY codeBl DESC  ";
            $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
        /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/


        //Create new pdf file
        $pdf=new FPDF();

        //Disable automatic page break
        $pdf->SetAutoPageBreak(false);

        //Add first page
        $pdf->AddPage();

        //set initial y axis position per page
        $y_axis_initial = 65;

        //Set Row Height
        $row_height = 6;

        $y_axis=65;

        //Set maximum rows per page
        $max = 30;
        $i = 0;
        $p=1;

        $nb_page=ceil($nbre[0]/$max);
        $image='';
        if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
            $image='pharmacie.png'; 
        }
        else{
            $image='shop.png';
        }
        $pdf-> SetFont("Arial","B",10);
        $pdf-> Image('images/'.$image,5,5,20,20);

        $pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 160, 8, '1/' . $nb_page, 0, 0, 'C');

        $num_fact = "Operations du ".$dateDebutA." au ".$dateFinA ;
        $pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(120, 15, 85, 9, "DF");
        $pdf->SetXY( 120, 15 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 85, 8, $num_fact, 0, 0, 'C');

                // observations
        $pdf->SetFont( "Arial", "BU", 16 ); $pdf->SetXY( 25, 9 ) ; $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, $_SESSION["labelB"], 0, "L");
        // $pdf->SetFont( "Arial", "", 11 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, '('.$_SESSION["type"].' & '.$_SESSION["categorie"].')', 0, "L");
        $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, 'Adresse : '.$_SESSION["adresseB"], 0, "L");
        $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 17 ) ; $pdf->MultiCell(190, 4, 'Telephone : '.$_SESSION["telBoutique"], 0, "L");

        $sql2="SELECT * FROM `".$nomtableFournisseur."` where idFournisseur=".$idFournisseur;
        $res2 = mysql_query($sql2) or die ("persoonel requête 1".mysql_error());
        $fournisseur = mysql_fetch_array($res2);

        $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 38 ) ; $pdf->MultiCell(190, 4, 'Nom :', 0, "L");
        $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 132, 38 ) ; $pdf->MultiCell(190, 4, $fournisseur["nomFournisseur"], 0, "L");
        $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 43 ) ; $pdf->MultiCell(190, 4, 'Adresse :', 0, "L");
        $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 137, 43 ) ; $pdf->MultiCell(190, 4, $fournisseur["adresseFournisseur"], 0, "L");
        $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 48 ) ; $pdf->MultiCell(190, 4, 'Telephone :', 0, "L");
        $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 141, 48 ) ; $pdf->MultiCell(190, 4, $fournisseur["telephoneFournisseur"], 0, "L");
        //$pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 53 ) ; $pdf->MultiCell(190, 4, 'Telephone :', 0, "L");
        //$pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 141, 53 ) ; $pdf->MultiCell(190, 4, $fournisseur["telephone"], 0, "L");


        if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
            $pdf->SetFillColor(192);
            $pdf->SetFont('Arial','B',12);
            $pdf->SetY($y_axis_initial);
            $pdf->SetX(10);
            $pdf->Cell(22,6,'Date',1,0,'C',1);
            $pdf->Cell(33,6,'Numero',1,0,'C',1);
            $pdf->Cell(75,6,'Libelle',1,0,'C',1);
            $pdf->Cell(30,6,'Montant BL',1,0,'C',1);
            $pdf->Cell(30,6,'Versement',1,0,'C',1);
        }
        else{
            //print column titles
            $pdf->SetFillColor(192);
            $pdf->SetFont('Arial','B',12);
            $pdf->SetY($y_axis_initial);
            $pdf->SetX(10);
            $pdf->Cell(22,6,'Date',1,0,'C',1);
            $pdf->Cell(33,6,'Numero',1,0,'C',1);
            $pdf->Cell(75,6,'Libelle',1,0,'C',1);
            $pdf->Cell(30,6,'Montant BL',1,0,'C',1);
            $pdf->Cell(30,6,'Versement',1,0,'C',1);
            // $pdf->Cell(30,6,'Solde',1,0,'C',1);
        }


        $y_axis = $y_axis + $row_height;
        //Select the Products you want to show in your PDF file
        $c=0;
        $l=1;
        $idAvant=0;
        $totalTVA=0;
        if(mysql_num_rows($resP1)){
            while ($bons = mysql_fetch_array($resP1)) { 
                $bon = explode("+", $bons['codeBl']);
                //echo $bon[1];
                if($bon[2]==1){
                    $sqlT1="SELECT * FROM `".$nomtableBl."` where idBl='".$bon[1]."' AND idFournisseur='".$idFournisseur."'  ";
                    $resT1 = mysql_query($sqlT1) or die ("persoonel requête A".mysql_error());
                    $bl = mysql_fetch_assoc($resT1);
                    if($bl!=null){
                        if ($i == $max)
                        {
                            $pdf->AddPage();
            
                            $image='';
                            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                                $image='pharmacie.png'; 
                            }
                            else{
                                $image='shop.png';
                            }
                            $pdf-> SetFont("Arial","B",10);
                            $pdf-> Image('images/'.$image,5,5,20,20);
                    
                            $pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 160, 8, '1/' . $nb_page, 0, 0, 'C');
                    
                            $num_fact = "Operations du ".$dateDebutA." au ".$dateFinA ;
                            $pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(120, 15, 85, 9, "DF");
                            $pdf->SetXY( 120, 15 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 85, 8, $num_fact, 0, 0, 'C');
                    
                                    // observations
                            $pdf->SetFont( "Arial", "BU", 16 ); $pdf->SetXY( 25, 9 ) ; $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, $_SESSION["labelB"], 0, "L");
                            // $pdf->SetFont( "Arial", "", 11 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, '('.$_SESSION["type"].' & '.$_SESSION["categorie"].')', 0, "L");
                            $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, 'Adresse : '.$_SESSION["adresseB"], 0, "L");
                            $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 17 ) ; $pdf->MultiCell(190, 4, 'Telephone : '.$_SESSION["telBoutique"], 0, "L");
                    
                            $sql2="SELECT * FROM `".$nomtableFournisseur."` where idFournisseur=".$idFournisseur;
                            $res2 = mysql_query($sql2) or die ("persoonel requête 1".mysql_error());
                            $fournisseur = mysql_fetch_array($res2);
                    
                            $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 38 ) ; $pdf->MultiCell(190, 4, 'Nom :', 0, "L");
                            $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 132, 38 ) ; $pdf->MultiCell(190, 4, $fournisseur["nomFournisseur"], 0, "L");
                            $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 43 ) ; $pdf->MultiCell(190, 4, 'Adresse :', 0, "L");
                            $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 137, 43 ) ; $pdf->MultiCell(190, 4, $fournisseur["adresseFournisseur"], 0, "L");
                            $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 48 ) ; $pdf->MultiCell(190, 4, 'Telephone :', 0, "L");
                            $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 141, 48 ) ; $pdf->MultiCell(190, 4, $fournisseur["telephoneFournisseur"], 0, "L");
                            //$pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 53 ) ; $pdf->MultiCell(190, 4, 'Telephone :', 0, "L");
                            //$pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 141, 53 ) ; $pdf->MultiCell(190, 4, $fournisseur["telephone"], 0, "L");
                    
                            $y_axis=65;
                    
                            if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
                                $pdf->SetFillColor(192);
                                $pdf->SetFont('Arial','B',12);
                                $pdf->SetY($y_axis_initial);
                                $pdf->SetX(10);
                                $pdf->Cell(22,6,'Date',1,0,'C',1);
                                $pdf->Cell(33,6,'Numero',1,0,'C',1);
                                $pdf->Cell(75,6,'Libelle',1,0,'C',1);
                                $pdf->Cell(30,6,'Montant BL',1,0,'C',1);
                                $pdf->Cell(30,6,'Versement',1,0,'C',1);
                            }
                            else{
                                //print column titles
                                $pdf->SetFillColor(192);
                                $pdf->SetFont('Arial','B',12);
                                $pdf->SetY($y_axis_initial);
                                $pdf->SetX(10);
                                $pdf->Cell(22,6,'Date',1,0,'C',1);
                                $pdf->Cell(33,6,'Numero',1,0,'C',1);
                                $pdf->Cell(75,6,'Libelle',1,0,'C',1);
                                $pdf->Cell(30,6,'Montant BL',1,0,'C',1);
                                $pdf->Cell(30,6,'Versement',1,0,'C',1);
                            }
                            
                            //Go to next row
                            $y_axis = $y_axis + $row_height;
                            
                            //Set $i variable to 0 (first row)
                            $i = 0;
            
                        }

                        $pdf->SetY($y_axis);
                        $pdf->SetFillColor(255,255,255);
                        $pdf->SetFont('Arial','B',8);
                        $pdf->SetX(10);
                        $pdf->Cell(22,6,$bl['dateBl'],1,0,'C',1);
                        $pdf->Cell(33,6,'BL : '.$bl['numeroBl'],1,0,'C',1);
                        $pdf->Cell(75,6,$bl['description'],1,0,'C',1);
                        $pdf->Cell(30,6,number_format($bl['montantBl'], 2, ',', ' '),1,0,'C',1);
                        $pdf->Cell(30,6,'',1,0,'C',1);

                        $y_axis = $y_axis + $row_height;
                        $i = $i + 1;
                        if($i==10){
                            $p = $p + 1;
                        }
                        $l = $l + 1;
                    }
                }
                else if($bon[2]==2){
                    $sqlT2="SELECT * FROM `".$nomtableVersement."` where idVersement='".$bon[1]."' AND idFournisseur='".$idFournisseur."' ";
                    $resT2 = mysql_query($sqlT2) or die ("persoonel requête B".mysql_error());
                    $versement = mysql_fetch_assoc($resT2);
                    if($versement!=null){
                        if ($i == $max)
                        {
                            $pdf->AddPage();
            
                            $image='';
                            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                                $image='pharmacie.png'; 
                            }
                            else{
                                $image='shop.png';
                            }
                            $pdf-> SetFont("Arial","B",10);
                            $pdf-> Image('images/'.$image,5,5,20,20);
                    
                            $pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 160, 8, '1/' . $nb_page, 0, 0, 'C');
                    
                            $num_fact = "Operations du ".$dateDebutA." au ".$dateFinA ;
                            $pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(120, 15, 85, 9, "DF");
                            $pdf->SetXY( 120, 15 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 85, 8, $num_fact, 0, 0, 'C');
                    
                                    // observations
                            $pdf->SetFont( "Arial", "BU", 16 ); $pdf->SetXY( 25, 9 ) ; $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, $_SESSION["labelB"], 0, "L");
                            // $pdf->SetFont( "Arial", "", 11 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, '('.$_SESSION["type"].' & '.$_SESSION["categorie"].')', 0, "L");
                            $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, 'Adresse : '.$_SESSION["adresseB"], 0, "L");
                            $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 17 ) ; $pdf->MultiCell(190, 4, 'Telephone : '.$_SESSION["telBoutique"], 0, "L");
                    
                            $sql2="SELECT * FROM `".$nomtableFournisseur."` where idFournisseur=".$idFournisseur;
                            $res2 = mysql_query($sql2) or die ("persoonel requête 1".mysql_error());
                            $fournisseur = mysql_fetch_array($res2);
                    
                            $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 38 ) ; $pdf->MultiCell(190, 4, 'Nom :', 0, "L");
                            $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 132, 38 ) ; $pdf->MultiCell(190, 4, $fournisseur["nomFournisseur"], 0, "L");
                            $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 43 ) ; $pdf->MultiCell(190, 4, 'Adresse :', 0, "L");
                            $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 137, 43 ) ; $pdf->MultiCell(190, 4, $fournisseur["adresseFournisseur"], 0, "L");
                            $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 48 ) ; $pdf->MultiCell(190, 4, 'Telephone :', 0, "L");
                            $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 141, 48 ) ; $pdf->MultiCell(190, 4, $fournisseur["telephoneFournisseur"], 0, "L");
                            //$pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 53 ) ; $pdf->MultiCell(190, 4, 'Telephone :', 0, "L");
                            //$pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 141, 53 ) ; $pdf->MultiCell(190, 4, $fournisseur["telephone"], 0, "L");
                    
                            $y_axis=65;
                            
                            if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
                                $pdf->SetFillColor(192);
                                $pdf->SetFont('Arial','B',12);
                                $pdf->SetY($y_axis_initial);
                                $pdf->SetX(10);
                                $pdf->Cell(22,6,'Date',1,0,'C',1);
                                $pdf->Cell(33,6,'Numero',1,0,'C',1);
                                $pdf->Cell(75,6,'Libelle',1,0,'C',1);
                                $pdf->Cell(30,6,'Montant BL',1,0,'C',1);
                                $pdf->Cell(30,6,'Versement',1,0,'C',1);
                            }
                            else{
                                //print column titles
                                $pdf->SetFillColor(192);
                                $pdf->SetFont('Arial','B',12);
                                $pdf->SetY($y_axis_initial);
                                $pdf->SetX(10);
                                $pdf->Cell(22,6,'Date',1,0,'C',1);
                                $pdf->Cell(33,6,'Numero',1,0,'C',1);
                                $pdf->Cell(75,6,'Libelle',1,0,'C',1);
                                $pdf->Cell(30,6,'Montant BL',1,0,'C',1);
                                $pdf->Cell(30,6,'Versement',1,0,'C',1);
                            }
                            
                            //Go to next row
                            $y_axis = $y_axis + $row_height;
                            
                            //Set $i variable to 0 (first row)
                            $i = 0;
            
                        }

                        $pdf->SetY($y_axis);
                        $pdf->SetFillColor(20,210,230);
                        $pdf->SetX(10);
                        $pdf->SetFont('Arial','B',8);
                        $pdf->Cell(22,6,$versement['dateVersement'],1,0,'C',1);
                        $pdf->Cell(33,6,'VERSEMENT : #'.$versement['idVersement'],1,0,'C',1);
                        $pdf->Cell(75,6,$versement['paiement'],1,0,'C',1);
                        $pdf->Cell(30,6,'',1,0,'C',1);
                        $pdf->Cell(30,6,number_format($versement['montant'], 2, ',', ' '),1,0,'C',1);

                        $y_axis = $y_axis + $row_height;
                        $i = $i + 1;
                        if($i==10){
                            $p = $p + 1;
                        }
                        $l = $l + 1;
                    
                    }
                }
                $c = $c + 1;
            }

            // si derniere page alors afficher total
            $pdf->SetY($y_axis);
            $pdf->SetFillColor(192);
            //$pdf->SetTextColor(255,255,255);
            $sql12="SELECT SUM(montantBl) FROM `".$nomtableBl."` where idFournisseur=".$idFournisseur." AND dateBl BETWEEN '".$dateDebut."' AND '".$dateFin."' ";
            $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
            $TotalB = mysql_fetch_array($res12) ; 

            $sql13="SELECT SUM(montant) FROM `".$nomtableVersement."` where idFournisseur=".$idFournisseur." AND dateVersement BETWEEN '".$dateDebut."' AND '".$dateFin."' ";
            $res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());
            $TotalV = mysql_fetch_array($res13) ;

            $pdf->SetFont('Arial','B',9);
            //$pdf->SetFont( "Arial", "B", 10 );
            $pdf->SetX(115);
            $pdf->Cell(35,8,'Total BL ',1,0,'L',1);
            $pdf->Cell(50,8,number_format($TotalB[0], 2, ',', ' ').' f cfa',1,1,'C',0);
            $pdf->SetX(115);
            $pdf->Cell(35,8,'Total Versements ',1,0,'L',1);
            $pdf->Cell(50,8,number_format($TotalV[0], 2, ',', ' ').' f cfa',1,1,'C',0);
            $pdf->SetX(115);
            $pdf->Cell(35,8,'Total a verser',1,0,'L',1);
            $pdf->Cell(50,8,number_format(($TotalB[0] - $TotalV[0]), 2, ',', ' ').' f cfa',1,1,'C',0); 
        }
        else{
            $pdf->SetFont( "Arial", "BU", 12); $pdf->SetXY( 90, 100 ) ; $pdf->Cell($pdf->GetStringWidth("Aucun BL"), 0, 'Aucun BL', 0, "L");
        }


        $pdf->Output("I", '1');

    }
}
else{
    if (isset($_POST['idFournisseur'])) {

        $dateDebut=htmlspecialchars(trim($_POST['dateDebut']));
        $debutDetails=explode("-", $dateDebut);
        $dateDebutA=$debutDetails[2].'-'.$debutDetails[1].'-'.$debutDetails[0];
        $dateFin=htmlspecialchars(trim($_POST['dateFin']));
        $finDetails=explode("-", $dateFin);
        $dateFinA=$finDetails[2].'-'.$finDetails[1].'-'.$finDetails[0];
        $idFournisseur=htmlspecialchars(trim($_POST['idFournisseur']));

        /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
                $sqlC="SELECT
                COUNT(*) AS total
            FROM
            (SELECT b.idFournisseur FROM `".$nomtableBl."` b where b.idFournisseur='".$idFournisseur."' AND b.dateBl BETWEEN '".$dateDebut."' AND '".$dateFin."'
            UNION ALL
            SELECT v.idFournisseur FROM `".$nomtableVersement."` v where v.idFournisseur='".$idFournisseur."'  AND v.dateVersement BETWEEN '".$dateDebut."' AND '".$dateFin."'
            ) AS a ";
            $resC = mysql_query($sqlC) or die ("persoonel requête 1".mysql_error());
            $nbre = mysql_fetch_array($resC) ;
        /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/

        /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
            $sqlP1="SELECT codeBl
            FROM
            (SELECT CONCAT(b.dateBl,'',b.heureBl,'+',b.idBl,'+1')  AS codeBl  FROM `".$nomtableBl."` b where b.idFournisseur='".$idFournisseur."' AND b.dateBl BETWEEN '".$dateDebut."' AND '".$dateFin."'
            UNION 
            SELECT CONCAT(v.dateVersement,'',v.heureVersement,'+',v.idVersement,'+2')  AS codeBl FROM `".$nomtableVersement."` v where v.idFournisseur='".$idFournisseur."'  AND v.dateVersement BETWEEN '".$dateDebut."' AND '".$dateFin."'
            ) AS a ORDER BY codeBl DESC  ";
            $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
        /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/


        //Create new pdf file
        $pdf=new FPDF();

        //Disable automatic page break
        $pdf->SetAutoPageBreak(false);

        //Add first page
        $pdf->AddPage();

        //set initial y axis position per page
        $y_axis_initial = 65;

        //Set Row Height
        $row_height = 6;

        $y_axis=65;

        //Set maximum rows per page
        $max = 30;
        $i = 0;
        $p=1;

        $nb_page=ceil($nbre[0]/$max);
        $image='';
        if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
            $image='pharmacie.png'; 
        }
        else{
            $image='shop.png';
        }
        $pdf-> SetFont("Arial","B",10);
        $pdf-> Image('images/'.$image,5,5,20,20);

        $pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 160, 8, '1/' . $nb_page, 0, 0, 'C');

        $num_fact = "Operations du ".$dateDebutA." au ".$dateFinA ;
        $pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(120, 15, 85, 9, "DF");
        $pdf->SetXY( 120, 15 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 85, 8, $num_fact, 0, 0, 'C');

                // observations
        $pdf->SetFont( "Arial", "BU", 16 ); $pdf->SetXY( 25, 9 ) ; $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, $_SESSION["labelB"], 0, "L");
        // $pdf->SetFont( "Arial", "", 11 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, '('.$_SESSION["type"].' & '.$_SESSION["categorie"].')', 0, "L");
        $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, 'Adresse : '.$_SESSION["adresseB"], 0, "L");
        $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 17 ) ; $pdf->MultiCell(190, 4, 'Telephone : '.$_SESSION["telBoutique"], 0, "L");

        $sql2="SELECT * FROM `".$nomtableFournisseur."` where idFournisseur=".$idFournisseur;
        $res2 = mysql_query($sql2) or die ("persoonel requête 1".mysql_error());
        $fournisseur = mysql_fetch_array($res2);

        $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 38 ) ; $pdf->MultiCell(190, 4, 'Nom :', 0, "L");
        $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 132, 38 ) ; $pdf->MultiCell(190, 4, $fournisseur["nomFournisseur"], 0, "L");
        $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 43 ) ; $pdf->MultiCell(190, 4, 'Adresse :', 0, "L");
        $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 137, 43 ) ; $pdf->MultiCell(190, 4, $fournisseur["adresseFournisseur"], 0, "L");
        $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 48 ) ; $pdf->MultiCell(190, 4, 'Telephone :', 0, "L");
        $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 141, 48 ) ; $pdf->MultiCell(190, 4, $fournisseur["telephoneFournisseur"], 0, "L");
        //$pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 53 ) ; $pdf->MultiCell(190, 4, 'Telephone :', 0, "L");
        //$pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 141, 53 ) ; $pdf->MultiCell(190, 4, $fournisseur["telephone"], 0, "L");


        if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
            $pdf->SetFillColor(192);
            $pdf->SetFont('Arial','B',12);
            $pdf->SetY($y_axis_initial);
            $pdf->SetX(10);
            $pdf->Cell(22,6,'Date',1,0,'C',1);
            $pdf->Cell(33,6,'Numero',1,0,'C',1);
            $pdf->Cell(75,6,'Libelle',1,0,'C',1);
            $pdf->Cell(30,6,'Montant BL',1,0,'C',1);
            $pdf->Cell(30,6,'Versement',1,0,'C',1);
        }
        else{
            //print column titles
            $pdf->SetFillColor(192);
            $pdf->SetFont('Arial','B',12);
            $pdf->SetY($y_axis_initial);
            $pdf->SetX(10);
            $pdf->Cell(22,6,'Date',1,0,'C',1);
            $pdf->Cell(33,6,'Numero',1,0,'C',1);
            $pdf->Cell(75,6,'Libelle',1,0,'C',1);
            $pdf->Cell(30,6,'Montant BL',1,0,'C',1);
            $pdf->Cell(30,6,'Versement',1,0,'C',1);
            // $pdf->Cell(30,6,'Solde',1,0,'C',1);
        }


        $y_axis = $y_axis + $row_height;
        //Select the Products you want to show in your PDF file
        $c=0;
        $l=1;
        $idAvant=0;
        $totalTVA=0;
        if(mysql_num_rows($resP1)){
            while ($bons = mysql_fetch_array($resP1)) { 
                $bon = explode("+", $bons['codeBl']);
                //echo $bon[1];
                if($bon[2]==1){
                    $sqlT1="SELECT * FROM `".$nomtableBl."` where idBl='".$bon[1]."' AND idFournisseur='".$idFournisseur."'  ";
                    $resT1 = mysql_query($sqlT1) or die ("persoonel requête A".mysql_error());
                    $bl = mysql_fetch_assoc($resT1);
                    if($bl!=null){
                        if ($i == $max)
                        {
                            $pdf->AddPage();
            
                            $image='';
                            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                                $image='pharmacie.png'; 
                            }
                            else{
                                $image='shop.png';
                            }
                            $pdf-> SetFont("Arial","B",10);
                            $pdf-> Image('images/'.$image,5,5,20,20);
                    
                            $pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 160, 8, '1/' . $nb_page, 0, 0, 'C');
                    
                            $num_fact = "Operations du ".$dateDebutA." au ".$dateFinA ;
                            $pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(120, 15, 85, 9, "DF");
                            $pdf->SetXY( 120, 15 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 85, 8, $num_fact, 0, 0, 'C');
                    
                                    // observations
                            $pdf->SetFont( "Arial", "BU", 16 ); $pdf->SetXY( 25, 9 ) ; $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, $_SESSION["labelB"], 0, "L");
                            // $pdf->SetFont( "Arial", "", 11 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, '('.$_SESSION["type"].' & '.$_SESSION["categorie"].')', 0, "L");
                            $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, 'Adresse : '.$_SESSION["adresseB"], 0, "L");
                            $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 17 ) ; $pdf->MultiCell(190, 4, 'Telephone : '.$_SESSION["telBoutique"], 0, "L");
                    
                            $sql2="SELECT * FROM `".$nomtableFournisseur."` where idFournisseur=".$idFournisseur;
                            $res2 = mysql_query($sql2) or die ("persoonel requête 1".mysql_error());
                            $fournisseur = mysql_fetch_array($res2);
                    
                            $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 38 ) ; $pdf->MultiCell(190, 4, 'Nom :', 0, "L");
                            $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 132, 38 ) ; $pdf->MultiCell(190, 4, $fournisseur["nomFournisseur"], 0, "L");
                            $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 43 ) ; $pdf->MultiCell(190, 4, 'Adresse :', 0, "L");
                            $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 137, 43 ) ; $pdf->MultiCell(190, 4, $fournisseur["adresseFournisseur"], 0, "L");
                            $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 48 ) ; $pdf->MultiCell(190, 4, 'Telephone :', 0, "L");
                            $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 141, 48 ) ; $pdf->MultiCell(190, 4, $fournisseur["telephoneFournisseur"], 0, "L");
                            //$pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 53 ) ; $pdf->MultiCell(190, 4, 'Telephone :', 0, "L");
                            //$pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 141, 53 ) ; $pdf->MultiCell(190, 4, $fournisseur["telephone"], 0, "L");
                    
                            $y_axis=65;
                    
                            if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
                                $pdf->SetFillColor(192);
                                $pdf->SetFont('Arial','B',12);
                                $pdf->SetY($y_axis_initial);
                                $pdf->SetX(10);
                                $pdf->Cell(22,6,'Date',1,0,'C',1);
                                $pdf->Cell(33,6,'Numero',1,0,'C',1);
                                $pdf->Cell(75,6,'Libelle',1,0,'C',1);
                                $pdf->Cell(30,6,'Montant BL',1,0,'C',1);
                                $pdf->Cell(30,6,'Versement',1,0,'C',1);
                            }
                            else{
                                //print column titles
                                $pdf->SetFillColor(192);
                                $pdf->SetFont('Arial','B',12);
                                $pdf->SetY($y_axis_initial);
                                $pdf->SetX(10);
                                $pdf->Cell(22,6,'Date',1,0,'C',1);
                                $pdf->Cell(33,6,'Numero',1,0,'C',1);
                                $pdf->Cell(75,6,'Libelle',1,0,'C',1);
                                $pdf->Cell(30,6,'Montant BL',1,0,'C',1);
                                $pdf->Cell(30,6,'Versement',1,0,'C',1);
                            }
                            
                            //Go to next row
                            $y_axis = $y_axis + $row_height;
                            
                            //Set $i variable to 0 (first row)
                            $i = 0;
            
                        }

                        $pdf->SetY($y_axis);
                        $pdf->SetFillColor(255,255,255);
                        $pdf->SetFont('Arial','B',8);
                        $pdf->SetX(10);
                        $pdf->Cell(22,6,$bl['dateBl'],1,0,'C',1);
                        $pdf->Cell(33,6,'BL : '.$bl['numeroBl'],1,0,'C',1);
                        $pdf->Cell(75,6,$bl['description'],1,0,'C',1);
                        $pdf->Cell(30,6,number_format($bl['montantBl'], 2, ',', ' '),1,0,'C',1);
                        $pdf->Cell(30,6,'',1,0,'C',1);

                        $y_axis = $y_axis + $row_height;
                        $i = $i + 1;
                        if($i==10){
                            $p = $p + 1;
                        }
                        $l = $l + 1;
                    }
                }
                else if($bon[2]==2){
                    $sqlT2="SELECT * FROM `".$nomtableVersement."` where idVersement='".$bon[1]."' AND idFournisseur='".$idFournisseur."' ";
                    $resT2 = mysql_query($sqlT2) or die ("persoonel requête B".mysql_error());
                    $versement = mysql_fetch_assoc($resT2);
                    if($versement!=null){
                        if ($i == $max)
                        {
                            $pdf->AddPage();
            
                            $image='';
                            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                                $image='pharmacie.png'; 
                            }
                            else{
                                $image='shop.png';
                            }
                            $pdf-> SetFont("Arial","B",10);
                            $pdf-> Image('images/'.$image,5,5,20,20);
                    
                            $pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 160, 8, '1/' . $nb_page, 0, 0, 'C');
                    
                            $num_fact = "Operations du ".$dateDebutA." au ".$dateFinA ;
                            $pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(120, 15, 85, 9, "DF");
                            $pdf->SetXY( 120, 15 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 85, 8, $num_fact, 0, 0, 'C');
                    
                                    // observations
                            $pdf->SetFont( "Arial", "BU", 16 ); $pdf->SetXY( 25, 9 ) ; $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, $_SESSION["labelB"], 0, "L");
                            // $pdf->SetFont( "Arial", "", 11 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, '('.$_SESSION["type"].' & '.$_SESSION["categorie"].')', 0, "L");
                            $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, 'Adresse : '.$_SESSION["adresseB"], 0, "L");
                            $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 17 ) ; $pdf->MultiCell(190, 4, 'Telephone : '.$_SESSION["telBoutique"], 0, "L");
                    
                            $sql2="SELECT * FROM `".$nomtableFournisseur."` where idFournisseur=".$idFournisseur;
                            $res2 = mysql_query($sql2) or die ("persoonel requête 1".mysql_error());
                            $fournisseur = mysql_fetch_array($res2);
                    
                            $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 38 ) ; $pdf->MultiCell(190, 4, 'Nom :', 0, "L");
                            $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 132, 38 ) ; $pdf->MultiCell(190, 4, $fournisseur["nomFournisseur"], 0, "L");
                            $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 43 ) ; $pdf->MultiCell(190, 4, 'Adresse :', 0, "L");
                            $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 137, 43 ) ; $pdf->MultiCell(190, 4, $fournisseur["adresseFournisseur"], 0, "L");
                            $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 48 ) ; $pdf->MultiCell(190, 4, 'Telephone :', 0, "L");
                            $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 141, 48 ) ; $pdf->MultiCell(190, 4, $fournisseur["telephoneFournisseur"], 0, "L");
                            //$pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 53 ) ; $pdf->MultiCell(190, 4, 'Telephone :', 0, "L");
                            //$pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 141, 53 ) ; $pdf->MultiCell(190, 4, $fournisseur["telephone"], 0, "L");
                    
                            $y_axis=65;

                            if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
                                $pdf->SetFillColor(192);
                                $pdf->SetFont('Arial','B',12);
                                $pdf->SetY($y_axis_initial);
                                $pdf->SetX(10);
                                $pdf->Cell(22,6,'Date',1,0,'C',1);
                                $pdf->Cell(33,6,'Numero',1,0,'C',1);
                                $pdf->Cell(75,6,'Libelle',1,0,'C',1);
                                $pdf->Cell(30,6,'Montant BL',1,0,'C',1);
                                $pdf->Cell(30,6,'Versement',1,0,'C',1);
                            }
                            else{
                                //print column titles
                                $pdf->SetFillColor(192);
                                $pdf->SetFont('Arial','B',12);
                                $pdf->SetY($y_axis_initial);
                                $pdf->SetX(10);
                                $pdf->Cell(22,6,'Date',1,0,'C',1);
                                $pdf->Cell(33,6,'Numero',1,0,'C',1);
                                $pdf->Cell(75,6,'Libelle',1,0,'C',1);
                                $pdf->Cell(30,6,'Montant BL',1,0,'C',1);
                                $pdf->Cell(30,6,'Versement',1,0,'C',1);
                            }
                            
                            //Go to next row
                            $y_axis = $y_axis + $row_height;
                            
                            //Set $i variable to 0 (first row)
                            $i = 0;
            
                        }

                        $pdf->SetY($y_axis);
                        $pdf->SetFillColor(20,210,230);
                        $pdf->SetX(10);
                        $pdf->SetFont('Arial','B',8);
                        $pdf->Cell(22,6,$versement['dateVersement'],1,0,'C',1);
                        $pdf->Cell(33,6,'VERSEMENT : #'.$versement['idVersement'],1,0,'C',1);
                        $pdf->Cell(75,6,$versement['paiement'],1,0,'C',1);
                        $pdf->Cell(30,6,'',1,0,'C',1);
                        $pdf->Cell(30,6,number_format($versement['montant'], 2, ',', ' '),1,0,'C',1);

                        $y_axis = $y_axis + $row_height;
                        $i = $i + 1;
                        if($i==10){
                            $p = $p + 1;
                        }
                        $l = $l + 1;
                    
                    }
                }
                $c = $c + 1;
            }

            // si derniere page alors afficher total
            $pdf->SetY($y_axis);
            $pdf->SetFillColor(192);
            //$pdf->SetTextColor(255,255,255);
            $sql12="SELECT SUM(montantBl) FROM `".$nomtableBl."` where idFournisseur=".$idFournisseur." AND dateBl BETWEEN '".$dateDebut."' AND '".$dateFin."' ";
            $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
            $TotalB = mysql_fetch_array($res12) ; 

            $sql13="SELECT SUM(montant) FROM `".$nomtableVersement."` where idFournisseur=".$idFournisseur." AND dateVersement BETWEEN '".$dateDebut."' AND '".$dateFin."' ";
            $res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());
            $TotalV = mysql_fetch_array($res13) ;

            $pdf->SetFont('Arial','B',9);
            //$pdf->SetFont( "Arial", "B", 10 );
            $pdf->SetX(115);
            $pdf->Cell(35,8,'Total BL ',1,0,'L',1);
            $pdf->Cell(50,8,number_format($TotalB[0], 2, ',', ' ').' f cfa',1,1,'C',0);
            $pdf->SetX(115);
            $pdf->Cell(35,8,'Total Versements ',1,0,'L',1);
            $pdf->Cell(50,8,number_format($TotalV[0], 2, ',', ' ').' f cfa',1,1,'C',0);
            $pdf->SetX(115);
            $pdf->Cell(35,8,'Total a verser',1,0,'L',1);
            $pdf->Cell(50,8,number_format(($TotalB[0] - $TotalV[0]), 2, ',', ' ').' f cfa',1,1,'C',0); 
        }
        else{
            $pdf->SetFont( "Arial", "BU", 12); $pdf->SetXY( 90, 100 ) ; $pdf->Cell($pdf->GetStringWidth("Aucun BL"), 0, 'Aucun BL', 0, "L");
        }


        $pdf->Output("I", '1');

    }
}

?>