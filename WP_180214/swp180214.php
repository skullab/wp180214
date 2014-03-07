<?php
/*
Plugin Name: WP180214
Plugin URI: http://www.skullab.com
Description: Questo plugin &egrave; stato sviluppato ad uso esclusivo del richiedente e non &egrave; disponibile in nessun repository pubblico.Per ulteriori dettagli contattare il fornitore dei servizi e/o il programmatore del plugin. PROCEDURA DI INSTALAZZIONE : (1) Cliccare su "Attiva" a sinistra di questa descrizione (2) Accedere alla pagina delle Impostazioni per apportare modifiche al plugin.Per ulteriori dettagli si consiglia la lettura della <strong>documentazione allegata</strong>.
Version: 1.0.0
Author: <A HREF="mailto:ivan.maruca@gmail.com?subject=WP180214">Ivan Maruca</A>
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
require_once 'utils.php';
require_once 'installation.php';
require_once 'display_page_setting.php';
require_once 'menu_pages.php';
require_once 'updatedata.php';
require_once 'shortcodes.php';
/*****************************************************************************************************/
register_activation_hook( __FILE__, 'swp180214_activation');
function swp180214_activation(){
	
	//UPDATE DB
	add_option(SWP180214_OPT_PLUGIN_VERSION,SWP180214_VERSION);
	add_option(SWP180214_OPT_DB_VERSION,SWP180214_DB_VERSION);
	
	add_option(SWP180214_OPT_FIRST_INSTALL,true);
	add_option(SWP180214_OPT_INSTALL_PROCESS,0);
	
	add_option(SWP180214_OPT_GETRIX_SCHEMA_URI,SWP180214_DEFAULT_GETRIX_SCHEMA_URI);
	add_option(SWP180214_OPT_GETRIX_SCHEMA_VERSION,SWP180214_DEFAULT_GETRIX_SCHEMA_VERSION);
	add_option(SWP180214_OPT_GETRIX_USER,SWP180214_DEFAULT_GETRIX_USER);
	
	add_option(SWP180214_OPT_GETRIX_FEED_URI,SWP180214_DEFAULT_GETRIX_FEED_URI);
	add_option(SWP180214_OPT_GETRIX_FEED_UPDATE_MODE,SWP180214_DEFAULT_GETRIX_FEED_UPDATE_MODE);
	
	add_option(SWP180214_OPT_PAGE_CREATED,false);
	add_option(SWP180214_OPT_PAGE_UPDATED,false);
	add_option(SWP180214_OPT_GETRIX_PAGE_ID,0);
	add_option(SWP180214_OPT_GETRIX_PAGE_NAME,'');
	add_option(SWP180214_OPT_GETRIX_PAGE_TITLE,'');
	add_option(SWP180214_OPT_GETRIX_PAGE_CONTENT,'');
	add_option(SWP180214_OPT_GETRIX_PAGE_PARENT_ID,0);
	add_option(SWP180214_OPT_GETRIX_PAGE_MENU_ORDER,0);
	add_option(SWP180214_OPT_GETRIX_PAGE_USER_ID,false);
	add_option(SWP180214_OPT_GETRIX_PAGE_STATUS,'publish');
	
	add_option(SWP180214_OPT_UPLOAD_DIR,false);
	add_option(SWP180214_OPT_GLOBAL_ERROR,false);
	
	add_option(SWP180214_OPT_UPDATE_AVAILABLE,false);	
}
/*****************************************************************************************************/
register_deactivation_hook( __FILE__, 'swp180214_deactivation' );
function swp180214_deactivation(){
	wp_clear_scheduled_hook( SWP180214_UPDATE_DATA_HOOK );
	remove_shortcode(SWP180214_SHORTCODE);
}
/*****************************************************************************************************/
function swp180214_register_options_install(){
	register_setting(SWP180214_OPT_GROUP_INSTALL,SWP180214_OPT_GETRIX_SCHEMA_URI);
	register_setting(SWP180214_OPT_GROUP_INSTALL,SWP180214_OPT_GETRIX_SCHEMA_VERSION);
	register_setting(SWP180214_OPT_GROUP_INSTALL,SWP180214_OPT_GETRIX_USER);
}
/*****************************************************************************************************/
function swp180214_register_options_feed(){
	register_setting(SWP180214_OPT_GROUP_FEED,SWP180214_OPT_GETRIX_FEED_URI);
	register_setting(SWP180214_OPT_GROUP_FEED,SWP180214_OPT_GETRIX_FEED_UPDATE_MODE);
}
/*****************************************************************************************************/
function swp180214_register_options_page(){
	register_setting(SWP180214_OPT_GROUP_PAGE,SWP180214_OPT_GETRIX_PAGE_ID);
	register_setting(SWP180214_OPT_GROUP_PAGE,SWP180214_OPT_GETRIX_PAGE_NAME);
	register_setting(SWP180214_OPT_GROUP_PAGE,SWP180214_OPT_GETRIX_PAGE_TITLE);
	register_setting(SWP180214_OPT_GROUP_PAGE,SWP180214_OPT_GETRIX_PAGE_CONTENT);
	register_setting(SWP180214_OPT_GROUP_PAGE,SWP180214_OPT_GETRIX_PAGE_PARENT_ID);
	register_setting(SWP180214_OPT_GROUP_PAGE,SWP180214_OPT_GETRIX_PAGE_MENU_ORDER);
	register_setting(SWP180214_OPT_GROUP_PAGE,SWP180214_OPT_GETRIX_PAGE_USER_ID);
	register_setting(SWP180214_OPT_GROUP_PAGE,SWP180214_OPT_GETRIX_PAGE_STATUS);
}
/*****************************************************************************************************/
function swp180214_register_script(){
	wp_register_script(SWP180214_JS_JQUERY_VALIDATOR,plugins_url('js/jquery.validate.js',__FILE__),array('jquery'));
	wp_register_script(SWP180214_JS_VALIDATOR,plugins_url('js/validator.install.js',__FILE__),array(SWP180214_JS_JQUERY_VALIDATOR));
	wp_register_script(SWP180214_JS_SETTINGS_PAGE,plugins_url('js/settings_page.js',__FILE__),array('jquery'));
}
function swp180214_register_script_shortcode(){
	wp_register_script(SWP180214_JS_SHORTCODE,plugins_url('js/shortcode.js',__FILE__),array('jquery'));
}
function swp180214_register_style_shortcode(){
	wp_register_style(SWP180214_CSS_SHORTCODE,plugins_url('css/page_layout.css',__FILE__));
}
/*****************************************************************************************************/
if(is_admin()){
	add_action('admin_init','swp180214_install');
	add_action('admin_init','swp180214_register_options_install');
	add_action('admin_init','swp180214_register_options_feed');
	add_action('admin_init','swp180214_register_options_page');
	add_action('admin_init','swp180214_register_script');
	add_action('admin_init','swp180214_upload_dir');
	
	add_action('admin_menu','swp180214_add_menu_pages');
	add_action( 'plugins_loaded', 'swp180214_update' );
	
	add_action('wp_ajax_swp180214_action_submit_install', 'swp180214_page_install_confirm');
	add_action('wp_ajax_swp180214_action_submit_feed', 'swp180214_page_feed_confirm');
	add_action('wp_ajax_swp180214_action_update_db', 'swp180214_page_update_db');
	add_action('wp_ajax_swp180214_action_create_page', 'swp180214_confirm_create_page');
	add_action('wp_ajax_swp180214_action_delete_page', 'swp180214_confirm_delete_page');
	add_action('wp_ajax_swp180214_action_update_page', 'swp180214_confirm_update_page');
	add_action('wp_ajax_swp180214_action_install_update', 'swp180214_confirm_install_update');
}
/*****************************************************************************************************/
add_action( SWP180214_UPDATE_DATA_HOOK, 'swp180214_populate_database' );
/*****************************************************************************************************/
// 											SHORTCODE
add_action('init','swp180214_register_script_shortcode');
add_action('init','swp180214_register_style_shortcode');
add_action('wp_ajax_swp180214_action_shortcode_search', 'swp180214_shortcode_search');
add_action('wp_ajax_swp180214_action_shortcode_details', 'swp180214_shortcode_details');
/*****************************************************************************************************/
switch (get_option(SWP180214_OPT_GETRIX_FEED_UPDATE_MODE)){
	case SWP180214_AUTOMATIC:
		if (!wp_next_scheduled(SWP180214_UPDATE_DATA_HOOK)) {
			$ret = wp_schedule_event( time(), 'daily', SWP180214_UPDATE_DATA_HOOK);
			if($ret != null){
				swp180214_debug('ERRORE : EVENTO NON SCHEDULATO');
			}
		}
		break;
	case SWP180214_MANUAL:
		wp_clear_scheduled_hook( SWP180214_UPDATE_DATA_HOOK );
		break;
}
/*****************************************************************************************************
										ADD ADMIN MENU PAGE
******************************************************************************************************/
function swp180214_add_menu_pages(){
	add_menu_page('WP180214','WP180214','manage_options',SWP180214_SLUG_SETTINGS,'swp180214_page_settings');
}
?>