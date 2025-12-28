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


$operation=@htmlspecialchars($_POST["operation"]);
$tri=@htmlspecialchars($_POST["tri"]);


if ($operation=="1" || $operation=="3" || $operation=="4") {

  $nbEntreeAvoir = @$_POST["nbEntreeAvoir"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page 		= ($nbEntreeAvoir!=0) ? $nbEntreeAvoir : 10; //item to display per page
  $page_number 		= 0; //page number
  $data = array();
  //$tabIdDesigantion = array();
  //$tabIdStock = array();
  $i=1;

  //Get page number from Ajax
  if(isset($_POST["page"])){
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
    if(!is_numeric($page_number)){die('Numéro de page invalide!');} //incase of invalid page number
  }else{
    $page_number = 1; //if there's no page number, set it to 1
  }
  
  //get total number of records from database
  
  if ($query =="") {
    # code...
    $sql="SELECT * from `".$nomtableClient."` where avoir=1 and archiver=0 order by idClient desc";

    $res=mysql_query($sql);
    
  } else {
    # code...
    //get total number of records from database
    $sql="SELECT * from `".$nomtableClient."` where avoir=1 && archiver=0 and (nom LIKE '%".$query."%' or prenom LIKE '%".$query."%') order by idClient desc";

    $res=mysql_query($sql);
  }
  
  while($client=mysql_fetch_array($res)){



    /*
  
    $sql12="SELECT montant FROM `".$nomtableBon."` where idClient='".$client['idClient']."' ";
  
    $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
  
    $TotalB = mysql_fetch_array($res12) ; 
  
   */
  
  if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
    $sql12="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient='".$client['idClient']."' AND verrouiller=1 AND (type=0 || type=30) ";
  
    $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
  
    $TotalB = mysql_fetch_array($res12) ;

    $sql11="SELECT SUM(apayerPagnet) FROM `".$nomtableMutuellePagnet."` where idClient='".$client['idClient']."' AND verrouiller=1 AND (type=0 || type=30) ";
    $res11 = mysql_query($sql11) or die ("persoonel requête 2".mysql_error());
    $TotalM = mysql_fetch_array($res11) ;
  

    $sql13="SELECT SUM(montant) FROM `".$nomtableVersement."` where idClient='".$client['idClient']."' ";
  
    $res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());
  
    $TotalV = mysql_fetch_array($res13) ;
  
    
    $T_solde=$TotalB[0] + $TotalM[0] - $TotalV[0];
  }
  else{
    $sql12="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` where idClient='".$client['idClient']."' AND verrouiller=1 AND (type=0 || type=30) ";
  
    $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
  
    $TotalB = mysql_fetch_array($res12) ;
  

    $sql13="SELECT SUM(montant) FROM `".$nomtableVersement."` where idClient='".$client['idClient']."' ";
  
    $res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());
  
    $TotalV = mysql_fetch_array($res13) ;
  
    
    $T_solde=$TotalB[0] - $TotalV[0];
  }
  
  
  
  
    // if ($T_solde < 0) {
  
      # code...
  
      $rows = array();
  
      // $rows[] = $i;
  
      $rows[] = strtoupper($client['numCarnet']);	
  
      $rows[] = strtoupper($client['nom']);	
  
      $rows[] = strtoupper($client['prenom']);		
  
      $rows[] = strtoupper($client['adresse']);
  
      $rows[] = strtoupper($client['telephone']);
  
      // if($T_solde>=0){
  
      //   $rows[] = '<span class="alert-danger">'.number_format(-$T_solde, 0, ',', ' ').' FCFA <a href=bonPclient.php?c='.$client['idClient'].'>Details </a></span>';
  
      // }
  
      // else{
  
        $rows[] = '<span class="alert-info">'.number_format($client['montantAvoir'], 0, ',', ' ').' FCFA <a href=bonPclient.php?c='.$client['idClient'].'>Details </a></span>';
  
      // }
  
      if ($client['archiver']==0) {
  
          
  
        if ($client['activer']==0 && ($TotalB[0]==0 &&  $client['solde']==0 )){
  
          if ($client['activer']==0){
  
            $rows[] = '<button type="button" onclick="activer_Client('.$client["idClient"].')" id="btn_Client-'.$client['idClient'].'" class="btn btn-success btn-xs" > Activer</button>&nbsp
  
            <a hidden><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Client('.$client["idClient"].','.$i.')" data-toggle="modal"    /></a>&nbsp;
  
          <a hidden><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Client('.$client["idClient"].','.$i.')" data-toggle="modal"    /></a>&nbsp';
  
          }
  
          else{
  
            $rows[] = '<a hidden><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Client('.$client["idClient"].','.$i.')" data-toggle="modal"    /></a>&nbsp
  
            <button type="button" onclick="archiver_Client('.$client["idClient"].')" id="btn_archive-'.$client['idClient'].'" class="btn btn-warning btn-xs" >Archiver </button>&nbsp
  
            <button type="button" onclick="desactiver_Client('.$client["idClient"].')" id="btn_Client-'.$client['idClient'].'" class="btn btn-danger btn-xs" >Desactiver </button>&nbsp
  
          <a hidden><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Client('.$client["idClient"].','.$i.')" data-toggle="modal"    /></a>&nbsp'; 
  
          }
  
          $rows[] = '';  
  
        }
  
        else{
  
          if ($client['activer']==0){
  
            $rows[] = '<button type="button" onclick="activer_Client('.$client["idClient"].')" id="btn_Client-'.$client['idClient'].'" class="btn btn-success btn-xs" > Activer</button>&nbsp
  
            <a hidden><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Client('.$client["idClient"].','.$i.')" data-toggle="modal"    /></a>&nbsp;';
  
          }
  
          else{
  
            $rows[] = '<button type="button" onclick="archiver_Client('.$client["idClient"].')" id="btn_archive-'.$client['idClient'].'" class="btn btn-warning btn-xs" >Archiver </button>&nbsp
  
            <button type="button" onclick="desactiver_Client('.$client["idClient"].')" id="btn_Client-'.$client['idClient'].'" class="btn btn-danger btn-xs" >Desactiver </button>&nbsp
  
            <a hidden><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Client('.$client["idClient"].','.$i.')" data-toggle="modal"    /></a>&nbsp;'; 
  
          }
  
        }
  
      }else {
  
        
        $rows[] = '<button type="button" onclick="relancer_Client('.$client["idClient"].')" id="btn_relancer-'.$client['idClient'].'" class="btn btn-success btn-xs" > Relancer</button>&nbsp';
  
      }

      $rows[] = number_format($client['montantAvoir'], 0, ',', ' ');

  
      $data[] = $rows;
  
      $i=$i + 1;
  
    // }
  
  }

  //get total number of records 
  $total_rows = count($data);

  $total_pages = ceil($total_rows/$item_per_page);

  //position of records
  $page_position = (($page_number-1) * $item_per_page);

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

  // if ($tri==1) {
    // usort($data, "cmp1");
    // var_dump($data);
  // } else {
    //usort($data, "cmp2");
    
    // var_dump($data);
  // }

  //var_dump($sql);
  //var_dump($res);
  //Display records fetched from database.
  
    echo '<table class="table table-striped contents"  border="1">';
    echo '<thead>
            <tr>
            <th style="width: 10%;">N° Carnet</th>
            <th style="width: 10%;">Nom</th>
            <th style="width: 15%;">Prenom</th>
            <th style="width: 15%;">Adresse</th>
            <th style="width: 10%;">Telephone</th>
            <th style="width: 18%;">Montant-avoir</th>
            <th style="width: 22%;">Operations</th>
            </tr>
          </thead>';

  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;

  $exportFile=$data;
  // var_dump($exportFile);
  // $data=array();
  $data = array_slice($data, $page_position, $item_per_page);
  // var_dump($data);

  // $data=array();
  foreach ($data as $tab) {

      echo  '<tr>';    
        echo '<td><span>'.$tab[0].'</span></td>';
        echo '<td><span>'.$tab[1].'</span></td>';
        echo '<td><span>'.$tab[2].'</span></td>';
        echo '<td><span>'.$tab[3].'</span></td>';
        echo '<td><span>'.$tab[4].'</span></td>';
        echo '<td><span>'.$tab[5].'</span></td>';
        echo '<td><span>'.$tab[6].'</span></td>';
      echo'</tr>';
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
                      "Montant avoir"
                  ]
              );
            
                var taille = table.length;
                for(var i = 0; i<taille; i++){

                  column1 = ""+table[i][1]+"";
                  column2 = table[i][2];
                  column3 = table[i][3];
                  column4 = table[i][4];
                  column5 = table[i][7];
          
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
                              { text: table[i][1],fontSize: 08 },
                              { text: table[i][2],fontSize: 08 ,alignment: "center" },
                              { text: table[i][3],fontSize: 08,alignment: "center"  },
                              { text: table[i][4],fontSize: 08 ,alignment: "center"},
                              { text: table[i][7],fontSize: 08,alignment: "center"  },
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
                          { text: "Montant avoir", style: "tableHeader",alignment: "center" },
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
