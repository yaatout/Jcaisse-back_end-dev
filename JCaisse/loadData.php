
<?php


session_start();


if(!$_SESSION['iduser']){



	header('Location:../index.php');



}

?>


<!DOCTYPE html>



<html>



<head>



  <meta charset="utf-8">



   <link rel="shortcut icon" href="../images/favicon.png"> 



   <link rel="stylesheet" href="../css/bootstrap.css">



  <style>



    .content2{



        text-align:right;



    }







    @media screen and (max-width:736px) and (min-width:641px){



        /* tablets, Ipad */



        .content2{



            margin-left: 0px;



            margin-top: 5px;



            text-align: center;



        }  



        .tabScore {



            display: none;



        } 



        .remise, .compte, .compteAvance, .versement, .client, .avanceInput {



            width: 28%;



        }          



        .clientMutuelle, .compteMutuelle, .tauxMutuelle, .idMutuelle, .avanceInputM, .compteAvanceM, .codeAdherantMutuelle, .codeBeneficiaire, .numeroRecu, .dateRecu, .codeBarreLigneMutuelle{



            width: 20%;



        }



        .compte, .compteAvance, .versement,.terminer, .tauxMutuelle, .client, .avanceInput {



            /* margin-top:3px; */



            margin-left:3px;



        }       



        .clientMutuelle, .compteMutuelle, .tauxMutuelle, .idMutuelle, .avanceInputM, .compteAvanceM, .codeAdherantMutuelle, .codeBeneficiaire, .numeroRecu, .dateRecu, .codeBarreLigneMutuelle {



            margin-top:3px;



            margin-left:3px;



        }        



        /* .clientMutuelle, .avanceInputM, .compteAvanceM{



            width: 16%;



            margin-left:10px;



        }           */



        .codeAdherantMutuelle, .codeBeneficiaire, .numeroRecu, .dateRecu{



            width: 14%;



        }          



        .codeBarreLigneMutuelle{



            width: 50%;



        }



    }







    @media screen and (max-width:768px) and (min-width:737px){



        /* tablets, Ipad */



        .content2{



            margin-left: 0px;



            /* margin-top: 5px; */



            text-align: center;



        }  



        .tabScore {



            display: none;



        } 



        .remise, .compte, .compteAvance, .versement, .client, .avanceInput {



            width: 110px;



        } 



        .compte, .compteAvance, .versement,.terminer, .client, .avanceInput {



            /* margin-top:3px; */



            margin-left:3px;



        }         



        .tauxMutuelle, .idMutuelle, .compteMutuelle, .annulerMutuelle, .terminerMutuelle {



            margin-top:10px;



            margin-left:10px;



        }         



        .clientMutuelle, .compteMutuelle, .tauxMutuelle, .idMutuelle, .avanceInputM, .compteAvanceM, .codeAdherantMutuelle, .codeBeneficiaire, .numeroRecu, .dateRecu, .codeBarreLigneMutuelle {



            margin-top:3px;



            margin-left:10px;



        }        



        .codeBeneficiaire{



            margin-left:15%;



        }                  



        .clientMutuelle, .compteMutuelle, .tauxMutuelle, .idMutuelle, .avanceInputM, .compteAvanceM{



            width: 26%;



            margin-left:3px;



        }          



        .codeAdherantMutuelle, .codeBeneficiaire, .numeroRecu, .dateRecu, .tauxMutuelle, .idMutuelle, .compteMutuelle{



            width: 26%;



        }          



        .codeBarreLigneMutuelle{



            width: 36%;



        }



    }



  </style>


   <script src="js/jquery-3.1.1.min.js"></script>

   <script src="js/bootstrap.js"></script>

   <script type="text/javascript" src="offline/sw.js"></script>


<!-- Include Date Range Picker -->





<!--script type="text/javascript" src="validationAjoutReference.js"></script-->



<link rel="stylesheet" type="text/css" href="css/style.css">




<style media="print" type="text/css">   .noImpr {   display:none;   } </style>



<title>JCAISSE</title>




</head>



<!-- Debut Code HTML -->



<body>   

    <div class="container" id="venteContent"> 



        <img src="images/loading-gif3.gif" style="margin-left:40%;margin-top:8%" class="loading-gif" alt="GIF" srcset="">



    </div>


    <script type="text/javascript">

      //  window.onload = function() {

            $.ajax({



                url: "ajax/loadDataAjax.php",



                method: "POST",



                success: function(data) {

                    var tab = data.split('<>');


                    localStorage.setItem('Iduser', tab[0]);



                    localStorage.setItem('Boutique', tab[1]);

                    

                    localStorage.setItem('Designation', tab[2]);



                    localStorage.setItem('Compte', tab[3]);



                    localStorage.setItem('Client', tab[4]);



                    //localStorage.setItem('Payement-salaire', tab[5]);



                    users=JSON.parse(localStorage.getItem("Boutique"));

                    //alert(users)

                    iduser=localStorage.getItem('Iduser')

                    searchUser=users.find(u=>u.idutilisateur == parseInt(iduser));

                    //alert(searchUser.idutilisateur)



                    if(searchUser){

                        
                        sessionStorage.setItem('offlineSession',searchUser.offline)
                        
                        sessionStorage.setItem('iduserSession',searchUser.idutilisateur)

                        //localStorage.setItem('iduser',searchUser.idutilisateur)

                        sessionStorage.setItem('prenomSession',searchUser.prenom)

                        sessionStorage.setItem('nomUserSession',searchUser.nom)                   

                        sessionStorage.setItem('adresseUserSession',searchUser.adresse)

                        sessionStorage.setItem('emailSession',searchUser.email)

                        sessionStorage.setItem('telPortableSession',searchUser.telPortable)

                        sessionStorage.setItem('telFixeSession',searchUser.telFixe)

                        sessionStorage.setItem('profilSession',searchUser.profil)

                        sessionStorage.setItem('creationBSession',searchUser.creationB)

                        sessionStorage.setItem('passwordSession',searchUser.motdepasse)



                        sessionStorage.setItem('idBoutiqueSession',searchUser.idBoutique)
                        

                        //localStorage.setItem('idBoutique',boutiques[i].idBoutique)

                    

                        sessionStorage.setItem('proprietaireSession',searchUser.proprietaire) 

                        sessionStorage.setItem('gerantSession',searchUser.gerant)

                        sessionStorage.setItem('caissierSession',searchUser.caissier)

                        sessionStorage.setItem('vendeurSession',searchUser.vendeur)

                        sessionStorage.setItem('activerSession',searchUser.activer)



                        //searchUser=boutiques.find(b=>b.idBoutique == sessionStorage.idBoutiqueSession);

                        sessionStorage.setItem('nomBSession',searchUser.nomBoutique)

                        sessionStorage.setItem('labelBSession',searchUser.labelB)

                        sessionStorage.setItem('adresseBSession',searchUser.adresseBoutique)

                        sessionStorage.setItem('typeSession',searchUser.type)

                        sessionStorage.setItem('categorieSession',searchUser.categorie)

                        sessionStorage.setItem('dateCBSession',searchUser.datecreation)

                        sessionStorage.setItem('activerBSession',searchUser.activer)

                        sessionStorage.setItem('caisseSession',searchUser.caisse)

                        sessionStorage.setItem('telBoutiqueSession',searchUser.telephone)

                        sessionStorage.setItem('RegistreComSession',searchUser.RegistreCom)

                        sessionStorage.setItem('NineaSession',searchUser.Ninea)

                        sessionStorage.setItem('enConfigurationSession',searchUser.enConfiguration)

                        sessionStorage.setItem('vitrineSession',searchUser.vitrine)

                        sessionStorage.setItem('importExpSession',searchUser.importExp);

                        ligne=[];
                        date=new Date(); 
                        datejour=date.toLocaleDateString("es-CL");
                        iduser=localStorage.getItem('Iduser')

                        if(!localStorage.ligne){

                            localStorage.setItem("ligne",JSON.stringify(ligne));   

                        }

                        window.location.reload()
                        //alert('ok')

                        window.location.href="/JCaisse/insertionLigneLight.php"
                        

                                                

                    }

                    

                },



                error: function(data) {



                    alert(data);



                },



                dataType: "text"



            });

            

       // }

        

    </script>



</body>











 