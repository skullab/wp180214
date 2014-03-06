<?php
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

/* SHORTCODE TAG : WP180214
 * ATTRIBUTI :
 * DIV = STRING - RICERCA | RISULTATI | IMMOBILI - DEFAULT NULL
 * CLASS = STRING (nome della classe css) - DEFAULT NULL
 * 
 * DIV => RICERCA
 * 	CONTRATTO = BOOLEAN - DEFAULT TRUE
 * 	CATEGORIA = BOOLEAN - DEFAULT TRUE
 * 	PREZZO = BOOLEAN - DEFAULT TRUE
 *  LIBERO = BOOLEAN - DEFAULT FALSE
 *  
 * DIV => RISULTATI
 *  LIMIT = INTEGER | 0 = SENZA LIMITI - DEFAULT 0
 *  TITOLO = BOOLEAN - DEFAULT TRUE
 *  TESTOBREVE = BOOLEAN - DEFAULT FALSE
 *  THUMB = BOOLEAN - DEFAULT TRUE
 *  ULTIMI = INTEGER | 0 = NESSUNO - DEFAULT 0
 *  
 * DIV => DETTAGLIO
 *  DESCRIZIONE = BOOLEAN - DEFAULT TRUE
 *  TITOLO = BOOLEAN - DEFAULT TRUE
 *  TESTOBREVE = BOOLEAN - DEFAULT FALSE
 *  TESTO = BOOLEAN - DEFAULT TRUE
 *  LINGUA = BOOLEAN - DEFAULT TRUE
 *  CODICENAZIONE = BOOLEAN - DEFAULT FALSE
 *  CODICECOMUNE = BOOLEAN - DEFAULT TRUE
 *  QUARTIERE = BOOLEAN - DEFAULT TRUE
 *  LOCALITA = BOOLEAN - DEFAULT TRUE
 *  ZONA = BOOLEAN - DEFAULT TRUE
 *  STRADA = BOOLEAN - DEFAULT TRUE
 *  INDIRIZZO = BOOLEAN - DEFAULT TRUE
 *  CIVICO = BOOELAN - DEFAULT TRUE
 *  CAP = BOOLEAN - DEFAULT TRUE
 *  MAPPA = BOOLEAN - DEFAULT TRUE
 *  NRLOCALI = BOOLEAN - DEFAULT TRUE
 *  NRVANI = BOOLEAN - DEFAULT TRUE
 *  PREZZO = BOOLEAN - DEFAULT TRUE
 *  MQSUPERFICIE = BOOLEAN - DEFAULT TRUE
 *  RIFERIMENTO = BOOLEAN - DEFAULT TRUE
 *  SPESEMENSILI = BOOLEAN - DEFAULT TRUE
 *  TIPOSPESE = BOOLEAN - DEFAULT TRUE
 *  DURATACONTRATTO = BOOLEAN - DEFAULT TRUE
 *  TIPOPROPRIETA = BOOLEAN - DEFAULT TRUE
 *  BAGNI = BOOLEAN - DEFAULT TRUE
 *  CUCINA = BOOLEAN - DEFAULT TRUE
 *  TERRAZZI = BOOLEAN - DEFAULT TRUE
 *  BOXAUTO = BOOLEAN - DEFAULT TRUE
 *  CANTINA = BOOLEAN - DEFAULT TRUE
 *  GIARDINO = BOOLEAN - DEFAULT TRUE
 *  RISCALDAMENTO = BOOLEAN - DEFAULT TRUE
 *  ARREDAMENTO = BOOLEAN - DEFAULT TRUE
 *  CLASSEENERGETICA = BOOLEAN - DEFAULT TRUE
 *  IPE = BOOLEAN - DEFAULT TRUE
 *  VIDEO = BOOLEAN - DEFAULT TRUE
 *  IMMAGINI = BOOLEAN - DEFAULT TRUE
 *  ALLEGATI = BOOLEAN - DEFAULT FALSE
 *  CUSTOM = {STRING - (nome tabella) , STRING - (nome colonna)}
 */
$swp180214_shortcode_atts = array(
'div' 			=> null,
'class' 		=> null,
'contratto' 	=> 'true',
'categoria' 	=> 'true',
'prezzo'		=> 'true',
'libero'		=> 'false',
'thumb'			=> 'true',
'ultimi'		=> 0,
'limit'			=> 0,
'descrizione'	=> 'true',
'titolo'		=> 'true',
'testobreve'	=> 'true',
'testo'			=> 'true',
'lingua'		=> 'true',
'codicenazione'	=> 'false',
'codicecomune'	=> 'true',
'quartiere'		=> 'true',
'localita'		=> 'true',
'zona'			=> 'true',
'strada'		=> 'true',
'indirizzo'		=> 'true',
'civico'		=> 'true',
'cap'			=> 'true',
'mappa'			=> 'true',
'nrlocali'		=> 'true',
'nrvani'
);

function swp180214_shortcode($atts,$content = null){
	global $swp180214_shortcode_atts,$wpdb ;
	extract(shortcode_atts($swp180214_shortcode_atts,$atts));
	wp_enqueue_style(SWP180214_CSS_SHORTCODE);
	wp_enqueue_script(SWP180214_JS_SHORTCODE);
	wp_localize_script(SWP180214_JS_SHORTCODE,'swp180214_ajax_placeholder',
	array('url' => admin_url('admin-ajax.php')));
	
	ob_start();
	if($div != null){
		?><div id="wp180214_<?php echo strtolower($div) ;?>" <?php if($class != null) echo "class=\"$class\"" ; ?> >
		<?php 		
			switch (strtolower($div)){
//===================================================================================================
				case 'ricerca' :
					?>
					<form name="wp180214_form_ricerca" action="#">
					<table id="wp180214_ricerca_table">
					<?php 
					if(strtolower($contratto) == 'true'){
					?><tr valign="top"><th scope="row">Contratto</th>
						<td>
						<input id="wp180214_ricerca_contratto_affitto" type="radio" value="A" name="wp180214_ricerca_contratto" />
						<label for="wp180214_ricerca_contratto_affitto">Affitto</label>
						<input id="wp180214_ricerca_contratto_vendita" type="radio" value="V" name="wp180214_ricerca_contratto" checked="checked"/>
						<label for="wp180214_ricerca_contratto_vendita">Vendita</label> 
						</td>
					  </tr>
					<?php 
					}
					if(strtolower($categoria) == 'true'){
					?><tr valign="top"><th scope="row">Categoria</th>
						<td>
						<select name="wp180214_ricerca_categoria">
						<?php
						$results = $wpdb->get_results("SELECT * FROM ".swp180214_table_prefix()."categorie");
						foreach ($results as $result){
						?>
						<option value="<?php echo strtolower($result->categoria) ;?>"><?php echo $result->annotazione?></option>
						<?php 
						} 
						?>
						</select>
						</td>
					  </tr>
					<?php 
					}
					if(strtolower($prezzo) == 'true'){
					?><tr valign="top"><th scope="row">Prezzo (&euro;)</th>
						<td>
						<label for="wp180214_ricerca_prezzo_minimo">da</label>
						<input id="wp180214_ricerca_prezzo_minimo" type="number" name="wp180214_ricerca_prezzo_minimo" placeholder="minimo"/>
						<label for="wp180214_ricerca_prezzo_massimo">a</label> 
						<input id="wp180214_ricerca_prezzo_massimo" type="number" name="wp180214_ricerca_prezzo_massimo" placeholder="massimo"/>						
						</td>
					</tr>
					<?php 
					}
					if(strtolower($libero) == 'true'){
					?><tr valign="top"><th scope="row">Testo</th>
						<td>
						<input id="wp180214_ricerca_libero" type="text" name="wp180214_ricerca_libero" size="30" placeholder="immetere un testo da cercare..."/>			
						</td>
					</tr>
					<?php 	
					}
					if(strtolower($contratto) == 'true' || strtolower($categoria) == 'true' || strtolower($prezzo) == 'true' || strtolower($libero) == 'true'){
					?><tr valign="top"><th scope="row"></th>
						<td>
							<input id="wp180214_ricerca_button" type="button" value="CERCA" onclick="swp180214_shortcode_search('<?php echo wp_create_nonce('swp180214_action_shortcode_search_nonce');?>');"/>
						</td>
					  </tr>
					<?php 
					}
					?>
					</table>
					</form><?php 
					break;
//===================================================================================================
				case 'risultati':
					wp_localize_script(SWP180214_JS_SHORTCODE,'swp180214_js_placeholder',
					array(
						'limit'			=> is_numeric($limit)? $limit : 0 ,
						'titolo' 		=> strtolower($titolo) == 'true' ? true : false ,
						'testobreve' 	=> strtolower($testobreve) == 'true' ? true : false ,
						'thumb' 		=> strtolower($thumb) == 'true' ? true : false ,
						'ultimi'		=> is_numeric($ultimi)? $ultimi : 0 ,
						'loader'		=> '<div id="wp180214_risultati_loader"><img src="'.plugins_url('res/images/circular_loader.gif',__FILE__).'" />&nbsp;Ricerca in corso...</div>',
 						'noimage'		=> plugins_url('res/images/no_image.png',__FILE__)
						));
					if(is_numeric($ultimi) && $ultimi > 0){
						$sql = 	"SELECT ".swp180214_table_prefix()."descrizione.idimmobile,
 								".swp180214_table_prefix()."descrizione.titolo,
 								".swp180214_table_prefix()."descrizione.testo,
 								".swp180214_table_prefix()."descrizione.testobreve,
 								".swp180214_table_prefix()."immagine.url AS thumb,
 								".swp180214_table_prefix()."immobile.datamodifica
 								FROM ".swp180214_table_prefix()."descrizione
 								LEFT OUTER JOIN ".swp180214_table_prefix()."immagine
 								ON ".swp180214_table_prefix()."descrizione.idimmobile = ".swp180214_table_prefix()."immagine.idimmobile
 								LEFT OUTER JOIN ".swp180214_table_prefix()."immobile
 								ON ".swp180214_table_prefix()."descrizione.idimmobile = ".swp180214_table_prefix()."immobile.idimmobile
 								WHERE ".swp180214_table_prefix()."descrizione.idimmobile IN (" ;

						$sql .= "SELECT idimmobile FROM ".swp180214_table_prefix()."immobile" ;
						$sql .= ") GROUP BY idimmobile ORDER BY ".swp180214_table_prefix()."immobile.datamodifica DESC LIMIT $ultimi" ;
						
						global $wpdb ;
						$results = $wpdb->get_results($sql,ARRAY_A);
						if($wpdb->num_rows > 0){
							foreach ($results as $row){
							?>
							<div class="wp180214_risultati_risultato" onclick="swp180214_shortcode_details('<?php echo $row['idimmobile']?>',this);">
							<?php
							if(strtolower($thumb) == 'true'){
								if($row['thumb'] != null){
									?><div class="wp180214_risultati_risultato_thumb"><img src="<?php echo $row['thumb'] ;?>" width="150" height="150"/></div><?php
								}else{
									?><div class="wp180214_risultati_risultato_thumb"><img src="<?php echo plugins_url('res/images/no_image.png',__FILE__);?>" width="150" height="150" /></div><?php 
								}
							}
							if(strtolower($titolo) == 'true'){
								if($row['titolo'] == ''){
									$row['titolo'] = substr($row['testo'], 0,50);
								}
								?><div class="wp180214_risultati_risultato_titolo"><?php echo $row['titolo'] ;?></div><?php 
							}
							if(strtolower($testobreve) == 'true'){
								if($row['testobreve'] == ''){
									$row['testobreve'] = substr($row['testo'],0,150)."..." ;
								}
								?><div class="wp180214_risultati_risultato_testobreve"><?php echo $row['testobreve'];?></div><?php 
							} 
							?>
							</div>
							<?php
							}
							 
						}else{
							
						}
										
					}
					break;
//===================================================================================================
				case 'dettaglio':
					?>
					
					<?php 
					break;
				default:
					if(!is_null($content)){
						echo do_shortcode($content);
					}	
			} 
		?>		
		</div>
		<?php 
	}
	return ob_get_clean();
}
add_shortcode( SWP180214_SHORTCODE, 'swp180214_shortcode' );

function swp180214_shortcode_search(){
	if(wp_verify_nonce( $_REQUEST['_nonce'], 'swp180214_action_shortcode_search_nonce')){
	global $wpdb ;
	
	$categoria = isset($_REQUEST['categoria']) ? $_REQUEST['categoria'] : 0 ;
	$contratto = isset($_REQUEST['contratto']) ? $_REQUEST['contratto'] : '' ;
	$prezzo_minimo = isset($_REQUEST['prezzo_minimo']) ? $_REQUEST['prezzo_minimo'] : '' ;
	$prezzo_massimo = isset($_REQUEST['prezzo_massimo']) ? $_REQUEST['prezzo_massimo'] : '' ;
	$libero = isset($_REQUEST['libero']) ? $_REQUEST['libero'] : '' ;
	
	$sql = 	"SELECT ".swp180214_table_prefix()."descrizione.idimmobile,
 			".swp180214_table_prefix()."descrizione.titolo,
 			".swp180214_table_prefix()."descrizione.testo,
 			".swp180214_table_prefix()."descrizione.testobreve,
 			".swp180214_table_prefix()."immagine.url AS thumb
 			FROM ".swp180214_table_prefix()."descrizione 
 			LEFT OUTER JOIN ".swp180214_table_prefix()."immagine 
 			ON ".swp180214_table_prefix()."descrizione.idimmobile = ".swp180214_table_prefix()."immagine.idimmobile 
 			WHERE ".swp180214_table_prefix()."descrizione.idimmobile IN (" ;
	
	$sql .= "SELECT idimmobile FROM ".swp180214_table_prefix()."immobile" ;
	
	if($categoria != 0){
		$sql .= " WHERE categoria = $categoria" ;
	}else{
		$sql .= " WHERE categoria > 0" ;
	}
	
	if($contratto != '' ){
		$sql .= " AND contratto = '$contratto'" ;
	}
	
	if($prezzo_minimo != '' && is_numeric($prezzo_minimo)){
		$sql .= " AND prezzo >= $prezzo_minimo" ;
	}
	
	if($prezzo_massimo != '' && is_numeric($prezzo_massimo)){
		$sql .= " AND prezzo <= $prezzo_massimo" ;
	}
	
	if($libero != ''){
		$libero = str_replace(" ", "%", $libero);
		$sql .= " AND ( codicecomune LIKE '%$libero%' OR quartiere LIKE '%$libero%' OR zona LIKE '%$libero%' OR indirizzo LIKE '%$libero%' OR cap LIKE '%$libero%' or strada LIKE '%$libero%' or tipologia LIKE '%$libero%' OR riferimento LIKE '%$libero%')" ;
	}
	
	$sql .= ") GROUP BY idimmobile" ;
	
	$results = $wpdb->get_results($sql,ARRAY_A);
	if($wpdb->num_rows > 0){
		$response = json_encode($results);
	}else{
		$response = '0';
	}
	echo $response ;
	
	}else die('error');
	die();
}

function swp180214_shortcode_details(){
	if(isset($_REQUEST['idimmobile'])){
		global $wpdb ;
		$idimmobile = $_REQUEST['idimmobile'] ;
		$sql_immobile = "SELECT * FROM ".swp180214_table_prefix()."immobile WHERE idimmobile = $idimmobile" ;
		
	}else die('error');
	die();
}
?>