$(document).ready(function(){
						   			   
	var resultat = true ; 
	
	$('#form').submit(function(){
		
		resultat = true ;
		
		/*
			if($('#choix').val()==1){
				$('#div-reference').show();
			    $('#div-categorie').show();
				$('#div-uniteStock').show();
			    $('#div-nbArticleUniteStock').show();	
				$('#div-prix').show();
			    $('#div-prixSF').hide();
				$('#div-prixuniteStock').show();					
			} 
			if($('#choix').val()==2){
				$('#div-reference').show();
			    $('#div-categorie').hide();
				$('#div-uniteStock').hide();
			    $('#div-nbArticleUniteStock').hide();	
				$('#div-prix').hide();
			    $('#div-prixSF').show();
				$('#prixSF').attr('placeholder', 'Prix du Service');
				$('#div-prixuniteStock').hide();					
			}
			if($('#choix').val()==3){
				$('#div-reference').show();
			    $('#div-categorie').hide();
				$('#div-uniteStock').hide();
			    $('#div-nbArticleUniteStock').hide();	
				$('#div-prix').hide();
			    $('#div-prixSF').show();
				$('#prixSF').attr('placeholder', 'Prix du Frais');
				$('#div-prixuniteStock').hide();					
			}*/
			  
			
		
		if($('#designation').val() == ""){	
			$('#designation').attr('placeholder', 'Je ne peux pas être vide!...').parent().addClass('has-error');
			$('#helpReference').text('Ce champ est obligatoire');
			resultat=false;
		}
		
		/*
		if(!isNumeric($('#nbArticleUniteStock').val())){	
			$('#nbArticleUniteStock').attr('placeholder', 'Je doit etre un nombre!...').parent().addClass('has-error');
			$('#helpNbArticleUniteStock').text('Ce champ est obligatoire');
			resultat=false;
		}	*/	
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
			$('#div-reference').show();
			$('#div-codebarre').show();			
			$('#div-categorie').show();
			$('#div-uniteStock').show();
			$('#div-nbArticleUniteStock').show();	
			$('#div-prix').show();
			$('#div-prixSF').hide();
			$('#div-prixuniteStock').show();				
		});
		
	    $('#radioService').click(function() {
										  
			$('#div-reference').show();
			$('#div-codebarre').hide();	
			$('#div-categorie').hide();
			$('#div-uniteStock').hide();
			$('#div-nbArticleUniteStock').hide();	
			$('#div-prix').hide();
			$('#designation').attr('placeholder', 'Nom de la Reference du Service ici...');
			//$('#LabelPrixSF').show();
			$('#div-prixSF').show();
			$('#prixSF').attr('placeholder', 'Prix du Service');
			$('#div-prixuniteStock').hide();				
		});
		
		$('#radioFrais').click(function() {
		
			$('#div-reference').show();
			$('#div-codebarre').hide();	
			$('#div-categorie').toggle();
			$('#div-uniteStock').hide();
			$('#div-nbArticleUniteStock').hide();	
			$('#div-prix').hide();
			$('#designation').attr('placeholder', 'Nom de la Reference du Frais ici...');
			$('#LabelPrixSF').show();
			$('#prixSF').attr('placeholder', 'Prix du Frais');
			$('#div-prixSF').show();
			$('#designation').attr('placeholder', 'Nom de la Reference du Frais ici...');
			$('#div-prixuniteStock').hide();										   
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

		
	

		/*
		$('#motdepasse1').keyup(function(){
			if($('#motdepasse1').val==""){
				$('#motdepasse1').attr('placeholder', 'Je doit avoir au moins 6 caractaires...').parent().addClass('has-error');
			    $('#helpMotdepasse1').text('Ce champ est obligatoire');
				
			}else{
				
				$('#motdepasse1').parent().removeClass('has-error');
				$('#helpMotdepasse1').text('');
				
				}
				
			if($('#motdepasse1').val().length < 6){
					$('#motdepasse1').attr('placeholder', 'Je ne peux pas être vide...').parent().addClass('has-error');
					$('#helpMotdepasse1').text('Le mot de passe est trop court!');	
			}
								   
		});
		
		*/
		/*
		
		$('#motdepasse2').keyup(function(){
			if($('#motdepasse2').val==""){
				$('#motdepasse2').attr('placeholder', 'Confirmer le mot de passe...').parent().addClass('has-error');
			    $('#helpMotdepasse2').text('Ce champ est obligatoire');
				
			}else{
				
				$('#motdepasse2').parent().removeClass('has-error');
				$('#helpMotdepasse2').text('');
				
				}
				
			if($('#motdepasse2').val().length < 6){
					$('#motdepasse2').attr('placeholder', 'Confirmer le mot de passe...').parent().addClass('has-error');
					$('#helpMotdepasse2').text('Je doit avoir au moins 6 caractaires...');	
			}
			
			if($('#motdepasse1').val() != $('#motdepasse2').val()){
					$('#motdepasse2').attr('placeholder', 'Confirmer le mot de passe...').parent().addClass('has-error');
					$('#helpMotdepasse2').text("Ce mot de passe est différent du premier...");	
			}
								   
		});							   
						   
		*/				   						  						   						   
});