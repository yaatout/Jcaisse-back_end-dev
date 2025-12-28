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
if(!$_SESSION['iduser']){
  header('Location:../index.php');
}

require('../connection.php');

require('../declarationVariables.php');

$data=array();

//Ajout de -1 jours
//$date1=date('Y-m-d', strtotime('-1 days'));

$sql="SELECT * from  `aaa-payement-salaire` where idBoutique =".$_SESSION['idBoutique']." order by datePS desc LIMIT 20";
//echo $sql;
$res=mysql_query($sql);
if(mysql_num_rows($res)){
	$i=1;
	while($tab=mysql_fetch_array($res)){
		
		$date = new DateTime($tab["datePS"]);
		$anneePS =$date->format('Y');
		$moisPS =$date->format('m');
		$jourPS =$date->format('d');
		$dateStringPS=$anneePS.''.$moisPS;
		$periode=$moisPS.'-'.$anneePS;
		if (strlen ($moisPS)==1)
		$estimationDatePaiement=$anneePS.'-0'.($moisPS+1).'-01';
		else
		$estimationDatePaiement=$anneePS.'-'.($moisPS+1).'-01';	
		
		
      $rows = array();
      if ($tab['aPayementBoutique']==0){
		  
		  if ($dateStringPS == $dateStringMA){
			 
			  $rows[] = '<span style="color:#ffcc00;;">'.$i.'</span>';
			  $rows[] = '<span style="color:#ffcc00;;">'.$periode.'</span>';
			  $rows[] = '<span style="color:#ffcc00;;">'.$tab["montantFixePayement"].' '.$_SESSION['symbole'].'</span>';
			  $rows[] = '<span style="color:#ffcc00;;">'.$estimationDatePaiement.'</span>';
			  
			  if (!$tab["refTransfert"]){
			  $rows[] = '<span style="color:#ffcc00;;">EN COURS ...</span>';
			  }else{
				$rows[] = '<span style="color:#ffcc00;;">VALIDATION EN COURS ...</span>';
			  }
			  $rows[] = '<a type="button" onclick="selectNbMoisPaiement('.$tab["idPS"].','.$tab["montantFixePayement"].')" id="btn_paiementWave-'.$tab['idPS'].'" class="btn btn-success" >
						<img src="images/Wave.png" width="25" height="25"> Wave
					</a>
					<button style="display:none" type="button" onclick="effectue_paiement('.$tab["idPS"].')" id="btn_paiement-'.$tab['idPS'].'" class="btn btn-warning" >
						<img src="images/Orange.png" width="25" height="25"> OMoney
					</button>
                    ';  
		  }else{
			  
			  $rows[] = '<span style="color:red;">'.$i.'</span>';
			  $rows[] = '<span style="color:red;">'.$periode.'</span>';
			  $rows[] = '<span style="color:red;">'.$tab["montantFixePayement"].' '.$_SESSION['symbole'].'</span>';
			  $rows[] = '<span style="color:red;">'.$tab["datePS"].'</span>';
			  
			  if (!$tab["refTransfert"]){
				$rows[] = '<span style="color:red;">EN RETARD</span>';
			  }else{
				$rows[] = '<span style="color:#ffcc00;">VALIDATION EN COURS ...</span>';
			  }
			  
			  $rows[] = '<a type="button" onclick="selectNbMoisPaiement('.$tab["idPS"].','.$tab["montantFixePayement"].')" id="btn_paiementWave-'.$tab['idPS'].'" class="btn btn-success" >
						<img src="images/Wave.png" width="25" height="25"> Wave
					</a>
					<button style="display:none" type="button" onclick="effectue_paiement('.$tab["idPS"].')" id="btn_paiement-'.$tab['idPS'].'" class="btn btn-warning" >
						<img src="images/Orange.png" width="25" height="25"> OMoney
					</button>
          			';
			
		  }


	  } else {


			$rows[] = '<span style="color:green;">'.$i.'</span>';
			$rows[] = '<span style="color:green;">'.$periode.'</span>';
			$rows[] = '<span style="color:green;">'.$tab["montantFixePayement"].' '.$_SESSION['symbole'].'</span>';
			$rows[] = '<span style="color:green;">'.$tab["datePaiement"].'</span>';
		    $rows[] = '<span style="color:green;">DEJA PAYE</span>';
			$rows[] = '<a href="pdfDecharge.php?iDS='.$tab["idPS"].'" target="_blank" class="btn btn-danger" >
			<i class="glyphicon glyphicon-transfer"></i>  FACTURE 
			</a>';
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


?>
