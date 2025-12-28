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
            'url':'datatables/stock_listeEntrees.php',
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
            { data: 'uniteStock' },
            { data: 'prixuniteStock' },
            { data: 'prixunitaire' },
            { data: 'prixachat' },
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
            'targets': [9,10], /* column index */
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
                'url':'datatables/stock_listeEntrees.php',
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
                { data: 'uniteStock' },
                { data: 'prixuniteStock' },
                { data: 'prixunitaire' },
                { data: 'prixachat' },
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
                'targets': [9,10], /* column index */
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
                'url':'datatables/stock_listeSorties.php',
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
                'url':'datatables/stock_listeInventaires.php',
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
            url:"calculs/stock.php",
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
                $('#slct_mdf_Stock_UniteStock option').remove();
                $('#inpt_mdf_Stock_PrixUniteStock').val('');
                $('#inpt_mdf_Stock_PrixUnitaire').val('');
                $('#inpt_mdf_Stock_PrixAchat').val('');
                $('#inpt_mdf_Stock_DateExpiration').val('');
                $.ajax({
                    url:"calculs/stock.php",
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
                        unite=details[10].split('<>')[0];
                        nbre_unite=details[10].split('<>')[1];
                        if(nbre_unite==1){
                            unite_val=details[3]+"<>1";
                            $('#slct_mdf_Stock_UniteStock').append("<option selected value='"+unite_val+"'>"+details[3]+"</option>");
                            $('#div_mdf_Stock_PrixUniteStock').hide();
                        }
                        else {
                            if(details[3]=='Article' || details[3]=='article'){
                                unite_val_1=details[3]+"<>1";
                                unite_val_2=unite+"<>"+nbre_unite;
                                $('#slct_mdf_Stock_UniteStock').append("<option selected value='"+unite_val_1+"'>"+details[3]+"</option>");
                                $('#slct_mdf_Stock_UniteStock').append("<option value='"+unite_val_2+"'>"+unite+"</option>");
                            }
                            else {
                                unite_val_1=unite+'<>'+nbre_unite;
                                unite_val_2="Article<>1";
                                $('#slct_mdf_Stock_UniteStock').append("<option selected value='"+unite_val_1+"'>"+unite+"</option>");
                                $('#slct_mdf_Stock_UniteStock').append("<option value='"+unite_val_2+"'>Article</option>");
                            }
                            $('#div_mdf_Stock_PrixUniteStock').show();
                        }
                        $('#inpt_mdf_Stock_PrixUniteStock').val(details[6]);
                        $('#inpt_mdf_Stock_PrixUnitaire').val(details[7]);
                        $('#inpt_mdf_Stock_PrixAchat').val(details[8]);
                        $('#inpt_mdf_Stock_DateExpiration').val(details[9]);
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
            uniteStock=$('#slct_mdf_Stock_UniteStock').val();
            prixUniteStock=$('#inpt_mdf_Stock_PrixUniteStock').val();
            prixUnitaire=$('#inpt_mdf_Stock_PrixUnitaire').val();
            prixAchat=$('#inpt_mdf_Stock_PrixAchat').val();
            dateExpiration=$('#inpt_mdf_Stock_DateExpiration').val();
              $.ajax({
                url:"calculs/stock.php",
                method:"POST",
                data:{
                    operation:'modifier_Stock',
                    idStock : idStock,
                    quantite : quantite,
                    uniteStock: uniteStock,    
                    prixUniteStock: prixUniteStock,
                    prixUnitaire : prixUnitaire,
                    prixAchat : prixAchat,
                    dateExpiration : dateExpiration,
                },
                success: function (data) {
                    var details = JSON.parse(data);
                    $('#listeEntrees tbody .entrees'+idStock).each(function() {
                          $(this).find("td:eq(1)").html(quantite);
                          $(this).find("td:eq(2)").html(details[1]);
                          $(this).find("td:eq(3)").html(details[2]);
                          $(this).find("td:eq(4)").html(prixUniteStock);
                          $(this).find("td:eq(5)").html(prixUnitaire);
                          $(this).find("td:eq(6)").html(prixAchat);
                          $(this).find("td:eq(7)").html(dateExpiration);
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
                    url:"calculs/stock.php",
                    method:"POST",
                    data:{
                        operation:'details_Stock',
                        idStock : idStock,
                    },
                    success: function (data) {
                        var details = JSON.parse(data);
                        $('#inpt_rtr_Stock_idStock').val(details[0]);
                        $('#inpt_rtr_Stock_DateEnregistrement').val(details[1]);
                        $('#inpt_rtr_Stock_Quantite').val(details[5]);
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
                url:"calculs/stock.php",
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
                    url:"calculs/stock.php",
                    method:"POST",
                    data:{
                        operation:'details_Stock',
                        idStock : idStock,
                    },
                    success: function (data) {
                        var details = JSON.parse(data);
                        $('#inpt_rtn_Stock_idStock').val(details[0]);
                        $('#inpt_rtn_Stock_DateEnregistrement').val(details[1]);
                        $('#inpt_rtn_Stock_Quantite').val(details[5]);
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
                url:"calculs/stock.php",
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
                $('#span_spm_Stock_UniteStock').text('');
                $('#span_spm_Stock_PrixUniteStock').text('');
                $('#span_spm_Stock_PrixUnitaire').text('');
                $('#span_spm_Stock_PrixAchat').text('');
                $('#span_spm_Stock_DateExpiration').text('');
                $.ajax({
                    url:"calculs/stock.php",
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
                        $('#span_spm_Stock_UniteStock').text(details[3]);
                        $('#span_spm_Stock_PrixUniteStock').text(details[6]);
                        $('#span_spm_Stock_PrixUnitaire').text(details[7]);
                        $('#span_spm_Stock_PrixAchat').text(details[8]);
                        $('#span_spm_Stock_DateExpiration').text(details[9]);
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
                url:"calculs/stock.php",
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