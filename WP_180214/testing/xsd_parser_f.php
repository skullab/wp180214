<?php
require_once 'xsd_parser.php';

$parser = new XSDParser('http://feed.getrix.it/xml/feed_2_0_0.xsd');
if($parser->get_error() == XSDParser::ERROR_RET_VALUE)die('XSDParser ERROR : NO SCHEMA to prepare :( !');

$immobile = $parser->get_xpath()->evaluate('//xs:element[@name="Immobile"]')->item(0);

$iterator_immobile = $parser->get_iterator($immobile);

$immobile_cols = array();
foreach ($iterator_immobile as $node){
	if($parser->is_element_node($node)){
		$name = $node->getAttribute('name');
		array_push($immobile_cols, $name);
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
	}
}
$iterators = array($iterator_descrizioni,
		$iterator_residenziale,
		$iterator_commerciale,
		$iterator_attivita,
		$iterator_terreno,
		$iterator_vacanze,
		$iterator_immagini,
		$iterator_allegati);

foreach ($iterators as $iterator){
	foreach ($iterator as $node){
		$count = 0 ;
		if($parser->is_element_node($node)){
			$col = $node->getAttribute('name');
			if(($key = array_search($col, $immobile_cols)) !== false) {
				unset($immobile_cols[$key]);
			}
		}		
	}
}

echo '<p>TABELLA IMMOBILI</p>';
foreach ($immobile_cols as $col){
	echo $col.'<br>';
}

echo '<p>TABELLA DESCRIZIONI</p>';
foreach ($iterator_descrizioni as $x){
	if($parser->is_element_node($x) && $parser->has_complextype($x)){
		echo '<h6>'.$x->getAttribute('name').'</h6>';
		$iter = $parser->get_iterator($x);
		foreach ($iter as $i){
			if($parser->is_element_node($i) && $parser->has_simpletype($i)){
				echo $i->getAttribute('name') ;
				$siter = $parser->get_iterator($i);
				foreach ($siter as $s){
					if($parser->is_restriction_node($s)){
						echo $s->getAttribute('base');
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
}
?>