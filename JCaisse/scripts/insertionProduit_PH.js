$(document).ready(function() {

    $('#listeProduits').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/insertionProduit_listeProduits_PH.php'
        },
        'dom': 'Blfrtip',
        "buttons": ['csv','print', 'excel', 'pdf'],
        "ordering": true,
        "order": [[0, 'desc']],
        'columns': [
          { data: 'idDesignation' },
          { data: 'designation' },
          { data: 'codeBarreDesignation' },
          { data: 'categorie' },
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

    $("#listeProduitsEvent").on( "click", function (e){
        e.preventDefault();
        $('#listeProduits').DataTable({
            'processing': true,
            'serverSide': true,
            'destroy': true,
            'serverMethod': 'post',
            'ajax': {
                'url':'datatables/insertionProduit_listeProduits_PH.php'
            },
            'dom': 'Blfrtip',
            "buttons": ['csv','print', 'excel', 'pdf'],
            "ordering": true,
            "order": [[0, 'desc']],
            'columns': [
              { data: 'idDesignation' },
              { data: 'designation' },
              { data: 'codeBarreDesignation' },
              { data: 'categorie' },
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

    $("#listeArchivesEvent").on( "click", function (e){
        e.preventDefault();
        $('#listeArchives').DataTable({
            'processing': true,
            'serverSide': true,
            'destroy': true,
            'serverMethod': 'post',
            'ajax': {
                'url':'datatables/insertionProduit_listeArchives_PH.php'
            },
            'dom': 'Blfrtip',
            "buttons": ['csv','print', 'excel', 'pdf'],
            "ordering": true,
            "order": [[0, 'desc']],
            'columns': [
                { data: 'idDesignation' },
                { data: 'designation' },
                { data: 'codeBarreDesignation' },
                { data: 'categorie' },
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

    $("#listeDoublonEvent").on( "click", function (e){
        e.preventDefault();
        $('#listeDoublonDesignation').DataTable({
          'processing': true,
          'serverSide': true,
          'destroy': true,
          'serverMethod': 'post',
          'ajax': {
              'url':'datatables/insertionProduit_listeDoublonDesignation.php'
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
              'url':'datatables/insertionProduit_listeDoublonDesignation.php'
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
                'url':'datatables/insertionProduit_listeDoublonCodeBarre.php'
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

    $("#inpt_ajt_Produit_Reference").on("keyup", function(e) {
        e.preventDefault();
        query = $('#inpt_ajt_Produit_Reference').val();
        if (query.length > 0) { 
            
            $(this).typeahead({
                source: function(query, result) {
                    $.ajax({
                        url:"calculs/insertionProduit_PH.php",
                        method: "POST",
                        data: {
                            operation:'choix_Reference',
                            query: query
                        },
                        dataType: "json",
                        success: function(data) {
                            result($.map(data, function(item) {
                                return item;
                            }))

                        },
                        error: function(err) {
                            $("#message_Produit_titre").text('Erreur');
                            $("#message_Produit .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
                            $('#message_Produit').modal('show');
                        }
                    });
                },
                highlighter: function (item) {
                        item = item.split('<>');
                        if(item[0]==0){
                            return '<span style="color:green">' + item[1] + '</span>';
                        }
                        else{
                            return '<span style="color:red">' + item[1] + '</span>';
                        }
                  },
            });

            $(this).focus();

            var reference=$('#form_ajouter_Produit .typeahead li.active').text();
            var keycode = (e.keyCode ? e.keyCode : e.which);
            if (keycode == '13') {
                $('#inpt_ajt_Produit_Reference').val('');
                $('#slct_ajt_Produit_Categorie option').remove();
                $('#slct_ajt_Produit_Forme option').remove();
                $('#slct_ajt_Produit_Tableau option').remove();
                $('#inpt_ajt_Produit_PrixPublic').val(0);
                $('#inpt_ajt_Produit_PrixSession').val(0);
                $('#inpt_ajt_Produit_CodeBarre').val('');

                $('#inpt_ajt_Produit_Reference').val(reference);
                $.ajax({
                    url:"calculs/insertionProduit_PH.php",
                    method:"POST",
                    data:{
                        operation:'details_Reference',
                        reference : reference
                    },
                    success: function (data) {
                        var details = JSON.parse(data);
                        var taille = details.length;
        
                        if(taille > 3) {
                            $('#inpt_ajt_Produit_Reference').val(details[1]);
                            $('#slct_ajt_Produit_Tableau').append("<option selected value='"+details[4]+"'>"+details[4]+"</option>");
                            $('#slct_ajt_Produit_Tableau').append("<option  value='Sans'>Sans</option>");
                            $('#slct_ajt_Produit_Tableau').append("<option  value='A'>A</option>");
                            $('#slct_ajt_Produit_Tableau').append("<option  value='C'>C</option>");
                            $('#inpt_ajt_Produit_PrixPublic').val(details[5]);
                            $('#inpt_ajt_Produit_PrixSession').val(details[6]);
                            $('#inpt_ajt_Produit_CodeBarre').val(details[7]);
            
                            $.ajax({
                              url:"calculs/insertionProduit_PH.php",
                              method:"POST",
                              data:{
                                  operation:'choix_Categorie',
                                  categorie : details[2],
                              },
                              success: function (data) {
                
                                  var choix = JSON.parse(data);
                                  var taille = choix.length;
                
                                  $('#slct_ajt_Produit_Categorie').append("<option selected value='"+details[2]+"'>"+details[2]+"</option>");
                
                                  for(var i = 0; i<taille; i++){
                                          $('#slct_ajt_Produit_Categorie').append("<option value='"+choix[i][1]+"'>"+choix[i][1]+"</option>");
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
                                url:"calculs/insertionProduit_PH.php",
                                method:"POST",
                                data:{
                                    operation:'choix_Forme',
                                    forme : details[3],
                                },
                                success: function (data) {
                  
                                    var choix = JSON.parse(data);
                                    var taille = choix.length;
                  
                                    $('#slct_ajt_Produit_Forme').append("<option selected value='"+details[3]+"'>"+details[3]+"</option>");
                  
                                    for(var i = 0; i<taille; i++){
                                            $('#slct_ajt_Produit_Forme').append("<option value='"+choix[i][1]+"'>"+choix[i][1]+"</option>");
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
                        else {

                            if(taille == 3){
                                $('#ajouter_Produit').modal('hide');
                                $("#message_Produit_titre").text('Message');
                                $("#message_Produit .modal-body").text('');
                                $('#inpt_ajt_Produit_Reference').val(details[1]);
                                $('#inpt_ajt_Produit_CodeBarre').val(details[2]);
                                message='<h4>Ce produit existe deja dans le systeme</h4></br>'+
                                '<div class="form-group">'+
                                    '<label>Reference : </label>'+
                                    '<span> '+details[1]+' </span>'+
                                '</div>'+
                                '<div class="form-group">'+
                                    '<label>Code Barre : </label>'+
                                    '<span> '+details[2]+' </span>'+
                                '</div>';
                                $("#message_Produit .modal-body").prepend(message);
                                $('#message_Produit').modal('show');
                            }
                            else {
                                $('#inpt_ajt_Produit_Reference').val(reference);
                            }

                            var categorie='';
                            var forme='';
                  
                            $('#slct_ajt_Produit_Tableau').append("<option selected >--------------</option>");
                            $('#slct_ajt_Produit_Tableau').append("<option  value='Sans'>Sans</option>");
                            $('#slct_ajt_Produit_Tableau').append("<option  value='A'>A</option>");
                            $('#slct_ajt_Produit_Tableau').append("<option  value='C'>C</option>");
                  
                            $.ajax({
                                url:"calculs/insertionProduit_PH.php",
                                method:"POST",
                                data:{
                                    operation:'choix_Categorie',
                                    categorie : categorie,
                                },
                                success: function (data) {
                  
                                    var choix = JSON.parse(data);
                                    var taille = choix.length;
                  
                                    $('#slct_ajt_Produit_Categorie').append("<option selected >--------------</option>");
                  
                                    for(var i = 0; i<taille; i++){
                                            $('#slct_ajt_Produit_Categorie').append("<option value='"+choix[i][1]+"'>"+choix[i][1]+"</option>");
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
                                url:"calculs/insertionProduit_PH.php",
                                method:"POST",
                                data:{
                                    operation:'choix_Forme',
                                    forme : forme,
                                },
                                success: function (data) {
                  
                                    var choix = JSON.parse(data);
                                    var taille = choix.length;
                  
                                    $('#slct_ajt_Produit_Forme').append("<option selected >--------------</option>");
                  
                                    for(var i = 0; i<taille; i++){
                                            $('#slct_ajt_Produit_Forme').append("<option value='"+choix[i][1]+"'>"+choix[i][1]+"</option>");
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
                    },
                    error: function() {
                        $("#message_Produit_titre").text('Erreur');
                        $("#message_Produit .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
                        $('#message_Produit').modal('show');
                    },
                    dataType:"text"
                }); 

            }

        }
                
    });

    $("#form_ajouter_Produit").on( "click", ".typeahead li a[class='dropdown-item']", function (e){
        var reference=$('#form_ajouter_Produit .typeahead li.active').text();
        e.preventDefault();
        $('#inpt_ajt_Produit_Reference').val('');
        $('#slct_ajt_Produit_Categorie option').remove();
        $('#slct_ajt_Produit_Forme option').remove();
        $('#slct_ajt_Produit_Tableau option').remove();
        $('#inpt_ajt_Produit_PrixPublic').val(0);
        $('#inpt_ajt_Produit_PrixSession').val(0);
        $('#inpt_ajt_Produit_CodeBarre').val('');

        $('#inpt_ajt_Produit_Reference').val(reference);
        $.ajax({
            url:"calculs/insertionProduit_PH.php",
            method:"POST",
            data:{
                operation:'details_Reference',
                reference : reference
            },
            success: function (data) {
                var details = JSON.parse(data);
                var taille = details.length;

                if(taille > 3) {
                    $('#inpt_ajt_Produit_Reference').val(details[1]);
                    $('#slct_ajt_Produit_Tableau').append("<option selected value='"+details[4]+"'>"+details[4]+"</option>");
                    $('#slct_ajt_Produit_Tableau').append("<option  value='Sans'>Sans</option>");
                    $('#slct_ajt_Produit_Tableau').append("<option  value='A'>A</option>");
                    $('#slct_ajt_Produit_Tableau').append("<option  value='C'>C</option>");
                    $('#inpt_ajt_Produit_PrixPublic').val(details[5]);
                    $('#inpt_ajt_Produit_PrixSession').val(details[6]);
                    $('#inpt_ajt_Produit_CodeBarre').val(details[7]);
    
                    $.ajax({
                    url:"calculs/insertionProduit_PH.php",
                    method:"POST",
                    data:{
                        operation:'choix_Categorie',
                        categorie : details[2],
                    },
                    success: function (data) {
        
                        var choix = JSON.parse(data);
                        var taille = choix.length;
        
                        $('#slct_ajt_Produit_Categorie').append("<option selected value='"+details[2]+"'>"+details[2]+"</option>");
        
                        for(var i = 0; i<taille; i++){
                                $('#slct_ajt_Produit_Categorie').append("<option value='"+choix[i][1]+"'>"+choix[i][1]+"</option>");
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
                        url:"calculs/insertionProduit_PH.php",
                        method:"POST",
                        data:{
                            operation:'choix_Forme',
                            forme : details[3],
                        },
                        success: function (data) {
        
                            var choix = JSON.parse(data);
                            var taille = choix.length;
        
                            $('#slct_ajt_Produit_Forme').append("<option selected value='"+details[3]+"'>"+details[3]+"</option>");
        
                            for(var i = 0; i<taille; i++){
                                    $('#slct_ajt_Produit_Forme').append("<option value='"+choix[i][1]+"'>"+choix[i][1]+"</option>");
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
                else {

                    if(taille == 3){
                        $('#ajouter_Produit').modal('hide');
                        $("#message_Produit_titre").text('Message');
                        $("#message_Produit .modal-body").text('');
                        $('#inpt_ajt_Produit_Reference').val(details[1]);
                        $('#inpt_ajt_Produit_CodeBarre').val(details[2]);
                        message='<h4>Ce produit existe deja dans le systeme</h4></br>'+
                        '<div class="form-group">'+
                            '<label>Reference : </label>'+
                            '<span> '+details[1]+' </span>'+
                        '</div>'+
                        '<div class="form-group">'+
                            '<label>Code Barre : </label>'+
                            '<span> '+details[2]+' </span>'+
                        '</div>';
                        $("#message_Produit .modal-body").prepend(message);
                        $('#message_Produit').modal('show');
                    }
                    else {
                        $('#inpt_ajt_Produit_Reference').val(reference);
                    }

                    var categorie='';
                    var forme='';
        
                    $('#slct_ajt_Produit_Tableau').append("<option selected >--------------</option>");
                    $('#slct_ajt_Produit_Tableau').append("<option  value='Sans'>Sans</option>");
                    $('#slct_ajt_Produit_Tableau').append("<option  value='A'>A</option>");
                    $('#slct_ajt_Produit_Tableau').append("<option  value='C'>C</option>");
        
                    $.ajax({
                        url:"calculs/insertionProduit_PH.php",
                        method:"POST",
                        data:{
                            operation:'choix_Categorie',
                            categorie : categorie,
                        },
                        success: function (data) {
        
                            var choix = JSON.parse(data);
                            var taille = choix.length;
        
                            $('#slct_ajt_Produit_Categorie').append("<option selected >--------------</option>");
        
                            for(var i = 0; i<taille; i++){
                                    $('#slct_ajt_Produit_Categorie').append("<option value='"+choix[i][1]+"'>"+choix[i][1]+"</option>");
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
                        url:"calculs/insertionProduit_PH.php",
                        method:"POST",
                        data:{
                            operation:'choix_Forme',
                            forme : forme,
                        },
                        success: function (data) {
        
                            var choix = JSON.parse(data);
                            var taille = choix.length;
        
                            $('#slct_ajt_Produit_Forme').append("<option selected >--------------</option>");
        
                            for(var i = 0; i<taille; i++){
                                    $('#slct_ajt_Produit_Forme').append("<option value='"+choix[i][1]+"'>"+choix[i][1]+"</option>");
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
            },
            error: function() {
                $("#message_Produit_titre").text('Erreur');
                $("#message_Produit .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
                $('#message_Produit').modal('show');
            },
            dataType:"text"
        }); 

    });

    $("#inpt_ajt_Produit_CodeBarre").on("keyup", function(e) {
        e.preventDefault();
        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == '13') {
            codeBarre = $('#inpt_ajt_Produit_CodeBarre').val();

            $('#inpt_ajt_Produit_Reference').val('');
            $('#slct_ajt_Produit_Categorie option').remove();
            $('#slct_ajt_Produit_Forme option').remove();
            $('#slct_ajt_Produit_Tableau option').remove();
            $('#inpt_ajt_Produit_PrixPublic').val(0);
            $('#inpt_ajt_Produit_PrixSession').val(0);
            $('#inpt_ajt_Produit_CodeBarre').val('');

            $.ajax({
                url:"calculs/insertionProduit_PH.php",
                method:"POST",
                data:{
                    operation:'details_Reference',
                    codeBarre : codeBarre
                },
                success: function (data) {
                    var details = JSON.parse(data);
                    var taille = details.length;
    
                    if(taille > 3) {
                        $('#inpt_ajt_Produit_Reference').val(details[1]);
                        $('#slct_ajt_Produit_Tableau').append("<option selected value='"+details[4]+"'>"+details[4]+"</option>");
                        $('#slct_ajt_Produit_Tableau').append("<option  value='Sans'>Sans</option>");
                        $('#slct_ajt_Produit_Tableau').append("<option  value='A'>A</option>");
                        $('#slct_ajt_Produit_Tableau').append("<option  value='C'>C</option>");
                        $('#inpt_ajt_Produit_PrixPublic').val(details[5]);
                        $('#inpt_ajt_Produit_PrixSession').val(details[6]);
                        $('#inpt_ajt_Produit_CodeBarre').val(details[7]);
        
                        $.ajax({
                        url:"calculs/insertionProduit_PH.php",
                        method:"POST",
                        data:{
                            operation:'choix_Categorie',
                            categorie : details[2],
                        },
                        success: function (data) {
            
                            var choix = JSON.parse(data);
                            var taille = choix.length;
            
                            $('#slct_ajt_Produit_Categorie').append("<option selected value='"+details[2]+"'>"+details[2]+"</option>");
            
                            for(var i = 0; i<taille; i++){
                                    $('#slct_ajt_Produit_Categorie').append("<option value='"+choix[i][1]+"'>"+choix[i][1]+"</option>");
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
                            url:"calculs/insertionProduit_PH.php",
                            method:"POST",
                            data:{
                                operation:'choix_Forme',
                                forme : details[3],
                            },
                            success: function (data) {
            
                                var choix = JSON.parse(data);
                                var taille = choix.length;
            
                                $('#slct_ajt_Produit_Forme').append("<option selected value='"+details[3]+"'>"+details[3]+"</option>");
            
                                for(var i = 0; i<taille; i++){
                                        $('#slct_ajt_Produit_Forme').append("<option value='"+choix[i][1]+"'>"+choix[i][1]+"</option>");
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
                    else {
    
                        if(taille == 3){
                            $('#ajouter_Produit').modal('hide');
                            $("#message_Produit_titre").text('Message');
                            $("#message_Produit .modal-body").text('');
                            $('#inpt_ajt_Produit_Reference').val(details[1]);
                            $('#inpt_ajt_Produit_CodeBarre').val(details[2]);
                            message='<h4>Ce produit existe deja dans le systeme</h4></br>'+
                            '<div class="form-group">'+
                                '<label>Reference : </label>'+
                                '<span> '+details[1]+' </span>'+
                            '</div>'+
                            '<div class="form-group">'+
                                '<label>Code Barre : </label>'+
                                '<span> '+details[2]+' </span>'+
                            '</div>';
                            $("#message_Produit .modal-body").prepend(message);
                            $('#message_Produit').modal('show');
                        }
                        else {
                                $('#inpt_ajt_Produit_CodeBarre').val(codeBarre);
                        }
    
                        var categorie='';
                        var forme='';
            
                        $('#slct_ajt_Produit_Tableau').append("<option selected >--------------</option>");
                        $('#slct_ajt_Produit_Tableau').append("<option  value='Sans'>Sans</option>");
                        $('#slct_ajt_Produit_Tableau').append("<option  value='A'>A</option>");
                        $('#slct_ajt_Produit_Tableau').append("<option  value='C'>C</option>");
            
                        $.ajax({
                            url:"calculs/insertionProduit_PH.php",
                            method:"POST",
                            data:{
                                operation:'choix_Categorie',
                                categorie : categorie,
                            },
                            success: function (data) {
            
                                var choix = JSON.parse(data);
                                var taille = choix.length;
            
                                $('#slct_ajt_Produit_Categorie').append("<option selected >--------------</option>");
            
                                for(var i = 0; i<taille; i++){
                                        $('#slct_ajt_Produit_Categorie').append("<option value='"+choix[i][1]+"'>"+choix[i][1]+"</option>");
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
                            url:"calculs/insertionProduit_PH.php",
                            method:"POST",
                            data:{
                                operation:'choix_Forme',
                                forme : forme,
                            },
                            success: function (data) {
            
                                var choix = JSON.parse(data);
                                var taille = choix.length;
            
                                $('#slct_ajt_Produit_Forme').append("<option selected >--------------</option>");
            
                                for(var i = 0; i<taille; i++){
                                        $('#slct_ajt_Produit_Forme').append("<option value='"+choix[i][1]+"'>"+choix[i][1]+"</option>");
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


  /**Debut Ajouter Produit**/
    $(function(){
      ajouter_Produit= function ()  {
          var categorie='';
          var forme='';
          $('#inpt_ajt_Produit_Reference').val('');
          $('#slct_ajt_Produit_Categorie option').remove();
          $('#slct_ajt_Produit_Forme option').remove();
          $('#slct_ajt_Produit_Tableau option').remove();
          $('#inpt_ajt_Produit_PrixPublic').val(0);
          $('#inpt_ajt_Produit_PrixSession').val(0);
          $('#inpt_ajt_Produit_CodeBarre').val('');

          $('#slct_ajt_Produit_Tableau').append("<option selected >--------------</option>");
          $('#slct_ajt_Produit_Tableau').append("<option  value='Sans'>Sans</option>");
          $('#slct_ajt_Produit_Tableau').append("<option  value='A'>A</option>");
          $('#slct_ajt_Produit_Tableau').append("<option  value='C'>C</option>");

          $.ajax({
              url:"calculs/insertionProduit_PH.php",
              method:"POST",
              data:{
                  operation:'choix_Categorie',
                  categorie : categorie,
              },
              success: function (data) {

                  var choix = JSON.parse(data);
                  var taille = choix.length;

                  $('#slct_ajt_Produit_Categorie').append("<option selected >--------------</option>");

                  for(var i = 0; i<taille; i++){
                          $('#slct_ajt_Produit_Categorie').append("<option value='"+choix[i][1]+"'>"+choix[i][1]+"</option>");
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
              url:"calculs/insertionProduit_PH.php",
              method:"POST",
              data:{
                  operation:'choix_Forme',
                  forme : forme,
              },
              success: function (data) {

                  var choix = JSON.parse(data);
                  var taille = choix.length;

                  $('#slct_ajt_Produit_Forme').append("<option selected >--------------</option>");

                  for(var i = 0; i<taille; i++){
                          $('#slct_ajt_Produit_Forme').append("<option value='"+choix[i][1]+"'>"+choix[i][1]+"</option>");
                  }

              },
              error: function() {
                $("#message_Produit_titre").text('Erreur');
                $("#message_Produit .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
                $('#message_Produit').modal('show');
              },
              dataType:"text"
          }); 

          $('#ajouter_Produit').modal('show');
      }
    });
    $("#btn_ajouter_Produit").click( function(){
      reference=$('#inpt_ajt_Produit_Reference').val();
      categorie=$('#slct_ajt_Produit_Categorie').val();
      forme=$('#slct_ajt_Produit_Forme').val();
      tableau=$('#slct_ajt_Produit_Tableau').val();
      prixPublic=$('#inpt_ajt_Produit_PrixPublic').val();
      prixSession=$('#inpt_ajt_Produit_PrixSession').val();
      codeBarre=$('#inpt_ajt_Produit_CodeBarre').val();
      $.ajax({
          url:"calculs/insertionProduit_PH.php",
          method:"POST",
          data:{
              operation:'ajouter_Produit',
              reference : reference,
              categorie : categorie,
              forme : forme,
              tableau : tableau,
              prixPublic : prixPublic,
              prixSession : prixSession,
              codeBarre : codeBarre
          },
          success: function (data) {
              var details = JSON.parse(data);
              var ligne = "<tr class='produit"+details[0]+"'>"+
              "<td>"+details[1]+"</td>"+
              "<td>"+details[7]+"</td>"+
              "<td>"+details[2]+"</td>"+
              "<td>"+details[3]+"</td>"+
              "<td>"+details[4]+"</td>"+
              "<td>"+details[5]+"</td>"+
              "<td>"+details[6]+"</td>"+
              "<td>"+
                "<a><img src='images/edit.png' align='middle' alt='modifier' onclick='modifier_Produit("+details[0]+")' data-toggle='modal' /></a>&nbsp"+
                "<a><img src='images/drop.png' align='middle' alt='supprimer' onclick='supprimer_Produit("+details[0]+")' data-toggle='modal' /></a>"+
              "</td>"+
              "</tr>";
              $("#listeProduits").prepend(ligne);
              $('#ajouter_Produit').modal('hide');

              $('#inpt_ajt_Produit_Stock_idProduit').val(details[0]);
              $('#inpt_ajt_Produit_Stock_Reference').val(details[1]);
              $('#inpt_ajt_Produit_Stock_Forme').val(details[3]);
              $('#inpt_ajt_Produit_Stock_Quantite').val(1);
              $('#inpt_ajt_Produit_Stock_DateExpiration').val('');
              $('#ajouter_Produit_Stock').modal('show');

          },
          error: function() {
            $("#message_Produit_titre").text('Erreur');
            $("#message_Produit .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
            $('#message_Produit').modal('show');
          },
          dataType:"text"
      });
    });
  /**Fin Ajouter Produit**/

  /**Debut Ajouter Stock Produit**/
    $("#btn_ajouter_Produit_Stock_Ajouter").click( function(){
        idProduit=$('#inpt_ajt_Produit_Stock_idProduit').val();
        quantite=$('#inpt_ajt_Produit_Stock_Quantite').val();
        dateExpiration=$('#inpt_ajt_Produit_Stock_DateExpiration').val();

        $('#btn_ajouter_Produit_Stock_Ajouter').prop("disabled", true );

        $.ajax({
            url:"calculs/insertionProduit_PH.php",
            method:"POST",
            data:{
                operation:'ajouter_Stock',
                idProduit : idProduit,
                quantite : quantite,
                dateExpiration : dateExpiration
            },
            success: function (data) {

                $('#inpt_ajt_Produit_Stock_Quantite').val('');
                $('#inpt_ajt_Produit_Stock_DateExpiration').val('');

                $('#btn_ajouter_Produit_Stock_Ajouter').prop("disabled", false );

            },
            error: function() {
                $("#message_Produit_titre").text('Erreur');
                $("#message_Produit .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
                $('#message_Produit').modal('show');
            },
            dataType:"text"
        }); 
    });
    $("#btn_ajouter_Produit_Stock_Terminer").click( function(){
        idProduit=$('#inpt_ajt_Produit_Stock_idProduit').val();
        quantite=$('#inpt_ajt_Produit_Stock_Quantite').val();
        dateExpiration=$('#inpt_ajt_Produit_Stock_DateExpiration').val();

        $('#btn_ajouter_Produit_Stock_Terminer').prop("disabled", true );

        $.ajax({
            url:"calculs/insertionProduit_PH.php",
            method:"POST",
            data:{
                operation:'ajouter_Stock',
                idProduit : idProduit,
                quantite : quantite,
                dateExpiration : dateExpiration
            },
            success: function (data) {
                $('#inpt_ajt_Produit_Stock_Quantite').val('');
                $('#inpt_ajt_Produit_Stock_DateExpiration').val('');
                $('#btn_ajouter_Produit_Stock_Terminer').prop("disabled", false );

                $('#ajouter_Produit_Stock').modal('hide');

            },
            error: function() {
                $("#message_Produit_titre").text('Erreur');
                $("#message_Produit .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
                $('#message_Produit').modal('show');
            },
            dataType:"text"
        }); 
    });
  /**Fin Ajouter Stock Produit**/

  /**Debut Modifier Produit**/
    $(function(){
      modifier_Produit= function (idProduit)  {
          $('#inpt_mdf_Produit_idProduit').val('');
          $('#inpt_mdf_Produit_Reference').val('');
          $('#slct_mdf_Produit_Categorie option').remove();
          $('#slct_mdf_Produit_Forme option').remove();
          $('#slct_mdf_Produit_Tableau option').remove();
          $('#inpt_mdf_Produit_PrixPublic').val('');
          $('#inpt_mdf_Produit_PrixSession').val('');
          $('#inpt_mdf_Produit_CodeBarre').val('');
          $.ajax({
              url:"calculs/insertionProduit_PH.php",
              method:"POST",
              data:{
                  operation:'details_Produit',
                  idProduit : idProduit,
              },
              success: function (data) {
                  var details = JSON.parse(data);
                  $('#inpt_mdf_Produit_idProduit').val(details[0]);
                  $('#inpt_mdf_Produit_Reference').val(details[1]);
                  $('#slct_mdf_Produit_Tableau').append("<option selected value='"+details[4]+"'>"+details[4]+"</option>");
                  $('#slct_mdf_Produit_Tableau').append("<option  value='Sans'>Sans</option>");
                  $('#slct_mdf_Produit_Tableau').append("<option  value='A'>A</option>");
                  $('#slct_mdf_Produit_Tableau').append("<option  value='C'>C</option>");
                  $('#inpt_mdf_Produit_PrixPublic').val(details[5]);
                  $('#inpt_mdf_Produit_PrixSession').val(details[6]);
                  $('#inpt_mdf_Produit_CodeBarre').val(details[7]);

                  $.ajax({
                    url:"calculs/insertionProduit_PH.php",
                    method:"POST",
                    data:{
                        operation:'choix_Categorie',
                        categorie : details[2],
                    },
                    success: function (data) {
      
                        var choix = JSON.parse(data);
                        var taille = choix.length;
      
                        $('#slct_mdf_Produit_Categorie').append("<option selected value='"+details[2]+"'>"+details[2]+"</option>");
      
                        for(var i = 0; i<taille; i++){
                                $('#slct_mdf_Produit_Categorie').append("<option value='"+choix[i][1]+"'>"+choix[i][1]+"</option>");
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
                    url:"calculs/insertionProduit_PH.php",
                    method:"POST",
                    data:{
                        operation:'choix_Forme',
                        forme : details[3],
                    },
                    success: function (data) {
      
                        var choix = JSON.parse(data);
                        var taille = choix.length;
      
                        $('#slct_mdf_Produit_Forme').append("<option selected value='"+details[3]+"'>"+details[3]+"</option>");
      
                        for(var i = 0; i<taille; i++){
                                $('#slct_mdf_Produit_Forme').append("<option value='"+choix[i][1]+"'>"+choix[i][1]+"</option>");
                        }
      
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                }); 

                  $('#modifier_Produit').modal('show');
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
    $("#btn_modifier_Produit").click( function(){
      idProduit=$('#inpt_mdf_Produit_idProduit').val();
      reference=$('#inpt_mdf_Produit_Reference').val();
      categorie=$('#slct_mdf_Produit_Categorie').val();
      forme=$('#slct_mdf_Produit_Forme').val();
      tableau=$('#slct_mdf_Produit_Tableau').val();
      prixPublic=$('#inpt_mdf_Produit_PrixPublic').val();
      prixSession=$('#inpt_mdf_Produit_PrixSession').val();
      codeBarre=$('#inpt_mdf_Produit_CodeBarre').val();
        $.ajax({
          url:"calculs/insertionProduit_PH.php",
          method:"POST",
          data:{
              operation:'modifier_Produit',
              idProduit : idProduit,
              reference : reference,
              categorie : categorie,
              forme: forme,    
              tableau : tableau,
              prixPublic: prixPublic,
              prixSession : prixSession,
              codeBarre : codeBarre,
          },
          success: function (data) {
              var details = JSON.parse(data);
              $('#listeProduits tbody .produit'+details[0]).each(function() {
                    $(this).find("td:eq(0)").html(details[1]);
                    $(this).find("td:eq(1)").html(details[7]);
                    $(this).find("td:eq(2)").html(details[2]);
                    $(this).find("td:eq(3)").html(details[3]);
                    $(this).find("td:eq(4)").html(details[4]);
                    $(this).find("td:eq(5)").html(details[5]);
                    $(this).find("td:eq(6)").html(details[6]);
                    $(this).animate({opacity: 0.65 }, "slow");
                    $('#modifier_Produit').modal('hide');
              });
              
          },
          error: function() {
            $("#message_Produit_titre").text('Erreur');
            $("#message_Produit .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
            $('#message_Produit').modal('show');
         },
          dataType:"text"
      }); 
    });
  /**Fin Modifier Produit**/

  /**Debut Supprimer Produit**/
    $(function(){
      supprimer_Produit= function (idProduit)  {
          $('#inpt_spm_Produit_idProduit').val('');
          $('#span_spm_Produit_Reference').text('');
          $('#span_spm_Produit_Categorie').text('');
          $('#span_spm_Produit_Forme').text('');
          $('#span_spm_produit_Tableau').text('');
          $('#span_spm_Produit_PrixPublic').text('');
          $('#span_spm_Produit_PrixSession').text('');
          $('#span_spm_Produit_CodeBarre').text('');
          $.ajax({
              url:"calculs/insertionProduit_PH.php",
              method:"POST",
              data:{
                  operation:'details_Produit',
                  idProduit : idProduit,
              },
              success: function (data) {
                  var details = JSON.parse(data);
                $('#inpt_spm_Produit_idProduit').val(details[0]);
                $('#span_spm_Produit_Reference').text(details[1]);
                $('#span_spm_Produit_Categorie').text(details[2]);
                $('#span_spm_Produit_Forme').text(details[3]);
                $('#span_spm_produit_Tableau').text(details[4]);
                $('#span_spm_Produit_PrixPublic').text(details[5]);
                $('#span_spm_Produit_PrixSession').text(details[6]);
                $('#span_spm_Produit_CodeBarre').text(details[7]);

                $('#supprimer_Produit').modal('show');
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
    $("#btn_supprimer_Produit").click( function(){
      idProduit=$('#inpt_spm_Produit_idProduit').val();
      $.ajax({
          url:"calculs/insertionProduit_PH.php",
          method:"POST",
          data:{
              operation:'supprimer_Produit',
              idProduit : idProduit
          },
          success: function (data) {
            $('#listeProduits tbody .produit'+idProduit).each(function() {
                  $(this).animate({ opacity: 1/2 }, 1000);
                  $(this).hide(3000, function () {
                      $(this).remove();
                  });
            });
            $('#supprimer_Produit').modal('hide');
          },
          error: function() {
            $("#message_Produit_titre").text('Erreur');
            $("#message_Produit .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
            $('#message_Produit').modal('show');
          },
          dataType:"text"
      }); 
    });
  /**Fin Supprimer Produit**/

  /**Debut Desarchiver Produit**/
    $(function(){
        action_Produit= function (idProduit)  {
            $('#inpt_act_Produit_idProduit').val('');
            $('#span_act_Produit_Reference').text('');
            $('#span_act_Produit_Categorie').text('');
            $('#span_act_Produit_Forme').text('');
            $('#span_act_produit_Tableau').text('');
            $('#span_act_Produit_PrixPublic').text('');
            $('#span_act_Produit_PrixSession').text('');
            $('#span_act_Produit_CodeBarre').text('');
            $('.div_unite_Stock').hide();
            $.ajax({
                url:"calculs/insertionProduit_PH.php",
                method:"POST",
                data:{
                    operation:'details_Produit',
                    idProduit : idProduit,
                },
                success: function (data) {
                    var details = JSON.parse(data);
                    $('#inpt_act_Produit_idProduit').val(details[0]);
                    $('#span_act_Produit_Reference').text(details[1]);
                    $('#span_act_Produit_Categorie').text(details[2]);
                    $('#span_act_Produit_Forme').text(details[3]);
                    $('#span_act_produit_Tableau').text(details[4]);
                    $('#span_act_Produit_PrixPublic').text(details[5]);
                    $('#span_act_Produit_PrixSession').text(details[6]);
                    $('#span_act_Produit_CodeBarre').text(details[7]);

                    $('#action_Produit').modal('show');
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
    $("#btn_desarchiver_Produit").click( function(){
        idProduit=$('#inpt_act_Produit_idProduit').val();
        $.ajax({
            url:"calculs/insertionProduit_PH.php",
            method:"POST",
            data:{
                operation:'desarchiver_Produit',
                idProduit : idProduit
            },
            success: function (data) {
            $('#listeArchives tbody .produit'+idProduit).each(function() {
                    $(this).animate({ opacity: 1/2 }, 1000);
                    $(this).hide(3000, function () {
                        $(this).remove();
                    });
                });
                $('#action_Produit').modal('hide');
            },
            error: function() {
                $("#message_Produit_titre").text('Erreur');
                $("#message_Produit .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
                $('#message_Produit').modal('show');
            },
            dataType:"text"
        }); 
    });
  /**Fin Desarchiver Produit**/

    $("#listeFusionEvent").on( "click", function (e){
    e.preventDefault();
    $('#listeFusion').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/fusionProduit_listeFusions.php'
        },
        "ordering": true,
        "order": [[0, 'desc'],[1, 'desc']],
        'columns': [
            { data: 'idDesignation' },
            { data: 'designation' },
            { data: 'codeBarreDesignation' },
            { data: 'quantite' },
            { data: 'forme' },
            { data: 'tableau' },
            { data: 'prixSession' },
            { data: 'prixPublic' },
            { data: 'stock' },
            { data: 'operations' }
        ],
        "fnCreatedRow": function( nRow, data, iDataIndex ) {
        $(nRow).attr('class', "fusion"+data['idDesignation']);
        },
        'columnDefs': [ 
        { "bVisible": false, "aTargets": [ 0 ] },
        {
            'targets': [3], /* column index */
            'orderable': false, /* true or false */
        }
        ]
    }); 
    });  

    $("#btn_lister_Produit").on( "click", function (e){
        e.preventDefault();
        $('#listeReference').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/fusionProduit_listeProduits.php'
        },
        "ordering": true,
        "order": [[0, 'desc']],
        'columns': [
            { data: 'idDesignation' },
            { data: 'designation' },
            { data: 'codeBarreDesignation' },
            { data: 'quantite' },
            { data: 'forme' },
            { data: 'tableau' },
            { data: 'prixSession' },
            { data: 'prixPublic' },
            { data: 'operations' }
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
            ajouter_Fusion = function (idProduit)  {
                $.ajax({
                    url:"calculs/fusionProduit.php",
                    method:"POST",
                    data:{
                        operation:'ajouter_Produit',
                        idProduit : idProduit
                    },
                    success: function (data) {
                    var details = JSON.parse(data);
                    if(details['type']==0){
                        $('#listeFusion').DataTable({
                        'processing': true,
                        'serverSide': true,
                        'destroy': true,
                        'serverMethod': 'post',
                        'ajax': {
                            'url':'datatables/fusionProduit_listeFusions.php'
                        },
                        "ordering": true,
                        "order": [[0, 'desc'],[1, 'desc']],
                        'columns': [
                            { data: 'idDesignation' },
                            { data: 'designation' },
                            { data: 'codeBarreDesignation' },
                            { data: 'quantite' },
                            { data: 'uniteStock' },
                            { data: 'nbreArticleUniteStock' },
                            { data: 'prixuniteStock' },
                            { data: 'prix' },
                            { data: 'prixachat' },
                            { data: 'stock' },
                            { data: 'operations' }
                        ],
                        "fnCreatedRow": function( nRow, data, iDataIndex ) {
                            $(nRow).attr('class', "fusion"+data['idDesignation']);
                        },
                        'columnDefs': [ 
                            { "bVisible": false, "aTargets": [ 0 ] },
                            {
                            'targets': [3], /* column index */
                            'orderable': false, /* true or false */
                            }
                        ]
                        }); 
                    }
                    else if(details['type']==1){
                        $('#listeFusion_ET').DataTable({
                        'processing': true,
                        'serverSide': true,
                        'destroy': true,
                        'serverMethod': 'post',
                        'ajax': {
                            'url':'datatables/fusionProduit_listeFusions.php'
                        },
                        "ordering": true,
                        "order": [[0, 'desc'],[1, 'desc']],
                        'columns': [
                            { data: 'idDesignation' },
                            { data: 'designation' },
                            { data: 'codeBarreDesignation' },
                            { data: 'surdepot' },
                            { data: 'sansdepot' },
                            { data: 'uniteStock' },
                            { data: 'nbreArticleUniteStock' },
                            { data: 'prixuniteStock' },
                            { data: 'prixachat' },
                            { data: 'stock' },
                            { data: 'operations' }
                        ],
                        "fnCreatedRow": function( nRow, data, iDataIndex ) {
                            $(nRow).attr('class', "fusion"+data['idDesignation']);
                        },
                        'columnDefs': [ 
                            { "bVisible": false, "aTargets": [ 0 ] },
                            {
                            'targets': [3], /* column index */
                            'orderable': false, /* true or false */
                            }
                        ]
                        }); 
                    }
                    else if(details['type']==2){
                        $('#listeFusion').DataTable({
                        'processing': true,
                        'serverSide': true,
                        'destroy': true,
                        'serverMethod': 'post',
                        'ajax': {
                            'url':'datatables/fusionProduit_listeFusions.php'
                        },
                        "ordering": true,
                        "order": [[0, 'desc'],[1, 'desc']],
                        'columns': [
                            { data: 'idDesignation' },
                            { data: 'designation' },
                            { data: 'codeBarreDesignation' },
                            { data: 'quantite' },
                            { data: 'forme' },
                            { data: 'tableau' },
                            { data: 'prixSession' },
                            { data: 'prixPublic' },
                            { data: 'stock' },
                            { data: 'operations' }
                        ],
                        "fnCreatedRow": function( nRow, data, iDataIndex ) {
                            $(nRow).attr('class', "fusion"+data['idDesignation']);
                        },
                        'columnDefs': [ 
                            { "bVisible": false, "aTargets": [ 0 ] },
                            {
                            'targets': [3], /* column index */
                            'orderable': false, /* true or false */
                            }
                        ]
                        }); 
                    }
            
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
            supprimer_Fusion = function (idProduit)  {
                $.ajax({
                    url:"calculs/fusionProduit.php",
                    method:"POST",
                    data:{
                        operation:'supprimer_Produit',
                        idProduit : idProduit
                    },
                    success: function (data) {
                    if(data==0){
                        $('#listeFusion tbody .fusion'+idProduit).each(function() {
                        $(this).animate({ opacity: 1/2 }, 1000);
                        $(this).hide(3000, function () {
                            $(this).remove();
                        });
                        });
                    }
                    else if(data==1){
                        $('#listeFusion_ET tbody .fusion'+idProduit).each(function() {
                        $(this).animate({ opacity: 1/2 }, 1000);
                        $(this).hide(3000, function () {
                            $(this).remove();
                        });
                        });
                    }
                    else if(data==2){
                        $('#listeFusion tbody .fusion'+idProduit).each(function() {
                        $(this).animate({ opacity: 1/2 }, 1000);
                        $(this).hide(3000, function () {
                            $(this).remove();
                        });
                        });
                    }

                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                });

            }
        });
    /**Fin Supprimer Produit**/

    /**Debut Fusion Produit**/
        $(function(){
            fusion_Produit = function (idProduit)  {
            $('.btn_fusion_Produit').prop("disabled", true);
            designation=$('#inpt_Produit_Designation_'+idProduit).val();
            codeBarre=$('#inpt_Produit_CodeBarre_'+idProduit).val();  
            quantite=$('#inpt_Produit_Quantite_'+idProduit).val();
            forme=$('#inpt_Produit_Forme_'+idProduit).val();
            tableau=$('#inpt_Produit_Tableau_'+idProduit).val();
            prixSession=$('#inpt_Produit_PrixSession_'+idProduit).val();
            prixPublic=$('#inpt_Produit_PrixPublic_'+idProduit).val();
            $.ajax({
                url:"calculs/fusionProduit.php",
                method:"POST",
                data:{
                    operation:'fusion_Produit',
                    idProduit : idProduit,
                    designation : designation,
                    codeBarre : codeBarre,
                    quantite : quantite,
                    forme : forme,
                    tableau : tableau,
                    prixSession : prixSession,
                    prixPublic : prixPublic
                },
                success: function (data) {
                    $('#listeFusion').DataTable({
                    'processing': true,
                    'serverSide': true,
                    'destroy': true,
                    'serverMethod': 'post',
                    'ajax': {
                        'url':'datatables/fusionProduit_listeFusions.php'
                    },
                    "ordering": true,
                    "order": [[0, 'desc'],[1, 'desc']],
                    'columns': [
                        { data: 'idDesignation' },
                        { data: 'designation' },
                        { data: 'codeBarreDesignation' },
                        { data: 'quantite' },
                        { data: 'forme' },
                        { data: 'tableau' },
                        { data: 'prixSession' },
                        { data: 'prixPublic' },
                        { data: 'stock' },
                        { data: 'operations' }
                    ],
                    "fnCreatedRow": function( nRow, data, iDataIndex ) {
                        $(nRow).attr('class', "fusion"+data['idDesignation']);
                    },
                    'columnDefs': [ 
                        { "bVisible": false, "aTargets": [ 0 ] },
                        {
                        'targets': [3], /* column index */
                        'orderable': false, /* true or false */
                    }
                    ]
                    }); 
                },
                error: function() {
                    alert("La requête "); },
                dataType:"text"
            });
            }
        });
    /**Fin Fusion Produit**/

    /**Debut Doublon Designation Produit**/
        $(function(){
            doublon_Designation= function (idProduit)  {
              $('#btn_Doublon_Designation-'+idProduit).prop("disabled", true);
              $.ajax({
                url:"calculs/doublonProduit.php",
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
                      "<td>"+details[i][9]+"</td>"+
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
                url:"calculs/doublonProduit.php",
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
                        "<td>"+details[i][9]+"</td>"+
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
            quantite=$('#inpt_Designation_Quantite_'+idProduit).val();
            prixPublic=$('#inpt_Designation_PrixPublic_'+idProduit).val();
            prixSession=$('#inpt_Designation_PrixSession_'+idProduit).val();
            $('.btn_Liste_Fusion').prop("disabled", true);
            $.ajax({
                url:"calculs/doublonProduit.php",
                method:"POST",
                data:{
                    operation:'fusion_Designation',
                    idProduit : idProduit,
                    codeBarre : codeBarre,
                    quantite : quantite,
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
            quantite=$('#inpt_CodeBarre_Quantite_'+idProduit).val();
            prixPublic=$('#inpt_CodeBarre_PrixPublic_'+idProduit).val();
            prixSession=$('#inpt_CodeBarre_PrixSession_'+idProduit).val();
            $('#btn_Valider-'+idProduit).prop("disabled", true); 
            $('.btn_Liste_Fusion').prop("disabled", true);
            $.ajax({
                url:"calculs/doublonProduit.php",
                method:"POST",
                data:{
                    operation:'fusion_CodeBarre',
                    idProduit : idProduit,
                    codeBarre : codeBarre,
                    quantite : quantite,
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
                url:"calculs/doublonProduit.php",
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
                url:"calculs/doublonProduit.php",
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

});