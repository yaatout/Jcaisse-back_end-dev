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

if(!$_SESSION['iduser']){
	header('Location:../index.php');
}
require('connection.php');

require('connectionPDO.php');

require('declarationVariables.php');

?>
<html>
    <head>
        <style>
            p.inline {display: inline-block;}
           /*  span { font-size: 5px; font-weight: bold; } */
        </style>
        <style type="text/css" media="print">
                @page
                {
                    size: auto;   /* auto is the initial value */
                    margin: 0px;  /* this affects the margin in the printer settings */
                    border: none ;
                    padding: 0px;
            
                }
        </style>
        <script src="js/jquery-3.1.1.min.js"></script>
        <link rel="stylesheet" type="text/css" href="css/facture.css">
    </head>
    <body>
      <div style="margin-right: 2%;">
        <div class="ticket">
            <p class="centered"> <strong style="font-size: 15px;"><?php echo strtoupper($_SESSION['labelB']); ?></strong>
                <br> ADRESSE : <?php echo $_SESSION['adresseB']; ?>
                <br> TELEPHONE : <?php echo $_SESSION['telBoutique']; ?>
            </p>
            <p class="centered" style="margin-right: 20px;">
              <span style="float: left"> <strong>Ticket</strong> : <span id="spn_Ticket"></span>  </span>  
              <span style="float: right"> <strong>Caissier(e)</strong> : <span id="spn_Caissier"></span>  </span>  
              <br>
              <span style="float: left"> <strong>Date</strong> : <span id="spn_Date"></span></span> 
              <span style="float: right"> <strong>Heure</strong> : <span id="spn_Heure"></span></span>  
            </p>
            <table>
                <thead>
                    <tr>
                        <th class="description">Description</th>
                        <th class="quantite">Q.</th>
                        <th class="prix">Pu</th>
                        <th class="total">Pt</th>
                    </tr>
                </thead>
                <tbody class="tablePanier">
                </tbody>
            </table>
            <p class="centered" style="margin-right: 20px;">
              <span style="float: left"> <strong>Total</strong> : <span id="spn_Total"></span></span> 
              <span style="float: right"> <strong>Compte</strong> : <span id="spn_Compte"></span></span>  
              <br>
              <span style="float: left"> <strong>Remise</strong> : <span id="spn_Remise"></span></span>
              <span style="float: right" class='spn_Caisse' > <strong>Especes</strong> : <span id="spn_Especes"></span> </span> 
              <br>
              <span style="float: left"> <strong>Net TTC</strong> : <span id="spn_NetAPayer"></span></span> 
              <span class='spn_Caisse' style="float: right"> <strong>Rendu</strong> : <span id="spn_Rendu"></span></span>  
              <br class='spn_Bon'>
              <span class='spn_Bon' style="float: left"> <strong>Client</strong> </span> 
              <span class='spn_Bon' style="float: right" id="spn_Client"></span>     
              <br> 
            </p>
            ************************************************************
            <p align="center"> 
                <span class="align-middle"  style="font-size:12px; font-family: Lucida, Lucida Calligraphy, Georgia, serif;">
                 MERCI DE VOTRE VISITE 
                </span>
            </p>
            ************************************************************
            <br> 
            <p align="center" id="spn_Barcode"></p>
        </div>
      </div>
    </body>    
    <script>
      $(document).ready(function () {
        var idPagnet = GetParameterValues('id');

        var type='code128';
          var orientation='horizontal';
          var size='30';
          var print='false';

          var barcode='<b style="font-size: 20px;">'+
            '<img class="barcode" style="width:5cm;"  src="barcode128.php?text='+idPagnet+'&codetype='+type+'&orientation='+orientation+'&size='+size+'&print='+print+'"/>'+
          '</b>'; 
          $('#spn_Barcode').prepend(barcode);

        $.ajax({
            url:"calculs/ventes.php",
            method:"POST",
            data:{
                operation:'detailsPanier',
                idPagnet : idPagnet,
            },
            success: function (data) {
              var lignes = JSON.parse(data);
              $('#spn_Ticket').text('# '+lignes[0]);
              $('#spn_Caissier').text(lignes[11]);
              if(lignes[1]!=0){
                $('#spn_Client').text(lignes[1]);
                $('.spn_Bon').show();
              }
              else{
                $('.spn_Bon').hide();
                $('.spn_Caisse').hide();
              }
              $('#spn_Date').text(lignes[2]);
              $('#spn_Heure').text(lignes[3]); 
              $('#spn_Total').text(lignes[4]);
              $('#spn_Remise').text(lignes[5]);
              $('#spn_NetAPayer').text(lignes[6]);
              compte = lignes[7].split('.');
              if (compte[0]==0 || compte[0]==1){
                $('#spn_Compte').text(compte[1]); 
                $('#spn_Especes').text(lignes[8]);
                $('#spn_Rendu').text(lignes[9]); 
                $('.spn_Caisse').show();
              }
              else{
                $('#spn_Compte').text(compte[1]);
                $('.spn_Caisse').hide();
              }
              var taille = lignes[10].length;
              for(var i = 0; i<taille; i++){
                var ligne = "<tr>"+
                  "<td>"+lignes[10][i][1].toLowerCase()+" ("+lignes[10][i][3]+")</td>"+
                  "<td class='centered'>"+lignes[10][i][2]+"</td>"+
                  "<td class='centered'>"+lignes[10][i][4]+"</td>"+
                  "<td class='centered'>"+lignes[10][i][5]+"</td>"+
                  "</tr>";
                  $(".tablePanier").prepend(ligne);
              } 

              window.print(); 
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        }); 

        function GetParameterValues(param) {
          var url = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
          for (var i = 0; i < url.length; i++) {
          var urlparam = url[i].split('=');
            if (urlparam[0] == param) {
              return urlparam[1];
            }
          }
        }
      });
      setTimeout("self.close()", 5000 );
    </script>

</html>