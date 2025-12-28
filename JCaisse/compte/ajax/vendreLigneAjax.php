<?php 
	session_start();
	if(!$_SESSION['iduser']){
		header('Location:index.php');
	}
mysql_connect("localhost","root","");
mysql_select_db("bdjournalcaisse");
$nomtableClient=$_SESSION['nomB']."-client";
$nomtablePagnet=$_SESSION['nomB']."-pagnet";
$nomtableStock=$_SESSION['nomB']."-stock";
$nomtableLigne=$_SESSION['nomB']."-lignepj";
$nomtableDesignation=$_SESSION['nomB']."-designation";

$date = new DateTime();
        $annee =$date->format('Y');
        $mois =$date->format('m');
        $jour =$date->format('d');
        $dateString2=$annee.'-'.$mois.'-'.$jour;

$reponse="produit non enregistrer";

	$codeBarre=htmlspecialchars(trim($_POST['codeBarreLigne']));
     $idPagnet=htmlspecialchars(trim($_POST['idPagnet']));
	$codeBrute=explode('-', $codeBarre);
    //$reponse+=sizeof($codeBrute);
    var_dump($codeBarre);
    var_dump($codeBrute);

    echo "taille size of ="+sizeof($codeBrute);
	echo "taille count"+count($codeBrute);


if ( count($codeBrute)>2 and strlen($codeBrute[2])!="") {

	$idStock=$codeBrute[0];
	$idDesignation=$codeBrute[1];
	$numero=$codeBrute[2];
	$sql4="SELECT * FROM `".$nomtableStock."` where idStock=".$idStock."";
      $res4=mysql_query($sql4) or die ("select stock impossible =>".mysql_error());
      	$stock = mysql_fetch_array($res4);
     	 $quantiteStockCourant=$stock['quantiteStockCourant'];
        if ($quantiteStockCourant>0) {
        // mise a jour de la table stock
              $quantiteStockCourant--;
              $sql5="UPDATE `".$nomtableStock."` set quantiteStockCourant='".$quantiteStockCourant."' where idStock=".$idStock;
              $res5=mysql_query($sql5) or die ("update quantiteStockCourant impossible =>".mysql_error());

              // insertion dans l'historique
             /**/ $sql6="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$idDesignation."";
              
              $res6=mysql_query($sql6) or die ("select stock impossible =>".mysql_error());
              $design = mysql_fetch_array($res6);
              $sql7="insert into `".$nomtableLigne."` (datepage,designation,unitevente,prixunitevente,quantite,remise,prixtotal,typeligne,idPagnet) 
               values('".$dateString2."','".$design['designation']."','".$stock['uniteStock']."','".$stock['prixunitaire']."','1','0','".$stock['prixunitaire']."','Entree','".$idPagnet."')";
               $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

            $sql14="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet."";
            $res14=mysql_query($sql14) or die ("select stock impossible =>".mysql_error());
            $pagnet = mysql_fetch_array($res14); 
            $totalp=$pagnet['totalp']+$stock['prixunitaire'];

            $sql15="UPDATE `".$nomtablePagnet."` set totalp='".$totalp."' where idPagnet=".$idPagnet;
            $res15=mysql_query($sql15) or die ("update Pagnet impossible =>".mysql_error());

              $resultat="ok";
         
        	 $reponse="produit enregistrer avec succes";
         }
	}

    header("Location:../insertionLigne.php");

 ?>