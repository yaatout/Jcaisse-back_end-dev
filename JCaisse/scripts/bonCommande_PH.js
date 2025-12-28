$(document).ready(function() {

    id=$('#inpt_BL_id').val();

    $('#listeStock').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/bonCommande_listeStock_PH.php',
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
          { data: 'quantite' },
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
            'targets': [3,10], /* column index */
            'orderable': false, /* true or false */
         }
        ]
    });  

    $.ajax({
        url:"calculs/bonCommande_PH.php",
        method:"POST",
        data:{
            operation:'stock',
        },
        success: function (data) {
          var details = JSON.parse(data);
             $("#montantAchats").text(details[0]);
             $("#montantVentes").text(details[1]);
            //console.log(data);*/
        },
        error: function() {
            console("La requête "); 
        },
        dataType:"text"
    });

    $("#btn_lister_Produit").on( "click", function (e){
      e.preventDefault();

      $('#listeProduits').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/bonCommande_listeProduits_PH.php'
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
              url:"calculs/bonCommande_PH.php",
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
                "<td>"+details[8]+"</td>"+
                "<td>"+
                  "<a><img src='images/drop.png' align='middle' alt='supprimer' onclick='supprimer_Stock("+details[0]+")'  data-toggle='modal' /></a>&nbsp"+
                "</td>"+
                "</tr>";

                $('#listeStock tbody .stock'+idProduit).each(function() {
                  $(this).animate({ opacity: 1/2 }, 300);
                  $(this).hide(300, function () {
                      $(this).remove();
                  });
                });

                $("#listeStock").prepend(ligne);

              },
              error: function() {
                  alert("La requête "); },
              dataType:"text"
          }); 

          }
      });
    /**Fin Ajouter Stock**/
  
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
            url:"calculs/bonCommande_PH.php",
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
        $.ajax({
            url:"calculs/bonCommande_PH.php",
            method:"POST",
            data:{
                operation:'supprimer_Stock',
                idStock : idStock
            },
            success: function (data) {
              $('#listeStock tbody .stock'+idStock).each(function() {
                    $(this).animate({ opacity: 1/2 }, 1000);
                    $(this).hide(3000, function () {
                        $(this).remove();
                    });
              });
              $('#supprimer_Stock').modal('hide');
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

});