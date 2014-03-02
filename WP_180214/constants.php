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

/*******************************************************************************************************************/
/** The internal plugin prefix */
define('SWP180214_PREFIX','swp180214_');
/** The plugin version */
define('SWP180214_VERSION','0.0.1');
/** The display plugin name */
define('SWP180214_DISPLAY_NAME','WP180214');
/** The slug plugin name */
define('SWP180214_SLUG_NAME','wp180214');
/** THE CURRENT DB VERSION */
define('SWP180214_DB_VERSION','1.0');
/*******************************************************************************************************************/
define('SWP180214_DEFAULT_GETRIX_SCHEMA_URI','http://feed.getrix.it/xml/feed_2_0_0.xsd');
define('SWP180214_DEFAULT_GETRIX_SCHEMA_VERSION','2.0.0');
/*******************************************************************************************************************/
/** The db version option */
define('SWP180214_OPT_DB_VERSION','swp180214_opt_db_version');
define('SWP180214_OPT_FIRST_INSTALL','swp180214_opt_first_install');
/** The Getrix Schema URI Location */
/*******************************************************************************************************************/
define('SWP180214_OPT_GROUP_INSTALL','swp180214_opt_group_install');
define('SWP180214_OPT_GETRIX_SCHEMA_URI','swp180214_opt_getrix_schema_uri');
define('SWP180214_OPT_GETRIX_SCHEMA_VERSION','swp180214_opt_getrix_schema_version');
define('SWP180214_OPT_GETRIX_USER','swp180214_opt_getrix_user');
/*******************************************************************************************************************/
define('SWP180214_SLUG_SETTINGS',SWP180214_SLUG_NAME.'-settings');
/*******************************************************************************************************************/
define('SWP180214_PAGE_NAME_SETTINGS',SWP180214_DISPLAY_NAME.' - Impostazioni');
define('SWP180214_PAGE_NAME_SETTINGS_DESCRIPTION','Impostazioni generali per il corretto funzionamento del plugin.');

define('SWP180214_PAGE_NAME_INSTALL',SWP180214_DISPLAY_NAME.' - Installazione');
define('SWP180214_PAGE_NAME_INSTALL_DESCRIPTION','Procedura guidata per la prima installazione del plugin.');
?>