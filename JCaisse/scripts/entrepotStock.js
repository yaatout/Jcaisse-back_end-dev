$(document).ready(function() {

  id=$('#inpt_Entrepot_id').val();

    $('#listeStock').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/entrepotStock_listeStock.php',
            'data':{
              'id' : id
            }
        },
        'dom': 'Blfrtip',
        "buttons": ['csv','print', 'excel', 'pdf'],
        "ordering": true,
        "order": [[0, 'desc'],[1, 'desc']],
        "lengthMenu": [10, 25, 50, 100, 500, 5000, 7500],
        'columns': [
          { data: 'idStock' },
          { data: 'idDesignation' },
          { data: 'designation' },
          { data: 'codeBarreDesignation' },
          { data: 'quantite' },
          { data: 'uniteStock' },
          { data: 'nbreArticleUniteStock' },
          { data: 'prixuniteStock' },
          { data: 'prixunitaire' },
          { data: 'prixachat' },
          { data: 'operations' }
        ],
        "fnCreatedRow": function( nRow, data, iDataIndex ) {
          $(nRow).attr('class', "stock"+data['idDesignation']);
        },
        'columnDefs': [ 
          { "bVisible": false, "aTargets": [ 0,1 ] },
          {
            'targets': [3,10], /* column index */
            'orderable': false, /* true or false */
         }
        ]
    });  


});