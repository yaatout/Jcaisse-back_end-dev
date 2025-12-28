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
if(!$_SESSION['iduser']){ 
  header('Location:../index.php');
  }

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

$operation=@htmlspecialchars($_POST["operation"]);
$tri=@htmlspecialchars($_POST["tri"]);
$date1VD=@htmlspecialchars($_POST["date1VD"]);
$date2VD=@htmlspecialchars($_POST["date2VD"]);




if ($operation=="1" || $operation=="3" || $operation=="4") {

    $nbEntreeVD = @$_POST["nbEntreeVD"];
    $query = @$_POST["query"];
    $cb = @$_POST["cb"];
    $item_per_page = ($nbEntreeVD!=0) ? $nbEntreeVD : 10; //item to display per page
    $page_number 		= 0; //page number
    $produits = array();
    $tabIdDesigantion = array();
    // $tabIdStock = array();

    //Get page number from Ajax
    if(isset($_POST["page"])){
        $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
        if(!is_numeric($page_number)){die('Numéro de page invalide!');} //incase of invalid page number
    }else{
        $page_number = 1; //if there's no page number, set it to 1
    }
    

    if ($date1VD !=="" && $date2VD !=="") {
        # code...
        
        // $sql="select DISTINCT p.datepagej from `".$nomtableDesignation."` d, `".$nomtablePagnet."` p, `".$nomtableLigne."` l WHERE l.idDesignation = d.idDesignation && p.idPagnet= l.idPagnet &&
        // d.classe=0  && p.idClient=0  && p.verrouiller=1 && p.type=0 && ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$date1VD."' AND '".$date2VD."') or (p.datepagej BETWEEN '".$date1VD."' AND '".$date2VD."')) ORDER BY p.idPagnet DESC ";
        // $res=mysql_query($sql);
        $sql="select DISTINCT p.datepagej from `".$nomtablePagnet."` p, `".$nomtableLigne."` l WHERE p.idPagnet= l.idPagnet &&
        l.classe=0  && p.idClient=0  && p.verrouiller=1 && p.type=0 && ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$date1VD."' AND '".$date2VD."') or (p.datepagej BETWEEN '".$date1VD."' AND '".$date2VD."')) ORDER BY p.idPagnet DESC ";
        $res=mysql_query($sql);

    } else {
        
        // $sql="select DISTINCT p.datepagej from `".$nomtableDesignation."` d, `".$nomtablePagnet."` p, `".$nomtableLigne."` l WHERE l.idDesignation = d.idDesignation && p.idPagnet= l.idPagnet &&
        // d.classe=0  && p.idClient=0  && p.verrouiller=1 && p.type=0 ORDER BY p.idPagnet DESC ";
        // $res=mysql_query($sql);
        $sql="select DISTINCT p.datepagej from `".$nomtablePagnet."` p, `".$nomtableLigne."` l WHERE p.idPagnet= l.idPagnet &&
        l.classe=0  && p.idClient=0  && p.verrouiller=1 && p.type=0 ORDER BY p.idPagnet DESC ";
        $res=mysql_query($sql);
    }
    
    $rows = [];
    $t = 0;
    while($vd = mysql_fetch_array($res)){
        $rows[] = $vd;
        $t = $t + 1;
    }

    $total_rows = $t;

    $total_pages = ceil($total_rows/$item_per_page);

    //position of records
    $page_position = (($page_number-1) * $item_per_page);

    // var_dump($total_rows);

  //Display records fetched from database.
  echo '<table id="tableVD" class="table table-striped display tabStock tableau3" align="left" border="1">';
  echo '<thead>
          <tr id="thVD">
            <th>Date</th>
            <th>Montant Total (FCFA)</th>
          </tr>
        </thead>';
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;

  $rows = array_slice($rows, $page_position, $item_per_page);

  foreach ($rows as $key) {
    
    $sql="select SUM(prixtotal) as total_ventes from `".$nomtablePagnet."` p, `".$nomtableLigne."` l WHERE p.idPagnet=l.idPagnet &&
    l.classe=0  && p.idClient=0  && p.verrouiller=1 && p.type=0 && p.datepagej='".$key['datepagej']."'";
    // $sql="select SUM(prixtotal) as total_ventes from `".$nomtableDesignation."` d, `".$nomtablePagnet."` p, `".$nomtableLigne."` l WHERE l.idDesignation = d.idDesignation && p.idPagnet= l.idPagnet &&
    // d.classe=0  && p.idClient=0  && p.verrouiller=1 && p.type=0 && p.datepagej='".$key['datepagej']."'";
    // d.classe=0  && p.idClient=0  && p.verrouiller=1 && p.type=0 && p.datepagej='".$key['datepagej']."' Group By p.datepagej";
    $res=mysql_query($sql);
    // var_dump($sql);
    $sommeVente = mysql_fetch_array($res);
    $sommeVente = $sommeVente['total_ventes'];

    echo '<tr>';
      echo '<td>'.$key['datepagej'].'</td>';
      echo '<td><span style="color:blue;">'.number_format($sommeVente, 0, ',', ' ').'</span></td>';
    echo '</tr>';


    $i++;
    $cpt++;   
  }
  if ($cpt==0) {
    # code...
    echo '<tr>';
    echo  '<td colspan="2" align="center">Données introuvables!</td>';
    echo '</tr>';
  }
  echo '</table>';

  echo '<div class="">';
    echo '<div class="col-md-4">';
      echo '<ul class="pull-left" style="margin-top:30px;">';
        if ($item_per_page > $total_rows) {
          # code...
            echo '1 à '.($total_rows).' sur '.$total_rows.' articles';        
        } else {
          # code...
          if ($page_number == 1) {
            # code...
            echo '1 à '.($item_per_page).' sur '.$total_rows.' articles';
          } else {
            # code...
            if (($total_rows-($item_per_page * $page_number)) < 0) {
              # code... 
              echo ($item_per_page * ($page_number-1) +1).' à '.$total_rows.' sur '.$total_rows.' articles';
            } else {
              # code...
              echo ($item_per_page * ($page_number-1) +1).' à '.($item_per_page * $page_number).' sur '.$total_rows.' articles';
            }
          }
        }      
      echo '</ul>';
    echo '</div>';
    echo '<div class="col-md-8">';
    // To generate links, we call the pagination function here. 
    echo paginate_function($item_per_page, $page_number, $total_rows, $total_pages);
    echo '</div>';
  echo '</div>';

}

/*****  Tri **/
else if ($operation=="2") {
  # code...
  $nbEntreeVD = @$_POST["nbEntreeVD"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page = ($nbEntreeVD!=0) ? $nbEntreeVD : 10; //item to display per page
  $page_number 		= 0; //page number
  $produits = array();
  $tabIdDesigantion = array();
  // $tabIdStock = array();

  //Get page number from Ajax
  if(isset($_POST["page"])){
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
    if(!is_numeric($page_number)){die('Numéro de page invalide!');} //incase of invalid page number
  }else{
    $page_number = 1; //if there's no page number, set it to 1
  }
  

  if ($query =="") {
    # code...
    $sql="select COUNT(*) as total_row from `".$nomtableEntrepotTransfert."`";
    $res=mysql_query($sql);
  } else {
    # code...
    //get total number of records from database
    $sql="select COUNT(*) as total_row from `".$nomtableEntrepotTransfert."` WHERE designation LIKE '%".$query."%'";
    $res=mysql_query($sql);
  }
  $total_rows = mysql_fetch_array($res);

  $total_pages = ceil($total_rows/$item_per_page);

  //position of records
  $page_position = (($page_number-1) * $item_per_page);


  if ($tri==1) {
    if ($query =="") {
      # code...
      $sql="SELECT * FROM `".$nomtableEntrepotTransfert."` ORDER BY designation DESC LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);

    } else {
      # code...     
      $sql="SELECT * FROM `".$nomtableEntrepotTransfert."` WHERE designation LIKE '%".$query."%' ORDER BY designation DESC LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);

    }
  } else {
    if ($query =="") {
      # code...
      $sql="SELECT * FROM `".$nomtableEntrepotTransfert."` ORDER BY designation LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);

    } else {
      # code...     
      $sql="SELECT * FROM `".$nomtableEntrepotTransfert."` WHERE designation LIKE '%".$query."%' ORDER BY designation  LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);

    }    
  }

  // var_dump(max($tabIdStock));

  //Display records fetched from database.
  echo '<table id="tableTransfert" class="table table-striped display tabStock tableau3" align="left" border="1">';
  echo '<thead>
          <tr id="thStock">
            <th>ORDRE</th>
            <th>DEPOT</th>
            <th>REFERENCE</th>
            <th>QUANTITE</th>
            <th>UNITE STOCK </th>
            <th>DATE DEBUT</th>
            <th>DATE FIN</th>
            <th>OPERATIONS</th>
            <th>PERSONNEL</th>
          </tr>
        </thead>';
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;

  // $produits = array_slice($produits, $page_position, $item_per_page);
  // var_dump($produits);

  // $produits=array();
while($transfert=mysql_fetch_array($res)){

  $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$transfert['idDesignation']."' ";
  $res1=mysql_query($sql1);
  $designation=mysql_fetch_array($res1);

  $sql2="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$transfert['idEntrepot']."' ";
  $res2=mysql_query($sql2);
  $depot=mysql_fetch_array($res2);

  $sql3="SELECT * from `".$nomtableEntrepotStock."` where idEntrepotTransfert='".$transfert['idEntrepotTransfert']."'  order by idEntrepotStock desc";
  $res3=mysql_query($sql3);
  $stock=mysql_fetch_array($res3);

  $sql0="SELECT * FROM `aaa-utilisateur` WHERE idutilisateur =".$transfert['iduser'];
  $res0=mysql_query($sql0);
  $personne = mysql_fetch_array($res0);

  $date1 = strtotime($dateString); 
  $date2 = strtotime($transfert['dateTransfert']); 
    //  var_dump($stock);

  $total=0;
  $sqlE="SELECT SUM(quantiteStockinitial)
  FROM `".$nomtableEntrepotStock."`
  where idDesignation ='".$designation['idDesignation']."' AND idEntrepotTransfert ='".$transfert['idEntrepotTransfert']."'  ";
  $resE=mysql_query($sqlE) or die ("select stock impossible =>".mysql_error());
  $E_stock = mysql_fetch_array($resE);
  if($E_stock[0]!=null){
    $total=$E_stock[0];
  }
    
    // if(in_array($designation['idDesignation'], $produits)){
    //   // echo "Existe.";
    // }
    // else{

      echo '<tr>';
        if($i==1){
          echo '<td>'.$i.'</td>';
          echo '<td><span style="color:blue;">'.strtoupper($depot['nomEntrepot']).'</span></td>';
          echo '<td><span class="produitTF'.$transfert["idEntrepotTransfert"].'" style="color:blue;">'.strtoupper($designation['designation']).'</span></td>';
          echo '<td><span style="color:blue;">'.$transfert['quantite'].'</span></td>';
          echo '<td><span style="color:blue;">'.strtoupper($designation['uniteStock']).'</span></td>';
          echo '<td><span style="color:blue;">'.$transfert['dateTransfert'].'</span></td>';
          if($stock!=null){
            echo '<td><span style="color:blue;">'.$stock['dateStockage'].'</span></td>';
          }
          else{
            echo '<td><span style="color:blue;"></span></td>';
          }
        }	
        else if($date1==$date2){
          echo '<td>'.$i.'</td>';
          echo '<td><span style="color:green;">'.strtoupper($depot['nomEntrepot']).'</span></td>';
          echo '<td><span class="produitTF'.$transfert["idEntrepotTransfert"].'" style="color:green;">'.strtoupper($designation['designation']).'</span></td>';
          echo '<td><span style="color:green;">'.$transfert['quantite'].'</span></td>';
          echo '<td><span style="color:green;">'.strtoupper($designation['uniteStock']).'</span></td>';
          echo '<td><span style="color:green;">'.$transfert['dateTransfert'].'</span></td>';
          if($stock!=null){
            echo '<td><span style="color:green;">'.$stock['dateStockage'].'</span></td>';
          }
          else{
            echo '<td><span style="color:green;"></span></td>';
          }
        }	
        else{
          echo '<td>'.$i.'</td>';
          echo '<td>'.strtoupper($depot['nomEntrepot']).'</td>';
          echo '<td><span class="produitTF'.$transfert["idEntrepotTransfert"].'" id="produitTF_1'.$transfert["idEntrepotTransfert"].'" >'.strtoupper($designation['designation']).'</span></td>';
          echo '<td>'.$transfert['quantite'].'</td>';
          echo '<td>'.strtoupper($designation['uniteStock']).'</td>';
          echo '<td>'.$transfert['dateTransfert'].'</td>';
          if($stock!=null){
            echo '<td>'.$stock['dateStockage'].'</td>';
          }
          else{
            echo '<td></td>';
          }
        }	

        if ($_SESSION['proprietaire']==1){
          if($transfert["quantite"]==$total){
            echo '<td><button type="button" class="btn btn-info btn_ajtStock" onclick="transfertStock_1('.$transfert["idEntrepotTransfert"].','.$designation["idDesignation"].','.$transfert["quantite"].','.$total.')"><i class="glyphicon glyphicon-ok">
              </i>
            </button></td>&nbsp;';
          }
          else {
            if ($transfert["etat1"]==1 && $transfert["etat2"]==0) {
              echo '<td><button type="button" disabled="true" class="btn btn-success btn_ajtStock" onclick="transfertStock_1('.$transfert["idEntrepotTransfert"].','.$designation["idDesignation"].','.$transfert["quantite"].','.$total.')"><i class="glyphicon glyphicon-ok">
              </i>
            </button></td>&nbsp;';
            }
            else if ($transfert["etat1"]==1 && $transfert["etat2"]==1) {
              if ($total!=0) {
                echo '<td><button type="button" class="btn btn-warning btn_ajtStock" onclick="transfertStock_2('.$transfert["idEntrepotTransfert"].','.$designation["idDesignation"].','.$transfert["quantite"].','.$total.')"><i class="glyphicon glyphicon-ok">
                  </i>
                </button></td>&nbsp;';
              }
              else {
                echo '<td><button type="button" class="btn btn-success btn_ajtStock" onclick="transfertStock_0('.$transfert["idEntrepotTransfert"].','.$designation["idDesignation"].','.$transfert["quantite"].','.$total.')"><i class="glyphicon glyphicon-ok">
                          </i>
                </button>&nbsp;
                <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Transfert('.$transfert["idEntrepotTransfert"].','.$i.')"   /></a></td>&nbsp;';
              }
            }
            else{
              if($total!=0){
                echo '<td><button type="button" class="btn btn-warning btn_ajtStock" onclick="transfertStock_2('.$transfert["idEntrepotTransfert"].','.$designation["idDesignation"].','.$transfert["quantite"].','.$total.')"><i class="glyphicon glyphicon-transfer">
                  </i>
                </button>&nbsp;
                <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Transfert('.$transfert["idEntrepotTransfert"].','.$i.')"  /></a></td>&nbsp;';
              }
              else{
                echo '<td><button type="button" class="btn btn-success btn_ajtStock" onclick="transfertStock_0('.$transfert["idEntrepotTransfert"].','.$designation["idDesignation"].','.$transfert["quantite"].','.$total.')"><i class="glyphicon glyphicon-transfer">
                          </i>
                </button>&nbsp;
                <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Transfert('.$transfert["idEntrepotTransfert"].','.$i.')"  /></a>&nbsp;
                <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Transfert('.$transfert["idEntrepotTransfert"].','.$i.')"   /></a></td>&nbsp;';
              }
            }
          }
        }
        else{
          if($transfert["quantite"]==$total){
            echo '<td><button type="button" disabled="true" class="btn btn-info btn_ajtStock"><i class="glyphicon glyphicon-ok">
            </i>
          </button></td>&nbsp;';
          }
          else if($transfert["etat1"]==1 && $transfert["iduser"]!=$_SESSION['iduser']){
            echo '<td><button type="button" disabled="true" class="btn btn-info btn_ajtStock"><i class="glyphicon glyphicon-ok">
            </i>
          </button></td>&nbsp;';
          }
          else if($transfert["etat1"]==1 && $transfert["etat2"]==1 && $transfert["iduser"]==$_SESSION['iduser']){
            echo '<td><button type="button" disabled="true" class="btn btn-info btn_ajtStock"><i class="glyphicon glyphicon-ok">
            </i>
          </button>&nbsp;
          <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Transfert('.$transfert["idEntrepotTransfert"].','.$i.')"  /></a>&nbsp;
          <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Transfert('.$transfert["idEntrepotTransfert"].','.$i.')"   /></a></td>&nbsp;';
          }
          else{
            echo '<td><button type="button" class="btn btn-success btn_ajtStock" onclick="validerTransfert_1('.$transfert["idEntrepotTransfert"].','.$designation["idDesignation"].')" id="btn_ValiderTransfert-'.$transfert['idEntrepotTransfert'].'"><i class="glyphicon glyphicon-ok">
            </i>
          </button>&nbsp;
          <a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Transfert('.$transfert["idEntrepotTransfert"].','.$i.')"  /></a></td>&nbsp;';
          }
        }

        echo '<td>'.strtoupper($personne['nom']).'</td>';

        // echo '<td>'.$i.'</td>';
        // echo '<td>'.strtoupper($designation['designation']).'</td>';
        // echo '<td>'.strtoupper($designation['codeBarreDesignation']).'</td>';
        // echo '<td>'.$S_stock[0].'</td>';
        // echo '<td>'.'<input type="number" data-id="'.$stock["idDesignation"].'" id="quantite-'.$stock["idDesignation"].'" class="form-control quantitePhysique" width="20%"  min=1 value=""/></td>'; 	
        // echo '<td><button type="button" class="btn btn-success btn_ajtStock" id="btn_InvStock-'.$stock['idDesignation'].'" onclick="inventaireDepot('.$stock["idDesignation"].')"><i class="glyphicon glyphicon-plus">
        //   </i>CORRIGER
        // </button>&nbsp;</td>';
      echo '</tr>';

      $i++;
      $cpt++;
      // $produits[] = $designation['idDesignation'];
    // }
  }
  if ($cpt==0) {
    # code...
    echo '<tr>';
    echo  '<td colspan="9" align="center">Données introuvables!</td>';
    echo '</tr>';
  }
  echo '</table>';

  echo '<div class="">';
    echo '<div class="col-md-4">';
      echo '<ul class="pull-left" style="margin-top:30px;">';
        if ($item_per_page > $total_rows) {
          # code...
            echo '1 à '.($total_rows).' sur '.$total_rows.' articles';        
        } else {
          # code...
          if ($page_number == 1) {
            # code...
            echo '1 à '.($item_per_page).' sur '.$total_rows.' articles';
          } else {
            # code...
            if (($total_rows-($item_per_page * $page_number)) < 0) {
              # code... 
              echo ($item_per_page * ($page_number-1) +1).' à '.$total_rows.' sur '.$total_rows.' articles';
            } else {
              # code...
              echo ($item_per_page * ($page_number-1) +1).' à '.($item_per_page * $page_number).' sur '.$total_rows.' articles';
            }
          }
        }      
      echo '</ul>';
    echo '</div>';
    echo '<div class="col-md-8">';
    // To generate links, we call the pagination function here. 
    echo paginate_function($item_per_page, $page_number, $total_rows, $total_pages);
    echo '</div>';
  echo '</div>';
}


function paginate_function($item_per_page, $current_page, $total_records, $total_pages)
  {
      $pagination = '';
      if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
        $pagination .= '<ul class="pagination pull-right">';
          
          if ($current_page == 1) {
            # code...
            $pagination .= '<li class="page-item disabled"><a data-page="1" class="page-link"><span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span></a>
            </li>';
          } else {
            # code...
            $pagination .= '<li class="page-item"><a href="#" data-page="'.($current_page - 1).'" class="page-link"><span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span></a></li>';
          }        
        
          if ($total_pages <= 5) {                                            
            for($page = 1; $page <= $total_pages; $page++){
              if ($current_page == $page) {
                # code...
                $pagination .= '<li class="page-item page active"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
              } else {
                # code...
                $pagination .= '<li class="page-item page"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
              }
              }
            }else {  
              if ($current_page == 1) {
                # code...
                $pagination .= '<li class="page-item active"><a href="#" data-page="1" class="page-link">1</a></li>';
              } else {
                # code...
                $pagination .= '<li class="page-item"><a href="#" data-page="1" class="page-link">1</a></li>';
              }
                                                              
              if($current_page == 1 || $current_page == 2){ 
                for($page = 2 ; $page <= 3; $page++){
                  if ($current_page == $page) {
                    # code...
                    $pagination .= '<li class="page-item page active"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                  } else {
                    # code...
                    $pagination .= '<li class="page-item page"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                  }                  
                }         
                $pagination .= '<li class="page-item"><a class="page-link">...</a></li>';
                                            
                } else if(($current_page > 2) and ($current_page < $total_pages - 2)){  
                  $pagination .= '<li class="page-item"><a class="page-link">...</a></li>';

                  for($page =$current_page ; $page <= ($current_page + 1); $page++){ 
                    if ($current_page == $page) {
                      # code...
                      $pagination .= '<li class="page-item page active"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                    } else {
                      # code...
                      $pagination .= '<li class="page-item page"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                    }
                }
                $pagination .= '<li class="page-item"><a class="page-link">...</a></li>';
              
              }else{
                $pagination .= '<li class="page-item"><a class="page-link">...</a></li>';
                for($page = ($total_pages - 2) ; $page <= ($total_pages - 1); $page++){
                  if ($current_page == $page) {
                    # code...
                    $pagination .= '<li class="page-item page active"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                  } else {
                    # code...
                    $pagination .= '<li class="page-item page"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                  }
                }
              }
              if ($current_page == $total_pages) {
                # code...
                $pagination .= '<li class="page-item page active"><a href="#" data-page="'.$total_pages.'" class="page-link">'.$total_pages.'</a></li>';
              } else {
                # code...
                $pagination .= '<li class="page-item page"><a href="#" data-page="'.$total_pages.'" class="page-link">'.$total_pages.'</a></li>';
              }
              }
          if ($current_page == $total_pages) {
            # code...
            $pagination .= '<li class="page-item disabled"><a data-page="'.$total_pages.'" class="page-link"><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></a></li>';
          } else {
            # code...
            $pagination .= '<li class="page-item"><a href="#" data-page="'.($current_page + 1).'" class="page-link"><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></a></li>';
          }
          
          $pagination .= '</ul>'; 
      }
      return $pagination; //return pagination links
  }

?>
