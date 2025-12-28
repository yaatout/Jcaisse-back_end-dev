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

function cmp1($a, $b)
{
    return strcmp($b["designation"], $a["designation"]);
}

function cmp2($a, $b)
{
    return number_format($a["s_stock"]) - number_format($b["s_stock"]);
}

$operation=@htmlspecialchars($_POST["operation"]);
$tri=@htmlspecialchars($_POST["tri"]);

$date1= date('Y-m-d', strtotime('+1 days')); 
$date2= date('Y-m-d', strtotime('+1 month'));
$date3= date('Y-m-d', strtotime('+2 month'));
$date4= date('Y-m-d', strtotime('+3 month'));
//$date5= date('Y-m-d', strtotime('+1 month'));


if ($operation=="1" || $operation=="3" || $operation=="4") {

  $nbEntreeEcheanceFnr = @$_POST["nbEntreeEcheanceFnr"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page 		= ($nbEntreeEcheanceFnr!=0) ? $nbEntreeEcheanceFnr : 10; //item to display per page
  $page_number 		= 0; //page number
  $produits = array();
  $tabIdDesigantion = array();
  $tabIdStock = array();

  //Get page number from Ajax
  if(isset($_POST["page"])){
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
    if(!is_numeric($page_number)){die('Numéro de page invalide!');} //incase of invalid page number
  }else{
    $page_number = 1; //if there's no page number, set it to 1
  }
  
  //get total number of records from database
  
  if ($query =="") {

    
    //if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
    # code...

      $sql1="SELECT * from `".$nomtableBl."` where dateEcheance !='' and dateEcheance <= '".$date1."' order by dateEcheance ASC ";
      $res1=mysql_query($sql1);

      $sql2="SELECT * from `".$nomtableBl."` where dateEcheance !='' and dateEcheance BETWEEN '".$date1."' AND '".$date2."' order by dateEcheance ASC ";
      $res2=mysql_query($sql2);

      $sql3="SELECT * from `".$nomtableBl."` where dateEcheance !='' and dateEcheance BETWEEN '".$date2."' AND '".$date3."' order by dateEcheance ASC ";
      $res3=mysql_query($sql3);

      $sql4="SELECT * from `".$nomtableBl."` where dateEcheance !='' and dateEcheance BETWEEN '".$date3."' AND '".$date4."' order by dateEcheance ASC ";
      $res4=mysql_query($sql4);

      $sql5="SELECT * from `".$nomtableBl."` where dateEcheance !='' and dateEcheance > '".$date4."' order by dateEcheance ASC ";
      $res5=mysql_query($sql5);
      
  } else {
    # code...
    //Limit our results within a specified range. 
      $sql1="SELECT * from `".$nomtableBl."` where dateEcheance !='' and dateEcheance <= '".$date1."' and (dateEcheance LIKE '%".$query."%') order by dateEcheance ASC ";
      $res1=mysql_query($sql1);

      $sql2="SELECT * from `".$nomtableBl."` where dateEcheance !='' and dateEcheance BETWEEN '".$date1."' AND '".$date2."' and (dateEcheance LIKE '%".$query."%') order by dateEcheance ASC ";
      $res2=mysql_query($sql2);

      $sql3="SELECT * from `".$nomtableBl."` where dateEcheance !='' and dateEcheance BETWEEN '".$date2."' AND '".$date3."' and (dateEcheance LIKE '%".$query."%') order by dateEcheance ASC ";
      $res3=mysql_query($sql3);

      $sql4="SELECT * from `".$nomtableBl."` where dateEcheance !='' and dateEcheance BETWEEN '".$date3."' AND '".$date4."' and (dateEcheance LIKE '%".$query."%') order by dateEcheance ASC ";
      $res4=mysql_query($sql4);

      $sql5="SELECT * from `".$nomtableBl."` where dateEcheance !='' and dateEcheance > '".$date4."' and (dateEcheance LIKE '%".$query."%') order by dateEcheance ASC ";
      $res5=mysql_query($sql5);

  }
  
  $data=array();

  //Ajout de -1 jours
  //$date1=date('Y-m-d', strtotime('-1 days'));  
  //$sql="SELECT * from  `".$nomtableStock."` where dateExpiration !='' and dateExpiration !='0000-00-00' and dateExpiration <= '".$date1."'";
  //$res=mysql_query($sql);
  //if(mysql_num_rows($res1)){
    while($bl=mysql_fetch_array($res1)){
      // $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$tab['idDesignation']."'";
      // $res1=mysql_query($sql1);
      if($bl["dateEcheance"]!=null && $bl["dateEcheance"]!=''){

        $sql0="SELECT * FROM `".$nomtableFournisseur."` where idFournisseur=".$bl["idFournisseur"]."";
        $res0 = mysql_query($sql0) or die ("persoonel requête 3".mysql_error());
        $fournisseur = mysql_fetch_assoc($res0);

        $rows = array();
        $rows[] = '<span style="color:#757561;">'.$bl["dateEcheance"].'</span>';
        $rows[] = '<span style="color:#757561;">'.$bl["montantBl"].'</span>';
        $rows[] = '<span style="color:#757561;">'.$bl["numeroBl"].'</span>';
        $rows[] = '<span style="color:#757561;">'.$bl["dateBl"].'</span>';
        $rows[] = '<span style="color:#757561;">'.$fournisseur["nomFournisseur"].'</span>';
        $data[] = $rows;

      }

    }

    while($bl=mysql_fetch_array($res2)){
      if($bl["dateEcheance"]!=null && $bl["dateEcheance"]!=''){

        $sql0="SELECT * FROM `".$nomtableFournisseur."` where idFournisseur=".$bl["idFournisseur"]."";
        $res0 = mysql_query($sql0) or die ("persoonel requête 3".mysql_error());
        $fournisseur = mysql_fetch_assoc($res0);

        $rows = array();
        $rows[] = '<span style="color:red;">'.$bl["dateEcheance"].'</span>';
        $rows[] = '<span style="color:red;">'.$bl["montantBl"].'</span>';
        $rows[] = '<span style="color:red;">'.$bl["numeroBl"].'</span>';
        $rows[] = '<span style="color:red;">'.$bl["dateBl"].'</span>';
        $rows[] = '<span style="color:red;">'.$fournisseur["nomFournisseur"].'</span>';
        $data[] = $rows;

      }
    }

    while($bl=mysql_fetch_array($res3)){
      if($bl["dateEcheance"]!=null && $bl["dateEcheance"]!=''){

        $sql0="SELECT * FROM `".$nomtableFournisseur."` where idFournisseur=".$bl["idFournisseur"]."";
        $res0 = mysql_query($sql0) or die ("persoonel requête 3".mysql_error());
        $fournisseur = mysql_fetch_assoc($res0);

        $rows = array();
        $rows[] = '<span style="color:#1b44fc;">'.$bl["dateEcheance"].'</span>';
        $rows[] = '<span style="color:#1b44fc;">'.$bl["montantBl"].'</span>';
        $rows[] = '<span style="color:#1b44fc;">'.$bl["numeroBl"].'</span>';
        $rows[] = '<span style="color:#1b44fc;">'.$bl["dateBl"].'</span>';
        $rows[] = '<span style="color:#1b44fc;">'.$fournisseur["nomFournisseur"].'</span>';
        $data[] = $rows;

      }
    //}
    }
    
    while($bl=mysql_fetch_array($res4)){
      if($bl["dateEcheance"]!=null && $bl["dateEcheance"]!=''){

        $sql0="SELECT * FROM `".$nomtableFournisseur."` where idFournisseur=".$bl["idFournisseur"]."";
        $res0 = mysql_query($sql0) or die ("persoonel requête 3".mysql_error());
        $fournisseur = mysql_fetch_assoc($res0);

        $rows = array();
        $rows[] = '<span style="color:green;">'.$bl["dateEcheance"].'</span>';
        $rows[] = '<span style="color:green;">'.$bl["montantBl"].'</span>';
        $rows[] = '<span style="color:green;">'.$bl["numeroBl"].'</span>';
        $rows[] = '<span style="color:green;">'.$bl["dateBl"].'</span>';
        $rows[] = '<span style="color:green;">'.$fournisseur["nomFournisseur"].'</span>';
        $data[] = $rows;

      }
    }

    while($bl=mysql_fetch_array($res5)){
      if($bl["dateEcheance"]!=null && $bl["dateEcheance"]!=''){

        $sql0="SELECT * FROM `".$nomtableFournisseur."` where idFournisseur=".$bl["idFournisseur"]."";
        $res0 = mysql_query($sql0) or die ("persoonel requête 3".mysql_error());
        $fournisseur = mysql_fetch_assoc($res0);

        $rows = array();
        $rows[] = '<span style="color:green;">'.$bl["dateEcheance"].'</span>';
        $rows[] = '<span style="color:green;">'.$bl["montantBl"].'</span>';
        $rows[] = '<span style="color:green;">'.$bl["numeroBl"].'</span>';
        $rows[] = '<span style="color:green;">'.$bl["dateBl"].'</span>';
        $rows[] = '<span style="color:green;">'.$fournisseur["nomFournisseur"].'</span>';
        $data[] = $rows;

      }
    }


    //get total number of records 
    $total_rows = count($data);

    $total_pages = ceil($total_rows/$item_per_page);

    //position of records
    $page_position = (($page_number-1) * $item_per_page);

    // if ($tri==1) {
      // usort($produits, "cmp1");
      // var_dump($produits);
    // } else {

    //usort($produits, "cmp2");
      
    //Display records fetched from database.
    $i = ($page_number - 1) * $item_per_page +1;
      $cpt = 0;
      // $produits=array();
      $data = array_slice($data, $page_position, $item_per_page);
    
    echo '<table class="table table-striped contents"  border="1">';
        echo '<thead>
                <tr>
                  <th style="width: 15%;">Echeance</th>
                  <th style="width: 15%;">Montant</th>
                  <th style="width: 15%;">Numero</th>
                  <th style="width: 18%;">Date</th>
                  <th style="width: 18%;">Fournisseur</th>
                </tr>
              </thead>';

      
      // var_dump($produits);

      // $produits=array();
      foreach ($data as $stock) {
        // $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
        // $res1=mysql_query($sql1);
        // $design=mysql_fetch_array($res1);
          echo  '<tr>';    
            echo '<td><span>'.$stock[0].'</span></td>';
            echo '<td><span>'.$stock[1].'</span></td>';
            echo '<td><span>'.$stock[2].'</span></td>';
            echo '<td><span>'.$stock[3].'</span></td>';
            echo '<td><span>'.$stock[4].'</span></td>';
          echo'</tr>';
          $i++;
          $cpt++;
      }  
        
      
      if ($cpt==0) {
        # code...
        echo '<tr>';
        echo  '<td colspan="10" align="center">Données introuvables!</td>';
        echo '</tr>';
      }
    echo '</table>';
  
    echo '<div class="">';
      echo '<div class="col-md-4">';
        echo '<ul class="pull-left">';
          if ($item_per_page > $total_rows) {
            # code...
              echo '1 à '.($total_rows).' sur '.$total_rows.' entrées';        
          } else {
            # code...
            if ($page_number == 1) {
              # code...
              echo '1 à '.($item_per_page).' sur '.$total_rows.' entrées';
            } else {
              # code...
              if (($total_rows-($item_per_page * $page_number)) < 0) {
                # code... 
                echo ($item_per_page * ($page_number-1) +1).' à '.$total_rows.' sur '.$total_rows.' entrées';
              } else {
                # code...
                echo ($item_per_page * ($page_number-1) +1).' à '.($item_per_page * $page_number).' sur '.$total_rows.' entrées';
              }
            }
          }      
        echo '</ul>';
      echo '</div>';
      echo '<div class="col-md-8">';
      // To generate links, we call the pagination function here. 
      echo paginate_function($item_per_page, $page_number, $total_rows, $total_pages);
      echo '</div>';
    echo '';

  }

function paginate_function($item_per_page, $current_page, $total_records, $total_pages){
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
