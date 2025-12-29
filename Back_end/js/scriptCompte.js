$(document).ready(function () {
    l=$('#l').val();
    //alert(l);
    $("#resumeOperation").load("ajax/compte/compteAjax.php",{"operation":"resumeTableauDeBord","l":l},  function (response, status, xhr){
        if(status == "success"){
            $(".loading-gif").hide(); 
            console.log(" Dans load testttttttttttt"); 
           // calcul_Montant_Fournisseur();
        }
    });
})
function popModCom(params) {
    $.post("compte/ajax/compteAjax.php",{
        idC:params,
        operation: "infoCompte"
    }, function(data, status){
        //alert("Data: " + data + "\nStatus: " + status);
        $('#imgmodifierCompte').modal('show'); 
    });
    
}
$("#btnModifierCompte").click(function(){
    $.post("compte/ajax/compteAjax.php",{
        operation: "modCompte"
    }, function(data, status){
        //alert("Data: " + data + "\nStatus: " + status);
        $('#addPersonnel').modal('hide'); 
    });
  });

$(document).ready(function() {  

    id=$('#inpt_C_id').val();
    dateDebut=$('#inpt_C_dateDebut').val();
    dateFin=$('#inpt_C_dateFin').val();
    console.log("testttttttttttt");
    query="";
    $("#listerOperations").load("ajax/compte/detailsCompteAjax.php",{"nbre_Entree":"","query":"","id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
        if(status == "success"){
            $(".loading-gif").hide(); 
            console.log(" Dans load testttttttttttt"); 
           // calcul_Montant_Fournisseur();
        }
    });
    
   $("#listerOperations").on( "change", "#slct_Nbre_ListerOperations", function (e){
        e.preventDefault();
        //query = $('#inpt_Search_ListerOperations').val();
        nbre_Entree = $('#slct_Nbre_ListerOperations').val() ;

        if (query.length == 0) {
            $("#listerOperations").load("ajax/compte/detailsCompteAjax.php",{"nbre_Entree":nbre_Entree,"query":"","id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
    
                if(status == "success"){
                    $('#slct_Nbre_ListerOperations').val(nbre_Entree) ;
                }
            });
        }else{
            $("#listerOperations").load("ajax/compte/detailsCompteAjax.php",{"nbre_Entree":nbre_Entree,"query":query,"id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                if(status == "success"){
                    $('#slct_Nbre_ListerOperations').val(nbre_Entree) ;     
                }
            });
        }

    });

    $("#listerOperations").on( "click", ".pagination a", function (e){

        e.preventDefault();
        page = $(this).attr("data-page"); //get page number from link
        query='';
        nbre_Entree = $('#slct_Nbre_ListerOperations').val() ;

        if (query.length == 0) {
            $("#listerOperations").load("ajax/compte/detailsCompteAjax.php",{"page":page,"nbre_Entree":nbre_Entree,"query":"","id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                if(status == "success"){
                    $('#slct_Nbre_ListerOperations').val(nbre_Entree) ;        
                }
            });
        }else{
            $("#listerOperations").load("ajax/compte/detailsCompteAjax.php",{"page":page,"nbre_Entree":nbre_Entree,"query":query,"id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                if(status == "success"){
                    $('#slct_Nbre_ListerOperations').val(nbre_Entree) ;       
                }
            });
        }

    });

    
});
