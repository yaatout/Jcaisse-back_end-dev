$(document).ready(function() {

    id=$('#inpt_BL_id').val();

    $('#listeStock').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/bonLivraison_listeStock_PH.php',
            'data':{
              'id' : id
          }
        },
        'dom': 'Blfrtip',
        "buttons": ['csv','print', 'excel', 'pdf'],
        "ordering": true,
        "order": [[0, 'desc'],[1, 'desc']],
        'columns': [
          { data: 'idStock' },
          { data: 'idDesignation' },
          { data: 'designation' },
          { data: 'initial' },
          { data: 'forme' },
          { data: 'prixSession' },
          { data: 'prixPublic' },
          { data: 'montantachat' },
          { data: 'dateStockage' },
          { data: 'dateExpiration' },
          { data: 'operations' }
        ],
        "fnCreatedRow": function( nRow, data, iDataIndex ) {
          $(nRow).attr('class', "stock"+data['idStock']);
        },
        'columnDefs': [ 
          { "bVisible": false, "aTargets": [ 0,1 ] },
          {
            'targets': [3,9], /* column index */
            'orderable': false, /* true or false */
         }
        ]
    });  

    $("#btn_lister_Produit").on( "click", function (e){
      e.preventDefault();

      $('#listeProduits').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/bonLivraison_listeProduits_PH.php'
        },
        "ordering": true,
        "order": [[0, 'desc']],
        'columns': [
          { data: 'idDesignation' },
          { data: 'designation' },
          { data: 'quantite' },
          { data: 'forme' },
          { data: 'prixSession' },
          { data: 'prixPublic' },
          { data: 'expiration' },
          { data: 'operations' },
        ],
        "fnCreatedRow": function( nRow, data, iDataIndex ) {
          $(nRow).attr('class', "produit"+data['idDesignation']);
        },
        'columnDefs': [ 
          { "bVisible": false, "aTargets": [ 0 ] },
          {
            'targets': [6,7], /* column index */
            'orderable': false, /* true or false */
            "bSortable": true ,
            //"aTargets": [ 3 ], "bSortable": true 
         },
        ]
      }); 
      
      $("#lister_Produit").modal(); 

    });

    /**Debut Ajouter Stock**/
      $(function(){
          ajouter_Stock = function (idProduit)  {
            quantite=$('#inpt_Stock_Quantite_'+idProduit).val();
            dateExpiration=$('#inpt_Stock_DateExpiration_'+idProduit).val();
            prixSession=$('#inpt_Stock_PrixSession_'+idProduit).val();
            prixPublic=$('#inpt_Stock_PrixPublic_'+idProduit).val();
            $.ajax({
              url:"calculs/bonLivraison_PH.php",
              method:"POST",
              data:{
                  operation:'ajouter_Stock',
                  idProduit : idProduit,
                  quantite : quantite,
                  prixSession : prixSession,
                  prixPublic : prixPublic,
                  dateExpiration : dateExpiration,
                  idBl : id
              },
              success: function (data) {
                var details = JSON.parse(data);
                var ligne = "<tr class='stock"+details[0]+"'>"+
                "<td>"+details[1]+"</td>"+
                "<td>"+details[2]+"</td>"+
                "<td>"+details[3]+"</td>"+
                "<td>"+details[4]+"</td>"+
                "<td>"+details[5]+"</td>"+
                "<td>"+details[6]+"</td>"+
                "<td>"+details[7]+"</td>"+
                "<td>"+details[9]+"</td>"+
                "<td>"+
                  "<a><img src='images/drop.png' align='middle' alt='supprimer' onclick='supprimer_Stock("+details[0]+")'  data-toggle='modal' /></a>&nbsp"+
                  "<a onclick='transferer_Stock("+details[0]+")' data-toggle='modal' ><span style='color:orange' class='glyphicon glyphicon-open'></span></a>&nbsp"+
                "</td>"+
                "</tr>";

                $('#listeStock tbody .stock'+idProduit).each(function() {
                  $(this).animate({ opacity: 1/2 }, 300);
                  $(this).hide(300, function () {
                      $(this).remove();
                  });
                });

                $("#listeStock").prepend(ligne);
                $('#spn_montant_Total').text(details[10]);
                $('#spn_montant_Bl').text(details[11]);
                $('#spn_montant_Restant').text(details[12]);

              },
              error: function() {
                  alert("La requête "); },
              dataType:"text"
          }); 

          }
      });
    /**Fin Ajouter Stock**/

    /**Debut Modifier Stock**/
      $(function(){
          modifier_Stock = function (idStock)  {
            quantite=$('#inpt_mdf_Stock_Quantite_'+idStock).val();
            dateExpiration=$('#inpt_mdf_Stock_DateExpiration_'+idStock).val();
            prixSession=$('#inpt_mdf_Stock_PrixSession_'+idStock).val(); 
            prixPublic=$('#inpt_mdf_Stock_PrixPublic_'+idStock).val(); 
            $.ajax({
              url:"calculs/bonLivraison_PH.php",
              method:"POST",
              data:{
                  operation:'modifier_Stock',
                  idStock : idStock,
                  quantite : quantite,
                  prixSession : prixSession,
                  prixPublic : prixPublic,
                  dateExpiration : dateExpiration,
                  idBl : id,
              },
              success: function (data) {
                var details = JSON.parse(data);
                $('#btn_modifier_Stock-'+idStock).remove();
                montant = quantite * prixSession;
                $('#listeStock tbody .stock'+idStock).each(function() {
                  $(this).find("td:eq(1)").html(quantite);
                  $(this).find("td:eq(3)").html(prixSession);
                  $(this).find("td:eq(4)").html(prixPublic);
                  $(this).find("td:eq(5)").html(montant);
                  $(this).find("td:eq(7)").html(dateExpiration);
                });
                $('#spn_montant_Total').text(details[0]);
                $('#spn_montant_Bl').text(details[1]);
                $('#spn_montant_Restant').text(details[2]);
              },
              error: function() {
                  alert("La requête "); },
              dataType:"text"
          }); 

          }
      });
    /**Fin Modifier Stock**/
  
    /**Debut Supprimer Stock**/
      $(function(){
        supprimer_Stock= function (idStock)  {
          $('#inpt_spm_Stock_idProduit').val('');
          $('#span_spm_Stock_Reference').text('');
          $('#span_spm_Stock_Quantite').text('');
          $('#span_spm_Stock_Forme').text('');
          $('#span_spm_Stock_PrixSession').text('');
          $('#span_spm_Stock_PrixPublic').text('');
          $('#span_spm_Stock_DateExpiration').text('');
          $.ajax({
            url:"calculs/bonLivraison_PH.php",
            method:"POST",
            data:{
                operation:'details_Stock',
                idStock : idStock,
            },
            success: function (data) {
                var details = JSON.parse(data);
              $('#inpt_spm_Stock_idProduit').val(idStock);
              $('#span_spm_Stock_Reference').text(details[0]);
              $('#span_spm_Stock_Quantite').text(details[1]);
              $('#span_spm_Stock_Forme').text(details[2]);
              $('#span_spm_Stock_PrixSession').text(details[3]);
              $('#span_spm_Stock_PrixPublic').text(details[4]);
              $('#span_spm_Stock_DateExpiration').text(details[5]);
              $('#supprimer_Stock').modal('show');
            },
            error: function() {
              $("#message_Produit_titre").text('Erreur');
              $("#message_Produit .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
              $('#message_Produit').modal('show');
           },
            dataType:"text"
          }); 
        }
      });
      $("#btn_supprimer_Stock").click( function(){
        idStock=$('#inpt_spm_Stock_idProduit').val();
        montant = $('#spn_montant_Bl').text();
        $.ajax({
            url:"calculs/bonLivraison_PH.php",
            method:"POST",
            data:{
                operation:'supprimer_Stock',
                idStock : idStock,
                idBl : id
            },
            success: function (data) {
              var details = JSON.parse(data);
              $('#listeStock tbody .stock'+idStock).each(function() {
                    $(this).animate({ opacity: 1/2 }, 1000);
                    $(this).hide(3000, function () {
                        $(this).remove();
                    });
              });
              $('#supprimer_Stock').modal('hide');
              $('#spn_montant_Total').text(details[0]);
              $('#spn_montant_Bl').text(details[1]);
              $('#spn_montant_Restant').text(details[2]);
            },
            error: function() {
              $("#message_Produit_titre").text('Erreur');
              $("#message_Produit .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
              $('#message_Produit').modal('show');
            },
            dataType:"text"
        }); 
      });
    /**Fin Supprimer Stock**/

    /**Debut Transferer Stock**/
      $(function(){
        transferer_Stock= function (idStock)  {
          $('#inpt_trsf_Stock_idProduit').val('');
          $('#inpt_trsf_Stock_Reference').val('');
          $('#inpt_trsf_Stock_Quantite').val('');
          $('#inpt_trsf_Stock_Forme').val('');
          $('#slct_trsf_Stock_Fournisseur option').remove();
          $('#slct_trsf_Stock_BL option').remove();

          $.ajax({
            url:"calculs/bonLivraison_PH.php",
            method:"POST",
            data:{
                operation:'details_Stock',
                idStock : idStock,
            },
            success: function (data) {
                var details = JSON.parse(data);
              $('#inpt_trsf_Stock_idProduit').val(idStock);
              $('#inpt_trsf_Stock_Reference').val(details[0]);
              $('#inpt_trsf_Stock_Quantite').val(details[1]);
              $('#inpt_trsf_Stock_Forme').val(details[2]);

              $.ajax({
                url:"calculs/bonLivraison.php",
                method:"POST",
                data:{
                    operation:'choix_Fournisseur'
                },
                success: function (data) {
        
                    var choix = JSON.parse(data);
                    var taille = choix.length;
        
                    $('#slct_trsf_Stock_Fournisseur option').remove();
        
                    $('#slct_trsf_Stock_Fournisseur').append("<option selected >--------------</option>");
        
                    for(var i = 0; i<taille; i++){
                            $('#slct_trsf_Stock_Fournisseur').append("<option value='"+choix[i][0]+"'>"+choix[i][1]+"</option>");
                    }
        
                },
                error: function() {
                  $("#message_Produit_titre").text('Erreur');
                  $("#message_Produit .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
                  $('#message_Produit').modal('show');
                },
                dataType:"text"
              }); 

              $('#transferer_Stock').modal('show');
            },
            error: function() {
              $("#message_Produit_titre").text('Erreur');
              $("#message_Produit .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
              $('#message_Produit').modal('show');
          },
            dataType:"text"
          }); 
        }
      });
      $(function(){
        choix_BL_Fournisseur= function (idFournisseur)  {
          $('#slct_trsf_Stock_BL option').remove();
          $.ajax({
            url:"calculs/bonLivraison_PH.php",
            method:"POST",
            data:{
                operation:'choix_BL_Fournisseur',
                idFournisseur : idFournisseur
            },
            success: function (data) {
                var choix = JSON.parse(data);
                var taille = choix.length;

                $('#slct_trsf_Stock_BL').append("<option selected value='0' >--------------</option>");

                for(var i = 0; i<taille; i++){
                        $('#slct_trsf_Stock_BL').append("<option value='"+choix[i][0]+"'>"+choix[i][1]+" / "+choix[i][2]+"</option>");
                }

            },
            error: function() {
              $("#message_Produit_titre").text('Erreur');
              $("#message_Produit .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
              $('#message_Produit').modal('show');
            },
            dataType:"text"
          }); 
        }
      });
      $("#btn_transferer_Stock").click( function(){
        idStock=$('#inpt_trsf_Stock_idProduit').val();
        idBon=$('#slct_trsf_Stock_BL').val();
        $.ajax({
            url:"calculs/bonLivraison_PH.php",
            method:"POST",
            data:{
                operation:'transferer_Stock',
                idStock : idStock,
                idBl : id,
                idBon : idBon
            },
            success: function (data) {
              var details = JSON.parse(data);
              $('#listeStock tbody .stock'+idStock).each(function() {
                    $(this).animate({ opacity: 1/2 }, 1000);
                    $(this).hide(3000, function () {
                        $(this).remove();
                    });
              });
              $('#transferer_Stock').modal('hide');
              $('#spn_montant_Total').text(details[0]);
              $('#spn_montant_Bl').text(details[1]);
              $('#spn_montant_Restant').text(details[2]);
            },
            error: function() {
              $("#message_Produit_titre").text('Erreur');
              $("#message_Produit .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
              $('#message_Produit').modal('show');
            },
            dataType:"text"
        }); 
      });
    /**Fin Transferer Stock**/

});