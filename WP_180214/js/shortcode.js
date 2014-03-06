/*
 Copyright 2014 Ivan Maruca

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/
var swp180214_risultati_loader 		= swp180214_js_placeholder.loader ;
var swp180214_risultati_no_image	= swp180214_js_placeholder.noimage ;
var swp180214_risultati_limit 		= swp180214_js_placeholder.limit ;
var swp180214_risultati_titolo 		= swp180214_js_placeholder.titolo ;
var swp180214_risultati_testobreve 	= swp180214_js_placeholder.testobreve ;
var swp180214_risultati_thumb		= swp180214_js_placeholder.thumb ;
var swp180214_risultati_ultimi		= swp180214_js_placeholder.ultimi ;
var swp180214_risultati_response 	= swp180214_js_placeholder.response ;

function swp180214_shortcode_search(nonce){

	jQuery('#wp180214_risultati').html(swp180214_risultati_loader);
	
	jQuery.post(swp180214_ajax_placeholder.url,{
		action:'swp180214_action_shortcode_search',
		_nonce:nonce,
		contratto:jQuery('input[name=wp180214_ricerca_contratto]:checked').val(),
		categoria:jQuery('select[name=wp180214_ricerca_categoria]').val(),
		prezzo_minimo:jQuery('input[name=wp180214_ricerca_prezzo_minimo]').val(),
		prezzo_massimo:jQuery('input[name=wp180214_ricerca_prezzo_massimo]').val(),
		libero:jQuery('input[name=wp180214_ricerca_libero]').val()
	},function(response) {
		if(response != 'error')swp180214_shortcode_risultati(response);
	});
	
	return true ;
}

function swp180214_shortcode_risultati(response){
	
	var html = '' ;
	var risultati = jQuery('#wp180214_risultati');
	if(risultati == undefined)return ;
	jQuery('#wp180214_risultati').slideDown(50);
	if(response == '0'){
		html = '<div id="wp180214_risultati_risultato_nessuno">Nessun Risultato</div>' ;
		jQuery(risultati).html(html);
		return;
	}
	
	
	var json = jQuery.parseJSON(response);
	
	jQuery.each(json,function(index,value){
		html += '<div class="wp180214_risultati_risultato" onclick="swp180214_shortcode_details(\''+value['idimmobile']+'\',this);">' ;
		if(swp180214_risultati_thumb){
			if(value['thumb'] != null){
				var thumb = value['thumb'].replace("\\","") ;
				html += '<div class="wp180214_risultati_risultato_thumb"><img src="'+thumb+'" width="150" height="150"/></div>' ;
			}else{
				html += '<div class="wp180214_risultati_risultato_thumb"><img src="'+swp180214_risultati_no_image+'" width="150" height="150" /></div>' ;
				
			}
		}
		
		if(swp180214_risultati_titolo){
			if(value['titolo'].length == 0){
				value['titolo'] = value['testo'].substring(0,50);
			}
			html += '<div class="wp180214_risultati_risultato_titolo">'+value['titolo']+'</div>' ;
		}
		
		if(swp180214_risultati_testobreve){
			if(value['testobreve'].length == 0){
				value['testobreve'] = value['testo'].substring(0,150) + "..." ;
			}
			html += '<div class="wp180214_risultati_risultato_testobreve">'+value['testobreve']+'</div>' ;
		}
		
		html += '</div>' ;
	});
	
	jQuery(risultati).html(html);
	
}

function swp180214_shortcode_details(idimmobile,div){
	
	if(jQuery('#wp180214_dettaglio') == undefined)return ;
	
	jQuery('#wp180214_risultati').slideUp(200);
	
	jQuery.post(swp180214_ajax_placeholder.url,{
		action:'swp180214_action_shortcode_details',
		idimmobile:idimmobile
	},function(response) {
		if(response != 'error'){
			alert(response);
		}else alert('error');
	});
}
