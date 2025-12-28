<?php
/*
R�sum�:
Commentaire:
version:1.1
Auteur: Ibrahima DIOP,EL hadji mamadou korka
Date de modification:07/04/2016; 04-05-2018
*/

session_start();

date_default_timezone_set('Africa/Dakar');


// var_dump($_SESSION['imagesPro']);

if (!$_SESSION['iduser']) {
  header('Location:../index.php');
}
require('connection.php');
require('connectionPDO.php');

require('declarationVariables.php');


$beforeTime = '00:00:00';
$afterTime = '06:00:00';

// var_dump(date('d-m-Y',strtotime("-1 days")));

if ($_SESSION['Pays'] == 'Canada') {
  $date = new DateTime();
  $timezone = new DateTimeZone('Canada/Eastern');
} else {
  $date = new DateTime();
  $timezone = new DateTimeZone('Africa/Dakar');
}
$date->setTimezone($timezone);
$heureString = $date->format('H:i:s');

if ($heureString >= $beforeTime && $heureString < $afterTime) {
  // var_dump ('is between');
  $date = new DateTime(date('d-m-Y', strtotime("-1 days")));
}

// $date->setTimezone($timezone);
$annee = $date->format('Y');
$mois = $date->format('m');
$jour = $date->format('d');
$dateString = $annee . '-' . $mois . '-' . $jour;
$dateHeures = $dateString . ' ' . $heureString;

$uploaded = 0;


$sqlU = "SELECT * FROM `aaa-utilisateur` where idutilisateur=" . $_SESSION['iduser'];
$resU = mysql_query($sqlU) or die("persoonel requête 2" . mysql_error());
$user = mysql_fetch_array($resU);

$iduser = $user['idutilisateur'];

/**Fin Button delete Image facture**/

if (isset($_POST['deleteImg'])) {
  $imgGet = $_POST['imgGet']; // image a supprimer
  $idPagnet = $_POST['idPagnet'];

  if (($key = array_search($imgGet, $_SESSION['imagesPro'])) !== false) {
    unset($_SESSION['imagesPro'][$key]);
  }

  if (count($_SESSION['imagesPro']) > 1) {
    # code...
    $imagesUp = implode(' | ', $_SESSION['imagesPro']);
  } else if (count($_SESSION['imagesPro']) == 1) {
    # code...
    $imagesUp = $_SESSION['imagesPro'][0];
  } else {
    # code...
    $imagesUp = '';
  }

  $sql2 = "UPDATE `" . $nomtablePagnet . "` set image='" . $imagesUp . "' where idPagnet='" . $idPagnet . "' ";
  $res2 = mysql_query($sql2) or die("modification 1 impossible =>" . mysql_error());

  $uploaded = 1;
}

// var_dump($imagesUp);
// var_dump($_SESSION['imagesPro']);
/**Fin Button delete Image facture**/

/**Debut Button upload Image facture**/
if (isset($_POST['btnUploadImgProforma'])) {
  $idPagnet = htmlspecialchars(trim($_POST['idPagnet']));
  if (isset($_FILES['file'])) {
    $tmpName = $_FILES['file']['tmp_name'];
    $name = $_FILES['file']['name'];
    $size = $_FILES['file']['size'];
    $error = $_FILES['file']['error'];

    $tabExtension = explode('.', $name);
    $extension = strtolower(end($tabExtension));

    $extensions = ['jpg', 'png', 'jpeg', 'gif', 'pdf'];
    $maxSize = 400000;

    if (in_array($extension, $extensions) && $error == 0) {

      $uniqueName = uniqid('', true);
      //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
      $file = $uniqueName . "." . $extension;
      //$file = 5f586bf96dcd38.73540086.jpg

      move_uploaded_file($tmpName, './PiecesJointes/' . $file);

      $sql2 = "UPDATE `" . $nomtablePagnet . "` set image='" . $file . "' where idPagnet='" . $idPagnet . "' ";
      $res2 = mysql_query($sql2) or die("modification 1 impossible =>" . mysql_error());

      $uploaded = 1;
    } else {
      echo "Une erreur est survenue";
    }
  }
}
if (isset($_POST['btnUploadImgProformaAjt'])) {
  $idPagnet = htmlspecialchars(trim($_POST['idPagnet']));
  if (isset($_FILES['file'])) {
    $tmpName = $_FILES['file']['tmp_name'];
    $name = $_FILES['file']['name'];
    $size = $_FILES['file']['size'];
    $error = $_FILES['file']['error'];

    $tabExtension = explode('.', $name);
    $extension = strtolower(end($tabExtension));

    $extensions = ['jpg', 'png', 'jpeg', 'gif', 'pdf'];
    $maxSize = 400000;

    if (in_array($extension, $extensions) && $error == 0) {

      $uniqueName = uniqid('', true);
      //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
      $file = $uniqueName . "." . $extension;
      //$file = 5f586bf96dcd38.73540086.jpg

      move_uploaded_file($tmpName, './PiecesJointes/' . $file);

      $sqlGetP = $bdd->prepare("SELECT * FROM `" . $nomtablePagnet . "` where idPagnet = " . $idPagnet);
      $sqlGetP->execute() or die(print_r($sqlGetP->errorInfo()));
      $items = $sqlGetP->fetch();

      $images = $items['image'];

      if ($images != null && $images != "") {

        $sql2 = "UPDATE `" . $nomtablePagnet . "` set image=CONCAT (image, ' | ', '" . $file . "') where idPagnet='" . $idPagnet . "' ";
        $res2 = mysql_query($sql2) or die("modification 1 impossible =>" . mysql_error());
      } else {

        $sql2 = "UPDATE `" . $nomtablePagnet . "` set image='" . $file . "' where idPagnet='" . $idPagnet . "' ";
        $res2 = mysql_query($sql2) or die("modification 1 impossible =>" . mysql_error());
      }
      $uploaded = 1;
    } else {
      echo "Une erreur est survenue";
    }
  }
}

/**Fin Button upload Image Proforma**/

?>

<?php require('entetehtml.php'); ?>

<body onLoad="process()">
  <?php
  /**************** MENU HORIZONTAL *************/

  require('header.php');



  if ($uploaded == 1) {
    # code... 
    echo '<script>
  $(function() {
      $("#imageNvProforma"+' . $idPagnet . ').modal("show");
  });</script>';
  }
  /**Debut Button valider date**/
  if (isset($_POST['dateSelectedValidate'])) {

    $dateSelected = $_POST['dateSelected'];
    $explodeDS = explode('-', $dateSelected);
    $dateString2 = $explodeDS[2] . '-' . $explodeDS[1] . '-' . $explodeDS[0];
    // header("Location:./proformaClientInValider.php?page=1&day=".$dateString2);
  } else if (isset($_GET['day'])) {

    $dateString2 = $_GET['day'];
  } else {

    $dateString2 = $jour . '-' . $mois . '-' . $annee;
  }
  /**Debut Button valider date**/


  // var_dump($dateString2);


  ?>

  <div class="container">


    <!-- Debut Javascript de l'Accordion pour Tout les Paniers -->
    <script>
      $(function() {
        $(".expand").on("click", function() {
          // $(this).next().slideToggle(200);
          $expand = $(this).find(">:first-child");

          if ($expand.text() == "+") {
            $expand.text("-");
          } else {
            $expand.text("+");
          }
        });

        $(".imageModal").on("click", function() {
          // $(this).next().slideToggle(200);
          img = $(this).attr("data-img");
          idPanier = $(this).attr("data-idp");

          $("#imgLocal").attr("src", "./PiecesJointes/" + img);
          $("#imgGet").val(img);
          $("#pagnet").val(idPanier);

        });
      });

      function showPreviewProforma(event, idPagnet) {
        var file = document.getElementById('input_file_proforma' + idPagnet).value;
        var reader = new FileReader();
        reader.onload = function() {
          var format = file.substr(file.length - 3);
          var pdf = document.getElementById('output_pdf_proforma' + idPagnet);
          var image = document.getElementById('output_image_proforma' + idPagnet);
          if (format == 'pdf') {
            document.getElementById('output_pdf_proforma' + idPagnet).style.display = "block";
            document.getElementById('output_image_proforma' + idPagnet).style.display = "none";
            pdf.src = reader.result;
          } else {
            document.getElementById('output_image_proforma' + idPagnet).style.display = "block";
            document.getElementById('output_pdf_proforma' + idPagnet).style.display = "none";
            image.src = reader.result;
          }
        }
        reader.readAsDataURL(event.target.files[0]);
        document.getElementById('btn_upload_proforma' + idPagnet).style.display = "block";
      }
    </script>
    <!-- Fin Javascript de l'Accordion pour Tout les Paniers -->

    <div class="container row" align="center">
      <form class="form-inline col-lg-6" method="POST" action="#">
        <div class="form-group mx-sm-3 mb-2">
          <input type="text" class="form-control clientProformaSearch" name="clientSearched" id="clientSearched" placeholder="Entrer le nom du client" required>
        </div>
        <button type="submit" name="clientSearchedValidation" class="btn btn-primary mb-2"><i class="glyphicon glyphicon-search"></i> Rechercher</button>
      </form>
      <form class="form-inline col-lg-6" method="POST" action="proformaClientInValider.php">
        <div class="form-group mx-sm-3 mb-2">
          <input type="date" class="form-control" name="dateSelected" id="dateSelected" required>
        </div>
        <button type="submit" name="dateSelectedValidate" class="btn btn-primary mb-2"><i class="glyphicon glyphicon-ok"></i></button>
      </form>
    </div><br><br>

    <div class="panel-group mt-5" id="accordion">
      <?php

      $item_per_page     = 50; //item to display per page

      if (isset($_GET['page'])) {
        $page_number = $_GET['page'];
      } else {
        $page_number = 1; //page number
      }

      /**Debut requete pour Lister les Paniers proforma  **/

      if (isset($_POST['clientSearchedValidation'])) {
        $nomClient = $_POST['clientSearched'];

        $sqlP01 = "SELECT count(idPagnet) FROM `" . $nomtablePagnet . "` where type=10 && verrouiller=1 && validerProforma=0 && nomClient='" . $nomClient . "' && (((CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)))) < '2024-07-26') ORDER BY idPagnet DESC";
        $resP01 = mysql_query($sqlP01) or die("persoonel requête 2" . mysql_error());

        $total_rows = mysql_fetch_array($resP01);

        $total_pages = ceil($total_rows[0] / $item_per_page);
        // var_dump($total_pages);

        //position of records
        $page_position = (($page_number - 1) * $item_per_page);

        $sqlP0 = "SELECT * FROM `" . $nomtablePagnet . "` where type=10 && verrouiller=1 && validerProforma=0 && nomClient='" . $nomClient . "' && (((CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)))) < '2024-07-26') ORDER BY validerProforma,terminerProforma,idPagnet DESC LIMIT $page_position, $item_per_page";
        $resP0 = mysql_query($sqlP0) or die("persoonel requête 2" . mysql_error());
      } else {

        $sqlP01 = "SELECT count(idPagnet) FROM `" . $nomtablePagnet . "` where type=10 && verrouiller=1 && validerProforma=0 && (((CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)))) < '2024-07-26') ORDER BY idPagnet DESC";
        $resP01 = mysql_query($sqlP01) or die("persoonel requête 2" . mysql_error());

        $total_rows = mysql_fetch_array($resP01);

        $total_pages = ceil($total_rows[0] / $item_per_page);
        // var_dump($total_pages);

        //position of records
        $page_position = (($page_number - 1) * $item_per_page);

        $sqlP0 = "SELECT * FROM `" . $nomtablePagnet . "` where type=10 && verrouiller=1 && validerProforma=0 && (((CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)))) < '2024-07-26') ORDER BY validerProforma,terminerProforma,idPagnet DESC LIMIT $page_position, $item_per_page";
        $resP0 = mysql_query($sqlP0) or die("persoonel requête 2" . mysql_error());
        // var_dump($sqlP0);
      }

      $cpt = $total_rows[0] - (($page_number * 10) - 10);
      $cpt2 = $total_rows[0] - (($page_number * 10) - 10);

      /**Fin requete pour Lister les Paniers vproforma  **/
      while ($proforma = mysql_fetch_array($resP0)) {
        // var_dump($proforma);

        if ($proforma['idClient'] == 0) {
          # code...
          $nomClient = $proforma['nomClient'];
        } else {
          # code...

          $sqlPC = "SELECT * FROM `" . $nomtableClient . "` where idClient=" . $proforma['idClient'];
          $resPC = mysql_query($sqlPC) or die("persoonel requête 2" . mysql_error());
          $client = mysql_fetch_array($resPC);

          $nomClient = $client['prenom'] . " " . $client['nom'];
        }


        $sql = "SELECT * from `aaa-utilisateur` where idutilisateur='" . $proforma['iduser'] . "' ";

        $res = mysql_query($sql);

        $userAutor = mysql_fetch_array($res);


        // var_dump($cpt);
      ?>

        <?php

        if (($proforma['idClient'] == 0)) {

        ?> <div class="panel panel-warning">

          <?php

        } else {

          ?> <div class="panel panel-success">
            <?php

          }

            ?>
            <div class="panel-heading">
              <h4 data-toggle="collapse" href="#collapse<?= $proforma['idPagnet'] ?>" data-parent="#accordion" class="panel-title expand">
                <div class="right-arrow pull-right">+</div>
                <a href="#collapse<?= $proforma['idPagnet'] ?>"> Bon Commande n° <?= $cpt ?>

                  <span class="spanDate noImpr"> </span>

                  <span class="spanDate noImpr"> Date: <?= $proforma['datepagej'] ?> </span>

                  <span class="spanDate noImpr"> Heure: <?= $proforma['heurePagnet'] ?> </span>

                  <span class="spanDate noImpr"> Client: <?= $nomClient ?> </span>

                  <span class="spanDate noImpr">#<?= $proforma['idPagnet'] ?></span>

                  <span class="spanDate noImpr">
                    <?php echo substr(strtoupper($userAutor['prenom']), 0, 3); ?></span>

                </a>
              </h4>
            </div>

            <div id="collapse<?= $proforma['idPagnet'] ?>" <?php
                                                            // var_dump($proforma['image']);
                                                            if ($cpt2 - $cpt == 0) {
                                                            ?> class="panel-collapse collapse in" <?php
                                                                                                } else {
                                                                                                  ?> class="panel-collapse collapse" <?php
                                                                                                                                    } ?>>
              <div class="panel-body">


                <button class="btn btn-success  pull-right" style="margin-right:20px;" onclick="mettreANiveauProforma(<?= $proforma['idPagnet'] ?>)">

                  Valider

                </button>

                <table class="table ">

                  <thead class="noImpr">

                    <tr>

                      <th>Référence</th>

                      <th>Quantité</th>

                      <th>Unité vente</th>

                      <th>Depot</th>

                      <th>Operation</th>

                    </tr>

                  </thead>

                  <tbody>

                    <?php

                    $sql1 = "SELECT * FROM `" . $nomtableLigne . "` where idPagnet=" . $proforma['idPagnet'] . " ORDER BY numligne DESC";

                    $res1 = mysql_query($sql1) or die("personel requête 2" . mysql_error());

                    while ($ligne = mysql_fetch_assoc($res1)) {

                      $sql00 = "SELECT * FROM `" . $nomtableDesignation . "` where idDesignation=" . $ligne['idDesignation'] . "";

                      $res00 = mysql_query($sql00) or die("personel requête 2" . mysql_error());
                      $designation = mysql_fetch_array($res00);
                      $nbrArticle = $designation['nbreArticleUniteStock'];

                    ?>

                      <tr>
                        <?php
                        $sqlES = "SELECT * from `" . $nomtableEntrepot . "` order by idEntrepot desc";
                        $resES = mysql_query($sqlES);
                        $title = '';
                        while ($entrepot = mysql_fetch_array($resES)) {
                          $sqlE = "SELECT SUM(quantiteStockCourant) 
                                                      FROM `" . $nomtableEntrepotStock . "`
                                                      where idDesignation ='" . $ligne['idDesignation'] . "' AND idEntrepot='" . $entrepot['idEntrepot'] . "' ";
                          $resE = mysql_query($sqlE) or die("select stock impossible =>" . mysql_error());
                          $E_stock = mysql_fetch_array($resE);
                          if ($E_stock[0] != null && $E_stock[0] != 0) {
                            $title = $title . $entrepot['nomEntrepot'] . ' : ' . ($E_stock[0] / $designation['nbreArticleUniteStock']) . ' ' . $designation['uniteStock'] . ' | ';
                          }
                        }
                        ?>
                        <td class="designation" title="<?php echo $title; ?>">
                          <?php echo $ligne['designation']; ?></td>

                        <td>

                          <input type="number" disabled min="1" class="form-control disabled" id="qty-<?= $ligne['numligne'] ?>" value="<?= $ligne['quantite'] ?>" onKeyup="modif_QtyETProforma(this.value,<?= $ligne['numligne'] ?>,<?= $ligne['idPagnet'] ?>)" onChange="modif_QtyETProforma(this.value,<?= $ligne['numligne'] ?>,<?= $ligne['idPagnet'] ?>)" <?= ($ligne['depotConfirm'] == 0) ? "" : "disabled"; ?> <?= ($ligne['idEntrepot'] == $user['idEntrepot'] || $user['idEntrepot'] == '' || $user['idEntrepot'] == 0) ? "" : "disabled"; ?>>
                          <span class="factureFois"></span>

                        </td>

                        <td class="unitevente">

                          <?php echo $ligne['unitevente']; ?>

                        </td>

                        <td>

                          <?php

                          $reqEp = "SELECT * from  `" . $nomtableEntrepot . "` e

                                                      where idEntrepot='" . $ligne['idEntrepot'] . "'";

                          $resEp = mysql_query($reqEp) or die("select stock impossible =>" . mysql_error());

                          $entrepot = mysql_fetch_assoc($resEp);

                          echo $entrepot['nomEntrepot'];

                          ?>

                        </td>

                        <td>
                          <button class="btn btn-success disabled" id="dmc-<?= $ligne['numligne'] ?>" data-idc="<?= $ligne['numligne'] ?>" onClick="depotManagerConfirm(<?= $ligne['numligne'] ?>)" <?= ($ligne['depotConfirm'] == 0) ? "" : "style=display:none"; ?> <?= ($ligne['idEntrepot'] == $user['idEntrepot'] || $user['idEntrepot'] == '' || $user['idEntrepot'] == 0) ? "" : "disabled"; ?>><i class="glyphicon glyphicon-ok"></i></button>

                          <button class="btn btn-primary disabled" id="dme-<?= $ligne['numligne'] ?>" data-ide="<?= $ligne['numligne'] ?>" onClick="depotManagerEdit(<?= $ligne['numligne'] ?>)" <?= ($ligne['depotConfirm'] == 0) ? "style=display:none" : ""; ?> <?= ($ligne['idEntrepot'] == $user['idEntrepot'] || $user['idEntrepot'] == '' || $user['idEntrepot'] == 0) ? "" : "disabled"; ?> <?= ($proforma['terminerProforma'] == 1 || $proforma['validerProforma'] == 1) ? "disabled" : ""; ?>><i class="glyphicon glyphicon-edit"></i></button>
                        </td>
                      </tr>

                    <?php

                    }

                    ?>

                  </tbody>

                </table>
              </div>
            </div>

            <div class="modal fade" <?php echo  "id=imageNvProforma" . $proforma['idPagnet']; ?> role="dialog">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header" style="padding:35px 50px;">
                    <button type="button" class="close" data-dismiss="modal" style="background-color:red">&times;</button>
                    <h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span>
                      Proforma : <b>#<?php echo  $proforma['idPagnet']; ?></b></h4>
                  </div>
                  <form method="post" enctype="multipart/form-data">
                    <div class="modal-body" style="padding:40px 50px;">
                      <input type="text" style="display:none" name="idPagnet" id="idPagnet_Upd_Nv" <?php echo  "value=" . $proforma['idPagnet'] . ""; ?> />
                      <div class="form-group" style="text-align:center;">
                        <?php
                        if ($proforma['image'] != null && $proforma['image'] != ' ') {
                          $format = substr($proforma['image'], -3);
                        ?>
                          <input type="file" name="file" value="<?php echo  $proforma['image']; ?>" accept="image/*" id="input_file_proforma<?php echo  $proforma['idPagnet']; ?>" onchange="showPreviewProforma(event,<?php echo  $proforma['idPagnet']; ?>);" required /><br />
                          <?php if ($format == 'pdf') { ?>
                            <iframe id="output_pdf_proforma<?php echo  $proforma['idPagnet']; ?>" src="./PiecesJointes/<?php echo  $proforma['image']; ?>" width="100%" height="500px"></iframe>
                            <img style="display:none;" width="500px" height="500px" id="output_image_proforma<?php echo  $proforma['idPagnet'];  ?>" />
                          <?php } else { ?>
                            <div class="row" id="output_image_proforma<?php echo  $proforma['idPagnet']; ?>">
                              <?php
                              $images = explode(' | ', $proforma['image']);
                              $_SESSION['imagesPro'] = $images;
                              foreach ($images as $key) {
                              ?>
                                <img class="col-lg-4 imageModal" data-img="<?= $key; ?>" data-idp="<?= $proforma['idPagnet']; ?>" src="./PiecesJointes/<?= $key; ?>" height="150px" data-toggle="modal" data-target="#imageModal" />

                              <?php } ?>
                            </div>
                            <iframe id="output_pdf_proforma<?php echo  $proforma['idPagnet'];  ?>" style="display:none;" width="100%" height="500px"></iframe>
                          <?php } ?>
                        <?php
                        } else { ?>
                          <input type="file" name="file" accept="image/*" id="input_file_proforma<?php echo  $proforma['idPagnet']; ?>" id="cover_image" onchange="showPreviewProforma(event,<?php echo  $proforma['idPagnet']; ?>);" required /><br />
                          <img style="display:none;" width="500px" height="500px" id="output_image_proforma<?php echo  $proforma['idPagnet']; ?>" />
                          <iframe id="output_pdf_proforma<?php echo  $proforma['idPagnet'];  ?>" style="display:none;" width="100%" height="500px"></iframe>
                        <?php
                        }
                        ?>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-success pull-left" name="btnUploadImgProforma" id="btn_upload_proforma<?php echo  $proforma['idPagnet']; ?>">
                        <span class="glyphicon glyphicon-upload"></span><?= ($proforma['image'] != null && $proforma['image'] != '') ? "Supprimer et " : ""; ?>
                        Enregistrer
                      </button>

                      <button type="submit" <?= ($proforma['image'] != null && $proforma['image'] != '') ? "" : "style=display:none"; ?> class="btn btn-info pull-right" name="btnUploadImgProformaAjt" id="btn_upload_proforma_Ajt<?php echo  $proforma['idPagnet']; ?>">
                        <span class="glyphicon glyphicon-plus"></span> Ajouter une image
                      </button>

                      <?php if ($proforma['image'] != null && $proforma['image'] != '') { ?>
                        <!-- <button type="submit" class="btn btn-primary pull-right" name="btnDownloadImg" >
                                                <span class="glyphicon glyphicon-download"></span> Telecharger
                                            </button> -->
                      <?php } ?>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            </div>
          <?php
          $cpt--;
          // var_dump($cpt);
        }
          ?>
          </div>
          <!-- Modal -->
          <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <!-- <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div> -->
                <div class="modal-body">
                  <form method="post">

                    <img alt="" align="center" id="imgLocal" width="450px" height="350px">
                    <input type="text" name="imgGet" id="imgGet">
                    <input type="text" name="idPagnet" id="pagnet">
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                      <button type="submit" class="btn btn-danger" name="deleteImg" id="deleteImg">Supprimer</button>
                    </div>

                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <?php echo paginate_function($item_per_page, $page_number, $total_rows[0], $total_pages, $dateString2); ?>
          </div>
    </div>
    <?php
    function paginate_function($item_per_page, $current_page, $total_records, $total_pages, $dateString2)
    {

      $pagination = '';
      if ($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages) { //verify total pages and current page number
        $pagination .= '<ul class="pagination pull-right">';

        if ($current_page == 1) {
          # code...
          $pagination .= '<li class="page-item disabled"><a href="proformaClientInValider.php?page=1" data-page="1" class="page-link"><span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span></a>
          </li>';
        } else {
          # code...
          $pagination .= '<li class="page-item"><a href="proformaClientInValider.php?page=' . ($current_page - 1) . '" data-page="' . ($current_page - 1) . '" class="page-link"><span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span></a></li>';
        }

        if ($total_pages <= 5) {
          for ($page = 1; $page <= $total_pages; $page++) {
            if ($current_page == $page) {
              # code...
              $pagination .= '<li class="page-item page active"><a href="proformaClientInValider.php?page=' . $page . '" data-page="' . $page . '" class="page-link">' . $page . '</a></li>';
            } else {
              # code...
              $pagination .= '<li class="page-item page"><a href="proformaClientInValider.php?page=' . $page . '" data-page="' . $page . '" class="page-link">' . $page . '</a></li>';
            }
          }
        } else {
          if ($current_page == 1) {
            # code...
            $pagination .= '<li class="page-item active"><a href="proformaClientInValider.php?page=1" data-page="1" class="page-link">1</a></li>';
          } else {
            # code...
            $pagination .= '<li class="page-item"><a href="proformaClientInValider.php?page=1" data-page="1" class="page-link">1</a></li>';
          }

          if ($current_page == 1 || $current_page == 2) {
            for ($page = 2; $page <= 3; $page++) {
              if ($current_page == $page) {
                # code...
                $pagination .= '<li class="page-item page active"><a href="proformaClientInValider.php?page=' . $page . '" data-page="' . $page . '" class="page-link">' . $page . '</a></li>';
              } else {
                # code...
                $pagination .= '<li class="page-item page"><a href="proformaClientInValider.php?page=' . $page . '" data-page="' . $page . '" class="page-link">' . $page . '</a></li>';
              }
            }
            $pagination .= '<li class="page-item"><a class="page-link">...</a></li>';
          } else if (($current_page > 2) and ($current_page < $total_pages - 2)) {
            $pagination .= '<li class="page-item"><a class="page-link">...</a></li>';

            for ($page = $current_page; $page <= ($current_page + 1); $page++) {
              if ($current_page == $page) {
                # code...
                $pagination .= '<li class="page-item page active"><a href="proformaClientInValider.php?page=' . $page . '" data-page="' . $page . '" class="page-link">' . $page . '</a></li>';
              } else {
                # code...
                $pagination .= '<li class="page-item page"><a href="proformaClientInValider.php?page=' . $page . '" data-page="' . $page . '" class="page-link">' . $page . '</a></li>';
              }
            }
            $pagination .= '<li class="page-item"><a class="page-link">...</a></li>';
          } else {
            $pagination .= '<li class="page-item"><a class="page-link">...</a></li>';
            for ($page = ($total_pages - 2); $page <= ($total_pages - 1); $page++) {
              if ($current_page == $page) {
                # code...
                $pagination .= '<li class="page-item page active"><a href="proformaClientInValider.php?page=' . $page . '" data-page="' . $page . '" class="page-link">' . $page . '</a></li>';
              } else {
                # code...
                $pagination .= '<li class="page-item page"><a href="proformaClientInValider.php?page=' . $page . '" data-page="' . $page . '" class="page-link">' . $page . '</a></li>';
              }
            }
          }
          if ($current_page == $total_pages) {
            # code...
            $pagination .= '<li class="page-item page active"><a href="proformaClientInValider.php?page=' . $page . '" data-page="' . $total_pages . '" class="page-link">' . $total_pages . '</a></li>';
          } else {
            # code...
            $pagination .= '<li class="page-item page"><a href="proformaClientInValider.php?page=' . $total_pages . '" data-page="' . $total_pages . '" class="page-link">' . $total_pages . '</a></li>';
          }
        }
        if ($current_page == $total_pages) {
          # code...
          $pagination .= '<li class="page-item disabled"><a href="proformaClientInValider.php?page=' . $total_pages . '" data-page="' . $total_pages . '" class="page-link"><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></a></li>';
        } else {
          # code...
          $pagination .= '<li class="page-item"><a href="proformaClientInValider.php?page=' . ($current_page + 1) . ' " data-page="' . ($current_page + 1) . '" class="page-link"><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></a></li>';
        }

        $pagination .= '</ul>';
      }
      return $pagination; //return pagination links
    }
    ?>