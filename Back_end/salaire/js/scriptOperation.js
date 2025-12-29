
function popDetSalAcc(mat) {
    //alert("TTTTTTTT");
    $( "#detailSalaireAccDiv" ).empty();
    $.post("salaire/ajax/operationSalaireAjax.php",{
        operation: "detailSalaireAcc",
        mat:mat
    }, function(data, status){
        $( "#detailSalaireAccDiv" ).append(data );
        //alert("Data: " + data + "\nStatus: " + status);
        $('#detailSalAccPop').modal('show'); 
    });
}

function popVirSalAcc(i,mont) {
    $( "#selectvAcc" ).empty();
    $.post("salaire/ajax/operationSalaireAjax.php",{
        operation: "popVirerSalaireAcc",
        i:i,
        mont:mont
    }, function(data, status){
        $( "#selectvAcc" ).empty();
        //result=data.split('<>');
        result=JSON.parse(data);
        console.log(JSON.parse(data));
        let user=result[0].split('<>');
        let optionsSel=result[1];
        // alert(u);
        // alert(option);
        
        $("#infoVacc").text(user[0]+" "+user[1]);
        $("#numeroVAcc").text(user[3]);
        $( "#selectvAcc" ).append(optionsSel );
        $("#iUVAcc").val(i);
        $("#montantVAcc").text(mont);
        $("#montantUVAcc").val(mont);
        

        $('#virerAccPop').modal('show'); 
    });
}
function popVirSalPers(iC,iP,iS) {
    //$( "#detailSalaireAccDiv" ).empty();
    $.post("salaire/ajax/operationSalaireAjax.php",{
        operation: "popVirerSalairePers",
        iC:iC,
        iP:iP,
        iS:iS
    }, function(data, status){
        $( "#selectvPers" ).empty();
        result=JSON.parse(data);
        console.log(JSON.parse(data));
        let contrat=result[0].split('<>');
        let optionsSel=result[1];
         
        console.log("La taille :"+optionsSel.length);
        if(optionsSel.length==0){
            $("#btnVirerIng").hide();
            $("#montantNoDisp").text("Montant non disponible dans le compte");
        }else{
            $("#btnVirerIng").show();
            $("#montantNoDisp").text("");
        };
        let montant=contrat[0];
        let numero=contrat[1];
        let matricule=contrat[2];

        $("#montantVPers").text(montant);
        $("#numeroRecePers").text(numero);
        $("#selectvPers").append(optionsSel );
        
        $("#idSP_Pers").val(iS);
        $("#montant_Pers").val(montant);
        $("#matricule_Pers").val(matricule);
        $("#telephone_Pers").val(numero);
        

        $('#virementPerSPop').modal('show'); 
    });
}
function popVirSalPersAvance(iC,iP,iS) {
    $( "#selectvPers_Avance" ).empty();
    $("#btnVirerAvancePers").hide();

    $.post("salaire/ajax/operationSalaireAjax.php",{
        operation: "popVirerSalairePers_Avance",
        iC:iC,
        iP:iP,
        iS:iS
    }, function(data, status){
        let contrat=data.split('<>');
        
        console.log(contrat);
        let montant=contrat[0];
        let numero=contrat[1];
        let matricule=contrat[2];
        console.log(montant);
        $("#montantVPers_Virem").text(montant);
        $("#numeroRecePers_Virem").text(numero);
        //$("#selectvPers").append(optionsSel );
        
        $("#idSP_Pers_Virem").val(iS);
        $("#montant_Pers_Virem").val(montant);
        $("#matricule_Pers_Virem").val(matricule);
        $("#telephone_Pers_Virem").val(numero);
        $("#motantViremAvancePers").val();
        $('#virerAvancePersPop').modal('show'); 
    });
}

$(function(){
    $("#motantViremAvancePers").keyup(function(){
        let montantTotal=Number($("#montant_Pers_Virem").val());
        let mont= Number($("#motantViremAvancePers").val());
        let idSP=$("#idSP_Pers_Virem").val();
        //console.log("montantTotal="+montantTotal);
        //console.log("mont="+mont);
        
        if (mont<=montantTotal) {
           
            //console.log("Superieur");
            $( "#selectvPers_Avance" ).empty();
            // $("#btnVirerAvancePers").show();
            // $("#montantIndispo").text("");

            $.post("salaire/ajax/operationSalaireAjax.php",{
                operation: "montantVirAvancePers",
                mont:mont,
                idSP:idSP
            }, function(data, status){
                console.log(data)
                if(data.length==0){
                    console.log("INFerieur 2");
                    $("#btnVirerAvancePers").hide();
                    $("#montantIndispo").text("Montant non disponible dans le compte");
                }else{
                    let chaine=data.split('<>');
                    console.log("la taiile "+chaine.length);
                    if (chaine[0].length==0) {
                        console.log("INFerieur 3");
                        $("#btnVirerAvancePers").hide();
                        $("#montantIndispo").text("Montant non disponible dans le compte");
                    }else if (chaine.length>1 && chaine[1].length!=0) {
                        console.log("INFerieur 4");
                        $("#btnVirerAvancePers").show();
                        $("#selectvPers_Avance").append(data);
                        $("#motantViremAvancePers").val(montantTotal)
                        //$("#motantViremAvancePers").attr("disabled", "disabled"); 
                        $("#montantIndispo").text("C'est votre deuxieme et dernier avance");
                    }
                    else{
                        console.log("Superieur");
                        $("#btnVirerAvancePers").show();
                        $("#montantIndispo").text("");
                        $("#selectvPers_Avance").append(data );
                    }
                    
                };
            });
        } else if (mont>montantTotal) {
            console.log("INFerieur 1");
            $("#btnVirerAvancePers").hide();
            $("#montantIndispo").text("Montant saisie ne doit pas depassé le montant du salaire");
            $( "#selectvPers_Avance" ).empty();
            
        }
        
        
    });
 });

function virementPerSPop(){
    let idSP=$("#idSP_Pers").val();
    let montant=$("#montant_Pers").val();
    let matriculePersonnel=$("#matricule_Pers").val();
    let telephone=$("#telephone_Pers").val();
    let datePaiement=$("#dateV_Pers").val();
    let comptePaiement=$("#selectvPers").val();
    //alert(datePaiement);
    $.post("salaire/ajax/operationSalaireAjax.php",{
        operation: "btnVirerIng",
        idSP:idSP,
        montant:montant,
        matriculePersonnel:matriculePersonnel,
        telephone:telephone,
        datePaiement:datePaiement,
        comptePaiement:comptePaiement
    }, function(data, status){
        //console.log(data);
        
        $('#virementPerSPop').modal('hide'); 
        location.reload(true);
    });

}
function virerAvancePers(){
    
    let idSP=$("#idSP_Pers_Virem").val();
    let montant=$("#motantViremAvancePers").val();
    //let telephone=$("#telephone_Pers_Virem").val();
    let datePaiement=$("#date_Pers_Virem").val();
    let comptePaiement=$("#selectvPers_Avance").val();

    //alert(datePaiement);
    $.post("salaire/ajax/operationSalaireAjax.php",{
        operation: "btnVirerAvance",
        idSP:idSP,
        motantAvance:montant,
        //telephone:telephone,
        datePaiement:datePaiement,
        comptePaiement:comptePaiement
    }, function(data, status){
        //console.log(data);
        
        $('#virerAvancePersPop').modal('hide'); 
        location.reload(true);
    });

}