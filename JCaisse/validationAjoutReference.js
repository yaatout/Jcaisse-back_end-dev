$(document).ready(function(){
						   			   
	var resultat = true ; 
	
	$('#form').submit(function(){
		
		resultat = true ;
		
		if($('#designation').val() == ""){	
			$('#designation').attr('placeholder', 'Je ne peux pas être vide!...').parent().addClass('has-error');
			$('#helpReference').text('Ce champ est obligatoire');
			resultat=false;
		}
		
		if(isNaN($('#seuil').val())){	
			$('#seuil').attr('placeholder', 'Je doit etre un nombre!...').parent().addClass('has-error');
			$('#helpSeuilStock').text('Ce champ doit etre un nombre');
			resultat=false;
		}
		
		if(isNaN($('#nbArticleUniteStock').val())){	
			$('#nbArticleUniteStock').attr('placeholder', 'Je doit etre un nombre!...').parent().addClass('has-error');
			$('#helpNbArticleUniteStock').text('Ce champ doit etre un nombre');
			resultat=false;
		}	
        /*if($('#nbArticleUniteStock').val()==""){	
			$('#nbArticleUniteStock').attr('placeholder', 'Je doit etre un nombre!...').parent().addClass('has-error');
			$('#helpNbArticleUniteStock').text('Ce champ est obligatoire');
			resultat=false;
		}*/
        if(isNaN($('#seuil').val())){	
			$('#seuil').attr('placeholder', 'Je doit etre un nombre!...').parent().addClass('has-error');
			$('#helpSeuilStock').text('Ce champ doit etre un nombre');
			resultat=false;
		}
        /*if($('#seuil').val()==""){	
			$('#seuil').attr('placeholder', 'Je doit etre un nombre!...').parent().addClass('has-error');
			$('#helpSeuilStock').text('Ce champ est obligatoire');
			resultat=false;
		}*/
        
         if(isNaN($('#prix').val())){	
			$('#prix').attr('placeholder', 'Je doit etre un nombre!...').parent().addClass('has-error');
			$('#helpPrixUnitaire').text('Ce champ doit etre un nombre');
			resultat=false;
		}
        /*if($('#prix').val()==""){	
			$('#prix').attr('placeholder', 'Je doit etre un nombre!...').parent().addClass('has-error');
			$('#helpPrixUnitaire').text('Ce champ est obligatoire');
			resultat=false;
		}*/

        if(isNaN($('#prixSF').val())){	
			$('#prixSF').attr('placeholder', 'Je doit etre un nombre!...').parent().addClass('has-error');
			$('#helpPrixSF').text('Ce champ doit etre un nombre');
			resultat=false;
		}
        /*if($('#prixSF').val()==""){	
			$('#prixSF').attr('placeholder', 'Je doit etre un nombre!...').parent().addClass('has-error');
			$('#helpPrixSF').text('Ce champ est obligatoire');
			resultat=false;
		}*/

        if(isNaN($('#prixuniteStock').val())){	
			$('#prixuniteStock').attr('placeholder', 'Je doit etre un nombre!...').parent().addClass('has-error');
			$('#helpPrixUniteStock').text('Ce champ doit etre un nombre');
			resultat=false;
		}
        /*if($('#prixuniteStock').val()==""){	
			$('#prixuniteStock').attr('placeholder', 'Je doit etre un nombre!...').parent().addClass('has-error');
			$('#helpPrixUniteStock').text('Ce champ est obligatoire');
			resultat=false;
		}*/
	/*	
		if($('#prenom').val()== ""){			
			$('#prenom').attr('placeholder', 'Je ne peux pas être vide!...').parent().addClass('has-error');
			$('#helpPrenom').text('Ce champs est obligatoire');
			resultat=false;
		
		}
		
		if($('#telPortable').val() == ""){	
			$('#telPortable').attr('placeholder', 'Je ne peux pas être vide!...').parent().addClass('has-error');
			$('#helpPortable').text('Ce champ est obligatoire');
			resultat=false;
		}
		
		if($('#email').val() == ""){	
			$('#email').attr('placeholder', 'Je ne peux pas être vide!...').parent().addClass('has-error');
			$('#helpEmail').text('Ce champ est obligatoire');
			resultat=false;
		}
		
		
		
		if($('#email').val() != ""){	
		
			var pattern= new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
			if (!pattern.test($("#email").val()))
				resultat=false;
		}

			
		if($('#motdepasse1').val()== ""){			
			$('#motdepasse1').attr('placeholder', 'Je doit avoir au moins 6 caractaires...').parent().addClass('has-error');
			$('#helpMotdepasse1').text('Ce champs est obligatoire');
			resultat=false;
		}
		
		if($('#motdepasse2').val()== ""){			
			$('#motdepasse2').attr('placeholder', 'Je ne peux pas être vide!...').parent().addClass('has-error');
			$('#helpMotdepasse2').text('Ce champs est obligatoire');
			resultat=false;
		
		}
		
		
		
		if(($('#telPortable').val().length != 9) && ($('#telPortable').val().length != 14)) {
			resultat=false;	
		}		
		
		
		
		if($('#motdepasse1').val().length < 6){
			resultat=false;	
		}		
		
		
		if($('#motdepasse2').val().length < 6){
			resultat=false;	
		}
		

		if($('#motdepasse1').val() != $('#motdepasse2').val()){
			resultat=false;	
		}	
		*/
		
		return resultat;
		
		});
	
	
/*
		$('#prenom').keyup(function(){
			if($('#prenom').val()==""){
				$('#prenom').attr('placeholder', 'Je ne peux pas être vide!...').parent().addClass('has-error');
			    $('#helpPrenom').text('Ce champ est obligatoire');
				
			}else{
				
				$('#prenom').parent().removeClass('has-error');
				$('#helpPrenom').text('');
				
				}
								   
		});	
		*/
/*		$('#designation').keyup(function(){
			if($('#designation').val()==""){
				$('#designation').attr('placeholder', 'Je ne peux pas être vide!...').parent().addClass('has-error');
			    $('#helpReference').text('Ce champ est obligatoire');
				
			}else{
				
				$('#designation').parent().removeClass('has-error');
				$('#helpReference').text('');
				
				}
								   
		});		
		*/
		
		
			
		
		
		
	$("#radioProduit").click(function () {
        $('.div-reference').show();
		$('.div-codebarre').show();
		$('.div-codebarreuniteStock').show();
        $('.div-categorie').show();
        $('.div-uniteStock').show();
        $('.div-nbArticleUniteStock').show();
        $('.div-seuilStock').show();
        $('.div-prix').show();
        $('.div-prixSF').hide();
        $('.div-prixuniteStock').show();
    });

    $('#radioService').click(function() {

        $('.div-reference').show();
		$('.div-codebarre').hide();	
		$('.div-codebarreuniteStock').hide();
        $('.div-categorie').hide();
        $('.div-uniteStock').hide();
        $('.div-nbArticleUniteStock').hide();
        $('.div-seuilStock').hide();
        $('.div-prix').hide();
        $('#designation').attr('placeholder', 'Nom de la Reference du Service ici...');
		//$('#designation').class("form-control");
        $('.LabelPrixSF').show();
        $('.div-prixSF').show();
        $('#prixSF').attr('placeholder', 'Prix du Service');
        $('.div-prixuniteStock').hide();
    });

    $('#radioFrais').click(function() {

        $('.div-reference').show();
		$('.div-codebarre').hide();		
		$('.div-codebarreuniteStock').hide();
        $('.div-categorie').hide();
        $('.div-uniteStock').hide();
        $('.div-nbArticleUniteStock').hide();
        $('.div-seuilStock').hide();
        $('.div-prix').hide();
        $('#designation').attr('placeholder', 'Nom de la Reference du Frais ici...');
		//$('#designation').class("form-control");
        $('.LabelPrixSF').show();
		$('.div-prixSF').show();
        $('#prixSF').attr('placeholder', 'Prix du Frais');
        $('.div-prixuniteStock').hide();
    });
		/*
		$('#email').keyup(function(){
			if($('#email').val()==""){
				$('#email').attr('placeholder', 'Je ne peux pas être vide!...').parent().addClass('has-error');
			    $('#helpEmail').text('Ce champ est obligatoire');
			}else{
				var pattern= new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
				if (pattern.test($("#email").val())){
					$('#email').parent().removeClass('has-error');
					$("#helpEmail").text('');
				}
				else{
					$('#email').parent().addClass('has-error');
					$("#helpEmail").text("Adresse email invalide.");
					resultat=false;
					}
				}				   
		});	
		*/


				   						  						   						   
});