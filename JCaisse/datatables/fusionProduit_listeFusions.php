<?php
   // Database connectionection
   session_start();

   if(!$_SESSION['iduser']){

   header('Location:../index.php');

   }

   require('../connection.php');

   require('../connectionPDO.php');
   
   require('../declarationVariables.php');

   // Reading value
   $draw = @$_POST['draw'];
   $row = @$_POST['start'];
   $rowperpage = @$_POST['length']; // Rows display per page
   $columnIndex = @$_POST['order'][0]['column']; // Column index
   $columnName = @$_POST['columns'][$columnIndex]['data']; // Column name
   $columnSortOrder = @$_POST['order'][0]['dir']; // asc or desc
   $searchValue = @$_POST['search']['value']; // Search value

   //var_dump($draw.''.$row);

   $searchArray = array();

   // Search
   $searchQuery = " ";
   if($searchValue != ''){
      $searchQuery = " AND (
           designation LIKE :designation OR
           codeBarreDesignation LIKE :codeBarreDesignation ) ";
      $searchArray = array( 
           'designation'=>"%$searchValue%",
           'codeBarreDesignation'=>"%$searchValue%"
      );
   }

   $totalRecords = count($_SESSION['fusions']);
   $totalRecordwithFilter = count($_SESSION['fusions']);
   $empRecords = $_SESSION['fusions'];

   $data = array();

   foreach ($empRecords as $produit => $row) {
        

      $cols_designation = '<textarea class="form-control" id="inpt_Produit_Designation_'.$row['idDesignation'].'">'.$row['designation'].'</textarea>';
      $cols_codeBarre = '<input type="text" class="form-control" id="inpt_Produit_CodeBarre_'.$row['idDesignation'].'" min=1 value="'.$row['codeBarreDesignation'].'" required=""/>';
     
      $cols_stock = '<input type="number" class="form-control" id="inpt_Produit_Quantite_'.$row['idDesignation'].'" min=1  value="0"/>';

      if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

         $cols_forme = '<input type="text" class="form-control" id="inpt_Produit_Forme_'.$row['idDesignation'].'" min=1 value="'.$row['forme'].'" required=""/>';
         $cols_tableau = '<input type="text" class="form-control" id="inpt_Produit_Tableau_'.$row['idDesignation'].'" value="'.$row['tableau'].'" />';   
         $cols_prixSession = '<input type="number" class="form-control" id="inpt_Produit_PrixSession_'.$row['idDesignation'].'" min=1 value="'.$row['prixSession'].'" required=""/>';
         $cols_prixPublic = '<input type="number" class="form-control" id="inpt_Produit_PrixPublic_'.$row['idDesignation'].'" min=1 value="'.$row['prixPublic'].'" required=""/>';
     
         $cols_operations = '<a><img src="images/drop.png" align="middle" alt="supprimer" onclick="supprimer_Fusion('.$row["idDesignation"].')"  data-toggle="modal" data-target="#imgsup'.$row["idDesignation"].'" /></a>&nbsp;&nbsp;
         <button type="button" onclick="fusion_Produit('.$row["idDesignation"].')" id="btn_fusion_Produit-'.$row['idDesignation'].'" class="btn btn-success btn_fusion_Produit">
            Fusion
         </button>'; 

         $data[] = array(
            "idDesignation"=>$row['idDesignation'],
            "designation"=>$cols_designation,
            "codeBarreDesignation"=>$cols_codeBarre,
            "quantite"=>$row['quantite'],
            "forme"=>$cols_forme,
            "tableau"=>$cols_tableau,
            "prixSession"=> $cols_prixSession,
            "prixPublic"=> $cols_prixPublic,
            "stock"=> $cols_stock,
            "operations"=>$cols_operations
         );
      }
      else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

         if($row["uniteStock"]!="Article" && $row["uniteStock"]!="article" && $row["uniteStock"]!="ARTICLE" && $row["uniteStock"]!="ARTICLES"){
            $cols_uniteStock = '<select style="width: 80px;" class="form-control"  id="slct_Produit_UniteStock_'.$row['idDesignation'].'">
                          <option selected value= "'.$row["uniteStock"].'">'.$row["uniteStock"].'</option>
                          <option value="Article">Article</option>
                      </select>';
         }
         else{
            $cols_uniteStock = '<select style="width: 80px;" class="form-control"  id="slct_Produit_UniteStock_'.$row['idDesignation'].'">
                        <option value="Article">Article</option>
                     </select>';
         }

         $cols_nombreArticleUS = '<input type="number" class="form-control" id="inpt_Produit_NombreArticleUS_'.$row['idDesignation'].'" value="'.$row['nbreArticleUniteStock'].'" />';   
         $cols_prixuniteStock = '<input type="number" class="form-control" id="inpt_Produit_PrixUniteStock_'.$row['idDesignation'].'" min=1 value="'.$row['prixuniteStock'].'" required=""/>';
         $cols_prixachat = '<input type="number" class="form-control" id="inpt_Produit_PrixAchat_'.$row['idDesignation'].'" min=1 value="'.$row['prixachat'].'" required=""/>';

         $cols_operations = '<a><img src="images/drop.png" align="middle" alt="supprimer" onclick="supprimer_Fusion('.$row["idDesignation"].')"  data-toggle="modal" data-target="#imgsup'.$row["idDesignation"].'" /></a>&nbsp;&nbsp;
         <button type="button" onclick="fusion_Produit_ET('.$row["idDesignation"].')" id="btn_fusion_Produit-'.$row['idDesignation'].'" class="btn btn-success btn_fusion_Produit">
            Fusion
         </button>'; 
         
         $data[] = array(
            "idDesignation"=>$row['idDesignation'],
            "designation"=>$cols_designation,
            "codeBarreDesignation"=>$cols_codeBarre,
            "surdepot"=>$row['surdepot'],
            "sansdepot"=>$row['sansdepot'],
            "uniteStock"=>$cols_uniteStock,
            "nbreArticleUniteStock"=>$cols_nombreArticleUS,
            "prixuniteStock"=>$cols_prixuniteStock,
            "prixachat"=> $cols_prixachat,
            "stock"=> $cols_stock,
            "operations"=> $cols_operations
         );
      }
      else{

         if($row["uniteStock"]!="Article" && $row["uniteStock"]!="article" && $row["uniteStock"]!="ARTICLE" && $row["uniteStock"]!="ARTICLES"){
            $cols_uniteStock = '<select style="width: 80px;" class="form-control"  id="slct_Produit_UniteStock_'.$row['idDesignation'].'">
                          <option selected value= "'.$row["uniteStock"].'">'.$row["uniteStock"].'</option>
                          <option value="Article">Article</option>
                      </select>';
         }
         else{
            $cols_uniteStock = '<select style="width: 80px;" class="form-control"  id="slct_Produit_UniteStock_'.$row['idDesignation'].'">
                        <option value="Article">Article</option>
                     </select>';
         }
         
         $cols_nombreArticleUS = '<input type="number" class="form-control" id="inpt_Produit_NombreArticleUS_'.$row['idDesignation'].'" value="'.$row['nbreArticleUniteStock'].'" />';   
         $cols_prixuniteStock = '<input type="number" class="form-control" id="inpt_Produit_PrixUniteStock_'.$row['idDesignation'].'" min=1 value="'.$row['prixuniteStock'].'" required=""/>';
         $cols_prix = '<input type="number" class="form-control" id="inpt_Produit_PrixUnitaire_'.$row['idDesignation'].'" min=1 value="'.$row['prix'].'" required=""/>';
         $cols_prixachat = '<input type="number" class="form-control" id="inpt_Produit_PrixAchat_'.$row['idDesignation'].'" min=1 value="'.$row['prixachat'].'" required=""/>';
        
         $cols_operations = '<a><img src="images/drop.png" align="middle" alt="supprimer" onclick="supprimer_Fusion('.$row["idDesignation"].')"  data-toggle="modal" data-target="#imgsup'.$row["idDesignation"].'" /></a>&nbsp;&nbsp;
         <button type="button" onclick="fusion_Produit('.$row["idDesignation"].')" id="btn_fusion_Produit-'.$row['idDesignation'].'" class="btn btn-success btn_fusion_Produit">
            Fusion
         </button>'; 
         
         $data[] = array(
            "idDesignation"=>$row['idDesignation'],
            "designation"=>$cols_designation,
            "codeBarreDesignation"=>$cols_codeBarre,
            "quantite"=>$row['quantite'],
            "uniteStock"=>$cols_uniteStock,
            "nbreArticleUniteStock"=>$cols_nombreArticleUS,
            "prixuniteStock"=>$cols_prixuniteStock,
            "prix"=> $cols_prix,
            "prixachat"=> $cols_prixachat,
            "stock"=> $cols_stock,
            "operations"=>$cols_operations
         );
      }

   }

   // Response
   $response = array(
      "draw" => intval($draw),
      "iTotalRecords" => $totalRecords,
      "iTotalDisplayRecords" => $totalRecordwithFilter,
      "aaData" => $data
   );

   echo json_encode($response);