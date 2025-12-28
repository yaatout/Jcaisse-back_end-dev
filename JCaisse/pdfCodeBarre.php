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

//require('fpdf/fpdf.php');

require('code128.php');

    $idPagnet=htmlspecialchars(trim($_POST['idPagnet']));
    $sqlP1="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";
    $resP1 = mysql_query($sqlP1) or die ("persoonel requête 1".mysql_error());
    $pagnet = mysql_fetch_array($resP1);

    /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
    $sqlC="SELECT COUNT(DISTINCT l.numligne)
    FROM `".$nomtableLigne."` l
    WHERE idPagnet ='".$idPagnet."' ORDER BY l.numligne ASC ";
    $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
    $nbre = mysql_fetch_array($resC) ; 
    /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/  

$pdf=new PDF_Code128();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);

//Disable automatic page break
$pdf->SetAutoPageBreak(false);


//set initial y axis position per page
$y_axis_initial = 10;

//Set Row Height
$row_height = 70;

$y_axis=10;

//Set maximum rows per page
$max = 8;
$i = 0;
$p=1;


//$y_axis = $y_axis + $row_height;

//Select the Products you want to show in your PDF file
$c=1;
$l=1;
$idAvant=0;
$totalTVA=0;
if(mysql_num_rows($resP1)){
    $sql0="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ORDER BY numligne DESC";
    $res0 = mysql_query($sql0) or die ("persoonel requête 2".mysql_error());
    while ($ligne = mysql_fetch_array($res0)) { 

        $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$ligne['idDesignation']."'";
        $res1=mysql_query($sql1);
        $design = mysql_fetch_array($res1);
        if($design['codeBarreDesignation']=='' && $design['codeBarreDesignation']==null){
            $code=$design['idDesignation'].'000'.$design['idDesignation'];
            $sql="UPDATE `".$nomtableDesignation."` set codeBarreDesignation='".$code."' where idDesignation='".$design['idDesignation']."' ";
            $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());
        }
        /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
            $sqlP2="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation']." ";
            $resP2 = mysql_query($sqlP2) or die ("persoonel requête 2".mysql_error());
            $produit = mysql_fetch_array($resP2);
        /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/

                    //If the current row is the last one, create new page and print column title
                    if ($i == $max)
                    {
                        $pdf->AddPage();
        
                        $y_axis=10;
                        
                        //Go to next row
                        //$y_axis = $y_axis + $row_height;
                        
                        //Set $i variable to 0 (first row)
                        $i = 0;
        
                    }
        
                    $designation = strtoupper($produit['designation']);
                    $image='images/produits.png';
                    
                    $pdf->SetDrawColor(235,236,236);
                    if($c==1){
                        //Nom Transfert
                        $pdf->SetLineWidth(1); $pdf->SetFillColor(255); $pdf->Rect(9, $y_axis, 96, $row_height, "DF");
                        // $pdf->SetXY( 8, ($y_axis + 5)); $pdf->SetFont( "Times", "B",  6);
                        $pdf->SetTextColor(0,0,0);
                        $pdf->ClippingCircle( 60, ($y_axis + 19),16,true); 
                        $pdf-> Image($image,43,($y_axis + 1),34,34); 
                        $pdf->UnsetClipping();
                        //Code Depot
                        //$pdf->SetLineWidth(0.1); $pdf->SetFillColor(255); $pdf->Rect(9, ($y_axis + 35), 105, $row_height, "DF");
                        $pdf->SetXY( 8, ($y_axis + 4 + 35)); $pdf->SetFont( "Times", "B", 10 ); 
                        $pdf->SetTextColor(0,0,0);
                        $pdf->MultiCell(106,5,iconv("UTF-8", "ISO-8859-1//TRANSLIT",$designation),0,'C',false);
                        $pdf->SetFillColor(0); 
                        $pdf->SetFont('Arial','',10);
                        $code=$produit['codeBarreDesignation'];
                        $pdf->Code128(38,($y_axis + 10 + 35),$code,45,20);
                    }
                    else if($c==2){
                        //Nom Transfert
                        $pdf->SetLineWidth(1); $pdf->SetFillColor(255); $pdf->Rect(105, $y_axis, 96, $row_height, "DF");
                        //$pdf->SetXY( 105, ($y_axis + 3)); $pdf->SetFont( "Times", "B",  6);
                        $pdf->SetTextColor(0,0,0);
                        $pdf->ClippingCircle( 157, ($y_axis + 19),16,true); 
                        $pdf-> Image($image,140,($y_axis + 1),35,35); 
                        $pdf->UnsetClipping();
                        //Code Depot
                        //$pdf->SetLineWidth(0.1); $pdf->SetFillColor(255); $pdf->Rect(9, ($y_axis + 35), 105, $row_height, "DF");
                        $pdf->SetXY( 105, ($y_axis + 4 + 35)); $pdf->SetFont( "Times", "B", 10 ); 
                        $pdf->SetTextColor(0,0,0);
                        $pdf->MultiCell(106,5,iconv("UTF-8", "ISO-8859-1//TRANSLIT",$designation),0,'C',false);
                        $pdf->SetFillColor(0); 
                        $pdf->SetFont('Arial','',10);
                        $code=$produit['codeBarreDesignation'];
                        $pdf->Code128(135,($y_axis + 10 + 35),$code,45,20);
                    }
                       
                        
                    
        
                    //Go to next row

                    $i = $i + 1;
                   

        $c = $c + 1;
        if($c==3){
            $c =1;
            $y_axis = $y_axis + $row_height;  
        }
    }
}
else{
    $pdf->SetFont( "Arial", "BU", 12); $pdf->SetXY( 90, 70 ) ; $pdf->Cell($pdf->GetStringWidth("Aucune Vente"), 0, 'Aucune Vente', 0, "L");
}

/*
//A set
$code='10100981';
$pdf->Code128(50,20,$code,80,20);
$pdf->SetXY(50,45);
$pdf->Write(5,$code);

//B set
$code='Code 128';
$pdf->Code128(50,70,$code,80,20);
$pdf->SetXY(50,95);
$pdf->Write(5,'B set: "'.$code.'"');

//C set
$code='12345678901234567890';
$pdf->Code128(50,120,$code,110,20);
$pdf->SetXY(50,145);
$pdf->Write(5,'C set: "'.$code.'"');

//A,C,B sets
$code='ABCDEFG1234567890AbCdEf';
$pdf->Code128(50,170,$code,125,20);
$pdf->SetXY(50,195);
$pdf->Write(5,'ABC sets combined: "'.$code.'"');
*/

$pdf->Output("I", '1');



?>