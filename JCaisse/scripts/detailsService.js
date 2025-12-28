$(document).ready(function() {

    var table="listeClients";

    id=$('#inpt_Produit_id').val();

    $('#listeSorties').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/detailsService_listeSorties.php',
            'data':{
                'id' : id,
            }
        },
        'dom': 'Blfrtip',
        "buttons": ['csv','print', 'excel', 'pdf'],
        "ordering": true,
        "order": [[0, 'desc']],
        'columns': [
            { data: 'idLigne' },
            { data: 'dateJour' },
            { data: 'heurePagnet' },
            { data: 'quantite' },
            { data: 'unitevente' },
            { data: 'prixunitevente' },
            { data: 'prixtotal' },
            { data: 'facture' },
            { data: 'client' },
            { data: 'personnel' },
        ],
        'columnDefs': [ 
            { "bVisible": false, "aTargets": [ 0 ] },
            {
            'targets': [8,9], /* column index */
            'orderable': false, /* true or false */
            }
        ]
    }); 

    $("#listeSortiesEvent").on( "click", function (e){
        e.preventDefault();
        
        $('#listeSorties').DataTable({
            'processing': true,
            'serverSide': true,
            'destroy': true,
            'serverMethod': 'post',
            'ajax': {
                'url':'datatables/detailsService_listeSorties.php',
                'data':{
                    'id' : id,
                }
            },
            'dom': 'Blfrtip',
            "buttons": ['csv','print', 'excel', 'pdf'],
            "ordering": true,
            "order": [[0, 'desc']],
            'columns': [
                { data: 'idLigne' },
                { data: 'dateJour' },
                { data: 'heurePagnet' },
                { data: 'quantite' },
                { data: 'unitevente' },
                { data: 'prixunitevente' },
                { data: 'prixtotal' },
                { data: 'facture' },
                { data: 'client' },
                { data: 'personnel' },
            ],
            'columnDefs': [ 
                { "bVisible": false, "aTargets": [ 0 ] },
                {
                'targets': [8,9], /* column index */
                'orderable': false, /* true or false */
                }
            ]
        }); 

    });



});