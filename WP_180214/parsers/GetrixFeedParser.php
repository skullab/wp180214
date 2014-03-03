<?php
class GetrixFeedParser{
	
	const DESCRIZIONI 						= 'Descrizioni';
	const RESIDENZIALE						= 'Residenziale';
	const COMMERCIALE						= 'Commerciale';
	const ATTIVITA							= 'Attivita';
	const TERRENO							= 'Terreno';
	const VACANZE							= 'Vacanze';
	const IMMAGINI							= 'Immagini';
	const ALLEGATI							= 'Allegati';
	
	private $feed ;
	private $xpath ;
	private $version,$user ;
	
	public function __construct($feedfile){
		$this->feed = new DOMDocument();
		$this->feed->load($feedfile)or die('<b> GetrixFeedParser Error : </b> impossible to load file : '.$feedfile);
		$this->xpath = new DOMXPath($this->feed);
		$this->prepare();
	}
	/*======================================================*/
	public function prepare(){
		$getrix = $this->xpath->evaluate('//Getrix')->item(0);
		$getrix_attributes = $getrix->attributes ;
		foreach ($getrix_attributes as $a){
			switch ($a->nodeName){
				case 'Versione':
					$this->version = $a->nodeValue ;
					break;
				case 'User':
					$this->user = $a->nodeValue;
					break;
			}
		}
	}
	/*======================================================*/
	public function get_version(){
		return $this->version ;
	}
	/*======================================================*/
	public function get_user(){
		return $this->user ;
	}
	/*======================================================*/
	// RETURN MULTIDIMENSIONAL ARRAY WITH IMMOBILI
	public function get_immobili(){
		$nodes = $this->xpath->evaluate('//Immobile');
		$ret_array = array();
		foreach ($nodes as $node){
			$a = array();
			$id = $node->attributes->getNamedItem('IDImmobile')->nodeValue;
			$a['idimmobile'] = $id ;
			$immobile = $this->xpath->evaluate('*',$node);
			foreach ($immobile as $i){
				switch ($i->nodeName){
					case 'PubblicaCivico':
						//SKIP !
						break;
					case self::DESCRIZIONI:
						$a[self::DESCRIZIONI] = $i ;
						break;
					case self::RESIDENZIALE:
						$a[self::RESIDENZIALE] = $i ;
						break;
					case self::COMMERCIALE:
						$a[self::COMMERCIALE] = $i ;
						break;
					case self::ATTIVITA:
						$a[self::ATTIVITA] = $i ;
						break;
					case self::TERRENO;
						$a[self::TERRENO] = $i ;
						break;
					case self::VACANZE:
						$a[self::VACANZE] = $i ;
						break;
					case self::IMMAGINI:
						$a[self::IMMAGINI] = $i ;
						break;
					case self::ALLEGATI:
						$a[self::ALLEGATI] = $i ;
						break;
					default:
						$a[strtolower($i->nodeName)] = $i->nodeValue ;
				}
				
			}
			array_push($ret_array, $a);
		}
		
		return $ret_array;
	}
	/*======================================================*/
	public function retrieve_table_data($immobile,$table){
		if(!isset($immobile[$table]))return null;
		$ret_array = array();
		$nodes = $this->xpath->evaluate('*',$immobile[$table]);
		switch ($table){
			case self::DESCRIZIONI:
				foreach ($nodes as $node){
					$a = array();
					$lingua = $node->attributes->getNamedItem('Lingua')->nodeValue ;
					$a['lingua'] = $lingua ;
					if($node->hasChildNodes()){
						$nodes = $this->xpath->evaluate('*',$node);
						foreach ($nodes as $node){
							$value = $node->nodeValue ;
							if(is_numeric($value)){
								$value = intval($value);
							}
							$a[strtolower($node->nodeName)] = $value;
						}
					}
					array_push($ret_array,$a);
				}
				break;
			case self::RESIDENZIALE:
				$a = array();
				foreach ($nodes as $node){
					$value = $node->nodeValue ;
					if(is_numeric($value)){
						$value = intval($value);
					}
					$a[strtolower($node->nodeName)] = $value;
				}
				array_push($ret_array,$a);
				break;
			case self::COMMERCIALE:
				$a = array();
				foreach ($nodes as $node){
					$value = $node->nodeValue ;
					if(is_numeric($value)){
						$value = intval($value);
					}
					$a[strtolower($node->nodeName)] = $value;
				}
				array_push($ret_array,$a);
				break;
			case self::ATTIVITA:
				$a = array();
				foreach ($nodes as $node){
					$value = $node->nodeValue ;
					if(is_numeric($value)){
						$value = intval($value);
					}
					$a[strtolower($node->nodeName)] = $value;
				}
				array_push($ret_array,$a);
				break;
			case self::TERRENO;
				$a = array();
				foreach ($nodes as $node){
					$value = $node->nodeValue ;
					if(is_numeric($value)){
						$value = intval($value);
					}
					$a[strtolower($node->nodeName)] = $value;
				}
				array_push($ret_array,$a);
				break;
			case self::VACANZE:
				$a = array();
				foreach ($nodes as $node){
					$value = $node->nodeValue ;
					if(is_numeric($value)){
						$value = intval($value);
					}
					$a[strtolower($node->nodeName)] = $value;
				}
				array_push($ret_array,$a);
				break;
			case self::IMMAGINI:				
				foreach ($nodes as $node){
					$a = array();
					$id = $node->attributes->getNamedItem('IDImmagine')->nodeValue ;
					$a['idimmagine'] = intval($id) ;
					if($node->hasChildNodes()){
						$nodes = $this->xpath->evaluate('*',$node);
						foreach ($nodes as $node){
							$value = $node->nodeValue ;
							if(strtolower($node->nodeName) == 'datainserimento' || strtolower($node->nodeName) == 'datamodifica'){
								$value = str_replace('T', ' ', $value);
							}
							if(is_numeric($value)){
								$value = intval($value);
							}
							$a[strtolower($node->nodeName)] = $value;
						}
					}
					array_push($ret_array,$a);
				}
				break;
			case self::ALLEGATI:
				foreach ($nodes as $node){
					$a = array();
					$id = $node->attributes->getNamedItem('IDAllegato')->nodeValue ;
					$a['idallegato'] = intval($id) ;
					if($node->hasChildNodes()){
						$nodes = $this->xpath->evaluate('*',$node);
						foreach ($nodes as $node){
							$value = $node->nodeValue ;
							if(is_numeric($value)){
								$value = intval($value);
							}
							$a[strtolower($node->nodeName)] = $value ;
						}
					}
					array_push($ret_array,$a);
				}
				break;
		}

		return $ret_array;
	}
	/*======================================================*/
	//INFLATE DATA IN MULTIDIMENSIONAL ARRAY
	public function inflate_data_table($table_data,$data){
		$ret_array = array();
		foreach ($table_data as $table){			
			foreach ($data as $key => $value){				
				if(is_numeric($value)){
					$value = intval($value);
				}
				$table[$key] = $value ;
			}
			array_push($ret_array, $table);
		}
		return $ret_array ;
	}
	/*======================================================*/
	/*======================================================*/
	/*======================================================*/
	/*======================================================*/
}
?>