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
   $draw = $_POST['draw'];
   $row = $_POST['start'];
   $rowperpage = $_POST['length']; // Rows display per page
   $columnIndex = $_POST['order'][0]['column']; // Column index
   $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
   $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
   $searchValue = $_POST['search']['value']; // Search value

   $dateDebut = @$_POST['dateDebut'];
   $dateFin = @$_POST['dateFin'];

   //var_dump($draw.''.$row);

   $searchArray = array();

   // Search
   $searchQuery = " ";
   if($searchValue != ''){
      $searchQuery = " AND (
           nom LIKE :nom OR
           prenom LIKE :prenom OR 
           pays LIKE :pays OR
           dateReservation LIKE :dateReservation OR
           solde LIKE :solde ) ";
      $searchArray = array( 
           'nom'=>"%$searchValue%",
           'prenom'=>"%$searchValue%",
           'pays'=>"%$searchValue%",
           'dateReservation'=>"%$searchValue%",
           'solde'=>"%$searchValue%"
      );
   }

   // Total number of records without filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableReservation."`  AND (dateReservation  BETWEEN '".$dateDebut."' AND '".$dateFin."') ");
   $stmt->execute();
   $records = $stmt->fetch();
   $totalRecords = $records['allcount'];

   // Total number of records with filtering
   $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableReservation."` WHERE 1 ".$searchQuery." AND (dateReservation  BETWEEN '".$dateDebut."' AND '".$dateFin."') ");
   $stmt->execute($searchArray);
   $records = $stmt->fetch();
   $totalRecordwithFilter = $records['allcount'];

   // Fetch records
   $stmt = $bdd->prepare("SELECT * FROM `".$nomtableReservation."` WHERE 1 ".$searchQuery." AND (dateReservation  BETWEEN '".$dateDebut."' AND '".$dateFin."') ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

   // Bind values
   foreach ($searchArray as $key=>$search) {
      $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
   }

   $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
   $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
   $stmt->execute();
   $reservations = $stmt->fetchAll();

   $data = array();

   foreach ($reservations as $reservation) {

    $stmLigne = $bdd->prepare("SELECT  COUNT(*) AS total, MIN(dateArrivee) as arrivee, MAX(dateDepart) as depart FROM `".$nomtableLigneReservation."` 
    WHERE idReservation = :idReservation AND classe=0 ");
    $stmLigne->execute(array(
        ':idReservation' => $reservation['idReservation']
    ));
    $ligne = $stmLigne->fetch(PDO::FETCH_ASSOC);

    $debut_date = date_create($ligne['arrivee']); 
    $fin_date = date_create($ligne['depart']); 
    $interval = date_diff($debut_date, $fin_date);
    $cols_jour=$interval->format('%R%a jours'); 

    $stmtVersement = $bdd->prepare("SELECT COUNT(*) AS total FROM `".$nomtableVersement."` WHERE idReservation=:idReservation ");
    $stmtVersement->bindValue(':idReservation', $reservation['idReservation'], PDO::PARAM_INT);
    $stmtVersement->execute();
    $versement = $stmtVersement->fetch();

      //Montant a verser du Client
      $solde = $reservation['solde'];

      //Colonne Montant a verser
      if ($_SESSION['bien']==1){
         if($solde>=0){
            $cols_montant = '<td><span class="alert-danger">'.number_format($solde, 0, ',', ' ').' FCFA <a href=detailsReservation_BN.php?id='.$reservation['idReservation'].'>Details </a></span></td>';
         }
         else{
            $cols_montant =  '<td> <span class="alert-success">'.number_format($solde, 0, ',', ' ').' FCFA <a href=detailsReservation_BN.php?id='.$reservation['idReservation'].'>Details </a></span></td>';
         }
      }
      else{
         if($solde>=0){
            $cols_montant = '<td><span class="alert-danger">'.number_format($solde, 0, ',', ' ').' FCFA <a href=detailsReservation.php?id='.$reservation['idReservation'].'>Details </a></span></td>';
         }
         else{
            $cols_montant =  '<td> <span class="alert-success">'.number_format($solde, 0, ',', ' ').' FCFA <a href=detailsReservation.php?id='.$reservation['idReservation'].'>Details </a></span></td>';
         }
      }

      if($ligne['total']>0 || $versement['total']>0){
         $cols_operations = '<td><a ><img src="images/edit.png" align="middle" alt="modifier" onclick="modifier_Reservation('.$reservation['idReservation'].')" data-toggle="modal"    /></a> </td>'; 
      }
      else{
         $cols_operations = '<td><a ><img src="images/edit.png" align="middle" alt="modifier" onclick="modifier_Reservation('.$reservation['idReservation'].')" data-toggle="modal"    /></a>&nbsp
         <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="supprimer_Reservation('.$reservation['idReservation'].')" data-toggle="modal" /></a></td>'; 
      }

      if($reservation['etat']==1) {
         $cols_etat = '<button type="button"  onclick="etat_Reservation('.$reservation['idReservation'].')" class="btn btn-success btn-xs" >
               En cours 
            </button> '; 
      }
      else if($reservation['etat']==2) {
         $cols_etat = '<button type="button"  onclick="etat_Reservation('.$reservation['idReservation'].')" class="btn btn-danger btn-xs" >
               Terminer
            </button> '; 
      }
      else {
         $cols_etat = '<button type="button"  onclick="etat_Reservation('.$reservation['idReservation'].')" class="btn btn-primary btn-xs" >
               En attente 
            </button> '; 
      }



      $data[] = array(
         "idReservation"=>$reservation['idReservation'],
         "nom"=>$reservation['nom'],
         "prenom"=>$reservation['prenom'],
         "pays"=>$reservation['pays'],
         "dateReservation"=>$reservation['dateReservation'],
         "dateArrivee"=>$ligne['arrivee'],
         "dateDepart"=>$ligne['depart'],
         "solde"=> $cols_montant,
         "etat"=> $cols_etat,
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