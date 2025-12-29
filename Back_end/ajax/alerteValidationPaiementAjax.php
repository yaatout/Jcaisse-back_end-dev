<?php
/*
Résumé :
Commentaire :
Version : 2.0
see also :
Auteur : Ibrahima DIOP, EL hadji mamadou korka
Date de création : 20/03/2016
Date derniére modification : 20/04/2016; 15-04-2018
*/
session_start();
/*if(!$_SESSION['iduser']){
  header('Location:../index.php');
}
*/
require('../connection.php');

require('../declarationVariables.php');

$data=array();

$sql="SELECT * from `aaa-payement-salaire` order by datePS desc";

$res=mysql_query($sql) or die ("modification impossible kkk ".mysql_error());
if(mysql_num_rows($res)){
	$i=1;
	while($tab=@mysql_fetch_array($res)){
		
		$date = new DateTime($tab["datePS"]);
		$anneePS =$date->format('Y');
		$moisPS  =$date->format('m');
		$jourPS  =$date->format('d');
		$dateStringPS=$anneePS.''.$moisPS;
		$periode=$moisPS.'-'.$anneePS;
		if (strlen ($moisPS)==1)
			$estimationDatePaiement=$anneePS.'-0'.($moisPS+1).'-01';
		else
			$estimationDatePaiement=$anneePS.'-'.($moisPS+1).'-01';
		
		$sql2="SELECT * FROM `aaa-boutique` WHERE idBoutique =".$tab["idBoutique"];
		$res2 = mysql_query($sql2) or die ("personel requête 2".mysql_error());
		$boutique3 = mysql_fetch_array($res2);
		$nomB=$boutique3['labelB'];
		
		$rows = array();
		if ($tab['aPayementBoutique']==0){
		  
		  if ($dateStringPS == $dateStringMA){
			 
			  $rows[] = $i;
			  $rows[] = '<span style="color:#ffcc00;">'.$tab["refTransfert"].' </span>';
			  $rows[] = '<span style="color:#ffcc00;">'.$nomB.'</span>';
			  $rows[] = '<span style="color:#ffcc00;">'.$periode.'</span>';
			  $rows[] = '<span style="color:#ffcc00;">'.$tab["montantFixePayement"].' F CFA</span>';
			  $rows[] = '<span style="color:#ffcc00;">'.$estimationDatePaiement.'</span>';
			  
			  if (!$tab["refTransfert"]){
				$rows[] = '<span style="color:#ffcc00;">EN COURS ...</span>';
			  }else{
				$rows[] = '<span style="color:#ffcc00;">VALIDATION EN COURS ...</span> <a  onclick="detRefTransferClick('.$tab["idPS"].')"> Details  </a>';
			  }
				$rows[] = '<button type="button" onclick="effectue_validationPaiement('.$tab["idPS"].')" id="btn_paiement-'.$tab['idPS'].'" class="btn btn-success" >
				<i class="glyphicon glyphicon-transfer"></i> PAIEMENT
				</button>';  
		  }else{
			  
			  $rows[] = '<span style="color:red;">'.$i.'</span>';
			  $rows[] = '<span style="color:#ffcc00;">'.$tab["refTransfert"].' </span>';
			  $rows[] = '<span style="color:#ffcc00;">'.$nomB.'</span>';
			  $rows[] = '<span style="color:red;">'.$periode.'</span>';
			  $rows[] = '<span style="color:red;">'.$tab["montantFixePayement"].' F CFA</span>';
			  $rows[] = '<span style="color:red;">'.$tab["datePS"].'</span>';
			  
			  if (!$tab["refTransfert"]){
				$rows[] = '<span style="color:red;">EN RETARD</span>';
			  }else{
				$rows[] = '<span style="color:#ffcc00;">VALIDATION EN COURS ...</span> <a  onclick="detRefTransferClick('.$tab["idPS"].')"> Details  </a>';
			  }
			  
			  $rows[] = '<button type="button" onclick="effectue_validationPaiement('.$tab["idPS"].')" id="btn_paiement-'.$tab['idPS'].'" class="btn btn-success" >
				<i class="glyphicon glyphicon-transfer"></i> PAIEMENT
				</button>';
		  }
	  }else{

			$rows[] = '<span style="color:green;">'.$i.'</span>';
			  $rows[] = '<span style="color:#ffcc00;">'.$tab["refTransfert"].' </span>';
			  $rows[] = '<span style="color:#ffcc00;">'.$nomB.'</span>';
			$rows[] = '<span style="color:green;">'.$periode.'</span>';
			$rows[] = '<span style="color:green;">'.$tab["montantFixePayement"].' F CFA</span>';
			$rows[] = '<span style="color:green;">'.$tab["datePaiement"].'</span>';
		    $rows[] = '<span style="color:green;">DEJA PAYE <a  onclick="detRefTransferClick('.$tab["idPS"].')"> Details  </a></span>';
			$rows[] = '<button type="button" onclick="rtr_facture('.$tab["idPS"].')" id="btn_facture-'.$tab['idPS'].'" class="btn btn-danger" >
			<i class="glyphicon glyphicon-transfer"></i>  FACTURE 
			</button>';
	  }

      $data[] = $rows;
	  $i++;
    }
}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);
//var_dump($data);

?>
