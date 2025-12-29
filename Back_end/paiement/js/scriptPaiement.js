
$("#btnReferencetransfertsOrange").click(function(){
    $.ajax({
        url: 'paiement/ajax/operationPaiementAjax.php',
        method: 'POST',
        data: $("#formulaireVersementOM").serialize(),
        success: function(data) { 
            //alert('ggg');
            $('#ReferencetransfertsOrange').modal('hide'); 
        },
        dataType: 'text'
    }); 
});
$("#btnReferencetransfertsWave").click(function(){
    $.ajax({
        url: 'paiement/ajax/operationPaiementAjax.php',
        method: 'POST',
        data: $("#formulaireVersementWav").serialize(),
        success: function(data) { 
            //alert('ggg'+data);
            $('#ReferencetransfertsWave').modal('hide'); 
        },
        dataType: 'text'
    }); 
});

$("#btnRecalcule").click(function(){
    $.post("paiement/ajax/operationPaiementAjax.php",{
        operation: "recalcule"
    }, function(data, status){
        alert("Data: " + data + "\nStatus: " + status);
        $('#addPersonnel').modal('hide'); 
    });
  });

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
                        url: "paiement/ajax/paiementPmAjax.php",
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
                            //console.log(data);
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
            $('#nbrMoisPm').val(nbrMoisPm);                
        }); 
    $("#montantFixePayementTotalPmB").focusout( function(){
            console.log('Entrer');
            var montantTotal=$('#montantFixePayementTotalPmB').val();
            var montant=$('#montantFixePayementMB').val();
            var nbrMoisPm=montantTotal/montant;
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
                           //console.log(data);
                        },
                        error: function() {
                            alert("La requête ");
                        },
                        dataType: "text"
                    });
    });

});
/********************************* AUtocompletion boutique********************************************/
$(function() {
    //alert('fffff');
    $("#nomBoutiquePm").keyup(function() {
        var query = $("#nomBoutiquePm").val();
        if (query.length > 0) {
            $.ajax({
                url: 'paiement/ajax/paiementPmAjax.php',
                method: 'POST',
                data: $("#formulaireInfo").serialize(),
                success: function(data) {
                    $("#reponseS").html(data);
                    //console.log(query);
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

//////////////////////IMPRIMER FACTURE PLUSIEURS MOIS
function imprimerPPM(params) {
    $.ajax({
                        url: "ajax/paiementPmAjax.php",
                        method: "POST",
                        data: {
                            operation: "imprimerFactPPM",
                            params: params
                        },
                        success: function(data) {
                            console.log('suceeeeeeeeeeeee'+data);
                            tab=data.split('<>');
                            nomC=tab[0];
                            adrC=tab[1];
                            telC=tab[2];

                            mMens=tab[3];
                            mTotal=tab[4];
                            var dPaiement=tab[5];
                            var paiementParts = dPaiement.split("-");
                            dPaiement=paiementParts[2]+'-'+paiementParts[1]+'-'+paiementParts[0];

                            var dDebut=tab[6];
                            var debutParts = dDebut.split("-");
                            dDebut=debutParts[2]+'-'+debutParts[1]+'-'+debutParts[0];

                            var dFin=tab[7];
                            var finParts = dFin.split("-");
                            dFin=finParts[2]+'-'+finParts[1]+'-'+finParts[0];
                            console.log(dFin);
                            nbrM=tab[8];

                            nomY=tab[9];
                            adrY=tab[10];
                            telY=tab[11];

                            date=new Date();
                            var datejour=date.toLocaleDateString("es-CL");

                            var logo='data:image/png;base64,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';
                            var docDefinition={
                                content: [
                                        /* {
                                            columns: [
                                                {
                                                    image: logo,
                                                    width: 70,
                                                    height: 70
                                                    
                                                    
                                                },
                                                {
                                                   /* stack:[
                                                        
                                                        {
                                                            text: nomY, fontSize: 18, bold:true,
                                                            
                                                        },
                                                         {	
                                                            text:[
                                                                {text: 'Adresse :', fontSize: 12, bold: true},
                                                                {text: adrY+'\n', fontSize: 11, bold: false},
                                                                {text: 'Téléphone :', fontSize: 12, bold: true},
                                                                {text: telY, fontSize: 11, bold: false},
                                                            ]
                                                        } ///


                                                    ],
                                                    width:320

                                                },

                                                {
                                                    stack:[
                                                        
                                                        {
                                                            text: `Date: ${new Date().toLocaleDateString("es-CL")}`,
                                                                
                                                            bold: true,fontSize: 11
                                                        },
                                                        '\n',
                                                        {
                                                            
                                                            style: 'tableExample',
                                                            table: {
                                                                widths: [130],
                                                                body: [
                                                                    [
                                                                        {
                                                                            text: 'FACTURE: #'+params, bold:true,
                                                                            fillColor: '#cccccc',border: [true, true, true, true], 
                                                                            alignment: 'center',fontSize: 11
                                                                        
                                                                        },
                                                                        
                                                                    ],
                                                                    
                                                                ]
                                                            }
                                                        },
                                                        '\n',
                                                        {
                                                            text:[
                                                                {text: 'Nom : ', fontSize: 12, bold: true},
                                                                {text: nomC+'\n', fontSize: 12, bold: false},
                                                                {text: 'Adresse : ', fontSize: 12, bold: true},
                                                                {text: adrC+'\n', fontSize: 12, bold: false},
                                                                {text: 'Téléphone : ', fontSize: 12, bold: true},
                                                                {text: telC+'\n\n', fontSize: 12, bold: false},
                                                            ]
                                                        },
                                                        '\n',

                                                    ],
                                                    
                                                },

                                            ],
                                            
                                        }, */
                                        {
                                            style: 'tableExample',
                                            table: {
                                                headerRows: 1,
                                                body: [
                                                    [
                                                        
                                                        [
                                                            {
                                                                image: logo,
                                                                width: 70,
                                                                height: 70
                                                            },
                                                            {
                                                            text: nomY, fontSize: 18, bold:true,
                                                            
                                                            },
                                                            {	
                                                                text:[
                                                                    {text: 'Adresse :', fontSize: 12, bold: true},
                                                                    {text: adrY+'\n', fontSize: 11, bold: false},
                                                                    {text: 'Téléphone :', fontSize: 12, bold: true},
                                                                    {text: telY, fontSize: 11, bold: false},
                                                                ]
                                                            } 
                                                        ],
                                                        {
                                                            text:'TTTTTTT', color: 'white'
                                                        },
                                                        {
                                                            //
                                                            stack:[
                                                        
                                                                    {
                                                                        text: `Date: ${new Date().toLocaleDateString("es-CL")}`,
                                                                            
                                                                        bold: true,fontSize: 11
                                                                    },
                                                                    '\n',
                                                                    {
                                                                        
                                                                        style: 'tableExample',
                                                                        table: {
                                                                            widths: [200],
                                                                            body: [
                                                                                [
                                                                                    {
                                                                                        text: 'FACTURE: #'+params, bold:true,
                                                                                        fillColor: '#cccccc',border: [true, true, true, true], 
                                                                                        alignment: 'center',fontSize: 11
                                                                                    
                                                                                    },
                                                                                    
                                                                                ],
                                                                                
                                                                            ]
                                                                        }
                                                                    },
                                                                    '\n',
                                                                    {
                                                                        text:[
                                                                            {text: 'Période : ', fontSize: 12, bold: true},
                                                                            {text: dDebut+' au '+dFin+'\n', fontSize: 12, bold: true},
                                                                            {text: 'Nom : ', fontSize: 12, bold: true},
                                                                            {text: nomC+'\n', fontSize: 12, bold: false},
                                                                            {text: 'Adresse : ', fontSize: 12, bold: true},
                                                                            {text: adrC+'\n', fontSize: 12, bold: false},
                                                                            {text: 'Téléphone : ', fontSize: 12, bold: true},
                                                                            {text: telC+'\n\n', fontSize: 12, bold: false},
                                                                        ]
                                                                    },
                                                                    '\n',

                                                                ],
                                                            //
                                                        }, 
                                                    ],
                                                ]
                                            },
                                            layout: 'noBorders'
                                        },
                                        '\n',
                                        {
                                            text:'PAIEMENT PLUSIEURS MOIS \n\n', bold:true, alignment: 'center',
                                        },
                                        {
                                            style: 'tableExample',
                                            //height:2000,
                                            table: {
                                                widths: ['*', 'auto', 'auto', 'auto'],
                                                heights: [0, 0, 410, 0],
                                                body: [
                                                    
                                                    [{text:'DESIGNATION', bold:true, alignment: 'center'}, {text:' QUANTITE ',bold:true, alignment: 'center'}, {text:' PRIX UNITE ',bold:true, alignment: 'center'}, {text:' PRIX TOTAL ', bold:true,fontSize: 11, alignment: 'center'}],                                                      
                                                    [{text:'Service Jcaisse mensuel'},{text:nbrM, alignment: 'center'},{text:mMens, alignment: 'center'},{text:mTotal, alignment: 'center'}],
                                                    [{text:''},{text:''},{text:''},{text:''}],
                                                    [{ text: 'Net a payer TTC : '+mTotal+' FCFA', fillColor: '#cccccc',fontSize: 11,border: [true, true, true, true], alignment: 'center', bold: true}, {text: '',border: [false, true, false, true]}, {text: '',border: [false, true, false, true]}, {text: '',border: [false, true, true, true]}],
                                                    [{text: '',border: [false, false, false, false]}, {text: '',border: [false, false, false, false]}, { text: 'Total Prix TTC :', fillColor: '#cccccc',fontSize: 10,bold: true},{text: ''+mTotal+' FCFA',fontSize: 10}]

                                                ],
                                            },
                                            
                                            
                                        },
                                        /* {text: '', style: 'subheader'},
                                        {
                                            style: 'tableExample',
                                            table: {
                                                heights: [20, 20, 20],
                                                widths: [100, 100],
                                                body: [
                                                    [ {text: 'Payé le : ', fillColor: '#cccccc', fontSize: 12, bold: true}, {text: paiementParts[2]+'-'+paiementParts[1]+'-'+paiementParts[0], fontSize: 12, bold: false, alignment: 'center'}],
                                                    [ {text: 'Date debut : ', fillColor: '#cccccc', fontSize: 12, bold: true} , {text: debutParts[2]+'-'+debutParts[1]+'-'+debutParts[0], fontSize: 12, bold: false, alignment: 'center'}],
                                                    [ {text: 'Date Fin : ',  fillColor: '#cccccc',fontSize: 12, bold: true}, {text:  finParts[2]+'-'+finParts[1]+'-'+finParts[0], fontSize: 12, bold: false, alignment: 'center'}]
                                                ]
                                            }
                                        }, */
                                        '\n','\n',
                                        {
                                            text: nomY,fontSize: 11, bold:true, alignment:'center'
                                        },
                                        '\n',
                                        {
                                            text: 'Adresse: '+adrY+'\n'+'Telephone: '+telY,fontSize: 11,alignment:'center'   
                                        }
                                        ],
                            }
                            pdfMake.createPdf(docDefinition).open();
                        },
                        error: function() {
                            alert("La requête ");
                        },
                        dataType: "text"
                    });
}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
///*************** Début paiement mois prec***************/
$(document).ready(function() {

    nbEntrePrec = $('#nbEntrePrec').val();

    $("#resultsPrec" ).load( "paiement/ajax/listerMoisPrecAjax.php",{"operation":1,"nbEntrePrec":nbEntrePrec,"query":"","cb":""}); //load initial records
    
    //executes code below when user click on pagination links
    $("#resultsPrec").on( "click", ".pagination a", function (e){
    // $("#resultsPrec").on( "click", function (e){
        e.preventDefault();
        $(".loading-div").show(); //show loading element
        // page = page+1; //get page number from link
        page = $(this).attr("data-page"); //get page number from link
        //  alert(page)

        nbEntrePrec = $('#nbEntrePrec').val()
        query = $('#searchInputPrec').val();

        if (query.length == 0) {
            $("#resultsPrec" ).load( "paiement/ajax/listerMoisPrecAjax.php",{"page":page,"operation":1,"nbEntrePrec":nbEntrePrec,"query":"","cb":""}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });
                
        }else{
            $("#resultsPrec" ).load( "paiement/ajax/listerMoisPrecAjax.php",{"page":page,"operation":1,"nbEntrePrec":nbEntrePrec,"query":query,"cb":""}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });
        }
        // $("#resultsPrec").load("ajax/listerProduitAjax.php",{"page":page,"operation":1}
    });
});
/********   RECHERCHE et NOMBRE D'ENTREES   ******/
$(document).ready(function() {

    $('#searchInputPrec').on("keyup", function(e) {
        e.preventDefault();
        
        query = $('#searchInputPrec').val()
        nbEntrePrec = $('#nbEntrePrec').val()

        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == '13') {
            // alert(1111)
            t = 1; // code barre
            
            if (query.length > 0) {
                
                $("#resultsPrec" ).load( "paiement/ajax/listerMoisPrecAjax.php",{"operation":3,"nbEntrePrec":nbEntrePrec,"query":query,"cb":t}); //load initial records
            }else{
                $("#resultsPrec" ).load( "paiement/ajax/listerMoisPrecAjax.php",{"operation":3,"nbEntrePrec":nbEntrePrec,"query":"","cb":t}); //load initial records
            }
        }else{
            // alert(2222)
            t = 0; // no code barre
            setTimeout(() => {
                if (query.length > 0) {
                    
                    $("#resultsPrec" ).load( "paiement/ajax/listerMoisPrecAjax.php",{"operation":3,"nbEntrePrec":nbEntrePrec,"query":query,"cb":t}); //load initial records
                }else{
                    $("#resultsPrec" ).load( "paiement/ajax/listerMoisPrecAjax.php",{"operation":3,"nbEntrePrec":nbEntrePrec,"query":"","cb":t}); //load initial records
                }
            }, 100);
        }
    });
    
    $('#nbEntrePrec').on("change", function(e) {
        e.preventDefault();

        nbEntrePrec = $('#nbEntrePrec').val()
        query = $('#searchInputPrec').val();

        if (query.length == 0) {
            $("#resultsPrec" ).load( "paiement/ajax/listerMoisPrecAjax.php",{"operation":4,"nbEntrePrec":nbEntrePrec,"query":"","cb":""}); //load initial records
                
        }else{
            $("#resultsPrec" ).load( "paiement/ajax/listerMoisPrecAjax.php",{"operation":4,"nbEntrePrec":nbEntrePrec,"query":query,"cb":""}); //load initial records
        }
            
        // $("#resultsPrec" ).load( "ajax/listerProduitAjax.php",{"operation":4,"nbEntreeBC":nbEntreeBC}); //load initial records
    });

});   

function validerPaiementOMPop(a) {
    $.ajax({
        url:"paiement/ajax/operationPaiementAjax.php",
        method:"POST",
        data:{
            operation:"chercherPS",
            a:a,
        },
        success: function (data) { 
            tab=data.split('<>');    
            $('#idPS_Rtr').val(a);
            $('#dateTransOMValidation').val(tab[8]);
            $('#montantFPaiementOMValidation').val(tab[3]);
            $('#refTransf').val(tab[17]);
            $('#labelBS').val(tab[5]);
            $('#validerPaiementOMPop').modal('show');
        },
        error: function() {
            alert("La requête "); },
        dataType:"text"
    });   
}
function savePaiementOM( ) { 
    $.ajax({
        url: 'paiement/ajax/operationPaiementAjax.php',
        method: 'POST',
        data: $("#validerPaiementOMForm").serialize(),
        success: function(data) { 
            
            $('#validerPaiementOMPop').modal('hide'); 
        },
        dataType: 'text'
    }); 
}
function validerPaiementWavePop(a) {
    $.ajax({
        url:"paiement/ajax/operationPaiementAjax.php",
        method:"POST",
        data:{
            operation:"chercherPS",
            a:a,
        },
        success: function (data) { 
            tab=data.split('<>');    
            $('#i_w').val(a);
            $('#dateTransfertWavValid').val(tab[8]);
            $('#montantFixePayementWavValid').val(tab[3]);
            $('#refTransfWavValid').val(tab[17]);
            $('#validerPaiementWavePop').modal('show');
        },
        error: function() {
            alert("La requête "); },
        dataType:"text"
    });   
}
function savePaiementWave( ) { 
    $.ajax({
        url: 'paiement/ajax/operationPaiementAjax.php',
        method: 'POST',
        data: $("#validerPaiementWavForm").serialize(),
        success: function(data) { 
            $("#validerPaiementWavForm").submit()
            // $('#validerPaiementWavePop').modal('hide'); 
        },
        dataType: 'text'
    }); 
}
function pEnCours () {
    ////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////
    ///*************** Début modification categorie details boutique***************/
    $(document).ready(function() {

        nbEntreEnCours = $('#nbEntreEnCours').val();

        $("#resultsEnCours" ).load( "paiement/ajax/listerMoisEnCoursAjax.php",{"operation":1,"nbEntreEnCours":nbEntreEnCours,"query":"","cb":""}); //load initial records
        
        //executes code below when user click on pagination links
        $("#resultsEnCours").on( "click", ".pagination a", function (e){
        // $("#resultsEnCours").on( "click", function (e){
            e.preventDefault();
            $(".loading-div").show(); //show loading element
            // page = page+1; //get page number from link
            page = $(this).attr("data-page"); //get page number from link
            //  alert(page)

            nbEntreEnCours = $('#nbEntreEnCours').val()
            query = $('#searchInputEnCours').val();

            if (query.length == 0) {
                $("#resultsEnCours" ).load( "paiement/ajax/listerMoisEnCoursAjax.php",{"page":page,"operation":1,"nbEntreEnCours":nbEntreEnCours,"query":"","cb":""}, function(){ //get content from PHP page
                    $(".loading-div").hide(); //once done, hide loading element
                });
                    
            }else{
                $("#resultsEnCours" ).load( "paiement/ajax/listerMoisEnCoursAjax.php",{"page":page,"operation":1,"nbEntreEnCours":nbEntreEnCours,"query":query,"cb":""}, function(){ //get content from PHP page
                    $(".loading-div").hide(); //once done, hide loading element
                });
            }
            // $("#resultsEnCours").load("ajax/listerProduitAjax.php",{"page":page,"operation":1}
        });
    });
    /********   RECHERCHE et NOMBRE D'ENTREES   ******/
    $(document).ready(function() {

        $('#searchInputEnCours').on("keyup", function(e) {
            e.preventDefault();
            
            query = $('#searchInputEnCours').val()
            nbEntreEnCours = $('#nbEntreEnCours').val()

            var keycode = (e.keyCode ? e.keyCode : e.which);
            if (keycode == '13') {
                // alert(1111)
                t = 1; // code barre
                
                if (query.length > 0) {
                    
                    $("#resultsEnCours" ).load( "paiement/ajax/listerMoisEnCoursAjax.php",{"operation":3,"nbEntreEnCours":nbEntreEnCours,"query":query,"cb":t}); //load initial records
                }else{
                    $("#resultsEnCours" ).load( "paiement/ajax/listerMoisEnCoursAjax.php",{"operation":3,"nbEntreEnCours":nbEntreEnCours,"query":"","cb":t}); //load initial records
                }
            }else{
                // alert(2222)
                t = 0; // no code barre
                setTimeout(() => {
                    if (query.length > 0) {
                        
                        $("#resultsEnCours" ).load( "paiement/ajax/listerMoisEnCoursAjax.php",{"operation":3,"nbEntreEnCours":nbEntreEnCours,"query":query,"cb":t}); //load initial records
                    }else{
                        $("#resultsEnCours" ).load( "paiement/ajax/listerMoisEnCoursAjax.php",{"operation":3,"nbEntreEnCours":nbEntreEnCours,"query":"","cb":t}); //load initial records
                    }
                }, 100);
            }
        });
        
        $('#nbEntreEnCours').on("change", function(e) {
            e.preventDefault();

            nbEntreEnCours = $('#nbEntreEnCours').val()
            query = $('#searchInputEnCours').val();

            if (query.length == 0) {
                $("#resultsEnCours" ).load( "paiement/ajax/listerMoisEnCoursAjax.php",{"operation":4,"nbEntreEnCours":nbEntreEnCours,"query":"","cb":""}); //load initial records
                    
            }else{
                $("#resultsEnCours" ).load( "paiement/ajax/listerMoisEnCoursAjax.php",{"operation":4,"nbEntreEnCours":nbEntreEnCours,"query":query,"cb":""}); //load initial records
            }
        });

    });
}
function pPlusMois () {
    //alert("DDDDD");
    ///*************** Début modification HORS PARAMETRE details boutique***************/
    $(document).ready(function() {

        nbEntrePlusMois = $('#nbEntrePlusMois').val();
        $("#resultsPlusMois" ).load( "paiement/ajax/listerPPlusMoisAjax.php",{"operation":1,"nbEntrePlusMois":nbEntrePlusMois,"query":"","cb":""}); //load initial records
        
        //executes code below when user click on pagination links
        $("#resultsPlusMois").on( "click", ".pagination a", function (e){
        // $("#resultsPlusMois").on( "click", function (e){
            e.preventDefault();
            $(".loading-div").show(); //show loading element
            // page = page+1; //get page number from link
            page = $(this).attr("data-page"); //get page number from link
            //  alert(page)

            nbEntrePlusMois = $('#nbEntrePlusMois').val()
            query = $('#searchInputPlusMois').val();

            if (query.length == 0) {
                $("#resultsPlusMois" ).load( "paiement/ajax/listerPPlusMoisAjax.php",{"page":page,"operation":1,"nbEntrePlusMois":nbEntrePlusMois,"query":"","cb":""}, function(){ //get content from PHP page
                    $(".loading-div").hide(); //once done, hide loading element
                });
                    
            }else{
                $("#resultsPlusMois" ).load( "paiement/ajax/listerPPlusMoisAjax.php",{"page":page,"operation":1,"nbEntrePlusMois":nbEntrePlusMois,"query":query,"cb":""}, function(){ //get content from PHP page
                    $(".loading-div").hide(); //once done, hide loading element
                });
            }
            // $("#resultsPlusMois").load("ajax/listerProduitAjax.php",{"page":page,"operation":1}
        });
    });
    /********   RECHERCHE et NOMBRE D'ENTREES   ******/
    $(document).ready(function() {

        $('#searchInputPlusMois').on("keyup", function(e) {
            e.preventDefault();
            
            query = $('#searchInputPlusMois').val()
            nbEntrePlusMois = $('#nbEntrePlusMois').val()

            var keycode = (e.keyCode ? e.keyCode : e.which);
            if (keycode == '13') {
                // alert(1111)
                t = 1; // code barre
                
                if (query.length > 0) {
                    
                    $("#resultsPlusMois" ).load( "paiement/ajax/listerPPlusMoisAjax.php",{"operation":3,"nbEntrePlusMois":nbEntrePlusMois,"query":query,"cb":t}); //load initial records
                }else{
                    $("#resultsPlusMois" ).load( "paiement/ajax/listerPPlusMoisAjax.php",{"operation":3,"nbEntrePlusMois":nbEntrePlusMois,"query":"","cb":t}); //load initial records
                }
            }else{
                // alert(2222)
                t = 0; // no code barre
                setTimeout(() => {
                    if (query.length > 0) {
                        
                        $("#resultsPlusMois" ).load( "paiement/ajax/listerPPlusMoisAjax.php",{"operation":3,"nbEntrePlusMois":nbEntrePlusMois,"query":query,"cb":t}); //load initial records
                    }else{
                        $("#resultsPlusMois" ).load( "paiement/ajax/listerPPlusMoisAjax.php",{"operation":3,"nbEntrePlusMois":nbEntrePlusMois,"query":"","cb":t}); //load initial records
                    }
                }, 100);
            }
        });
        
        $('#nbEntrePlusMois').on("change", function(e) {
            e.preventDefault();

            nbEntrePlusMois = $('#nbEntrePlusMois').val()
            query = $('#searchInputPlusMois').val();

            if (query.length == 0) {
                $("#resultsPlusMois" ).load( "paiement/ajax/listerPPlusMoisAjax.php",{"operation":4,"nbEntrePlusMois":nbEntrePlusMois,"query":"","cb":""}); //load initial records
                    
            }else{
                $("#resultsPlusMois" ).load( "paiement/ajax/listerPPlusMoisAjax.php",{"operation":4,"nbEntrePlusMois":nbEntrePlusMois,"query":query,"cb":""}); //load initial records
            }
        });

    });
}
function pHors() {
    
}