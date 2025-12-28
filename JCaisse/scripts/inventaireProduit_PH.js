$(document).ready(function() {

    $('#listeDoublonDesignation').DataTable({
      'processing': true,
      'serverSide': true,
      'destroy': true,
      'serverMethod': 'post',
      'ajax': {
          'url':'datatables/inventaireProduit_listeDoublonDesignation.php'
        },
      'dom': 'Blfrtip',
      "buttons": ['csv','print', 'excel', 'pdf'],
      "ordering": true,
      "order": [[0, 'desc']],
      "lengthMenu": [10, 25, 50, 100, 500, 5000, 7500],
      'columns': [
        { data: 'idDesignation' },
        { data: 'designation' },
        { data: 'codeBarreDesignation' },
        { data: 'forme' },
        { data: 'tableau' },
        { data: 'prixPublic' },
        { data: 'prixSession' },
        { data: 'operations' }
      ],
      "fnCreatedRow": function( nRow, data, iDataIndex ) {
          $(nRow).attr('class', "produit"+data['idDesignation']);
      },
      'columnDefs': [ 
          { "bVisible": false, "aTargets": [ 0 ] },
          {
          'targets': [7], /* column index */
          'orderable': false, /* true or false */
          "bSortable": true ,
          //"aTargets": [ 3 ], "bSortable": true 
      },
      ]
    }); 

    $("#listeDoublonEvent").on( "click", function (e){
        e.preventDefault();
        $('#listeDoublonDesignation').DataTable({
          'processing': true,
          'serverSide': true,
          'destroy': true,
          'serverMethod': 'post',
          'ajax': {
              'url':'datatables/inventaireProduit_listeDoublonDesignation.php'
            },
          'dom': 'Blfrtip',
          "buttons": ['csv','print', 'excel', 'pdf'],
          "ordering": true,
          "order": [[0, 'desc']],
          "lengthMenu": [10, 25, 50, 100, 500, 5000, 7500],
          'columns': [
            { data: 'idDesignation' },
            { data: 'designation' },
            { data: 'codeBarreDesignation' },
            { data: 'forme' },
            { data: 'tableau' },
            { data: 'prixPublic' },
            { data: 'prixSession' },
            { data: 'operations' }
          ],
          "fnCreatedRow": function( nRow, data, iDataIndex ) {
              $(nRow).attr('class', "produit"+data['idDesignation']);
          },
          'columnDefs': [ 
              { "bVisible": false, "aTargets": [ 0 ] },
              {
              'targets': [7], /* column index */
              'orderable': false, /* true or false */
              "bSortable": true ,
              //"aTargets": [ 3 ], "bSortable": true 
          },
          ]
        }); 
    });
    
    $("#listeDoublonDesignationEvent").on( "click", function (e){
      e.preventDefault();
      $('#listeDoublonDesignation').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/inventaireProduit_listeDoublonDesignation.php'
          },
        'dom': 'Blfrtip',
        "buttons": ['csv','print', 'excel', 'pdf'],
        "ordering": true,
        "order": [[0, 'desc']],
        "lengthMenu": [10, 25, 50, 100, 500, 5000, 7500],
        'columns': [
          { data: 'idDesignation' },
          { data: 'designation' },
          { data: 'codeBarreDesignation' },
          { data: 'forme' },
          { data: 'tableau' },
          { data: 'prixPublic' },
          { data: 'prixSession' },
          { data: 'operations' }
        ],
        "fnCreatedRow": function( nRow, data, iDataIndex ) {
            $(nRow).attr('class', "produit"+data['idDesignation']);
        },
        'columnDefs': [ 
            { "bVisible": false, "aTargets": [ 0 ] },
            {
            'targets': [7], /* column index */
            'orderable': false, /* true or false */
            "bSortable": true ,
            //"aTargets": [ 3 ], "bSortable": true 
        },
        ]
      }); 
    });

    $("#listeDoublonCodeBarreEvent").on( "click", function (e){
      e.preventDefault();
      $('#listeDoublonCodebarre').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/inventaireProduit_listeDoublonCodeBarre.php'
          },
        'dom': 'Blfrtip',
        "buttons": ['csv','print', 'excel', 'pdf'],
        "ordering": true,
        "order": [[0, 'desc']],
        "lengthMenu": [10, 25, 50, 100, 500, 5000, 7500],
        'columns': [
          { data: 'idDesignation' },
          { data: 'designation' },
          { data: 'codeBarreDesignation' },
          { data: 'forme' },
          { data: 'tableau' },
          { data: 'prixPublic' },
          { data: 'prixSession' },
          { data: 'operations' }
        ],
        "fnCreatedRow": function( nRow, data, iDataIndex ) {
            $(nRow).attr('class', "produit"+data['idDesignation']);
        },
        'columnDefs': [ 
            { "bVisible": false, "aTargets": [ 0 ] },
            {
            'targets': [7], /* column index */
            'orderable': false, /* true or false */
            "bSortable": true ,
            //"aTargets": [ 3 ], "bSortable": true 
        },
        ]
      }); 
    });

    $("#listeStockEvent").on( "click", function (e){
      e.preventDefault();
      $('#listeStock').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/inventaireProduit_listeStock.php'
        },
        'dom': 'Blfrtip',
        "buttons": ['csv','print', 'excel', 'pdf'],
        "ordering": true,
        "order": [[0, 'desc'],[1, 'desc']],
        "lengthMenu": [10, 25, 50, 100, 500, 5000, 7500],
        'columns': [
          { data: 'idDesignation' },
          { data: 'designation' },
          { data: 'codeBarreDesignation' },
          { data: 'forme' },
          { data: 'tableau' },
          { data: 'prixPublic' },
          { data: 'prixSession' },
          { data: 'quantite' },
          { data: 'inventaire' },
          { data: 'operations' }
        ],
        "fnCreatedRow": function( nRow, data, iDataIndex ) {
          $(nRow).attr('class', "stock"+data['idDesignation']);
        },
        'columnDefs': [ 
          { "bVisible": false, "aTargets": [ 0] },
          {
            'targets': [3,9], /* column index */
            'orderable': false, /* true or false */
         }
        ]
    });  
    });

    $("#listeInventaireEvent").on( "click", function (e){
        e.preventDefault();
        $('#listeInventaire').DataTable({
          'processing': true,
          'serverSide': true,
          'destroy': true,
          'serverMethod': 'post',
          'ajax': {
              'url':'datatables/inventaireProduit_listeInventaire.php'
          },
          'dom': 'Blfrtip',
          "buttons": ['csv','print', 'excel', 'pdf'],
          "ordering": true,
          "order": [[0, 'asc'],[1, 'desc']],
          "lengthMenu": [10, 25, 50, 100, 500, 5000, 7500],
          'columns': [
            { data: 'idDesignation' },
            { data: 'designation' },
            { data: 'codeBarreDesignation' },
            { data: 'forme' },
            { data: 'tableau' },
            { data: 'prixPublic' },
            { data: 'prixSession' },
            { data: 'quantite' },
            { data: 'inventaire' },
            { data: 'operations' }
          ],
          "fnCreatedRow": function( nRow, data, iDataIndex ) {
            $(nRow).attr('class', "stock"+data['idDesignation']);
          },
          'columnDefs': [ 
            { "bVisible": false, "aTargets": [ 0] },
            {
              'targets': [3,9], /* column index */
              'orderable': false, /* true or false */
           }
          ]
        });  
    });

    $("#listeNonInventaireEvent").on( "click", function (e){
        e.preventDefault();
        $('#listeNonInventaire').DataTable({
          'processing': true,
          'serverSide': true,
          'destroy': true,
          'serverMethod': 'post',
          'ajax': {
              'url':'datatables/inventaireProduit_listeNonInventaire.php'
          },
          'dom': 'Blfrtip',
          "buttons": ['csv','print', 'excel', 'pdf'],
          "ordering": true,
          "order": [[0, 'desc'],[1, 'desc']],
          "lengthMenu": [10, 25, 50, 100, 500, 5000, 7500],
          'columns': [
            { data: 'idDesignation' },
            { data: 'designation' },
            { data: 'codeBarreDesignation' },
            { data: 'forme' },
            { data: 'tableau' },
            { data: 'prixPublic' },
            { data: 'prixSession' },
            { data: 'quantite' },
            { data: 'operations' }
          ],
          "fnCreatedRow": function( nRow, data, iDataIndex ) {
            $(nRow).attr('class', "stock"+data['idDesignation']);
          },
          'columnDefs': [ 
            { "bVisible": false, "aTargets": [ 0] },
            {
              'targets': [3,8], /* column index */
              'orderable': false, /* true or false */
           }
          ]
        });  
    });

    $("#listeDetailsInventaireEvent").on( "click", function (e){
      e.preventDefault();
      $('#listeDetailsInventaire').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/inventaireProduit_listeDetailsInventaire.php'
        },
        'dom': 'Blfrtip',
        "buttons": ['csv','print', 'excel', 'pdf'],
        "ordering": true,
        "order": [[0, 'asc'],[1, 'desc']],
        "lengthMenu": [10, 25, 50, 100, 500, 5000, 7500],
        'columns': [
          { data: 'idDesignation' },
          { data: 'designation' },
          { data: 'codeBarreDesignation' },
          { data: 'forme' },
          { data: 'tableau' },
          { data: 'prixPublic' },
          { data: 'prixSession' },
          { data: 'quantite' },
          { data: 'inventaire' },
          { data: 'operations' }
        ],
        "fnCreatedRow": function( nRow, data, iDataIndex ) {
          $(nRow).attr('class', "stock"+data['idDesignation']);
        },
        'columnDefs': [ 
          { "bVisible": false, "aTargets": [ 0] },
          {
            'targets': [3,9], /* column index */
            'orderable': false, /* true or false */
         }
        ]
      });  
  });

    /**Debut Doublon Designation Produit**/
      $(function(){
          doublon_Designation= function (idProduit)  {
            $('#btn_Doublon_Designation-'+idProduit).prop("disabled", true);
            $.ajax({
              url:"calculs/inventaireProduit.php",
              method:"POST",
              data:{
                  operation:'doublon_Designation',
                  idProduit : idProduit
              },
              success: function (data) {
                var details = JSON.parse(data);
                var taille = details.length;
                $('#listeFusion tbody > tr').remove();
                for(var i = 0; i<taille; i++){
                  var ligne = "<tr class='produit"+details[i][0]+"'>"+
                    "<td>"+details[i][1]+"</td>"+
                    "<td>"+details[i][2]+"</td>"+
                    "<td>"+details[i][3]+"</td>"+
                    "<td>"+details[i][4]+"</td>"+
                    "<td>"+details[i][5]+"</td>"+
                    "<td>"+details[i][6]+"</td>"+
                    "<td>"+details[i][7]+"</td>"+
                    "<td>"+details[i][8]+"</td>"+
                  "</tr>";
                  $("#listeFusion").prepend(ligne);
                }  
                $("#lister_Doublon").modal(); 
              },
              error: function() {
                  alert("La requête "); },
              dataType:"text"
          }); 

          }
      });
    /**Fin Doublon Designation Produit**/

    /**Debut Doublon Code Barre Produit**/
      $(function(){
          doublon_CodeBarre= function (idProduit)  {
            $('#btn_Doublon_Designation_CodeBarre-'+idProduit).prop("disabled", true);
            $.ajax({
              url:"calculs/inventaireProduit.php",
              method:"POST",
              data:{
                  operation:'doublon_CodeBarre',
                  idProduit : idProduit
              },
              success: function (data) {
                var details = JSON.parse(data);
                var taille = details.length;
                $('#listeFusion tbody > tr').remove();
                for(var i = 0; i<taille; i++){
                  var ligne = "<tr class='produit"+details[i][0]+"'>"+
                    "<td>"+details[i][1]+"</td>"+
                    "<td>"+details[i][2]+"</td>"+
                    "<td>"+details[i][3]+"</td>"+
                    "<td>"+details[i][4]+"</td>"+
                    "<td>"+details[i][5]+"</td>"+
                    "<td>"+details[i][6]+"</td>"+
                    "<td>"+details[i][7]+"</td>"+
                    "<td>"+details[i][8]+"</td>"+
                  "</tr>";
                  $("#listeFusion").prepend(ligne);
                }  
                $("#lister_Doublon").modal(); 
              },
              error: function() {
                  alert("La requête "); },
              dataType:"text"
          }); 

          }
      });
    /**Fin Doublon Code Barre Produit**/

    /**Debut Fusion Designation Produit**/
      $(function(){
          fusion_Designation= function (idProduit)  {
            codeBarre=$('#inpt_Designation_CodeBarre_'+idProduit).val(); 
            prixPublic=$('#inpt_Designation_PrixPublic_'+idProduit).val();
            prixSession=$('#inpt_Designation_PrixSession_'+idProduit).val();
            $('.btn_Liste_Fusion').prop("disabled", true);
            $.ajax({
              url:"calculs/inventaireProduit.php",
              method:"POST",
              data:{
                  operation:'fusion_Designation',
                  idProduit : idProduit,
                  codeBarre : codeBarre,
                  prixPublic : prixPublic,
                  prixSession : prixSession
              },
              success: function (data) {
                var details = JSON.parse(data); 
                $("#lister_Doublon").modal('hide');
                $('#listeDoublonDesignation').DataTable().ajax.reload();
              },
              error: function() {
                  alert("La requête "); },
              dataType:"text"
            }); 
          }
      });
    /**Fin Fusion Designation Produit**/

    /**Debut Fusion Code Barre Produit**/
      $(function(){
          fusion_CodeBarre= function (idProduit)  {
            codeBarre=$('#inpt_CodeBarre_CodeBarre_'+idProduit).val(); 
            prixPublic=$('#inpt_CodeBarre_PrixPublic_'+idProduit).val();
            prixSession=$('#inpt_CodeBarre_PrixSession_'+idProduit).val();
            $('#btn_Valider-'+idProduit).prop("disabled", true); 
            $('.btn_Liste_Fusion').prop("disabled", true);
            $.ajax({
              url:"calculs/inventaireProduit.php",
              method:"POST",
              data:{
                  operation:'fusion_CodeBarre',
                  idProduit : idProduit,
                  codeBarre : codeBarre,
                  prixPublic : prixPublic,
                  prixSession : prixSession
              },
              success: function (data) {
                var details = JSON.parse(data); 
                $("#lister_Doublon").modal('hide');
                $('#listeDoublonCodebarre').DataTable().ajax.reload();
              },
              error: function() {
                  alert("La requête "); },
              dataType:"text"
            }); 
          }
      });
    /**Fin Fusion Code Barre Produit**/

    /**Debut Supprimer Designation Produit**/
      $(function(){
        supprimer_Designation= function (idProduit)  { 
          $('#btn_Valider-'+idProduit).prop("disabled", true); 
          $('.btn_Liste_Fusion').prop("disabled", true);
          $.ajax({
            url:"calculs/inventaireProduit.php",
            method:"POST",
            data:{
                operation:'supprimer_Designation',
                idProduit : idProduit
            },
            success: function (data) {
              var details = JSON.parse(data); 
              $("#lister_Doublon").modal('hide');
              $('#listeDoublonDesignation').DataTable().ajax.reload();
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
          }); 
        }
      });
    /**Fin Fusion Designation Produit**/

    /**Debut Supprimer Code Barre Produit**/
      $(function(){
          supprimer_CodeBarre= function (idProduit)  { 
            $('#btn_Valider-'+idProduit).prop("disabled", true); 
            $('.btn_Liste_Fusion').prop("disabled", true);
            $.ajax({
              url:"calculs/inventaireProduit.php",
              method:"POST",
              data:{
                  operation:'supprimer_CodeBarre',
                  idProduit : idProduit
              },
              success: function (data) {
                var details = JSON.parse(data); 
                $("#lister_Doublon").modal('hide');
                $('#listeDoublonCodebarre').DataTable().ajax.reload();
              },
              error: function() {
                  alert("La requête "); },
              dataType:"text"
            }); 
          }
      });
    /**Fin Supprimer Code Barre Produit**/

    /**Debut Inventaire Stock Produit**/
      $(function(){
          inventaire_Stock= function (idProduit)  {
            stock=$('#span_Stock_Quantite_'+idProduit).text();
            quantite=$('#inpt_Stock_Quantite_'+idProduit).val();
            prixPublic=$('#inpt_Stock_PrixPublic_'+idProduit).val();
            prixSession=$('#inpt_Stock_PrixSession_'+idProduit).val();
            $('#btn_Stock-'+idProduit).prop("disabled", true);
            $.ajax({
              url:"calculs/inventaireProduit.php",
              method:"POST",
              data:{
                  operation:'inventaire_Stock',
                  idProduit : idProduit,
                  stock : stock,
                  quantite : quantite,
                  prixPublic : prixPublic,
                  prixSession : prixSession
              },
              success: function (data) {
                var details = JSON.parse(data);
              },
              error: function() {
                  alert("La requête "); },
              dataType:"text"
          }); 

          }
      });
    /**Fin Inventaire Stock Produit**/

    /**Debut Ajouter Stock Produit**/
      $(function(){
          ajouter_Stock = function (idProduit)  {
            stock=$('#span_Inventaire_Quantite_'+idProduit).text();
            quantite=$('#inpt_Inventaire_Quantite_'+idProduit).val();
            prixPublic=$('#inpt_Inventaire_PrixPublic_'+idProduit).val();
            prixSession=$('#inpt_Inventaire_PrixSession_'+idProduit).val();
            $('#btn_Inventaire-'+idProduit).prop("disabled", true);
            $.ajax({
              url:"calculs/inventaireProduit.php",
              method:"POST",
              data:{
                  operation:'ajouter_Stock',
                  idProduit : idProduit,
                  stock : stock,
                  quantite : quantite,
                  prixPublic : prixPublic,
                  prixSession : prixSession
              },
              success: function (data) {
                //alert(data)
                var details = JSON.parse(data);
              },
              error: function() {
                  alert("La requête "); },
              dataType:"text"
          }); 

          }
      });
    /**Fin Ajouter Stock Produit**/

    /**Debut Inventaire Stock Produit Non Inventaire**/
      $(function(){
        inventaire_Produit = function (idProduit)  {
          quantite=$('#inpt_NonInventaire_Quantite_'+idProduit).val();
          prixPublic=$('#inpt_NonInventaire_PrixPublic_'+idProduit).val();
          prixSession=$('#inpt_NonInventaire_PrixSession_'+idProduit).val();
          prixAchat=$('#inpt_NonInventaire_PrixAchat_'+idProduit).val();  
          $('#btn_NonInventaire-'+idProduit).prop("disabled", true);
          $.ajax({
            url:"calculs/inventaireProduit.php",
            method:"POST",
            data:{
                operation:'inventaire_Produit',
                idProduit : idProduit,
                quantite : quantite,
                prixPublic : prixPublic,
                prixSession : prixSession
            },
            success: function (data) {
              var details = JSON.parse(data);
              //alert(data)
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        }); 

        }
      });
    /**Fin Inventaire Stock Produit Non Inventaire**/

    /**Debut Details Inventaire**/
      $(function(){
          details_Inventaire= function (idProduit)  {
            $('#btn_Doublon_Designation_CodeBarre-'+idProduit).prop("disabled", true);
            $('#listeAvant tbody > tr').remove();
            $('#listeApres tbody > tr').remove();
            $("#lister_Details").modal(); 
            $.ajax({
              url:"calculs/inventaireProduit.php",
              method:"POST",
              data:{
                  operation:'details_Inventaire',
                  idProduit : idProduit
              },
              success: function (data) {
                var details = JSON.parse(data);
                var taille_Inventaire = details[0].length;
                for(var i = 0; i<taille_Inventaire; i++){
                  var ligne = "<tr class='produit"+details[0][i][0]+"'>"+
                    "<td>"+details[0][i][1]+"</td>"+
                    "<td>"+details[0][i][2]+"</td>"+
                    "<td>"+details[0][i][3]+"</td>"+
                    "<td>"+details[0][i][4]+"</td>"+
                  "</tr>";
                  $("#listeAvant").prepend(ligne);
                }  

                var taille_Stock = details[1].length;
                for(var i = 0; i<taille_Stock; i++){
                  var ligne = "<tr class='produit"+details[1][i][0]+"'>"+
                    "<td>"+details[1][i][1]+"</td>"+
                    "<td>"+details[1][i][2]+"</td>"+
                    "<td>"+details[1][i][3]+"</td>"+
                    "<td>"+details[1][i][4]+"</td>"+
                  "</tr>";
                  $("#listeApres").prepend(ligne);
                }
              },
              error: function() {
                  alert("La requête "); },
              dataType:"text"
          }); 

          }
      });
    /**Fin Details Inventaire**/

    $("#btn_vider_Stock").on( "click", function (e){
      e.preventDefault();
      $("#btn_vider_Stock").prop("disabled", true); 
      $.ajax({
          url:"calculs/inventaireProduit.php",
          method:"POST",
          data:{
              operation:'vider_Stock',
          },
          success: function (data) {
            $('#vider_Stock').modal('hide');
            var details = JSON.parse(data);
          },
          error: function() {
              alert("La requête "); },
          dataType:"text"
      }); 
    });

});