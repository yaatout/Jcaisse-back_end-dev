<?php 
	session_start();
	if(!$_SESSION['iduser']){
		header('Location:../index.php');
	}
	
require('../connection.php');

require('../declarationVariables.php');

$reponse="produit non enregistrer";

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
  //$reponse+=sizeof($codeBrute);
  // $designation=@htmlspecialchars(utf8_decode($_POST["designation"]));
  $designation=@$_POST["designation"];
  $idPagnet=@htmlspecialchars($_POST["idPagnet"]);
  $result="0";
  // $result=$designation." / ".$idPagnet;

  $sql0="SELECT * FROM `".$nomtablePagnet."` where idPagnet=".$idPagnet." ";
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
                  $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";
                  $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
                  $ligne1 = mysql_fetch_assoc($res);
                  /***Debut verifier si la ligne du produit existe deja ***/
                  if($ligne1 != null){
                      /***Debut verifier si le produit existe deja ***/
                      $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."'  and  unitevente!='Article' and  unitevente!='article' and classe=0 ";
                      $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
                      $ligne2 = mysql_fetch_assoc($res);
                      /***Debut verifier si le produit existe deja ***/
                      $quantite = $ligne1['quantite'] + 1;
                      $prixTotal=$quantite*$ligne1['prixunitevente'];
                      if($_SESSION['Pays']=='Canada' && $design['tva']==1){
                        $prixTotalTvaP=$prixTotal*$_SESSION['tvaP'];
                        $prixTotalTvaR=$prixTotal*$_SESSION['tvaR'];
                        $sql7="UPDATE `".$nomtableLigne."` set quantite='".$quantite."',prixtotal=".$prixTotal.",prixtotalTvaP=".$prixTotalTvaP.",prixtotalTvaR=".$prixTotalTvaR." where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";
                        $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error()); 
                      }
                      else{
                        $sql7="UPDATE `".$nomtableLigne."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";
                        $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error()); 
                      }

                      $sql2="SELECT * from  `".$nomtableLigne."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
                      $res2=mysql_query($sql2);
                      if(mysql_num_rows($res2)){
                        $ligne=mysql_fetch_assoc($res2);
                        $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
                        $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
                        $TotalT = mysql_fetch_array($resT) ;

                        if($_SESSION['Pays']=='Canada'){ 
                          $sqlTP="SELECT SUM(prixtotalTvaP) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
                          $resTP = mysql_query($sqlTP) or die ("persoonel requête 2".mysql_error());
                          $TotalTvaP = mysql_fetch_array($resTP) ;

                          $sqlTR="SELECT SUM(prixtotalTvaR) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
                          $resTR = mysql_query($sqlTR) or die ("persoonel requête 2".mysql_error());
                          $TotalTvaR = mysql_fetch_array($resTR) ;

                          $totalPrix=$TotalT[0]+$TotalTvaP[0]+$TotalTvaR[0];
                        }
                        else{
                          $totalPrix=$TotalT[0];
                        }
                        

                        $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' AND quantiteStockCourant!=0 ";
                        $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
                        $t_stock = mysql_fetch_array($res_t) ;
                        $result='2<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$quantite.'<>'.$totalPrix;
                      }               
                  }
                  else {
                      $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
                      $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
                      $ligne = mysql_fetch_assoc($res) ;
                      if(mysql_num_rows($res)){
                          if($ligne['classe']==0 || $ligne['classe']==1){
                              if($_SESSION['Pays']=='Canada' && $design['tva']==1){ 
                                $prixTotalTvaP=$design['prix']*$_SESSION['tvaP'];
                                $prixTotalTvaR=$design['prix']*$_SESSION['tvaR'];
                                $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,prixtotalTvaP,prixtotalTvaR,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$prixTotalTvaP.",".$prixTotalTvaR.",".$idPagnet.",0)";
                                $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                              }
                              else{
                                $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",0)";
                                $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() ); 
                              }
  
                              $sql2="SELECT * from  `".$nomtableLigne."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
                              $res2=mysql_query($sql2);
                              if(mysql_num_rows($res2)){
                                $ligne=mysql_fetch_assoc($res2);
                                $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
                                $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
                                $TotalT = mysql_fetch_array($resT) ;
  
                                if($_SESSION['Pays']=='Canada'){ 
                                  $sqlTP="SELECT SUM(prixtotalTvaP) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
                                  $resTP = mysql_query($sqlTP) or die ("persoonel requête 2".mysql_error());
                                  $TotalTvaP = mysql_fetch_array($resTP) ;
        
                                  $sqlTR="SELECT SUM(prixtotalTvaR) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
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
                          $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,prixtotalTvaP,prixtotalTvaR,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$prixTotalTvaP.",".$prixTotalTvaR.",".$idPagnet.",0)";
                          $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                        }
                        else{
                          $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",0)";
                          $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                        }
  
                          $sql2="SELECT * from  `".$nomtableLigne."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
                              $res2=mysql_query($sql2);
                              if(mysql_num_rows($res2)){
                                $ligne=mysql_fetch_assoc($res2);
                                $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
                                $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
                                $TotalT = mysql_fetch_array($resT) ;
  
                                if($_SESSION['Pays']=='Canada'){ 
                                  $sqlTP="SELECT SUM(prixtotalTvaP) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
                                  $resTP = mysql_query($sqlTP) or die ("persoonel requête 2".mysql_error());
                                  $TotalTvaP = mysql_fetch_array($resTP) ;
        
                                  $sqlTR="SELECT SUM(prixtotalTvaR) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
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
                  $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";
                  $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
                  $ligne1 = mysql_fetch_assoc($res);
                  /***Debut verifier si la ligne du produit existe deja ***/
                  if($ligne1 != null){
                      /***Debut verifier si le produit existe deja ***/
                      $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."'  and  unitevente!='Article' and  unitevente!='article' and classe=0 ";
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
                            $sql7="UPDATE `".$nomtableLigne."` set quantite='".$quantite."',prixtotal=".$prixTotal.",prixtotalTvaP=".$prixTotalTvaP.",prixtotalTvaR=".$prixTotalTvaR." where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";
                            $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error()); 
                          }
                          else{
                            $sql7="UPDATE `".$nomtableLigne."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";
                            $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error()); 
                          }
  
                          $sql2="SELECT * from  `".$nomtableLigne."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
                          $res2=mysql_query($sql2);
                          if(mysql_num_rows($res2)){
                            $ligne=mysql_fetch_assoc($res2);
                            $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
                            $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
                            $TotalT = mysql_fetch_array($resT) ;
  
                            if($_SESSION['Pays']=='Canada'){ 
                              $sqlTP="SELECT SUM(prixtotalTvaP) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
                              $resTP = mysql_query($sqlTP) or die ("persoonel requête 2".mysql_error());
                              $TotalTvaP = mysql_fetch_array($resTP) ;
    
                              $sqlTR="SELECT SUM(prixtotalTvaR) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
                              $resTR = mysql_query($sqlTR) or die ("persoonel requête 2".mysql_error());
                              $TotalTvaR = mysql_fetch_array($resTR) ;
    
                              $totalPrix=$TotalT[0]+$TotalTvaP[0]+$TotalTvaR[0];
                            }
                            else{
                              $totalPrix=$TotalT[0];
                            }
                            
  
                            $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' AND quantiteStockCourant!=0 ";
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
                      $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
                      $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
                      $ligne = mysql_fetch_assoc($res) ;
                      if(mysql_num_rows($res)){
                          if($ligne['classe']==0 || $ligne['classe']==1){
                              if($_SESSION['Pays']=='Canada' && $design['tva']==1){ 
                                $prixTotalTvaP=$design['prix']*$_SESSION['tvaP'];
                                $prixTotalTvaR=$design['prix']*$_SESSION['tvaR'];
                                $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,prixtotalTvaP,prixtotalTvaR,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$prixTotalTvaP.",".$prixTotalTvaR.",".$idPagnet.",0)";
                                $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                              }
                              else{
                                $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",0)";
                                $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() ); 
                              }
  
                              $sql2="SELECT * from  `".$nomtableLigne."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
                              $res2=mysql_query($sql2);
                              if(mysql_num_rows($res2)){
                                $ligne=mysql_fetch_assoc($res2);
                                $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
                                $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
                                $TotalT = mysql_fetch_array($resT) ;
  
                                if($_SESSION['Pays']=='Canada'){ 
                                  $sqlTP="SELECT SUM(prixtotalTvaP) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
                                  $resTP = mysql_query($sqlTP) or die ("persoonel requête 2".mysql_error());
                                  $TotalTvaP = mysql_fetch_array($resTP) ;
        
                                  $sqlTR="SELECT SUM(prixtotalTvaR) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
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
                          $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,prixtotalTvaP,prixtotalTvaR,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$prixTotalTvaP.",".$prixTotalTvaR.",".$idPagnet.",0)";
                          $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                        }
                        else{
                          $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",0)";
                          $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                        }
  
                          $sql2="SELECT * from  `".$nomtableLigne."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
                              $res2=mysql_query($sql2);
                              if(mysql_num_rows($res2)){
                                $ligne=mysql_fetch_assoc($res2);
                                $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
                                $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
                                $TotalT = mysql_fetch_array($resT) ;
  
                                if($_SESSION['Pays']=='Canada'){ 
                                  $sqlTP="SELECT SUM(prixtotalTvaP) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
                                  $resTP = mysql_query($sqlTP) or die ("persoonel requête 2".mysql_error());
                                  $TotalTvaP = mysql_fetch_array($resTP) ;
        
                                  $sqlTR="SELECT SUM(prixtotalTvaR) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
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
            $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,prixtotalTvaP,prixtotalTvaR,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$prixTotalTvaP.",".$prixTotalTvaR.",".$idPagnet.",0)";
            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
          }
          else{
          $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",0)";
          $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
          }
          if($res7){
            $sql2="SELECT * from  `".$nomtableLigne."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
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
            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and designation='".$design['designation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";
            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
            $ligne1 = mysql_fetch_assoc($res);
            /***Debut verifier si la ligne du produit existe deja ***/
            if($ligne1 != null){
                $quantite = $ligne1['quantite'] + 1;
                $prixTotal=$quantite*$ligne1['prixunitevente'];
                $sql7="UPDATE `".$nomtableLigne."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and (unitevente='Article' or  unitevente='article') and classe=0 ";
                $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());
            }
            else {
                $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
                $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
                $ligne = mysql_fetch_assoc($res) ;
                if(mysql_num_rows($res)){
                    if($ligne['classe']==0){
                        $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",0)";
                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() ); 
                    }
                }
                else{
                    $sql7="insert into `".$nomtableLigne."` (designation, idStock, idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',0,'".$design['idDesignation']."','article',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",0)";
                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );
                }

            }
        }
    }
    else if($design['classe']==1 && ($pagnet['type']==0 || $pagnet['type']==10 || $pagnet['type']==30)){
        $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
        $ligne = mysql_fetch_assoc($res) ;
        if(mysql_num_rows($res)){
            /***Debut verifier si la ligne du produit existe deja ***/
            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=1  ";
            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
            $ligne1 = mysql_fetch_assoc($res);
            /***Debut verifier si la ligne du produit existe deja ***/
            if($ligne1 != null){
                $quantite = $ligne1['quantite'] + 1;
                $prixTotal=$quantite*$ligne1['prixunitevente'];
                $sql7="UPDATE `".$nomtableLigne."` set quantite='".$quantite."',prixtotal=".$prixTotal." where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=1  ";
                $res7=mysql_query($sql7) or die ("update quantiteStockCourant impossible =>".mysql_error());

                $sql2="SELECT * from  `".$nomtableLigne."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
                $res2=mysql_query($sql2);
                if(mysql_num_rows($res2)){
                  $ligne=mysql_fetch_assoc($res2);
                  $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
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
                        $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."','".$design['idDesignation']."','".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",1)";
                        $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                        $sql2="SELECT * from  `".$nomtableLigne."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
                        $res2=mysql_query($sql2);
                        if(mysql_num_rows($res2)){
                          $ligne=mysql_fetch_assoc($res2);
                          $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
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
                      $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."','".$design['idDesignation']."','".$design['idDesignation']."','".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",1)";
                      $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                      $sql2="SELECT * from  `".$nomtableLigne."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
                      $res2=mysql_query($sql2);
                      if(mysql_num_rows($res2)){
                        $ligne=mysql_fetch_assoc($res2);
                        $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
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
                
                $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",1)";
                $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                $sql2="SELECT * from  `".$nomtableLigne."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
                $res2=mysql_query($sql2);
                if(mysql_num_rows($res2)){
                  $ligne=mysql_fetch_assoc($res2);
                  $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
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
    else if($design['classe']==2 && $pagnet['type']==0 ){
        $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
        $ligne = mysql_fetch_assoc($res) ;
        if(mysql_num_rows($res)){
            /***Debut verifier si la ligne du produit existe deja ***/
            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and idStock='".$design['idDesignation']."' and classe=2  ";
            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
            $ligne1 = mysql_fetch_assoc($res);
            /***Debut verifier si la ligne du produit existe deja ***/
            if($ligne1 != null){
               
            }
            else {
                if($ligne['classe']==2){
                    $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",2)";
                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                    $sql2="SELECT * from  `".$nomtableLigne."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
                    $res2=mysql_query($sql2);
                    if(mysql_num_rows($res2)){
                      $ligne=mysql_fetch_assoc($res2);
                      $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
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
            $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",2)";
            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

            $sql2="SELECT * from  `".$nomtableLigne."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
            $res2=mysql_query($sql2);
            if(mysql_num_rows($res2)){
              $ligne=mysql_fetch_assoc($res2);
              $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
              $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
              $TotalT = mysql_fetch_array($resT) ;

              $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";
              $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
              $t_stock = mysql_fetch_array($res_t) ;
              $result='7<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0].'<>'.$TotalT[0];
            }
        }
    }
    else if($design['classe']==5 && $pagnet['type']==0){
        $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
        $ligne = mysql_fetch_assoc($res) ;
        if(mysql_num_rows($res)){
            /***Debut verifier si la ligne du produit existe deja ***/
            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=5  ";
            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
            $ligne1 = mysql_fetch_assoc($res);
            /***Debut verifier si la ligne du produit existe deja ***/
            if($ligne1 != null){
            }
            else {
                if($ligne['classe']==5){
                    $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",5)";
                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                    $sql2="SELECT * from  `".$nomtableLigne."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
                    $res2=mysql_query($sql2);
                    if(mysql_num_rows($res2)){
                      $ligne=mysql_fetch_assoc($res2);
                      $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
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
            $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",5)";
            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

            $sql2="SELECT * from  `".$nomtableLigne."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
            $res2=mysql_query($sql2);
            if(mysql_num_rows($res2)){
              $ligne=mysql_fetch_assoc($res2);
              $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
              $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
              $TotalT = mysql_fetch_array($resT) ;

              $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";
              $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
              $t_stock = mysql_fetch_array($res_t) ;
              $result='5<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0];
            }
        }
    }
    else if($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1){
      if($design['classe']==6 && $pagnet['type']==0){
        $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
        $ligne = mysql_fetch_assoc($res) ;
        if(mysql_num_rows($res)){
            /***Debut verifier si la ligne du produit existe deja ***/
            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=7  ";
            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
            $ligne1 = mysql_fetch_assoc($res);
            /***Debut verifier si la ligne du produit existe deja ***/
            if($ligne1 != null){
            }
            else {
                if($ligne['classe']==6){
                    $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",6)";
                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                    $sql2="SELECT * from  `".$nomtableLigne."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
                    $res2=mysql_query($sql2);
                    if(mysql_num_rows($res2)){
                      $ligne=mysql_fetch_assoc($res2);
                      $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
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
            $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",6)";
            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

            $sql2="SELECT * from  `".$nomtableLigne."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
            $res2=mysql_query($sql2);
            if(mysql_num_rows($res2)){
              $ligne=mysql_fetch_assoc($res2);
              $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
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
        $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
        $ligne = mysql_fetch_assoc($res) ;
        if(mysql_num_rows($res)){
            /***Debut verifier si la ligne du produit existe deja ***/
            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=7  ";
            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
            $ligne1 = mysql_fetch_assoc($res);
            /***Debut verifier si la ligne du produit existe deja ***/
            if($ligne1 != null){
            }
            else {
                if($ligne['classe']==9){
                    $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",9)";
                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                    $sql2="SELECT * from  `".$nomtableLigne."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
                    $res2=mysql_query($sql2);
                    if(mysql_num_rows($res2)){
                      $ligne=mysql_fetch_assoc($res2);
                      $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
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
            $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",9)";
            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

            $sql2="SELECT * from  `".$nomtableLigne."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
            $res2=mysql_query($sql2);
            if(mysql_num_rows($res2)){
              $ligne=mysql_fetch_assoc($res2);
              $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
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
    else if($design['classe']==7 && $pagnet['type']==0){
        $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$idPagnet." ";
        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
        $ligne = mysql_fetch_assoc($res) ;
        if(mysql_num_rows($res)){
            /***Debut verifier si la ligne du produit existe deja ***/
            $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."' and idDesignation='".$design['idDesignation']."' and classe=7  ";
            $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
            $ligne1 = mysql_fetch_assoc($res);
            /***Debut verifier si la ligne du produit existe deja ***/
            if($ligne1 != null){
            }
            else {
                if($ligne['classe']==7){
                    $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",7)";
                    $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

                    $sql2="SELECT * from  `".$nomtableLigne."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
                    $res2=mysql_query($sql2);
                    if(mysql_num_rows($res2)){
                      $ligne=mysql_fetch_assoc($res2);
                      $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
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
            $sql7="insert into `".$nomtableLigne."` (designation, idStock,idDesignation, unitevente,prixunitevente,quantite,prixtotal,idPagnet,classe)values('".$design['designation']."',".$design['idDesignation'].",".$design['idDesignation'].",'".$design['uniteStock']."',".$design['prix'].",1,".$design['prix'].",".$idPagnet.",7)";
            $res7=mysql_query($sql7) or die ("insertion pagnier impossible 7  =>".mysql_error() );

            $sql2="SELECT * from  `".$nomtableLigne."` WHERE designation='".$design['designation']."' AND idPagnet='".$idPagnet."' ";
            $res2=mysql_query($sql2);
            if(mysql_num_rows($res2)){
              $ligne=mysql_fetch_assoc($res2);
              $sqlT="SELECT SUM(prixtotal) FROM `".$nomtableLigne."` where idPagnet='".$idPagnet."'  ";
              $resT = mysql_query($sqlT) or die ("persoonel requête 2".mysql_error());
              $TotalT = mysql_fetch_array($resT) ;

              $sql_t="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ";
              $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());
              $t_stock = mysql_fetch_array($res_t) ;
              $result='5<>'.$design['idDesignation'].'<>'.$design['designation'].'<>'.$ligne['unitevente'].'<>'.$design['prix'].'<>'.$ligne['numligne'].'<>'.$TotalT[0].'<>'.$design['uniteStock'].'<>'.$design['nbreArticleUniteStock'].'<>'.$t_stock[0];
            }
        }
    }
    else if($design['classe']==8){
        $sql3="UPDATE `".$nomtablePagnet."` set type='1' where idPagnet=".$idPagnet;
        $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());
        $result='6<>0';
    }
    else if($design['classe']==10){
        $sql3="UPDATE `".$nomtablePagnet."` set type='10' where idPagnet=".$idPagnet;
        $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());
        $result='6<>0';
    }
    else if($design['classe']==20){
      $sql3="UPDATE `".$nomtablePagnet."` set type='20' where idPagnet=".$idPagnet;
      $res3=@mysql_query($sql3) or die ("mise à jour verouillage  impossible".mysql_error());
      $result='6<>0';
    }
  }

  exit($result);
