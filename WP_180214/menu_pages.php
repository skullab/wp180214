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
	
	wp_enqueue_style(SWP180214_CSS_SETTINGS_MENU,plugins_url('css/settings_style.css',__FILE__));
	wp_enqueue_script(SWP180214_JS_SETTINGS_PAGE);
	wp_localize_script(SWP180214_JS_SETTINGS_PAGE,'swp180214_ajax_placeholder',
	array('url' => admin_url('admin-ajax.php')));
	
	wp_enqueue_script(SWP180214_JS_VALIDATOR);
	wp_localize_script(SWP180214_JS_VALIDATOR,'swp180214_js_placeholder',
		array('usercode' => SWP180214_DEFAULT_GETRIX_USER,
			  'feeduri' => SWP180214_DEFAULT_GETRIX_FEED_URI,
			  'process' => get_option(SWP180214_OPT_INSTALL_PROCESS)));
	
	wp_localize_script(SWP180214_JS_VALIDATOR,'swp180214_ajax_placeholder',
		array('url' => admin_url('admin-ajax.php'),'nonce' => wp_create_nonce('swp180214_action_submit_install_nonce')));
	?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"></div>
		<h2><?php echo SWP180214_PAGE_NAME_SETTINGS ; ?></h2>
		<span class="description"><?php echo SWP180214_PAGE_NAME_SETTINGS_DESCRIPTION?></span>
		<div id="swp180214_cssmenu">
			<ul id="swp180214_menulist">
   				<li id="swp180214_generale" class="active" onclick="swp180214_show_content(this);"><a><span>Generale</span></a></li>
   				<li id="swp180214_visualizzazione" onclick="swp180214_show_content(this);"><a><span>Pagina di visualizzazione</span></a></li>
   				<li id="swp180214_database" onclick="swp180214_show_content(this);"><a><span>Gestione Database</span></a></li>
   				<li id="swp180214_doc" onclick="swp180214_show_content(this);"><a><span>Documentazione</span></a></li>
   				<li id="swp180214_contatti" class="last" onclick="swp180214_show_content(this);"><a><span>Contatti</span></a></li>
			</ul>
		</div>
		<div id="swp180214_content">
		
			<div id="swp180214_content_list">
				
				<div id="swp180214_generale_content">
				<span class="description">
				<h3><?php echo SWP180214_DISPLAY_NAME;?> > <a href="admin.php?page=<?php echo SWP180214_SLUG_SETTINGS ;?>">Impostazioni</a> > <a>Generale</a></h3>
				<?php 
					if(get_option(SWP180214_OPT_UPDATE_AVAILABLE)){
						?>
						<strong>E' disponibile un aggiornamento del plugin !</strong>
						<input type="button" class="button-primary" value="Aggiorna ora !" onclick=" swp180214_request_install_update('<?php echo wp_create_nonce('swp180214_action_install_update_nonce');?>');"/>
						<div id="swp180214_loader_install_update" style="display: none;">
               				<img style="float:left;padding-right:10px;" src="<?php echo plugins_url('res/images/circular_loader.gif',__FILE__);?>"/>
               				<div><h3>Aggiornamento in corso...</h3></div>
               			</div> 
						<?php 
					}else{
						?>
						Il plugin &egrave; installato correttamente !<br><br>
						<?php 
					}
				?>
				</span>
				<h3>Info</h3>
				<span class="description">
				<ul>
					<li>Versione plugin : <?php echo SWP180214_VERSION ;?>
					<li>Versione Getrix schema : <?php echo get_option(SWP180214_OPT_GETRIX_SCHEMA_VERSION);?>
					<li>Modalita di aggiornamento dati : 
					<?php
						$mode = get_option(SWP180214_OPT_GETRIX_FEED_UPDATE_MODE) == SWP180214_AUTOMATIC ? 'Automatica (CronJob)' : 'Manuale' ;
						echo $mode ; 
					?>
				</ul>
				</span>
				<h3>Opzioni</h3>
				<ul>
					<li><a class="button-primary" onclick="swp180214_show_install_parameters();">Modifica i parametri di installazione</a>
					<div id="swp180214_install_parameters" class="swp180214_content_parameters">
					<strong>ATTENZIONE ! La modifica dei parametri di installazione puo compromettere l'uso del plugin.</strong>
						<style type="text/css">
							#swp180214_install_form label.error { color:#a10000; margin-left:5px;}
						</style>
					
					<form id="<?php echo SWP180214_PREFIX.'install_form'?>" method="post" action="options.php" onsubmit="swp180214_onsubmit(2,'<?php echo wp_create_nonce('swp180214_action_submit_install_nonce');?>');">
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
						<input id="<?php echo SWP180214_OPT_GETRIX_FEED_UPDATE_MODE ;?>_auto" type="radio" name="<?php echo SWP180214_OPT_GETRIX_FEED_UPDATE_MODE ;?>" value="<?php echo SWP180214_AUTOMATIC;?>" <?php checked(get_option(SWP180214_OPT_GETRIX_FEED_UPDATE_MODE),SWP180214_AUTOMATIC);?>/>
						<label for="<?php echo SWP180214_OPT_GETRIX_FEED_UPDATE_MODE ;?>_auto">Automatica (CronJob)</label>						
						<br>
						<input id="<?php echo SWP180214_OPT_GETRIX_FEED_UPDATE_MODE ;?>_manual" type="radio" name="<?php echo SWP180214_OPT_GETRIX_FEED_UPDATE_MODE ;?>" value="<?php echo SWP180214_MANUAL;?>" <?php checked(get_option(SWP180214_OPT_GETRIX_FEED_UPDATE_MODE),SWP180214_MANUAL);?>/>
						<label for="<?php echo SWP180214_OPT_GETRIX_FEED_UPDATE_MODE ;?>_manual">Manuale</label>						
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
                            	<input type="submit" class="button-primary" id="submit" name="submit" value="<?php _e('Salva') ?> " />
                               	<input type="button" class="button-primary" onclick="swp180214_restore_default_feed_values()" value="<?php _e('Ripristina valori di default') ?> " />
                               	<input type="button" class="button-primary" onclick="swp180214_show_advanced();" style="float:right;" value="<?php _e('Avanzate') ?> " />
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
		
					</div>
					<li><a class="button-primary" onclick="swp180214_show_uninstall();">Disintalla il plugin</a>
					<div id="swp180214_uninstall_box" class="swp180214_content_parameters">
					<span class="description">
					Per disinstallare il plugin recarsi nella pagina <a href="plugins.php">Plugins</a>, disattivare questo plugin e poi cliccare su <em style="color:#ff4629;">Cancella</em>
					</span>
					</div>
				</ul>
				</div>
				
				<div id="swp180214_visualizzazione_content">
				<span class="description">
				<h3><?php echo SWP180214_DISPLAY_NAME;?> > <a href="admin.php?page=<?php echo SWP180214_SLUG_SETTINGS ;?>">Impostazioni</a> > <a>Pagina di visualizzazione<a></a></h3>
				</span>
				<?php swp180214_get_display_page_settings();?>
				</div>
				
				<div id="swp180214_database_content">
				<span class="description">
				<h3><?php echo SWP180214_DISPLAY_NAME;?> > <a href="admin.php?page=<?php echo SWP180214_SLUG_SETTINGS ;?>">Impostazioni</a> > <a>Gestione Database<a></a></h3>
				</span>
				<h3>Info</h3>
				<span class="description">
				<ul>
					<?php
					global $wpdb ;
					$tables = @$wpdb->get_results("SELECT tables FROM ".swp180214_table_prefix()."getrix_tree",ARRAY_A);
					if($tables){
						$sql = "SELECT COUNT(*) FROM %s";
						foreach ($tables as $table){
							$sql_sane = sprintf($sql,$table['tables']);
							$count = $wpdb->get_var($sql_sane);
							?>
							<li>Numero records trovati in tabella <?php echo $table['tables']." : ".$count ; ?> 
							<?php 
						}
					}else{
						?>
						<li><strong><img src="<?php echo plugins_url('res/images/warning.png',__FILE__);?>" />Attenzione ! Integratita database compromessa. Verificare l'esistenza delle tabelle.</strong> 
						<?php 
					}
					?>
					<li>Versione database : <?php echo SWP180214_DB_VERSION; ?>
					<?php
					if(get_option(SWP180214_OPT_GETRIX_FEED_UPDATE_MODE) == SWP180214_AUTOMATIC){
					?>
					<li>Prossimo aggiornamento : <?php echo date("d/m/Y H:i:s",wp_next_scheduled(SWP180214_UPDATE_DATA_HOOK));?><?php 
					}
					?>
				</ul>
				</span>
				<?php
					if(get_option(SWP180214_OPT_GETRIX_FEED_UPDATE_MODE) == SWP180214_MANUAL){
						?>
						<strong>L'aggiornamento dati &egrave; in modalita manuale.</strong>
						<a class="button-primary" onclick="swp180214_request_update_db('<?php echo wp_create_nonce('swp180214_action_update_db_nonce');?>');">Aggiornare i dati ora</a><br>
						
						<div id="swp180214_loader_db" style="display: none;">
               				<img style="float:left;padding-right:10px;" src="<?php echo plugins_url('res/images/circular_loader.gif',__FILE__);?>"/>
               				<div><h3>Aggiornamento dati in corso...</h3></div>
               			</div>
						<?php 
					}else{
						?>
						<span class="description">
						I dati vengono aggiornati automaticamente ogni giorno.<br>
						Per aggiornare i dati ora, modificare le impostazioni di aggiornamento<br>
						dal menu <a href="#" onclick="swp180214_hide_advanced();">Generale</a>
						</span>
						<?php 
					} 
				?>
				</div>
				
				<div id="swp180214_doc_content">
				<span class="description">
				<h3><?php echo SWP180214_DISPLAY_NAME;?> > <a href="admin.php?page=<?php echo SWP180214_SLUG_SETTINGS ;?>">Impostazioni</a> > <a>Documentazione<a></a></h3>
				La documentazione di questo plugin &egrave; disponibile in formato PDF <a href="<?php echo plugins_url('doc/doc.pdf',__FILE__);?>">QUI</a>
				</span>
				</div>
				
				<div id="swp180214_contatti_content">
				<span class="description">
				<h3><?php echo SWP180214_DISPLAY_NAME;?> > <a href="admin.php?page=<?php echo SWP180214_SLUG_SETTINGS ;?>">Impostazioni</a> > <a>Contatti</a></h3>
				Questo plugin &egrave; stato realizzato da <A HREF="mailto:ivan.maruca@gmail.com?subject=WP180214">Ivan Maruca</A> Copyrights(c) 2014<br>
				Per ogni informazione rivolgersi a :
				<ul>
					<li><A HREF="mailto:ivan.maruca@gmail.com?subject=WP180214">Ivan Maruca</A> (Skullab.com)
					<li><A HREF="mailto:daniele@gcore.it?subject=WP180214">Daniele Territo</A> (Gcore.it)
					<li><A HREF="mailto:stefano@gcore.it?subject=WP180214">Stefano Zarba</A> (Gcore.it)
				</ul>
				</span>
				</div>
				
				<div id="swp180214_avanzate_content">
				<span class="description">
				<h3><?php echo SWP180214_DISPLAY_NAME;?> > <a href="admin.php?page=<?php echo SWP180214_SLUG_SETTINGS ;?>">Impostazioni</a> > <a href="#" onclick="swp180214_hide_advanced();">Generale</a> > <a>Avanzate</a></h3>
				</span>
				<strong>ATTENZIONE ! La modifica dei parametri di installazione puo compromettere l'uso del plugin.</strong>
				<style type="text/css">
					#swp180214_install_form label.error { color:#a10000; margin-left:5px;}
				</style>
	
				<form id="<?php echo SWP180214_PREFIX.'install_form'?>" method="post" action="options.php" onsubmit="swp180214_onsubmit(1,'<?php echo wp_create_nonce('swp180214_action_submit_install_nonce');?>');">
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
                    	<input type="submit" class="button-primary" id="submit" name="submit" value="<?php _e('Salva') ?> " />
                        <input type="button" class="button-primary" onclick="swp180214_restore_default_install_values()" value="<?php _e('Ripristina valori di default') ?> " />
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
	
				<div>
				
			</div>
		</div>
	</div><?php 
}

function swp180214_confirm_install_update(){
	if(wp_verify_nonce( $_REQUEST['_nonce'], 'swp180214_action_install_update_nonce' )){
		swp180214_install_db();
		update_option(SWP180214_OPT_DB_VERSION,SWP180214_DB_VERSION);
		update_option(SWP180214_OPT_UPDATE_AVAILABLE,false);
		echo 'Aggiornamento eseguito !';
	}else die('RICHIESTA NON PERMESSA');
	die();
}
function swp180214_page_update_db(){
	if(wp_verify_nonce( $_REQUEST['_dbnonce'], 'swp180214_action_update_db_nonce' )){
		swp180214_populate_database();
		echo 'Aggiornamento eseguito !';
	}else die('RICHIESTA NON PERMESSA');
	die();
}

function swp180214_page_install_confirm(){
	if(wp_verify_nonce( $_REQUEST['_nonce'], 'swp180214_action_submit_install_nonce' )){
		if(get_option(SWP180214_OPT_FIRST_INSTALL)){
			update_option(SWP180214_OPT_INSTALL_PROCESS,1);
		}
	}else die('RICHIESTA NON PERMESSA');
	die();
}

function swp180214_page_feed_confirm(){
	if(wp_verify_nonce( $_REQUEST['_nonce'], 'swp180214_action_submit_install_nonce' )){		
		update_option(SWP180214_OPT_INSTALL_PROCESS,3);
	}else die('RICHIESTA NON PERMESSA');
	die();
}

// PROCEDURA DI INSTALLAZIONE
function swp180214_page_install(){

	wp_enqueue_script(SWP180214_JS_VALIDATOR);
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
	
	switch(get_option(SWP180214_OPT_INSTALL_PROCESS)){
		case 0:
			?>
			<style type="text/css">
				#swp180214_install_form label.error { color:#a10000; margin-left:5px;}
			</style>
			<div class="wrap">
			<div id="icon-options-general" class="icon32"></div>
				<h2><?php echo SWP180214_PAGE_NAME_INSTALL ; ?> - Fase 1</h2>
				<span class="description"><?php echo SWP180214_PAGE_NAME_INSTALL_DESCRIPTION?></span>
				<form id="<?php echo SWP180214_PREFIX.'install_form'?>" method="post" action="options.php" onsubmit="swp180214_onsubmit()">
				<?php 
					settings_fields(SWP180214_OPT_GROUP_INSTALL);
					do_settings_sections(SWP180214_OPT_GROUP_INSTALL);
				?>
					<table class="form-table">
						<tbody>
							<tr valign="top"><th scope="row">Getrix Schema URI</th>
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
					
							<tr valign="top"><th scope="row"></th>
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
			</div>
			<?php 
			break;
		case 1:
			?>
			<div class="wrap">
				<div id="icon-options-general" class="icon32"></div>
				<h2><?php echo SWP180214_PAGE_NAME_INSTALL ; ?> - Fase 2</h2>
				<span class="description"><?php echo SWP180214_PAGE_NAME_INSTALL_DESCRIPTION?></span>
				
	            <div class="description" id="swp180214_install_info">
	            	<ul type="circle">
	               		<?php swp180214_install_db(); ?>
	               	</ul>
	         	</div>
	           
	           	<a href="admin.php?page=<?php echo SWP180214_SLUG_SETTINGS ;?>" class="button-primary">Prosegui</a>
			</div>
			<?php 
			break;
		case 2:
			?>
			<style type="text/css">
				#swp180214_install_form label.error { color:#a10000; margin-left:5px;}
			</style>
			<div class="wrap">
				<div id="icon-options-general" class="icon32"></div>
				<h2><?php echo SWP180214_PAGE_NAME_INSTALL ; ?> - Fase 3</h2>
				<span class="description"><?php echo SWP180214_PAGE_NAME_INSTALL_DESCRIPTION?></span>
					<form id="<?php echo SWP180214_PREFIX.'install_form'?>" method="post" action="options.php" onsubmit="swp180214_onsubmit()">
					<?php 
					settings_fields(SWP180214_OPT_GROUP_FEED);
					do_settings_sections(SWP180214_OPT_GROUP_FEED);
					?>
					<table class="form-table">
						<tbody>
							<tr valign="top"><th scope="row">Getrix Feed URI</th>
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
								
									<input id="<?php echo SWP180214_OPT_GETRIX_FEED_UPDATE_MODE ;?>_auto" type="radio" name="<?php echo SWP180214_OPT_GETRIX_FEED_UPDATE_MODE ;?>" value="<?php echo SWP180214_AUTOMATIC;?>" <?php checked(get_option(SWP180214_OPT_GETRIX_FEED_UPDATE_MODE),SWP180214_AUTOMATIC);?>/>
									<label for="<?php echo SWP180214_OPT_GETRIX_FEED_UPDATE_MODE ;?>_auto">Automatica (CronJob)</label>						
									<br>
									<input id="<?php echo SWP180214_OPT_GETRIX_FEED_UPDATE_MODE ;?>_manual" type="radio" name="<?php echo SWP180214_OPT_GETRIX_FEED_UPDATE_MODE ;?>" value="<?php echo SWP180214_MANUAL;?>" <?php checked(get_option(SWP180214_OPT_GETRIX_FEED_UPDATE_MODE),SWP180214_MANUAL);?>/>
									<label for="<?php echo SWP180214_OPT_GETRIX_FEED_UPDATE_MODE ;?>_manual">Manuale</label>	
									
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
			</div>
			<?php 
			break;
	}
}
?>