/*
R�sum�:
Commentaire:
version:1.1
Auteur: Korka
Date de modification:
*/

 function validation(){
    //Si le champ est vide
      console.log('data33');
    //  alert("test radio button333");
 }
$(function() {
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

$(function(){
    $(document).on('click','input:checkbox[name=frites]',function(){
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
