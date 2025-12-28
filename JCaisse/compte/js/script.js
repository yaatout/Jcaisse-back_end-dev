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

function validation(){
    //Si le champ est vide
      console.log('data33');
    //  alert("test radio button333");
}
$(document).ready( function($){
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
})
$(document).ready( function($){
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

/**Debut categorie categorie par click**/
function tabDetClick(idCataT){
       if ($.fn.DataTable.isDataTable('#tableDesignation')) {
                $('#tableDesignation').DataTable().destroy();
      }

                                  $("#tableDesignation").dataTable({
                                  "bProcessing": true,
                                  "sAjaxSource": "ajax/catalogueDetailsProduits_PHAjax.php?operation=details&id=<?php echo $tab0['id']; ?>",
                                  "aoColumns": [
                                      { mData: "0" } ,
                                      { mData: "1" },
                                      { mData: "2" },
                                      { mData: "3" },
                                      { mData: "4" },
                                      { mData: "5" },
                                      { mData: "6" },
                                      { mData: "7" }
                                    ],
                                  });

 }
/**Fin categorie categorie catalogueTotal**/


/**Debut categorie categorie par click**/
function tabCategClick(idCataT){

    if ($.fn.DataTable.isDataTable('#tableCategorie')) {
          $('#tableCategorie').DataTable().destroy();
      }
    // console.log(idD+" "+idCT+" "+ordre);

    $("#tableCategorie").dataTable({
    "bProcessing": true,
    "sAjaxSource": "ajax/catalogueDetailsCategoriesAjax.php?id="+idCataT,
    "aoColumns": [
        { mData: "0" } ,
        { mData: "1" } ,
        { mData: "2" },
        { mData: "3" }
      ],

    });
}
/**Fin categorie categorie catalogueTotal**/

/**Debut categorie categorie par click**/
function tabFormeClick(idCataT){

    if ($.fn.DataTable.isDataTable('#tableForme')) {
          $('#tableForme').DataTable().destroy();
      }
    // console.log(idD+" "+idCT+" "+ordre);

    $("#tableForme").dataTable({
    "bProcessing": true,
    "sAjaxSource": "ajax/catalogueDetailsFormesAjax.php?id="+idCataT,
    "aoColumns": [
        { mData: "0" } ,
        { mData: "1" },
        { mData: "2" }
      ],
    });
}
/**Fin categorie categorie catalogueTotal**/
/**Debut categorie categorie par click**/
function tabDoubClick(idCataT){

    if ($.fn.DataTable.isDataTable('#tableDoublon')) {
          $('#tableDoublon').DataTable().destroy();
      }
    // console.log(idD+" "+idCT+" "+ordre);

    $("#tableDoublon").dataTable({
    "bProcessing": true,
    "sAjaxSource": "ajax/catalogueDetailsProduits_PHAjax.php?operation=doublon&id="+idCataT,
    "aoColumns": [
        { mData: "0" } ,
        { mData: "1" },
        { mData: "2" },
        { mData: "3" },
        { mData: "4" },
        { mData: "5" },
        { mData: "6" },
        { mData: "7" }
      ],

    });
}
/**Fin categorie categorie catalogueTotal**/
/**Debut categorie categorie par click**/
function tabFusClick(idCataT){

    if ($.fn.DataTable.isDataTable('#tableFusion')) {
          $('#tableFusion').DataTable().destroy();
      }
    // console.log(idD+" "+idCT+" "+ordre);

    $("#tableFusion").dataTable({
    "bProcessing": true,
    "sAjaxSource": "ajax/catalogueDetailsProduits_PHAjax.php?operation=fusion&id="+idCataT,
    "aoColumns": [
        { mData: "0" } ,
        { mData: "1" },
        { mData: "2" },
        { mData: "3" },
        { mData: "4" },
        { mData: "5" },
        { mData: "6" },
        { mData: "7" }
      ],

    });
}
/**Fin categorie categorie catalogueTotal**/

/**Debut select forme par click**/
function selectForme(id,idCataT){
  $.ajax({
      url:"ajax/catalogueDetailsProduits_PHAjax.php",
      method:"POST",
      data:{
          action:'listeSelectForme',
          id : idCataT,
      },
      success: function (data) {
          // tab=data.split('<>');
          var data = JSON.parse(data);
           var len = data.length;

                for( var i = 0; i<len; i++){
                    var name = data[i];
                    $('#formeD-'+id).append("<option value='"+name+"'>"+name+"</option>");
                    // console.log('='+data[i]);

                }
          // $.each(obj,function(value)
          //       {
          //           $('#formeD-'+id).append('<option value=' + value + '>' + value + '</option>');
          //       });

      },
      error: function() {
          alert("La requête 3"); },
      dataType:"text"
  });
}
/**Fin select forme par click **/

/**Debut select categorie par click**/
function selectCategorie(id,idCataT){
  $.ajax({
      url:"ajax/catalogueDetailsProduits_PHAjax.php",
      method:"POST",
      data:{
          action:'listeSelectCategorie',
          id : idCataT,
      },
      success: function (data) {
          // tab=data.split('<>');
          var data = JSON.parse(data);
           var len = data.length;

                for( var i = 0; i<len; i++){
                    var name = data[i];
                    $('#categorieD-'+id).append("<option value='"+name+"'>"+name+"</option>");
                    // console.log('='+data[i]);

                }
          // $.each(obj,function(value)
          //       {
          //           $('#formeD-'+id).append('<option value=' + value + '>' + value + '</option>');
          //       });

      },
      error: function() {
          alert("La requête 3"); },
      dataType:"text"
  });
}
/**Fin select categorie par click **/

/**Debut Doublon Designation catalogueTotal**/
function dbl_Designation_PH(idD,idCT,ordre){
    //alert(id);
    /*****************DataTable debut************************/
    $("#idDo").val(idD);
    $("#idCT").val(idCT);
    if ($.fn.DataTable.isDataTable('#tableDo')) {
          $('#tableDo').DataTable().destroy();
      }
    console.log(idD+" "+idCT+" "+ordre);
    $('#popD').modal('show');
    $("#tableDo").dataTable({
    "bProcessing": true,
    "sAjaxSource": "ajax/listeProduit-DoublonPHAjax.php?idD="+idD+"&idCT="+idCT+"",
    "aoColumns": [
      { mData: "0" },
      { mData: "1" },
      { mData: "2" },
      { mData: "3" },
      { mData: "4" },
      { mData: "5" },
      { mData: "6" }
      ],
    });
   /******************DataTable Fin**************************/
    /*$.ajax({
        url:"ajax/listeProduit-DoublonPHAjax.php",
        method:"GET",
        data:{
            operation:1,
            id : idD,
            idCT : idCT,
        },
        success: function (data) {
          // tab = data.split('+');
          // tabIdFusionDoub=tab[0].join('-');
          // tabDoublon=tab[1].join('-');
          //console.log(tab[0]);
          console.log(data);
          alert(data);
        },
        error: function() {
            alert("La requête 3"); },
        dataType:"text"
    });*/
}
/**Fin Doublon Designation catalogueTotal**/

/**Debut eliminer tous Doublon  catalogueTotal**/
function eli_tousDoub_PH(idCT){
    //alert(idCT);
    console.log(idCT);
    $.ajax({
        url:"ajax/listeProduit-DoublonPHAjax.php",
        method:"GET",
        data:{
            operation:1,
            tache : 'eliminerTDoub',
            idCT : idCT,
        },
        success: function (data) {
          // tab = data.split('+');
          // tabIdFusionDoub=tab[0].join('-');
          // tabDoublon=tab[1].join('-');
          //console.log(tab[0]);
          console.log('ffffffffffffff'+data);
           $('#elToutDoub').modal('hide');
          
        },
        error: function() {
            alert("La requête 3"); },
        dataType:"text"
    });
}
/**Fin eliminer tous Doublon  catalogueTotal**/

/**Debut Doublon Designation catalogueTotal**/
function fus_Designation_PH(idD,idCT,ordre){
    //alert(id);
    /*****************DataTable debut************************/
    $("#idF").val(idD);
    $("#idCTF").val(idCT);
    if ($.fn.DataTable.isDataTable('#tableFu')) {
          $('#tableFu').DataTable().destroy();
      }
    console.log(idD+" "+idCT+" "+ordre);
    $('#fu').modal('show');
    $("#tableFu").dataTable({
    "bProcessing": true,
    "sAjaxSource": "ajax/listeProduit-FusionPHAjax.php?idD="+idD+"&idCT="+idCT+"",
    "aoColumns": [
      { mData: "0" },
      { mData: "1" },
      { mData: "2" },
      { mData: "3" },
      { mData: "4" },
      { mData: "5" },
      { mData: "6" }
      ],
    });
   /******************DataTable Fin**************************/
    /*$.ajax({
        url:"ajax/listeProduit-DoublonPHAjax.php",
        method:"GET",
        data:{
            operation:1,
            id : idD,
            idCT : idCT,
        },
        success: function (data) {
          // tab = data.split('+');
          // tabIdFusionDoub=tab[0].join('-');
          // tabDoublon=tab[1].join('-');
          //console.log(tab[0]);
          console.log(data);
          alert(data);
        },
        error: function() {
            alert("La requête 3"); },
        dataType:"text"
    });*/
}
/**Fin Doublon Designation catalogueTotal**/

/**Debut Uploader Image Existante Designation Vitrine**/
function imgEX_DesignationVT(id){
    $.ajax({
        url:"ajax/vitrineAjax.php",
        method:"POST",
        data:{
            operation:1,
            id : id,
        },
        success: function (data) {
            tab=data.split('<>');
            $('#id_Upd_Ex').val(tab[0]);
            $('#img_Upd_Ex').val(tab[6]);
            $('#imgsrc_Upd').src='../uploads/'+tab[6];
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
 /****************************DEBUT CHEKBOX POUR FUSION*********************************/
$(function(){
    $(document).on('click','input:checkbox[name=frites]',function(){
      console.log('TTTTTTTTTTTTTTTTT');
         var valeur=$(this).val();
         var test = $(this).attr('id');
         tab=test.split('-');
         id=tab[0];
         if($(this). is(":checked")){
              console.log("Checkbox is checked."+id );
              if (id=='designation') {
                  $("#designationF").val(valeur);
              }else if (id=='categorie') {
                  $("#categorieF").val(valeur);
              }else if (id=='nbreArticleUniteStock') {
                  $("#nbreArticleUniteStockF").val(valeur);
              }else if (id=='uniteStock') {
                  $("#uniteStockF").val(valeur);
              }else if (id=='prix') {
                  $("#prixF").val(valeur);
              }else if (id=='prixuniteStock') {
                  $("#prixuniteStockF").val(valeur);
              }else if (id=='codeBarreDesignation') {
                    $("#codeBarreDesignationPH").val(valeur);
              }
          }
          else if($(this). is(":not(:checked)")){
              console.log("Checkbox is unchecked." );
          }
        console.log('cliiiiiiiiiiiiiiiiiiii');
    });

    $(document).on('click','input:checkbox[name=ph]',function(){
      console.log('cvvvvvvvvvvvvv');
         var valeur=$(this).val();
         var test = $(this).attr('id');
         tab=test.split('-');
         id=tab[0];
         if($(this). is(":checked")){
              console.log("Checkbox is checked."+id );
              if (id=='designation') {
                  $("#designationPH").val(valeur);
              }else if (id=='categorie') {
                  $("#categoriePH").val(valeur);
              }else if (id=='forme') {
                  $("#formPH").val(valeur);
              }else if (id=='tableau') {
                  $("#tableauPH").val(valeur);
              }else if (id=='prixSession') {
                  $("#prixSessionPH").val(valeur);
              }else if (id=='prixPublic') {
                  $("#prixPublicPH").val(valeur);
              }else if (id=='codeBarreDesignation') {
                    $("#codeBarreDesignationPH").val(valeur);
              }
          }
          else if($(this). is(":not(:checked)")){
              console.log("Checkbox is unchecked." );
          }
        console.log('cliiiiiiiiiiiiiiiiiiii');
    });

    $('#btnFusion').click(function() {

      $("#commentForm").validate();
    });
});
/****************************FIN CHEKBOX POUR FUSION*********************************/

/**Debut Modifier Produit */
function mdf_Designation(idDesignation,id,ordre){
    $.ajax({
        url:"ajax/catalogueSqlAjax.php",
        method:"POST",
        data:{
            operation:'SearchProduit',
            idDesignation : idDesignation,
            id : id,
        },
        success: function (data) {
            result=data.split('<>');
            $('#ordre_Mdf').val(ordre);
            $('#id_Mdf').val(id);
            $('#idDesignation_Mdf').val(result[0]);
            $('#designation_Mdf').val(result[1]);
            $('#categorie_Mdf').val(result[2]);
            $('#uniteStock_Mdf').val(result[3]);
            $('#nbArticleUniteStock_Mdf').val(result[4]);
            $('#prix_Mdf').val(result[5]);
            $('#prixuniteStock_Mdf').val(result[6]);
            $('#modifierDesignation').modal('show');
        },
        error: function() {
            alert("La requête "); },
        dataType:"text"
    });
}
$(function(){
    $("#btnModifierDesignation").click( function(){
        var ordre=$('#ordre_Mdf').val();
        var id=$('#id_Mdf').val();
        var idDesignation=$('#idDesignation_Mdf').val();
        var designation=$('#designation_Mdf').val();
        var categorie=$('#categorie_Mdf').val();
        var uniteStock=$('#uniteStock_Mdf').val();
        var nbreArticleUS=$('#nbArticleUniteStock_Mdf').val();
        var prix=$('#prix_Mdf').val();
        var prixUS=$('#prixuniteStock_Mdf').val();
        //alert(id);
        $.ajax({
            url:"ajax/catalogueSqlAjax.php",
            method:"POST",
            data:{
                operation: 'EditProduit',
                idDesignation : idDesignation,
                id : id,
                designation : designation,
                categorie : categorie,
                uniteStock : uniteStock,
                nbreArticleUS : nbreArticleUS,
                prix : prix,
                prixUS : prixUS
            },
            success: function (data) {
                if(data==1){
                    $('#modifierDesignation').modal('hide');
                    var table = $('#tableDesignation').DataTable();
                    nv_ordre=ordre - 1;
                    var ligne = table.row(nv_ordre).data();
                    ligne[0] = ordre; ligne[1] = designation;
                    ligne[2] = categorie; ligne[3] = uniteStock;
                    ligne[4] = nbreArticleUS; ligne[5] = prix;
                    ligne[6] = prixUS; ligne[7] = 'En cours';
                    $('#tableDesignation').dataTable().fnUpdate(ligne,nv_ordre,undefined,false);
                    $( table.row(nv_ordre).nodes() ).css({ 'background-color' : 'yellow'});
                }
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        });
    });
});
/**Fin Modifier Produit */

/**Debut Modifier Produit Pharmacie */
function mdf_Designation_PH(idDesignation,id,ordre){
  var designation = $('#designationD-' + idDesignation).val();
  var categorie = $('#categorieD-' + idDesignation).val();
  var forme = $('#formeD-' + idDesignation).val();
  var tableau = $('#tableauD-' + idDesignation).val();
  var prixSession = $('#prixSessionD-' + idDesignation).val();
  var prixPublic = $('#prixPublicD-' + idDesignation).val();
  var prixPublic = $('#prixPublicD-' + idDesignation).val();

  // console.log('GGGGggggggggggGGGG'+idDesignation);
  $.ajax({
      url: "ajax/catalogueSqlAjax.php",
      method: "POST",
      data: {
          operation: 'EditProduit_PH',
          idDesignation : idDesignation,
          id : id,
          designation : designation,
          categorie : categorie,
          forme : forme,
          tableau : tableau,
          prixSession : prixSession,
          prixPublic : prixPublic
      },
      success: function(data) {
          tab = data.split('<>');
          if (tab[0] == 1) {
              $('#pencilmoD-'+ idDesignation).remove();
              $('#designationD-' + idDesignation).prop('disabled', true);
              $('#categorieD-' + idDesignation).prop('disabled', true);
              $('#formeD-' + idDesignation).prop('disabled', true);
              $('#tableauD-' + idDesignation).prop('disabled', true);
              $('#prixSessionD-' + idDesignation).prop('disabled', true);
              $('#prixPublicD-' + idDesignation).prop('disabled', true);
              $('#prixPublicD-' + idDesignation).prop('disabled', true);
          } else {
              $('#msg_info_js').modal('show');
          }
          //console.log(data);
      },
      error: function() {
          alert("La requête 2");
      },
      dataType: "text"
  });
}
// function mdf_Designation_PH(idDesignation,id,ordre){
//     $.ajax({
//         url:"ajax/catalogueSqlAjax.php",
//         method:"POST",
//         data:{
//             operation:'SearchProduit_PH',
//             idDesignation : idDesignation,
//             id : id,
//         },
//         success: function (data) {
//             result=data.split('<>');
//             $('#ordre_Mdf').val(ordre);
//             $('#id_Mdf').val(id);
//             $('#idDesignation_Mdf').val(result[0]);
//             $('#designation_Mdf').val(result[1]);
//             $('#categorie_Mdf').val(result[2]);
//             $('#forme_Mdf').val(result[3]);
//             $('#tableau_Mdf').val(result[4]);
//             $('#prixSession_Mdf').val(result[5]);
//             $('#prixPublic_Mdf').val(result[6]);
//             $('#modifierDesignation_PH').modal('show');
//         },
//         error: function() {
//             alert("La requête "); },
//         dataType:"text"
//     });
// }
// $(function(){
//     $("#btnModifierDesignation_PH").click( function(){
//         var ordre=$('#ordre_Mdf').val();
//         var id=$('#id_Mdf').val();
//         var idDesignation=$('#idDesignation_Mdf').val();
//         var designation=$('#designation_Mdf').val();
//         var categorie=$('#categorie_Mdf').val();
//         var forme=$('#forme_Mdf').val();
//         var tableau=$('#tableau_Mdf').val();
//         var prixSession=$('#prixSession_Mdf').val();
//         var prixPublic=$('#prixPublic_Mdf').val();
//         //alert(id);
//         $.ajax({
//             url:"ajax/catalogueSqlAjax.php",
//             method:"POST",
//             data:{
//                 operation: 'EditProduit_PH',
//                 idDesignation : idDesignation,
//                 id : id,
//                 designation : designation,
//                 categorie : categorie,
//                 forme : forme,
//                 tableau : tableau,
//                 prixSession : prixSession,
//                 prixPublic : prixPublic
//             },
//             success: function (data) {
//                 if(data==1){
//                     $('#modifierDesignation_PH').modal('hide');
//                     var table = $('#tableDesignation').DataTable();
//                     nv_ordre=ordre - 1;
//                     var ligne = table.row(nv_ordre).data();
//                     ligne[0] = designation;
//                     ligne[1] = 'idFusion';
//                     ligne[2] = categorie; ligne[3] = forme;
//                     ligne[4] = tableau; ligne[5] = prixSession;
//                     ligne[6] = prixPublic; ligne[7] = 'En cours';
//                     $('#tableDesignation').dataTable().fnUpdate(ligne,nv_ordre,undefined,false);
//                     $( table.row(nv_ordre).nodes() ).css({ 'background-color' : 'yellow'});
//                 }
//             },
//             error: function() {
//                 alert("La requête "); },
//             dataType:"text"
//         });
//     });
// });
/**Fin Modifier Produit Pharmacie */

/**Debut Supprimer Produit */
function spm_Designation(idDesignation,id,ordre){
    $.ajax({
        url:"ajax/catalogueSqlAjax.php",
        method:"POST",
        data:{
            operation:'SearchProduit',
            idDesignation : idDesignation,
            id : id,
        },
        success: function (data) {
            result=data.split('<>');
            $('#ordre_Spm').val(ordre);
            $('#id_Spm').val(id);
            $('#idDesignation_Spm').val(result[0]);
            $('#designation_Spm').text(result[1]);
            $('#categorie_Spm').text(result[2]);
            $('#uniteStock_Spm').text(result[3]);
            $('#nbArticleUniteStock_Spm').text(result[4]);
            $('#prix_Spm').text(result[5]);
            $('#prixuniteStock_Spm').text(result[6]);
            $('#supprimerDesignation').modal('show');
        },
        error: function() {
            alert("La requête "); },
        dataType:"text"
    });
}
$(function(){
    $("#btnSupprimerDesignation").click( function(){
        var ordre=$('#ordre_Spm').val();
        var id=$('#id_Spm').val();
        var idDesignation=$('#idDesignation_Spm').val();
        $.ajax({
            url:"ajax/catalogueSqlAjax.php",
            method:"POST",
            data:{
                operation: 'DeleteProduit',
                idDesignation : idDesignation,
                id : id,
            },
            success: function (data) {
                result=data.split('<>');
                if(result[0]==1){
                    $('#supprimerDesignation').modal('hide');
                    var table = $('#tableDesignation').DataTable();
                    nv_ordre=ordre - 1;
                    var ligne = table.row(nv_ordre).data();
                    ligne[0] = ordre; ligne[1] = result[1];
                    ligne[2] = result[2]; ligne[3] = result[3];
                    ligne[4] = result[4]; ligne[5] = result[5];
                    ligne[6] = result[6]; ligne[7] = 'En cours';
                    $('#tableDesignation').dataTable().fnUpdate(ligne,nv_ordre,undefined,false);
                    $( table.row(nv_ordre).nodes() ).css({ 'background-color' : 'red'});
                }
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        });
    });
});
/**Fin Supprimer Produit */

/**Debut Supprimer Produit Pharmacie */
function spm_Designation_PH(idDesignation,id,ordre){
    $.ajax({
        url:"ajax/catalogueSqlAjax.php",
        method:"POST",
        data:{
            operation:'SearchProduit_PH',
            idDesignation : idDesignation,
            id : id,
        },
        success: function (data) {
            result=data.split('<>');
            $('#ordre_Spm').val(ordre);
            $('#id_Spm').val(id);
            $('#idDesignation_Spm').val(result[0]);
            $('#designation_Spm').text(result[1]);
            $('#categorie_Spm').text(result[2]);
            $('#forme_Spm').text(result[3]);
            $('#tableau_Spm').text(result[4]);
            $('#prixSession_Spm').text(result[5]);
            $('#prixPublic_Spm').text(result[6]);
            $('#supprimerDesignation_PH').modal('show');
        },
        error: function() {
            alert("La requête "); },
        dataType:"text"
    });
}
$(function(){
    $("#btnSupprimerDesignation_PH").click( function(){
        var ordre=$('#ordre_Spm').val();
        var id=$('#id_Spm').val();
        var idDesignation=$('#idDesignation_Spm').val();
        $.ajax({
            url:"ajax/catalogueSqlAjax.php",
            method:"POST",
            data:{
                operation: 'DeleteProduit_PH',
                idDesignation : idDesignation,
                id : id,
            },
            success: function (data) {
                result=data.split('<>');
                if(result[0]==1){
                    $('#supprimerDesignation_PH').modal('hide');
                    var table = $('#tableDesignation').DataTable();
                    nv_ordre=ordre - 1;
                    var ligne = table.row(nv_ordre).data();
                    ligne[0] = result[1];
                    ligne[1] = 'idFusion';
                    ligne[2] = result[2]; ligne[3] = result[3];
                    ligne[4] = result[4]; ligne[5] = result[5];
                    ligne[6] = result[6]; ligne[7] = 'En cours';
                    $('#tableDesignation').dataTable().fnUpdate(ligne,nv_ordre,undefined,false);
                    $( table.row(nv_ordre).nodes() ).css({ 'background-color' : 'red'});
                }
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        });
    });
});
/**Fin Supprimer Produit Pharmacie */

/**Debut Code Barre Produit Pharmacie */
function codeBR_Designation_PH(idDesignation,id,ordre){
    $.ajax({
        url:"ajax/catalogueSqlAjax.php",
        method:"POST",
        data:{
            operation:'SearchProduit_PH',
            idDesignation : idDesignation,
            id : id,
        },
        success: function (data) {
            result=data.split('<>');
            $('#idCT_Cbr').val(id);
            $('#idDesignation_Cbr').val(idDesignation);
            $('#ordre_Cbr').val(ordre);
            $('#designation_Cbarre').text(result[1]);
            $('#categorie_Cbarre').text(result[2]);
            $('#forme_Cbarre').text(result[3]);
            $('#tableau_Cbarre').text(result[4]);
            $('#valeur_Cbarre').val(result[7]);
            $('#codeBRDesignation_PH').modal('show');
        },
        error: function() {
            alert("La requête "); },
        dataType:"text"
    });
}
$(function(){
    $("#btnModifierCBarre_PH").click( function(){

        var ordre=$('#ordre_Cbr').val();
        var id=$('#idCT_Cbr').val();
        var idDesignation=$('#idDesignation_Cbr').val();
        var codeBarreDesignation=$('#valeur_Cbarre').val();
        $.ajax({
            url:"ajax/catalogueSqlAjax.php",
            method:"POST",
            data:{
                operation: 'EditCodeBarre_PH',
                idDesignation : idDesignation,
                codeBarreDesignation : codeBarreDesignation,
                id : id,
            },
            success: function (data) {
                result=data.split('<>');
                if(result[0]==1){
                    $('#iconeCBarre-'+ idDesignation).remove();
                    $('#codeBRDesignation_PH').modal('hide');
                }
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        });
    });
});
/**Fin Code Barre Produit Pharmacie */

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


}

/**Debut Modifier Categorie Pharmacie*/
function mdf_Categorie_PH(idCategorie,idCatalogueTotal,ordre){
    $.ajax({
        url:"ajax/categorieSqlAjax.php",
        method:"POST",
        data:{
            operation:'SearchCategorie_PH',
            idCategorie : idCategorie,
            id : idCatalogueTotal,
        },
        success: function (data) {
            result=data.split('<>');
            $('#ordreC_Mdf').val(ordre);
            $('#idCate_Mdf').val(idCategorie);
            $('#idCataT_Mdf').val(result[0]);
            $('#nomCategorie_Mdf').val(result[1]);
            $('#nomCategorie_old').val(result[1]);
            $('#categorieParent_Mdf').val(result[2]);
            $('#modifierCategorie_PH').modal('show');
        },
        error: function() {
            alert("La requête "); },
        dataType:"text"
    });
}
$(function(){
    $("#btnModifierCategorie_PH").click( function(){

        var ordre=$('#ordreC_Mdf').val();
        var idCate=$('#idCate_Mdf').val();
        var idCataT=$('#idCataT_Mdf').val();
        var nomCategorie_Mdf=$('#nomCategorie_Mdf').val();
        var nomCategorie_old=$('#nomCategorie_old').val();
        var categorieParent_Mdf=$('#categorieParent_Mdf').val();
        //alert('8888888888'+ordre+idCate+idCataT+nomCategorie_Mdf+categorieParent_Mdf);
        $.ajax({
            url:"ajax/categorieSqlAjax.php",
            method:"POST",
            data:{
                operation: 'EditCategorie_PH',
                idCategorie : idCate,
                id : idCataT,
                nomCategorie : nomCategorie_Mdf,
                nomCategorie_old : nomCategorie_old,
                categorieParent : categorieParent_Mdf
            },
            success: function (data) {
                if(data==1){
                  //alert('1111'+data);
                    $('#modifierCategorie_PH').modal('hide');
                    var table = $('#tableCategorie').DataTable();
                    nv_ordre=ordre - 1;
                    var ligne = table.row(nv_ordre).data();
                    ligne[0] = nv_ordre;
                    ligne[1] = nomCategorie_Mdf;
                     ligne[2] = categorieParent_Mdf;
                     ligne[3] = 'En cours';
                    $('#tableCategorie').dataTable().fnUpdate(ligne,nv_ordre,undefined,false);
                    $( table.row(nv_ordre).nodes() ).css({ 'background-color' : 'yellow'});
                }
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        });
    });
});
/**Fin Modifier Categorie Pharmacie */

/**Debut Supprimer Categorie Pharmacie */
function spm_Categorie_PH(idCategorie,idCatalogueTotal,ordre){
  $.ajax({
      url:"ajax/categorieSqlAjax.php",
      method:"POST",
      data:{
          operation:'SearchCategorie_PH',
          idCategorie : idCategorie,
          id : idCatalogueTotal
      },
      success: function (data) {
          result=data.split('<>');
          $('#ordreC_Spm').val(ordre);
          $('#idCate_Spm').val(idCategorie);
          $('#idCataT_Spm').val(result[0]);
          $('#nomCategorie_Spm').val(result[1]);
          $('#categorieParent_Spm').val(result[2]);
          $('#supprimerCategorie_PH').modal('show');
      },
      error: function() {
          alert("La requête "); },
      dataType:"text"
  });
}
$(function(){
    $("#btnSupprimererCategorie_PH").click( function(){

        var ordre=$('#ordreC_Spm').val();
        var idCate=$('#idCate_Spm').val();
        var idCataT=$('#idCataT_Spm').val();
        var nomCategorie_Mdf=$('#nomCategorie_Spm').val();
        var categorieParent_Mdf=$('#categorieParent_Spm').val();
        //alert('8888888888'+ordre+idCate+idCataT+nomCategorie_Mdf+categorieParent_Mdf);
        $.ajax({
            url:"ajax/categorieSqlAjax.php",
            method:"POST",
            data:{
                operation: 'DeleteCategorie_PH',
                idCategorie : idCate,
                id : idCataT,
                nomCategorie : nomCategorie_Mdf,
                categorieParent : categorieParent_Mdf
            },
            success: function (data) {
                if(data==1){
                    $('#supprimerCategorie_PH').modal('hide');
                    var table = $('#tableCategorie').DataTable();
                    nv_ordre=ordre - 1;
                    var ligne = table.row(nv_ordre).data();
                    ligne[0] = nv_ordre;
                    ligne[1] = nomCategorie_Mdf;
                     ligne[2] = categorieParent_Mdf;
                     ligne[3] = 'Supprimée';
                    $('#tableCategorie').dataTable().fnUpdate(ligne,nv_ordre,undefined,false);
                    $( table.row(nv_ordre).nodes() ).css({ 'background-color' : 'yellow'});
                }
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        });
    });
});
/**Fin Supprimer Categorie Pharmacie */
/**Debut Modifier Categorie Pharmacie*/
function mdf_Forme_PH(idForme,idCatalogueTotal,ordre){
  console.log('Le ordre'+ordre);
    $.ajax({
        url:"ajax/FormeSqlAjax.php",
        method:"POST",
        data:{
            operation:'SearchForme_PH',
            idForme : idForme,
            id : idCatalogueTotal
        },
        success: function (data) {
            result=data.split('<>');
            //console.log("bbbbbbbbb"+result[1]);
            $('#ordreF_Mdf').val(ordre);
            $('#idForme_Mdf').val(idForme);
            $('#idCataTF_Mdf').val(idCatalogueTotal);
            $('#nomForme_Mdf').val(result[1]);
            $('#nomForme_old').val(result[1]);
            $('#modifierForme_PH').modal('show');
        },
        error: function() {
            alert("La requête "); },
        dataType:"text"
    });
}
$(function(){
    $("#btnModifierForme_PH").click( function(){

        var ordre=$('#ordreF_Mdf').val();
        var idForme=$('#idForme_Mdf').val();
        var idCataT=$('#idCataTF_Mdf').val();
        var nomForme_Mdf=$('#nomForme_Mdf').val();
        var nomForme_old=$('#nomForme_old').val();
        //alert('8888888888'+ordre+idCate+idCataT+nomCategorie_Mdf+categorieParent_Mdf);
        $.ajax({
            url:"ajax/formeSqlAjax.php",
            method:"POST",
            data:{
                operation: 'EditForme_PH',
                idForme : idForme,
                id : idCataT,
                nomForme : nomForme_Mdf,
                nomForme_old : nomForme_old
            },
            success: function (data) {
              //alert('0000'+data);
                if(data==1){
                  //alert('1111'+data);
                    $('#modifierForme_PH').modal('hide');
                    var table = $('#tableForme').DataTable();
                    //console.log('ORDRE'+ordre);
                    nv_ordre=ordre - 1;
                    var ligne = table.row(nv_ordre).data();
                    //console.log('2222');
                    ligne[0] = nv_ordre;
                    //console.log('3333');
                    ligne[1] = nomForme_Mdf;
                     ligne[2] = 'En cours';
                    $('#tableForme').dataTable().fnUpdate(ligne,nv_ordre,undefined,false);
                    $( table.row(nv_ordre).nodes() ).css({ 'background-color' : 'yellow'});
                }
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        });
    });
});
/**Fin Modifier Categorie Pharmacie */

/**Debut Supprimer Categorie Pharmacie */
function spm_Forme_PH(idForme,idCatalogueTotal,ordre){
  $.ajax({
      url:"ajax/formeSqlAjax.php",
      method:"POST",
      data:{
          operation:'SearchForme_PH',
          idForme : idForme,
          id : idCatalogueTotal
      },
      success: function (data) {
          result=data.split('<>');
          $('#ordreF_Spm').val(ordre);
          $('#idForme_Spm').val(idForme);
          $('#idCataTF_Spm').val(result[0]);
          $('#nomForme_Spm').val(result[1]);
          $('#supprimerForme_PH').modal('show');
      },
      error: function() {
          alert("La requête "); },
      dataType:"text"
  });
}
$(function(){
    $("#btnSupprimerForme_PH").click( function(){
              var ordre=$('#ordreF_Spm').val();
              var idForme=$('#idForme_Spm').val();
              var idCataT=$('#idCataTF_Spm').val();
              var nomForme_Spm=$('#nomForme_Spm').val();
              console.log("btn supp");
              //alert('8888888888'+ordre+idForme+idCataT+nomForme_Spm);
              $.ajax({
                  url:"ajax/formeSqlAjax.php",
                  method:"POST",
                  data:{
                      operation: 'DeleteForme_PH',
                      idForme : idForme,
                      id : idCataT,
                      nomForme : nomForme_Spm
                  },
                  success: function (data) {
                      if(data==1){
                          $('#supprimerForme_PH').modal('hide');
                          var table = $('#tableForme').DataTable();
                          nv_ordre=ordre - 1;
                          var ligne = table.row(nv_ordre).data();
                          ligne[0] = nv_ordre;
                          ligne[1] = nomForme_Spm;
                           ligne[2] = 'Supprimée';
                          $('#tableForme').dataTable().fnUpdate(ligne,nv_ordre,undefined,false);
                          $( table.row(nv_ordre).nodes() ).css({ 'background-color' : 'yellow'});
                      }
                  },
                  error: function() {
                      alert("La requête "); },
                  dataType:"text"
              });
    });
});
/**Fin Supprimer Categorie Pharmacie */

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