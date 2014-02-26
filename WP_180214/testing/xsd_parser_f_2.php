<?php
define ( 'SCHEMA_URI_1', 'http://feed.getrix.it/xml/feed_1_0_0.xsd' );
define ( 'SCHEMA_URI_2', 'http://feed.getrix.it/xml/feed_2_0_0.xsd' );
define ( 'TABLE_REFERENCE', 'TABLE_REFERENCE' );
require_once 'xsd_parser.php';

// $parser = new XSDParser(SCHEMA_URI_1);
$parser = new XSDParser ( SCHEMA_URI_2 );
if ($parser->get_error () == XSDParser::ERROR_RET_VALUE)
	die ( 'XSDParser ERROR : NO SCHEMA to prepare :( !' );
	// ==================================================================================
$gettrix_node = $parser->get_xpath ()->evaluate ( '//xs:element[@name="Getrix"]' )->item ( 0 );
$getrix_attributes = $parser->get_attribute_explode ( $parser->get_next_node ( $gettrix_node ) );
// var_dump($getrix_attributes);
$getrix_version = $getrix_attributes [0] ['fixed'];
echo '<h3> GETRIX SCHEMA - versione : ' . $getrix_version . '</h3>';
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
	$table_node = $parser->get_xpath ()->evaluate ( '//xs:element[@name="' . $table . '"]' )->item ( 0 );
	$table_iterators [$table] = $parser->get_iterator ( $table_node );
}
// echo 'SKIPPED TABLES<br>';
// var_dump($skipped_tables);
// ==================================================================================
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
// GENERAZIONE STRUTTURE TABELLARI SQL
$tables_structure_sanitized = array ();
foreach ( $getrix_tables as $table ) {
	$tables_structure_sanitized [$table] = array ();
	foreach ( $tables_structure [$table] as $ts ) {
		if ($ts ['type'] == TABLE_REFERENCE)
			break;
		array_push ( $tables_structure_sanitized [$table], $ts );
	}
}
//var_dump($tables_structure_sanitized);
// RICERCA DEGLI ATTRIBUTI DEGLI ELEMENTI TABELLARI
foreach ( $getrix_tables as $table ) {
	$node = $parser->get_xpath ()->evaluate ( '//xs:element[@name="' . $table . '"]' )->item ( 0 );
	$node_attributes = $parser->get_attribute_explode ( $parser->get_next_node ( $node ) );
	if($node_attributes){
		//echo '<p>'.$table.'</p>';
		//var_dump($node_attributes);
		foreach ($node_attributes as $attribute){
			array_push($tables_structure_sanitized[$table],$attribute);
		}
	}
}
var_dump($tables_structure_sanitized);
// ==================================================================================
?>