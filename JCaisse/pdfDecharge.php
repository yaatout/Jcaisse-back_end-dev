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

    $idPayement=htmlspecialchars(trim($_GET['iDS']));
    $sql1="SELECT * FROM `aaa-payement-salaire` WHERE idPS =".$idPayement." " ;
    $res1 = mysql_query($sql1) or die ("persoonel requête 1".mysql_error());
    $payement = mysql_fetch_array($res1);

    $sql2="SELECT * FROM `aaa-boutique` WHERE idBoutique =".$payement['idBoutique'];
    $res2 = mysql_query($sql2) or die ("personel requête 2".mysql_error());
    $boutique = mysql_fetch_array($res2);

    $sql3="SELECT * 
    FROM `aaa-utilisateur` u
    INNER JOIN `aaa-acces` a ON a.idutilisateur = u.idutilisateur
    WHERE a.idBoutique =".$payement['idBoutique']." AND a.proprietaire=1 ";
    $res3 = mysql_query($sql3) or die ("personel requête 2".mysql_error());
    $proprietaire = mysql_fetch_array($res3);


    //Create new pdf file
    $pdf=new FPDF();

    //Disable automatic page break
    $pdf->SetAutoPageBreak(false);

    //Add first page
    $pdf->AddPage();


    /** */
    $pdf->SetLineWidth(0.5); $pdf->SetFillColor(192); $pdf->Rect(10, 25, 85, 10, "DF");
    $pdf->SetXY( 10, 25 ); $pdf->SetFont( "Arial", "B", 18); 
    $pdf->Cell( 85, 10,'JCAISSE', 0, 0, 'L');
    $pdf->SetLineWidth(0.5); $pdf->SetFillColor(255); $pdf->Rect(10, 35, 85, 20, "DF");
    $pdf->SetXY( 10, 35 ); $pdf->SetFont( "Arial", "B", 10 ); 
    $pdf->MultiCell(85,5,iconv('UTF-8', 'ISO-8859-2',"Diabir en Face de l'Universite Assane SECK de Ziguinchor\nTéléphone : 77 915 59 47\nEmail : contact@jcaisse.shop"),0,'L',false);
    /** */
            

    $date_paie=explode("-", $payement['datePaiement']);
    $jour_paie=$date_paie[2]; $mois_paie=$date_paie[1]; $annee_paie=$date_paie[0];
    $num_fact=$mois_paie.''.$jour_paie.''.$payement['idPS'].'-'.$annee_paie ;

    $pdf->SetXY( 115, 30 ); $pdf->SetFont( "Arial", "B", 20 ); $pdf->Cell( 90, 8, 'FACTURE', 0, 0, 'C');
    $pdf->SetLineWidth(0.5); $pdf->SetFillColor(192); $pdf->Rect(115, 40, 85, 6, "DF");
    $pdf->SetXY( 115, 40 ); $pdf->SetFont( "Arial", "B", 10 ); 
    $pdf->Cell( 45, 6,'Date', 1, 0, 'L');$pdf->Cell( 40, 6, '#Facture', 1, 0, 'L');
    //$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(120, 30, 85, 9, "DF");
    $pdf->SetLineWidth(0.5); $pdf->SetFillColor(255); $pdf->Rect(115,46 , 85, 8, "DF");
    $pdf->SetXY( 115, 46 ); $pdf->SetFont( "Arial", "B", 12 ); 
    $pdf->Cell( 45, 8,$jour_paie.'-'.$mois_paie.'-'.$annee_paie, 1, 0, 'C');$pdf->Cell( 40, 8, $num_fact, 1, 0, 'C');
    /** */

    /** */
    $pdf->SetLineWidth(0.5); $pdf->SetFillColor(255); $pdf->Rect(10, 62, 85, 20, "DF");
    $pdf->SetXY( 10, 62 ); $pdf->SetFont( "Arial", "B", 10 ); 
    $pdf->MultiCell(85,5,iconv('UTF-8', 'ISO-8859-2',"Facturé a : \n".$boutique['nomBoutique']."\nAdresse : ".$boutique['adresseBoutique']."\nTéléphone : ".$boutique['telephone']),0,'L',false);
    /** */

    /** */
    $pdf->SetLineWidth(0.5); $pdf->SetFillColor(255); $pdf->Rect(110, 62, 90, 20, "DF");
    $pdf->SetXY( 110, 62 ); $pdf->SetFont( "Arial", "B", 10 ); 
    $pdf->MultiCell(90,5,iconv('UTF-8', 'ISO-8859-2', "Client : \n".$proprietaire['prenom'].' '.$proprietaire['nom']."\nTéléphone : ".$proprietaire['telPortable']."\nEmail : ".$proprietaire['email']),0,'L',false);
    /** */

    /** */
    $cachet='images/cachet.png';
    $periode_paie=explode("-", $payement['datePS']);
    $mois_periode=$periode_paie[1]; $annee_periode=$periode_paie[0];
    $mois=$mois_periode;

    $periode=calendrierMois($mois);
    $pdf->SetLineWidth(0.5); $pdf->SetFillColor(192); $pdf->Rect(10, 90, 190, 8, "DF");
    $pdf->SetXY( 10, 90 ); $pdf->SetFont( "Arial", "B", 10); 
    $pdf->Cell( 100, 8,'Description', 0, 0, 'L');$pdf->Cell( 30, 8,'Periode', 0, 0, 'C');$pdf->Cell( 30, 8,'Prix', 0, 0, 'C');$pdf->Cell( 30, 8,'Prix TTC', 0, 0, 'C');
    $pdf->SetLineWidth(0.5);$pdf->SetFillColor(255); $pdf->Rect(10, 98, 190, 100, "DF");
    $pdf->SetXY( 10, 100 ); $pdf->SetFont( "Arial", "B", 10 ); 
    $pdf->MultiCell(100,5,iconv('UTF-8', 'ISO-8859-2',"Cette facture certifie le paiement de l'utilisation\ndu service JCaisse pour l'édition du journal de\ncaisse et la gestion du stock de la période  de\n".$periode.' '.$annee_paie." de ".$boutique['nomBoutique'].".\n"),0,'L',false);
    $pdf-> Image('images/cachet.png',100,147,50,50);
    $pdf->SetXY( 110, 98 ); $pdf->SetFont( "Arial", "B", 10 );
    $pdf->Cell( 30, 8,$periode.' '.$annee_paie, 0, 0, 'C');$pdf->Cell( 30, 8,number_format($payement['montantFixePayement'], 2, ',', ' '), 0, 0, 'C');$pdf->Cell( 30, 8,number_format($payement['montantFixePayement'], 2, ',', ' '), 0, 0, 'C');
    $pdf->SetLineWidth(0.5); $pdf->SetFillColor(255); $pdf->Rect(10, 198, 100, 20, "DF");
    $pdf->SetXY( 10, 110 ); $pdf->SetFont( "Arial", "B", 10); 
    $pdf->SetLineWidth(0.5); $pdf->SetFillColor(255); $pdf->Rect(110, 198, 90, 10, "DF");
    $pdf->SetXY( 110, 100 ); $pdf->SetFont( "Arial", "B", 10);
    $pdf->SetLineWidth(0.5); $pdf->SetFillColor(255); $pdf->Rect(110, 208, 90, 10, "DF");
    $pdf->SetXY( 110, 100 ); $pdf->SetFont( "Arial", "B", 12);
    $pdf->Cell( 90, 228, 'Total : '.number_format($payement['montantFixePayement'], 2, ',', ' ').' FCFA', 0, 0, 'R');
    /** */

    $pdf->SetY(289);
    $pdf->SetFillColor(255,255,255);
    $pdf->SetX(0);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(210,10,'',1,0,'L',1);
    $pdf->SetY(290);

    $pdf->SetFont('Arial',"BU",13);
    $pdf->Cell(22,7,'JCAISSE',0,0,'L',1);
    $pdf->SetFont('Arial',"B",8);
    $pdf->Cell(12,7,iconv('UTF-8', 'ISO-8859-2', "édité par"),0,0,'C',1);
    $pdf->SetFont('Arial',"B",10);
    $pdf->Cell(40,7,iconv('UTF-8', 'ISO-8859-2', "YAATOUT SARL"),0,0,'L',1);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(70,7,iconv('UTF-8', 'ISO-8859-2', 'Régistre de commerce : SN ZGR-2009-B-434'),0,0,'L',1);
    $pdf->Cell(40,7,iconv('UTF-8', 'ISO-8859-2', 'Ninéa : 007272365'),0,0,'R',1);

    $pdf->Output("D",'Paiement '.$periode.' '.$annee_paie.'.pdf');


    function calendrierMois($mois) {
        switch($mois) {
            case '01': $mois = 'Janvier'; break;
            case '02': $mois = 'Fevrier'; break;
            case '03': $mois = 'Mars'; break;
            case '04': $mois = 'Avril'; break;
            case '05': $mois = 'Mai'; break;
            case '06': $mois = 'Juin'; break;
            case '07': $mois = 'Juillet'; break;
            case '08': $mois = 'Aout'; break;
            case '09': $mois = 'Septembre'; break;
            case '10': $mois = 'Octobre'; break;
            case '11': $mois = 'Novembre'; break;
            case '12': $mois = 'Decembre'; break;
            default: $mois =''; break;
        }
        return $mois;
    }

?>