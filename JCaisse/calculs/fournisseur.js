$(document).ready(function() {

    $('#listeFournisseurs').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/fournisseur_listeFournisseurs.php'
        },
        'dom': 'Blfrtip',
        "buttons": ['csv','print', 'excel', 'pdf'],
        "ordering": true,
        "order": [[0, 'desc']],
        'columns': [
          { data: 'idFournisseur' },
          { data: 'nomFournisseur' },
          { data: 'adresseFournisseur' },
          { data: 'telephoneFournisseur' },
          { data: 'banqueFournisseur' },
          { data: 'numBanqueFournisseur' },
          { data: 'montant' },
          { data: 'operations' }
        ],
        "fnCreatedRow": function( nRow, data, iDataIndex ) {
          $(nRow).attr('class', "fournisseur"+data['idFournisseur']);
       },
       'columnDefs': [ 
        { "bVisible": false, "aTargets": [ 0 ] },
        {
        'targets': [6,7], /* column index */
        'orderable': false, /* true or false */
        }
      ]
    }); 

    $.ajax({
      url:"calculs/fournisseur.php",
      method:"POST",
      data:{
          operation:'montant',
      },
      success: function (data) {
        var details = JSON.parse(data);
           $("#montantSolde").text(details[0]);
          //console.log(data);*/
      },
      error: function() {
          console("La requête "); 
      },
      dataType:"text"
  });
    
  /**Debut Ajouter Fournisseur**/
    $("#btn_ajouter_Fournisseur").click( function(){
      nom=$('#inpt_ajt_Fournisseur_Nom').val();
      adresse=$('#inpt_ajt_Fournisseur_Adresse').val();
      telephone=$('#inpt_ajt_Fournisseur_Telephone').val();
      banque=$('#slct_ajt_Fournisseur_Banque').val();
      numBanque=$('#inpt_ajt_Fournisseur_NumBanque').val();
      $.ajax({
          url:"calculs/fournisseur.php",
          method:"POST",
          data:{
              operation:'ajouter_Fournisseur',
              nom : nom,
              adresse : adresse,
              telephone : telephone,
              banque : banque,
              numBanque : numBanque,
          },
          success: function (data) {
              var details = JSON.parse(data);
              var ligne = "<tr class='fournisseur"+details[0]+"'>"+
              "<td>"+details[1]+"</td>"+
              "<td>"+details[2]+"</td>"+
              "<td>"+details[3]+"</td>"+
              "<td>"+details[4]+"</td>"+
              "<td>"+details[5]+"</td>"+
              "<td>"+
                 "<span class='alert-success'> 0 FCFA <a href='detailsFournisseur.php?iDS="+details[0]+"'>Details </a></span>"+
              "</td>"+
              "<td>"+
                  "<a><img src='images/edit.png' align='middle' alt='modifier' onclick='modifier_Fournisseur("+details[0]+")' data-toggle='modal' /></a>&nbsp"+
                  "<a><img src='images/drop.png' align='middle' alt='supprimer' onclick='supprimer_Fournisseur("+details[0]+")' data-toggle='modal' /></a>"+
              "</td>"+
              "</tr>";
              $("#listeFournisseurs").prepend(ligne);
              $('#ajouter_Fournisseur').modal('hide');
          },
          error: function() {
              alert("La requête "); },
          dataType:"text"
      });
    });
  /**Fin Ajouter Fournisseur**/

  /**Debut Modifier Fournisseur**/
    $(function(){
        modifier_Fournisseur= function (idFournisseur)  {
            $('#inpt_mdf_Fournisseur_idFournisseur').val('');
            $('#inpt_mdf_Fournisseur_Nom').val('');
            $('#inpt_mdf_Fournisseur_Adresse').val('');
            $('#inpt_mdf_Fournisseur_Telephone').val('');
            $('#slct_mdf_Fournisseur_Banque').val('');
            $('#inpt_mdf_Fournisseur_NumBanque').val('');
            $.ajax({
                url:"calculs/fournisseur.php",
                method:"POST",
                data:{
                    operation:'details_Fournisseur',
                    idFournisseur : idFournisseur,
                },
                success: function (data) {
                    var details = JSON.parse(data);
                    $('#inpt_mdf_Fournisseur_idFournisseur').val(details[0]);
                    $('#inpt_mdf_Fournisseur_Nom').val(details[1]);
                    $('#inpt_mdf_Fournisseur_Adresse').val(details[2]);
                    $('#inpt_mdf_Fournisseur_Telephone').val(details[3]);
                    $('#slct_mdf_Fournisseur_Banque').val(details[4]);
                    $('#inpt_mdf_Fournisseur_NumBanque').val(details[5]);
                    $('#modifier_Fournisseur').modal('show');
                },
                error: function() {
                    alert("La requête "); },
                dataType:"text"
            });
        }
    });
    $("#btn_modifier_Fournisseur").click( function(){
        idFournisseur=$('#inpt_mdf_Fournisseur_idFournisseur').val();
        nom=$('#inpt_mdf_Fournisseur_Nom').val();
        adresse=$('#inpt_mdf_Fournisseur_Adresse').val();
        telephone=$('#inpt_mdf_Fournisseur_Telephone').val();
        banque=$('#slct_mdf_Fournisseur_Banque').val();
        numBanque=$('#inpt_mdf_Fournisseur_NumBanque').val();
        $.ajax({
            url:"calculs/fournisseur.php",
            method:"POST",
            data:{
                operation:'modifier_Fournisseur',
                idFournisseur : idFournisseur,
                nom : nom,
                adresse : adresse,
                telephone : telephone,
                banque : banque,
                numBanque : numBanque,   
            },
            success: function (data) {
                var details = JSON.parse(data);
                $('#listeFournisseurs tbody .fournisseur'+details[0]).each(function() {
                    $(this).find("td:eq(0)").html(details[1]);
                    $(this).find("td:eq(1)").html(details[2]);
                    $(this).find("td:eq(2)").html(details[3]);
                    $(this).find("td:eq(3)").html(details[4]);
                    $(this).find("td:eq(4)").html(details[5]);
                    $(this).find("td:eq(5)").html(details[6]);
                    $(this).animate({opacity: 0.65 }, "slow");
                });
                $('#modifier_Fournisseur').modal('hide');
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        }); 
    });
  /**Fin Modifier Fournisseur**/

  /**Debut Supprimer Fournisseur**/
    $(function(){
        supprimer_Fournisseur= function (idFournisseur)  {
          $('#inpt_spm_Fournisseur_idFournisseur').val('');
          $('#span_spm_Fournisseur_Nom').text('');
          $('#span_spm_Fournisseur_Adresse').text('');
          $('#span_spm_Fournisseur_Telephone').text('');
          $('#span_spm_Fournisseur_Banque').text('');
          $('#span_spm_Fournisseur_NumBanque').text('');
          $.ajax({
              url:"calculs/fournisseur.php",
              method:"POST",
              data:{
                  operation:'details_Fournisseur',
                  idFournisseur : idFournisseur,
              },
              success: function (data) {
                  var details = JSON.parse(data);
                  $('#inpt_spm_Fournisseur_idFournisseur').val(details[0]);
                  $('#span_spm_Fournisseur_Nom').text(details[1]);
                  $('#span_spm_Fournisseur_Adresse').text(details[2]);
                  $('#span_spm_Fournisseur_Telephone').text(details[3]);
                  $('#span_spm_Fournisseur_Banque').text(details[4]);
                  $('#span_spm_Fournisseur_NumBanque').text(details[5]);
                  $('#supprimer_Fournisseur').modal('show');
              },
              error: function() {
                  alert("La requête "); },
              dataType:"text"
          });
        }
    });
    $("#btn_supprimer_Fournisseur").click( function(){
        idFournisseur=$('#inpt_spm_Fournisseur_idFournisseur').val();
        $.ajax({
            url:"calculs/fournisseur.php",
            method:"POST",
            data:{
                operation:'supprimer_Fournisseur',
                idFournisseur : idFournisseur
            },
            success: function (data) {
              $('#listeFournisseurs tbody .fournisseur'+idFournisseur).each(function() {
                  $(this).animate({ opacity: 1/2 }, 1000);
                  $(this).hide(3000, function () {
                      $(this).remove();
                  });
              });
              $('#supprimer_Fournisseur').modal('hide');
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        }); 
    });
  /**Fin Supprimer Fournisseur**/


});