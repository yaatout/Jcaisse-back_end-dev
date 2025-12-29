
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
         
        // alert(option);
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
    $.post("salaire/ajax/operationSalaireAjax.php",{
        operation: "popVirerSalairePers_Avance",
        iC:iC,
        iP:iP,
        iS:iS
    }, function(data, status){
        //result=JSON.parse(data);
        //console.log(JSON.parse(data));
        let contrat=data.split('<>');
        
        console.log(contrat);
        //let optionsSel=result[1];
         //alert(u);
         //alert(option);
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
        

        $('#virerAvancePersPop').modal('show'); 
    });
}

$(function(){
    $("#motantViremAvancePers").keyup(function(){
        let montantTotal=Number($("#montant_Pers_Virem").val());
        let mont= Number($("#motantViremAvancePers").val());
        let idSP=$("#idSP_Pers_Virem").val();
        console.log("montantTotal="+montantTotal);
        console.log("mont="+mont);
        
        if (mont<=montantTotal) {
            console.log("Superieur");
            $( "#selectvPers_Avance" ).empty();
            $("#btnVirerAvancePers").show();
            $("#montantIndispo").text("");

            $.post("salaire/ajax/operationSalaireAjax.php",{
                operation: "montantVirAvancePers",
                mont:mont,
                idSP:idSP
            }, function(data, status){
                $("#selectvPers_Avance").append(data );
            });
        } else if (mont>montantTotal) {
            console.log("INFerieur");
            $("#btnVirerAvancePers").hide();
            $("#montantIndispo").text("Montant indisponible");
            $( "#selectvPers_Avance" ).empty();
            
        }
        
        
    });
 });