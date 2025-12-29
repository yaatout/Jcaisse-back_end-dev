$(document).ready(function () {
    l=$('#l').val();
    //alert(l);
    $("#resumeOperation").load("compte/ajax/compteAjax.php",{"operation":"resumeTableauDeBord","l":l},  function (response, status, xhr){
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
        console.log(data);
        tab = data.split('<>');
        $('#inputIModH').val(tab[0]) ;
        $('#inputnomMod').val(tab[1]) ;
        $('#selectMod').val(tab[2]) ;
        $('#numComMod').val(tab[3]) ;
        $('#imgmodifierCompte').modal('show'); 
        

    });
    
}
$("#btnModifierCompte").click(function(){
    $.post("compte/ajax/compteAjax.php",{
        operation: "btnModifierCompte",
        idCompte:$('#inputIModH').val() ,
        nomCompte:$('#inputnomMod').val() ,
        typeCompte:$('#selectMod').val() ,
        numeroCompte:$('#numComMod').val() 
    }, function(data, status){
        //alert("Data: " + data + "\nStatus: " + status);
        $('#imgmodifierCompte').modal('hide'); 
    });
});
function popSupCom(params) {
    $.post("compte/ajax/compteAjax.php",{
        idC:params,
        operation: "infoCompte"
    }, function(data, status){
        console.log(data);
        tab = data.split('<>');
        $('#idCSup').val(tab[0]) ;
        $('#inputnomCSup').val(tab[1]) ;
        $('#inputTypCSup').val(tab[2]) ;
        $('#inputNumCSup').val(tab[3]) ;
        $('#imgsuprimerCompte').modal('show'); 
        

    });
    
}
$("#btnSuprimerCompte").click(function(){
    $.post("compte/ajax/compteAjax.php",{
        operation: "btnSuprimerCompte",
        idCompte:$('#idCSup').val() 
    }, function(data, status){
        //alert("Data: " + data + "\nStatus: " + status);
        $('#imgsuprimerCompte').modal('hide'); 
    });
  });
$(document).ready(function() {  

    id=$('#inpt_C_id').val();
    dateDebut=$('#inpt_C_dateDebut').val();
    dateFin=$('#inpt_C_dateFin').val();
    query="";
    $("#listerOperations").load("compte/ajax/detailsCompteAjax.php",{"nbre_Entree":"","query":"","id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
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
            $("#listerOperations").load("compte/ajax/detailsCompteAjax.php",{"nbre_Entree":nbre_Entree,"query":"","id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
    
                if(status == "success"){
                    $('#slct_Nbre_ListerOperations').val(nbre_Entree) ;
                }
            });
        }else{
            $("#listerOperations").load("compte/ajax/detailsCompteAjax.php",{"nbre_Entree":nbre_Entree,"query":query,"id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
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
            $("#listerOperations").load("compte/ajax/detailsCompteAjax.php",{"page":page,"nbre_Entree":nbre_Entree,"query":"","id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                if(status == "success"){
                    $('#slct_Nbre_ListerOperations').val(nbre_Entree) ;        
                }
            });
        }else{
            $("#listerOperations").load("compte/ajax/detailsCompteAjax.php",{"page":page,"nbre_Entree":nbre_Entree,"query":query,"id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                if(status == "success"){
                    $('#slct_Nbre_ListerOperations').val(nbre_Entree) ;       
                }
            });
        }

    });
});
