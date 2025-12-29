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
session_start();
if(!$_SESSION['iduserBack'])
    header('Location:../index.php');

  require('../connection.php');

  require('../declarationVariables.php');

$date = new DateTime();
$timezone = new DateTimeZone('Africa/Dakar');
$date->setTimezone($timezone);

$debut=@$_GET['debut'];
$fin=@$_GET['fin'];
$operaton=@$_GET['operation'];
//var_dump($debut);
$data=array();
$payements=array();
$i=1;
$fin = new DateTime($debut);
$annee =$fin->format('Y');
$mois =$fin->format('m');
$finP=$debut;

if ($operaton=='payement') {

    // les calculs se font le 20 de chaque mois 
    //$jourDebut=20; 
    // les paiements se font au plus tard le 05 de chaque mois
    
    //var_dump($finP);
    
    $moiM=0;
    $moiM=$mois-1;
    //var_dump($mois);
    //var_dump($moiM);

        $anneeM=$annee;
        if($moiM<10){
            $moiM='0'.$moiM;
            if($mois=='01'){
                $moiM=12;
                $anneeM=$annee-1;
                $anneeM="$anneeM";
            }
        }
      //$debutP=$anneeM.'-'.$moiM.'-'.$jourDebut;
      //die($debutP);
      //$debutP=new DateTime($debutP);
    /*$sql3="SELECT * FROM `aaa-payement-salaire` where ( datePS LIKE '%".$annee."-".$mois."%' or datePS LIKE '%".$mois."-".$annee."%' ) and idBoutique=".$boutique["idBoutique"];*/
    
  /*$sql1="SELECT * FROM `aaa-payement-reference` WHERE dateRefTransfertValidation BETWEEN '".$debutP."' AND '".$finP."' ORDER BY id";*/
  $sql1="SELECT * FROM `aaa-payement-salaire` where ( datePS LIKE '%".$anneeM."-".$moiM."%' or datePS LIKE '%".$moiM."-".$anneeM."%' ) ORDER BY idPS";
  $res1=mysql_query($sql1);
//die($sql1);
  while($pay=mysql_fetch_array($res1)){
      $rows = array();
      $rows[] = $i;
      /*$rows[] = '<span >'.strtoupper($pay['dateRefTransfertValidation']).'</span>';
      $rows[] = '<span >'.strtoupper($pay['montant']).'</span>';
      $rows[] = '<span >'.strtoupper($pay['refTransfertValidation']).'</span>';*/
      $sql21="SELECT * FROM `aaa-boutique` WHERE idBoutique =".$pay['idBoutique'];
      $res21 = mysql_query($sql21) or die ("personel requête 2".mysql_error());
      $boutique = mysql_fetch_array($res21); 
      //die($boutique):
      $rows[] = '<span >'.strtoupper($boutique['nomBoutique']).'</span>';
      $rows[] = '<span >'.strtoupper($pay['datePaiement']).'</span>';
      $rows[] = '<span >'.strtoupper($pay['montantFixePayement']).'</span>';
      $rows[] = '<span >'.strtoupper($pay['refTransfert']).'</span>';
         
      /*$db = explode("-", $debut);
      $date_debut=$db[0].''.$db[1].''.$db[2];
      $df = explode("-", $fin);
      $date_fin=$df[0].''.$df[1].''.$df[2];*/
      $rows[] = '<button  type="button" onclick="rapport_Entrees()" class="btn btn-primary" >
      <i class="glyphicon glyphicon-transfer"></i> Details
      </button>';

      $data[] = $rows;
      $i=$i + 1;
  }
  //print_r($data);
}elseif ($operaton=='salaire') {

      $sop=@$_GET['sop'];
      if ($sop=="accompagnateurs") {
        /*$sql="SELECT * FROM `aaa-payement-salaire` WHERE aSalaireAccompagnateur=1 AND datePS BETWEEN '".$debut."' AND '".$fin."' ";*/
        $sql="SELECT * FROM `aaa-payement-salaire` WHERE aSalaireAccompagnateur=1 AND  ( datePS LIKE '%".$annee."-".$mois."%' or datePS LIKE '%".$mois."-".$annee."%' ) ";
        $res=mysql_query($sql);

        while($pay=mysql_fetch_array($res)){
            $rows = array();
            $rows[] = $i;
            $rows[] = '<span style="color:blue;">'.strtoupper($pay['accompagnateur']).'</span>';
            $rows[] = '<span style="color:blue;">'.strtoupper($pay['etapeAccompagnement']).'</span>';
            $rows[] = '<span style="color:blue;">'.strtoupper($pay['montantFixePayement']).'</span>';
            $rows[] = '<span style="color:blue;">'.strtoupper($pay['idBoutique']).'</span>';
               
            $db = explode("-", $debut);
            $date_debut=$db[0].''.$db[1].''.$db[2];
            $df = explode("-", $fin);
            $date_fin=$df[0].''.$df[1].''.$df[2];
            $rows[] = '<button  type="button" onclick="rapport_Entrees(0,'.$date_debut.','.$date_fin.')" class="btn btn-primary" >
            <i class="glyphicon glyphicon-transfer"></i> Details
            </button>';

            $data[] = $rows;
            $i=$i + 1;
        }
      }elseif ($sop=="ingenieur") {
        /*$sql="SELECT * FROM `aaa-salaire-personnel` WHERE profil='Ingenieur' AND datePaiement BETWEEN '".$debut."' AND '".$fin."' ";*/
        $sql="SELECT * FROM `aaa-salaire-personnel` WHERE profil='Ingenieur' AND ( datePaiement LIKE '%".$annee."-".$mois."%' or datePaiement LIKE '%".$mois."-".$annee."%' ) ";
        $res=mysql_query($sql);

        while($pay=mysql_fetch_array($res)){
              $rows = array();
            $rows[] = $i;
            $rows[] = '<span style="color:blue;">'.strtoupper($pay['prenom']).'</span>';
            $rows[] = '<span style="color:blue;">'.strtoupper($pay['nom']).'</span>';
            $rows[] = '<span style="color:blue;">'.strtoupper($pay['montant']).'</span>';
            $rows[] = '<span style="color:blue;">'.strtoupper($pay['comptePaiement']).'</span>';
               
            $db = explode("-", $debut);
            $date_debut=$db[0].''.$db[1].''.$db[2];
            $df = explode("-", $fin);
            $date_fin=$df[0].''.$df[1].''.$df[2];
            $rows[] = '<button  type="button" onclick="rapport_Entrees()" class="btn btn-primary" >
            <i class="glyphicon glyphicon-transfer"></i> Details
            </button>';

            $data[] = $rows;
            $i=$i + 1;
        }
      }elseif ($sop=="admin") {
        $sql="SELECT * FROM `aaa-salaire-personnel` WHERE profil='Admin' AND ( datePaiement LIKE '%".$annee."-".$mois."%' or datePaiement LIKE '%".$mois."-".$annee."%' ) ";
        $res=mysql_query($sql);

        while($pay=mysql_fetch_array($res)){
             $rows = array(); 
            $rows[] = $i;
            $rows[] = '<span style="color:blue;">'.strtoupper($pay['prenom']).'</span>';
            $rows[] = '<span style="color:blue;">'.strtoupper($pay['nom']).'</span>';
            $rows[] = '<span style="color:blue;">'.strtoupper($pay['montant']).'</span>';
            $rows[] = '<span style="color:blue;">'.strtoupper($pay['comptePaiement']).'</span>';
               
            $db = explode("-", $debut);
            $date_debut=$db[0].''.$db[1].''.$db[2];
            $df = explode("-", $fin);
            $date_fin=$df[0].''.$df[1].''.$df[2];
            $rows[] = '<button  type="button" onclick="rapport_Entrees()" class="btn btn-primary" >
            <i class="glyphicon glyphicon-transfer"></i> Details
            </button>';

            $data[] = $rows;
            $i=$i + 1;
        }
      }elseif ($sop=="editeur") {
        $sql="SELECT * FROM `aaa-salaire-personnel` WHERE profil='Editeur catalogue' AND ( datePaiement LIKE '%".$annee."-".$mois."%' or datePaiement LIKE '%".$mois."-".$annee."%' ) ";
        $res=mysql_query($sql);

        while($pay=mysql_fetch_array($res)){
             $rows = array(); 
            $rows[] = $i;
            $rows[] = '<span style="color:blue;">'.strtoupper($pay['prenom']).'</span>';
            $rows[] = '<span style="color:blue;">'.strtoupper($pay['nom']).'</span>';
            $rows[] = '<span style="color:blue;">'.strtoupper($pay['montant']).'</span>';
            $rows[] = '<span style="color:blue;">'.strtoupper($pay['comptePaiement']).'</span>';
               
            $db = explode("-", $debut);
            $date_debut=$db[0].''.$db[1].''.$db[2];
            $df = explode("-", $fin);
            $date_fin=$df[0].''.$df[1].''.$df[2];
            $rows[] = '<button  type="button" onclick="rapport_Entrees()" class="btn btn-primary" >
            <i class="glyphicon glyphicon-transfer"></i> Details
            </button>';

            $data[] = $rows;
            $i=$i + 1;
        }
      }
}elseif ($operaton=='compte') {
      $sop=@$_GET['sop'];
      if ($sop=="mobile") {
          $nomCompte=@$_GET['nomCompte'];          
          $sql0="SELECT * FROM `aaa-compte` WHERE nomCompte='".$nomCompte."' ";
          $res0=mysql_query($sql0);
        
          while ( $compte=mysql_fetch_array($res0)) {
                $rows = array();           
                $sql="SELECT * FROM `aaa-comptemouvement` WHERE idCompte='".$compte['idCompte']."' AND dateOperation LIKE '%".$annee."-".$mois."%' or dateOperation LIKE '%".$mois."-".$annee."%' ";
                $res=mysql_query($sql);
                while($pay=mysql_fetch_array($res)){
                    $rows = array();
                    $rows[] = $i;
                    $rows[] = '<span style="color:blue;">'.strtoupper($pay['operation']).'</span>';
                    $rows[] = '<span style="color:blue;">'.strtoupper($pay['montant']).'</span>';
                    //$rows[] = '<span style="color:blue;">'.strtoupper($pay['numeroDestinataire']).'</span>';
                    $rows[] = '<span style="color:blue;">'.strtoupper($pay['description']).'</span>';
                       
                   /* $db = explode("-", $debut);
                    $date_debut=$db[0].''.$db[1].''.$db[2];
                    $df = explode("-", $fin);
                    $date_fin=$df[0].''.$df[1].''.$df[2];*/
                    $rows[] = '<button  type="button"  class="btn btn-primary" >
                    <i class="glyphicon glyphicon-transfer"></i> Details
                    </button>';

                    $data[] = $rows;
                    $i=$i + 1;
                }
          }

          
      }
      else if ($sop=="bancaire") {
        $i=1;
          $sql0="SELECT * FROM `aaa-compte` WHERE typeCompte='compte bancaire' ";
          $res0=mysql_query($sql0);
        
          while ( $compte=mysql_fetch_array($res0)) {
                $sql="SELECT * FROM `aaa-comptemouvement` WHERE idCompte='".$compte['idCompte']."' AND dateOperation LIKE '%".$annee."-".$mois."%' or dateOperation LIKE '%".$mois."-".$annee."%' ";
                $res=mysql_query($sql);

                while($pay2=mysql_fetch_array($res)){
                    $rows = array();  
                    $rows[] = $i;
                    $rows[] = '<span style="color:blue;">'.strtoupper($pay2['operation']).'</span>';
                    $rows[] = '<span style="color:blue;">'.strtoupper($pay2['montant']).'</span>';
                    //$rows[] = '<span style="color:blue;">'.strtoupper($pay['numeroDestinataire']).'</span>';
                    $rows[] = '<span style="color:blue;">'.strtoupper($pay2['description']).'</span>';
                       
                   /* $db = explode("-", $debut);
                    $date_debut=$db[0].''.$db[1].''.$db[2];
                    $df = explode("-", $fin);
                    $date_fin=$df[0].''.$df[1].''.$df[2];*/
                    $rows[] = '<button  type="button"  class="btn btn-primary" >
                    <i class="glyphicon glyphicon-transfer"></i> Details
                    </button>';

                    $data[] = $rows;
                    $i=$i + 1;
                }
          }
    
      }else if ($sop=="cheque") {
          $i=1;
          $sql0="SELECT * FROM `aaa-compte` WHERE typeCompte='compte cheques' ";
          $res0=mysql_query($sql0);
        
          while ( $compte=mysql_fetch_array($res0)) {
                $sql="SELECT * FROM `aaa-comptemouvement` WHERE idCompte='".$compte['idCompte']."' AND dateOperation LIKE '%".$annee."-".$mois."%' or dateOperation LIKE '%".$mois."-".$annee."%'";
                $res=mysql_query($sql);

                while($pay=mysql_fetch_array($res)){
                    $rows = array();  
                    $rows[] = $i;
                    $rows[] = '<span style="color:blue;">'.strtoupper($pay['operation']).'</span>';
                    $rows[] = '<span style="color:blue;">'.strtoupper($pay['montant']).'</span>';
                    //$rows[] = '<span style="color:blue;">'.strtoupper($pay['numeroDestinataire']).'</span>';
                    $rows[] = '<span style="color:blue;">'.strtoupper($pay['description']).'</span>';
                       
                   /* $db = explode("-", $debut);
                    $date_debut=$db[0].''.$db[1].''.$db[2];
                    $df = explode("-", $fin);
                    $date_fin=$df[0].''.$df[1].''.$df[2];*/
                    $rows[] = '<button  type="button"  class="btn btn-primary" >
                    <i class="glyphicon glyphicon-transfer"></i> Details
                    </button>';

                    $data[] = $rows;
                    $i=$i + 1;
                }
          }
    
      }else if ($sop=="pret") {
          $i=1;
          $sql0="SELECT * FROM `aaa-compte` WHERE typeCompte='compte pret' ";
          $res0=mysql_query($sql0);
        
          while ( $compte=mysql_fetch_array($res0)) {
                $sql="SELECT * FROM `aaa-comptemouvement` WHERE idCompte='".$compte['idCompte']."' AND dateOperation LIKE '%".$annee."-".$mois."%' or dateOperation LIKE '%".$mois."-".$annee."%' ";
                $res=mysql_query($sql);

                while($pay=mysql_fetch_array($res)){
                    $rows = array();  
                    $rows[] = $i;
                    $rows[] = '<span style="color:blue;">'.strtoupper($pay['operation']).'</span>';
                    $rows[] = '<span style="color:blue;">'.strtoupper($pay['montant']).'</span>';
                    //$rows[] = '<span style="color:blue;">'.strtoupper($pay['numeroDestinataire']).'</span>';
                    $rows[] = '<span style="color:blue;">'.strtoupper($pay['description']).'</span>';
                       
                   /* $db = explode("-", $debut);
                    $date_debut=$db[0].''.$db[1].''.$db[2];
                    $df = explode("-", $fin);
                    $date_fin=$df[0].''.$df[1].''.$df[2];*/
                    $rows[] = '<button  type="button"  class="btn btn-primary" >
                    <i class="glyphicon glyphicon-transfer"></i> Details
                    </button>';

                    $data[] = $rows;
                    $i=$i + 1;
                }
          }
    
      }else if ($sop=="pretParEntite") {
          $i=1;
          $sql0="SELECT * FROM `aaa-compte` WHERE typeCompte='compte pret' ";
          $res0=mysql_query($sql0);
        
          while ( $compte=mysql_fetch_array($res0)) {
                    $rows = array();  
                    $rows[] = $i;
                    $rows[] = '<span style="color:blue;">'.strtoupper($compte['nomCompte']).'</span>';
                    $rows[] = '<span style="color:blue;">'.strtoupper($compte['numeroCompte']).'</span>';
                    $rows[] = '<span style="color:blue;">'.strtoupper($compte['montantCompte']).'</span>';
                    $rows[] = '<button  type="button"  class="btn btn-primary" >
                    <i class="glyphicon glyphicon-transfer"></i> Details
                    </button>';

                    $data[] = $rows;
                    $i=$i + 1;
                
          }
    
      }else if ($sop=="caisse") {
        $i=1;
        $sql0="SELECT * FROM `aaa-compte` WHERE typeCompte='caisse' ";
        $res0=mysql_query($sql0);
      
        while ( $compte=mysql_fetch_array($res0)) {
              $sql="SELECT * FROM `aaa-comptemouvement` WHERE idCompte='".$compte['idCompte']."' AND dateOperation LIKE '%".$annee."-".$mois."%' or dateOperation LIKE '%".$mois."-".$annee."%' ";
              $res=mysql_query($sql);

              while($pay2=mysql_fetch_array($res)){
                  $rows = array();  
                  $rows[] = $i;
                  $rows[] = '<span style="color:blue;">'.strtoupper($pay2['operation']).'</span>';
                  $rows[] = '<span style="color:blue;">'.strtoupper($pay2['montant']).'</span>';
                  //$rows[] = '<span style="color:blue;">'.strtoupper($pay['numeroDestinataire']).'</span>';
                  $rows[] = '<span style="color:blue;">'.strtoupper($pay2['description']).'</span>';
                     
                 /* $db = explode("-", $debut);
                  $date_debut=$db[0].''.$db[1].''.$db[2];
                  $df = explode("-", $fin);
                  $date_fin=$df[0].''.$df[1].''.$df[2];*/
                  $rows[] = '<button  type="button"  class="btn btn-primary" >
                  <i class="glyphicon glyphicon-transfer"></i> Details
                  </button>';

                  $data[] = $rows;
                  $i=$i + 1;
              }
        }
    }
}





$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);

?>
