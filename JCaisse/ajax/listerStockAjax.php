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
require('../connectionPDO.php');

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
function cmp3($a, $b)
{
    return strcmp($b["idStock"], $a["idStock"]);
}
function sortScripts($a, $b)
{
    return $b['idStock'] - $a['idStock'];
}

$operation=@htmlspecialchars($_POST["operation"]);
$tri=@htmlspecialchars($_POST["tri"]);
// $produits=array();


if ($operation=="1" || $operation=="3" || $operation=="4") {

  $nbEntreeS = @$_POST["nbEntreeS"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page = ($nbEntreeS!=0) ? $nbEntreeS : 10; //item to display per page
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
    // $sql="SELECT sum(s.quantiteStockCourant) as S_stock, dateStockage, s.idDesignation, d.prix, d.prixuniteStock, d.prixachat, d.designation FROM `".$nomtableStock."` s
    // LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
    // WHERE d.classe=0 AND (quantiteStockCourant<>0 OR quantiteStockCourant=0) ORDER BY s.idStock DESC";
    // $res=mysql_query($sql);
    
    $sqlGetStock = $bdd->prepare("SELECT max(idStock) as idStock, sum(s.quantiteStockCourant) as S_stock, dateStockage, s.idDesignation, d.prix, d.prixuniteStock, d.prixachat, d.designation FROM `".$nomtableStock."` s
    LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
    WHERE d.classe=0 AND (quantiteStockCourant<>0 OR quantiteStockCourant=0) GROUP BY s.idDesignation ORDER BY s.idStock DESC");
    // $sqlGetStock = $bdd->prepare("SELECT sum(s.quantiteStockCourant), quantiteStockTemp, s.idDesignation FROM `".$nomtableStock."` s  WHERE designation LIKE '%".$query."%' GROUP BY s.idDesignation HAVING sum(s.quantiteStockCourant)>0 and sum(s.quantiteStockCourant)<>s.quantiteStockTemp ORDER BY s.quantiteStockTemp ASC");
    // var_dump($sqlGetStock);dateStockage
    $sqlGetStock->execute(array()) or die(print_r($sqlGetStock->errorInfo()));

  } else {
    # code... 
    if ($cb==1) {
      //Limit our results within a specified range. 
      // $sql="SELECT * FROM `".$nomtableStock."` s
      // LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
      // WHERE d.classe=0 AND (quantiteStockCourant<>0 OR quantiteStockCourant=0) and (d.codeBarreDesignation='".$query."') ORDER BY s.idStock DESC";
      // // WHERE d.classe=0 AND (quantiteStockCourant<>0 OR quantiteStockCourant=0) and (d.designation LIKE '%".$query."%' or d.codeBarreDesignation LIKE '%".$query."%') ORDER BY s.idStock DESC LIMIT $page_position, $item_per_page";
      // $res=mysql_query($sql);
      
      $sqlGetStock = $bdd->prepare("SELECT max(idStock) as idStock, sum(s.quantiteStockCourant) as S_stock, dateStockage, s.idDesignation, d.prix, d.prixuniteStock, d.prixachat, d.designation FROM `".$nomtableStock."` s
      LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
      WHERE d.classe=0 AND (quantiteStockCourant<>0 OR quantiteStockCourant=0) and d.codeBarreDesignation='".$query."' GROUP BY s.idDesignation ORDER BY s.idStock DESC");
      $sqlGetStock->execute(array()) or die(print_r($sqlGetStock->errorInfo()));

    } else {
      //Limit our results within a specified range. 
      // $sql="SELECT * FROM `".$nomtableStock."` s
      // LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
      // WHERE d.classe=0 AND (quantiteStockCourant<>0 OR quantiteStockCourant=0) and (d.designation LIKE '%".$query."%' or d.codeBarreDesignation LIKE '%".$query."%') ORDER BY s.idStock DESC";
      // // WHERE d.classe=0 AND (quantiteStockCourant<>0 OR quantiteStockCourant=0) and (d.designation LIKE '%".$query."%' or d.codeBarreDesignation LIKE '%".$query."%') ORDER BY s.idStock DESC LIMIT $page_position, $item_per_page";      
      // $res=mysql_query($sql);

      $sqlGetStock = $bdd->prepare("SELECT max(idStock) as idStock, sum(s.quantiteStockCourant) as S_stock, dateStockage, s.idDesignation, d.prix, d.prixuniteStock, d.prixachat, d.designation FROM `".$nomtableStock."` s
      LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
      WHERE d.classe=0 AND (quantiteStockCourant<>0 OR quantiteStockCourant=0) and (d.designation LIKE '%".$query."%' or d.codeBarreDesignation LIKE '%".$query."%') GROUP BY s.idDesignation ORDER BY s.idStock DESC");
      $sqlGetStock->execute(array()) or die(print_r($sqlGetStock->errorInfo()));
    }
  }

  // while($stock=mysql_fetch_array($res)){
  //   if (in_array($stock['idDesignation'], $tabIdDesigantion)) {
  //     # code...
  //   } else {
  //     # code...
  //     $sqlS="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation ='".$stock['idDesignation']."'  ";
  //     $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
  //     $S_stock = mysql_fetch_array($resS);

  //     $tabIdDesigantion[]=$stock['idDesignation'];
  //     $tabIdStock[]=$stock['idStock'];
  //     $stock["quantiteS"]=$S_stock[0];
  //     $produits[]=$stock;
  //   }
    
  // }
  $produits = $sqlGetStock->fetchAll();
  
  // var_dump($produits);

  usort($produits, "sortScripts");

  // var_dump($produits);
  //get total number of records 
  $total_rows = count($produits);

  $total_pages = ceil($total_rows/$item_per_page);

  //position of records
  $page_position = (($page_number-1) * $item_per_page);
  
  echo '
    <center style="margin-bottom :10px;  margin-top :10px;" class="btn_Impression"> 
    <button onclick="client_Excel()">
      <span class="glyphicon glyphicon-download"></span>
      EXCEL
    </button>  
    </center> 
  ';

  // var_dump(max($tabIdStock));

  //Display records fetched from database.
  echo '<table class="table table-striped contents display tabStock" id="tableStock" border="1">';
  echo '<thead>
          <tr id="thStock">
            <th>ORDRE</th>
            <th>REFERENCE</th>
            <th>CODEBARRE</th>
            <th>QUANTITE</th>
            <th>UNITE STOCK (US)</th>
            <th>NOMBRE ARTICLE U.S</th>
            <th>PRIX U.S</th>
            <th>PRIX UNITAIRE (PU)</th>
            <th>PRIX ACHAT (PA)</th>
            <th>OPERATIONS</th>
          </tr>
        </thead>';
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;

  $exportFile=$produits;

  $produits = array_slice($produits, $page_position, $item_per_page);
  // var_dump($produits);
  
  $sqlLastStock="SELECT * FROM `". $nomtableStock."` ORDER BY idStock DESC LIMIT 1";
  $resLS=mysql_query($sqlLastStock);
  $lastStock=mysql_fetch_array($resLS);
  $idMaxStock=$lastStock['idStock'];

  // $produits=array();
  foreach ($produits as $stock) {

    $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
    $res1=mysql_query($sql1);
    $designation=mysql_fetch_array($res1);
  
    $sqlS="SELECT * FROM `".$nomtableStock."` where idDesignation ='".$stock['idDesignation']."' order by idStock DESC LIMIT 1 ";
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $stockItem = mysql_fetch_array($resS);
  
    $date1 = strtotime($dateString); 
    $date2 = strtotime($stock['dateStockage']); 
    //  var_dump($stock);
    
    // if(in_array($designation['idDesignation'], $produits)){
    //   // echo "Existe.";
    // }
    // else{

      $S_stock=$stock['S_stock'];

      echo '<tr>';
      if($stockItem['idStock'] == $idMaxStock){
        echo  '<td>'.$i.'</td>';
        echo  '<td><span style="color:blue;">'.strtoupper($designation['designation']).'</span></td>';
        echo  '<td><span style="color:blue;">'.strtoupper($designation['codeBarreDesignation']).'</span></td>';
        if ($_SESSION['categorie']=="Grossiste") {
          if($S_stock!=0 && $S_stock!=null){
            echo  '<td><span style="color:blue;">'.round(($S_stock / $designation['nbreArticleUniteStock']),1) .'</span></td>';
          }
          else{
            echo  '<td><span style="color:blue;">'.$S_stock.'</span></td>';
          }
        }
        else{
          echo  '<td><span style="color:blue;">'.$S_stock.'</span></td>';
        }
        echo  '<td><span style="color:blue;">'.strtoupper($designation['uniteStock']).'</span></td>';
        echo  '<td><span style="color:blue;">'.$designation['nbreArticleUniteStock'].'</span></td>';
        echo  '<td><span style="color:blue;">'.$stock['prixuniteStock'].'</span></td>';
        echo  '<td><span style="color:blue;">'.$stock['prix'].'</span></td>';
        echo  '<td><span style="color:blue;">'.$designation['prixachat'].'</span></td>';
      }	
      else if($date1==$date2){
        echo  '<td>'.$i.'</td>';
        echo  '<td><span style="color:#901E06;">'.strtoupper($designation['designation']).'</span></td>';
        echo  '<td><span style="color:#901E06;">'.strtoupper($designation['codeBarreDesignation']).'</span></td>';
        if ($_SESSION['categorie']=="Grossiste") {
          if($S_stock!=0 && $S_stock!=null){
            echo  '<td><span style="color:#901E06;">'.round(($S_stock / $designation['nbreArticleUniteStock']),1) .'</span></td>';
          }
          else{
            echo  '<td><span style="color:#901E06;">'.$S_stock.'</span></td>';
          }
        }
        else{
          echo  '<td><span style="color:#901E06;">'.$S_stock.'</span></td>';
        }
        echo  '<td><span style="color:#901E06;">'.strtoupper($designation['uniteStock']).'</span></td>';
        echo  '<td><span style="color:#901E06;">'.$designation['nbreArticleUniteStock'].'</span></td>';
        echo  '<td><span style="color:#901E06;">'.$stock['prixuniteStock'].'</span></td>';
        echo  '<td><span style="color:#901E06;">'.$stock['prix'].'</span></td>';
        echo  '<td><span style="color:#901E06;">'.$designation['prixachat'].'</span></td>';
      }	
      else{
        echo  '<td>'.$i.'</td>';
        echo  '<td>'.strtoupper($designation['designation']).'</td>';
        echo  '<td>'.strtoupper($designation['codeBarreDesignation']).'</td>';
        if ($_SESSION['categorie']=="Grossiste") {
          if($S_stock!=0 && $S_stock!=null){
            echo  '<td>'.round(($S_stock / $designation['nbreArticleUniteStock']),1).'</td>';
          }
          else{
            echo  '<td>'.$S_stock.'</td>' ;
          }
        }
        else{
          echo  '<td>'.$S_stock.'</td>' ;
        }
        echo  '<td>'.strtoupper($designation['uniteStock']).'</td>';
        echo  '<td>'.$designation['nbreArticleUniteStock'].'</td>';
        echo  '<td>'.$stock['prixuniteStock'].'</td>';
        echo  '<td>'.$stock['prix'].'</td>';
        echo  '<td>'.$designation['prixachat'].'</td>'; 
      }	
      if($S_stock!=0){
        
        echo  '<td><a href="stock.php?iDS='.$stock["idDesignation"].'"><span style="color:green;">Details</span>';
        if ($_SESSION['idBoutique'] == 145 || $_SESSION['idBoutique'] == 111) {
          // var_dump($S_stock.'//'.$stockItem['quantiteStockTemp']);
            if ($S_stock> 2 && $stockItem['quantiteStockTemp'] <= 2) {

            echo '<span id="intro2" style="margin-top: -5px;margin-right: 0px;border-radius: 50%;background: red;position: absolute;" class="badge bg-warning intro2">N</span>';

          } else if ($S_stock <= 2) {

          }
          else {

            echo '<span id="intro2" style="margin-top: -5px;margin-right: 0px;border-radius: 50%;background: green;position: absolute;" class="badge bg-warning intro2">N</span>';
          }
        }

        echo '</a></td>';
      }
      else{
        echo  '<td><a href="stock.php?iDS='.$stock["idDesignation"].'"><span style="color:#901E06;">Details</span></a></td>';
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
    echo  '<td colspan="10" align="center">Données introuvables!</td>';
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

  echo '
  <script>
      $(document).ready(function() {    

        var table = '.json_encode($exportFile).';
    
          $(function(){
            client_Excel= function ()  {
                /* Declaring array variable */
                var rows =[];
                rows.push(
                  [
                      "REFERENCE",
                      "PRIX U.S",
                      "PRIX UNITAIRE",
                      "PRIX ACHAT",
                      "QUANTITE"
                  ]
              );
            
                var taille = table.length;
                for(var i = 0; i<taille; i++){

                  column1 = ""+table[i]["designation"]+"";
                  column2 = table[i]["prixuniteStock"];
                  column3 = table[i]["prix"];
                  column4 = table[i]["prixachat"];
                  column5 = table[i]["S_stock"];
          
                  /* add a new records in the array */
                  rows.push(
                      [
                          column1,
                          column2,
                          column3,
                          column4,
                          column5
                      ]
                  );
              
            
                }


                csvContent = "data:text/csv;charset=UTF-8,";
                /* add the column delimiter as comma(;) and each row splitted by new line character (\n) */
                rows.forEach(function(rowArray){
                    row = rowArray.join(";");
                    csvContent += row + "\r\n";
                });

                console.log(csvContent)
        
                /* create a hidden <a> DOM node and set its download attribute */
                
                var encodedUri = encodeURI(csvContent);
                var link = document.createElement("a");
                link.setAttribute("href", encodedUri);
                link.setAttribute("download", "stock.csv");
                document.body.appendChild(link);
                /* download the data file named "Stock_Price_Report.csv" */
                link.click();

            }
          });

      });
  </script>
  ';

}

/*****  Tri **/
else if ($operation=="2") {
  # code...
  $nbEntreeS = @$_POST["nbEntreeS"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page = ($nbEntreeS!=0) ? $nbEntreeS : 10;//item to display per page
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
    // $sql="SELECT sum(s.quantiteStockCourant) as S_stock, dateStockage, s.idDesignation, d.prix, d.prixuniteStock, d.prixachat, d.designation FROM `".$nomtableStock."` s
    // LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
    // WHERE d.classe=0 AND (quantiteStockCourant<>0 OR quantiteStockCourant=0) ORDER BY s.idStock DESC";
    // $res=mysql_query($sql);
    
    $sqlGetStock = $bdd->prepare("SELECT max(idStock) as idStock, sum(s.quantiteStockCourant) as S_stock, dateStockage, s.idDesignation, d.prix, d.prixuniteStock, d.prixachat, d.designation FROM `".$nomtableStock."` s
    LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
    WHERE d.classe=0 AND (quantiteStockCourant<>0 OR quantiteStockCourant=0) GROUP BY s.idDesignation ORDER BY s.idStock DESC");
    // $sqlGetStock = $bdd->prepare("SELECT sum(s.quantiteStockCourant), quantiteStockTemp, s.idDesignation FROM `".$nomtableStock."` s  WHERE designation LIKE '%".$query."%' GROUP BY s.idDesignation HAVING sum(s.quantiteStockCourant)>0 and sum(s.quantiteStockCourant)<>s.quantiteStockTemp ORDER BY s.quantiteStockTemp ASC");
    // var_dump($sqlGetStock);dateStockage
    $sqlGetStock->execute(array()) or die(print_r($sqlGetStock->errorInfo()));

  } else {
    # code... 
    if ($cb==1) {
      //Limit our results within a specified range. 
      // $sql="SELECT * FROM `".$nomtableStock."` s
      // LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
      // WHERE d.classe=0 AND (quantiteStockCourant<>0 OR quantiteStockCourant=0) and (d.codeBarreDesignation='".$query."') ORDER BY s.idStock DESC";
      // // WHERE d.classe=0 AND (quantiteStockCourant<>0 OR quantiteStockCourant=0) and (d.designation LIKE '%".$query."%' or d.codeBarreDesignation LIKE '%".$query."%') ORDER BY s.idStock DESC LIMIT $page_position, $item_per_page";
      // $res=mysql_query($sql);
      
      $sqlGetStock = $bdd->prepare("SELECT max(idStock) as idStock, sum(s.quantiteStockCourant) as S_stock, dateStockage, s.idDesignation, d.prix, d.prixuniteStock, d.prixachat, d.designation FROM `".$nomtableStock."` s
      LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
      WHERE d.classe=0 AND (quantiteStockCourant<>0 OR quantiteStockCourant=0) and d.codeBarreDesignation='".$query."' GROUP BY s.idDesignation ORDER BY s.idStock DESC");
      $sqlGetStock->execute(array()) or die(print_r($sqlGetStock->errorInfo()));

    } else {
      //Limit our results within a specified range. 
      // $sql="SELECT * FROM `".$nomtableStock."` s
      // LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
      // WHERE d.classe=0 AND (quantiteStockCourant<>0 OR quantiteStockCourant=0) and (d.designation LIKE '%".$query."%' or d.codeBarreDesignation LIKE '%".$query."%') ORDER BY s.idStock DESC";
      // // WHERE d.classe=0 AND (quantiteStockCourant<>0 OR quantiteStockCourant=0) and (d.designation LIKE '%".$query."%' or d.codeBarreDesignation LIKE '%".$query."%') ORDER BY s.idStock DESC LIMIT $page_position, $item_per_page";      
      // $res=mysql_query($sql);

      $sqlGetStock = $bdd->prepare("SELECT max(idStock) as idStock, sum(s.quantiteStockCourant) as S_stock, dateStockage, s.idDesignation, d.prix, d.prixuniteStock, d.prixachat, d.designation FROM `".$nomtableStock."` s
      LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
      WHERE d.classe=0 AND (quantiteStockCourant<>0 OR quantiteStockCourant=0) and (d.designation LIKE '%".$query."%' or d.codeBarreDesignation LIKE '%".$query."%') GROUP BY s.idDesignation ORDER BY s.idStock DESC");
      $sqlGetStock->execute(array()) or die(print_r($sqlGetStock->errorInfo()));
    }
  }

  // while($stock=mysql_fetch_array($res)){
  //   if (in_array($stock['idDesignation'], $tabIdDesigantion)) {
  //     # code...
  //   } else {
  //     # code...
  //     $sqlS="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation ='".$stock['idDesignation']."'  ";
  //     $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
  //     $S_stock = mysql_fetch_array($resS);

  //     $tabIdDesigantion[]=$stock['idDesignation'];
  //     $tabIdStock[]=$stock['idStock'];
  //     $stock["quantiteS"]=$S_stock[0];
  //     $produits[]=$stock;
  //   }
    
  // }
  $produits = $sqlGetStock->fetchAll();
  
  // var_dump($produits);

  usort($produits, "sortScripts");

  // var_dump($produits);
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
  
  echo '
    <center style="margin-bottom :10px;  margin-top :10px;" class="btn_Impression"> 
    <button onclick="client_Excel()">
      <span class="glyphicon glyphicon-download"></span>
      EXCEL
    </button>  
    </center> 
  ';

  // var_dump(max($tabIdStock));

  //Display records fetched from database.
  echo '<table class="table table-striped contents display tabStock" id="tableStock" border="1">';
  echo '<thead>
          <tr id="thStock">
            <th>ORDRE</th>
            <th>REFERENCE</th>
            <th>CODEBARRE</th>
            <th>QUANTITE</th>
            <th>UNITE STOCK (US)</th>
            <th>NOMBRE ARTICLE U.S</th>
            <th>PRIX U.S</th>
            <th>PRIX UNITAIRE (PU)</th>
            <th>PRIX ACHAT (PA)</th>
            <th>OPERATIONS</th>
          </tr>
        </thead>';
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;

  $exportFile=$produits;

  $produits = array_slice($produits, $page_position, $item_per_page);
  // var_dump($produits);
  
  $sqlLastStock="SELECT * FROM `". $nomtableStock."` ORDER BY idStock DESC LIMIT 1";
  $resLS=mysql_query($sqlLastStock);
  $lastStock=mysql_fetch_array($resLS);
  $idMaxStock=$lastStock['idStock'];

  // $produits=array();
  foreach ($produits as $stock) {

    $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
    $res1=mysql_query($sql1);
    $designation=mysql_fetch_array($res1);
  
    $sqlS="SELECT * FROM `".$nomtableStock."` where idDesignation ='".$stock['idDesignation']."' order by idStock DESC LIMIT 1 ";
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $stockItem = mysql_fetch_array($resS);
  
    $date1 = strtotime($dateString); 
    $date2 = strtotime($stock['dateStockage']); 
    //  var_dump($stock);
    
    // if(in_array($designation['idDesignation'], $produits)){
    //   // echo "Existe.";
    // }
    // else{

      $S_stock=$stock['S_stock'];

      echo '<tr>';
      if($stockItem['idStock'] == $idMaxStock){
        echo  '<td>'.$i.'</td>';
        echo  '<td><span style="color:blue;">'.strtoupper($designation['designation']).'</span></td>';
        echo  '<td><span style="color:blue;">'.strtoupper($designation['codeBarreDesignation']).'</span></td>';
        if ($_SESSION['categorie']=="Grossiste") {
          if($S_stock!=0 && $S_stock!=null){
            echo  '<td><span style="color:blue;">'.round(($S_stock / $designation['nbreArticleUniteStock']),1) .'</span></td>';
          }
          else{
            echo  '<td><span style="color:blue;">'.$S_stock.'</span></td>';
          }
        }
        else{
          echo  '<td><span style="color:blue;">'.$S_stock.'</span></td>';
        }
        echo  '<td><span style="color:blue;">'.strtoupper($designation['uniteStock']).'</span></td>';
        echo  '<td><span style="color:blue;">'.$designation['nbreArticleUniteStock'].'</span></td>';
        echo  '<td><span style="color:blue;">'.$stock['prixuniteStock'].'</span></td>';
        echo  '<td><span style="color:blue;">'.$stock['prix'].'</span></td>';
        echo  '<td><span style="color:blue;">'.$designation['prixachat'].'</span></td>';
      }	
      else if($date1==$date2){
        echo  '<td>'.$i.'</td>';
        echo  '<td><span style="color:#901E06;">'.strtoupper($designation['designation']).'</span></td>';
        echo  '<td><span style="color:#901E06;">'.strtoupper($designation['codeBarreDesignation']).'</span></td>';
        if ($_SESSION['categorie']=="Grossiste") {
          if($S_stock!=0 && $S_stock!=null){
            echo  '<td><span style="color:#901E06;">'.round(($S_stock / $designation['nbreArticleUniteStock']),1) .'</span></td>';
          }
          else{
            echo  '<td><span style="color:#901E06;">'.$S_stock.'</span></td>';
          }
        }
        else{
          echo  '<td><span style="color:#901E06;">'.$S_stock.'</span></td>';
        }
        echo  '<td><span style="color:#901E06;">'.strtoupper($designation['uniteStock']).'</span></td>';
        echo  '<td><span style="color:#901E06;">'.$designation['nbreArticleUniteStock'].'</span></td>';
        echo  '<td><span style="color:#901E06;">'.$stock['prixuniteStock'].'</span></td>';
        echo  '<td><span style="color:#901E06;">'.$stock['prix'].'</span></td>';
        echo  '<td><span style="color:#901E06;">'.$designation['prixachat'].'</span></td>';
      }	
      else{
        echo  '<td>'.$i.'</td>';
        echo  '<td>'.strtoupper($designation['designation']).'</td>';
        echo  '<td>'.strtoupper($designation['codeBarreDesignation']).'</td>';
        if ($_SESSION['categorie']=="Grossiste") {
          if($S_stock!=0 && $S_stock!=null){
            echo  '<td>'.round(($S_stock / $designation['nbreArticleUniteStock']),1).'</td>';
          }
          else{
            echo  '<td>'.$S_stock.'</td>' ;
          }
        }
        else{
          echo  '<td>'.$S_stock.'</td>' ;
        }
        echo  '<td>'.strtoupper($designation['uniteStock']).'</td>';
        echo  '<td>'.$designation['nbreArticleUniteStock'].'</td>';
        echo  '<td>'.$stock['prixuniteStock'].'</td>';
        echo  '<td>'.$stock['prix'].'</td>';
        echo  '<td>'.$designation['prixachat'].'</td>'; 
      }	
      if($S_stock!=0){
        
        echo  '<td><a href="stock.php?iDS='.$stock["idDesignation"].'"><span style="color:green;">Details</span>';
        if ($_SESSION['idBoutique'] == 145 || $_SESSION['idBoutique'] == 111) {
          // var_dump($S_stock.'//'.$stockItem['quantiteStockTemp']);
            if ($S_stock> 2 && $stockItem['quantiteStockTemp'] <= 2) {

            echo '<span id="intro2" style="margin-top: -5px;margin-right: 0px;border-radius: 50%;background: red;position: absolute;" class="badge bg-warning intro2">N</span>';

          } else if ($S_stock <= 2) {

          }
          else {

            echo '<span id="intro2" style="margin-top: -5px;margin-right: 0px;border-radius: 50%;background: green;position: absolute;" class="badge bg-warning intro2">N</span>';
          }
        }

        echo '</a></td>';
      }
      else{
        echo  '<td><a href="stock.php?iDS='.$stock["idDesignation"].'"><span style="color:#901E06;">Details</span></a></td>';
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
    echo  '<td colspan="10" align="center">Données introuvables!</td>';
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

  echo '
  <script>
      $(document).ready(function() {    

        var table = '.json_encode($exportFile).';
    
          $(function(){
            client_Excel= function ()  {
                /* Declaring array variable */
                var rows =[];
                rows.push(
                  [
                      "REFERENCE",
                      "PRIX U.S",
                      "PRIX UNITAIRE",
                      "PRIX ACHAT",
                      "QUANTITE"
                  ]
              );
            
                var taille = table.length;
                for(var i = 0; i<taille; i++){

                  column1 = ""+table[i]["designation"]+"";
                  column2 = table[i]["prixuniteStock"];
                  column3 = table[i]["prix"];
                  column4 = table[i]["prixachat"];
                  column5 = table[i]["S_stock"];
          
                  /* add a new records in the array */
                  rows.push(
                      [
                          column1,
                          column2,
                          column3,
                          column4,
                          column5
                      ]
                  );
              
            
                }


                csvContent = "data:text/csv;charset=UTF-8,";
                /* add the column delimiter as comma(;) and each row splitted by new line character (\n) */
                rows.forEach(function(rowArray){
                    row = rowArray.join(";");
                    csvContent += row + "\r\n";
                });

                console.log(csvContent)
        
                /* create a hidden <a> DOM node and set its download attribute */
                
                var encodedUri = encodeURI(csvContent);
                var link = document.createElement("a");
                link.setAttribute("href", encodedUri);
                link.setAttribute("download", "stock.csv");
                document.body.appendChild(link);
                /* download the data file named "Stock_Price_Report.csv" */
                link.click();

            }
          });

      });
  </script>
  ';
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