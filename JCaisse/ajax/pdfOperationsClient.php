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
    if (isset($_POST['idClient'])) {

        $dateDebut=htmlspecialchars(trim($_POST['dateDebut']));
        $debutDetails=explode("-", $dateDebut);
        $dateDebutA=$debutDetails[2].'-'.$debutDetails[1].'-'.$debutDetails[0];
        $dateFin=htmlspecialchars(trim($_POST['dateFin']));
        $finDetails=explode("-", $dateFin);
        $dateFinA=$finDetails[2].'-'.$finDetails[1].'-'.$finDetails[0];
        $idClient=htmlspecialchars(trim($_POST['idClient']));

        /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
            $sqlC="SELECT
                COUNT(*) AS total
            FROM
            (SELECT p.idClient FROM `".$nomtablePagnet."` p where p.idClient='".$idClient."' AND p.verrouiller=1 AND (p.type=0 || p.type=30) AND (CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."')  or (p.datepagej BETWEEN '".$dateDebut."' AND '".$dateFin."')
            UNION ALL
            SELECT v.idClient FROM `".$nomtableVersement."` v where v.idClient='".$idClient."' AND (CONCAT(CONCAT(SUBSTR(v.dateVersement,7, 10),'',SUBSTR(v.dateVersement,3, 4)),'',SUBSTR(v.dateVersement,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') or (v.dateVersement BETWEEN '".$dateDebut."' AND '".$dateFin."')
            ) AS a ";
            $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
            $nbre = mysql_fetch_array($resC) ;
        /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/

        /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
            $sqlP1="SELECT codePagnet
            FROM
            (SELECT CONCAT(SUBSTR(p.datepagej,7, 4),'',SUBSTR(p.datepagej,4, 2),'',SUBSTR(p.datepagej,1, 2),'',p.heurePagnet,'+',p.idPagnet,'+1') AS codePagnet FROM `".$nomtablePagnet."` p where p.idClient='".$idClient."' AND p.verrouiller=1 AND (p.type=0 || p.type=30)  AND (CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."')  or (p.datepagej BETWEEN '".$dateDebut."' AND '".$dateFin."')
            UNION ALL
            SELECT CONCAT(SUBSTR(v.dateVersement,7, 4),'',SUBSTR(v.dateVersement,4, 2),'',SUBSTR(v.dateVersement,1, 2),'',v.heureVersement,'+',v.idVersement,'+2') AS codeVersement FROM `".$nomtableVersement."` v where v.idClient='".$idClient."' AND  (CONCAT(CONCAT(SUBSTR(v.dateVersement,7, 10),'',SUBSTR(v.dateVersement,3, 4)),'',SUBSTR(v.dateVersement,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') or (v.dateVersement BETWEEN '".$dateDebut."' AND '".$dateFin."')
            ) AS a ORDER BY codePagnet DESC  ";
            $resP1 = mysql_query($sqlP1) or die ("persoonel requête 20".mysql_error());
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
  
        if( strlen($_SESSION["descriptionB"]) <= 30){
      
            $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 23 ) ; $pdf->MultiCell(190, 4, ''.$_SESSION["descriptionB"], 0, "L");
    
    
        }
    
        else{
    
            $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 23 ) ; $pdf->MultiCell(190, 4, ''.substr($_SESSION["descriptionB"],0,30), 0, "L");
    
            $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 26 ) ; $pdf->MultiCell(190, 4, substr($_SESSION["descriptionB"],30,40), 0, "L");
    
    
        }
        
        if($_SESSION['RegistreCom']!='' && $_SESSION['RegistreCom']!=null){

            $pdf->SetXY( 25, 30 ); 

            $pdf->Cell( $pdf->GetPageWidth(), 5, "REGISTRE DE COMMERCE : ".$_SESSION['RegistreCom'], 0, 0, 'L');

        }

        if($_SESSION['Ninea']!='' && $_SESSION['Ninea']!=null){

            $pdf->SetXY( 25, 35 );

            $pdf->Cell( $pdf->GetPageWidth(), 5, "NINEA : ".$_SESSION['Ninea'], 0, 0, 'L');

        }


        $sql2="SELECT * FROM `".$nomtableClient."` where idClient=".$idClient;
        $res2 = mysql_query($sql2) or die ("persoonel requête 1".mysql_error());
        $client = mysql_fetch_array($res2);

        $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 38 ) ; $pdf->MultiCell(190, 4, 'Nom :', 0, "L");
        $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 132, 38 ) ; $pdf->MultiCell(190, 4, $client["nom"], 0, "L");
        $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 43 ) ; $pdf->MultiCell(190, 4, 'Prenom :', 0, "L");
        $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 137, 43 ) ; $pdf->MultiCell(190, 4, $client["prenom"], 0, "L");
        $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 48 ) ; $pdf->MultiCell(190, 4, 'Adresse :', 0, "L");
        $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 137, 48 ) ; $pdf->MultiCell(190, 4, $client["adresse"], 0, "L");
        $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 53 ) ; $pdf->MultiCell(190, 4, 'Telephone :', 0, "L");
        $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 141, 53 ) ; $pdf->MultiCell(190, 4, $client["telephone"], 0, "L");


        //print column titles
        $pdf->SetFillColor(192);
        $pdf->SetFont('Arial','B',12);
        $pdf->SetY($y_axis_initial);
        $pdf->SetX(5);
        $pdf->Cell(75,6,'Designation',1,0,'C',1);
        $pdf->Cell(15,6,'Qte',1,0,'C',1);
        $pdf->Cell(20,6,'Prix',1,0,'C',1);
        $pdf->Cell(20,6,'Prix TT',1,0,'C',1);
        $pdf->Cell(20,6,'Taux %',1,0,'C',1);
        $pdf->Cell(16,6,'Remise',1,0,'C',1);
        $pdf->Cell(17,6,'Date',1,0,'C',1);
        $pdf->Cell(17,6,'Facture',1,0,'C',1);


        $y_axis = $y_axis + $row_height;

        //Select the Products you want to show in your PDF file
        $c=0;
        $l=1;
        $idAvant=0;
        $totalTVA=0;
        if(mysql_num_rows($resP1)){
            while ($bons = mysql_fetch_array($resP1)) { 

                $bon = explode("+", $bons['codePagnet']);
                if($bon[2]==1){
                    $sqlT1="SELECT * FROM `".$nomtablePagnet."` where idPagnet='".$bon[1]."' AND idClient='".$idClient."' ";
                    $resT1 = mysql_query($sqlT1) or die ("persoonel requête 2".mysql_error());
                    $pagnet = mysql_fetch_assoc($resT1);
                    if($pagnet!=null){
                        $sql8="SELECT * 
                        FROM `".$nomtableLigne."` l
                        WHERE l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne ASC";
                        $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
                        $m=0;
                        while ($ligne = mysql_fetch_array($res8)) {
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
                
                                $pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 160, 8, $p . '/' . $nb_page, 0, 0, 'C');
                
                                $num_fact = "Operations du ".$dateDebutA." au ".$dateFinA ;
                                $pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(120, 15, 85, 9, "DF");
                                $pdf->SetXY( 120, 15 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 85, 8, $num_fact, 0, 0, 'C');
                                
                                        // observations
                                $pdf->SetFont( "Arial", "BU", 16 ); $pdf->SetXY( 25, 9 ) ; $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, $_SESSION["labelB"], 0, "L");
                                // $pdf->SetFont( "Arial", "", 11 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, '('.$_SESSION["type"].' & '.$_SESSION["categorie"].')', 0, "L");
                                $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, 'Adresse : '.$_SESSION["adresseB"], 0, "L");
                                $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 17 ) ; $pdf->MultiCell(190, 4, 'Telephone : '.$_SESSION["telBoutique"], 0, "L");
                                
                                if( strlen($_SESSION["descriptionB"]) <= 30){
                            
                                    $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 23 ) ; $pdf->MultiCell(190, 4, ''.$_SESSION["descriptionB"], 0, "L");
                            
                            
                                }
                            
                                else{
                            
                                    $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 23 ) ; $pdf->MultiCell(190, 4, ''.substr($_SESSION["descriptionB"],0,30), 0, "L");
                            
                                    $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 26 ) ; $pdf->MultiCell(190, 4, substr($_SESSION["descriptionB"],30,40), 0, "L");
                            
                            
                                }
                                
                                if($_SESSION['RegistreCom']!='' && $_SESSION['RegistreCom']!=null){

                                    $pdf->SetXY( 25, 30 ); 

                                    $pdf->Cell( $pdf->GetPageWidth(), 5, "REGISTRE DE COMMERCE : ".$_SESSION['RegistreCom'], 0, 0, 'L');

                                }

                                if($_SESSION['Ninea']!='' && $_SESSION['Ninea']!=null){

                                    $pdf->SetXY( 25, 35 );

                                    $pdf->Cell( $pdf->GetPageWidth(), 5, "NINEA : ".$_SESSION['Ninea'], 0, 0, 'L');

                                }
                                
                                $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 38 ) ; $pdf->MultiCell(190, 4, 'Nom :', 0, "L");
                                $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 132, 38 ) ; $pdf->MultiCell(190, 4, $client["nom"], 0, "L");
                                $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 43 ) ; $pdf->MultiCell(190, 4, 'Prenom :', 0, "L");
                                $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 137, 43 ) ; $pdf->MultiCell(190, 4, $client["prenom"], 0, "L");
                                $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 48 ) ; $pdf->MultiCell(190, 4, 'Adresse :', 0, "L");
                                $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 137, 48 ) ; $pdf->MultiCell(190, 4, $client["adresse"], 0, "L");
                                $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 53 ) ; $pdf->MultiCell(190, 4, 'Telephone :', 0, "L");
                                $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 141, 53 ) ; $pdf->MultiCell(190, 4, $client["telephone"], 0, "L");
                
                                $y_axis=65;
                                
                                $pdf->SetFillColor(192);
                                $pdf->SetFont('Arial','B',12);
                                $pdf->SetY($y_axis_initial);
                                $pdf->SetX(5);
                                $pdf->Cell(75,6,'Designation',1,0,'C',1);
                                $pdf->Cell(15,6,'Qte',1,0,'C',1);
                                $pdf->Cell(20,6,'Prix',1,0,'C',1);
                                $pdf->Cell(20,6,'Prix TT',1,0,'C',1);
                                $pdf->Cell(20,6,'Taux %',1,0,'C',1);
                                $pdf->Cell(16,6,'Remise',1,0,'C',1);
                                $pdf->Cell(17,6,'Date',1,0,'C',1);
                                $pdf->Cell(17,6,'Facture',1,0,'C',1);
                                
                                //Go to next row
                                $y_axis = $y_axis + $row_height;
                                
                                //Set $i variable to 0 (first row)
                                $i = 0;
                
                            }
                
                            $sqlD="select * from `".$nomtableDesignation."` where idDesignation='".$ligne["idDesignation"]."' ";
                            $resD=mysql_query($sqlD);
                            $produit = mysql_fetch_assoc($resD);
                
                            $designation = strtoupper($ligne['designation']);
                            $quantite = $ligne['quantite'];
                            $prixU = number_format($ligne['prixPublic'], 0, ',', ' ');
                            $unite = $ligne['forme'];
                            $prixT = number_format($ligne['prixtotal'], 0, ',', ' ');
                            $remise = $pagnet['remise'];
                
                            if($c%2==0){
                                $pdf->SetY($y_axis);
                                $pdf->SetFillColor(255,255,255);
                                //$pdf->SetTextColor(255,255,255);
                                $pdf->SetX(5);
                                $pdf->SetFont('Arial','B',8);
                                $pdf->Cell(75,6,$designation,1,0,'L',1);
                                $pdf->Cell(15,6,$quantite,1,0,'C',1);
                                $pdf->Cell(20,6,$prixU,1,0,'C',1);
                                $pdf->Cell(20,6,$prixT,1,0,'C',1);
                                $pdf->Cell(20,6,'(0%) '.$prixT,1,0,'L',1);
                                if($idAvant==$pagnet['idPagnet']){
                                    if($i==($max - 1) || $nbre[0]==$l){
                                        $pdf->Cell(16,6,'  ',1,0,'C',0);
                                    }
                                    else{
                                        $pdf->Cell(16,6,'  ',0,0,'C',0);
                                    }
                                }
                                else{
                                    $pdf->Cell(16,6,$remise,1,0,'C',1);
                                }
                                $pdf->Cell(17,6,$pagnet['datepagej'],1,0,'C',1);
                                $pdf->Cell(17,6,'#'.$pagnet['idPagnet'],1,0,'C',1);
                            }
                            else{
                                $pdf->SetY($y_axis);
                                $pdf->SetFillColor(200,210,230);
                                //$pdf->SetTextColor(255,255,255);
                                $pdf->SetX(5);
                                $pdf->SetFont('Arial','B',8);
                                $pdf->Cell(75,6,$designation,1,0,'L',1);
                                $pdf->Cell(15,6,$quantite,1,0,'C',1);
                                $pdf->Cell(20,6,$prixU,1,0,'C',1);
                                $pdf->Cell(20,6,$prixT,1,0,'C',1);
                                $pdf->Cell(20,6,'(0%) '.$prixT,1,0,'L',1);
                                if($idAvant==$pagnet['idPagnet']){
                                    if($i==($max - 1) || $nbre[0]==$l){
                                        $pdf->Cell(16,6,'  ',1,0,'C',0);
                                    }
                                    else{
                                        $pdf->Cell(16,6,'  ',0,0,'C',0);
                                    }
                                }
                                else{
                                    $pdf->Cell(16,6,$remise,1,0,'C',1);
                                }
                                $pdf->Cell(17,6,$pagnet['datepagej'],1,0,'C',1);
                                $pdf->Cell(17,6,'#'.$pagnet['idPagnet'],1,0,'C',1);
                            }
                
                            //Go to next row
                            $y_axis = $y_axis + $row_height;
                            $i = $i + 1;
                            if($i==10){
                                $p = $p + 1;
                            }
                
                            $idAvant=$pagnet['idPagnet'];
                            $l = $l + 1; 
                            $m=$m + 1;                
                
                        }
                      
                    }
                }
                else if($bon[2]==2){
                    $sqlT2="SELECT * FROM `".$nomtableVersement."` where idVersement='".$bon[1]."' AND idClient='".$idClient."' ";
                    $resT2 = mysql_query($sqlT2) or die ("persoonel requête 2".mysql_error());
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
            
                            $pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 160, 8, $p . '/' . $nb_page, 0, 0, 'C');
            
                            $num_fact = "Operations du ".$dateDebutA." au ".$dateFinA ;
                            $pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(120, 15, 85, 9, "DF");
                            $pdf->SetXY( 120, 15 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 85, 8, $num_fact, 0, 0, 'C');
                            
                                    // observations
                            $pdf->SetFont( "Arial", "BU", 16 ); $pdf->SetXY( 25, 9 ) ; $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, $_SESSION["labelB"], 0, "L");
                            // $pdf->SetFont( "Arial", "", 11 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, '('.$_SESSION["type"].' & '.$_SESSION["categorie"].')', 0, "L");
                            $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, 'Adresse : '.$_SESSION["adresseB"], 0, "L");
                            $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 17 ) ; $pdf->MultiCell(190, 4, 'Telephone : '.$_SESSION["telBoutique"], 0, "L");
                            
                            if( strlen($_SESSION["descriptionB"]) <= 30){
                        
                                $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 23 ) ; $pdf->MultiCell(190, 4, ''.$_SESSION["descriptionB"], 0, "L");
                        
                        
                            }
                        
                            else{
                        
                                $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 23 ) ; $pdf->MultiCell(190, 4, ''.substr($_SESSION["descriptionB"],0,30), 0, "L");
                        
                                $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 26 ) ; $pdf->MultiCell(190, 4, substr($_SESSION["descriptionB"],30,40), 0, "L");
                        
                        
                            }
                            
                            if($_SESSION['RegistreCom']!='' && $_SESSION['RegistreCom']!=null){

                                $pdf->SetXY( 25, 30 ); 

                                $pdf->Cell( $pdf->GetPageWidth(), 5, "REGISTRE DE COMMERCE : ".$_SESSION['RegistreCom'], 0, 0, 'L');

                            }

                            if($_SESSION['Ninea']!='' && $_SESSION['Ninea']!=null){

                                $pdf->SetXY( 25, 35 );

                                $pdf->Cell( $pdf->GetPageWidth(), 5, "NINEA : ".$_SESSION['Ninea'], 0, 0, 'L');

                            }
                            
                            $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 38 ) ; $pdf->MultiCell(190, 4, 'Nom :', 0, "L");
                            $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 132, 38 ) ; $pdf->MultiCell(190, 4, $client["nom"], 0, "L");
                            $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 43 ) ; $pdf->MultiCell(190, 4, 'Prenom :', 0, "L");
                            $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 137, 43 ) ; $pdf->MultiCell(190, 4, $client["prenom"], 0, "L");
                            $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 48 ) ; $pdf->MultiCell(190, 4, 'Adresse :', 0, "L");
                            $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 137, 48 ) ; $pdf->MultiCell(190, 4, $client["adresse"], 0, "L");
                            $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 53 ) ; $pdf->MultiCell(190, 4, 'Telephone :', 0, "L");
                            $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 141, 53 ) ; $pdf->MultiCell(190, 4, $client["telephone"], 0, "L");
            
                            $y_axis=65;
                            
                            $pdf->SetFillColor(192);
                            $pdf->SetFont('Arial','B',12);
                            $pdf->SetY($y_axis_initial);
                            $pdf->SetX(5);
                            $pdf->Cell(75,6,'Designation',1,0,'C',1);
                            $pdf->Cell(15,6,'Qte',1,0,'C',1);
                            $pdf->Cell(20,6,'Prix',1,0,'C',1);
                            $pdf->Cell(20,6,'Prix TT',1,0,'C',1);
                            $pdf->Cell(20,6,'Taux %',1,0,'C',1);
                            $pdf->Cell(16,6,'Remise',1,0,'C',1);
                            $pdf->Cell(17,6,'Date',1,0,'C',1);
                            $pdf->Cell(17,6,'Facture',1,0,'C',1);
                            
                            //Go to next row
                            $y_axis = $y_axis + $row_height;
                            
                            //Set $i variable to 0 (first row)
                            $i = 0;
            
                        }

                        $pdf->SetY($y_axis);
                        $pdf->SetFillColor(20,210,230);
                        $pdf->SetX(5);
                        $pdf->SetFont('Arial','B',8);
                        $pdf->Cell(75,6,'VERSEMENT',1,0,'L',1);
                        $pdf->Cell(15,6,'1',1,0,'C',1);
                        $pdf->Cell(20,6,number_format($versement['montant'], 0, ',', ' '),1,0,'C',1);
                        $pdf->Cell(20,6,number_format($versement['montant'], 0, ',', ' '),1,0,'C',1);
                        $pdf->Cell(20,6,'(0%) '.number_format($versement['montant'], 0, ',', ' '),1,0,'L',1);
                        $pdf->Cell(16,6,'0',1,0,'C',1);
                        $pdf->Cell(17,6,$versement['dateVersement'],1,0,'C',1);
                        $pdf->Cell(17,6,'#'.$versement['idVersement'],1,0,'C',1);

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
            $sql12="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$idClient." AND verrouiller=1 AND type=0 AND (CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') or (datepagej BETWEEN '".$dateDebut."' AND '".$dateFin."') ";
            $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
            $TotalB = mysql_fetch_array($res12) ;
            $sql13="SELECT SUM(montant) FROM `".$nomtableVersement."` where idClient=".$idClient." AND  (CONCAT(CONCAT(SUBSTR(dateVersement,7, 10),'',SUBSTR(dateVersement,3, 4)),'',SUBSTR(dateVersement,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') or (dateVersement BETWEEN '".$dateDebut."' AND '".$dateFin."') ";
            $res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());
            $TotalV = mysql_fetch_array($res13) ;
            $pdf->SetFont('Arial','B',9);
            //$pdf->SetFont( "Arial", "B", 10 );
            $pdf->SetX(120);
            $pdf->Cell(35,8,'Montant Bons ',1,0,'L',1);
            $pdf->Cell(50,8,number_format($TotalB[0], 0, ',', ' ').' f cfa',1,1,'C',0);
            $pdf->SetX(120);
            $pdf->Cell(35,8,'Montant Versements ',1,0,'L',1);
            $pdf->Cell(50,8,number_format($TotalV[0], 0, ',', ' ').' f cfa',1,1,'C',0);
            $pdf->SetX(120);
            $pdf->Cell(35,8,'Montant a verser',1,0,'L',1);
            $pdf->Cell(50,8,number_format(($TotalB[0] - $TotalV[0]), 0, ',', ' ').' f cfa',1,1,'C',0); 
        }
        else{
            $pdf->SetFont( "Arial", "BU", 12); $pdf->SetXY( 90, 70 ) ; $pdf->Cell($pdf->GetStringWidth("Aucune Vente"), 0, 'Aucune Vente', 0, "L");
        }


        $pdf->Output("I", '1');

    }
}
else{
    if (isset($_POST['idClient'])) {

        $dateDebut=htmlspecialchars(trim($_POST['dateDebut']));
        $debutDetails=explode("-", $dateDebut);
        $dateDebutA=$debutDetails[2].'-'.$debutDetails[1].'-'.$debutDetails[0];
        $dateFin=htmlspecialchars(trim($_POST['dateFin']));
        $finDetails=explode("-", $dateFin);
        $dateFinA=$finDetails[2].'-'.$finDetails[1].'-'.$finDetails[0];
        $idClient=htmlspecialchars(trim($_POST['idClient']));

        /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
            $sqlC="SELECT
                COUNT(*) AS total
            FROM
            (SELECT p.idClient FROM `".$nomtablePagnet."` p where p.idClient='".$idClient."' AND p.verrouiller=1 AND (p.type=0 || p.type=30) AND (CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."')  or (p.datepagej BETWEEN '".$dateDebut."' AND '".$dateFin."')
            UNION ALL
            SELECT v.idClient FROM `".$nomtableVersement."` v where v.idClient='".$idClient."' AND (CONCAT(CONCAT(SUBSTR(v.dateVersement,7, 10),'',SUBSTR(v.dateVersement,3, 4)),'',SUBSTR(v.dateVersement,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') or (v.dateVersement BETWEEN '".$dateDebut."' AND '".$dateFin."')
            ) AS a ";
            $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
            $nbre = mysql_fetch_array($resC) ;
        /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/

        /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
            $sqlP1="SELECT codePagnet
            FROM
            (SELECT CONCAT(SUBSTR(p.datepagej,7, 4),'',SUBSTR(p.datepagej,4, 2),'',SUBSTR(p.datepagej,1, 2),'',p.heurePagnet,'+',p.idPagnet,'+1') AS codePagnet FROM `".$nomtablePagnet."` p where p.idClient='".$idClient."' AND p.verrouiller=1 AND (p.type=0 || p.type=30) AND (CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."')  or (p.datepagej BETWEEN '".$dateDebut."' AND '".$dateFin."')
            UNION ALL
            SELECT CONCAT(SUBSTR(v.dateVersement,7, 4),'',SUBSTR(v.dateVersement,4, 2),'',SUBSTR(v.dateVersement,1, 2),'',v.heureVersement,'+',v.idVersement,'+2') AS codeVersement FROM `".$nomtableVersement."` v where v.idClient='".$idClient."' AND  (CONCAT(CONCAT(SUBSTR(v.dateVersement,7, 10),'',SUBSTR(v.dateVersement,3, 4)),'',SUBSTR(v.dateVersement,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') or (v.dateVersement BETWEEN '".$dateDebut."' AND '".$dateFin."')
            ) AS a ORDER BY codePagnet DESC  ";
            $resP1 = mysql_query($sqlP1) or die ("persoonel requête 20".mysql_error());
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
        
        
        if( strlen($_SESSION["descriptionB"]) <= 30){
      
            $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 23 ) ; $pdf->MultiCell(190, 4, ''.$_SESSION["descriptionB"], 0, "L");
    
    
        }
    
        else{
    
            $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 23 ) ; $pdf->MultiCell(190, 4, ''.substr($_SESSION["descriptionB"],0,30), 0, "L");
    
            $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 26 ) ; $pdf->MultiCell(190, 4, substr($_SESSION["descriptionB"],30,40), 0, "L");
    
    
        }
        
        if($_SESSION['RegistreCom']!='' && $_SESSION['RegistreCom']!=null){

            $pdf->SetXY( 25, 30 ); 

            $pdf->Cell( $pdf->GetPageWidth(), 5, "REGISTRE DE COMMERCE : ".$_SESSION['RegistreCom'], 0, 0, 'L');

        }

        if($_SESSION['Ninea']!='' && $_SESSION['Ninea']!=null){

            $pdf->SetXY( 25, 35 );

            $pdf->Cell( $pdf->GetPageWidth(), 5, "NINEA : ".$_SESSION['Ninea'], 0, 0, 'L');

        }

        $sql2="SELECT * FROM `".$nomtableClient."` where idClient=".$idClient;
        $res2 = mysql_query($sql2) or die ("persoonel requête 1".mysql_error());
        $client = mysql_fetch_array($res2);

        $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 38 ) ; $pdf->MultiCell(190, 4, 'Nom :', 0, "L");
        $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 132, 38 ) ; $pdf->MultiCell(190, 4, $client["nom"], 0, "L");
        $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 43 ) ; $pdf->MultiCell(190, 4, 'Prenom :', 0, "L");
        $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 137, 43 ) ; $pdf->MultiCell(190, 4, $client["prenom"], 0, "L");
        $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 48 ) ; $pdf->MultiCell(190, 4, 'Adresse :', 0, "L");
        $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 137, 48 ) ; $pdf->MultiCell(190, 4, $client["adresse"], 0, "L");
        $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 53 ) ; $pdf->MultiCell(190, 4, 'Telephone :', 0, "L");
        $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 141, 53 ) ; $pdf->MultiCell(190, 4, $client["telephone"], 0, "L");


        if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
            $pdf->SetFillColor(192);
            $pdf->SetFont('Arial','B',12);
            $pdf->SetY($y_axis_initial);
            $pdf->SetX(5);
            $pdf->Cell(75,6,'Designation',1,0,'C',1);
            $pdf->Cell(20,6,'Depot',1,0,'C',1);
            $pdf->Cell(15,6,'Qte',1,0,'C',1);
            $pdf->Cell(20,6,'Prix',1,0,'C',1);
            $pdf->Cell(20,6,'Prix TT',1,0,'C',1);
            $pdf->Cell(16,6,'Remise',1,0,'C',1);
            $pdf->Cell(17,6,'Date',1,0,'C',1);
            $pdf->Cell(17,6,'Facture',1,0,'C',1);
        }
        else{
            //print column titles
            $pdf->SetFillColor(192);
            $pdf->SetFont('Arial','B',12);
            $pdf->SetY($y_axis_initial);
            $pdf->SetX(5);
            $pdf->Cell(75,6,'Designation',1,0,'C',1);
            $pdf->Cell(20,6,'Unite',1,0,'C',1);
            $pdf->Cell(15,6,'Qte',1,0,'C',1);
            $pdf->Cell(20,6,'Prix',1,0,'C',1);
            $pdf->Cell(20,6,'Prix TT',1,0,'C',1);
            $pdf->Cell(16,6,'Remise',1,0,'C',1);
            $pdf->Cell(17,6,'Date',1,0,'C',1);
            $pdf->Cell(17,6,'Facture',1,0,'C',1);
        }


        $y_axis = $y_axis + $row_height;

        //Select the Products you want to show in your PDF file
        $c=0;
        $l=1;
        $idAvant=0;
        $totalTVA=0;
        if(mysql_num_rows($resP1)){
            while ($bons = mysql_fetch_array($resP1)) { 

                $bon = explode("+", $bons['codePagnet']);
                if($bon[2]==1){
                    $sqlT1="SELECT * FROM `".$nomtablePagnet."` where idPagnet='".$bon[1]."' AND idClient='".$idClient."' ";
                    $resT1 = mysql_query($sqlT1) or die ("persoonel requête 2".mysql_error());
                    $pagnet = mysql_fetch_assoc($resT1);
                    if($pagnet!=null){
                        $sql8="SELECT * 
                        FROM `".$nomtableLigne."` l
                        WHERE l.idPagnet='".$pagnet['idPagnet']."' ORDER BY numligne ASC";
                        $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
                        $m=0;
                        while ($ligne = mysql_fetch_array($res8)) {
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
                
                                $pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 160, 8, $p . '/' . $nb_page, 0, 0, 'C');
                
                                $num_fact = "Operations du ".$dateDebutA." au ".$dateFinA ;
                                $pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(120, 15, 85, 9, "DF");
                                $pdf->SetXY( 120, 15 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 85, 8, $num_fact, 0, 0, 'C');
                                
                                        // observations
                                $pdf->SetFont( "Arial", "BU", 16 ); $pdf->SetXY( 25, 9 ) ; $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, $_SESSION["labelB"], 0, "L");
                                // $pdf->SetFont( "Arial", "", 11 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, '('.$_SESSION["type"].' & '.$_SESSION["categorie"].')', 0, "L");
                                $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, 'Adresse : '.$_SESSION["adresseB"], 0, "L");
                                $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 17 ) ; $pdf->MultiCell(190, 4, 'Telephone : '.$_SESSION["telBoutique"], 0, "L");
                                
                                if( strlen($_SESSION["descriptionB"]) <= 30){
                            
                                    $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 23 ) ; $pdf->MultiCell(190, 4, ''.$_SESSION["descriptionB"], 0, "L");
                            
                            
                                }
                            
                                else{
                            
                                    $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 23 ) ; $pdf->MultiCell(190, 4, ''.substr($_SESSION["descriptionB"],0,30), 0, "L");
                            
                                    $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 26 ) ; $pdf->MultiCell(190, 4, substr($_SESSION["descriptionB"],30,40), 0, "L");
                            
                            
                                }
                                
                                if($_SESSION['RegistreCom']!='' && $_SESSION['RegistreCom']!=null){

                                    $pdf->SetXY( 25, 30 ); 

                                    $pdf->Cell( $pdf->GetPageWidth(), 5, "REGISTRE DE COMMERCE : ".$_SESSION['RegistreCom'], 0, 0, 'L');

                                }

                                if($_SESSION['Ninea']!='' && $_SESSION['Ninea']!=null){

                                    $pdf->SetXY( 25, 35 );

                                    $pdf->Cell( $pdf->GetPageWidth(), 5, "NINEA : ".$_SESSION['Ninea'], 0, 0, 'L');

                                }
                                
                                $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 38 ) ; $pdf->MultiCell(190, 4, 'Nom :', 0, "L");
                                $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 132, 38 ) ; $pdf->MultiCell(190, 4, $client["nom"], 0, "L");
                                $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 43 ) ; $pdf->MultiCell(190, 4, 'Prenom :', 0, "L");
                                $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 137, 43 ) ; $pdf->MultiCell(190, 4, $client["prenom"], 0, "L");
                                $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 48 ) ; $pdf->MultiCell(190, 4, 'Adresse :', 0, "L");
                                $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 137, 48 ) ; $pdf->MultiCell(190, 4, $client["adresse"], 0, "L");
                                $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 53 ) ; $pdf->MultiCell(190, 4, 'Telephone :', 0, "L");
                                $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 141, 53 ) ; $pdf->MultiCell(190, 4, $client["telephone"], 0, "L");
                
                                $y_axis=65;
                                
                                if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
                                    $pdf->SetFillColor(192);
                                    $pdf->SetFont('Arial','B',12);
                                    $pdf->SetY($y_axis_initial);
                                    $pdf->SetX(5);
                                    $pdf->Cell(75,6,'Designation',1,0,'C',1);
                                    $pdf->Cell(20,6,'Depot',1,0,'C',1);
                                    $pdf->Cell(15,6,'Qte',1,0,'C',1);
                                    $pdf->Cell(20,6,'Prix',1,0,'C',1);
                                    $pdf->Cell(20,6,'Prix TT',1,0,'C',1);
                                    $pdf->Cell(16,6,'Remise',1,0,'C',1);
                                    $pdf->Cell(17,6,'Date',1,0,'C',1);
                                    $pdf->Cell(17,6,'Facture',1,0,'C',1);
                                }
                                else{
                                    //print column titles
                                    $pdf->SetFillColor(192);
                                    $pdf->SetFont('Arial','B',12);
                                    $pdf->SetY($y_axis_initial);
                                    $pdf->SetX(5);
                                    $pdf->Cell(75,6,'Designation',1,0,'C',1);
                                    $pdf->Cell(20,6,'Unite',1,0,'C',1);
                                    $pdf->Cell(15,6,'Qte',1,0,'C',1);
                                    $pdf->Cell(20,6,'Prix',1,0,'C',1);
                                    $pdf->Cell(20,6,'Prix TT',1,0,'C',1);
                                    $pdf->Cell(16,6,'Remise',1,0,'C',1);
                                    $pdf->Cell(17,6,'Date',1,0,'C',1);
                                    $pdf->Cell(17,6,'Facture',1,0,'C',1);
                                }
                                
                                //Go to next row
                                $y_axis = $y_axis + $row_height;
                                
                                //Set $i variable to 0 (first row)
                                $i = 0;
                
                            }
                
                            $sqlD="select * from `".$nomtableDesignation."` where idDesignation='".$ligne["idDesignation"]."' ";
                            $resD=mysql_query($sqlD);
                            $produit = mysql_fetch_assoc($resD);
                
                            $designation = strtoupper($ligne['designation']);
                            $quantite = $ligne['quantite'];
                            $categorie = strtoupper($produit['categorie']);
                            if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
                                $sqlE="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$ligne['idEntrepot']."' ";
                                $resE=mysql_query($sqlE);
                                $entrepot=mysql_fetch_array($resE);
                                // libelle
                                $prixU = number_format($ligne['prixunitevente'], 0, ',', ' ');
                                $unite = substr(strtoupper($entrepot['nomEntrepot']),0,7);
                            }
                            else{
                                $prixU = number_format($ligne['prixunitevente'], 0, ',', ' ');
                                $unite = $ligne['unitevente'];
                            }
                            $prixT = number_format($ligne['prixtotal'], 0, ',', ' ');
                            $remise = $pagnet['remise'];
                
                            if($c%2==0){
                                $pdf->SetY($y_axis);
                                $pdf->SetFillColor(255,255,255);
                                //$pdf->SetTextColor(255,255,255);
                                $pdf->SetX(5);
                                $pdf->SetFont('Arial','B',8);
                                $pdf->Cell(75,6,$designation,1,0,'L',1);
                                $pdf->Cell(20,6,$unite,1,0,'C',1);
                                $pdf->Cell(15,6,$quantite,1,0,'C',1);
                                $pdf->Cell(20,6,$prixU,1,0,'C',1);
                                $pdf->Cell(20,6,$prixT,1,0,'C',1);
                                if($idAvant==$pagnet['idPagnet']){
                                    if($i==($max - 1) || $nbre[0]==$l){
                                        $pdf->Cell(16,6,'  ',1,0,'C',0);
                                    }
                                    else{
                                        $pdf->Cell(16,6,'  ',0,0,'C',0);
                                    }
                                }
                                else{
                                    $pdf->Cell(16,6,$remise,1,0,'C',1);
                                }
                                $pdf->Cell(17,6,$pagnet['datepagej'],1,0,'C',1);
                                $pdf->Cell(17,6,'#'.$pagnet['idPagnet'],1,0,'C',1);
                            }
                            else{
                                $pdf->SetY($y_axis);
                                $pdf->SetFillColor(200,210,230);
                                //$pdf->SetTextColor(255,255,255);
                                $pdf->SetX(5);
                                $pdf->SetFont('Arial','B',8);
                                $pdf->Cell(75,6,$designation.' '.$categorie,1,0,'L',1);
                                $pdf->Cell(20,6,$unite,1,0,'C',1);
                                $pdf->Cell(15,6,$quantite,1,0,'C',1);
                                $pdf->Cell(20,6,$prixU,1,0,'C',1);
                                $pdf->Cell(20,6,$prixT,1,0,'C',1);
                                if($idAvant==$pagnet['idPagnet']){
                                    if($i==($max - 1) || $nbre[0]==$l){
                                        $pdf->Cell(16,6,'  ',1,0,'C',0);
                                    }
                                    else{
                                        $pdf->Cell(16,6,'  ',0,0,'C',0);
                                    }
                                }
                                else{
                                    $pdf->Cell(16,6,$remise,1,0,'C',1);
                                }
                                $pdf->Cell(17,6,$pagnet['datepagej'],1,0,'C',1);
                                $pdf->Cell(17,6,'#'.$pagnet['idPagnet'],1,0,'C',1);
                            }
                
                            //Go to next row
                            $y_axis = $y_axis + $row_height;
                            $i = $i + 1;
                            if($i==10){
                                $p = $p + 1;
                            }
                
                            $idAvant=$pagnet['idPagnet'];
                            $l = $l + 1; 
                            $m=$m + 1;                
                
                        }
                    }
                }
                else if($bon[2]==2){
                    $sqlT2="SELECT * FROM `".$nomtableVersement."` where idVersement='".$bon[1]."' AND idClient='".$idClient."' ";
                    $resT2 = mysql_query($sqlT2) or die ("persoonel requête 2".mysql_error());
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
            
                            $pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 160, 8, $p . '/' . $nb_page, 0, 0, 'C');
            
                            $num_fact = "Operations du ".$dateDebutA." au ".$dateFinA ;
                            $pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(120, 15, 85, 9, "DF");
                            $pdf->SetXY( 120, 15 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 85, 8, $num_fact, 0, 0, 'C');
                            
                                    // observations
                            $pdf->SetFont( "Arial", "BU", 16 ); $pdf->SetXY( 25, 9 ) ; $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, $_SESSION["labelB"], 0, "L");
                            // $pdf->SetFont( "Arial", "", 11 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, '('.$_SESSION["type"].' & '.$_SESSION["categorie"].')', 0, "L");
                            $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, 'Adresse : '.$_SESSION["adresseB"], 0, "L");
                            $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 17 ) ; $pdf->MultiCell(190, 4, 'Telephone : '.$_SESSION["telBoutique"], 0, "L");
                            
                            if( strlen($_SESSION["descriptionB"]) <= 30){
                        
                                $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 23 ) ; $pdf->MultiCell(190, 4, ''.$_SESSION["descriptionB"], 0, "L");
                        
                        
                            }
                        
                            else{
                        
                                $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 23 ) ; $pdf->MultiCell(190, 4, ''.substr($_SESSION["descriptionB"],0,30), 0, "L");
                        
                                $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 26 ) ; $pdf->MultiCell(190, 4, substr($_SESSION["descriptionB"],30,40), 0, "L");
                        
                        
                            }
                            
                            if($_SESSION['RegistreCom']!='' && $_SESSION['RegistreCom']!=null){

                                $pdf->SetXY( 25, 30 ); 

                                $pdf->Cell( $pdf->GetPageWidth(), 5, "REGISTRE DE COMMERCE : ".$_SESSION['RegistreCom'], 0, 0, 'L');

                            }

                            if($_SESSION['Ninea']!='' && $_SESSION['Ninea']!=null){

                                $pdf->SetXY( 25, 35 );

                                $pdf->Cell( $pdf->GetPageWidth(), 5, "NINEA : ".$_SESSION['Ninea'], 0, 0, 'L');

                            }
                            
                            $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 38 ) ; $pdf->MultiCell(190, 4, 'Nom :', 0, "L");
                            $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 132, 38 ) ; $pdf->MultiCell(190, 4, $client["nom"], 0, "L");
                            $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 43 ) ; $pdf->MultiCell(190, 4, 'Prenom :', 0, "L");
                            $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 137, 43 ) ; $pdf->MultiCell(190, 4, $client["prenom"], 0, "L");
                            $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 48 ) ; $pdf->MultiCell(190, 4, 'Adresse :', 0, "L");
                            $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 137, 48 ) ; $pdf->MultiCell(190, 4, $client["adresse"], 0, "L");
                            $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 120, 53 ) ; $pdf->MultiCell(190, 4, 'Telephone :', 0, "L");
                            $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 141, 53 ) ; $pdf->MultiCell(190, 4, $client["telephone"], 0, "L");
            
                            $y_axis=65;
                            
                            if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
                                $pdf->SetFillColor(192);
                                $pdf->SetFont('Arial','B',12);
                                $pdf->SetY($y_axis_initial);
                                $pdf->SetX(5);
                                $pdf->Cell(75,6,'Designation',1,0,'C',1);
                                $pdf->Cell(20,6,'Depot',1,0,'C',1);
                                $pdf->Cell(15,6,'Qte',1,0,'C',1);
                                $pdf->Cell(20,6,'Prix',1,0,'C',1);
                                $pdf->Cell(20,6,'Prix TT',1,0,'C',1);
                                $pdf->Cell(16,6,'Remise',1,0,'C',1);
                                $pdf->Cell(17,6,'Date',1,0,'C',1);
                                $pdf->Cell(17,6,'Facture',1,0,'C',1);
                            }
                            else{
                                //print column titles
                                $pdf->SetFillColor(192);
                                $pdf->SetFont('Arial','B',12);
                                $pdf->SetY($y_axis_initial);
                                $pdf->SetX(5);
                                $pdf->Cell(75,6,'Designation',1,0,'C',1);
                                $pdf->Cell(20,6,'Unite',1,0,'C',1);
                                $pdf->Cell(15,6,'Qte',1,0,'C',1);
                                $pdf->Cell(20,6,'Prix',1,0,'C',1);
                                $pdf->Cell(20,6,'Prix TT',1,0,'C',1);
                                $pdf->Cell(16,6,'Remise',1,0,'C',1);
                                $pdf->Cell(17,6,'Date',1,0,'C',1);
                                $pdf->Cell(17,6,'Facture',1,0,'C',1);
                            }
                            
                            //Go to next row
                            $y_axis = $y_axis + $row_height;
                            
                            //Set $i variable to 0 (first row)
                            $i = 0;
            
                        }

                        $pdf->SetY($y_axis);
                        $pdf->SetFillColor(20,210,230);
                        $pdf->SetX(5);
                        $pdf->SetFont('Arial','B',8);
                        $pdf->Cell(75,6,'VERSEMENT',1,0,'L',1);
                        $pdf->Cell(20,6,'Especes',1,0,'C',1);
                        $pdf->Cell(15,6,'1',1,0,'C',1);
                        $pdf->Cell(20,6,number_format($versement['montant'], 0, ',', ' '),1,0,'C',1);
                        $pdf->Cell(20,6,number_format($versement['montant'], 0, ',', ' '),1,0,'C',1);
                        $pdf->Cell(16,6,'0',1,0,'C',1);
                        $pdf->Cell(17,6,$versement['dateVersement'],1,0,'C',1);
                        $pdf->Cell(17,6,'#'.$versement['idVersement'],1,0,'C',1);

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
            $sql12="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient=".$idClient." AND verrouiller=1 AND type=0 AND (CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') or (datepagej BETWEEN '".$dateDebut."' AND '".$dateFin."') ";
            $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
            $TotalB = mysql_fetch_array($res12) ;
            $sql13="SELECT SUM(montant) FROM `".$nomtableVersement."` where idClient=".$idClient." AND  (CONCAT(CONCAT(SUBSTR(dateVersement,7, 10),'',SUBSTR(dateVersement,3, 4)),'',SUBSTR(dateVersement,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') ";
            $res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());
            $TotalV = mysql_fetch_array($res13) ;
            $pdf->SetFont('Arial','B',9);
            //$pdf->SetFont( "Arial", "B", 10 );
            $pdf->SetX(120);
            $pdf->Cell(35,8,'Montant Bons ',1,0,'L',1);
            $pdf->Cell(50,8,number_format($TotalB[0], 0, ',', ' ').' f cfa',1,1,'C',0);
            $pdf->SetX(120);
            $pdf->Cell(35,8,'Montant Versements ',1,0,'L',1);
            $pdf->Cell(50,8,number_format($TotalV[0], 0, ',', ' ').' f cfa',1,1,'C',0);
            $pdf->SetX(120);
            $pdf->Cell(35,8,'Montant a verser',1,0,'L',1);
            $pdf->Cell(50,8,number_format(($TotalB[0] - $TotalV[0]), 0, ',', ' ').' f cfa',1,1,'C',0); 
        }
        else{
            $pdf->SetFont( "Arial", "BU", 12); $pdf->SetXY( 90, 70 ) ; $pdf->Cell($pdf->GetStringWidth("Aucune Vente"), 0, 'Aucune Vente', 0, "L");
        }


        $pdf->Output("I", '1');

    }
}

?>