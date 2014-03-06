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

var swp180214_risultati_loader 				= swp180214_js_placeholder.loader ;
var swp180214_risultati_no_image			= swp180214_js_placeholder.noimage ;
var swp180214_risultati_limit 				= swp180214_js_placeholder.limit ;
var swp180214_risultati_titolo 				= swp180214_js_placeholder.titolo ;
var swp180214_risultati_testobreve 			= swp180214_js_placeholder.testobreve ;
var swp180214_risultati_thumb				= swp180214_js_placeholder.thumb ;
var swp180214_risultati_ultimi				= swp180214_js_placeholder.ultimi ;
var swp180214_risultati_response 			= swp180214_js_placeholder.response ;

var swp180214_dettaglio_arrow				= swp180214_js_placeholder.arrow ;
var swp180214_dettaglio_descrizione			= swp180214_js_placeholder.descrizione ;
var swp180214_dettaglio_titolo				= swp180214_js_placeholder.titolo ;
var swp180214_dettaglio_datainserimento		= swp180214_js_placeholder.datainserimento ;
var swp180214_dettaglio_testo				= swp180214_js_placeholder.testo ;
var swp180214_dettaglio_lingua				= swp180214_js_placeholder.lingua ;
var swp180214_dettaglio_codicenazione		= swp180214_js_placeholder.codicenazione ;
var swp180214_dettaglio_codicecomune		= swp180214_js_placeholder.codicecomune ;
var swp180214_dettaglio_quartiere			= swp180214_js_placeholder.quartiere ;
var swp180214_dettaglio_localita			= swp180214_js_placeholder.localita ;
var swp180214_dettaglio_zona				= swp180214_js_placeholder.zona ;
var swp180214_dettaglio_strada				= swp180214_js_placeholder.strada ;
var swp180214_dettaglio_indirizzo			= swp180214_js_placeholder.indirizzo ;
var swp180214_dettaglio_civico				= swp180214_js_placeholder.civico ;
var swp180214_dettaglio_cap					= swp180214_js_placeholder.cap ;
var swp180214_dettaglio_mappa				= swp180214_js_placeholder.mappa ;
var swp180214_dettaglio_nrlocali			= swp180214_js_placeholder.nrlocali ;
var swp180214_dettaglio_nrvani				= swp180214_js_placeholder.nrvani ;
var swp180214_dettaglio_prezzo				= swp180214_js_placeholder.prezzo ;
var swp180214_dettaglio_mqsuperficie		= swp180214_js_placeholder.mqsuperficie ;
var swp180214_dettaglio_riferimento			= swp180214_js_placeholder.riferimento ;
var swp180214_dettaglio_spesemensili		= swp180214_js_placeholder.spesemensili ;
var swp180214_dettaglio_tipospese			= swp180214_js_placeholder.tipospese ;
var swp180214_dettaglio_duratacontratto		= swp180214_js_placeholder.duratacontratto ;
var swp180214_dettaglio_tipoproprieta		= swp180214_js_placeholder.tipoproprieta ;
var swp180214_dettaglio_bagni				= swp180214_js_placeholder.bagni ;
var swp180214_dettaglio_cucina				= swp180214_js_placeholder.cucina ;
var swp180214_dettaglio_terrazzi			= swp180214_js_placeholder.terrazzi ;
var swp180214_dettaglio_boxauto				= swp180214_js_placeholder.boxauto ;
var swp180214_dettaglio_cantina				= swp180214_js_placeholder.cantina ;
var swp180214_dettaglio_giardino			= swp180214_js_placeholder.giardino ;
var swp180214_dettaglio_riscaldamento		= swp180214_js_placeholder.riscaldamento ;
var swp180214_dettaglio_arredamento			= swp180214_js_placeholder.arredamento ;
var swp180214_dettaglio_classeenergetica	= swp180214_js_placeholder.classeenergetica ;
var swp180214_dettaglio_tipologia			= swp180214_js_placeholder.tipologia ;
var swp180214_dettaglio_ipe					= swp180214_js_placeholder.ipe ;
var swp180214_dettaglio_video				= swp180214_js_placeholder.video ;
var swp180214_dettaglio_immagini			= swp180214_js_placeholder.immagini ;
var swp180214_dettaglio_allegati			= swp180214_js_placeholder.allegati ;
var swp180214_dettaglio_custom				= swp180214_js_placeholder.custom ;

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
			swp180214_shortcode_show_details(response);
		}
	});
}

function swp180214_shortcode_hide_details(){
	if(jQuery('#wp180214_dettaglio') == undefined || jQuery('#wp180214_risultati') == undefined)return;
	jQuery('#wp180214_dettaglio').slideUp(50);
	jQuery('#wp180214_risultati').slideDown(200);
}

function swp180214_shortcode_show_details(response){
	if(jQuery('#wp180214_dettaglio') == undefined)return;
	jQuery('#wp180214_dettaglio').slideDown(200);
	
	//=========================================================================
	var html = '<div class="wp180214_dettaglio_back" onclick="swp180214_shortcode_hide_details()"><img src="'+swp180214_dettaglio_arrow	+'" />&nbsp;Indietro</div>' ;
	var json = jQuery.parseJSON(response);
	//=========================================================================
	if(swp180214_dettaglio_titolo){
		jQuery.each(json.T1,function(index,value){
			if(value['titolo'] == ''){
				value['titolo'] = value['testo'].substring(0,50);
			}
			html += '<div id="wp180214_dettaglio_titolo">'+value['titolo']+'</div>' ;
	});
	}
	if(swp180214_dettaglio_immagini){
		html += '<label id="wp180214_dettaglio_immagini_label">Foto</label><div id="wp180214_dettaglio_immagini">';
		jQuery.each(json.T7,function(index,value){
				if(value['url'] != undefined && value['url'] != ''){
					html += '<a href="'+value['url']+'" title="'+value['titolo']+'"><img src="'+value['url']+'" width="60" height="60"></a>';
				}
		});
		html += '</div>';
	}
	//=========================================================================
	html += '<label id="wp180214_dettaglio_dettagli_label">Dettagli</label>';
	//=========================================================================
	html += '<div><table id="wp180214_dettaglio_dettagli_table><tbody>';
	jQuery.each(json.T0,function(index,value){
		if(swp180214_dettaglio_tipologia){
			html += '<tr valign="top"><th scope="row">Tipologia</th><td>'+value['tipologia']+'</td></tr>';
		}
		if(swp180214_dettaglio_tipoproprieta){
			switch(value['tipoproprieta']){
				case '1':
					value['tipoproprieta'] = 'Intera Proprieta' ;
				break;
				case '2':
					value['tipoproprieta'] = 'Nuda Proprieta' ;
				break;
				case '3':
					value['tipoproprieta'] = 'Parziale Proprieta' ;
				break;
				case '4':
					value['tipoproprieta'] = 'Usufrutto' ;
				break;
				case '5':
					value['tipoproprieta'] = 'Multiproprieta' ;
				break;
				case '6':
					value['tipoproprieta'] = 'Diritto di superficie' ;
				break;
				default:
					value['tipoproprieta'] = 'n.d.' ;
			}
			html += '<tr valign="top"><th scope="row">Tipo proprieta</th><td>'+value['tipoproprieta']+'</td></tr>';
		}
		if(swp180214_dettaglio_datainserimento){
			html += '<tr valign="top"><th scope="row">Data annuncio</th><td>'+value['datainserimento']+'</td></tr>';
		}
		if(swp180214_dettaglio_localita && value['localita'] != null){
			html += '<tr valign="top"><th scope="row">Localita</th><td>'+value['localita']+'</td></tr>';
		}
		if(swp180214_dettaglio_zona && value['zona'] != null){
			html += '<tr valign="top"><th scope="row">Localita</th><td>'+value['zona']+'</td></tr>';
		}
		if(swp180214_dettaglio_strada && value['strada'] != null){
			html += '<tr valign="top"><th scope="row">Strada</th><td>'+value['strada']+'</td></tr>';
		}
		if(swp180214_dettaglio_indirizzo && value['indirizzo'] != null){
			var strada = value['strada'] != null ? value['strada'] : '' ;
			html += '<tr valign="top"><th scope="row">Indirizzo</th><td>'+strada+' '+value['indirizzo']+'</td></tr>';
		}
	});
	html += '</tbody></table></div>';
	jQuery('#wp180214_dettaglio').html(html);
}
