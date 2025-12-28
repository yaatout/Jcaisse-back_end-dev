$(document).ready(function() {

    $('#listeComptes').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/compte_listeComptes.php'
        },
        'dom': 'Blfrtip',
        "buttons": ['csv','print', 'excel', 'pdf'],
        "ordering": true,
        "order": [[0, 'asc']],
        'columns': [
          { data: 'idCompte' },
          { data: 'nomCompte' },
          { data: 'typeCompte' },
          { data: 'numeroCompte' },
          { data: 'montantCompte' },
          { data: 'operations' },
          { data: 'image' }
        ],
        "fnCreatedRow": function( nRow, data, iDataIndex ) {
          $(nRow).attr('class', "Compte"+data['idCompte']);
       },
       'columnDefs': [ 
        { "bVisible": false, "aTargets": [ 0 ] },
        {
        'targets': [4,5,6], /* column index */
        'orderable': false, /* true or false */
        }
      ]
    }); 

    $.ajax({
      url:"calculs/compte.php",
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
    
  /**Debut Ajouter Compte**/
    $("#btn_ajouter_Compte").click( function(){
      nom=$('#inpt_ajt_Compte_Nom').val();
      adresse=$('#inpt_ajt_Compte_Adresse').val();
      telephone=$('#inpt_ajt_Compte_Telephone').val();
      banque=$('#slct_ajt_Compte_Banque').val();
      numBanque=$('#inpt_ajt_Compte_NumBanque').val();
      $.ajax({
          url:"calculs/compte.php",
          method:"POST",
          data:{
              operation:'ajouter_Compte',
              nom : nom,
              adresse : adresse,
              telephone : telephone,
              banque : banque,
              numBanque : numBanque,
          },
          success: function (data) {
              var details = JSON.parse(data);
              var ligne = "<tr class='Compte"+details[0]+"'>"+
              "<td>"+details[1]+"</td>"+
              "<td>"+details[2]+"</td>"+
              "<td>"+details[3]+"</td>"+
              "<td>"+details[4]+"</td>"+
              "<td>"+details[5]+"</td>"+
              "<td>"+
                 "<span class='alert-success'> 0 FCFA <a href='detailscompte.php?iDS="+details[0]+"'>Details </a></span>"+
              "</td>"+
              "<td>"+
                  "<a><img src='images/edit.png' align='middle' alt='modifier' onclick='modifier_Compte("+details[0]+")' data-toggle='modal' /></a>&nbsp"+
                  "<a><img src='images/drop.png' align='middle' alt='supprimer' onclick='supprimer_Compte("+details[0]+")' data-toggle='modal' /></a>"+
              "</td>"+
              "</tr>";
              $("#listeComptes").prepend(ligne);
              $('#ajouter_Compte').modal('hide');
          },
          error: function() {
              alert("La requête "); },
          dataType:"text"
      });
    });
  /**Fin Ajouter Compte**/

  /**Debut Modifier Compte**/
    $(function(){
        modifier_Compte= function (idCompte)  {
            $('#inpt_mdf_Compte_idCompte').val('');
            $('#inpt_mdf_Compte_Nom').val('');
            $('#inpt_mdf_Compte_Adresse').val('');
            $('#inpt_mdf_Compte_Telephone').val('');
            $('#slct_mdf_Compte_Banque').val('');
            $('#inpt_mdf_Compte_NumBanque').val('');
            $.ajax({
                url:"calculs/compte.php",
                method:"POST",
                data:{
                    operation:'details_Compte',
                    idCompte : idCompte,
                },
                success: function (data) {
                    var details = JSON.parse(data);
                    $('#inpt_mdf_Compte_idCompte').val(details[0]);
                    $('#inpt_mdf_Compte_Nom').val(details[1]);
                    $('#inpt_mdf_Compte_Adresse').val(details[2]);
                    $('#inpt_mdf_Compte_Telephone').val(details[3]);
                    $('#slct_mdf_Compte_Banque').val(details[4]);
                    $('#inpt_mdf_Compte_NumBanque').val(details[5]);
                    $('#modifier_Compte').modal('show');
                },
                error: function() {
                    alert("La requête "); },
                dataType:"text"
            });
        }
    });
    $("#btn_modifier_Compte").click( function(){
        idCompte=$('#inpt_mdf_Compte_idCompte').val();
        nom=$('#inpt_mdf_Compte_Nom').val();
        adresse=$('#inpt_mdf_Compte_Adresse').val();
        telephone=$('#inpt_mdf_Compte_Telephone').val();
        banque=$('#slct_mdf_Compte_Banque').val();
        numBanque=$('#inpt_mdf_Compte_NumBanque').val();
        $.ajax({
            url:"calculs/compte.php",
            method:"POST",
            data:{
                operation:'modifier_Compte',
                idCompte : idCompte,
                nom : nom,
                adresse : adresse,
                telephone : telephone,
                banque : banque,
                numBanque : numBanque,   
            },
            success: function (data) {
                var details = JSON.parse(data);
                $('#listeComptes tbody .Compte'+details[0]).each(function() {
                    $(this).find("td:eq(0)").html(details[1]);
                    $(this).find("td:eq(1)").html(details[2]);
                    $(this).find("td:eq(2)").html(details[3]);
                    $(this).find("td:eq(3)").html(details[4]);
                    $(this).find("td:eq(4)").html(details[5]);
                    $(this).find("td:eq(5)").html(details[6]);
                    $(this).animate({opacity: 0.65 }, "slow");
                });
                $('#modifier_Compte').modal('hide');
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        }); 
    });
  /**Fin Modifier Compte**/

  /**Debut Supprimer Compte**/
    $(function(){
        supprimer_Compte= function (idCompte)  {
          $('#inpt_spm_Compte_idCompte').val('');
          $('#span_spm_Compte_Nom').text('');
          $('#span_spm_Compte_Adresse').text('');
          $('#span_spm_Compte_Telephone').text('');
          $('#span_spm_Compte_Banque').text('');
          $('#span_spm_Compte_NumBanque').text('');
          $.ajax({
              url:"calculs/compte.php",
              method:"POST",
              data:{
                  operation:'details_Compte',
                  idCompte : idCompte,
              },
              success: function (data) {
                  var details = JSON.parse(data);
                  $('#inpt_spm_Compte_idCompte').val(details[0]);
                  $('#span_spm_Compte_Nom').text(details[1]);
                  $('#span_spm_Compte_Adresse').text(details[2]);
                  $('#span_spm_Compte_Telephone').text(details[3]);
                  $('#span_spm_Compte_Banque').text(details[4]);
                  $('#span_spm_Compte_NumBanque').text(details[5]);
                  $('#supprimer_Compte').modal('show');
              },
              error: function() {
                  alert("La requête "); },
              dataType:"text"
          });
        }
    });
    $("#btn_supprimer_Compte").click( function(){
        idCompte=$('#inpt_spm_Compte_idCompte').val();
        $.ajax({
            url:"calculs/compte.php",
            method:"POST",
            data:{
                operation:'supprimer_Compte',
                idCompte : idCompte
            },
            success: function (data) {
              $('#listeComptes tbody .Compte'+idCompte).each(function() {
                  $(this).animate({ opacity: 1/2 }, 1000);
                  $(this).hide(3000, function () {
                      $(this).remove();
                  });
              });
              $('#supprimer_Compte').modal('hide');
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        }); 
    });
  /**Fin Supprimer Compte**/


});