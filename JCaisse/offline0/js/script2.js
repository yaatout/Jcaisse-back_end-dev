    $(document).on("click", "#addPanier", function(e) {          

        // $("#loading_gif_modal").modal("show");


        $(".img-load").show();

        $("#addPanier").attr("disabled","disabled");


        //$("#addPanier").attr("disabled","disabled");

        if (navigator.onLine == true) {
            $.ajax({

                url: "ajax/operationPanierAjax.php",

                method: "POST",

                data: {

                    operation: 1,

                    btnSavePagnetVente: 'btnSavePagnetVente',


                },

                success: function(data) { 

                    // alert(data)

                    $("#venteContent" ).load( "ajax/loadContainerAjax.php", function(data){ //get content from PHP page

                        // alert(data)

                        $(".loading-gif").hide();

                        // $("#loading_gif_modal").modal("hide");

                        $(".img-load").hide();

                        $(".codeBarreLigneVt").focus();

                    });

                    

                },

                error: function() {

                    //alert("La requête ");
                    

                },

                dataType: "text"

            });

        }
        else{

            $(".loading-gif").hide();

            // $("#loading_gif_modal").modal("hide");

            $(".img-load").hide();

            $(".codeBarreLigneVt").focus();
            function getPagnet(){

                const pagnier= localStorage.getItem("pagnet");
                if (pagnier==null) {  
                    return [];
                } 
                return JSON.parse(pagnier);
            }
            function savePanier(pagnet){

                localStorage.setItem("pagnet", JSON.stringify(pagnet));    
            }
            lesPaniers=JSON.parse(localStorage.getItem("pagnet"));
            users=JSON.parse(localStorage.getItem("User"));
            
            var i=1;
            if(lesPaniers!=null && users!=null){
                for(var j=0; j< lesPaniers.length;j++){
                    if(lesPaniers[j].verrouiller==0){
                        i=i+1;
                    }  
                }
            }

            if(i<=1  && users!=null){
                date=new Date();
                datejour=date.toLocaleDateString("es-CL");
                //alert(datejour);
                heure=date.toLocaleTimeString("fr");
            
                var pagnets=getPagnet();

                var Panier = new Object();
                Panier.idPagnet =pagnets.length;
                Panier.datepagej =datejour;
                Panier.type = 0;
                Panier.heurePagnet = heure;
                Panier.totalp = 0;
                Panier.remise = 0;
                Panier.apayerPagnet = 0;
                Panier.restourne = 0;
                Panier.versement = 0;
                Panier.verrouiller =0; 
                Panier.idClient =0;
                Panier.idVitrine =0;
                Panier.idCompte =0;
                Panier.avance=0
                Panier.dejaTerminer=0;
                Panier.image=null;
                Panier.synchronise=0;

                for(var j=0; j<users.length; j++){
                    Panier.iduser =users[j].idutilisateur;
                    break;
                }
                

                pagnets.push(Panier);
                savePanier(pagnets);
            }

            $("#somme_Total" + idPanier).text(somme_A_payer());
            $("#somme_Apayer" + idPanier).text((somme_A_payer()));

            function somme_A_payer(){
                const ligne=JSON.parse(localStorage.getItem("ligne"));
                const panier=JSON.parse(localStorage.getItem("pagnet"));

                if(ligne!=null){
                    var idP=panier.find(p=>p.idPagnet==panier[panier.length-1].idPagnet);
                    var sommeT=0;
                        for(var i=ligne.length-1; 0<=i;i--){  
            
                            if(idP.idPagnet==ligne[i].idPagnet){
            
                                sommeT=sommeT+(ligne[i].quantite * ligne[i].prixunitevente);
                            }
                        }

                        return sommeT;
                    }
            }

            alert("La connexion est interrompue!\nVous pouvez continuez en mode hors connexion.");
            window.location.replace("/JCaisse/offline/insertionLigneLight.html");
            //$("#idPanier").load("container.html");
            
        }

        $("#collapse1").collapse('hide');

        var lcd=$("#lcd_Machine").val();

        if(lcd==1){

            var quantite=" ";

            var prix=0;

            $.ajax({

                url : "http://localhost:8080/app.js", // Url of backend (can be python, php, etc..)

                type: "GET", // data type (can be get, post, put, delete)

                data: { 

                    "quantite": quantite, 

                    "prix": prix,

                }, // data in json format

                async : false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)

                success: function(response) {

                    console.log(response);

                },

            });

        }

    }) 