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

// Safe execution...
if ( !function_exists( 'add_action' ) ) {
	echo '<b>ERRORE : </b> Questo è un plugin per WordPress e non può essere eseguito come script PHP';
	exit;
}

/*******************************************************************************************************************/
/** The internal plugin prefix */
define('SWP180214_PREFIX','swp180214_');
/** The plugin version */
define('SWP180214_VERSION','1.0.0');
/** The display plugin name */
define('SWP180214_DISPLAY_NAME','WP180214');
/** The slug plugin name */
define('SWP180214_SLUG_NAME','wp180214');
/** THE CURRENT DB VERSION */
define('SWP180214_DB_VERSION','1.0');
define('SWP180214_AUTOMATIC','swp180214_automatic');
define('SWP180214_MANUAL','swp180214_manual');
define('SWP180214_UPDATE_DATA_HOOK','swp180214_update_data_hook');
define('SWP180214_SHORTCODE','WP180214');
/*******************************************************************************************************************/
define('SWP180214_ERROR_MALFORMED_FEED_URI',100);
define('SWP180214_ERROR_DOWNLOAD_FEED_URI',200);
define('SWP180214_ERROR_UNZIP_FEED',300);
define('SWP180214_ERROR_RENAME_FEED',400);
define('SWP180214_ERROR_MKDIR',500);
define('SWP180214_ERROR_FEED_VERSION',600);
define('SWP180214_ERROR_FEED_USER',700);
/*******************************************************************************************************************/
define('SWP180214_DEFAULT_GETRIX_SCHEMA_URI','http://feed.getrix.it/xml/feed_2_0_0.xsd');
define('SWP180214_DEFAULT_GETRIX_SCHEMA_VERSION','2.0.0');
define('SWP180214_DEFAULT_GETRIX_USER','9F431778-4CAC-4534-B5F9-F458A87E2279');
define('SWP180214_DEFAULT_GETRIX_FEED_URI','http://feed.getrix.it/xml/'.SWP180214_DEFAULT_GETRIX_USER.'.zip');
define('SWP180214_DEFAULT_GETRIX_FEED_UPDATE_MODE',SWP180214_AUTOMATIC);
/*******************************************************************************************************************/
define('SWP180214_OPT_PLUGIN_VERSION','swp180214_opt_plugin_version');
define('SWP180214_OPT_DB_VERSION','swp180214_opt_db_version');
define('SWP180214_OPT_FIRST_INSTALL','swp180214_opt_first_install');
define('SWP180214_OPT_INSTALL_PROCESS','swp180214_opt_install_process');
define('SWP180214_OPT_PAGE_CREATED','swp180214_opt_page_created');
define('SWP180214_OPT_PAGE_UPDATED','swp180214_opt_page_updated');
/*******************************************************************************************************************/
define('SWP180214_OPT_GROUP_INSTALL','swp180214_opt_group_install');
define('SWP180214_OPT_GETRIX_SCHEMA_URI','swp180214_opt_getrix_schema_uri');
define('SWP180214_OPT_GETRIX_SCHEMA_VERSION','swp180214_opt_getrix_schema_version');
define('SWP180214_OPT_GETRIX_USER','swp180214_opt_getrix_user');
/*******************************************************************************************************************/
define('SWP180214_OPT_GROUP_FEED','swp180214_opt_group_feed');
define('SWP180214_OPT_GETRIX_FEED_URI','swp180214_opt_getrix_feed_uri');
define('SWP180214_OPT_GETRIX_FEED_UPDATE_MODE','swp180214_opt_getrix_feed_update_mode');
/*******************************************************************************************************************/
define('SWP180214_OPT_GROUP_PAGE','swp180214_opt_group_page');
define('SWP180214_OPT_GETRIX_PAGE_ID','swp180214_opt_getrix_page_id');
define('SWP180214_OPT_GETRIX_PAGE_NAME','swp180214_opt_getrix_page_name');
define('SWP180214_OPT_GETRIX_PAGE_CONTENT','swp180214_opt_getrix_page_content');
define('SWP180214_OPT_GETRIX_PAGE_TITLE','swp180214_opt_getrix_page_title');
define('SWP180214_OPT_GETRIX_PAGE_USER_ID','swp180214_opt_getrix_page_user_id');
define('SWP180214_OPT_GETRIX_PAGE_MENU_ORDER','swp180214_opt_getrix_page_menu_order');
define('SWP180214_OPT_GETRIX_PAGE_PARENT_ID','swp180214_opt_getrix_page_parent_id');
define('SWP180214_OPT_GETRIX_PAGE_STATUS','swp180214_opt_getrix_page_status');
/*******************************************************************************************************************/
define('SWP180214_OPT_UPLOAD_DIR','swp180214_opt_upload_dir');
/*******************************************************************************************************************/
define('SWP180214_OPT_GLOBAL_ERROR','swp180214_opt_global_error');
/*******************************************************************************************************************/
define('SWP180214_OPT_UPDATE_AVAILABLE','swp180214_opt_update_available');
/*******************************************************************************************************************/
define('SWP180214_SLUG_SETTINGS',SWP180214_SLUG_NAME.'-settings');
/*******************************************************************************************************************/
define('SWP180214_PAGE_NAME_SETTINGS',SWP180214_DISPLAY_NAME.' - Impostazioni');
define('SWP180214_PAGE_NAME_SETTINGS_DESCRIPTION','Impostazioni generali per il corretto funzionamento del plugin.');

define('SWP180214_PAGE_NAME_INSTALL',SWP180214_DISPLAY_NAME.' - Installazione');
define('SWP180214_PAGE_NAME_INSTALL_DESCRIPTION','Procedura guidata per la prima installazione del plugin.');
/*******************************************************************************************************************/
define('SWP180214_JS_JQUERY_VALIDATOR','swp180214_js_jquery_validator');
define('SWP180214_JS_VALIDATOR','swp180214_js_validator');
define('SWP180214_JS_SETTINGS_PAGE','swp180214_js_settings_page');
/*******************************************************************************************************************/
define('SWP180214_CSS_SETTINGS_MENU','swp180214_css_settings_menu');
?>