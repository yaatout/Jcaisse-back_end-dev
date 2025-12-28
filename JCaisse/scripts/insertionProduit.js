$(document).ready(function() {

    $('#listeProduits').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/insertionProduit_listeProduits.php'
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
          { data: 'uniteStock' },
          { data: 'nbreArticleUniteStock' },
          { data: 'prixuniteStock' },
          { data: 'prix' },
          { data: 'prixachat' },
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
                'url':'datatables/insertionProduit_listeProduits.php'
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
              { data: 'uniteStock' },
              { data: 'nbreArticleUniteStock' },
              { data: 'prixuniteStock' },
              { data: 'prix' },
              { data: 'prixachat' },
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

    $("#listeCodeBarresEvent").on( "click", function (e){
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
            { data: 'uniteStock' },
            { data: 'prixuniteStock' },
            { data: 'prix' },
            { data: 'prixachat' },
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
            { data: 'uniteStock' },
            { data: 'prixuniteStock' },
            { data: 'prix' },
            { data: 'prixachat' },
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
            { data: 'uniteStock' },
            { data: 'prixuniteStock' },
            { data: 'prix' },
            { data: 'prixachat' },
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
                'url':'datatables/insertionProduit_listeArchives.php'
            },
            'dom': 'Blfrtip',
            "buttons": ['csv','print', 'excel', 'pdf'],
            "ordering": true,
            "order": [[0, 'desc']],
            'columns': [
              { data: 'idDesignation' },
              { data: 'designation' },
              { data: 'codeBarreDesignation' },
              { data: 'uniteStock' },
              { data: 'nbreArticleUniteStock' },
              { data: 'prixuniteStock' },
              { data: 'prix' },
              { data: 'prixachat' },
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
                        url:"calculs/insertionProduit.php",
                        method: "POST",
                        data: {
                            operation:'choix_Reference',
                            query: query
                        },
                        dataType: "json",
                        success: function(data) {
                           // alert(data)
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
                $('#slct_ajt_Produit_UniteStock option').remove();
                $('#inpt_ajt_Produit_NombreArticles').val(1);
                $('#inpt_ajt_Produit_PrixUniteStock').val(0);
                $('#inpt_ajt_Produit_PrixUnitaire').val(0);
                $('#inpt_ajt_Produit_PrixAchat').val(0);
                $('#inpt_ajt_Produit_CodeBarre').val('');
                $('.div_unite_Stock').hide();

                $.ajax({
                    url:"calculs/insertionProduit.php",
                    method:"POST",
                    data:{
                        operation:'details_Reference',
                        reference : reference,
                    },
                    success: function (data) {
                        var details = JSON.parse(data);
                        var taille = details.length;
        
                        if(taille > 3) {
                            $('#inpt_ajt_Produit_Reference').val(details[1]);
                            $('#inpt_ajt_Produit_NombreArticles').val(details[4]);
                            $('#inpt_ajt_Produit_PrixUniteStock').val(details[5]);
                            $('#inpt_ajt_Produit_PrixUnitaire').val(details[6]);
                            $('#inpt_ajt_Produit_PrixAchat').val(details[7]);
                            $('#inpt_ajt_Produit_CodeBarre').val(details[8]);
                            if(details[3]=='Article' || details[3]=='article'){
                            $('.div_unite_Stock').hide();
                            }
                            else {
                            $('.div_unite_Stock').show();
                            $('.span_uniteStock').text(details[3]);
                            }
            
                            $.ajax({
                                url:"calculs/insertionProduit.php",
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
                                url:"calculs/insertionProduit.php",
                                method:"POST",
                                data:{
                                    operation:'choix_UniteStock',
                                    uniteStock : details[3],
                                },
                                success: function (data) {
                
                                    var choix = JSON.parse(data);
                                    var taille = choix.length;
                
                                    $('#slct_ajt_Produit_UniteStock').append("<option selected value='"+details[3]+"'>"+details[3]+"</option>");
                
                                    for(var i = 0; i<taille; i++){
                                            $('#slct_ajt_Produit_UniteStock').append("<option value='"+choix[i][1]+"'>"+choix[i][1]+"</option>");
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
                            else{
                                $('#inpt_ajt_Produit_Reference').val(reference);
                            }
                            var categorie='';
                            var uniteStock='';
                
                            $.ajax({
                                url:"calculs/insertionProduit.php",
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
                                url:"calculs/insertionProduit.php",
                                method:"POST",
                                data:{
                                    operation:'choix_UniteStock',
                                    uniteStock : uniteStock,
                                },
                                success: function (data) {
                  
                                    var choix = JSON.parse(data);
                                    var taille = choix.length;
                  
                                    $('#slct_ajt_Produit_UniteStock').append("<option selected >--------------</option>");
                  
                                    for(var i = 0; i<taille; i++){
                                            $('#slct_ajt_Produit_UniteStock').append("<option value='"+choix[i][1]+"'>"+choix[i][1]+"</option>");
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
        $('#slct_ajt_Produit_UniteStock option').remove();
        $('#inpt_ajt_Produit_NombreArticles').val(1);
        $('#inpt_ajt_Produit_PrixUniteStock').val(0);
        $('#inpt_ajt_Produit_PrixUnitaire').val(0);
        $('#inpt_ajt_Produit_PrixAchat').val(0);
        $('#inpt_ajt_Produit_CodeBarre').val('');
        $('.div_unite_Stock').hide();

        $.ajax({
            url:"calculs/insertionProduit.php",
            method:"POST",
            data:{
                operation:'details_Reference',
                reference : reference,
            },
            success: function (data) {
                var details = JSON.parse(data);
                var taille = details.length;

                if(taille > 3) {
                    $('#inpt_ajt_Produit_Reference').val(details[1]);
                    $('#inpt_ajt_Produit_NombreArticles').val(details[4]);
                    $('#inpt_ajt_Produit_PrixUniteStock').val(details[5]);
                    $('#inpt_ajt_Produit_PrixUnitaire').val(details[6]);
                    $('#inpt_ajt_Produit_PrixAchat').val(details[7]);
                    $('#inpt_ajt_Produit_CodeBarre').val(details[8]);
                    if(details[3]=='Article' || details[3]=='article'){
                    $('.div_unite_Stock').hide();
                    }
                    else {
                    $('.div_unite_Stock').show();
                    $('.span_uniteStock').text(details[3]);
                    }
    
                    $.ajax({
                        url:"calculs/insertionProduit.php",
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
                        url:"calculs/insertionProduit.php",
                        method:"POST",
                        data:{
                            operation:'choix_UniteStock',
                            uniteStock : details[3],
                        },
                        success: function (data) {
        
                            var choix = JSON.parse(data);
                            var taille = choix.length;
        
                            $('#slct_ajt_Produit_UniteStock').append("<option selected value='"+details[3]+"'>"+details[3]+"</option>");
        
                            for(var i = 0; i<taille; i++){
                                    $('#slct_ajt_Produit_UniteStock').append("<option value='"+choix[i][1]+"'>"+choix[i][1]+"</option>");
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
                    else{
                        $('#inpt_ajt_Produit_Reference').val(reference);
                    }
                    var categorie='';
                    var uniteStock='';
        
                    $.ajax({
                        url:"calculs/insertionProduit.php",
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
                        url:"calculs/insertionProduit.php",
                        method:"POST",
                        data:{
                            operation:'choix_UniteStock',
                            uniteStock : uniteStock,
                        },
                        success: function (data) {
          
                            var choix = JSON.parse(data);
                            var taille = choix.length;
          
                            $('#slct_ajt_Produit_UniteStock').append("<option selected >--------------</option>");
          
                            for(var i = 0; i<taille; i++){
                                    $('#slct_ajt_Produit_UniteStock').append("<option value='"+choix[i][1]+"'>"+choix[i][1]+"</option>");
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
            $('#slct_ajt_Produit_UniteStock option').remove();
            $('#inpt_ajt_Produit_NombreArticles').val(1);
            $('#inpt_ajt_Produit_PrixUniteStock').val(0);
            $('#inpt_ajt_Produit_PrixUnitaire').val(0);
            $('#inpt_ajt_Produit_PrixAchat').val(0);
            $('#inpt_ajt_Produit_CodeBarre').val('');
            $('.div_unite_Stock').hide();

            $.ajax({
                url:"calculs/insertionProduit.php",
                method:"POST",
                data:{
                    operation:'details_Reference',
                    codeBarre : codeBarre,
                },
                success: function (data) {
                    var details = JSON.parse(data);
                    var taille = details.length;

                    if(taille > 3) {
                        $('#inpt_ajt_Produit_Reference').val(details[1]);
                        $('#inpt_ajt_Produit_NombreArticles').val(details[4]);
                        $('#inpt_ajt_Produit_PrixUniteStock').val(details[5]);
                        $('#inpt_ajt_Produit_PrixUnitaire').val(details[6]);
                        $('#inpt_ajt_Produit_PrixAchat').val(details[7]);
                        $('#inpt_ajt_Produit_CodeBarre').val(details[8]);
                        if(details[3]=='Article' || details[3]=='article'){
                        $('.div_unite_Stock').hide();
                        }
                        else {
                        $('.div_unite_Stock').show();
                        $('.span_uniteStock').text(details[3]);
                        }
        
                        $.ajax({
                            url:"calculs/insertionProduit.php",
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
                            url:"calculs/insertionProduit.php",
                            method:"POST",
                            data:{
                                operation:'choix_UniteStock',
                                uniteStock : details[3],
                            },
                            success: function (data) {
            
                                var choix = JSON.parse(data);
                                var taille = choix.length;
            
                                $('#slct_ajt_Produit_UniteStock').append("<option selected value='"+details[3]+"'>"+details[3]+"</option>");
            
                                for(var i = 0; i<taille; i++){
                                        $('#slct_ajt_Produit_UniteStock').append("<option value='"+choix[i][1]+"'>"+choix[i][1]+"</option>");
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
                        else{
                            $('#inpt_ajt_Produit_CodeBarre').val(codeBarre);
                        }
                        var categorie='';
                        var uniteStock='';
            
                        $.ajax({
                            url:"calculs/insertionProduit.php",
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
                            url:"calculs/insertionProduit.php",
                            method:"POST",
                            data:{
                                operation:'choix_UniteStock',
                                uniteStock : uniteStock,
                            },
                            success: function (data) {
              
                                var choix = JSON.parse(data);
                                var taille = choix.length;
              
                                $('#slct_ajt_Produit_UniteStock').append("<option selected >--------------</option>");
              
                                for(var i = 0; i<taille; i++){
                                        $('#slct_ajt_Produit_UniteStock').append("<option value='"+choix[i][1]+"'>"+choix[i][1]+"</option>");
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
          var uniteStock='';
          $('#inpt_ajt_Produit_Reference').val('');
          $('#slct_ajt_Produit_Categorie option').remove();
          $('#slct_ajt_Produit_UniteStock option').remove();
          $('#inpt_ajt_Produit_NombreArticles').val(1);
          $('#inpt_ajt_Produit_PrixUniteStock').val(0);
          $('#inpt_ajt_Produit_PrixUnitaire').val(0);
          $('#inpt_ajt_Produit_PrixAchat').val(0);
          $('#inpt_ajt_Produit_CodeBarre').val('');
          $('.div_unite_Stock').hide();

          $.ajax({
              url:"calculs/insertionProduit.php",
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
              url:"calculs/insertionProduit.php",
              method:"POST",
              data:{
                  operation:'choix_UniteStock',
                  uniteStock : uniteStock,
              },
              success: function (data) {

                  var choix = JSON.parse(data);
                  var taille = choix.length;

                  $('#slct_ajt_Produit_UniteStock').append("<option selected >--------------</option>");

                  for(var i = 0; i<taille; i++){
                          $('#slct_ajt_Produit_UniteStock').append("<option value='"+choix[i][1]+"'>"+choix[i][1]+"</option>");
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
    $(function(){
      choix_unite= function (unite)  {
          if(unite=='Article' || unite=='article'){
            $('.div_unite_Stock').hide();
          }
          else {
            $('.div_unite_Stock').show();
            $('.span_uniteStock').text(unite);
          }
      }
    });
    $("#btn_ajouter_Produit").click( function(){
      reference=$('#inpt_ajt_Produit_Reference').val();
      categorie=$('#slct_ajt_Produit_Categorie').val();
      uniteStock=$('#slct_ajt_Produit_UniteStock').val();
      nombreArticles=$('#inpt_ajt_Produit_NombreArticles').val();
      prixUniteStock=$('#inpt_ajt_Produit_PrixUniteStock').val();
      prixUnitaire=$('#inpt_ajt_Produit_PrixUnitaire').val();
      prixAchat=$('#inpt_ajt_Produit_PrixAchat').val();
      codeBarre=$('#inpt_ajt_Produit_CodeBarre').val();

      $('#inpt_ajt_Produit_Stock_Quantite').val('');
      $('#inpt_ajt_Produit_Stock_DateExpiration').val('');
      $('#slct_ajt_Produit_Stock_UniteStock option').remove();
      
      $.ajax({
          url:"calculs/insertionProduit.php",
          method:"POST",
          data:{
              operation:'ajouter_Produit',
              reference : reference,
              categorie : categorie,
              uniteStock : uniteStock,
              nombreArticles : nombreArticles,
              prixUniteStock : prixUniteStock,
              prixUnitaire : prixUnitaire,
              prixAchat : prixAchat,
              codeBarre : codeBarre
          },
          success: function (data) {
              var details = JSON.parse(data);
              var ligne = "<tr class='produit"+details[0]+"'>"+
              "<td>"+details[1]+"</td>"+
              "<td>"+details[2]+"</td>"+
              "<td>"+details[3]+"</td>"+
              "<td>"+details[4]+"</td>"+
              "<td>"+details[5]+"</td>"+
              "<td>"+details[6]+"</td>"+
              "<td>"+details[7]+"</td>"+
              "<td>"+
                "<a><img src='images/edit.png' align='middle' alt='modifier' onclick='modifier_Produit("+details[0]+")' data-toggle='modal' /></a>&nbsp"+
                "<a><img src='images/drop.png' align='middle' alt='supprimer' onclick='supprimer_Produit("+details[0]+")' data-toggle='modal' /></a>"+
              "</td>"+
              "</tr>";
              $("#listeProduits").prepend(ligne);
              $('#ajouter_Produit').modal('hide');

              $('#inpt_ajt_Produit_Stock_idProduit').val(details[0]);
              $('#inpt_ajt_Produit_Stock_Reference').val(details[1]);
              $('#inpt_ajt_Produit_Stock_Quantite').val(1);
              if(details[3]=='Article' || details[3]=='article'){
                $('#slct_ajt_Produit_Stock_UniteStock').append("<option selected value='"+details[3]+"'>"+details[3]+"</option>");
              }
              else {
                $('#slct_ajt_Produit_Stock_UniteStock').append("<option selected value='"+details[3]+"'>"+details[3]+"</option>");
                $('#slct_ajt_Produit_Stock_UniteStock').append("<option value='Article'>Article</option>");
              }
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
    uniteStock=$('#slct_ajt_Produit_Stock_UniteStock').val();
    dateExpiration=$('#inpt_ajt_Produit_Stock_DateExpiration').val();

    $('#btn_ajouter_Produit_Stock_Ajouter').prop("disabled", true );

    $.ajax({
        url:"calculs/insertionProduit.php",
        method:"POST",
        data:{
            operation:'ajouter_Stock',
            idProduit : idProduit,
            quantite : quantite,
            uniteStock : uniteStock,
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
    uniteStock=$('#slct_ajt_Produit_Stock_UniteStock').val();
    dateExpiration=$('#inpt_ajt_Produit_Stock_DateExpiration').val();

    $('#btn_ajouter_Produit_Stock_Terminer').prop("disabled", true );

    $.ajax({
        url:"calculs/insertionProduit.php",
        method:"POST",
        data:{
            operation:'ajouter_Stock',
            idProduit : idProduit,
            quantite : quantite,
            uniteStock : uniteStock,
            dateExpiration : dateExpiration
        },
        success: function (data) {

            $('#inpt_ajt_Produit_Stock_Quantite').val('');
            $('#inpt_ajt_Produit_Stock_DateExpiration').val('');
            $('#slct_ajt_Produit_Stock_UniteStock option').remove();
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
          $('#slct_mdf_Produit_UniteStock option').remove();
          $('#inpt_mdf_Produit_NombreArticles').val('');
          $('#inpt_mdf_Produit_PrixUniteStock').val('');
          $('#inpt_mdf_Produit_PrixUnitaire').val('');
          $('#inpt_mdf_Produit_PrixAchat').val('');
          $('#inpt_mdf_Produit_CodeBarre').val('');
          $('.div_unite_Stock').hide();
          $.ajax({
              url:"calculs/insertionProduit.php",
              method:"POST",
              data:{
                  operation:'details_Produit',
                  idProduit : idProduit,
              },
              success: function (data) {
                  var details = JSON.parse(data);
                  $('#inpt_mdf_Produit_idProduit').val(details[0]);
                  $('#inpt_mdf_Produit_Reference').val(details[1]);
                  $('#inpt_mdf_Produit_NombreArticles').val(details[4]);
                  $('#inpt_mdf_Produit_PrixUniteStock').val(details[5]);
                  $('#inpt_mdf_Produit_PrixUnitaire').val(details[6]);
                  $('#inpt_mdf_Produit_PrixAchat').val(details[7]);
                  $('#inpt_mdf_Produit_CodeBarre').val(details[8]);
                  if(details[3]=='Article' || details[3]=='article'){
                    $('.div_unite_Stock').hide();
                  }
                  else {
                    $('.div_unite_Stock').show();
                    $('.span_uniteStock').text(details[3]);
                  }

                  $.ajax({
                    url:"calculs/insertionProduit.php",
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
                      url:"calculs/insertionProduit.php",
                      method:"POST",
                      data:{
                          operation:'choix_UniteStock',
                          uniteStock : details[3],
                      },
                      success: function (data) {
        
                          var choix = JSON.parse(data);
                          var taille = choix.length;
        
                          $('#slct_mdf_Produit_UniteStock').append("<option selected value='"+details[3]+"'>"+details[3]+"</option>");
        
                          for(var i = 0; i<taille; i++){
                                  $('#slct_mdf_Produit_UniteStock').append("<option value='"+choix[i][1]+"'>"+choix[i][1]+"</option>");
                          }
        
                      },
                      error: function() {
                        $("#message_Produit_titre").text('Erreur');
                        $("#message_Produit .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
                        $('#message_Produit').modal('show');
                      },
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
      uniteStock=$('#slct_mdf_Produit_UniteStock').val();
      nombreArticles=$('#inpt_mdf_Produit_NombreArticles').val();
      prixUniteStock=$('#inpt_mdf_Produit_PrixUniteStock').val();
      prixUnitaire=$('#inpt_mdf_Produit_PrixUnitaire').val();
      prixAchat=$('#inpt_mdf_Produit_PrixAchat').val();
      codeBarre=$('#inpt_mdf_Produit_CodeBarre').val();
        $.ajax({
          url:"calculs/insertionProduit.php",
          method:"POST",
          data:{
              operation:'modifier_Produit',
              idProduit : idProduit,
              reference : reference,
              categorie : categorie,
              uniteStock: uniteStock,    
              nombreArticles : nombreArticles,
              prixUniteStock: prixUniteStock,
              prixUnitaire : prixUnitaire,
              prixAchat : prixAchat,
              codeBarre : codeBarre,
          },
          success: function (data) {
              var details = JSON.parse(data);
              $('#listeProduits tbody .produit'+details[0]).each(function() {
                    $(this).find("td:eq(0)").html(details[1]);
                    $(this).find("td:eq(1)").html(details[8]);
                    $(this).find("td:eq(2)").html(details[3]);
                    $(this).find("td:eq(3)").html(details[4]);
                    $(this).find("td:eq(4)").html(details[5]);
                    $(this).find("td:eq(5)").html(details[6]);
                    $(this).find("td:eq(6)").html(details[7]);
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
          $('#span_spm_Produit_UniteStock').text('');
          $('#span_spm_produit_NombreArticles').text('');
          $('#span_spm_Produit_PrixUniteStock').text('');
          $('#span_spm_Produit_PrixUnitaire').text('');
          $('#span_spm_Produit_PrixAchat').text('');
          $('#span_spm_Produit_CodeBarre').text('');
          $('.div_unite_Stock').hide();
          $.ajax({
              url:"calculs/insertionProduit.php",
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
                $('#span_spm_Produit_UniteStock').text(details[3]);
                $('#span_spm_produit_NombreArticles').text(details[4]);
                $('#span_spm_Produit_PrixUniteStock').text(details[5]);
                $('#span_spm_Produit_PrixUnitaire').text(details[6]);
                $('#span_spm_Produit_PrixAchat').text(details[7]);
                $('#span_spm_Produit_CodeBarre').text(details[8]);
                if(details[3]=='Article' || details[3]=='article'){
                  $('.div_unite_Stock').hide();
                }
                else {
                  $('.div_unite_Stock').show();
                  $('.span_uniteStock').text(details[3]);
                }
                
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
          url:"calculs/insertionProduit.php",
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
            $('#span_act_Produit_UniteStock').text('');
            $('#span_act_produit_NombreArticles').text('');
            $('#span_act_Produit_PrixUniteStock').text('');
            $('#span_act_Produit_PrixUnitaire').text('');
            $('#span_act_Produit_PrixAchat').text('');
            $('#span_act_Produit_CodeBarre').text('');
            $('.div_unite_Stock').hide();
            $.ajax({
                url:"calculs/insertionProduit.php",
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
                $('#span_act_Produit_UniteStock').text(details[3]);
                $('#span_act_produit_NombreArticles').text(details[4]);
                $('#span_act_Produit_PrixUniteStock').text(details[5]);
                $('#span_act_Produit_PrixUnitaire').text(details[6]);
                $('#span_act_Produit_PrixAchat').text(details[7]);
                $('#span_act_Produit_CodeBarre').text(details[8]);
                if(details[3]=='Article' || details[3]=='article'){
                    $('.div_unite_Stock').hide();
                }
                else {
                    $('.div_unite_Stock').show();
                    $('.span_uniteStock').text(details[3]);
                }
                
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
            url:"calculs/insertionProduit.php",
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
            { data: 'uniteStock' },
            { data: 'nbreArticleUniteStock' },
            { data: 'prixuniteStock' },
            { data: 'prix' },
            { data: 'prixachat' },
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
                        $('#listeFusion_PH').DataTable({
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
                        $('#listeFusion_PH tbody .fusion'+idProduit).each(function() {
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
            uniteStock=$('#slct_Produit_UniteStock_'+idProduit).val();
            nombreArticleUS=$('#inpt_Produit_NombreArticleUS_'+idProduit).val();
            prixUniteStock=$('#inpt_Produit_PrixUniteStock_'+idProduit).val();
            prixUnitaire=$('#inpt_Produit_PrixUnitaire_'+idProduit).val();
            prixAchat=$('#inpt_Produit_PrixAchat_'+idProduit).val();
            $.ajax({
                url:"calculs/fusionProduit.php",
                method:"POST",
                data:{
                    operation:'fusion_Produit',
                    idProduit : idProduit,
                    designation : designation,
                    codeBarre : codeBarre,
                    quantite : quantite,
                    uniteStock : uniteStock,
                    nombreArticleUS : nombreArticleUS,
                    prixUniteStock : prixUniteStock,
                    prixUnitaire : prixUnitaire,
                    prixAchat : prixAchat,
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
            prixUniteStock=$('#inpt_Designation_PrixUniteStock_'+idProduit).val();
            prixUnitaire=$('#inpt_Designation_PrixUnitaire_'+idProduit).val();
            prixAchat=$('#inpt_Designation_PrixAchat_'+idProduit).val();  
            $('#btn_Valider-'+idProduit).prop("disabled", true); 
            $('.btn_Liste_Fusion').prop("disabled", true);
            $.ajax({
                url:"calculs/doublonProduit.php",
                method:"POST",
                data:{
                    operation:'fusion_Designation',
                    idProduit : idProduit,
                    codeBarre : codeBarre,
                    quantite : quantite,
                    prixUniteStock : prixUniteStock,
                    prixUnitaire : prixUnitaire,
                    prixAchat : prixAchat
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
            prixUniteStock=$('#inpt_CodeBarre_PrixUniteStock_'+idProduit).val();
            prixUnitaire=$('#inpt_CodeBarre_PrixUnitaire_'+idProduit).val();
            prixAchat=$('#inpt_CodeBarre_PrixAchat_'+idProduit).val();  
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
                    prixUniteStock : prixUniteStock,
                    prixUnitaire : prixUnitaire,
                    prixAchat : prixAchat
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