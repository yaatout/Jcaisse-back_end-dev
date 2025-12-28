$(document).ready(function() { 

    dateDebut=$('#inpt_Reservation_dateDebut').val();
    dateFin=$('#inpt_Reservation_dateFin').val();
    
    $('#listeReservations').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/reservation_listerReservations.php',
            'data':{
                'dateDebut' : dateDebut,
                'dateFin' : dateFin
            }
        },
        'dom': 'Blfrtip',
        "buttons": ['csv','print', 'excel', 'pdf'],
        "ordering": true,
        "order": [[4, 'desc']],
        'columns': [
          { data: 'idReservation' },  
          { data: 'nom' },
          { data: 'prenom' },
          { data: 'pays' },
          { data: 'dateReservation' },
          { data: 'dateArrivee' },
          { data: 'dateDepart' },
          { data: 'solde' },
          { data: 'etat' },
          { data: 'operations' }
        ],
        "fnCreatedRow": function( nRow, data, iDataIndex ) {
            $(nRow).attr('class', "reservation"+data['idReservation']);
        },
        'columnDefs': [ 
            { "bVisible": false, "aTargets": [ 0 ] },
            {
            'targets': [5,6,7,9], /* column index */
            'orderable': false, /* true or false */
            }
        ]
    });  

    $("#btn_ajouter_Reservation").on( "click", function (e){
        e.preventDefault();
        nom=$('#inpt_ajt_Reservation_Nom').val();
        prenom=$('#inpt_ajt_Reservation_Prenom').val();
        adresse=$('#inpt_ajt_Reservation_Adresse').val();
        telephone=$('#inpt_ajt_Reservation_Telephone').val();
        pays=$('#inpt_ajt_Reservation_Pays').val();
        dateReservation=$('#inpt_ajt_Reservation_Date').val();
        $.ajax({
            url:"calculs/reservation.php",
            method:"POST",
            data:{
                operation:'ajouter_Reservation',
                nom : nom,
                prenom : prenom,
                adresse : adresse,
                telephone : telephone,
                pays : pays,
                dateReservation : dateReservation,
            },
            success: function (data) {
                var details = JSON.parse(data);
                var ligne = "<tr class='reservation"+details[0]+"'>"+
                "<td>"+details[1]+"</td>"+
                "<td>"+details[2]+"</td>"+
                "<td>"+details[3]+"</td>"+
                "<td>"+details[4]+"</td>"+
                "<td></td>"+
                "<td></td>"+
                "<td>"+
                    "<span class='alert-danger'>0 FCFA <a href='detailsReservation.php?id="+details[0]+"'>Details </a></span>"+
                "</td>"+
                "<td>"+
                    "<button type='button' onclick='etat_Reservation("+details[0]+")'  class='btn btn-primary btn-xs' >  En attente </button>"+
                "</td>"+
                "<td>"+
                    "<a><img src='images/edit.png' align='middle' alt='modifier' onclick='modifier_Reservation("+details[0]+")' data-toggle='modal' /></a>&nbsp"+
                    "<a><img src='images/drop.png' align='middle' alt='supprimer' onclick='supprimer_Reservation("+details[0]+")' data-toggle='modal' /></a>&nbsp"+
                 "</td>"+
                "</tr>";
                $("#listeReservations").prepend(ligne);
                $('#ajouter_Reservation').modal('hide');
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        }); 

    });

    /**Debut Modifier Reservation**/
        $(function(){
            modifier_Reservation= function (idReservation)  {
                $('#inpt_mdf_Reservation_idReservation').val('');
                $('#inpt_mdf_Reservation_Nom').val('');
                $('#inpt_mdf_Reservation_Prenom').val('');
                $('#inpt_mdf_Reservation_Adresse').val('');
                $('#inpt_mdf_Reservation_Telephone').val('');
                $('#inpt_mdf_Reservation_Pays').val('');
                $('#inpt_mdf_Reservation_Date').val('');
                $.ajax({
                    url:"calculs/reservation.php",
                    method:"POST",
                    data:{
                        operation:'details_Reservation',
                        idReservation : idReservation
                    },
                    success: function (data) {
                        var details = JSON.parse(data);
                        $('#inpt_mdf_Reservation_idReservation').val(details[0]);
                        $('#inpt_mdf_Reservation_Nom').val(details[1]);
                        $('#inpt_mdf_Reservation_Prenom').val(details[2]);
                        $('#inpt_mdf_Reservation_Adresse').val(details[3]);
                        $('#inpt_mdf_Reservation_Telephone').val(details[4]);
                        $('#inpt_mdf_Reservation_Pays').val(details[5]);
                        $('#inpt_mdf_Reservation_Date').val(details[6]);
                        $('#modifier_Reservation').modal('show');
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                });
            }
        });
        $("#btn_modifier_Reservation").click( function(){
            idReservation=$('#inpt_mdf_Reservation_idReservation').val();
            nom=$('#inpt_mdf_Reservation_Nom').val();
            prenom=$('#inpt_mdf_Reservation_Prenom').val();
            adresse=$('#inpt_mdf_Reservation_Adresse').val();
            telephone=$('#inpt_mdf_Reservation_Telephone').val();
            pays=$('#inpt_mdf_Reservation_Pays').val();
            dateReservation=$('#inpt_mdf_Reservation_Date').val();
            $.ajax({
                url:"calculs/reservation.php",
                method:"POST",
                data:{
                    operation:'modifier_Reservation',
                    idReservation : idReservation,
                    nom : nom,
                    prenom : prenom,
                    adresse: adresse,    
                    telephone : telephone,
                    pays : pays,
                    dateReservation: dateReservation
                },
                success: function (data) {
                    var details = JSON.parse(data);
                    $('#listeReservations tbody .reservation'+details[0]).each(function() {
                        $(this).find("td:eq(0)").html(details[1]);
                        $(this).find("td:eq(1)").html(details[2]);
                        $(this).find("td:eq(2)").html(details[3]);
                        $(this).find("td:eq(3)").html(details[4]);
                        $(this).animate({opacity: 0.65 }, "slow");
                    });
                    $('#modifier_Reservation').modal('hide');
                },
                error: function() {
                    alert("La requête "); },
                dataType:"text"
            }); 
        });
    /**Fin Modifier Reservation**/

    /**Debut Supprimer Reservation**/
        $(function(){
            supprimer_Reservation= function (idReservation)  {
                $('#inpt_spm_Reservation_idReservation').val('');
                $('#span_spm_Reservation_Nom').text('');
                $('#span_spm_Reservation_Prenom').text('');
                $('#span_spm_Reservation_Adresse').text('');
                $('#span_spm_Reservation_Telephone').text('');
                $('#span_spm_Reservation_Pays').text('');
                $('#span_spm_Reservation_Date').text('');
                $.ajax({
                    url:"calculs/reservation.php",
                    method:"POST",
                    data:{
                        operation:'details_Reservation',
                        idReservation : idReservation,
                    },
                    success: function (data) {
                        var details = JSON.parse(data);
                        $('#inpt_spm_Reservation_idReservation').val(details[0]);
                        $('#span_spm_Reservation_Nom').text(details[1]);
                        $('#span_spm_Reservation_Prenom').text(details[2]);
                        $('#span_spm_Reservation_Adresse').text(details[3]);
                        $('#span_spm_Reservation_Telephone').text(details[4]);
                        $('#span_spm_Reservation_Pays').text(details[5]);
                        $('#span_spm_Reservation_Date').text(details[6]);
                        $('#supprimer_Reservation').modal('show');
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                }); 

            }
        });
        $("#btn_supprimer_Reservation").click( function(){
            idReservation=$('#inpt_spm_Reservation_idReservation').val();
            $.ajax({
                url:"calculs/reservation.php",
                method:"POST",
                data:{
                    operation:'supprimer_Reservation',
                    idReservation : idReservation
                },
                success: function (data) {
                    $('#listeReservations tbody .reservation'+idReservation).each(function() {
                        $(this).animate({ opacity: 1/2 }, 1000);
                        $(this).hide(3000, function () {
                            $(this).remove();
                        });
                    });
                    $('#supprimer_Reservation').modal('hide');
                },
                error: function() {
                    alert("La requête "); },
                dataType:"text"
            }); 
        });
    /**Fin Supprimer Reservation**/

    /**Debut Changer Etat Reservation**/
        $(function(){
            etat_Reservation= function (idReservation)  {
                $('#inpt_etat_Reservation_idReservation').val('');
                $('#span_etat_Reservation_Nom').text('');
                $('#span_etat_Reservation_Prenom').text('');
                $('#span_etat_Reservation_Adresse').text('');
                $('#span_etat_Reservation_Telephone').text('');
                $('#span_etat_Reservation_Pays').text('');
                $('#span_etat_Reservation_Date').text('');
                $.ajax({
                    url:"calculs/reservation.php",
                    method:"POST",
                    data:{
                        operation:'details_Reservation',
                        idReservation : idReservation,
                    },
                    success: function (data) {
                        var details = JSON.parse(data);
                        $('#inpt_etat_Reservation_idReservation').val(details[0]);
                        $('#span_etat_Reservation_Nom').text(details[1]);
                        $('#span_etat_Reservation_Prenom').text(details[2]);
                        $('#span_etat_Reservation_Adresse').text(details[3]);
                        $('#span_etat_Reservation_Telephone').text(details[4]);
                        $('#span_etat_Reservation_Pays').text(details[5]);
                        $('#span_etat_Reservation_Date').text(details[6]);
                        $('#etat_Reservation').modal('show');
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                }); 

            }
        });
        $("#btn_Reservation_EnAttente").click( function(){
            idReservation=$('#inpt_etat_Reservation_idReservation').val();
            $.ajax({
                url:"calculs/reservation.php",
                method:"POST",
                data:{
                    operation:'etat_Reservation',
                    idReservation : idReservation,
                    etat : 0,
                },
                success: function (data) {
                    $('#listeReservations tbody .reservation'+idReservation).each(function() {
                        $(this).find("td:eq(7)").html();
                        $(this).find("td:eq(7)").html("<button type='button' onclick='etat_Reservation("+idReservation+")'  class='btn btn-primary btn-xs' >  En attente </button>");
                        $(this).animate({opacity: 0.65 }, "slow");
                    });
                    $('#etat_Reservation').modal('hide');
                },
                error: function() {
                    alert("La requête "); },
                dataType:"text"
            }); 
        });
        $("#btn_Reservation_EnCours").click( function(){
            idReservation=$('#inpt_etat_Reservation_idReservation').val();
            $.ajax({
                url:"calculs/reservation.php",
                method:"POST",
                data:{
                    operation:'etat_Reservation',
                    idReservation : idReservation,
                    etat : 1,
                },
                success: function (data) {
                    var details = JSON.parse(data);
                    $('#listeReservations tbody .reservation'+idReservation).each(function() {
                        $(this).find("td:eq(7)").html();
                        $(this).find("td:eq(7)").html("<button type='button' onclick='etat_Reservation("+idReservation+")'  class='btn btn-success btn-xs' >  En cours </button>");
                        $(this).animate({opacity: 0.65 }, "slow");
                    });
                    $('#etat_Reservation').modal('hide');
                },
                error: function() {
                    alert("La requête "); },
                dataType:"text"
            }); 
        });
        $("#btn_Reservation_Terminer").click( function(){
            idReservation=$('#inpt_etat_Reservation_idReservation').val();
            $.ajax({
                url:"calculs/reservation.php",
                method:"POST",
                data:{
                    operation:'etat_Reservation',
                    idReservation : idReservation,
                    etat : 2,
                },
                success: function (data) {
                    var details = JSON.parse(data);
                    $('#listeReservations tbody .reservation'+idReservation).each(function() {
                        $(this).find("td:eq(7)").html();
                        $(this).find("td:eq(7)").html("<button type='button' onclick='etat_Reservation("+idReservation+")'  class='btn btn-danger btn-xs' > Terminer </button>");
                        $(this).animate({opacity: 0.65 }, "slow");
                    });
                    $('#etat_Reservation').modal('hide');
                },
                error: function() {
                    alert("La requête "); },
                dataType:"text"
            }); 
        });
    /**Fin Changer Etat Reservation**/

});
