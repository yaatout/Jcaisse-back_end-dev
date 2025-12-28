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

if (isset($_POST['idMutuelle'])) {
    $idMutuelle=htmlspecialchars(trim($_POST['idMutuelle']));
    $dateDebut=htmlspecialchars(trim($_POST['dateDebut']));
    $debutDetails=explode("-", $dateDebut);
    $dateDebutA=$debutDetails[2].'-'.$debutDetails[1].'-'.$debutDetails[0];
    $dateFin=htmlspecialchars(trim($_POST['dateFin']));
    $finDetails=explode("-", $dateFin);
    $dateFinA=$finDetails[2].'-'.$finDetails[1].'-'.$finDetails[0];


    $sql0="SELECT * FROM `".$nomtableMutuelle."` where idMutuelle=".$idMutuelle."";
    $res0 = mysql_query($sql0) or die ("persoonel requête 3".mysql_error());
    $mutuelle = mysql_fetch_assoc($res0);

    /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
        $sqlP1="SELECT DISTINCT m.idMutuellePagnet
        FROM `".$nomtableMutuellePagnet."` m
        INNER JOIN `".$nomtableMutuelle."` l ON l.idMutuelle = m.idMutuelle
        WHERE l.idMutuelle='".$idMutuelle."' && m.verrouiller=1 && m.type=0 && (CONCAT(CONCAT(SUBSTR(m.datepagej,7, 10),'',SUBSTR(m.datepagej,3, 4)),'',SUBSTR(m.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') ORDER BY m.idMutuellePagnet ASC";
        $resP1 = mysql_query($sqlP1) or die ("persoonel requête 1 ".mysql_error());
    /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/

    /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
        $sqlC="SELECT COUNT(*)
        FROM `".$nomtableMutuellePagnet."` m
        INNER JOIN `".$nomtableMutuelle."` l ON l.idMutuelle = m.idMutuelle
        WHERE l.idMutuelle='".$idMutuelle."' && m.verrouiller=1 && m.type=0 && (CONCAT(CONCAT(SUBSTR(m.datepagej,7, 10),'',SUBSTR(m.datepagej,3, 4)),'',SUBSTR(m.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') ORDER BY m.idMutuellePagnet ASC";
        $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
        $nbre = mysql_fetch_array($resC) ; 
    /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/ 

    /**Debut requete pour Calculer la Somme des Ventes d'Aujourd'hui  **/
        $sqlS="SELECT SUM(totalp)
        FROM `".$nomtableMutuellePagnet."` m
        INNER JOIN `".$nomtableMutuelle."` l ON l.idMutuelle = m.idMutuelle
        WHERE l.idMutuelle='".$idMutuelle."' && m.verrouiller=1 && m.type=0 && (CONCAT(CONCAT(SUBSTR(m.datepagej,7, 10),'',SUBSTR(m.datepagej,3, 4)),'',SUBSTR(m.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') ORDER BY m.idMutuellePagnet ASC";
        $resS = mysql_query($sqlS) or die ("persoonel requête 3".mysql_error());
        $totalPanier = mysql_fetch_array($resS) ; 

        $sqlM="SELECT SUM(apayerMutuelle)
        FROM `".$nomtableMutuellePagnet."` m
        INNER JOIN `".$nomtableMutuelle."` l ON l.idMutuelle = m.idMutuelle
        WHERE l.idMutuelle='".$idMutuelle."' && m.verrouiller=1 && m.type=0 && (CONCAT(CONCAT(SUBSTR(m.datepagej,7, 10),'',SUBSTR(m.datepagej,3, 4)),'',SUBSTR(m.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') ORDER BY m.idMutuellePagnet ASC";
        $resM = mysql_query($sqlM) or die ("persoonel requête 4".mysql_error());
        $totalMutuelle = mysql_fetch_array($resM) ;
    /**Fin requete pour Calculer la Somme des Ventes d'Aujourd'hui **/

    //Create new pdf file
    $pdf=new FPDF();

    //Disable automatic page break
    $pdf->SetAutoPageBreak(false);

    //Add first page
    $pdf->AddPage();

    //set initial y axis position per page
    $y_axis_initial = 53;

    //Set Row Height
    $row_height = 6;

    $y_axis=53;

    //Set maximum rows per page
    $max = 35;
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

    $pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 160, 8, $p . '/' . $nb_page, 0, 0, 'C');
            
    $num_fact = utf8_decode("Opérations du ").$dateDebutA." au ".$dateFinA ;
    $pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(120, 15, 85, 8, "DF");
    $pdf->SetXY( 120, 15 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 85, 8, $num_fact, 0, 0, 'C');

    // observations
    $pdf->SetFont( "Arial", "BU", 16 ); $pdf->SetXY( 25, 9 ) ; $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, $_SESSION["labelB"], 0, "L");
    // $pdf->SetFont( "Arial", "", 11 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, '('.$_SESSION["type"].' & '.$_SESSION["categorie"].')', 0, "L");
    $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, 'Adresse : '.$_SESSION["adresseB"], 0, "L");
    $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 17 ) ; $pdf->MultiCell(190, 4, 'Telephone : '.$_SESSION["telBoutique"], 0, "L");

    $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 120, 30 ) ; $pdf->MultiCell(190, 4, 'Nom :', 0, "L");
    $pdf->SetFont( "Arial", "", 12 ); $pdf->SetXY( 134, 30 ) ; $pdf->MultiCell(190, 4, $mutuelle["nomMutuelle"], 0, "L");
    $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 120, 35 ) ; $pdf->MultiCell(190, 4, 'Taux :', 0, "L");
    $pdf->SetFont( "Arial", "", 12 ); $pdf->SetXY( 134, 35 ) ; $pdf->MultiCell(190, 4, ' ('.$mutuelle["tauxMutuelle"].'%)', 0, "L");
    $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 120, 40 ) ; $pdf->MultiCell(190, 4, 'Adresse :', 0, "L");
    $pdf->SetFont( "Arial", "", 12 ); $pdf->SetXY( 140, 40 ) ; $pdf->MultiCell(190, 4, $mutuelle["adresseMutuelle"], 0, "L");
    $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 120, 45 ) ; $pdf->MultiCell(190, 4, 'Telephone :', 0, "L");
    $pdf->SetFont( "Arial", "", 12 ); $pdf->SetXY( 145, 45 ) ; $pdf->MultiCell(190, 4, $mutuelle["telephoneMutuelle"], 0, "L");
    

    //$pdf->SetFont( "Arial", "BU", 14 ); $pdf->SetXY( 5, 32 ) ;$pdf->MultiCell(190, 4, 'Mutuelle de Sante : '.$mutuelle["nomMutuelle"].' ('.$mutuelle["tauxMutuelle"].'%)', 0, "C"); 

    //print column titles
    $pdf->SetFillColor(192);
    $pdf->SetFont('Arial','B',12);
    $pdf->SetY($y_axis_initial);
    $pdf->SetX(5);
    $pdf->Cell(30,6,'Facture',1,0,'C',1);
    $pdf->Cell(80,6,utf8_decode('Prénom Nom'),1,0,'C',1);
    $pdf->Cell(50,6,utf8_decode('Code adhérant'),1,0,'C',1);
    $pdf->Cell(40,6,'Montant',1,0,'C',1);
    // $pdf->Cell(30,6,'Beneficiaire',1,0,'C',1);
    // $pdf->Cell(20,6,'Date',1,0,'C',1);


    $y_axis = $y_axis + $row_height;

    //Select the Products you want to show in your PDF file
    $c=0;
    $l=1;
    $idAvant=0;
    $totalTVA=0;
    if(mysql_num_rows($resP1)){
        while ($pagnets = mysql_fetch_array($resP1)) { 

            $sqlP="select * from `".$nomtableMutuellePagnet."` where idMutuellePagnet='".$pagnets["idMutuellePagnet"]."' ";
            $resP=mysql_query($sqlP);
            $pagnet = mysql_fetch_array($resP); 

            $pdf->SetY($y_axis);
            $pdf->SetFillColor(255,255,255);
            //$pdf->SetTextColor(255,255,255);
            $pdf->SetX(5);
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(30,6, $pagnet['numeroRecu'] ,1,0,'C',1);
            $pdf->Cell(80,6, $pagnet['adherant'] ,1,0,'C',1);
            $pdf->Cell(50,6, $pagnet['codeBeneficiaire'] ,1,0,'C',1);
            $pdf->Cell(40,6, number_format($pagnet['apayerMutuelle'], 2, ',', ' ') ,1,0,'C',1);
            // $pdf->Cell(30,6, $pagnet['codeBeneficiaire'] ,1,0,'C',1);
            // $pdf->Cell(20,6, $pagnet['datepagej'] ,1,0,'C',1);


            //If the current row is the last one, create new page and print column title
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
                $pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(120, 15, 85, 8, "DF");
                $pdf->SetXY( 120, 15 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 85, 8, $num_fact, 0, 0, 'C');

                $pdf->SetFont( "Arial", "BU", 16 ); $pdf->SetXY( 25, 9 ) ; $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, $_SESSION["labelB"], 0, "L");
                // $pdf->SetFont( "Arial", "", 11 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, '('.$_SESSION["type"].' & '.$_SESSION["categorie"].')', 0, "L");
                $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, 'Adresse : '.$_SESSION["adresseB"], 0, "L");
                $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 17 ) ; $pdf->MultiCell(190, 4, 'Telephone : '.$_SESSION["telBoutique"], 0, "L");

                $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 120, 30 ) ; $pdf->MultiCell(190, 4, 'Nom :', 0, "L");
                $pdf->SetFont( "Arial", "", 12 ); $pdf->SetXY( 134, 30 ) ; $pdf->MultiCell(190, 4, $mutuelle["nomMutuelle"], 0, "L");
                $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 120, 35 ) ; $pdf->MultiCell(190, 4, 'Taux :', 0, "L");
                $pdf->SetFont( "Arial", "", 12 ); $pdf->SetXY( 134, 35 ) ; $pdf->MultiCell(190, 4, ' ('.$mutuelle["tauxMutuelle"].'%)', 0, "L");
                $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 120, 40 ) ; $pdf->MultiCell(190, 4, 'Adresse :', 0, "L");
                $pdf->SetFont( "Arial", "", 12 ); $pdf->SetXY( 140, 40 ) ; $pdf->MultiCell(190, 4, $mutuelle["adresseMutuelle"], 0, "L");
                $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 120, 45 ) ; $pdf->MultiCell(190, 4, 'Telephone :', 0, "L");
                $pdf->SetFont( "Arial", "", 12 ); $pdf->SetXY( 145, 45 ) ; $pdf->MultiCell(190, 4, $mutuelle["telephoneMutuelle"], 0, "L");

                $y_axis_initial = 53;
                $y_axis=53;
                
                //print column titles
                $pdf->SetFillColor(192);
                $pdf->SetFillColor(192);
                $pdf->SetFont('Arial','B',12);
                $pdf->SetY($y_axis_initial);
                $pdf->SetX(5);
                $pdf->Cell(30,6,'Facture',1,0,'C',1);
                $pdf->Cell(80,6,utf8_decode('Prénom Nom'),1,0,'C',1);
                $pdf->Cell(50,6,utf8_decode('Code adhérant'),1,0,'C',1);
                $pdf->Cell(40,6,'Montant',1,0,'C',1);
                // $pdf->Cell(30,6,'Beneficiaire',1,0,'C',1);
                // $pdf->Cell(20,6,'Date',1,0,'C',1);
                
                //Go to next row
                // $y_axis = $y_axis + $row_height;
                
                //Set $i variable to 0 (first row)
                $i = 0;

            }


            //Go to next row
            $y_axis = $y_axis + $row_height;
            $i = $i + 1;
            if($i==10){
                $p = $p + 1;
            }

            $l = $l + 1;

            // si derniere page alors afficher total
            if ($nbre[0]==($l - 1))
            {
                $pdf->SetY($y_axis);
                $pdf->SetFillColor(192);
                //$pdf->SetTextColor(255,255,255);
                
                $pdf->SetFont('Arial','B',12);
                $pdf->SetX(125);
                $pdf->Cell(40,8,'TOTAL',1,0,'C',1);
                $pdf->Cell(40,8,number_format($totalMutuelle[0], 2, ',', ' '),1,1,'C',0);
                $pdf->SetX(125);
                $pdf->Cell(40,8,'NET A PAYER',1,0,'C',1);
                $pdf->Cell(40,8,number_format($totalMutuelle[0], 0, ',', ' '),1,0,'C',0);
            }

            $y1 = 270;
            //Positionnement en bas et tout centrer
            $pdf->SetXY( 1, $y1 ); $pdf->SetFont('Arial','B',10);
            $pdf->Cell( $pdf->GetPageWidth(), 5, strtoupper($_SESSION["labelB"]), 0, 0, 'C');
            
            $pdf->SetFont('Arial','',10);
            if($_SESSION['RegistreCom']!='' && $_SESSION['RegistreCom']!=null){
                $pdf->SetXY( 1, $y1 + 4 ); 
                $pdf->Cell( $pdf->GetPageWidth(), 5, "REGISTRE DE COMMERCE : ".$_SESSION['RegistreCom'], 0, 0, 'C');
            }
            if($_SESSION['Ninea']!='' && $_SESSION['Ninea']!=null){
                $pdf->SetXY( 1, $y1 + 8 );
                $pdf->Cell( $pdf->GetPageWidth(), 5, "NINEA : ".$_SESSION['Ninea'], 0, 0, 'C');
            }
            $pdf->SetXY( 1, $y1 + 12 );
            $pdf->Cell( $pdf->GetPageWidth(), 5, "ADRESSE : ".$_SESSION["adresseB"], 0, 0, 'C');

            $pdf->SetXY( 1, $y1 + 16 );
            $pdf->Cell( $pdf->GetPageWidth(), 5, "TELEPHONE : ".$_SESSION["telBoutique"], 0, 0, 'C');
        }
    }
    else{
        $pdf->SetFont( "Arial", "BU", 12); $pdf->SetXY( 90, 70 ) ; $pdf->Cell($pdf->GetStringWidth("Aucune Vente"), 0, 'Aucune Vente', 0, "L");
    }


    $pdf->Output("I", '1');

}

?>