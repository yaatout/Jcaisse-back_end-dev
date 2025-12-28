<?php
/*
Résumé :
Commentaire :
Version : 2.0
see also :
Auteur : Ibrahima DIOP
Date de création : 20/03/2016
Date dernière modification : 20/04/2016
*/

session_start();

if(!$_SESSION['iduser']){
	header('Location:../index.php');
}
require('connection.php');

require('declarationVariables.php');

$date = new DateTime();
$timezone = new DateTimeZone('Africa/Dakar');
$date->setTimezone($timezone);
$datehier = date('d-m-Y', strtotime('-1 days'));
$datehier_Y = date('Y-m-d', strtotime('-1 days'));

$id=@$_GET['iDS'];
if(isset($_GET['debut']) && isset($_GET['fin'])){
  $dateDebut=@htmlspecialchars($_GET["debut"]);
  $dateFin=@htmlspecialchars($_GET["fin"]);
}
else {
  $dateDebut=date('Y-m-d', strtotime('-1 years'));
  $dateFin=$dateString;
}

$sql="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$id."' ";
$res=mysql_query($sql);
$design=mysql_fetch_array($res);

/**Debut Button upload Image Depense**/
if (isset($_POST['btnUploadImgDepense'])) {
  $idDepense=htmlspecialchars(trim($_POST['idDepense']));
  if(isset($_FILES['file'])){
      $tmpName = $_FILES['file']['tmp_name'];
      $name = $_FILES['file']['name'];
      $size = $_FILES['file']['size'];
      $error = $_FILES['file']['error'];

      $tabExtension = explode('.', $name);
      $extension = strtolower(end($tabExtension));

      $extensions = ['jpg', 'png', 'jpeg', 'gif','pdf'];
      $maxSize = 400000;

      if(in_array($extension, $extensions) && $error == 0){

          $uniqueName = uniqid('', true);
          //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
          $file = $uniqueName.".".$extension;
          //$file = 5f586bf96dcd38.73540086.jpg

          move_uploaded_file($tmpName, './PiecesJointes/'.$file);

          $sql2="UPDATE `".$nomtablePagnet."` set image='".$file."' where idPagnet='".$idDepense."' ";
          $res2=mysql_query($sql2) or die ("modification 1 impossible =>".mysql_error());
      }
      else{
          echo "Une erreur est survenue";
      }
  }
}
/**Fin Button upload Image Depense**/


/*************  LES BOUTONS IMPORTATION ET EXPORTATION EN CSV ET AJOUTER STOCK *******************/
/*************************************************************************************************/

require('entetehtml.php'); 
?>

<body >
  
   <?php require('header.php'); ?>
<div class="container" >


<input id="inpt_Produit_id" type="hidden" value="<?= $id?>" />
<input id="inpt_Produit_dateDebut" type="hidden" value="<?= $dateDebut?>" />
<input id="inpt_Produit_dateFin" type="hidden" value="<?= $dateFin?>" />

<script type="text/javascript">

  $(function() {

          id = <?php echo json_encode($id); ?>;

          dateDebut = <?php echo json_encode($dateDebut); ?>;

          dateFin = <?php echo json_encode($dateFin); ?>;

          tabDebut=dateDebut.split('-');

          tabFin=dateFin.split('-');

      var start = tabDebut[2]+'/'+tabDebut[1]+'/'+tabDebut[0];

      var end = tabFin[2]+'/'+tabFin[1]+'/'+tabFin[0];

      function cb(start, end) {

          var debut=start.format('YYYY-MM-DD');

          var fin=end.format('YYYY-MM-DD');

          window.location.href = "detailsDepense.php?iDS="+id+"&&debut="+debut+"&&fin="+fin;

          //alert(start);

      }

      $('#reportrange').daterangepicker({

          startDate: start,

          endDate: end,

          locale: {

              format: 'DD/MM/YYYY',

              separator: ' - ',

              applyLabel: 'Valider',

              cancelLabel: 'Annuler',

              fromLabel: 'De',

              toLabel: 'à',

              customRangeLabel: 'Choisir',

              daysOfWeek: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve','Sa'],

              monthNames: ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'November', 'Decembre'],

              firstDay: 1

          },

          ranges: {

              'Hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],

              'Une Semaine': [moment().subtract(6, 'days'), moment()],

              'Un Mois': [moment().subtract(30, 'days'), moment()],

              'Ce mois ci': [moment().startOf('month'), moment()],

              'Dernier Mois': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]

          },

      }, cb);

      cb(start, end);



  });
  function showImageDepense(idDepense) {
      var nom=$('#imageDepense'+idDepense).attr("data-image");
      $('#idDepense_View').text(nom);
      $('#idDepense_Upd_Nv').val(idDepense);
      $('#input_file_Depense').val('');
      $('#imageNvDepense').modal('show');
      var file = $('#imageDepense'+idDepense).val();
      if(file!=null && file!=''){
          var format = file.substr(file.length - 3);
          if(format=='pdf'){
              document.getElementById('output_pdf_Depense').style.display = "block";
              document.getElementById('output_image_Depense').style.display = "none";
              document.getElementById("output_pdf_Depense").src="./PiecesJointes/"+file;
          }
          else{
              document.getElementById('output_image_Depense').style.display = "block";
              document.getElementById('output_pdf_Depense').style.display = "none";
              document.getElementById("output_image_Depense").src="./PiecesJointes/"+file;
          }
      }
      else{
          document.getElementById('output_pdf_Depense').style.display = "none";
          document.getElementById('output_image_Depense').style.display = "none";
      }
  }
  function showPreviewDepense(event) {
      var file = document.getElementById('input_file_Depense').value;
      var reader = new FileReader();
      reader.onload = function()
      {
          var format = file.substr(file.length - 3);
          var pdf = document.getElementById('output_pdf_Depense');
          var image = document.getElementById('output_image_Depense');
          if(format=='pdf'){
              document.getElementById('output_pdf_Depense').style.display = "block";
              document.getElementById('output_image_Depense').style.display = "none";
              pdf.src = reader.result;
          }
          else{
              document.getElementById('output_image_Depense').style.display = "block";
              document.getElementById('output_pdf_Depense').style.display = "none";
              image.src = reader.result;
          }
      }
      reader.readAsDataURL(event.target.files[0]);
      document.getElementById('btn_upload_Depense').style.display = "block";
  }

</script>

<div class="col-sm-4 pull-left" >
 <a  class="btn btn-warning  pull-left" style="margin-top:8px;" href="insertionDepense.php" > Retour </a>
</div>

<div class="jumbotron noImpr">



  <div class="col-sm-4 pull-right" >

      <div aria-label="navigation">

          <ul class="pager">

              <li>

              <input type="text" id="reportrange" />

              </li>

          </ul>

      </div>   

  </div>

  <h2>Depense : <?php echo $design['designation']; ?> </h2>

  <h5>Du : 

      <?php

              $debutDetails=explode("-", $dateDebut);

              $dateDebutA=$debutDetails[2].'-'.$debutDetails[1].'-'.$debutDetails[0];

              $finDetails=explode("-", $dateFin);

              $dateFinA=$finDetails[2].'-'.$finDetails[1].'-'.$finDetails[0];

              echo $dateDebutA." au ".$dateFinA; 

      ?>

  </h5>

  <div class="panel-group">

      <div class="panel" style="background:#cecbcb;">

          <div class="panel-heading" >

              <h4 class="panel-title">

              <a data-toggle="collapse" href="#collapse1">Montant Total : <span id="montantDepenses"></span>  </a>

              </h4>

          </div>

          <div id="collapse1" class="panel-collapse collapse">

              <div class="panel-heading" style="margin-left:2%;">

                
              </div>

          </div>

      </div>

  </div>

  <form class="form-inline pull-left noImpr"  target="_blank" style="margin-left:20px;"

      method="post" action="pdfOperationLigne.php" >

      <input type="hidden" name="dateDebut" id="dateDebutOP"  <?php echo  "value=".$dateDebut."" ; ?> >

      <input type="hidden" name="dateFin" id="dateFinOP"  <?php echo  "value=".$dateFin."" ; ?> >

      <input type="hidden" name="id" id="idClientOP"  <?php echo  "value=".$id."" ; ?> >

      <button  class="btn btn-primary  pull-left" style="margin-left:20px;" >

          <span class="glyphicon glyphicon-print"></span> Impression Relevé

      </button>

  </form>

</div>

  <ul class="nav nav-tabs">
    <li class="active" id="listeSortiesEvent"><a data-toggle="tab" href="#listeSortiesTab">LISTE DES SORTIES</a></li>
  </ul>
  <div class="tab-content">
      <div class="tab-pane fade in active"  id="listeSortiesTab">
        <br />
        <div class="table-responsive">
          <table id="listeSorties" class="display tabStockSorties" width="100%" border="1">
            <thead>
              <tr>
                <th>IdLigne</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Quantite</th>
                <th>Unite Vente</th>
                <th>Prix Unite Vente</th>
                <th>Prix Total</th>
                <th>Description</th>
                <th>Piece Jointe</th>
                <th>Personnel</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
  </div>
</div>

<div class="modal fade"  id="imageNvDepense" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> Description : <b> <span id="idDepense_View"></span></b></h4>
            </div>
            <form   method="post" enctype="multipart/form-data">
                <div class="modal-body" style="padding:40px 50px;">
                    <input  type="text" style="display:none;" name="idDepense" id="idDepense_Upd_Nv" />
                    <div class="form-group" style="text-align:center;" >
                        <input type="file" name="file" accept="image/*" id="input_file_Depense" onchange="showPreviewDepense(event);"/><br />
                        <img style="display:none;" width="500px" height="500px" id="output_image_Depense"/>
                        <iframe style="display:none;" id="output_pdf_Depense" width="100%" height="500px"></iframe>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" style="display:none" class="btn btn-success pull-left" name="btnUploadImgDepense" id="btn_upload_Depense" >
                        <span class="glyphicon glyphicon-upload"></span> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>

</html>

<script type="text/javascript" src="scripts/insertionDepense.js"></script>