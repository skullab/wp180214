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
	if(get_option(SWP180214_OPT_FIRST_INSTALL)){
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

// PROCEDURA DI INSTALLAZIONE
function swp180214_page_install(){
	?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"></div>
		<h2><?php echo SWP180214_PAGE_NAME_INSTALL ; ?></h2>
		<span class="description"><?php echo SWP180214_PAGE_NAME_INSTALL_DESCRIPTION?></span>
		<form method="post" action="options.php" onsubmit="return false;">
		<?php 
			settings_fields(SWP180214_OPT_GROUP_INSTALL);
			do_settings_sections(SWP180214_OPT_GROUP_INSTALL);
		?>
		<table class="form-table">
			<tbody>
			<tr valign="top">
				<th scope="row">Getrix schema uri</th>
				<td>
					<input type="text" size="100" name="<?php echo SWP180214_OPT_GETRIX_SCHEMA_URI ;?>" value="<?php echo get_option(SWP180214_OPT_GETRIX_SCHEMA_URI);?>" /><br>
					<span class="description">
					Indirizzo URI relativo allo schema XSD da utilizzare.<br>Se non si &egrave; sicuri lasciare le impostazioni di default.
					</span>
				</td>
			</tr>
			
			<tr valign="top">
				<th scope="row">Getrix versione schema</th>
				<td>
					<input type="text" size="10" name="<?php echo SWP180214_OPT_GETRIX_SCHEMA_VERSION ;?>" value="<?php echo get_option(SWP180214_OPT_GETRIX_SCHEMA_VERSION);?>" /><br>
					<span class="description">
					Versione dello schema da utilizzare.<br>Se non si &egrave; sicuri lasciare le impostazioni di default.
					</span>
				</td>
			</tr>
			
			<tr valign="top">
				<th scope="row">Getrix codice utente</th>
				<td>
					<input type="text" size="100" name="<?php echo SWP180214_OPT_GETRIX_USER ;?>" value="<?php echo get_option(SWP180214_OPT_GETRIX_USER);?>" /><br>
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
                               </p>
                           </td>
                   </tr>
			</tbody>
		</table>
		<?php //submit_button('Installa');?> 
		</form>
	</div><?php 
}
?>