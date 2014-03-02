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

function swp180214_install(){
	//swp180214_debug('inizio installazione');
	require_once 'utils.php' ;
	swp180214_show_warning();
}

function swp180214_update(){
	//swp180214_debug('verifica aggiornamenti');
	$current_db_version = get_option(SWP180214_OPT_DB_VERSION);
	if($current_db_version != SWP180214_DB_VERSION){
		//swp180214_debug('nuova versione del db trovata');
		swp180214_install();
	}
}

/*****************************************************************************************************
										INSTALLATION SECTION
******************************************************************************************************/

function swp180214_install_db(){
	//swp180214_debug('creazione tabelle sql');
}
?>