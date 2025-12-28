<?php 

// 	session_start();

// 	if(!$_SESSION['iduser']){

// 		header('Location:../index.php');

// 	}

	

// require('../connection.php');



// require('../declarationVariables.php');



$reponse="produit non enregistrer";


$sqlU = "SELECT * FROM `aaa-utilisateur` where idutilisateur=".$_SESSION['iduser'];
$resU = mysql_query($sqlU) or die ("persoonel requête 2".mysql_error());
$userId= mysql_fetch_array($resU);


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

$annee =$date->format('Y');

$mois =$date->format('m');

$jour =$date->format('d');

$heureString=$date->format('H:i:s');

$dateString=$annee.'-'.$mois.'-'.$jour;

$dateString2=$jour.'-'.$mois.'-'.$annee;

$dateHeures=$dateString.' '.$heureString;

$dateStringMA=$annee.''.$mois;

/**Fin informations sur la date d'Aujourdhui **/



$operation=@htmlspecialchars($_POST["operation"]);



if($operation == 1){

    $source = [];

    //$reponse+=sizeof($codeBrute);

    // $reponse="<ul><li>aucune donnee trouvé</li></ul>";

    $query=htmlspecialchars(trim($_POST['query']));

    $reqS="SELECT * from `".$nomtableDesignation."` where lower(designation) LIKE '%$query%' ORDER BY idDesignation ASC LIMIT 10  ";

    $resS=mysql_query($reqS) or die ("insertion impossible");



    if($resS){

      // $reponse="<ul class='ulc'>";

        while ($data=mysql_fetch_array($resS)) {

              // $reponse.="<li class='lic'>".$data['designation']."-".$data['idDesignation']."</li>";

              if(($data['uniteStock']=='Transaction' || $data['uniteStock']=='Credit')  && $data['seuil']==1){

                //$reponse.="<li class='lic' style='background-color: #e0dd21ed; '>".$data['designation']." </li>";

              }

              if($data['uniteStock']!='Transaction'){

                  if($data['classe']==0){

                    if (($_SESSION['type']=="Superette") && ($_SESSION['categorie']=="Detaillant")) {

                      if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1){

                        $sql_t="SELECT * FROM `".$nomtableStock."` where idDesignation='".$data['idDesignation']."'  ";

                        $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

                        if(mysql_num_rows($res_t)){

                          // $reponse.="<li class='licV' style='background-color: #abdbb7;'><span class='btn btn-group btnSpan' style='font-size: 18px; '>".$data['designation']." <span style='display:none'>=></span>&nbsp;&nbsp;<span disabled='true' class='badge bg-warning'>".$t_stock[0]."</span></span></li>";

                          $source[] = $data['designation'];

                        }  

                      }

                      else{

                        $sql_t="SELECT * FROM `".$nomtableStock."` where idDesignation='".$data['idDesignation']."'  ";

                        $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

                        if(mysql_num_rows($res_t)){

                          // $reponse.="<li class='licV' style='background-color: #abdbb7;'><span class='btn btn-group btnSpan' style='font-size: 18px; '>".$data['designation']." <span style='display:none'>=></span>&nbsp;&nbsp;<span disabled='true' class='badge bg-warning'></span></span></li>";

                         $source[] = $data['designation'];

                        }

                      } 

                    } 

                    else{

                      $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$data['idDesignation']."' ";

                      $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

                      $t_stock = mysql_fetch_array($res_t) ;

                      if($t_stock[0]>0){

                        // $reponse.="<li class='licV' style='background-color: #abdbb7;'><span class='btn btn-group btnSpan' style='font-size: 18px; '>".$data['designation']." <span style='display:none'>=></span>&nbsp;&nbsp;<span disabled='true' class='badge bg-warning'>".$t_stock[0]."</span></span></li>";

                         $source[] = $data['designation'].' => '.$t_stock[0];

                      }  

                    }    

                  }

                  if($data['classe']==1){

                    // $reponse.="<li class='licV'  style='background-color: #abdbb7;'><span class='btn btn-group btnSpan' style='font-size: 18px; '>".$data['designation']." </span></li>";

                      $source[] = $data['designation'];

                  }

                  if($data['classe']==2){

                    // $reponse.="<li class='licV'  style='background-color: #f0a7a1ed;'><span class='btn btn-group btnSpan' style='font-size: 18px; '>".$data['designation']." </span></li>";

                      $source[] = $data['designation'];

                  }

                  if($data['classe']==3){

                    if($data['seuil']==1){

                      // $reponse.="<li class='licV'  style='background-color: #e0dd21ed;'><span class='btn btn-group btnSpan' style='font-size: 18px; '>".$data['codeBarreDesignation']." <span style='display:none'>=></span>&nbsp;&nbsp;<span disabled='true'> ".$data['designation']." ".$data['uniteStock']."</span></span></li>";

                         $source[] = $data['codeBarreDesignation'].' => '.$data['designation']." ".$data['uniteStock'];

                    }

                  }

                  if($data['classe']==5){

                    // $reponse.="<li class='licV'  style='background-color: #aceef3;font-size: 20px; '><span class='btn btn-group btnSpan' style='font-size: 18px; '>".$data['designation']." </span></li>";

                    $source[] = $data['designation'];

                  }

                  //if($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1){

                    if($data['classe']==6){

                      // $reponse.="<li class='licV'  style='background-color: #aceef3; font-size: 20px;'><span class='btn btn-group btnSpan' style='font-size: 18px; '>".$data['designation']." </span></li>";

                      $source[] = $data['designation'];

                    }

                  //}

                  if($data['classe']==7){

                    // $reponse.="<li class='licV'  style='background-color: #aceef3;font-size: 20px; '><span class='btn btn-group btnSpan' style='font-size: 18px; '>".$data['designation']." </span></li>";

                    $source[] = $data['designation'];

                  }

                  if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1){

                    if($data['classe']==8){

                      // $reponse.="<li class='licV'  style='background-color: #f0a7a1ed; font-size: 20px;'><span class='btn btn-group btnSpan' style='font-size: 18px; '>".$data['designation']." </span></li>";

                      $source[] = $data['designation'];

                    }

                  }

                  if (($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1) && $_SESSION['enConfiguration']==0){

                    if($data['classe']==9){

                      // $reponse.="<li class='licV'  style='background-color: #aceef3;font-size: 20px; '><span class='btn btn-group btnSpan' style='font-size: 18px; '>".$data['designation']." </span></li>";

                      $source[] = $data['designation'];

                    }

                  }

                  if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1){

                    if($data['classe']==10){

                      // $reponse.="<li class='licV'  style='background-color: #aceef3;font-size: 20px; '><span class='btn btn-group btnSpan' style='font-size: 18px; '>".$data['designation']." </span></li>";

                      $source[] = $data['designation'];

                    }

                  }

                  if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1){

                    if($data['classe']==20){

                      // $reponse.="<li class='licV'  style='background-color: #aceef3;font-size: 20px; '><span class='btn btn-group btnSpan' style='font-size: 18px; '>".$data['designation']." </span></li>";

                      $source[] = $data['designation'];

                    }

                  }

                }

            }

      // $reponse.="</ul>";

    }

    // exit($reponse);

    echo json_encode($source);

}

else if($operation == 2){

  //$reponse+=sizeof($codeBrute);

  $reponse="<ul><li>aucune donnee trouvé</li></ul>";

  $query=htmlspecialchars(trim($_POST['query']));

  $reqS="SELECT * from `".$nomtableDesignation."` where designation LIKE '%$query%' ";

  $resS=mysql_query($reqS) or die ("insertion impossible");



  if($resS){

    $reponse="<ul class='ulc'>";

      while ($data=mysql_fetch_array($resS)) {

            if($data['uniteStock']!='Transaction' && $data['uniteStock']!='Facture' && $data['uniteStock']!='Credit'){

                if($data['classe']==0){

                  $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$data['idDesignation']."' ";

                  $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

                  $t_stock = mysql_fetch_array($res_t) ;

                  if($t_stock[0]>0){

                    $reponse.="     <li class='lic' style='background-color: #abdbb7; '>".$data['designation']." <span style='display:none'>=></span>&nbsp;&nbsp;<span disabled='true' class='badge bg-warning'>".$t_stock[0]."</span></li>";

                  }        

                }

                if($data['classe']==1){

                  $reponse.="<li class='lic'  style='background-color: #abdbb7; '>".$data['designation']." </li>";

                }

                if($data['classe']==6){

                  $reponse.="<li class='lic'  style='background-color: #aceef3; '>".$data['designation']." </li>";

                }

                if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1){

                  if($data['classe']==8){

                    $reponse.="<li class='lic'  style='background-color: #f0a7a1ed; '>".$data['designation']." </li>";

                  }

                }

                if (($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1) && $_SESSION['enConfiguration']==0){

                  if($data['classe']==9){

                    $reponse.="<li class='lic'  style='background-color: #aceef3; '>".$data['designation']." </li>";

                  }

                }

              }

          }

    $reponse.="</ul>";

  }

  exit($reponse);

}

else if($operation == 3){

  $source = [];

  //$reponse+=sizeof($codeBrute);

  // $reponse="<ul><li>aucune donnee trouvé</li></ul>";

  $query=htmlspecialchars(trim($_POST['query']));

  // $reqS="SELECT * from `".$nomtableDesignation."` where designation LIKE '%$query%' ";

  $reqS="SELECT *, Sum(quantiteStockCourant) as t_stock from `".$nomtableDesignation."` d, `".$nomtableStock."` s where d.idDesignation=s.idDesignation and d.classe=0 and s.designation LIKE '%$query%' GROUP BY s.idDesignation 
  HAVING SUM(quantiteStockCourant)>0";

  $resS=mysql_query($reqS) or die ("insertion impossible");
  
  $reqD="SELECT * from `".$nomtableDesignation."` where classe<>0 and designation LIKE '%$query%'";

  $resD=mysql_query($reqD) or die ("get designation by classe<>0");


  if($resS){

    // $reponse="<ul class='ulc'>";

      while ($data=mysql_fetch_array($resS)) {

            // $reponse.="<li class='lic'>".$data['designation']."-".$data['idDesignation']."</li>";

            /*if($data['uniteStock']=='Transaction' && $data['seuil']==1){

              $reponse.="<li class='lic' style='background-color: #e0dd21ed; '>".$data['designation']." </li>";

            }*/

            if($data['forme']!='Transaction' && $data['forme']!='Facture' && $data['forme']!='Credit'){

              if($data['classe']==0){

                // $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$data['idDesignation']."' ";

                // $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

                // $t_stock = mysql_fetch_array($res_t) ;

                // if($t_stock[0]>0){

                  // $reponse.="     <li class='lic' style='background-color: #abdbb7; '>".$data['designation']." <span style='display:none'>=></span>&nbsp;&nbsp;<span disabled='true' class='badge bg-warning'>".$t_stock[0]."</span></li>";

                  $source[] = $data['designation'].' => '.$data['t_stock'];

                // }        

              }

            }

          }

    // $reponse.="</ul>";

  }

  if ($resD) {
      

    while ($data=mysql_fetch_array($resD)) {
      

      if($data['forme']!='Transaction' && $data['forme']!='Facture' && $data['forme']!='Credit'){

        if($data['classe']==1){

          // $reponse.="<li class='lic'  style='background-color: #abdbb7; '>".$data['designation']." </li>";

          $source[] = $data['designation'];

        }

        if($data['classe']==2){

          // $reponse.="<li class='lic'  style='background-color: #f0a7a1ed; '>".$data['designation']." </li>";

          $source[] = $data['designation'];

        }

        if (($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1) && $_SESSION['enConfiguration']==0){

          if($data['classe']==9){

            // $reponse.="<li class='lic'  style='background-color: #aceef3; '>".$data['designation']." </li>";

            $source[] = $data['designation'];

          }

        }

      }
    }
  }

  // exit($reponse);

  echo json_encode($source);

}

else if($operation == 4){

  //$reponse+=sizeof($codeBrute);

  $reponse="<ul><li>aucune donnee trouvé</li></ul>";

  $query=htmlspecialchars(trim($_POST['query']));

  $reqS="SELECT * from `".$nomtableDesignation."` where designation LIKE '%$query%' ";

  $resS=mysql_query($reqS) or die ("insertion impossible");



  if($resS){

    $reponse="<ul class='ulc'>";

      while ($data=mysql_fetch_array($resS)) {

            // $reponse.="<li class='lic'>".$data['designation']."-".$data['idDesignation']."</li>";

            /*if($data['uniteStock']=='Transaction' && $data['seuil']==1){

              $reponse.="<li class='lic' style='background-color: #e0dd21ed; '>".$data['designation']." </li>";

            }*/

            if($data['forme']!='Transaction' && $data['forme']!='Facture' && $data['forme']!='Credit'){

                if($data['classe']==0){

                  $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$data['idDesignation']."' ";

                  $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

                  $t_stock = mysql_fetch_array($res_t) ;

                  if($t_stock[0]>0){

                    $reponse.="     <li class='lic' style='background-color: #abdbb7; '>".$data['designation']." <span style='display:none'>=></span>&nbsp;&nbsp;<span disabled='true' class='badge bg-warning'>".$t_stock[0]."</span></li>";

                  }        

                }

                if($data['classe']==1){

                  $reponse.="<li class='lic'  style='background-color: #abdbb7; '>".$data['designation']." </li>";

                }

                if($data['classe']==6){

                  $reponse.="<li class='lic'  style='background-color: #aceef3; '>".$data['designation']." </li>";

                }

                if (($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1) && $_SESSION['enConfiguration']==0){

                  if($data['classe']==9){

                    $reponse.="<li class='lic'  style='background-color: #aceef3; '>".$data['designation']." </li>";

                  }

                }

              }

          }

    $reponse.="</ul>";

  }

  exit($reponse);

}

else if($operation == 5){

  //$reponse+=sizeof($codeBrute);

  $reponse="<ul><li>aucune donnee trouvé</li></ul>";

  $query=htmlspecialchars(trim($_POST['query']));

  $reqS="SELECT * from `".$nomtableDesignation."` where designation LIKE '%$query%' ";

  $resS=mysql_query($reqS) or die ("insertion impossible");



  if($resS){

    $reponse="<ul class='ulc'>";

      while ($data=mysql_fetch_array($resS)) {

            // $reponse.="<li class='lic'>".$data['designation']."-".$data['idDesignation']."</li>";

            if(($data['uniteStock']=='Transaction' || $data['uniteStock']=='Credit')  && $data['seuil']==1){

              $reponse.="<li class='lic' style='background-color: #e0dd21ed; '>".$data['designation']." </li>";

            }

            if($data['uniteStock']!='Transaction' && $data['uniteStock']!='Facture' && $data['uniteStock']!='Credit'){

                if($data['classe']==0){

                  $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$data['idDesignation']."' ";

                  $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

                  $t_stock = mysql_fetch_array($res_t) ;

                  if($t_stock[0]>0){

                    $reponse.="     <li class='lic' style='background-color: #abdbb7; '>".$data['designation']." <span style='display:none'>=></span>&nbsp;&nbsp;<span disabled='true' class='badge bg-warning'>".$t_stock[0]."</span></li>";

                  }        

                }

                if($data['classe']==1){

                  $reponse.="<li class='lic'  style='background-color: #abdbb7; '>".$data['designation']." </li>";

                }

                if($data['classe']==2){

                  $reponse.="<li class='lic'  style='background-color: #f0a7a1ed; '>".$data['designation']." </li>";

                }

                if($data['classe']==5){

                  $reponse.="<li class='lic'  style='background-color: #aceef3; '>".$data['designation']." </li>";

                }

                if($data['classe']==7){

                  $reponse.="<li class='lic'  style='background-color: #aceef3; '>".$data['designation']." </li>";

                }

                if($data['classe']==8){

                  $reponse.="<li class='lic'  style='background-color: #f0a7a1ed; '>".$data['designation']." </li>";

                }

                if (($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1) && $_SESSION['enConfiguration']==0){

                  if($data['classe']==9){

                    $reponse.="<li class='lic'  style='background-color: #aceef3; '>".$data['designation']." </li>";

                  }

                }

              }

          }

    $reponse.="</ul>";

  }

  exit($reponse);

}

else if($operation == 6){

  //$reponse+=sizeof($codeBrute);

  $designation=$_POST["designation"];

  $idPagnet=@htmlspecialchars($_POST["idPagnet"]);

  $result="0";

  $sql0="SELECT * FROM `".$nomtablePagnetTampon."` where idPagnet=".$idPagnet." ";

  $res0 = mysql_query($sql0) or die ("persoonel requête 2".mysql_error());

  $pagnet = mysql_fetch_assoc($res0) ;


  $sql="SELECT * FROM `".$nomtableDesignation."` where (codeBarreDesignation='".$designation."' or codeBarreuniteStock='".$designation."' or designation='".$designation."') ";

  $res=mysql_query($sql) or die ("select stock impossible =>".mysql_error());

  if(mysql_num_rows($res)){

    $design = mysql_fetch_assoc($res);

    if($design['classe']==0){

      $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$design['idDesignation']."' ";

      $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

      $t_stock = mysql_fetch_array($res_t) ;

      $restant = $t_stock[0];

      if($restant>0){

          /***Debut verifier si la ligne du produit existe deja ***/

          $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and classe=0 ";

          $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

          $ligne = mysql_fetch_assoc($res);

          /***Debut verifier si la ligne du produit existe deja ***/

          if($ligne != null){

              $quantite = $ligne['quantite'] + 1;

              $reste = $t_stock[0] - $quantite;

              if ($reste>=0){

                  $prixTotal=$quantite*$ligne['prixPublic'];

                  $sql7="UPDATE `".$nomtableLigneTampon."` set quantite=".$quantite.",prixtotal=".$prixTotal." where idPagnet=".$idPagnet." and idDesignation=".$design['idDesignation'];

                  $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());



                  $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

                  $res2=mysql_query($sql2);

                  if(mysql_num_rows($res2)){

                    $ligne=mysql_fetch_assoc($res2);

                    $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                    $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                    $TotalT = mysql_fetch_array($resT) ;

                    $result='2<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$design['forme'].'<>'.$design['prixPublic'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$quantite.'<>'.$pagnet['taux'];

                  }

              }

              else{

                  $result='3<>'.$t_stock[0];

              }                     

          }

          else {

              $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet=".$idPagnet." ";

              $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

              $ligne = mysql_fetch_assoc($res) ;

              if(mysql_num_rows($res)){

                  if($ligne['classe']==0 || $ligne['classe']==1){

                      $sql7="insert into `".$nomtableLigneTampon."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idPagnet.",0)";

                      $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                      $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

                      $res2=mysql_query($sql2);

                      if(mysql_num_rows($res2)){

                        $ligne=mysql_fetch_assoc($res2);

                        $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                        $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                        $TotalT = mysql_fetch_array($resT) ;

                        $result='1<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$design['forme'].'<>'.$design['prixPublic'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$pagnet['taux'];

                      }

                  }

              }

              else{

                  $sql7="insert into `".$nomtableLigneTampon."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idPagnet.",0)";

                  $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                  $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

                  $res2=mysql_query($sql2);

                  if(mysql_num_rows($res2)){

                    $ligne=mysql_fetch_assoc($res2);

                    $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                    $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                    $TotalT = mysql_fetch_array($resT) ;

                    $result='1<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$design['forme'].'<>'.$design['prixPublic'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$pagnet['taux'];

                  }

              }

          

          }

      }

      else{

        $result='3<>0';

      }

    }

    if($design['classe']==1){

        $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet=".$idPagnet." ";

        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

        $ligne = mysql_fetch_assoc($res) ;

        if(mysql_num_rows($res)){

            /***Debut verifier si la ligne du produit existe deja ***/

            $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and classe=1 ";

            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

            $ligne1 = mysql_fetch_assoc($res);

            /***Debut verifier si la ligne du produit existe deja ***/

            if($ligne1 != null){

                $quantite = $ligne1['quantite'] + 1;

                $prixTotal=$quantite*$ligne1['prixPublic'];

                $sql7="UPDATE `".$nomtableLigneTampon."` set quantite=".$quantite.",prixtotal=".$prixTotal." where idPagnet=".$idPagnet." and idDesignation=".$design['idDesignation'];

                $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());



                $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

                $res2=mysql_query($sql2);

                if(mysql_num_rows($res2)){

                  $ligne=mysql_fetch_assoc($res2);

                  $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                  $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                  $TotalT = mysql_fetch_array($resT) ;

                  $result='2<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$design['forme'].'<>'.$design['prixPublic'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$quantite.'<>'.$pagnet['taux'];

                }

            }

            else {

                if($ligne['classe']==1){

                    if($design['forme']!='Transaction'){

                      $sql7="insert into `".$nomtableLigneTampon."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idPagnet.",1)";

                      $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                      $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

                      $res2=mysql_query($sql2);

                      if(mysql_num_rows($res2)){

                        $ligne=mysql_fetch_assoc($res2);

                        $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                        $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                        $TotalT = mysql_fetch_array($resT) ;

                        $result='4<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$design['forme'].'<>'.$design['prixPublic'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$pagnet['taux'];

                      }

                    }

                    

                }

                else if($ligne['classe']==0){

                  if($design['forme']!='Transaction'){

                    $sql7="insert into `".$nomtableLigneTampon."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idPagnet.",1)";

                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                    $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

                    $res2=mysql_query($sql2);

                    if(mysql_num_rows($res2)){

                      $ligne=mysql_fetch_assoc($res2);

                      $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                      $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                      $TotalT = mysql_fetch_array($resT) ;

                      $result='1<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$design['forme'].'<>'.$design['prixPublic'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$pagnet['taux'];

                    }

                  }

                  

              }

            }

            

        }

        else{

            if($design['forme']=='Transaction'){

              /*  $reqT="SELECT * from `aaa-transaction` where idTransaction='".$design['description']."'";

                $resT=mysql_query($reqT) or die ("select stock impossible =>".mysql_error());

                $transaction = mysql_fetch_array($resT);

                $image=$transaction['aliasTransaction'];

                $trans_alias=$transaction['aliasTransaction'];

                $trans_pagnet=$idPagnet;

                $msg_transaction="OK";*/

            }

            else{

              $sql7="insert into `".$nomtableLigneTampon."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idPagnet.",1)";

              $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



              $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

              $res2=mysql_query($sql2);

              if(mysql_num_rows($res2)){

                $ligne=mysql_fetch_assoc($res2);

                $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                $TotalT = mysql_fetch_array($resT) ;

                $result='4<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$design['forme'].'<>'.$design['prixPublic'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$pagnet['taux'];

              }

            }

        }

    }

    if($design['classe']==2){

        $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet=".$idPagnet." ";

        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

        $ligne = mysql_fetch_assoc($res) ;

        if(mysql_num_rows($res)){

            /***Debut verifier si la ligne du produit existe deja ***/

            $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and classe=2 ";

            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

            $ligne1 = mysql_fetch_assoc($res);

            /***Debut verifier si la ligne du produit existe deja ***/

            if($ligne1 != null){

                $quantite = $ligne1['quantite'] + 1;

                $prixTotal=$quantite*$ligne1['prixPublic'];

                $sql7="UPDATE `".$nomtableLigneTampon."` set quantite=".$quantite.",prixtotal=".$prixTotal." where idPagnet=".$idPagnet." and idDesignation=".$design['idDesignation'];

                $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());



                $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

                $res2=mysql_query($sql2);

                if(mysql_num_rows($res2)){

                  $ligne=mysql_fetch_assoc($res2);

                  $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                  $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                  $TotalT = mysql_fetch_array($resT) ;

                  $result='2<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$design['forme'].'<>'.$design['prixPublic'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$quantite.'<>'.$pagnet['taux'];

                }

            }

            else {

                if($ligne['classe']==2){

                  $sql7="insert into `".$nomtableLigneTampon."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idPagnet.",2)";

                  $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                  $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

                  $res2=mysql_query($sql2);

                  if(mysql_num_rows($res2)){

                    $ligne=mysql_fetch_assoc($res2);

                    $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                    $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                    $TotalT = mysql_fetch_array($resT) ;

                    $result='1<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$design['forme'].'<>'.$design['prixPublic'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$pagnet['taux'];

                  }

                }

            }

        }

        else{

          $sql7="insert into `".$nomtableLigneTampon."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idPagnet.",2)";

          $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



          $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

          $res2=mysql_query($sql2);

          if(mysql_num_rows($res2)){

            $ligne=mysql_fetch_assoc($res2);

            $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

            $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

            $TotalT = mysql_fetch_array($resT) ;

            $result='1<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$design['forme'].'<>'.$design['prixPublic'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$pagnet['taux'];

          }

        }

    }

    if($design['classe']==9){

        $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet=".$idPagnet." ";

        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

        $ligne = mysql_fetch_assoc($res) ;

        if(mysql_num_rows($res)){

            /***Debut verifier si la ligne du produit existe deja ***/

            $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and classe=9 ";

            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

            $ligne1 = mysql_fetch_assoc($res);

            /***Debut verifier si la ligne du produit existe deja ***/

            if($ligne1 != null){



            }

            else {

                if($ligne['classe']==9){

                  $sql7="insert into `".$nomtableLigneTampon."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idPagnet.",9)";

                  $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                  $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

                  $res2=mysql_query($sql2);

                  if(mysql_num_rows($res2)){

                    $ligne=mysql_fetch_assoc($res2);

                    $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                    $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                    $TotalT = mysql_fetch_array($resT) ;

                    $result='1<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$design['forme'].'<>'.$design['prixPublic'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$pagnet['taux'];

                  }

                }

            }

        }

        else{

          $sql7="insert into `".$nomtableLigneTampon."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idPagnet.",9)";

          $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



          $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

          $res2=mysql_query($sql2);

          if(mysql_num_rows($res2)){

            $ligne=mysql_fetch_assoc($res2);

            $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

            $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

            $TotalT = mysql_fetch_array($resT) ;

            $result='1<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$design['forme'].'<>'.$design['prixPublic'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$ligne['classe'].'<>'.$pagnet['taux'];

          }

        }

    }

  }

  exit($result);

}

else if($operation == 7){



  $idPagnet=@htmlspecialchars($_POST["idPagnet"]);

  $numligne=@htmlspecialchars($_POST["numligne"]);

  $result="0";

  $sql1="SELECT * from  `".$nomtableLigneTampon."` WHERE numligne='".$numligne."' ";

  $res1=mysql_query($sql1);

  if(mysql_num_rows($res1)){

      $ligne=mysql_fetch_assoc($res1);

      $sql7="DELETE FROM `".$nomtableLigneTampon."` where numligne='".$numligne."' ";

      $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



      $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

      $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

      $TotalT = mysql_fetch_array($resT) ;

      $sql0="SELECT * FROM `".$nomtablePagnetTampon."` where idPagnet=".$idPagnet." ";
      $res0 = mysql_query($sql0) or die ("persoonel requête 2".mysql_error());
      $pagnet = mysql_fetch_assoc($res0) ;

      $result='1<>'.$ligne['idDesignation'].'<>'.$ligne['designation'].'<>'.$ligne['forme'].'<>'.$ligne['prixPublic'].'<>'.$numligne.'<>'.$TotalT[0].'<>'.$pagnet['taux'];

  }

  exit($result);

}

else if($operation == 8){

  //$reponse+=sizeof($codeBrute);

  $source = [];

  // $reponse="<ul><li>aucune donnee trouvé</li></ul>";

  $query=htmlspecialchars(trim($_POST['query']));



  if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

    if($_SESSION['proprietaire']==1){

      $sqlV="SELECT DISTINCT d.designation

        FROM `".$nomtableDesignation."` d

        INNER JOIN `".$nomtableLigneTampon."` l ON l.idDesignation = d.idDesignation

        LEFT JOIN `".$nomtablePagnetTampon."` p ON p.idPagnet = l.idPagnet

        LEFT JOIN `".$nomtableMutuellePagnet."` m ON m.idMutuellePagnet = l.idMutuellePagnet

        WHERE (p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."' && p.type=0)  or (m.idClient=0  && m.verrouiller=1 && m.datepagej ='".$dateString2."'  && m.type=0) && d.designation LIKE '%$query%'  ";

      $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());

    }

    else{

      $sqlV="SELECT DISTINCT d.designation

        FROM `".$nomtableDesignation."` d

        INNER JOIN `".$nomtableLigneTampon."` l ON l.idDesignation = d.idDesignation

        LEFT JOIN `".$nomtablePagnetTampon."` p ON p.idPagnet = l.idPagnet

        LEFT JOIN `".$nomtableMutuellePagnet."` m ON m.idMutuellePagnet = l.idMutuellePagnet

        WHERE p.iduser='".$_SESSION['iduser']."' && (p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."' && p.type=0)  or (m.idClient=0  && m.verrouiller=1 && m.datepagej ='".$dateString2."'  && m.type=0) && d.designation LIKE '%$query%'  ";

      $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());

    }

  }

  else{

    $sqlV="SELECT DISTINCT d.designation

      FROM `".$nomtableDesignation."` d

      INNER JOIN `".$nomtableLigneTampon."` l ON l.idDesignation = d.idDesignation

      INNER JOIN `".$nomtablePagnetTampon."` p ON p.idPagnet = l.idPagnet

      WHERE p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateString2."'  && p.type=0 && d.designation LIKE '%$query%'  ";

    $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());

  }



  if($resV){

    // $reponse="<ul class='ulc'>";

      while ($data=mysql_fetch_array($resV)) {

        $source[] = $data['designation'];

        // $reponse.="<li class='liV'  style='background-color: #abdbb7; '>".$data['designation']." </li>";

      }

    // $reponse.="</ul>";

  }

  // exit($reponse);

  echo json_encode($source);

}

else if($operation == 9){

  //$reponse+=sizeof($codeBrute);

  $reponse="<ul><li>aucune donnee trouvé</li></ul>";

  $query=htmlspecialchars(trim($_POST['query']));

  $date_jour=@htmlspecialchars($_POST["date_jour"]);



  if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

    if($_SESSION['proprietaire']==1){

      $sqlV="SELECT DISTINCT d.designation

        FROM `".$nomtableDesignation."` d

        INNER JOIN `".$nomtableLigneTampon."` l ON l.idDesignation = d.idDesignation

        INNER JOIN `".$nomtablePagnetTampon."` p ON p.idPagnet = l.idPagnet

        WHERE p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$date_jour."'  && p.type=0 && d.designation LIKE '%$query%'  ";

      $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());

    }

    else{

      $sqlV="SELECT DISTINCT d.designation

        FROM `".$nomtableDesignation."` d

        INNER JOIN `".$nomtableLigneTampon."` l ON l.idDesignation = d.idDesignation

        INNER JOIN `".$nomtablePagnetTampon."` p ON p.idPagnet = l.idPagnet

        WHERE p.iduser='".$_SESSION['iduser']."' && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$date_jour."'  && p.type=0 && d.designation LIKE '%$query%'  ";

      $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());

    }

  }

  else{

    $sqlV="SELECT DISTINCT d.designation

      FROM `".$nomtableDesignation."` d

      INNER JOIN `".$nomtableLigneTampon."` l ON l.idDesignation = d.idDesignation

      INNER JOIN `".$nomtablePagnetTampon."` p ON p.idPagnet = l.idPagnet

      WHERE p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$date_jour."'  && p.type=0 && d.designation LIKE '%$query%'  ";

    $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());

  }



  if($resV){

    $reponse="<ul class='ulc'>";

      while ($data=mysql_fetch_array($resV)) {

        if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

          $reponse.="<li class='liVapPh'  style='background-color: #abdbb7; '>".$data['designation']." </li>";

        }

        else{

          $reponse.="<li class='liVap'  style='background-color: #abdbb7; '>".$data['designation']." </li>";

        }

      }

    $reponse.="</ul>";

  }

  exit($reponse);

}

else if($operation == 10){

  //$reponse+=sizeof($codeBrute);

  $reponse="<ul><li>aucune donnee trouvé</li></ul>";

  $query=htmlspecialchars(trim($_POST['query']));

  $idClient=@htmlspecialchars($_POST["idClient"]);



  $sqlV="SELECT DISTINCT d.designation

    FROM `".$nomtableDesignation."` d

    INNER JOIN `".$nomtableLigneTampon."` l ON l.idDesignation = d.idDesignation

    INNER JOIN `".$nomtablePagnetTampon."` p ON p.idPagnet = l.idPagnet

    WHERE p.idClient='".$idClient."'  && p.verrouiller=1 && p.type=0 && d.designation LIKE '%$query%'  ";

  $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());



  if($resV){

    $reponse="<ul class='ulc'>";

      while ($data=mysql_fetch_array($resV)) {

          $reponse.="<li class='liB'  style='background-color: #abdbb7; '>".$data['designation']." </li>";

      }

    $reponse.="</ul>";

  }

  exit($reponse);

}

else if($operation == 11){

  //$reponse+=sizeof($codeBrute);

  $reponse="<ul><li>aucune donnee trouvé</li></ul>";

  $query=htmlspecialchars(trim($_POST['query']));

  $reqS="SELECT * from `aaa-catalogue-Pharmacie-Detaillant` where designation LIKE '%$query%' ";

  $resS=mysql_query($reqS) or die ("insertion impossible");



  if($resS){

    $reponse="<ul class='ulc'>";

      while ($data=mysql_fetch_array($resS)) {

        $reponse.="<li class='liD'  style='background-color: #abdbb7; '>".$data['designation']." </li>";   

      }

    $reponse.="</ul>";

  }

  exit($reponse);

}

else if($operation == 12){

  $designation=@htmlspecialchars($_POST["designation"]);

  $sql3='SELECT * from  `aaa-catalogue-Pharmacie-Detaillant` where designation="'.$designation.'" ';

  $res3=mysql_query($sql3);

  if($design=mysql_fetch_array($res3)){

    $result='1<>'.$design['idFusion'].'<>'.$design['designation'].'<>'.$design['categorie'].'<>'.$design['forme'].'<>'.$design['tableau'].'<>'.$design['prixSession'].'<>'.$design['prixPublic'].'<>'.$design['codeBarreDesignation'];

  }

  else{

    $result="0";

  }

  exit($result);

}

else if($operation == 13){

  $source = [];

  //$reponse+=sizeof($codeBrute);

  // $reponse="<ul><li>aucune donnee trouvé</li></ul>";

  $query=htmlspecialchars(trim($_POST['query']));
  $idPagnet=htmlspecialchars(trim($_POST['idPagnet']));

  // $reqS="SELECT * from `".$nomtableDesignation."` where designation LIKE '%$query%' ";

  // $resS=mysql_query($reqS) or die ("insertion impossible");
  
  $reqP="SELECT * from `".$nomtablePagnetTampon."` where idPagnet=".$idPagnet;
  $resP=mysql_query($reqP) or die ("insertion impossible");
  $panier=mysql_fetch_array($resP);
  
  if (($userId['idEntrepot']==0 || $userId['idEntrepot']==null || $userId['idEntrepot']=='') || $panier['type']==10) {
    
    $reqS="SELECT *, Sum(quantiteStockCourant) as t_stock, d.designation as ref, d.nbreArticleUniteStock as nbra from `".$nomtableDesignation."` d, `".$nomtableEntrepotStock."` s where d.idDesignation=s.idDesignation and d.classe=0 and d.designation LIKE '%$query%' GROUP BY s.idDesignation 
    HAVING SUM(quantiteStockCourant)>0";

  } else {
      
    $reqS="SELECT *, Sum(quantiteStockCourant) as t_stock, d.designation as ref, d.nbreArticleUniteStock as nbra from `".$nomtableDesignation."` d, `".$nomtableEntrepotStock."` s where d.idDesignation=s.idDesignation and d.classe=0 and idEntrepot=".$userId['idEntrepot']." and d.designation LIKE '%$query%' GROUP BY s.idDesignation 
    HAVING SUM(quantiteStockCourant)>0";
  }

  $resS=mysql_query($reqS) or die ("insertion impossible");

  
  $reqD="SELECT * from `".$nomtableDesignation."` where classe<>0 and designation LIKE '%$query%'";

  $resD=mysql_query($reqD) or die ("get designation by classe<>0");

    if($resS){

      // $reponse="<ul class='ulc'>";

        while ($data=mysql_fetch_array($resS)) {

              // $reponse.="<li class='lic'>".$data['designation']."-".$data['idDesignation']."</li>";

              if(($data['uniteStock']=='Transaction' || $data['uniteStock']=='Credit')  && $data['seuil']==1){

                // $reponse.="<li class='licET' style='background-color: #e0dd21ed;'>".$data['designation']." </li>";

                $source[] = $data['designation'];

              }

                if($data['uniteStock']!='Transaction' && $data['uniteStock']!='Facture' && $data['uniteStock']!='Credit'){

                  // if($data['classe']==0) {

                  //   if (($userId['idEntrepot']==0 || $userId['idEntrepot']==null || $userId['idEntrepot']=='') || $panier['type']==10) {
                  //     # code...
                  //     $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableEntrepotStock."` where idDesignation='".$data['idDesignation']."'";

                  //   } else {
                  //     # code...
                  //     $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableEntrepotStock."` where idDesignation='".$data['idDesignation']."' AND idEntrepot=".$userId['idEntrepot']."";

                  //   }

                  //   $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

                  //   $t_stock = mysql_fetch_array($res_t) ;

                  //   if ($t_stock[0]>0) {

                      // $reponse.="<li class='licET' style='background-color: #abdbb7; '><span class='btn btn-group btnSpan' style='font-size: 18px; '>".$data['designation']."<span style='display:none'>=></span>&nbsp;&nbsp;<span disabled='true' class='badge bg-warning'>".($t_stock[0] / $data['nbreArticleUniteStock']) ."</span></span></li>";

                      if ($_SESSION['caissierNoAccess']==1) {
                        # code...
                        $source[] = $data['ref'];
                      } else {
                        # code...
                        $source[] = $data['ref'].' => '.($data['t_stock'] / $data['nbra']);
                      }

                    // }        

                  // }

                }

            }

      // $reponse.="</ul>";

    }
    
  if ($resD) {
      

    while ($data=mysql_fetch_array($resD)) {
      if($data['uniteStock']!='Transaction' && $data['uniteStock']!='Facture' && $data['uniteStock']!='Credit'){

        if($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1){

          if($data['classe']==6){

            // $reponse.="<li class='licET' style='background-color: #aceef3; '><span class='btn btn-group btnSpan' style='font-size: 18px; '>".$data['designation']."</span></li>";

            $source[] = $data['designation'];

          }

        }
        
        if (($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1)){

          if($data['classe']==7){

            $source[] = $data['designation'];

          }

        }

        if ($_SESSION['enConfiguration']==0){

          if($data['classe']==9){

            // $reponse.="<li class='licET'  style='background-color: #aceef3; '><span class='btn btn-group btnSpan' style='font-size: 18px; '>".$data['designation']."</span></li>";

            $source[] = $data['designation'];

          }

        }

        if($data['classe']==1){

          // $reponse.="<li class='licV'  style='background-color: #abdbb7;'><span class='btn btn-group btnSpan' style='font-size: 18px; '>".$data['designation']." </span></li>";

            $source[] = $data['designation'];

        }

        if($data['classe']==2){

          // $reponse.="<li class='licET'  style='background-color: #f0a7a1ed; '><span class='btn btn-group btnSpan' style='font-size: 18px; '>".$data['designation']."</span></li>";

          $source[] = $data['designation'];

        }
        
        if (($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1)){
          // if (($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1) && $_SESSION['enConfiguration']==0){

          if($data['classe']==5){

            // $reponse.="<li class='licET'  style='background-color: #aceef3; '><span class='btn btn-group btnSpan' style='font-size: 18px; '>".$data['designation']."</span></li>";

            $source[] = $data['designation'];

          }

        }

        if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1){

          if($data['classe']==8){

            // $reponse.="<li class='licET'  style='background-color: #f0a7a1ed; '><span class='btn btn-group btnSpan' style='font-size: 18px; '>".$data['designation']."</span></li>";

            $source[] = $data['designation'];

          }

        }

        if (($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1 || $_SESSION['caissier']==1)){
          // if (($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1) && $_SESSION['enConfiguration']==0){

          if($data['classe']==10){

            // $reponse.="<li class='licET'  style='background-color: #aceef3; '><span class='btn btn-group btnSpan' style='font-size: 18px; '>".$data['designation']."</span></li>";

            $source[] = $data['designation'];

          }

        }
      }

    }
  }

  // exit($reponse);

    echo json_encode($source);

}

else if($operation == 14){

  //$reponse+=sizeof($codeBrute);

  $reponse="<ul><li>aucune donnee trouvé</li></ul>";

  $query=htmlspecialchars(trim($_POST['query']));

  $reqS="SELECT * from `".$nomtableFacture."` where prenom LIKE '%$query%' ";

  $resS=mysql_query($reqS) or die ("insertion impossible");



    if($resS){

      $reponse="<ul class='ulc'>";

        while ($data=mysql_fetch_array($resS)) {

               $reponse.="<li class='lifET'>".$data['prenom']."<>".$data['nom']."<>".$data['adresse']."<>".$data['telephone']."</li>";

          }

      $reponse.="</ul>";

    }

  exit($reponse);

}

else if($operation == 15){

  //$reponse+=sizeof($codeBrute);

  // $designation=@htmlspecialchars(utf8_decode($_POST["designation"]));

  $designation=@$_POST["designation"];

  $idPagnet=@htmlspecialchars($_POST["idPagnet"]);

  $result="0";

  // $result=$designation." / ".$idPagnet;



  $sql0="SELECT * FROM `".$nomtablePagnetTampon."` where idPagnet=".$idPagnet." ";

  $res0 = mysql_query($sql0) or die ("persoonel requête 2".mysql_error());

  $pagnet = mysql_fetch_assoc($res0) ;



  $sql="SELECT * FROM `".$nomtableDesignation."` where (codeBarreDesignation='".$designation."' or codeBarreuniteStock='".$designation."' or designation='".$designation."') ";

  $res=mysql_query($sql) or die ("select stock impossible =>".mysql_error());



  if(mysql_num_rows($res)){

    $design = mysql_fetch_assoc($res);

    if($design['classe']==0){

        if($pagnet['type']==0 || $pagnet['type']==10 || $pagnet['type']==30){

            if (($_SESSION['type']=="Superette") && ($_SESSION['categorie']=="Detaillant")) {

              //if($restant>0){

                  /***Debut verifier si la ligne du produit existe deja ***/

                  $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";

                  $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

                  $ligne1 = mysql_fetch_assoc($res);

                  /***Debut verifier si la ligne du produit existe deja ***/

                  if($ligne1 != null){

                      /***Debut verifier si le produit existe deja ***/

                      $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."'  and  unitevente!='Article' and  unitevente!='article' and classe=0 ";

                      $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

                      $ligne2 = mysql_fetch_assoc($res);

                      /***Debut verifier si le produit existe deja ***/

                      $quantite = $ligne1['quantite'] + 1;

                      $prixTotal=$quantite*$ligne1['prixunitevente'];

                      if($_SESSION['Pays']=='Canada' && $design['tva']==1){

                        $prixTotalTvaP=$prixTotal*$_SESSION['tvaP'];

                        $prixTotalTvaR=$prixTotal*$_SESSION['tvaR'];

                        $sql7="UPDATE `".$nomtableLigneTampon."` set quantite='".$quantite."',prixtotal=".$prixTotal.",prixtotalTvaP=".$prixTotalTvaP.",prixtotalTvaR=".$prixTotalTvaR." where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";

                        $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error()); 

                      }

                      else{

                        $sql7="UPDATE `".$nomtableLigneTampon."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";

                        $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error()); 

                      }



                      $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

                      $res2=mysql_query($sql2);

                      if(mysql_num_rows($res2)){

                        $ligne=mysql_fetch_assoc($res2);

                        $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                        $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                        $TotalT = mysql_fetch_array($resT) ;



                        if($_SESSION['Pays']=='Canada'){ 

                          $sqlTP="SELECT SUM(prixtotalTvaP) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                          $resTP = mysql_query($sqlTP) or die ("persoonel requête 2".mysql_error());

                          $TotalTvaP = mysql_fetch_array($resTP) ;



                          $sqlTR="SELECT SUM(prixtotalTvaR) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                          $resTR = mysql_query($sqlTR) or die ("persoonel requête 2".mysql_error());

                          $TotalTvaR = mysql_fetch_array($resTR) ;



                          $totalPrix=$TotalT[0]+$TotalTvaP[0]+$TotalTvaR[0];

                        }

                        else{

                          $totalPrix=$TotalT[0];

                        }

                        



                        $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";

                        $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

                        $t_stock = mysql_fetch_array($res_t) ;

                        $result='2<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$quantite.'<>'.$totalPrix;

                      }               

                  }

                  else {

                      $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet=".$idPagnet." ";

                      $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

                      $ligne = mysql_fetch_assoc($res) ;

                      if(mysql_num_rows($res)){

                          if($ligne['classe']==0 || $ligne['classe']==1){

                              if($_SESSION['Pays']=='Canada' && $design['tva']==1){ 

                                $prixTotalTvaP=$design['prix']*$_SESSION['tvaP'];

                                $prixTotalTvaR=$design['prix']*$_SESSION['tvaR'];

                                $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,prixtotalTvaP,prixtotalTvaR,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$prixTotalTvaP.",".$prixTotalTvaR.",".$idPagnet.",0)";

                                $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                              }

                              else{

                                $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",0)";

                                $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() ); 

                              }

  

                              $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

                              $res2=mysql_query($sql2);

                              if(mysql_num_rows($res2)){

                                $ligne=mysql_fetch_assoc($res2);

                                $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                                $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                                $TotalT = mysql_fetch_array($resT) ;

  

                                if($_SESSION['Pays']=='Canada'){ 

                                  $sqlTP="SELECT SUM(prixtotalTvaP) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                                  $resTP = mysql_query($sqlTP) or die ("persoonel requête 2".mysql_error());

                                  $TotalTvaP = mysql_fetch_array($resTP) ;

        

                                  $sqlTR="SELECT SUM(prixtotalTvaR) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                                  $resTR = mysql_query($sqlTR) or die ("persoonel requête 2".mysql_error());

                                  $TotalTvaR = mysql_fetch_array($resTR) ;

        

                                  $totalPrix=$TotalT[0]+$TotalTvaP[0]+$TotalTvaR[0];

                                }

                                else{

                                  $totalPrix=$TotalT[0];

                                }

  

                                $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";

                                $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

                                $t_stock = mysql_fetch_array($res_t) ;

                                if (($_SESSION['type']=="Divers") && ($_SESSION['categorie']=="Detaillant")) {

                                  $result='10<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$totalPrix;;

                                }

                                else{

                                  $result='1<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$totalPrix;;

                                }

                                

                              }

                          }

                      }

                      else{

                        if($_SESSION['Pays']=='Canada' && $design['tva']==1){ 

                          $prixTotalTvaP=$design['prix']*$_SESSION['tvaP'];

                          $prixTotalTvaR=$design['prix']*$_SESSION['tvaR'];

                          $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,prixtotalTvaP,prixtotalTvaR,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$prixTotalTvaP.",".$prixTotalTvaR.",".$idPagnet.",0)";

                          $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                        }

                        else{

                          $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",0)";

                          $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                        }

  

                          $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

                              $res2=mysql_query($sql2);

                              if(mysql_num_rows($res2)){

                                $ligne=mysql_fetch_assoc($res2);

                                $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                                $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                                $TotalT = mysql_fetch_array($resT) ;

  

                                if($_SESSION['Pays']=='Canada'){ 

                                  $sqlTP="SELECT SUM(prixtotalTvaP) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                                  $resTP = mysql_query($sqlTP) or die ("persoonel requête 2".mysql_error());

                                  $TotalTvaP = mysql_fetch_array($resTP) ;

        

                                  $sqlTR="SELECT SUM(prixtotalTvaR) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                                  $resTR = mysql_query($sqlTR) or die ("persoonel requête 2".mysql_error());

                                  $TotalTvaR = mysql_fetch_array($resTR) ;

        

                                  $totalPrix=$TotalT[0]+$TotalTvaP[0]+$TotalTvaR[0];

                                }

                                else{

                                  $totalPrix=$TotalT[0];

                                }

  

                                $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";

                                $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

                                $t_stock = mysql_fetch_array($res_t) ;

                                if (($_SESSION['type']=="Divers") && ($_SESSION['categorie']=="Detaillant")) {

                                  $result='10<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$totalPrix;

                                }

                                else{

                                  $result='1<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$totalPrix;

                                }

                                

                              }

                      }

                  

                  }

              //}

              //else{

              //  $result='3<>0';

              //}

            }

            else{

              $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$design['idDesignation']."' AND quantiteStockCourant!=0 ";

              $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

              $t_stock = mysql_fetch_array($res_t) ;

              $restant = $t_stock[0];

              //if($restant>0){

                  /***Debut verifier si la ligne du produit existe deja ***/

                  $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";

                  $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

                  $ligne1 = mysql_fetch_assoc($res);

                  /***Debut verifier si la ligne du produit existe deja ***/

                  if($ligne1 != null){

                      /***Debut verifier si le produit existe deja ***/

                      $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."'  and  unitevente!='Article' and  unitevente!='article' and classe=0 ";

                      $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

                      $ligne2 = mysql_fetch_assoc($res);

                      /***Debut verifier si le produit existe deja ***/

                      $existant=0;

                      if($ligne2 != null){

                          $existant=$ligne2['quantite']*$design['nbreArticleUniteStock'];

                      }

                      $quantite = $ligne1['quantite'] + 1;

                      $reste = $t_stock[0] - $quantite - $existant;

                      if ($reste>=0){

                          $prixTotal=$quantite*$ligne1['prixunitevente'];

                          if($_SESSION['Pays']=='Canada' && $design['tva']==1){

                            $prixTotalTvaP=$prixTotal*$_SESSION['tvaP'];

                            $prixTotalTvaR=$prixTotal*$_SESSION['tvaR'];

                            $sql7="UPDATE `".$nomtableLigneTampon."` set quantite='".$quantite."',prixtotal=".$prixTotal.",prixtotalTvaP=".$prixTotalTvaP.",prixtotalTvaR=".$prixTotalTvaR." where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";

                            $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error()); 

                          }

                          else{

                            $sql7="UPDATE `".$nomtableLigneTampon."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";

                            $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error()); 

                          }

  

                          $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

                          $res2=mysql_query($sql2);

                          if(mysql_num_rows($res2)){

                            $ligne=mysql_fetch_assoc($res2);

                            $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                            $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                            $TotalT = mysql_fetch_array($resT) ;

  

                            if($_SESSION['Pays']=='Canada'){ 

                              $sqlTP="SELECT SUM(prixtotalTvaP) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                              $resTP = mysql_query($sqlTP) or die ("persoonel requête 2".mysql_error());

                              $TotalTvaP = mysql_fetch_array($resTP) ;

    

                              $sqlTR="SELECT SUM(prixtotalTvaR) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                              $resTR = mysql_query($sqlTR) or die ("persoonel requête 2".mysql_error());

                              $TotalTvaR = mysql_fetch_array($resTR) ;

    

                              $totalPrix=$TotalT[0]+$TotalTvaP[0]+$TotalTvaR[0];

                            }

                            else{

                              $totalPrix=$TotalT[0];

                            }

                            

  

                            $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";

                            $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

                            $t_stock = mysql_fetch_array($res_t) ;

                            $result='2<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$quantite.'<>'.$totalPrix;

                          }

                      }

                      else{

                        $result='3<>0';

                      }                     

                  }

                  else {

                      $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet=".$idPagnet." ";

                      $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

                      $ligne = mysql_fetch_assoc($res) ;

                      if(mysql_num_rows($res)){

                          if($ligne['classe']==0 || $ligne['classe']==1){

                              if($_SESSION['Pays']=='Canada' && $design['tva']==1){ 

                                $prixTotalTvaP=$design['prix']*$_SESSION['tvaP'];

                                $prixTotalTvaR=$design['prix']*$_SESSION['tvaR'];

                                $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,prixtotalTvaP,prixtotalTvaR,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$prixTotalTvaP.",".$prixTotalTvaR.",".$idPagnet.",0)";

                                $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                              }

                              else{

                                $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",0)";

                                $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() ); 

                              }

  

                              $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

                              $res2=mysql_query($sql2);

                              if(mysql_num_rows($res2)){

                                $ligne=mysql_fetch_assoc($res2);

                                $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                                $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                                $TotalT = mysql_fetch_array($resT) ;

  

                                if($_SESSION['Pays']=='Canada'){ 

                                  $sqlTP="SELECT SUM(prixtotalTvaP) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                                  $resTP = mysql_query($sqlTP) or die ("persoonel requête 2".mysql_error());

                                  $TotalTvaP = mysql_fetch_array($resTP) ;

        

                                  $sqlTR="SELECT SUM(prixtotalTvaR) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                                  $resTR = mysql_query($sqlTR) or die ("persoonel requête 2".mysql_error());

                                  $TotalTvaR = mysql_fetch_array($resTR) ;

        

                                  $totalPrix=$TotalT[0]+$TotalTvaP[0]+$TotalTvaR[0];

                                }

                                else{

                                  $totalPrix=$TotalT[0];

                                }

  

                                $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";

                                $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

                                $t_stock = mysql_fetch_array($res_t) ;

                                if (($_SESSION['type']=="Divers") && ($_SESSION['categorie']=="Detaillant")) {

                                  $result='10<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$totalPrix;;

                                }

                                else{

                                  $result='1<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$totalPrix;;

                                }

                                

                              }

                          }

                      }

                      else{

                        if($_SESSION['Pays']=='Canada' && $design['tva']==1){ 

                          $prixTotalTvaP=$design['prix']*$_SESSION['tvaP'];

                          $prixTotalTvaR=$design['prix']*$_SESSION['tvaR'];

                          $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,prixtotalTvaP,prixtotalTvaR,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$prixTotalTvaP.",".$prixTotalTvaR.",".$idPagnet.",0)";

                          $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                        }

                        else{

                          $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",0)";

                          $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                        }

  

                          $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

                              $res2=mysql_query($sql2);

                              if(mysql_num_rows($res2)){

                                $ligne=mysql_fetch_assoc($res2);

                                $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                                $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                                $TotalT = mysql_fetch_array($resT) ;

  

                                if($_SESSION['Pays']=='Canada'){ 

                                  $sqlTP="SELECT SUM(prixtotalTvaP) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                                  $resTP = mysql_query($sqlTP) or die ("persoonel requête 2".mysql_error());

                                  $TotalTvaP = mysql_fetch_array($resTP) ;

        

                                  $sqlTR="SELECT SUM(prixtotalTvaR) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                                  $resTR = mysql_query($sqlTR) or die ("persoonel requête 2".mysql_error());

                                  $TotalTvaR = mysql_fetch_array($resTR) ;

        

                                  $totalPrix=$TotalT[0]+$TotalTvaP[0]+$TotalTvaR[0];

                                }

                                else{

                                  $totalPrix=$TotalT[0];

                                }

  

                                $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";

                                $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

                                $t_stock = mysql_fetch_array($res_t) ;

                                if (($_SESSION['type']=="Divers") && ($_SESSION['categorie']=="Detaillant")) {

                                  $result='10<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$totalPrix;

                                }

                                else{

                                  $result='1<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$totalPrix;

                                }

                                

                              }

                      }

                  

                  }

              //}

              //else{

              //  $result='3<>0';

              //}

            }

        }

        else if($pagnet['type']==20){

          if($_SESSION['Pays']=='Canada' && $design['tva']==1){ 

            $prixTotalTvaP=$design['prix']*$_SESSION['tvaP'];

            $prixTotalTvaR=$design['prix']*$_SESSION['tvaR'];

            $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,prixtotalTvaP,prixtotalTvaR,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$prixTotalTvaP.",".$prixTotalTvaR.",".$idPagnet.",0)";

            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

          }

          else{

          $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",0)";

          $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

          }

          if($res7){

            $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

            $res2=mysql_query($sql2);

            $ligne=mysql_fetch_assoc($res2);

            if($design['codeBarreDesignation']!='' && $design['codeBarreDesignation']!=null){

              $result='8<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$design['codeBarreDesignation'].'<>'.$design['prix'].'<>'.$ligne['numligne'];

            }

            else{

              $result='8<>'.$design['idDesignation'].'<>'.$design['designation'].'<>Neant<>'.$design['prix'].'<>'.$ligne['numligne'];

            }

          }

        }

        else{

            /***Debut verifier si la ligne du produit existe deja ***/

            $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";

            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

            $ligne1 = mysql_fetch_assoc($res);

            /***Debut verifier si la ligne du produit existe deja ***/

            if($ligne1 != null){

                $quantite = $ligne1['quantite'] + 1;

                $prixTotal=$quantite*$ligne1['prixunitevente'];

                $sql7="UPDATE `".$nomtableLigneTampon."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";

                $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());

            }

            else {

                $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet=".$idPagnet." ";

                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

                $ligne = mysql_fetch_assoc($res) ;

                if(mysql_num_rows($res)){

                    if($ligne['classe']==0){

                        $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",0)";

                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() ); 

                    }

                }

                else{

                    $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",0)";

                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                }



            }

        }

    }

    if($design['classe']==1 && ($pagnet['type']==0 || $pagnet['type']==10 || $pagnet['type']==30)){

        $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet=".$idPagnet." ";

        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

        $ligne = mysql_fetch_assoc($res) ;

        if(mysql_num_rows($res)){

            /***Debut verifier si la ligne du produit existe deja ***/

            $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=1  ";

            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

            $ligne1 = mysql_fetch_assoc($res);

            /***Debut verifier si la ligne du produit existe deja ***/

            if($ligne1 != null){

                $quantite = $ligne1['quantite'] + 1;

                $prixTotal=$quantite*$ligne1['prixunitevente'];

                $sql7="UPDATE `".$nomtableLigneTampon."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=1  ";

                $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());



                $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

                $res2=mysql_query($sql2);

                if(mysql_num_rows($res2)){

                  $ligne=mysql_fetch_assoc($res2);

                  $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                  $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                  $TotalT = mysql_fetch_array($resT) ;



                  $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";

                  $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

                  $t_stock = mysql_fetch_array($res_t) ;

                  $result='2<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$quantite.'<>'.$TotalT[0];

                }

            }

            else {

                if($ligne['classe']==1){

                    if($design['uniteStock']!='Transaction'){

                        $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."','".$design['idDesignation']."','".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",1)";

                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                        $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

                        $res2=mysql_query($sql2);

                        if(mysql_num_rows($res2)){

                          $ligne=mysql_fetch_assoc($res2);

                          $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                          $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                          $TotalT = mysql_fetch_array($resT) ;



                          $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";

                          $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

                          $t_stock = mysql_fetch_array($res_t) ;

                          $result='4<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0];

                        }

                    }

                    

                }

                else if($ligne['classe']==0){

                  if($design['uniteStock']!='Transaction'){

                      $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."','".$design['idDesignation']."','".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",1)";

                      $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                      $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

                      $res2=mysql_query($sql2);

                      if(mysql_num_rows($res2)){

                        $ligne=mysql_fetch_assoc($res2);

                        $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                        $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                        $TotalT = mysql_fetch_array($resT) ;



                        $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";

                        $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

                        $t_stock = mysql_fetch_array($res_t) ;

                        $result='1<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0];

                      }

                  }

                  

              }

                

            }

            

        }

        else{

            if($design['uniteStock']=='Transaction'){

                $reqT="SELECT * from `aaa-transaction` where idTransaction='".$design['description']."'";

                $resT=mysql_query($reqT) or die ("select stock impossible =>".mysql_error());

                $transaction = mysql_fetch_assoc($resT);

                $image=$transaction['aliasTransaction'];

                $trans_alias=$transaction['aliasTransaction'];

                $trans_pagnet=$idPagnet;

                $msg_transaction="OK";

            }

            else if($design['uniteStock']=='Credit'){

                $reqT="SELECT * from `aaa-transaction` where idTransaction='".$design['description']."'";

                $resT=mysql_query($reqT) or die ("select stock impossible =>".mysql_error());

                $transaction = mysql_fetch_assoc($resT);

                $image=$transaction['aliasTransaction'];

                $trans_alias=$transaction['aliasTransaction'];

                $trans_pagnet=$idPagnet;

                $msg_credit="OK";

            }

            else{

                

                $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",1)";

                $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

                $res2=mysql_query($sql2);

                if(mysql_num_rows($res2)){

                  $ligne=mysql_fetch_assoc($res2);

                  $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                  $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                  $TotalT = mysql_fetch_array($resT) ;



                  $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";

                  $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

                  $t_stock = mysql_fetch_array($res_t) ;

                  $result='4<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$TotalT[0];

                }

            }

        }

    }

    if($design['classe']==2 && $pagnet['type']==0 ){

        $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet=".$idPagnet." ";

        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

        $ligne = mysql_fetch_assoc($res) ;

        if(mysql_num_rows($res)){

            /***Debut verifier si la ligne du produit existe deja ***/

            $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."' and idStock='".$design['idDesignation']."' and classe=2  ";

            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

            $ligne1 = mysql_fetch_assoc($res);

            /***Debut verifier si la ligne du produit existe deja ***/

            if($ligne1 != null){

               

            }

            else {

                if($ligne['classe']==2){

                    $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",2)";

                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                    $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

                    $res2=mysql_query($sql2);

                    if(mysql_num_rows($res2)){

                      $ligne=mysql_fetch_assoc($res2);

                      $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                      $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                      $TotalT = mysql_fetch_array($resT) ;



                      $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";

                      $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

                      $t_stock = mysql_fetch_array($res_t) ;

                      $result='7<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$TotalT[0];

                    }

                }

            }

        }

        else{

            $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",2)";

            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



            $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

            $res2=mysql_query($sql2);

            if(mysql_num_rows($res2)){

              $ligne=mysql_fetch_assoc($res2);

              $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

              $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

              $TotalT = mysql_fetch_array($resT) ;



              $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";

              $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

              $t_stock = mysql_fetch_array($res_t) ;

              $result='7<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$TotalT[0];

            }

        }

    }

    if($design['classe']==5 && $pagnet['type']==0){

        $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet=".$idPagnet." ";

        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

        $ligne = mysql_fetch_assoc($res) ;

        if(mysql_num_rows($res)){

            /***Debut verifier si la ligne du produit existe deja ***/

            $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=5  ";

            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

            $ligne1 = mysql_fetch_assoc($res);

            /***Debut verifier si la ligne du produit existe deja ***/

            if($ligne1 != null){

            }

            else {

                if($ligne['classe']==5){

                    $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",5)";

                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                    $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

                    $res2=mysql_query($sql2);

                    if(mysql_num_rows($res2)){

                      $ligne=mysql_fetch_assoc($res2);

                      $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                      $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                      $TotalT = mysql_fetch_array($resT) ;

        

                      $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";

                      $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

                      $t_stock = mysql_fetch_array($res_t) ;

                      $result='5<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$TotalT[0];

                    }

                }

            }

        }

        else{

            $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",5)";

            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



            $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

            $res2=mysql_query($sql2);

            if(mysql_num_rows($res2)){

              $ligne=mysql_fetch_assoc($res2);

              $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

              $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

              $TotalT = mysql_fetch_array($resT) ;



              $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";

              $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

              $t_stock = mysql_fetch_array($res_t) ;

              $result='5<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0];

            }

        }

    }

    if($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1){

      if($design['classe']==6 && $pagnet['type']==0){

        $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet=".$idPagnet." ";

        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

        $ligne = mysql_fetch_assoc($res) ;

        if(mysql_num_rows($res)){

            /***Debut verifier si la ligne du produit existe deja ***/

            $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=7  ";

            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

            $ligne1 = mysql_fetch_assoc($res);

            /***Debut verifier si la ligne du produit existe deja ***/

            if($ligne1 != null){

            }

            else {

                if($ligne['classe']==6){

                    $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",6)";

                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                    $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

                    $res2=mysql_query($sql2);

                    if(mysql_num_rows($res2)){

                      $ligne=mysql_fetch_assoc($res2);

                      $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                      $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                      $TotalT = mysql_fetch_array($resT) ;

        

                      $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";

                      $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

                      $t_stock = mysql_fetch_array($res_t) ;

                      $result='7<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$TotalT[0];

                    }

                }

            }

        }

        else{

            $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",6)";

            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



            $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

            $res2=mysql_query($sql2);

            if(mysql_num_rows($res2)){

              $ligne=mysql_fetch_assoc($res2);

              $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

              $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

              $TotalT = mysql_fetch_array($resT) ;



              $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";

              $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

              $t_stock = mysql_fetch_array($res_t) ;

              $result='7<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$TotalT[0];

            }

        }

      }

      else if($design['classe']==9 && $pagnet['type']==0){

        $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet=".$idPagnet." ";

        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

        $ligne = mysql_fetch_assoc($res) ;

        if(mysql_num_rows($res)){

            /***Debut verifier si la ligne du produit existe deja ***/

            $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=7  ";

            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

            $ligne1 = mysql_fetch_assoc($res);

            /***Debut verifier si la ligne du produit existe deja ***/

            if($ligne1 != null){

            }

            else {

                if($ligne['classe']==9){

                    $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",9)";

                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                    $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

                    $res2=mysql_query($sql2);

                    if(mysql_num_rows($res2)){

                      $ligne=mysql_fetch_assoc($res2);

                      $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                      $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                      $TotalT = mysql_fetch_array($resT) ;

        

                      $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";

                      $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

                      $t_stock = mysql_fetch_array($res_t) ;$result='6<>0';

                    }

                }

            }

        }

        else{

            $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",9)";

            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



            $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

            $res2=mysql_query($sql2);

            if(mysql_num_rows($res2)){

              $ligne=mysql_fetch_assoc($res2);

              $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

              $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

              $TotalT = mysql_fetch_array($resT) ;



              $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";

              $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

              $t_stock = mysql_fetch_array($res_t) ;

              $result='6<>0';

            }

        }

      }

    }

    if($design['classe']==7 && $pagnet['type']==0){

        $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet=".$idPagnet." ";

        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

        $ligne = mysql_fetch_assoc($res) ;

        if(mysql_num_rows($res)){

            /***Debut verifier si la ligne du produit existe deja ***/

            $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=7  ";

            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

            $ligne1 = mysql_fetch_assoc($res);

            /***Debut verifier si la ligne du produit existe deja ***/

            if($ligne1 != null){

            }

            else {

                if($ligne['classe']==7){

                    $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",7)";

                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                    $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

                    $res2=mysql_query($sql2);

                    if(mysql_num_rows($res2)){

                      $ligne=mysql_fetch_assoc($res2);

                      $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

                      $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                      $TotalT = mysql_fetch_array($resT) ;

        

                      $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";

                      $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

                      $t_stock = mysql_fetch_array($res_t) ;

                      $result='5<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$TotalT[0];

                    }

                }

            }

        }

        else{

            $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",7)";

            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



            $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";

            $res2=mysql_query($sql2);

            if(mysql_num_rows($res2)){

              $ligne=mysql_fetch_assoc($res2);

              $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

              $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

              $TotalT = mysql_fetch_array($resT) ;



              $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";

              $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

              $t_stock = mysql_fetch_array($res_t) ;

              $result='5<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0];

            }

        }

    }

    if($design['classe']==8){

        $sql3="UPDATE `".$nomtablePagnetTampon."` set type='1' where idPagnet=".$idPagnet;

        $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());

        $result='6<>0';

    }

    if($design['classe']==10){

        $sql3="UPDATE `".$nomtablePagnetTampon."` set type='10' where idPagnet=".$idPagnet;

        $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());

        $result='6<>0';

    }

    if($design['classe']==20){

      $sql3="UPDATE `".$nomtablePagnetTampon."` set type='20' where idPagnet=".$idPagnet;

      $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());

      $result='6<>0';

    }

  }

  exit($result);

}

else if($operation == 16){

  $idPagnet=@htmlspecialchars($_POST["idPagnet"]);

  $numligne=@htmlspecialchars($_POST["numligne"]);

  $result="0";
  
  $sql0="SELECT * FROM `".$nomtablePagnetTampon."` where idPagnet=".$idPagnet." ";
  
  $res0 = mysql_query($sql0) or die ("persoonel requête 2".mysql_error());
  
  $pagnet = mysql_fetch_assoc($res0) ;

  $sql1="SELECT * from  `".$nomtableLigneTampon."` WHERE numligne='".$numligne."' ";

  $res1=mysql_query($sql1);

  if(mysql_num_rows($res1)){

      $ligne=mysql_fetch_assoc($res1);

      $sql7="DELETE FROM `".$nomtableLigneTampon."` where numligne='".$numligne."' ";

      $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



      $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";

      $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

      $TotalT = mysql_fetch_array($resT) ;

      $result='1<>'.$ligne['idDesignation'].'<>'.$ligne['designation'].'<>'.$ligne['unitevente'].'<>'.$ligne['prixunitevente'].'<>'.$numligne.'<>'.$TotalT[0].'<>'.$pagnet['taux'];

  }

  exit($result);

}

else if($operation == 17){

  //$reponse+=sizeof($codeBrute);
  
  $designation=@$_POST["designation"];
  
  $idPagnet=@htmlspecialchars($_POST["idPagnet"]);
  
  $result="0";
  
  $sql0="SELECT * FROM `".$nomtablePagnetTampon."` where idPagnet=".$idPagnet." ";
  
  $res0 = mysql_query($sql0) or die ("persoonel requête 2".mysql_error());
  
  $pagnet = mysql_fetch_assoc($res0) ;
  
  
  $sql="SELECT * FROM `".$nomtableDesignation."` where (codeBarreDesignation='".$designation."' or codeBarreuniteStock='".$designation."' or designation='".$designation."') ";
  
  $res=mysql_query($sql) or die ("select stock impossible =>".mysql_error());
  
  if (mysql_num_rows($res)) {
  
    $design = mysql_fetch_assoc($res); 
    // var_dump($design['classe']);
    // var_dump($design['classe']==7 && $pagnet['type']==0);
  
    if($design['classe']==0) {
  
        if($pagnet['type']==0 || $pagnet['type']==30 || $pagnet['type']==10){
  
            $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableEntrepotStock."` where idDesignation='".$design['idDesignation']."' ";
  
            $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
  
            $t_stock = mysql_fetch_array($res_t) ;
  
            $restant = $t_stock[0];
  
            if ($restant > 0) {
  
                /***Debut verifier si la ligne du produit existe deja ***/
  
                $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=0 ";
  
                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
  
                $ligne = mysql_fetch_assoc($res);
  
                /***Debut verifier si la ligne du produit existe deja ***/
  
                if ($ligne != null) {
  
                    $reqP="SELECT * from  `".$nomtableEntrepotStock."` 
  
                    where idDesignation='".$design['idDesignation']."' AND quantiteStockCourant>0 AND idEntrepot!='".$ligne['idEntrepot']."'";
  
                    $resP=mysql_query($reqP) or die ("select stock impossible =>".mysql_error());
  
                    if(mysql_num_rows($resP)){
  
                        $entrepot = mysql_fetch_assoc($resP);  
  
                        /***Debut verifier si la ligne du produit existe deja ***/
  
                        $sql1="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' AND idEntrepot='".$entrepot['idEntrepot']."'  and classe=0 ";
  
                        $res1 = mysql_query($sql1) or die ("persoonel requête 2".mysql_error());
  
                        $ligne1 = mysql_fetch_assoc($res1);
  
                        /***Debut verifier si la ligne du produit existe deja ***/
  
                        if($ligne1 != null){
  
                            
  
                        }
  
                        else{
  
                            $reqP1="SELECT * from  `".$nomtableEntrepotStock."` 
  
                            where idDesignation='".$design['idDesignation']."' AND quantiteStockCourant>0 AND idEntrepot!='".$ligne['idEntrepot']."' AND idEntrepot!='".$ligne1['idEntrepot']."'  ";
  
                            $resP1=mysql_query($reqP1) or die ("select stock impossible =>".mysql_error());
  
                            $produit = mysql_fetch_assoc($resP1);
  
                                if(mysql_num_rows($resP1)){
  
                                    if($ligne['classe']==0){
  
                                        $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','".$produit['idEntrepot']."','".$design['uniteStock']."',".$design['prixuniteStock'].",1,".$design['prixuniteStock'].",".$idPagnet.",0)";
  
                                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() ); 
  
                                        $result='2<>0';
  
                                    }
  
                                }
  
                        }
  
                    }
  
                    
  
                }
  
                else {
  
                    $sqlN="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet=".$idPagnet." ";
  
                    $resN = mysql_query($sqlN) or die ("persoonel requête 2".mysql_error());
  
                    $ligneN = mysql_fetch_assoc($resN) ;
  
                    if($_SESSION['entrepot']!=0 || $_SESSION['entrepot']!=null) {
  
                        $reqP="SELECT * from  `".$nomtableEntrepotStock."` 
  
                        where idDesignation='".$design['idDesignation']."' AND quantiteStockCourant>0 AND idEntrepot='".$_SESSION['entrepot']."'";
  
                        $resP=mysql_query($reqP) or die ("select stock impossible =>".mysql_error());
  
                        if(mysql_num_rows($resP)){
  
                            $entrepot = mysql_fetch_assoc($resP);
  
                            if($ligneN['classe']==0 || $ligneN['classe']==1){
  
                                $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','".$entrepot['idEntrepot']."','".$design['uniteStock']."',".$design['prixuniteStock'].",1,".$design['prixuniteStock'].",".$idPagnet.",0)";
  
                                $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() ); 
  
                                $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
  
                                $res2=mysql_query($sql2);
  
                                if(mysql_num_rows($res2)) {
  
                                  $ligne=mysql_fetch_assoc($res2);
  
                                  $sqlE="SELECT * from `".$nomtableEntrepot."`  WHERE idEntrepot='".$ligne['idEntrepot']."' ";
  
                                  $resE=mysql_query($sqlE);
  
                                  $ligne_Dep = mysql_fetch_assoc($resE);
  
                                  /***Debut verifier si la ligne du produit existe deja ***/
  
                                  $sqlEl="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."' and idDesignation='".$ligne['idDesignation']."' and idEntrepot!='".$ligne['idEntrepot']."'";
  
                                  $resEl = mysql_query($sqlEl) or die ("persoonel requête 2".mysql_error());
  
                                  /***Debut verifier si la ligne du produit existe deja ***/
  
                                  if (!mysql_num_rows($resEl)) {
  
                                      $reqDp="SELECT * from  `".$nomtableEntrepot."` e
  
                                      INNER JOIN `".$nomtableEntrepotStock."` s ON s.idEntrepot = e.idEntrepot
  
                                      where s.designation='".$ligne['designation']."' AND s.quantiteStockCourant>0 AND e.idEntrepot<>'".$ligne["idEntrepot"]."' ORDER BY quantiteStockCourant DESC ";
  
                                      $resDp=mysql_query($reqDp) or die ("select stock impossible =>".mysql_error());
  
                                      $data=array();
  
                                      $data_distinct=array();
  
                                      while ($depot_Et = mysql_fetch_assoc($resDp)) {
  
                                        if (in_array($depot_Et['idEntrepot'], $data_distinct)) {
  
                                          // echo "Existe.";
  
                                        }
  
                                        else {
  
                                          $rows = array();	
  
                                          $rows[] = $depot_Et['idEntrepot'];
  
                                          $rows[] = $depot_Et['nomEntrepot'];
  
                                          $data[] = $rows;
  
                                          $data_distinct[] = $depot_Et['idEntrepot'];
  
                                        }
  
                                      }
  
                                  }
  
                                  $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";
  
                                  $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
  
                                  $TotalT = mysql_fetch_array($resT) ;
  
                                  $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";
  
                                  $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
  
                                  $t_stock = mysql_fetch_array($res_t) ;

                                      
                                  if ($pagnet['type']==10 || $_SESSION['entrepot']==0) {
                                    # code...
                                    $result='1<>'.$ligne['idDesignation'].'<>'.$ligne['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prixuniteStock'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$ligne_Dep['idEntrepot'].'<>'.$ligne_Dep['nomEntrepot'].'<>'.json_encode($data).'<>'.$pagnet['taux'];

                                  } else {
                                    # code...
                                    $result='1<>'.$ligne['idDesignation'].'<>'.$ligne['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prixuniteStock'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$ligne_Dep['idEntrepot'].'<>'.$ligne_Dep['nomEntrepot'].'<>[]<>'.$pagnet['taux'];

                                  }
  
                                  // $result='1<>'.$ligne['idDesignation'].'<>'.$ligne['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prixuniteStock'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$ligne_Dep['idEntrepot'].'<>'.$ligne_Dep['nomEntrepot'].'<>'.json_encode($data).'<>'.$pagnet['taux'];
  
                                }
  
                            }
  
                        }
  
                        else {
  
                            $reqP1="SELECT * from  `".$nomtableEntrepotStock."` 
  
                            where idDesignation='".$design['idDesignation']."' AND quantiteStockCourant>0 ORDER BY quantiteStockCourant DESC LIMIT 0,1 ";
  
                            $resP1=mysql_query($reqP1) or die ("select stock impossible =>".mysql_error());
  
                            $depot = mysql_fetch_assoc($resP1);
  
                            $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','".$depot['idEntrepot']."','".$design['uniteStock']."',".$design['prixuniteStock'].",1,".$design['prixuniteStock'].",".$idPagnet.",0)";
  
                            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
  
  
                            $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
  
                            $res2=mysql_query($sql2);
  
                            if(mysql_num_rows($res2)){
  
                              $ligne=mysql_fetch_assoc($res2);
    
                              $sqlE="SELECT * from `".$nomtableEntrepot."`  WHERE idEntrepot='".$ligne['idEntrepot']."' ";
  
                              $resE=mysql_query($sqlE);
  
                              $ligne_Dep = mysql_fetch_assoc($resE);
  
  
  
                              /***Debut verifier si la ligne du produit existe deja ***/
  
                              $sqlEl="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."' and idDesignation='".$ligne['idDesignation']."' and idEntrepot!='".$ligne['idEntrepot']."'";
  
                              $resEl = mysql_query($sqlEl) or die ("persoonel requête 2".mysql_error());
  
                              /***Debut verifier si la ligne du produit existe deja ***/
  
                              if(!mysql_num_rows($resEl)){
  
                                  $reqDp="SELECT * from  `".$nomtableEntrepot."` e
  
                                  INNER JOIN `".$nomtableEntrepotStock."` s ON s.idEntrepot = e.idEntrepot
  
                                  where s.designation='".$ligne['designation']."' AND s.quantiteStockCourant>0 AND e.idEntrepot<>'".$ligne["idEntrepot"]."' ORDER BY quantiteStockCourant DESC ";
  
                                  $resDp=mysql_query($reqDp) or die ("select stock impossible =>".mysql_error());
  
                                  $data=array();
  
                                  $data_distinct=array();
  
                                  while ($depot_Et = mysql_fetch_assoc($resDp)) {
  
                                    if(in_array($depot_Et['idEntrepot'], $data_distinct)){
  
                                      // echo "Existe.";
  
                                    }
  
                                    else{
  
                                      $rows = array();	
  
                                      $rows[] = $depot_Et['idEntrepot'];
  
                                      $rows[] = $depot_Et['nomEntrepot'];
  
                                      $data[] = $rows;
  
                                      $data_distinct[] = $depot_Et['idEntrepot'];
  
                                    }
  
                                  }
  
                              }
  
                              $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";
  
                              $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
  
                              $TotalT = mysql_fetch_array($resT) ;
  
  
  
                              $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";
  
                              $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
  
                              $t_stock = mysql_fetch_array($res_t) ;
  
                              
                              if ($pagnet['type']==10 || $_SESSION['entrepot']==0) {
                                # code...
                                $result='1<>'.$ligne['idDesignation'].'<>'.$ligne['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prixuniteStock'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$ligne_Dep['idEntrepot'].'<>'.$ligne_Dep['nomEntrepot'].'<>'.json_encode($data).'<>'.$pagnet['taux'];

                              } else {
                                # code...
                                $result='1<>'.$ligne['idDesignation'].'<>'.$ligne['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prixuniteStock'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$ligne_Dep['idEntrepot'].'<>'.$ligne_Dep['nomEntrepot'].'<>[]<>'.$pagnet['taux'];

                              }
  
                              // $result='1<>'.$ligne['idDesignation'].'<>'.$ligne['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prixuniteStock'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$ligne_Dep['idEntrepot'].'<>'.$ligne_Dep['nomEntrepot'].'<>'.json_encode($data).'<>'.$pagnet['taux'];
  
                            }
  
                        }
  
                    }
  
                    else{
  
                        $reqP="SELECT * from  `".$nomtableEntrepotStock."` 
  
                        where idDesignation='".$design['idDesignation']."' AND quantiteStockCourant>0 ORDER BY quantiteStockCourant DESC LIMIT 0,1 ";
  
                        $resP=mysql_query($reqP) or die ("select stock impossible =>".mysql_error());
  
                        if(mysql_num_rows($resP)){
  
                            $entrepot = mysql_fetch_assoc($resP);
  
                            if($ligneN['classe']==0){
  
                                $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','".$entrepot['idEntrepot']."','".$design['uniteStock']."',".$design['prixuniteStock'].",1,".$design['prixuniteStock'].",".$idPagnet.",0)";
  
                                $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() ); 
  
  
  
                                $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
  
                                $res2=mysql_query($sql2);
  
                                if(mysql_num_rows($res2)){
  
                                  $ligne=mysql_fetch_assoc($res2);
  
      
  
                                  $sqlE="SELECT * from `".$nomtableEntrepot."`  WHERE idEntrepot='".$ligne['idEntrepot']."' ";
  
                                  $resE=mysql_query($sqlE);
  
                                  $ligne_Dep = mysql_fetch_assoc($resE);
  
      
  
                                  /***Debut verifier si la ligne du produit existe deja ***/
  
                                  $sqlEl="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."' and idDesignation='".$ligne['idDesignation']."' and idEntrepot!='".$ligne['idEntrepot']."'";
  
                                  $resEl = mysql_query($sqlEl) or die ("persoonel requête 2".mysql_error());
  
                                  /***Debut verifier si la ligne du produit existe deja ***/
  
                                  if(!mysql_num_rows($resEl)){
  
                                      $reqDp="SELECT * from  `".$nomtableEntrepot."` e
  
                                      INNER JOIN `".$nomtableEntrepotStock."` s ON s.idEntrepot = e.idEntrepot
  
                                      where s.designation='".$ligne['designation']."' AND s.quantiteStockCourant>0 AND e.idEntrepot<>'".$ligne["idEntrepot"]."' ORDER BY quantiteStockCourant DESC ";
  
                                      $resDp=mysql_query($reqDp) or die ("select stock impossible =>".mysql_error());
  
                                      $data=array();
  
                                      $data_distinct=array();
  
                                      while ($depot_Et = mysql_fetch_assoc($resDp)) {
  
                                        if(in_array($depot_Et['idEntrepot'], $data_distinct)){
  
                                          // echo "Existe.";
  
                                        }
  
                                        else{
  
                                          $rows = array();	
  
                                          $rows[] = $depot_Et['idEntrepot'];
  
                                          $rows[] = $depot_Et['nomEntrepot'];
  
                                          $data[] = $rows;
  
                                          $data_distinct[] = $depot_Et['idEntrepot'];
  
                                        }
  
                                      }
  
                                  }
  
      
  
                                  $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";
  
                                  $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
  
                                  $TotalT = mysql_fetch_array($resT) ;
  
      
  
                                  $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";
  
                                  $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
  
                                  $t_stock = mysql_fetch_array($res_t) ;
                                    
                                  // if ($pagnet['type']==10) {
                                  //   # code...
                                  //   $result='1<>'.$ligne['idDesignation'].'<>'.$ligne['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prixuniteStock'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$ligne_Dep['idEntrepot'].'<>'.$ligne_Dep['nomEntrepot'].'<>'.json_encode($data).'<>'.$pagnet['taux'];

                                  // } else {
                                    # code...
                                    $result='1<>'.$ligne['idDesignation'].'<>'.$ligne['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prixuniteStock'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$ligne_Dep['idEntrepot'].'<>'.$ligne_Dep['nomEntrepot'].'<><>'.$pagnet['taux'];

                                  // }
                                  // $result='1<>'.$ligne['idDesignation'].'<>'.$ligne['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prixuniteStock'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$ligne_Dep['idEntrepot'].'<>'.$ligne_Dep['nomEntrepot'].'<>'.json_encode($data).'<>'.$pagnet['taux'];
  
                                }
  
                            }
  
                        }
  
                        else{
  
                            $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','".$entrepot['idEntrepot']."','".$design['uniteStock']."',".$design['prixuniteStock'].",1,".$design['prixuniteStock'].",".$idPagnet.",0)";
  
                            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
  
  
  
                            $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
  
                            $res2=mysql_query($sql2);
  
                            if(mysql_num_rows($res2)){
  
                              $ligne=mysql_fetch_assoc($res2);
  
  
  
                              $sqlE="SELECT * from `".$nomtableEntrepot."`  WHERE idEntrepot='".$ligne['idEntrepot']."' ";
  
                              $resE=mysql_query($sqlE);
  
                              $ligne_Dep = mysql_fetch_assoc($resE);
  
  
  
                              /***Debut verifier si la ligne du produit existe deja ***/
  
                              $sqlEl="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."' and idDesignation='".$ligne['idDesignation']."' and idEntrepot!='".$ligne['idEntrepot']."'";
  
                              $resEl = mysql_query($sqlEl) or die ("persoonel requête 2".mysql_error());
  
                              /***Debut verifier si la ligne du produit existe deja ***/
  
                              if(!mysql_num_rows($resEl)){
  
                                  $reqDp="SELECT * from  `".$nomtableEntrepot."` e
  
                                  INNER JOIN `".$nomtableEntrepotStock."` s ON s.idEntrepot = e.idEntrepot
  
                                  where s.designation='".$ligne['designation']."' AND s.quantiteStockCourant>0 AND e.idEntrepot<>'".$ligne["idEntrepot"]."' ORDER BY quantiteStockCourant DESC ";
  
                                  $resDp=mysql_query($reqDp) or die ("select stock impossible =>".mysql_error());
  
                                  $data=array();
  
                                  $data_distinct=array();
  
                                  while ($depot_Et = mysql_fetch_assoc($resDp)) {
  
                                    if(in_array($depot_Et['idEntrepot'], $data_distinct)){
  
                                      // echo "Existe.";
  
                                    }
  
                                    else{
  
                                      $rows = array();	
  
                                      $rows[] = $depot_Et['idEntrepot'];
  
                                      $rows[] = $depot_Et['nomEntrepot'];
  
                                      $data[] = $rows;
  
                                      $data_distinct[] = $depot_Et['idEntrepot'];
  
                                    }
  
                                  }
  
                              }
  
  
  
                              $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";
  
                              $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
  
                              $TotalT = mysql_fetch_array($resT) ;
  
  
  
                              $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";
  
                              $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
  
                              $t_stock = mysql_fetch_array($res_t) ;
  
                              // if ($pagnet['type']==10) {
                              //   # code...
                              //   $result='1<>'.$ligne['idDesignation'].'<>'.$ligne['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prixuniteStock'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$ligne_Dep['idEntrepot'].'<>'.$ligne_Dep['nomEntrepot'].'<>'.json_encode($data).'<>'.$pagnet['taux'];

                              // } else {
                              //   # code...
                                $result='1<>'.$ligne['idDesignation'].'<>'.$ligne['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prixuniteStock'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$ligne_Dep['idEntrepot'].'<>'.$ligne_Dep['nomEntrepot'].'<><>'.$pagnet['taux'];

                              // }
                              // $result='1<>'.$ligne['idDesignation'].'<>'.$ligne['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prixuniteStock'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$ligne_Dep['idEntrepot'].'<>'.$ligne_Dep['nomEntrepot'].'<>'.json_encode($data).'<>'.$pagnet['taux'];
  
                            }
  
                        }
  
                    }
  
                }
  
            }
  
            else{
  
              $result='3<>0';
  
            }
  
        }
  
        else{
  
            /***Debut verifier si la ligne du produit existe deja ***/
  
            $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";
  
            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
  
            $ligne1 = mysql_fetch_assoc($res);
  
            /***Debut verifier si la ligne du produit existe deja ***/
  
            if($ligne1 != null){
  
                $quantite = $ligne1['quantite'] + 1;
  
                $prixTotal=$quantite*$ligne1['prixunitevente'];
  
                $sql7="UPDATE `".$nomtableLigneTampon."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";
  
                $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());
  
            }
  
            else {
  
                $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet=".$idPagnet." ";
  
                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
  
                $ligne = mysql_fetch_assoc($res) ;
  
                if(mysql_num_rows($res)){
  
                    if($ligne['classe']==0){
  
                        $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."',0,'".$design['uniteStock']."',".$design['prixuniteStock'].",1,".$design['prixuniteStock'].",".$idPagnet.",0)";
  
                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() ); 
  
                    
  
                    }
  
                }
  
                else{
  
                    $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock, idDesignation,idEntrepot, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."',0,'".$design['uniteStock']."',".$design['prixuniteStock'].",1,".$design['prixuniteStock'].",".$idPagnet.",0)";
  
                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() ); 
  
                
  
                }
  
  
  
            }  
  
        }
  
    }
  
    else if($design['classe']==1 && $pagnet['type']==0){
  
        $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet=".$idPagnet." ";
  
        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
  
        $ligne = mysql_fetch_assoc($res) ;
  
        if(mysql_num_rows($res)){
  
            /***Debut verifier si la ligne du produit existe deja ***/
  
            $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=1  ";
  
            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
  
            $ligne1 = mysql_fetch_assoc($res);
  
            /***Debut verifier si la ligne du produit existe deja ***/
  
            if($ligne1 != null){
  
                $quantite = $ligne1['quantite'] + 1;
  
                $prixTotal=$quantite*$ligne1['prixunitevente'];
  
                $sql7="UPDATE `".$nomtableLigneTampon."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=1  ";
  
                $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());
  
                $result='2<>0';
  
            }
  
            else {
  
                if($ligne['classe']==1 || $ligne['classe']==0){
  
                    if($design['uniteStock']!='Transaction'){
  
                        $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe,idEntrepot)values('".$design['designation']."','".$design['idDesignation']."','".$design['idDesignation']."','".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",1,".$_SESSION['entrepot'].")";
  
                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
  
                        $result='2<>0';
  
                    }
  
                    
  
                }
  
                
  
            }
  
            
  
        }
  
        else{
  
  
          $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe,idEntrepot)values('".$design['designation']."','".$design['idDesignation']."','".$design['idDesignation']."','".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",1,".$_SESSION['entrepot'].")";
  
          $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

          $result='2<>0';
  
        }
  
    }
  
    else if($design['classe']==2 && $pagnet['type']==0){
  
        $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet=".$idPagnet." ";
  
        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
  
        $ligne = mysql_fetch_assoc($res) ;
  
        if(mysql_num_rows($res)){
  
            /***Debut verifier si la ligne du produit existe deja ***/
  
            $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."' and idStock='".$design['idDesignation']."' and classe=2  ";
  
            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
  
            $ligne1 = mysql_fetch_assoc($res);
  
            /***Debut verifier si la ligne du produit existe deja ***/
  
            if($ligne1 != null){
  
                $quantite = $ligne1['quantite'] + 1;
  
                $prixTotal=$quantite*$ligne1['prixunitevente'];
  
                $sql7="UPDATE `".$nomtableLigneTampon."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and idStock='".$design['idDesignation']."' and classe=2  ";
  
                $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());
  
                $result='2<>0';
  
            }
  
            else {
  
                if($ligne['classe']==2){
  
                    $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",2)";
  
                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
  
                    $result='2<>0';
  
                }
  
            }
  
        }
  
        else{
  
            $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",2)";
  
            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
  
            $result='2<>0';
  
        }
  
    }
  
    else if($design['classe']==5 && $pagnet['type']==0){
  
      $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet=".$idPagnet." ";
  
      $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
  
      $ligne = mysql_fetch_assoc($res) ;
  
      if(mysql_num_rows($res)){
  
          /***Debut verifier si la ligne du produit existe deja ***/
  
          $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=5  ";
  
          $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
  
          $ligne1 = mysql_fetch_assoc($res);
  
          /***Debut verifier si la ligne du produit existe deja ***/
  
          if($ligne1 != null){
  
          }
  
          else {
  
              if($ligne['classe']==5){
  
                  $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",5)";
  
                  $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
  
  
  
                  $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
  
                  $res2=mysql_query($sql2);
  
                  if(mysql_num_rows($res2)){
  
                    $ligne=mysql_fetch_assoc($res2);
  
                    $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";
  
                    $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
  
                    $TotalT = mysql_fetch_array($resT) ;
  
      
  
                    $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."' ";
  
                    $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
  
                    $t_stock = mysql_fetch_array($res_t) ;
  
                    $result='5<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$TotalT[0].'<>'.$pagnet['taux'];
  
                  }
  
              }
  
          }
  
      }
  
      else{
  
          $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",5)";
  
          $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
  
  
  
          $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
  
          $res2=mysql_query($sql2);
  
          if(mysql_num_rows($res2)){
  
            $ligne=mysql_fetch_assoc($res2);
  
            $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";
  
            $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
  
            $TotalT = mysql_fetch_array($resT) ;
  
  
  
            $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."' ";
  
            $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
  
            $t_stock = mysql_fetch_array($res_t) ;
  
            $result='5<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$pagnet['taux'];
  
          }
  
      }
  
    }
  
    else if($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1){
  
      if($design['classe']==6 && $pagnet['type']==0){
  
        $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet=".$idPagnet." ";
  
        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
  
        $ligne = mysql_fetch_assoc($res) ;
  
        if(mysql_num_rows($res)){
  
            /***Debut verifier si la ligne du produit existe deja ***/
  
            $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=7  ";
  
            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
  
            $ligne1 = mysql_fetch_assoc($res);
  
            /***Debut verifier si la ligne du produit existe deja ***/
  
            if($ligne1 != null){
  
            }
  
            else {
  
                if($ligne['classe']==6){
  
                    $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",6)";
  
                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
  
  
  
                    $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
  
                    $res2=mysql_query($sql2);
  
                    if(mysql_num_rows($res2)){
  
                      $ligne=mysql_fetch_assoc($res2);
  
                      $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";
  
                      $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
  
                      $TotalT = mysql_fetch_array($resT) ;
  
        
  
                      $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";
  
                      $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
  
                      $t_stock = mysql_fetch_array($res_t) ;
  
                      $result='2<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0];
  
                    }
  
                }
  
            }
  
        }
  
        else{
  
            $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",6)";
  
            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
  
  
  
            $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
  
            $res2=mysql_query($sql2);
  
            if(mysql_num_rows($res2)){
  
              $ligne=mysql_fetch_assoc($res2);
  
              $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";
  
              $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
  
              $TotalT = mysql_fetch_array($resT) ;
  
  
  
              $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";
  
              $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
  
              $t_stock = mysql_fetch_array($res_t) ;
  
              $result='2<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0];
  
            }
  
        }
  
      }
      else if($design['classe']==7 && $pagnet['type']==0) {
        // var_dump('in');
    
          $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet=".$idPagnet." ";
    
          $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    
          $ligne = mysql_fetch_assoc($res) ;
    
          if(mysql_num_rows($res)){
    
              /***Debut verifier si la ligne du produit existe deja ***/
    
              $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=7  ";
    
              $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    
              $ligne1 = mysql_fetch_assoc($res);
    
              /***Debut verifier si la ligne du produit existe deja ***/
    
              if($ligne1 != null){
    
              }
    
              else {
    
                  if($ligne['classe']==7){
    
                      $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",7)";
    
                      $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
    
                      $result='2<>0';
    
                  }
    
              }
    
          }
    
          else{
    
              $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",7)";
    
              $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
    
              $result='2<>0';
    
          }
    
      }
      else if($design['classe']==9 && $pagnet['type']==0){
      
          $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet=".$idPagnet." ";
      
          $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
      
          $ligne = mysql_fetch_assoc($res) ;
      
          if(mysql_num_rows($res)){
      
              /***Debut verifier si la ligne du produit existe deja ***/
      
              $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=7  ";
      
              $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
      
              $ligne1 = mysql_fetch_assoc($res);
      
              /***Debut verifier si la ligne du produit existe deja ***/
      
              if($ligne1 != null){
      
              }
      
              else {
      
                  if($ligne['classe']==9){
      
                      $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",9)";
      
                      $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
      
      
      
                      $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
      
                      $res2=mysql_query($sql2);
      
                      if(mysql_num_rows($res2)){
      
                      $ligne=mysql_fetch_assoc($res2);
      
                      $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";
      
                      $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
      
                      $TotalT = mysql_fetch_array($resT) ;
      
      
      
                      $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";
      
                      $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
      
                      $t_stock = mysql_fetch_array($res_t) ;$result='6<>0';
      
                      }
      
                  }
      
              }
      
          }
      
          else{
      
              $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",9)";
      
              $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
      
      
      
              $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
      
              $res2=mysql_query($sql2);
      
              if(mysql_num_rows($res2)){
      
              $ligne=mysql_fetch_assoc($res2);
      
              $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."'  ";
      
              $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
      
              $TotalT = mysql_fetch_array($resT) ;
      
      
      
              $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";
      
              $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
      
              $t_stock = mysql_fetch_array($res_t) ;
      
              $result='6<>0';
      
              }
      
          }
      
      }
      else if($design['classe']==10){
  
        $sql3="UPDATE `".$nomtablePagnetTampon."` set type='10' where idPagnet=".$idPagnet;
  
        $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());
  
        $result='2<>0';
  
      }
      else if($design['classe']==1 && $pagnet['type']==0){
  
        $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet=".$idPagnet." ";
  
        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
  
        $ligne = mysql_fetch_assoc($res) ;
  
        if(mysql_num_rows($res)){
  
            /***Debut verifier si la ligne du produit existe deja ***/
  
            $sql="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=1  ";
  
            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
  
            $ligne1 = mysql_fetch_assoc($res);
  
            /***Debut verifier si la ligne du produit existe deja ***/
  
            if($ligne1 != null){
  
                $quantite = $ligne1['quantite'] + 1;
  
                $prixTotal=$quantite*$ligne1['prixunitevente'];
  
                $sql7="UPDATE `".$nomtableLigneTampon."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=1  ";
  
                $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());
  
                $result='2<>0';
  
            }
  
            else {
  
                if($ligne['classe']==1 || $ligne['classe']==0){
  
                    if($design['uniteStock']!='Transaction'){
  
                        $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe,idEntrepot)values('".$design['designation']."','".$design['idDesignation']."','".$design['idDesignation']."','".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",1,".$_SESSION['entrepot'].")";
  
                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
  
                        $result='2<>0';
  
                    }
  
                    
  
                }
  
                
  
            }
  
            
  
        }
  
        else{
  
          $sql7="insert into `".$nomtableLigneTampon."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe,idEntrepot)values('".$design['designation']."','".$design['idDesignation']."','".$design['idDesignation']."','".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",1,".$_SESSION['entrepot'].")";
  
          $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

          $result='2<>0';
  
        }
  
    }
  
    }
  
    else if($design['classe']==8){
  
        $sql3="UPDATE `".$nomtablePagnetTampon."` set type='1' where idPagnet=".$idPagnet;
  
        $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());
  
        $result='2<>0';
  
    }
  
    else if($design['classe']==10){
  
        $sql3="UPDATE `".$nomtablePagnetTampon."` set type='10' where idPagnet=".$idPagnet;
  
        $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());
  
        $result='2<>0';
  
    }
  
  }

  // var_dump($result);
  
  exit($result);
  
}

else if($operation == 18){

  //$reponse+=sizeof($codeBrute);

  // $reponse="<ul><li>aucune donnee trouvé</li></ul>";

  $source=[];

  $query=htmlspecialchars(trim($_POST['query']));

  $reqS="SELECT * from `".$nomtableCategorie."` where nomcategorie LIKE '%$query%' ";

  $resS=mysql_query($reqS) or die ("insertion impossible");



  if($resS){

    // $reponse="<ul class='ulc'>";

      while ($data=mysql_fetch_array($resS)) {

        // $reponse.="<li class='liC_Ph'  style='background-color: #abdbb7; '>".$data['nomcategorie']." </li>";   

        $source[]=$data['nomcategorie'];

      }

    // $reponse.="</ul>";

  }

  // exit($reponse);

  echo json_encode($source);

}

else if($operation == 19){

  //$reponse+=sizeof($codeBrute);

  $source=[];

  // $reponse="<ul><li>aucune donnee trouvé</li></ul>";

  $query=htmlspecialchars(trim($_POST['query']));

  $reqS="SELECT DISTINCT forme from `".$nomtableDesignation."` where forme LIKE '%$query%' ";

  $resS=mysql_query($reqS) or die ("insertion impossible");



  if($resS){

    // $reponse="<ul class='ulc'>";

      while ($data=mysql_fetch_array($resS)) {

        // $reponse.="<li class='liF_Ph'  style='background-color: #abdbb7; '>".$data['forme']." </li>";   

        $source[]=$data['forme'];

      }

    // $reponse.="</ul>";

  }

  // exit($reponse);

  echo json_encode($source);

}

else if($operation == 20){

  //$reponse+=sizeof($codeBrute);

  // $reponse="<ul><li>aucune donnee trouvé</li></ul>";

  $source = [];

  $query=htmlspecialchars(trim($_POST['query']));

  $reqS="SELECT * from `".$nomtableDesignation."` where designation LIKE '%$query%' ";

  $resS=mysql_query($reqS) or die ("insertion impossible");

  if($resS){

    // $reponse="<ul class='ulc'>";

      while ($data=mysql_fetch_array($resS)) {

            // $reponse.="<li class='lic'>".$data['designation']."-".$data['idDesignation']."</li>";

            /*if($data['uniteStock']=='Transaction' && $data['seuil']==1){

              $reponse.="<li class='lic' style='background-color: #e0dd21ed; '>".$data['designation']." </li>";

            }*/

            if($data['forme']!='Transaction' && $data['forme']!='Facture' && $data['forme']!='Credit'){

                if($data['classe']==0){

                  $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$data['idDesignation']."' ";

                  $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

                  $t_stock = mysql_fetch_array($res_t) ;

                  if($t_stock[0]>0){

                    $source[] = $data['designation']." => ".$t_stock[0];

                    // $reponse.="     <li class='licM' style='background-color: #abdbb7; '>".$data['designation']." <span style='display:none'>=></span>&nbsp;&nbsp;<span disabled='true' class='badge bg-warning'>".$t_stock[0]."</span></li>";

                  }        

                }

                if($data['classe']==1){

                  $source[] = $data['designation'];

                  // $reponse.="<li class='licM'  style='background-color: #abdbb7; '>".$data['designation']." </li>";

                }

                if (($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1) && $_SESSION['enConfiguration']==0){

                  if($data['classe']==9){

                    $source[] = $data['designation'];

                    // $reponse.="<li class='licM'  style='background-color: #aceef3; '>".$data['designation']." </li>";

                  }

                }

              }

          }

    // $reponse.="</ul>";

  }

  // exit($reponse);

  echo json_encode($source);

}

else if($operation == 21){

  //$reponse+=sizeof($codeBrute);

  $designation=@$_POST["designation"];

  $idMutuellePagnet=@htmlspecialchars($_POST["idMutuellePagnet"]);

  $result="0";

  $sqlMP="SELECT * FROM `".$nomtableMutuellePagnet."` where idMutuellePagnet=".$idMutuellePagnet." ";

  $resMP = mysql_query($sqlMP) or die ("persoonel requête 2".mysql_error());

  if($resMP){

    $pagnet = mysql_fetch_assoc($resMP) ;

    $sql="SELECT * FROM `".$nomtableDesignation."` where (codeBarreDesignation='".$designation."' or codeBarreuniteStock='".$designation."' or designation='".$designation."') ";

    $res=mysql_query($sql) or die ("select stock impossible =>".mysql_error());

    if(mysql_num_rows($res)){

      $design = mysql_fetch_assoc($res);

      if($design['classe']==0){

        $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$design['idDesignation']."' ";

        $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

        $t_stock = mysql_fetch_array($res_t) ;

        $restant = $t_stock[0];

        if($restant>0){

            /***Debut verifier si la ligne du produit existe deja ***/

            $sql="SELECT * FROM `".$nomtableLigneTampon."` where idMutuellePagnet='".$idMutuellePagnet."' and designation='".$design['designation']."' and classe=0 ";

            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

            $ligne = mysql_fetch_assoc($res);

            /***Debut verifier si la ligne du produit existe deja ***/

            if($ligne != null){

                $quantite = $ligne['quantite'] + 1;

                $reste = $t_stock[0] - $quantite;

                if ($reste>=0){

                    $prixTotal=$quantite*$ligne['prixPublic'];

                    $sql7="UPDATE `".$nomtableLigneTampon."` set quantite=".$quantite.",prixtotal=".$prixTotal." where idMutuellePagnet=".$idMutuellePagnet." and idDesignation=".$design['idDesignation'];

                    $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());



                    $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idMutuellePagnet='".$idMutuellePagnet."' ";

                    $res2=mysql_query($sql2);

                    if(mysql_num_rows($res2)){

                      $ligne=mysql_fetch_assoc($res2);

                      $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idMutuellePagnet='".$idMutuellePagnet."'  ";

                      $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                      $TotalT = mysql_fetch_array($resT) ;

                      $result='2<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$design['forme'].'<>'.$design['prixPublic'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$quantite.'<>'.$pagnet['taux'];

                    }

                }

                else{

                    $result='3<>'.$t_stock[0];

                }                     

            }

            else {

                $sql="SELECT * FROM `".$nomtableLigneTampon."` where idMutuellePagnet=".$idMutuellePagnet." ";

                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

                $ligne = mysql_fetch_assoc($res) ;

                if(mysql_num_rows($res)){

                    if($ligne['classe']==0 || $ligne['classe']==1){

                        $sql7="insert into `".$nomtableLigneTampon."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idMutuellePagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idMutuellePagnet.",0)";

                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                        $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idMutuellePagnet='".$idMutuellePagnet."' ";

                        $res2=mysql_query($sql2);

                        if(mysql_num_rows($res2)){

                          $ligne=mysql_fetch_assoc($res2);

                          $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idMutuellePagnet='".$idMutuellePagnet."'  ";

                          $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                          $TotalT = mysql_fetch_array($resT) ;

                          $result='1<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$design['forme'].'<>'.$design['prixPublic'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$pagnet['taux'];

                        }

                    }

                }

                else{

                    $sql7="insert into `".$nomtableLigneTampon."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idMutuellePagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idMutuellePagnet.",0)";

                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                    $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idMutuellePagnet='".$idMutuellePagnet."' ";

                    $res2=mysql_query($sql2);

                    if(mysql_num_rows($res2)){

                      $ligne=mysql_fetch_assoc($res2);

                      $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idMutuellePagnet='".$idMutuellePagnet."'  ";

                      $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                      $TotalT = mysql_fetch_array($resT) ;

                      $result='1<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$design['forme'].'<>'.$design['prixPublic'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$pagnet['taux'];

                    }

                }

            

            }

        }

        else{

          $result='3<>0';

        }

      }
      if($design['classe']==1){

            /***Debut verifier si la ligne du produit existe deja ***/

            $sql="SELECT * FROM `".$nomtableLigneTampon."` where idMutuellePagnet='".$idMutuellePagnet."' and designation='".$design['designation']."' and classe=0 ";

            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

            $ligne = mysql_fetch_assoc($res);

            /***Debut verifier si la ligne du produit existe deja ***/

            if($ligne != null){

                $quantite = $ligne['quantite'] + 1;

                $reste = $t_stock[0] - $quantite;

                if ($reste>=0){

                    $prixTotal=$quantite*$ligne['prixPublic'];

                    $sql7="UPDATE `".$nomtableLigneTampon."` set quantite=".$quantite.",prixtotal=".$prixTotal." where idMutuellePagnet=".$idMutuellePagnet." and idDesignation=".$design['idDesignation'];

                    $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());



                    $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idMutuellePagnet='".$idMutuellePagnet."' ";

                    $res2=mysql_query($sql2);

                    if(mysql_num_rows($res2)){

                      $ligne=mysql_fetch_assoc($res2);

                      $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idMutuellePagnet='".$idMutuellePagnet."'  ";

                      $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                      $TotalT = mysql_fetch_array($resT) ;

                      $result='2<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$design['forme'].'<>'.$design['prixPublic'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$quantite.'<>'.$pagnet['taux'];

                    }

                }

                else{

                    $result='3<>'.$t_stock[0];

                }                     

            }

            else {

                $sql="SELECT * FROM `".$nomtableLigneTampon."` where idMutuellePagnet=".$idMutuellePagnet." ";

                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

                $ligne = mysql_fetch_assoc($res) ;

                if(mysql_num_rows($res)){

                    if($ligne['classe']==0 || $ligne['classe']==1){

                        $sql7="insert into `".$nomtableLigneTampon."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idMutuellePagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idMutuellePagnet.",0)";

                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                        $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idMutuellePagnet='".$idMutuellePagnet."' ";

                        $res2=mysql_query($sql2);

                        if(mysql_num_rows($res2)){

                          $ligne=mysql_fetch_assoc($res2);

                          $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idMutuellePagnet='".$idMutuellePagnet."'  ";

                          $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                          $TotalT = mysql_fetch_array($resT) ;

                          $result='1<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$design['forme'].'<>'.$design['prixPublic'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$pagnet['taux'];

                        }

                    }

                }

                else{

                    $sql7="insert into `".$nomtableLigneTampon."`(designation,idDesignation,idStock, forme,prixPublic,quantite,prixtotal,idMutuellePagnet,classe)values('".$design['designation']."','".$design['idDesignation']."',0,'".$design['forme']."',".$design['prixPublic'].",1,".$design['prixPublic'].",".$idMutuellePagnet.",0)";

                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



                    $sql2="SELECT * from  `".$nomtableLigneTampon."` WHERE designation='".$design['designation']."' AND idMutuellePagnet='".$idMutuellePagnet."' ";

                    $res2=mysql_query($sql2);

                    if(mysql_num_rows($res2)){

                      $ligne=mysql_fetch_assoc($res2);

                      $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idMutuellePagnet='".$idMutuellePagnet."'  ";

                      $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

                      $TotalT = mysql_fetch_array($resT) ;

                      $result='1<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$design['forme'].'<>'.$design['prixPublic'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$pagnet['taux'];

                    }

                }

            

            }

      }

    }

  }

  exit($result);

}

else if($operation == 22){



  $idMutuellePagnet=@htmlspecialchars($_POST["idMutuellePagnet"]);

  $numligne=@htmlspecialchars($_POST["numligne"]);

  $result="0";

  $sqlMP="SELECT * FROM `".$nomtableMutuellePagnet."` where idMutuellePagnet=".$idMutuellePagnet." ";

  $resMP = mysql_query($sqlMP) or die ("persoonel requête 2".mysql_error());

  if($resMP){

      $pagnet = mysql_fetch_assoc($resMP) ;

      $sql1="SELECT * from  `".$nomtableLigneTampon."` WHERE numligne='".$numligne."' ";

      $res1=mysql_query($sql1);

      if(mysql_num_rows($res1)){

          $ligne=mysql_fetch_assoc($res1);

          $sql7="DELETE FROM `".$nomtableLigneTampon."` where numligne='".$numligne."' ";

          $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );



          $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idMutuellePagnet='".$idMutuellePagnet."'  ";

          $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

          $TotalT = mysql_fetch_array($resT) ;

          $result='1<>'.$ligne['idDesignation'].'<>'.$ligne['designation'].'<>'.$ligne['forme'].'<>'.$ligne['prixPublic'].'<>'.$numligne.'<>'.$TotalT[0].'<>'.$pagnet['taux'];

      }

  }

  exit($result);

}

else if($operation == 23){

  //$reponse+=sizeof($codeBrute);

  // $reponse="<ul><li>aucune donnee trouvé</li></ul>";

  $source = [];

  $query=htmlspecialchars(trim($_POST['query']));



  $sqlC="SELECT * FROM `".$nomtableClient."` where (prenom LIKE '%$query%' OR nom LIKE '%$query%') and avoir=0 ";

  $resC = mysql_query($sqlC) or die ("persoonel requête 3".mysql_error());



  if($resC){

    // $reponse="<ul class='ulc'>";

      while ($data=mysql_fetch_array($resC)) {

        $source[] = $data['prenom']." ".strtoupper($data['nom']);

        // $reponse.="<li class='liCM'  style='background-color: #abdbb7; '>".$data['prenom']." ".strtoupper($data['nom'])." </li>";   

      }

    // $reponse.="</ul>";

    $idMutuellePagnet=@$_POST['idMutuellePagnet'];

    $sqlM="SELECT * FROM `".$nomtableMutuellePagnet."` where idMutuellePagnet=".$idMutuellePagnet." ";

    $resM = mysql_query($sqlM) or die ("persoonel requête 2".mysql_error());

    if($resM){

      $sql3="UPDATE `".$nomtableMutuellePagnet."` set adherant='".$query."' where idMutuellePagnet=".$idMutuellePagnet;

      $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());

    }

  }

  // exit($reponse);

  echo json_encode($source);

}

else if($operation == 24){

  $client=@$_POST['client'];

  $idMutuellePagnet=@$_POST['idMutuellePagnet'];

  $reponse="0";

  $sqlM="SELECT * FROM `".$nomtableMutuellePagnet."` where idMutuellePagnet=".$idMutuellePagnet." ";

  $resM = mysql_query($sqlM) or die ("persoonel requête 2".mysql_error());

  if($resM){

    $sql3="UPDATE `".$nomtableMutuellePagnet."` set adherant='".$client."' where idMutuellePagnet=".$idMutuellePagnet;

    $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());

    $reponse="1";

  }

  exit($reponse);

}

else if($operation == 25){

  $codeAdherant=@$_POST['codeAdherant'];

  $idMutuellePagnet=@$_POST['idMutuellePagnet'];

  $reponse="0";

  $sqlM="SELECT * FROM `".$nomtableMutuellePagnet."` where idMutuellePagnet=".$idMutuellePagnet." ";

  $resM = mysql_query($sqlM) or die ("persoonel requête 2".mysql_error());

  if($resM){

    $sql3="UPDATE `".$nomtableMutuellePagnet."` set codeAdherant='".$codeAdherant."' where idMutuellePagnet=".$idMutuellePagnet;

    $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());

    $reponse="1";

  }

  exit($reponse);

}









else if($operation == 26){

  $clients = [];

  $query=htmlspecialchars(trim($_POST['query']));

  

  $sql3="SELECT * FROM `".$nomtableClient."` where (prenom LIKE '%$query%' or nom LIKE '%$query%' or adresse LIKE '%$query%') and archiver=0 and activer=1 and avoir=0 ORDER BY idClient ASC";

  $res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());



  while($client = mysql_fetch_assoc($res3)){

      $clients[] = $client['idClient']." . ".$client['prenom']." ".$client['nom']." ".$client['adresse'];

  }

  $clients[] = '0 . simple simple simple';
  

  echo json_encode($clients);

}



else if($operation == 27){

  // $idPanier=htmlspecialchars(trim($_POST['idPanier']));

  $idClient=htmlspecialchars(trim($_POST['idClient']));

  $idPanier=htmlspecialchars(trim($_POST['idPanier']));

  $dataType=htmlspecialchars(trim($_POST['typeData']));

  // $nomtablePagnetTampon=$_SESSION['nomB'].'-pagnet';

  // $nomtableClient=$_SESSION['nomB'].'-client';



  // $sql3="UPDATE `".$nomtablePagnetTampon."` SET idClient = 0 where idPagnet=".$idPanier;

  if ($dataType == 'simple') {

    # code...

    $r = '';

    if ($idClient == '0') {
      if ($_SESSION['compte'] == 1) {
        # code...
        $sql3="UPDATE `".$nomtablePagnetTampon."` SET type=0, idCompte=0, idClient=".$idClient." where idPagnet='".$idPanier."'";

        $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible 1--- ".mysql_error());
      } else {
        # code...
        $sql3="UPDATE `".$nomtablePagnetTampon."` SET type=0, idClient=".$idClient." where idPagnet='".$idPanier."'";

        $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible 1--- ".mysql_error());
      }
      
      $r = '1'.'/'.$idClient.'/'.$idPanier; 

    } else {

      if ($_SESSION['compte'] == 1) {
        $sql3="UPDATE `".$nomtablePagnetTampon."` SET type=30, idCompte=2, idClient=".$idClient." where idPagnet='".$idPanier."'";

        $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible 1--- ".mysql_error());     
      } else {
        
        $sql3="UPDATE `".$nomtablePagnetTampon."` SET type=0, idClient=".$idClient." where idPagnet='".$idPanier."'";

        $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible 1--- ".mysql_error());
      }
      $r = '2'.'/'.$idClient.'/'.$idPanier; 

    }

  } else if ($dataType == 'mutuelle') {

    # code...

    if ($idClient == '0') {

      # code...

      $sql3="UPDATE `".$nomtableMutuellePagnet."` SET type=0, idCompte=0, idClient=".$idClient." where idMutuellePagnet='".$idPanier."'";

      $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible 1--- ".mysql_error());

      $r = '3'.'/'.$idClient.'/'.$idPanier; 

    } else {

      # code...

      $sql3="UPDATE `".$nomtableMutuellePagnet."` SET type=30, idCompte=2, idClient=".$idClient." where idMutuellePagnet='".$idPanier."'";

      $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible 1--- ".mysql_error());

      $r = '4'.'/'.$idClient.'/'.$idPanier; 

    }

  }

  



  // $sql4="SELECT * FROM  `".$nomtableClient."` where idClient=".$idClient;

  // $res4=@mysql_query($sql4) or die ("mise à jour verouillage  impossible 2+++ ".mysql_error());



  // $client = mysql_fetch_array($res4);



  // echo $client['prenom']." ".$client['nom']." ".$client['adresse'];

  echo $r;

  // echo $nomtableClient.' / '.$nomtablePagnetTampon;

}



else if($operation == 28){

  // $idPanier=htmlspecialchars(trim($_POST['idPanier']));

  $msg_info='';

  $idMutuellePagnet=@$_POST['idMutuellePagnet'];

  $codeBeneficiaire=htmlspecialchars(trim($_POST['codeBeneficiaire']));

  $numeroRecu=htmlspecialchars(trim($_POST['numeroRecu']));

  $dateRecu=htmlspecialchars(trim($_POST['dateRecu']));

  $nomAdherant=htmlspecialchars(trim($_POST['nomAdherant']));

  $sql="SELECT * FROM `".$nomtableMutuellePagnet."` where idMutuellePagnet=".$idMutuellePagnet." ";

  $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

  $pagnet = mysql_fetch_assoc($res) ;

  if($pagnet['adherant']!=null && $pagnet['adherant']!=' '){
    $nomAdherant=$pagnet['adherant'];
  }

  if($pagnet!=null){

      $sql0="SELECT * FROM `".$nomtableMutuelle."` where idMutuelle=".$pagnet['idMutuelle']." ";

      $res0 = mysql_query($sql0) or die ("persoonel requête 2".mysql_error());

      $mutuelle = mysql_fetch_assoc($res0) ;

      if($mutuelle!=null && ($pagnet['codeAdherant']!=null && $pagnet['codeAdherant']!=' ') && ($nomAdherant!='' && $codeBeneficiaire!='' && $numeroRecu!='' && $dateRecu!='')){

          $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idMutuellePagnet=".$idMutuellePagnet." ";

          $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

          $TotalP = mysql_fetch_array($resT) ;



          $totalp=$TotalP[0];

          $apayerMutuelle= ($totalp * $pagnet['taux']) / 100;

          $apayerPagnet= $totalp - $apayerMutuelle;

          if(@$_POST['versement']==''){

              $versement=0;

              $monaie=0;

          }

          else{

              $versement=@$_POST['versement'];

              $monaie=$versement-$apayerPagnet;

          }



          //$msg_info="<p>$totalp</p> <p>$remise</p> <p>$versement</p> <p>$apayerPagnet</p>";



          $sqlL="SELECT * FROM `".$nomtableLigneTampon."` where idMutuellePagnet=".$idMutuellePagnet." ";

          $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());

          //$ligne = mysql_fetch_assoc($resL) ;



          /*****Debut Nombre de Panier ouvert****/

          $query = mysql_query("SELECT * FROM `".$nomtableLigneTampon."` where idMutuellePagnet=".$idMutuellePagnet." ");

          $nbre_fois=mysql_num_rows($query);

          /*****Fin Nombre de Panier ouvert****/



          /*****Debut Difference entre Total Panier et Remise****/

          $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idMutuellePagnet=".$idMutuellePagnet." ";

          $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

          $TotalT = mysql_fetch_array($resT) ;



          $difference=$TotalT[0];

          /*****Fin Difference entre Total Panier et Remise****/



          if($pagnet['verrouiller']==0){

              if($nbre_fois>0){

                  if($difference>=0){

                      mysql_query("SET AUTOCOMMIT=0;");

                      mysql_query("START TRANSACTION;");

                      $i=0;

                      $j=0;

                      while ($ligne=mysql_fetch_assoc($resL)){

                          if($ligne['classe']==0){

                              $sqlS="SELECT * FROM `".$nomtableDesignation."` WHERE idDesignation='".$ligne['idDesignation']."' ";

                              $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                              $designation = mysql_fetch_assoc($resS) ;

                                  if(mysql_num_rows($resS)){

                                      $restant=$ligne['quantite'];

                                      $sqlD="SELECT * FROM `".$nomtableStock."` WHERE idDesignation='".$ligne['idDesignation']."' ORDER BY idStock ASC ";

                                      $resD=mysql_query($sqlD) or die ("select designation impossible =>".mysql_error());

                                      $stock0 = mysql_fetch_assoc($resD);

                                      $quantiteinitial=$stock0['quantiteStockCourant'] - $restant;

                                      if($quantiteinitial >= 0){

                                          $sqlS0="UPDATE `".$nomtableStock."` SET quantiteStockCourant='".$quantiteinitial."' WHERE idStock='".$stock0['idStock']."' ";

                                          $resS0=mysql_query($sqlS0) or die ("update stock impossible =>".mysql_error());

                                          if($resS0){

                                              $i=$i + 1;;

                                          }

                                      }

                                      else{

                                          $sqlE="SELECT * FROM `".$nomtableStock."` WHERE idDesignation='".$ligne['idDesignation']."' ORDER BY idStock ASC ";

                                          $resE=mysql_query($sqlE) or die ("select designation impossible =>".mysql_error());

                                          $k=0;

                                          $l=0;

                                          while ($stock = mysql_fetch_assoc($resE)) {

                                              if($restant>= 0){

                                                  $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;

                                                  if($quantiteStockCourant > 0){

                                                      $sqlS1="UPDATE `".$nomtableStock."` SET quantiteStockCourant='".$quantiteStockCourant."' WHERE idStock='".$stock['idStock']."' ";

                                                      $resS1=mysql_query($sqlS1) or die ("update stock impossible =>".mysql_error());

                                                      if($resS1){

                                                          $k=$k + 1;

                                                      }

                                                  }

                                                  else{

                                                      $sqlS2="UPDATE `".$nomtableStock."` SET quantiteStockCourant=0 WHERE idStock='".$stock['idStock']."' ";

                                                      $resS2=mysql_query($sqlS2) or die ("update stock impossible =>".mysql_error());

                                                      if($resS2){

                                                          $k=$k + 1;

                                                      }

                                                  }

                                                  $restant= $restant - $stock['quantiteStockCourant'] ;

                                                  $l=$l + 1;

                                              }

                                          }

                                          if($k==$l){

                                              $i=$i + 1;

                                          }

                                      }

                                  }

                          }

                          else{

                              $i=$i + 1;  

                          }

                          $j=$j + 1;

                      }

                      if($pagnet['adherant']!=null && $pagnet['adherant']!=' '){
                        $sql3="UPDATE `".$nomtableMutuellePagnet."` set codeBeneficiaire='".$codeBeneficiaire."',numeroRecu='".$numeroRecu."',dateRecu='".$dateRecu."',

                        verrouiller='1',totalp=".$totalp.",apayerPagnet=".$apayerPagnet.",apayerMutuelle=".$apayerMutuelle.",versement='".$versement."',restourne='".$monaie."' where idMutuellePagnet=".$idMutuellePagnet;
  
                        $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());
                      }
                      else{
                        $sql3="UPDATE `".$nomtableMutuellePagnet."` set adherant='".$nomAdherant."',codeBeneficiaire='".$codeBeneficiaire."',numeroRecu='".$numeroRecu."',dateRecu='".$dateRecu."',

                        verrouiller='1',totalp=".$totalp.",apayerPagnet=".$apayerPagnet.",apayerMutuelle=".$apayerMutuelle.",versement='".$versement."',restourne='".$monaie."' where idMutuellePagnet=".$idMutuellePagnet;
  
                        $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());
                      }



          

                      if ($_SESSION['compte']==1) {

                        if(isset($_POST['compte'])) {

                            $idCompte=$_POST['compte'];

                            

                            $sqlL="SELECT * FROM `".$nomtableLigneTampon."` where idMutuellePagnet=".$idMutuellePagnet." ORDER BY numLigne LIMIT 1 ";

                            $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());

                            $lignes = mysql_fetch_array($resL) ;

                            if ($lignes['classe'] == '2') {

                                

                                $description="Dépenses";

                                $operation='retrait';

                

                                $sql8="UPDATE `".$nomtableMutuellePagnet."` set idCompte=".$idCompte." where  idPagnet=".$idMutuellePagnet;

                                $res8=@mysql_query($sql8) or die ("mise à jour pour activer ou pas ".mysql_error());

                

                                $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte - ".$apayerPagnet." where  idCompte=".$idCompte;

                                $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());

                

                                $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,mouvementLink,idUser) values(".$apayerPagnet.",'".$operation."',".$idCompte.",'".$description."','".$dateHeures."','".$dateHeures."',".$idPagnet.",".$_SESSION['iduser'].")";

                                $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error());

                                

                            }else {

                                    

                              if(isset($_POST['avanceInput']) && $_POST['avanceInput'] > 0 && $idCompte == '2'){

                                  // $avanceInput=$_POST['avanceInput'];

                                  $montant=htmlspecialchars(trim($_POST['avanceInput']));

                                  $compteAvance=htmlspecialchars(trim($_POST['compteAvance']));

                                  $idClient=$pagnet['idClient'];

                                  $dateVersement=$dateString2;

                                  

                                  $sql30="UPDATE `".$nomtableMutuellePagnet."` set avance=".$montant." where idMutuellePagnet=".$idMutuellePagnet;

                                  $res30=mysql_query($sql30) or die ("update avance pagnet impossible =>".mysql_error());



                                  $sql2="insert into `".$nomtableVersement."` (idClient,paiement,montant,dateVersement,heureVersement,idCompte,idMutuellePagnet,iduser) values(".$idClient.",'avance client',".$montant.",'".$dateVersement."','".$heureString."',".$compteAvance.",".$idMutuellePagnet.",".$_SESSION['iduser'].")";

                                  $res2=mysql_query($sql2) or die ("insertion Versement impossible =>".mysql_error());

                                  // $solde=$montant+$client['solde'];

                                  $reqv="SELECT idVersement from `".$nomtableVersement."` order by idVersement desc limit 1";

                                  $resv=mysql_query($reqv) or die ("persoonel requête 2".mysql_error());

                                  $v = mysql_fetch_array($resv);

                                  $idVersement=$v['idVersement'];



                                  $sql3="UPDATE `".$nomtableClient."` set solde=solde-".$montant." where idClient=".$idClient;

                                  $res3=mysql_query($sql3) or die ("update solde client impossible =>".mysql_error());



                                  $operation='depot';

                                  $description="Avance bon mutuelle";



                                  $sql7="UPDATE `".$nomtableCompte."` set montantCompte=montantCompte + ".$montant." where idCompte=".$compteAvance;

                                  $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());



                                  $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,idVersement,idMutuellePagnet,idUser) values(".$montant.",'".$operation."',".$compteAvance.",'".$description."','".$dateHeures."','".$dateHeures."',".$idVersement.",".$idMutuellePagnet.",".$_SESSION['iduser'].")";

                                  $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error() );

                                  

                                  $operation2='retrait';

                                  $idCompteBon='2';

                                  $description2="Bon mutuelle encaissé";



                                  $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte - ".$montant." where  idCompte=".$idCompteBon;

                                  $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());



                                  $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,idVersement,idMutuellePagnet,idUser) values(".$montant.",'".$operation2."',".$idCompteBon.",'".$description2."','".$dateHeures."','".$dateHeures."',".$idVersement.",".$idMutuellePagnet.",".$_SESSION['iduser'].")";

                                  $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error() );

                                      

                              }



                            $operation='depot';

                            if ($idCompte == '2') {

                                $description="Bon imputation adhérant";

                                $idClient=$pagnet['idClient'];



                                $sql3="UPDATE `".$nomtableClient."` set solde=solde+".$apayerPagnet." where idClient=".$idClient;

                                $res3=mysql_query($sql3) or die ("update solde client impossible =>".mysql_error());

                                

                                $sql18="SELECT SUM(apayerPagnet) FROM `".$nomtableMutuellePagnet."` where verrouiller='1' && idClient=".$idClient." && (type=0 || type=30) ";

                                $res18 = mysql_query($sql18) or die ("persoonel requête 2".mysql_error());

                                $TotalP = mysql_fetch_array($res18) ;



                                $sql19="SELECT SUM(apayerPagnet) FROM `".$nomtableMutuellePagnet."` where idClient=".$idClient." && type=1 ";

                                $res19 = mysql_query($sql19) or die ("persoonel requête 2".mysql_error());

                                $TotalR = mysql_fetch_array($res19) ;



                                $total=$TotalP[0] - $TotalR[0];

                                

                                $sql20="UPDATE `".$nomtableBon."` set montant=".$total.", date='".$dateString."' where idClient=".$idClient;

                                $res20=mysql_query($sql20) or die ("update Pagnet impossible =>".mysql_error());



                            } else {

                                $description="Encaissement imputation adhérant";

                            }

            

                            $sql8="UPDATE `".$nomtableMutuellePagnet."`  set  idCompte=".$idCompte." where  idMutuellePagnet=".$idMutuellePagnet;

                            $res8=@mysql_query($sql8) or die ("mise à jour pour activer ou pas ".mysql_error());

            

                            $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte + ".$apayerPagnet." where  idCompte=".$idCompte;

                            $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());

            

                            $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,idMutuellePagnet,idUser) values(".$apayerPagnet.",'".$operation."',".$idCompte.",'".$description."','".$dateHeures."','".$dateHeures."',".$idMutuellePagnet.",".$_SESSION['iduser'].")";

                            $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error());

                          }                      

                        }

                      }



                      if(($i==$j) && $res3){

                          mysql_query("COMMIT;");

                      }

                      else{

                          mysql_query("ROLLBACK;");

                          $msg_info="PROBLEME CONNECTION INTERNET .</br></br> Veuillez terminer le bon numéro ".$idMutuellePagnet.".";

                      }

                  }

                  else {

                      $msg_info="IMPOSSIBLE.</br></br> Avant de terminer le pagnet numéro ".$idMutuellePagnet.", il faut verifier la remise.";

                  }

                  

              }

              else {

                  $msg_info="IMPOSSIBLE.</br></br> Avant de terminer le pagnet numéro ".$idMutuellePagnet.", il faut au moins ajouter un produit.";

              }

          }



          // if(isset($_POST['compte'])) {

            //     $operation='depot';

            //     $idCompte=$_POST['compte'];

            //     if ($idCompte == '2') {

            //         # code...

            //         $description="Bon imputation adhérant";

            //     } else {

            //         # code...

            //         $description="Encaissement imputation adhérant";

            //     }

            //     // var_dump($idCompte);

            //     // $description=$refTransf;

            //     // $newMontant=$compte['montantCompte']+$montantTransfert;



            //     $sql8="UPDATE `".$nomtableMutuellePagnet."`  set  idCompte=".$idCompte." where  idMutuellePagnet=".$idMutuellePagnet;

            //     //var_dump($sql7);

            //     $res8=@mysql_query($sql8) or die ("mise à jour pour activer ou pas ".mysql_error());



            //     $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte + ".$apayerPagnet." where  idCompte=".$idCompte;

            //     //var_dump($sql7);

            //     $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());



            //     $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,idUser) values(".$apayerPagnet.",'".$operation."',".$idCompte.",'".$description."','".$dateHeures."','".$dateHeures."',".$_SESSION['iduser'].")";

            //     //var_dump($sql6);

            //     $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error() );

          // }

      }

      else{

           $msg_info="IMPOSSIBLE.</br></br> Il manque des informations pour la mutuelle de sante.";

      }

  }

  else{

      $msg_info="IMPOSSIBLE.</br></br> Vous n'avez pas choisi la mutuelle de sante.";

  }



  echo $msg_info;

}



else if($operation == 29){

  // $idPanier=htmlspecialchars(trim($_POST['idPanier']));

  $msg_info='';

  

  $idMutuellePagnet=@$_POST['idMutuellePagnet'];

  $nomAdherant=@$_POST['nomAdherant'];

  $codeAdherant=@$_POST['codeAdherant'];

  $nomMutuelle=@$_POST['nomMutuelle'];

  $idClientAv=@$_POST['idClientAvoir'];

  // var_dump($nomMutuelle);

  $codeBeneficiaire=htmlspecialchars(trim($_POST['codeBeneficiaire']));

  $numeroRecu=htmlspecialchars(trim($_POST['numeroRecu']));

  $dateRecu=htmlspecialchars(trim($_POST['dateRecu']));



  $sql="SELECT * FROM `".$nomtableMutuellePagnet."` where idMutuellePagnet=".$idMutuellePagnet." ";

  $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

  $pagnet = mysql_fetch_assoc($res) ;

  if($pagnet!=null){

      if ($idClientAv!=null) {

        # code...

        $sql0="SELECT * FROM `".$nomtableMutuelle."` where nomMutuelle='".$nomMutuelle."'";

        // var_dump($nomtableMutuelle);

        // var_dump($sql0);

      } else {

        # code...

        $sql0="SELECT * FROM `".$nomtableMutuelle."` where idMutuelle=".$pagnet['idMutuelle']." ";

        $codeAdherant=$pagnet['codeAdherant'];

        

      }

    

      // var_dump($idClientAv."-".$codeAdherant."/".$codeBeneficiaire."/".$numeroRecu."/".$dateRecu);

      $res0 = mysql_query($sql0) or die ("persoonel requête 2".mysql_error());

      $mutuelle = mysql_fetch_assoc($res0) ;

      

      if($mutuelle!=null && ($codeAdherant!=null && $codeAdherant!=' ') && ($codeBeneficiaire!='' && $numeroRecu!='' && $dateRecu!='')){

          $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idMutuellePagnet=".$idMutuellePagnet." ";

          $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

          $TotalP = mysql_fetch_array($resT) ;



          $totalp=$TotalP[0];

          $apayerMutuelle= ($totalp * $pagnet['taux']) / 100;

          $apayerPagnet= $totalp - $apayerMutuelle;



          //$msg_info="<p>$totalp</p> <p>$remise</p> <p>$versement</p> <p>$apayerPagnet</p>";



          $sqlL="SELECT * FROM `".$nomtableLigneTampon."` where idMutuellePagnet=".$idMutuellePagnet." ";

          $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());

          //$ligne = mysql_fetch_assoc($resL) ;



          /*****Debut Nombre de Panier ouvert****/

          $query = mysql_query("SELECT * FROM `".$nomtableLigneTampon."` where idMutuellePagnet=".$idMutuellePagnet." ");

          $nbre_fois=mysql_num_rows($query);

          /*****Fin Nombre de Panier ouvert****/



          /*****Debut Difference entre Total Panier et Remise****/

          $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigneTampon."` where idMutuellePagnet=".$idMutuellePagnet." ";

          $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());

          $TotalT = mysql_fetch_array($resT) ;



          $difference=$TotalT[0];

          /*****Fin Difference entre Total Panier et Remise****/



          if($pagnet['verrouiller']==0){

              if($nbre_fois>0){

                  if($difference>=0){

                    mysql_query("SET AUTOCOMMIT=0;");

                    mysql_query("START TRANSACTION;");

                    $i=0;

                    $j=0;

                    while ($ligne=mysql_fetch_assoc($resL)){

                        if($ligne['classe']==0){

                            $sqlS="SELECT * FROM `".$nomtableDesignation."` WHERE idDesignation='".$ligne['idDesignation']."' ";

                            $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                            $designation = mysql_fetch_assoc($resS) ;

                                if(mysql_num_rows($resS)){

                                    $restant=$ligne['quantite'];

                                    $sqlD="SELECT * FROM `".$nomtableStock."` WHERE idDesignation='".$ligne['idDesignation']."' ORDER BY idStock ASC ";

                                    $resD=mysql_query($sqlD) or die ("select designation impossible =>".mysql_error());

                                    $stock0 = mysql_fetch_assoc($resD);

                                    $quantiteinitial=$stock0['quantiteStockCourant'] - $restant;

                                    if($quantiteinitial >= 0){

                                        $sqlS0="UPDATE `".$nomtableStock."` SET quantiteStockCourant='".$quantiteinitial."' WHERE idStock='".$stock0['idStock']."' ";

                                        $resS0=mysql_query($sqlS0) or die ("update stock impossible =>".mysql_error());

                                        if($resS0){

                                            $i=$i + 1;;

                                        }

                                    }

                                    else{

                                        $sqlE="SELECT * FROM `".$nomtableStock."` WHERE idDesignation='".$ligne['idDesignation']."' ORDER BY idStock ASC ";

                                        $resE=mysql_query($sqlE) or die ("select designation impossible =>".mysql_error());

                                        $k=0;

                                        $l=0;

                                        while ($stock = mysql_fetch_assoc($resE)) {

                                            if($restant>= 0){

                                                $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;

                                                if($quantiteStockCourant > 0){

                                                    $sqlS1="UPDATE `".$nomtableStock."` SET quantiteStockCourant='".$quantiteStockCourant."' WHERE idStock='".$stock['idStock']."' ";

                                                    $resS1=mysql_query($sqlS1) or die ("update stock impossible =>".mysql_error());

                                                    if($resS1){

                                                        $k=$k + 1;

                                                    }

                                                }

                                                else{

                                                    $sqlS2="UPDATE `".$nomtableStock."` SET quantiteStockCourant=0 WHERE idStock='".$stock['idStock']."' ";

                                                    $resS2=mysql_query($sqlS2) or die ("update stock impossible =>".mysql_error());

                                                    if($resS2){

                                                        $k=$k + 1;

                                                    }

                                                }

                                                $restant= $restant - $stock['quantiteStockCourant'] ;

                                                $l=$l + 1;

                                            }

                                        }

                                        if($k==$l){

                                            $i=$i + 1;

                                        }

                                    }

                                }

                        }

                        else{

                            $i=$i + 1;  

                        }

                        $j=$j + 1;

                    }



                    /*****Debut Nombre de Panier ouvert****/

                    // $queryC = mysql_query("SELECT * FROM `".$nomtableClient."` where idClient=".$idClientAv." ");

                    // $nbre_fois=mysql_num_rows($queryC);

                    /*****Fin Nombre de Panier ouvert****/

                     if ($idClientAv!=null) {

                       # code...

  

                        $sql0="SELECT * FROM `".$nomtableMutuelle."` where nomMutuelle='".$nomMutuelle."'";

                        $res0 = mysql_query($sql0) or die ("persoonel requête 2".mysql_error());

                        $mt = mysql_fetch_array($res0) ;

                        // var_dump($mt['idMutuelle']);

                        $sql3="UPDATE `".$nomtableMutuellePagnet."` set adherant='".$nomAdherant."',codeBeneficiaire='".$codeBeneficiaire."',numeroRecu='".$numeroRecu."',dateRecu='".$dateRecu."',

                        verrouiller='1',totalp=".$totalp.",apayerPagnet=0,apayerMutuelle=".$apayerPagnet.",idMutuelle=".$mt['idMutuelle']." where idMutuellePagnet=".$idMutuellePagnet;

                        $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());



                        $sql8="UPDATE `".$nomtableClient."` set montantAvoir=montantAvoir-".$apayerPagnet." where  idClient=".$idClientAv;

                        $res8=@mysql_query($sql8) or die ("mise à jour pour activer ou pas ".mysql_error());

                     }else{

                      $sql3="UPDATE `".$nomtableMutuellePagnet."` set adherant='".$nomAdherant."',codeBeneficiaire='".$codeBeneficiaire."',numeroRecu='".$numeroRecu."',dateRecu='".$dateRecu."',

                      verrouiller='1',totalp=".$totalp.",apayerPagnet=".$apayerPagnet.",apayerMutuelle=".$apayerMutuelle." where idMutuellePagnet=".$idMutuellePagnet;

                      $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());

                       

                      if($_SESSION['compte']==1) {

                        $operation='depot';

                        $idCompte='2'; 

                        $description="Bon imputation adhérant";

                        // var_dump($idCompte);

                        // $description=$refTransf;

                        // $newMontant=$compte['montantCompte']+$montantTransfert;

                        $sql8="UPDATE `".$nomtableMutuellePagnet."` set idCompte=".$idCompte." where  idMutuellePagnet=".$idMutuellePagnet;

                        $res8=@mysql_query($sql8) or die ("mise à jour pour activer ou pas ".mysql_error());

        

                        $sql7="UPDATE `".$nomtableCompte."` set montantCompte= montantCompte + ".$apayerPagnet." where  idCompte=".$idCompte;

                        $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());

        

                        $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,idMutuellePagnet,idUser) values(".$apayerPagnet.",'".$operation."',".$idCompte.",'".$description."','".$dateHeures."','".$dateHeures."',".$idMutuellePagnet.",".$_SESSION['iduser'].")";

                        $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error());

        

                        if(isset($_POST['avanceInput']) && $_POST['avanceInput'] > 0 && $idCompte == '2'){

                          // $avanceInput=$_POST['avanceInput'];

                          $montant=htmlspecialchars(trim($_POST['avanceInput']));

                          $compteAvance=htmlspecialchars(trim($_POST['compteAvance']));

                          $idClient=$pagnet['idClient'];

                          $dateVersement=$dateString2;

                          

                          $sql30="UPDATE `".$nomtableMutuellePagnet."` set avance=".$montant." where idMutuellePagnet=".$idMutuellePagnet;

                          $res30=mysql_query($sql30) or die ("update avance pagnet impossible =>".mysql_error());



                          $sql2="insert into `".$nomtableVersement."` (idClient,paiement,montant,dateVersement,heureVersement,idCompte,idMutuellePagnet,iduser) values(".$idClient.",'avance client',".$montant.",'".$dateVersement."','".$heureString."',".$compteAvance.",".$idMutuellePagnet.",".$_SESSION['iduser'].")";

                          $res2=mysql_query($sql2) or die ("insertion Versement impossible =>".mysql_error());

                          // $solde=$montant+$client['solde'];

                          $reqv="SELECT idVersement from `".$nomtableVersement."` order by idVersement desc limit 1";

                          $resv=mysql_query($reqv) or die ("persoonel requête 2".mysql_error());

                          $v = mysql_fetch_array($resv);

                          $idVersement=$v['idVersement'];



                          $sql3="UPDATE `".$nomtableClient."` set solde=solde-".$montant." where idClient=".$idClient;

                          $res3=mysql_query($sql3) or die ("update solde client impossible =>".mysql_error());



                          $operation='depot';

                          $description="Avance bon mutuelle";



                          $sql7="UPDATE `".$nomtableCompte."` set montantCompte=montantCompte + ".$montant." where idCompte=".$compteAvance;

                          $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());



                          $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,idVersement,idMutuellePagnet,idUser) values(".$montant.",'".$operation."',".$compteAvance.",'".$description."','".$dateHeures."','".$dateHeures."',".$idVersement.",".$idMutuellePagnet.",".$_SESSION['iduser'].")";

                          $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error() );

                          

                          $operation2='retrait';

                          $idCompteBon='2';

                          $description2="Bon mutuelle encaissé";



                          $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte - ".$montant." where  idCompte=".$idCompteBon;

                          $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());



                          $sql6="insert into `".$nomtableComptemouvement."` (montant,operation,idCompte,description,dateOperation,dateSaisie,idVersement,idMutuellePagnet,idUser) values(".$montant.",'".$operation2."',".$idCompteBon.",'".$description2."','".$dateHeures."','".$dateHeures."',".$idVersement.",".$idMutuellePagnet.",".$_SESSION['iduser'].")";

                          $res6=mysql_query($sql6) or die ("insertion Cmpte impossible =>".mysql_error() );

                              

                        }

                      }



                      if(($i==$j) && $res3){

                          mysql_query("COMMIT;");

                      }

                      else{

                          mysql_query("ROLLBACK;");

                          $msg_info="PROBLEME CONNECTION INTERNET .</br></br> Veuillez terminer le bon numéro ".$idMutuellePagnet.".";

                      }

                    }

                  }

                  else {

                      $msg_info="IMPOSSIBLE.</br></br> Avant de terminer le pagnet numéro ".$idMutuellePagnet.", il faut verifier la remise.";

                  }

                  

              }

              else {

                  $msg_info="IMPOSSIBLE.</br></br> Avant de terminer le pagnet numéro ".$idMutuellePagnet.", il faut au moins ajouter un produit.";

              }

          }



          // if(isset($_POST['compte'])) {

             

          // }

      }

      else{

           $msg_info="IMPOSSIBLE.</br></br> Il manque des informations pour la mutuelle de sante.";

      }

  }

  else{

      $msg_info="IMPOSSIBLE.</br></br> Vous n'avez pas choisi la mutuelle de sante.";

  }

  

  echo $msg_info;

}

else if($operation == 30){



  $idPagnet=htmlspecialchars(trim($_POST['idPanier']));



  $sql1="UPDATE `".$nomtablePagnetTampon."` set  verrouiller=0 where idPagnet=".$idPagnet;

  $res1=mysql_query($sql1) or die ("Edition panier impossible =>".mysql_error());

        

  if($res1){

      

    $sqlP="SELECT * FROM `".$nomtablePagnetTampon."` where idPagnet=".$idPagnet." ";

    $resP = mysql_query($sqlP) or die ("persoonel requête 2".mysql_error());

    $pagnet = mysql_fetch_assoc($resP) ;



    $sqlL="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet=".$idPagnet." ";

    $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());

    //$ligne = mysql_fetch_assoc($resL) ;

    $idClient=$pagnet['idClient'];

    if($pagnet['type']==0 || $pagnet['type']==30){

        while ($ligne=mysql_fetch_assoc($resL)){

            if($ligne['classe']==0){

                $sqlS="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];

                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                $designation = mysql_fetch_assoc($resS) ;

                    if(mysql_num_rows($resS)){

                        if (($ligne['unitevente']!="Article")&&($ligne['unitevente']!="article")) {

                            $sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ORDER BY idStock DESC ";

                            $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                            $retour=$ligne['quantite']*$designation['nbreArticleUniteStock'];
                            
                            $qtyVendu=$ligne['quantite']*$designation['nbreArticleUniteStock'];

                            while ($stock = mysql_fetch_assoc($resD)) {

                                if($retour>= 0){

                                    $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;

                                    if($stock['totalArticleStock'] >= $quantiteStockCourant){

                                        $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];

                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                    }

                                    else{

                                        $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$stock['totalArticleStock']." where idStock=".$stock['idStock'];

                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                    }

                                    $retour=($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'] ;

                                }

                            }



                        }

                        else if(($ligne['unitevente']=="Article")||($ligne['unitevente']=="article")){

                            $sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ORDER BY idStock DESC ";

                            $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                            $retour=$ligne['quantite'];
                            
                            $qtyVendu=$ligne['quantite'];

                            while ($stock = mysql_fetch_assoc($resD)) {

                                if($retour >= 0){

                                    $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;

                                    if($stock['totalArticleStock'] >= $quantiteStockCourant){

                                        $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];

                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                    }

                                    else{

                                        $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$stock['totalArticleStock']." where idStock=".$stock['idStock'];

                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                    

                                    }

                                    $retour=($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'] ;

                                    

                                }

                                

                            }



                        }                    
                        
                        /************************/
                        
                        $sqlSV="UPDATE `".$nomtableStock."` set quantiteStockTemp=quantiteStockTemp+".$qtyVendu." where idDesignation=".$ligne['idDesignation'];
                        $resSV=mysql_query($sqlSV) or die ("update quantiteStockCourant impossible =>".mysql_error());
                        /************************/

                    }

            }

        }



        // // suppression du pagnet aprés su^ppression de ses lignes

        // $sqlR="UPDATE `".$nomtablePagnetTampon."` set type=2 where idPagnet=".$idPagnet;

        // $resR=@mysql_query($sqlR) or die ("mise à jour client  impossible".mysql_error());



        if ($_SESSION['compte']==1) {

            $description="Retour panier";

            $operation='retrait';

        

            $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte - ".$pagnet['apayerPagnet']." where  idCompte=".$pagnet['idCompte'];

            $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());

            

            if ($pagnet['remise']!='' && $pagnet['remise']!=0) {

                $sql8="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte - ".$pagnet['remise']." where  idCompte=3";

                $res8=@mysql_query($sql8) or die ("mise à jour 2 pour activer ou pas ".mysql_error());

            }

            //Annulation des mouvements relatifs à ce panier

            $sql="DELETE FROM `".$nomtableComptemouvement."` where  mouvementLink=".$idPagnet."";

            $res=@mysql_query($sql) or die ("mise à jour idClient ".mysql_error());



            if ($pagnet['avance'] != 0) {

                

                $sql0="DELETE FROM `".$nomtableVersement."` where  idPagnet=".$idPagnet."";

                $res0=@mysql_query($sql0) or die ("mise à jour idClient ".mysql_error());

            }



            if ($pagnet['idCompte'] == 2) {

                # code...

                /************************************** UPDATE BON Et DU REMISE******************************************/

                $sql18="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnetTampon."` where verrouiller='1' && idClient=".$idClient." && (type=0 || type=30) ";

                $res18 = mysql_query($sql18) or die ("persoonel requête 2".mysql_error());

                $TotalP = mysql_fetch_array($res18) ;



                $sql19="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnetTampon."` where idClient=".$idClient." && type=1 ";

                $res19 = mysql_query($sql19) or die ("persoonel requête 2".mysql_error());

                $TotalR = mysql_fetch_array($res19) ;



                $total=$TotalP[0] - $TotalR[0];

                

                $sql20="UPDATE `".$nomtableBon."` set montant=".$total.", date='".$dateString."' where idClient=".$idClient;

                $res20=mysql_query($sql20) or die ("update Pagnet impossible =>".mysql_error());

                

                $sql3="UPDATE `".$nomtableClient."` set solde=solde-".$pagnet['apayerPagnet']." where idClient=".$idClient;

                $res3=mysql_query($sql3) or die ("update solde client impossible =>".mysql_error());



            }

        }



    }

    $result="1";

  }

  else{

    $result="0"; 

  }

  exit($result);

}

else if($operation == 31){



  $idPagnet=htmlspecialchars(trim($_POST['idPanier']));



  $sql1="UPDATE `".$nomtablePagnetTampon."` set  verrouiller=0 where idPagnet=".$idPagnet;

  $res1=mysql_query($sql1) or die ("Edition panier impossible =>".mysql_error());

        

  if($res1){

     

    $sqlP="SELECT * FROM `".$nomtablePagnetTampon."` where idPagnet=".$idPagnet." ";

    $resP = mysql_query($sqlP) or die ("persoonel requête 2".mysql_error());

    $pagnet = mysql_fetch_assoc($resP) ;



    $sqlL="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet=".$idPagnet." ";

    $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());

    //$ligne = mysql_fetch_assoc($resL) ;



    if($pagnet['type']==0 || $pagnet['type']==30){

        while ($ligne=mysql_fetch_assoc($resL)){

            if($ligne['classe']==0){

                $sqlS="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];

                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                $designation = mysql_fetch_assoc($resS) ;

                    if(mysql_num_rows($resS)){

                        if ($ligne['unitevente']==$designation['uniteStock']) {

                            $sqlD="SELECT * FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."'  AND idEntrepot='".$ligne['idEntrepot']."' ORDER BY idEntrepotStock DESC ";

                            $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                            $retour=$ligne['quantite']*$designation['nbreArticleUniteStock'];

                            while ($stock = mysql_fetch_assoc($resD)) {

                                if($retour>= 0){

                                    $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;

                                    if($stock['totalArticleStock'] >= $quantiteStockCourant){

                                        $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$quantiteStockCourant." where idEntrepotStock=".$stock['idEntrepotStock'];

                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                    }

                                    else{

                                        $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$stock['totalArticleStock']." where idEntrepotStock=".$stock['idEntrepotStock'];

                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                    }

                                    $retour=($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'] ;

                                }

                            }



                        }

                        else if ($ligne['unitevente']=='Demi Gros') {

                            $sqlD="SELECT * FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."'  AND idEntrepot='".$ligne['idEntrepot']."' ORDER BY idEntrepotStock DESC ";

                            $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                            $retour=$ligne['quantite']* ($designation['nbreArticleUniteStock']/2);

                            while ($stock = mysql_fetch_assoc($resD)) {

                                if($retour>= 0){

                                    $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;

                                    if($stock['totalArticleStock'] >= $quantiteStockCourant){

                                        $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$quantiteStockCourant." where idEntrepotStock=".$stock['idEntrepotStock'];

                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                    }

                                    else{

                                        $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$stock['totalArticleStock']." where idEntrepotStock=".$stock['idEntrepotStock'];

                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                    }

                                    $retour=($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'] ;

                                }

                            }



                        }

                        else {

                            $sqlD="SELECT * FROM `".$nomtableEntrepotStock."` where idDesignation='".$ligne['idDesignation']."'  AND idEntrepot='".$ligne['idEntrepot']."' ORDER BY idEntrepotStock DESC ";

                            $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                            $retour=$ligne['quantite'];

                            while ($stock = mysql_fetch_assoc($resD)) {

                                if($retour >= 0){

                                    $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;

                                    if($stock['totalArticleStock'] >= $quantiteStockCourant){

                                        $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$quantiteStockCourant." where idEntrepotStock=".$stock['idEntrepotStock'];

                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                    }

                                    else{

                                        $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$stock['totalArticleStock']." where idEntrepotStock=".$stock['idEntrepotStock'];

                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                    

                                    }

                                    $retour=($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'] ;

                                    

                                }

                                

                            }



                        }

                    }

            }

        }



        // suppression du pagnet aprés su^ppression de ses lignes

        // $sqlR="UPDATE `".$nomtablePagnetTampon."` set type=2 where idPagnet=".$idPagnet;

        // $resR=@mysql_query($sqlR) or die ("mise à jour client  impossible".mysql_error());



        

        if ($_SESSION['compte']==1) {

            $description="Retour panier";

            $operation='retrait';

        

            $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte - ".$pagnet['apayerPagnet']." where  idCompte=".$pagnet['idCompte'];

            $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());

            

            if ($pagnet['remise']!='' && $pagnet['remise']!=0) {

                $sql8="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte - ".$pagnet['remise']." where  idCompte=3";

                $res8=@mysql_query($sql8) or die ("mise à jour 2 pour activer ou pas ".mysql_error());

            }

            //Annulation des mouvements relatifs à ce panier

            $sql="DELETE FROM `".$nomtableComptemouvement."` where  mouvementLink=".$idPagnet."";

            $res=@mysql_query($sql) or die ("mise à jour idClient ".mysql_error());



            if ($pagnet['avance'] != 0) {

                

                $sql0="DELETE FROM `".$nomtableVersement."` where  idPagnet=".$idPagnet."";

                $res0=@mysql_query($sql0) or die ("mise à jour idClient ".mysql_error());

            }



            if ($pagnet['idCompte'] == 2) {

                # code...

                /************************************** UPDATE BON Et DU REMISE******************************************/

                $sql18="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnetTampon."` where verrouiller='1' && idClient=".$pagnet['idClient']." && (type=0 || type=30) ";

                $res18 = mysql_query($sql18) or die ("persoonel requête 2".mysql_error());

                $TotalP = mysql_fetch_array($res18) ;



                $sql19="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnetTampon."` where idClient=".$pagnet['idClient']." && type=1 ";

                $res19 = mysql_query($sql19) or die ("persoonel requête 2".mysql_error());

                $TotalR = mysql_fetch_array($res19) ;



                $total=$TotalP[0] - $TotalR[0];

                

                $sql20="UPDATE `".$nomtableBon."` set montant=".$total.", date='".$dateString."' where idClient=".$pagnet['idClient'];

                $res20=mysql_query($sql20) or die ("update Pagnet impossible =>".mysql_error());

                

                $sql3="UPDATE `".$nomtableClient."` set solde=solde-".$pagnet['apayerPagnet']." where idClient=".$pagnet['idClient'];

                $res3=mysql_query($sql3) or die ("update solde client impossible =>".mysql_error());



            }

        }



      $result="1";

    }



  }

  else{

    $result="0"; 

  }

  exit($result);

}



else if($operation == 32){



  $idPagnet=htmlspecialchars(trim($_POST['idPanier']));



  $sql1="UPDATE `".$nomtablePagnetTampon."` set  verrouiller=0 where idPagnet=".$idPagnet;

  $res1=mysql_query($sql1) or die ("Edition panier impossible =>".mysql_error());

        

  if($res1){



    $sqlP="SELECT * FROM `".$nomtablePagnetTampon."` where idPagnet=".$idPagnet." ";

    $resP = mysql_query($sqlP) or die ("persoonel requête 2".mysql_error());

    $pagnet = mysql_fetch_assoc($resP) ;



    if($pagnet['type']==0 || $pagnet['type']==30){

        $sqlL="SELECT * FROM `".$nomtableLigneTampon."` WHERE idPagnet='".$idPagnet."' ";

        $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());

        mysql_query("SET AUTOCOMMIT=0;");

        mysql_query("START TRANSACTION;");

        $i=0;

        $j=0;

        while ($ligne=mysql_fetch_assoc($resL)){

            if($ligne['classe']==0){

                $sqlS="SELECT * FROM `".$nomtableDesignation."` WHERE idDesignation='".$ligne['idDesignation']."' ";

                $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

                $designation = mysql_fetch_assoc($resS) ;

                    if(mysql_num_rows($resS)){

                        $sqlD="SELECT * FROM `".$nomtableStock."` WHERE idDesignation='".$ligne['idDesignation']."' ORDER BY idStock DESC ";

                        $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

                        $retour=$ligne['quantite'];

                        $k=0;

                        $l=0;

                        while ($stock = mysql_fetch_assoc($resD)) {

                            if($retour >= 0){

                                $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;

                                if($stock['quantiteStockinitial'] >= $quantiteStockCourant){

                                    $sqlS="UPDATE `".$nomtableStock."` SET quantiteStockCourant='".$quantiteStockCourant."' WHERE idStock='".$stock['idStock']."' ";

                                    $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                    if($resS){

                                        $k=$k + 1;

                                    }

                                }

                                else{

                                    $sqlS="UPDATE `".$nomtableStock."` SET quantiteStockCourant='".$stock['quantiteStockinitial']."' WHERE idStock='".$stock['idStock']."' ";

                                    $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                    if($resS){

                                        $k=$k + 1;

                                    }

                                    

                                }

                                $retour=($retour + $stock['quantiteStockCourant']) - $stock['quantiteStockinitial'] ;

                                $l=$l + 1;

                            }

                        }

                        if($k==$l){

                            $i=$i + 1;

                        }

                    }

            }

            if($ligne['classe']==1 ||  $ligne['classe']==2 || $ligne['classe']==5 || $ligne['classe']==7){

                $i=$i + 1;

            }

            $j=$j + 1;

        }



        // suppression du pagnet aprés su^ppression de ses lignes

        // $sqlRP="UPDATE `".$nomtablePagnetTampon."` set type=2 where idPagnet=".$idPagnet;

        // $resRP=@mysql_query($sqlRP) or die ("mise à jour client  impossible".mysql_error());



        if ($_SESSION['compte']==1) {

            $description="Retour panier";

            $operation='retrait';

        

            $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte - ".$pagnet['apayerPagnet']." where  idCompte=".$pagnet['idCompte'];

            $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());

            

            if ($pagnet['remise']!='' && $pagnet['remise']!=0) {

                $sql8="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte - ".$pagnet['remise']." where  idCompte=3";

                $res8=@mysql_query($sql8) or die ("mise à jour 2 pour activer ou pas ".mysql_error());

            }

            //Annulation des mouvements relatifs à ce panier

            $sql="DELETE FROM `".$nomtableComptemouvement."` where  mouvementLink=".$idPagnet."";

            $res=@mysql_query($sql) or die ("mise à jour idClient ".mysql_error());



            if ($pagnet['avance'] != 0) {

                

                $sql0="DELETE FROM `".$nomtableVersement."` where  idPagnet=".$idPagnet."";

                $res0=@mysql_query($sql0) or die ("mise à jour idClient ".mysql_error());

            }



            if ($pagnet['idCompte'] == 2) {

                # code...

                /************************************** UPDATE BON Et DU REMISE******************************************/

                $sql18="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnetTampon."` where verrouiller='1' && idClient=".$pagnet['idClient']." && (type=0 || type=30) ";

                $res18 = mysql_query($sql18) or die ("persoonel requête 2".mysql_error());

                $TotalP = mysql_fetch_array($res18) ;



                $sql19="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnetTampon."` where idClient=".$pagnet['idClient']." && type=1 ";

                $res19 = mysql_query($sql19) or die ("persoonel requête 2".mysql_error());

                $TotalR = mysql_fetch_array($res19) ;



                $total=$TotalP[0] - $TotalR[0];

                

                $sql20="UPDATE `".$nomtableBon."` set montant=".$total.", date='".$dateString."' where idClient=".$pagnet['idClient'];

                $res20=mysql_query($sql20) or die ("update Pagnet impossible =>".mysql_error());

                

                $sql3="UPDATE `".$nomtableClient."` set solde=solde-".$pagnet['apayerPagnet']." where idClient=".$pagnet['idClient'];

                $res3=mysql_query($sql3) or die ("update solde client impossible =>".mysql_error());



            }

        }



        if($i==$j){

            mysql_query("COMMIT;");

        }

        else{

            mysql_query("ROLLBACK;");

            $msg_info="<p>PROBLEME CONNECTION INTERNET .</br></br> Veuillez retourner le panier numéro ".$idPagnet.".</p>";

        }



      $result="1";

    }



  }

  else{

    $result="0"; 

  }

  exit($result);

  }

  else if($operation == 33){

  

    $sql1="UPDATE `aaa-boutique` set showInfoChange=1 where idBoutique=".$_SESSION['idBoutique'];

    $res1=mysql_query($sql1) or die ("Edition panier impossible =>".mysql_error());

          

    if($res1){

      $result="1"; 

    }

    else{

      $result="0"; 

    }

    exit($result);

    }

   else if($operation == 36){
      //$reponse+=sizeof($codeBrute);
      $reponse="<ul><li>aucune donnee trouvé</li></ul>";
      $query=htmlspecialchars(trim($_POST['query']));
      $reqS="SELECT * from `aaa-catalogue-Superette-Detaillant` where (designation LIKE '%$query%') OR (codeBarreDesignation LIKE '%$query%')  ";
      $resS=mysql_query($reqS) or die ("insertion impossible");
    
      if($resS){
        $reponse="<ul class='ulc'>";
          while ($data=mysql_fetch_array($resS)) {
            $reponse.="<li class='liBt'  style='background-color: #abdbb7; '>".$data['designation']." </li>";   
          }
        $reponse.="</ul>";
      }
      exit($reponse);
    }
    if($operation == 37){
      $designation=@htmlspecialchars($_POST["designation"]);
      $sql3='SELECT * from  `aaa-catalogue-Superette-Detaillant` where designation="'.$designation.'" ';
      $res3=mysql_query($sql3);
      if($design=mysql_fetch_array($res3)){
        $result='1<>'.$design['idFusion'].'<>'.$design['designation'].'<>'.$design['categorie'].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$design['prix'].'<>'.$design['prixuniteStock'].'<>'.$design['codeBarreDesignation'];
      }
      else{
        $result="0";
      }
      exit($result);
    }
    else if($operation == 38){



      $idMutuellePagnet=htmlspecialchars(trim($_POST['idMutuellePanier']));
    
    
    
      $sql1="UPDATE `".$nomtableMutuellePagnet."` set verrouiller=0 where idMutuellePagnet=".$idMutuellePagnet;
    
      $res1=mysql_query($sql1) or die ("Edition panier impossible =>".mysql_error());
    
      $result="0";
    
      if($res1){
    
        $sqlP="SELECT * FROM `".$nomtableMutuellePagnet."` where idMutuellePagnet=".$idMutuellePagnet." ";
    
        $resP = mysql_query($sqlP) or die ("persoonel requête 2".mysql_error());
    
        $mutuelle = mysql_fetch_assoc($resP) ;
    
        if($mutuelle['type']==0 || $mutuelle['type']==30){
    
            $sqlL="SELECT * FROM `".$nomtableLigneTampon."` WHERE idMutuellePagnet='".$idMutuellePagnet."' ";
    
            $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());
    
            mysql_query("SET AUTOCOMMIT=0;");
    
            mysql_query("START TRANSACTION;");
    
            $i=0;
    
            $j=0;
    
            while ($ligne=mysql_fetch_assoc($resL)){
    
                if($ligne['classe']==0){
    
                    $sqlS="SELECT * FROM `".$nomtableDesignation."` WHERE idDesignation='".$ligne['idDesignation']."' ";
    
                    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    
                    $designation = mysql_fetch_assoc($resS) ;
    
                        if(mysql_num_rows($resS)){
    
                            $sqlD="SELECT * FROM `".$nomtableStock."` WHERE idDesignation='".$ligne['idDesignation']."' ORDER BY idStock DESC ";
    
                            $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
    
                            $retour=$ligne['quantite'];
    
                            $k=0;
    
                            $l=0;
    
                            while ($stock = mysql_fetch_assoc($resD)) {
    
                                if($retour >= 0){
    
                                    $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;
    
                                    if($stock['quantiteStockinitial'] >= $quantiteStockCourant){
    
                                        $sqlS="UPDATE `".$nomtableStock."` SET quantiteStockCourant='".$quantiteStockCourant."' WHERE idStock='".$stock['idStock']."' ";
    
                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
    
                                        if($resS){
    
                                            $k=$k + 1;
    
                                        }
    
                                    }
    
                                    else{
    
                                        $sqlS="UPDATE `".$nomtableStock."` SET quantiteStockCourant='".$stock['quantiteStockinitial']."' WHERE idStock='".$stock['idStock']."' ";
    
                                        $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
    
                                        if($resS){
    
                                            $k=$k + 1;
    
                                        }
    
                                        
    
                                    }
    
                                    $retour=($retour + $stock['quantiteStockCourant']) - $stock['quantiteStockinitial'] ;
    
                                    $l=$l + 1;
    
                                }
    
                            }
    
                            if($k==$l){
    
                                $i=$i + 1;
    
                            }
    
                        }
    
                }
    
                if($ligne['classe']==1 ||  $ligne['classe']==2 || $ligne['classe']==5 || $ligne['classe']==7){
    
                    $i=$i + 1;
    
                }
    
                $j=$j + 1;
    
            }
        
            // suppression du pagnet aprés su^ppression de ses lignes
    
            // $sqlRP="UPDATE `".$nomtablePagnetTampon."` set type=2 where idPagnet=".$idPagnet;
    
            // $resRP=@mysql_query($sqlRP) or die ("mise à jour client  impossible".mysql_error());
    
            
            if ($_SESSION['compte']==1) {
    
                $description="Retour panier imputation";
    
                $operation='retrait';
    
            
    
                $sql7="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte - ".$mutuelle['apayerPagnet']." where  idCompte=".$mutuelle['idCompte'];
    
                $res7=@mysql_query($sql7) or die ("mise à jour 2 pour activer ou pas ".mysql_error());
    
                
    
                if ($mutuelle['remise']!='' && $mutuelle['remise']!=0) {
    
                    $sql8="UPDATE `".$nomtableCompte."` set  montantCompte= montantCompte - ".$mutuelle['remise']." where  idCompte=3";
    
                    $res8=@mysql_query($sql8) or die ("mise à jour 2 pour activer ou pas ".mysql_error());
    
                }
    
                //Annulation des mouvements relatifs à ce panier
    
                $sql="DELETE FROM `".$nomtableComptemouvement."` where  idMutuellePagnet=".$idMutuellePagnet."";
    
                $res=@mysql_query($sql) or die ("mise à jour idClient ".mysql_error());
    
    
    
                if ($mutuelle['avance'] != 0) {
    
                    
    
                    $sql0="DELETE FROM `".$nomtableVersement."` where  idMutuellePagnet=".$idMutuellePagnet."";
    
                    $res0=@mysql_query($sql0) or die ("mise à jour idClient ".mysql_error());
    
                }
    
    
    
                if ($mutuelle['idCompte'] == 2) {
    
                    # code...
    
                    /************************************** UPDATE BON Et DU REMISE******************************************/
    
                    $sql18="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnetTampon."` where verrouiller='1' && idClient=".$mutuelle['idClient']." && (type=0 || type=30) ";
    
                    $res18 = mysql_query($sql18) or die ("persoonel requête 2".mysql_error());
    
                    $TotalP = mysql_fetch_array($res18) ;
    
    
    
                    $sql19="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnetTampon."` where idClient=".$idClient." && type=1 ";
    
                    $res19 = mysql_query($sql19) or die ("persoonel requête 2".mysql_error());
    
                    $TotalR = mysql_fetch_array($res19) ;
    
    
    
                    $total=$TotalP[0] - $TotalR[0];
    
                    
    
                    $sql20="UPDATE `".$nomtableBon."` set montant=".$total.", date='".$dateString."' where idClient=".$mutuelle['idClient'];
    
                    $res20=mysql_query($sql20) or die ("update Pagnet impossible =>".mysql_error());
    
                    
    
                    $sql3="UPDATE `".$nomtableClient."` set solde=solde-".$mutuelle['apayerPagnet']." where idClient=".$mutuelle['idClient'];
    
                    $res3=mysql_query($sql3) or die ("update solde client impossible =>".mysql_error());
    
    
    
                }
    
            }
    
    
    
            if($i==$j){
    
                mysql_query("COMMIT;");
    
            }
    
            else{
    
                mysql_query("ROLLBACK;");
    
                $msg_info="<p>PROBLEME CONNECTION INTERNET .</br></br> Veuillez retourner le panier numéro ".$idMutuellePagnet.".</p>";
    
            }
    
    
    
          $result="1";
    
        }
    
      }
    
      else{
    
        $result="0"; 
    
      }
    
      exit($result);
    
    }
    
else if($operation == 39){

  $clients = [];

  $query=htmlspecialchars(trim($_POST['query']));

  

  $sql3="SELECT * FROM `".$nomtableClient."` where (prenom LIKE '%$query%' or nom LIKE '%$query%' or adresse LIKE '%$query%') and archiver=0 and activer=1 and avoir=0 ORDER BY idClient ASC";

  $res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());



  while($client = mysql_fetch_assoc($res3)){

      $clients[] = $client['idClient']." -- ".$client['prenom']." ".$client['nom']." -- ".$client['adresse'];

  }

  

  echo json_encode($clients);

}

else if($operation == 40){

  // $idPanier=htmlspecialchars(trim($_POST['idPanier']));

  $nomClient=htmlspecialchars(trim($_POST['nomClient']));

  $idClient=htmlspecialchars(trim($_POST['idClient']));

  $idPanier=htmlspecialchars(trim($_POST['idPanier']));

  
        # code...
    $sql3="UPDATE `".$nomtablePagnetTampon."` SET nomClient='".$nomClient."', idClient='".$idClient."' where idPagnet=".$idPanier;
    // var_dump($sql3);

    $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible 1--- ".mysql_error());

    echo (1);
} 
else if($operation == 41) {
	$idPanierPro = $_POST['idPanier'];

	$sql0="SELECT * FROM `".$nomtablePagnetTampon."` where idPagnet=".$idPanierPro." ";
	$res0 = mysql_query($sql0) or die ("persoonel requête 2".mysql_error());
	$pagnet = mysql_fetch_assoc($res0) ;
  $idPagnet = $pagnet['idPagnet'];
  $totalp = $pagnet['totalp'];
  $idClient = $pagnet['idClient'];

	$sql01="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet=".$idPanierPro." and quantite>0 ";
	$res01 = mysql_query($sql01) or die ("persoonel requête 2".mysql_error());

	$date = date("d-m-Y");
	$heure = date("H:i:s");

	$insertNewPanier = "INSERT INTO `".$nomtablePagnetTampon."`(datepagej, type, idClient, heurePagnet, totalp, apayerPagnet, verrouiller, iduser) values ('".$date."',0,".$idClient.",'".$heure."',".$totalp.",".$totalp.",1,".$_SESSION['iduser'].")";
	$result1 = @mysql_query($insertNewPanier) or die ("insertion new panier impossible".mysql_error()) ;

	$sql1="SELECT * FROM `".$nomtablePagnetTampon."` ORDER BY idPagnet DESC LIMIT 1";
	$res1 = mysql_query($sql1) or die ("persoonel requête 2".mysql_error());
	$pagnet = mysql_fetch_assoc($res1);
  $idPagnet = $pagnet['idPagnet'];

	while ($key=mysql_fetch_assoc($res01)) {
    
    $sql02="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$key['idDesignation'];
    $res02 = mysql_query($sql02) or die ("persoonel requête 2".mysql_error());
    $designation = mysql_fetch_assoc($res02) ;

		$insertNewLignes = "INSERT INTO `".$nomtableLigneTampon."`(idPagnet,idDesignation,idEntrepot, designation, unitevente, prixunitevente, quantite, prixtotal, classe) values (".$idPagnet.",".$key['idDesignation'].",".$key['idEntrepot'].",'".$key['designation']."','".$key['unitevente']."',".$key['prixunitevente'].",".$key['quantite'].",".$key['prixtotal'].", 0)";
		$result2 = @mysql_query($insertNewLignes) or die ("insertion new ligne impossible".mysql_error()) ;

    if ($key['unitevente']==$designation['uniteStock']) {

      $sqlD="SELECT * FROM `".$nomtableEntrepotStock."` where idDesignation='".$key['idDesignation']."' AND idEntrepot='".$key['idEntrepot']."' ORDER BY idEntrepotStock ASC ";

      $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

      $restant=$key['quantite']*$designation['nbreArticleUniteStock'];

      while ($stock = mysql_fetch_assoc($resD)) {

          if($restant>= 0) {

              $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;

              if($quantiteStockCourant > 0) {

                  $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$quantiteStockCourant." where idEntrepotStock=".$stock['idEntrepotStock'];

                  $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

              }

              else {

                  $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=0 where idEntrepotStock=".$stock['idEntrepotStock'];

                  $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

              }

              $restant= $restant - $stock['quantiteStockCourant'] ;

          }

      }



    }

    else if ($key['unitevente']=='Demi Gros') {

      $sqlD="SELECT * FROM `".$nomtableEntrepotStock."` where idDesignation='".$key['idDesignation']."' AND idEntrepot='".$key['idEntrepot']."' ORDER BY idEntrepotStock ASC ";

      $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

      $restant=$key['quantite']*($designation['nbreArticleUniteStock']/2);

      while ($stock = mysql_fetch_assoc($resD)) {

          if($restant>= 0){

              $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;

              if($quantiteStockCourant > 0){

                  $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$quantiteStockCourant." where idEntrepotStock=".$stock['idEntrepotStock'];

                  $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

              }

              else{

                  $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=0 where idEntrepotStock=".$stock['idEntrepotStock'];

                  $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

              }

              $restant= $restant - $stock['quantiteStockCourant'] ;

          }

      }

    }

    else {

      $sqlD="SELECT * FROM `".$nomtableEntrepotStock."` where idDesignation='".$key['idDesignation']."' AND idEntrepot='".$key['idEntrepot']."' ORDER BY idEntrepotStock ASC ";

      $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());

      $restant=$key['quantite'];

      while ($stock = mysql_fetch_assoc($resD)) {

          if ($restant>= 0) {

              $quantiteStockCourant = $stock['quantiteStockCourant'] - $restant;

              if($quantiteStockCourant > 0){

                  $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=".$quantiteStockCourant." where idEntrepotStock=".$stock['idEntrepotStock'];

                  $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

              }

              else{

                  $sqlS="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=0 where idEntrepotStock=".$stock['idEntrepotStock'];

                  $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

              }

              $restant= $restant - $stock['quantiteStockCourant'] ;

          }

      }

    }

	}
  
  $sql4="UPDATE `".$nomtablePagnetTampon."` SET validerProforma=1 where idPagnet=".$idPanierPro;

  $res4=@mysql_query($sql4) or die ("mise à jour verouillage  impossible 1--- ".mysql_error());

    exit($_SESSION['venterapideEt']);
}
else if($operation == 42){

  $clients = [];

  $query=htmlspecialchars(trim($_POST['query']));

  $tab=[];
  

  $sql3="SELECT * FROM `".$nomtablePagnetTampon."` where type=10 and verrouiller=1 and  (nomClient LIKE '%$query%') ORDER BY nomClient";

  $res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());



  while($client = mysql_fetch_assoc($res3)){
      if (in_array($client['nomClient'], $tab)) {
       
      } else {
      
        $clients[] = $client['nomClient'];
        $tab[] = $client['nomClient'];
      }

  }
  echo json_encode($clients);
}


 ?>