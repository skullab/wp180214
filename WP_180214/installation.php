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

function swp180214_install($update = false){
	//swp180214_debug('inizio installazione');
	if($update || get_option(SWP180214_OPT_FIRST_INSTALL)){	
		swp180214_show_warning($update);
	}
}

function swp180214_update(){
	//swp180214_debug('verifica aggiornamenti');
	$current_db_version = get_option(SWP180214_OPT_DB_VERSION);
	$current_plugin_version = get_option(SWP180214_OPT_PLUGIN_VERSION);
	
	if($current_plugin_version != SWP180214_VERSION){
		update_option(SWP180214_OPT_PLUGIN_VERSION,SWP180214_VERSION);
	}
	
	//TODO ONLINE UPDATE RESPONSE
	if($current_db_version != SWP180214_DB_VERSION){
		update_option(SWP180214_OPT_UPDATE_AVAILABLE,true);
		swp180214_install(true);
		return;
	}
	
	update_option(SWP180214_OPT_UPDATE_AVAILABLE,false);
}

/*****************************************************************************************************
										INSTALLATION SECTION
******************************************************************************************************/
function swp180214_table_prefix(){
	global $wpdb ;
	return $wpdb->prefix.SWP180214_PREFIX ;
}

function swp180214_log_on_file($line){
	$filepath = plugins_url('log/log_'.date("Y_m_d").'.txt');
	file_put_contents($filepath, $line.PHP_EOL, FILE_APPEND);
}

function swp180214_install_db(){
	
	require_once 'parsers/xsd_parser.php';
	define ( 'TABLE_REFERENCE', 'TABLE_REFERENCE' );
	define ( 'GETRIX_TREE', 'Getrix_tree' );
	define ('INNODB','InnoDB');
	global $parser;
	
	$parser = new XSDParser(get_option(SWP180214_OPT_GETRIX_SCHEMA_URI));
	if ($parser->get_error() === XSDParser::ERROR_RET_VALUE){
		echo ( '<li><b>XSDParser ERROR : </b>Errore  durante la lettura dello schema: '.get_option(SWP180214_OPT_GETRIX_SCHEMA_URI).', 
				<a href="admin.php?page='.SWP180214_SLUG_SETTINGS.'">aggiornare</a> la pagina per ripetere l\'installazione' );
		update_option(SWP180214_OPT_INSTALL_PROCESS,0);
		return;
	}
	
	$gettrix_node = $parser->get_xpath ()->evaluate ( '//xs:element[@name="Getrix"]' )->item ( 0 );
	$getrix_attributes = $parser->get_attribute_explode ( $parser->get_next_node ( $gettrix_node ) );
	// var_dump($getrix_attributes);
	$getrix_version = $getrix_attributes [0] ['fixed'];
	echo '<li><b> GETRIX SCHEMA - versione : ' . $getrix_version . '</b>';
	//echo '<li>Generazione struttuta tabelle...';
	// ==================================================================================
	// RELAZIONE TABELLARE SPORCA
	$getrix_tables = array ();
	$skipped_tables = array ();
	$getrix_iterator = $parser->get_iterator ( $gettrix_node );
	foreach ( $getrix_iterator as $node ) {
		if ($parser->is_element_node ( $node ) && $parser->has_complextype ( $node )) {
			$name = $node->getAttribute ( 'name' );
			array_push ( $getrix_tables, $name );
			// echo 'GENERARE TABELLA : '.$name.'<br>';
		}
	}
	$getrix_tables_count = count ( $getrix_tables );
	for($i = 0; $i < $getrix_tables_count; $i ++) {
		$current_name_sanitized = substr ( $getrix_tables [$i], 0, strlen ( $getrix_tables [$i] ) - 1 );
		@$next_name_sanitized = substr ( $getrix_tables [$i + 1], 0, strlen ( $getrix_tables [$i + 1] ) - 1 );
		// echo $current_name_sanitized.' '.$next_name_sanitized.'<br>';
		if ($current_name_sanitized == $next_name_sanitized) {
			// echo 'doppio<br>';
			array_push ( $skipped_tables, $getrix_tables [$i] );
			unset ( $getrix_tables [$i] );
		}
	}
	// RELAZIONE TABELLARE SANA
	// GENERAZIONE ITERATORI
	$table_iterators = array ();
	
	foreach ( $getrix_tables as $table ) {
		// echo 'GENERARE TABELLA : '.$table.'<br>';
		// $table_node = $parser->get_xpath ()->evaluate ( '//xs:element[@name="' . $table . '"]' )->item(0);
		$table_node = $parser->get_xpath ()->evaluate ( '
			//xs:element[@name="' . $table . '"]' );
		// echo $table.' trovati n : '.$table_node->length.'<br>';
		if ($table_node->length > 1) {
			// var_dump($table_node);
			$table_node = $parser->get_xpath ()->evaluate ( '
			//xs:element[@name="' . $getrix_tables [0] . '"]/xs:complexType/xs:sequence/xs:element[@name="' . $table . '"]' );
			// echo 'sostituito con <br>';
			// var_dump($table_node);
		}
		$table_node = $table_node->item ( 0 );
		$table_iterators [$table] = $parser->get_iterator ( $table_node );
	}
	// echo 'SKIPPED TABLES<br>';
	// var_dump($skipped_tables);
	// ==================================================================================
	// GENERAZIONE RELAZIONI TABELLARI SPORCHE
	function get_array_from_iterator($iterator) {
		global $parser;
		$ret_array = array ();
		foreach ( $iterator as $node ) {
			if ($parser->is_element_node ( $node )) {
				$node_array = array ();
				$name = $node->getAttribute ( 'name' );
				$node_array ['name'] = $name;
					
				if ($parser->has_simpletype ( $node )) {
					$restrictions = $parser->get_xpath ()->evaluate ( '*/xs:restriction', $node );
					$simple = $parser->get_next_node ( $node );
					foreach ( $restrictions as $r ) {
						$node_array ['type'] = $r->getAttribute ( 'base' );
					}
					$attributes = $parser->get_attribute_explode ( $simple );
					if (! empty ( $attributes )) {
						$node_array ['attributes'] = $attributes;
					}
				} elseif ($parser->has_complextype ( $node )) {
					$node_array ['type'] = TABLE_REFERENCE;
					$complex = $parser->get_next_node ( $node );
					$attributes = $parser->get_attribute_explode ( $complex );
	
					$sequence = $parser->get_xpath ()->evaluate ( '*/xs:sequence', $node );
					$node_array ['table'] = $sequence;
					$node_array ['table_attribute'] = $attributes;
				} else {
					$node_array ['type'] = $node->getAttribute ( 'type' );
				}
				$node_array ['dom_element'] = $node;
				array_push ( $ret_array, $node_array );
			}
		}
		return $ret_array;
	}
	$tables_structure = array ();
	foreach ( $table_iterators as $key => $iterator ) {
		$ai = get_array_from_iterator ( $iterator );
		$ai_count = count ( $ai );
		for($i = 0; $i < $ai_count; $i ++) {
			$ai_current = $ai [$i];
			foreach ( $skipped_tables as $table ) {
				$keys = array_keys ( $ai_current, $table );
				if (! empty ( $keys )) {
					// echo $key . ' trovato indice ' . $i . '<br>';
					unset ( $ai [$i] );
				}
			}
		}
		$tables_structure [$key] = $ai;
	}
	// var_dump ( $tables_structure ['Immobile'] );
	// ==================================================================================
	// GENERAZIONE RELAZIONI TABELLARI SANE
	$tables_structure_sanitized = array ();
	foreach ( $getrix_tables as $table ) {
		$tables_structure_sanitized [$table] = array ();
		foreach ( $tables_structure [$table] as $ts ) {
			if ($ts ['type'] == TABLE_REFERENCE)
				break;
			array_push ( $tables_structure_sanitized [$table], $ts );
		}
	}
	// var_dump($tables_structure_sanitized);
	// RICERCA DEGLI ATTRIBUTI DEGLI ELEMENTI TABELLARI
	foreach ( $getrix_tables as $table ) {
		$node = $parser->get_xpath ()->evaluate ( '//xs:element[@name="' . $table . '"]' );
		if ($node->length > 1) {
			$node = $parser->get_xpath ()->evaluate ( '
			//xs:element[@name="' . $getrix_tables [0] . '"]/xs:complexType/xs:sequence/xs:element[@name="' . $table . '"]' );
		}
		$node = $node->item ( 0 );
		$node_attributes = $parser->get_attribute_explode ( $parser->get_next_node ( $node ) );
		if ($node_attributes) {
			// echo '<p>'.$table.'</p>';
			// var_dump($node_attributes);
			foreach ( $node_attributes as $attribute ) {
				if ($table == $getrix_tables [0] && $attribute ['name'] == $node_attributes [0] ['name']) {
					$root_table_reference = $attribute ['name'];
				}
				array_push ( $tables_structure_sanitized [$table], $attribute );
			}
		}
	}
	// var_dump($tables_structure_sanitized);
	//echo '<li>Struttura tabelle creata.';
	//echo '<li>Generazione codice sql...';
	// ==================================================================================
	// GENERAZIONE ALBERO TABELLE
	
	$prefix = swp180214_table_prefix();
	
	
	$sql_tables_structure = array ();
	$getrix_tables_tree_structure = array (
			array (
					'name' => '_id',
					'type' => XSDParser::DATA_INT
			),
			array (
					'name' => 'tables',
					'type' => ''
			)
	);
	$sql_tables_structure [GETRIX_TREE] = $parser->mysql_generate_create_table ( $prefix.GETRIX_TREE, $getrix_tables_tree_structure, false, true, true, null, false, null, null,INNODB);
	
	// GENERAZIONE STRUTTURE TABELLARI SQL
	foreach ( $getrix_tables as $table ) {
		// echo count($tables_structure_sanitized[$table]).'<br>';
		
		$id = array (
				'name' => '_id',
				'type' => XSDParser::DATA_INT
		);
		$foreign_key = strtolower ( $root_table_reference );
		$structure = array (
				$id
		);
		foreach ( $tables_structure_sanitized [$table] as $col ) {
			array_push ( $structure, $col );
		}
		if ($table != $getrix_tables [0]) {
			$sql_tables_structure [$table] = $parser->mysql_generate_create_table ( $prefix.$table, $structure, false, true, true, $foreign_key, true, $foreign_key, $prefix.$getrix_tables [0],INNODB);
		} else {
			$sql_tables_structure [$table] = $parser->mysql_generate_create_table ( $prefix.$table, $structure, false, true, true, $foreign_key, false, null, null,INNODB);
		}
	}
	// ==================================================================================
	//echo '<li>Codice sql generato. Inizio creazione DATABASE';
	
	global $wpdb;
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
	foreach ( $sql_tables_structure as $sql ) {
		swp180214_log_on_file($sql);
		dbDelta( $sql );
	}
	// ==================================================================================
	// CREAZIONE TABELLE DI RIFERIMENTO
	require_once 'query.php';
	dbDelta($sql_create_table_categorie);
	dbDelta($sql_create_table_contratti);
	dbDelta($sql_create_table_proprieta);
	dbDelta($sql_create_table_spese);
	// ==================================================================================
	// INSERIMENTO DATI NELLE TABELLE DI RIFERIMENTO
	$categorie = explode(';',$sql_insert_categorie);
	foreach ($categorie as $sql){
		$wpdb->query($sql);
	}
	$contratti = explode(';',$sql_insert_contratti);
	foreach ($contratti as $sql){
		$wpdb->query($sql);
	}
	$proprieta = explode(';',$sql_insert_proprieta);
	foreach ($proprieta as $sql){
		$wpdb->query($sql);
	}
	$spese = explode(';',$sql_insert_spese);
	foreach ($spese as $sql){
		$wpdb->query($sql);
	}
	foreach ($getrix_tables as $table){
		$wpdb->query($wpdb->prepare($sql_insert_getrix_tree,strtolower(swp180214_table_prefix().$table)));
	}
	// ==================================================================================
	echo '<li>CREAZIONE DATABASE COMPLETATA !';
	// ==================================================================================
	if(get_option(SWP180214_OPT_FIRST_INSTALL)){
		update_option(SWP180214_OPT_INSTALL_PROCESS,2);
	}
	return;
}
?>