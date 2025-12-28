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

$annee =$date->format('Y');
$mois =$date->format('m');
$jour =$date->format('d');
$heureString=$date->format('H:i:s');
$dateString=$annee.'-'.$mois.'-'.$jour ;



$debut=@$_GET['debut'];
$fin=@$_GET['fin'];
$operaton=@$_GET['operation'];

$data=array();
$payements=array();
$i=1;
if ($operaton=='payement') {
  
  $sql1="SELECT * FROM `aaa-payement-reference` WHERE dateRefTransfertValidation BETWEEN '".$debut."' AND '".$fin."' ORDER BY id";
  $res1=mysql_query($sql1);

  while($pay=mysql_fetch_array($res1)){
      $rows = array();
      $rows[] = $i;
      $rows[] = '<span style="color:blue;">'.strtoupper($pay['dateRefTransfertValidation']).'</span>';
      $rows[] = '<span style="color:blue;">'.strtoupper($pay['montant']).'</span>';
      $rows[] = '<span style="color:blue;">'.strtoupper($pay['refTransfertValidation']).'</span>';
         
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
}elseif ($operaton=='salaire') {

      $sop=@$_GET['sop'];
      if ($sop=="accompagnateurs") {
        $sql="SELECT * FROM `aaa-payement-salaire` WHERE aSalaireAccompagnateur=1 AND datePS BETWEEN '".$debut."' AND '".$fin."' ";
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
        $sql="SELECT * FROM `aaa-salaire-personnel` WHERE profil='Ingenieur' AND datePaiement BETWEEN '".$debut."' AND '".$fin."' ";
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
            $rows[] = '<button  type="button" onclick="rapport_Entrees(0,'.$date_debut.','.$date_fin.')" class="btn btn-primary" >
            <i class="glyphicon glyphicon-transfer"></i> Details
            </button>';

            $data[] = $rows;
            $i=$i + 1;
        }
      }elseif ($sop=="admin") {
        $sql="SELECT * FROM `aaa-salaire-personnel` WHERE profil='Admin' AND datePaiement BETWEEN '".$debut."' AND '".$fin."' ";
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
            $rows[] = '<button  type="button" onclick="rapport_Entrees(0,'.$date_debut.','.$date_fin.')" class="btn btn-primary" >
            <i class="glyphicon glyphicon-transfer"></i> Details
            </button>';

            $data[] = $rows;
            $i=$i + 1;
        }
      }elseif ($sop=="editeur") {
        $sql="SELECT * FROM `aaa-salaire-personnel` WHERE profil='Editeur catalogue' AND datePaiement BETWEEN '".$debut."' AND '".$fin."' ";
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
            $rows[] = '<button  type="button" onclick="rapport_Entrees(0,'.$date_debut.','.$date_fin.')" class="btn btn-primary" >
            <i class="glyphicon glyphicon-transfer"></i> Details
            </button>';

            $data[] = $rows;
            $i=$i + 1;
        }
      }
}elseif ($operaton=='compte') {
      $sop=@$_GET['sop'];
      if ($sop=="mobile") {

          $sql0="SELECT * FROM `aaa-compte` WHERE typeCompte='compte mobile' ";
          $res0=mysql_query($sql0);
        
          while ( $compte=mysql_fetch_array($res0)) {
                $rows = array();           
                $sql="SELECT * FROM `aaa-comptemouvement` WHERE idCompte='".$compte['idCompte']."' AND dateOperation BETWEEN '".$debut."' AND '".$fin."' ";
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

          $sql0="SELECT * FROM `aaa-compte` WHERE typeCompte='compte bancaire' ";
          $res0=mysql_query($sql0);
        
          while ( $compte=mysql_fetch_array($res0)) {
                $sql="SELECT * FROM `aaa-comptemouvement` WHERE idCompte='".$compte['idCompte']."' AND dateOperation BETWEEN '".$debut."' AND '".$fin."' ";
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
                $sql="SELECT * FROM `aaa-comptemouvement` WHERE idCompte='".$compte['idCompte']."' AND dateOperation BETWEEN '".$debut."' AND '".$fin."' ";
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
                $sql="SELECT * FROM `aaa-comptemouvement` WHERE idCompte='".$compte['idCompte']."' AND dateOperation BETWEEN '".$debut."' AND '".$fin."' ";
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
}





$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);

?>
