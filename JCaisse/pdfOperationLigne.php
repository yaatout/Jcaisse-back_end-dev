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

require('connectionPDO.php');

require('declarationVariables.php');

require('fpdf/fpdf.php');


if (isset($_POST['id'])) {

    $idDesignation=htmlspecialchars(trim($_POST['id']));
    $dateDebut=htmlspecialchars(trim($_POST['dateDebut']));
    $debutDetails=explode("-", $dateDebut);
    $dateDebutA=$debutDetails[2].'-'.$debutDetails[1].'-'.$debutDetails[0];
    $dateFin=htmlspecialchars(trim($_POST['dateFin']));
    $finDetails=explode("-", $dateFin);
    $dateFinA=$finDetails[2].'-'.$finDetails[1].'-'.$finDetails[0];


/**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
    $sqlP1="SELECT *,CONCAT(CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)),'',p.heurePagnet) as idLigne,
             CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) as dateJour
    FROM `".$nomtableLigne."` l
    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet  
    WHERE p.verrouiller=1 AND (p.type=0 || p.type=1 || p.type=11 || p.type=30) AND idDesignation='".$idDesignation."' AND (CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') ORDER BY idLigne DESC";
    $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
/**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/

/**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
    $sqlC="SELECT COUNT(DISTINCT l.numligne)
    FROM `".$nomtableLigne."` l
    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet 
    WHERE p.verrouiller=1 AND (p.type=0 || p.type=1 || p.type=11 || p.type=30) AND idDesignation='".$idDesignation."' AND (CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') ";
    $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
    $nbre = mysql_fetch_array($resC) ; 
/**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/ 

/**Debut requete pour Calculer la Somme des Depenses d'Aujourd'hui  **/
    $montant = 0;
    $stmtLigne = $bdd->prepare("SELECT * FROM `".$nomtableLigne."` l
    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
    WHERE p.verrouiller=1 AND (p.type=0 || p.type=1 || p.type=11 || p.type=30) AND l.idDesignation=:idDesignation AND (CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') ");
    $stmtLigne->bindValue(':idDesignation', (int)$idDesignation, PDO::PARAM_INT);
    $stmtLigne->execute();
    $lignes = $stmtLigne->fetchAll();
    foreach ($lignes as $ligne) {
        $montant = $montant + $ligne['prixtotal'];
    }
   
/**Fin requete pour Calculer la Somme des Depenses d'Aujourd'hui  **/

$sql="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$idDesignation."' ";
$res=mysql_query($sql);
$design=mysql_fetch_array($res);

//Create new pdf file
$pdf=new FPDF();

//Disable automatic page break
$pdf->SetAutoPageBreak(false);

//Add first page
$pdf->AddPage();

//set initial y axis position per page
$y_axis_initial = 43;

//Set Row Height
$row_height = 6;

$y_axis=43;

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
        
$champ_date = date_create($dateString2); $annee = date_format($champ_date, 'Y');
$num_fact = "Operations du ".$dateDebutA." au ".$dateFinA ;
$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(120, 15, 85, 8, "DF");
$pdf->SetXY( 120, 15 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 85, 8, $num_fact, 0, 0, 'C');

// observations
$pdf->SetFont( "Arial", "BU", 16 ); $pdf->SetXY( 25, 9 ) ; $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, $_SESSION["labelB"], 0, "L");
// $pdf->SetFont( "Arial", "", 11 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, '('.$_SESSION["type"].' & '.$_SESSION["categorie"].')', 0, "L");
$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, 'Adresse : '.$_SESSION["adresseU"], 0, "L");
$pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 17 ) ; $pdf->MultiCell(190, 4, 'Telephone : '.$_SESSION["telPortable"].' / '.$_SESSION["telFixe"], 0, "L");

if($design['classe']==1){
    $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 5, 35 ) ; $pdf->MultiCell(190, 4, 'Service : '.$design['designation'], 0, "L");
}
else if($design['classe']==2){
    $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 5, 35 ) ; $pdf->MultiCell(190, 4, 'Depense : '.$design['designation'], 0, "L");
}
//$pdf->Cell($pdf->GetStringWidth("Depenses"), 0, 'Depenses', 0, "L");


//print column titles
$pdf->SetFillColor(192);
$pdf->SetFont('Arial','B',12);
$pdf->SetY($y_axis_initial);
$pdf->SetX(5);
$pdf->Cell(27,6,'Date',1,0,'C',1);
$pdf->Cell(27,6,'Heure',1,0,'C',1);
$pdf->Cell(27,6,'Quantite',1,0,'C',1);
$pdf->Cell(30,6,'Unite',1,0,'C',1);
$pdf->Cell(31,6,'Prix',1,0,'C',1);
$pdf->Cell(31,6,'Prix TT',1,0,'C',1);
$pdf->Cell(27,6,'Facture',1,0,'C',1);

$y_axis = $y_axis + $row_height;

//Select the Products you want to show in your PDF file
$c=0;
$l=1;
$idAvant=0;
while ($ligne = mysql_fetch_array($resP1)) { 

    /*
    $sqlP="select * from `".$nomtablePagnet."` where idPagnet='".$pagnets["idPagnet"]."' ";
    $resP=mysql_query($sqlP);
    $pagnet = mysql_fetch_array($resP); 

    $sql8="SELECT * 
    FROM `".$nomtableLigne."` l
    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation 
    WHERE l.idPagnet='".$ligne['idPagnet']."' ORDER BY numligne ASC";
    $res8 = mysql_query($sql8) or die ("persoonel requête 2".mysql_error());
    while ($ligne = mysql_fetch_array($res8)) {
        */

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

            $champ_date = date_create($dateString2); $annee = date_format($champ_date, 'Y');
            $num_fact = "Operations du ".$dateDebutA." au ".$dateFinA ;
            $pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(120, 15, 85, 8, "DF");
            $pdf->SetXY( 120, 15 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 85, 8, $num_fact, 0, 0, 'C');

            $pdf->SetFont( "Arial", "BU", 16 ); $pdf->SetXY( 25, 9 ) ; $pdf->Cell($pdf->GetStringWidth($_SESSION["labelB"]), 0, $_SESSION["labelB"], 0, "L");
            // $pdf->SetFont( "Arial", "", 11 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, '('.$_SESSION["type"].' & '.$_SESSION["categorie"].')', 0, "L");
            $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 13 ) ; $pdf->MultiCell(190, 4, 'Adresse : '.$_SESSION["adresseU"], 0, "L");
            $pdf->SetFont( "Arial", "", 8 ); $pdf->SetXY( 25, 17 ) ; $pdf->MultiCell(190, 4, 'Telephone : '.$_SESSION["telPortable"].' / '.$_SESSION["telFixe"], 0, "L");

            if($design['classe']==1){
                $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 5, 35 ) ; $pdf->MultiCell(190, 4, 'Service : '.$design['designation'], 0, "L");
            }
            else if($design['classe']==2){
                $pdf->SetFont( "Arial", "B", 12 ); $pdf->SetXY( 5, 35 ) ; $pdf->MultiCell(190, 4, 'Depense : '.$design['designation'], 0, "L");
            }

            $y_axis=43;
            
            //print column titles for the current page
            $pdf->SetY($y_axis_initial);
            $pdf->SetX(5);
            $pdf->Cell(27,6,'Date',1,0,'C',1);
            $pdf->Cell(27,6,'Heure',1,0,'C',1);
            $pdf->Cell(27,6,'Quantite',1,0,'C',1);
            $pdf->Cell(30,6,'Unite',1,0,'C',1);
            $pdf->Cell(31,6,'Prix',1,0,'C',1);
            $pdf->Cell(31,6,'Prix TT',1,0,'C',1);
            $pdf->Cell(27,6,'Facture',1,0,'C',1);
            
            //Go to next row
            $y_axis = $y_axis + $row_height;
            
            //Set $i variable to 0 (first row)
            $i = 0;

        }

        $designation = $ligne['designation'];
        $quantite = $ligne['quantite'];
        if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
            $unite = $ligne['forme'];
            $prixU = number_format($ligne['prixPublic'], 0, ',', ' ');
        }
        else{
            $unite = $ligne['unitevente'];
            $prixU = number_format($ligne['prixunitevente'], 0, ',', ' ');
        }
        $prixT = number_format($ligne['prixtotal'], 0, ',', ' ');
        $heure = $ligne['heurePagnet'];

        $pdf->SetY($y_axis);
        $pdf->SetFillColor(255,255,255);
        //$pdf->SetTextColor(255,255,255);
        $pdf->SetX(5);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(27,6,$ligne['dateJour'],1,0,'C',1);
        $pdf->Cell(27,6,$heure,1,0,'C',1);
        $pdf->Cell(27,6,$quantite,1,0,'C',1);
        $pdf->Cell(30,6,$unite,1,0,'C',1);
        $pdf->Cell(31,6,$prixU,1,0,'C',1);
        $pdf->Cell(31,6,$prixT,1,0,'C',1);
        $pdf->Cell(27,6,'#'.$ligne['numligne'],1,0,'C',1);

        //Go to next row
        $y_axis = $y_axis + $row_height;
        $i = $i + 1;
        if($i==10){
            $p = $p + 1;
        }

        $idAvant=$ligne['idPagnet'];
        

   // }

    // si derniere page alors afficher total

            
    $l = $l + 1;
    $c = $c + 1;
}
if ($nbre[0]==($l - 1))
{
    $pdf->SetY($y_axis);
    $pdf->SetFillColor(192);
    //$pdf->SetTextColor(255,255,255);
    
    $pdf->SetFont('Arial','B',10);
    $pdf->SetX(116);
    $pdf->Cell(31,10,'TOTAL',1,0,'L',1);
    $pdf->Cell(58,10,number_format($montant, 0, ',', ' ').' '.$_SESSION['symbole'],1,0,'C',0);
    
}

$pdf->Output("I", '1');

}

?>