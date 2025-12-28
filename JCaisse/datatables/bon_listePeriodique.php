<?php
   // Database connectionection
   session_start();

   if(!$_SESSION['iduser']){

   header('Location:../JCaisse/index.php');

   }

   require('../connection.php');

   require('../connectionPDO.php');
   
   require('../declarationVariables.php');

   // Reading value
   $draw = $_POST['draw'];
   $row = $_POST['start'];
   $rowperpage = $_POST['length']; // Rows display per page
   $columnIndex = $_POST['order'][0]['column']; // Column index
   $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
   $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
   $searchValue = $_POST['search']['value']; // Search value

   //var_dump($draw.''.$row);

   $dateDebut = @$_POST['dateDebut'];
   $dateFin = @$_POST['dateFin'];

   $searchArray = array();

   // Search
   $searchQuery = " ";
   if($searchValue != ''){
      $searchQuery = " AND (
           nom LIKE :nom OR
           prenom LIKE :prenom ) ";
      $searchArray = array( 
           'nom'=>"%$searchValue%",
           'prenom'=>"%$searchValue%"
      );
   }

   // Total number of records without filtering
   $stmt = $bdd->prepare("SELECT COUNT(DISTINCT(c.idClient)) AS allcount FROM `".$nomtableClient."` c
   INNER JOIN `".$nomtablePagnet."` p ON p.idClient = c.idClient
   INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet 
   WHERE p.verrouiller=1 AND (p.type=0 || p.type=1 || p.type=11 || p.type=30) AND (CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') ");
   $stmt->execute();
   $records = $stmt->fetch();
   $totalRecords = $records['allcount'];

   // Total number of records with filtering
   $stmt = $bdd->prepare("SELECT COUNT(DISTINCT(c.idClient)) AS allcount FROM `".$nomtableClient."` c
   INNER JOIN `".$nomtablePagnet."` p ON p.idClient = c.idClient
   INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet 
   WHERE 1 ".$searchQuery." AND p.verrouiller=1 AND (p.type=0 || p.type=1 || p.type=11 || p.type=30) AND (CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."') ");
   $stmt->execute($searchArray);
   $records = $stmt->fetch();
   $totalRecordwithFilter = $records['allcount'];

   // Fetch records
   $stmt = $bdd->prepare("SELECT SUM(p.totalp) AS montant, SUM(l.quantite) AS quantite, c.solde as solde, c.idClient as idClient, c.nom as nom, c.prenom as prenom, c.telephone as telephone, c.activer as activer FROM `".$nomtableClient."` c
   INNER JOIN `".$nomtablePagnet."` p ON p.idClient = c.idClient
   INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet  
   WHERE 1 ".$searchQuery." AND p.verrouiller=1 AND (p.type=0 || p.type=1 || p.type=11 || p.type=30) AND (CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$dateDebut."' AND '".$dateFin."')
   GROUP BY c.idClient ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

   // Bind values
   foreach ($searchArray as $key=>$search) {
      $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
   }

   $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
   $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
   $stmt->execute();
   $clients = $stmt->fetchAll();

   $data = array();

   foreach ($clients as $client) {

      //Montant a verser du Client
      $solde = $client['solde'];

      //Colonne Montant a verser
      if($solde>=0){
         $cols_solde = '<td><span class="alert-danger">'.number_format($solde, 0, ',', ' ').' FCFA <a href=bonPclient.php?c='.$client['idClient'].'>Details </a></span></td>';
      }
      else{
         $cols_solde =  '<td> <span class="alert-success">'.number_format($solde, 0, ',', ' ').' FCFA <a href=bonPclient.php?c='.$client['idClient'].'>Details </a></span></td>';
      }

      $cols_operations = '';

      //Colonne Operations
      if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1) {
         if ($client['activer']==0 && $client['quantite']>0  &&  $client['solde']==0 ){
            if ($client['activer']==0){
               $cols_operations = '<td><a ><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Client('.$client["idClient"].')" data-toggle="modal"    /></a>&nbsp
               <button type="button" onclick="activer_Client('.$client["idClient"].')" id="btn_Client-'.$client['idClient'].'" class="btn btn-success btn-xs" > Activer</button>&nbsp
               <a ><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Client('.$client["idClient"].')" data-toggle="modal"    /></a>&nbsp</td>';
            }
            else{
               $cols_operations = '<td><a ><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Client('.$client["idClient"].')" data-toggle="modal"    /></a>&nbsp
               <button type="button" onclick="archiver_Client('.$client["idClient"].')" id="btn_archive-'.$client['idClient'].'" class="btn btn-warning btn-xs" >Archiver </button>&nbsp
               <button type="button" onclick="desactiver_Client('.$client["idClient"].')" id="btn_Client-'.$client['idClient'].'" class="btn btn-danger btn-xs" >Desactiver </button>&nbsp
               <a ><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Client('.$client["idClient"].')" data-toggle="modal"    /></a>&nbsp</td>'; 
            }
         }
         else{  
            if ($client['activer']==0){
               $cols_operations = '<td><a ><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Client('.$client["idClient"].')" data-toggle="modal"    /></a>&nbsp
               <button type="button" onclick="activer_Client('.$client["idClient"].')" id="btn_Client-'.$client['idClient'].'" class="btn btn-success btn-xs" > Activer</button>&nbsp</td>';
            }
            else{
               $cols_operations = '<td><a ><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Client('.$client["idClient"].')" data-toggle="modal"/></a>&nbsp
               <button type="button" onclick="archiver_Client('.$client["idClient"].')" id="btn_archive-'.$client['idClient'].'" class="btn btn-warning btn-xs" >Archiver </button>&nbsp
               <button type="button" onclick="desactiver_Client('.$client["idClient"].')" id="btn_Client-'.$client['idClient'].'" class="btn btn-danger btn-xs" >Desactiver </button>&nbsp</td>'; 
            }
         }
      }
      else{
         $cols_operations = '';
      }

      $cols_quantite = $client['quantite'];
      $cols_montant = $client['montant'];

      $data[] = array(
         "nom"=> $client['nom'],
         "prenom"=> $client['prenom'],
         "telephone"=> $client['telephone'],
         "quantite"=> $cols_quantite ,
         "montant"=> $cols_montant,
         "solde"=> $cols_solde,
         "operations"=>$cols_operations
      );


   }

   // Response
   $response = array(
      "draw" => intval($draw),
      "iTotalRecords" => $totalRecords,
      "iTotalDisplayRecords" => $totalRecordwithFilter,
      "aaData" => $data
   );

   echo json_encode($response);