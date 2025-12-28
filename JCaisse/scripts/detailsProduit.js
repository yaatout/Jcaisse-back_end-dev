$(document).ready(function() {

    id=$('#inpt_Produit_id').val();
    dateDebut=$('#inpt_Produit_dateDebut').val();
    dateFin=$('#inpt_Produit_dateFin').val();

    $('#listeEntrees').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/detailsProduit_listeEntrees.php',
            'data':{
                'id' : id,
                'dateDebut' : dateDebut,
                'dateFin' : dateFin
            }
        },
        'dom': 'Blfrtip',
        "buttons": ['csv','print', 'excel', 'pdf'],
        "lengthMenu": [10, 25, 50, 100, 500, 5000, 7500],
        "ordering": true,
        "order": [[0, 'desc']],
        'columns': [
            { data: 'idStock' },
            { data: 'dateStockage' },
            { data: 'quantiteStockinitial' },
            { data: 'quantiteStockCourant' },
            { data: 'uniteStock' },
            { data: 'prixuniteStock' },
            { data: 'prixunitaire' },
            { data: 'prixachat' },
            { data: 'dateExpiration' },
            { data: 'personnel' },
        ],
        "fnCreatedRow": function( nRow, data, iDataIndex ) {
            $(nRow).attr('class', "entrees"+data['idStock']);
        },
        'columnDefs': [ 
            { "bVisible": false, "aTargets": [ 0 ] },
            {
            'targets': [9], /* column index */
            'orderable': false, /* true or false */
            }
        ]
    });  

    $("#listeEntreesEvent").on( "click", function (e){
        e.preventDefault();
        $('#listeEntrees').DataTable({
            'processing': true,
            'serverSide': true,
            'destroy': true,
            'serverMethod': 'post',
            'ajax': {
                'url':'datatables/detailsProduit_listeEntrees.php',
                'data':{
                    'id' : id,
                    'dateDebut' : dateDebut,
                    'dateFin' : dateFin
                }
            },
            'dom': 'Blfrtip',
            "buttons": ['csv','print', 'excel', 'pdf'],
            "lengthMenu": [10, 25, 50, 100, 500, 5000, 7500],
            "ordering": true,
            "order": [[0, 'desc']],
            'columns': [
                { data: 'idStock' },
                { data: 'dateStockage' },
                { data: 'quantiteStockinitial' },
                { data: 'quantiteStockCourant' },
                { data: 'uniteStock' },
                { data: 'prixuniteStock' },
                { data: 'prixunitaire' },
                { data: 'prixachat' },
                { data: 'dateExpiration' },
                { data: 'personnel' },
            ],
            "fnCreatedRow": function( nRow, data, iDataIndex ) {
                $(nRow).attr('class', "entrees"+data['idStock']);
            },
            'columnDefs': [ 
                { "bVisible": false, "aTargets": [ 0 ] },
                {
                'targets': [9], /* column index */
                'orderable': false, /* true or false */
                }
            ]
        });  

    });

    $("#listeSortiesEvent").on( "click", function (e){
        e.preventDefault();
        
        $('#listeSorties').DataTable({
            'processing': true,
            'serverSide': true,
            'destroy': true,
            'serverMethod': 'post',
            'ajax': {
                'url':'datatables/detailsProduit_listeSorties.php',
                'data':{
                    'id' : id,
                    'dateDebut' : dateDebut,
                    'dateFin' : dateFin
                }
            },
            'dom': 'Blfrtip',
            "buttons": ['csv','print', 'excel', 'pdf'],
            "lengthMenu": [10, 25, 50, 100, 500, 5000, 7500],
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

    $("#listeInventairesEvent").on( "click", function (e){
        e.preventDefault();

        $('#listeInventaires').DataTable({
            'processing': true,
            'serverSide': true,
            'destroy': true,
            'serverMethod': 'post',
            'ajax': {
                'url':'datatables/detailsProduit_listeInventaires.php',
                'data':{
                    'id' : id,
                    'dateDebut' : dateDebut,
                    'dateFin' : dateFin
                }
            },
            'dom': 'Blfrtip',
            "buttons": ['csv','print', 'excel', 'pdf'],
            "lengthMenu": [10, 25, 50, 100, 500, 5000, 7500],
            "ordering": true,
            "order": [[0, 'desc']],
            'columns': [
                { data: 'idInventaire' },
                { data: 'dateInventaire' },
                { data: 'quantite' },
                { data: 'quantiteStockCourant' },
                { data: 'dateStockage' },
                { data: 'type' },
            ],
            'columnDefs': [ 
                { "bVisible": false, "aTargets": [ 0 ] },
                {
                'targets': [4], /* column index */
                'orderable': false, /* true or false */
                }
            ]
        }); 

    });

    $.ajax({
        url:"calculs/insertionProduit.php",
        method:"POST",
        data:{
            operation:'produit',
            idProduit : id,
            dateDebut : dateDebut,
            dateFin : dateFin
        },
        success: function (data) {
          var details = JSON.parse(data);
             $("#quantiteEntrees").text(details[0]);
             $("#quantiteSorties").text(details[1]);
             $("#montantVente").text(details[2]);
             $("#montantBon").text(details[3]);
        },
        error: function() {
            console("La requête "); 
        },
        dataType:"text"
    });


});