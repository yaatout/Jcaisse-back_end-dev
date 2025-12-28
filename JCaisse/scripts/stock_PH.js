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
            'url':'datatables/stock_listeEntrees_PH.php',
            'data':{
                'id' : id,
                'dateDebut' : dateDebut,
                'dateFin' : dateFin
            }
        },
        'dom': 'Blfrtip',
        "buttons": ['csv','print', 'excel', 'pdf'],
        "ordering": true,
        "order": [[0, 'desc']],
        'columns': [
            { data: 'idStock' },
            { data: 'dateStockage' },
            { data: 'quantiteStockinitial' },
            { data: 'quantiteStockCourant' },
            { data: 'forme' },
            { data: 'prixPublic' },
            { data: 'prixSession' },
            { data: 'dateExpiration' },
            { data: 'personnel' },
            { data: 'operations' }
           
        ],
        "fnCreatedRow": function( nRow, data, iDataIndex ) {
            $(nRow).attr('class', "entrees"+data['idStock']);
        },
        'columnDefs': [ 
            { "bVisible": false, "aTargets": [ 0 ] },
            {
            'targets': [8,9], /* column index */
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
                'url':'datatables/stock_listeEntrees_PH.php',
                'data':{
                    'id' : id,
                    'dateDebut' : dateDebut,
                    'dateFin' : dateFin
                }
            },
            'dom': 'Blfrtip',
            "buttons": ['csv','print', 'excel', 'pdf'],
            "ordering": true,
            "order": [[0, 'desc']],
            'columns': [
                { data: 'idStock' },
                { data: 'dateStockage' },
                { data: 'quantiteStockinitial' },
                { data: 'quantiteStockCourant' },
                { data: 'forme' },
                { data: 'prixPublic' },
                { data: 'prixSession' },
                { data: 'dateExpiration' },
                { data: 'personnel' },
                { data: 'operations' },
              
            ],
            "fnCreatedRow": function( nRow, data, iDataIndex ) {
                $(nRow).attr('class', "entrees"+data['idStock']);
            },
            'columnDefs': [ 
                { "bVisible": false, "aTargets": [ 0 ] },
                {
                'targets': [8,9], /* column index */
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
                'url':'datatables/stock_listeSorties_PH.php',
                'data':{
                    'id' : id,
                    'dateDebut' : dateDebut,
                    'dateFin' : dateFin
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
                { data: 'prixPublic' },
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

    $("#listeImputationsEvent").on( "click", function (e){
        e.preventDefault();
        
        $('#listeImputations').DataTable({
            'processing': true,
            'serverSide': true,
            'destroy': true,
            'serverMethod': 'post',
            'ajax': {
                'url':'datatables/stock_listeImputations_PH.php',
                'data':{
                    'id' : id,
                    'dateDebut' : dateDebut,
                    'dateFin' : dateFin
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
                { data: 'prixPublic' },
                { data: 'mutuelle' },
                { data: 'facture' },
                { data: 'adherant' },
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
                'url':'datatables/stock_listeInventaires_PH.php',
                'data':{
                    'id' : id,
                    'dateDebut' : dateDebut,
                    'dateFin' : dateFin
                }
            },
            'dom': 'Blfrtip',
            "buttons": ['csv','print', 'excel', 'pdf'],
            "ordering": true,
            "order": [[0, 'desc']],
            'columns': [
                { data: 'idInventaire' },
                { data: 'dateInventaire' },
                { data: 'heureInventaire' },
                { data: 'quantiteStockCourant' },
                { data: 'quantite' },
                { data: 'dateStockage' },
                { data: 'type' },
                { data: 'personnel'},
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

    $("#btn_details").on( "click", function (e){
        e.preventDefault();
        $.ajax({
            url:"calculs/stock_PH.php",
            method:"POST",
            data:{
                operation:'calcul_Quantite',
                idProduit: id,
                dateDebut: dateDebut,
                dateFin: dateFin,
            },
            success: function (data) {
                var details = JSON.parse(data);
                var entrees=details.split('<>')[0];
                var sorties=details.split('<>')[1];
                $('#spn_quantite_Entrees').text(entrees);
                $('#spn_quantite_Sorties').text(sorties);
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        });     

    });

    /**Debut Modifier Stock**/
        $(function(){
            modifier_Stock= function (idStock)  {
                $('#inpt_mdf_Stock_idStock').val('');
                $('#inpt_mdf_Stock_DateEnregistrement').val('');
                $('#inpt_mdf_Stock_Quantite').val('');
                $('#inpt_mdf_Stock_Forme').val('');
                $('#inpt_mdf_Stock_PrixPublic').val('');
                $('#inpt_mdf_Stock_PrixSession').val('');
                $('#inpt_mdf_Stock_DateExpiration').val('');
                $.ajax({
                    url:"calculs/stock_PH.php",
                    method:"POST",
                    data:{
                        operation:'details_Stock',
                        idStock : idStock,
                    },
                    success: function (data) {
                        var details = JSON.parse(data);
                        $('#inpt_mdf_Stock_idStock').val(details[0]);
                        $('#inpt_mdf_Stock_DateEnregistrement').val(details[1]);
                        $('#inpt_mdf_Stock_Quantite').val(details[2]);
                        $('#inpt_mdf_Stock_Forme').val(details[3]);
                        $('#inpt_mdf_Stock_PrixPublic').val(details[5]);
                        $('#inpt_mdf_Stock_PrixSession').val(details[6]);
                        $('#inpt_mdf_Stock_DateExpiration').val(details[7]);
                        $('#modifier_Stock').modal('show');
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                });
            }
        });
        $("#btn_modifier_Stock").click( function(){
            idStock=$('#inpt_mdf_Stock_idStock').val();
            quantite=$('#inpt_mdf_Stock_Quantite').val();
            forme=$('#inpt_mdf_Stock_Forme').val();
            prixPublic=$('#inpt_mdf_Stock_PrixPublic').val();
            prixSession=$('#inpt_mdf_Stock_PrixSession').val();
            dateExpiration=$('#inpt_mdf_Stock_DateExpiration').val();
              $.ajax({
                url:"calculs/stock_PH.php",
                method:"POST",
                data:{
                    operation:'modifier_Stock',
                    idStock : idStock,
                    quantite : quantite,
                    forme: forme,    
                    prixPublic: prixPublic,
                    prixSession : prixSession,
                    dateExpiration : dateExpiration,
                },
                success: function (data) {
                    var details = JSON.parse(data);
                    $('#listeEntrees tbody .entrees'+idStock).each(function() {
                          $(this).find("td:eq(1)").html(quantite);
                          $(this).find("td:eq(2)").html(details[1]);
                          $(this).find("td:eq(3)").html(forme);
                          $(this).find("td:eq(4)").html(prixPublic);
                          $(this).find("td:eq(5)").html(prixSession);
                          $(this).find("td:eq(6)").html(dateExpiration);
                          $(this).animate({opacity: 0.65 }, "slow");
                          $('#modifier_Stock').modal('hide');
                    });
                    
                },
                error: function() {

                },
                dataType:"text"
            }); 
        });
    /**Fin Modifier Stock**/
 
    /**Debut Retirer Stock**/
        $(function(){
            stock_Retirer= function (idStock)  {
                $('#inpt_rtr_Stock_idStock').val('');
                $('#inpt_rtr_Stock_DateEnregistrement').val('');
                $('#inpt_rtr_Stock_Quantite').val('');
                $('#inpt_rtr_Stock_DateExpiration').val('');
                $.ajax({
                    url:"calculs/stock_PH.php",
                    method:"POST",
                    data:{
                        operation:'details_Stock',
                        idStock : idStock,
                    },
                    success: function (data) {
                        var details = JSON.parse(data);
                        $('#inpt_rtr_Stock_idStock').val(details[0]);
                        $('#inpt_rtr_Stock_DateEnregistrement').val(details[1]);
                        $('#inpt_rtr_Stock_Quantite').val(details[4]);
                        $('#inpt_rtr_Stock_DateExpiration').val(details[9]);
                        $('#stock_Retirer').modal('show');
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                });
            }
        });
        $("#btn_retirer_Stock").click( function(){
            idStock=$('#inpt_rtr_Stock_idStock').val();
            quantite=$('#inpt_rtr_Stock_Quantite').val();
            $.ajax({
                url:"calculs/stock_PH.php",
                method:"POST",
                data:{
                    operation:'retirer_Stock',
                    idStock : idStock,
                    quantite : quantite,
                },
                success: function (data) {
                  $('#listeEntrees tbody .entrees'+idStock).each(function() {
                    $(this).find("td:eq(2)").html(quantite);
                    $(this).animate({opacity: 0.65 }, "slow");
                  });
                  $('#stock_Retirer').modal('hide');
                },
                error: function() {
                    alert("La requête "); },
                dataType:"text"
            }); 
        });
    /**Fin Retirer Stock**/

    /**Debut Retourner Stock**/
        $(function(){
            stock_Retourner= function (idStock)  {
                $('#inpt_rtn_Stock_idStock').val('');
                $('#inpt_rtn_Stock_DateEnregistrement').val('');
                $('#inpt_rtn_Stock_Quantite').val('');
                $('#inpt_rtn_Stock_DateExpiration').val('');
                $.ajax({
                    url:"calculs/stock_PH.php",
                    method:"POST",
                    data:{
                        operation:'details_Stock',
                        idStock : idStock,
                    },
                    success: function (data) {
                        var details = JSON.parse(data);
                        $('#inpt_rtn_Stock_idStock').val(details[0]);
                        $('#inpt_rtn_Stock_DateEnregistrement').val(details[1]);
                        $('#inpt_rtn_Stock_Quantite').val(details[4]);
                        $('#inpt_rtn_Stock_DateExpiration').val(details[9]);
                        $('#stock_Retourner').modal('show');
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                });
            }
        });
        $("#btn_retourner_Stock").click( function(){
            idStock=$('#inpt_rtn_Stock_idStock').val();
            quantite=$('#inpt_rtn_Stock_Quantite').val();
            $.ajax({
                url:"calculs/stock_PH.php",
                method:"POST",
                data:{
                    operation:'retourner_Stock',
                    idStock : idStock,
                    quantite : quantite,
                },
                success: function (data) {
                  $('#listeEntrees tbody .entrees'+idStock).each(function() {
                    $(this).find("td:eq(2)").html(quantite);
                    $(this).animate({opacity: 0.65 }, "slow");
                  });
                  $('#stock_Retourner').modal('hide');
                },
                error: function() {
                    alert("La requête "); },
                dataType:"text"
            }); 
        });
    /**Fin Retourner Stock**/

    /**Debut Supprimer Stock**/
        $(function(){
            supprimer_Stock= function (idStock)  {
                $('#inpt_spm_Stock_idStock').val('');
                $('#span_spm_Stock_DateEnregistrement').text('');
                $('#span_spm_Stock_Quantite').text('');
                $('#span_spm_Stock_Forme').text('');
                $('#span_spm_Stock_PrixPublic').text('');
                $('#span_spm_Stock_PrixSession').text('');
                $('#span_spm_Stock_DateExpiration').text('');
                $.ajax({
                    url:"calculs/stock_PH.php",
                    method:"POST",
                    data:{
                        operation:'details_Stock',
                        idStock : idStock,
                    },
                    success: function (data) {
                        var details = JSON.parse(data);
                        $('#inpt_spm_Stock_idStock').val(details[0]);
                        $('#span_spm_Stock_DateEnregistrement').text(details[1]);
                        $('#span_spm_Stock_Quantite').text(details[2]);
                        $('#span_spm_Stock_Forme').text(details[3]);
                        $('#span_spm_Stock_PrixPublic').text(details[4]);
                        $('#span_spm_Stock_PrixSession').text(details[5]);
                        $('#span_spm_Stock_DateExpiration').text(details[6]);
                        $('#supprimer_Stock').modal('show');
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                });
            }
        });
        $("#btn_supprimer_Stock").click( function(){
            idStock=$('#inpt_spm_Stock_idStock').val();
            $.ajax({
                url:"calculs/stock_PH.php",
                method:"POST",
                data:{
                    operation:'supprimer_Stock',
                    idStock : idStock
                },
                success: function (data) {
                  $('#listeEntrees tbody .entrees'+idStock).each(function() {
                      $(this).animate({ opacity: 1/2 }, 1000);
                      $(this).hide(3000, function () {
                          $(this).remove();
                      });
                  });
                  $('#supprimer_Stock').modal('hide');
                },
                error: function() {
                    alert("La requête "); },
                dataType:"text"
            }); 
        });
    /**Fin Supprimer Stock**/



});