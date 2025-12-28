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
$y_axis_initial = 10;

//Set Row Height
$row_height = 42.3;

$y_axis=0;

//Set maximum rows per page
$max = 21;
$i = 0;
$p=1;


//$y_axis = $y_axis + $row_height;

//Select the Products you want to show in your PDF file
$c=1;
$l=1;
$idAvant=0;
$totalTVA=0;

//$nbre = count($_SESSION['etiquettes']);
$etiquettes = $_SESSION['etiquettes'];
$nbre = 0;

foreach ($etiquettes as $produits => $produit) {
    $nbre = $nbre + $produit['quantite'];
}


foreach ($etiquettes as $produits => $produit) {
    for ($q=0; $q<$produit['quantite']; $q++) {

        //If the current row is the last one, create new page and print column title
        if ($i == $max)
        {
            $pdf->AddPage();

            $y_axis=0;
            
            //Go to next row
            //$y_axis = $y_axis + $row_height;
            
            //Set $i variable to 0 (first row)
            $i = 0;

        }

        $designation = strtoupper($produit['designation']);
        $prix = number_format($produit['prix'], 0, ',', ' ');

        if($c==1){
            $pdf->SetLineWidth(0.1); $pdf->SetFillColor(255); $pdf->Rect(0, $y_axis, 70, $row_height, "DF");
            $pdf->SetXY( 1, ($y_axis + 14)); $pdf->SetFont( "Times", "B",  60);
            $pdf->SetTextColor(194,8,8);
            $pdf->MultiCell(71,8,iconv("UTF-8", "ISO-8859-1//TRANSLIT",$prix),0,'C',false);
            $pdf->SetXY( 1, ($y_axis + 25)); $pdf->SetFont( "Times", "B", 10 );     
            $pdf->SetTextColor(0,0,0); 
            $pdf->MultiCell(70,5,$designation,0,'C',false);                
        }
        else if($c==2){
            $pdf->SetLineWidth(0.1); $pdf->SetFillColor(255); $pdf->Rect(70, $y_axis, 70, $row_height, "DF");
            $pdf->SetXY( 71, ($y_axis + 14)); $pdf->SetFont( "Times", "B", 60 ); 
            $pdf->SetTextColor(194,8,8);
            $pdf->MultiCell(65,5,iconv("UTF-8", "ISO-8859-1//TRANSLIT",$prix),0,'C',false);
            $pdf->SetXY( 70, ($y_axis + 25)); $pdf->SetFont( "Times", "B", 10 ); 
            $pdf->SetTextColor(0,0,0);
            $pdf->MultiCell(64,5,$designation,0,'C',false);
        }
        else if($c==3){
            $pdf->SetLineWidth(0.1); $pdf->SetFillColor(255); $pdf->Rect(140, $y_axis, 70, $row_height, "DF");
            $pdf->SetXY( 141, ($y_axis + 14)); $pdf->SetFont( "Times", "B", 60 );
            $pdf->SetTextColor(194,8,8);
            $pdf->MultiCell(65,5,iconv("UTF-8", "ISO-8859-1//TRANSLIT",$prix),0,'C',false);
            $pdf->SetTextColor(0,0,0); 
            $pdf->SetXY( 140, ($y_axis + 25)); $pdf->SetFont( "Times", "B", 10 );
            $pdf->MultiCell(64,5,$designation,0,'C',false); 
        }
        

        //Go to next row

        $i = $i + 1;
        if($i==10){
            $p = $p + 1;
        }

        $l = $l + 1;

        // si derniere page alors afficher total
        if ($nbre ==($l - 1))
        {
            
            
        }
                

        $c = $c + 1;
        if($c==4){
            $c =1;
            $y_axis = $y_axis + $row_height;  
        }

    }
}


$pdf->Output("I", '1');



?>