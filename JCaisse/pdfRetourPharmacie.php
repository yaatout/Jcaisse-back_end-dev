<?php

session_start();

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



    //Create new pdf file
    $pdf=new FPDF();

    //Disable automatic page break
    $pdf->SetAutoPageBreak(false);

    //Add first page
    $pdf->AddPage();

    //set initial y axis position per page
    $y_axis_initial = 60;

    //Set Row Height
    $row_height = 6;

    $y_axis=60;

    //Set maximum rows per page
    $max = 35;
    $i = 0;
    $p=1;

    
    $image='pharmacie.png';
    $pdf-> SetFont("Arial","B",10);
    $pdf-> Image('images/'.$image,5,5,20,20);

    //$nb_page=ceil($nbre[0]/$max);

    //$pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 160, 8, $p . '/' . $nb_page, 0, 0, 'C');
            
    //$champ_date = date_create($dateString2); $annee = date_format($champ_date, 'Y');
    $num_fact = "BON DE RETOUR" ;
    $pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(120, 15, 85, 9, "DF");
    $pdf->SetXY( 120, 15 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 85, 8, $num_fact, 0, 0, 'C');

            // observations
    $pdf->SetFont( "Arial", "BU", 16 ); $pdf->SetXY( 25, 9 ) ; $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, $_SESSION["labelB"], 0, "L");
   // $pdf->SetFont( "Arial", "", 11 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, '('.$_SESSION["type"].' & '.$_SESSION["categorie"].')', 0, "L");
    $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, 'Adresse : '.$_SESSION["adresseU"], 0, "L");
    $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 17 ) ; $pdf->MultiCell(190, 4, 'Telephone : '.$_SESSION["telPortable"].' / '.$_SESSION["telFixe"], 0, "L");

    //$pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 5, 38 ) ; $pdf->MultiCell(190, 4, 'No Facture :', 0, "L");
    //$pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 27, 38 ) ; $pdf->MultiCell(190, 4, '#'.$pagnet["idPagnet"], 0, "L");
    $pdf->SetFont( "Arial", "B", 10 ); $pdf->SetXY( 5, 43 ) ; $pdf->MultiCell(190, 4, 'Date :', 0, "L");
    $pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 16, 43 ) ; $pdf->MultiCell(190, 4, $dateString2, 0, "L");
    //$pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 5, 46 ) ; $pdf->MultiCell(190, 4, 'Mode de Reglement : '.$dateString2, 0, "L");

    //print column titles
    $pdf->SetFillColor(192);
    $pdf->SetFont('Arial','B',12);
    $pdf->SetY($y_axis_initial);
    $pdf->SetX(5);
    $pdf->Cell(115,6,'Designation',1,0,'L',1);
    $pdf->Cell(25,6,'Quantite',1,0,'L',1);
    $pdf->Cell(30,6,'Prix Session',1,0,'L',1);
    $pdf->Cell(30,6,'Prix Public',1,0,'L',1);

    $y_axis = $y_axis + $row_height;

    //Select the Products you want to show in your PDF file
    $c=0;
    $l=1;
    $totalPS=0;
    $totalPP=0;

    $sql1="SELECT * FROM `".$nomtablePagnet."` where idClient=0  && verrouiller=1 && datepagej ='".$dateString2."' && totalp=0 ";
    $res1 = mysql_query($sql1) or die ("persoonel requête 1".mysql_error());
    while ($pagnet = mysql_fetch_array($res1)) {

        $sql2="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ORDER BY numligne DESC";
        $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
        while ($ligne = mysql_fetch_array($res2)) {

            $sqlS="SELECT *
            FROM `".$nomtableStock."`
            where idStock='".$ligne['idStock']."' ";
            $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
            $stock = mysql_fetch_array($resS);
           
            $designation = strtoupper($ligne['designation']);
            $quantite = $ligne['quantite'];
            $prixU = number_format($stock['prixSession'], 2, ',', ' ');
            $prixT = number_format($stock['prixPublic'], 2, ',', ' ');
            $totalPS=$totalPS + ($stock['prixSession'] *$ligne['quantite']);
            $totalPP=$totalPP + ($stock['prixPublic'] * $ligne['quantite']);
    
            $pdf->SetY($y_axis);
            $pdf->SetFillColor(255,255,255);
            //$pdf->SetTextColor(255,255,255);
            $pdf->SetX(5);
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(115,6,$designation,1,0,'L',1);
            $pdf->Cell(25,6,$quantite,1,0,'L',1);
            $pdf->Cell(30,6,$prixU,1,0,'L',1);
            $pdf->Cell(30,6,$prixT,1,0,'L',1);
    
    
            //Go to next row
            $y_axis = $y_axis + $row_height;
            $i = $i + 1;
            if($i==10){
                $p = $p + 1;
            }     
        
        }
    
    }


    $pdf->SetY(($y_axis + 5));
    $pdf->SetFillColor(192);
    //$pdf->SetTextColor(255,255,255);
    
    $pdf->SetFont('Arial','B',8);
    $pdf->SetX(120);
    $pdf->Cell(35,8,'Total Prix Session',1,0,'L',1);
    $pdf->Cell(50,8,number_format($totalPS, 2, ',', ' '),1,1,'L',0);
    $pdf->SetX(120);
    $pdf->Cell(35,8,'Total Prix Public',1,0,'L',1);
    $pdf->Cell(50,8,number_format($totalPP, 2, ',', ' '),1,1,'L',0);


    $pdf->Output("I", '1');


?>