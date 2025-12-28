$(document).ready(function() {

    dateDebut=$('#inpt_Mouvement_dateDebut').val();
    dateFin=$('#inpt_Mouvement_dateFin').val();

    $('#listeClients').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/bon_listeClients.php'
        },
        'dom': 'Blfrtip',
      "buttons": ['csv','print', 'excel', 'pdf'],
      "lengthMenu": [10, 25, 50, 100, 500, 5000, 7500],
        'columns': [
          { data: 'nom' },
          { data: 'prenom' },
          { data: 'adresse' },
          { data: 'telephone' },
          { data: 'plafond' },
          { data: 'montant' },
          { data: 'operations' }
        ],
        'columnDefs': [ {
            'targets': [6], /* column index */
            'orderable': false, /* true or false */
         }
        ]
    });  

    $.ajax({
        url:"calculs/bon.php",
        method:"POST",
        data:{
            operation:'clients',
        },
        success: function (data) {
            $("#montantClients").text(data);
            //console.log(data);*/
        },
        error: function() {
            console("La requête "); 
        },
        dataType:"text"
    });

    $("#listeClientsEvent").on( "click", function (e){
        e.preventDefault();
        $('#listeClients').DataTable({
            'processing': true,
            'serverSide': true,
            "destroy": true,
            'serverMethod': 'post',
            'ajax': {
                'url':'datatables/bon_listeClients.php'
            },
            'dom': 'Blfrtip',
            "buttons": ['csv','print', 'excel', 'pdf'],
            "lengthMenu": [10, 25, 50, 100, 500, 5000, 7500],
            'columns': [
              { data: 'nom' },
              { data: 'prenom' },
              { data: 'adresse' },
              { data: 'telephone' },
              { data: 'plafond' },
              { data: 'montant' },
              { data: 'operations' }
            ],
            'columnDefs': [ {
                'targets': [5,6], /* column index */
                'orderable': false, /* true or false */
             }]
        });

        $.ajax({
            url:"calculs/bon.php",
            method:"POST",
            data:{
                operation:'clients',
            },
            success: function (data) {
                $("#montantClients").text(data);
                //console.log(data);*/
            },
            error: function() {
                console("La requête "); 
            },
            dataType:"text"
        });
    });

    $("#listeDepotsEvent").on( "click", function (e){
        e.preventDefault();
        $('#listeDepots').DataTable({
            'processing': true,
            'serverSide': true,
            "destroy": true,
            'serverMethod': 'post',
            'ajax': {
                'url':'datatables/bon_listeDepots.php'
            },
            'dom': 'Blfrtip',
            "buttons": ['csv','print', 'excel', 'pdf'],
            "order": [[1, 'desc']],
            "lengthMenu": [10, 25, 50, 100, 500, 5000, 7500],
            'columns': [
              { data: 'nom' },
              { data: 'prenom' },
              { data: 'adresse' },
              { data: 'telephone' },
              { data: 'montant' },
              { data: 'operations' }
            ],
            'columnDefs': [ {
                'targets': [4,5], /* column index */
                'orderable': false, /* true or false */
             }]
        });

        $.ajax({
            url:"calculs/bon.php",
            method:"POST",
            data:{
                operation:'depots',
            },
            success: function (data) {
                $("#montantDepots").text(data);
                //console.log(data);*/
            },
            error: function() {
                console("La requête "); 
            },
            dataType:"text"
        });
    });

    $("#listeDettesEvent").on( "click", function (e){
        e.preventDefault();
        $('#listeDettes').DataTable({
            'processing': true,
            'serverSide': true,
            "destroy": true,
            'serverMethod': 'post',
            'ajax': {
                'url':'datatables/bon_listeDettes.php'
            },
            'dom': 'Blfrtip',
            "buttons": ['csv','print', 'excel', 'pdf'],
            "order": [[0, 'asc']],
            "lengthMenu": [10, 25, 50, 100, 500, 5000, 7500],
            'columns': [
              { data: 'nom' },
              { data: 'prenom' },
              { data: 'adresse' },
              { data: 'telephone' },
              { data: 'montant' },
              { data: 'operations' }
            ],
            'columnDefs': [ {
                'targets': [4,5], /* column index */
                'orderable': false, /* true or false */
             }]
        });

        $.ajax({
            url:"calculs/bon.php",
            method:"POST",
            data:{
                operation:'dettes',
            },
            success: function (data) {
                $("#montantDettes").text(data);
                //console.log(data);*/
            },
            error: function() {
                console("La requête "); 
            },
            dataType:"text"
        });
    });

    $("#listePersonnelsEvent").on( "click", function (e){
        e.preventDefault();
        $('#listePersonnels').DataTable({
                'processing': true,
                'serverSide': true,
                "destroy": true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'datatables/bon_listePersonnels.php'
                },
                'dom': 'Blfrtip',
                "buttons": ['csv','print', 'excel', 'pdf'],
                "order": [[0, 'asc']],
                "lengthMenu": [10, 25, 50, 100, 500, 5000, 7500],
                'columns': [
                { data: 'nom' },
                { data: 'prenom' },
                { data: 'adresse' },
                { data: 'telephone' },
                { data: 'montant' },
                { data: 'operations' }
                ],
                'columnDefs': [ {
                    'targets': [4,5], /* column index */
                    'orderable': false, /* true or false */
                 }]
        });

        $.ajax({
            url:"calculs/bon.php",
            method:"POST",
            data:{
                operation:'personnels',
            },
            success: function (data) {
                $("#montantPersonnels").text(data);
                //console.log(data);*/
            },
            error: function() {
                console("La requête "); 
            },
            dataType:"text"
        });
    });

    $("#listeArchivesEvent").on( "click", function (e){
        e.preventDefault();
        $('#listeArchives').DataTable({
                'processing': true,
                'serverSide': true,
                "destroy": true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'datatables/bon_listeArchives.php'
                },
                'dom': 'Blfrtip',
                "buttons": ['csv','print', 'excel', 'pdf'],
                "order": [[0, 'asc']],
                "lengthMenu": [10, 25, 50, 100, 500, 5000, 7500],
                'columns': [
                { data: 'nom' },
                { data: 'prenom' },
                { data: 'adresse' },
                { data: 'telephone' },
                { data: 'montant' },
                { data: 'operations' }
                ],
                'columnDefs': [ {
                    'targets': [4,5], /* column index */
                    'orderable': false, /* true or false */
                 }]
        });

        $.ajax({
            url:"calculs/bon.php",
            method:"POST",
            data:{
                operation:'archives',
            },
            success: function (data) {
                $("#montantArchives").text(data);
                //console.log(data);*/
            },
            error: function() {
                console("La requête "); 
            },
            dataType:"text"
        });
    });

    $("#listeAvoirsEvent").on( "click", function (e){
        e.preventDefault();
        $('#listeAvoirs').DataTable({
                'processing': true,
                'serverSide': true,
                "destroy": true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'datatables/bon_listeAvoirs.php'
                },
                'dom': 'Blfrtip',
                "buttons": ['csv','print', 'excel', 'pdf'],
                "order": [[0, 'asc']],
                "lengthMenu": [10, 25, 50, 100, 500, 5000, 7500],
                'columns': [
                { data: 'nom' },
                { data: 'prenom' },
                { data: 'adresse' },
                { data: 'telephone' },
                { data: 'montant' },
                { data: 'operations' }
                ],
                'columnDefs': [ {
                    'targets': [4,5], /* column index */
                    'orderable': false, /* true or false */
                 }]
        });

        $.ajax({
            url:"calculs/bon.php",
            method:"POST",
            data:{
                operation:'avoirs',
            },
            success: function (data) {
                $("#montantAvoirs").text(data);
                //console.log(data);*/
            },
            error: function() {
                console("La requête "); 
            },
            dataType:"text"
        });
    });

    $("#listePeriodiqueEvent").on( "click", function (e){
        e.preventDefault();
        $('#listePeriodique').DataTable({
            'processing': true,
            'serverSide': true,
            "destroy": true,
            'serverMethod': 'post',
            'ajax': {
                'url':'datatables/bon_listePeriodique.php',
                'data':{
                    'dateDebut' : dateDebut,
                    'dateFin' : dateFin
                }
            },
            'dom': 'Blfrtip',
            "buttons": ['csv','print', 'excel', 'pdf'],
            "order": [[3, 'desc'],[4, 'desc']],
            "lengthMenu": [10, 25, 50, 100, 500, 5000, 7500],
            'columns': [
                { data: 'nom' },
                { data: 'prenom' },
                { data: 'telephone' },
                { data: 'quantite' },
                { data: 'montant', className: 'dt-head-right dt-body-right' },
                { data: 'solde', className: 'dt-head-right dt-body-right' },
                { data: 'operations' }
            ],
            'columnDefs': [ {
                'targets': [5,6], /* column index */
                'orderable': false, /* true or false */
             }]
        });
    });

});