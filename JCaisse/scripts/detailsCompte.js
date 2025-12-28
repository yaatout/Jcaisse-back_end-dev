$(document).ready(function() {  

    id=$('#inpt_Compte_id').val();
    dateDebut=$('#inpt_Compte_dateDebut').val();
    dateFin=$('#inpt_Compte_dateFin').val();

    $("#listerOperations").load("datatables/detailsCompte.php",{"nbre_Entree":"","query":"","id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
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
                    $("#listerOperations").load("datatables/detailsCompte.php",{"nbre_Entree":nbre_Entree,"query":query,"id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                        if(status == "success"){
                                    
                        }
                    });
                }else{
                    $("#listerOperations").load("datatables/detailsCompte.php",{"nbre_Entree":nbre_Entree,"query":"","id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                        if(status == "success"){
                                    
                        }
                    });
                }
            }else{
                setTimeout(() => {
                    if (query.length > 0) {
                        $("#listerOperations").load("datatables/detailsCompte.php",{"nbre_Entree":nbre_Entree,"query":query,"id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                            if(status == "success"){
                                        
                            }
                        });
                    }else{
                        $("#listerOperations").load("datatables/detailsCompte.php",{"nbre_Entree":nbre_Entree,"query":"","id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
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
            $("#listerOperations").load("datatables/detailsCompte.php",{"nbre_Entree":nbre_Entree,"query":"","id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                if(status == "success"){
                    $('#slct_Nbre_ListerOperations').val(nbre_Entree) ;
                }
            });
        }else{
            $("#listerOperations").load("datatables/detailsCompte.php",{"nbre_Entree":nbre_Entree,"query":query,"id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
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
            $("#listerOperations").load("datatables/detailsCompte.php",{"page":page,"nbre_Entree":nbre_Entree,"query":"","id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                if(status == "success"){
                    $('#slct_Nbre_ListerOperations').val(nbre_Entree) ;        
                }
            });
        }else{
            $("#listerOperations").load("datatables/detailsCompte.php",{"page":page,"nbre_Entree":nbre_Entree,"query":query,"id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                if(status == "success"){
                    $('#slct_Nbre_ListerOperations').val(nbre_Entree) ;       
                }
            });
        }

    });

    $(function(){
        annuler_Mouvement= function (idMouvement,montant)  {
          $('#inpt_annl_Mouvement_idFournisseur').val(idMouvement);
          $('#span_annl_Mouvement_Numero').text(idMouvement);
          $('#span_annl_Mouvement_Montant').text(montant);
          $('#annuler_Operation').modal('show');
        }
    });

    function isFrais() {
        var avecFrais = document.getElementById("avecFrais");
        var text = document.getElementById("text");
        if (avecFrais.checked == true){
          text.style.display = "block";
        } else {
          $("#inpF").val(0 );
          text.style.display = "none";
        }
    }
      
    function isFrais2() {
        var avecFrais = document.getElementById("avecFrais2");
        var text = document.getElementById("text");
        if (avecFrais.checked == true){
            text.style.display = "block";
        } else {
            $("#inpF").val(0 );
            text.style.display = "none";
        }
    }

    $(".myRadio input[type=radio]").on("change", function() {
        entreCompte = $(this).val()
        // alert(entreCompte)
        if (entreCompte == 'option1') {
        // if (compte == '1') {
            // alert('ci if bi')
            $("#divCompteDest").attr('style','display:block')
            $("#divNumDest").attr('style','display:none')
            $("#compteDest").attr('required','')
            $("#numeroDestinataire").removeAttr('required')
            $("#numeroDestinataire").val('')
        } else {
            // alert('ci else bi')
            $("#divCompteDest").attr('style','display:none')
            $("#divNumDest").attr('style','display:block')
            $("#numeroDestinataire").attr('required','')
            $("#compteDest").removeAttr('required')
            $("#compteDest").val('')
        }

    });

    $(".myRadio input[type=checkbox]").on("change", function() {
        frais = $(this).val()
        c = $(".myRadio input[type=checkbox]:checked").length > 0;
        // alert(c)
        // if (frais == 'option3') {
        if (c) {
            // alert('ci if bi')
            $("#divMontantFrais").attr('style','display:block')
            $("#montantFrais").attr('required','')
        } else {
            // alert('ci else bi')
            $("#divMontantFrais").attr('style','display:none')
            $("#montantFrais").removeAttr('required')
            $("#montantFrais").val('')
        }

    });

    $('#numTel').mask("99 999 99 99");
    $('#refTransf').mask("AANNNNNN.NNNN.ANNNNN",{translation:  {
                                A: {pattern: /[A-Za-z]/},
                                N: {pattern: /[0-9]/}
    }});
    $('#dateTransfert').mask('00/00/0000');

    $("#refTransf").keyup( function(){
        var query = $("#refTransf").val();
        if (query.length >=2) {
            //var text=query.charAt(0).toUpperCase();
            console.log('fff'+query.toUpperCase());
            //$("#refTransf").html();
            $("#refTransf").val(query.toUpperCase());
        }
        else{
        }

    });
    



});
