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

function swp180214_page_settings(){
	if(get_option(SWP180214_OPT_FIRST_INSTALL) || get_option(SWP180214_OPT_INSTALL_PROCESS) < 3){
		swp180214_page_install();
		return ;
	}
	?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"></div>
		<h2><?php echo SWP180214_PAGE_NAME_SETTINGS ; ?></h2>
		<span class="description"><?php echo SWP180214_PAGE_NAME_SETTINGS_DESCRIPTION?></span>
		
	</div><?php 
}

function swp180214_page_install_confirm(){
	if(wp_verify_nonce( $_REQUEST['_nonce'], 'swp180214_action_submit_install_nonce' )){
		update_option(SWP180214_OPT_INSTALL_PROCESS,1);
	}else die('ERRORE DURANTE L\'INSTALLAZIONE');
	die();
}

function swp180214_page_feed_confirm(){
	if(wp_verify_nonce( $_REQUEST['_nonce'], 'swp180214_action_submit_install_nonce' )){
		switch (get_option(SWP180214_OPT_GETRIX_FEED_UPDATE_MODE)){
			case SWP180214_AUTOMATIC:
				if (!wp_next_scheduled(SWP180214_UPDATE_DATA_HOOK)) {
					wp_schedule_event( time(), 'daily', SWP180214_UPDATE_DATA_HOOK);
				}
				break;
			case SWP180214_MANUAL:
				wp_clear_scheduled_hook( SWP180214_UPDATE_DATA_HOOK );
				break;
		}
		
		update_option(SWP180214_OPT_INSTALL_PROCESS,3);
	}else die('ERRORE DURANTE L\'INSTALLAZIONE');
	die();
}

// PROCEDURA DI INSTALLAZIONE
function swp180214_page_install(){
	wp_enqueue_script(SWP180214_JS_VALIDATOR);
	//'9F431778-4CAC-4534-B5F9-F458A87E2279'
	wp_localize_script(SWP180214_JS_VALIDATOR,'swp180214_js_placeholder',
		array('usercode' => SWP180214_DEFAULT_GETRIX_USER,
			  'feeduri' => SWP180214_DEFAULT_GETRIX_FEED_URI,
			  'process' => get_option(SWP180214_OPT_INSTALL_PROCESS)));
	wp_localize_script(SWP180214_JS_VALIDATOR,'swp180214_ajax_placeholder',
		array('url' => admin_url('admin-ajax.php'),'nonce' => wp_create_nonce('swp180214_action_submit_install_nonce')));
	
	if(get_option(SWP180214_OPT_INSTALL_PROCESS) == 3){
		update_option(SWP180214_OPT_FIRST_INSTALL,false);
		swp180214_page_settings();
		return;
	}
	
	if(get_option(SWP180214_OPT_INSTALL_PROCESS) == 1){
		?>		
		<div class="wrap">
			<div id="icon-options-general" class="icon32"></div>
			<h2><?php echo SWP180214_PAGE_NAME_INSTALL ; ?></h2>
			<span class="description"><?php echo SWP180214_PAGE_NAME_INSTALL_DESCRIPTION?></span>
			
            <div class="description" id="swp180214_install_info">
            	<ul type="circle">
               		<?php swp180214_install_db(); ?>
               	</ul>
         	</div>
           
           <a href="admin.php?page=<?php echo SWP180214_SLUG_SETTINGS ;?>" class="button-primary">Prosegui</a>
		</div><?php
		return;
	}
	
	if(get_option(SWP180214_OPT_INSTALL_PROCESS) == 2){
		?>
		<style type="text/css">
			#swp180214_install_form label.error { color:#a10000; margin-left:5px;}
		</style>
		<div class="wrap">
			<div id="icon-options-general" class="icon32"></div>
			<h2><?php echo SWP180214_PAGE_NAME_INSTALL ; ?></h2>
			<span class="description"><?php echo SWP180214_PAGE_NAME_INSTALL_DESCRIPTION?></span>
			<form id="<?php echo SWP180214_PREFIX.'install_form'?>" method="post" action="options.php" onsubmit="swp180214_onsubmit()">
			<?php 
			settings_fields(SWP180214_OPT_GROUP_FEED);
			do_settings_sections(SWP180214_OPT_GROUP_FEED);
			?>
			<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">Getrix Feed URI</th>
					<td>
						<label for="<?php echo SWP180214_OPT_GETRIX_FEED_URI ;?>"></label>
						<input id="<?php echo SWP180214_OPT_GETRIX_FEED_URI ;?>" type="url" size="100" name="<?php echo SWP180214_OPT_GETRIX_FEED_URI ;?>" value="<?php echo get_option(SWP180214_OPT_GETRIX_FEED_URI);?>" required/><br>
						<span class="description">
						Indirizzo URI relativo al feed XML da utilizzare.<br>Se non si &egrave; sicuri lasciare le impostazioni di default.
						</span>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row">Modalita di aggiornamento dati</th>
					<td>
						
						<input id="<?php echo SWP180214_OPT_GETRIX_FEED_UPDATE_MODE ;?>" type="radio" name="<?php echo SWP180214_OPT_GETRIX_FEED_UPDATE_MODE ;?>" value="<?php echo SWP180214_AUTOMATIC;?>" <?php checked(get_option(SWP180214_OPT_GETRIX_FEED_UPDATE_MODE),SWP180214_AUTOMATIC);?>/>
						<label for="<?php echo SWP180214_OPT_GETRIX_FEED_UPDATE_MODE ;?>">Automatica (CronJob)</label>
						<br>
						<input id="<?php echo SWP180214_OPT_GETRIX_FEED_UPDATE_MODE ;?>" type="radio" name="<?php echo SWP180214_OPT_GETRIX_FEED_UPDATE_MODE ;?>" value="<?php echo SWP180214_MANUAL;?>" <?php checked(get_option(SWP180214_OPT_GETRIX_FEED_UPDATE_MODE),SWP180214_MANUAL);?>/>
						<label for="<?php echo SWP180214_OPT_GETRIX_FEED_UPDATE_MODE ;?>">Manuale</label>
						<br>
						<span class="description">
						Specifica la modalita di aggiornamento dei dati.<br>Se non si &egrave; sicuri lasciare le impostazioni di default.
						</span>
					</td>
				</tr>
				
				<tr valign="top">
                	<th scope="row"></th>
                    	<td>
                        	<p>
                            	<input type="submit" class="button-primary" id="submit" name="submit" value="<?php _e('Prosegui') ?> " />
                               	<input type="button" class="button-primary" onclick="swp180214_restore_default_feed_values()" value="<?php _e('Ripristina valori di default') ?> " />
                               	<div id="swp180214_loader" style="display: none;">
               						<img style="float:left;padding-right:10px;" src="<?php echo plugins_url('res/images/circular_loader.gif',__FILE__);?>"/>
               						<div><h3>Salvataggio in corso...</h3></div>
               					</div>                                  
                            </p>
                        </td>
                </tr>
				
			</tbody>
			</table>
			</form>
		</div><?php
		return;
	}
	
	if(get_option(SWP180214_OPT_INSTALL_PROCESS) == 0){
	?>
	<style type="text/css">
		#swp180214_install_form label.error { color:#a10000; margin-left:5px;}
	</style>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"></div>
		<h2><?php echo SWP180214_PAGE_NAME_INSTALL ; ?></h2>
		<span class="description"><?php echo SWP180214_PAGE_NAME_INSTALL_DESCRIPTION?></span>
		<form id="<?php echo SWP180214_PREFIX.'install_form'?>" method="post" action="options.php" onsubmit="swp180214_onsubmit()">
		<?php 
			settings_fields(SWP180214_OPT_GROUP_INSTALL);
			do_settings_sections(SWP180214_OPT_GROUP_INSTALL);
		?>
		<table class="form-table">
			<tbody>
			<tr valign="top">
				<th scope="row">Getrix Schema URI</th>
				<td>
					<label for="<?php echo SWP180214_OPT_GETRIX_SCHEMA_URI ;?>"></label>
					<input id="<?php echo SWP180214_OPT_GETRIX_SCHEMA_URI ;?>" type="url" size="100" name="<?php echo SWP180214_OPT_GETRIX_SCHEMA_URI ;?>" value="<?php echo get_option(SWP180214_OPT_GETRIX_SCHEMA_URI);?>" required/><br>
					<span class="description">
					Indirizzo URI relativo allo schema XSD da utilizzare.<br>Se non si &egrave; sicuri lasciare le impostazioni di default.
					</span>
				</td>
			</tr>
			
			<tr valign="top">
				<th scope="row">Getrix versione schema</th>
				<td>
					<label for="<?php echo SWP180214_OPT_GETRIX_SCHEMA_VERSION ;?>"></label>
					<input id="<?php echo SWP180214_OPT_GETRIX_SCHEMA_VERSION ;?>" type="text" size="10" name="<?php echo SWP180214_OPT_GETRIX_SCHEMA_VERSION ;?>" value="<?php echo get_option(SWP180214_OPT_GETRIX_SCHEMA_VERSION);?>" required/><br>
					<span class="description">
					Versione dello schema da utilizzare.<br>Se non si &egrave; sicuri lasciare le impostazioni di default.
					</span>
				</td>
			</tr>
			
			<tr valign="top">
				<th scope="row">Getrix codice utente</th>
				<td>
					<label for="<?php echo SWP180214_OPT_GETRIX_USER ;?>"></label>
					<input id="<?php echo SWP180214_OPT_GETRIX_USER ;?>" type="text" size="100" name="<?php echo SWP180214_OPT_GETRIX_USER ;?>" value="<?php echo get_option(SWP180214_OPT_GETRIX_USER);?>" required/><br>
					<span class="description">
					Inserire il codice utente rilasciato dal fornitore del feed.<br>Se non si &egrave; sicuri lasciare le impostazioni di default.
					</span>
				</td>
			</tr>
			
			<tr valign="top">
                       <th scope="row"></th>
                           <td>
                               <p>
                                   <input type="submit" class="button-primary" id="submit" name="submit" value="<?php _e('Installa') ?> " />
                                   <input type="button" class="button-primary" onclick="swp180214_restore_default_install_values()" value="<?php _e('Ripristina valori di default') ?> " />
                                  	<div id="swp180214_loader" style="display: none;">
               							<img style="float:left;padding-right:10px;" src="<?php echo plugins_url('res/images/circular_loader.gif',__FILE__);?>"/>
               							<div><h3>Installazione in corso...</h3></div>
               						</div>                                  
                               </p>
                           </td>
                   </tr>
			</tbody>
		</table>
		</form>
	</div><?php
	} 
}
?>