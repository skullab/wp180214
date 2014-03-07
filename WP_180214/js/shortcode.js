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

var swp180214_dettaglio_youtube				= swp180214_js_placeholder.youtube ;
var swp180214_dettaglio_arrow				= swp180214_js_placeholder.arrow ;
var swp180214_dettaglio_descrizione			= swp180214_js_placeholder.descrizione ;//ok
var swp180214_dettaglio_titolo				= swp180214_js_placeholder.titolo ;//ok
var swp180214_dettaglio_datainserimento		= swp180214_js_placeholder.datainserimento ;//ok
var swp180214_dettaglio_testo				= swp180214_js_placeholder.testo ;//ok
var swp180214_dettaglio_lingua				= swp180214_js_placeholder.lingua ; // non serve
var swp180214_dettaglio_codicenazione		= swp180214_js_placeholder.codicenazione ; // non serve
var swp180214_dettaglio_codicecomune		= swp180214_js_placeholder.codicecomune ;
var swp180214_dettaglio_quartiere			= swp180214_js_placeholder.quartiere ;
var swp180214_dettaglio_localita			= swp180214_js_placeholder.localita ;
var swp180214_dettaglio_zona				= swp180214_js_placeholder.zona ;
var swp180214_dettaglio_strada				= swp180214_js_placeholder.strada ;
var swp180214_dettaglio_indirizzo			= swp180214_js_placeholder.indirizzo ;
var swp180214_dettaglio_civico				= swp180214_js_placeholder.civico ;
var swp180214_dettaglio_cap					= swp180214_js_placeholder.cap ; // fin qui ok

var swp180214_dettaglio_mappa				= swp180214_js_placeholder.mappa ; //todo

var swp180214_dettaglio_nrlocali			= swp180214_js_placeholder.nrlocali ; //ok
var swp180214_dettaglio_nrvani				= swp180214_js_placeholder.nrvani ; //ok
var swp180214_dettaglio_prezzo				= swp180214_js_placeholder.prezzo ; //ok
var swp180214_dettaglio_mqsuperficie		= swp180214_js_placeholder.mqsuperficie ;
var swp180214_dettaglio_riferimento			= swp180214_js_placeholder.riferimento ;
var swp180214_dettaglio_spesemensili		= swp180214_js_placeholder.spesemensili ;
var swp180214_dettaglio_tipospese			= swp180214_js_placeholder.tipospese ;
var swp180214_dettaglio_duratacontratto		= swp180214_js_placeholder.duratacontratto ;
var swp180214_dettaglio_tipoproprieta		= swp180214_js_placeholder.tipoproprieta ;//fin qui ok

var swp180214_dettaglio_bagni				= swp180214_js_placeholder.bagni ; //ok
var swp180214_dettaglio_cucina				= swp180214_js_placeholder.cucina ; //ok
var swp180214_dettaglio_terrazzi			= swp180214_js_placeholder.terrazzi ;//ok
var swp180214_dettaglio_boxauto				= swp180214_js_placeholder.boxauto ;//ok
var swp180214_dettaglio_cantina				= swp180214_js_placeholder.cantina ;//ok
var swp180214_dettaglio_giardino			= swp180214_js_placeholder.giardino ;//ok
var swp180214_dettaglio_riscaldamento		= swp180214_js_placeholder.riscaldamento ;//ok
var swp180214_dettaglio_arredamento			= swp180214_js_placeholder.arredamento ;//ok

var swp180214_dettaglio_classeenergetica	= swp180214_js_placeholder.classeenergetica ; //ok
var swp180214_dettaglio_tipologia			= swp180214_js_placeholder.tipologia ; //ok
var swp180214_dettaglio_ipe					= swp180214_js_placeholder.ipe ;//ok
var swp180214_dettaglio_video				= swp180214_js_placeholder.video ;
var swp180214_dettaglio_immagini			= swp180214_js_placeholder.immagini ;//ok
var swp180214_dettaglio_allegati			= swp180214_js_placeholder.allegati ;//todo
var swp180214_dettaglio_custom				= swp180214_js_placeholder.custom ; //todo

function swp180214_shortcode_search(nonce){
	
	if(jQuery('#wp180214_risultati') != undefined)jQuery('#wp180214_risultati').html(swp180214_risultati_loader);
	if(jQuery('#wp180214_dettaglio') != undefined)jQuery('#wp180214_dettaglio').slideUp(50);
	
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
	var categoria = 0 ;
	jQuery.each(json.T0,function(index,value){
		categoria = value['categoria'] ;
		var contratto = '' ;
		switch(value['contratto']){
			case 'V':
				contratto = 'Vendita' ;
				break;
			case 'A':
				contratto = 'Affitto' ;
				break;
		}
		
		html += '<tr valign="top"><th scope="row">Contratto</th><td>'+contratto+'</td></tr>';

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
		if(swp180214_dettaglio_codicenazione){
			html += '<tr valign="top"><th scope="row">Codice nazione</th><td>'+value['codicenazione']+'</td></tr>';
		}
		if(swp180214_dettaglio_codicecomune){
			html += '<tr valign="top"><th scope="row">Codice comune</th><td>'+value['codicecomune']+'</td></tr>';
		}
		if(swp180214_dettaglio_quartiere && value['quartiere'] != null){
			html += '<tr valign="top"><th scope="row">Quartiere</th><td>'+value['quartiere']+'</td></tr>';
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
		if(swp180214_dettaglio_civico && value['civico'] != null){
			html += '<tr valign="top"><th scope="row">Civico</th><td>'+value['civico']+'</td></tr>';
		}
		if(swp180214_dettaglio_cap && value['cap'] != null){
			html += '<tr valign="top"><th scope="row">CAP</th><td>'+value['cap']+'</td></tr>';
		}
		if(swp180214_dettaglio_indirizzo && value['indirizzo'] != null){
			var strada = value['strada'] != null ? value['strada'] : '' ;
			html += '<tr valign="top"><th scope="row">Indirizzo</th><td>'+strada+' '+value['indirizzo']+'</td></tr>';
		}
		if(swp180214_dettaglio_nrlocali && value['nrlocali'] != null){
			html += '<tr valign="top"><th scope="row">Numero locali</th><td>'+value['nrlocali']+'</td></tr>';
		}
		if(swp180214_dettaglio_nrvani && value['nrvani'] != null){
			html += '<tr valign="top"><th scope="row">Numero vani</th><td>'+value['nrvani']+'</td></tr>';
		}
		if(swp180214_dettaglio_mqsuperficie && value['mqsuperficie'] != null){
			html += '<tr valign="top"><th scope="row">Mq superficie</th><td>'+value['mqsuperficie']+'</td></tr>';
		}
		if(value['trattativariservata'] != null && value['trattativariservata'] == '1'){
			html += '<tr valign="top"><th scope="row">Trattativa riservata</th><td>Si</td></tr>';
		}
		if(swp180214_dettaglio_prezzo){			
			html += '<tr valign="top"><th scope="row">Prezzo</th><td>&euro; '+value['prezzo'].substring(0,value['prezzo'].length - 3)+'</td></tr>';
		}
		if(swp180214_dettaglio_spesemensili && value['spesemensili'] != null){			
			html += '<tr valign="top"><th scope="row">Spese mensili</th><td>&euro;'+value['spesemensili'].substring(0,value['spesemensili'].length - 3)+'</td></tr>';
		}
		if(swp180214_dettaglio_tipospese && value['tipospese'] != null){
			var tipospese = '' ;
			switch(value['tipospese']){
				case '0':
					tipospese = 'Mensili' ;
					break;
				case '1':
					tipospese = 'annuali' ;
					break;
			}
			html += '<tr valign="top"><th scope="row">Tipo spese</th><td>'+tipospese+'</td></tr>';
		}
		if(swp180214_dettaglio_duratacontratto && value['duratacontratto'] != null){
			var duratacontratto = '' ;
			switch(value['tipospese']){
			case '1':
				tipospese = '4 + 4 anni' ;
				break;
			case '2':
				tipospese = '6 + 6' ;
				break;
			case '3':
				tipospese = '8 + 8' ;
				break;
			case '4':
				tipospese = '12 + 12' ;
				break;
			case '5':
				tipospese = 'studentesco' ;
				break;
			case '6':
				tipospese = 'uso foresteria' ;
				break;
			case '7':
				tipospese = 'stagionale' ;
				break;
			case '8':
				tipospese = 'annuale' ;
				break;
			case '255':
				tipospese = 'n.d.' ;
				break;
		}
			html += '<tr valign="top"><th scope="row">Durata contratto</th><td>'+duratacontratto+'</td></tr>';
		}
		if(swp180214_dettaglio_riferimento){			
			html += '<tr valign="top"><th scope="row">Riferimento</th><td>'+value['riferimento']+'</td></tr>';
		}
	});
	
	if(categoria != 0){
		var json_categoria ;
		switch(categoria){
			case '1':
				json_categoria = json.T2 ;
				break;
			case '2':
				json_categoria = json.T3 ;
				break;
			case '3':
				json_categoria = json.T4 ;
				break;
			case '4':
				json_categoria = json.T6 ;
				break;
			case '5':
				json_categoria = json.T5 ;
				break;
		}
		jQuery.each(json_categoria,function(index,value){
			
			if(swp180214_dettaglio_bagni && value['nrbagni'] != null){			
				html += '<tr valign="top"><th scope="row">Bagni</th><td>'+value['nrbagni']+'</td></tr>';
			}
			if(swp180214_dettaglio_cucina && value['cucina'] != null){
				var cucina = '' ;
				switch(value['cucina']){
					case '1':
						cucina = 'Abitabile' ;
						break;
					case '2':
						cucina = 'Angolo cottura' ;
						break;
					case '3':
						cucina = 'Cucinino' ;
						break;
					case '4':
						cucina = 'Semi abitabile' ;
						break;
					case '5':
						cucina = 'Tinello' ;
						break;
					case '6':
						cucina = 'A vista' ;
						break;
					case '255':
						cucina = 'Non presente' ;
						break;
				}
				html += '<tr valign="top"><th scope="row">Cucina</th><td>'+cucina+'</td></tr>';
			}
			if(swp180214_dettaglio_terrazzi && value['nrterrazzi'] != null){			
				html += '<tr valign="top"><th scope="row">Terrazzi</th><td>'+value['nrterrazzi']+'</td></tr>';
			}
			if(swp180214_dettaglio_boxauto && value['boxauto'] != null){
				var boxauto = '';
				switch(value['boxauto']){
					case '1':
						boxauto = 'Singolo';
						break;
					case '2':
						boxauto = 'Doppio';
						break;
					case '3':
						boxauto = 'Triplo';
						break;
					case '255':
						boxauto = 'Assente';
						break;
				}
				html += '<tr valign="top"><th scope="row">Box auto</th><td>'+boxauto+'</td></tr>';
			}
			if(swp180214_dettaglio_cantina && value['cantina'] != null){
				var cantina = '';
				switch(value['cantina']){
					case '1':
						cantina = 'Presente';
						break;
					case '2':
						cantina = 'Assente';
						break;
				}
				html += '<tr valign="top"><th scope="row">Cantina</th><td>'+cantina+'</td></tr>';
			}
			if(swp180214_dettaglio_giardino){
				var giardino = '' ;
				if(value['giardinocondominiale'] != null && value['giardinocondominiale'] == '1'){
					html += '<tr valign="top"><th scope="row">Giardino condominiale</th><td>Si</td></tr>';
				}
				if(value['giardinoprivato'] != null){
					switch(value['giardinoprivato']){
						case '1':
							giardino = 'Presente' ;
							break;
						case '2':
							giardino = 'Assente' ;
							break;
					}
					html += '<tr valign="top"><th scope="row">Giardino privato</th><td>'+giardino+'</td></tr>';
					if(giardino == 'Presente' && value['mqgiardinoprivato'] != null){
						html += '<tr valign="top"><th scope="row">Mq Giardino privato</th><td>'+value['mqgiardinoprivato']+'</td></tr>';
					}
				}
			}
			if(swp180214_dettaglio_riscaldamento && value['riscaldamento'] != null){
				var riscaldamento = '';
				switch(value['riscaldamento']){
					case '1':
						riscaldamento = 'Autonomo';
						break;
					case '2':
						riscaldamento = 'Centralizzato';
						break;
					case '255':
						riscaldamento = 'Assente';
						break;
				}
				html += '<tr valign="top"><th scope="row">Riscaldamento</th><td>'+riscaldamento+'</td></tr>';
			}
			if(swp180214_dettaglio_arredamento && value['arredamento'] != null){
				var arredamento = '';
				switch(value['arredamento']){
					case '1':
						arredamento = 'Parziale';
						break;
					case '2':
						arredamento = 'Completo';
						break;
					case '255':
						arredamento = 'Assente';
						break;
				}
				html += '<tr valign="top"><th scope="row">Arredamento</th><td>'+arredamento+'</td></tr>';
			}
			if(value['esenteclasseenergetica'] != null && value['esenteclasseenergetica'] == '1'){
				html += '<tr valign="top"><th scope="row">Esente classe energetica</th><td>Si</td></tr>';
			}
			if(swp180214_dettaglio_classeenergetica && value['classeenergetica'] != null){			
				html += '<tr valign="top"><th scope="row">Classe energetica</th><td>'+value['classeenergetica']+'</td></tr>';
			}
			if(swp180214_dettaglio_ipe && value['ipe'] != null){			
				html += '<tr valign="top"><th scope="row">I.P.E.</th><td>'+value['ipe'].substring(0,value['ipe'].length - 3)+' Kwh/mq anno</td></tr>';
			}
		});
	}
	
	html += '</tbody></table></div>';
	
	if(swp180214_dettaglio_descrizione){
		html += '<label id="wp180214_dettaglio_descrizione_label">Descrizione</label>';
		jQuery.each(json.T1,function(index,value){
			if(value['testo'] != ''){
				html += '<div id="wp180214_dettaglio_descrizione">'+value['testo']+'</div>' ;
			}			
		});
	}
	
	if(swp180214_dettaglio_video){
		html += '<label id="wp180214_dettaglio_video_label">Video</label>';
		html += '<div id="wp180214_dettaglio_video">';
		jQuery.each(json.T0,function(index,value){
			if(value['urlvideo'] != null){
				html += '<a href="'+value['urlvideo']+'"><img src="'+swp180214_dettaglio_youtube+'" /></a>';
			}
			if(value['idyoutube1'] != null){
				html += '<iframe width="560" height="315" src="http://www.youtube.com/embed/'+value['idyoutube1']+'" frameborder="0" allowfullscreen></iframe>' ;
			}
			if(value['idyoutube2'] != null){
				html += '<iframe width="560" height="315" src="http://www.youtube.com/embed/'+value['idyoutube2']+'" frameborder="0" allowfullscreen></iframe>' ;
			}
			if(value['idyoutube3'] != null){
				html += '<iframe width="560" height="315" src="http://www.youtube.com/embed/'+value['idyoutube3']+'" frameborder="0" allowfullscreen></iframe>' ;
			}
			if(value['idyoutube4'] != null){
				html += '<iframe width="560" height="315" src="http://www.youtube.com/embed/'+value['idyoutube4']+'" frameborder="0" allowfullscreen></iframe>' ;
			}
		});
		
		html += '</div>';
	}
	
	/*if(swp180214_dettaglio_mappa){
		jQuery.each(json.T0,function(index,value){
			if(value['pubblicamappa'] != null && value['pubblicamappa'] == '1'){
				html += '<label id="wp180214_dettaglio_mappa_label">Mappa</label>';
			}
		});
	}*/
	
	jQuery('#wp180214_dettaglio').html(html);
}
