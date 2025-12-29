<?php
//if (!empty($_POST['submit'])){

/*$idproduit        =@$_POST["idproduit"];
$nom              =@$_POST["nom"];
$prenom           =@$_POST["prenom"];
$dateNaiss        =@$_POST["datenaiss"];*/

$idproduit        =12;
$nom              ="Diop";
$prenom           ="Ibrahima";
$dateNaiss        ="16/06/1981";

require("fpdf/fpdf.php");
$pdf=new FPDF();
$pdf->AddPage();
$pdf->SetFont("Arial","B",16);

$pdf->Cell(0,10,"Welcome {$idproduit}",1,1,"C");

$pdf->Cell(50,10,"Nom:",1,0);
$pdf->Cell(50,10,$nom,1,1);

$pdf->Cell(50,10,"Prénom:",1,0);
$pdf->Cell(50,10,$prenom,1,1);

$pdf->Cell(50,10,"Date naissance:",1,0);
$pdf->Cell(50,10,$dateNaiss,1,1);

$pdf->output();

//}
?>
