$(document).ready(function() {  

    id=$('#inpt_Bien_id').val();

    $("#listerOperations").load("datatables/detailsReservation_BN.php",{"nbre_Entree":"","query":"","id":id},  function (response, status, xhr){
        if(status == "success"){
                    
        }
    });
    
    $("#inpt_Search_ListerOperations").on("keyup", function(e) {

        e.preventDefault();
        query = $('#inpt_Search_ListerOperations').val();
        nbre_Entree = $('#slct_Nbre_ListerOperations').val() ;

        var keycode = (e.keyCode ? e.keyCode : e.which);
            if (keycode == '13') {
                if (query.length > 0) {
                    $("#listerOperations").load("datatables/detailsReservation_BN.php",{"nbre_Entree":nbre_Entree,"query":query,"id":id},  function (response, status, xhr){
                        if(status == "success"){
                                    
                        }
                    });
                }else{
                    $("#listerOperations").load("datatables/detailsReservation_BN.php",{"nbre_Entree":nbre_Entree,"query":"","id":id},  function (response, status, xhr){
                        if(status == "success"){
                                    
                        }
                    });
                }
            }else{
                setTimeout(() => {
                    if (query.length > 0) {
                        $("#listerOperations").load("datatables/detailsReservation_BN.php",{"nbre_Entree":nbre_Entree,"query":query,"id":id},  function (response, status, xhr){
                            if(status == "success"){
                                        
                            }
                        });
                    }else{
                        $("#listerOperations").load("datatables/detailsReservation_BN.php",{"nbre_Entree":nbre_Entree,"query":"","id":id},  function (response, status, xhr){
                            if(status == "success"){
                                        
                            }
                        });
                    }

                }, 100);
            }

    });

    $("#listerOperations").on( "change", "#slct_Nbre_ListerOperations", function (e){
        e.preventDefault();
        query = $('#inpt_Search_ListerOperations').val();
        nbre_Entree = $('#slct_Nbre_ListerOperations').val() ;

        if (query.length == 0) {
            $("#listerOperations").load("datatables/detailsReservation_BN.php",{"nbre_Entree":nbre_Entree,"query":"","id":id},  function (response, status, xhr){
                if(status == "success"){
                    $('#slct_Nbre_ListerOperations').val(nbre_Entree) ;
                }
            });
        }else{
            $("#listerOperations").load("datatables/detailsReservation_BN.php",{"nbre_Entree":nbre_Entree,"query":query,"id":id},  function (response, status, xhr){
                if(status == "success"){
                    $('#slct_Nbre_ListerOperations').val(nbre_Entree) ;     
                }
            });
        }

    });

    $("#listerOperations").on( "click", ".pagination a", function (e){

        e.preventDefault();
        page = $(this).attr("data-page"); //get page number from link
        query = $('#inpt_Search_ListerOperations').val();
        nbre_Entree = $('#slct_Nbre_ListerOperations').val() ;

        if (query.length == 0) {
            $("#listerOperations").load("datatables/detailsReservation_BN.php",{"page":page,"nbre_Entree":nbre_Entree,"query":"","id":id},  function (response, status, xhr){
                if(status == "success"){
                    $('#slct_Nbre_ListerOperations').val(nbre_Entree) ;        
                }
            });
        }else{
            $("#listerOperations").load("datatables/detailsReservation_BN.php",{"page":page,"nbre_Entree":nbre_Entree,"query":query,"id":id},  function (response, status, xhr){
                if(status == "success"){
                    $('#slct_Nbre_ListerOperations').val(nbre_Entree) ;       
                }
            });
        }

    });

    $("#inpt_ajt_Bien_Reference").on("keyup", function(e) {
        e.preventDefault();
        query = $('#inpt_ajt_Bien_Reference').val();
        if (query.length > 0) { 
        
            $(this).typeahead({
                source: function(query, result) {
                    $.ajax({
                        url:"calculs/detailsReservation_BN.php",
                        method: "POST",
                        data: {
                            operation: "choix_Reference",
                            query: query
                        },
                        dataType: "json",
                        success: function(data) {
                            result($.map(data, function(item) {
                                return item;
                            }))
                        },
                        error: function(err) {
                            alert("La requête ss");
                        }
                    });
                }
            });
            $(this).focus();

            var reference=$('#form_ajouter_Bien .typeahead li.active').text();
            var keycode = (e.keyCode ? e.keyCode : e.which);
            if (keycode == '13') {
                details = reference.split(', Prix = ');
                $('#inpt_ajt_Bien_Reference').val(details[0]);
                $('#inpt_ajt_Bien_Prix').val(details[1]);
            }

        }
                
    });

    $.ajax({
        url:"calculs/detailsReservation_BN.php",
        method:"POST",
        data:{
            operation:'reservation',
            idReservation : id
        },
        success: function (data) {
          var details = JSON.parse(data);
             $("#montantBiens").text(details[0]);
             $("#montantVersements").text(details[1]);
             $("#montantReservation").text(details[2]);
        },
        error: function() {
            console("La requête "); 
        },
        dataType:"text"
    });

    $("#form_ajouter_Bien").on( "click",".typeahead li a[class='dropdown-item']", function (e){
        var reference=$('#form_ajouter_Bien .typeahead li.active').text();                
        details = reference.split(', Prix = ');
        $('#inpt_ajt_Bien_Reference').val(details[0]);
        $('#inpt_ajt_Bien_Prix').val(details[1]);
    });


    /**Debut Ajouter Bien**/
        $("#btn_ajouter_Bien").click( function(){
            reference=$('#inpt_ajt_Bien_Reference').val();
            prixBien=$('#inpt_ajt_Bien_Prix').val();
            tab_DateArrivee=$('#inpt_ajt_Bien_DateArrivee').val();
            tab = tab_DateArrivee.split('T');
            dateArrivee=tab[0];
            heureArrivee=tab[1];
            tab_DateDepart=$('#inpt_ajt_Bien_DateDepart').val();
            tab = tab_DateDepart.split('T');
            dateDepart=tab[0];
            heureDepart=tab[1];
            $.ajax({
                url:"calculs/detailsReservation_BN.php",
                method:"POST",
                data:{
                    operation:'ajouter_Bien',
                    reference : reference,
                    idReservation : id,
                    prixBien : prixBien,
                    dateArrivee : dateArrivee,
                    heureArrivee : heureArrivee,
                    dateDepart : dateDepart,
                    heureDepart : heureDepart,
                },
                success: function (data) {
                    $('#ajouter_Bien').modal('hide');
                    $("#listerOperations").load("datatables/detailsReservation_BN.php",{"nbre_Entree":"","query":"","id":id},  function (response, status, xhr){
                        if(status == "success"){
                            $.ajax({
                                url:"calculs/detailsReservation_BN.php",
                                method:"POST",
                                data:{
                                    operation:'reservation',
                                    idReservation : id
                                },
                                success: function (data) {
                                  var details = JSON.parse(data);
                                     $("#montantBiens").text(details[0]);
                                     $("#montantVersements").text(details[1]);
                                     $("#montantReservation").text(details[2]);
                                },
                                error: function() {
                                    console("La requête "); 
                                },
                                dataType:"text"
                            });
                        }
                    });
                },
                error: function() {
                    alert("La requête "); },
                dataType:"text"
            });
        });
    /**Fin Ajouter Bien**/

    /**Debut Supprimer Bien**/
        $(function(){
            supprimer_Bien= function (numligne)  {
                $('#inpt_spm_Bien_numligne').val('');
                $('#inpt_spm_Bien_Reference').val('');
                $('#inpt_spm_Bien_Prix').text('');
                $('#inpt_spm_Bien_DateArrivee').text('');
                $('#inpt_spm_Bien_DateDepart').text('');
                $('#inpt_spm_Bien_Prixtotal').text('');
                $.ajax({
                    url:"calculs/detailsReservation_BN.php",
                    method:"POST",
                    data:{
                        operation:'details_Bien',
                        numligne : numligne,
                    },
                    success: function (data) {
                        var details = JSON.parse(data);
                        $('#inpt_spm_Bien_numligne').val(details[0]);
                        $('#inpt_spm_Bien_Reference').text(details[1]);
                        $('#inpt_spm_Bien_Prix').text(details[2]);
                        $('#inpt_spm_Bien_DateArrivee').text(details[3]);
                        $('#inpt_spm_Bien_DateDepart').text(details[4]);
                        $('#inpt_spm_Bien_Prixtotal').text(details[5]);
                        $('#supprimer_Bien').modal('show');
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                }); 
            }
        });
        $("#btn_supprimer_Bien").click( function(){
            numligne=$('#inpt_spm_Bien_numligne').val();
            $.ajax({
                url:"calculs/detailsReservation_BN.php",
                method:"POST",
                data:{
                    operation:'supprimer_Bien',
                    numligne : numligne,
                },
                success: function (data) {
                    $('#supprimer_Bien').modal('hide');
                    $("#listerOperations").load("datatables/detailsReservation_BN.php",{"nbre_Entree":"","query":"","id":id},  function (response, status, xhr){
                        if(status == "success"){
                            $.ajax({
                                url:"calculs/detailsReservation_BN.php",
                                method:"POST",
                                data:{
                                    operation:'reservation',
                                    idReservation : id
                                },
                                success: function (data) {
                                  var details = JSON.parse(data);
                                     $("#montantBiens").text(details[0]);
                                     $("#montantVersements").text(details[1]);
                                     $("#montantReservation").text(details[2]);
                                },
                                error: function() {
                                    console("La requête "); 
                                },
                                dataType:"text"
                            });
                        }
                    });
                },
                error: function() {
                    alert("La requête "); },
                dataType:"text"
            }); 
        });
    /**Fin Supprimer Bien**/

    /**Debut Ajouter Versement**/
        $("#btn_ajouter_Versement").click( function(){
            montant=$('#inpt_ajt_Versement_Montant').val();
            dateVersement=$('#inpt_ajt_Versement_Date').val();
            description=$('#inpt_ajt_Versement_Description').val();
            compte=$('#inpt_ajt_Versement_Compte').val();
            $.ajax({
                url:"calculs/detailsReservation_BN.php",
                method:"POST",
                data:{
                    operation:'ajouter_Versement',
                    idReservation : id,
                    montant : montant,
                    dateVersement : dateVersement,
                    description : description,
                    compte : compte
                },
                success: function (data) {
                    $('#ajouter_Versement').modal('hide');
                    $("#listerOperations").load("datatables/detailsReservation_BN.php",{"nbre_Entree":"","query":"","id":id},  function (response, status, xhr){
                        if(status == "success"){
                            $.ajax({
                                url:"calculs/detailsReservation_BN.php",
                                method:"POST",
                                data:{
                                    operation:'reservation',
                                    idReservation : id
                                },
                                success: function (data) {
                                  var details = JSON.parse(data);
                                     $("#montantBiens").text(details[0]);
                                     $("#montantVersements").text(details[1]);
                                     $("#montantReservation").text(details[2]);
                                },
                                error: function() {
                                    console("La requête "); 
                                },
                                dataType:"text"
                            });
                        }
                    });
                },
                error: function() {
                    alert("La requête "); },
                dataType:"text"
            });
        });
    /**Fin Ajouter Versement**/

    /**Debut Modifier Versement**/
        $(function(){
            modifier_Versement= function (idVersement)  {
                $("#ancien_Versement"+idVersement).hide();
                $("#nouveau_Versement"+idVersement).show();
            }
        });
        $(function(){
            btn_modifier_Versement= function (idVersement)  {
                numero=$('#inpt_mdf_Versement_Numero'+idVersement).val();
                description=$('#inpt_mdf_Versement_Description'+idVersement).val();
                montant=$('#inpt_mdf_Versement_Montant'+idVersement).val();
                dateVersement=$('#inpt_mdf_Versement_Date'+idVersement).val();
                $.ajax({
                    url:"calculs/detailsReservation_BN.php",
                    method:"POST",
                    data:{
                        operation:'modifier_Versement',
                        idVersement : idVersement,
                        numero : numero,
                        montant : montant,
                        dateVersement : dateVersement,
                        description : description
                    },
                    success: function (data) {
                        $("#listerOperations").load("datatables/detailsReservation_BN.php",{"nbre_Entree":"","query":"","id":id},  function (response, status, xhr){
                            if(status == "success"){
                                $.ajax({
                                    url:"calculs/detailsReservation_BN.php",
                                    method:"POST",
                                    data:{
                                        operation:'reservation',
                                        idReservation : id
                                    },
                                    success: function (data) {
                                      var details = JSON.parse(data);
                                         $("#montantBiens").text(details[0]);
                                         $("#montantVersements").text(details[1]);
                                         $("#montantReservation").text(details[2]);
                                    },
                                    error: function() {
                                        console("La requête "); 
                                    },
                                    dataType:"text"
                                });
                            }
                        });
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                });
            }
        });
    /**Fin Modifier Versement**/

    /**Debut Supprimer Versement**/
        $(function(){
            supprimer_Versement= function (idVersement)  {
                $('#inpt_spm_Versement_idVersement').val('');
                $('#span_spm_Versement_Numero').text('');
                $('#span_spm_Versement_Date').text('');
                $('#span_spm_Versement_Montant').text('');
                $('#span_spm_Versement_Description').text('');
                $.ajax({
                    url:"calculs/detailsReservation_BN.php",
                    method:"POST",
                    data:{
                        operation:'details_Versement',
                        idVersement : idVersement,
                    },
                    success: function (data) {
                        var details = JSON.parse(data);
                        $('#inpt_spm_Versement_idVersement').val(details[0]);
                        $('#span_spm_Versement_Numero').text('#'+details[0]);
                        $('#span_spm_Versement_Date').text(details[1]);
                        $('#span_spm_Versement_Montant').text(details[2]);
                        $('#span_spm_Versement_Description').text(details[3]);
                        $('#supprimer_Versement').modal('show');
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                }); 
            }
        });
        $("#btn_supprimer_Versement").click( function(){
            idVersement=$('#inpt_spm_Versement_idVersement').val();
            $.ajax({
                url:"calculs/detailsReservation_BN.php",
                method:"POST",
                data:{
                    operation:'supprimer_Versement',
                    idVersement : idVersement,
                },
                success: function (data) {
                    $('#supprimer_Versement').modal('hide');
                    $("#listerOperations").load("datatables/detailsReservation_BN.php",{"nbre_Entree":"","query":"","id":id},  function (response, status, xhr){
                        if(status == "success"){
                            $.ajax({
                                url:"calculs/detailsReservation_BN.php",
                                method:"POST",
                                data:{
                                    operation:'reservation',
                                    idReservation : id
                                },
                                success: function (data) {
                                  var details = JSON.parse(data);
                                     $("#montantBiens").text(details[0]);
                                     $("#montantVersements").text(details[1]);
                                     $("#montantReservation").text(details[2]);
                                },
                                error: function() {
                                    console("La requête "); 
                                },
                                dataType:"text"
                            });
                        }
                    });
                },
                error: function() {
                    alert("La requête "); },
                dataType:"text"
            }); 
        });
    /**Fin Supprimer Versement**/

    


});
