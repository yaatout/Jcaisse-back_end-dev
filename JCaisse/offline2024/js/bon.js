//$(document).ready(function() {  
    
     
   
    // $("#listerClients1").load("./load/client_ListerClients",{"nbre_Entree":"","query":""}, function (response, status, xhr){
    //     if(status == "success"){
    //          $(".tab_Depots a").css('pointer-events', 'auto');
    //          $(".tab_Dettes a").css('pointer-events', 'auto');
    //          $(".tab_Personnels a").css('pointer-events', 'auto');
    //     }     
    //  });  

    
    // $("#inpt_Search_ListerClients").on("keyup", function(e) {

    //     e.preventDefault();
    //     query = $('#inpt_Search_ListerClients').val();
    //     nbre_Entree = $('#slct_Nbre_ListerClients').val() ;

    //     var keycode = (e.keyCode ? e.keyCode : e.which);
    //         if (keycode == '13') {
    //             if (query.length > 0) {
    //                 $("#listerClients1" ).load("./load/client_ListerClients",{"nbre_Entree":nbre_Entree,"query":query}); //load initial records
    //             }else{

    //                 $("#listerClients1" ).load("./load/client_ListerClients",{"nbre_Entree":nbre_Entree,"query":""}); //load initial records
    //             }
    //         }else{
    //             setTimeout(() => {
    //                 if (query.length > 0) {
    //                     $("#listerClients1" ).load("./load/client_ListerClients",{"nbre_Entree":nbre_Entree,"query":query}); //load initial records
    //                 }else{
    //                     $("#listerClients1" ).load("./load/client_ListerClients",{"nbre_Entree":nbre_Entree,"query":""}); //load initial records
    //                 }

    //             }, 100);
    //         }

    // });

    // $("#slct_Nbre_ListerClients").on("change", function(e) {

    //     e.preventDefault();
    //     query = $('#inpt_Search_ListerClients').val();
    //     nbre_Entree = $('#slct_Nbre_ListerClients').val() ;

    //     if (query.length == 0) {
    //         $("#listerClients1" ).load("./load/client_ListerClients",{"nbre_Entree":nbre_Entree,"query":query}); //load initial records
    //     }else{

    //         $("#listerClients1" ).load("./load/client_ListerClients",{"nbre_Entree":nbre_Entree,"query":""}); //load initial records
    //     }

    // });

    // $("#listerClients1").on( "click", ".pagination a", function (e){

    //     e.preventDefault();
    //     page = $(this).attr("data-page"); //get page number from link

    //     query = $('#inpt_Search_ListerClients').val();
    //     nbre_Entree = $('#slct_Nbre_ListerClients').val() ;

    //     if (query.length == 0) {
    //         $("#listerClients1" ).load("./load/client_ListerClients",{"page":page,"nbre_Entree":nbre_Entree,"query":query}); //load initial records
    //     }else{

    //         $("#listerClients1" ).load("./load/client_ListerClients",{"page":page,"nbre_Entree":nbre_Entree,"query":""}); //load initial records
    //     }

    // });

    // $(".tab_Depots").click(function(e){

    //     e.preventDefault();
    //     nbre_Entree = $('#slct_Nbre_ListerDepots').val() ;

    //     $("#listerDepots").load("./load/client_ListerDepots",{"nbre_Entree":nbre_Entree,"query":""}, function (data){ });
    // });

    // $("#inpt_Search_ListerDepots").on("keyup", function(e) {

    //     e.preventDefault();
    //     query = $('#inpt_Search_ListerDepots').val();
    //     nbre_Entree = $('#slct_Nbre_ListerDepots').val() ;

    //     var keycode = (e.keyCode ? e.keyCode : e.which);
    //         if (keycode == '13') {
    //             if (query.length > 0) {
    //                 $("#listerDepots" ).load("./load/client_ListerDepots",{"nbre_Entree":nbre_Entree,"query":query}); //load initial records
    //             }else{

    //                 $("#listerDepots" ).load("./load/client_ListerDepots",{"nbre_Entree":nbre_Entree,"query":""}); //load initial records
    //             }
    //         }else{
    //             setTimeout(() => {
    //                 if (query.length > 0) {
    //                     $("#listerDepots" ).load("./load/client_ListerDepots",{"nbre_Entree":nbre_Entree,"query":query}); //load initial records
    //                 }else{
    //                     $("#listerDepots" ).load("./load/client_ListerDepots",{"nbre_Entree":nbre_Entree,"query":""}); //load initial records
    //                 }

    //             }, 100);
    //         }

    // });

    // $("#slct_Nbre_ListerDepots").on("change", function(e) {

    //     e.preventDefault();
    //     query = $('#inpt_Search_ListerDepots').val();
    //     nbre_Entree = $('#slct_Nbre_ListerDepots').val() ;

    //     if (query.length == 0) {
    //         $("#listerDepots" ).load("./load/client_ListerDepots",{"nbre_Entree":nbre_Entree,"query":query}); //load initial records
    //     }else{

    //         $("#listerDepots" ).load("./load/client_ListerDepots",{"nbre_Entree":nbre_Entree,"query":""}); //load initial records
    //     }

    // });

    // $("#listerDepots").on( "click", ".pagination a", function (e){

    //     e.preventDefault();
    //     page = $(this).attr("data-page"); //get page number from link

    //     query = $('#inpt_Search_ListerDepots').val();
    //     nbre_Entree = $('#slct_Nbre_ListerDepots').val() ;

    //     if (query.length == 0) {
    //         $("#listerDepots" ).load("./load/client_ListerDepots",{"page":page,"nbre_Entree":nbre_Entree,"query":query}); //load initial records
    //     }else{

    //         $("#listerDepots" ).load("./load/client_ListerDepots",{"page":page,"nbre_Entree":nbre_Entree,"query":""}); //load initial records
    //     }

    // });

    // $(".tab_Dettes").click(function(e){

    //     e.preventDefault();
    //     nbre_Entree = $('#slct_Nbre_ListerDettes').val() ;

    //     $("#listerDettes").load("./load/client_ListerDettes",{"nbre_Entree":nbre_Entree,"query":""}, function (data){ }); 
    
    // });

    // $("#inpt_Search_ListerDettes").on("keyup", function(e) {

    //     e.preventDefault();
    //     query = $('#inpt_Search_ListerDettes').val();
    //     nbre_Entree = $('#slct_Nbre_ListerDettes').val() ;

    //     var keycode = (e.keyCode ? e.keyCode : e.which);
    //         if (keycode == '13') {
    //             if (query.length > 0) {
    //                 $("#listerDettes" ).load("./load/client_ListerDettes",{"nbre_Entree":nbre_Entree,"query":query}); //load initial records
    //             }else{

    //                 $("#listerDettes" ).load("./load/client_ListerDettes",{"nbre_Entree":nbre_Entree,"query":""}); //load initial records
    //             }
    //         }else{
    //             setTimeout(() => {
    //                 if (query.length > 0) {
    //                     $("#listerDettes" ).load("./load/client_ListerDettes",{"nbre_Entree":nbre_Entree,"query":query}); //load initial records
    //                 }else{
    //                     $("#listerDettes" ).load("./load/client_ListerDettes",{"nbre_Entree":nbre_Entree,"query":""}); //load initial records
    //                 }

    //             }, 100);
    //         }

    // });

    // $("#slct_Nbre_ListerDettes").on("change", function(e) {

    //     e.preventDefault();
    //     query = $('#inpt_Search_ListerDettes').val();
    //     nbre_Entree = $('#slct_Nbre_ListerDettes').val() ;

    //     if (query.length == 0) {
    //         $("#listerDettes" ).load("./load/client_ListerDettes",{"nbre_Entree":nbre_Entree,"query":query}); //load initial records
    //     }else{

    //         $("#listerDettes" ).load("./load/client_ListerDettes",{"nbre_Entree":nbre_Entree,"query":""}); //load initial records
    //     }

    // });

    // $("#listerDettes").on( "click", ".pagination a", function (e){

    //     e.preventDefault();
    //     page = $(this).attr("data-page"); //get page number from link

    //     query = $('#inpt_Search_ListerDettes').val();
    //     nbre_Entree = $('#slct_Nbre_ListerDettes').val() ;

    //     if (query.length == 0) {
    //         $("#listerDettes" ).load("./load/client_ListerDettes",{"page":page,"nbre_Entree":nbre_Entree,"query":query}); //load initial records
    //     }else{

    //         $("#listerDettes" ).load("./load/client_ListerDettes",{"page":page,"nbre_Entree":nbre_Entree,"query":""}); //load initial records
    //     }

    // });

    // $(".tab_Personnels").click(function(e){

    //     e.preventDefault();
    //     nbre_Entree = $('#slct_Nbre_ListerPersonnels').val() ;

    //     $("#listerPersonnels").load("./load/client_ListerPersonnels",{"nbre_Entree":nbre_Entree,"query":""}, function (data){ });
    
    // });

    // $("#inpt_Search_ListerPersonnels").on("keyup", function(e) {

    //     e.preventDefault();
    //     query = $('#inpt_Search_ListerPersonnels').val();
    //     nbre_Entree = $('#slct_Nbre_ListerPersonnels').val() ;

    //     var keycode = (e.keyCode ? e.keyCode : e.which);
    //         if (keycode == '13') {
    //             if (query.length > 0) {
    //                 $("#listerPersonnels" ).load("./load/client_ListerPersonnels",{"nbre_Entree":nbre_Entree,"query":query}); //load initial records
    //             }else{

    //                 $("#listerPersonnels" ).load("./load/client_ListerPersonnels",{"nbre_Entree":nbre_Entree,"query":""}); //load initial records
    //             }
    //         }else{
    //             setTimeout(() => {
    //                 if (query.length > 0) {
    //                     $("#listerPersonnels" ).load("./load/client_ListerPersonnels",{"nbre_Entree":nbre_Entree,"query":query}); //load initial records
    //                 }else{
    //                     $("#listerPersonnels" ).load("./load/client_ListerPersonnels",{"nbre_Entree":nbre_Entree,"query":""}); //load initial records
    //                 }

    //             }, 100);
    //         }

    // });

    // $("#slct_Nbre_ListerPersonnels").on("change", function(e) {

    //     e.preventDefault();
    //     query = $('#inpt_Search_ListerPersonnels').val();
    //     nbre_Entree = $('#slct_Nbre_ListerPersonnels').val() ;

    //     if (query.length == 0) {
    //         $("#listerPersonnels" ).load("./load/client_ListerPersonnels",{"nbre_Entree":nbre_Entree,"query":query}); //load initial records
    //     }else{

    //         $("#listerPersonnels" ).load("./load/client_ListerPersonnels",{"nbre_Entree":nbre_Entree,"query":""}); //load initial records
    //     }

    // });

    // $("#listerPersonnels").on( "click", ".pagination a", function (e){

    //     e.preventDefault();
    //     page = $(this).attr("data-page"); //get page number from link

    //     query = $('#inpt_Search_ListerPersonnels').val();
    //     nbre_Entree = $('#slct_Nbre_ListerPersonnels').val() ;

    //     if (query.length == 0) {
    //         $("#listerPersonnels" ).load("./load/client_ListerPersonnels",{"page":page,"nbre_Entree":nbre_Entree,"query":query}); //load initial records
    //     }else{

    //         $("#listerPersonnels" ).load("./load/client_ListerPersonnels",{"page":page,"nbre_Entree":nbre_Entree,"query":""}); //load initial records
    //     }

    // });

    // $(".tab_Archives").click(function(e){

    // });
    

//})


var table='<table class="table table-striped contents display tabStock" id="tableStock" border="1">'+

    '<thead>'+

        '<tr>'+
            '<th style="width: 15%;">Nom</th>'+
            '<th style="width: 15%;">Prenom</th>'+
            '<th style="width: 15%;">Adresse</th>'+
            '<th style="width: 15%;">Telephone</th>'+
            '<th style="width: 16%;">Montant-à-Verser</th>'+
            
            
        '</tr>'+

    '</thead>'+

    '<tbody>'+
    
        

        
    '</tbody>'+

'</table>';
document.getElementById('listerClients').innerHTML=table

clients=JSON.parse(localStorage.getItem("Client"));
if(clients){
    //alert(clients)
    for(var i=0; i<clients.length; i++){        
        if(clients[i].solde > 0){
            var l="<tr>" +
        
                "<td>" + clients[i].prenom + "</td>" +

                "<td>"+clients[i].nom+"</td>" +

                "<td>"+clients[i].adresse+"</td>" +

                "<td>"+clients[i].telephone +"</td>" +

                "<td><span class='alert-danger'>"+clients[i].solde+" <a href='bonPclient.html' onclick='getIdClient("+clients[i].idClient+")'> Details</a> </span></td>"+
                
            "<tr>";  
            $("#tableStock").prepend(l);
            

        }else{
            if(clients[i].solde < 0){
                var l="<tr>" +
            
                    "<td>" + clients[i].prenom + "</td>" +
    
                    "<td>"+clients[i].nom+"</td>" +
    
                    "<td>"+clients[i].adresse+"</td>" +
    
                    "<td>"+clients[i].telephone +"</td>" +
    
                    "<td><span class='alert-success'>"+clients[i].solde+" <a href='bonPclient.html' onclick='getIdClient("+clients[i].idClient+")'> Details</a> </span></td>"+
                    
                "<tr>"; 
                $("#tableStock").prepend(l);
    
            }

        }
         
    }

    function getIdClient(id){
        date=new Date();
        dateFin=date.toLocaleDateString("es-CL");

        boutiques=JSON.parse(localStorage.getItem("Boutique"));
        searchBoutique=boutiques.find(b=>b.offline == 1);
        datecreation=searchBoutique.datecreation
        debut=datecreation.split('-')
        dateDebut=debut[2]+'-'+debut[1]+'-'+debut[0];
        localStorage.setItem('Periode', dateDebut+' au '+dateFin)
        localStorage.setItem('idClient', id)
        return id;
        
    }

}

