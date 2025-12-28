$(document).ready(function(){
						   
		
				   
	var resultat = true ; 
	
	$('#form').submit(function(){
		
		resultat = true ;
		
/*    TESTER LES CHAMPS QUI NE DOIVENT PAS ETRE VIDE QUAND ON SOUMET LE FORMULAIRE ET 
      ENVOYER DES MESSAGES SI UNE DE CES CHAMPS EST VIDE ET
	  SANS SOUMETTRE LE FORMULAIRE AU SERVEUR (resultat=false;).      */

		if($('#nom').val() == ""){	
			$('#nom').attr('placeholder', 'Je ne peux pas être vide!...').parent().addClass('has-error');
			$('#helpNom').text('Ce champ est obligatoire');
			resultat=false;
		}
		
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
		
/*    IL FAUT AUSSI D'AUTRES CONTRAINTES SUR LES CHAMPS COMME :
           NUMERO TELEPHONE (qui doit avoir 9 ou 14 caractaires), 
           EMAIL (qui doit être sous la forme : ibrahima.diop@univ-zig.sn) ET 
	       MOTS DE PASSES (qui doivent avoir au moyen 6 caractaires et sont tous deux identiques). 
		   SI CES CONTRAINTE NE SONT PAS RESPECTER LE FORMULAIRE N4EST PAS SOUMIT (resultat=false;)*/		
		
		if(($('#telPortable').val().length != 9) && ($('#telPortable').val().length != 13) && ($('#telPortable').val().length != 14)) {
		
			var pattern= new RegExp([+0-9]);
			if (!pattern.test($("#telPortable").val()))
				resultat=false;	
		}	
		
		
		if($('#email').val() != ""){	
		
			var pattern= new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
			if (!pattern.test($("#email").val()))
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
		
		
		return resultat;
		
		});
	
	
/*    TESTER LES CHAMPS QUI NE DOIVENT PAS ETRE VIDE DES QU'ILS SONT SELECTIONNER ET 
      ENVOYER DES MESSAGES SI UN DE CES CHAMPS EST VIDE ET NETTOYER LES MESSAGES QUAND TOUT EST EN ORDRE.      */
	  
		$('#prenom').keyup(function(){
			if($('#prenom').val()==""){
				$('#prenom').attr('placeholder', 'Je ne peux pas être vide!...').parent().addClass('has-error');
			    $('#helpPrenom').text('Ce champ est obligatoire');
				
			}else{
				$('#prenom').parent().removeClass('has-error');
				$('#helpPrenom').text('');
				}
								   
		});	
		
		
		$('#nom').keyup(function(){
			if($('#nom').val()==""){
				$('#nom').attr('placeholder', 'Je ne peux pas être vide!...').parent().addClass('has-error');
			    $('#helpNom').text('Ce champ est obligatoire');
			}else{
				$('#nom').parent().removeClass('has-error');
				$('#helpNom').text('');
				}
								   
		});			

		
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
		
		
		
		$('#telPortable').keyup(function(){
			if($('#telPortable').val()==""){
				$('#telPortable').attr('placeholder', 'Je ne peux pas être vide...').parent().addClass('has-error');
			    $('#helpPortable').text('Ce champ est obligatoire');
				
			}else{
				
				$('#telPortable').parent().removeClass('has-error');
				$('#helpPortable').text('');
				
				}
				
			if(($('#telPortable').val().length != 9) && ($('#telPortable').val().length != 14)){
					$('#telPortable').attr('placeholder', 'Je ne peux pas être vide...').parent().addClass('has-error');
					$('#helpPortable').text('Le numero est de 9 ou 14 chiffres !');	
			}
								   
		});
		

		
		$('#motdepasse1').keyup(function(){
			if($('#motdepasse1').val()==""){
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
		
		
		
		$('#motdepasse2').keyup(function(){
			if($('#motdepasse2').val()==""){
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
						   
						   						  						   						   
});