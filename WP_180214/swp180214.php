<?php
/*
Plugin Name: WP180214
Plugin URI: http://www.skullab.com
Description: Questo plugin &egrave; stato sviluppato ad uso esclusivo del richiedente e non &egrave; disponibile in nessun repository pubblico.Per ulteriori dettagli contattare il fornitore dei servizi e/o il programmatore del plugin. PROCEDURA DI INSTALAZZIONE : (1) Cliccare su "Attiva" a sinistra di questa descrizione (2) Accedere alla pagina delle Impostazioni per apportare modifiche al plugin.Per ulteriori dettagli si consiglia la lettura della <strong>documentazione allegata</strong>.
Version: 0.0.1
Author: <A HREF="mailto:ivan.maruca@gmail.com?subject=WPplugin_vetrinaImmobiliare">Ivan Maruca</A>
Author URI: http://www.skullab.com
License: Apache License, Version 2.0
*/

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

// Safe execution...
if ( !function_exists( 'add_action' ) ) {
	echo '<b>ERRORE : </b> Questo è un plugin per WordPress e non può essere eseguito come script PHP';
	exit;
}

/*****************************************************************************************************
									GLOBAL REQUIRE SECTION																   	
******************************************************************************************************/
require_once 'constants.php' ;
require_once 'debug.php';
require_once 'installation.php';
require_once 'menu_pages.php';
/*****************************************************************************************************/
register_activation_hook( __FILE__, 'swp180214_activation');
function swp180214_activation(){
	update_option(SWP180214_OPT_FIRST_INSTALL,true);
	
	// UPDATE DB VERSION
	if(!get_option(SWP180214_OPT_DB_VERSION)){
		add_option(SWP180214_OPT_DB_VERSION,SWP180214_DB_VERSION);
	}else{
		update_option(SWP180214_OPT_DB_VERSION,SWP180214_DB_VERSION);
	}
	
	add_option(SWP180214_OPT_FIRST_INSTALL,true);
	add_option(SWP180214_OPT_GETRIX_SCHEMA_URI,SWP180214_DEFAULT_GETRIX_SCHEMA_URI);
	add_option(SWP180214_OPT_GETRIX_SCHEMA_VERSION,SWP180214_DEFAULT_GETRIX_SCHEMA_VERSION);
}
/*****************************************************************************************************/
function swp180214_register_options_install(){
	register_setting(SWP180214_OPT_GROUP_INSTALL,SWP180214_OPT_GETRIX_SCHEMA_URI);
	register_setting(SWP180214_OPT_GROUP_INSTALL,SWP180214_OPT_GETRIX_SCHEMA_VERSION);
	register_setting(SWP180214_OPT_GROUP_INSTALL,SWP180214_OPT_GETRIX_USER);
}
/*****************************************************************************************************/
function swp180214_register_script(){
	wp_register_script(SWP180214_JS_JQUERY_VALIDATOR,plugins_url('js/jquery.validate.js',__FILE__),array('jquery'));
	wp_register_script(SWP180214_JS_VALIDATOR,plugins_url('js/validator.install.js',__FILE__),array(SWP180214_JS_JQUERY_VALIDATOR));
}
/*****************************************************************************************************/
if(is_admin()){
	add_action('admin_init','swp180214_install');
	add_action('admin_init','swp180214_register_options_install');
	add_action('admin_init','swp180214_register_script');
	add_action('admin_menu','swp180214_add_menu_pages');
	add_action( 'plugins_loaded', 'swp180214_update' );
	
	add_action('wp_ajax_swp180214_action_submit_install', 'swp180214_page_install_confirm');
}
/*****************************************************************************************************
										ADD ADMIN MENU PAGE
******************************************************************************************************/
function swp180214_add_menu_pages(){
	require_once 'menu_pages.php';
	add_menu_page('WP180214','WP180214','manage_options',SWP180214_SLUG_SETTINGS,'swp180214_page_settings');
}
?>