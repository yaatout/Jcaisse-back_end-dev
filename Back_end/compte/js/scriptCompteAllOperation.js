$(document).ready(function () {
    //l=$('#l').val();
    //alert(l);
    $("#resumeAllOperation").load("compte/ajax/allOpCompteAjax.php",{"operation":"resumeTableauDeBord"},  function (response, status, xhr){
        if(status == "success"){
            $(".loading-gif").hide(); 
            console.log(" Dans load testttttTTTTT"); 
           // calcul_Montant_Fournisseur();
        }
    });
})

$(document).ready(function() {  

    id=$('#inpt_C_id').val();
    dateDebut=$('#inpt_C_dateDebut').val();
    dateFin=$('#inpt_C_dateFin').val();
    query="";
    $("#listerAllOperations").load("compte/ajax/allOpCompteAjax.php",{"nbre_Entree":"","query":"","id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
        if(status == "success"){
            $(".loading-gif").hide(); 
            console.log(" Dans load PPPPPP"); 
           // calcul_Montant_Fournisseur();
        }
    });
    
   $("#listerAllOperations").on( "change", "#slct_Nbre_listerAllOperations", function (e){
        e.preventDefault();
        //query = $('#inpt_Search_listerAllOperations').val();
        nbre_Entree = $('#slct_Nbre_listerAllOperations').val() ;

        if (query.length == 0) {
            $("#listerAllOperations").load("compte/ajax/allOpCompteAjax.php",{"nbre_Entree":nbre_Entree,"query":"","id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
    
                if(status == "success"){
                    $('#slct_Nbre_listerAllOperations').val(nbre_Entree) ;
                }
            });
        }else{
            $("#listerAllOperations").load("compte/ajax/allOpCompteAjax.php",{"nbre_Entree":nbre_Entree,"query":query,"id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                if(status == "success"){
                    $('#slct_Nbre_listerAllOperations').val(nbre_Entree) ;     
                }
            });
        }

    });

    $("#listerAllOperations").on( "click", ".pagination a", function (e){

        e.preventDefault();
        page = $(this).attr("data-page"); //get page number from link
        query='';
        nbre_Entree = $('#slct_Nbre_listerAllOperations').val() ;

        if (query.length == 0) {
            $("#listerAllOperations").load("compte/ajax/allOpCompteAjax.php",{"page":page,"nbre_Entree":nbre_Entree,"query":"","id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                if(status == "success"){
                    $('#slct_Nbre_listerAllOperations').val(nbre_Entree) ;        
                }
            });
        }else{
            $("#listerAllOperations").load("compte/ajax/allOpCompteAjax.php",{"page":page,"nbre_Entree":nbre_Entree,"query":query,"id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                if(status == "success"){
                    $('#slct_Nbre_listerAllOperations').val(nbre_Entree) ;       
                }
            });
        }

    });
});
