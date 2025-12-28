$(document).ready(function() {

    $('#listeEtiquette').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/impressionEtiquette_listeEtiquette.php'
        },
        "ordering": true,
        "order": [[0, 'desc'],[1, 'desc']],
        'columns': [
          { data: 'idDesignation' },
          { data: 'designation' },
          { data: 'quantite' },
          { data: 'unite' },
          { data: 'prix' },
          { data: 'operations' }
        ],
        "fnCreatedRow": function( nRow, data, iDataIndex ) {
          $(nRow).attr('class', "etiquette"+data['idDesignation']);
        },
        'columnDefs': [ 
          { "bVisible": false, "aTargets": [ 0 ] },
          {
            'targets': [3], /* column index */
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
            'url':'datatables/impressionEtiquette_listeProduits.php'
        },
        "ordering": true,
        "order": [[0, 'desc']],
        'columns': [
          { data: 'idDesignation' },
          { data: 'designation' },
          { data: 'quantite' },
          { data: 'uniteStock' },
          { data: 'prixuniteStock' },
          { data: 'prix' },
          { data: 'operations' },
        ],
        "fnCreatedRow": function( nRow, data, iDataIndex ) {
          $(nRow).attr('class', "produit"+data['idDesignation']);
        },
        'columnDefs': [ 
          { "bVisible": false, "aTargets": [ 0 ] },
          {
            'targets': [1,6], /* column index */
            'orderable': false, /* true or false */
            "bSortable": true ,
            //"aTargets": [ 3 ], "bSortable": true 
         },
        ]
      }); 
      
      $("#lister_Produit").modal(); 

    });

    /**Debut Ajouter Produit**/
      $(function(){
        ajouter_Produit = function (idProduit)  {
            quantite=$('#inpt_Stock_Quantite_'+idProduit).val();
            uniteStock=$('#slct_Stock_UniteStock_'+idProduit).val();
            prixUniteStock=$('#inpt_Stock_PrixUniteStock_'+idProduit).val();
            prixUnitaire=$('#inpt_Stock_PrixUnitaire_'+idProduit).val(); 

            $.ajax({
              url:"calculs/impressionEtiquette.php",
              method:"POST",
              data:{
                  operation:'ajouter_Produit',
                  idProduit : idProduit,
                  quantite : quantite,
                  uniteStock : uniteStock,
                  prixUniteStock : prixUniteStock,
                  prixUnitaire : prixUnitaire,
              },
              success: function (data) {
                var details = JSON.parse(data);
                var ligne = "<tr class='etiquette"+idProduit+"'>"+
                "<td>"+details['designation']+"</td>"+
                "<td>"+details['quantite']+"</td>"+
                "<td>"+details['unite']+"</td>"+
                "<td>"+details['prix']+"</td>"+
                "<td>"+
                    "<a><img src='images/drop.png' align='middle' alt='supprimer' onclick='supprimer_Etiquette("+idProduit+")' data-toggle='modal' /></a>"+
                "</td>"+
                "</tr>";

                $('#listeEtiquette tbody .etiquette'+idProduit).each(function() {
                  $(this).animate({ opacity: 1/2 }, 300);
                  $(this).hide(300, function () {
                      $(this).remove();
                  });
                });

                $("#listeEtiquette").prepend(ligne);
      
              },
              error: function() {
                  alert("La requête "); },
              dataType:"text"
          }); 

          }
      });
    /**Fin Ajouter Produit**/

    /**Debut Supprimer Produit**/
      $(function(){
        supprimer_Produit = function (idProduit)  {
          $.ajax({
              url:"calculs/impressionEtiquette.php",
              method:"POST",
              data:{
                  operation:'supprimer_Produit',
                  idProduit : idProduit
              },
              success: function (data) {
                $('#listeEtiquette tbody .etiquette'+idProduit).each(function() {
                    $(this).animate({ opacity: 1/2 }, 1000);
                    $(this).hide(3000, function () {
                        $(this).remove();
                    });
                });

              },
              error: function() {
                  alert("La requête "); },
              dataType:"text"
          });

        }
      });
    /**Fin Supprimer Produit**/

});