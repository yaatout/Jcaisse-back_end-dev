$(document).ready(function() {

    $('#listeChambres').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/insertionChambre_listeChambres.php'
        },
        'dom': 'Blfrtip',
        "buttons": ['csv','print', 'excel', 'pdf'],
        "ordering": true,
        "order": [[0, 'desc']],
        "lengthMenu": [10, 25, 50, 100, 500, 5000, 7500],
        'columns': [
          { data: 'idDesignation' },
          { data: 'designation' },
          { data: 'codeBarreDesignation' },
          { data: 'type' },
          { data: 'categorie' },
          { data: 'prix' },
          { data: 'description' },
          { data: 'operations' }
        ],
        "fnCreatedRow": function( nRow, data, iDataIndex ) {
            $(nRow).attr('class', "chambre"+data['idDesignation']);
         },
        'columnDefs': [ 
            { "bVisible": false, "aTargets": [ 0 ] },
            {
            'targets': [5], /* column index */
            'orderable': false, /* true or false */
            "bSortable": true ,
            //"aTargets": [ 3 ], "bSortable": true 
         },
        ]
    });  

  /**Debut Ajouter Chambre**/
    $(function(){
      ajouter_Chambre= function ()  {
         var categorie='';
          $('#inpt_ajt_Chambre_Reference').val('');
          $('#inpt_ajt_Chambre_Numero').val(''); 
          $('#inpt_ajt_Chambre_Type').val(''); 
          //$('#slct_ajt_Chambre_Categorie option').remove();
          $('#inpt_ajt_Chambre_Prix').val(0);
          $('#inpt_ajt_Chambre_Description').val();

/*           $.ajax({
            url:"calculs/insertionChambre.php",
            method:"POST",
            data:{
                operation:'choix_Categorie',
                categorie : categorie,
            },
            success: function (data) {

                var choix = JSON.parse(data);
                var taille = choix.length;

                $('#slct_ajt_Chambre_Categorie').append("<option selected >--------------</option>");

                for(var i = 0; i<taille; i++){
                        $('#slct_ajt_Chambre_Categorie').append("<option value='"+choix[i][1]+"'>"+choix[i][1]+"</option>");
                } 

            },
            error: function() {
              $("#message_Produit_titre").text('Erreur');
              $("#message_Produit .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
              $('#message_Produit').modal('show');
            },
            dataType:"text"
        }); */

          $('#ajouter_Chambre').modal('show');
      }
    });

    $("#btn_ajouter_Chambre").click( function(){
      reference=$('#inpt_ajt_Chambre_Reference').val();
      categorie=$('#slct_ajt_Chambre_Categorie').val();
      type=$('#inpt_ajt_Chambre_Type').val();
      prix=$('#inpt_ajt_Chambre_Prix').val();
      description=$('#inpt_ajt_Chambre_Description').val();
      numero=$('#inpt_ajt_Chambre_Numero').val();

      $.ajax({
          url:"calculs/insertionChambre.php",
          method:"POST",
          data:{
              operation:'ajouter_Chambre',
              reference : reference,
              categorie : categorie,
              type : type,
              prix : prix,
              description : description,
              numero : numero
          },
          success: function (data) {
              var details = JSON.parse(data);
              var ligne = "<tr class='chambre"+details[0]+"'>"+
              "<td>"+details[1]+"</td>"+
              "<td>"+details[2]+"</td>"+
              "<td>"+details[3]+"</td>"+
              "<td>"+details[4]+"</td>"+
              "<td>"+details[5]+"</td>"+
              "<td>"+details[6]+"</td>"+
              "<td>"+
                "<a><img src='images/edit.png' align='middle' alt='modifier' onclick='modifier_Chambre("+details[0]+")' data-toggle='modal' /></a>&nbsp"+
                "<a><img src='images/drop.png' align='middle' alt='supprimer' onclick='supprimer_Chambre("+details[0]+")' data-toggle='modal' /></a>"+
              "</td>"+
              "</tr>";
              $("#listeChambres").prepend(ligne);
              $('#ajouter_Chambre').modal('hide');
          },
          error: function() {
            $("#message_Chambre_titre").text('Erreur');
            $("#message_Chambre .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
            $('#message_Chambre').modal('show');
          },
          dataType:"text"
      });
    });
  /**Fin Ajouter Chambre**/

  /**Debut Modifier Chambre**/
    $(function(){
      modifier_Chambre= function (idChambre)  {
          $('#inpt_mdf_Chambre_idChambre').val('');
          $('#inpt_mdf_Chambre_Reference').val('');
          $('#slct_mdf_Chambre_Categorie option').remove();
          $('#inpt_mdf_Chambre_Type').val('');
          $('#inpt_mdf_Chambre_Prix').val('');
          $('#inpt_mdf_Chambre_Description').val('');
          $('#inpt_mdf_Chambre_Numero').val('');
          $.ajax({
              url:"calculs/insertionChambre.php",
              method:"POST",
              data:{
                  operation:'details_Chambre',
                  idChambre : idChambre,
              },
              success: function (data) {
                  var details = JSON.parse(data);
                  $('#inpt_mdf_Chambre_idChambre').val(details[0]);
                  $('#inpt_mdf_Chambre_Reference').val(details[1]);
                  $('#inpt_mdf_Chambre_Numero').val(details[2]);
                  $('#inpt_mdf_Chambre_Type').val(details[3]);
                  $('#inpt_mdf_Chambre_Prix').val(details[5]);
                  $('#inpt_mdf_Chambre_Description').val(details[6]);
                  $('#slct_mdf_Chambre_Categorie').append("<option value='"+details[4]+"'>"+details[4]+"</option>"); 
                  options = ['Aubonne','Chateau de Rolle','Gimel','Gland','Goree','Mont sur Rolle','Rolle'];
                  var taille = options.length;
                  for(var i = 0; i<taille; i++){
                      if(options[i]!=details[4]){
                        $('#slct_mdf_Chambre_Categorie').append("<option value='"+options[i]+"'>"+options[i]+"</option>");
                      }
                  }

/*                   $.ajax({
                    url:"calculs/insertionChambre.php",
                    method:"POST",
                    data:{
                        operation:'choix_Categorie',
                        categorie : details[4],
                    },
                    success: function (data) {
      
                        var choix = JSON.parse(data);
                        var taille = choix.length;
      
                        $('#slct_mdf_Chambre_Categorie').append("<option selected value='"+details[4]+"'>"+details[4]+"</option>");
      
                        for(var i = 0; i<taille; i++){
                                $('#slct_mdf_Chambre_Categorie').append("<option value='"+choix[i][1]+"'>"+choix[i][1]+"</option>");
                        }
      
                    },
                    error: function() {
                        $("#message_Chambre_titre").text('Erreur');
                        $("#message_Chambre .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
                        $('#message_Chambre').modal('show');
                    },
                    dataType:"text"
                  }); 
 */
                  $('#modifier_Chambre').modal('show');
              },
              error: function() {
                $("#message_Chambre_titre").text('Erreur');
                $("#message_Chambre .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
                $('#message_Chambre').modal('show');
              },
              dataType:"text"
          });
      }
    });
    $("#btn_modifier_Chambre").click( function(){
      idChambre=$('#inpt_mdf_Chambre_idChambre').val();
      reference=$('#inpt_mdf_Chambre_Reference').val();
      categorie=$('#slct_mdf_Chambre_Categorie').val();
      type=$('#inpt_mdf_Chambre_Type').val();
      prix=$('#inpt_mdf_Chambre_Prix').val();
      description=$('#inpt_mdf_Chambre_Description').val();
      numero=$('#inpt_mdf_Chambre_Numero').val();
        $.ajax({
          url:"calculs/insertionChambre.php",
          method:"POST",
          data:{
              operation:'modifier_Chambre',
              idChambre : idChambre,
              reference : reference,
              categorie : categorie,
              type : type,
              prix : prix,
              description : description,
              numero : numero
          },
          success: function (data) {
              var details = JSON.parse(data);
              $('#listeChambres tbody .chambre'+details[0]).each(function() {
                    $(this).find("td:eq(0)").html(details[1]);
                    $(this).find("td:eq(1)").html(details[8]);
                    $(this).find("td:eq(2)").html(details[3]);
                    $(this).find("td:eq(3)").html(details[4]);
                    $(this).find("td:eq(4)").html(details[5]);
                    $(this).find("td:eq(5)").html(details[6]);
                    $(this).find("td:eq(6)").html(details[7]);
                    $(this).animate({opacity: 0.65 }, "slow");
                    $('#modifier_Chambre').modal('hide');
              });
              
          },
          error: function() {
            $("#message_Chambre_titre").text('Erreur');
            $("#message_Chambre .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
            $('#message_Chambre').modal('show');
          },
          dataType:"text"
      }); 
    });
  /**Fin Modifier Chambre**/

  /**Debut Supprimer Chambre**/
    $(function(){
      supprimer_Chambre= function (idChambre)  {
          $('#inpt_spm_Chambre_idChambre').val('');
          $('#span_spm_Chambre_Reference').text('');
          $('#span_spm_Chambre_Categorie').text('');
          $('#span_spm_Chambre_Type').text('');
          $('#span_spm_Chambre_Prix').text('');
          $('#span_spm_Chambre_Description').text('');
          $('#span_spm_Chambre_Numero').text('');
          $.ajax({
              url:"calculs/insertionChambre.php",
              method:"POST",
              data:{
                  operation:'details_Chambre',
                  idChambre : idChambre,
              },
              success: function (data) {
                  var details = JSON.parse(data);
                $('#inpt_spm_Chambre_idChambre').val(details[0]);
                $('#span_spm_Chambre_Reference').text(details[1]);
                $('#span_spm_Chambre_Numero').text(details[2]);
                $('#span_spm_Chambre_Type').text(details[3]);
                $('#span_spm_Chambre_Categorie').text(details[4]);
                $('#span_spm_Chambre_Prix').text(details[5]);
                $('#span_spm_Chambre_Description').text(details[6]);

                
                $('#supprimer_Chambre').modal('show');
              },
              error: function() {
                $("#message_Chambre_titre").text('Erreur');
                $("#message_Chambre .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
                $('#message_Chambre').modal('show');
             },
              dataType:"text"
          }); 
      }
    });
    $("#btn_supprimer_Chambre").click( function(){
      idChambre=$('#inpt_spm_Chambre_idChambre').val();
      $.ajax({
          url:"calculs/insertionChambre.php",
          method:"POST",
          data:{
              operation:'supprimer_Chambre',
              idChambre : idChambre
          },
          success: function (data) {
            $('#listeChambres tbody .chambre'+idChambre).each(function() {
                  $(this).animate({ opacity: 1/2 }, 1000);
                  $(this).hide(3000, function () {
                      $(this).remove();
                  });
            });
            $('#supprimer_Chambre').modal('hide');
          },
          error: function() {
            $("#message_Chambre_titre").text('Erreur');
            $("#message_Chambre .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
            $('#message_Chambre').modal('show');
          },
          dataType:"text"
      }); 
    });
  /**Fin Supprimer Chambre**/


});