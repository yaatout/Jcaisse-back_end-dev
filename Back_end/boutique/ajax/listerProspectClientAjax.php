<?php

session_start();
if(!$_SESSION['iduserBack']){
	header('Location:../../index.php');
}
require('../../connectionPDO.php');


$date = new DateTime();
$timezone = new DateTimeZone('Africa/Dakar');
$date->setTimezone($timezone);

$annee =$date->format('Y');
$mois =$date->format('m');
$jour =$date->format('d');
$heureString=$date->format('H:i:s');
$dateString=$annee.'-'.$mois.'-'.$jour ;
$dateString2=$jour.'-'.$mois.'-'.$annee ;

$moiM=$mois-1;
        $anneeM=$annee;
        if($moiM<10){
            $moiM='0'.$moiM;
            if($mois=='01'){
                $moiM=12;
                $anneeM=$annee-1;
                $anneeM="$anneeM";
            }
        }


$operation=@$_POST['operation'];

if ($operation=="1" || $operation=="3" || $operation=="4") {

  $nbEntreeProspect = @$_POST["nbEntreeProspect"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page 		= ($nbEntreeProspect!=0) ? $nbEntreeProspect : 10; //item to display per page
  $page_number 		= 0; //page number
  
  //Get page number from Ajax
  if(isset($_POST["page"])){
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
    if(!is_numeric($page_number)){die('Numéro de page invalide!');} //incase of invalid page number
  }else{
    $page_number = 1; //if there's no page number, set it to 1
  }
  
//get total number of records from database
  
    $sqla = $bdd->prepare("SELECT COUNT(*) as total_row FROM `aaa-boutique-prospectClient` ");     
    $sqla->execute();  
    $total_a = $sqla->fetch();

    
  $total_rows = $total_a['total_row'];
  $total_pages = ceil($total_rows/$item_per_page);
      
      //position of records
      $page_position = (($page_number-1) * $item_per_page);
      
      if ($query =="") {
        $req1 = $bdd->prepare("SELECT * FROM `aaa-boutique-prospectClient` ORDER BY `nom` ASC  LIMIT $page_position ,$item_per_page"); 
        $req1->execute()  or die(print_r($req1->errorInfo())); 

        /* $req2 = $bdd->prepare("SELECT * FROM `aaa-payement-salaire` p LEFT JOIN `aaa-boutique` b ON p.idBoutique = b.idBoutique WHERE aPayementBoutique=:p
            and (`datePS` LIKE '%".$annee."-".$mois."%') LIMIT $page_position ,$item_per_page"); 
        $req2->execute(array('p' =>1))  or die(print_r($req2->errorInfo()));  */
        
      } else {
        //Limit our results within a specified range.         
            $req1 = $bdd->prepare("SELECT * FROM `aaa-boutique-prospectClient` WHERE 
                 nom LIKE '%".$query."%'  ORDER BY `nom` ASC  LIMIT $page_position ,$item_per_page"); 
            $req1->execute()  or die(print_r($req1->errorInfo())); 

            /* $req2 = $bdd->prepare("SELECT * FROM `aaa-payement-salaire` p LEFT JOIN `aaa-boutique` b ON p.idBoutique = b.idBoutique WHERE aPayementBoutique=:p 
                and labelB LIKE '%".$query."%' and (`datePS` LIKE '%".$annee."-".$mois."%' )  LIMIT $page_position ,$item_per_page"); 
            $req2->execute(array('p' =>1))  or die(print_r($req2->errorInfo())); */                 
      }

      
      $allClientProspec = $req1->fetchAll();
      echo '<table class="table table-striped contents display tabDesign tableau3" border="1">';
      echo '<thead>
              <tr id="">
                <th>Nom boutique</th>
                <th>Prenom</th>
                <th>Nom</th>
                <th>Adresse</th>
                <th>Email</th>
                <th>Téléphone Portable</th>
                <th>Téléphone Fixe</th>
                <th>Opération</th>
                <th>Interactions</th>
              </tr>
            </thead>';
      $i = ($page_number - 1) * $item_per_page +1;
      $cpt = 0; 

        foreach ($allClientProspec as $client) {  ?>
                                       
                                        <tr> 
                                          <?php
                                            echo  '<td><span style="color:blue;"><form class="form form-inline" role="form"  method="post" > ';
                                            echo  '<span style="color:blue;"><input type="text"  class="form-control" id="clientPnomBoutique-'.$client['idBPC'].'" min=1 value="'.$client['nomBoutique'].'" disabled /></span></td>';
                                            echo    '<td><span style="color:blue;"><input type="text"  class="form-control" id="clientPprenom-'.$client['idBPC'].'" min=1 value="'.$client['prenom'].'" disabled /></span></td>';
                                            echo  '<td><span style="color:blue;"><input type="text" class="form-control" id="clientPnom-'.$client['idBPC'].'" min=1 value="'.$client['nom'].'"  disabled/></span></td>';
                                            echo  '<td><span style="color:blue;"><input type="text"  class="form-control" id="clientPadresse-'.$client['idBPC'].'" min=1 value="'.$client['adresse'].'"  disabled/></span></td>';
                                            echo  '<td><span style="color:blue;"><input type="text"  class="form-control" id="clientPemail-'.$client['idBPC'].'" min=1 value="'.$client['email'].'"  disabled/></span></td>';
                                            echo  '<td><span style="color:blue;"><input type="text"  class="form-control" id="clientPtelPortable-'.$client['idBPC'].'" min=1 value="'.$client['telPortable'].'" disabled /></span></td>';
                                            echo  '<td><span style="color:blue;"><input type="text" class="form-control" id="clientPtelFixe-'.$client['idBPC'].'" min=1 value="'.$client['telFixe'].'" disabled /></span></td>';
                                            echo  '<td id="tdClientPost'.$client['idBPC'].'" >'; ?>
                                                <button type="button" class="btn btn-primary" 
                                                                        onclick="activerInput(<?php echo $client['idBPC'] ?>)" id="btnActModCP<?php echo $client['idBPC'] ?>">
                                                                                <i class="glyphicon glyphicon-pencil"></i>Modifier
                                                            </button>
                                              <?php
                                            echo "</td>";
                                            
                                          ?> 
                                            <td>
                                                <div class="btn-group">
                                                  <button type="button" class="btn btn-sm btn-info" onclick="ajouterInteraction(<?php echo $client['idBPC']; ?>, '<?php echo addslashes($client['prenom'].' '.$client['nom']); ?>')" title="Ajouter une interaction">
                                                    <i class="fa fa-plus"></i>
                                                  </button>
                                                  <button type="button" class="btn btn-sm btn-primary" onclick="voirInteractions(<?php echo $client['idBPC']; ?>, '<?php echo addslashes($client['prenom'].' '.$client['nom']); ?>')" title="Voir les interactions">
                                                    <i class="fa fa-list"></i>
                                                  </button>
                                                </div>
                                            </td> 
                                                 <!-- <?php
                                                    ///////////////////////////////////
                                                    if ($client['idBoutiqueProspect']!=NULL) {
                                                      $sql00a = $bdd->prepare("SELECT * FROM `aaa-boutique-prospect` WHERE idBP =:b ");     
                                                      $sql00a->execute(array('b'=>$client['idBoutiqueProspect']));  
                                                      $boutique = $sql00a->fetch();?>

                                                      <td> 
                                                          <?php 
                                                          echo $boutique['nomBoutique'].'<br>';
                                                          if ( $boutique['installer']=='1') {
                                                            echo "<span style='color: green; background-color:white;'>Dejà installé</span>";
                                                          }
                                                          echo '<br><a href="#" onclick="DetailsBoutProspectPopPup('.$client['idBoutiqueProspect'].','.$client['idBPC'].')" >Détails</a>';   ?> 
                                                      </td>
                                                      <td>
                                                        <?php 
                                                        if ($boutique['installer']=='0') {?>
                                                              <button type="button" class="btn btn-primary" 
                                                                        onclick="activerInput(<?php echo $client['idBPC'] ?>)" id="btnActModCP<?php echo $client['idBPC'] ?>">
                                                                                <i class="glyphicon glyphicon-pencil"></i>Modifier
                                                            </button>
                                                          <?php }?>
                                                          <a onclick="popUpSpm_ClientP (<?=$client['idBPC']?>,'<?=$client['prenom']?>','<?=$client['nom']?>')" ><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                                                      </td>
                                                      <td>
                                                          <div class="btn-group">
                                                              <button type="button" class="btn btn-sm btn-info" onclick="ajouterInteraction(<?php echo $client['idBPC']; ?>, '<?php echo addslashes($client['prenom'].' '.$client['nom']); ?>')" title="Ajouter une interaction">
                                                                  <i class="fa fa-plus"></i>
                                                              </button>
                                                              <button type="button" class="btn btn-sm btn-primary" onclick="voirInteractions(<?php echo $client['idBPC']; ?>, '<?php echo addslashes($client['prenom'].' '.$client['nom']); ?>')" title="Voir les interactions">
                                                                  <i class="fa fa-list"></i>
                                                              </button>
                                                          </div>
                                                      </td> 
                                                  <?php } else { ?>
                                                    <td>  
                                                            <button type="button" class="btn btn-primary"   
                                                            onclick="addBoutiqueProspectPopPup(<?php echo $client['idBPC'] ?>)" >
                                                                    <i class="glyphicon glyphicon-plus"></i>Nouvelle entreprise
                                                            </button>
                                                    </td>
                                                    <td>
                                                      <button type="button" class="btn btn-primary" 
                                                                      onclick="activerInput(<?php echo $client['idBPC'] ?>)" id="btnActModCP<?php echo $client['idBPC'] ?>">
                                                                              <i class="glyphicon glyphicon-pencil"></i>Modifier
                                                      </button>
                                                      <a onclick="popUpSpm_ClientP (<?=$client['idBPC']?>,'<?=$client['prenom']?>','<?=$client['nom']?>')" ><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-info" onclick="ajouterInteraction(<?php echo $client['idBPC']; ?>, '<?php echo addslashes($client['prenom'].' '.$client['nom']); ?>')">
                                                            <i class="fa fa-phone"></i> <i class="fa fa-envelope"></i>
                                                        </button>
                                                    </td>
                                                    <?php }
                                                 ?>   -->
                                        </tr>
                                    <?php
              
          $i++;
          $cpt++;
      } 
    if ($cpt==0) {
      echo '<tr>';
      echo  '<td colspan="9" align="center">Données introuvables!</td>';
      echo '</tr>';
    }
    echo '</table>';
    echo '<div class="">';
      echo '<div class="col-md-4">';
        echo '<ul class="pull-left">';
          if ($item_per_page > $total_rows) {
              echo '1 à '.($total_rows).' sur '.$total_rows.' articles';        
          } else {
            if ($page_number == 1) {
              echo '1 à '.($item_per_page).' sur '.$total_rows.' articles';
            } else {
              if (($total_rows-($item_per_page * $page_number)) < 0) {
                echo ($item_per_page * ($page_number-1) +1).' à '.$total_rows.' sur '.$total_rows.' articles';
              } else {
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

<script>
// Fonction pour ouvrir la modale d'ajout d'interaction
function ajouterInteraction(idClient, nomClient) {
    // Créer le contenu de la modale
    var modalContent = `
    <div class="modal fade" id="modalInteraction" tabindex="-1" role="dialog" aria-labelledby="modalInteractionLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modalInteractionLabel">Nouvelle interaction - ${nomClient}</h4>
                </div>
                <div class="modal-body">
                    <form id="formInteraction">
                        <input type="hidden" name="idClient" value="${idClient}">
                        <div class="form-group">
                            <label>Type d'interaction</label>
                            <select class="form-control" name="typeInteraction" required>
                                <option value="">Sélectionnez un type</option>
                                <option value="appel">Appel téléphonique</option>
                                <option value="email">Email</option>
                                <option value="rdv">Rendez-vous</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Date et heure</label>
                            <input type="datetime-local" class="form-control" name="dateInteraction" required>
                        </div>
                        <div class="form-group">
                            <label>Notes</label>
                            <textarea class="form-control" name="notes" rows="3" placeholder="Détails de l'interaction..."></textarea>
                        </div>
                        <div class="form-group">
                            <label>À faire pour le suivi</label>
                            <input type="text" class="form-control" name="suivi" placeholder="Action à effectuer...">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary" onclick="enregistrerInteraction()">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>`;

    // Ajouter la modale au DOM si elle n'existe pas déjà
    if ($('#modalInteraction').length === 0) {
        $('body').append(modalContent);
    }

    // Initialiser la date et l'heure actuelles
    var now = new Date();
    var formattedDate = now.toISOString().slice(0, 16);
    $('input[name="dateInteraction"]').val(formattedDate);

    // Afficher la modale
    $('#modalInteraction').modal('show');
}

// Fonction pour enregistrer l'interaction
function enregistrerInteraction() {
    // Récupérer les données du formulaire
    var formData = $('#formInteraction').serialize();
    
    // Afficher un message de chargement
    var loadingMsg = $('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i><p>Enregistrement en cours...</p></div>');
    $('#formInteraction').html(loadingMsg);
    
    // Envoyer les données via AJAX
    $.ajax({
        url: 'boutique/ajax/ajouterInteractionClient.php',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Afficher un message de succès
                $('#formInteraction').html('<div class="alert alert-success">' + response.message + '</div>');
                
                // Fermer la modale après 1.5 secondes
                setTimeout(function() {
                    $('#modalInteraction').modal('hide');
                    // Recharger la page pour afficher les nouvelles données
                    location.reload();
                }, 1500);
            } else {
                // Afficher un message d'erreur
                $('#formInteraction').html('<div class="alert alert-danger">' + response.message + '</div>');
            }
        },
        error: function() {
            $('#formInteraction').html('<div class="alert alert-danger">Une erreur est survenue lors de l\'enregistrement.</div>');
        }
    });
}

// Fermer et supprimer la modale lorsqu'elle est masquée
$('body').on('hidden.bs.modal', '#modalInteraction, #modalVoirInteractions', function () {
    $(this).remove();
});

// Fonction pour afficher les interactions d'un client
function voirInteractions(idClient, nomClient) {
    // Créer le contenu de la modale
    var modalContent = `
    <div class="modal fade" id="modalVoirInteractions" tabindex="-1" role="dialog" aria-labelledby="modalVoirInteractionsLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modalVoirInteractionsLabel">Interactions - ${nomClient}</h4>
                </div>
                <div class="modal-body">
                    <div id="listeInteractions">
                        <div class="text-center">
                            <i class="fa fa-spinner fa-spin fa-2x"></i>
                            <p>Chargement des interactions...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>`;

    // Ajouter la modale au DOM si elle n'existe pas déjà
    if ($('#modalVoirInteractions').length === 0) {
        $('body').append(modalContent);
    }

    // Afficher la modale
    $('#modalVoirInteractions').modal('show');

    // Charger les interactions via AJAX
    $.ajax({
        url: 'boutique/ajax/listerInteractionsClient.php',
        type: 'GET',
        data: { idClient: idClient },
        dataType: 'html',
        success: function(response) {
            $('#listeInteractions').html(response);
        },
        error: function() {
            $('#listeInteractions').html('<div class="alert alert-danger">Une erreur est survenue lors du chargement des interactions.</div>');
        }
    });
}
</script>
