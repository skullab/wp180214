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
/*****************************************************************************************************/

register_activation_hook( __FILE__, 'swp180214_activation');
function swp180214_activation(){
	swp180214_debug('attivazione plugin');	
	swp180214_update();
}
add_action( 'plugins_loaded', 'swp180214_update' );
?>