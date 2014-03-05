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
 * DIV = RICERCA | RISULTATI | IMMOBILI
 * CLASS = nome della classe css
 * 
 * DIV => RICERCA
 * 	CONTRATTO = TRUE | FALSE
 * 	TIPOLOGIA = TRUE | FALSE
 * 	PREZZO = TRUE | FALSE
 */
$swp180214_shortcode_atts = array(
'div' 		=> null,
'class' 	=> null,
'contratto' => 'true',
'tipologia' => 'true',
'prezzo'	=> 'true',
);
function swp180214_shortcode($atts,$content){
	global $swp180214_shortcode_atts,$wpdb ;
	extract(shortcode_atts($swp180214_shortcode_atts,$atts));
	
	ob_start();
	if($div != null){
		?><div id="wp180214_<?php echo strtolower($div) ;?>" <?php if($class != null) echo "class=\"$class\"" ; ?> >
			
		<?php
			switch (strtolower($div)){
//===================================================================================================
				case 'ricerca' :
					?>
					<form>
					<table>
					<?php 
					if(strtolower($contratto) == 'true'){
					?><tr valign="top"><th scope="row">Contratto</th>
						<td>
						<input id="wp180214_ricerca_contratto_affitto" type="radio" value="affito" name="wp180214_ricerca_contratto" checked="checked"/>
						<label for="wp180214_ricerca_contratto_affitto">Affitto</label>
						<input id="wp180214_ricerca_contratto_vendita" type="radio" value="vendita" name="wp180214_ricerca_contratto"/>
						<label for="wp180214_ricerca_contratto_vendita">Vendita</label> 
						</td>
					  </tr>
					<?php 
					}
					if(strtolower($tipologia) == 'true'){
					?><tr valign="top"><th scope="row">Tipologia</th>
						<td>
						<select name="wp180214_ricerca_tipologia">
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
					?><tr valign="top"><th scope="row"></th>
						<td>
							<input id="wp180214_ricerca_button" type="button" value="CERCA" />
						</td>
					  </tr>
					</table>
					</form><?php 
					break;
//===================================================================================================
				case 'risultati':
					?>
					
					<?php 
					break;
//===================================================================================================
				case 'immobili':
					break;
			} 
		?>		
		</div>
		<?php 
	}
	return ob_get_clean();
}
add_shortcode( SWP180214_SHORTCODE, 'swp180214_shortcode' );
?>