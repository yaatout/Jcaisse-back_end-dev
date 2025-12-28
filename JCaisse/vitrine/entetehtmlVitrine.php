<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <base href="https://jcaisse.org/JCaisse/">
  <!-- <base href="http://localhost:81/jcaisse_save/JCaisseI/"> -->
   <link rel="shortcut icon" href="images/favicon.png">
   <link rel="stylesheet" href="css/bootstrap.css">
   <link rel="stylesheet" href="css/datatables.min.css">
   <link rel="stylesheet" href="vitrine/css/croppie.css" />
   <script src="js/jquery-3.1.1.min.js"></script>
   <script src="js/bootstrap.js"></script>
   <script type="text/javascript" src="js/jquery-barcode.js"></script>
   <script type="text/javascript" src="js/jquery.mask.min.js"></script>

   <!-- <link rel="stylesheet" href="vitrine/crop/jquery.Jcrop.min.css" type="text/css" />
   <script src="vitrine/crop/jquery.Jcrop.min.js"></script> -->
   <style>
     .panel-group .panel {
          border-radius: 1px;
          box-shadow: none;
          border-color: #EEEEEE;
      }
      .panel-title > a {
          display: block;

      }
  </style>
  <style>
  </style>
   <script src="js/datatables.min.js"></script>
   <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
   <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
   <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
   <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
   <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
   <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
   <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
   <script> $(document).ready(function () { $("#exemple").DataTable();});</script>
   <script> $(document).ready(function () { $("#exemple2").DataTable();});</script>
   <script> $(document).ready(function () { $("#exemple3").DataTable();});</script>
   <script type="text/javascript" src="js/script.js"></script>
    <script type="text/javascript" src="vitrine/js/scriptsVitrine.js"></script>
    <?php
        if ($_SESSION['type']=="Entrepot") {
    ?>
        <script type="text/javascript" src="vitrine/js/scriptsVitrine-entrepot.js"></script>

    <?php
        }
    ?>

<script type="text/javascript" src="vitrine/js/croppie.js"></script>
<script type="text/javascript" src="vitrine/js/typeahead/bootstrap3-typeahead.min.js"></script>
   <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" />

<!-- Include Date Range Picker -->
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
   <!--script type="text/javascript" src="validationAjoutReference.js"></script-->
   <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="vitrine/css/styleVitrine.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style media="print" type="text/css">   .noImpr {   display:none;   } </style>
	<title>JCAISSE</title>
</head>
