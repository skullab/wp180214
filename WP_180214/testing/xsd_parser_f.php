<?php
require_once 'xsd_parser.php';

$parser = new XSDParser('http://feed.getrix.it/xml/feed_2_0_0.xsd');
if($parser->get_error() == XSDParser::ERROR_RET_VALUE)die('XSDParser ERROR : NO SCHEMA to prepare :( !');
$getrix = $parser->get_xpath()->evaluate('//xs:element[@name="Getrix"]')->item(0);
$getrix_complex = $parser->get_next_node($getrix);
$getrix_attrs = $parser->get_attribute_explode($getrix_complex);
//var_dump($getrix_attrs);

$immobile = $parser->get_xpath()->evaluate('//xs:element[@name="Immobile"]')->item(0);
$immobile_complex = $parser->get_next_node($immobile);
$immobile_attrs = $parser->get_attribute_explode($immobile_complex);
//var_dump($immobile_attrs);

$iterator_immobile = $parser->get_iterator($immobile);

$immobile_reference = array();
foreach ($iterator_immobile as $node){
	if($parser->is_element_node($node)){
		$node_array = array(); 
		$name = $node->getAttribute('name');
		$node_array['name'] = $name ;
		
		//echo $name.' ';
		
		if($parser->has_simpletype($node)){
			$restrictions = $parser->get_xpath()->evaluate('*/xs:restriction',$node);
			$simple = $parser->get_next_node($node);
			foreach ($restrictions as $r){
				$node_array['type'] = $r->getAttribute('base');
				//echo $r->getAttribute('base').'<br>';
			}
			$attributes = $parser->get_attribute_explode($simple);
			if(!empty($attributes)){
				$node_array['attributes'] = $attributes ;
				//var_dump($attributes);
			}
		}else {
			$node_array['type'] = $node->getAttribute('type');
			//echo $node->getAttribute('type').'<br>';
		}
		
		switch ($name){
			case 'Descrizioni':
				$iterator_descrizioni = $parser->get_iterator($node);
				break;
			case 'Residenziale':
				$iterator_residenziale = $parser->get_iterator($node);
				break;
			case 'Commerciale':
				$iterator_commerciale = $parser->get_iterator($node);
				break;
			case 'Attivita':
				$iterator_attivita = $parser->get_iterator($node);
				break;
			case 'Terreno':
				$iterator_terreno = $parser->get_iterator($node);
				break;
			case 'Vacanze':
				$iterator_vacanze = $parser->get_iterator($node);
				break;
			case 'Immagini':
				$iterator_immagini = $parser->get_iterator($node);
				break;
			case 'Allegati':
					$iterator_allegati = $parser->get_iterator($node);
					break;				
		}
		array_push($immobile_reference, $node_array);		
	}
}

$iterators = array(
		$iterator_descrizioni,
		$iterator_residenziale,
		$iterator_commerciale,
		$iterator_attivita,
		$iterator_terreno,
		$iterator_vacanze,
		$iterator_immagini,
		$iterator_allegati);

foreach ($immobile_reference as $reference){
	switch ($reference['name']){
		case 'Descrizioni':
			echo 'creazione tabella descrizioni<br>';
			break;
		case 'Residenziale':
			echo 'creazione tabella residenziale<br>';
			break;
		case 'Commerciale':
			echo 'creazione tabella commerciale<br>';
			break;
		case 'Attivita':
			echo 'creazione tabella attivita<br>';
			break;
		case 'Terreno':
			echo 'creazione tabella terreno<br>';
			break;
		case 'Vacanze':
			echo 'creazione tabella vacanze<br>';
			break;
		case 'Immagini':
			echo 'creazione tabella immagini<br>';
			break;
		case 'Allegati':
			echo 'creazione tabella allegati<br>';
			break;
	}
};

/*foreach ($iterators as $iterator){
	echo '<br>';
	foreach ($iterator as $node){
		if($parser->is_element_node($node)){
			$col = $node->getAttribute('name');
			echo $col.' ' ;
			foreach ($immobile_reference as $reference){
				if(($key = array_search($col, $reference)) !== false) {
					echo ' TROVATO '.$key; 
				}
				//echo $reference['type'].'<br>';
			}
			echo '<br>';
		}		
	}
}*/

/*echo '<p>TABELLA IMMOBILI</p>';
foreach ($immobile_reference as $reference){
	var_dump($reference);
}
echo '<p>TABELLA DESCRIZIONI</p>';
foreach ($iterator_descrizioni as $x){
	if($parser->is_element_node($x) && $parser->has_complextype($x)){
		echo '<h6>'.$x->getAttribute('name').'</h6>';
		$iter = $parser->get_iterator($x);
		foreach ($iter as $i){
			if($parser->is_element_node($i) && $parser->has_simpletype($i)){
				echo $i->getAttribute('name').'&nbsp;' ;
				$siter = $parser->get_iterator($i);
				foreach ($siter as $s){
					if($parser->is_restriction_node($s)){
						echo $s->getAttribute('base').'<br>';
					}
				}
			}
		}
	}
}

echo '<p>TABELLA RESIDENZIALE</p>';
foreach ($iterator_residenziale as $x){
	if($parser->is_element_node($x)){
		echo $x->getAttribute('name').'<br>';
	}
}*/
?>