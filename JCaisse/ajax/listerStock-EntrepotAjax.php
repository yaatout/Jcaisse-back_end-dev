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
/*
$sql="select * from `".$nomtableStock."` where quantiteStockCourant!=0 order by idStock DESC";
$res=mysql_query($sql);
*/

function cmp1($a, $b)
{
    return strcmp($b["designation"], $a["designation"]);
}

function cmp2($a, $b)
{
    return strcmp($a["designation"], $b["designation"]);
}

$operation=@htmlspecialchars($_POST["operation"]);
$tri=@htmlspecialchars($_POST["tri"]);


if ($operation=="1" || $operation=="3" || $operation=="4") {

  $nbEntreeSEt = @$_POST["nbEntreeSEt"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page = ($nbEntreeSEt!=0) ? $nbEntreeSEt : 10; //item to display per page
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
  
  if ($query =="") {
    # code...
    $sql="SELECT * FROM `".$nomtableStock."` s
    LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
    WHERE d.classe=0 AND (quantiteStockCourant<>0 OR quantiteStockCourant=0) ORDER BY s.idStock DESC";
    // WHERE d.classe=0 AND (quantiteStockCourant<>0 OR quantiteStockCourant=0) ORDER BY s.idStock DESC LIMIT $page_position, $item_per_page";
    $res=mysql_query($sql);
  } else {
    # code... 
    if ($cb==1) {
      //Limit our results within a specified range. 
      $sql="SELECT * FROM `".$nomtableStock."` s
      LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
      WHERE d.classe=0 AND (quantiteStockCourant<>0 OR quantiteStockCourant=0) and (d.codeBarreDesignation='".$query."') ORDER BY s.idStock DESC";
      // WHERE d.classe=0 AND (quantiteStockCourant<>0 OR quantiteStockCourant=0) and (d.designation LIKE '%".$query."%' or d.codeBarreDesignation LIKE '%".$query."%') ORDER BY s.idStock DESC LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
    }else {
      //Limit our results within a specified range. 
      $sql="SELECT * FROM `".$nomtableStock."` s
      LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
      WHERE d.classe=0 AND (quantiteStockCourant<>0 OR quantiteStockCourant=0) and (d.designation LIKE '%".$query."%' or d.codeBarreDesignation LIKE '%".$query."%') ORDER BY s.idStock DESC";
      // WHERE d.classe=0 AND (quantiteStockCourant<>0 OR quantiteStockCourant=0) and (d.designation LIKE '%".$query."%' or d.codeBarreDesignation LIKE '%".$query."%') ORDER BY s.idStock DESC LIMIT $page_position, $item_per_page";      
      $res=mysql_query($sql);
    }
  }

  while($stock=mysql_fetch_array($res)){
    if (in_array($stock['idDesignation'], $tabIdDesigantion)) {
      # code...
    } else {
      # code...
      $tabIdDesigantion[]=$stock['idDesignation'];
      $tabIdStock[]=$stock['idStock'];
      $produits[]=$stock;
    }
    
  }

  //get total number of records 
  $total_rows = count($produits);

  $total_pages = ceil($total_rows/$item_per_page);

  //position of records
  $page_position = (($page_number-1) * $item_per_page);

// var_dump(max($tabIdStock));

  //Display records fetched from database.
  echo '<table id="tableStock" class=" table table-striped display tabStock tableau3" align="left" border="1">
        <thead>
          <tr id="thStock">
            <th>ORDRE</th>
            <th>REFERENCE</th>
            <th>CATEGORIE</th>
            <th>QUANTITE STOCK SUR DEPOT</th>
            <th>QUANTITE STOCK SANS DEPOT</th>
            <th>UNITE STOCK </th>
            <th>PRIX UNITE STOCK (PU)</th>
            <th>PRIX ACHAT (PA)</th>
            <th>OPERATIONS</th>
          </tr>
        </thead>';
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;

  $produits = array_slice($produits, $page_position, $item_per_page);
  // var_dump($produits);

  // $produits=array();
  foreach ($produits as $stock) {

    $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
    $res1=mysql_query($sql1);
    $designation=mysql_fetch_array($res1);
  
    $sqlS="SELECT SUM(quantiteStockCourant)
    FROM `".$nomtableEntrepotStock."`
    where idDesignation ='".$stock['idDesignation']."'  ";
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $S_stock = mysql_fetch_array($resS);
  
    $date1 = strtotime($dateString); 
    $date2 = strtotime($stock['dateStockage']); 
  
    $sqlI="SELECT SUM(quantiteStockinitial*nbreArticleUniteStock)
    FROM `".$nomtableStock."`
    where idDesignation ='".$stock['idDesignation']."'  ";
    $resI=mysql_query($sqlI) or die ("select stock impossible =>".mysql_error());
    $I_stock = mysql_fetch_array($resI);
  
    $sqlE="SELECT SUM(quantiteStockinitial*nbreArticleUniteStock)
    FROM `".$nomtableEntrepotStock."`
    where idDesignation ='".$stock['idDesignation']."' AND (idTransfert=0 OR idTransfert IS NULL)   ";
    $resE=mysql_query($sqlE) or die ("select stock impossible =>".mysql_error());
    $E_stock = mysql_fetch_array($resE);

      echo '<tr>';
      // if($stock['idStock'] == max($tabIdStock)){
      if($stock['idStock'] == max($tabIdStock)){
        echo  '<td>'.$i.'</td>';
        echo  '<td><span style="color:blue;">'.strtoupper($designation['designation']).'</span></td>';
        echo  '<td><span style="color:blue;">'.strtoupper($designation['categorie']).'</span></td>';
        
        if($designation['nbreArticleUniteStock']!=0 || $designation['nbreArticleUniteStock']!=null){
          if($S_stock[0]!=0 && $S_stock[0]!=null){
            echo  '<td><span style="color:blue;">'.($S_stock[0] / $designation['nbreArticleUniteStock']) .'</span></td>';
          }
          else{
            echo  '<td><span style="color:blue;">'.$S_stock[0].'</span></td>';
          }
        }
        else{
          echo  '<td><span style="color:blue;">'.$S_stock[0].'</span></td>';
        }
        echo  '<td><span style="color:blue;">'.(($I_stock[0] - $E_stock[0])/$designation['nbreArticleUniteStock']).'</span></td>';
        echo  '<td><span style="color:blue;">'.strtoupper($designation['uniteStock']).'</span></td>';
        echo  '<td><span style="color:blue;">'.number_format($designation['prixuniteStock'], 0, ',', ' ').'</span></td>';
   
        echo  '<td><span style="color:blue;">'.number_format($designation['prixachat'], 0, ',', ' ').'</span></td>';
      }	
      else if($date1==$date2){
        echo  '<td>'.$i.'</td>';
        echo  '<td><span style="color:#901E06;">'.strtoupper($designation['designation']).'</span></td>';
        echo  '<td><span style="color:#901E06;">'.strtoupper($designation['categorie']).'</span></td>';
        if($designation['nbreArticleUniteStock']!=0 || $designation['nbreArticleUniteStock']!=null){
          echo  '<td><span style="color:#901E06;">'.($S_stock[0] / $designation['nbreArticleUniteStock']) .'</span></td>';
        }
        else{
          echo  '<td><span style="color:#901E06;">'.$S_stock[0].'</span></td>';
        }
        echo  '<td><span style="color:#901E06;">'.(($I_stock[0] - $E_stock[0])/$designation['nbreArticleUniteStock']).'</span></td>';
        echo  '<td><span style="color:#901E06;">'.strtoupper($designation['uniteStock']).'</span></td>';
        echo  '<td><span style="color:#901E06;">'.number_format($designation['prixuniteStock'], 0, ',', ' ').'</span></td>';
      
        echo  '<td><span style="color:#901E06;">'.number_format($designation['prixachat'], 0, ',', ' ').'</span></td>';
      }	
      else{
        echo  '<td>'.$i.'</td>';
        echo  '<td>'.strtoupper($designation['designation']).'</td>';
        echo  '<td>'.strtoupper($designation['categorie']).'</td>';
        if($designation['nbreArticleUniteStock']!=0 || $designation['nbreArticleUniteStock']!=null){
          if($S_stock[0]!=0 && $S_stock[0]!=null){
            echo  '<td>'.($S_stock[0] / $designation['nbreArticleUniteStock']).'</td>';
          }
          else{
            echo  '<td>'.$S_stock[0].'</td>';
          }
        }
        else{
          echo  '<td>'.$S_stock[0].'</td>';
        }
        echo  '<td>'.(($I_stock[0] - $E_stock[0])/$designation['nbreArticleUniteStock']).'</td>';
        echo  '<td>'.strtoupper($designation['uniteStock']).'</td>';
        echo  '<td>'.number_format($designation['prixuniteStock'], 0, ',', ' ').'</td>';
      //  $rows[] = $S_stock[0];
      //  $rows[] = $designation['uniteDetails'];
      echo  '<td>'.number_format($designation['prixachat'], 0, ',', ' ').'</td>'; 
      }	

      $sqlI="SELECT SUM(quantiteStockinitial*nbreArticleUniteStock)
      FROM `".$nomtableStock."`
      where idDesignation ='".$stock['idDesignation']."'  ";
      $resI=mysql_query($sqlI) or die ("select stock impossible =>".mysql_error());
      $I_stock = mysql_fetch_array($resI);

      $sqlE="SELECT SUM(quantiteStockinitial*nbreArticleUniteStock)
      FROM `".$nomtableEntrepotStock."`
      where idDesignation ='".$stock['idDesignation']."' AND (idTransfert=0 OR idTransfert IS NULL)   ";
      $resE=mysql_query($sqlE) or die ("select stock impossible =>".mysql_error());
      $E_stock = mysql_fetch_array($resE);

      if($I_stock[0]==$E_stock[0]){
        echo  '<td><a href="stock-Entrepot.php?iDS='.$stock["idDesignation"].'"><span style="color:green;">Details</span></a></td>';
      }
      else{
        echo  '<td><a href="stock-Entrepot.php?iDS='.$stock["idDesignation"].'"><span style="color:#901E06;">Details</span></a></td>';
      }

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

/*****  Tri **/
else if ($operation=="2") {
  # code...
  $nbEntreeSEt = @$_POST["nbEntreeSEt"];
  $query = @$_POST["query"];
  $item_per_page = ($nbEntreeSEt!=0) ? $nbEntreeSEt : 10;//item to display per page
  $page_number 		= 0; //page number
  $produits = array();
  $tabIdDesigantion = array();
  $tabIdStock = array();

  //Get page number from Ajax
  if(isset($_POST["page"])){
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
    if(!is_numeric($page_number)){die('Invalid page number!');} //incase of invalid page number
  }else{
    $page_number = 1; //if there's no page number, set it to 1
  }

  if ($query =="") {
    # code...   
    $sql="SELECT * FROM `".$nomtableStock."` s
    LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation
    WHERE d.classe=0 AND quantiteStockCourant<>0  ORDER BY s.idStock DESC";
    $res=mysql_query($sql);
  } else {
    # code...
    //Limit our results within a specified range. 
    $sql="SELECT * FROM `".$nomtableStock."` s
    LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation
    WHERE d.classe=0 AND quantiteStockCourant<>0 and (d.designation LIKE '%".$query."%' or d.codeBarreDesignation LIKE '%".$query."%') ORDER BY s.idStock DESC";
    $res=mysql_query($sql);
  }

  while($stock=mysql_fetch_array($res)){
    if (in_array($stock['idDesignation'], $tabIdDesigantion)) {
      # code...
    } else {
      # code...
      $tabIdDesigantion[]=$stock['idDesignation'];
      $tabIdStock[]=$stock['idStock'];
      $produits[]=$stock;
    }
    
  }

  //get total number of records 
  $total_rows = count($produits);

  $total_pages = ceil($total_rows/$item_per_page);

  //position of records
  $page_position = (($page_number-1) * $item_per_page);

  if ($tri==1) {
    usort($produits, "cmp1");
    // var_dump($produits);
  } else {
    usort($produits, "cmp2");
    // var_dump($produits);
  }
  
  // $results->bind_result($id, $name, $message); //bind variables to prepared statement

  echo '<table class="table table-striped contents display tabStock" id="tableStock" border="1">
        <thead>
          <tr id="thStock">
            <th>ORDRE</th>
            <th>REFERENCE</th>
            <th>CATEGORIE</th>
            <th>QUANTITE STOCK SUR DEPOT</th>
            <th>QUANTITE STOCK SANS DEPOT</th>
            <th>UNITE STOCK </th>
            <th>PRIX UNITE STOCK (PU)</th>
            <th>PRIX ACHAT (PA)</th>
            <th>OPERATIONS</th>
          </tr>
        </thead>';
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;

  $produits = array_slice($produits, $page_position, $item_per_page);
  // var_dump($produits);

  // $produits=array();
  foreach ($produits as $stock) {

    $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
    $res1=mysql_query($sql1);
    $designation=mysql_fetch_array($res1);
  
    $sqlS="SELECT SUM(quantiteStockCourant)
    FROM `".$nomtableEntrepotStock."`
    where idDesignation ='".$stock['idDesignation']."'";
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $S_stock = mysql_fetch_array($resS);
  
    $date1 = strtotime($dateString); 
    $date2 = strtotime($stock['dateStockage']); 
  
    $sqlI="SELECT SUM(quantiteStockinitial*nbreArticleUniteStock)
    FROM `".$nomtableStock."`
    where idDesignation ='".$stock['idDesignation']."'  ";
    $resI=mysql_query($sqlI) or die ("select stock impossible =>".mysql_error());
    $I_stock = mysql_fetch_array($resI);
  
    $sqlE="SELECT SUM(quantiteStockinitial*nbreArticleUniteStock)
    FROM `".$nomtableEntrepotStock."`
    where idDesignation ='".$stock['idDesignation']."' AND (idTransfert=0 OR idTransfert IS NULL)   ";
    $resE=mysql_query($sqlE) or die ("select stock impossible =>".mysql_error());
    $E_stock = mysql_fetch_array($resE);

      echo '<tr>';
      // if($stock['idStock'] == max($tabIdStock)){
      if($stock['idStock'] == max($tabIdStock)){
        echo  '<td>'.$i.'</td>';
        echo  '<td><span style="color:blue;">'.strtoupper($designation['designation']).'</span></td>';
        echo  '<td><span style="color:blue;">'.strtoupper($designation['categorie']).'</span></td>';
        
        if($designation['nbreArticleUniteStock']!=0 || $designation['nbreArticleUniteStock']!=null){
          if($S_stock[0]!=0 && $S_stock[0]!=null){
            echo  '<td><span style="color:blue;">'.($S_stock[0] / $designation['nbreArticleUniteStock']) .'</span></td>';
          }
          else{
            echo  '<td><span style="color:blue;">'.$S_stock[0].'</span></td>';
          }
        }
        else{
          echo  '<td><span style="color:blue;">'.$S_stock[0].'</span></td>';
        }
        echo  '<td><span style="color:blue;">'.(($I_stock[0] - $E_stock[0])/$designation['nbreArticleUniteStock']).'</span></td>';
        echo  '<td><span style="color:blue;">'.strtoupper($designation['uniteStock']).'</span></td>';
        echo  '<td><span style="color:blue;">'.number_format($designation['prixuniteStock'], 0, ',', ' ').'</span></td>';
   
        echo  '<td><span style="color:blue;">'.number_format($designation['prixachat'], 0, ',', ' ').'</span></td>';
      }	
      else if($date1==$date2){
        echo  '<td>'.$i.'</td>';
        echo  '<td><span style="color:#901E06;">'.strtoupper($designation['designation']).'</span></td>';
        echo  '<td><span style="color:#901E06;">'.strtoupper($designation['categorie']).'</span></td>';
        if($designation['nbreArticleUniteStock']!=0 || $designation['nbreArticleUniteStock']!=null){
          echo  '<td><span style="color:#901E06;">'.($S_stock[0] / $designation['nbreArticleUniteStock']) .'</span></td>';
        }
        else{
          echo  '<td><span style="color:#901E06;">'.$S_stock[0].'</span></td>';
        }
        echo  '<td><span style="color:#901E06;">'.(($I_stock[0] - $E_stock[0])/$designation['nbreArticleUniteStock']).'</span></td>';
        echo  '<td><span style="color:#901E06;">'.strtoupper($designation['uniteStock']).'</span></td>';
        echo  '<td><span style="color:#901E06;">'.number_format($designation['prixuniteStock'], 0, ',', ' ').'</span></td>';
      
        echo  '<td><span style="color:#901E06;">'.number_format($designation['prixachat'], 0, ',', ' ').'</span></td>';
      }	
      else{
        echo  '<td>'.$i.'</td>';
        echo  '<td>'.strtoupper($designation['designation']).'</td>';
        echo  '<td>'.strtoupper($designation['categorie']).'</td>';
        if($designation['nbreArticleUniteStock']!=0 || $designation['nbreArticleUniteStock']!=null){
          if($S_stock[0]!=0 && $S_stock[0]!=null){
            echo  '<td>'.($S_stock[0] / $designation['nbreArticleUniteStock']).'</td>';
          }
          else{
            echo  '<td>'.$S_stock[0].'</td>';
          }
        }
        else{
          echo  '<td>'.$S_stock[0].'</td>';
        }
        echo  '<td>'.(($I_stock[0] - $E_stock[0])/$designation['nbreArticleUniteStock']).'</td>';
        echo  '<td>'.strtoupper($designation['uniteStock']).'</td>';
        echo  '<td>'.number_format($designation['prixuniteStock'], 0, ',', ' ').'</td>';
      //  $rows[] = $S_stock[0];
      //  $rows[] = $designation['uniteDetails'];
      echo  '<td>'.number_format($designation['prixachat'], 0, ',', ' ').'</td>'; 
      }	

      $sqlI="SELECT SUM(quantiteStockinitial*nbreArticleUniteStock)
      FROM `".$nomtableStock."`
      where idDesignation ='".$stock['idDesignation']."'  ";
      $resI=mysql_query($sqlI) or die ("select stock impossible =>".mysql_error());
      $I_stock = mysql_fetch_array($resI);

      $sqlE="SELECT SUM(quantiteStockinitial*nbreArticleUniteStock)
      FROM `".$nomtableEntrepotStock."`
      where idDesignation ='".$stock['idDesignation']."' AND (idTransfert=0 OR idTransfert IS NULL)   ";
      $resE=mysql_query($sqlE) or die ("select stock impossible =>".mysql_error());
      $E_stock = mysql_fetch_array($resE);

      if($I_stock[0]==$E_stock[0]){
        echo  '<td><a href="stock-Entrepot.php?iDS='.$stock["idDesignation"].'"><span style="color:green;">Details</span></a></td>';
      }
      else{
        echo  '<td><a href="stock-Entrepot.php?iDS='.$stock["idDesignation"].'"><span style="color:#901E06;">Details</span></a></td>';
      }

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


// $data=array();
// $produits=array();
// $i=1;
// while($stock=mysql_fetch_array($res)){

//   // $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
//   // $res1=mysql_query($sql1);
//   // $designation=mysql_fetch_array($res1);

//   // $sqlS="SELECT SUM(quantiteStockCourant)
//   // FROM `".$nomtableEntrepotStock."`
//   // where idDesignation ='".$stock['idDesignation']."'  ";
//   // $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
//   // $S_stock = mysql_fetch_array($resS);

//   // $date1 = strtotime($dateString); 
//   // $date2 = strtotime($stock['dateStockage']); 

//   // $sqlI="SELECT SUM(quantiteStockinitial)
//   // FROM `".$nomtableStock."`
//   // where idDesignation ='".$stock['idDesignation']."'  ";
//   // $resI=mysql_query($sqlI) or die ("select stock impossible =>".mysql_error());
//   // $I_stock = mysql_fetch_array($resI);

//   // $sqlE="SELECT SUM(quantiteStockinitial)
//   // FROM `".$nomtableEntrepotStock."`
//   // where idDesignation ='".$stock['idDesignation']."' AND (idTransfert=0 OR idTransfert IS NULL)   ";
//   // $resE=mysql_query($sqlE) or die ("select stock impossible =>".mysql_error());
//   // $E_stock = mysql_fetch_array($resE);

//   /*
//     $sqlD="SELECT SUM(quantiteStockinitial)
//     FROM `".$nomtableStock."`
//     where idDesignation ='".$stock['idDesignation']."'  ";
//     $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
//     $D_stock = mysql_fetch_array($resD);

//     $sqlF="SELECT SUM(quantite)
//     FROM  `".$nomtableLigne."` l
//     INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
//     where p.verrouiller=1 && p.type=0 && l.idDesignation='".$stock['idDesignation']."' ";
//     $resF=mysql_query($sqlF) or die ("select stock impossible =>".mysql_error());
//     $F_stock = mysql_fetch_array($resF);
//   */

//   if(in_array($designation['idDesignation'], $produits)){
//     // echo "Existe.";
//    }
//    else{

//   $rows = array();
//   if($i==1){
//     $rows[] = $i;
//     $rows[] = '<span style="color:blue;">'.strtoupper($designation['designation']).'</span>';
//     $rows[] = '<span style="color:blue;">'.strtoupper($designation['categorie']).'</span>';
//     if($designation['nbreArticleUniteStock']!=0 || $designation['nbreArticleUniteStock']!=null){
//       if($S_stock[0]!=0 && $S_stock[0]!=null){
//         $rows[] = '<span style="color:blue;">'.($S_stock[0] / $designation['nbreArticleUniteStock']) .'</span>';
//       }
//       else{
//         $rows[] = '<span style="color:blue;">'.$S_stock[0].'</span>';
//       }
//     }
//     else{
//       $rows[] = '<span style="color:blue;">'.$S_stock[0].'</span>';
//     }
//     $rows[] = '<span style="color:blue;">'.(($I_stock[0] - $E_stock[0])/$designation['nbreArticleUniteStock']).'</span>';
//     $rows[] = '<span style="color:blue;">'.strtoupper($designation['uniteStock']).'</span>';
//     $rows[] = '<span style="color:blue;">'.number_format($designation['prixuniteStock'], 0, ',', ' ').'</span>';
//    // $rows[] = '<span style="color:blue;">'.$S_stock[0] .'</span>';
//    // $rows[] = '<span style="color:blue;">'.$designation['uniteDetails'].'</span>';
//     $rows[] = '<span style="color:blue;">'.number_format($designation['prixachat'], 0, ',', ' ').'</span>';
//   }	
//   else if($date1==$date2){
//     $rows[] = $i;
//     $rows[] = '<span style="color:#901E06;">'.strtoupper($designation['designation']).'</span>';
//     $rows[] = '<span style="color:#901E06;">'.strtoupper($designation['categorie']).'</span>';
//     if($designation['nbreArticleUniteStock']!=0 || $designation['nbreArticleUniteStock']!=null){
//       $rows[] = '<span style="color:#901E06;">'.($S_stock[0] / $designation['nbreArticleUniteStock']) .'</span>';
//     }
//     else{
//       $rows[] = '<span style="color:#901E06;">'.$S_stock[0].'</span>';
//     }
//     $rows[] = '<span style="color:#901E06;">'.(($I_stock[0] - $E_stock[0])/$designation['nbreArticleUniteStock']).'</span>';
//     $rows[] = '<span style="color:#901E06;">'.strtoupper($designation['uniteStock']).'</span>';
//     $rows[] = '<span style="color:#901E06;">'.number_format($designation['prixuniteStock'], 0, ',', ' ').'</span>';
//    // $rows[] = '<span style="color:#901E06;">'.$S_stock[0] .'</span>';
//    // $rows[] = '<span style="color:#901E06;">'.$designation['uniteDetails'].'</span>';
//     $rows[] = '<span style="color:#901E06;">'.number_format($designation['prixachat'], 0, ',', ' ').'</span>';
//   }	
//   else{
//     $rows[] = $i;
//     $rows[] = strtoupper($designation['designation']);
//     $rows[] = strtoupper($designation['categorie']);
//     if($designation['nbreArticleUniteStock']!=0 || $designation['nbreArticleUniteStock']!=null){
//       if($S_stock[0]!=0 && $S_stock[0]!=null){
//         $rows[] = ($S_stock[0] / $designation['nbreArticleUniteStock']);
//       }
//       else{
//         $rows[] = $S_stock[0] ;
//       }
//     }
//     else{
//       $rows[] = $S_stock[0] ;
//     }
//     $rows[] = (($I_stock[0] - $E_stock[0])/$designation['nbreArticleUniteStock']);
//     $rows[] = strtoupper($designation['uniteStock']);
//     $rows[] = number_format($designation['prixuniteStock'], 0, ',', ' ');
//   //  $rows[] = $S_stock[0];
//   //  $rows[] = $designation['uniteDetails'];
//     $rows[] = number_format($designation['prixachat'], 0, ',', ' '); 
//   }	

//   $sqlI="SELECT SUM(quantiteStockinitial)
//   FROM `".$nomtableStock."`
//   where idDesignation ='".$stock['idDesignation']."'  ";
//   $resI=mysql_query($sqlI) or die ("select stock impossible =>".mysql_error());
//   $I_stock = mysql_fetch_array($resI);

//   $sqlE="SELECT SUM(quantiteStockinitial)
//   FROM `".$nomtableEntrepotStock."`
//   where idDesignation ='".$stock['idDesignation']."' AND (idTransfert=0 OR idTransfert IS NULL)   ";
//   $resE=mysql_query($sqlE) or die ("select stock impossible =>".mysql_error());
//   $E_stock = mysql_fetch_array($resE);

//   /*
//     $sqlV="SELECT * from `".$nomtableInventaire."` 
//     where idDesignation='".$stock['idDesignation']."' AND (type=0 || type=1) order by idInventaire desc";
//     $resV=mysql_query($sqlV);

//     $plus=0;
//     $moins=0;
//     while($inventaire=mysql_fetch_array($resV)){
//       if($inventaire['quantite'] > $inventaire['quantiteStockCourant'] || $inventaire['quantite'] < $inventaire['quantiteStockCourant']){
//         if($inventaire['quantite'] > $inventaire['quantiteStockCourant']){
//           $plus = $plus + (($inventaire['quantite'] - $inventaire['quantiteStockCourant']) / $inventaire['nbreArticleUniteStock']);
//         }
//         else if ($inventaire['quantite'] < $inventaire['quantiteStockCourant']) {
//           $moins = $moins + (($inventaire['quantiteStockCourant'] - $inventaire['quantite']) / $inventaire['nbreArticleUniteStock']);
//         }	
//       }
//     }
//       if($S_stock[0]!=0 && $S_stock[0]!=null){
//       $N_stock = $F_stock[0] + ($S_stock[0] / $designation['nbreArticleUniteStock']) - $plus + $moins + ($I_stock[0]-$E_stock[0]);
//     }
//     else{
//       $N_stock = $F_stock[0] + $S_stock[0] - $plus + $moins + ($I_stock[0]-$E_stock[0]);
//     }
//   */

//   if($I_stock[0]==$E_stock[0]){
//     /*
//       if($D_stock[0]==abs($N_stock)){
//         $rows[] = '<a href="stock-Entrepot.php?iDS='.$stock["idDesignation"].'"><span style="color:green;">Details</span></a>';
//       }
//       else{
//         $rows[] = '<a href="stock-Entrepot.php?iDS='.$stock["idDesignation"].'"><span style="color:red;">+Details '.$D_stock[0].'-'.$F_stock[0].'-'.$plus.'-'.$moins.' </span></a>';
//       }
//     */
//     $rows[] = '<a href="stock-Entrepot.php?iDS='.$stock["idDesignation"].'"><span style="color:green;">Details</span></a>';
//   }
//   else{
//     /*
//       if($D_stock[0]==abs($N_stock)){
//         $rows[] = '<a href="stock-Entrepot.php?iDS='.$stock["idDesignation"].'"><span style="color:#901E06;">Details</span></a>';
//       }
//       else{
//         $rows[] = '<a href="stock-Entrepot.php?iDS='.$stock["idDesignation"].'"><span style="color:red;">+Details '.$D_stock[0].'-'.$F_stock[0].'-'.$plus.'-'.$moins.'</span></a>';
//       }
//     */
//     $rows[] = '<a href="stock-Entrepot.php?iDS='.$stock["idDesignation"].'"><span style="color:#901E06;">Details</span></a>';
//   }


//   $data[] = $rows;
//   $i=$i + 1;
//   $produits[] = $designation['idDesignation'];
// }
// }


// $results = ["sEcho" => 1,
//           "iTotalRecords" => count($data),
//           "iTotalDisplayRecords" => count($data),
//           "aaData" => $data ];

// echo json_encode($results);

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


  // function paginate_function($item_per_page, $current_page, $total_records, $total_pages)
  // {

    //     $pagination = '';
    //     if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
    //         $pagination .= '<ul class="pagination">';
            
    //         $right_links    = $current_page + 3; 
    //         $previous       = $current_page - 3; //previous link 
    //         $next           = $current_page + 1; //next link
    //         $first_link     = true; //boolean var to decide our first link
            
    //         if($current_page > 1){
    //           $previous_link = ($previous==0)?1:$previous;
    //           $pagination .= '<li class="first"><a href="#" data-page="1" title="First">«</a></li>'; //first link
    //           $pagination .= '<li><a href="#" data-page="'.$previous_link.'" title="Previous"><</a></li>'; //previous link
    //               for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
    //                   if($i > 0){
    //                       $pagination .= '<li><a href="#" data-page="'.$i.'" title="Page'.$i.'">'.$i.'</a></li>';
    //                   }
    //               }   
    //           $first_link = false; //set first link to false
    //         }
            
    //         if($first_link){ //if current active page is first link
    //             $pagination .= '<li class="first active">'.$current_page.'</li>';
    //         }elseif($current_page == $total_pages){ //if it's the last active link
    //             $pagination .= '<li class="last active">'.$current_page.'</li>';
    //         }else{ //regular current link
    //             $pagination .= '<li class="active">'.$current_page.'</li>';
    //         }
                    
    //         for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
    //             if($i<=$total_pages){
    //                 $pagination .= '<li><a href="#" data-page="'.$i.'" title="Page '.$i.'">'.$i.'</a></li>';
    //             }
    //         }
    //         if($current_page < $total_pages){ 
    //           $next_link = ($i > $total_pages)? $total_pages : $i;
    //           $pagination .= '<li><a href="#" data-page="'.$next_link.'" title="Next">></a></li>'; //next link
    //           $pagination .= '<li class="last"><a href="#" data-page="'.$total_pages.'" title="Last">»</a></li>'; //last link
    //         }
            
    //         $pagination .= '</ul>'; 
    //     }
    //     return $pagination; //return pagination links
    // }

?>
