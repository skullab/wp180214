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
//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )exit();

/*****************************************************************************************************
 										DELETE OPTIONS
******************************************************************************************************/
delete_option(SWP180214_OPT_DB_VERSION);
delete_option(SWP180214_OPT_PLUGIN_VERSION);

delete_option(SWP180214_OPT_FIRST_INSTALL);
delete_option(SWP180214_OPT_GETRIX_FEED_UPDATE_MODE);
delete_option(SWP180214_OPT_GETRIX_FEED_URI);
delete_option(SWP180214_OPT_GETRIX_PAGE_CONTENT);
delete_option(SWP180214_OPT_GETRIX_PAGE_ID);
delete_option(SWP180214_OPT_GETRIX_PAGE_MENU_ORDER);
delete_option(SWP180214_OPT_GETRIX_PAGE_NAME);
delete_option(SWP180214_OPT_GETRIX_PAGE_PARENT_ID);
delete_option(SWP180214_OPT_GETRIX_PAGE_STATUS);
delete_option(SWP180214_OPT_GETRIX_PAGE_TITLE);
delete_option(SWP180214_OPT_GETRIX_PAGE_USER_ID);
delete_option(SWP180214_OPT_GETRIX_SCHEMA_URI);
delete_option(SWP180214_OPT_GETRIX_SCHEMA_VERSION);
delete_option(SWP180214_OPT_GETRIX_USER);
delete_option(SWP180214_OPT_GLOBAL_ERROR);
delete_option(SWP180214_OPT_GROUP_FEED);
delete_option(SWP180214_OPT_GROUP_INSTALL);
delete_option(SWP180214_OPT_GROUP_PAGE);
delete_option(SWP180214_OPT_INSTALL_PROCESS);
delete_option(SWP180214_OPT_PAGE_CREATED);
delete_option(SWP180214_OPT_PAGE_UPDATED);
delete_option(SWP180214_OPT_UPDATE_AVAILABLE);
delete_option(SWP180214_OPT_UPLOAD_DIR);
/*****************************************************************************************************
									   DELETE DB TABLES
******************************************************************************************************/
global $wpdb ;
$sql = "DROP TABLE IF EXISTS %s";
$tables = $wpdb->get_results("SELECT tables FROM wp_swp180214_getrix_tree",ARRAY_A);
foreach ($tables as $table){
	if($table['tables'] != "wp_swp180214_immobile"){
		$sql_sane = sprintf($sql,$table['tables']);
		$wpdb->query($sql_sane);
	}
}
$wpdb->query("DROP TABLE IF EXISTS wp_swp180214_immobile");
$wpdb->query("DROP TABLE IF EXISTS wp_swp180214_categorie");
$wpdb->query("DROP TABLE IF EXISTS wp_swp180214_contratti");
$wpdb->query("DROP TABLE IF EXISTS wp_swp180214_proprieta");
$wpdb->query("DROP TABLE IF EXISTS wp_swp180214_spese");
$wpdb->query("DROP TABLE IF EXISTS wp_swp180214_getrix_tree");
?>