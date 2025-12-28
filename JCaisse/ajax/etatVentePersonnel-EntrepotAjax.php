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
$idPersonnel=@htmlspecialchars($_POST["idP"]);

// $annee =$date->format('Y');
// $mois =$date->format('m');
// $jour =$date->format('d');
// $heureString=$date->format('H:i:s');
// $dateString=$annee.'-'.$mois.'-'.$jour ;


if ($operation=="1" || $operation=="3" || $operation=="4") {

  $nbEntreeEVT = @$_POST["nbEntreeEVT"];
  $query = @$_POST["query"];
  $_debutTab = explode('-',$_POST["debut"]);
  $_finTab = explode('-',$_POST["fin"]);
  $_debut = $_POST["debut"];
  $_fin = $_POST["fin"];
  $item_per_page = ($nbEntreeEVT!=0) ? $nbEntreeEVT : 10; //item to display per page
  $page_number 		= 0; //page number

  $debut = $_debutTab[2].'-'.$_debutTab[1].'-'.$_debutTab[0];
  $fin = $_finTab[2].'-'.$_finTab[1].'-'.$_finTab[0];

//   var_dump($debut.'/'.$fin);

  //Get page number from Ajax
  if(isset($_POST["page"])){
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
    if(!is_numeric($page_number)){die('Numéro de page invalide!');} //incase of invalid page number
  }else{
    $page_number = 1; //if there's no page number, set it to 1
  }
  
  
  $sqlU = "SELECT * FROM `aaa-utilisateur` u,  `aaa-acces` a where u.idutilisateur=a.idutilisateur and u.idutilisateur=".$idPersonnel;
  // var_dump($sqlU);
  $resU=$bdd->query($sqlU);
  $_users=$resU->fetch();
  
  if ($query =="") {
    # code...   

    $sqlV = "SELECT l.designation, d.nbreArticleUniteStock, d.uniteStock, sum(quantite) as totalQty FROM `".$nomtablePagnet."` p,  `".$nomtableLigne."` l, `".$nomtableDesignation."` d where p.idPagnet=l.idPagnet and l.idDesignation=d.idDesignation and p.iduser=".$idPersonnel." and l.classe=0 and (p.type=0 || p.type=30 || p.type=11) and ((CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)) BETWEEN '".$_debut."' AND '".$_fin."') or (datepagej BETWEEN '".$_debut."' AND '".$_fin."')) GROUP BY l.idDesignation ORDER BY d.designation";
    
  } else {
    # code...
    //get total number of records from database
      # code...
      $sqlV = "SELECT l.designation, d.nbreArticleUniteStock, d.uniteStock, sum(quantite) as totalQty FROM `".$nomtablePagnet."` p,  `".$nomtableLigne."` l, `".$nomtableDesignation."` d where p.idPagnet=l.idPagnet and l.idDesignation=d.idDesignation and p.iduser=".$idPersonnel." and l.classe=0 and (p.type=0 || p.type=30 || p.type=11) and (d.designation LIKE '%".$query."%' or d.uniteStock LIKE '%".$query."%') and ((CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)) BETWEEN '".$_debut."' AND '".$_fin."') or (datepagej BETWEEN '".$_debut."' AND '".$_fin."') ) GROUP BY l.idDesignation ORDER BY d.designation";

  }
   
  $resV=$bdd->query($sqlV);
  $data=$resV->fetchAll();

  // var_dump($data);
  // die();

  $users = [];
  $TotalB = 0;
  $TotalV = 0;
  $TotalVers = 0;


//   $resU = mysql_query($sqlU) or die ("persoonel requête 2".mysql_error());
//   $users = mysql_fetch_array($resU);


  //get total number of records 
  $total_rows = count($data);

  $total_pages = ceil($total_rows/$item_per_page);

  //position of records
  $page_position = (($page_number-1) * $item_per_page);

  echo '
    <center style="margin-bottom :10px;  margin-top :10px;" class="btn_Impression"> 
    <button onclick="users_Excel()">
      <span class="glyphicon glyphicon-download"></span>
      EXCEL
    </button> 
    <button onclick="users_PDF()">
      <span class="glyphicon glyphicon-download"></span>
      PDF
    </button> 
    </center> 
  ';
  
  //Display records fetched from database.
  echo '<table class="table table-striped contents display tabStock" id="tableStock" border="1">';
  echo '<thead>
              <tr>
                  <th style="width: 15%;">Ordre</th>
                  <th style="width: 15%;">Designation</th>
                  <th style="width: 15%;">Unité Stock</th>
                  <th style="width: 15%;">Total quantité </th>
                  ';
                  if ($_SESSION['proprietaire']==1) {
                    # code...
                    echo '  
                      <th style="width: 22%;">Operations</th>';
                  }
        
  echo '           </tr>
            </thead>';
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;
  $j = 1;

  $nbDouzaine=0;
  $nbPieces=0;
  
  foreach ($data as $d) {

    if ($d['nbreArticleUniteStock'] == 1) {
      $nbPieces += $d['totalQty'];
    } else {
        $nbDouzaine += $d['totalQty'];
    }
  }

  $exportFile=$data;
// var_dump($exportFile);
  $data = array_slice($data, $page_position, $item_per_page);

  foreach ($data as $d) {
    
    echo '<tr>';
                
        echo  '<td>'.$j.'</td>';	
        echo  '<td>'.strtoupper($d['designation']).'</td>';
        echo  '<td>'.strtoupper($d['uniteStock']).'</td>';			
        echo  '<td>'.number_format(($d['totalQty']), 0, ',', ' ').'</td>';			

        if ($_SESSION['proprietaire']==1) {

              
            echo  '<td></td>';
        }

    echo '</tr>';

    $i++;
    $j++;
    $cpt++;
  }
  
  if ($cpt==0) {
    # code...
    echo '<tr>';
    echo  '<td colspan="5" align="center">Données introuvables!</td>';
    echo '</tr>';
  }
  echo '</table>';

  echo '<div class="row">';
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
  echo '<div class="row">';
    echo '<div class="col-lg-6">';
      echo '<h3><b>';
      echo '<span> Nombre de douzaine : '.$nbDouzaine.'</span>';   
      echo '</b></h3>';
    echo '</div>';
    echo '<div class="col-lg-6">';
      echo '<h3><b>';
      echo '<span> Nombre de pièces : '.$nbPieces.'</span>';   
      echo '</b></h3>';
    echo '</div>';
  echo '</div>';
  
  echo '
  <script>
      $(document).ready(function() {    

          var table = '.json_encode($exportFile).';
          var dateDebut = '.json_encode($debut).';
          var dateFin = '.json_encode($fin).';
    
          $(function(){
            users_Excel= function ()  {
                /* Declaring array variable */
                var rows =[];
                rows.push(
                  [
                      
                      "Designation",
                      "Unité Stock",
                      "Total Quantité"
                  ]
              );
            
                var taille = table.length;
                for(var i = 0; i<taille; i++){

                  column1 = ""+table[i]["designation"]+"";
                  column2 = table[i]["uniteStock"];
                  column3 = table[i]["totalQty"];
          
                  /* add a new records in the array */
                  rows.push(
                      [
                          column1,
                          column2,
                          column3
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
                link.setAttribute("download", "etatVentes.csv");
                document.body.appendChild(link);
                /* download the data file named "Stock_Price_Report.csv" */
                link.click();

            }
          });

          $(function(){
            users_PDF= function ()  {
              data = [];
              function setColumns(data) {
                  let tableData = [];
                  
                  var taille = table.length;
                  for(var i = 0; i<taille; i++){
                          tableData.push([
                              { text: table[i]["designation"],fontSize: 10 ,alignment: "center" },
                              { text: table[i]["uniteStock"],fontSize: 10,alignment: "center"  },
                              { text: table[i]["totalQty"],fontSize: 10,alignment: "center"  }
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
                            text: "Du "+dateDebut+" au "+dateFin,
                              
                            bold: true,fontSize: 11
                          },
                          "\n",
                          {
                            
                          style: "tableExample",
                          table: {
                            widths: [250],
                            body: [
                              [
                                {
                                  text: "Vente Personnel par article", bold:true,
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
                      widths: [170,150, "*"],
                      body: [
                        [
                          { text: "Designation", style: "tableHeader",alignment: "center" }, 
                          { text: "Unité Stock", style: "tableHeader"}, 
                          { text: "Total Quantité", style: "tableHeader",alignment: "center" }
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

