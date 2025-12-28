$(document).ready(function() {

    dateDebut=$('#inpt_Depense_dateDebut').val();
    dateFin=$('#inpt_Depense_dateFin').val();

    $('#listeDepenses').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/insertionDepense_listeDepenses.php'
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
          $(nRow).attr('class', "depense"+data['idDesignation']);
       },
       'columnDefs': [ 
        { "bVisible": false, "aTargets": [ 0 ] },
        {
        'targets': [4], /* column index */
        'orderable': false, /* true or false */
        }
      ]
    });

    $("#listeDepensesEvent").on( "click", function (e){
        e.preventDefault();
        $('#listeDepenses').DataTable({
            'processing': true,
            'serverSide': true,
            'destroy': true,
            'serverMethod': 'post',
            'ajax': {
                'url':'datatables/insertionDepense_listeDepenses.php'
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
              $(nRow).attr('class', "depense"+data['idDesignation']);
           },
           'columnDefs': [ 
            { "bVisible": false, "aTargets": [ 0 ] },
            {
            'targets': [4], /* column index */
            'orderable': false, /* true or false */
            }
          ]
        });
    });

    $("#listeMouvementsEvent").on( "click", function (e){
        e.preventDefault();
        $('#listeMouvements').DataTable({
            'processing': true,
            'serverSide': true,
            'destroy': true,
            'serverMethod': 'post',
            'ajax': {
                'url':'datatables/insertionDepense_listeMouvements.php',
                'data':{
                    'dateDebut' : dateDebut,
                    'dateFin' : dateFin
                }
            },
            'dom': 'Blfrtip',
            "buttons": ['csv','print', 'excel', 'pdf'],
            "ordering": true,
            "order": [[0, 'desc']],
            'columns': [
              { data: 'idDesignation' }, 
              { data: 'designation' },
              { data: 'quantite' },
              { data: 'montant' },
              { data: 'operations' },
            ],
            "fnCreatedRow": function( nRow, data, iDataIndex ) {
              $(nRow).attr('class', "depense"+data['idDesignation']);
           },
           'columnDefs': [ 
            { "bVisible": false, "aTargets": [ 0 ] },
            {
            'targets': [4], /* column index */
            'orderable': false, /* true or false */
            }
          ]
        });
    });

    
  /**Debut Ajouter Depense**/
    $("#btn_ajouter_Depense").click( function(){
      reference=$('#inpt_ajt_Depense_Reference').val();
      unite=$('#inpt_ajt_Depense_Unite').val();
      prix=$('#inpt_ajt_Depense_Prix').val();
      $.ajax({
          url:"calculs/insertionDepense.php",
          method:"POST",
          data:{
              operation:'ajouter_Depense',
              reference : reference,
              unite : unite,
              prix : prix
          },
          success: function (data) {
              var details = JSON.parse(data);
              var ligne = "<tr class='depense"+details[0]+"'>"+
              "<td>"+details[1]+"</td>"+
              "<td>"+details[2]+"</td>"+
              "<td>"+details[3]+"</td>"+
              "<td>"+
                  "<a><img src='images/edit.png' align='middle' alt='modifier' onclick='modifier_Depense("+details[0]+")' data-toggle='modal' /></a>&nbsp"+
                  "<a><img src='images/drop.png' align='middle' alt='supprimer' onclick='supprimer_Depense("+details[0]+")' data-toggle='modal' /></a>"+
              "</td>"+
              "</tr>";
              $("#listeDepenses").prepend(ligne);
              $('#ajouter_Depense').modal('hide');
          },
          error: function() {
              alert("La requête "); },
          dataType:"text"
      });
    });
  /**Fin Ajouter Depense**/

  /**Debut Modifier Depense**/
    $(function(){
        modifier_Depense= function (idDepense)  {
            $('#inpt_mdf_Depense_idDepense').val('');
            $('#inpt_mdf_Depense_Reference').val('');
            $('#inpt_mdf_Depense_Unite').val('');
            $('#inpt_mdf_Depense_Prix').val('');
            $.ajax({
                url:"calculs/insertionDepense.php",
                method:"POST",
                data:{
                    operation:'details_Depense',
                    idDepense : idDepense,
                },
                success: function (data) {
                    var details = JSON.parse(data);
                    $('#inpt_mdf_Depense_idDepense').val(details[0]);
                    $('#inpt_mdf_Depense_Reference').val(details[1]);
                    $('#inpt_mdf_Depense_Unite').val(details[2]);
                    $('#inpt_mdf_Depense_Prix').val(details[3]);
                    $('#modifier_Depense').modal('show');
                },
                error: function() {
                    alert("La requête "); },
                dataType:"text"
            });
        }
    });
    $("#btn_modifier_Depense").click( function(){
        idDepense=$('#inpt_mdf_Depense_idDepense').val();
        reference=$('#inpt_mdf_Depense_Reference').val();
        unite=$('#inpt_mdf_Depense_Unite').val();
        prix=$('#inpt_mdf_Depense_Prix').val();
        $.ajax({
            url:"calculs/insertionDepense.php",
            method:"POST",
            data:{
                operation:'modifier_Depense',
                idDepense : idDepense,
                reference : reference,
                unite : unite,
                prix: prix,    
            },
            success: function (data) {
                var details = JSON.parse(data);
                $('#listeDepenses tbody .depense'+details[0]).each(function() {
                    $(this).find("td:eq(0)").html(details[1]);
                    $(this).find("td:eq(1)").html(details[2]);
                    $(this).find("td:eq(2)").html(details[3]);
                    $(this).animate({opacity: 0.65 }, "slow");
                });
                $('#modifier_Depense').modal('hide');
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        }); 
    });
  /**Fin Modifier Depense**/

  /**Debut Supprimer Depense**/
    $(function(){
        supprimer_Depense= function (idDepense)  {
          $('#inpt_spm_Depense_idDepense').val('');
          $('#span_spm_Depense_Reference').text('');
          $('#span_spm_Depense_Unite').text('');
          $('#span_spm_Depense_Prix').text('');
          $.ajax({
              url:"calculs/insertionDepense.php",
              method:"POST",
              data:{
                  operation:'details_Depense',
                  idDepense : idDepense,
              },
              success: function (data) {
                  var details = JSON.parse(data);
                  $('#inpt_spm_Depense_idDepense').val(details[0]);
                  $('#span_spm_Depense_Reference').text(details[1]);
                  $('#span_spm_Depense_Unite').text(details[2]);
                  $('#span_spm_Depense_Prix').text(details[3]);
                  $('#supprimer_Depense').modal('show');
              },
              error: function() {
                  alert("La requête "); },
              dataType:"text"
          });
        }
    });
    $("#btn_supprimer_Depense").click( function(){
        idDepense=$('#inpt_spm_Depense_idDepense').val();
        $.ajax({
            url:"calculs/insertionDepense.php",
            method:"POST",
            data:{
                operation:'supprimer_Depense',
                idDepense : idDepense
            },
            success: function (data) {
              $('#listeDepenses tbody .depense'+idDepense).each(function() {
                  $(this).animate({ opacity: 1/2 }, 1000);
                  $(this).hide(3000, function () {
                      $(this).remove();
                  });
              });
              $('#supprimer_Depense').modal('hide');
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        }); 
    });
  /**Fin Supprimer Depense**/

  id=$('#inpt_Produit_id').val();
  dateDebut=$('#inpt_Produit_dateDebut').val();
  dateFin=$('#inpt_Produit_dateFin').val();

  $('#listeSorties').DataTable({
      'processing': true,
      'serverSide': true,
      'destroy': true,
      'serverMethod': 'post',
      'ajax': {
          'url':'datatables/detailsDepense_listeSorties.php',
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
          { data: 'description' },
          { data: 'image' },
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
    url:"calculs/insertionDepense.php",
    method:"POST",
    data:{
        operation:'depense',
        idDepense : id,
        dateDebut : dateDebut,
        dateFin : dateFin
    },
    success: function (data) {
        var details = JSON.parse(data);
        $("#montantDepenses").text(details);
    },
    error: function() {
        console("La requête "); 
    },
    dataType:"text"
});


});