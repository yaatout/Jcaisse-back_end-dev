<?php
/*
Résumé :
Commentaire :
Version : 2.0
see also :
Auteur : Ibrahima DIOP; ,EL hadji mamadou korka
Date de création : 20/03/2016
Date derni�re modification : 20/04/2016; 15-04-2018
*/
//ob_end_flush();

session_start();


if(!$_SESSION['iduser']){
	header('Location:../index.php');
}
if($_SESSION['iduser']){
	if ((!$_SESSION['creationB']) && ($_SESSION['profil']=='Admin')){
	    echo'<!DOCTYPE html>';
		echo'<html>';
		echo'<head>';
		echo'<script language="JavaScript">document.location="creationBoutique.php"</script>';
		echo'</head>';
		echo'</html>';
	}

require('connection.php');
require('connectionPDO.php');

require('declarationVariables.php');

// var_dump($_SESSION['idBoutique']);

// $sql17="SELECT * FROM `aaa-boutique`";
// $res17 = mysql_query($sql17) or die ("personel requête 2".mysql_error());
// while ($b = mysql_fetch_array($res17)) {
// 	// var_dump($b)
// 	$nomBoutiqueP=$b['nomBoutique']."-pagnet";
// 	$nomBoutiqueL=$b['nomBoutique']."-lignepj";
// 	$sql20="ALTER TABLE `".$nomBoutiqueP."` ADD COLUMN IF NOT EXISTS `validerProforma` INT NULL DEFAULT NULL;";
// 	$sql21="ALTER TABLE `".$nomBoutiqueP."` ADD COLUMN IF NOT EXISTS `terminerProforma` INT NULL DEFAULT NULL;";
// 	$sql22="ALTER TABLE `".$nomBoutiqueP."` ADD COLUMN IF NOT EXISTS `nbColis` INT NULL DEFAULT NULL;";
// 	$sql23="ALTER TABLE `".$nomBoutiqueL."` ADD COLUMN IF NOT EXISTS `depotConfirm` INT NULL DEFAULT NULL;";
// 	$res12 =@mysql_query($sql20) or die ("validerProforma ".mysql_error());
// 	$res13 =@mysql_query($sql21) or die ("terminerProforma ".mysql_error());
// 	$res14 =@mysql_query($sql22) or die ("nbColis ".mysql_error());
// 	$res15 =@mysql_query($sql23) or die ("depotConfirm ".mysql_error());
// 	// var_dump(111);
// }
/**********************/

	// $sql50="ALTER TABLE `aaa-boutique` ADD IF NOT EXISTS `compte` int(11) NOT NULL;";
	// $res50 =@mysql_query($sql50) or die ("creation table compte impossible ".mysql_error());
	/* $sql17="SELECT * FROM `aaa-boutique`";
	 $res17 = mysql_query($sql17) or die ("personel requête 2".mysql_error());
	 while ($b = mysql_fetch_array($res17)) {
	// 	// var_dump($b)
		//$nomBoutiqueP=$b['nomBoutique']."-pagnet";
	 	$sql21="ALTER TABLE `".$nomBoutiqueP."` CHANGE `nbColis` `nbColis` INT(11) NOT NULL;";
	 	$sql22="ALTER TABLE `".$nomBoutiqueP."` CHANGE `validerProforma` `validerProforma` INT(11) NOT NULL;";
	 	$sql23="ALTER TABLE `".$nomBoutiqueP."` CHANGE `terminerProforma` `terminerProforma` INT(11) NOT NULL;";
		

	 	$res21 =@mysql_query($sql21) or die ("1- ".mysql_error());
	 	$res22 =@mysql_query($sql22) or die ("2- ".mysql_error());
	 	$res23 =@mysql_query($sql23) or die ("3- ".mysql_error());
	 }*/

	// UPDATE `Zig supermarche lampfall-stock` SET `quantiteStockTemp`=`quantiteStockCourant` WHERE 1;
	// $tabId = [];
	// $sql17="SELECT * FROM `Zig supermarche lampfall-stock` where quantiteStockCourant>0";
	// $res17 = mysql_query($sql17) or die ("personel requête 2".mysql_error());
	// while ($b = mysql_fetch_array($res17)) {
	// 	// var_dump($b)
	// 	if (in_array($b['idDesignation'], $tabId)) {
			
	// 	} else {
			
	// 		$sqlS="SELECT SUM(quantiteStockCourant) FROM `Zig supermarche lampfall-stock` where idDesignation ='".$b['idDesignation']."'  ";
	// 		$resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
	// 		$S_stock = mysql_fetch_array($resS);
		
	// 		$sqlS0="UPDATE `Zig supermarche lampfall-stock` set quantiteStockTemp=".$S_stock[0]." where idDesignation=".$b['idDesignation'];
			
	// 		$resS0=mysql_query($sqlS0) or die ("update quantiteStockCourant impossible =>".mysql_error());

	// 		$tabId[] = $b['idDesignation'];
			
	// 	}
	// }


if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

	$sqlv="select * from `".$nomtableDesignation."` where classe=5 ";
	$resv=mysql_query($sqlv);
	if(mysql_num_rows($resv)){
	}
	else{
		$sql="insert into `".$nomtableDesignation."` (designation,classe,prixSession,forme,prixPublic,tableau,seuil,categorie)values ('Approvisionnement Caisse',5,0,'Espece','0','Sans','0','Sans')";
		$res=@mysql_query($sql) or die ("insertion impossible Frais".mysql_error());
	}
	$sqlb="select * from `".$nomtableDesignation."` where classe=6 ";
	$resb=mysql_query($sqlb);
	if(mysql_num_rows($resb)){
	}
	else{
		$sql="insert into `".$nomtableDesignation."` (designation,classe,prixSession,forme,prixPublic,tableau,seuil,categorie)values ('Bon en Especes',6,0,'Espece','0','Sans','0','Sans')";
		$res=@mysql_query($sql) or die ("insertion impossible Frais".mysql_error());
	}
	$sqlb="select * from `".$nomtableDesignation."` where classe=7 ";
	$resb=mysql_query($sqlb);
	if(mysql_num_rows($resb)){
	}
	else{
		$sql="insert into `".$nomtableDesignation."` (designation,classe,prixSession,forme,prixPublic,tableau,seuil,categorie)values ('Retrait Caisse',7,0,'Espece','0','Sans','0','Sans')";
		$res=@mysql_query($sql) or die ("insertion impossible Frais".mysql_error());
	}
	$sqlb="select * from `".$nomtableDesignation."` where classe=9 ";
	$resb=mysql_query($sqlb);
	if(mysql_num_rows($resb)){
	}
	else{
		$sql="insert into `".$nomtableDesignation."` (designation,classe,prixSession,forme,prixPublic,tableau,seuil,categorie)values ('Bon en Initilisation',9,0,'Espece','0','Sans','0','Sans')";
		$res=@mysql_query($sql) or die ("insertion impossible Frais".mysql_error());
	}
	$sqlb="select * from `".$nomtableDesignation."` where classe=10 ";
	$resb=mysql_query($sqlb);
	if(mysql_num_rows($resb)){
	}
	else{
		$sql="insert into `".$nomtableDesignation."` (designation,classe,prixSession,forme,prixPublic,tableau,seuil,categorie)values ('Proforma',10,0,'Espece','0','Sans','0','Sans')";
		$res=@mysql_query($sql) or die ("insertion impossible Frais".mysql_error());
	}
	

}
else {

	$sqlv="select * from `".$nomtableDesignation."` where classe=5 ";
	$resv=mysql_query($sqlv);
	if(mysql_num_rows($resv)){
	}
	else{
		$sql="insert into `".$nomtableDesignation."` (designation,classe,prix,uniteStock,prixuniteStock,nbreArticleUniteStock,seuil,categorie)values ('Approvisionnement Caisse',5,0,'Espece','0','1','10','Sans')";
		$res=@mysql_query($sql) or die ("insertion impossible Frais".mysql_error());
	}
	$sqlb="select * from `".$nomtableDesignation."` where classe=6 ";
	$resb=mysql_query($sqlb);
	if(mysql_num_rows($resb)){
	}
	else{
		$sql="insert into `".$nomtableDesignation."` (designation,classe,prix,uniteStock,prixuniteStock,nbreArticleUniteStock,seuil,categorie)values ('Bon en Especes',6,0,'Espece','0','1','10','Sans')";
		$res=@mysql_query($sql) or die ("insertion impossible Frais".mysql_error());
	}
	$sqlb="select * from `".$nomtableDesignation."` where classe=7 ";
	$resb=mysql_query($sqlb);
	if(mysql_num_rows($resb)){
	}
	else{
		$sql="insert into `".$nomtableDesignation."` (designation,classe,prix,uniteStock,prixuniteStock,nbreArticleUniteStock,seuil,categorie)values ('Retrait Caisse',7,0,'Espece','0','1','10','Sans')";
		$res=@mysql_query($sql) or die ("insertion impossible Frais".mysql_error());
	}
	$sqlb="select * from `".$nomtableDesignation."` where classe=8 ";
	$resb=mysql_query($sqlb);
	if(mysql_num_rows($resb)){
	}
	else{
		$sql="insert into `".$nomtableDesignation."` (designation,classe,prix,uniteStock,prixuniteStock,nbreArticleUniteStock,seuil,categorie)values ('Retour Pagnet',8,0,'Espece','0','1','10','Sans')";
		$res=@mysql_query($sql) or die ("insertion impossible Frais".mysql_error());
	}
	$sqlb="select * from `".$nomtableDesignation."` where classe=9 ";
	$resb=mysql_query($sqlb);
	if(mysql_num_rows($resb)){
	}
	else{
		$sql="insert into `".$nomtableDesignation."` (designation,classe,prix,uniteStock,prixuniteStock,nbreArticleUniteStock,seuil,categorie)values ('Bon Initialisation',9,0,'Espece','0','1','10','Sans')";
		$res=@mysql_query($sql) or die ("insertion impossible Frais".mysql_error());
	}
	$sqlb="select * from `".$nomtableDesignation."` where classe=10 ";
	$resb=mysql_query($sqlb);
	if(mysql_num_rows($resb)){
	}
	else{
		$sql="insert into `".$nomtableDesignation."` (designation,classe,prix,uniteStock,prixuniteStock,nbreArticleUniteStock,seuil,categorie)values ('Proforma',10,0,'Espece','0','1','10','Sans')";
		$res=@mysql_query($sql) or die ("insertion impossible Frais".mysql_error());
	}
	$sqlb="select * from `".$nomtableDesignation."` where classe=20 ";
	$resb=mysql_query($sqlb);
	if(mysql_num_rows($resb)){
	}
	else{
		$sql="insert into `".$nomtableDesignation."` (designation,classe,prix,uniteStock,prixuniteStock,nbreArticleUniteStock,seuil,categorie)values ('CodeBarre',20,0,'Espece','0','1','10','Sans')";
		$res=@mysql_query($sql) or die ("insertion impossible Frais".mysql_error());
	}
	
}


$idStock         =@$_POST["idStock"];

$designation      =@htmlentities($_POST["designation"]);
$stock            =@$_POST["stock"];
$uniteStock       =@$_POST["uniteStock"];
//$nombreArticle    =@$_POST["nombreArticle"];
$dateExpiration   =@$_POST["dateExpiration"];


$modifier         =@$_POST["modifier"];
$annuler          =@$_POST["annuler"];
/***************/

$idStock2       =@$_GET["idStock"];



if (isset($_POST['btnTerminerInP'])) {

	$vente          =$_POST['vente'];
	$versement      =$_POST['versement'];
	$depense        =$_POST['depense'];
	$bon            =$_POST['bon'];
	//$totalService   =$_POST['totalService'];
	$totalCaisse    =$_POST['totalCaisse'];
	
	
	$test=0;
	$sql8="select * from `".$nomtablePage."` where datepage='".$dateString."'";
	$res8=mysql_query($sql8);
	if(mysql_num_rows($res8)){
		$test=1;
		$sql="update `".$nomtablePage."` set totalCaisse=".$totalCaisse.",totalVente=".$vente.",totalVersement=".$versement.",totalFrais=".$depense.",totalBon=".$bon." where datepage='".$dateString."'";
		$res=@mysql_query($sql)or die ("modification page impossible");
	
	}else{
	if ($test==0){
	//$sql3="insert into `".$nomtablePage."`(datepage,totalCaisse,totalVente,totalService,totalVersement,totalFrais,totalBon) values('".$dateString."',".$totalCaisse.",".$vente.",".$totalService.",".$versement.",".$depense.",".$bon.")";
	$sql3="insert into `".$nomtablePage."`(datepage,totalCaisse,totalVente,totalVersement,totalFrais,totalBon) values('".$dateString."',".$totalCaisse.",".$vente.",".$versement.",".$depense.",".$bon.")";
	//echo $sql3;
	$res3=mysql_query($sql3) or die ("insertion page impossible =>".mysql_error());
	}
  }
	
	
	
}


	
/*
function date_diff($date1, $date2) {
$count = 0;
//Ex 2012-10-01 and 2012-10-20
if(strtotime($date1) < strtotime($date2))
{
  $current = $date1;
  while(strtotime($current) < strtotime($date2)){
      $current = date("Y-m-d",strtotime("+1 day", strtotime($current)));
      $count++;
  }
}
//Ex 2012-10-20 and 2012-10-01
else if(strtotime($date2) < strtotime($date1))
{
  $current = $date2;
  while(strtotime($current) < strtotime($date1)){
      $current = date("Y-m-d",strtotime("+1 day", strtotime($current)));
      $count++;
  }
  $current = $current * -1;
}
return $count; }
*/
/*****************************/
require('entetehtml.php');
require('header.php');
 $_SESSION['page']='vente';
?>
<body >


<?php 

//-----------------------------------------------------------------------------------
echo'<div class="container" align="center">';

	// $date = new DateTime();
	// $timezone = new DateTimeZone('Africa/Dakar');
	// $date->setTimezone($timezone);
	// $heureString=$date->format('H:i:s'); 
	/**Debut informations sur la date d'Aujourdhui **/
    $beforeTime = '00:00:00';
    $afterTime = '06:00:00';

    /**Debut informations sur la date d'Aujourdhui **/
    if($_SESSION['Pays']=='Canada'){ 
        $date = new DateTime();
        $timezone = new DateTimeZone('Canada/Eastern');
    }
    else{
        $date = new DateTime();
        $timezone = new DateTimeZone('Africa/Dakar');
    }
    $date->setTimezone($timezone);
    $heureString=$date->format('H:i:s');

    if ($heureString >= $beforeTime && $heureString < $afterTime) {
        // var_dump ('is between');
    $date = new DateTime (date('d-m-Y',strtotime("-1 days")));
    }


	$annee =$date->format('Y');
	$mois =$date->format('m');
	$jour =$date->format('d');
	$dateString2=$jour.'-'.$mois.'-'.$annee;

    echo"<script type='text/javascript'>
        // $(window).on('load',function(){
		// 	// alert(666)
        //     // $.ajax({
        //     //     url: 'ajax/ajouterLigneAjax.php',
        //     //     method: 'POST',
        //     //     data: {
        //     //         operation: 83,
        //     //         jour: ".$jour.",
        //     //         mois: ".$mois.",
        //     //         annee: ".$annee.",
        //     //     },
        //     //     success: function(data) {
        //     //         tab = data.split('<>');
        //     //         if (tab[0] == 1) {
        //     //             $('#totalApprov').text(tab[1]);
        //     //             $('#totalRetrait').text(tab[2]);
        //     //             $('#totalVente').text(tab[3]);
        //     //             $('#totalRetour').text(tab[4]);
        //     //             $('#totalService').text(tab[5]);
        //     //             $('#totalDepense').text(tab[6]);
        //     //             $('#totaBonEspeces').text(tab[7]);
        //     //             $('#totalBonProduit').text(tab[8]);
        //     //             $('#totalVersementClient').text(tab[9]);
        //     //             $('#totalVersementFournisseur').text(tab[10]);
        //     //             $('#totalCaisse').text(tab[11]);
        //     //             $('#totalBenefice').text(tab[12]);
        //     //             $('#totalVersementMutuelle').text(tab[13]);
        //     //             $('#totalVenteMobile').text(tab[14]);
		// 	// 			$('#totalBonMutuelle').text(tab[15]);
        //     //         }
        //     //     },
        //     //     dataType: 'text'
        //     // });
        // });
        $(document).ready(function(){
            var jour=''+".$jour.";
            var mois=''+".$mois.";
            var annee=".$annee.";
            if(jour.length==1){
                jour='0'+jour;
            }
            if(mois.length==1){
                mois='0'+mois;
            }
            dateJour=jour+'-'+mois+'-'+annee;
            $('#btnModalApprovisionnement').click(function(){
                $('#myModalApprovisionnement').modal();
                $('#tableApprovisionnement').dataTable({
                    'bProcessing': true,
                    'destroy': true,
                    'sAjaxSource': 'ajax/listerJourApprovisionnementAjax.php?dateJour='+dateJour,
                    'aoColumns': [
                        { mData: '0' },
                        { mData: '1'  },
                    ],
                
                }); 
            });
            $('#btnModalRetrait').click(function(){
                $('#myModalRetrait').modal();
                $('#tableRetrait').dataTable({
                    'bProcessing': true,
                    'destroy': true,
                    'sAjaxSource': 'ajax/listerJourRetraitAjax.php?dateJour='+dateJour,
                    'aoColumns': [
                        { mData: '0' },
                        { mData: '1'  },
                    ],
                
                }); 
            });
            $('#btnModalVt').click(function(){
              $('#myModalVt').modal();
			//   alert(555)
                $('#tableVente').dataTable({
                    'bProcessing': true,
                    'destroy': true,
                    'sAjaxSource': 'ajax/listerJourVenteAjax.php?dateJour='+dateJour,
                    'aoColumns': [
                        { mData: '0' },
                        { mData: '1'  },
                        { mData: '2'  },
						{ mData: '3' },
                        { mData: '4'  }
                    ],
                
                }); 
            });
			$('#btnModalVtMobile').click(function(){
				$('#myModalVtMobile').modal();
				  $('#tableVenteMobile').dataTable({
					  'bProcessing': true,
					  'destroy': true,
					  'sAjaxSource': 'ajax/listerJourVenteMobileAjax.php?dateJour='+dateJour,
					  'aoColumns': [
						  { mData: '0' },
						  { mData: '1'  }
					  ],
				  
				  }); 
			  });
            $('#btnModalSv').click(function(){
                $('#myModalSv').modal();
                  $('#tableService').dataTable({
                      'bProcessing': true,
                      'destroy': true,
                      'sAjaxSource': 'ajax/listerJourServiceAjax.php?dateJour='+dateJour,
                      'aoColumns': [
                          { mData: '0' },
                          { mData: '1'  },
                      ],
                  
                  }); 
            });
            $('#btnModalDp').click(function(){
                $('#myModalDp').modal();
                  $('#tableDepense').dataTable({
                      'bProcessing': true,
                      'destroy': true,
                      'sAjaxSource': 'ajax/listerJourDepenseAjax.php?dateJour='+dateJour,
                      'aoColumns': [
                          { mData: '0' },
                          { mData: '1'  },
                      ],
                  
                  }); 
            });
            $('#btnModalBE').click(function(){
                $('#myModalBE').modal();
                  $('#tableBonEspece').dataTable({
                      'bProcessing': true,
                      'destroy': true,
                      'sAjaxSource': 'ajax/listerJourBonEspeceAjax.php?dateJour='+dateJour,
                      'aoColumns': [
                          { mData: '0' },
                          { mData: '1'  },
                      ],
                  
                  }); 
            });
            $('#btnModalBP').click(function(){
                $('#myModalBP').modal();
                $('#tableBonProduit').dataTable({
                    'bProcessing': true,
                    'destroy': true,
                    'sAjaxSource': 'ajax/listerJourBonProduitAjax.php?dateJour='+dateJour,
                    'aoColumns': [
                        { mData: '0' },
                        { mData: '1'  },
                    ],
                
                }); 
            });
			$('#btnModalBM').click(function(){
                $('#myModalBM').modal();
                $('#tableBonMutuelle').dataTable({
                    'bProcessing': true,
                    'destroy': true,
                    'sAjaxSource': 'ajax/listerJourBonMutuelleAjax.php?dateJour='+dateJour,
                    'aoColumns': [
                        { mData: '0' },
                        { mData: '1'  },
                    ],
                
                }); 
            });
            $('#btnModalVClient').click(function(){
                $('#myModalVClient').modal();
                $('#tableVersementClient').dataTable({
                    'bProcessing': true,
                    'destroy': true,
                    'sAjaxSource': 'ajax/listerJourVersementClientAjax.php?dateJour='+dateJour,
                    'aoColumns': [
                        { mData: '0' },
                        { mData: '1'  },
                        { mData: '2'  },
                    ],
                
                }); 
            });
            $('#btnModalVMutuelle').click(function(){
                $('#myModalVMutuelle').modal();
                $('#tableVersementMutuelle').dataTable({
                    'bProcessing': true,
                    'destroy': true,
                    'sAjaxSource': 'ajax/listerJourVersementMutuelleAjax.php?dateJour='+dateJour,
                    'aoColumns': [
                        { mData: '0' },
                        { mData: '1'  },
                    ],
                
                }); 
            });
            $('#btnModalVFournisseur').click(function(){
                $('#myModalVFournisseur').modal();
                $('#tableVersementFournisseur').dataTable({
                    'bProcessing': true,
                    'destroy': true,
                    'sAjaxSource': 'ajax/listerJourVersementFournisseurAjax.php?dateJour='+dateJour,
                    'aoColumns': [
                        { mData: '0' },
                        { mData: '1'  },
                    ],
                
                }); 
            });
            $('#btnModalPs').click(function(){
                $('#myModalPs').modal();
                $('#tablePersonnel').dataTable({
                    'bProcessing': true,
                    'destroy': true,
                    'sAjaxSource': 'ajax/listerJourPersonnelAjax.php?dateJour='+dateJour,
                    'aoColumns': [
                        { mData: '0' },
                        { mData: '1' },
                        { mData: '2' },
                        { mData: '3' },";
					if ($_SESSION['compte']==1) {
					echo "
						{ mData: '4' },
						{ mData: '5' },
						{ mData: '6' },
						{ mData: '7' },
						{ mData: '8' },
						{ mData: '9' },
						{ mData: '10' },
						{ mData: '11' },";
					}else {
						# code...
						echo "
						{ mData: '4' },
						{ mData: '5' },
						{ mData: '6' },
						{ mData: '7' },
						{ mData: '8' },
						{ mData: '9' },";
					}
					echo "
                    ],
                
                }); 
            });
            $('#btnModalBN').click(function(){
                $('#myModalBN').modal();
                $('#tableBenefice').dataTable({
                    'bProcessing': true,
                    'destroy': true,
                    'sAjaxSource': 'ajax/listerJourBeneficeAjax.php?dateJour='+dateJour,
                    'aoColumns': [
                        { mData: '0' },
                        { mData: '1'  },
                        { mData: '2'  },
                    ],
                
                }); 
            });
        });		

		$(function() {
			$('#col').on( 'click', function() {
				// alert(777)
				e = $('#expand').attr('class');

				if(e == 'glyphicon glyphicon-chevron-up') {
					$('#expand').attr('class','glyphicon glyphicon-chevron-down');
				} else {
					$('#expand').attr('class','glyphicon glyphicon-chevron-up');
					$.ajax({
						url: 'ajax/ajouterLigneAjax.php',
						method: 'POST',
						data: {
							operation: 83,
							jour: ".$jour.",
							mois: ".$mois.",
							annee: ".$annee.",
						},
						success: function(data) {
							tab = data.split('<>');
							if (tab[0] == 1) {
								$('#totalApprov').text(tab[1]);
								$('#totalRetrait').text(tab[2]);
								$('#totalVente').text(tab[3]);
								$('#totalRetour').text(tab[4]);
								$('#totalService').text(tab[5]);
								$('#totalDepense').text(tab[6]);
								$('#totaBonEspeces').text(tab[7]);
								$('#totalBonProduit').text(tab[8]);
								$('#totalVersementClient').text(tab[9]);
								$('#totalVersementFournisseur').text(tab[10]);
								$('#totalCaisse').text(tab[11]);
								$('#totalBenefice').text(tab[12]);
								$('#totalVersementMutuelle').text(tab[13]);
								$('#totalVenteMobile').text(tab[14]);
								$('#totalBonMutuelle').text(tab[15]);
							}
						},
						dataType: 'text'
					});
				}
			});
		});
    </script>";

	?>

		<?php  

		$sql0="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique ='".$_SESSION['idBoutique']."' and YEAR(datePs)='".$annee."' and MONTH(datePs)!='".$mois."' and aPayementBoutique=0 ";

		$res0=mysql_query($sql0);
		$ps = mysql_fetch_array($res0) ;
		$idPS = @$ps['idPS'];
		// var_dump($annee);
		// var_dump($mois);
		// var_dump($idPS);

		if(mysql_num_rows($res0)){

			// var_dump($jour);
			if($jour > 5){
				
				echo " <script type='text/javascript'>
					$(document).ready(function(){
						// effectue_paiementWave(".$idPS.")
					
					});
				</script>"; 
			} 

		}
		
		// if (isset($_POST['loadTampon'])) {
		// 	// var_dump($_SESSION['nomB']);

			
		// 	$sql="SELECT * FROM `".$nomtablePagnetTampon."` where synchronise=0 and verrouiller=1";
				
		// 	// var_dump($sql);
		// 	$statement = $bdd->prepare($sql);
		// 	$statement->execute();
		// 	$tamponsP = $statement->fetchAll(PDO::FETCH_ASSOC); 

		// 	$bdd->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		// 	try {
		// 		// From this point and until the transaction is being committed every change to the database can be reverted
		// 		$bdd->beginTransaction();  /**** generate barcode ****/

		// 		foreach ($tamponsP as $tp) {
		// 			// var_dump($tp);

		// 			$sqlL="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet = ".$tp['idPagnet'];
				
		// 	// var_dump($sqlL);
		// 			$statementL = $bdd->prepare($sqlL);
		// 			$statementL->execute();
		// 			$tamponsL = $statementL->fetchAll(PDO::FETCH_ASSOC); 

		// 			$req4 = $bdd->prepare("INSERT INTO `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,apayerPagnet,verrouiller,idClient,remise,restourne,versement,idCompte,avance,idCompteAvance)
		// 			values (:d,:h,:u,:t,:tp,:ap,:v,:c,:r,:res,:ver,:idC,:av,:idcav)");
		// 		// var_dump($req4);
		// 			$req4->execute(array(
		// 				'd' => $tp['datepagej'],
		// 				'h' => $tp['heurePagnet'],
		// 				'u' => $tp['iduser'],
		// 				't' => $tp['type'],
		// 				'tp' => $tp['totalp'],
		// 				'ap' => $tp['apayerPagnet'],
		// 				'v' => 1,
		// 				'c' => $tp['idClient'],
		// 				'r' => $tp['remise'],
		// 				'res' => $tp['restourne'],
		// 				'ver' => $tp['versement'],
		// 				'idC' => $tp['idCompte'],
		// 				'av' => $tp['avance'],
		// 				'idcav' => $tp['idCompteAvance']
		// 			))  or die(print_r("Insert pagnet 2 ".$req4->errorInfo()));
		// 			$req4->closeCursor();
		// 			$idPagnet = $bdd->lastInsertId();
		// 			// var_dump($idPagnet);

		// 			foreach ($tamponsL as $tl) {
		// 				// var_dump($tl);
		// 				$preparedStatement = $bdd->prepare(
		// 					"insert into `".$nomtableLigne."` (designation, idDesignation, unitevente, prixunitevente, quantite, prixtotal, idPagnet, classe)
		// 					 values (:d,:idd,:uv,:pu,:qty,:p,:idp,:c)"
		// 				);
		// 	// var_dump($preparedStatement);
						
		// 				$preparedStatement->execute([
		// 					'd' => $tl['designation'],
		// 					'idd' => $tl['idDesignation'],
		// 					'uv' => $tl['unitevente'],
		// 					'pu' => $tl['prixunitevente'],
		// 					'qty' => $tl['quantite'],
		// 					'p' => $tl['prixtotal'],
		// 					'idp' => $idPagnet,
		// 					'c' => $tl['classe']
		// 				]);
		// 			}
					
		// 			$sqlU="UPDATE `".$nomtablePagnetTampon."` SET synchronise=1 where idPagnet=".$tp['idPagnet'];
					
		// 			$statementU = $bdd->prepare($sqlU);
		// 			$statementU->execute();
		// 		}

		// 		// Make the changes to the database permanent
		// 		$bdd->commit();
		// 		// header("Refresh:0");
		// 	}
		// 	catch ( PDOException $e ) { 
		// 		// Failed to insert the order into the database so we rollback any changes
		// 		$bdd->rollback();
		// 		throw $e;

		// 		// echo '0';
		// 	}
    
		// }	
							

	?>
	<table >
		<tr>
			<td>
				<div class="container">
					<?php 
					
					if ($_SESSION['tampon'] == 1) {
						
					?>
						<div align="center" hidden>
							<!-- Button trigger modal -->
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tamponModal">
								<span class="glyphicon glyphicon-upload"> </span> Charger
							</button>

							<!-- Modal -->
							<div class="modal fade" id="tamponModal" tabindex="-1" role="dialog" aria-labelledby="tamponModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="tamponModalLabel">Chargement</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<form action="#" method="post">
										<div class="modal-body">
											vous confirmez le chargment ?
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Non</button>
											<button type="submit" name="loadTampon" class="btn btn-success">Oui, je confirme</button>
										</div>
									</form>
								</div>
							</div>
							</div>
						</div>
						<br>
					<?php } ?>
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading" id="col" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
								<h3 class="panel-title">ETAT DE LA CAISSE DU <?php echo $dateString2; ?><span class="glyphicon glyphicon-chevron-down" id="expand" style="float:right;"><a>Détails</a></span></h3>
							</div>
							<div class="panel-body collapse" id="collapseOne" aria-labelledby="headingOne">
								<table class="table table-bordered table-responsive ">
									<?php if (($_SESSION['type']=="Divers" || $_SESSION['type']=="Superette" || $_SESSION['type']=="Restaurant" || $_SESSION['type']=="Cosmetique" || $_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Detaillant"|| $_SESSION['categorie']=="Grossiste")) { ?>
										<tr>
											<?php
												echo '
												<td> 
													Approvisionnement :
												</td>';
											?>
											<td>
												<span id="totalApprov"></span> <?php echo $_SESSION['symbole']; ?>
												&nbsp;&nbsp;
												<button class="btn btn-default btn-xs" id="btnModalApprovisionnement" style="margin-right:20px;" >
													<span style="color:green;font-size: 12px;">Details</span>
												</button>
												<?php
													echo'
														<div class="modal" id="myModalApprovisionnement">
															<div class="modal-dialog">
																<div class="modal-content">
																	<!-- Modal Header -->
																	<div class="modal-header">
																		<h4 class="modal-title"><b> Details des Ventes</b> </h4>
																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																	</div>
																	<!-- Modal body -->
																	<div class="modal-body">
																		<div class="table-responsive">
																			<table id="tableApprovisionnement" class="display" width="100%" border="1">
																				<thead>
																					<tr>
																						<th>Reference</th>
																						<th>Montant</th>
																					</tr>
																				</thead>
																			</table>
																		</div>
																	</div>
																	<!-- Modal footer -->
																	<div class="modal-footer">
																		<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
																	</div>
																</div>
															</div>
														</div>
															';
												?>
											</td>
										</tr>
										<tr>
											<?php
												echo '
												<td> 
													Retrait Caisse :
												</td>';
											?>
											<td>
												<span id="totalRetrait"></span> <?php echo $_SESSION['symbole']; ?>
												&nbsp;&nbsp;
												<button class="btn btn-default btn-xs" id="btnModalRetrait" style="margin-right:20px;" >
													<span style="color:green;font-size: 12px;">Details</span>
												</button>
												<?php
													echo'
														<div class="modal" id="myModalRetrait">
															<div class="modal-dialog">
																<div class="modal-content">
																	<!-- Modal Header -->
																	<div class="modal-header">
																		<h4 class="modal-title"><b> Details Retrait Caisse</b> </h4>
																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																	</div>
																	<!-- Modal body -->
																	<div class="modal-body">
																		<div class="table-responsive">
																			<table id="tableRetrait" class="display" width="100%" border="1">
																				<thead>
																					<tr>
																						<th>Reference</th>
																						<th>Montant</th>
																					</tr>
																				</thead>
																			</table>
																		</div>
																	</div>
																	<!-- Modal footer -->
																	<div class="modal-footer">
																		<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
																	</div>
																</div>
															</div>
														</div>
															';
												?>
											</td>
										</tr>
									<?php }?>
									<tr>
										<?php
											echo '
											<td> 
												Ventes caisse:
											</td>';
										?>
										<td>
											<span id="totalVente"></span> <?php echo $_SESSION['symbole']; ?>
											&nbsp;&nbsp;
											<button class="btn btn-default btn-xs" id="btnModalVt" style="margin-right:20px;" >
												<span style="color:green;font-size: 12px;">Details</span>
											</button>
											<?php
												if($_SESSION['Pays']=='Canada'){ 
													echo'
													<div class="modal" id="myModalVt">
														<div class="modal-dialog">
															<div class="modal-content">
																<!-- Modal Header -->
																<div class="modal-header">
																	<h4 class="modal-title"><b> Details des Ventes</b> </h4>
																	<button type="button" class="close" data-dismiss="modal">&times;</button>
																</div>
																<!-- Modal body -->
																<div class="modal-body">
																	<div class="table-responsive">
																		<table id="tableVente" class="display" width="100%" border="1">
																			<thead>
																				<tr>
																					<th>Reference</th>
																					<th>Quantite</th>
																					<th>Montant</th>
																					<th>Tva Total</th>
																					<th>Prix Total</th>
																				</tr>
																			</thead>
																		</table>
																	</div>
																</div>
																<!-- Modal footer -->
																<div class="modal-footer">
																	<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
																</div>
															</div>
														</div>
													</div>
														';
												}
												else{
													echo'
													<div class="modal" id="myModalVt">
														<div class="modal-dialog">
															<div class="modal-content">
																<!-- Modal Header -->
																<div class="modal-header">
																	<h4 class="modal-title"><b> Details des Ventes</b> </h4>
																	<button type="button" class="close" data-dismiss="modal">&times;</button>
																</div>
																<!-- Modal body -->
																<div class="modal-body">
																	<div class="table-responsive">
																		<table id="tableVente" class="display" width="100%" border="1">
																			<thead>
																				<tr>
																					<th>Reference</th>
																					<th>Quantite</th>
																					<th>Montant</th>
																					<th>Tva Total</th>
																					<th>Prix Total</th>
																				</tr>
																			</thead>
																		</table>
																	</div>
																</div>
																<!-- Modal footer -->
																<div class="modal-footer">
																	<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
																</div>
															</div>
														</div>
													</div>
														';
												}
											?>
										</td>
									</tr>                                                               
                                <?php if ($_SESSION['compte']==1) { ?>
									<tr>
										<?php
											echo '
											<td> 
												Ventes mobile:
											</td>';
										?>
										<td>
											<span id="totalVenteMobile"></span> <?php echo $_SESSION['symbole']; ?>
											&nbsp;&nbsp;
											<button class="btn btn-default btn-xs" id="btnModalVtMobile" style="margin-right:20px;">
												<span style="color:green;font-size: 12px;">Details</span>
											</button>
											<?php
												echo'
													<div class="modal" id="myModalVtMobile">
														<div class="modal-dialog">
															<div class="modal-content">
																<!-- Modal Header -->
																<div class="modal-header">
																	<h4 class="modal-title"><b> Details des Ventes mobiles</b> </h4>
																	<button type="button" class="close" data-dismiss="modal">&times;</button>
																</div>
																<!-- Modal body -->
																<div class="modal-body">
																	<div class="table-responsive">
																		<table id="tableVenteMobile" class="display" width="100%" border="1">
																			<thead>
																				<tr>
																					<th>Reference</th>
																					<th>Montant</th>
																				</tr>
																			</thead>
																		</table>
																	</div>
																</div>
																<!-- Modal footer -->
																<div class="modal-footer">
																	<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
																</div>
															</div>
														</div>
													</div>
														';
											?>
										</td>
									</tr>                                                               
                                	<?php } ?>
									<?php
										if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
											
										}
										else{
											$sqlt="select * from `".$nomtablePagnet."` where idClient=0 &&  verrouiller=1 && datepagej ='".$dateString2."' && type=1";
											$rest=mysql_query($sqlt);
											if(mysql_num_rows($rest)){?>
												<tr>
													<td>Retour pagnet : </td>
													<td>
														<span id="totalRetour"></span>  <?php echo $_SESSION['symbole']; ?>
														&nbsp;&nbsp;<a href="" data-toggle="modal" data-target="#myModalRp">Details</a>
														<?php
															echo'
																<div class="modal" id="myModalRp">
																	<div class="modal-dialog">
																		<div class="modal-content">
																			<!-- Modal Header -->
																			<div class="modal-header">
																				<h4 class="modal-title"><b> Details des Ventes</b> </h4>
																				<button type="button" class="close" data-dismiss="modal">&times;</button>
																			</div>
																			<!-- Modal body -->
																			<div class="modal-body">
																				<div class="table-responsive">
																					
																				</div>
																			</div>
																			<!-- Modal footer -->
																			<div class="modal-footer">
																				<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
																			</div>
																		</div>
																	</div>
																</div>
																	';
														?>
													</td>
												</tr> 
												<?php	
											}
										}
									?>
									<tr>
										<?php
											$sqlv="select * from `".$nomtableDesignation."` where classe=1 ";
											$resv=mysql_query($sqlv);
											if(mysql_num_rows($resv)){ ?>
												<td>Services : </td>
												<td>
													<span id="totalService"></span>  <?php echo $_SESSION['symbole']; ?>
													&nbsp;&nbsp;
													<button class="btn btn-default btn-xs" id="btnModalSv" style="margin-right:20px;" >
														<span style="color:green;font-size: 12px;">Details</span>
													</button>
													<?php
														echo'
															<div class="modal" id="myModalSv">
																<div class="modal-dialog">
																	<div class="modal-content">
																		<!-- Modal Header -->
																		<div class="modal-header">
																			<h4 class="modal-title"><b> Details des Services</b> </h4>
																			<button type="button" class="close" data-dismiss="modal">&times;</button>
																		</div>
																		<!-- Modal body -->
																		<div class="modal-body">
																			<div class="table-responsive">
																				<table id="tableService" class="display" width="100%" border="1">
																					<thead>
																						<tr>
																							<th>Reference</th>
																							<th>Montant</th>
																						</tr>
																					</thead>
																				</table>
																			</div>
																		</div>
																		<!-- Modal footer -->
																		<div class="modal-footer">
																			<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
																		</div>
																	</div>
																</div>
															</div>
																';
													?>
												</td>
												<?php		
											}
											
										?>
									</tr>
									<?php
										if (($_SESSION['type']=="Multi-service") && ($_SESSION['categorie']=="Detaillant")) {
											$reqT="SELECT * from `aaa-transaction` where typeTransaction='Transaction' ";
											$resT=mysql_query($reqT) or die ("select stock impossible =>".mysql_error());
											$somInitiale=0;
											$somFinale=0;
											while ($transaction = mysql_fetch_array($resT)){
												$sql0="SELECT * FROM `".$nomtableDesignation."` where description=".$transaction['idTransaction'];
												$res0=mysql_query($sql0) or die ("select stock impossible =>".mysql_error());
												if(mysql_num_rows($res0)){
													$trans = mysql_fetch_array($res0);
													if($trans['seuil']==1){
														$sql1="SELECT * FROM `".$nomtableTransfert."` where dateTransfert='".$dateString."' AND idTransaction='".$transaction['idTransaction']."' ORDER BY idTransfert DESC";
														$res1=mysql_query($sql1) or die ("select stock impossible =>".mysql_error());
														$transfert = mysql_fetch_array($res1);

														$somInitiale=$somInitiale + $transfert['montantAvant'];
														$somFinale=$somFinale + $transfert['compte'];
													}
												}
											}


											echo'
												<tr>
													<td>Transferts :</td>
													<td>
														&nbsp;&nbsp;<a href="" data-toggle="modal" data-target="#myModalT">Details</a>

														<div class="modal" id="myModalT">
															<div class="modal-dialog">
																<div class="modal-content">
																	<!-- Modal Header -->
																	<div class="modal-header">
																		<h4 class="modal-title"><b> Details Transferts</b> </h4>
																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																	</div>
																	<!-- Modal body -->
																	<div class="modal-body">
																		<table class="table table-bordered table-responsive ">
																			<thead>
																				<tr>
																					<th>TRANSFERT</th>
																					<th>MONTANT INITIAL</th>
																					<th>MONTANT FINAL</th>
																					<th>RAPPORT</th>
																				</tr>
																			</thead>
																			';
																			$reqT0="SELECT * from `aaa-transaction` where typeTransaction='Transaction' ";
																			$resT0=mysql_query($reqT) or die ("select stock impossible =>".mysql_error());
																			while ($transaction = mysql_fetch_array($resT0)){
																				$sql0="SELECT * FROM `".$nomtableDesignation."` where description=".$transaction['idTransaction'];
																				$res0=mysql_query($sql0) or die ("select stock impossible =>".mysql_error());
																				if(mysql_num_rows($res0)){
																					$trans = mysql_fetch_array($res0);
																					if($trans['seuil']==1){
																						$sql1="SELECT * FROM `".$nomtableTransfert."` where dateTransfert='".$dateString."' AND idTransaction='".$transaction['idTransaction']."' ORDER BY idTransfert DESC";
																						$res1=mysql_query($sql1) or die ("select stock impossible =>".mysql_error());
																						$transfert = mysql_fetch_array($res1);
																						echo '
																						<tr>
																							<td><b>'.$trans['designation'].' </b></td>
																							<td><b>'.number_format(($transfert['montantAvant'] * $_SESSION['devise']), 2, ',', ' ').' '.$_SESSION['symbole'].' </b>&nbsp;  </td>
																							<td><b>'.number_format(($transfert['compte'] * $_SESSION['devise']), 2, ',', ' ').' '.$_SESSION['symbole'].' </b>&nbsp;  </td>
																							<td><b>'.number_format((($transfert['compte'] - $transfert['montantAvant']) * $_SESSION['devise']), 2, ',', ' ').' '.$_SESSION['symbole'].' </b>&nbsp;  </td>
																						</tr>
																						';
																					}
																				}
																			}
																			echo '
																		</table>
																	</div>
																	<!-- Modal footer -->
																	<div class="modal-footer">
																		<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
																	</div>

																</div>
															</div>
														</div>
													</td>
												</tr> 
												';
										}
									?>
									<?php
										$sqld="select * from `".$nomtableDesignation."` where classe=2";
										$resd=mysql_query($sqld);
										if(mysql_num_rows($resd)){
											echo'
											<tr>
												<td>Depenses :</td>';
												echo '<td>
													<span id="totalDepense"></span> '.$_SESSION['symbole'].'
													&nbsp;&nbsp;
													<button class="btn btn-default btn-xs" id="btnModalDp" style="margin-right:20px;" >
														<span style="color:green;font-size: 12px;">Details</span>
													</button>';
														echo'
														<div class="modal" id="myModalDp">
															<div class="modal-dialog">
																<div class="modal-content">
																	<!-- Modal Header -->
																	<div class="modal-header">
																		<h4 class="modal-title"><b> Details des Depenses</b> </h4>
																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																	</div>
																	<!-- Modal body -->
																	<div class="modal-body">
																		<div class="table-responsive">
																			<table id="tableDepense" class="display" width="100%" border="1">
																				<thead>
																					<tr>
																						<th>Reference</th>
																						<th>Montant</th>
																					</tr>
																				</thead>
																			</table>
																		</div>
																	</div>
																	<!-- Modal footer -->
																	<div class="modal-footer">
																		<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
																	</div>
																</div>
															</div>
														</div>
													</td>
											</tr> 
											';	
										}
									?>
									<?php
										if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {?>
										<?php	
										}
										else{ ?>
											<tr>
												<td> Bon en Especes : </td>
												<td>
													<span id="totaBonEspeces"></span>  <?php echo $_SESSION['symbole']; ?>
													&nbsp;&nbsp;
													<button class="btn btn-default btn-xs" id="btnModalBE" style="margin-right:20px;" >
														<span style="color:green;font-size: 12px;">Details</span>
													</button>
													<?php
														echo'
															<div class="modal" id="myModalBE">
																<div class="modal-dialog">
																	<div class="modal-content">
																		<!-- Modal Header -->
																		<div class="modal-header">
																			<h4 class="modal-title"><b> Details des Bons</b> </h4>
																			<button type="button" class="close" data-dismiss="modal">&times;</button>
																		</div>
																		<!-- Modal body -->
																		<div class="modal-body">
																			<div class="table-responsive">
																				<table id="tableBonEspece" class="display" width="100%" border="1">
																					<thead>
																						<tr>
																							<th>Client</th>
																							<th>Montant</th>
																						</tr>
																					</thead>
																				</table>
																			</div>
																		</div>
																		<!-- Modal footer -->
																		<div class="modal-footer">
																			<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
																		</div>
																	</div>
																</div>
															</div>
														';
													?>
												</td>
											</tr>
										<?php }
									?>
									<tr>
										<td>Versements clients :</td>
										<td>
										<span id="totalVersementClient"></span> <?php echo $_SESSION['symbole']; ?>
											&nbsp;&nbsp;
											<button class="btn btn-default btn-xs" id="btnModalVClient" style="margin-right:20px;" >
												<span style="color:green;font-size: 12px;">Details</span>
											</button>
											<?php
												echo'
													<div class="modal" id="myModalVClient">
														<div class="modal-dialog">
															<div class="modal-content">
																<!-- Modal Header -->
																<div class="modal-header">
																	<h4 class="modal-title"><b> Details des Versements Clients</b> </h4>
																	<button type="button" class="close" data-dismiss="modal">&times;</button>
																</div>
																<!-- Modal body -->
																<div class="modal-body">
																	<div class="table-responsive">
																		<table id="tableVersementClient" class="display" width="100%" border="1">
																			<thead>
																				<tr>
																					<th>Client</th>
																					<th>Montant caisse</th>';
																					if ($_SESSION['compte']==1) {
																						# code...
																						echo '
																						<th>Montant mobile</th>';
																					}else{
																						# code...
																						echo '
																						<th hidden>Montant mobile</th>';
																					}
																					echo '
																				</tr>
																			</thead>
																		</table>
																	</div>
																</div>
																<!-- Modal footer -->
																<div class="modal-footer">
																	<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
																</div>
															</div>
														</div>
													</div>
														';
												?>
										</td>
									</tr>
									<?php
										if (($_SESSION['mutuelle']==1) && ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {?>
											<tr class="hidden">
												<td>Versements Mutuelles :</td>
												<td>
												<span id="totalVersementMutuelle"></span> <?php echo $_SESSION['symbole']; ?>
													&nbsp;&nbsp;
													<button class="btn btn-default btn-xs" id="btnModalVMutuelle" style="margin-right:20px;" >
														<span style="color:green;font-size: 12px;">Details</span>
													</button>
													<?php
														echo'
															<div class="modal" id="myModalVMutuelle">
																<div class="modal-dialog">
																	<div class="modal-content">
																		<!-- Modal Header -->
																		<div class="modal-header">
																			<h4 class="modal-title"><b> Details des Versements Mutuelles</b> </h4>
																			<button type="button" class="close" data-dismiss="modal">&times;</button>
																		</div>
																		<!-- Modal body -->
																		<div class="modal-body">
																			<div class="table-responsive">
																				<table id="tableVersementMutuelle" class="display" width="100%" border="1">
																					<thead>
																						<tr>
																							<th>Mutuelle</th>
																							<th>Montant</th>
																						</tr>
																					</thead>
																				</table>
																			</div>
																		</div>
																		<!-- Modal footer -->
																		<div class="modal-footer">
																			<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
																		</div>
																	</div>
																</div>
															</div>
																';
														?>
												</td>
											</tr><?php 
										}
									?>
									<tr>
										<td>Versements Fournisseurs :</td>
										<td>
										<span id="totalVersementFournisseur"></span>  <?php echo $_SESSION['symbole']; ?>
											&nbsp;&nbsp;
											<button class="btn btn-default btn-xs" id="btnModalVFournisseur" style="margin-right:20px;" >
												<span style="color:green;font-size: 12px;">Details</span>
											</button>
											<?php
												echo'
													<div class="modal" id="myModalVFournisseur">
														<div class="modal-dialog">
															<div class="modal-content">
																<!-- Modal Header -->
																<div class="modal-header">
																	<h4 class="modal-title"><b> Details des Versements Fournisseurs</b> </h4>
																	<button type="button" class="close" data-dismiss="modal">&times;</button>
																</div>
																<!-- Modal body -->
																<div class="modal-body">
																	<div class="table-responsive">
																		<table id="tableVersementFournisseur" class="display" width="100%" border="1">
																			<thead>
																				<tr>
																					<th>Fournisseur</th>
																					<th>Montant</th>
																				</tr>
																			</thead>
																		</table>
																	</div>
																</div>
																<!-- Modal footer -->
																<div class="modal-footer">
																	<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
																</div>
															</div>
														</div>
													</div>
														';
												?>
										</td>
									</tr>
									<tr class="danger">
										<td> <b> Total opérations : </b></td>
										<td><b><span id="totalCaisse"></span>  <?php echo $_SESSION['symbole']; ?></b>
										&nbsp;&nbsp;
										<button class="btn btn-default btn-xs" id="btnModalPs" style="margin-right:20px;" >
											<span style="color:green;font-size: 12px;">Details</span>
										</button>
										<?php
											echo'
												<div class="modal" id="myModalPs">
													<div class="modal-dialog modal-lg">
														<div class="modal-content">
															<!-- Modal Header -->
															<div class="modal-header">
																<h4 class="modal-title"><b> Details du Personnel</b> </h4>
																<button type="button" class="close" data-dismiss="modal">&times;</button>
															</div>
															<!-- Modal body -->
															<div class="modal-body">
																<div class="table-responsive">
																	<table id="tablePersonnel" class="display" width="100%" border="1">
																		<thead>
																			<tr>
																				<th>Prénom et Nom</th>
																				<th>Approv. Caisse</th>
																				<th>Retrait Caisse</th>
																				<th>Ventes caisse</th>';
																				if ($_SESSION['compte']==1) {
																					# code...
																					echo '
																					<th>Ventes mobile</th>';
																				}
																				echo '
																				<th>Services</th>
																				<th>Vers. Client caisse</th>';
																				if ($_SESSION['compte']==1) {
																					# code...
																					echo '
																					<th>Vers. Client mobile</th>';
																				}
																				echo '
																				<th>Vers. Fournisseur</th>
																				<th>Depenses</th>
																				<th>Remises</th>
																				<th align="right">TOTAL</th>
																			</tr>
																		</thead>
																	</table>
																</div>
															</div>
															<!-- Modal footer -->
															<div class="modal-footer">
																<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
															</div>
														</div>
													</div>
												</div>
											';
										?>
											</b>
										</td>
									</tr>
									<tr class="warning">
										<td><b> Total Bon Produit :  </b></td>
										<td><b>
											<span id="totalBonProduit"></span>  <?php echo $_SESSION['symbole']; ?>
											&nbsp;&nbsp;
											<button class="btn btn-default btn-xs" id="btnModalBP" style="margin-right:20px;" >
												<span style="color:green;font-size: 12px;">Details</span>
											</button>
											<?php
													echo'
														<div class="modal" id="myModalBP">
															<div class="modal-dialog">
																<div class="modal-content">
																	<!-- Modal Header -->
																	<div class="modal-header">
																		<h4 class="modal-title"><b> Details des Bons</b> </h4>
																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																	</div>
																	<!-- Modal body -->
																	<div class="modal-body">
																		<div class="table-responsive">
																			<table id="tableBonProduit" class="display" width="100%" border="1">
																				<thead>
																					<tr>
																						<th>Client</th>
																						<th>Montant</th>
																					</tr>
																				</thead>
																			</table>
																		</div>
																	</div>
																	<!-- Modal footer -->
																	<div class="modal-footer">
																		<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
																	</div>
																</div>
															</div>
														</div>
													';
													?>
											</b>
										</td>
									</tr>
									<?php 
									if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 
										if ($_SESSION['mutuelle']==1){
										?>
											<tr class="warning">
												<td><b> Total Bon Mutuelle :  </b></td>
												<td><b>
													<span id="totalBonMutuelle"></span>  <?php echo $_SESSION['symbole']; ?>
													&nbsp;&nbsp;
													<button class="btn btn-default btn-xs" id="btnModalBM" style="margin-right:20px;" >
														<span style="color:green;font-size: 12px;">Details</span>
													</button>
													<?php
															echo'
																<div class="modal" id="myModalBM">
																	<div class="modal-dialog">
																		<div class="modal-content">
																			<!-- Modal Header -->
																			<div class="modal-header">
																				<h4 class="modal-title"><b> Details des Bons</b> </h4>
																				<button type="button" class="close" data-dismiss="modal">&times;</button>
																			</div>
																			<!-- Modal body -->
																			<div class="modal-body">
																				<div class="table-responsive">
																					<table id="tableBonMutuelle" class="display" width="100%" border="1">
																						<thead>
																							<tr>
																								<th>Mutuelle</th>
																								<th>Montant</th>
																							</tr>
																						</thead>
																					</table>
																				</div>
																			</div>
																			<!-- Modal footer -->
																			<div class="modal-footer">
																				<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
																			</div>
																		</div>
																	</div>
																</div>
															';
															?>
													</b>
												</td>
											</tr>
										<?php 
										}
									}
									else{ ?>
										<?php if (($_SESSION['type']=="Divers") && ($_SESSION['categorie']=="Detaillant") ) { ?>
											<tr class="success">
												<td> <b> Benefice : </b></td>
												<td><b><span id="totalBenefice"></span> <?php echo $_SESSION['symbole']; ?></b>
												&nbsp;&nbsp;
												<button class="btn btn-default btn-xs" id="btnModalBN" style="margin-right:20px;" >
													<span style="color:green;font-size: 12px;">Details</span>
												</button>
												<?php
													echo'
														<div class="modal" id="myModalBN">
															<div class="modal-dialog modal-lg">
																<div class="modal-content">
																	<!-- Modal Header -->
																	<div class="modal-header">
																		<h4 class="modal-title"><b> Details du Benefice</b> </h4>
																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																	</div>
																	<!-- Modal body -->
																	<div class="modal-body">
																		<div class="table-responsive">
																			<table id="tableBenefice" class="display" width="100%" border="1">
																				<thead>
																					<tr>
																						<th>Reference</th>
																						<th>Prix Vente</th>
																						<th>Prix Achat</th>
																					</tr>
																				</thead>
																			</table>
																		</div>
																	</div>
																	<!-- Modal footer -->
																	<div class="modal-footer">
																		<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
																	</div>
																</div>
															</div>
														</div>
													';
												?>
												</td>
											</tr>
										<?php }
										else { ?>
											<?php if ($_SESSION['proprietaire']=="Admin") { ?>
												<tr class="success">
													<td> <b> Benefice : </b></td>
													<td><b><span id="totalBenefice"></span> <?php echo $_SESSION['symbole']; ?></b>
													&nbsp;&nbsp;
													<button class="btn btn-default btn-xs" id="btnModalBN" style="margin-right:20px;" >
														<span style="color:green;font-size: 12px;">Details</span>
													</button>
													<?php
														echo'
															<div class="modal" id="myModalBN">
																<div class="modal-dialog modal-lg">
																	<div class="modal-content">
																		<!-- Modal Header -->
																		<div class="modal-header">
																			<h4 class="modal-title"><b> Details du Benefice</b> </h4>
																			<button type="button" class="close" data-dismiss="modal">&times;</button>
																		</div>
																		<!-- Modal body -->
																		<div class="modal-body">
																			<div class="table-responsive">
																				<table id="tableBenefice" class="display" width="100%" border="1">
																					<thead>
																						<tr>
																							<th>Reference</th>
																							<th>Prix Vente</th>
																							<th>Prix Achat</th>
																						</tr>
																					</thead>
																				</table>
																			</div>
																		</div>
																		<!-- Modal footer -->
																		<div class="modal-footer">
																			<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
																		</div>
																	</div>
																</div>
															</div>
														';
													?>
													</td>
												</tr>
											<?php } ?>
										<?php } ?>
									<?php } ?>
									<?php
										if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
											$sql1="SELECT * FROM `". $nomtableEntrepot."` ORDER BY idEntrepot ASC";
											$res1=mysql_query($sql1);
											echo'
												<tr>
													<td>Transferts :</td>
													<td><a href="" data-toggle="modal" data-target="#myModalTF">Details</a>
													<div class="modal" id="myModalTF">
														<div class="modal-dialog modal-lg">
															<div class="modal-content">

																<!-- Modal Header -->
																<div class="modal-header">
																	<h4 class="modal-title"><b> Details des Transfert</b> </h4>
																	<button type="button" class="close" data-dismiss="modal">&times;</button>
																</div>

																<!-- Modal body -->
																<div class="modal-body">
																	<ul class="nav nav-tabs">';
																		$i=0;
																		while($depot = mysql_fetch_array($res1)) {
																			if($i==0){
																				echo '<li class="active"><a data-toggle="tab" href="#Depot'.$depot['idEntrepot'].'">'.strtoupper($depot['nomEntrepot']).'</a></li>';
																			}
																			else{
																				echo'<li><a data-toggle="tab" href="#Depot'.$depot['idEntrepot'].'">'.strtoupper($depot['nomEntrepot']).'</a></li>';
																			}
																			$i=$i+1;
																		}
																		echo '
																	</ul>
																	<div class="tab-content">';
																		$sql2="SELECT * FROM `". $nomtableEntrepot."` ORDER BY idEntrepot ASC";
																		$res2=mysql_query($sql2);
																		$j=0;
																		while($entrepot = mysql_fetch_array($res2)) {
																			if($j==0){
																				$sql4="SELECT * FROM `". $nomtableEntrepotStock."` where idEntrepot='".$entrepot['idEntrepot']."' AND idTransfert!=0 AND dateStockage='".$dateString."' ";
																				$res4=mysql_query($sql4);
																				echo '<div class="tab-pane fade in active" id="Depot'.$entrepot['idEntrepot'].'">
																					<table class="table ">
																						<thead class="noImpr">
																							<tr>
																								<th>Reference</th>
																								<th>Quantite</th>
																								<th>Unite Stock</th>
																								<th>Origine</th>
																								<th>Destination</th>
																							</tr>
																						</thead>
																						<tbody>';
																							if(mysql_num_rows($res4)){
																								while($transfert = mysql_fetch_array($res4)) {
																									$sql3="SELECT * FROM `". $nomtableEntrepotStock."` where idEntrepotStock='".$transfert['idTransfert']."'";
																									$res3=mysql_query($sql3);
																									$transfertDepot=mysql_fetch_array($res3);
																									
																									$sql5="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$transfertDepot['idEntrepot']."'";
																									$res5=mysql_query($sql5);
																									$depotTransfert=mysql_fetch_array($res5);

																									echo '
																									<tr>
																										<td>'.strtoupper($transfert['designation']).'</td>
																										<td>'.$transfert['quantiteStockinitial'].'</td>
																										<td>'.strtoupper($transfert['uniteStock']).'</td>
																										<td>'.$depotTransfert['nomEntrepot'].'</td>
																										<td>'.$entrepot['nomEntrepot'].'</td>
																									</tr> ';
																								}
																							}
																							echo '
																						</tbody>
																					</table>
																				</div>';
																			}
																			else{
																				$sql4="SELECT * FROM `". $nomtableEntrepotStock."` where  idEntrepot='".$entrepot['idEntrepot']."' AND idTransfert!=0 AND dateStockage='".$dateString."' ";
																				$res4=mysql_query($sql4);
																				echo '<div class="tab-pane fade" id="Depot'.$entrepot['idEntrepot'].'">
																					<table class="table ">
																						<thead class="noImpr">
																							<tr>
																								<th>Reference</th>
																								<th>Quantite</th>
																								<th>Unite Stock</th>
																								<th>Origine</th>
																								<th>Destination</th>
																							</tr>
																						</thead>
																						<tbody>';
																							if(mysql_num_rows($res4)){
																								while($transfert = mysql_fetch_array($res4)) {
																									$sql3="SELECT * FROM `". $nomtableEntrepotStock."` where idEntrepotStock='".$transfert['idTransfert']."'";
																									$res3=mysql_query($sql3);
																									$transfertDepot=mysql_fetch_array($res3);
																									
																									$sql5="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$transfertDepot['idEntrepot']."'";
																									$res5=mysql_query($sql5);
																									$depotTransfert=mysql_fetch_array($res5);

																									echo '
																										<tr>
																											<td>'.strtoupper($transfert['designation']).'</td>
																											<td>'.$transfert['quantiteStockinitial'].'</td>
																											<td>'.strtoupper($transfert['uniteStock']).'</td>
																											<td>'.$depotTransfert['nomEntrepot'].'</td>
																											<td>'.$entrepot['nomEntrepot'].'</td>
																										</tr> ';
																								}
																							}
																							echo '
																						</tbody>
																					</table>
																				</div>';
																			}
																			$j=$j+1;
																		}
																		echo '
																	</div>
																</div>

																<!-- Modal footer -->
																<div class="modal-footer">
																	<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
																</div>

															</div>
														</div>
													</div>
													</td>
												</tr> 
												';

											$result = mysql_query("SHOW TABLES LIKE '".$nomtableFacture."'");
											if($rTableExist = mysql_fetch_array($result)) {
												echo'
												<tr>
													<td>Facture :</td>
													<td><a href="" data-toggle="modal" data-target="#myModalTFact">Details</a>';
													$sqlP1="SELECT * 
													FROM `".$nomtableFacture."` f
													INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = f.idPagnet
													WHERE f.dateFacture ='".$dateString."'  ORDER BY f.idFacture DESC";
													$resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
													if(mysql_num_rows($resP1)){
														echo '
														<div class="modal" id="myModalTFact">
															<div class="modal-dialog modal-lg">
																<div class="modal-content">

																<!-- Modal Header -->
																<div class="modal-header">
																	<h4 class="modal-title"><b> Details des Factures</b> </h4>
																	<button type="button" class="close" data-dismiss="modal">&times;</button>
																</div>

																<!-- Modal body -->
																<div class="modal-body">
																	<table class="table table-bordered table-responsive ">
																		<tr>
																			<th>Prénom et Nom</th>
																			<th>Adresse</th>
																			<th>Telephone</th>
																			<th align="right">TOTAL</th>
																			<th>Heure</th>
																		</tr>';
																		while($facture = mysql_fetch_array($resP1)) {
																			$sqlS="SELECT * FROM `".$nomtablePagnet."` 
																				WHERE idPagnet='".$facture["idPagnet"]."' ";
																			$resS = mysql_query($sqlS) or die ("persoonel requête 2".mysql_error());
																			$pagnet = mysql_fetch_array($resS);
																			echo '<tr>
																					<td><b>'.$facture["prenom"].' &nbsp; '.$facture["nom"].'</b></td>
																					<td><b>'.$facture["adresse"].'</b></td>
																					<td><b>'.$facture["telephone"].'</b></td>
																					<td align="right"><b>'.number_format($pagnet['apayerPagnet'], 2, ',', ' ').' '.$_SESSION['symbole'].'</b>&nbsp;FCFA</td>
																					<td><b>'.$facture["heureFacture"].'</b></td>
																				</tr>';
																		}
																		echo'		
																	</table>
																</div>

																	<!-- Modal body -->
																	<div class="modal-body">
																		
																	</div>

																	<!-- Modal footer -->
																	<div class="modal-footer">
																		<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
																	</div>

																</div>
															</div>
														</div>
														';
													}
													echo '
													</td>
												</tr> 
												';
											}
										}
									?>
								</table>
								<form class="form-inline pull-right noImpr"  target="_blank" style="margin-right:20px;"
									method="post" action="pdfDivers.php" >
									<input type="hidden" name="dateImp" id="idDateImp"  <?php echo  "value=".$dateString2."" ; ?> >
									<button class="btn btn-danger  pull-right" style="margin-right:20px;" >
										<span class="glyphicon glyphicon-download-alt"></span> Impression Divers
									</button>
								</form>
								<!-- Debut Ventes par date -->
								<button type="submit" id="btnVenteParDate" data-toggle="modal" data-target="#msg_vente_par_date" class="btn btn-info" style="margin-left:20px;">
									<span class="glyphicon glyphicon-plus"></span> Total ventes par date
								</button>
								<div id="msg_vente_par_date" class="modal fade " role="dialog">
									<div class="modal-dialog modal-lg">
										<!-- Modal content-->
										<div class="modal-content">
											<div class="modal-header panel-primary">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Total ventes par date</h4>
												<input type="hidden" name="pageTotalVente" id="pageTotalVente" value="1">
											</div>
											<div class="modal-body">
												<center>     
													<form  target="_blank" style="margin-top:10px;" method="post" action="pdfVenteParDate.php">
														<input type="hidden" name="date1VD" id="date1VDHidden">
														<input type="hidden" name="date2VD" id="date2VDHidden">
														<button type="submit" class="btn btn-default" >
															<span class="glyphicon glyphicon-download-alt"></span> PDF
														</button>
													</form>
												</center>
												<div class="table-responsive">                
													<label class="pull-left" for="nbEntreeVD">Nombre entrées </label>
													<select class="pull-left" id="nbEntreeVD">
														<optgroup>
															<option value="10">10</option>
															<option value="20">20</option>
															<option value="50">50</option> 
														</optgroup>       
													</select>													
													<div class="pull-right" align="center">
														<input type="date" name="" id="date1VD">&ensp;&ensp;
														<input type="date" name="" id="date2VD">&ensp;&ensp;
														<button class="btn btn-info btn-sm" id="btnVenteByPeriod" onclick="sumVenteByPeriod()" type="button">OK</button>
													</div>			
													<img src="images/loading-gif3.gif" style="margin-left:40%;margin-top:8%" class="loading-gif" alt="GIF" srcset="">
													<div id="results_vente_par_date"><!-- content will be loaded here -->
													</div>
												</div>
											</div>
											<div class="modal-footer">
												<!-- <input type="checkbox" class="pull-left" name="showInfoChange" id="showInfoChange"> -->
												<!-- <label for="showInfoChange" class="pull-left">&nbsp;&nbsp; Ne plus afficher ce message</label> -->
												<button type="button" id="" class="btn btn-danger btn-sm" data-dismiss="modal">Fermer</button>
											</div>
										</div>
									</div>
								</div>
								<!-- Fin Ventes par date -->
								<form class="form-inline pull-left noImpr"  target="_blank" style="margin-left:20px;"
									method="post" action="pdfVente.php" >
									<input type="hidden" name="dateImp" id="idDateImp"  <?php echo  "value=".$dateString2."" ; ?> >
									<button type="submit" class="btn btn-success  pull-right" style="margin-left:20px;" >
										<span class="glyphicon glyphicon-download-alt"></span> Impression Ventes
									</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</td>
		</tr>
	</table>


	<?php echo'</div>';
			
			//-----------------------------------------------------------------
			
	if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1) {		
			//--------------------------------------------------
			echo '<div class="container">';
			require('alerte.php');
			echo'</div>';
			
			
			//--------------------------------------------------------
	} 


?>
<?php if ($_SESSION['showInfoChange'] == 10 && $_SESSION['proprietaire'] == 1) { ?>
	<!-- Debut Message d'Alerte concernant client required -->
	<div id="msg_info_change" class="modal fade " role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header panel-primary">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Info changement</h4>
				</div>
				<div class="modal-body">
					<p> Contenu </br>
					</p>
				</div>
				<div class="modal-footer">
					<input type="checkbox" class="pull-left" name="showInfoChange" id="showInfoChange">
					<label for="showInfoChange" class="pull-left">&nbsp;&nbsp; Ne plus afficher ce message</label>
					<button type="button" id="closeShowInfoChange" class="btn btn-primary" data-dismiss="modal">Fermer</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Fin Message d'Alerte concernant client required -->
	<script type='text/javascript'>
		$(window).on('load',function(){
			$('#msg_info_change').modal('show');
		});
	</script>
<?php } ?>


    
<div class="modal fade" id="selectNbMoisModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

		<div class="modal-dialog" role="document">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Formulaire de paiement</h4>

			</div>

			<div class="modal-body">
				<div name="deleteContainer">
					<input type="hidden" id="idPsModalSelectNbMois">
					<input type="hidden" id="montantFormuleInput">
					<div class="form-group" align="center">
						<h3>
							<span class="">Choisir le nombre de mois à payer</span>
						</h3>
						<Select id="selectNbMois" onChange="changeFormule()" class="form-control" style="background-color:gold;width: 100px;">
							<option value="1">1</option>
							<option value="3">3</option>
							<option value="6">6</option>
							<option value="12">12</option>
						</Select>
					</div><br><br><br>
					<div class="form-group alert alert-info" id="divFormule" style="width: 250px;">
						Montant à payer : <span id="montantFormule"></span> FCFA
					</div>
					<div class="modal-footer">

						<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

						<button type="button" name="btnSelectNbMois" onClick="effectue_paiementWave()" class="btn btn-primary">Valider</button>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>

</body>
</html> <?php
}
else{
echo'<!DOCTYPE html>';
echo'<html>';
echo'<head>';
echo'<script language="JavaScript">document.location="../index.php"</script>';
echo'</head>';
echo'</html>';
}
?>
