$(document).ready(function() {

    $('#listeBiens').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/insertionBien_listeBiens.php'
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
          { data: 'prix' },
          { data: 'description' },
          { data: 'operations' }
        ],
        "fnCreatedRow": function( nRow, data, iDataIndex ) {
            $(nRow).attr('class', "Bien"+data['idDesignation']);
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

  /**Debut Ajouter Bien**/
    $(function(){
      ajouter_Bien= function ()  {
          $('#inpt_ajt_Bien_Reference').val('');
          $('#inpt_ajt_Bien_Numero').val(''); 
          $('#inpt_ajt_Bien_Type').val(''); 
          $('#inpt_ajt_Bien_Prix').val(0);
          $('#inpt_ajt_Bien_Description').val();
          $('#ajouter_Bien').modal('show');
      }
    });
    $("#btn_ajouter_Bien").click( function(){
      reference=$('#inpt_ajt_Bien_Reference').val();
      type=$('#inpt_ajt_Bien_Type').val();
      prix=$('#inpt_ajt_Bien_Prix').val();
      description=$('#inpt_ajt_Bien_Description').val();
      numero=$('#inpt_ajt_Bien_Numero').val();
      $.ajax({
          url:"calculs/insertionBien.php",
          method:"POST",
          data:{
              operation:'ajouter_Bien',
              reference : reference,
              type : type,
              prix : prix,
              description : description,
              numero : numero
          },
          success: function (data) {
              var details = JSON.parse(data);
              var ligne = "<tr class='Bien"+details[0]+"'>"+
              "<td>"+details[1]+"</td>"+
              "<td>"+details[2]+"</td>"+
              "<td>"+details[3]+"</td>"+
              "<td>"+details[4]+"</td>"+
              "<td>"+details[5]+"</td>"+
              "<td>"+
                "<a><img src='images/edit.png' align='middle' alt='modifier' onclick='modifier_Bien("+details[0]+")' data-toggle='modal' /></a>&nbsp"+
                "<a><img src='images/drop.png' align='middle' alt='supprimer' onclick='supprimer_Bien("+details[0]+")' data-toggle='modal' /></a>"+
              "</td>"+
              "</tr>";
              $("#listeBiens").prepend(ligne);
              $('#ajouter_Bien').modal('hide');
          },
          error: function() {
            $("#message_Bien_titre").text('Erreur');
            $("#message_Bien .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
            $('#message_Bien').modal('show');
          },
          dataType:"text"
      });
    });
  /**Fin Ajouter Bien**/

  /**Debut Modifier Bien**/
    $(function(){
      modifier_Bien= function (idBien)  {
          $('#inpt_mdf_Bien_idBien').val('');
          $('#inpt_mdf_Bien_Reference').val('');
          $('#inpt_mdf_Bien_Type').val('');
          $('#inpt_mdf_Bien_Prix').val('');
          $('#inpt_mdf_Bien_Description').val('');
          $('#inpt_mdf_Bien_Numero').val('');
          $.ajax({
              url:"calculs/insertionBien.php",
              method:"POST",
              data:{
                  operation:'details_Bien',
                  idBien : idBien,
              },
              success: function (data) {
                  var details = JSON.parse(data);
                  $('#inpt_mdf_Bien_idBien').val(details[0]);
                  $('#inpt_mdf_Bien_Reference').val(details[1]);
                  $('#inpt_mdf_Bien_Numero').val(details[2]);
                  $('#inpt_mdf_Bien_Type').val(details[3]);
                  $('#inpt_mdf_Bien_Prix').val(details[4]);
                  $('#inpt_mdf_Bien_Description').val(details[5]);
                  $('#modifier_Bien').modal('show');
              },
              error: function() {
                $("#message_Bien_titre").text('Erreur');
                $("#message_Bien .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
                $('#message_Bien').modal('show');
              },
              dataType:"text"
          });
      }
    });
    $("#btn_modifier_Bien").click( function(){
      idBien=$('#inpt_mdf_Bien_idBien').val();
      reference=$('#inpt_mdf_Bien_Reference').val();
      type=$('#inpt_mdf_Bien_Type').val();
      prix=$('#inpt_mdf_Bien_Prix').val();
      description=$('#inpt_mdf_Bien_Description').val();
      numero=$('#inpt_mdf_Bien_Numero').val();
        $.ajax({
          url:"calculs/insertionBien.php",
          method:"POST",
          data:{
              operation:'modifier_Bien',
              idBien : idBien,
              reference : reference,
              type : type,
              prix : prix,
              description : description,
              numero : numero
          },
          success: function (data) {
              var details = JSON.parse(data);
              $('#listeBiens tbody .Bien'+details[0]).each(function() {
                    $(this).find("td:eq(0)").html(details[1]);
                    $(this).find("td:eq(1)").html(details[2]);
                    $(this).find("td:eq(2)").html(details[3]);
                    $(this).find("td:eq(3)").html(details[4]);
                    $(this).find("td:eq(4)").html(details[5]);
                    $(this).find("td:eq(5)").html(details[6]);
                    $(this).animate({opacity: 0.65 }, "slow");
                    $('#modifier_Bien').modal('hide');
              });
              
          },
          error: function() {
            $("#message_Bien_titre").text('Erreur');
            $("#message_Bien .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
            $('#message_Bien').modal('show');
          },
          dataType:"text"
      }); 
    });
  /**Fin Modifier Bien**/

  /**Debut Supprimer Bien**/
    $(function(){
      supprimer_Bien= function (idBien)  {
          $('#inpt_spm_Bien_idBien').val('');
          $('#span_spm_Bien_Reference').text('');
          $('#span_spm_Bien_Type').text('');
          $('#span_spm_Bien_Prix').text('');
          $('#span_spm_Bien_Description').text('');
          $('#span_spm_Bien_Numero').text('');
          $.ajax({
              url:"calculs/insertionBien.php",
              method:"POST",
              data:{
                  operation:'details_Bien',
                  idBien : idBien,
              },
              success: function (data) {
                  var details = JSON.parse(data);
                $('#inpt_spm_Bien_idBien').val(details[0]);
                $('#span_spm_Bien_Reference').text(details[1]);
                $('#span_spm_Bien_Numero').text(details[2]);
                $('#span_spm_Bien_Type').text(details[3]);
                $('#span_spm_Bien_Prix').text(details[4]);
                $('#span_spm_Bien_Description').text(details[5]);

                
                $('#supprimer_Bien').modal('show');
              },
              error: function() {
                $("#message_Bien_titre").text('Erreur');
                $("#message_Bien .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
                $('#message_Bien').modal('show');
             },
              dataType:"text"
          }); 
      }
    });
    $("#btn_supprimer_Bien").click( function(){
      idBien=$('#inpt_spm_Bien_idBien').val();
      $.ajax({
          url:"calculs/insertionBien.php",
          method:"POST",
          data:{
              operation:'supprimer_Bien',
              idBien : idBien
          },
          success: function (data) {
            $('#listeBiens tbody .Bien'+idBien).each(function() {
                  $(this).animate({ opacity: 1/2 }, 1000);
                  $(this).hide(3000, function () {
                      $(this).remove();
                  });
            });
            $('#supprimer_Bien').modal('hide');
          },
          error: function() {
            $("#message_Bien_titre").text('Erreur');
            $("#message_Bien .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
            $('#message_Bien').modal('show');
          },
          dataType:"text"
      }); 
    });
  /**Fin Supprimer Bien**/


});