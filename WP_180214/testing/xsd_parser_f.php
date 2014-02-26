<?php
require_once 'xsd_parser.php';

$parser = new XSDParser ( 'http://feed.getrix.it/xml/feed_1_0_0.xsd' );
if ($parser->get_error () == XSDParser::ERROR_RET_VALUE)
	die ( 'XSDParser ERROR : NO SCHEMA to prepare :( !' );
$getrix = $parser->get_xpath ()->evaluate ( '//xs:element[@name="Getrix"]' )->item ( 0 );
$getrix_complex = $parser->get_next_node ( $getrix );
$getrix_attrs = $parser->get_attribute_explode ( $getrix_complex );
$getrix_iterator = $parser->get_iterator($getrix);
// var_dump($getrix_attrs);
$tables = array();
foreach ($getrix_iterator as $node){
	if($parser->is_element_node($node)){
		if($parser->has_complextype($node)){
			$table_name = $node->getAttribute('name').'<br>';
			array_push($tables,$table_name);
		}
	}
}

$immobile = $parser->get_xpath ()->evaluate ( '//xs:element[@name="Immobile"]' )->item ( 0 );
$immobile_complex = $parser->get_next_node ( $immobile );
$immobile_attrs = $parser->get_attribute_explode ( $immobile_complex );
//var_dump($immobile_attrs);

$iterator_immobile = $parser->get_iterator ( $immobile );

$iterators = array();

$immobile_reference = array ();
foreach ( $iterator_immobile as $node ) {
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
			$node_array ['type'] = 'TABLE_REFERENCE';
			$complex = $parser->get_next_node ( $node );
			$attributes = $parser->get_attribute_explode ( $complex );
			
			$sequence = $parser->get_xpath ()->evaluate ( '*/xs:sequence', $node );
			$node_array ['table'] = $sequence;
			$node_array ['table_attribute'] = $attributes;
		} else {
			$node_array ['type'] = $node->getAttribute ( 'type' );
		}
		$node_array ['dom_element'] = $node;
		
		foreach ($tables as $table_name){
			if($name == $table_name){
				$iterators[$name]= $parser->get_iterator($node);
			}
		}
		/*switch ($name) {
			case 'Descrizioni' :
				$iterator_descrizioni = $parser->get_iterator ( $node );				
				break;
			case 'Descrizione' :
				//$iterator_descrizioni = $parser->get_iterator ( $node );
				break;
			case 'Residenziale' :
				$iterator_residenziale = $parser->get_iterator ( $node );				
				break;
			case 'Commerciale' :
				$iterator_commerciale = $parser->get_iterator ( $node );
				break;
			case 'Attivita' :
				$iterator_attivita = $parser->get_iterator ( $node );
				break;
			case 'Terreno' :
				$iterator_terreno = $parser->get_iterator ( $node );
				break;
			case 'Vacanze' :
				$iterator_vacanze = $parser->get_iterator ( $node );
				break;
			case 'Immagini' :
				$iterator_immagini = $parser->get_iterator ( $node );
				break;
			case 'Allegati' :
				$iterator_allegati = $parser->get_iterator ( $node );
				break;
		}*/
		
		array_push ( $immobile_reference, $node_array );
	}
}
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
				$node_array ['type'] = 'TABLE_REFERENCE';
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

/*$iterators = array (
		'descrizioni'=>$iterator_descrizioni,
		'residenziale'=>$iterator_residenziale,
		'commerciale'=>$iterator_commerciale,
		'attivita'=>$iterator_attivita,
		'terreno'=>$iterator_terreno,
		'vacanze'=>$iterator_vacanze,
		'immagini'=>$iterator_immagini,
		'allegati'=>$iterator_allegati 
);*/

// var_dump($immobile_reference);

// TABELLA IMMOBILI
$immobili_table = array ();
array_push ( $immobili_table, array (
		'name' => 'id',
		'type' => 'xs:int' 
) );
array_push ( $immobili_table, $immobile_attrs [0] );
foreach ( $immobile_reference as $reference ) {
	if ($reference ['type'] == 'TABLE_REFERENCE')
		break;
	array_push ( $immobili_table, $reference );
}

$descrizioni_array = get_array_from_iterator($iterators['descrizioni']);
$residenziale_array = get_array_from_iterator($iterators['residenziale']);
$commerciale_array = get_array_from_iterator($iterators['commerciale']);
$attivita_array = get_array_from_iterator($iterators['attivita']);
$terreno_array = get_array_from_iterator($iterators['terreno']);
$vacanze_array = get_array_from_iterator($iterators['vacanze']);
$immagini_array = get_array_from_iterator($iterators['immagini']);
$allegati_array = get_array_from_iterator($iterators['allegati']);

/***************************************************************************/


function refact_header($header){
	global $parser ;
	$attributes = array('name'=>'','type'=>'');
	if($header['type'] == 'TABLE_REFERENCE'){
		$header['type'] == 'xs:int';
		if(!empty($header['table_attribute'])){
			$attributes = array();
			foreach ($header['table_attribute'] as $attribute){
				$a = array();
				$a['name'] = $attribute['name'];
				if($attribute['has_simpletype']){
					$simpletype = $attribute['simpletype'] ;
					$restriction = $parser->get_xpath()->evaluate('*',$simpletype)->item(0);
					$a['type'] = $restriction->getAttribute('base');
				}else{
					$a['type'] = $attribute['type'];
				}
				array_push($attributes,$a);
			}
			/*$attribute = array('name'=>$header['table_attribute'][0]['name']);
			if($header['table_attribute'][0]['has_simpletype']){
				$simpletype = $header['table_attribute'][0]['simpletype'] ;
				$restriction = $parser->get_xpath()->evaluate('*',$simpletype)->item(0);
				$attribute['type'] = $restriction->getAttribute('base');
			}else{
				$attribute['type'] = $header['table_attribute'][0]['type'];
			}*/
		}
	}
	return $attributes ;
}

/*$descrizioni_header = $descrizioni_array[0];
$immagini_header = $immagini_array[0];
$allegati_header = $allegati_array[0];*/

$descrizioni_header = refact_header($descrizioni_array[0]);
unset($descrizioni_array[0]);

$immagini_header = refact_header($immagini_array[0]);
unset($immagini_array[0]);

$allegati_header = refact_header($allegati_array[0]);
unset($allegati_array[0]);

/*if($descrizioni_header['type'] == 'TABLE_REFERENCE'){
	$descrizioni_header['type'] == 'xs:int';
	if(!empty($descrizioni_header['table_attribute'])){
		$descrizioni_attribute = array('name'=>$descrizioni_header['table_attribute'][0]['name']);
		if($descrizioni_header['table_attribute'][0]['has_simpletype']){
			$simpletype = $descrizioni_header['table_attribute'][0]['simpletype'] ;
			$restriction = $parser->get_xpath()->evaluate('*',$simpletype)->item(0);
			$descrizioni_attribute['type'] = $restriction->getAttribute('base');
		}
	}
}*/

//unset($descrizioni_array[0]);
/*$descrizioni_table = array(
		array('name'=>'id','type'=>'xs:int'),
		array('name'=>$immobile_attrs[0]['name'],'type'=>$immobile_attrs[0]['type']),
		$descrizioni_header);
foreach ($descrizioni_array as $des_array){
	array_push($descrizioni_table, $des_array);
}*/

/***************************************************************************/
function get_table($arr,$h = null){
	global $immobile_attrs ;
	$table = array(
			array('name'=>'id','type'=>'xs:int'),
			array('name'=>$immobile_attrs[0]['name'],'type'=>$immobile_attrs[0]['type']));
	if(!is_null($h)){
		if(@is_array($h[0])){
			foreach ($h as $hh)array_push($table, $hh);
		}else array_push($table, $h);
	}
	foreach ($arr as $a){
		array_push($table,$a);
	}
	return $table;
}
/***************************************************************************/
$immobili_sql = $parser->mysql_generate_create_table ( 'immobili', $immobili_table, true, true, true, 'InnoDB' );
$descrizioni_sql = $parser->mysql_generate_create_table('descrizioni', get_table($descrizioni_array,$descrizioni_header), true, true, true, 'InnoDB' );
$residenziale_sql = $parser->mysql_generate_create_table('residenziale', get_table($residenziale_array), true, true, true, 'InnoDB' );
$commerciale_sql = $parser->mysql_generate_create_table('commerciale', get_table($commerciale_array), true, true, true, 'InnoDB' );
$attivita_sql = $parser->mysql_generate_create_table('attivita', get_table($attivita_array), true, true, true, 'InnoDB' );
$terreno_sql = $parser->mysql_generate_create_table('terreno', get_table($terreno_array), true, true, true, 'InnoDB' );
$vacanze_sql = $parser->mysql_generate_create_table('vacanze', get_table($vacanze_array), true, true, true, 'InnoDB' );
$immagini_sql = $parser->mysql_generate_create_table('immagini', get_table($immagini_array,$immagini_header), true, true, true, 'InnoDB' );
$allegati_sql = $parser->mysql_generate_create_table('allegati', get_table($allegati_array,$allegati_header), true, true, true, 'InnoDB' );

$sql_array = array($immobili_sql,$descrizioni_sql,$residenziale_sql,$commerciale_sql,$attivita_sql,$terreno_sql,$vacanze_sql,$immagini_sql,$allegati_sql);
foreach ($sql_array as $sql){
	//echo $sql.'<br><br>';
}
// echo $immobili_sql.'<br>' ;

?>