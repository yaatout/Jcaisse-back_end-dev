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

// function cmp1($a, $b)
// {
//     return strcmp($b["designation"], $a["designation"]);
// }

// function cmp2($a, $b)
// {
//     return number_format($a["s_cli"]) - number_format($b["s_cli"]);
// }

$date = new DateTime();
$timezone = new DateTimeZone('Africa/Dakar');
$date->setTimezone($timezone);

$operation=@htmlspecialchars($_POST["operation"]);
$tri=@htmlspecialchars($_POST["tri"]);

// $annee =$date->format('Y');
// $mois =$date->format('m');
// $jour =$date->format('d');
// $heureString=$date->format('H:i:s');
// $dateString=$annee.'-'.$mois.'-'.$jour ;

  /****** PERSONNELS ******/
  
  $T_perso=0;
  
if ($operation=="1") {
  $sqlP="SELECT * from `".$nomtableClient."` where personnel=1 and archiver=0 order by idClient desc";
  $resP=mysql_query($sqlP);

  while($client=mysql_fetch_array($resP)){
    /*
    $sql12="SELECT montant FROM `".$nomtableBon."` where idClient='".$client['idClient']."' ";
    $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
    $TotalB = mysql_fetch_array($res12) ; 
  */
  if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
    $sql12="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient='".$client['idClient']."' AND verrouiller=1 AND (type=0 || type=30 || type=11) ";
    $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
    $TotalB = mysql_fetch_array($res12) ;

    $sql11="SELECT SUM(apayerPagnet) FROM `".$nomtableMutuellePagnet."`  where idClient='".$client['idClient']."' AND verrouiller=1 AND (type=0 || type=30 || type=11)";
    $res11 = mysql_query($sql11) or die ("persoonel requête 2".mysql_error());
    $TotalM = mysql_fetch_array($res11) ;

    $sql13="SELECT SUM(montant) FROM `".$nomtableVersement."` where idClient='".$client['idClient']."' ";
    $res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());
    $TotalV = mysql_fetch_array($res13) ;
    
    $T_solde=$TotalB[0] + $TotalM[0] - $TotalV[0];
  }
  else{
    $sql12="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient='".$client['idClient']."' AND verrouiller=1 AND (type=0 || type=30 || type=11) ";
    $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
    $TotalB = mysql_fetch_array($res12) ;

    $sql13="SELECT SUM(montant) FROM `".$nomtableVersement."` where idClient='".$client['idClient']."' ";
    $res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());
    $TotalV = mysql_fetch_array($res13) ;
    
    $T_solde=$TotalB[0] - $TotalV[0];
  }


    $T_perso=$T_perso+$T_solde;

  }
}


if ($operation=="1" || $operation=="3" || $operation=="4") {

  $nbEntreePersonnel = @$_POST["nbEntreePersonnel"];
  $query = @$_POST["query"];
  $item_per_page = ($nbEntreePersonnel!=0) ? $nbEntreePersonnel : 10; //item to display per page
  $page_number 		= 0; //page number
  $clients = array();
  $tabIdClient = array();
  $tabIdClient = array();

  //Get page number from Ajax
  if(isset($_POST["page"])){
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
    if(!is_numeric($page_number)){die('Numéro de page invalide!');} //incase of invalid page number
  }else{
    $page_number = 1; //if there's no page number, set it to 1
  }
  
  
  if ($query =="") {
    # code...
    
    $sql="SELECT * from `".$nomtableClient."` where personnel=1 and archiver=0 order by idClient desc";
    $res=mysql_query($sql);
    
  } else {
    # code...
    //get total number of records from database
      # code...
      
      $sql="SELECT * from `".$nomtableClient."` where personnel=1 and archiver=0 and (nom LIKE '%".$query."%' or prenom LIKE '%".$query."%' or telephone LIKE '%".$query."%') order by idClient desc";
      $res=mysql_query($sql);
      # code...
  }

  while($cli=mysql_fetch_array($res)){

    if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
      $sql12="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient='".$cli['idClient']."' AND verrouiller=1 AND (type=0 || type=30 || type=11) ";
      $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
      $TotalB = mysql_fetch_array($res12) ;

      $sql11="SELECT SUM(apayerPagnet) FROM `".$nomtableMutuellePagnet."` where idClient='".$cli['idClient']."' AND verrouiller=1 AND (type=0 || type=30 || type=11) ";
      $res11 = mysql_query($sql11) or die ("persoonel requête 2".mysql_error());
      $TotalM = mysql_fetch_array($res11) ;
    
      $sql13="SELECT SUM(montant) FROM `".$nomtableVersement."` where idClient='".$cli['idClient']."' ";
      $res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());
      $TotalV = mysql_fetch_array($res13) ;
  
      $T_solde=$TotalB[0] + $TotalM[0] - $TotalV[0];
  
      // if ($T_solde >= 0){
        $cli["TotalB"]=$TotalB[0] + $TotalM[0];
        $cli["TotalV"]=$TotalV[0];
        $cli["total"]=$T_solde;
        $clients[]=$cli;
      // }
    }
    else{
      $sql12="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient='".$cli['idClient']."' AND verrouiller=1 AND (type=0 || type=30 || type=11) ";
      $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
      $TotalB = mysql_fetch_array($res12) ;
    
      $sql13="SELECT SUM(montant) FROM `".$nomtableVersement."` where idClient='".$cli['idClient']."' ";
      $res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());
      $TotalV = mysql_fetch_array($res13) ;
  
      $T_solde=$TotalB[0] - $TotalV[0];
  
      // if ($T_solde >= 0){
        $cli["TotalB"]=$TotalB[0];
        $cli["TotalV"]=$TotalV[0];
        $cli["total"]=$T_solde;
        $clients[]=$cli;
      // }
    }

  }
  echo '
    <center style="margin-bottom :10px;  margin-top :10px;" class="btn_Impression"> 
    <button onclick="client_Excel()">
      <span class="glyphicon glyphicon-download"></span>
      EXCEL
    </button> 
    <button onclick="client_PDF()">
      <span class="glyphicon glyphicon-download"></span>
      PDF
    </button> 
    </center> 
  ';

  //get total number of records 
  $total_rows = count($clients);

  $total_pages = ceil($total_rows/$item_per_page);

  //position of records
  $page_position = (($page_number-1) * $item_per_page);
  
    echo '<br/><div class="container row">
          <a class="col-lg-6 alert alert-info" href="#">LISTE DES CLIENTS ';
              
            if ($_SESSION['proprietaire']==1) { 
              echo  ' => Valeur Montant du personnel =  '.number_format(($T_perso * $_SESSION['devise']), 2, ',', ' ').' '.$_SESSION['symbole'];
            }
            
        echo ' </a>
        </div>';
  
  //Display records fetched from database.
  echo '<table class="table table-striped contents display tabStock" id="tableStock" border="1">';
  echo '<thead>
              <tr>
                  <th style="width: 15%;">Nom</th>
                  <th style="width: 15%;">Prenom</th>
                  <th style="width: 15%;">Adresse</th>
                  <th style="width: 15%;">Telephone</th>
                  <th style="width: 18%;">Montant-à-Verser</th>
                  <th style="width: 22%;">Operations</th>
              </tr>
            </thead>';
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;

  $exportFile=$clients;

  $clients = array_slice($clients, $page_position, $item_per_page);
  
  foreach ($clients as $cli) {
    
    $T_solde=$cli["TotalB"] - $cli["TotalV"];
    $TotalB=$cli["TotalB"];
        # code...
    echo '<tr>';              
      echo  '<td>'.strtoupper($cli['nom']).'</td>';	
      echo  '<td>'.strtoupper($cli['prenom']).'</td>';		
      echo  '<td>'.strtoupper($cli['adresse']).'</td>';
      echo  '<td>'.strtoupper($cli['telephone']).'</td>';
      if($T_solde >= 0){
          echo'<td><span class="alert-danger">'.number_format($T_solde, 0, ',', ' ').' FCFA <a href=bonPclient.php?c='.$cli['idClient'].'>Details </a></span></td>';
      }
      else{
          echo  '<td> <span class="alert-success">'.number_format($T_solde, 0, ',', ' ').' FCFA <a href=bonPclient.php?c='.$cli['idClient'].'>Details </a></span></td>';
      }
      if ($cli['archiver']==0) {
          
          if ($cli['activer']==0 && ($TotalB[0]==0 &&  $cli['solde']==0 )){
          if ($cli['activer']==0){
              echo  '<td><a ><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Client('.$cli["idClient"].','.$i.')" data-toggle="modal"    /></a>&nbsp
              <button type="button" onclick="activer_Client('.$cli["idClient"].')" id="btn_Client-'.$cli['idClient'].'" class="btn btn-success btn-xs" > Activer</button>&nbsp
          <a ><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Client('.$cli["idClient"].','.$i.')" data-toggle="modal"    /></a>&nbsp</td>';
          }
          else{
              echo  '<td><a ><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Client('.$cli["idClient"].','.$i.')" data-toggle="modal"    /></a>&nbsp
              <button type="button" onclick="archiver_Client('.$cli["idClient"].')" id="btn_archive-'.$cli['idClient'].'" class="btn btn-warning btn-xs" >Archiver </button>&nbsp
              <button type="button" onclick="desactiver_Client('.$cli["idClient"].')" id="btn_Client-'.$cli['idClient'].'" class="btn btn-danger btn-xs" >Desactiver </button>&nbsp
          <a ><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Client('.$cli["idClient"].','.$i.')" data-toggle="modal"    /></a>&nbsp</td>'; 
          }
          echo  '';  
          }
          else{  
          if ($cli['activer']==0){
              echo  '<td><a ><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Client('.$cli["idClient"].','.$i.')" data-toggle="modal"    /></a>&nbsp
              <button type="button" onclick="activer_Client('.$cli["idClient"].')" id="btn_Client-'.$cli['idClient'].'" class="btn btn-success btn-xs" > Activer</button>&nbsp</td>';
          }
          else{
              echo  '<td><a ><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Client('.$cli["idClient"].','.$i.')" data-toggle="modal"/></a>&nbsp
              <button type="button" onclick="archiver_Client('.$cli["idClient"].')" id="btn_archive-'.$cli['idClient'].'" class="btn btn-warning btn-xs" >Archiver </button>&nbsp
              <button type="button" onclick="desactiver_Client('.$cli["idClient"].')" id="btn_Client-'.$cli['idClient'].'" class="btn btn-danger btn-xs" >Desactiver </button>&nbsp</td>'; 
          }
          }
      }else {
          
          echo  '<td><button type="button" onclick="relancer_Client('.$cli["idClient"].')" id="btn_relancer-'.$cli['idClient'].'" class="btn btn-success btn-xs" > Relancer</button>&nbsp</td>';
      }
    echo '</tr>';
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
      echo '<ul class="pull-left" style="margin-top:30px;">';
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
  echo '</div>';
  
  echo '
  <script>
      $(document).ready(function() {    

          var table = '.json_encode($exportFile).';
          var dateDebut = '.json_encode($dateString).';
    
          $(function(){
            client_Excel= function ()  {
                /* Declaring array variable */
                var rows =[];
                rows.push(
                  [
                      "Nom",
                      "Prenom",
                      "Adresse",
                      "Telephone",
                      "Montant à verser"
                  ]
              );
            
                var taille = table.length;
                for(var i = 0; i<taille; i++){

                  column1 = ""+table[i]["nom"]+"";
                  column2 = table[i]["prenom"];
                  column3 = table[i]["adresse"];
                  column4 = table[i]["telephone"];
                  column5 = table[i]["total"];
          
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
                link.setAttribute("download", "clients.csv");
                document.body.appendChild(link);
                /* download the data file named "Stock_Price_Report.csv" */
                link.click();

            }
          });

          $(function(){
            client_PDF= function ()  {
              data = [];
              function setColumns(data) {
                  let tableData = [];
                  
                  var taille = table.length;
                  for(var i = 0; i<taille; i++){
                          tableData.push([
                              { text: table[i]["nom"],fontSize: 08 },
                              { text: table[i]["prenom"],fontSize: 08 ,alignment: "center" },
                              { text: table[i]["adresse"],fontSize: 08,alignment: "center"  },
                              { text: table[i]["telephone"],fontSize: 08 ,alignment: "center"},
                              { text: table[i]["total"],fontSize: 08,alignment: "center"  },
                          ]);
                  }
                  return tableData;
              }
      
              let lignes = setColumns(data);
            

              var docDefinition = {
                content: [
                  {
                    columns: [
                      {
                        stack:[
                          
                          {
                            text: "Du "+dateDebut,
                              
                            bold: true,fontSize: 11
                          },
                          "\n",
                          {
                            
                          style: "tableExample",
                          table: {
                            widths: [130],
                            body: [
                              [
                                {
                                  text: "Clients", bold:true,
                                  fillColor: "#cccccc",border: [true, true, true, true], 
                                  alignment: "center",fontSize: 11
                                  
                                },
                                
                              ],
                              
                            ]
                          }
                          }
                        ],
                        
                      },
                
                    ],
                  },
                  {
                    style: "tableExample",
                    table: {
                      widths: [80,130, "*","*",100],
                      body: [
                        [
                          { text: "Nom", style: "tableHeader"}, 
                          { text: "Prenom", style: "tableHeader",alignment: "center" }, 
                          { text: "Adresse", style: "tableHeader",alignment: "center" },
                          { text: "Telephone", style: "tableHeader",alignment: "center" }, 
                          { text: "Montant à verser", style: "tableHeader",alignment: "center" },
                        ],
                        ...lignes,
                      ]
                    },
                  
                  }
                ]
              };
              pdfMake.createPdf(docDefinition).open();

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

?>