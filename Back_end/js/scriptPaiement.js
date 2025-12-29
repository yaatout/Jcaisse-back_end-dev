$(function() {
    $("#refTransfPm----------").focusout(function() {
         //console.log('refTransfPm');
        var refTransfPm = $('#refTransfPm').val();
        var montantFixePayementTotalPm = $('#montantFixePayementTotalPm').val();
        //console.log(refTransfPm);
       /* var datePS = $('#datePS_Rtr').val();
        var montantFixePayement = $('#montantFixePayement_Rtr').val();
        var refTransf = $('#refTransf_Rtr').val();
        var numTel = $('#numTel_Rtr').val();*/
        $.ajax({
            url: "ajax/paiementPmAjax.php",
            method: "POST",
            data: {
                operation: 1,
                refTransfPm: refTransfPm,
            },
            success: function(montantMensuel) {
                var nbrMoisPm= montantFixePayementTotalPm / montantMensuel;
                $('#montantFixePayementM').val(montantMensuel);
                $('#nbrMoisPm').val(nbrMoisPm);
                console.log(' montantFixePayementTotalPm'+montantFixePayementTotalPm);
                console.log(' montantMensuel'+montantMensuel);
                console.log(' pooooo'+nbrMoisPm);
            },
            error: function() {
                alert("La requête ");
            },
            dataType: "text"
        });
    });
    /*$("#montantFixePayementTotalPm").focusout(function() {
         //console.log('refTransfPm');
        var refTransfPm = $('#refTransfPm').val();
        var montantFixePayementTotalPm = $('#montantFixePayementTotalPm').val();
        //console.log(refTransfPm);
        //var datePS = $('#datePS_Rtr').val();
        //var montantFixePayement = $('#montantFixePayement_Rtr').val();
        //var refTransf = $('#refTransf_Rtr').val();
        //var numTel = $('#numTel_Rtr').val();
        $.ajax({
            url: "ajax/paiementPmAjax.php",
            method: "POST",
            data: {
                operation: 1,
                refTransfPm: refTransfPm,
            },
            success: function(montantMensuel) {
                var nbrMoisPm= montantFixePayementTotalPm / montantMensuel;
                $('#montantFixePayementM').val(montantMensuel);
                $('#nbrMoisPm').val(nbrMoisPm);
               
            },
            error: function() {
                alert("La requête ");
            },
            dataType: "text"
        });
    });*/
    
    
    /*$("#btnRefTransPlusieursMois").click(function() {
         console.log('B222222222');
         var dateTransfertPm = $('#dateTransfertPm').val();
         var nbrMoisPm = $('#nbrMoisPm').val(); 
         var montantFixePayementM = $('#montantFixePayementM').val(); 
         var montantFixePayementTotalPm = $('#montantFixePayementTotalPm').val() ;
         var refTransfPm = $('#refTransfPm').val() ;
         var numTelPm = $('#numTelPm').val() ;
         var typeCompteMobile =  $("input[name='operateur']:checked").val() ;
         alert(" mobbbbb "+typeCompteMobile);
         $.ajax({
                        url: "ajax/paiementPmAjax.php",
                        method: "POST",
                        data: {
                            operation: 2,
                            dateTransfert: dateTransfertPm,
                            nbrMoisPm: nbrMoisPm,
                            montantFixePayementM: montantFixePayementM,
                            montantFixePayementTotalPm: montantFixePayementTotalPm,
                            refTransfPm: refTransfPm,
                            numTelPm: numTelPm,
                            typeCompteMobile: typeCompteMobile,
                        },
                        success: function(data) {
                           console.log('suceeeeeeeeeeeee');
                           console.log(data);
                        },
                        error: function() {
                            alert("La requête ");
                        },
                        dataType: "text"
                    });
         console.log('successsssssss');
    });*/
    $("#btnRefTransPlusieursMois").click(function() {    
         console.log('B222222222');
         var idBoutique = $('#idBoutiquePm').val();
         var heurePaiementB = $('#heurePaiementB').val();
         var datePaiementB = $('#datePaiementB').val();
         var dateTransfertPm = $('#dateTransfertPm').val();
         var montantFixePayementM = $('#montantFixePayementM').val(); 
         var refTransfPm = $('#refTransfPm').val() ;
         var montantFixePayementTotalPm = $('#montantFixePayementTotalPm').val() ;
         var nbrMoisPm = $('#nbrMoisPm').val(); 
         var numTelPm = $('#numTelPm').val() ;
         var typeCompteMobile =  $("input[name='operateur']:checked").val() ;
         //alert(" mobbbbb "+typeCompteMobile);
         $.ajax({
                        url: "ajax/paiementPmAjax.php",
                        method: "POST",
                        data: {
                            operation: 5,
                            idBoutique: idBoutique,
                            heurePaiement: heurePaiementB,
                            datePaiement: datePaiementB,
                            dateTransfert: dateTransfertPm,
                            nbrMoisPm: nbrMoisPm,
                            montantFixePayementM: montantFixePayementM,
                            montantFixePayementTotalPm: montantFixePayementTotalPm,
                            refTransfPm: refTransfPm,
                            numTelPm: numTelPm,
                            typeCompteMobile: typeCompteMobile,
                        },
                        success: function(data) {
                            console.log('suceeeeeeeeeeeee');
                            alert('Paiement plusieurs mois effectué avec succé');
                            $('#RefTransPlusieursMois').modal('hide');
                        },
                        error: function() {
                            alert("La requête ");
                        },
                        dataType: "text"
                    });
         console.log('successsssssss');
    });
    
    $("#montantFixePayementTotalPm").focusout( function(){
            console.log('Entrer');
            var montantTotal=$('#montantFixePayementTotalPm').val();
            var montant=$('#montantFixePayementM').val();
            var nbrMoisPm=montantTotal/montant;
            console.log('KKKKKPPPPP'+montantTotal);
            console.log('KKKKKPPPPP'+montant);
            console.log('KKKKKPPPPP'+nbrMoisPm);
            $('#nbrMoisPm').val(nbrMoisPm);                
        }); 
    $("#montantFixePayementTotalPmB").focusout( function(){
            console.log('Entrer');
            var montantTotal=$('#montantFixePayementTotalPmB').val();
            var montant=$('#montantFixePayementMB').val();
            var nbrMoisPm=montantTotal/montant;
            console.log('KKKKKPPPPP'+montantTotal);
            console.log('KKKKKPPPPP'+montant);
            console.log('KKKKKPPPPP'+nbrMoisPm);
            $('#nbrMoisPmB').val(nbrMoisPm);                
        });  
     
    $("#btnValidationPlMoisB").click(function() {
         console.log('Plusieur moi B');
         var idPS = $('#idPS_RtrB').val();
         var idBoutique = $('#idBoutiqueB').val();
         var datePaiement = $('#datePaiementB').val();
         var heurePaiement = $('#heurePaiementB').val();
         var dateTransfertPm = $('#dateTransfertPmB').val();
         var nbrMoisPm = $('#nbrMoisPmB').val(); 
         var montantFixePayementM = $('#montantFixePayementMB').val(); 
         var montantFixePayementTotalPm = $('#montantFixePayementTotalPmB').val() ;
         var refTransfPm = $('#refTransfPm').val() ;
         var numTelPm = $('#numTelPm').val() ;
         var typeCompteMobile =  $("input[name='operateur']:checked").val() ;
         //alert(" mobbbbb "+typeCompteMobile);
         $.ajax({
                        url: "ajax/paiementPmAjax.php",
                        method: "POST",
                        data: {
                            operation: 3,
                            idPS: idPS,
                            idBoutique: idBoutique,
                            datePaiement: datePaiement,
                            heurePaiement: heurePaiement,
                            dateTransfert: dateTransfertPmB,
                            nbrMoisPm: nbrMoisPmB,
                            montantFixePayementM: montantFixePayementMB,
                            montantFixePayementTotalPm: montantFixePayementTotalPmB,
                            refTransfPm: refTransfPmB,
                            numTelPm: numTelPmB,
                            typeCompteMobile: typeCompteMobileB,
                        },
                        success: function(data) {
                           console.log(data);
                        },
                        error: function() {
                            alert("La requête ");
                        },
                        dataType: "text"
                    });
    });

});
//////////////////////IMPRIMER FACTURE PLUSIEURS MOIS

/********************************* AUtocompletion boutique********************************************/
$(function() {
    $("#nomBoutiquePm").keyup(function() {
        var query = $("#nomBoutiquePm").val();
        if (query.length > 0) {
            $.ajax({
                url: 'ajax/paiementPmAjax.php',
                method: 'POST',
                data: $("#formulaireInfo").serialize(),
                success: function(data) {
                    $("#reponseS").html(data);
                   // console.log(query);
                   // console.log(data);
                },
                dataType: 'text'
            });
        }

    });
    $(document).on('click', 'li[class="licR"]', function() {
        var designation = $(this).text();
        tab = designation.split('<>');
        var resultat = tab[1] + "  " + tab[0];
        $("#idBoutiquePm").val(tab[0]);
        $("#nomBoutiquePm").val(tab[1]);
        $("#montantFixePayementM").val(tab[2]);
        $("#reponseS").html('');
        /*$.ajax({
                url: 'ajax/paiementPmAjax.php',
                method: 'POST',
                data: {
                        operation: 2,
                        idBoutique: tab[0],
                },
                success: function(data) {
                    $("#reponseS").html(data);
                    console.log(query);
                    console.log(data);
                },
                dataType: 'text'
            });*/
    });
});