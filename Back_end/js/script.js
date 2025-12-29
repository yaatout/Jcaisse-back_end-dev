/*
R�sum�:
Commentaire:
version:1.1
Auteur: Korka
Date de modification:
*/
//    $( "#lbF" ).hide( );
//    $( "#inpF" ).hide( );


function isFrais() {

  var avecFrais = document.getElementById("avecFrais");
  var text = document.getElementById("text");

  if (avecFrais.checked == true){
    text.style.display = "block";
//    $( "#lbF" ).show( );
//    $( "#inpF" ).show( );
  } else {

    $("#inpF").val(0 );
    text.style.display = "none";

  }
}
function isFrais2() {

  var avecFrais = document.getElementById("avecFrais2");
  var text = document.getElementById("text2");

  if (avecFrais.checked == true){
    text.style.display = "block";
    //$( "#lbF2" ).show( );
    //$( "#inpF2" ).show( );
  } else {
    $("#inpF2").val(0 );
    text.style.display = "none";
  }
} 
function isCaisse() {

    var btnCaisse = document.getElementById("idCaisse");
    var divRef = document.getElementById("divRef");
    var divNumTel = document.getElementById("divNumTel");
  
    if (btnCaisse.checked == true){
        divRef.style.display = "none";
        divNumTel.style.display = "none";     
    } 
  }
function isNotCaisse() {

    var divRef = document.getElementById("divRef");
    var divNumTel = document.getElementById("divNumTel");
  
    divRef.style.display = "block";
    divNumTel.style.display = "block";
    
}
function validation(){
    //Si le champ est vide
      console.log('data33');
    //  alert("test radio button333");
}

$(document).ready( function($){
    $('#numTel,#numTel2,#numTelPm,#numTelOMValidation,#numTelWavValidation').mask("99 999 99 99");
    $('#refTransf,#refTransfOMValidation').mask("AANNNNNN.NNNN.ANNNNN",{translation:  {
                                A: {pattern: /[A-Za-z]/},
                                N: {pattern: /[0-9]/}
                         }});
    $('#dateTransfert').mask('00/00/0000');

    
    }
);


/**Debut ajouter Vitrine**/
function ajt_vitrine(id){
     $.ajax({
         url:"ajax/ajouterVitrineAjax.php",
         method:"POST",
         data:{
             operation:1,
             idDesignation : id,
         },
         success: function (data) {
             console.log('id ='+id);
            tab=data.split('+');
            var ligne = "<tr><td>0</td><td>"+tab[0]+"</td><td>"+tab[1]+"</td><td>"+tab[2]+"</td><td>"+tab[3]+"</td><td>"+tab[4]+"</td><td>En cours KKKKK...</td></tr>";
            $("table.tabStock").prepend(ligne);
            $('#btn_ajtVitrine-'+id).prop('disabled', true);
             console.log(data);
         },
         error: function() {
             alert("La requête "); },
         dataType:"text"
     });
}
/**Fin ajouter Vitrine**/

/**Debut ajouter Vitrine Pharma**/
function ajt_vitrinePH(id){


   // var designation=$('#designation-'+id).val();
    var categorie=$('#categorie-'+id).val();
    var design=$('#designation-'+id).val();
    var forme=$('#forme-'+id).val();
    var tableau=$('#tableau-'+id).val();
    var prixPublic=$('#prixPublic-'+id).val();
      var codeBarreDesignat=$('#codeBarreDesignation-'+id).val();
    var codeBarreuniteSt=$('#codeBarreuniteStock-'+id).val();
    var idFus=$('#idFusion-'+id).val();
    //console.log('data');
    //alert (design);
     $.ajax({
         url:"ajax/ajouterVitrineAjax.php",
         method:"POST",
         data:{
             operation:2,
             idDesignation : id,
             categorie : categorie ,
             designation : design ,
             forme : forme,
             tableau : tableau,
             prixPublic : prixPublic,
             codeBarreDesignation : codeBarreDesignat,
             codeBarreuniteStock : codeBarreuniteSt,
             idFusion : idFus,
         },
         success: function (data) {
            tab=data.split('+');
            var ligne = "<tr><td>0</td><td>"+tab[0]+"</td><td>"+tab[1]+"</td><td>"+tab[2]+"</td><td>"+tab[3]+"</td><td>"+tab[4]+"</td><td>En cours ...</td></tr>";
            $("table.tabVitrine").prepend(ligne);
            $('#uniteStock-'+id).prop('disabled', true);
            $('#prixUnitaire-'+id).prop('disabled', true);
            $('#prixuniteStock-'+id).prop('disabled', true);
            $('#init_Produit-'+id).prop('disabled', true);
             //console.log(data);
         },
         error: function() {
             alert("La requête "); },
         dataType:"text"
     });
}
/**Fin ajouter Vitrine PHAr**/

/**Debut supprimer Designation Vitrine**/
function spm_DesignationVT(id){
    //alert(id);
    $.ajax({
        url:"ajax/vitrineAjax.php",
        method:"POST",
        data:{
            operation:1,
            id : id,
        },
        success: function (data) {
            tab=data.split('<>');
            $('#id_Spm').val(tab[0]);
            $('#designation_Spm').val(tab[1]);
            $('#categorie_Spm').val(tab[2]);
            $('#uniteStock_Spm').val(tab[3]);
            $('#prixuniteStock_Spm').val(tab[4]);
            $('#prix_Spm').val(tab[5]);
            $('#img_Spm').val(tab[6]);
            $('#supprimerDesignation').modal('show');
        },
        error: function() {
            alert("La requête 3"); },
        dataType:"text"
    });
}
/**Fin supprimer Designation Vitrine**/

/**Debut Uploader Image Existante Designation catalogue**/
function imgE_DesignationC (idD,id,i){
    
    //alert("tab[6]");
    console.log("jjjjjjjjj");
    $.ajax({
        url:"ajax/listerCatalogueBAjax.php",
        method:"POST",
        data:{
            operation:'image',
            id : id,
            idD : idD,
        },
        success: function (data) {
            tab=data.split('<>');
            $('#id_Upd_ExC').val(tab[0]);
            $('#idDE').val(idD);
            $('#img_Upd_ExC').val(tab[6]);
            $("#imgsrc_UpdC").attr("src",'uploads/'+tab[6]);
            $('#imageEDesignationC').modal('show');
        },
        error: function() {
            alert("La requête 1 "); },
        dataType:"text"
    });
}
/**Fin Uploader Image Existante Designation catalogue**/

/**Debut Uploader Image Existante Designation catalogue**/
function imgN_DesignationC (idD,id,i){
    $.ajax({
        url:"ajax/listerCatalogueBAjax.php",
        method:"POST",
        data:{
            operation:'image',
            id : id,
            idD : idD,
        },
        success: function (data) {
            tab=data.split('<>');
            $('#id_Upd_NvC').val(tab[0]);
            $('#idDN').val(idD);
            console.log('iiiiiii'+idD);
            $('#imageNDesignationC').modal('show');
        },
        error: function() {
            alert("La requête 2"); },
        dataType:"text"
    });
}
/**Fin Uploader Image Existante Designation catalogue**/

/**Debut Uploader Image Existante Designation Vitrine**/
function imgEX_DesignationVT(id){
    
    //alert("tab[6]");
    console.log("jjjjjjjjj");
    $.ajax({
        url:"ajax/vitrineAjax.php",
        method:"POST",
        data:{
            operation:1,
            id : id,
        },
        success: function (data) {
            tab=data.split('<>');
            
            //alert('../../uploads/'+tab[6]);
            $('#id_Upd_Ex').val(tab[0]);
            $('#img_Upd_Ex').val(tab[6]);
            $("#imgsrc_Upd").attr("src",'../JCaisse/vitrine/uploads/'+tab[6]);
            $('#imageExDesignation').modal('show');
        },
        error: function() {
            alert("La requête 1 "); },
        dataType:"text"
    });
}
/**Fin Uploader Image Existante Designation Vitrine**/

/**Debut Uploader Image Nouvelle Designation Vitrine**/
function imgNV_DesignationVT(id){
    $.ajax({
        url:"ajax/vitrineAjax.php",
        method:"POST",
        data:{
            operation:1,
            id : id,
        },
        success: function (data) {
            tab=data.split('<>');
            $('#id_Upd_Nv').val(tab[0]);
            $('#imageNvDesignation').modal('show');
        },
        error: function() {
            alert("La requête 2"); },
        dataType:"text"
    });
}
/**Fin Uploader Image Nouvelle  Designation Vitrine**/

$(function() {
    $("#typeCompteS").change(function() {
     if($(this).val()=="compte cheques" || $(this).val()=="compte pret"){
        console.log('data2');
         $( "#nclB" ).hide( );
         $( "#icLB" ).hide( );
         $( "#numeroCompte" ).val('Sans numéro');
         $( "#numeroCompte" ).hide( );
         $( "#montantCompte" ).val(0);
         $( "#montantCompte" ).hide( );
     }else{
            $( "#nclB" ).show( );
            $( "#icLB" ).show( );
            $( "#numeroCompte" ).show( );
            $( "#montantCompte" ).show( );
     }
    //alert("test radio button");


    });


 $(".idtest").click(function(){
    console.log('data2');
   //alert("test radio button");
 });

$('#envoyerStock').click(function(even) {
        var idD=document.getElementById('idD');
        var missIdD=document.getElementById('missIdD');
        if (idD.validity.valueMissing) {
                event.preventDefault();
                missIdD.textContent='Choisir une designation existante';
                missIdD.style.color='red';
            }
         alert("CSV555555555");

    });
});

/**Debut Modifier Produit */
function imp_facture(idPayement){
    $('.btnPopUpFact').prop('disabled', true);
    $.ajax({
        url:"ajax/pdfFactureAjax.php",
        method:"POST",
        data:{
            idPayement : idPayement
        },
        success: function (data) {
            if(data==1){
                $('#id_Result').text('envoyée par mail avec succes.');
                $('#msg_info_js').modal('show');
                $('.popUpFact').modal('hide');
                $('.btnPopUpFact').prop('disabled', '');
            }
            else{
                $('#id_Result').text('non envoyé par mail. Erreur');
                $('#msg_info_js').modal('show');
                $('.popUpFact').modal('hide');
                $('.btnPopUpFact').prop('disabled', '');
            }
        },
        error: function() {
            alert("La requête "); },
        dataType:"text"
    });

}
function effectue_validationPaiement(idPS){

    $.ajax({
        url:"ajax/ajouterValidationPaiementAjax.php",
        method:"POST",
        data:{
            operation:1,
            idPS : idPS,
        },
        success: function (data) {
            tab=data.split('+');
			//alert(data);
            $('#idPS_Rtr').val(tab[0]);
            $('#idBoutique_Rtr').val(tab[1]);
            $('#montantFixePayement_Rtr').val(tab[2]);
            $('#datePS_Rtr').val(tab[3]);
			$('#refTransf_Rtr').val(tab[4]);
            $('#numTel_Rtr').val(tab[5]);
            $('#validationPaiementModal').modal('show');
        },
        error: function() {
            alert("La requête "); },
        dataType:"text"
    });
}
//////////////////////////////////////////////////////////////////////////////////
//////////////////////////DEBUT SALAIRE PERSONNEL/////////////////////////////////

function creerContrat(id) {
    
    $.ajax({
        url:"ajax/personnel/contratAjax.php",
        method:"POST",
        data:{
            operation:"cherchePersonnel",
            idPersonnel : id,
        },
        success: function (data) { 
            //console.log(data);
            //alert(data);           
            $('#idUCcontrat').val(id);
            $('#profilUCcontrat').val(data);
            $('#creerContrat').modal('show');
        },
        error: function() {
            alert("La requête "); },
        dataType:"text"
    });
} 
function voirContrat(idContrat) {    
    $.ajax({
        url:"ajax/personnel/contratAjax.php",
        method:"POST",
        data:{
            operation:"chercheContrat",
            idContrat : idContrat,
        },
        success: function (data) { 
            //alert(data);
            tab=data.split('<>');     
            $('#prenomUCont').html( tab[1]);
            $('#nomUCont').html( tab[2]);
            $('#profilUCont').html( tab[3]);
            $('#montantUCont').html( tab[4]);
            $('#debutUCont').html( tab[5]); 
            $('#finUCont').html( tab[6]);
            $('#voirContrat').modal('show');
        },
        error: function() {
            alert("La requête "); },
        dataType:"text"
    });
}
function voirContratToMod(idContrat) {    
    $.ajax({
        url:"ajax/personnel/contratAjax.php",
        method:"POST",
        data:{
            operation:"chercheContrat",
            idContrat : idContrat,
        },
        success: function (data) { 
            //alert(data);
            tab=data.split('<>');    
            $('#ipContMod').val(idContrat);
            $('#pContMod').html( tab[1]);
            $('#nContMod').html( tab[2]);
            $('#profContMod').html( tab[3]);
            $('#montContMod').val( tab[4]);
            $('#dDebutContMod').val( tab[5]); 
            $('#dFinContMod').val( tab[6]);
            $('#modContratPersonnel').modal('show');
        },
        error: function() {
            alert("La requête "); },
        dataType:"text"
    });
}
function upContratPopup(idC) {
    $.ajax({
        url:"ajax/personnel/contratAjax.php",
        method:"POST",
        data:{
            operation:"chercheContrat",
            idContrat : idC,
        },
        success: function (data) { 
            //alert(data);
            tab=data.split('<>');     
            console.log(tab);
            $('#idCont_Upd_Nv').val( idC); 
            var dataYesUpL=' <input type="file" name="fileUp" accept="image/*" id="input_file_Cont" id="cover_image" onchange="showPrevieCont(event);" /><br />'+                                                                           
                    '<input  type="hidden"   name="image" id="imageCont_Upd_Nv"  />'+
                    '<iframe id="output_pdf_Cont" src="" width="100%" height="500px"></iframe> ';
            var dataNoUpL=' <input type="file" name="fileUp" accept="image/*" id="input_file_Cont" id="cover_image" onchange="showPrevieCont(event);"/>';
            var yesBut=' <button type="button" class="btn btn-primary pull-right" name="btnDownloadImg"  onclick="printDocPDF()">'+
                    '<span class="glyphicon glyphicon-download"></span> Telecharger</button>';
            
            if (tab[7].length !=0 ) {
                $("#noUpload").html('');
                $("#yesUpload").html(dataYesUpL);
                $("#yesBoutonTelecharger").html(yesBut);

                $('#imageCont_Upd_Nv').val(tab[7]);
                var chemin="./PiecesJointes/"+tab[7];
                var frame = document.getElementById('output_pdf_Cont');
                frame.src=chemin; 
            } else {
                $("#noUpload").html(dataNoUpL);
                $("#yesUpload").html('');
                $("#yesBoutonTelecharger").html('');

                $('#prCon').html( tab[1]);
                $('#nCon').html( tab[2]);
                $('#fileUp').val( idC);           
            }      
            $('#upNvContrat').modal('show');
        },
        error: function() {
            alert("La requête "); },
        dataType:"text"
    });
    
}
function showPrevieCont(event) {
    var idC=document.getElementById('idC').value;
    alert(idC);
    var file = document.getElementById('input_file_Cont').value;
    var reader = new FileReader();
    reader.onload = function()
    {
        var format = file.substr(file.length - 3);
        var pdf = document.getElementById('output_pdf_Cont');
        if(format=='pdf'){
            document.getElementById('output_pdf_Cont').style.display = "block";
            pdf.src = reader.result;
        }
    }
    reader.readAsDataURL(event.target.files[0]);
    document.getElementById('btn_upload_Cont').style.display = "block";
}
function printDocPDF() {
    
    var nomImage=document.getElementById('imageCont_Upd_Nv').value;
    var chemin="./PiecesJointes/"+nomImage;

    $.ajax({
        url:"ajax/personnel/contratAjax.php",
        method:"POST",
        data:{
            operation:"telechargerContrat",
            nomImage : nomImage,
        },
        success: function (data) { 
            
        },
        error: function() {
            alert("La requête "); },
        dataType:"text"
    });
}
function creerContratP(i) {   
    $('#ip').val(i);

    $('#creerContratPersonnel').modal('show');
}
function annulContratPop(i) {   
    $('#iAnnCont').val(i);
    $('#annulerContrat').modal('show');
}
//////////////////////////FIN SALAIRE PERSONNEL/////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////

/////salaireAccompagnateur.php
function desBouton (idb){
            //alert(idb);
            //alert("#"+idb+"");
            console.log(idb);
            //e.preventDefault();
            //$("#"+idb+"").prop("disabled", true);
             //document.getElementById("btnVirerPers").disabled = 'true';
             //alert('OK deja desactive');
            //$("#FormVirerPers").submit();
}

// $(document).ready(function(){
//     $("#btnVirerPers").on("click", function () {
//         alert("eeeeeeeeeee");
//         $("#btnVirerPers").prop("disabled", true);
//         alert('OK');
//         //$("#FormVirerPers").submit();
//     });
//  });
//////////////////////////Piece Jointe compte/////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////

function upPJPopup(idMv) {
    $.ajax({
        url:"ajax/compte/compteAjax.php",
        method:"POST",
        data:{
            operation:"chercheMvn",
            idMouv : idMv,
        },
        success: function (data) { 
            tab=data.split('<>');   
            //tab=data[0];     
            console.log(tab);
            $('#idMv').val( idMv); 
            var dataYesUpL=' <input type="file" name="file" accept="image/*,.pdf" id="input_file_pj" id="cover_image" onchange="showPreviepj(event);" /><br />'+                                                                           
                    '<input  type="hidden"   name="image" id="idPj_Upd_Nv"  />'+
                    '<iframe id="output_pdf_pj" src="" width="100%" height="500px"></iframe> ';
            var dataNoUpL=' <input type="file" name="file" accept="image/*,.pdf" id="input_file_pj" id="cover_image" onchange="showPreviepj(event);"/>';
            //var yesBut=' <button type="button" class="btn btn-primary pull-right" name="btnDownloadImg"  onclick="printDocPDF()">'+
                   // '<span class="glyphicon glyphicon-download"></span> Telecharger</button>';
            var yesBut='';
            
            if (tab[1].length !=0) {
                //alert(data);
                $("#noUpload").html('');
                $("#yesUpload").html(dataYesUpL);
                $("#yesBoutonTelecharger").html(yesBut);

                //$('#idPj_Upd_Nv').val(idMv);
                var chemin="./PiecesJointes/"+tab[1];
                var frame = document.getElementById('output_pdf_pj');
                frame.src=chemin; 
            } else {
                $("#noUpload").html(dataNoUpL);
                $("#yesUpload").html('');
                $("#yesBoutonTelecharger").html('');

                //$('#prCon').html( idMv);
                //$('#nCon').html( tab[2]);
                $('#fileUp').val( idMv);           
            }      
            
        },
        error: function() {
            alert("La requête "); },
        dataType:"text"
    });
    $('#upPJointe').modal('show');
}
function showPreviepj(event) {
    //var id=document.getElementById('idMv').value;
    //alert(id);
    var file = document.getElementById('input_file_pj').value;
    var reader = new FileReader();
    reader.onload = function()
    {
        var format = file.substr(file.length - 3);
        var pdf = document.getElementById('output_pdf_pj');
        if(format=='pdf'){
            document.getElementById('output_pdf_pj').style.display = "block";
                document.getElementById('output_image_Bon').style.display = "none";
            
            pdf.src = reader.result;
        }else{
            document.getElementById('output_image_Bon').style.display = "block";
            document.getElementById('output_pdf_Bon').style.display = "none";
            document.getElementById("output_image_Bon").src="./PiecesJointes/"+file;
        }
    }
    reader.readAsDataURL(event.target.files[0]);
    document.getElementById('btn_upload_pj').style.display = "block";
}
function showImageBon(idPagnet) {
    $('#idBon_View').text(idPagnet);
    $('#idBon_Upd_Nv').val(idPagnet);
    $('#input_file_Bon').val('');
    $('#imageNvBon').modal('show');
    var file = $('#imageBon'+idPagnet).val();
    if(file!=null && file!=''){
        var format = file.substr(file.length - 3);
        if(format=='pdf'){
            document.getElementById('output_pdf_Bon').style.display = "block";
            document.getElementById('output_image_Bon').style.display = "none";
            document.getElementById("output_pdf_Bon").src="./PiecesJointes/"+file;
        }
        else{
            document.getElementById('output_image_Bon').style.display = "block";
            document.getElementById('output_pdf_Bon').style.display = "none";
            document.getElementById("output_image_Bon").src="./PiecesJointes/"+file;
        }
    }
    else{
        document.getElementById('output_pdf_Bon').style.display = "none";
        document.getElementById('output_image_Bon').style.display = "none";
    }
}
function showPreviewBon(event) {
    var file = document.getElementById('input_file_Bon').value;
    var reader = new FileReader();
    reader.onload = function()
    {
        var format = file.substr(file.length - 3);
        var pdf = document.getElementById('output_pdf_Bon');
        var image = document.getElementById('output_image_Bon');
        if(format=='pdf'){
            document.getElementById('output_pdf_Bon').style.display = "block";
            document.getElementById('output_image_Bon').style.display = "none";
            pdf.src = reader.result;
        }
        else{
            document.getElementById('output_image_Bon').style.display = "block";
            document.getElementById('output_pdf_Bon').style.display = "none";
            image.src = reader.result;
        }
    }
    reader.readAsDataURL(event.target.files[0]);
    document.getElementById('btn_upload_Bon').style.display = "block";
}
//////////////////////////FIN Piece jointe Compte/////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////


$(function(){
    $("#btn_aj_validationPaiement").click( function(){

        var idPS=$('#idPS_Rtr').val();
        var datePS=$('#datePS_Rtr').val();
        var montantFixePayement=$('#montantFixePayement_Rtr').val();
        var refTransf=$('#refTransf_Rtr').val();
        var numTel=$('#numTel_Rtr').val();
        $.ajax({
            url:"ajax/ajouterValidationPaiementAjax.php",
            method:"POST",
            data:{
                operation:2,
                idPS : idPS,
                refTransf : refTransf,
                numTel : numTel,
            },
            success: function (data) {
				alert(data+" est remis à jour");
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        });
    });
});
function detRefTransferClick(idPS){
  $.ajax({
      url:"ajax/detailRefTransfertAjax.php",
      method:"POST",
      data:{
          idPS : idPS,
      },
      success: function (data) {
          result=data.split('+');
          $('#datePS').val(result[0]);
          $('#nomBoutique').val(result[1]);
          $('#refTransfert').val(result[2]);
          $('#DetReferencetransferts').modal('show');
      },
      error: function() {
          alert("La requête "); },
      dataType:"text"
  });
}
$(function(){
    $("#montantTransfertPm").click( function(){

        var ordre=$('#ordreF_Mdf').val();
        var idForme=$('#idForme_Mdf').val();
        var idCataT=$('#idCataTF_Mdf').val();
        var nomForme_Mdf=$('#nomForme_Mdf').val();
        var nomForme_old=$('#nomForme_old').val();
         $.ajax({
              url:"ajax/detailRefTransfertAjax.php",
              method:"POST",
              data:{
                  idPS : idPS,
              },
              success: function (data) {
                  result=data.split('+');
                  $('#datePS').val(result[0]);
                  $('#nomBoutique').val(result[1]);
                  $('#refTransfert').val(result[2]);
                  $('#DetReferencetransferts').modal('show');
              },
              error: function() {
                  alert("La requête "); },
              dataType:"text"
          });
    });
});

//$(function() {
    //var dd = moment("31/05/2016 09:00", "DD/MM/YYYY HH:mm");
    /* var myDate = $("#startDate").attr('data-date');
    $('#startDate').datepicker({
                  yearRange: "-20:+3",
                  maxDate: '-1M',
                  changeMonth: true,
                  changeYear: true,
                  changeDay: true,
                  setDate: myDate,
                  showButtonPanel: false,
                  dateFormat: 'mm-yy',
                  onClose: function(dateText, inst) {
                      var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                      var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                      $(this).datepicker('setDate', new Date(year, month, 1));
                  },
    });
    $('#startDate').datepicker('setDate', myDate); */
//}); 
function date_jourN() {
    
    var $picker = $("#startDate");
    var date=new Date($picker.datepicker('getDate'));
    console.log('avens'+date);
    date.setMonth(date.getMonth()+1);
    $picker.datepicker('setDate', date)
    //return false;
}
function date_jourP() {
   
     var $picker = $("#startDate");
     var date=new Date($picker.datepicker('getDate'));
    console.log('la date '+date);
    date.setMonth(date.getMonth()-1);
    $picker.datepicker('setDate', date);
    console.log('la date '+date);
    return false;
  
}
function date_jour() {
     console.log('la date on change '+$("#startDate").val());
   
}

function date_jour(jour) {
    var x = document.getElementById(jour).value;
    var tab = x.split('-');
    var date_jour = tab[2] + '-' + tab[1] + '-' + tab[0];
    window.location.href = "rapport.php?dateprevious=" + date_jour;
}