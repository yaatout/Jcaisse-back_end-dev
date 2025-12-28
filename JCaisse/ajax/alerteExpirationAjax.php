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
$date2= date('Y-m-d', strtotime('+3 month'));
$date3= date('Y-m-d', strtotime('+6 month'));
$date4= date('Y-m-d', strtotime('+12 month'));
//$date5= date('Y-m-d', strtotime('+1 month'));


if ($operation=="1" || $operation=="3" || $operation=="4") {

  $nbEntreeAlerteExp = @$_POST["nbEntreeAlerteExp"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page 		= ($nbEntreeAlerteExp!=0) ? $nbEntreeAlerteExp : 10; //item to display per page
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
      $sql1="SELECT * from  `".$nomtableStock."` s
      LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation  where s.dateExpiration !='' and s.dateExpiration !='0000-00-00' and s.dateExpiration <= '".$date1."' order by s.dateExpiration ASC";
      $res1=mysql_query($sql1);

      $sql2="SELECT * from  `".$nomtableStock."` s
      LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation where s.dateExpiration !='' and s.dateExpiration BETWEEN '".$date1."' AND '".$date2."' order by s.dateExpiration ASC";
      $res2=mysql_query($sql2);

      $sql3="SELECT * from  `".$nomtableStock."` s
      LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation where s.dateExpiration !='' and s.dateExpiration BETWEEN '".$date2."' AND '".$date3."' order by s.dateExpiration ASC";
      $res3=mysql_query($sql3);

      $sql4="SELECT * from  `".$nomtableStock."` s
      LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation where s.dateExpiration !='' and s.dateExpiration BETWEEN '".$date3."' AND '".$date4."' order by s.dateExpiration ASC";
      $res4=mysql_query($sql4);

      $sql5="SELECT * from  `".$nomtableStock."` s
      LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation where s.dateExpiration !='' and s.dateExpiration > '".$date4."' order by s.dateExpiration ASC";
      $res5=mysql_query($sql5);
      
  } else {
    # code...
    //Limit our results within a specified range. 
      $sql1="SELECT * from  `".$nomtableStock."`s 
      LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation where s.dateExpiration !='' and s.dateExpiration !='0000-00-00' and s.dateExpiration <= '".$date1."'
      and (d.designation LIKE '%".$query."%' or d.codeBarreDesignation LIKE '%".$query."%')";
      $res1=mysql_query($sql1);

      $sql2="SELECT * from  `".$nomtableStock."` s
      LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation where s.dateExpiration !='' and s.dateExpiration BETWEEN '".$date1."' AND '".$date2."' 
      and (d.designation LIKE '%".$query."%' or d.codeBarreDesignation LIKE '%".$query."%')";
      $res2=mysql_query($sql2);

      $sql3="SELECT * from  `".$nomtableStock."` s 
      LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation where s.dateExpiration !='' and s.dateExpiration BETWEEN '".$date2."' AND '".$date3."' 
      and (d.designation LIKE '%".$query."%' or d.codeBarreDesignation LIKE '%".$query."%')  order by s.dateExpiration DESC ";
      $res3=mysql_query($sql3);

      $sql4="SELECT * from  `".$nomtableStock."` s 
      LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation where s.dateExpiration !='' and s.dateExpiration BETWEEN '".$date3."' AND '".$date4."' 
      and (d.designation LIKE '%".$query."%' or d.codeBarreDesignation LIKE '%".$query."%')  order by s.dateExpiration DESC ";
      $res4=mysql_query($sql4);

      $sql5="SELECT * from  `".$nomtableStock."` s 
      LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation where s.dateExpiration !='' and s.dateExpiration > '".$date4." ' 
      and (d.designation LIKE '%".$query."%' or d.codeBarreDesignation LIKE '%".$query."%') order by s.dateExpiration DESC";
      $res5=mysql_query($sql5);

  }
  
  $data=array();

  //Ajout de -1 jours
  //$date1=date('Y-m-d', strtotime('-1 days'));  
  //$sql="SELECT * from  `".$nomtableStock."` where dateExpiration !='' and dateExpiration !='0000-00-00' and dateExpiration <= '".$date1."'";
  //$res=mysql_query($sql);
  //if(mysql_num_rows($res1)){
    while($tab=mysql_fetch_array($res1)){
      // $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$tab['idDesignation']."'";
      // $res1=mysql_query($sql1);
      if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) { 

        $sqlS="SELECT SUM(quantiteStockCourant) FROM `".$nomtableEntrepotStock."`
        where idStock ='".$tab['idStock']."' ";
        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
        $S_stock = mysql_fetch_array($resS);
        
        $sqlI="SELECT SUM(quantiteStockinitial) FROM `".$nomtableStock."`
        where idStock ='".$tab['idStock']."' ";
        $resI=mysql_query($sqlI) or die ("select stock impossible =>".mysql_error());
        $I_stock = mysql_fetch_array($resI);
        
        $sqlE="SELECT SUM(quantiteStockinitial) FROM `".$nomtableEntrepotStock."`
        where idStock ='".$tab['idStock']."' AND (idTransfert=0 OR idTransfert IS NULL)  ";
        $resE=mysql_query($sqlE) or die ("select stock impossible =>".mysql_error());
        $E_stock = mysql_fetch_array($resE);

        if($I_stock[0]==$E_stock[0]){
          if($S_stock[0]!=0){
            $rows = array();
            $rows[] = '<span style="color:#757561;">'.$tab["dateExpiration"].'</span>';
            $rows[] = '<span style="color:#757561;">'.strtoupper($tab['designation']).'</span>';
            $rows[] = '<span style="color:#757561;">'.($S_stock[0]/$tab['nbreArticleUniteStock']).'</span>';
            $rows[] = '<span style="color:#757561;">EXPIRE</span>';
            $rows[] = '<button type="button" onclick="rtr_Stock('.$tab["idStock"].')" id="btn_RetirerStock-'.$tab['idStock'].'" class="btn btn-danger" >
              <i class="glyphicon glyphicon-transfer"></i> RETIRER
              </button>';
    
            $data[] = $rows;
          }
        }
        else{
          $rows = array();
          $rows[] = '<span style="color:#757561;">'.$tab["dateExpiration"].'</span>';
          $rows[] = '<span style="color:#757561;">'.strtoupper($tab['designation']).'</span>';
          $rows[] = '<span style="color:#757561;">'.($I_stock[0] - $E_stock[0] + ($S_stock[0] / $tab['nbreArticleUniteStock'])).'</span>';
          $rows[] = '<span style="color:#757561;">EXPIRE</span>';
          $rows[] = '<button type="button" onclick="rtr_Stock('.$tab["idStock"].')" id="btn_RetirerStock-'.$tab['idStock'].'" class="btn btn-danger" >
            <i class="glyphicon glyphicon-transfer"></i> RETIRER
            </button>';
  
          $data[] = $rows;
        }

      }
      else{
        if($tab["quantiteStockCourant"]!=0){
          $rows = array();
          $rows[] = '<span style="color:red;">'.$tab["dateExpiration"].'</span>';
          $rows[] = '<span style="color:red;">'.strtoupper($tab['designation']).'</span>';
          $rows[] = '<span style="color:red;">'.$tab['quantiteStockCourant'].'</span>';
          $rows[] = '<span style="color:red;">EXPIRE</span>';
          $rows[] = '<button type="button" onclick="rtr_Stock('.$tab["idStock"].')" id="btn_RetirerStock-'.$tab['idStock'].'" class="btn btn-danger" >
            <i class="glyphicon glyphicon-transfer"></i> RETIRER
            </button>';
  
          $data[] = $rows;
        }
      }
    }

    while($tab=mysql_fetch_array($res2)){
      if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) { 

        $sqlS="SELECT SUM(quantiteStockCourant) FROM `".$nomtableEntrepotStock."`
        where idStock ='".$tab['idStock']."' ";
        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
        $S_stock = mysql_fetch_array($resS);
        
        $sqlI="SELECT SUM(quantiteStockinitial) FROM `".$nomtableStock."`
        where idStock ='".$tab['idStock']."' ";
        $resI=mysql_query($sqlI) or die ("select stock impossible =>".mysql_error());
        $I_stock = mysql_fetch_array($resI);
        
        $sqlE="SELECT SUM(quantiteStockinitial) FROM `".$nomtableEntrepotStock."`
        where idStock ='".$tab['idStock']."' AND (idTransfert=0 OR idTransfert IS NULL)  ";
        $resE=mysql_query($sqlE) or die ("select stock impossible =>".mysql_error());
        $E_stock = mysql_fetch_array($resE);

        if($I_stock[0]==$E_stock[0]){
          if($S_stock[0]!=0){
            $rows = array();
            $rows[] = '<span style="color:red;">'.$tab["dateExpiration"].'</span>';
            $rows[] = '<span style="color:red;">'.strtoupper($tab['designation']).'</span>';
            $rows[] = '<span style="color:red;">'.($S_stock[0]/$tab['nbreArticleUniteStock']).'</span>';
            $rows[] = '<span style="color:red;">CONSEILLER</span>';
            $rows[] = '<button type="button" onclick="rtr_Stock('.$tab["idStock"].')" id="btn_RetirerStock-'.$tab['idStock'].'" class="btn btn-danger" >
              <i class="glyphicon glyphicon-transfer"></i> RETIRER
              </button>';
    
            $data[] = $rows;
          }
        }
        else{
          $rows = array();
          $rows[] = '<span style="color:red;">'.$tab["dateExpiration"].'</span>';
          $rows[] = '<span style="color:red;">'.strtoupper($tab['designation']).'</span>';
          $rows[] = '<span style="color:red;">'.($I_stock[0] - $E_stock[0] + ($S_stock[0] / $tab['nbreArticleUniteStock'])).'</span>';
          $rows[] = '<span style="color:red;">CONSEILLER</span>';
          $rows[] = '<button type="button" onclick="rtr_Stock('.$tab["idStock"].')" id="btn_RetirerStock-'.$tab['idStock'].'" class="btn btn-danger" >
            <i class="glyphicon glyphicon-transfer"></i> RETIRER
            </button>';
  
          $data[] = $rows;
        }

      }
      else{
        if($tab["quantiteStockCourant"]!=0){
          $rows = array();
          $rows[] = '<span style="color:orange;">'.$tab["dateExpiration"].'</span>';
          $rows[] = '<span style="color:orange;">'.strtoupper($tab['designation']).'</span>';
          $rows[] = '<span style="color:orange;">'.$tab['quantiteStockCourant'].'</span>';
          $rows[] = '<span style="color:orange;">CONSEILLER</span>';
          $rows[] = '<button type="button" onclick="rtr_Stock('.$tab["idStock"].')" id="btn_RetirerStock-'.$tab['idStock'].'" class="btn btn-danger" >
          <i class="glyphicon glyphicon-transfer"></i> RETIRER
          </button>';
          $data[] = $rows;
        }
      }
    }

    while($tab=mysql_fetch_array($res3)){
      if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) { 

        $sqlS="SELECT SUM(quantiteStockCourant) FROM `".$nomtableEntrepotStock."`
        where idStock ='".$tab['idStock']."' ";
        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
        $S_stock = mysql_fetch_array($resS);
        
        $sqlI="SELECT SUM(quantiteStockinitial) FROM `".$nomtableStock."`
        where idStock ='".$tab['idStock']."' ";
        $resI=mysql_query($sqlI) or die ("select stock impossible =>".mysql_error());
        $I_stock = mysql_fetch_array($resI);
        
        $sqlE="SELECT SUM(quantiteStockinitial) FROM `".$nomtableEntrepotStock."`
        where idStock ='".$tab['idStock']."' AND (idTransfert=0 OR idTransfert IS NULL)  ";
        $resE=mysql_query($sqlE) or die ("select stock impossible =>".mysql_error());
        $E_stock = mysql_fetch_array($resE);

        if($I_stock[0]==$E_stock[0]){
          if($S_stock[0]!=0){
            $rows = array();
            $rows[] = '<span style="color:#1b44fc;">'.$tab["dateExpiration"].'</span>';
            $rows[] = '<span style="color:#1b44fc;">'.strtoupper($tab['designation']).'</span>';
            $rows[] = '<span style="color:#1b44fc;">'.($S_stock[0]/$tab['nbreArticleUniteStock']).'</span>';
            $rows[] = '<span style="color:#1b44fc;">CONSEILLER</span>';
            $rows[] = '<button type="button" onclick="rtr_Stock('.$tab["idStock"].')" id="btn_RetirerStock-'.$tab['idStock'].'" class="btn btn-danger" >
              <i class="glyphicon glyphicon-transfer"></i> RETIRER
              </button>';
    
            $data[] = $rows;
          }
        }
        else{
          $rows = array();
          $rows[] = '<span style="color:#1b44fc;">'.$tab["dateExpiration"].'</span>';
          $rows[] = '<span style="color:#1b44fc;">'.strtoupper($tab['designation']).'</span>';
          $rows[] = '<span style="color:#1b44fc;">'.($I_stock[0] - $E_stock[0] + ($S_stock[0] / $tab['nbreArticleUniteStock'])).'</span>';
          $rows[] = '<span style="color:#1b44fc;">CONSEILLER</span>';
          $rows[] = '<button type="button" onclick="rtr_Stock('.$tab["idStock"].')" id="btn_RetirerStock-'.$tab['idStock'].'" class="btn btn-danger" >
            <i class="glyphicon glyphicon-transfer"></i> RETIRER
            </button>';
  
          $data[] = $rows;
        }

      }
      else{
        if($tab["quantiteStockCourant"]!=0){
          $rows = array();
          $rows[] = '<span style="color:#F88811;">'.$tab["dateExpiration"].'</span>';
          $rows[] = '<span style="color:#F88811;">'.strtoupper($tab['designation']).'</span>';
          $rows[] = '<span style="color:##F88811;">'.$tab['quantiteStockCourant'].'</span>';
          $rows[] = '<span style="color:#F88811;">CONSEILLER</span>';
          $rows[] = '<button type="button" onclick="rtr_Stock('.$tab["idStock"].')" id="btn_RetirerStock-'.$tab['idStock'].'" class="btn btn-warning" >
            <i class="glyphicon glyphicon-transfer"></i> RETIRER
            </button>';
          $data[] = $rows;
        }
      }
    //}
    }
    
    while($tab=mysql_fetch_array($res4)){
      if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) { 

        $sqlS="SELECT SUM(quantiteStockCourant) FROM `".$nomtableEntrepotStock."`
        where idStock ='".$tab['idStock']."' ";
        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
        $S_stock = mysql_fetch_array($resS);
        
        $sqlI="SELECT SUM(quantiteStockinitial) FROM `".$nomtableStock."`
        where idStock ='".$tab['idStock']."' ";
        $resI=mysql_query($sqlI) or die ("select stock impossible =>".mysql_error());
        $I_stock = mysql_fetch_array($resI);
        
        $sqlE="SELECT SUM(quantiteStockinitial) FROM `".$nomtableEntrepotStock."`
        where idStock ='".$tab['idStock']."' AND (idTransfert=0 OR idTransfert IS NULL)  ";
        $resE=mysql_query($sqlE) or die ("select stock impossible =>".mysql_error());
        $E_stock = mysql_fetch_array($resE);

        if($I_stock[0]==$E_stock[0]){
          if($S_stock[0]!=0){
            $rows = array();
            $rows[] = '<span style="color:green;">'.$tab["dateExpiration"].'</span>';
            $rows[] = '<span style="color:green;">'.strtoupper($tab['designation']).'</span>';
            $rows[] = '<span style="color:green;">'.($S_stock[0]/$tab['nbreArticleUniteStock']).'</span>';
            $rows[] = '<span style="color:green;">NORMAL</span>';
            $rows[] = '<button type="button" onclick="rtr_Stock('.$tab["idStock"].')" id="btn_RetirerStock-'.$tab['idStock'].'" class="btn btn-danger" >
              <i class="glyphicon glyphicon-transfer"></i> RETIRER
              </button>';
    
            $data[] = $rows;
          }
        }
        else{
          $rows = array();
          $rows[] = '<span style="color:green;">'.$tab["dateExpiration"].'</span>';
          $rows[] = '<span style="color:green;">'.strtoupper($tab['designation']).'</span>';
          $rows[] = '<span style="color:green;">'.($I_stock[0] - $E_stock[0] + ($S_stock[0] / $tab['nbreArticleUniteStock'])).'</span>';
          $rows[] = '<span style="color:green;">NORMAL</span>';
          $rows[] = '<button type="button" onclick="rtr_Stock('.$tab["idStock"].')" id="btn_RetirerStock-'.$tab['idStock'].'" class="btn btn-danger" >
            <i class="glyphicon glyphicon-transfer"></i> RETIRER
            </button>';
  
          $data[] = $rows;
        }

      }
      else{
        if($tab["quantiteStockCourant"]!=0){
          $rows = array();
          $rows[] = '<span style="color:green;">'.$tab["dateExpiration"].'</span>';
          $rows[] = '<span style="color:green;">'.strtoupper($tab['designation']).'</span>';
          $rows[] = '<span style="color:green;">'.$tab['quantiteStockCourant'].'</span>';
          $rows[] = '<span style="color:green;">NORMAL</span>';
          $rows[] = '<button  type="button" onclick="rtr_Stock('.$tab["idStock"].')" id="btn_RetirerStock-'.$tab['idStock'].'" class="btn btn-danger" >
          <i class="glyphicon glyphicon-transfer"></i> RETIRER
          </button>';
          $data[] = $rows;
        }
      }
    }

    while($tab=mysql_fetch_array($res5)){
      if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) { 

        $sqlS="SELECT SUM(quantiteStockCourant) FROM `".$nomtableEntrepotStock."`
        where idStock ='".$tab['idStock']."' ";
        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
        $S_stock = mysql_fetch_array($resS);
        
        $sqlI="SELECT SUM(quantiteStockinitial) FROM `".$nomtableStock."`
        where idStock ='".$tab['idStock']."' ";
        $resI=mysql_query($sqlI) or die ("select stock impossible =>".mysql_error());
        $I_stock = mysql_fetch_array($resI);
        
        $sqlE="SELECT SUM(quantiteStockinitial) FROM `".$nomtableEntrepotStock."`
        where idStock ='".$tab['idStock']."' AND (idTransfert=0 OR idTransfert IS NULL)  ";
        $resE=mysql_query($sqlE) or die ("select stock impossible =>".mysql_error());
        $E_stock = mysql_fetch_array($resE);

        if($I_stock[0]==$E_stock[0]){
          if($S_stock[0]!=0){
            $rows = array();
            $rows[] = '<span style="color:green;">'.$tab["dateExpiration"].'</span>';
            $rows[] = '<span style="color:green;">'.strtoupper($tab['designation']).'</span>';
            $rows[] = '<span style="color:green;">'.($S_stock[0]/$tab['nbreArticleUniteStock']).'</span>';
            $rows[] = '<span style="color:green;">NORMAL</span>';
            $rows[] = '<button type="button" onclick="rtr_Stock('.$tab["idStock"].')" id="btn_RetirerStock-'.$tab['idStock'].'" class="btn btn-danger" >
              <i class="glyphicon glyphicon-transfer"></i> RETIRER
              </button>';
    
            $data[] = $rows;
          }
        }
        else{
          $rows = array();
          $rows[] = '<span style="color:green;">'.$tab["dateExpiration"].'</span>';
          $rows[] = '<span style="color:green;">'.strtoupper($tab['designation']).'</span>';
          $rows[] = '<span style="color:green;">'.($I_stock[0] - $E_stock[0] + ($S_stock[0] / $tab['nbreArticleUniteStock'])).'</span>';
          $rows[] = '<span style="color:green;">NORMAL</span>';
          $rows[] = '<button type="button" onclick="rtr_Stock('.$tab["idStock"].')" id="btn_RetirerStock-'.$tab['idStock'].'" class="btn btn-danger" >
            <i class="glyphicon glyphicon-transfer"></i> RETIRER
            </button>';
  
          $data[] = $rows;
        }

      }
      else{
        if($tab["quantiteStockCourant"]!=0){
          $rows = array();
          $rows[] = '<span style="color:green;">'.$tab["dateExpiration"].'</span>';
          $rows[] = '<span style="color:green;">'.strtoupper($tab['designation']).'</span>';
          $rows[] = '<span style="color:green;">'.$tab['quantiteStockCourant'].'</span>';
          $rows[] = '<span style="color:green;">NORMAL</span>';
          $rows[] = '<button  type="button" onclick="rtr_Stock('.$tab["idStock"].')" id="btn_RetirerStock-'.$tab['idStock'].'" class="btn btn-danger" >
          <i class="glyphicon glyphicon-transfer"></i> RETIRER
          </button>';
          $data[] = $rows;
        }
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
                  <th style="width: 15%;">Expiration</th>
                  <th style="width: 15%;">Réference</th>
                  <th style="width: 15%;">Quantité</th>
                  <th style="width: 15%;">Statut</th>
                  <th style="width: 18%;">Operation</th>
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
