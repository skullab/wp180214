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

function swp180214_confirm_create_page(){
	//swp180214_debug('richiesta pagina');
	if(wp_verify_nonce( $_REQUEST['_nonce'], 'swp180214_action_create_page_nonce' )){
		update_option(SWP180214_OPT_PAGE_CREATED,true);
		echo 'Pagina creata !';
	}else die('RICHIESTA NON PERMESSA');
	die();
}

function swp180214_confirm_update_page(){
	if(wp_verify_nonce( $_REQUEST['_nonce'], 'swp180214_action_update_page_nonce' )){
		update_option(SWP180214_OPT_PAGE_UPDATED,true);
	}else die('RICHIESTA NON PERMESSA');
	die();
}

function swp180214_confirm_delete_page(){
	if(wp_verify_nonce( $_REQUEST['_nonce'], 'swp180214_action_delete_page_nonce' )){
		update_option(SWP180214_OPT_GETRIX_PAGE_ID,0);
		update_option(SWP180214_OPT_GETRIX_PAGE_NAME,'');
		update_option(SWP180214_OPT_GETRIX_PAGE_TITLE,'');
		update_option(SWP180214_OPT_GETRIX_PAGE_CONTENT,'');
		update_option(SWP180214_OPT_GETRIX_PAGE_PARENT_ID,0);
		update_option(SWP180214_OPT_GETRIX_PAGE_MENU_ORDER,0);
		update_option(SWP180214_OPT_GETRIX_PAGE_USER_ID,false);
		update_option(SWP180214_OPT_GETRIX_PAGE_STATUS,'publish');
		update_option(SWP180214_OPT_PAGE_CREATED,false);
		echo 'Pagina rimossa !';
	}else die('RICHIESTA NON PERMESSA');
	die();
}
function swp180214_update_display_page(){
	
	if(!get_option(SWP180214_OPT_PAGE_CREATED))return;
	
	global $user_ID ;
	
	$page = array();
	$page['post_type']    = 'page';
	$page['post_author']  = $user_ID;
	
	//PAGE EXIST ?
	if(get_option(SWP180214_OPT_GETRIX_PAGE_ID) != 0){
		$page['ID'] 			= get_option(SWP180214_OPT_GETRIX_PAGE_ID);		
		$page['post_content'] 	= get_post($page['ID'])->post_content ;
		$page['post_parent']	= get_post($page['ID'])->post_parent ;
		$page['post_status']	= get_post($page['ID'])->post_status ;
		$page['post_title']		= get_post($page['ID'])->post_title ;
		$page['menu_order']		= get_post($page['ID'])->menu_order ;		
	}

	//UPDATE REQUEST OR NEW PAGE ?
	if(get_option(SWP180214_OPT_PAGE_UPDATED) || get_option(SWP180214_OPT_GETRIX_PAGE_ID) == 0){
		$page['post_content'] = get_option(SWP180214_OPT_GETRIX_PAGE_CONTENT);
		$page['post_parent']  = get_option(SWP180214_OPT_GETRIX_PAGE_PARENT_ID);
		$page['post_status']  = get_option(SWP180214_OPT_GETRIX_PAGE_STATUS);
		$page['post_title']   = get_option(SWP180214_OPT_GETRIX_PAGE_TITLE);
		$page['menu_order']   = get_option(SWP180214_OPT_GETRIX_PAGE_MENU_ORDER);
	}else{
		update_option(SWP180214_OPT_GETRIX_PAGE_CONTENT,$page['post_content']);
		update_option(SWP180214_OPT_GETRIX_PAGE_PARENT_ID,$page['post_parent']);
		update_option(SWP180214_OPT_GETRIX_PAGE_STATUS,$page['post_status']);
		update_option(SWP180214_OPT_GETRIX_PAGE_TITLE,$page['post_title']);
		update_option(SWP180214_OPT_GETRIX_PAGE_MENU_ORDER,$page['menu_order']);
	}
		
	//$page = apply_filters('swp180214_display_page', $page);
	
	if(get_option(SWP180214_OPT_GETRIX_PAGE_ID) != 0 && get_option(SWP180214_OPT_PAGE_UPDATED)){
		$pageid = wp_update_post($page);
		update_option(SWP180214_OPT_PAGE_UPDATED,false);
	}else{
		$pageid = wp_insert_post ($page);
	}
	
	if ($pageid == 0) {
		?>
		<strong>Errore durante la creazione della pagina !</strong>
		<?php 	
	}else{
		update_option(SWP180214_OPT_GETRIX_PAGE_ID,$pageid);
		?>
		<span class="description"><h3>ID PAGINA : <?php echo get_option(SWP180214_OPT_GETRIX_PAGE_ID)?></h3></span>
		<?php 
	}
}

function swp180214_get_page_onsubmit(){
	if(get_option(SWP180214_OPT_PAGE_CREATED)){
		?>
		onsubmit="swp180214_request_update_page('<?php echo wp_create_nonce('swp180214_action_update_page_nonce')?>');"
		<?php 
	}
}
function swp180214_get_display_page_settings(){
	?>
	<style type="text/css">
	#swp180214_display_page label.error { color:#a10000; margin-left:5px;}
	</style>
	<form id="<?php echo SWP180214_PREFIX.'display_page'?>" method="post" action="options.php" <?php swp180214_get_page_onsubmit()?>>
	<?php 
		settings_fields(SWP180214_OPT_GROUP_PAGE);
		do_settings_sections(SWP180214_OPT_GROUP_PAGE);
	
	
	if(!get_option(SWP180214_OPT_PAGE_CREATED)){
		?>
		<script type="text/javascript">
			jQuery(document).ready(function($){
				$('#swp180214_display_page_settings').css('display','block');
				$('#swp180214_display_page_content').css('display','none');
			});
		</script>
		<?php 
	}else{
		?>
		<script type="text/javascript">
			jQuery(document).ready(function($){
				$('#swp180214_display_page_settings').css('display','none');
				$('#swp180214_display_page_content').css('display','block');
				
				$('#swp180214_display_page_show_settings').click(function(){
						if($('#swp180214_display_page_settings').css('display') == 'none'){
							$('#swp180214_display_page_content').slideUp(200);
							$('#swp180214_display_page_settings').slideDown(200);							
						}
				});

				$('#swp180214_display_page_show_content').click(function(){
					if($('#swp180214_display_page_content').css('display') == 'none'){						
						$('#swp180214_display_page_content').slideDown(200);
						$('#swp180214_display_page_settings').slideUp(200);							
					}
				});

				$('#swp180214_display_page_delete').click(function(){
					var response = confirm("Si vuole davvero rimuovere la pagina di visualizzazione ?");
					if(response == true){					
						swp180214_request_delete_page('<?php echo wp_create_nonce('swp180214_action_delete_page_nonce');?>');
					}
				});
			});
		</script>
		<a id="swp180214_display_page_show_settings" class="button-primary">Impostazioni pagina</a>&nbsp;&nbsp;
		<a id="swp180214_display_page_show_content" class="button-primary">Contenuto pagina</a>&nbsp;&nbsp;
		<a id="swp180214_display_page_delete" class="button-primary">Rimuovi collegamento pagina</a>
		<?php 
	}
		
	?>
	<div id="swp180214_display_page_settings">
	<table class="form-table">
	<tbody>
		<?php
			if(!get_option(SWP180214_OPT_PAGE_CREATED)){
				?>
				<strong>Non &grave; stata creata ancora nessuna pagina di visualizzaizone ! Completare il form per crearla.</strong>
				<?php 
			}else{
				swp180214_update_display_page();
			}
			
		?>
		
		<tr valign="top"><th scope="row">Titolo pagina</th>
			<td>
				<input id="<?php echo SWP180214_OPT_GETRIX_PAGE_TITLE ;?>" name="<?php echo SWP180214_OPT_GETRIX_PAGE_TITLE ;?>" size="50" value="<?php echo get_option(SWP180214_OPT_GETRIX_PAGE_TITLE);?>" required/><br>
				<span class="description">
				Il titolo della pagina di visualizzazione.
				</span>
			</td>
		</tr>
		
		<tr valign="top"><th scope="row">Nome slug della pagina</th>
			<td>
				<input id="<?php echo SWP180214_OPT_GETRIX_PAGE_NAME ;?>" name="<?php echo SWP180214_OPT_GETRIX_PAGE_NAME ;?>" size="50" value="<?php echo get_option(SWP180214_OPT_GETRIX_PAGE_NAME);?>" required/><br>
				<span class="description">
				Il nome slug della pagina di visualizzazione che apparira nella barra degli indirizzi.
				</span>
			</td>
		</tr>
		
		<tr valign="top"><th scope="row">Pagina genitore</th>
			<td>				
				<select id="<?php echo SWP180214_OPT_GETRIX_PAGE_PARENT_ID ;?>" name="<?php echo SWP180214_OPT_GETRIX_PAGE_PARENT_ID ;?>">
				<option value="0">Nessuna</option>
				<?php
					$pages = get_pages(array(
								'sort_order' => 'ASC',
								'sort_column' => 'post_title',
								'hierarchical' => 1,
								'exclude' => '',
								'include' => '',
								'meta_key' => '',
								'meta_value' => '',
								'authors' => '',
								'child_of' => 0,
								'parent' => -1,
								'exclude_tree' => '',
								'number' => '',
								'offset' => 0,
								'post_type' => 'page',
								'post_status' => 'publish'));
					foreach ($pages as $p){
						echo '<option value="'.$p->ID.'">'.$p->post_title.'</option>';
					} 
				?>
				</select><br>				
				<span class="description">
				Il nome della pagina genitore alla quale questa pagina sara figlia.<br>
				Selezionare "Nessuna" per visualizzare la pagina allo stesso livello delle altre.
				</span>
			</td>
		</tr>
		
		<tr valign="top"><th scope="row">Ordinamento</th>
			<td>
				<input id="<?php echo SWP180214_OPT_GETRIX_PAGE_MENU_ORDER ;?>" name="<?php echo SWP180214_OPT_GETRIX_PAGE_MENU_ORDER ;?>" size="2" value="<?php echo get_option(SWP180214_OPT_GETRIX_PAGE_MENU_ORDER);?>" required/><br>
				<span class="description">
				Un numero che identifica l'ordine con il quale verra visualizzata la pagina nel menu.<br>
				Se non si &egrave; sicuri lasciare le impostazioni di default (0).
				</span>
			</td>
		</tr>
		
		<tr valign="top"><th scope="row">Stato</th>
			<td>
				<select id="<?php echo SWP180214_OPT_GETRIX_PAGE_STATUS ;?>" name="<?php echo SWP180214_OPT_GETRIX_PAGE_STATUS ;?>">
				<option value="publish" <?php selected(get_option(SWP180214_OPT_GETRIX_PAGE_STATUS),"publish");?>>Pubblica</option>
				<option value="draft" <?php selected(get_option(SWP180214_OPT_GETRIX_PAGE_STATUS),"draft");?>>Bozza</option>
				</select>
				<span class="description">
				Indica lo stato della pagina, come Pubblicata oppure come Bozza.
				</span>
			</td>
		</tr>
		
		<tr valign="top"><th scope="row"></th>
        	<td>
            	<p>	
            		<input type="hidden" name="<?php echo SWP180214_OPT_GETRIX_PAGE_ID ;?>" value="<?php echo get_option(SWP180214_OPT_GETRIX_PAGE_ID);?>" /> 
            		<?php
            			if(!get_option(SWP180214_OPT_PAGE_CREATED)){
            				?>
            				<input type="submit" class="button-primary" onclick="swp180214_request_create_page('<?php echo wp_create_nonce('swp180214_action_create_page_nonce');?>');" value="<?php _e('Crea la pagina'); ?> " />
            				<?php 
            			}else{
            				?>
            				<input type="submit" class="button-primary" id="submit" name="submit" value="<?php _e('Salva'); ?> " />
            				<?php 
            			} 
            		?>
                	
                	                               	
                    <div id="swp180214_loader_page" style="display: none;">
               			<img style="float:left;padding-right:10px;" src="<?php echo plugins_url('res/images/circular_loader.gif',__FILE__);?>"/>
               			<div><h3>Salvataggio in corso...</h3></div>
               		</div>                                  
                </p>
           	</td>
       </tr>
       
	</tbody>
	</table>
	</div>
	
	<div id="swp180214_display_page_content">
		<table class="form-table">
		<tbody>
			<span class="description">
			Contenuto della pagina di visualizzazione.<br>
			Utilizzare lo shortcode [WP180214] per i contenuti dinamici. Per maggiori informazioni consultare la <a href="<?php echo plugins_url('doc/doc.pdf',__FILE__);?>">documentazione</a>
			</span>
			<tr valign="top"><th scope="row">Contenuto</th>
				<td>
					<textarea rows="15" cols="100" name="<?php echo SWP180214_OPT_GETRIX_PAGE_CONTENT ;?>" required><?php echo get_option(SWP180214_OPT_GETRIX_PAGE_CONTENT);?></textarea>
				</td>
			</tr>
			
			<tr valign="top"><th scope="row"></th>
        	<td>
            	<p>
            		<input type="submit" class="button-primary" id="submit" name="submit" value="<?php _e('Salva'); ?> " />	
            	</p>
            </td>
            
		</tbody>
		</table>
	</div>
	</form>
	<?php 
}
?>