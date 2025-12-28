$(document).ready(function() {

  $('#listeServices').DataTable({
      'processing': true,
      'serverSide': true,
      'destroy': true,
      'serverMethod': 'post',
      'ajax': {
          'url':'datatables/insertionService_listeServices.php'
      },
      'dom': 'Blfrtip',
      "buttons": ['csv','print', 'excel', 'pdf'],
      "ordering": true,
      "order": [[0, 'desc']],
      'columns': [
        { data: 'idDesignation' }, 
        { data: 'designation' },
        { data: 'unite' },
        { data: 'prix' },
        { data: 'operations' },
      ],
      "fnCreatedRow": function( nRow, data, iDataIndex ) {
        $(nRow).attr('class', "service"+data['idDesignation']);
     },
     'columnDefs': [ 
      { "bVisible": false, "aTargets": [ 0 ] },
      {
      'targets': [4], /* column index */
      'orderable': false, /* true or false */
      }
    ]
  });

  
/**Debut Ajouter Service**/
  $("#btn_ajouter_Service").click( function(){
    reference=$('#inpt_ajt_Service_Reference').val();
    unite=$('#inpt_ajt_Service_Unite').val();
    prix=$('#inpt_ajt_Service_Prix').val();
    $.ajax({
        url:"calculs/insertionService.php",
        method:"POST",
        data:{
            operation:'ajouter_Service',
            reference : reference,
            unite : unite,
            prix : prix
        },
        success: function (data) {
            var details = JSON.parse(data);
            var ligne = "<tr class='service"+details[0]+"'>"+
            "<td>"+details[1]+"</td>"+
            "<td>"+details[2]+"</td>"+
            "<td>"+details[3]+"</td>"+
            "<td>"+
                "<a><img src='images/edit.png' align='middle' alt='modifier' onclick='modifier_Service("+details[0]+")' data-toggle='modal' /></a>&nbsp"+
                "<a><img src='images/drop.png' align='middle' alt='supprimer' onclick='supprimer_Service("+details[0]+")' data-toggle='modal' /></a>"+
            "</td>"+
            "</tr>";
            $("#listeServices").prepend(ligne);
            $('#ajouter_Service').modal('hide');
        },
        error: function() {
            alert("La requête "); },
        dataType:"text"
    });
  });
/**Fin Ajouter Service**/

/**Debut Modifier Service**/
  $(function(){
      modifier_Service= function (idService)  {
          $('#inpt_mdf_Service_idService').val('');
          $('#inpt_mdf_Service_Reference').val('');
          $('#inpt_mdf_Service_Unite').val('');
          $('#inpt_mdf_Service_Prix').val('');
          $.ajax({
              url:"calculs/insertionService.php",
              method:"POST",
              data:{
                  operation:'details_Service',
                  idService : idService,
              },
              success: function (data) {
                  var details = JSON.parse(data);
                  $('#inpt_mdf_Service_idService').val(details[0]);
                  $('#inpt_mdf_Service_Reference').val(details[1]);
                  $('#inpt_mdf_Service_Unite').val(details[2]);
                  $('#inpt_mdf_Service_Prix').val(details[3]);
                  $('#modifier_Service').modal('show');
              },
              error: function() {
                  alert("La requête "); },
              dataType:"text"
          });
      }
  });
  $("#btn_modifier_Service").click( function(){
      idService=$('#inpt_mdf_Service_idService').val();
      reference=$('#inpt_mdf_Service_Reference').val();
      unite=$('#inpt_mdf_Service_Unite').val();
      prix=$('#inpt_mdf_Service_Prix').val();
      $.ajax({
          url:"calculs/insertionService.php",
          method:"POST",
          data:{
              operation:'modifier_Service',
              idService : idService,
              reference : reference,
              unite : unite,
              prix: prix,    
          },
          success: function (data) {
              var details = JSON.parse(data);
              $('#listeServices tbody .service'+details[0]).each(function() {
                  $(this).find("td:eq(0)").html(details[1]);
                  $(this).find("td:eq(1)").html(details[2]);
                  $(this).find("td:eq(2)").html(details[3]);
                  $(this).animate({opacity: 0.65 }, "slow");
              });
              $('#modifier_Service').modal('hide');
          },
          error: function() {
              alert("La requête "); },
          dataType:"text"
      }); 
  });
/**Fin Modifier Service**/

/**Debut Supprimer Service**/
  $(function(){
      supprimer_Service= function (idService)  {
        $('#inpt_spm_Service_idService').val('');
        $('#span_spm_Service_Reference').text('');
        $('#span_spm_Service_Unite').text('');
        $('#span_spm_Service_Prix').text('');
        $.ajax({
            url:"calculs/insertionService.php",
            method:"POST",
            data:{
                operation:'details_Service',
                idService : idService,
            },
            success: function (data) {
                var details = JSON.parse(data);
                $('#inpt_spm_Service_idService').val(details[0]);
                $('#span_spm_Service_Reference').text(details[1]);
                $('#span_spm_Service_Unite').text(details[2]);
                $('#span_spm_Service_Prix').text(details[3]);
                $('#supprimer_Service').modal('show');
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        });
      }
  });
  $("#btn_supprimer_Service").click( function(){
      idService=$('#inpt_spm_Service_idService').val();
      $.ajax({
          url:"calculs/insertionService.php",
          method:"POST",
          data:{
              operation:'supprimer_Service',
              idService : idService
          },
          success: function (data) {
            $('#listeServices tbody .service'+idService).each(function() {
                $(this).animate({ opacity: 1/2 }, 1000);
                $(this).hide(3000, function () {
                    $(this).remove();
                });
            });
            $('#supprimer_Service').modal('hide');
          },
          error: function() {
              alert("La requête "); },
          dataType:"text"
      }); 
  });
/**Fin Supprimer Service**/

    id=$('#inpt_Produit_id').val();
    dateDebut=$('#inpt_Produit_dateDebut').val();
    dateFin=$('#inpt_Produit_dateFin').val();

    $('#listeSorties').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/detailsService_listeSorties.php',
            'data':{
                'id' : id,
                'dateDebut' : dateDebut,
                'dateFin' : dateFin
            }
        },
        'dom': 'Blfrtip',
        "buttons": ['csv','print', 'excel', 'pdf'],
        "ordering": true,
        "order": [[0, 'desc']],
        'columns': [
            { data: 'idLigne' },
            { data: 'dateJour' },
            { data: 'heurePagnet' },
            { data: 'quantite' },
            { data: 'unitevente' },
            { data: 'prixunitevente' },
            { data: 'prixtotal' },
            { data: 'facture' },
            { data: 'client' },
            { data: 'personnel' },
        ],
        'columnDefs': [ 
            { "bVisible": false, "aTargets": [ 0 ] },
            {
            'targets': [8,9], /* column index */
            'orderable': false, /* true or false */
            }
        ]
    }); 

    $.ajax({
        url:"calculs/insertionService.php",
        method:"POST",
        data:{
            operation:'service',
            idService : id,
            dateDebut : dateDebut,
            dateFin : dateFin
        },
        success: function (data) {
          var details = JSON.parse(data);
             $("#montantServices").text(details);
        },
        error: function() {
            console("La requête "); 
        },
        dataType:"text"
    });


});