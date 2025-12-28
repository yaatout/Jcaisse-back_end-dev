$(document).ready(function() {

  $('#listeStock').DataTable({
      'processing': true,
      'serverSide': true,
      'destroy': true,
      'serverMethod': 'post',
      'ajax': {
          'url':'datatables/gestionStock_listeStock_ET.php'
      },
      'dom': 'Blfrtip',
      "buttons": ['csv','print', 'excel', 'pdf'],
      "ordering": true,
      "order": [[0, 'desc']],
      "lengthMenu": [10, 25, 50, 100, 500, 5000, 7500],
      'columns': [
        { data: 'idStock' },
        { data: 'idDesignation' },
        { data: 'designation' },
        { data: 'sansdepot' },
        { data: 'surdepot' },
        { data: 'uniteStock' },
        { data: 'nbreArticleUniteStock' },
        { data: 'prixuniteStock' },
        { data: 'prixachat' },
        { data: 'operations' },
      ],
      "fnCreatedRow": function( nRow, data, iDataIndex ) {
        $(nRow).attr('class', "stock"+data['idDesignation']);
      },
      'columnDefs': [ 
        { "bVisible": false, "aTargets": [ 0,1 ] },
        {
          'targets': [3,7], /* column index */
          'orderable': false, /* true or false */
       }
      ]
  });  

  $.ajax({
      url:"calculs/gestionStock_ET.php",
      method:"POST",
      data:{
          operation:'stock',
      },
      success: function (data) {
        var details = JSON.parse(data);
          $("#montantAchats").text(details[0]);
          $("#montantVentes").text(details[1]);
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
          'url':'datatables/gestionStock_listeProduits_ET.php'
      },
      "ordering": true,
      "order": [[0, 'desc']],
      'columns': [
        { data: 'idDesignation' },
        { data: 'designation' },
        { data: 'quantite' },
        { data: 'uniteStock' },
        { data: 'prixuniteStock' },
        { data: 'prixachat' },
        { data: 'entrepot' },
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
    
    $.ajax({
      url:"calculs/gestionStock_ET.php",
      method:"POST",
      data:{
          operation:'choix_Fournisseur'
      },
      success: function (data) {

          var choix = JSON.parse(data);
          var taille = choix.length;

          $('#slct_Stock_Fournisseur option').remove();

          $('#slct_Stock_Fournisseur').append("<option selected >--------------</option>");

          for(var i = 0; i<taille; i++){
                  $('#slct_Stock_Fournisseur').append("<option value='"+choix[i][0]+"'>"+choix[i][1]+"</option>");
          }

      },
      error: function() {
        $("#message_Produit_titre").text('Erreur');
        $("#message_Produit .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
        $('#message_Produit').modal('show');
      },
      dataType:"text"
    }); 

    $.ajax({
        url:"calculs/gestionStock_ET.php",
        method:"POST",
        data:{
            operation:'choix_BL'
        },
        success: function (data) {
            var choix = JSON.parse(data);
            var taille = choix.length;

            $('#slct_Stock_BL option').remove();

            $('#slct_Stock_BL').append("<option selected value='0' >--------------</option>");

            for(var i = 0; i<taille; i++){
                    $('#slct_Stock_BL').append("<option value='"+choix[i][0]+"'>"+choix[i][1]+" / "+choix[i][2]+"</option>");
            }

        },
        error: function() {
          $("#message_Produit_titre").text('Erreur');
          $("#message_Produit .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
          $('#message_Produit').modal('show');
        },
        dataType:"text"
    }); 
    
    $("#lister_Produit").modal(); 

  });

  $(function(){
    choix_BL_Fournisseur= function (idFournisseur)  {
      $('#slct_Stock_BL option').remove();
      $.ajax({
        url:"calculs/gestionStock_ET.php",
        method:"POST",
        data:{
            operation:'choix_BL_Fournisseur',
            idFournisseur : idFournisseur
        },
        success: function (data) {
            var choix = JSON.parse(data);
            var taille = choix.length;

            $('#slct_Stock_BL').append("<option selected value='0' >--------------</option>");

            for(var i = 0; i<taille; i++){
                    $('#slct_Stock_BL').append("<option value='"+choix[i][0]+"'>"+choix[i][1]+" / "+choix[i][2]+"</option>");
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

  /**Debut Ajouter Stock Produit**/
    $(function(){
        ajouter_Stock = function (idProduit)  {
          quantite=$('#inpt_Stock_Quantite_'+idProduit).val();
          dateExpiration=$('#inpt_Stock_DateExpiration_'+idProduit).val();
          prixUniteStock=$('#inpt_Stock_PrixUniteStock_'+idProduit).val();
          prixAchat=$('#inpt_Stock_PrixAchat_'+idProduit).val();
          entrepot=$('#slct_Stock_Entrepot_'+idProduit).val();
          idBl=$('#slct_Stock_BL').val();

          $.ajax({
            url:"calculs/gestionStock_ET.php",
            method:"POST",
            data:{
                operation:'ajouter_Stock',
                idProduit : idProduit,
                quantite : quantite,
                prixUniteStock : prixUniteStock,
                prixAchat : prixAchat,
                entrepot : entrepot,
                dateExpiration : dateExpiration,
                idBl : idBl
            },
            success: function (data) {
              var details = JSON.parse(data);
              var ligne = "<tr class='stock"+idProduit+"'>"+
              "<td>"+details[1]+"</td>"+
              "<td>"+details[2]+"</td>"+
              "<td>"+details[3]+"</td>"+
              "<td>"+details[4]+"</td>"+
              "<td>"+details[5]+"</td>"+
              "<td>"+details[6]+"</td>"+
              "<td>"+details[7]+"</td>"+
              "<td>"+
                "<a href='stock-Entrepot.php?iDS="+idProduit+"'><span style='color:green;'>Details</span></a>"+
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
  /**Fin Ajouter Stock Produit**/

});